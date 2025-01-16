<?php

namespace App\Livewire\Modals;

use App\Models\Module;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class TopicModal extends Modal
{
    #[Rule('required')]
    public $module_id ;
    public $topic_name;
    public $description;
    public $enabled = 0;
    public $owner_name = '';
    public function create()
    {
        $this->validate();

        $topic = new Topic();

        $topic = new Topic();
        $topic->module_id = $this->module_id;
        $topic->name = $this->topic_name;
        $topic->description = $this->description;
        $topic->enabled = $this->enabled;

        //Append the creator
        $user_id = Auth::user()->id;
        $topic->user_id = $user_id;

        $countTopicByname = Topic::where('name', $this->topic_name)->count();

        if ($countTopicByname < 1) {
            Topic::create([
                'module_id'=>$this->module_id,
                'name' => $this->topic_name,
                'description' => $this->description,
                'enabled' => $this->enabled,
                'user_id' => $user_id,
            ]);

            session()->flash('success', 'Topic added successfully!');
        } else {
            session()->flash('error', 'Topic already exists!');
        }
    }
    #[Computed()]
    public function modules(){
        return Module::all();
    }
    public function render()
    {
        return view('livewire.modals.topic-modal');
    }
}
