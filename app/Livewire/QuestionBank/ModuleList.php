<?php

namespace App\Livewire\QuestionBank;

use App\Models\Module;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ModuleList extends Component
{
    use WithPagination;
    public $search;
    public $modules = [];

    public function render()
    {
        $modules = Module::all();
        $users = User::all();
        return view('livewire.question-bank.module-list', ['modules' => $modules, 'users' => $users]);
    }


    //Delete Module
    public function delete($id)
    {
        try {
            /*
             Before Deleting a module first we must check if it is referenced in other tables
            */
            $isModuleUsed = DB::table('test_topics as tt')
                ->join('topics as t', 'tt.test_topic_set_id', '=', 't.id') //I have to cross check if test_topic_set_id or topic_id is used here
                ->where('t.module_id', $id)
                ->exists();
            if ($isModuleUsed) {
                //Set the module as disabled
                $module = Module::find($id);
                if ($module) {
                    $module->enabled = 0;
                    $module->save();

                    noty()
                        ->livewire()
                        ->addSuccess('Module disabled sccessfully!');
                }
            } else {
                $module = Module::find($id);
                if ($module) {
                    $module->delete();
                    noty()
                        ->livewire()
                        ->addSuccess('Module deleted sccessfully!');
                }
            }
        } catch (Exception $ex) {
            noty()
                ->livewire()
                ->addSuccess('Operation failed!' . $ex);
        }
    }
}
