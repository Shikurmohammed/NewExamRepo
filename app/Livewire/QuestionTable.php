<?php

namespace App\Livewire;

use App\Models\Question;
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
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class QuestionTable extends PowerGridComponent
{
    use WithExport;
    use WithPagination;
    public string $tableName = 'question-table-jegalw-table';
    protected string $paginationTheme = 'tailwind';
    public function setUp(): array
    {
        $this->showCheckBox();
        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->pageName('questions')
                ->showPerPage(perPage: 5, perPageValues: [0, 3, 10, 25, 50])
                ->showRecordCount(),
            PowerGrid::exportable(fileName: 'Questions')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV)
                ->striped('A6ACCD'),

        ];
    }

    public function datasource() //: Builder
    {
        return Question::with('topic')->get();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('topic_name', function (Question $question) {
                // Check if the topic exists and has a name
                return $question->topic && $question->topic->name ? $question->topic->name : 'No Topic Name';
            })
            ->add('description')
            ->add('explanation')
            ->add('enabled')
            ->add('type')
            ->add('difficulty')
            ->add('position')
            ->add('timer')
            ->add('fullscreen')
            ->add('inline_answers')
            ->add('auto_next')
            ->add('created_by')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Action'),
            Column::make('Id', 'id')->hidden(),
            Column::make('Topic id', 'topic_name')->sortable()
                ->searchable(),
            Column::make('Description', 'description')->sortable()
                ->sortable()
                ->searchable(),

            Column::make('Explanation', 'explanation')
                ->sortable()
                ->searchable(),

            Column::make('Enabled', 'enabled')
                ->sortable()
                ->searchable(),

            Column::make('Type', 'type'),
            Column::make('Difficulty', 'difficulty'),
            Column::make('Position', 'position'),
            Column::make('Timer', 'timer'),
            Column::make('Fullscreen', 'fullscreen')
                ->sortable()
                ->searchable(),

            Column::make('Inline answers', 'inline_answers')
                ->sortable()
                ->searchable(),

            Column::make('Auto next', 'auto_next')
                ->sortable()
                ->searchable(),

            Column::make('Created by', 'created_by')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),
            Column::make('updated at', 'updated_at')
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

    public function actions(Question $row): array
    {
        return [
            Button::add('view')
                ->id()
                ->slot(' &#128065; view')
                ->class('
               flex gap-2 hover:text-slate-700 hover:bg-slate-100
                 font-bold p-1 px-2 rounded dark:ring-pg-primary-600 text-blue-300
                  ')
                ->openModal('modals.details-modals.question-details-modal', ['questionId' => $row->id]),
            Button::add('edit')
                ->slot('&#9889; Edit')
                ->id()
                ->class('flex gap-2 hover:text-slate-700 hover:bg-slate-100
                 font-bold p-1 px-2 rounded dark:ring-pg-primary-600
                  dark:border-pg-primary-600 dark:hover:bg-pg-primary-700
                  dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->openModal('modals.edit-modals.edit-question-modal', ['questionId' => $row->id]),


            Button::add('delete')
                ->slot('   &#128465; Delete')
                ->class('flex gap-2 hover:text-slate-700
                 hover:bg-slate-100 font-bold p-1 px-2 rounded text-red-300')
                ->openModal('modals.delete-modals.delete-question-modal', ['questionId' => $row->id]),
        ];
    }
}
