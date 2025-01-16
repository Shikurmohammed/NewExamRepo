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
        return Test::paginate(5);
    }
    #[Computed()]
    public function topics(){
        return Topic::paginate(3);
    }
    #[Computed()]
    public function questions(){
        return Question::paginate(5);
    }

    public function render()
    {
        return view('livewire.exams.question-assignment');
    }
}
