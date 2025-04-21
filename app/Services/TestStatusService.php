<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestStatusService
{
    // Constants matching the original status codes
    const STATUS_NOT_STARTED = 0;
    const STATUS_CREATED = 1;
    const STATUS_QUESTIONS_DISPLAYED = 2;
    const STATUS_QUESTIONS_ANSWERED = 3;
    const STATUS_LOCKED = 4;

    protected $secondsInMinute;

    public function __construct()
    {
        $this->secondsInMinute = config('tests.seconds_in_minute', 60);
    }

    public function checkTestStatus($userId, $testId, $duration)
    {
        $currentTime = Carbon::now();
        $testStatus = self::STATUS_NOT_STARTED;
        $testuserId = 0;
        // Get current test status for the user
        $testUser = DB::table('tests_users')
            ->where('test_id', $testId)
            ->where('user_id', $userId)
            ->orderBy('status')
            ->first(['id', 'status', 'created_at']);
        // dd($testUser);
        if ($testUser) {
            $testuserId = $testUser->id;
            $testStatus = $testUser->status;

            $endTime = Carbon::parse($testUser->created_at)
                ->addMinutes($duration);

            // Check for timeout condition
            if (
                $testStatus > self::STATUS_NOT_STARTED &&
                $testStatus < self::STATUS_LOCKED &&
                $currentTime->greaterThan($endTime)
            ) {

                // Lock the test due to timeout
                DB::table('tests_users')
                    ->where('id', $testuserId)
                    ->update(['status' => self::STATUS_LOCKED]);

                return [self::STATUS_LOCKED, $testuserId];
            }

            // Handle different status cases
            switch ($testStatus) {
                case self::STATUS_NOT_STARTED:
                    // Delete incomplete test
                    DB::table('tests_users')
                        ->where('id', $testuserId)
                        ->delete();
                    break;

                case self::STATUS_CREATED:
                    // Check if all questions were displayed
                    $undisplayedCount = DB::table('test_logs')
                        ->where('tests_users_id', $testuserId)
                        ->whereNull('display_time')
                        ->count();

                    if ($undisplayedCount === 0) {
                        // Update to "questions displayed" status
                        DB::table('tests_users')
                            ->where('id', $testuserId)
                            ->update(['status' => self::STATUS_QUESTIONS_DISPLAYED]);

                        $testStatus = self::STATUS_QUESTIONS_DISPLAYED;
                    }
                    break;

                case self::STATUS_QUESTIONS_DISPLAYED:
                    // Check if test has been completed
                    $unansweredCount = DB::table('test_logs')
                        ->where('tests_users_id', $testuserId)
                        ->whereNull('change_time')
                        ->count();

                    if ($unansweredCount === 0) {
                        // Update to "questions answered" status
                        DB::table('tests_users')
                            ->where('user_id', $testuserId)
                            ->update(['status' => self::STATUS_QUESTIONS_ANSWERED]);

                        $testStatus = self::STATUS_QUESTIONS_ANSWERED;
                    }
                    break;
            }
        }

        return [$testStatus, $testuserId];
    }
}
