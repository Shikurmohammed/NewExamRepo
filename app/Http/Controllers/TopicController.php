<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

use function Flasher\Noty\Prime\noty;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       //Retrieve Toipc Data
       $topic_data = Topic::all();
       $module_data = Module::all();
       $users = User::all();
       return view('pages.shared.topic.view', compact('topic_data','users', 'module_data'));
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
        //
        $topic = new Topic();

        $topic->module_id = $request->module_id;
        $topic->name = $request->topic_name;
        $topic->description = $request->description;
        $topic->enabled = $request->enabled;
        //Save
       $topic->save();
        noty()->success('Topic created successfully!');

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
    }

    //Get Topic by module id
    public function getTopicsByModule(Request $request)
    {
        $moduleId = $request->module_id;
        // Fetch topics related to the selected module
        $topics = Topic::where('module_id', $moduleId)->get();
        // Return topics as JSON
        return response()->json($topics);
    }
}
