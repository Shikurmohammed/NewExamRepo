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


    // public function startExam(TestExecutionService $testService, $testId)
    // {

    //     dd("Inside startExam");
    //     try {
    //         if ($testService->executeTest($testId)) {
    //             return redirect()->route('mytest.list');
    //         }
    //     } catch (\Throwable $th) {
    //         return back()->with('error', 'Unable to start test' . $th->getMessage());
    //     }
    // }
    // public function executeExam($id) {}
    // // public function render()
    // // {
    // //     return view('livewire.examinee.my-test');
    // // }

    public function render()
    {
        // $userId = Auth::id();
        // $tests = DB::select("
        //     SELECT t.*, tu.*
        //     FROM tests t
        //     JOIN tests_users tu ON t.id = tu.test_id
        //     WHERE tu.user_id = ?
        //     AND tu.status < 5
        //     ORDER BY t.start DESC
        // ", [$userId]);
        //dd($tests);

        return view('livewire.examinee.my-test');
    }
}
