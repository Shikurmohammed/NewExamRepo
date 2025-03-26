<?php

namespace App\Livewire;

use App\Models\Answer;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class AnswerTable extends PowerGridComponent
{
    use WithExport;
    //use WithPagination;
    public string $tableName = 'answer-table-zwshok-table';
    protected $listeners = ['refreshAnswerTable' => '$refresh'];

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()->showSoftDeletes(showMessage: true),
            PowerGrid::footer()
                ->pageName('answers')
                ->showPerPage(perPage: 10, perPageValues: [5, 10, 25, 50, 'All'])
                ->showRecordCount(),
            PowerGrid::exportable(fileName: 'answers')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV)
                ->striped('A6ACCD')->stripTags(true), // Specify columns to strip tags,
        ];
    }

    public function datasource() //: Builder
    {
        //return Answer::query();
        return Answer::with('question')->get();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('question_description', function (Answer $answer) {
                return $answer->question && $answer->question->description ?
                    $answer->question->description : 'No Question specified';
            })
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
            Column::make('Question', 'question_description'),
            Column::make('Answer', 'description')
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
        return [
            Button::add('view')
                ->id()
                ->slot(' &#128065; view')
                ->class('
               flex gap-2 hover:text-slate-700 hover:bg-slate-100
                 font-bold p-1 px-2 rounded dark:ring-pg-primary-600 text-blue-300
                  ')
                ->openModal('modals.details-modals.answer-details-modal', ['answerId' => $row->id]),
            Button::add('edit')
                ->slot('&#9889; Edit')
                ->id()
                ->class('flex gap-2 hover:text-slate-700 hover:bg-slate-100
                 font-bold p-1 px-2 rounded dark:ring-pg-primary-600
                  dark:border-pg-primary-600 dark:hover:bg-pg-primary-700
                  dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->openModal('modals.edit-modals.edit-answer-modal', ['answerId' => $row->id]),
            Button::add('delete')
                ->slot('   &#128465; Delete')
                ->class('flex gap-2 hover:text-slate-700
                 hover:bg-slate-100 font-bold p-1 px-2 rounded text-red-300')
                ->openModal('modals.delete-modals.delete-answer-modal', ['answerId' => $row->id]),
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
