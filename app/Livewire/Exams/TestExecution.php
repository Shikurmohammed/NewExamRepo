<?php

namespace App\Livewire\Exams;

use App\Services\TestExecutionService;
use App\Services\TestPasswordService;
use App\Services\TestQuestionService;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class TestExecution extends Component
{
    // public function render()
    // {
    //     return view('livewire.exams.test-execution');
    // }

    public $testId;
    public $testLogId = 0;
    public $answerPositions = [];
    public $answerText = '';
    public $testComment = '';
    public $reactionTime = 0;
    public $showTerminationConfirmation = false;
    public $omittedQuestionsCount = 0;
    public $remainingTime;
    public $isTestActive = false;
    public $examEndTime;

    protected $testExecutionService;
    protected $testPasswordService;
    protected $testQuestionService;

    public function boot(
        TestExecutionService $testExecutionService,
        TestPasswordService $testPasswordService,
        TestQuestionService $testQuestionService
    ) {
        $this->testExecutionService = $testExecutionService;
        $this->testPasswordService = $testPasswordService;
        $this->testQuestionService = $testQuestionService;
    }

    public function mount($testId)
    {
        $this->testId = (int)$testId;
        $this->initializeTest();
    }

    public function initializeTest()
    {
        // Check test password if required
        if (!$this->testPasswordService->checkTestAccess($this->testId)) {
            return redirect()->route('login', ['testid' => $this->testId]);
        }

        // Handle test repeat if requested
        if (request()->has('repeat') && request()->input('repeat') == 1) {
            $this->testExecutionService->repeatTest($this->testId);
        }

        // Initialize test execution
        $this->isTestActive = $this->testExecutionService->startTest($this->testId);

        if (!$this->isTestActive) {
            return redirect()->route('myexam_list'); //home
        }

        // Initialize timer
        $this->examEndTime = $this->testExecutionService->getTestEndTime($this->testId);
        $this->remainingTime = now()->diffInSeconds($this->examEndTime, false);
    }

    public function render()
    {
        if (!$this->isTestActive) {
            return view('livewire.exams.test-not-available');
        }

        return view('livewire.exams.test-execution', [
            'testName' => $this->testExecutionService->getTestName($this->testId),
            'questionForm' => $this->testQuestionService->getQuestionForm(
                $this->testId,
                $this->testLogId,
                'testExecutionForm'
            ),
            'terminationForm' => $this->showTerminationConfirmation
                ? $this->renderTerminationForm()
                : null,
        ]);
    }

    public function updateQuestion()
    {
        $this->validate([
            'answerPositions' => 'nullable|array',
            'answerText' => 'nullable|string',
            'reactionTime' => 'nullable|integer',
        ]);

        $this->testQuestionService->updateQuestionLog(
            $this->testId,
            $this->testLogId,
            $this->answerPositions,
            $this->answerText,
            $this->reactionTime
        );

        // Update test comment if provided
        if (!empty($this->testComment)) {
            $this->testExecutionService->updateTestComment($this->testId, $this->testComment);
        }
    }

    public function nextQuestion($nextQuestionId)
    {
        $this->updateQuestion();
        $this->testLogId = (int)$nextQuestionId;
        $this->resetAnswerFields();
    }

    public function prevQuestion($prevQuestionId)
    {
        $this->updateQuestion();
        $this->testLogId = (int)$prevQuestionId;
        $this->resetAnswerFields();
    }

    public function jumpToQuestion($questionId)
    {
        $this->updateQuestion();
        $this->testLogId = (int)$questionId;
        $this->resetAnswerFields();
    }

    public function confirmTermination()
    {
        $this->omittedQuestionsCount = $this->testQuestionService->getOmittedQuestionsCount($this->testId);
        $this->showTerminationConfirmation = true;
    }

    public function terminateTest()
    {
        $this->updateQuestion();
        $this->testExecutionService->terminateUserTest($this->testId);
        return redirect()->route('myexam_list');
    }

    public function cancelTermination()
    {
        $this->showTerminationConfirmation = false;
    }

    protected function resetAnswerFields()
    {
        $this->answerPositions = [];
        $this->answerText = '';
        $this->reactionTime = 0;
    }

    protected function renderTerminationForm()
    {
        return view('livewire.exams.test-termination-form', [
            'omittedQuestionsCount' => $this->omittedQuestionsCount,
            'warningMessage' => Lang::get('m_confirm_test_termination'),
        ]);
    }
}
