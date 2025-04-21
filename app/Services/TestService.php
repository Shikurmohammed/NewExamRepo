<?php

namespace App\Services;

use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestService
{
    /**
     * Get basic test data
     */

    public function getTestData($testId)
    {
        return Test::find($testId);
    }
    //Get Test Data
    public function getTestPassword(int $testId): ?string
    {
        return $this->getTestAttribute($testId, 'password');
    }

    /**
     * Get test name
     */
    public function getTestName(int $testId): ?string
    {
        return $this->getTestAttribute($testId, 'name');
    }

    /**
     * Get test duration in seconds
     */
    public function getTestDuration(int $testId): int
    {
        $duration = $this->getTestAttribute($testId, 'duration');
        return (int) ($duration * config('tests.seconds_in_minute', 60));
    }

    /**
     * Get test start time
     */
    public function getTestStartTime(int $testUserId): int
    {
        try {
            //$testUser = TestUser::findOrFail($testUserId);
            $testUser = DB::query("SELECT * from tests_users where user_id =$testUserId")->get();
            return strtotime($testUser->created_at);
        } catch (\Exception $e) {
            Log::error("Failed to get test start time: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Generic method to get test attribute
     */
    protected function getTestAttribute(int $testId, string $attribute)
    {
        try {
            $test = Test::findOrFail($testId);
            return $test->{$attribute} ?? null;
        } catch (\Exception $e) {
            Log::error("Failed to get test {$attribute}: " . $e->getMessage());
            return null;
        }
    }
}
