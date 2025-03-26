<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarkInActiveUsersOffline extends Command
{
    protected $signature = 'users:offline';
    protected $description = 'Mark inactive users as offline';
    public function handle()
    {
        // Define the inactive time
        $inactiveTime = Carbon::now()->subMinutes(5);
        // Get users who are marked as online and haven't updated in the last 5 minutes
        $users = User::where('is_online', 1)
            ->where('updated_at', '<', $inactiveTime)
            ->get(); // .get() will return an Eloquent collection of users

        // Now loop through and mark them as offline
        foreach ($users as $user) {
            // Mark each user as offline
            DB::table('users')->where('id', $user->id)->update(['is_online' => 0]);
            //Log::info('User ' . $user->id . ' marked as offline');
        }
    }
}
