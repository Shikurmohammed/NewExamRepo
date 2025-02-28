<?php

namespace App\Livewire\Modals;

use App\Models\Answer;
use App\Models\Module;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class AnswerModal extends Modal
{
    public $module_id;
    #[Rule('required')]
    public $question_id;
    #[Rule('required')]
    public $topic_id;
    #[Rule('required')]
    public $description;
    public $explanation;
    public $enabled = true;
    public $position;
    public $is_right;
    public $keyboard_key;
    public $positions = [];


    #[Computed()]
    public function modules()
    {
        return Module::all();
    }
    #[Computed()]
    public function topics()
    {
        return Topic::all();
    }
    #[Computed()]
    public function questions()
    {
        return Question::all();
    }

    public function updatedQuestionId($value)
    {
        $this->fetchPositions($value);
    }

    public function fetchPositions($question_id)
    {
        $this->positions = Answer::where('question_id', $question_id)
            ->orderBy('position')
            ->pluck('position')
            ->toArray();

        // Add an option for inserting at the end
        $maxPosition = count($this->positions) > 0 ? max($this->positions) + 1 : 1;
        $this->positions[] = $maxPosition; // Add the option to insert at the end
    }
    //Save to DB
    public function create()
    {
        $this->validate(); //Validate before logic starts
        try {
            $isAnswerExists = Answer::where('question_id', $this->question_id)->where('description', $this->description)->exists();
            if ($isAnswerExists) {
                noty()
                    ->livewire()
                    ->addWarning('Answer already exists!');
                return;
            }
            $answer = new Answer();
            $answer->question_id = $this->question_id;
            $answer->description = $this->description;
            $answer->explanation = $this->explanation;
            $answer->is_right = $this->is_right ? 1 : 0;
            $answer->enabled = $this->enabled ? 1 : 0;
            // $answer->created_by = Auth::user()->name;
            //Determine position
            if (is_null($this->position)) {
                //Fetch maximum position for the current question
                $maxPosition = Answer::where('question_id', $this->question_id)->max('position');
                $answer->position = $maxPosition ? $maxPosition + 1 : 1; //Default is 1 if the question has no answer
            } else {
                $answer->position = $this->position; //Use the one provided from a from.
            }

            /*
                  Before saving new answer, increament position of existing
                  answers having position greater than the current answer's position
            */
            DB::transaction(function () use ($answer) {
                $question_id = $this->question_id;
                $position = $this->position;
                $sql_update_position = "UPDATE answers set position = position + 1 where question_id = :question_id AND position >= :position";
                DB::update($sql_update_position, ['question_id' => $question_id, 'position' => $position]);
                $answer->save();
            });

            noty()
                ->livewire()
                ->addSuccess('Answer added successfully!');
            $this->reset();
        } catch (\Throwable $th) {
            noty()
                ->livewire()
                ->addError('Error occured:: ' . $th->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.modals.answer-modal');
    }
}
