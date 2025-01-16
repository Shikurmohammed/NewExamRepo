<?php

namespace App\Livewire\QuestionBank;

use App\Models\Module;
use App\Models\Topic;
use App\Models\User;
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
}
