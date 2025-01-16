<?php

namespace App\Livewire\QuestionBank;

use App\Models\Answer;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class AnswerList extends Component
{
    use WithPagination;

    #[Computed()]
    public function answers(){
        return Answer::paginate(10);
    }
    public function render()
    {
        return view('livewire.question-bank.answer-list');
    }
}
