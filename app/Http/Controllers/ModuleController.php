<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Topic;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Flasher\Noty\Prime\NotyInterface;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

use function Flasher\Noty\Prime\noty;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Retrieve Module Data
        $module_data = Module::paginate(8);
        $users = User::all();

        return view('pages.shared.module.view', compact('module_data', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Save module data to database
        try {
            $module = new Module;
            $module->name = $request->module_name;
            $user_id = Auth::user()->id;
            $module->user_id = $user_id;
            $module->enabled = $request->enabled;
            $countModuleByname = Module::where('name', $request->module_name)->count();
            if ($countModuleByname < 1) {
                $module->save();
                noty()->success('Module added successfuly!');
            } else {
                noty()->error("Module already exist!");
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
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
        try {
            /*
             Before Deleting a module first we must check if it is referenced in other tables
            */
            $isModuleUsed = DB::table('test_topics as tt')
            ->join('topics as t', 'tt.test_topic_set_id', '=', 't.id')
            ->where('t.module_id', $id)
            ->exists();
            if ($isModuleUsed) {
                //Set the module as disabled
                $module = Module::find($id);
                if ($module) {
                    $module->enabled = 0;
                    $module->save();
                    noty()->success("Module disabled sccessfully!");
                }
            } else {
                $module = Module::find($id);
                if ($module) {
                   // $module->delete();
                    noty()->success("Module deleted successfuly!");
                }
            }

            return redirect()->back();
        } catch (Exception $ex) {
            noty()->error("Operation failed!" . $ex);
        }
    }
}
