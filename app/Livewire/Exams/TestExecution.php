<?php

namespace App\Livewire\Exams;

use App\Services\TestExecutionService;
use App\Services\TestPasswordService;
use App\Services\TestQuestionService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TestExecution extends Component
{
    // public function render()
    // {
    //     return view('livewire.exams.test-execution');
    // }

    public $testId;
    public $testLogId = 0; //0
    public $answerPositions = [];
    public $answerText = '';
    public $testComment = '';
    public $reactionTime = 0;
    public $showTerminationConfirmation = false;
    public $omittedQuestionsCount = 0;
    public $remainingTime;
    public $isTestActive = false;
    public $examEndTime;
    public $timeoutLogout = false;

    protected $testExecutionService;
    protected $testPasswordService;
    protected $testQuestionService;
    protected $listeners = ['timerUpdated' => 'updateTimer'];
    // public $questionForm;
    //public $questionFormHtml;
    public string $testState = '';
    public $previousLogId = 1;
    public $nextLogId = 2;

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
            // return redirect()->route('test_login', ['testId' => $this->testId]);
        }

        // Check if test is repeatable, mark previous test attempts as repeated
        if (request()->has('repeat') && request()->input('repeat') == 1) {
            $this->testExecutionService->repeatTest($this->testId);
        }

        // Initialize test execution
        // $this->isTestActive = $this->testExecutionService->startTest($this->testId);
        // $this->isTestActive = $this->testExecutionService->executeTest($this->testId);

        $this->testState = $this->testExecutionService->executeTest($this->testId);
        $this->isTestActive = in_array($this->testState, ['started', 'continued']);
        $this->handleRequestInputs();
        $this->handleForceTermination();

        //  dd($this->isTestActive);

        // if (!$this->isTestActive) {
        //     return redirect()->route('myexam_list'); //home
        // }

        // Initialize timer
        $this->examEndTime = $this->testExecutionService->getTestEndTime($this->testId);
        $this->remainingTime = now()->diffInSeconds($this->examEndTime, false);

        // dd($this->examEndTime);
    }
    public function handleRequestInputs()
    {
        // Handle test log ID
        if (request()->filled('testlogid')) {
            $this->testLogId = (int) request('testlogid');
        }
        // Handle answer positions
        if (request()->filled('answpos')) {
            $answposInput = request('answpos');
            $this->answerPositions = is_numeric($answposInput)
                ? [(int) $answposInput => 1]
                : collect((array) $answposInput)->mapWithKeys(fn($v) => [(int) $v => 1])->toArray();
        }

        // Handle answer text
        if (request()->filled('answertext')) {
            $this->answerText = request('answertext');
        }

        // Handle reaction time
        if (request()->filled('reaction_time')) {
            $this->reactionTime = (int) request('reaction_time');
        }
    }
    public function handleForceTermination()
    {
        // Forced termination logic
        if (
            request()->filled('forceterminate') &&
            $this->testExecutionService->isRightTestlogUser($this->testId, $this->testLogId)
        ) {
            if (request('forceterminate') === 'lasttimedquestion') {
                $this->testExecutionService->updateQuestionLog(
                    $this->testId,
                    $this->testLogId,
                    $this->answerPositions,
                    $this->answerText,
                    $this->reactionTime
                );
            }

            $this->testExecutionService->terminateTest($this->testId);
            // Laravel redirect (instead of raw header)
            redirect()->route('test.index')->send();
            exit;
        }
    }
    public function executeExam(TestExecutionService $testExecutionService1, $testId) //For auto-injecting TestExecutionService $testExecutionService1
    {
        dd("dasda");
        try {
            $value =  $testExecutionService1->executeTest($testId);
            //$value = $testExecutionService1->repeatTest($testId);
            if ($value) {
                //return view('livewire.exams.test-question-form');
                //return view('livewire.exams.test-execution', ['test' => '$test']);
                return redirect()->route('mytest.list');
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return back()->with('error', 'Unable to execute test' . $th->getMessage());
        }
    }

    public function render()
    {
        if (!$this->isTestActive) {
            return view('livewire.exams.test-not-available');
        }

        // Get question data (could also be question HTML if your service renders it)
        $questionFormHtml = $this->testQuestionService->getQuestionData(
            $this->testId,
            $this->testLogId,
            'testExecutionForm'
        );

        // Log question content status
        if (empty($questionFormHtml)) {
            Log::warning("Question form is empty for testLogId: {$this->testLogId}");
        } else {
            Log::info("Question form rendered for testLogId: {$this->testLogId}");
        }
        //dd($this->testQuestionService->getQuestionData($this->testId, $this->testLogId));
        return view('livewire.exams.test-execution', [
            'testName' => $this->testExecutionService->getTestName($this->testId),
            'questionData' => $this->testQuestionService->getQuestionData($this->testId, $this->testLogId),
            'terminationForm' => $this->showTerminationConfirmation
                ? $this->renderTerminationForm()
                : null,
        ]);
        // return view('livewire.exams.test-execution', [
        //     'testName' => $this->testExecutionService->getTestName($this->testId),
        //     'questionFormHtml' => $questionFormHtml,
        //     'terminationForm' => $this->showTerminationConfirmation
        //         ? $this->renderTerminationForm()
        //         : null,
        // ]);
    }

    // public function render()
    // {
    //     // dd($this->isTestActive);
    //     if (!$this->isTestActive) {
    //         return view('livewire.exams.test-not-available');
    //     }
    //     // Fetch the question form
    //     $questionFormHtml = $this->testQuestionService->getQuestionData(
    //         $this->testId,
    //         $this->testLogId,
    //         'testExecutionForm'
    //     );

    //     // Debugging output
    //     if (empty($this->questionFormHtml)) {
    //         Log::info('Question form is empty.'); // Log if it's empty
    //     } else {
    //         Log::info('Question form content: ' . $this->questionFormHtml); // Log the content
    //     }
    //     return view('livewire.exams.test-execution', [
    //         'testName' => $this->testExecutionService->getTestName($this->testId),
    //         'questionFormHtml' => $this->testQuestionService->getQuestionData(
    //             $this->testId,
    //             $this->testLogId,
    //             'testExecutionForm'
    //         ),
    //         'terminationForm' => $this->showTerminationConfirmation
    //             ? $this->renderTerminationForm()
    //             : null,
    //     ]);
    // }
    // public function showQuestion(TestQuestionService $testQuestionService, $testId, $testLogId = 0)
    // {
    //     $questionData = $testQuestionService->getQuestionData($testId, $testLogId);
    //     // dd($questionData);

    //     if (empty($questionData)) {
    //         abort(404, 'Question not found');
    //     }
    //     //livewire.exams.test-question-form
    //     return view('livewire.exams.test-question-form', [
    //         'questionData' => $questionData,
    //         'translations' => [
    //             'm_unanswered' => __('m_unanswered'),
    //             'w_false' => __('w_false'),
    //             'w_true' => __('w_true'),
    //             // Add other translations as needed
    //         ]
    //     ]);
    // }

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
        session()->flash('message', 'Answer saved!');
        noty()->livewire()->addSuccess("Answer saved!");
    }

    public function nextQuestion($nextQuestionId)
    {
        $this->testLogId = (int)$nextQuestionId;
        $this->updateQuestion();
        //$this->testLogId = (int)$nextQuestionId;
        $this->resetAnswerFields();
    }

    public function prevQuestion($prevQuestionId)
    {
        $this->testLogId = (int)$prevQuestionId;
        $this->updateQuestion();
        //  $this->testLogId = (int)$prevQuestionId;
        $this->resetAnswerFields();
    }

    public function jumpToQuestion($questionId)
    {
        $this->updateQuestion();
        $this->testLogId = (int)$questionId;
        $this->resetAnswerFields();
    }
    public function updateReactionTime()
    {
        $elapsedTime = $this->reactionTime;

        // You can save or log it if needed
        Log::info("User has spent {$elapsedTime} seconds on this question.");

        // Optionally store to DB
        // TestLog::where('id', $this->testLogId)->update(['reaction_time' => $elapsedTime]);
    }

    public function confirmTermination()
    {
        $this->omittedQuestionsCount = $this->testQuestionService->getOmittedQuestionsCount($this->testId);
        $this->showTerminationConfirmation = true;
    }

    public function terminateTest()
    {
        $this->updateQuestion();  // Save final answer if needed
        $this->testExecutionService->terminateUserTest($this->testId);
        return redirect()->route('myexam_list'); //Or myexam_list if preferred
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
        $this->testComment = '';
    }

    protected function renderTerminationForm()
    {
        return view('livewire.exams.test-termination-form', [
            'omittedQuestionsCount' => $this->omittedQuestionsCount,
            'warningMessage' => Lang::get('m_confirm_test_termination'),
        ]);
    }
}
