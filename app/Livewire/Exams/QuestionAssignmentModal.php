<?php

namespace App\Livewire\Exams;

use App\Livewire\Modals\Modal;
use App\Models\Module;
use App\Models\Question;
use App\Models\Test;
use App\Models\Test_topic_sets;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;

class QuestionAssignmentModal extends Modal
{
    #[Rule(['required', 'array', 'min:1'])]
    public array $topic_ids = [];
    #[Rule(['required'])]
    public $test_id;
    #[Rule(['required'])]
    public $question_ids = [];
    public $selectedQuestionType;
    public $selectedDifficultyLevel;
    #[Rule(['required'])]
    public $answer_count;
    public $question_count;
    public $max_score =0;//May be provided from a form.


    public function render()
    {
        $question_type = [
            ['id' => 1, 'name' => 'Single Answer'],
            ['id' => 2, 'name' => 'Multiple Answer'],
            ['id' => 3, 'name' => 'Free Answer'],
            ['id' => 4, 'name' => 'Ordering Answer']

        ];
        $difficulty_level = [
            ['id' => 1, 'name' => 'Easy'],
            ['id' => 2, 'name' => 'Medium'],
            ['id' => 3, 'name' => 'Hard']
        ];
        return view('livewire.exams.question-assignment-modal', compact('question_type', 'difficulty_level'));
    }

    #[Computed()]
    public function moduleWithTopics()
    {
        return Module::where('enabled', 1)
            ->with(['topics' => function ($query) {
                $query->where('enabled', 1);
            }])
            ->get();
    }
    #[Computed()]
    public function questions()
    {
        $questions = [];

        if (empty($this->topic_ids)) {
            $questions = Question::all();
        } else {
            $questions = Question::whereIn('topic_id', $this->topic_ids)->get();
        }


        return   $questions;
    }

    #[Computed()]
    public function tests()
    {
        return Test::all();
    }

    public function assignQuestion()
    {
        $this->validate();
        try {
            // Check if the test is in use in test_users table
            $isTestUsedInTestUsers = DB::table('tests_users')->where('test_id', $this->test_id)->exists();
            if ($isTestUsedInTestUsers) {
                noty()->livewire()->addError('Attention, Test is in use!');
                //return redirect()->back();
                //  return response()->json(['err' => 'Attention, Test is in use ']);
                return;
            }
            $selectedTopics = $this->topic_ids;
            $selectedTopics = [$selectedTopics];
            $selectedQuestions = $this->question_ids;
            $selectedQuestions = [$selectedQuestions];
            $answers = $this->answer_count;
            $difficulty = $this->selectedDifficultyLevel;
            $quantity = count($selectedQuestions) ?? 1;

            if ($selectedTopics && $quantity) {
                if ($this->selectedQuestionType == 3) {
                    $answers = 0; // Free answer has no alternative answers to display!
                } elseif ($answers < 2 && $difficulty > 0) {
                    $answers = 2; // Questions must have at least 2 alternative answers
                }
                //Some modifictions needed here, i.e to accept module ids from a form
                $sql_question_position = '';
                $sql_answer_position = '';

                dd($this->test_id);
                $test = Test::where('id', $this->test_id)->get();
                dd($test);
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
                //dd($selectedTopics);

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
                    noty()->livewire()->addError("There are not enough questions available for the test");
                    //return redirect()->back();
                    return response()->json(['err' => count($total_questions) . 'There are not enough questions available for the test' . $quantity . " Answer:" . $answers]);
                }

                if ($selectedTopics !== []) {
                    //Inser topics to this test
                    $test_topic_set = new Test_topic_sets();
                    $test_topic_set->test_id = $this->test_id;
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
                noty()->livewire()->addError("Please select topics and questions to assign to the test");
                //return redirect()->back();
                return response()->json(['err' => 'Please select topics and questions to assign to the test ']);
            }

            return response()->json([
                'success' => 'Question assigned successfully',
                'selected_topics' => $selectedTopics,
                'selected_questions' => $selectedQuestions,
                'test name' => $this->test_id,
                'type' => $this->type,
                'difficulty' => $this->difficulty,
                'ToSave' => $test_topic_set
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            noty()->livewire()->addError("Error!" . $e->getMessage());
            return response()->json(['err' => 'Fail: ' . $e->getMessage()]);
        }
    }

    public function updated($propertyName)
    {
        //Count questions for the selected module or topic
        if (str_starts_with($propertyName, 'topic_ids')) {

            foreach ($this->topic_ids as $id) {
                $sql = "";
                if (str_starts_with($id, '#')) { //If Id starts with #, it will be a mdoule id
                    $id = str_replace('#', '', $id);
                    //For topics under this module, we will count the number of enabled questions available
                    $sql .= "SELECT COUNT(q.id) as total_questions
                      from modules as m join  topics as t on m.id = t.module_id
                      LEFT JOIN questions As q on t.id = q.topic_id
                      where t.module_id =:module_id GROUP BY m.id";
                    $res = DB::select($sql, ["module_id" => $id]);
                    $this->question_count = !empty($res) ? $res[0]->total_questions : 0;
                } else {
                    $sql .= "SELECT COUNT(q.id) as total_questions FROM topics as t LEFT JOIN questions
                            as q on t.id = q.topic_id where t.id=:topic_id group by t.id";
                    $res = DB::select($sql, ["topic_id" => $id]);
                    $this->question_count = !empty($res) ? $res[0]->total_questions : 0;
                }
            }
        }
    }
}
