<?php

namespace App\Services;

use App\Models\TestLog;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class TestQuestionService
{
    public function getQuestionForm(int $testId, int $testLogId, string $formName): string
    {
        // Implementation to generate question form HTML
        return '';
    }

    public function updateQuestionLog(
        int $testId,
        int $testLogId,
        array $answerPositions,
        string $answerText,
        int $reactionTime
    ): void {
        DB::update("
    UPDATE test_logs
    SET
        testlog_answer_positions = ?,
        testlog_answer_text = ?,
        testlog_reaction_time = ?,
        testlog_change_time = ?
    WHERE testlog_id = ?
    AND testlog_testuser_id = (
        SELECT testuser_id
        FROM test_user
        WHERE testuser_test_id = ?
        AND testuser_user_id = ?
    )
", [
            json_encode($answerPositions),
            $answerText,
            $reactionTime,
            now(),
            $testLogId,
            $testId,
            auth()->id()
        ]);
    }

    public function getOmittedQuestionsCount(int $testId): int
    {
        $count = DB::select("
    SELECT COUNT(*) AS count
    FROM test_logs
    WHERE testlog_testuser_id IN (
        SELECT testuser_id
        FROM test_user
        WHERE testuser_test_id = ?
        AND testuser_user_id = ?
    )
    AND (testlog_display_time IS NULL OR testlog_change_time IS NULL)
", [$testId, auth()->id()]);

        $countValue = $count[0]->count; // Access the count value
        return $countValue;
    }
}
