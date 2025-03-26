<?php

namespace App\Services;

use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    public function executeTest(int $testId): bool
    {
        try {
            $test = $this->getValidTest($testId);
            // dd($test);
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

            return $this->handleTestStatus($testStatus, $test);
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    protected function getValidTest(int $testId): Test
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        return Test::where('id', $testId)
            ->where('start', '<', $now)
            ->where('end', '>', $now)
            ->firstOrFail();
    }

    protected function handleTestStatus(int $testStatus, Test $test): bool
    {
        // Test can be repeated
        if ($testStatus > 4 && $this->canRepeatTest($test)) {
            return $this->testCreationService->createTest(
                $test->id,
                Auth::id()
            );
        }

        switch ($testStatus) {
            case 0: // Not yet created
                return $this->testCreationService->createTest(
                    $test->id,
                    Auth::id()
                );
            case 1: // Created
            case 2: // Questions displayed
            case 3: // Questions answered
                return true;
            case 4: // Locked
            default:
                return false;
        }
    }

    protected function canRepeatTest(Test $test): bool
    {
        $userTestCount = $this->testCountService->countUserTests(
            Auth::id(),
            $test->id
        );

        return $userTestCount < $test->repeatable || $test->repeatable == 1;
    }




    public function startTest(int $testId): bool
    {
        $test = Test::where('id', $testId)
            ->where('start', '<', now())
            ->where('end', '>', now())
            ->first();

        return $test && $this->testAuthorizationService->isValidTestUser(
            $test->id,
            request()->ip(),
            $test->ip_range
        );
    }

    // public function repeatTest(int $testId): void
    // {
    //     TestUser::where('testuser_test_id', $testId)
    //         ->where('testuser_user_id', auth()->id())
    //         ->where('testuser_status', '>', 3)
    //         ->orderBy('testuser_status', 'desc')
    //         ->each(function ($testUser) {
    //             $testUser->increment('testuser_status');
    //         });
    // }

    // public function terminateUserTest(int $testId): void
    // {
    //     TestUser::where('testuser_test_id', $testId)
    //         ->where('testuser_user_id', auth()->id())
    //         ->where('testuser_status', '<', 4)
    //         ->update(['testuser_status' => 4]);
    // }

    // public function updateTestComment(int $testId, string $comment): void
    // {
    //     TestUser::where('testuser_test_id', $testId)
    //         ->where('testuser_user_id', auth()->id())
    //         ->update(['testuser_comment' => $comment]);
    // }

    public function getTestEndTime(int $testId)
    {
        return Test::find($testId)->end;
    }

    public function getTestName(int $testId): string
    {
        return Test::find($testId)->name;
    }

    // protected function isValidTestUser(int $testId, string $userIp, ?string $allowedIps): bool
    // {
    //     // Implementation of IP validation and other checks
    // }
}
