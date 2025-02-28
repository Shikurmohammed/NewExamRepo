<?php

namespace App\Livewire\Exams;

use App\Livewire\Modals\Modal;
use App\Models\Group;
use App\Models\Module;
use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;

class TestModal extends Modal
{
    #[Rule(['required'])]
    public $test_name;
    public $description;
    public $start;
    public $end;
    public $duration;
    public $group_id;
    public $score_right = 1;
    public $score_wrong = 0;
    public $score_threshold;

    #[Rule(['required', 'min:6'])]
    public $exam_password;
    public $score_unanswered = 0;
    public $max_score;

    public $random_questions_select = true;
    public $random_questions_order = true;
    public $random_answers_select = true;
    public $random_answers_order = true;
    public $mcma_partial_score = true;
    public $noanswer_enabled = true;
    public $comment_enabled = true;
    public $repeatable;

    public $result_to_user;
    public $report_to_user;
    public $logout_on_timeout;
    public $menu_enabled = true;

    public $questions_order_mode;
    public $qordmode = [
        ['id' => 0, 'name' => 'position'],
        ['id' => 1, 'name' => 'alphabet'],
        ['id' => 2, 'name' => 'type'],
        ['id' => 3, 'name' => 'id'],
        ['id' => 4, 'name' => 'topic']
    ];

    public function updated($propertyName)
    {
        if ($propertyName == 'start' || $propertyName == 'end') {
            if ($this->start && $this->end) {
                $start = Carbon::parse($this->start);
                $end = Carbon::parse($this->end);
                $this->duration = $end->diffInMinutes($start);
            }
        }
    }
    public function create()
    {

        $this->validate();
        try {

            $is_test_name_exist = Test::where('name', $this->test_name)->exists();
            if ($is_test_name_exist) {
                noty()->livewire()->addError("Test name already exist!");
                return redirect()->back();
            }

            $test = new Test();
            $test->user_id = Auth::id();
            $test->name = $this->test_name;
            $description = trim(strip_tags($this->description));
            $test->description =  $description;

            if ($this->start) {
                $test->start = Carbon::parse($this->start)->format('Y-m-d H:i:s');
            }
            if ($this->end) {
                $test->end = Carbon::parse($this->end)->format('Y-m-d H:i:s');
            }
            // Calculate duration if both start and end are set
            if ($this->start && $this->end) {
                $start = Carbon::parse($this->start);
                $end = Carbon::parse($this->end);
                $test->duration = $this->duration ?? $end->diffInMinutes($start);
            } else {
                $test->duration = $this->duration ?? 0; // or handle accordingly
            }
            //IP Range will be considered later

            $test->score_right = $this->score_right ? 1 : 0;
            $test->score_wrong = $this->score_wrong ? 1 : 0;
            $test->score_threshold = $this->score_threshold ? 1 : 0;
            //Hash the password
            if ($this->exam_password)
                $test->password = Hash::make($this->exam_password);
            $test->score_unanswered = $this->score_unanswered ? 1 : 0;
            $test->max_score = $this->max_score ?? 0;
            $test->random_questions_select = $this->random_questions_select ? 1 : 0;
            $test->random_answers_select = $this->random_answers_select ? 1 : 0;

            $test->mcma_partial_score = $this->mcma_partial_score ? 1 : 0;
            $test->noanswer_enabled = $this->noanswer_enabled ?? 0;
            $test->comment_enabled = $this->comment_enabled ? 1 : 0;
            $test->repeatable = $this->repateable ? 1 : 0;

            $test->result_to_user = $this->result_to_user ? 1 : 0;
            $test->report_to_user = $this->report_to_user ? 1 : 0;
            $test->logout_on_timeout = $this->logout_on_timeout ? 1 : 0;
            $test->menu_enabled = $this->menu_enabled ? 1 : 0;
            //question_order_mode and answer_order_mode
            $test->created_by = Auth::user()->name;
            $test->save(); //Save test and get test_id to save in test_groups table

            $group_ids = $this->group_id;
            $group_ids = [$group_ids];
            if ($group_ids) {
                foreach ($group_ids as $group_id) {
                    $test->groups()->attach($group_id); //Save test_id and group_id test_groups
                }
                noty()->livewire()->addSuccess("Test added successfully");
                $this->reset();
                return redirect()->back(); //('/view_test');
            } else {
                noty()->livewire()->addError("Error!");
            }
        } catch (\Throwable $th) {
            noty()->livewire()->addError("Error!" . $th->getMessage());
        }
    }
    #[Computed()]
    public function modules()
    {
        return Module::all();
    }
    #[Computed()]
    public function groups()
    {
        return Group::all();
    }
    public function render()
    {
        return view('livewire.exams.test-modal');
    }
}
