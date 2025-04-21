<?php

namespace App\Livewire\Modals\DetailsModals;

use App\Models\Answer;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class AnswerDetailsModal extends ModalComponent
{
    public $answerId; // ID of the answer to delete
    public $answer;
    public function mount($answerId)
    {
        $this->answerId = $answerId;
        $this->answer = Answer::with('question')->find($answerId);
    }
    public function render()
    {
        return view('livewire.modals.details-modals.answer-details-modal');
    }
}
