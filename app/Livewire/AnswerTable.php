<?php

namespace App\Livewire;

use App\Models\Answer;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class AnswerTable extends PowerGridComponent
{
    public string $tableName = 'answer-table-zwshok-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()->showSoftDeletes(showMessage: true),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Answer::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('question_id')
            ->add('description')
            ->add('explanation')
            ->add('is_right')
            ->add('enabled')
            ->add('position')
            ->add('keyboard_key')
            ->add('created_at')
            ->add('updated_at')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Action'),
            Column::make('Id', 'id'),
            Column::make('Question id', 'question_id'),
            Column::make('Description', 'description')
                ->sortable()
                ->searchable(),

            Column::make('Explanation', 'explanation')
                ->sortable()
                ->searchable(),

            Column::make('Is right', 'is_right')
                ->sortable()
                ->searchable(),

            Column::make('Enabled', 'enabled')
                ->sortable()
                ->searchable(),

            Column::make('Position', 'position'),
            Column::make('Keyboard key', 'keyboard_key'),
            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),
            Column::make('Updated at', 'updated_at')
                ->sortable()
                ->searchable(),

        ];
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

    public function actions(Answer $row): array
    {
        //var_dump($row);
        return [
            Button::add('view')
                ->slot("View" . $row->id)
                ->id()
                ->class('text-indigo-500 text-white rounded')
                ->dispatch('view', ['rowId' => $row->id]),
            Button::add('edit')
                ->slot("&#9889; Edit" . $row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600
                         dark:border-pg-primary-600 dark:hover:bg-pg-primary-700
                         dark:ring-offset-pg-primary-800 dark:text-pg-primary-300
                          dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
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
