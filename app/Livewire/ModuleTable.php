<?php

namespace App\Livewire;

use App\Models\Module;
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

final class ModuleTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'module-table-wui6ii-table';

    public array $name = [];
    public array $enabled = [];
    public array $user_id = [];
    public array $created_at = [];
    public array $updated_at = [];


    public array $rules = [
        'name.*' => ['required', 'string', 'unique:modules,name'],
    ];
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->pageName('modules')
                ->showPerPage(perPage: 10, perPageValues: [0, 1, 10, 25, 50])
                ->showRecordCount(),
            PowerGrid::exportable(fileName: 'Module')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV)
                ->striped('A6ACCD'),
        ];
    }

    public function datasource()
    {
        return Module::with('user')->get();
        //return Module::query()->with('user');
    }

    public function relationSearch(): array
    {
        return [
            'module' => ['name', 'enabled', 'user_id', 'created_at', 'updated_at'],
        ];
    }
    protected $listeners = ['moduleUpdated' => 'refreshModules', 'moduleDeleted' => 'refreshModules'];

    public function refreshModules()
    {
        $this->datasource(); // Refresh the modules list
    }
    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('enabled')
            //  ->add('user_id')
            ->add('email', function (Module $module) {
                return $module->user->email;
            })
            ->add('Created By', function (Module $module) {
                return $module->user->name;
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable()->editOnClick(true, '', '', true),

            Column::make('Enabled', 'enabled')
                ->sortable()
                ->searchable()->toggleable(true,),

            Column::make('Created By', 'email')->searchable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::make('Updated at', 'updated_at')
                ->sortable()
                ->searchable(),


            Column::action('Action')
        ];
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        //Validate before update
        $this->validate();

        Module::find($id)->update([$field => $value]);
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        Module::query()->find($id)->update([
            $field => e($value),
        ]);
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
        redirect('module/' . $rowId . '/edit');
    }

    public function actions(Module $row): array
    {
        return [
            Button::add('view')
                ->id()
                ->slot(' &#128065; view')
                ->class('
               flex gap-2 hover:text-slate-700 hover:bg-slate-100
                 font-bold p-1 px-2 rounded dark:ring-pg-primary-600 text-blue-300
                  ')
                ->openModal('modals.details-modals.module-details-modal', ['moduleId' => $row->id]),
            Button::add('edit')
                ->slot('&#9889; Edit')
                ->id()
                ->class('flex gap-2 hover:text-slate-700 hover:bg-slate-100
                 font-bold p-1 px-2 rounded dark:ring-pg-primary-600
                  dark:border-pg-primary-600 dark:hover:bg-pg-primary-700
                  dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->openModal('modals.edit-modals.edit-module-modal', ['moduleId' => $row->id]),


            Button::add('delete')
                ->slot('   &#128465; Delete')
                ->class('flex gap-2 hover:text-slate-700
                 hover:bg-slate-100 font-bold p-1 px-2 rounded text-red-300')
                ->openModal('modals.delete-modals.delete-module-modal', ['moduleId' => $row->id]),

        ];
    }
}
