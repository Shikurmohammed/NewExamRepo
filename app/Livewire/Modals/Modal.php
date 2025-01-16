<?php

namespace App\Livewire\Modals;

use Livewire\Component;

class Modal extends Component
{
    public $isModalOpen = false;

    public function openModal()
    {
        sleep(2);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {

        $this->isModalOpen = false;
    }

}
