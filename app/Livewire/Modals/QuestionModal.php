<?php

namespace App\Livewire\Modals;

use App\Models\Module;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionImport;

class QuestionModal extends Modal
{

    //Importing question from csv,excel,...
    use WithFileUploads;
    public $file;

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

    public $positions = [];

    public function updatedTopicId($value)
    {
        $this->fetchPositions($value);
    }

    public function fetchPositions($topic_id)
    {
        $this->positions = Question::where('topic_id', $topic_id)
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
            $isQuestionExists = Question::where('description', $this->description)->exists();
            if ($isQuestionExists) {
                noty()
                    ->livewire()
                    ->addWarning('Question already exists!');
                return;
            }
            $question = new Question();
            $question->topic_id = $this->topic_id ?? 1; //Later modify it
            $question->description = $this->description;
            $question->explanation = $this->explanation;
            $question->enabled = $this->enabled ?? 1;

            $question->type = $this->type ?? 1;
            $question->difficulty = $this->difficulty ?? 1;
            $question->timer = $this->timer;

            $question->fullscreen = $this->fullscreen ?? 1;
            $question->inline_answers = $this->inline_answers ?? 1;
            $question->auto_next = $this->auto_next ?? 1;
            $question->created_by = Auth::user()->name;

            //Determine position
            if (is_null($this->position)) {
                //Fetch maximum position for the current topic
                $maxPosition = Question::where('topic_id', $this->topic_id)->max('position');
                $question->position = $maxPosition ? $maxPosition + 1 : 1; //Default is 1 if the topic has no question
            } else {
                $question->position = $this->position; //Use the one provided from a from.
            }

            /*Before saving new question, increament position of existing
              questions having position greater than the current question's position
            */
            DB::transaction(function () use ($question) {
                $topic_id = $this->topic_id;
                $position = $this->position;
                $sql_update_position = "UPDATE questions set position = position + 1 where topic_id = :topic_id AND position >= :position";
                DB::update($sql_update_position, ['topic_id' => $topic_id, 'position' => $position]);
                $question->save();
            });

            noty()
                ->livewire()
                ->addSuccess('Question added successfully!');
            $this->reset();
        } catch (\Throwable $th) {
            noty()
                ->livewire()
                ->addError('Error occured:: ' . $th->getMessage());
        }
    }
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

    public function render()
    {

        return view('livewire.modals.question-modal', ['selectedIds' => $this->module_id]);
    }


    public function importQuestions()
    {

        $this->validate([
            'file' => 'required|mimes:csv,xlsx,xls |max:2048',
        ], [
            'file.required' => 'Please upload a file.',
            'file.mimes' => 'The file must be a CSV, XLSX, or XLS file.',
        ]);
        $path = $this->file->store('questions');
        try {
            // Excel::import(new QuestionImport, $this->file);
            $data = Excel::import(new QuestionImport, $path);
            $this->file = null;
        } catch (\Exception $e) {
            noty()
                ->livewire()
                ->addError('Import Failed!' . $e->getMessage());
        }
    }
}
