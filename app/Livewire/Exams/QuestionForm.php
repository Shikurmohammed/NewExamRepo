<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Test;
use App\Models\Question;
use App\Models\TestLog;
use App\Models\Answer;
use App\Models\LogAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;

class QuestionForm extends Component
{
    public $testId;
    public $testLogId;
    public $formName;
    public $question;
    public $answers = [];
    public $selectedAnswers = [];
    public $answerText = '';
    public $showFullscreen = false;
    public $remainingTime;
    public $examEndTime;
    public $autoNext = false;

    protected $listeners = ['refreshQuestion' => 'refresh'];

    public function mount($testId, $testLogId, $formName)
    {
        $this->testId = $testId;
        $this->testLogId = $testLogId;
        $this->formName = $formName;
        $this->loadQuestion();
    }

    public function loadQuestion()
    {
        if (!$this->testLogId) {
            $this->getFirstQuestion();
        }

        $result = DB::select("
                    SELECT tl.*, q.*, la.*, a.*
                    FROM test_logs tl
                    LEFT JOIN questions q ON tl.question_id = q.id
                    LEFT JOIN log_answers la ON tl.testlog_id = la.testlog_id
                    LEFT JOIN answers a ON la.answer_id = a.id
                    WHERE tl.testlog_id = ?
                    LIMIT 1
                ", [$this->testLogId]);

        $this->question = !empty($result) ? $result[0] : null;
        if ($this->question) {
            $this->showFullscreen = (bool)$this->question->question->question_fullscreen;
            $this->initializeAnswers();
            $this->markQuestionAsDisplayed();
            $this->initializeTimer();
        }
    }

    protected function getFirstQuestion()
    {
        $testLog = DB::select("
                    SELECT *
                    FROM test_logs tl
                    WHERE EXISTS (
                        SELECT 1
                        FROM test_user tu
                        WHERE tu.testuser_test_id = ?
                        AND tu.testuser_user_id = ?
                        AND tu.testuser_status < 5
                        AND tu.testuser_id = tl.testlog_testuser_id
                    )
                    ORDER BY tl.testlog_id
                    LIMIT 1
                ", [$this->testId, auth()->id()]);

        $testLog = !empty($testLog) ? $testLog[0] : null; // Assign the first object or null if no result
        if ($testLog) {
            $this->testLogId = $testLog->testlog_id;
        }
    }

    protected function initializeAnswers()
    {
        $this->answers = $this->question->logAnswers->map(function ($logAnswer) {
            return [
                'id' => $logAnswer->logansw_id,
                'answer_id' => $logAnswer->answer_id,
                'description' => $logAnswer->answer->answer_description,
                'selected' => $logAnswer->logansw_selected,
                'position' => $logAnswer->logansw_position,
                'keyboard_key' => $logAnswer->answer->answer_keyboard_key
            ];
        });
        $this->selectedAnswers = collect($this->answers)->mapWithKeys(function ($answer) {
            return [$answer['id'] => $answer['selected']];
        })->toArray();

        $this->answerText = $this->question->testlog_answer_text ?? '';
    }

    protected function markQuestionAsDisplayed()
    {
        if (!$this->question->testlog_display_time) {
            $this->question->update(['display_time' => now()]);
        }
    }

    protected function initializeTimer()
    {
        $test = Test::find($this->testId);
        $this->examEndTime = now()->addMinutes($test->test_duration_time);
        $this->remainingTime = now()->diffInSeconds($this->examEndTime, false);
    }

    public function render()
    {
        return view('livewire.question-form', [
            'testData' => Test::find($this->testId),
            'noAnswerEnabled' => $this->question->question->test->test_noanswer_enabled ?? false,
            'inlineAnswers' => $this->question->question->question_inline_answers ?? false,
            'autoNextEnabled' => $this->question->question->question_auto_next ?? false,
            'questionTimer' => $this->question->question->question_timer ?? 0,
            'enableVirtualKeyboard' => config('tce.enable_virtual_keyboard', false),
            'answerTextareaCols' => config('tce.answer_textarea_cols', 80),
            'answerTextareaRows' => config('tce.answer_textarea_rows', 10),
        ]);
    }

    public function saveAnswer()
    {
        $this->validateAnswer();

        $data = [
            'testlog_answer_text' => $this->answerText,
            'testlog_change_time' => now(),
            'testlog_reaction_time' => $this->calculateReactionTime(),
        ];

        $this->question->update($data);

        foreach ($this->answers as $answer) {
            DB::update("
                    UPDATE log_answers
                    SET logansw_selected = ?, logansw_position = ?
                    WHERE logansw_id = ?
                ", [
                $this->selectedAnswers[$answer['id']] ?? 0, // For logansw_selected
                $this->selectedAnswers[$answer['id']] ?? null, // For logansw_position
                $answer['id'] // For logansw_id
            ]);
        }

        if ($this->autoNext) {
            $this->emit('nextQuestion');
        }
    }

    protected function validateAnswer()
    {
        $rules = [];

        if ($this->question->question->question_type == 3) {
            $rules['answerText'] = 'nullable|string';
        } else {
            foreach ($this->answers as $answer) {
                $rules["selectedAnswers.{$answer['id']}"] = $this->getValidationRule($answer);
            }
        }

        $this->validate($rules);
    }

    protected function getValidationRule($answer)
    {
        switch ($this->question->question->question_type) {
            case 1: // MCSA
            case 2: // MCMA
                return 'nullable|integer';
            case 4: // ORDER
                return 'nullable|integer|min:1';
            default:
                return 'nullable';
        }
    }

    protected function calculateReactionTime()
    {
        // Implementation to calculate reaction time
        return now()->diffInMilliseconds(Session::get('question_display_time'));
    }

    public function refresh()
    {
        $this->loadQuestion();
    }

    public function handleKeyPress($key)
    {
        foreach ($this->answers as $answer) {
            if ($answer['keyboard_key'] == $key) {
                $this->selectedAnswers[$answer['id']] = 1;
                $this->autoNext = true;
                $this->saveAnswer();
                break;
            }
        }
    }
}
