<?php

namespace App\Livewire\Modals\DeleteModals;

use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteQuestionModal extends ModalComponent
{
    public $questionId; // ID of the module to delete
    public $message; // Confirmation message

    /**
     * Mount the component.
     *
     * @param int $moduleId
     * @param string $message
     */
    public function mount($questionId, $message = 'Are you sure you want to delete this item?')
    {
        $this->questionId = $questionId;
        $this->message = $message;
    }


    public function delete()
    {
        try {
            $question = Question::find($this->questionId);
            if (!$question) {
                noty()->livewire()->addError('Question not found!');
                return;
            }

            // Check if question is used in test_logs and answers table, for now am checking for test_logs only,
            // If you prefer for both, you can uncomment the query below.
            // $isQuestionUsed =  DB::table('answers')->where('question_id',   $questionId)
            //     ->orWhereExists(function ($query) use ($questionId) {
            //         $query->select(DB::raw(1))
            //             ->from('test_logs')
            //             ->where('question_id', $questionId);
            //     })->exists();
            $isQuestionUsed = DB::table('test_logs')->where('question_id', $this->questionId)->exists();
            if ($isQuestionUsed) {
                noty()->livewire()->addWarning('Cannot delete! This question is in use.');
                return;
            }

            DB::transaction(function () use ($question) {
                // Adjust positions before deleting
                Question::where('topic_id', $question->topic_id)
                    ->where('position', '>', $question->position)
                    ->decrement('position');

                // Delete the question
                $question->delete();
            });

            noty()->livewire()->addSuccess('Question deleted successfully!');
            $this->dispatch('questionDeleted'); // Refresh UI
        } catch (\Throwable $th) {
            noty()->livewire()->addError('Error occurred: ' . $th->getMessage());
        }
    }
    public static function closeModalOnClickAway(): bool
    {
        return true;
    }

    public static function modalSize(): string
    {
        return 'sm';
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
