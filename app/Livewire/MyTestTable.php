<?php

namespace App\Livewire;

use App\Models\Test;
use App\Models\User;
use App\Services\TestAuthorizationService;
use App\Services\TestCountService;
use App\Services\TestService;
use App\Services\TestStatisticsService;
use App\Services\TestStatusService;
use App\Services\UserTestService;
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
    protected $userTestService;
    protected $testCountService;

    public function boot(
        TestStatusService $testStatusService,
        TestStatisticsService $testStatsService,
        TestService $testService,
        TestAuthorizationService $testAuthService,
        UserTestService $userTestService,
        TestCountService $testCountService

    ) {
        $this->testStatusService = $testStatusService;
        $this->testStatsService = $testStatsService;
        $this->testService = $testService;
        $this->testAuthService = $testAuthService;
        $this->userTestService = $userTestService;
        $this->testCountService = $testCountService;
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

        //This is an implementation for F_getUsertests()
        $userId = Auth::id();
        $currentTime = now();
        // dd(now());
        $hideExpiredTests = config('tests.hide_expired_tests');
        // Fetch tests with conditions, and hiding old repeated tests
        $tests = Test::whereIn('id', function ($query) {
            $query->select('test_id')->from('test_topic_sets');
        })
            ->where('start', '<', $currentTime)
            ->when($hideExpiredTests, function ($query) use ($currentTime) {

                return $query->where('end', '>', $currentTime);
            })
            ->orderByDesc('start')
            ->get(); // Get all tests based on the above conditions
        // dd($tests);

        // Filter the tests based on user validation
        $filteredTests = $tests->filter(function ($test) use ($userId) {
            //echo $test;
            return $this->testAuthService->isValidTestUser($test->id, request()->ip(), $test->ip_range);
        });
        return $filteredTests; // Return the filtered tests
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->searchable()->sortable(),
            Column::make('Exam Name', 'name')->searchable()->sortable(),
            Column::make('Description', 'description'),
            Column::make('Start at', 'start'), //->editOnClick(true),
            Column::make('End at', 'end'), //->editOnClick(true),
            Column::make('Status', 'status'),
            Column::action('Action'), // Make sure to define the action column
        ];
    }
    //Inline Update
    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        //Validate before update
        $this->validate();

        Test::find($id)->update([$field => $value]);
    }
    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name', function ($test) {
                return $this->userTestService->getTestInfoLink($test);
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

                // dd([$testStatus, $testuserId]);
                //dd($test->result_to_user);
                if ($testStatus >= 4 || $test->result_to_user) { //&&
                    $userTestData = $this->testStatsService->getUserTestStats($test->id, $userId, $testuserId);
                    $passMsg = '';
                    //dd($userTestData);
                    $statusHtml = "0.0 %";

                    if (isset($userTestData['score'])) {
                        // Format score display
                        if ($userTestData['max_score'] > 0) {
                            $percentage = round(100 * $userTestData['score'] / $userTestData['max_score']);
                            $statusHtml = "{$userTestData['score']} / {$userTestData['max_score']} ($percentage%)";
                        } else {
                            $statusHtml = $userTestData['score'];
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
        // dd(Carbon::parse($row->end));
        // dd($currentTime);
        // if ($row->id == 3)
        //     dd(Carbon::parse($row->end));
        $expired = $currentTime->greaterThanOrEqualTo(Carbon::parse($row->end));
        [$testStatus, $testuserId] = $this->testStatusService->checkTestStatus($userId, $row->id, $row->duration);
        if ($expired) {
            return []; // Return an empty array if expired
        }
        $buttons = []; // Initialize an array to hold the buttons
        // display various action links by status case
        switch ($testStatus) {
            case 0: // 0 means the test generation process is started but not completed
                $url = config('tests.display_test_description') || !empty($row->password)
                    ? 'start_exam'
                    : 'execute_exam';
                $buttons[] = Button::make('execute', 'Execute')
                    ->slot(' &#128065; Execute')
                    ->class('
                      flex gap-2 hover:text-slate-700 hover:bg-slate-100
                      font-bold p-1 px-2 rounded dark:ring-pg-primary-600 text-blue-300')
                    ->route($url, ['testId' => $row->id], '_blank');
                //  dd($url);
                break;

            case 1: // 1 = the test has been successfully created
            case 2: // 2 = all questions have been displayed to the user
            case 3: // 3 = all questions have been answered
                // continue test
                {
                    $buttons[] = Button::make('continue', 'Continue')
                        ->slot(' &#128065; Continue')
                        ->class('flex gap-2 hover:text-slate-700 hover:bg-slate-100 font-bold p-1 px-2 rounded dark:ring-pg-primary-600 text-blue-300')
                        ->route('execute_exam', ['testId' => $row->id], '_blank');
                    break;
                }

            default: // 4 or greater = test can be repeated
                $countTests = $this->testCountService->countUserTests($userId, $row->id);
                if ($countTests < $row->repeatable || $row->repeatable == 1) {
                    $url = config('tests.display_test_description') || !empty($row->password)
                        ? 'start_exam'
                        : 'execute_exam';
                    $buttons[] = Button::make('repeat', 'Repeat')
                        ->slot(' &#128065; Repeat')
                        ->class('flex gap-2 hover:text-slate-700 hover:bg-slate-100 font-bold p-1 px-2 rounded dark:ring-pg-primary-600 text-blue-300')
                        ->route($url, ['testId' => $row->id, 'repeat' => 1], '_blank'); //->attributes(['target' => '_blank']);
                    // dd($url);
                }
                break;
        }
        return $buttons; // Return the array of buttons
    }
}
