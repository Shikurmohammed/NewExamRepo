<?php

namespace App\Livewire\Exams;

use App\Models\Group;
use App\Models\Test;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class TestList extends Component
{

    public $search;

    #[Computed()]
    public function groups()
    {
        return Group::all();
    }
    #[Computed()]
    public  function tests()
    {
        $tests = [];
        if (!$this->search) {

            $tests = Test::with('user', 'groups')->paginate(4);
        } else {
            $this->search = strtolower(trim($this->search));
            $tests = Test::with('user', 'groups')->where('name', 'Like', "%{$this->search}%")->paginate(4);
        }
        return $tests;
    }
    public function lockTest($id)
    {
        try {
            $test = Test::find($id);
            $test->isLocked = 1;
            $test->save();
            noty()->livewire()->addSuccess("Test locked successfully");
        } catch (\Exception $e) {
            return response()->json(['err' => 'Fail: ' . $e->getMessage()]);
        }
    }
    public function unLockTest($id)
    {
        try {
            $test = Test::find($id);
            $test->isLocked = 0;
            $test->save();
            noty()->livewire()->addSuccess("Test unlocked successfully");
        } catch (\Exception $e) {
            return response()->json(['err' => 'Fail: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $test = Test::find($id);
            if (! $test) {
                return response()->json(['error' => "Test with ID:: " . $id . " not found!"]);
            }
            //check if test is used in test_groups table
            $isTestUsedInTest_group = DB::table('test_groups')->where('test_id', $id)->exists();
            if ($isTestUsedInTest_group) {
                $test->groups()->detach(); //Detach group_id from test_group table
            }
            $test->delete();
            noty()->livewire()->addSuccess("Test with ID:: " . $id . " deleted successfully!");
        } catch (\Exception $e) {
            noty()->livewire()->addSuccess($e->getMessage() . "Failed to delete test with ID::" . $id);
        }
    }
    public function update($id)
    {
        $this->validate();
        try {
            $testInuse = DB::table('tests_users', 'test_id', $id)->exists();
            if ($testInuse) {
                noty()->error("Test is in use!");
                return redirect()->back();
            }
            $test = Test::findOrFail($id);
            $score_threshold = $this->score_threshold ?? 0;
            $max_score = $this->max_score ?? 0;
            //score_threshold => pass score
            if ($score_threshold >  $max_score) {
                //If the pass point is greater than the maximum score, set the pass point to 60 % of the maximum score
                $score_threshold = 0.6 * $max_score;
            }
            //Hash the password
            if ($this->password)
                $test->password = Hash::make($this->exam_password);


            // Populate the test object with the this data
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
            $test->score_unanswered = $this->score_unanswered ?? 0;
            $test->max_score = $this->max_score ?? 0;
            $test->random_questions_select = $this->random_questions_select ?? 0;
            $test->random_answers_select = $this->random_questions_select ?? 0;

            $test->mcma_partial_score = $this->mcma_partial_score ?? 0;
            $test->noanswer_enabled = $this->noanswer_enabled ?? 0;
            $test->comment_enabled = $this->comment_enabled ?? 0;
            $test->repeatable = $this->repateable ?? 0;

            $test->result_to_user = $this->result_to_user ?? 0;
            $test->report_to_user = $this->report_to_user ?? 0;
            $test->logout_on_timeout = $this->logout_on_timeout ?? 0;
            $test->menu_enabled = $this->menu_enabled ?? 0;
            //question_order_mode and answer_order_mode
            $test->updated_by = Auth::user()->name;
            $group_ids = $this->group_id;
            $group_ids = [$group_ids];
            /*
              Delete previous group_id's from test_groups table where the test_id is $id,  This will maintain data integrity,
              so that we don't have to worry about orphaned data during updation or deletion of data.
            */
            DB::table('test_groups')->where('test_id', $id)->delete();
            if ($group_ids) {
                $test->save(); //Save test and get test_id to save in test_groups table
                foreach ($group_ids as $group_id) {
                    $test->groups()->attach($group_id); //Save test_id and group_id test_groups
                }
                noty()->success("Test updated successfully");
                return redirect()->back(); //('/view_test');
            } else {
                noty()->error("Error: ");
            }
            return redirect()->back();
        } catch (\Exception $e) {
        }
        //return redirect('/view_test');
    }

    public function render()
    {
        return view('livewire.exams.test-list');
    }
}
