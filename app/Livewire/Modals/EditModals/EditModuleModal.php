<?php

namespace App\Livewire\Modals\EditModals;


use Livewire\Component;


use LivewireUI\Modal\ModalComponent;
use App\Models\Module; // Import your Module model
use Illuminate\Support\Facades\DB;

class EditModuleModal extends ModalComponent
{
    public $moduleId;
    public $name;
    public $enabled = false;
    public $createdBy = '';

    public function mount($moduleId)
    {
        // Fetch the module data based on $moduleId
        $module = Module::with('user')->find($moduleId);

        if (!$module) {
            noty()->livewire()
                ->addError('Module not found!');
            return;
        }
        $this->moduleId = $module->id;
        $this->name = $module->name;
        $this->enabled = (bool)$module->enabled;
        $this->createdBy = $module->user->name;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:modules,name,' .
                $this->moduleId //allow current modue Id
        ];
    }
    public function save()
    {
        // Validate the input
        $this->validate();
        // Get the module by ID (assuming $this->moduleId is set)
        $module = Module::find($this->moduleId);

        // Check if the module is in use in another table
        $isModuleUsed = DB::table('topics')->where('module_id', $this->moduleId)->exists();

        if ($isModuleUsed) {
            noty()
                ->livewire()
                ->addWarning('Sorry, this module is in use!');
            return;
        }

        // Update the module within a transaction
        DB::transaction(function () use ($module) {
            if ($module) {
                // If the module exists, update it
                $module->update([
                    'name' => $this->name,
                    'enabled' => $this->enabled,
                ]);
                noty()
                    ->livewire()
                    ->addSuccess('Congratulations, module updated successfully!');
            } else {
                // If the module doesn't exist, create a new one (optional)
                Module::create([
                    'name' => $this->name,
                    'enabled' => $this->enabled,
                ]);
                noty()
                    ->livewire()
                    ->addSuccess('Congratulations, module added successfully!');
            }
        });

        // Emit an event to refresh the parent component (optional)
        $this->dispatch('moduleUpdated');

        // Close the modal
        $this->closeModal();
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
        return view('livewire.modals.edit-modals.edit-module-modal');
    }
}
