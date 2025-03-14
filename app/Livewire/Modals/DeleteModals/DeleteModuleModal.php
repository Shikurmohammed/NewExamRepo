<?php

namespace App\Livewire\Modals\DeleteModals;

use App\Models\Module;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteModuleModal extends ModalComponent
{
    public $moduleId; // ID of the module to delete
    public $message; // Confirmation message

    /**
     * Mount the component.
     *
     * @param int $moduleId
     * @param string $message
     */
    public function mount($moduleId, $message = 'Are you sure you want to delete this item?')
    {
        $this->moduleId = $moduleId;
        $this->message = $message;
    }

    /**
     * Delete the module.
     */
    public function delete1()
    {
        // Delete the module
        Module::find($this->moduleId)->delete();

        // Close the modal
        $this->closeModal();

        // Emit an event to refresh the parent component
        $this->dispatch('moduleDeleted');
        noty()->livewire()
            ->addWarning('Module removed successfully!');
    }
    public function delete()
    {
        try {
            /*
             Before Deleting a module first we must check if it is referenced in other tables
            */
            // $isModuleUsed = DB::table('test_topics as tt')
            //     ->join('topics as t', 'tt.test_topic_set_id', '=', 't.id')
            //     ->where('t.module_id', $this->moduleId)
            //     ->exists();
            $isModuleUsed = DB::table('topics')->where('module_id', $this->moduleId)->exists();
            if ($isModuleUsed) {
                //Set the module as disabled
                $module = Module::find($this->moduleId);
                if ($module) {
                    $module->enabled = 0;
                    $module->save();

                    noty()
                        ->livewire()
                        ->addSuccess('Module disabled sccessfully!');
                }
            } else {
                $module = Module::find($this->moduleId);
                if ($module) {
                    $module->delete();
                    noty()
                        ->livewire()
                        ->addSuccess('Module deleted sccessfully!');
                }
            }
            // Close the modal
            $this->closeModal();
            // Emit an event to refresh the parent component
            $this->dispatch('moduleDeleted');
        } catch (Exception $ex) {
            noty()
                ->livewire()
                ->addSuccess('Operation failed!' . $ex);
        }
    }
    public static function closeModalOnClickAway(): bool
    {
        return true;
    }

    public static function modalSize(): string
    {
        return 'sm';
    }

    public function render()
    {
        return view('livewire.modals.delete-modals.delete-module-modal');
    }
}
