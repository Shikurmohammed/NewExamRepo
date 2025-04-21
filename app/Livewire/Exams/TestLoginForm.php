<?php

namespace App\Livewire\Exams;

use App\Models\Test;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class TestLoginForm extends Component
{
    public $test_password;
    public $test_id;

    public function mount($testId)
    {
        $this->test_id = $testId;
    }

    public function submit()
    {
        $this->validate([
            'test_password' => 'required|string|max:255',
        ]);
        $test = Test::find($this->test_id);
        if (!$test || !Hash::check($this->test_password, $test->password)) {
            noty()->livewire()->addError('The provided password is incorrect.');
            return false;
        }
        noty()->livewire()->addSuccess('Logged in successfully.');
        return redirect()->route('execute_exam', ['testId' => $this->test_id]);
    }

    public function render()
    {
        return view('livewire.exams.test-login-form');
    }
}
