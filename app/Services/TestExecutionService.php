<?php

namespace App\Services;

use App\Models\Test;
use App\Models\TestLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TestExecutionService
{
    protected $testAuthorizationService;
    protected $testStatusService;
    protected $testCreationService;
    protected  $testCountService;
    public function __construct(
        TestAuthorizationService $testAuthorizationService,
        TestStatusService $testStatusService,
        TestCreationService $testCreationService,
        TestCountService $testCountService
    ) {
        $this->testAuthorizationService = $testAuthorizationService;
        $this->testStatusService = $testStatusService;
        $this->testCreationService = $testCreationService;
        $this->testCountService = $testCountService;
    }

    public function executeTest(int $testId) //: bool
    {
        //dd("Inside executeTest" . $testId);
        //dd($testId);
        try {
            //  dd($this->getValidTest($testId));
            if (!$this->getValidTest($testId)) {
                noty()->livewire()->addWarning('Oops , the test was expierd.');
                return false;
            }
            $test = $this->getValidTest($testId);

            if (!$this->testAuthorizationService->isValidTestUser(
                $test->id,
                request()->ip(),
                $test->ip_range
            )) {
                return false;
            }

            [$testStatus, $testUserId] = $this->testStatusService->checkTestStatus(
                Auth::id(),
                $test->id,
                $test->duration
            );
            //dd([$testStatus, $testUserId]);
            //  dd(get_class($test), $test);

            $testStatusResult = $this->handleTestStatus($testStatus, $test);
            return $testStatusResult;
        } catch (\Exception $e) {
            report($e);
            dd(
                "Error in executeTest: " . $e->getMessage() . $e->getLine()
            ); // . $e->getFile() .$e->getTraceAsString()
            return false;
        }
    }

    protected function getValidTest(int $testId): ?Test
    {
        //dd($testId);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $test = Test::where('id', $testId)
            ->where('start', '<', $now)
            ->where('end', '>', $now)
            ->first();
        return $test;
    }
    protected function handleTestStatus(int $testStatus, Test $test): string
    {
        if ($testStatus > 4 && $this->canRepeatTest($test)) {
            return $this->testCreationService->createTest($test->id, Auth::id())
                ? 'started'
                : 'error';
        }

        switch ($testStatus) {
            case 0:
                return $this->testCreationService->createTest($test->id, Auth::id())
                    ? 'started'
                    : 'error';
            case 1:
            case 2:
            case 3:
                return 'continued';

            case 4:
            default:
                return 'finished';
        }
    }

    // protected function handleTestStatus(int $testStatus, Test $test): bool
    // {
    //     // dd($testStatus);
    //     // Test can be repeated
    //     //  dd($test);
    //     if ($testStatus > 4 && $this->canRepeatTest($test)) {

    //         return $this->testCreationService->createTest(
    //             $test->id,
    //             Auth::id()
    //         ) ? true : false;
    //     }
    //     // dd($testStatus);
    //     switch ($testStatus) {
    //         case 0: // Not yet created
    //             // dd("Not yet created");

    //             return $this->testCreationService->createTest(
    //                 $test->id,
    //                 Auth::id()
    //             ) ? true : false;

    //         case 1: // Created
    //         case 2: // Questions displayed
    //         case 3: // Questions answered
    //             return true;
    //         case 4: // Locked
    //         default:
    //             return false;
    //     }
    // }

    protected function canRepeatTest(Test $test): bool
    {
        //dd($test);
        $userTestCount = $this->testCountService->countUserTests(
            Auth::id(),
            $test->id
        );

        return $userTestCount < $test->repeatable || $test->repeatable == 1;
    }

    // public function startTest(int $testId): bool
    // {
    //     $test = Test::where('id', $testId)
    //         ->where('start', '<', now())
    //         ->where('end', '>', now())
    //         ->first();

    //     // dd($test);
    //     return $test && $this->testAuthorizationService->isValidTestUser(
    //         $test->id,
    //         request()->ip(),
    //         $test->ip_range
    //     );
    // }
    //This will increase the status of tests_users,
    public function repeatTest(int $testId): void
    {
        //Get user test attempts and
        DB::table('tests_users')
            ->where('test_id', $testId)
            ->where('user_id', auth()->id())
            ->where('status', '>', 3)
            ->orderBy('status', 'desc')
            ->get()
            ->each(function ($testUser) { //Mark attempts as repeated
                DB::table('tests_users')
                    ->where('id', $testUser->id)
                    ->increment('status');
            });
    }

    public function terminateUserTest(int $testId): void
    {
        DB::table('tests_users')
            ->where('test_id', $testId)
            ->where('user_id', auth()->id())
            ->where('status', '<', 4)
            ->update(['status' => 4]);
    }

    public function updateTestComment(int $testId, string $comment): void
    {
        DB::table('tests_users')
            ->where('test_id', $testId)
            ->where('user_id', auth()->id())
            ->update(['comment' => $comment]);
    }

    public function getTestEndTime(int $testId)
    {
        //May I should get Test StartTime(created_at) from tests_users table according to the original code
        return Test::find($testId)->end;
    }

    public function getTestName(int $testId): string
    {
        return Test::find($testId)->name;
    }

    function isRightTestlogUser(int $test_id, int $testlog_id): bool
    {
        // Ensure the user is authenticated
        $userId = Auth::id();
        // Query the TestLog with its relationship to TestUser
        $testLog = TestLog::where('id', $testlog_id)
            ->whereHas('testUser', function ($query) use ($test_id, $userId) {
                $query->where('user_id', $userId)
                    ->where('test_id', $test_id);
            })
            ->first();
        return $testLog !== null;
    }
}
