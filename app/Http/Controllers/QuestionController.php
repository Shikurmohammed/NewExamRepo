<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Http\Request;

use function Flasher\Noty\Prime\noty;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $question_data = Question::all();
        $module_data = Module::all();
        $topic_data = Topic::all();
        // $topic_data_with = Topic::where('module');


        return view('shared.question.view_question', compact('question_data', 'module_data', 'topic_data'));
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
            $question = new Question();

            $question->topic_id= $request->topic_id;
            $question->description= $request->question_name;
            $question->explanation= $request->explanation;
            $question->enabled= $request->enabled;

            $question->type= $request->type;
            $question->difficulty= $request->difficulty_level;
            $question->position= $request->position;
            $question->timer= $request->timer;

            $question->fullscreen= $request->isFullScreen;
            $question->inline_answers= $request->isInlineAnswer;
            $question->auto_next= $request->isAutoNext;
           //save
           $question->save();
           noty()->success("Question addedd successfully!");
         return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
        }

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

    //Get Question by topic id
    public function getQuestionsByTopic(Request $request)
    {
        $topicId = $request->topic_id;
        // Fetch questions related to the selected topic
        $questions = Question::where('topic_id', $topicId)->get();
        // Return topics as JSON
        return response()->json($questions);
    }
}
