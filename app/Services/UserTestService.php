<?php

namespace App\Services;

use App\Models\Test;
use App\Models\TestSubjset;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserTestService
{
    protected $testAuthorizationService;
    protected $testStatusService;
    protected $testCountService;
    protected $testTestStatService;

    public function __construct(
        TestAuthorizationService $testAuthorizationService,
        TestStatusService $testStatusService,
        TestCountService $testCountService,
        TestStatisticsService $testTestStatService
    ) {
        $this->testAuthorizationService = $testAuthorizationService;
        $this->testStatusService = $testStatusService;
        $this->testCountService = $testCountService;
        $this->testTestStatService = $testTestStatService;
    }

    public function getUserTests(): string
    {
        $tests = $this->getAvailableTests();
        $html = '';

        foreach ($tests as $test) {
            if ($this->testAuthorizationService->isValidTestUser(
                $test->id,
                request()->ip(),
                $test->ip_range
            )) {
                $html .= $this->renderTestRow($test);
            }
        }

        return $this->wrapInTable($html);
    }

    protected function getAvailableTests()
    {
        $query = DB::select(
            "SELECT *
                    FROM tests
                    WHERE id IN (
                        SELECT test_id
                        FROM test_topic_sets
                    )
                    AND start < ?
                    " . (config('tests.hide_expired_tests', true) ? "AND end > ?" : "") . "
                    ORDER BY start DESC
                ",
            array_filter([Carbon::now(), Carbon::now()])
        );
        return  $query;
    }

    protected function renderTestRow(Test $test): string
    {
        [$testStatus, $testuserId] = $this->testStatusService->checkTestStatus(
            Auth::id(),
            $test->id,
            $test->duration
        );

        $isExpired = Carbon::now() >= Carbon::parse($test->end);
        $dateStyle = $isExpired ? ' style="color:#666666;"' : '';

        $row = '<tr>';
        $row .= $this->renderTestNameCell($test);
        $row .= $this->renderDateCell($test->start, $dateStyle);
        $row .= $this->renderDateCell($test->end, $dateStyle);
        $row .= $this->renderStatusCell($test, $testStatus, $testuserId);
        $row .= $this->renderActionCell($test, $testStatus, $isExpired);
        $row .= '</tr>';

        return $row;
    }

    protected function renderTestNameCell(Test $test): string
    {
        $cellClass = !empty($test->password) ? ' style="background-color:#ffffcc;"' : '';
        return '<td' . $cellClass . '><strong>' . $this->getTestInfoLink($test) . '</strong></td>';
    }

    protected function renderDateCell(string $date, string $style): string
    {
        return '<td' . $style . '>' . $date . '</td>';
    }

    protected function renderStatusCell(Test $test, int $testStatus, int $testuserId): string
    {
        $cell = '<td';
        $content = '&nbsp;';

        if ($testStatus >= 4 && $test->result_to_user) {
            $userTestData = $this->testTestStatService->getUserTestStats(
                $test->id,
                Auth::id(),
                $testuserId
            );

            if (isset($userTestData['user_score'])) {
                $cell .= $this->getStatusCellStyle($userTestData);
                $content = $this->getResultLink($userTestData, $testuserId, $test->id);
            }
        }

        return $cell . '>' . $content . '</td>';
    }

    protected function getStatusCellStyle(array $userTestData): string
    {
        if (isset($userTestData['score_threshold']) && $userTestData['score_threshold'] > 0) {
            return $userTestData['user_score'] >= $userTestData['score_threshold']
                ? ' style="background-color:#ddffdd;"'
                : ' style="background-color:#ffdddd;"';
        }
        return '';
    }

    protected function getResultLink(array $userTestData, int $testuserId, int $testId): string
    {
        $passMsg = $this->getPassMessage($userTestData);

        if ($userTestData['test_max_score'] > 0) {
            $percentage = round(100 * $userTestData['user_score'] / $userTestData['test_max_score']);
            return '<a href="' . route('test.results', [
                'testuser_id' => $testuserId,
                'test_id' => $testId
            ]) . '" title="' . __('h_result') . '">' .
                $userTestData['user_score'] . ' / ' . $userTestData['test_max_score'] .
                ' (' . $percentage . '%)' . $passMsg . '</a>';
        }

        return '<a href="' . route('test.results', [
            'testuser_id' => $testuserId,
            'test_id' => $testId
        ]) . '" title="' . __('h_result') . '">' .
            $userTestData['user_score'] . $passMsg . '</a>';
    }

    protected function getPassMessage(array $userTestData): string
    {
        if (isset($userTestData['test_score_threshold']) && $userTestData['test_score_threshold'] > 0) {
            return $userTestData['user_score'] >= $userTestData['test_score_threshold']
                ? ' - ' . __('w_passed')
                : ' - ' . __('w_not_passed');
        }
        return '';
    }

    protected function renderActionCell(Test $test, int $testStatus, bool $isExpired): string
    {
        if ($isExpired) {
            return '<td style="text-align:center;"></td>';
        }

        switch ($testStatus) {
            case 0:
                return $this->renderStartTestLink($test);
            case 1:
            case 2:
            case 3:
                return $this->renderContinueTestLink($test);
            default:
                return $this->renderRepeatTestLink($test);
        }
    }

    protected function renderStartTestLink(Test $test): string
    {
        $url = config('tce.display_test_description') || !empty($test->test_password)
            ? route('test.start', $test->test_id)
            : route('test.execute', $test->test_id);

        return '<td style="text-align:center;">' .
            '<a href="' . $url . '" title="' . __('h_execute') . '" class="btn btn-success">' .
            __('w_execute') . '</a></td>';
    }

    protected function renderContinueTestLink(Test $test): string
    {
        return '<td style="text-align:center;">' .
            '<a href="' . route('test.execute', $test->test_id) . '" title="' . __('h_continue') . '" class="btn btn-primary">' .
            __('w_continue') . '</a></td>';
    }

    protected function renderRepeatTestLink(Test $test): string
    {
        $userTestCount = $this->testCountService->countUserTests(Auth::id(), $test->test_id);

        if ($userTestCount < $test->test_repeatable || $test->test_repeatable == 1) {
            $url = config('tce.display_test_description') || !empty($test->test_password)
                ? route('test.start', ['testid' => $test->test_id, 'repeat' => 1])
                : route('test.execute', ['testid' => $test->test_id, 'repeat' => 1]);

            return '<td style="text-align:center;">' .
                '<a href="' . $url . '" title="' . __('h_repeat_test') . '" class="btn btn-info">' .
                __('w_repeat') . '</a></td>';
        }

        return '<td style="text-align:center;"></td>';
    }

    protected function wrapInTable(string $content): string
    {
        if (empty($content)) {
            return __('m_no_test_available');
        }

        return '<table class="testlist">
            <tr>
                <th>' . __('w_test') . '</th>
                <th>' . __('w_from') . '</th>
                <th>' . __('w_to') . '</th>
                <th>' . __('w_status') . '</th>
                <th>' . __('w_action') . '</th>
            </tr>
            ' . $content . '
        </table>';
    }

    protected function getTestInfoLink($test): string
    {
        return '<a href="' . route('test.info', $test->id) . '"
            onclick="window.open(this.href,\'testInfoWindow\',\'height=600,width=800,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no\');return false;"
            title="' . __('m_new_window_link') . '">' . htmlspecialchars($test->name) . '</a>';
    }
}
