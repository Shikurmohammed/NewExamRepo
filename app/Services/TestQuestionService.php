<?php

namespace App\Services;

use App\Models\TestLog;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestQuestionService
{
    public function getQuestionData(int $testId, int $testLogId): array
    {
        // Step 1: Handle first question if no specific testLogId is provided
        if ($testLogId === 0) {
            $firstQuestion = DB::select('
            SELECT tl.*,tl.question_id as tl_question_id
            FROM tests_users tu
            JOIN test_logs tl ON tl.tests_users_id = tu.id
            WHERE tu.test_id = ?
              AND tu.user_id = ?
              AND tu.status < 5
            ORDER BY tl.id
            LIMIT 1
        ', [$testId, auth()->id()]);

            if (empty($firstQuestion)) {
                return [];
            }

            $testLog = $firstQuestion[0];
            $testLogId = $testLog->id;
        } else {
            // Step 2: Retrieve test log and associated data for the given testLogId
            $testLogData = DB::select('
            SELECT tl.*, tl.question_id AS tl_question_id, q.*, la.*, a.*, a.id as answer_id
            FROM test_logs tl
            LEFT JOIN questions q ON tl.question_id = q.id
            LEFT JOIN answer_logs la ON tl.id = la.test_logs_id
            LEFT JOIN answers a ON la.answer_id = a.id
            WHERE tl.id = ?
            LIMIT 1
        ', [$testLogId]);

            if (empty($testLogData)) {
                return [];
            }
            $testLog = $testLogData[0];
        }

        // Step 3: Mark question as displayed if not already
        if (empty($testLog->display_time)) {
            DB::table('test_logs')
                ->where('id', $testLogId)
                ->update(['display_time' => now()]);

            // Update local object to reflect change
            $testLog->display_time = now();
        }

        // Step 4: Fetch question details
        if ($testLog->tl_question_id)
            $question = Question::find($testLog->tl_question_id);
        if (!$question) {
            return [];
        }

        // Get test data
        $test_data = DB::table('tests')->where('id', $testId)->first();
        $examtime = now()->timestamp + ($test_data->duration * 60);
        // Get answers
        $answers = DB::table('answers')
            ->join('answer_logs', 'answer_id', '=', 'id')
            ->where('test_logs_id', $testLogId)
            ->orderBy('order')
            ->get();
        // dd($answers);


        // 🧠 Generate question menu
        $questionMenu = $this->generateQuestionMenu($testLog->tests_users_id, $testLogId, $testId);
        // dd($questionMenu);
        // Step 5: Return structured response
        return [
            'testId' => $testId,
            'testLogId' => $testLogId,
            'display_time' => $testLog->display_time,
            'question' => [
                'id' => $question->id,
                'description' => $question->description,
                'type' => $question->type,
                'timer' => $question->timer,
                'fullscreen' => (bool) $question->fullscreen,
                'inline_answers' => (bool) $question->inline_answers,
                'auto_next' => (bool) $question->auto_next,
            ],
            'testData' => [
                'noanswer_enabled' => (bool) $test_data->noanswer_enabled,
                'mcma_radio' => (bool) $test_data->mcma_radio,
                'logout_on_timeout' => (bool) $test_data->logout_on_timeout,
            ],
            'answers' => $this->formatAnswers($testLogId, $testLog->tl_question_id),
            'config' => [
                'enable_virtual_keyboard' => config('tests.enable_virtual_keyboard'),
                'textarea_cols' => config('tests.answer_textarea_cols'),
                'textarea_rows' => config('tests.answer_textarea_rows'),
            ],
            'questionMenu' => $questionMenu,
        ];
    }

    protected function formatAnswers(int $testLogId, int $questionId): array
    {
        // dd($testLogId, $questionId);
        $answers = DB::select(
            "
                            SELECT
                                a.id,
                                a.description,
                                a.keyboard_key,
                                la.is_selected AS selected,
                                la.position AS answer_position
                            FROM answers a
                            LEFT JOIN answer_logs la
                                ON la.answer_id = a.id AND la.test_logs_id = ?
                            WHERE a.question_id = ?
                            ORDER BY a.position ASC
                        ",
            [$testLogId, $questionId]
        );
        // dd($answers);

        return array_map(function ($ans) {
            return [
                'id' => $ans->id,
                'description' => $ans->description,
                'selected' => (bool)$ans->selected,
                'position' => $ans->answer_position,
                'keyboard_key' => $ans->keyboard_key,
            ];
        }, $answers);
    }

    protected function generateQuestionMenu(int $testsUsersId, int $currentTestLogId, int $testId): array
    {
        $logs = DB::table('test_logs as tl')
            ->join('questions as q', 'tl.question_id', '=', 'q.id')
            ->where('tl.tests_users_id', $testsUsersId)
            ->orderBy('tl.id')
            ->select('tl.id as testlog_id', 'tl.display_time', 'tl.change_time', 'q.description', 'q.difficulty', 'q.timer')
            ->get();

        $menu = [];
        $prevId = null;
        $nextId = null;
        $currentIndex = 0;

        foreach ($logs as $index => $log) {
            if ($log->testlog_id == $currentTestLogId) {
                $currentIndex = $index;
                $prevId = $logs[$index - 1]->testlog_id ?? null;
                $nextId = $logs[$index + 1]->testlog_id ?? null;
            }

            $menu[] = [
                'id' => $log->testlog_id,
                'description' => $log->description,
                'displayed' => !empty($log->display_time),
                'answered' => !empty($log->change_time),
                'score' => config('tests.test_score_right') * $log->difficulty,
            ];
        }

        return [
            'currentIndex' => $currentIndex + 1,
            'previousId' => $prevId,
            'nextId' => $nextId,
            'questions' => $menu
        ];
    }




    public function getQuestionData1(int $testId, int $testLogId): array
    {
        // Execute raw SQL query,    to retirieve question to display
        //,tu.*, t.*
        //  LEFT JOIN tests_users tu ON tl.tests_users_id = tu.user_id
        $testLog = DB::select("
                        SELECT tl.*, tl.question_id as tl_question_id, q.*, la.*, a.*
                        FROM test_logs tl
                        LEFT JOIN questions q ON tl.question_id = q.id
                        LEFT JOIN answer_logs la ON tl.id = la.test_logs_id
                        LEFT JOIN answers a ON la.answer_id = a.id
                        WHERE tl.id = ?
                        LIMIT 1
                    ", [$testLogId]);
        // Handle first question if none specified
        if ($testLogId === 0) {
            $firstQuestion = DB::select('
                        SELECT tl.*
                        FROM tests_users tu, test_logs tl
                        WHERE tl.tests_users_id = tu.id
                        AND tu.test_id = ?
                        AND tu.user_id = ?
                        AND tu.status < 5
                        ORDER BY tl.id
                        LIMIT 1
                    ', [$testId, auth()->id()]);
            if (empty($firstQuestion)) {
                return [];
            }
            $testLog = $firstQuestion;
            $testLogId = $firstQuestion[0]->id;
        }
        $testLog = $testLog[0];
        // Mark question as displayed if not already
        if (!$testLog->display_time) {
            DB::table('test_logs')
                ->where('id', $testLogId)
                ->update(['display_time' => now()]);
        }

        $question = Question::find($testLog->tl_question_id);
        //dd($question);
        return [
            'testId' => $testId,
            'testLogId' => $testLogId,
            'display_time' => $testLog->display_time,
            'question' => [
                'id' => $testLog->tl_question_id,
                'description' => $question->description,
                'type' => $question->type,
                'timer' => $question->timer,
                'fullscreen' => (bool)$question->fullscreen,
                'inline_answers' => (bool)$question->inline_answers,
                'auto_next' => (bool)$question->auto_next,
            ],
            'testData' => [
                //'id' => $testLog->test_id,
                'noanswer_enabled' => (bool)$question->noanswer_enabled,
                'mcma_radio' => (bool)$question->mcma_radio,
                'logout_on_timeout' => (bool)$question->logout_on_timeout,
            ],
            //'answers' => $this->formatAnswers($testLog),
            'config' => [
                'enable_virtual_keyboard' => config('tests.enable_virtual_keyboard'),
                'textarea_cols' => config('tests.answer_textarea_cols'),
                'textarea_rows' => config('tests.answer_textarea_rows'),
            ]
        ];
    }
    protected function formatAnswers1111(object $testLog): array
    {
        // You'll need to adjust this based on your actual answer data structure
        // dd($testLog);
        return [
            [
                'id' => $testLog->answer_id,
                'description' => $testLog->answer_description,
                'selected' => $testLog->logansw_selected,
                'position' => $testLog->logansw_position,
                'keyboard_key' => $testLog->answer_keyboard_key
            ]
        ];
    }

    public function updateQuestionLog(
        int $testId,
        int $testLogId,
        array $answerPositions,
        string $answerText,
        int $reactionTime
    ): void {

        DB::table('test_logs')
            ->where('id', $testLogId)
            ->where('tests_users_id', function ($query) use ($testId) {
                $query->select('user_id')
                    ->from('tests_users')
                    ->where('test_id', $testId)
                    ->where('user_id', auth()->id())
                    ->limit(1);
            })
            ->update([
                //'position' => json_encode($answerPositions),
                'answer_text' => $answerText,
                'reaction_time' => $reactionTime,
                'change_time' => now(),
            ]);
    }
    public function getOmittedQuestionsCount(int $testId): int
    {
        $count = DB::select("
    SELECT COUNT(*) AS count
    FROM test_logs
    WHERE testlog_testuser_id IN (
        SELECT testuser_id
        FROM test_user
        WHERE testuser_test_id = ?
        AND testuser_user_id = ?
    )
    AND (testlog_display_time IS NULL OR testlog_change_time IS NULL)
", [$testId, auth()->id()]);

        $countValue = $count[0]->count; // Access the count value
        return $countValue;
    }
}
