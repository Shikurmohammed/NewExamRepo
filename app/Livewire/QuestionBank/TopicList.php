<?php

namespace App\Livewire\QuestionBank;

use App\Models\Module;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TopicList extends Component
{
    use WithPagination;
    public $search;
    public function render()
    {

        $topics = [];
        if (!$this->search) {

            $topics = Topic::latest()->paginate(5);
        } else {
            $this->search = $this->search;
            $topics = Topic::latest()->where('name', 'Like', "%{$this->search}%")->paginate(10);
        }
        return view(
            'livewire.question-bank.topic-list',
            ['topics' => $topics]
        );
    }
    //Delete topic
    public function delete($id)
    {
        try {
            /*
             Before deleting a topic first we must check if it is referenced in other tables, for example test_topics
            */

            $isTopicUsed = DB::table('test_topics')->where('test_topic_set_id',$id)->count();
            if ($isTopicUsed > 0) {
                $sql1 = "UPDATE topics set enabled =0 where id =$id";
                DB::query($sql1);
                noty()
                    ->livewire()
                    ->addWarning("The topic with ID::" . $id . " is currntly in use! ");
                    return;
            } else {
                $topic = Topic::find($id);
                if ($topic) {
                    $topic->delete();
                    noty()
                        ->livewire()
                        ->addSuccess("Topic deleted successfully!");
                }
            }
        } catch (\Exception $ex) {
            dd($ex);
            noty()
                ->livewire()
                ->addError('Operation failed!' . $ex);
        }
    }

}
