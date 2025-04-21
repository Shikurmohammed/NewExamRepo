<?php

namespace App\Livewire\Modals\DeleteModals;

use App\Models\User;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteUserModal extends ModalComponent
{
    public $userId; // ID of the topic to delete
    public $message; // Confirmation message

    public function mount($userId, $message = 'Are you sure you want to delete this user?')
    {
        $this->userId = $userId;
        $this->message = $message;
    }
    public function delete()
    {
        try {
            $user = User::findOrFail($this->userId);
            $user->delete();
            noty()
                ->livewire()
                ->addSuccess("User deleted successfully!");
            $this->dispatch('refreshUserTable');
        } catch (\Throwable $th) {
            noty()
                ->livewire()
                ->addError('Operation failed!' . $th->getMessage());
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
