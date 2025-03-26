<?php

namespace App\Livewire\Modals\EditModals;

use App\Models\Group;
use App\Models\Module;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class EditTestModal extends ModalComponent
{
    public $testId;
    public $test;

    #[Rule(['required'])]
    public $test_name;
    public $description;
    public $start;
    public $end;
    public $duration;
    public $group_id;
    public $score_right = 1;
    public $score_wrong = 0;
    public $score_threshold;

    #[Rule(['required', 'min:6'])]
    public $exam_password;
    public $score_unanswered = 0;
    public $max_score;

    public $random_questions_select = true;
    public $random_questions_order = true;
    public $random_answers_select = true;
    public $random_answers_order = true;
    public $mcma_partial_score = true;
    public $noanswer_enabled = true;
    public $comment_enabled = true;
    public $repeatable;

    public $result_to_user;
    public $report_to_user;
    public $logout_on_timeout;
    public $menu_enabled = true;

    public $questions_order_mode;
    public $qordmode = [
        ['id' => 0, 'name' => 'position'],
        ['id' => 1, 'name' => 'alphabet'],
        ['id' => 2, 'name' => 'type'],
        ['id' => 3, 'name' => 'id'],
        ['id' => 4, 'name' => 'topic']
    ];

    protected array $messages = [
        'test_name.required' => 'Test name is required.',
        'exam_password.required' => 'Exam password is required.'
    ];

    #[Computed()]
    public function modules()
    {
        return Module::all();
    }
    #[Computed()]
    public function groups()
    {
        return Group::all();
    }
    public function updated($propertyName)
    {
        if ($propertyName == 'start' || $propertyName == 'end') {
            if ($this->start && $this->end) {
                $start = Carbon::parse($this->start);
                $end = Carbon::parse($this->end);
                $this->duration = $end->diffInMinutes($start);
            }
        }
    }
    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    public static function modalSize(): string
    {
        return '7xl';
    }
    public function render()
    {
        return view('livewire.modals.edit-modals.edit-test-modal');
    }
}
