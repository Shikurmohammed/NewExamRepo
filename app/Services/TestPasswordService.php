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
        $sessionValue = Session::get('session_test_login');
        if ($sessionValue == null) {
            return false;
        }

        return hash_equals($sessionValue, hash('sha256', $loginKey)); //to be checked
    }

    public function getTestPassword(int $testId): ?string
    {
        return Test::find($testId)->password;
    }
}
