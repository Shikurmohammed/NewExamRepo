<?php

namespace App\Livewire\Modals\DeleteModals;

use App\Models\Answer;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteANswerModal extends ModalComponent
{
    public $answerId; // ID of the answer to delete
    public $message; // Confirmation message
    public function mount($answerId, $message = 'Are you sure you want to delete this item?')
    {
        $this->answerId = $answerId;
        $this->message = $message;
    }
    public function delete()
    {
        DB::beginTransaction();
        try {
            // Fetch the answer once
            $answer = Answer::find($this->answerId);
            if (!$answer) {
                noty()->livewire()->addError('Answer not found!');
                return;
            }
            // Check if the answer is used
            $isAnswerUsed = DB::table('answer_logs')->where('answer_id', $this->answerId)->exists();

            if ($isAnswerUsed) {
                // Disable the answer instead of deleting
                $answer->enabled = 0;
                $answer->save();

                DB::commit(); // Ensure the transaction is committed
                noty()->livewire()->addSuccess('Answer in-use!');
            } else {
                // Store these values before deleting
                $answer_position = $answer->answer_position;
                $answer_question_id = $answer->answer_question_id;
                // Delete the answer
                $answer->delete();
                noty()->livewire()->addSuccess('Answer deleted successfully!');

                // Adjust positions only if necessary
                if ($answer_position > 0) {
                    Answer::where('answer_question_id', $answer_question_id)
                        ->where('answer_position', '>', $answer_position)
                        ->decrement('answer_position');
                }

                DB::commit();
            }

            // Close the modal
            $this->closeModal();

            // Emit event to refresh Livewire components
            $this->dispatch('refreshAnswerTable');
        } catch (\Exception $ex) {
            DB::rollback();
            noty()->livewire()->addError('Operation failed! ' . $ex->getMessage());
        }
    }

    public static function closeModalOnClickAway(): bool
    {
        return true;
    }

    public static function modalMaxWidth(): string
    {
        return 'sm';
    }
    public function render()
    {
        return view('livewire.modals.delete-modals.confirm-delete');
    }
}
