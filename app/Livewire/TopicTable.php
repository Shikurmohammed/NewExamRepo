<?php

namespace App\Livewire;

use App\Models\Topic;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Responsive;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class TopicTable extends PowerGridComponent
{
    public string $tableName = 'topics'; //'topic-table-x6udtw-table';
    public bool $showFilters = true;
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
        return Topic::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('module_id')
            ->add('name')
            ->add('description')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Module id', 'module_id'),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Description', 'description')
                ->sortable()
                ->searchable()->editOnClick(
                    hasPermission: auth()->check(),
                    fallback: '- empty -',
                    saveOnMouseOut: true,
                ),



            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),
            Column::make('Updated at', 'updated_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('name', 'id')
                ->dataSource(Topic::all())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Topic $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: ' . $row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
    }


    // public function actionRules($row): array
    // {
    //     //var_dump('s');
    //     return [
    //         // Hide button edit for ID 1
    //         Rule::button('edit')
    //             ->when(fn($row) => $row->id === 1)
    //             ->hide(),
    //     ];
    // }
}
