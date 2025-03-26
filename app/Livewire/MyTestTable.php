<?php

namespace App\Livewire;

use App\Models\Test;
use App\Services\TestAuthorizationService;
use App\Services\TestService;
use App\Services\TestStatisticsService;
use App\Services\TestStatusService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class MyTestTable extends PowerGridComponent
{
    public string $tableName = 'my-test-table-uvvt7f-table';
    protected $testStatusService;
    protected $testStatsService;
    protected $testService;
    protected $testAuthService;

    public function boot(
        TestStatusService $testStatusService,
        TestStatisticsService $testStatsService,
        TestService $testService,
        TestAuthorizationService $testAuthService
    ) {
        $this->testStatusService = $testStatusService;
        $this->testStatsService = $testStatsService;
        $this->testService = $testService;
        $this->testAuthService = $testAuthService;
    }

    public function setUp(): array
    {
        $this->showCheckBox();
        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }
    public $tests = [];

    public function datasource()
    {
        $userId = Auth::id();
        $currentTime = now();
        $hideExpiredTests = config('tests.hide_expired_tests');
        // Fetch tests with conditions
        $tests = Test::whereIn('id', function ($query) {
            $query->select('test_id')->from('test_topic_sets');
        })
            ->where('start', '<', $currentTime)
            ->when($hideExpiredTests, function ($query) use ($currentTime) {
                return $query->where('end', '>', $currentTime);
            })
            ->orderByDesc('start')
            ->get(); // Get all tests based on the above conditions

        // Filter the tests based on user validation

        $filteredTests = $tests->filter(function ($test) use ($userId) {
            return $this->testAuthService->isValidTestUser($test->id, request()->ip(), $test->ip_range);
        });
        // dd($filteredTests);

        return $filteredTests; // Return the filtered tests
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->searchable()->sortable(),
            Column::make('Exam Name', 'name')->searchable()->sortable(),
            Column::make('Description', 'description'),
            Column::make('Start at', 'start'),
            Column::make('End at', 'end'),
            Column::make('Status', 'status'),
            Column::action('Action'), // Make sure to define the action column
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name', function ($test) {
                return $this->testInfoLink($test->id, $test->name);
            })
            ->add('start', function ($test) {
                return $test->start;
            })
            ->add('end', function ($test) {
                return $test->end;
            })
            ->add('status', function ($test) {
                $userId = Auth::id();
                [$testStatus, $testuserId] = $this->testStatusService->checkTestStatus($userId, $test->id, $test->duration);
                $statusHtml = '&nbsp;';

                if ($testStatus >= 0 && !$test->result_to_user) { // if ($testStatus >= 4 && $test->result_to_user)

                    $userTestData = $this->testStatsService->getUserTestStats($test->id, $userId, $testuserId);
                    $passMsg = '';

                    if (isset($userTestData['user_score'])) {
                        // Format score display
                        if ($userTestData['max_score'] > 0) {
                            $percentage = round(100 * $userTestData['user_score'] / $userTestData['max_score']);
                            $statusHtml = "{$userTestData['user_score']} / {$userTestData['max_score']} ($percentage%)";
                        } else {
                            $statusHtml = $userTestData['user_score'];
                        }
                    }
                }

                return $statusHtml;
            });
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name'),
            Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Test $row): array
    {
        $userId = Auth::id();
        $currentTime = Carbon::now();
        $expired = $currentTime->greaterThanOrEqualTo(Carbon::parse($row->end));
        [$testStatus, $testuserId] = $this->testStatusService->checkTestStatus($userId, $row->id, $row->duration);
        if ($expired) {
            return []; // Return an empty array if expired
        }
        $buttons = []; // Initialize an array to hold the buttons
        switch ($testStatus) {
            case 0:
                $url = config('tce.display_test_description') || !empty($row->password)
                    ? 'start_exam'
                    : 'execute_exam';
                $buttons[] = Button::make('execute', 'Execute')
                    ->slot(' &#128065; Execute')
                    ->class('
                      flex gap-2 hover:text-slate-700 hover:bg-slate-100
                      font-bold p-1 px-2 rounded dark:ring-pg-primary-600 text-blue-300')
                    ->route($url, ['testId' => $row->id], '_blank');
                break;

            case 1:
            case 2:
            case 3:
                $buttons[] = Button::make('continue', 'Continue')
                    ->slot(' &#128065; Continue')
                    ->class('flex gap-2 hover:text-slate-700 hover:bg-slate-100 font-bold p-1 px-2 rounded dark:ring-pg-primary-600 text-blue-300')
                    ->route('test.execute', ['testId' => $row->id]);
                break;

            default:
                if ($this->countUserTest($userId, $row->id) < $row->repeatable || $row->repeatable == 1) {
                    $url = config('tce.display_test_description') || !empty($row->password)
                        ? 'test.start'
                        : 'test.execute';
                    $buttons[] = Button::make('repeat', 'Repeat')
                        ->slot(' &#128065; view')
                        ->class('flex gap-2 hover:text-slate-700 hover:bg-slate-100 font-bold p-1 px-2 rounded dark:ring-pg-primary-600 text-blue-300')
                        ->route($url, ['testid' => $row->id, 'repeat' => 1]);
                }
                break;
        }
        return $buttons; // Return the array of buttons
    }

    protected function countUserTest($userId, $testId)
    {
        // Implement counting user tests
    }

    protected function testInfoLink($testId, $linkName = '')
    {
        // Implement test info link generation
    }
}
