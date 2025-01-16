<?php

namespace App\Livewire\QuestionBank;

use App\Models\Module;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionList extends Component
{
    use WithPagination;
public $search;
    public function delete($id)
    {
        try {
            $question = Question::find($id);
            if ($question) {
                session()->flash('success', 'Question added successfully!');
                //$question->delete();
            } else {
                session()->flash('error', 'Question already exists!');
            }
        } catch (\Exception $e) {
            session()->flash('error', "");
        }
    }
    #[Computed()]
    public function questions()
    {
        $questions = [];
        if (!$this->search) {

            $questions = Question::latest()->paginate(4);
        } else {
            $this->search = strtolower(trim($this->search));
            $questions = Question::latest()->where('description', 'Like', "%{$this->search}%")->paginate(4);
        }
        return   $questions;
    }

    public function render()
    {
        return view(
            'livewire.question-bank.question-list'
        );
    }
}
