<?php

namespace App\Livewire\QuestionBank;

use App\Models\Answer;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class AnswerList extends Component
{
    use WithPagination;

    #[Computed()]
    public function answers()
    {
        return Answer::all();
    }
    public function delete($id)
    {
        try {
            $answer = Answer::find($id);
            $questionId = $answer->question_id;
            $position = $answer->position;

            $isAnswerUsed = DB::table('answer_logs')->where('answer_id', $id)->count();
            if ($isAnswerUsed > 0) {
                if ($answer) {
                    $answer->enabled = 0;
                    $answer->save();
                    noty()->livewire()->addWarning('Answer In use!');
                }
                return;
            }
            $answer->delete();
            //Reorder the remaining answers
            if ($answer->position > 0) {
                $sql = "UPDATE answers set position = position -1 where question_id = :question_id AND position > :position ";
                DB::update($sql, ['question_id' => $questionId, 'position' => $position]);
            }
            noty()->livewire()->addSuccess('Answer deleted sccessfully!');
        } catch (\Exception $e) {
            noty()->livewire()->addError('Error:' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.question-bank.answer-list');
    }
}
