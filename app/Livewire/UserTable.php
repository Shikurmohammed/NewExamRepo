<?php

namespace App\Livewire;

use App\Exports\CustomPowerGridExport;
use App\Models\Group;
use App\Models\USER;
use App\Models\User as ModelsUser;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
//use Livewire\Attributes\Rule;
use PowerComponents\LivewirePowerGrid\Facades\Rule; // [!code ++]

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

    protected $listeners = ['refreshUserTable' => '$refresh'];
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
    public function header(): array
    {
        return [
            Button::add('Group')
                ->slot('Bulk Group')
                ->slot(__('Group (<span x-text="window.pgBulkActions.count(\'' . $this->tableName . '\')"></span>)'))
                ->class(' border-slate-400 flex gap-2 hover:text-slate-700 hover:bg-slate-100
                 font-bold p-1 px-2 rounded dark:ring-pg-primary-600 text-blue-300')
                //->can(!empty($this->checkboxValues))
                ->openModal('modals.user-group-modal', ['selectedUserIds' => $this->checkboxValues]), //We will get selected items from $this->checkboxValues
        ];
    }


    public function datasource() //: Builder
    {
        // return User::query();
        $user_id  = Auth::user()->id;
        return User::with('groups')->where('id', '!=', $user_id)->get();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('first_name', function (USER $user) {
                $firstName = $user->first_name ?? '';
                $middleName = $user->middle_name ?? '';
                $lastName = $user->last_name ?? '';
                $fullName =  $firstName . ' ' . $middleName . ' ' . $lastName;
                return $fullName;
            })
            // ->add('middle_name')
            // ->add('last_name')
            ->add('email')
            ->add('access_level')
            ->add('status')
            // ->add('status', function (USER $user) {
            //     // Check status value for conditional styling
            //     return $user->status === 1
            //         ? '<span class="px-2 py-1 text-white bg-green-500 rounded">Active</span>'
            //         : '<span class="px-2 py-1 text-black bg-gray-300 rounded">Inactive</span>';
            // })
            ->add('is_online', function (User $user) {
                return $user->is_online
                    ? '<span class="px-2 py-1 text-white bg-green-500 rounded hover:bg-blue-400">Online</span>'
                    : '<span class="px-2 py-1 text-black bg-gray-300 rounded hover:bg-slate-400">Offline</span>';
            })
            ->add('group', function (USER $user) {
                return $user->groups->isNotEmpty() ? $user->groups->pluck('name')->implode(',') : '';
            })
            ->add('verify_code')
            ->add('otp_key')
            ->add('remember_token')
            ->add('two_factor_secret')
            ->add('created_at')
            ->add('updated_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Action'), //->visibleInExport(false)
            Column::make('Id', 'id'),
            Column::make('Full Name', 'first_name')
                ->sortable()
                ->searchable(),
            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Access level', 'access_level')
                ->sortable()
                ->searchable(),
            Column::make('Enable/Disable', 'status')
                ->toggleable(true)
                ->sortable(),
            Column::make('Status', 'is_online')
                ->sortable()
                ->searchable(),
            //->toggleable(true),
            Column::make('Group', 'group')
                ->sortable()
                ->searchable(),
            Column::make('Verify code', 'verify_code')
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
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        $user = User::find($id);
        if ($user) {
            $user->update([
                $field => $value
            ]);
        }
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
            Button::add('view')
                ->id()
                ->slot('&#128065; view')
                ->class('
               flex gap-2 hover:text-slate-700 hover:bg-slate-100
                 font-bold p-1 px-2 rounded dark:ring-pg-primary-600 text-blue-300
                  ')
                ->openModal('modals.details-modals.user-details-modal', ['userId' => $row->id]),
            Button::add('edit')
                ->slot('&#9889; Edit')
                ->id()
                ->class('flex gap-2 hover:text-slate-700 hover:bg-slate-100
                 font-bold p-1 px-2 rounded dark:ring-pg-primary-600
                  dark:border-pg-primary-600 dark:hover:bg-pg-primary-700
                  dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->openModal('modals.edit-modals.edit-user-modal', ['userId' => $row->id]),


            Button::add('delete')
                ->slot('   &#128465; Delete')
                ->class('flex gap-2 hover:text-slate-700
                 hover:bg-slate-100 font-bold p-1 px-2 rounded text-red-300')
                ->openModal('modals.delete-modals.delete-user-modal', ['userId' => $row->id]),
        ];
    }
}
