<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Test;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestCreationService
{
    public function createTest($testId, $userId)
    {
        // Perform the transaction
        $result = DB::transaction(function () use ($testId, $userId) {

            // 1. Validate test limits
            if ($this->isTestOverLimits()) {
                return false; // If the test is over limits, return false
            }

            // 2. Get test data
            $test = Test::findOrFail($testId);

            // 3. Create test user record
            $testUser = $this->createTestUserRecord($testId, $userId);
            Log::info('Test user record created', ['testUser' => $testUser]);

            // 4. Get the first test of this type (if exists)
            $firstTest = DB::select('
            SELECT *
            FROM tests_users
            WHERE test_id = ?
            AND user_id = ?
            ORDER BY created_at
            LIMIT 1
        ', [$testId, $userId]);

            // Assign firstTest or null if no result found
            $firstTest = !empty($firstTest) ? $firstTest[0] : null;

            // 5. Select and assign questions
            if ($test->random_questions_select || !$firstTest) {

                $this->assignRandomQuestions($test, $testUser, $firstTest);
                // dd("sada");
            } else {

                $this->copyQuestionsFromFirstTest($test, $testUser, $firstTest);
            }

            // 6. Update test user status
            DB::update(
                'UPDATE tests_users
                SET status = ?, updated_at = ?
                WHERE test_id = ? AND user_id = ?',
                [1, now(), $testId, $userId]
            );



            // Return the test user object from the transaction closure
            return $testUser;
        });

        // 7. Return the result after the transaction is completed
        return $result;
    }


    protected function createTestUserRecord($testId, $userId)
    {
        try {
            $insertedId = DB::table('tests_users')->insertGetId([
                'test_id' => $testId,
                'user_id' => $userId,
                'status' => 0,
                'user_comment' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $testUser = DB::table('tests_users')->find($insertedId);
            //  dd($testUser);

            // No commit needed — outer transaction handles it
            return $testUser;
        } catch (\Throwable $th) {
            dd($th->getMessage()); // This will trigger rollback automatically in outer transaction
        }
    }

    protected function assignRandomQuestions($test, $testUser, $firstTest)
    {
        $questionsData = [];
        $selectedQuestionIds = [];
        $subjectSets = DB::table('test_topic_sets')
            ->where('test_id', $test->id)
            ->orderBy('type')
            ->orderBy('difficulty')
            ->orderByDesc('answers')
            ->get();
        //dd($subjectSets);
        foreach ($subjectSets as $subjectSet) {
            // Get questions matching criteria
            $subjectIds = DB::table('test_topics')
                ->where('test_topic_set_id', $subjectSet->id)
                ->pluck('topic_id')
                ->toArray();
            $questions = Question::whereIn('topic_id', $subjectIds)
                ->where('difficulty', $subjectSet->difficulty)
                ->where('enabled', true)
                ->whereNotIn('id', $selectedQuestionIds);


            // Apply type filter if specified
            if ($subjectSet->type > 0) {
                $questions->where('type', $subjectSet->type);
            }

            // Apply question type specific filters
            switch ($subjectSet->type) {
                case 1: // MCSA
                    $questions = $this->filterMCSAQuestions($questions, $subjectSet);
                    break;
                case 2: // MCMA
                    $questions = $this->filterMCMAQuestions($questions, $subjectSet);
                    break;
                case 4: // ORDER
                    $questions = $this->filterOrderQuestions($questions);
                    break;
            }

            // Apply ordering
            if ($test->random_questions_select || $test->random_questions_order) {
                $questions->inRandomOrder();
            } else {
                $questions = $this->applyQuestionOrdering($questions, $test->questions_order_mode);
            }

            // Limit quantity
            $questions = $questions->limit($subjectSet->quantity)->get();

            // Store questions data
            foreach ($questions as $question) {
                $questionsData[] = [
                    'id' => $question->id,
                    'type' => $question->type,
                    'answers' => $subjectSet->answers,
                    'score' => $test->score_unanswered * $question->difficulty,
                    'position' => $question->position
                ];

                $selectedQuestionIds[] = $question->id;
            }
        }


        // Shuffle if random ordering
        if ($test->random_questions_select || $test->random_questions_order) {

            shuffle($questionsData);
        } else {
            usort($questionsData, function ($a, $b) {
                return $a['position'] <=> $b['position'];
            });
        }

        // Create test logs and answers
        $order = 0;
        foreach ($questionsData as $questionData) {

            $order++;
            $this->createTestLogWithAnswers($testUser, $questionData, $order, $firstTest, $test);
            //dd("Inside assignRandomQuestions");
        }
    }

    protected function filterMCSAQuestions($query, $subjectSet)
    {
        // Questions with at least one right answer
        $query->whereHas('answers', function ($q) use ($subjectSet) {
            $q->where('enabled', true)
                ->where('is_right', true);

            if (!$subjectSet->random_answers_order && $subjectSet->answers_order_mode == 0) {
                $q->where('position', '>', 0);
            }
        });

        // Questions with required number of wrong answers
        if ($subjectSet->answers > 0) {
            $query->whereHas('answers', function ($q) use ($subjectSet) {
                $q->where('enabled', true)
                    ->where('is_right', false);

                if (!$subjectSet->random_answers_order && $subjectSet->answers_order_mode == 0) {
                    $q->where('position', '>', 0);
                }
            }, '>=', $subjectSet->answers - 1);
        }

        return $query;
    }

    protected function filterMCMAQuestions($query, $subjectSet)
    {
        if ($subjectSet->answers > 0) {
            $query->whereHas('answers', function ($q) use ($subjectSet) {
                $q->where('enabled', true);

                if (!$subjectSet->random_answers_order && $subjectSet->answers_order_mode == 0) {
                    $q->where('position', '>', 0);
                }
            }, '>=', $subjectSet->answers);
        }

        return $query;
    }

    protected function filterOrderQuestions($query)
    {
        return $query->whereHas('answers', function ($q) {
            $q->where('enabled', true)
                ->where('position', '>', 0);
        }, '>', 1);
    }

    protected function applyQuestionOrdering($query, $orderMode)
    {
        switch ($orderMode) {
            case 0: // position
                return $query->where('position', '>', 0)->orderBy('position');
            case 1: // alphabetic
                return $query->orderBy('description');
            case 2: // ID
                return $query->orderBy('id');
            case 3: // type
                return $query->orderBy('type');
            case 4: // subject ID
                return $query->orderBy('topic_id');
            default:
                return $query;
        }
    }

    protected function copyQuestionsFromFirstTest($test, $testUser, $firstTest)
    {
        $order = 0;
        $testLogs = DB::table('test_logs')
            ->join('questions', 'test_logs.question_id', '=', 'questions.id') // Join with questions table
            ->where('test_logs.tests_users_id', $firstTest->id) // Filter by the firstTest ID
            ->when($test->random_questions_order, function ($query) {
                return $query->inRandomOrder(); // Random order if condition is true
            }, function ($query) {
                return $query->orderBy('test_logs.order'); // Order by 'order' if condition is false
            })
            ->select('test_logs.*', 'questions.*') // Select necessary columns
            ->get();
        // dd($testLogs);
        foreach ($testLogs as $testLog) {
            $order++;

            $newTestLog = $this->createTestLogWithAnswers(
                $testUser,
                [
                    'id' => $testLog->question_id,
                    'type' => $testLog->question->type,
                    'answers' => $testLog->num_answers,
                    'score' => $test->score_unanswered * $testLog->question->difficulty
                ],
                $order,
                $firstTest,
                $test
            );
        }
    }

    protected function createTestLogWithAnswers($testUser, $questionData, $order, $firstTest, $test)
    {

        $testLog = DB::insert('
                        INSERT INTO test_logs (tests_users_id, question_id, score, `order`, num_answers, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, NOW(), NOW())
                    ', [
            $testUser->id,
            $questionData['id'],
            $questionData['score'],
            $order,
            $questionData['answers']
        ]);
        // dd($testLog);
        // Get the last inserted test log
        $testLog = DB::select('
            SELECT *
            FROM test_logs
            WHERE tests_users_id = ?
            AND question_id = ?
            AND `order` = ?
            ORDER BY created_at DESC
            LIMIT 1
        ', [
            $testUser->id,
            $questionData['id'],
            $order
        ]);
        // Check if the test log was created successfully
        if (!$testLog) {
            throw new \Exception('Failed to create test log');
        }
        // Assign the first object or null if no result found
        $testLog = DB::select('
            SELECT *
            FROM test_logs
            WHERE tests_users_id = ?
            AND question_id = ?
            AND `order` = ?
            ORDER BY created_at DESC
            LIMIT 1
        ', [
            $testUser->id,
            $questionData['id'],
            $order
        ]);
        $testLog = !empty($testLog) ? $testLog[0] : null; // Assign the first object or null if no result found
        //   dd($testLog);
        $this->addQuestionAnswers($testLog, $questionData['id'], $questionData['type'], $questionData['answers'], $firstTest, $test);
        return $testLog;
    }

    protected function addQuestionAnswers($testLog, $questionId, $questionType, $numAnswers, $firstTest, $test)
    {
        //dd($testLog, $questionId, $questionType, $numAnswers, $firstTest, $test);
        $question = Question::findOrFail($questionId);
        //dd($question);

        // Get answers based on test configuration
        $answers = $this->getAnswersForQuestion($question, $questionType, $numAnswers, $firstTest, $test);
        // Create answer logs
        foreach ($answers as $answer) {
            $testLog->answerLogs()->create([
                'answer_id' => $answer->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    protected function getAnswersForQuestion($question, $questionType, $numAnswers, $firstTest, $test)
    {
        $answers = $question->answers()->where('enabled', true);
        //dd($answers);

        // If copying from first test, get same answers
        if ($firstTest) {

            $originalTestLog =  DB::table('test_logs')
                ->where('tests_users_id', $firstTest->id)
                ->where('question_id', $question->id)
                ->orderBy('created_at')
                ->first(); //get()

            // dd($originalTestLog);
            if ($originalTestLog) {
                $getAnswers = DB::table('answer_logs')
                    ->where('test_logs_id', $originalTestLog->id) // Adjust the column name as needed
                    ->orderBy('created_at')
                    ->get();
                return $getAnswers;
            }
        }

        // Apply answer ordering
        if (!$test->random_answers_order && $test->answers_order_mode == 0) {
            $answers->where('position', '>', 0)->orderBy('position');
        } elseif ($test->random_answers_order) {
            $answers->inRandomOrder();
        } else {
            $answers = $this->applyAnswerOrdering($answers, $test->answers_order_mode);
        }

        // Limit number of answers if specified
        if ($numAnswers > 0) {
            $answers->limit($numAnswers);
        }

        return $answers->get();
    }

    protected function applyAnswerOrdering($query, $orderMode)
    {
        // Similar to question ordering but for answers
        // Implementation depends on your answer ordering requirements
        return $query->orderBy('position');
    }

    protected function isTestOverLimits()
    {
        // Implement your test limit validation logic
        return false;
    }

    public function updateTestUserStat($date)
    {
        try {
            DB::table('testuser_stat')->insert(['tus_date' => $date]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update test user stats: ' . $e->getMessage());
            return false;
        }
    }
}
