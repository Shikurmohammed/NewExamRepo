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
    public $score_right;
    public $score_wrong;
    public $score_threshold;

    public $exam_password;
    public $score_unanswered;
    public $max_score;

    public $random_questions_select;
    public $random_answers_select;
    public $mcma_partial_score;
    public $noanswer_enabled;
    public $comment_enabled;
    public $repeatable;

    public $result_to_user;
    public $report_to_user;
    public $logout_on_timeout;
    public $menu_enabled;


    public function create()
    {

        $this->validate();
        try {
            $test = new Test();
            $test->user_id = Auth::id();
            $test->name = $this->test_name;
            $description = trim(strip_tags($this->description));
            $test->description =  $description;
            if ($this->start)
                $test->start = Carbon::parse($this->start)->format('Y-m-d H:i:s');
            if ($this->start)
                $test->end = Carbon::parse($this->end)->format('Y-m-d H:i:s');
            $test->duration = $this->duration;

            $test->score_right = $this->score_right ?? 0;
            $test->score_wrong = $this->score_wrong ?? 0;
            $test->score_threshold = $this->score_threshold ?? 0;
            //Hash the password
            if ($this->exam_password)
                $test->password = Hash::make($this->exam_password);
            $test->score_unanswered = $this->score_unanswered ?? 0;
            $test->max_score = $this->max_score ?? 0;
            $test->random_questions_select = $this->random_questions_select ?? 0;
            $test->random_answers_select = $this->random_answers_select ?? 0;

            $test->mcma_partial_score = $this->mcma_partial_score ?? 0;
            $test->noanswer_enabled = $this->noanswer_enabled ?? 0;
            $test->comment_enabled = $this->comment_enabled ?? 0;
            $test->repeatable = $this->repateable ?? 0;

            $test->result_to_user = $this->result_to_user ?? 0;
            $test->report_to_user = $this->report_to_user ?? 0;
            $test->logout_on_timeout = $this->logout_on_timeout ?? 0;
            $test->menu_enabled = $this->menu_enabled ?? 0;

            //question_order_mode and answer_order_mode
            $test->created_by = Auth::user()->name;
            $group_ids = $this->group_id;
            $group_ids = [$group_ids];

            $is_test_name_exist = Test::where('name', $this->test_name)->exists();
            //dd($test);
            if ($is_test_name_exist) {
                noty()->livewire()->addError("Test name already exist!");
                return redirect()->back();
            } else {
                if ($group_ids) {
                    $test->save(); //Save test and get test_id to save in test_groups table
                    foreach ($group_ids as $group_id) {
                        $test->groups()->attach($group_id); //Save test_id and group_id test_groups
                    }
                    noty()->livewire()->addSuccess("Test added successfully");
                    $this->reset();
                    return redirect()->back(); //('/view_test');
                } else {
                    noty()->livewire()->addError("Error!");
                }
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
