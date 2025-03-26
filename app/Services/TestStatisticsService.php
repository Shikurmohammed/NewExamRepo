<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TestStatisticsService
{
    protected $testService;
    protected $normalizer;

    public function __construct(TestService $testService, TestStatisticsNormalizer $normalizer)
    {
        $this->testService = $testService;
        $this->normalizer = $normalizer;
    }

    /**
     * Get combined test data and user statistics
     */
    public function getUserTestStats($testId, $userId = 0, $testuserId = 0, $publicMode = false)
    {
        $testData = $this->testService->getTestData($testId)->toArray();
        $userTotals = $this->getUserTestTotals($testId, $userId, $testuserId, $publicMode);
        return array_merge($testData, $userTotals);
    }

    /**
     * Get user-specific test statistics
     */
    public function getUserTestTotals($testId, $userId = 0, $testuserId = 0, $publicMode = false)
    {
        $data = [];
        $statusFilter = $publicMode ? 3 : 0;

        if ($testId > 0 && $userId > 0 && $testuserId > 0) {
            $result = DB::table('tests_users')
                ->join(
                    'test_logs',
                    'tests_users.users_id ',
                    '=',
                    'test_logs.tests_users_id '
                )
                ->select(
                    DB::raw('SUM(test_logs.score) as total_score'),
                    DB::raw('MAX(test_logs.change_time) as test_end_time'),
                    'test_users.user_id',
                    'test_users.created_at',
                    'test_users.status',
                    'test_users.comment'
                )
                ->where('test_users.user_id', $testuserId)
                ->where('test_users.test_id', $testId)
                ->where('test_users.user_id', $userId)
                ->where('test_users.status', '>', $statusFilter)
                ->groupBy([
                    'test_users.user_id',
                    'test_users.created_at',
                    'test_users.status',
                    'test_users.comment'
                ])
                ->first();

            if ($result) {
                $data = [
                    'user_id' => $result->testuser_id,
                    'score' => $result->total_score,
                    'start' => $result->created_at,
                    'end' => $result->test_end_time,
                    'status' => $result->status,
                    'comment' => $result->comment
                ];
            }
        }

        return $data;
    }


    /**
     * Get normalized test statistics
     */
    public function getTestStats($testId, $groupId = 0, $userId = 0, $startDate = null, $endDate = null, $testuserId = 0, $publicMode = false)
    {
        $data = $this->getRawTestStats($testId, $groupId, $userId, $startDate, $endDate, $testuserId, [], $publicMode);

        if (isset($data['qstats']['recurrence'])) {
            return $this->normalizer->normalize($data);
        }

        return $data;
    }

    /**
     * Get raw test statistics data
     */
    public function getRawTestStats($testId, $groupId = 0, $userId = 0, $startDate = null, $endDate = null, $testuserId = 0, $data = [], $publicMode = false)
    {
        // If no specific test ID, get all authorized tests
        if ($testId == 0) {
            $query = DB::table('tests_users')
                ->select('test_id')
                ->groupBy('test_id')
                ->orderBy('test_id');

            $this->applyFilters($query, $testId, $groupId, $userId, $startDate, $endDate, $testuserId, $publicMode);

            $testIds = $query->pluck('test_id')->filter(function ($tid) {
                return $this->isAuthorizedUser('tests', 'test_id', $tid, 'user_id');
            });

            foreach ($testIds as $tid) {
                $data = $this->getRawTestStats($tid, $groupId, $userId, $startDate, $endDate, $testuserId, $data, $publicMode);
            }

            return $data;
        }

        $testData = $this->testService->getTestData($testId);

        // Initialize data structure if not set
        if (!isset($data['qstats'])) {
            $data['qstats'] = $this->initializeStatsStructure();
        }

        // Main query for question statistics
        $query = DB::table('tests_users')
            ->join('test_logs', 'tests_users.user_id', '=', 'test_logs.tests_users_id ')
            ->join('questions', 'test_logs.question_id ', '=', 'questions.id')
            ->join('topics', 'questions.topic_id', '=', 'topics.id')
            ->join('modules', 'topics.module_id', '=', 'modules.id')
            ->select(
                'modules.id',
                'topics.id',
                'questions.id',
                'modules.name',
                'topics.name',
                'topics.description',
                'questions.description',
                DB::raw('COUNT(questions.id) AS recurrence'),
                DB::raw('AVG(test_logs.score) AS average_score'),
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, test_logs.display_time, test_logs.change_time)) AS average_time'),
                DB::raw('MIN(questions.type) AS question_type'),
                DB::raw('MIN(questions.difficulty) AS question_difficulty')
            );

        if ($userId > 0 && $testuserId > 0) {
            $query->addSelect(
                'test_logs.score',
                'test_logs.user_ip',
                'test_logs.display_time',
                'test_logs.change_time',
                'test_logs.reaction_time',
                'test_logs.answer_text',
                'questions.type',
                'questions.explanation'
            );
        }

        $this->applyFilters($query, $testId, $groupId, $userId, $startDate, $endDate, $testuserId, $publicMode);

        $query->groupBy(
            'modules.id',
            'topics.id',
            'questions.id',
            'modules.name',
            'topics.name',
            'topics.description',
            'questions.description'
        );

        if (!($userId > 0 && $testuserId > 0)) {
            $query->orderBy('modules.name')
                ->orderBy('topics.name')
                ->orderBy('questions.description');
        }

        $results = $query->get();

        foreach ($results as $result) {
            $questionMaxScore = $testData['score_right'] * $result->question_difficulty;
            $questionHalfScore = $questionMaxScore / 2;

            // Get counts for different answer states
            $counts = $this->getQuestionCounts(
                $testId,
                $groupId,
                $userId,
                $startDate,
                $endDate,
                $testuserId,
                $result->question_id,
                $questionHalfScore,
                $publicMode
            );

            // Process the statistics for this question
            $data = $this->processQuestionStats(
                $data,
                $result,
                $counts,
                $questionMaxScore,
                $testId,
                $groupId,
                $userId,
                $startDate,
                $endDate,
                $testuserId,
                $publicMode
            );
        }

        return $data;
    }

    /**
     * Initialize the statistics data structure
     */
    protected function initializeStatsStructure()
    {
        return [
            'recurrence' => 0,
            'recurrence_perc' => 0,
            'average_score' => 0,
            'average_score_perc' => 0,
            'average_time' => 0,
            'right' => 0,
            'right_perc' => 0,
            'wrong' => 0,
            'wrong_perc' => 0,
            'unanswered' => 0,
            'unanswered_perc' => 0,
            'undisplayed' => 0,
            'undisplayed_perc' => 0,
            'unrated' => 0,
            'unrated_perc' => 0,
            'qnum' => 0,
            'module' => [],
        ];
    }

    /**
     * Apply filters to the query
     */
    protected function applyFilters($query, $testId, $groupId, $userId, $startDate, $endDate, $testuserId, $publicMode)
    {
        $query->where('test_logs.tests_users_id', '=', 'tests_users.user_id');

        if ($publicMode) {
            $testIdsResults = $this->getTestIdResults($testId, $userId);
            $query->whereIn('tests_users.test_id', $testIdsResults)
                ->where('tests_users.status', '>', 3);
        }

        if ($testId > 0) {
            $query->where('tests_users.test_id', $testId);
        }

        if ($userId > 0) {
            $query->join('users', 'tests_users.user_id', '=', 'users.id')
                ->where('users.id', $userId);

            if ($testuserId > 0) {
                $query->where('tests_users.user_id', $testuserId);
            }
        } elseif ($groupId > 0) {
            $query->join('users', 'tests_users.user_id', '=', 'users.id')
                ->join('user_groups', 'users.id', '=', 'user_groups.user_id')
                ->where('user_groups.group_id', $groupId);
        }

        if (!empty($startDate)) {
            $query->where('tests_users.created_at', '>=', Carbon::parse($startDate));
        }

        if (!empty($endDate)) {
            $query->where('tests_users.created_at', '<=', Carbon::parse($endDate));
        }
    }

    /**
     * Get counts for question states (right, wrong, etc.)
     */
    protected function getQuestionCounts($testId, $groupId, $userId, $startDate, $endDate, $testuserId, $questionId, $halfScore, $publicMode)
    {
        $baseQuery = DB::table('tests_users')
            ->join('test_logs', 'tests_users.user_id', '=', 'test_logs.tests_users_id ')
            ->where('test_logs.question_id', $questionId);

        $this->applyFilters($baseQuery, $testId, $groupId, $userId, $startDate, $endDate, $testuserId, $publicMode);

        return [
            'right' => (clone $baseQuery)->where('test_logs.score', '>', $halfScore)->count(),
            'wrong' => (clone $baseQuery)->where('test_logs.score', '<=', $halfScore)->count(),
            'unanswered' => (clone $baseQuery)->whereNull('test_logs.change_time')->count(),
            'undisplayed' => (clone $baseQuery)->whereNull('test_logs.display_time')->count(),
            'unrated' => (clone $baseQuery)->whereNull('test_logs.score')->count(),
            'all_answers' => DB::table('tests_users')
                ->join('test_logs', 'tests_users.user_id', '=', 'test_logs.tests_users_id ')
                ->join('answer_logs', 'test_logs.id', '=', 'answer_logs.test_logs_id')
                ->join('answers', 'answer_logs.answer_id', '=', 'answers.id')
                ->where('test_logs.question_id', $questionId)
                ->when($testId > 0, fn($q) => $q->where('tests_users.test_id', $testId))
                ->when($userId > 0, fn($q) => $q->where('tests_users.user_id', $userId))
                ->when($testuserId > 0, fn($q) => $q->where('tests_users.id', $testuserId))
                ->when(!empty($startDate), fn($q) => $q->where('tests_users.created_at', '>=', Carbon::parse($startDate)))
                ->when(!empty($endDate), fn($q) => $q->where('tests_users.created_at', '<=', Carbon::parse($endDate)))
                ->count()
        ];
    }

    /**
     * Process statistics for a single question
     */
    protected function processQuestionStats($data, $result, $counts, $questionMaxScore, $testId, $groupId, $userId, $startDate, $endDate, $testuserId, $publicMode)
    {
        $moduleKey = "'{$result->module_id}'";
        $topicKey = "'{$result->topic_id}'";
        $questionKey = "'{$result->question_id}'";

        // Initialize structures if not exists
        if (!isset($data['qstats']['module'][$moduleKey])) {
            $data['qstats']['module'][$moduleKey] = $this->initializeModuleStructure($result);
        }

        if (!isset($data['qstats']['module'][$moduleKey]['subject'][$topicKey])) {
            $data['qstats']['module'][$moduleKey]['subject'][$topicKey] = $this->initializeSubjectStructure($result);
        }

        if (!isset($data['qstats']['module'][$moduleKey]['subject'][$topicKey]['question'][$questionKey])) {
            $data['qstats']['module'][$moduleKey]['subject'][$topicKey]['question'][$questionKey] = $this->initializeQuestionStructure($result);
        }

        $averageScorePerc = $questionMaxScore > 0 ? $result->average_score / $questionMaxScore : 0;

        // Update question stats
        $question = &$data['qstats']['module'][$moduleKey]['subject'][$topicKey]['question'][$questionKey];
        $question['qnum']++;
        $question['recurrence'] += $result->recurrence;
        $question['average_score'] += $result->average_score;
        $question['average_score_perc'] += $averageScorePerc;
        $question['average_time'] += $result->average_time;
        $question['right'] += $counts['right'];
        $question['wrong'] += $counts['wrong'];
        $question['unanswered'] += $counts['unanswered'];
        $question['undisplayed'] += $counts['undisplayed'];
        $question['unrated'] += $counts['unrated'];
        $question['anum'] += $counts['all_answers'];

        // Update subject stats
        $subject = &$data['qstats']['module'][$moduleKey]['subject'][$topicKey];
        $subject['qnum']++;
        $subject['recurrence'] += $result->recurrence;
        $subject['average_score'] += $result->average_score;
        $subject['average_score_perc'] += $averageScorePerc;
        $subject['average_time'] += $result->average_time;
        $subject['right'] += $counts['right'];
        $subject['wrong'] += $counts['wrong'];
        $subject['unanswered'] += $counts['unanswered'];
        $subject['undisplayed'] += $counts['undisplayed'];
        $subject['unrated'] += $counts['unrated'];

        // Update module stats
        $module = &$data['qstats']['module'][$moduleKey];
        $module['qnum']++;
        $module['recurrence'] += $result->recurrence;
        $module['average_score'] += $result->average_score;
        $module['average_score_perc'] += $averageScorePerc;
        $module['average_time'] += $result->average_time;
        $module['right'] += $counts['right'];
        $module['wrong'] += $counts['wrong'];
        $module['unanswered'] += $counts['unanswered'];
        $module['undisplayed'] += $counts['undisplayed'];
        $module['unrated'] += $counts['unrated'];

        // Update totals
        $data['qstats']['qnum']++;
        $data['qstats']['recurrence'] += $result->recurrence;
        $data['qstats']['average_score'] += $result->average_score;
        $data['qstats']['average_score_perc'] += $averageScorePerc;
        $data['qstats']['average_time'] += $result->average_time;
        $data['qstats']['right'] += $counts['right'];
        $data['qstats']['wrong'] += $counts['wrong'];
        $data['qstats']['unanswered'] += $counts['unanswered'];
        $data['qstats']['undisplayed'] += $counts['undisplayed'];
        $data['qstats']['unrated'] += $counts['unrated'];

        // Get answer statistics if needed
        if ($userId > 0 && $testuserId > 0) {
            $this->processAnswerStats($data, $testId, $groupId, $userId, $startDate, $endDate, $testuserId, $result->question_id, $publicMode);
        }

        return $data;
    }

    /**
     * Process statistics for answers
     */
    protected function processAnswerStats(&$data, $testId, $groupId, $userId, $startDate, $endDate, $testuserId, $questionId, $publicMode)
    {
        $query = DB::table('tests_users')
            ->join('test_logs', 'tests_users.user_id', '=', 'test_logs.testlog_testuser_id')
            ->join('answer_logs', 'test_logs.id', '=', 'answer_logs.test_logs_id')
            ->join('answers', 'answer_logs.answer_id', '=', 'answers.id')
            ->select(
                'answers.id',
                'answers.description',
                DB::raw('COUNT(answers.id) AS recurrence'),
                'answer_logs.position',
                'answer_logs.is_selected',
                'answers.is_right',
                'answers.position',
                'answers.explanation'
            )
            ->where('test_logs.question_id', $questionId);

        $this->applyFilters($query, $testId, $groupId, $userId, $startDate, $endDate, $testuserId, $publicMode);

        $answers = $query->groupBy(
            'answers.id',
            'answers.description',
            'answer_logs.position',
            'answer_logs.is_selected',
            'answers.is_right',
            'answers.position',
            'answers.explanation'
        )
            ->orderBy('answers.description')
            ->get();

        foreach ($answers as $answer) {
            $rightCount = DB::table('tests_users')
                ->join('test_logs', 'tests_users.user_id', '=', 'test_logs.tests_users_id ')
                ->join('answer_logs', 'test_logs.id', '=', 'answer_logs.test_logs_id')
                ->join('answers', 'answer_logs.answer_id', '=', 'answers.id')
                ->where('test_logs.question_id', $questionId)
                ->where('answers.id', $answer->answer_id)
                ->where(function ($q) use ($answer) {
                    $q->where(function ($q) {
                        $q->where('answers.is_right', '0')
                            ->where('answer_logs.logansw_selected', 0);
                    })->orWhere(function ($q) {
                        $q->where('answers.is_right', '1')
                            ->where('answer_logs.is_selected', 1);
                    })->orWhere(function ($q) {
                        $q->whereNotNull('answers.position')
                            ->whereNotNull('answer_logs.position')
                            ->whereColumn('answers.position', 'answer_logs.position');
                    });
                })
                ->count();

            $wrongCount = DB::table('tests_users')
                // Similar query as above but for wrong answers
                // Implementation omitted for brevity
                ->count();

            $unansweredCount = DB::table('tests_users')
                ->join('test_logs', 'tests_users.user_id', '=', 'test_logs.tests_users_id')
                ->join('answer_logs', 'test_logs.testlog_id', '=', 'answer_logs.test_logs_id')
                ->join('answers', 'answer_logs.answer_id', '=', 'answers.answer_id')
                ->where('test_logs.question_id', $questionId)
                ->where('answers.answer_id', $answer->answer_id)
                ->where('answer_logs.is_selected', -1)
                ->count();

            // Update answer stats in the data structure
            // Implementation omitted for brevity
        }
    }

    /**
     * Normalize statistics averages
     */
    protected function normalizeTestStatAverages($data)
    {
        // Implementation of normalization logic
        // This would calculate percentages and averages across the structure
        return $data;
    }

    /**
     * Initialize module structure
     */
    protected function initializeModuleStructure($result)
    {
        return [
            'id' => $result->module_id,
            'name' => $result->module_name,
            'recurrence' => 0,
            'recurrence_perc' => 0,
            'average_score' => 0,
            'average_score_perc' => 0,
            'average_time' => 0,
            'right' => 0,
            'right_perc' => 0,
            'wrong' => 0,
            'wrong_perc' => 0,
            'unanswered' => 0,
            'unanswered_perc' => 0,
            'undisplayed' => 0,
            'undisplayed_perc' => 0,
            'unrated' => 0,
            'unrated_perc' => 0,
            'qnum' => 0,
            'subject' => [],
        ];
    }

    /**
     * Initialize subject structure
     */
    protected function initializeSubjectStructure($result)
    {
        return [
            'id' => $result->subject_id,
            'name' => $result->subject_name,
            'description' => $result->subject_description,
            'recurrence' => 0,
            'recurrence_perc' => 0,
            'average_score' => 0,
            'average_score_perc' => 0,
            'average_time' => 0,
            'right' => 0,
            'right_perc' => 0,
            'wrong' => 0,
            'wrong_perc' => 0,
            'unanswered' => 0,
            'unanswered_perc' => 0,
            'undisplayed' => 0,
            'undisplayed_perc' => 0,
            'unrated' => 0,
            'unrated_perc' => 0,
            'qnum' => 0,
            'question' => [],
        ];
    }

    /**
     * Initialize question structure
     */
    protected function initializeQuestionStructure($result)
    {
        return [
            'id' => $result->question_id,
            'description' => $result->question_description,
            'type' => $result->question_type,
            'difficulty' => $result->question_difficulty,
            'recurrence' => 0,
            'recurrence_perc' => 0,
            'average_score' => 0,
            'average_score_perc' => 0,
            'average_time' => 0,
            'right' => 0,
            'right_perc' => 0,
            'wrong' => 0,
            'wrong_perc' => 0,
            'unanswered' => 0,
            'unanswered_perc' => 0,
            'undisplayed' => 0,
            'undisplayed_perc' => 0,
            'unrated' => 0,
            'unrated_perc' => 0,
            'qnum' => 0,
            'anum' => 0,
            'answer' => [],
        ];
    }

    /**
     * Check if user is authorized
     */
    protected function isAuthorizedUser($table, $idField, $idValue, $userIdField)
    {
        // Implementation depends on your authorization system
        return true;
    }

    /**
     * Get test IDs with results for user
     */
    protected function getTestIdResults($testId, $userId)
    {
        // Implementation depends on your test result system
        return [$testId];
    }
}
