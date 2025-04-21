<?php

namespace App\Livewire\Examinee;

use App\Services\TestExecutionService;
use App\Services\TestPasswordService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class StartExam extends Component
{
    // public $testId;
    // public $password = '';
    // public $showPassword = false;

    // protected $testPasswordService;
    // protected $testExecutionService;

    // public function boot(TestPasswordService $testPasswordService, TestExecutionService $testExecutionService)
    // {
    //     $this->testPasswordService = $testPasswordService;
    //     $this->testExecutionService = $testExecutionService;
    // }

    // public function mount($testId)
    // {
    //     $this->testId = $testId;
    //     Log::info('StartExam mount called with testId: ' . $testId);  // Log testId

    //     // You can check here whether password is required
    //     $this->showPassword = $this->testPasswordService->isPasswordRequired($testId);
    // }

    // public function startTest()
    // {
    //     // Validate password if required
    //     if ($this->showPassword && !$this->testPasswordService->checkPassword($this->testId, $this->password)) {
    //         session()->flash('error', 'Invalid password.');
    //         return;
    //     }

    //     // Execute the test (initialize it)
    //     $this->testExecutionService->executeTest($this->testId);

    //     return redirect()->route('execute_exam', ['testId' => $this->testId]);
    // }

    // public function render()
    // {
    //     // return view('livewire.examinee.start-exam', [
    //     //     'testDescription' => $this->testExecutionService->getTestDescription($this->testId),
    //     // ]);
    //     return view('livewire.examinee.start-exam');
    // }


    public function render()
    {
        // return view('livewire.exams.test-question-form'); //
        return view('livewire.examinee.start-exam');
    }
}
