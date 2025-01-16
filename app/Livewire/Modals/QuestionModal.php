<?php

namespace App\Livewire\Modals;

use App\Models\Module;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

class QuestionModal extends Modal
{
    #[Url()]
    public $module_id;
    // #[Rule('required')]
    public $topic_id;
    #[Rule('required')]
    public $description;
    public $explanation;
    public $enabled;
    public $type;
    public $difficulty;
    public $position;
    public $timer;
    public $fullscreen;
    public $inline_answers;
    public $auto_next;

    #[Computed()]
    public function modules()
    {
        return Module::all();
    }
    #[Computed()]
    public function topics()
    {
        return Topic::where('module_id', $this->module_id)->get();
    }

    //Save to DB
    public function create()
    {
        $this->validate();//Validate before logic starts
        try {
            $isQuestionExists = Question::where('description', $this->description)->exists();
            $question = new Question();
            $question->topic_id = $this->topic_id ?? 1; //Later modify it
            $question->description = $this->description;
            $question->explanation = $this->explanation;
            $question->enabled = $this->enabled ?? 1;

            $question->type = $this->type ?? 1;
            $question->difficulty = $this->difficulty ?? 1;
            $question->position = $this->position ?? 1;
            $question->timer = $this->timer;

            $question->fullscreen = $this->fullscreen ?? 1;
            $question->inline_answers = $this->inline_answers ?? 1;
            $question->auto_next = $this->auto_next ?? 1;

            $question->created_by = Auth::user()->name;
            //save
            if (!$isQuestionExists) {
                //   $question->save();
                noty()
                    ->livewire()
                    ->addSuccess('Question added successfully!');
                $this->reset();
            } else {

                noty()
                    ->livewire()
                    ->addWarning('Question already exists!');
            }
        } catch (\Throwable $th) {
            noty()
                ->livewire()
                ->addError('Error occured:: '.$th->getMessage());
        }
    }

    public function render()
    {

        return view('livewire.modals.question-modal', ['selectedIds' => $this->module_id]);
    }
}
