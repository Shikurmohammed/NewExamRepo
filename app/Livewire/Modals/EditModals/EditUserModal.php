<?php

namespace App\Livewire\Modals\EditModals;

use App\Models\Group;
use App\Models\User;
use Exception;
use Livewire\Attributes\Rule;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;


class EditUserModal extends ModalComponent
{

    public $userId;
    public $user;

    #[Rule('required')]
    public $first_name = '';
    #[Rule('required')]
    public $middle_name = '';
    #[Rule('required',)]
    public $last_name;

    public string $email = '';
    #[Rule('required')]
    public string $password = 'test@123';
    public string $two_factor_secret = '';
    #[Rule('required',)]
    public $access_level;
    public $status = false;
    public $groups;
    public $group_id;
    public $roles = [
        ['id' => 1, 'description' => 'Examinee'],
        ['id' => 5, 'description' => 'Examiner'],
        ['id' => 10, 'description' => 'Admin']
    ];

    //Validation messages
    protected array $messages = [
        'first_name.required' => 'First Name is required!',
        'middle_name.required' => 'Middle Name is required!',
        'last_name.required' => 'Last Name is required!',

        'email.required' => 'Email is required!',
        'email.email' => 'Enter a valid email format (e.g., xxx@awash.com).',
        'email.unique' => 'This email is already taken. Try another!',
        'access_level.required' => 'Access Level(Role) is required.',

    ];
    public function rules()
    {
        return [
            'email' => 'unique:users,email,' . $this->userId,
        ];
    }
    public function mount($userId)
    {
        $this->userId = $userId;
        $this->user = User::find($userId);

        $this->groups = Group::all();

        //populate the edit form with current user's data
        $this->first_name = $this->user->first_name;
        $this->middle_name = $this->user->middle_name;
        $this->last_name = $this->user->last_name;

        $this->email = $this->user->email;
        $this->status = $this->user->status;
        $this->access_level = $this->user->access_level;
    }
    public function updateUser()
    {
        $validatedData = $this->validate();

        try {
            $this->user->update($validatedData);
            noty()
                ->livewire()
                ->addSuccess('User updated successfully!');
            $this->dispatch('refreshUserTable');
        } catch (Exception $ex) {

            noty()
                ->livewire()
                ->addError('Error occured:: ' . $ex->getMessage());
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
        return view('livewire.modals.edit-modals.edit-user-modal');
    }
}
