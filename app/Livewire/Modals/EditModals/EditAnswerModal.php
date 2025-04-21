<?php

namespace App\Livewire\Modals\EditModals;

use App\Models\Answer;
use App\Models\Module;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class EditAnswerModal extends ModalComponent
{

    public $module_id;
    #[Rule('required')]
    public $question_id;
    #[Rule('required')]
    public $topic_id;
    #[Rule('required')]
    public $description;
    public $explanation;
    public $enabled;
    public $position;
    public $is_right;
    public $keyboard_key;
    public $positions = [];
    public $answerId;
    public $answer;
    public function mount($answerId)
    {
        // Fetch the module data based on $moduleId
        $answer = Answer::with('question')->find($answerId);
        if (!$answer) {
            noty()->livewire()
                ->addError('Module not found!');
            return;
        }
        $this->answerId = $answerId; //$answer->id;
        $this->question_id = $answer->question_id;
        $this->description = $answer->description;
        $this->explanation = $answer->explanation;
        $this->enabled = (bool)$answer->enabled;
        $this->position = (bool)$answer->position;
        $this->is_right = (bool) $answer->is_right;
        $this->keyboard_key = (bool)$answer->keyboard_key;
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

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $answer = Answer::with('question')->find($this->answerId);

            // Get previous position
            $prev_position = $this->answer->position ?? 0;

            // If answer is disabled, reset position
            $new_position = $this->enabled ? ($this->position ?? $prev_position) : 0;

            // Check for duplicate answer
            $exists = Answer::where('description', $this->description)
                ->where('question_id', $this->question_id)
                ->where('id', '!=', $answer->id)
                ->exists();

            if ($exists) {
                noty()->livewire()->addWarning('Duplicate answer detected.');
                return;
            }

            // Handle position shifting logic
            if ($prev_position !== $new_position) {
                if ($new_position > 0) {
                    Answer::where('question_id', $this->question_id)
                        ->where('position', $new_position)
                        ->update(['position' => $prev_position]);
                } else {
                    Answer::where('question_id', $this->question_id)
                        ->where('position', '>', $prev_position)
                        ->decrement('position');
                }
            }

            // Update the answer
            $answer->update([
                'question_id' => $this->question_id,
                'description' => $this->description,
                'explanation' => $this->explanation,
                'is_right' => $this->is_right,
                'enabled' => $this->enabled,
                'position' => $new_position,
                'keyboard_key' => $this->keyboard_key,
            ]);

            DB::commit();
            noty()->livewire()->addSuccess('Answer updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            noty()->livewire()->addError('An error occurred while updating the answer.' . $e->getMessage());
        }
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    public static function modalSize(): string
    {
        return '6xl';
    }
    public function render()
    {
        return view('livewire.modals.edit-modals.edit-answer-modal');
    }
}
