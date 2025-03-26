<?php

namespace App\Livewire\Examinee;

use App\Models\Test;
use App\Services\TestExecutionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class MyTest extends Component
{
    #[Computed()]
    public  function tests()
    {
        return Test::all();
    }
    // public function startExam($id)
    // {
    //     return redirect()->route('mytest.list');
    // }

    public function startExam(TestExecutionService $testService, $testId)
    {

        try {
            if ($testService->executeTest($testId)) {
                return redirect()->route('mytest.list');
            }
        } catch (\Throwable $th) {
            return back()->with('error', 'Unable to start test' . $th->getMessage());
        }
    }
    public function executeExam($id) {}
    // public function render()
    // {
    //     return view('livewire.examinee.my-test');
    // }

    public function render()
    {
        return view('livewire.examinee.my-test', ['tests' => $this->tests]);
    }
}
