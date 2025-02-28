<?php

namespace App\Livewire\Exams;

use App\Models\Question;
use App\Models\Test;
use App\Models\Topic;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionAssignment extends Component
{
    use WithPagination;
    #[Computed()]
    public function tests(){
        return Test::all();
    }
    #[Computed()]
    public function topics(){
        return Topic::all();
    }
    #[Computed()]
    public function questions(){
        return Question::all();
    }

    public function render()
    {
        return view('livewire.exams.question-assignment');
    }
}
