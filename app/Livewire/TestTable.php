<?php

namespace App\Livewire;

use App\Models\Test;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Facades\Rule; // [!code ++]

final class TestTable extends PowerGridComponent
{
    public string $tableName = 'test-table-ocgpmu-table';

    protected $listeners = [
        'refreshTestTable' => '$refresh',
        'bulkLock' => 'handleBulkLock',
        'bulkUnlock' => 'handleBulkUnlock',
        'bulkDelete' => 'handleBulkDelete',
        // 'pg:checkboxChanged-' . $this->tableName => 'handleCheckboxChange'
    ];
    public string $mybuttonclass = 'w-auto mr-1 px-3 py-2 text-gray-600 transition bg-transparent
    bg-white border-0 rounded-md focus:ring-primary-600 focus-within:focus:ring-primary-600
    focus-within:ring-primary-600 dark:focus-within:ring-primary-600 ring-1 focus-within:ring-2
     dark:ring-pg-primary-600 dark:text-pg-primary-300 ring-gray-300 dark:bg-pg-primary-800
     dark:placeholder-pg-primary-400 ring-0 placeholder:text-gray-400 focus:outline-none sm:text-sm
     sm:leading-6';
    public function header(): array
    {
        return [
            Button::add('Lock')
                ->slot('Lock')
                ->class($this->mybuttonclass . ' border-slate-400 hover:text-slate-700 hover:bg-slate-100 font-bold  rounded dark:ring-pg-primary-600 text-red-300')
                ->dispatch('bulkLock', []),  // Dispatch specific action

            Button::add('Unlock', [])
                ->slot('Unlock')
                ->class($this->mybuttonclass . ' border-slate-400 hover:text-slate-700 hover:bg-slate-100 font-bold  rounded dark:ring-pg-primary-600 text-blue-300')
                ->dispatch('bulkUnlock', []),

            Button::add('Delete')
                ->slot('Delete')
                ->class($this->mybuttonclass . 'border-slate-400 hover:text-slate-700 hover:bg-slate-100 font-bold  rounded dark:ring-pg-primary-600 text-red-400')
                ->dispatch('bulkDelete', [])
        ];
    }
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->pageName('users')
                ->showPerPage(perPage: 10, perPageValues: [0, 1, 10, 25, 50])
                ->showRecordCount(),
            PowerGrid::exportable(fileName: 'my-export-file')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV)
                ->striped('A6ACCD'),

        ];
    }



    public function datasource(): Builder
    {
        return Test::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('created_at');
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Test $row): array
    {
        return [


            Button::add('view')
                ->id()
                ->slot('&#128065; view')
                ->class('
               flex gap-2 hover:text-slate-700 hover:bg-slate-100
                 font-bold p-1 px-2 rounded dark:ring-pg-primary-600 text-blue-300
                  ')
                ->openModal('modals.details-modals.test-details-modal', ['testId' => $row->id]),
            Button::add('edit')
                ->slot('&#9889; Edit')
                ->id()
                ->class('flex gap-2 hover:text-slate-700 hover:bg-slate-100
                 font-bold p-1 px-2 rounded dark:ring-pg-primary-600
                  dark:border-pg-primary-600 dark:hover:bg-pg-primary-700
                  dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->openModal('modals.edit-modals.edit-test-modal', ['testId' => $row->id]),


            Button::add('delete')
                ->slot('   &#128465; Delete')
                ->class('flex gap-2 hover:text-slate-700
                 hover:bg-slate-100 font-bold p-1 px-2 rounded text-red-300')
                ->openModal('modals.delete-modals.delete-test-modal', ['testId' => $row->id]),
        ];
    }
    public function columns(): array
    {
        return [
            Column::action('Action'),
            // Column::make('Id', 'id'),
            Column::make('name', 'name')->contentClasses('text-blue-600'),
            Column::make('description', 'description'),
            Column::make('start', 'start'),
            Column::make('end', 'end'),
            Column::make('duration', 'duration'),
            Column::make('is_locked', 'isLocked')
                ->sortable()->contentClasses([
                    1   => 'text-green-600',
                    0 => 'text-red-600'
                ]), //->toggleable(true),
            Column::make('ip_range', 'ip_range'),
            Column::make('result_to_user', 'result_to_user'),
            Column::make('report_to_user', 'report_to_user'),
            Column::make('score_right', 'score_right'),
            Column::make('score_wrong', 'score_wrong'),
            Column::make('score_unanswered', 'score_unanswered'),


            Column::make('max_score', 'max_score'),
            Column::make('score_threshold', 'score_threshold'),
            Column::make('random_questions_select', 'random_questions_select'),
            Column::make('random_questions_order', 'random_questions_order'),
            Column::make('questions_order_mode', 'questions_order_mode'),
            Column::make('random_answers_select', 'random_answers_select'),
            Column::make('random_answers_order', 'random_answers_order'),
            Column::make('answers_order_mode', 'answers_order_mode'),

            Column::make('comment_enabled', 'comment_enabled'),
            Column::make('menu_enabled', 'menu_enabled'),
            Column::make('noanswer_enabled', 'noanswer_enabled'),
            Column::make('mcma_radio', 'mcma_radio'),
            Column::make('repeatable', 'repeatable'),
            Column::make('mcma_partial_score', 'mcma_partial_score'),
            Column::make('logout_on_timeout', 'logout_on_timeout'),
            // Column::make('password', 'password'),
            Column::make('user_id', 'user_id'),


            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),
            Column::make('Updated at', 'updated_at')
                ->sortable()
                ->searchable(),



        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        try {
            switch ($field) {
                case 'isLocked':
                    $test = Test::find($id);
                    if ($test) {
                        $test->update([
                            $field => $value
                        ]);
                    }
                    $message = $test->isLocked == 1 ? 'Locked' : 'Unlocked';
                    noty()->livewire()->addSuccess("Test " . $message . " successfully");
                    break;
                default:
                    break;
            }
        } catch (\Exception $e) {
            noty()->livewire()->addSuccess("Error ::" . $e->getMessage());
        }
    }

    //Bulk Operations
    public function handleBulkLock()
    {
        $this->processBulkAction('lock');
    }

    public function handleBulkUnlock()
    {
        $this->processBulkAction('unlock');
    }

    public function handleBulkDelete()
    {
        $this->processBulkAction('delete');
    }

    protected function processBulkAction($action)
    {
        if (empty($this->checkboxValues)) {
            noty()->livewire()->addError('Please select at least one item');
            return;
        }

        try {
            $message = '';
            foreach ($this->checkboxValues as $id) {
                switch ($action) {
                    case 'lock':
                        $test = Test::find($id);
                        if (!$test->isLocked == 0) {
                            $message = 'Test with ID:: ' . $id . ' already locked!';
                            // return;
                        }
                        $test->update(['isLocked' => 1]);
                        $message = 'Test with ID:: ' . $id . ' locked!';


                        break;
                    case 'unlock':
                        $test = Test::find($id);
                        if (!$test->isLocked == 1) {
                            $message = 'Test with ID:: ' . $id . ' already unlocked!';
                            //return;
                        }
                        $test->update(['isLocked' => 0]);
                        $message = 'Test with ID:: ' . $id . ' unlocked!';

                        break;
                    case 'delete':
                        $test = Test::find($id);
                        // $test->delete();
                        $message = 'Deleted ' . $id;
                        break;
                }
            }
            noty()->livewire()->addSuccess("Test " . $message . " successfully");

            $this->dispatch('refreshTestTable');
        } catch (\Exception $e) {
            noty()->livewire()->addError($e->getMessage());
        }
    }

    public function handleCheckboxChange($selectedIds)
    {
        $this->checkboxValues = $selectedIds;
    }
}
