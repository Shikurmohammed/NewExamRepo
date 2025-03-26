<?php

namespace App\Services;

use App\Models\Test;
use Illuminate\Support\Facades\Session;

class TestPasswordService
{
    public function checkTestAccess(int $testId): bool
    {
        $password = $this->getTestPassword($testId);

        if (empty($password)) {
            return true;
        }

        $loginKey = $password . $testId . auth()->id() . request()->ip();
        return hash_equals(Session::get('session_test_login'), hash('sha256', $loginKey));
    }

    public function getTestPassword(int $testId): ?string
    {
        return Test::find($testId)->test_password;
    }
}
