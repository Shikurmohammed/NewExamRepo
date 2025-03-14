<?php

namespace App\Livewire\Modals\DeleteModals;

use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteTopicModal extends ModalComponent
{
    public $topicId; // ID of the topic to delete
    public $message; // Confirmation message

    public function mount($topicId, $message = 'Are you sure you want to delete this item?')
    {
        $this->topicId = $topicId;
        $this->message = $message;
    }


    //Delete topic
    public function delete()
    {
        try {
            $topicId = $this->topicId;
            /*
             Before deleting a topic first we must check if it is referenced in other tables, for example questions and  test_topics
            */
            $isTopicUsed = DB::table('questions')->where('topic_id',   $topicId)
                ->orWhereExists(function ($query) use ($topicId) {
                    $query->select(DB::raw(1))
                        ->from('test_topics')
                        ->where('topic_id', $topicId);
                })->exists();
            if ($isTopicUsed) {
                $sql1 = "UPDATE topics set enabled =0 where id =$topicId";
                DB::query($sql1);
                noty()
                    ->livewire()
                    ->addWarning("The topic with ID::" . $topicId . " is currntly in use! ");
                return;
            } else {
                $topic = Topic::find($topicId);
                if ($topic) {
                    $topic->delete();
                    noty()
                        ->livewire()
                        ->addSuccess("Topic deleted successfully!");
                }
            }
            $this->closeModal();
            // Emit an event to refresh the parent component
            $this->dispatch('topicDeleted');
        } catch (\Exception $ex) {
            dd($ex);
            noty()
                ->livewire()
                ->addError('Operation failed!' . $ex);
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
        return view('livewire.modals.delete-modals.confirm-delete');
    }
}
