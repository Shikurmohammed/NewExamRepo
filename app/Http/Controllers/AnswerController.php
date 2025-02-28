<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Module;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Http\Request;

use function Flasher\Noty\Prime\noty;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $answer_data = Answer::all();

        $module_data = Module::all();
        $topic_data = Topic::all();
        //This line will fetch data from questions and answers tables
        //based on the function we specified in the Models(Question and Answer)
        $questions = Question::with('Answers')->get();

        return view('shared.answer.view_answer', compact('answer_data', 'module_data', 'topic_data', 'questions'));
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
        $answer = new Answer();
        $answer->question_id = $request->question_id;
        $answer->description = $request->description;
        $answer->explanation = $request->explanation;
        $answer->position = $request->position;

        $answer->is_right = $request->is_right;
        $answer->enabled = $request->enabled;
        $answer->keyboard_key = $request->keyboard_key;
        //check answer's unquness
        $uniquesAnswer = Answer::where('description', $request->description)->count();//May be we can filter using topic_id and description

        if ($uniquesAnswer  < 1) {
            $position = $request->position ?: 0;
            if ($position <= 0) { //If position not passed from the form
                //Get the max position from question table
                $max_position = Answer::max('position') ? Answer::max('position') : 0; //means Select max(position) from questions;
                $position =  $max_position + 1;
            } else {
                //If position is passed from a form and greater than 0,
                //then shift the existing questions to make room for the new question.
                Answer::where('position', '>=', $position)->increment('position');
            }
            //Insert the question at specified position,.ie if passed from form we will insert at the position passed from the form,
            //otherwise,we insert at max(postion)+1 at the end.
            $answer->position = $position;
            $answer->save();
            noty()->success("Answer addedd successfully" . "on position:" . $position);
        }
        else{
            noty()->error("Duplicate answer for same question!");
        }
        return redirect()->back();
    }

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
    //Reorder Answers
    public function swap() {}
}
