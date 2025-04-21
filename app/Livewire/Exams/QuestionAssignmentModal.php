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
use Livewire\Component;

class QuestionAssignmentModal extends Modal
{
    #[Rule(['required'])] // ,'array', 'min:1'
    public array $topic_ids;

    #[Rule(['required'])]
    public $test_id;

    public $question_ids;

    #[Rule(['required'])]
    public $selectedQuestionType;

    #[Rule(['required'])]
    public $selectedDifficultyLevel;

    #[Rule(['required'])]
    public $answer_count;

    public $question_count;
    public $max_score = 0;

    protected array $messages = [
        'topic_ids.required' => 'Topic(s) are required.', //'Please select topics to assign to the test',
        'topic_ids.min' => 'Please select at least one topic to assign to the test',
        'question_ids.required' => 'Please select questions to assign to the test',
        'selectedQuestionType.required' => 'Please select a question type',
        'selectedDifficultyLevel.required' => 'Please select a difficulty level',
        'test_id.required' => 'Please select a test',
        'selectedQuestionType.required' => 'Qestion type is required',
        'selectedDifficultyLevel.required' => 'Difficulty level is required',
        'answer_count.required' => 'Number of alternative of answer(s) is required.',
    ];

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
        $tests = Test::all();
        return view(
            'livewire.exams.question-assignment-modal',
            compact('question_type', 'difficulty_level', 'tests')
        );
    }

    #[Computed()]
    public function moduleWithTopics()
    {
        $modules = Module::where('enabled', 1)
            ->with(['topics' => function ($query) {
                $query->where('enabled', 1);
            }])
            ->get();

        $options = [];

        foreach ($modules as $module) {
            // Add module (Bold)
            $options[] = [
                'id' => '#' . $module->id,
                'name' => '(Module) ' . $module->name,
                'is_module' => true,
                'class' => 'cursor-pointer p-2 hover:bg-gray-200 font-bold'

            ];

            // Add its topics
            foreach ($module->topics as $topic) {
                $options[] = [
                    'id' => (string) $topic->id,
                    'name' => '— ' . $topic->name,
                    'is_module' => false,
                    'class' => 'cursor-pointer p-2 hover:bg-gray-200 pl-4'
                ];
            }
        }

        return $options;
    }

    #[Computed()]
    public function questions()
    {
        if (empty($this->topic_ids)) {
            return Question::all();
        }

        $topics = [];
        $modules = [];

        foreach ($this->topic_ids as $id) {
            if (str_starts_with($id, '#')) {
                $modules[] = str_replace('#', '', $id);
            } else {
                $topics[] = $id;
            }
        }

        // Debugging: Check what we got for topics and modules
        // Log::info('Selected Topics:', $topics);
        // Log::info('Selected Modules:', $modules);

        // Fetch topic IDs linked to selected modules
        $moduleTopicIds = Topic::whereIn('module_id', $modules)->pluck('id')->toArray();

        // Debugging: Check the fetched module topic IDs
        //Log::info('Module Topic IDs:', $moduleTopicIds);

        // Merge topic IDs
        $allTopicIds = array_merge($topics, $moduleTopicIds);

        // Debugging: Final topic IDs used for filtering
        //Log::info('Final Topic IDs for Filtering:', $allTopicIds);

        // Fetch questions based on topics
        $questions = Question::whereIn('topic_id', $allTopicIds)->get();

        // Debugging: Check the final query result
        Log::info('Filtered Questions:', $questions->toArray());

        return Question::whereIn('topic_id', $allTopicIds)->get();;
    }

    public function assignQuestion()
    {
        $this->validate();
        try {
            $isTestUsedInTestUsers = DB::table('tests_users')->where('test_id', $this->test_id)->exists();
            if ($isTestUsedInTestUsers) {
                noty()->livewire()->addError('Attention, Test is in use!');
                return;
            }

            $selectedTopics = $this->topic_ids;
            $selectedQuestions = $this->question_ids;
            $answers = $this->answer_count;
            $difficulty = $this->selectedDifficultyLevel;
            // $quantity = count($selectedQuestions) ?? 1;
            $quantity = $this->question_count;
            //  dd($quantity);

            // dd($selectedQuestions);
            // dd($difficulty,  $answers, $this->selectedQuestionType);

            if ($selectedTopics && $quantity) {
                if ($this->selectedQuestionType == 3) {
                    $answers = 0;
                } elseif ($answers < 2 && $difficulty > 0) {
                    $answers = 2;
                }
                $test = Test::find($this->test_id);
                $selectedTopicsString = implode(',', array_map(function ($id) {
                    return str_starts_with($id, '#') ? str_replace('#', '', $id) : $id;
                }, $selectedTopics));

                // $sql = "SELECT COUNT(*) as total_questions FROM questions WHERE topic_id IN ($selectedTopicsString) AND difficulty = ?";
                // $bindings = [$difficulty];

                // if ($test->type > 0) {
                //     $sql .= " AND type = ?";
                //     $bindings[] = $test->type;
                // }

                // $sql .= " LIMIT $quantity";

                // $total_questions = DB::select($sql, $bindings);
                // dd(count($total_questions));


                $sql = "SELECT COUNT(*) as total_questions FROM questions WHERE topic_id IN ($selectedTopicsString) AND difficulty = ?";
                $bindings = [$difficulty];

                if ($test->type > 0) {
                    $sql .= " AND type = ?";
                    $bindings[] = $test->type;
                }

                // Removed LIMIT, as it's not needed for COUNT
                $total_questions = DB::select($sql, $bindings);

                // Since count will return an array, retrieve the total_questions value
                $questionCount = $total_questions[0]->total_questions;

                //dd($questionCount);

                if ($questionCount < $quantity) { //<
                    noty()->livewire()->addError("There are not enough questions available for the test");
                    return;
                }

                if ($selectedTopics !== []) {
                    $test_topic_set = new Test_topic_sets();
                    $test_topic_set->test_id = $this->test_id;
                    $test_topic_set->type = $test->type;
                    $test_topic_set->difficulty = $difficulty;
                    $test_topic_set->quantity = $quantity;
                    $test_topic_set->answers = $answers;
                    $test_topic_set->save();
                    foreach ($selectedTopics as $topic) {
                        DB::table('test_topics')->insert([
                            'topic_id' => str_starts_with($topic, '#') ? str_replace('#', '', $topic) : $topic,
                            'test_topic_set_id' => $test_topic_set->id
                        ]);
                    }
                }
            } else {

                noty()->livewire()->addError("Please select topics and questions to assign to the test");
                return;
            }
            // dd("asda");

            noty()->livewire()->addSuccess("Question assigned successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            noty()->livewire()->addError("Error!" . $e->getMessage());
        }
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'topic_ids') {
            $this->question_count = 0;
            $newTopics = [];

            foreach ($this->topic_ids as $id) {
                if (str_starts_with($id, '#')) {
                    $moduleId = str_replace('#', '', $id);
                    $moduleTopics = Topic::where('module_id', $moduleId)->pluck('id')->toArray();
                    $newTopics = array_merge($newTopics, $moduleTopics);
                } else {
                    $newTopics[] = $id;
                }
            }

            $this->topic_ids = array_unique($newTopics);
            $this->question_count = Question::whereIn('topic_id', $this->topic_ids)->count();

            $this->question_ids = null; //reset question ids, otherwise the select box may not be updated
        }
    }
}
