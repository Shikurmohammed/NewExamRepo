<?php

namespace App\Livewire\Modals\DetailsModals;

use App\Models\User;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class UserDetailsModal extends ModalComponent
{

    public $userId; // ID of the topic to display
    public $user; // Topic details

    public function mount($userId)
    {
        $this->userId = $userId;
        //$this->topic = Topic::with('module')->find($topicId); // Fetch the topic details
        $this->user = User::find($userId);
    }

    public static function closeModalOnClickAway(): bool
    {
        return true;
    }

    public static function modalSize(): string
    {
        return 'lg';
    }

    public function render()
    {
        return view('livewire.modals.details-modals.user-details-modal');
    }
}
