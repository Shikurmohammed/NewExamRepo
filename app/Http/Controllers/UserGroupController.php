<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserGroupController extends Controller
{

    public function addUsersToGroup(Request $request)
    {
        try {
            // Convert comma-separated user IDs into an array
            $selectedUserIds = explode(',', $request->user_id);

            // Validate that each user ID exists
            $invalidUserIds = [];
            $users = User::whereIn('id', $selectedUserIds)->get()->keyBy('id');

            $invalidUserIds = $this->checkUserExistanceInDbTable($selectedUserIds, $users);
            if ($invalidUserIds) {
                noty()->error("User IDs " . implode(', ', $invalidUserIds) . " do not exist.");
                return redirect()->back();
            }

            // Retrieve the group IDs from the request
            $groupIds = $request->group_id;
            if (!is_array($groupIds)) {
                $groupIds = [$groupIds]; // Ensure $groupIds is an array
            }

            $groups = Group::whereIn('id', $groupIds)->get();
            $invalidGroupIds = array_diff($groupIds, $groups->pluck('id')->toArray());
            if ($invalidGroupIds) {
                return response()->json(['error' => "Group IDs " . implode(', ', $invalidGroupIds) . " do not exist."], 404);
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
                noty()->error("Users " . implode(', ', $alreadyInGroup) . " are already in their respective groups.");
            } else {
                noty()->success("Users successfully added to the groups!");
            }
        } catch (\Throwable $th) {
            noty()->error("Error occurred: " . $th->getMessage());
        }

        return redirect()->back();
    }
    public function removeUsersFromGroup(Request $request)
    {
        //Convert comma-separted user_id's to an array
        try {
            $selectedUserIds = explode(',', $request->user_id);
            $invalidUserIds = [];
            $users = User::whereIn('id', $selectedUserIds)->get()->keyBy('id');

            $invalidUserIds = $this->checkUserExistanceInDbTable($selectedUserIds, $users);
            if ($invalidUserIds)
                return response()->json(["error" => "User ID's" . implode(',' . $invalidUserIds . "do not exist!")]);
            //Get group ids from the request
            $groupIds = $request->group_id;
            //Ensure the $groupIds  is an array
            if (!is_array($groupIds)) {
                $groupIds = [$groupIds]; //Convert single ID to array.
            }
            $groups = Group::whereIn('id', $groupIds)->get();
            $invalidGroupIds = array_diff($groupIds, $groups->pluck('id')->toArray());
            if ($invalidGroupIds)
                return response()->json(["error" => "Group ID's" . implode(',' . $invalidGroupIds . "do not exist!")]);

            $usersToDetach = [];
            foreach ($users as $user) {
                foreach ($groups as $group) {
                    if ($user->groups()->where('group_id', $group->id)->exists()) {
                        $usersToDetach[] = $user->id;
                    }
                }
            }
            //Detach users from the group: remove from the user_groups table;
            if (!empty($usersToDetach)) {
                $group->users()->detach($usersToDetach);
                return response()->json("Users successfully removed from the group!");
            }
        } catch (\Throwable $th) {
            return response()->json("Error occured: " . $th->getMessage());
        }
    }

    public function moveToGroup()
    {
        //To be done...
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
}
