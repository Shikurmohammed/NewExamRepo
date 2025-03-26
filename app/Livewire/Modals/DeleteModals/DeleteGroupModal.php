<?php

namespace App\Livewire\Modals\DeleteModals;

use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteGroupModal extends ModalComponent
{
    public $groupId; // ID of the Group to display
    public $message; // message

    public function mount($groupId, $message = 'Are you sure you want to delete this item?')
    {
        $this->groupId = $groupId;
        $this->message = $message;
    }
    public function delete()
    {
        try {
            // Fetch the answer once
            $group = Group::find($this->groupId);
            if (!$group) {
                noty()->livewire()->addError('Group not found!');
                return;
            }

            // Check if the answer is used

            $isGroupUsed = DB::table('test_groups')->where('group_id', $this->groupId)->exists();
            if ($isGroupUsed) {
                noty()->livewire()->addSuccess('Group in-use!');
                return;
            }

            $group->delete();
            noty()->livewire()->addSuccess('Group removed successfully!');
            $this->dispatch('refreshGroupTable');
        } catch (\Throwable $th) {
            //throw $th;
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
