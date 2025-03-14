<?php

namespace App\Livewire\Modals\DetailsModals;

use App\Models\Topic;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class TopicDetailsModal extends ModalComponent
{

    public $topicId; // ID of the topic to display
    public $topic; // Topic details

    public function mount($topicId)
    {
        $this->topicId = $topicId;
        //$this->topic = Topic::with('module')->find($topicId); // Fetch the topic details
        $this->topic = Cache::remember("topic_{$topicId}", 60, function () use ($topicId) {
            return Topic::with('module')->find($topicId) ?: null; // Return null if not found
        });
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
        return view('livewire.modals.details-modals.topic-details-modal');
    }
}
