<?php

namespace App\Services;

use App\Models\TestUser;
use Illuminate\Support\Facades\DB;

class TestCountService
{
    /**
     * Count how many times a user has taken a specific test
     *
     * @param int $userId
     * @param int $testId
     * @return int
     */
    //Counts user tests which as status >=4
    public function countUserTests(int $userId, int $testId): int
    {
        $count = DB::select("
                    SELECT COUNT(*) AS count
                    FROM tests_users
                    WHERE test_id = ?
                    AND user_id = ?
                    AND status >= 4
                ", [$testId, $userId]);
        $countValue = $count[0]->count; // Access the count value
        // dd($countValue);
        return $countValue;
    }

    /**
     * Generic row counter for any table with optional conditions
     *
     * @param string $table
     * @param array $conditions
     * @return int
     */
    public function countRows(string $table, array $conditions = []): int
    {
        $query = DB::table($table);

        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $query->whereIn($column, $value);
            } else {
                $query->where($column, $value);
            }
        }

        return $query->count();
    }
}
