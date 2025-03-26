<?php

namespace App\Livewire\Modals\DeleteModals;

use App\Models\Test;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteTestModal extends ModalComponent
{
    public $testId; // ID of the Group to display
    public $message; // message

    public function mount($testId, $message = 'Are you sure you want to delete this test?')
    {
        $this->testId = $testId;
        $this->message = $message;
    }

    public function delete()
    {
        try {
            //To be implemented later
            //$isTestUsed = DB::table('test_logs')->where('test_id', $this->testId)->exists();
            $test = Test::find($this->testId);
            $test->delete();
            // Close the modal
            $this->closeModal();
            // Emit an event to refresh the Test table
            noty()
                ->livewire()
                ->addSuccess('Test deleted successfully!');
            $this->dispatch('refreshTestTable');
        } catch (Exception $ex) {
            noty()
                ->livewire()
                ->addError('Operation failed!' . $ex);
        }
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
