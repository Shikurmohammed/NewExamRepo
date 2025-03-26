<?php

namespace App\Services;

class TestStatisticsNormalizer
{
    /**
     * Normalize test statistics by calculating percentages and averages
     */
    public function normalize(array $data): array
    {
        if (!isset($data['qstats']['recurrence']) || $data['qstats']['recurrence'] <= 0) {
            return $data;
        }

        $this->normalizeGlobalStats($data['qstats']);

        foreach ($data['qstats']['module'] as $moduleKey => &$module) {
            $this->normalizeModuleStats($module, $data['qstats']['recurrence']);

            foreach ($module['subject'] as $subjectKey => &$subject) {
                $this->normalizeSubjectStats($subject, $data['qstats']['recurrence']);

                foreach ($subject['question'] as $questionKey => &$question) {
                    $this->normalizeQuestionStats($question, $data['qstats']['recurrence']);

                    foreach ($question['answer'] as $answerKey => &$answer) {
                        $this->normalizeAnswerStats($answer, $question['anum']);
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Normalize global statistics
     */
    protected function normalizeGlobalStats(array &$stats): void
    {
        $stats['recurrence_perc'] = 100;
        $stats['average_score'] = $this->safeDivide($stats['average_score'], $stats['qnum']);
        $stats['average_score_perc'] = round($this->safeDivide(100 * $stats['average_score_perc'], $stats['recurrence']));
        $stats['average_time'] = $this->safeDivide($stats['average_time'], $stats['qnum']);
        $stats['right_perc'] = round($this->safeDivide(100 * $stats['right'], $stats['recurrence']));
        $stats['wrong_perc'] = round($this->safeDivide(100 * $stats['wrong'], $stats['recurrence']));
        $stats['unanswered_perc'] = round($this->safeDivide(100 * $stats['unanswered'], $stats['recurrence']));
        $stats['undisplayed_perc'] = round($this->safeDivide(100 * $stats['undisplayed'], $stats['recurrence']));
        $stats['unrated_perc'] = round($this->safeDivide(100 * $stats['unrated'], $stats['recurrence']));
    }

    /**
     * Normalize module statistics
     */
    protected function normalizeModuleStats(array &$module, int $totalRecurrence): void
    {
        $module['recurrence_perc'] = round($this->safeDivide(100 * $module['recurrence'], $totalRecurrence));
        $module['average_score'] = $this->safeDivide($module['average_score'], $module['qnum']);
        $module['average_score_perc'] = round($this->safeDivide(100 * $module['average_score_perc'], $module['recurrence']));
        $module['average_time'] = $this->safeDivide($module['average_time'], $module['qnum']);
        $module['right_perc'] = round($this->safeDivide(100 * $module['right'], $module['recurrence']));
        $module['wrong_perc'] = round($this->safeDivide(100 * $module['wrong'], $module['recurrence']));
        $module['unanswered_perc'] = round($this->safeDivide(100 * $module['unanswered'], $module['recurrence']));
        $module['undisplayed_perc'] = round($this->safeDivide(100 * $module['undisplayed'], $module['recurrence']));
        $module['unrated_perc'] = round($this->safeDivide(100 * $module['unrated'], $module['recurrence']));
    }

    /**
     * Normalize subject statistics
     */
    protected function normalizeSubjectStats(array &$subject, int $totalRecurrence): void
    {
        $subject['recurrence_perc'] = round($this->safeDivide(100 * $subject['recurrence'], $totalRecurrence));
        $subject['average_score'] = $this->safeDivide($subject['average_score'], $subject['qnum']);
        $subject['average_score_perc'] = round($this->safeDivide(100 * $subject['average_score_perc'], $subject['recurrence']));
        $subject['average_time'] = $this->safeDivide($subject['average_time'], $subject['qnum']);
        $subject['right_perc'] = round($this->safeDivide(100 * $subject['right'], $subject['recurrence']));
        $subject['wrong_perc'] = round($this->safeDivide(100 * $subject['wrong'], $subject['recurrence']));
        $subject['unanswered_perc'] = round($this->safeDivide(100 * $subject['unanswered'], $subject['recurrence']));
        $subject['undisplayed_perc'] = round($this->safeDivide(100 * $subject['undisplayed'], $subject['recurrence']));
        $subject['unrated_perc'] = round($this->safeDivide(100 * $subject['unrated'], $subject['recurrence']));
    }

    /**
     * Normalize question statistics
     */
    protected function normalizeQuestionStats(array &$question, int $totalRecurrence): void
    {
        $question['recurrence_perc'] = round($this->safeDivide(100 * $question['recurrence'], $totalRecurrence));
        $question['average_score'] = $this->safeDivide($question['average_score'], $question['qnum']);
        $question['average_score_perc'] = round($this->safeDivide(100 * $question['average_score_perc'], $question['recurrence']));
        $question['average_time'] = $this->safeDivide($question['average_time'], $question['qnum']);
        $question['right_perc'] = round($this->safeDivide(100 * $question['right'], $question['recurrence']));
        $question['wrong_perc'] = round($this->safeDivide(100 * $question['wrong'], $question['recurrence']));
        $question['unanswered_perc'] = round($this->safeDivide(100 * $question['unanswered'], $question['recurrence']));
        $question['undisplayed_perc'] = round($this->safeDivide(100 * $question['undisplayed'], $question['recurrence']));
        $question['unrated_perc'] = round($this->safeDivide(100 * $question['unrated'], $question['recurrence']));
    }

    /**
     * Normalize answer statistics
     */
    protected function normalizeAnswerStats(array &$answer, int $totalAnswers): void
    {
        $answer['recurrence_perc'] = round($this->safeDivide(100 * $answer['recurrence'], $totalAnswers));
        $answer['right_perc'] = round($this->safeDivide(100 * $answer['right'], $answer['recurrence']));
        $answer['wrong_perc'] = round($this->safeDivide(100 * $answer['wrong'], $answer['recurrence']));
        $answer['unanswered_perc'] = round($this->safeDivide(100 * $answer['unanswered'], $answer['recurrence']));
    }

    /**
     * Safe division to avoid division by zero
     */
    protected function safeDivide(float $numerator, float $denominator): float
    {
        return $denominator != 0 ? $numerator / $denominator : 0;
    }
}
