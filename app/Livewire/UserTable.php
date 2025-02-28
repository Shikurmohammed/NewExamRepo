<?php

namespace App\Livewire;

use App\Exports\CustomPowerGridExport;
use App\Models\USER;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class UserTable extends PowerGridComponent
{
    ///use ActionButton;
    use WithExport;
    use WithPagination;
    public string $tableName = 'user-table-bwsy5x-table';

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
        return User::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('access_level')
            ->add('verify_code')
            ->add('status')
            ->add('otp_key')
            ->add('remember_token')
            ->add('two_factor_secret')
            ->add('created_at')
            ->add('updated_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Access level', 'access_level')
                ->sortable()
                ->searchable(),

            Column::make('Verify code', 'verify_code')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),

            Column::make('otp_key', 'otp_key')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),
            Column::make('Updated at', 'updated_at')
                ->sortable()
                ->searchable()
            // ->format('d/m/Y H:i:s'),
            , // Custom format

            Column::action('Action') //->visibleInExport(false)
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->dispatchBrowserEvent('alert', ['message' => $rowId]);
    }


    public function actions(USER $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: ' . $row->id)
                ->id()
                ->class('pg-btn-white green:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('detail')
                ->slot('detail: ' . $row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('detail', ['rowId' => $row->id]),
            //Button::add('export')->slot('Export to')->dispatch('detail', ['rowId' => $row->id]),

        ];
    }



    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
