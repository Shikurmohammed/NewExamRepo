<?php

namespace App\Livewire\Modals\DetailsModals;

use App\Models\Test;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class TestDetailsModal extends ModalComponent
{
    public $testId; // ID of the test to display
    public $test; // Test details

    public function mount($testId)
    {
        $this->testId = $testId;
        $this->test = Test::find($testId);
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    public static function modalSize(): string
    {
        return '7xl';
    }
    public function render()
    {
        return view('livewire.modals.details-modals.test-details-modal');
    }
}
