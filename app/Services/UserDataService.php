<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserDataService
{
    /**
     * Get user data by ID
     *
     * @param int $userId
     * @return array
     */
    public function getUserData(int $userId): array
    {
        try {
            $user = User::findOrFail($userId);
            return $this->formatUserData($user);
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    /**
     * Format user data for response
     */
    protected function formatUserData(User $user): array
    {
        return [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            // Add other fields as needed
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

    /**
     * Handle errors
     */
    protected function handleError(\Exception $e): void
    {
        // Log the error
        Log::error('User data retrieval failed: ' . $e->getMessage());

        // Optionally throw a custom exception
        // throw new UserDataException('Failed to retrieve user data');
    }
}
