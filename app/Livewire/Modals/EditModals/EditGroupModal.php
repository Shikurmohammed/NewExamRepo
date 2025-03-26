<?php

namespace App\Livewire\Modals\EditModals;

use App\Models\Group;
use Carbon\Carbon;
use Livewire\Attributes\Rule;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class EditGroupModal extends ModalComponent
{

    #[Rule('required|unique:groups,name')]
    public $name;
    public $groupId;
    public $created_by;
    public array $messages = [
        'name.required' => 'Group name is required.',
        'name.unique' => 'Group already exists.',
    ];

    public function mount($groupId)
    {
        // Fetch the module data based on $moduleId
        $group = Group::find($groupId);
        if (!$group) {
            noty()->livewire()
                ->addError('Module not found!');
            return;
        }
        $this->groupId = $group->id;
        $this->name = $group->name;
        $this->created_by = $group->created_by;
    }


    public function updateGroup()
    {
        $this->validate();
        try {
            $group = Group::find($this->groupId);
            $group->name = $this->name;
            $group->created_by = $this->created_by;
            $group->updated_at = Carbon::now();
            $isGroupExists = Group::where('name', $this->name)->exists();
            if ($isGroupExists) {
                noty()->livewire()->addError("Group already Exists!");
                return;
            }
            $group->save();
            noty()->livewire()->addSuccess("Group updated successfully!");
            $this->dispatch('refreshGroupTable');
        } catch (\Throwable $th) {
            noty()->livewire()->addError("Error: " . $th->getMessage());
        }
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    public static function modalSize(): string
    {
        return '6xl';
    }

    public function render()
    {
        return view('livewire.modals.edit-modals.edit-group-modal');
    }
}
