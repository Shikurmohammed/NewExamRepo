<?php

namespace App\Livewire\QuestionBank;

use App\Models\Module;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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
            $topicId = $question->topic_id;
            $position = $question->position;

            $isQuestionUsed = DB::table('test_logs')->where('question_id', $id)->count();
            if ($isQuestionUsed > 0) {
                if ($question) {
                    noty()->livewire()->addWarning('Question In use!');
                    $question->enabled = 0;
                    $question->save();
                }
                return;
            }
            $question->delete();
            //Reorder the remaining questions
            if ($question->position > 0) {
                $sql = "UPDATE questions set position = position -1 where topic_id = :topic_id AND position > :position ";
                DB::update($sql, ['topic_id' => $topicId, 'position' => $position]);
            }
            noty()->livewire()->addSuccess('Question deleted sccessfully!');
        } catch (\Exception $e) {
            noty()->livewire()->addError('Error:' . $e->getMessage());
        }
    }
    #[Computed()]
    public function questions()
    {
        return Question::all();
    }

    public function render()
    {
        return view(
            'livewire.question-bank.question-list'
        );
    }



}
