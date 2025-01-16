<?php

namespace App\Http\Controllers;

use App\Models;
use App\Models\Group;
use App\Models\Question;
use App\Models\Test;
use App\Models\test_subject_sets;
use App\Models\Test_topic_sets;
use App\Models\Topic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Flasher\Noty\Prime\noty;

class TestTopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        //$group_data = Group::all();
        $question_data = Question::all();
        $topic_data = Topic::all();
        $test_data = Test::all();

        $question_type = [
            ['id' => 1, 'name' => 'Single Answer'],
            ['id' => 2, 'name' => 'Multiple Answer'],
            ['id' => 3, 'name' => 'Free Answer'],
            ['id' => 4, 'name' => 'Ordering Answer']

        ];
        $question_difficulty_level = [
            ['id' => 1, 'name' => 'Easy'],
            ['id' => 2, 'name' => 'Medium'],
            ['id' => 3, 'name' => 'Hard']
        ];
        return view('shared.assignQuestion.view_question_assignment', compact('test_data', 'question_data', 'topic_data', 'question_type', 'question_difficulty_level'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    //Add question to test from previous test
    public function store(Request $request)
    {
        try {
            $selectedTopics = $request->input('selected_topics', []);
            $selectedQuestions = $request->input('selected_questions', []);
            $testId = $request->input('test_id');
            $type = $request->input('type');
            $difficulty = $request->input('difficulty');
            $quantity = count($selectedQuestions);
            //Fetch test_topic_set data using the provided test_id
            $test_topic_set_data = Test_topic_sets::where('test_id', $request->test_id)->first();

            if ($test_topic_set_data) {
                foreach ($test_topic_set_data as $tts) {
                    $test_topic_set = new Test_topic_sets();
                    $test_topic_set->test_id = $request->test_id;
                    $test_topic_set->type = $tts->type ?? $type;
                    $test_topic_set->difficulty = $tts->difficulty ?? $difficulty;
                    $test_topic_set->quantity = $tts->quantity ?? $quantity;
                    $test_topic_set->save();
                    foreach ($selectedTopics as  $topics) {
                        $test_topic_set->topics()->attach($topics);
                    }
                }
            } else {
                $test_topic_set = new Test_topic_sets();
                $test_topic_set->test_id = $testId;
                $test_topic_set->type = $type;
                $test_topic_set->difficulty = $difficulty;
                $test_topic_set->quantity = $quantity;
                $test_topic_set->save();
                foreach ($selectedTopics as  $topics) {
                    $test_topic_set->topics()->attach($topics);
                }
            }

            return response()->json([
                'success' => 'Question assigned successfully',
                'selected_topics' => $selectedTopics,
                'selected_questions' => $selectedQuestions,
                'test name' => $request->test_id,
                'type' => $request->type_id,
                'difficulty' => $request->difficulty_id,
                'ToSave' => $test_topic_set
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            //noty()->error("Test Not Added");
            return response()->json(['err' => 'Faiil' . $e->getMessage()]);
        }
    }

    //add question to test
    public function assignQuestion(Request $request)
    {
        try {
            // Check if the test is in use in test_users table
            $isTestUsedInTestUsers = DB::table('tests_users')->where('test_id', $request->test_id)->exists();
            if ($isTestUsedInTestUsers) {
                noty()->error('Attention, Test is in use!');
                //return redirect()->back();
                return response()->json(['err' => 'Attention, Test is in use ']);
            }

            $selectedTopics = $request->input('selected_topics', []);
            $selectedQuestions = $request->input('selected_questions', []);
            $answers = $request->input('answer_count');
            $difficulty = $request->input('difficulty');
            $quantity = count($selectedQuestions);

            if ($selectedTopics && $quantity) {
                if ($request->type == 3) {
                    $answers = 0; // Free answer has no alternative answers to display!
                } elseif ($answers < 2 && $difficulty > 0) {
                    $answers = 2; // Questions must have at least 2 alternative answers
                }
                //Some modifictions needed here, i.e to accept module ids from a form
                $sql_question_position = '';
                $sql_answer_position = '';

                $test = Test::where('id', $request->test_id)->first();
                $random_questions_order = $test->random_questions_select;
                $questions_order_mode = $test->questions_order_mode;
                $random_answers_order = $test->random_answers_order;
                $answers_order_mode = $test->answers_order_mode;


                //Ensure that questions orderd by positions if the random_questions_select is false and questions_order_mode is 0
                if (!$random_questions_order && $questions_order_mode == 0) {
                    //Ensures that only questions with postion value > 0 are included in the sql query
                    $sql_question_position .= 'AND position > 0';
                }
                if (!$random_answers_order && $answers_order_mode == 0) {
                    $sql_answer_position .= 'AND position > 0';
                }

                $selectedTopicsString = implode(',', $selectedTopics);
                //Check if the number of questions required for the test is available
                //Here we need to track reusable and non-reusable questions,To do...
                $sql = "SELECT COUNT(*) as total_questions FROM questions WHERE topic_id IN ($selectedTopicsString) AND difficulty = ? AND enabled = 1";
                $bindings = [$difficulty];

                if ($test->type > 0) {
                    $sql .= " AND type = ?";
                    $bindings[] = $test->type;
                }
                //If Question type is multiple choice single answer, ensure it has enough answers, at least 2
                if ($test->type == 1) {
                    $sql .= " AND id IN (SELECT question_id FROM answers WHERE enabled = 1 AND is_right = 1 $sql_answer_position GROUP BY question_id HAVING COUNT(id) > 0)";
                    $sql .= " AND id IN (SELECT question_id FROM answers WHERE enabled = 1 AND is_right = 0 $sql_answer_position GROUP BY question_id HAVING COUNT(id) > 0)";
                }
                //If Question type is multiple choice multiple answer, ensure it has enough answers, at least 2
                if ($test->type == 2) {
                    $sql .= " AND id IN (SELECT question_id FROM answers WHERE enabled = 1 $sql_answer_position GROUP BY question_id HAVING COUNT(id) > ?)";
                    $bindings[] = $answers;
                }
                //For Ordering questions, ensure that the questions has enough answers
                if ($test->type == 4) {
                    $sql .= " AND question_id IN (SELECT question_id FROM answers WHERE enabled = 1 AND position > 0 GROUP BY question_id HAVING COUNT(id) > ?)";
                    $bindings[] = $answers;
                }
                //IF Database is ORACLE :  $sqlq = 'SELECT * FROM (' . $sqlq . ') WHERE rownum <= ' . $uantity . '';
                $sql .= " $sql_question_position LIMIT $quantity";

                $total_questions = DB::select($sql, $bindings); //Get the total number of questions available

                if (count($total_questions) < $quantity) { //Check if the number of questions required for the test is available
                    noty()->error("There are not enough questions available for the test");
                    //return redirect()->back();
                    return response()->json(['err' => count($total_questions) . 'There are not enough questions available for the test' . $quantity . " Answer:" . $answers]);
                }

                if ($selectedTopics !== []) {
                    //Inser topics to this test
                    $test_topic_set = new Test_topic_sets();
                    $test_topic_set->test_id = $request->test_id;
                    $test_topic_set->type = $test->type;
                    $test_topic_set->difficulty = $difficulty;
                    $test_topic_set->quantity = $quantity;
                    $test_topic_set->answers = $answers;
                    $test_topic_set->save();

                    foreach ($selectedTopics as $topic) {
                        DB::table('test_topics')->insert([
                            'topic_id' => $topic,
                            'test_topic_set_id' => $test_topic_set->id
                        ]);
                    }
                }
            } else {
                noty()->error("Please select topics and questions to assign to the test");
                //return redirect()->back();
                return response()->json(['err' => 'Please select topics and questions to assign to the test ']);
            }

            return response()->json([
                'success' => 'Question assigned successfully',
                'selected_topics' => $selectedTopics,
                'selected_questions' => $selectedQuestions,
                'test name' => $request->test_id,
                'type' => $request->type,
                'difficulty' => $request->difficulty,
                'ToSave' => $test_topic_set
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['err' => 'Fail: ' . $e->getMessage()]);
        }
    }
    public function removeTopicsFromTest(Request $request)
    {
        try {
            //Check if test is in use in tests_users table
            $isTestUsedInTestUsers = DB::table('tests_users')->where('test_id', $request->test_id)->exists();
            if ($isTestUsedInTestUsers) {
                return response()->json(['err' => 'Attention, Test is in use!']);
            }
            //Remove each selected topics from the test
            for ($i = 0; $i < count($request->selected_topics); $i++) {
                $sql = "DELETE FROM test_topic_sets where test_id =? AND id=?";
                $bindings = [$request->test_id, $i];
                DB::delete($sql, $bindings);
            }
        } catch (\Exception $e) {
        }
    }

    public function assignQuestion1(Request $request)
    {

        try {
            //dd($request->all());

            //Check if the test is in use in test_users table
            $isTestUsedInTestUsers = DB::table('tests_users')->where('test_id', $request->test_id)->exists();
            if ($isTestUsedInTestUsers) {
                noty()->error('Attention, Test is in use!');
                return redirect()->back();
            }
            $selectedTopics = $request->input('selected_topics', []);
            $selectedQuestions = $request->input('selected_questions', []);
            $answers = 2; //$request->input('answers');
            $difficulty = $request->input('difficulty');
            $quantity = count($selectedQuestions);

            if ($selectedTopics && $quantity) { //If topics and number of questions needed are selected
                if ($request->type == 3) {
                    $answers = 0; //Free answer has no alternative answers to display!
                } elseif ($answers < 2 && $difficulty > 0) {
                    $answers = 2; //Questions must have at least 2 alternative answers
                }
                //Some modifictions needed here, i.e to accept module ids from a form
                $sql_question_position = '';
                $sql_answer_position = '';

                $test = Test::where('id', $request->test_id)->first();
                $random_questions_order = $test->random_questions_select;
                $questions_order_mode = $test->questions_order_mode;
                $random_answers_order = $test->random_answers_order;
                $answers_order_mode = $test->answers_order_mode;

                //Ensure that questions orderd by positions if the random_questions_select is false and questions_order_mode is 0
                if (!$random_questions_order && $questions_order_mode == 0) {
                    //Ensures that only questions with postion value > 0 are included in the sql query
                    $sql_question_position .= 'AND position > 0';
                }
                //Ensure that answers are orderd by their positions if the random answers select is false and answers_order_mode is 0
                if (!$random_answers_order && $answers_order_mode == 0) {
                    //Ensures that only answers with postion value > 0 are included in the sql query
                    $sql_answer_position .= 'AND position > 0';
                }
                $selectedTopicsString = implode(',', $selectedTopics);
                //Check if the number of questions required for the test is available
                //Here we need to track reusable and non-reusable questions,To do...
                $sql = "SELECT COUNT(*) as total_questions FROM questions where topic_id IN
                $selectedTopicsString AND difficulty =' . $difficulty . ' AND enabled = 1";
                if ($test->type > 0) {
                    $sql .= "AND type = '.$test->type.'";
                }
                //If Question type is multiple choice single answer, ensure it has enough answers, at least 2
                if ($test->type == 1) {
                    $sql .= "AND id IN( SELECT question_id FROM answers WHERE enabled = 1 AND is_right = 1)";
                    $sql .= $sql_answer_position;
                    $sql .= "GROUP BY question_id HAVING(COUNT(id) > 0)";
                    $sql .= "AND id  IN(SELECT question_id FROM answers WHERE enabled =1 AND is_right = 0)";
                    $sql .= $sql_answer_position;
                    $sql .= "GROUP BY question_id";

                    if ($answers > 0) {
                        $sql .= "HAVING COUNT(id) >0";
                    }
                    $sql .= ")";
                }
                if ($test->type == 2) {
                    //For multiple choice multiple answers questions,
                    //ensure that the questions has enough answers, at least 2
                    $sql .= "AND id IN(SELECT question_id FROm answers where enabled =1)";
                    $sql .= $sql_answer_position;
                    $sql .= " GROUP BY question_id ";
                    if ($answers > 0) {
                        $sql .= "HAVING COUNT(id) > $answers";
                    }
                    $sql .= ")";
                }
                if ($test->type == 4) {
                    //For Ordering questions, ensure that the questions has enough answers
                    $sql .= "AND question_id IN ( SELECT question_id FROM answers WHERE enabled =1 AND position >0 GROUP BY question_id HAVING (COUNT(id)";
                }
                $sql .= $sql_question_position;
                //IF Database is ORACLE :  $sqlq = 'SELECT * FROM (' . $sqlq . ') WHERE rownum <= ' . $uantity . '';
                $sql .= "LIMIT $quantity";



                $total_questions = DB::select($sql); //Get the total number of questions available
                if ($total_questions < $quantity) {
                    noty()->error("There are not enough questions available for the test ");
                    return redirect()->back();
                }

                if ($selectedTopics !== []) {
                    //Inser topics to this test
                    $sql = "INSERT INTO test_topic_sets (test_id, type, difficulty, quantity, answers)
                      VALUES($request->test_id,  $test->type, $difficulty,$quantity, $answers)";
                    $test_topic_set_id = DB::insert($sql);

                    foreach ($selectedTopics as $topic) {
                        $sql = "INSERT INTO test_topics(topic_id, test_topic_set_id) VALUES( $topic, $test_topic_set_id)";
                    }
                }
            } else {
                noty()->error("Please select topics and questions to assign to the test");
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            //noty()->error("Test Not Added");
            return response()->json(['err' => 'Faiil' . $e]);
        }
    }
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function startTest(string $id){

    }
}
