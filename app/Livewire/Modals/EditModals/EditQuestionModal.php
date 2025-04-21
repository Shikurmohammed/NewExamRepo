<?php

namespace App\Livewire\Modals\EditModals;

use App\Models\Module;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

/**
 * File Name: EditQuestionModal
 * Description: Edits questions, ensures the existing data are safe
 * Author: Shikur Mohammed, Software Engineer.
 * (c) Copy right: Awash Bank, All rights reserved.
 *
 */
class EditQuestionModal extends ModalComponent
{

    #[Rule('required')]
    public $topic_id;
    #[Rule('required',)]
    public $questionId;
    #[Rule('required')]
    public $description;
    public $explanation;
    #[Rule('required')]
    public $type;
    #[Rule('required')]
    public $difficulty;
    public $position;
    public $timer;
    public $enabled = false;
    public $fullscreen = false;
    public $inline_answers = false;
    public $auto_next = false;
    public $positions = [];

    public function mount($questionId)
    {
        // Fetch the module data based on $moduleId
        $question = Question::with('topic')->find($questionId);

        if (!$question) {
            noty()->livewire()
                ->addError('Topic not found!');
            return;
        }
        $this->topic_id = $question->topic_id;
        $this->description = $question->description;
        $this->explanation = $question->explanation;
        $this->type = $question->type;
        $this->difficulty = $question->difficulty;
        $this->position = $question->position;
        $this->timer = $question->timer;
        $this->enabled = (bool) $question->enabled;
        $this->fullscreen = (bool)$question->fullscreen;
        $this->inline_answers = (bool)$question->inline_answers;
        $this->auto_next = (bool)$question->auto_next;
        //$this->createdBy = $topic->user->name;
    }
    //save changes
    public function save()
    {
        $this->validate();
        try {
            $question = Question::find($this->questionId);
            if (!$question) {
                noty()->livewire()->addError('Question not found!');
                return;
            }

            // Check if topic exists before assigning
            if (!Topic::where('id', $this->topic_id)->exists()) {
                noty()->livewire()->addError('Invalid topic selected!');
                return;
            }

            // Prevent updates if the question is in use
            if (DB::table('test_logs')->where('question_id', $this->questionId)->exists()) {
                noty()->livewire()->addWarning('Sorry, this question is in use!');
                return;
            }

            DB::transaction(function () use ($question) {
                // Update fields
                $question->topic_id = (int) $this->topic_id;
                $question->description = $this->description;
                $question->explanation = $this->explanation;
                $question->enabled = $this->enabled ?? 1;
                $question->type = $this->type ?? 1;
                $question->difficulty = $this->difficulty ?? 1;
                $question->timer = $this->timer;
                $question->fullscreen = $this->fullscreen ?? 1;
                $question->inline_answers = $this->inline_answers ?? 1;
                $question->auto_next = $this->auto_next ?? 1;
                $question->created_by = Auth::user()?->name ?? 'Unknown';

                // Update position logic
                if (is_null($this->position)) {
                    $question->position = Question::where('topic_id', $this->topic_id)->max('position') + 1 ?? 1;
                } else {
                    Question::where('topic_id', $this->topic_id)
                        ->where('position', '>=', $this->position)
                        ->increment('position');

                    $question->position = $this->position;
                }

                $question->update();
            });

            noty()->livewire()->addSuccess('Question updated successfully!');
            $this->reset();
            $this->dispatch('questionUpdated');
            $this->closeModal();
        } catch (\Throwable $th) {
            noty()->livewire()->addError('Error occurred: ' . $th->getMessage());
        }
    }

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
        //dd($this->positions);
        // var_dump($this->positions);

    }

    #[Computed()]
    public function topics()
    {
        return Topic::all();
    }
    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    public static function modalSize(): string
    {
        return '7xl';
    }

    // public static function modalMaxWidth(): string
    // {
    //     return '7xl';
    // }
    public function render()
    {
        return view('livewire.modals.edit-modals.edit-question-modal');
    }
}
