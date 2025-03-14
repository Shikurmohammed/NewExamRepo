<?php

namespace App\Livewire\Modals\DetailsModals;

use LivewireUI\Modal\ModalComponent;
use App\Models\Module; // Import your Module model

class ModuleDetailsModal extends ModalComponent
{


    public $moduleId; // ID of the module to display
    public $module; // Module details

    /**
     * Mount the component.
     *
     * @param int $moduleId
     */
    public function mount($moduleId)
    {
        $this->moduleId = $moduleId;
        $this->module = Module::find($moduleId); // Fetch the module details
    }

    /**
     * Determine if the modal should close when clicking outside of it.
     *
     * @return bool
     */
    public static function closeModalOnClickAway(): bool
    {
        return true; // Set to false to disable closing on outside clicks
    }

    /**
     * Set the modal size.
     *
     * @return string
     */
    public static function modalSize(): string
    {
        return 'lg'; // Options: sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl
    }

    /**
     * Render the modal view.
     *
     * @return \Illuminate\Contracts\View\View
     */ public function render()
    {
        return view('livewire.modals.details-modals.module-details-modal');
    }
}
