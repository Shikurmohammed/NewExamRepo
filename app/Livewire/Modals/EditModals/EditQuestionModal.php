<?php

namespace App\Livewire\Modals\EditModals;

use App\Models\Module;
use App\Models\Topic;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class EditQuestionModal extends ModalComponent
{

    #[Url()]
    public $module_id;
    // #[Rule('required')]
    public $topic_id;
    #[Rule('required')]
    public $description;
    public $explanation;
    public $enabled;
    public $type;
    public $difficulty;
    public $position;
    public $timer;
    public $fullscreen;
    public $inline_answers;
    public $auto_next;
    public $positions = [];
    //save changes
    public function save()
    {


        // Emit an event to refresh the parent component (optional)
        $this->dispatch('moduleUpdated');

        // Close the modal
        $this->closeModal();
    }

    #[Computed()]
    public function modules()
    {
        return Module::all();
    }
    #[Computed()]
    public function topics()
    {
        return Topic::all();
    }
    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    public static function modalSize(): string
    {
        return '7xl';
    }

    // public static function modalMaxWidth(): string
    // {
    //     return '7xl';
    // }
    public function render()
    {
        return view('livewire.modals.edit-modals.edit-question-modal');
    }
}
