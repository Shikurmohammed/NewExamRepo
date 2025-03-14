<?php

namespace App\Livewire\Modals\EditModals;

use App\Models\Module;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class EditTopicModal extends ModalComponent
{

    public $module_id;
    public $topic_name;
    public $topic_id;
    public $description;
    public $enabled = false;
    public $created_by = '';
    #[Computed()]
    public function modules()
    {
        return Module::all();
    }
    public function mount($topicId)
    {
        // Fetch the topic data based on $topic_id
        $topic = Topic::with('user')->find($topicId);

        if (!$topic) {
            noty()->livewire()
                ->addError('Topic not found!');
            return;
        }
        $this->topic_id = $topic->id;
        $this->topic_name = $topic->name;
        $this->description = $topic->description;
        $this->enabled = (bool)$topic->enabled;
        $this->created_by = $topic->user->name;
        $this->module_id = $topic->module_id;
    }
    protected function rules()
    {
        return [
            'topic_name' => 'required|string|max:255|unique:topics,name,' .
                $this->topic_id
        ];
    }
    public function save()
    {
        // Validate the input
        $this->validate();
        $topicId = $this->topic_id;
        $topic = Topic::find($topicId);
        //Check if topic is used in questions or test_topics table
        $isTopicUsed =  DB::table('questions')->where('topic_id',   $topicId)
            ->orWhereExists(function ($query) use ($topicId) {
                $query->select(DB::raw(1))
                    ->from('test_topics')
                    ->where('topic_id', $topicId);
            })->exists();
        if ($isTopicUsed) {
            noty()
                ->livewire()
                ->addWarning('Sorry, this topic is in use!');
            return;
        }
        // Update the topic within a transaction
        DB::transaction(function () use ($topic) {
            if ($topic) {
                // If the topic exists, update it
                $topic->update([
                    'name' => $this->topic_name,
                    'description' => $this->description,
                    'enabled' => $this->enabled,
                    'user_id' => Auth::user()->id,
                    'module_id' => $this->module_id
                ]);
                noty()
                    ->livewire()
                    ->addSuccess('Congratulations, topic updated successfully!');
            } else {
                // If the topic doesn't exist, create a new one (optional)
                Module::create([
                    'name' => $this->topic_name,
                    'description' => $this->description,
                    'enabled' => $this->enabled,
                    'user_id' => Auth::user()->id,
                    'module_id' => $this->module_id
                ]);
                noty()
                    ->livewire()
                    ->addSuccess('Congratulations, topic added successfully!');
            }
        });
        // Close the modal
        $this->closeModal();
        // Emit an event to refresh the parent component (optional)
        $this->dispatch('topicUpdated');
    }
    public $isModelDialogOpen = false;
    protected $listeners = ['openEditModuleModal' => 'isDialogOpen'];
    public function isDialogOpen($rowId)
    {
        $this->isModelDialogOpen = true;
        $this->mount($rowId);
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
        return view('livewire.modals.edit-modals.edit-topic-modal');
    }
}
