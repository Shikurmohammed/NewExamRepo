<?php

namespace App\Livewire\Modals\DetailsModals;

use App\Models\Group;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class GroupDetailsModal extends ModalComponent
{

    public $groupId; // ID of the Group to display
    public $group; // Group details

    public function mount($groupId)
    {
        $this->groupId = $groupId;
        $this->group = Group::find($groupId);
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
        return view('livewire.modals.details-modals.group-details-modal');
    }
}
