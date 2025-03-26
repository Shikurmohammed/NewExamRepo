<?php

namespace App\Livewire\Modals;

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class UserGroupModal extends ModalComponent
{


    public $selectedUserIds = [];
    public $group_id;
    // protected $listeners = ['openUserGroupModal' => 'setSelectedUsers'];
    protected $messages = [
        'selectedUserIds.required' => 'No user has been selected!'
    ];
    public function mount($selectedUserIds)
    {
        $this->selectedUserIds = $selectedUserIds;
    }

    // public function setSelectedUsers($selectedUserIds)
    // {
    //     $this->selectedUserIds = $selectedUserIds;
    //     dd($this->selectedUserIds);
    // }

    public function addUsersToGroup()
    {
        //$this->validate(['selectedUserIds' => 'required']);
        try {
            // Validate that each user ID exists
            $invalidUserIds = [];
            $users = User::whereIn('id', $this->selectedUserIds)->get()->keyBy('id');

            $invalidUserIds = $this->checkUserExistanceInDbTable($this->selectedUserIds, $users);
            if ($invalidUserIds) {
                noty()->livewire()->addError("User IDs " . implode(', ', $invalidUserIds) . " do not exist.");
                return;
            }

            // Retrieve the group IDs from the request
            $groupIds = $this->group_id;
            if (!is_array($groupIds)) {
                $groupIds = [$groupIds]; // Ensure $groupIds is an array
            }

            $groups = Group::whereIn('id', $groupIds)->get();
            $invalidGroupIds = array_diff($groupIds, $groups->pluck('id')->toArray());
            if ($invalidGroupIds) {
                noty()->livewire()->addError("Group IDs " . implode(', ', $invalidGroupIds) . " do not exist.");
                return;
            }

            $alreadyInGroup = [];
            $usersToAttach = [];

            foreach ($users as $user) {
                foreach ($groups as $group) {
                    // Check if the user is already in the group
                    if (!$group->users()->where('user_id', $user->id)->exists()) {
                        $usersToAttach[$group->id][] = $user->id; // Group by group ID
                    } else {
                        // Store unique user IDs that are already in the group
                        $alreadyInGroup[] = $user->id;
                    }
                }
            }

            // Ensure unique values for alreadyInGroup
            $alreadyInGroup = array_unique($alreadyInGroup);

            // Use transaction for safety
            DB::transaction(function () use ($usersToAttach) {
                foreach ($usersToAttach as $groupId => $userIds) {
                    $group = Group::find($groupId);
                    $group->users()->syncWithoutDetaching($userIds);
                }
            });

            // Feedback
            if (!empty($alreadyInGroup)) {
                noty()->livewire()->addError("Users " . implode(', ', $alreadyInGroup) . " are already in their respective groups.");
            } else {
                noty()
                    ->livewire()
                    ->addSuccess("Users successfully added to the groups!");
            }
            $this->dispatch('refreshUserTable');
        } catch (\Throwable $th) {
            noty()
                ->livewire()
                ->addError("Error occurred: " . $th->getMessage());
        }
    }
    public function removeUsersFromGroup()
    {
        //Convert comma-separted user_id's to an array
        try {
            $selectedUserIds = $this->selectedUserIds;
            $invalidUserIds = [];
            $users = User::whereIn('id', $selectedUserIds)->get()->keyBy('id');

            $invalidUserIds = $this->checkUserExistanceInDbTable($selectedUserIds, $users);
            if ($invalidUserIds) {
                noty()->livewire()->addError("User ID's" . implode(
                    ',' . $invalidUserIds . "do not exist!"
                ));

                return;
            }
            //Get group ids from the request
            $groupIds = $this->group_id;
            //Ensure the $groupIds  is an array
            if (!is_array($groupIds)) {
                $groupIds = [$groupIds]; //Convert single ID to array.
            }
            $groups = Group::whereIn('id', $groupIds)->get();
            $invalidGroupIds = array_diff($groupIds, $groups->pluck('id')->toArray());
            if ($invalidGroupIds) {
                noty()->livewire()->addError("Group ID's" . implode(',' . $invalidGroupIds . "do not exist!"));
                return;
            }
            //dd($groups);
            $usersToDetach = [];
            foreach ($users as $user) {
                foreach ($groups as $group) {
                    if ($user->groups()->where('group_id', $group->id)->exists()) {
                        // $usersToDetach[] = $user->id;//This will remove users from single group at a time
                        //Make it associative array(key value pair) so that we will able to remove multiple groups at a time
                        // $usersToDetach will look like :-[ 1=>[1,2,..],2=>[1,2,...]] where the 1 is Group Id (Key) and [1,2,..] is user-ids (values)
                        $usersToDetach[$group->id][] = $user->id;
                    }
                }
            }
            //dd($usersToDetach);
            //Detach users from the group: remove from the user_groups table;
            if (!empty($usersToDetach)) {
                DB::transaction(function () use ($usersToDetach) {
                    foreach ($usersToDetach as $groupId => $userIds) {
                        $group = Group::find($groupId);
                        $group->users()->detach($userIds);
                    }
                });
                $this->dispatch('refreshUserTable');
                noty()->livewire()->addSuccess("Users successfully removed from the group!");
                return;
            }
        } catch (\Throwable $th) {
            noty()
                ->livewire()
                ->addError("Error occurred: " . $th->getMessage());
        }
    }
    //Check if each selected user_id exists in the users table, to ensure data integrity!
    public function checkUserExistanceInDbTable($userIdsFromForm, $userIdsFromTable)
    {
        $invalidUserIds = [];
        foreach ($userIdsFromForm as $userId) {
            if (!$userIdsFromTable->has($userId)) {
                $invalidUserIds[] = $userId;
            }
        }
        return  $invalidUserIds;
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
        return view('livewire.modals.user-group-modal', ['groups' => Group::all()]);
    }
}
