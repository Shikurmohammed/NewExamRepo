<?php

namespace App\Livewire\Modals;

use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class GroupModal extends Modal
{

    #[Rule('required|unique:groups,name')]
    public $name;
    public $created_by;

    public function mount()
    {
        $this->created_by = Auth::user()->email;
    }

    public array $messages = [
        'name.required' => 'Group name is required.',
        'name.unique' => 'Group already exists.',
    ];
    public function createGroup()
    {
        $this->validate();
        try {
            $group = new Group();
            $group->name = $this->name;
            $group->created_by = $this->created_by;
            $group->created_at = Carbon::now();
            $isGroupExists = Group::where('name', $this->name)->exists();
            if ($isGroupExists) {
                noty()->livewire()->addError("Group already Exists!");
                return;
            }
            $group->save();
            noty()->livewire()->addSuccess("Group added successfully!");
            $this->dispatch('refreshGroupTable');
        } catch (\Throwable $th) {
            noty()->livewire()->addError("Error: " . $th->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.modals.group-modal');
    }
}
