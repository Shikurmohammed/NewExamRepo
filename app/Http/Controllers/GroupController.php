<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Module;
use Illuminate\Http\Request;

use function Flasher\Noty\Prime\noty;
use function Flasher\Notyf\Prime\notyf;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $group_data = Group::all();
        return view('pages.shared.group.view', compact('group_data'));
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
        try {
            $group = new Group();
            $is_group_name_exist = Group::where('name', $request->group_name)->count();
            if ($is_group_name_exist > 0) {
                noty()->error("Attention, Group name already exist!");
            } else {
                $group->name = $request->group_name;
                $group->save();
                noty()->success("Group created successfully!");
            }
        } catch (\Throwable $th) {
            noty()->error("Failed to create group!" . $th->getMessage());
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $group = Group::findOrFail($id);
        return view('shared.group.edit_group', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $is_group_name_exist = Group::where('name', $request->group_name)->count();
        if ($is_group_name_exist > 0) {
            noty()->error("Attention, Group name already exist!");
        } else {
            $group->name = $request->group_name;
            $group->save();
            noty()->success("Group updated successfully!");
        }
        return redirect('/view_group');
        //return redirect()->route('view.group');//Used if the route has a name
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        if ($group) {
            $group->delete();
            noty()->success("Group removed successfull!");
        } else
            noty()->error("There is no group with the id :: " . $id . " !");


        return redirect()->back();
    }
}
