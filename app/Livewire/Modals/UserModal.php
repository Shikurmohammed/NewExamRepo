<?php

namespace App\Livewire\Modals;

use App\Models\Group;
use App\Models\User;
use Exception;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Symfony\Contracts\Service\Attribute\Required;

class UserModal extends Modal
{
    #[Rule('required')]
    public $first_name = '';
    #[Rule('required')]
    public $middle_name = '';
    #[Rule('required',)]
    public $last_name;
    #[Rule('required|email|unique:users,email')]
    public string $email = '';
    #[Rule('required')]
    public string $password = 'test@123';
    public string $two_factor_secret = '';
    #[Rule('required',)]
    public $access_level;
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

    public function mount()
    {
        $this->groups = Group::all();
    }
    public function createUser()
    {
        $validatedData = $this->validate();

        try {
            User::create($validatedData);
            noty()
                ->livewire()
                ->addSuccess('User added successfully!');
            $this->dispatch('refreshUserTable');
        } catch (Exception $ex) {

            noty()
                ->livewire()
                ->addError('Error occured:: ' . $ex->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.modals.user-modal');
    }
}
