<?php

namespace App\Livewire\Modals\DetailsModals;

use App\Models\Question;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class QuestionDetailsModal extends ModalComponent
{

    public $questionId; // ID of the topic to display
    public $question; // Topic details

    public function mount($questionId)
    {
        $this->questionId = $questionId;
        $this->question = Question::with('topic')->find($questionId); // Fetch the topic details
    }

    public static function closeModalOnClickAway(): bool
    {
        return true;
    }

    public static function modalSize(): string
    {
        return 'lg'; //
    }

    public function render()
    {
        return view('livewire.modals.details-modals.question-details-modal');
    }
}
