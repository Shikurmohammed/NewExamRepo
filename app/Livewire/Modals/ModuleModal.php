<?php

namespace App\Livewire\Modals;

use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ModuleModal extends Modal
{
    #[Rule('required')]
    public $module_name = '';
    public $enabled = 0;
    public $owner_name = '';
    public function create()
    {
        $this->validate();
        try {
            $module = new Module;
            $module->name = $this->module_name;
            $user_id = Auth::user()->id;
            $module->user_id = $user_id;
            $module->enabled = $this->enabled;
            $countModuleByname = Module::where('name', $this->module_name)->count();

            if ($countModuleByname < 1) {
                Module::create([
                    'name' => $this->module_name,
                    'enabled' => $this->enabled,
                    'user_id' => $user_id,
                ]);

                noty()
                    ->livewire()
                    ->addSuccess('Module added successfully!');
            } else {
                noty()
                    ->livewire()
                    ->addError('Module already exists!');
            }
        } catch (\Throwable $th) {
            noty()
                ->livewire()
                ->addError($th->getMessage());
        }
    }
    public function edit($id) {}
    public function render()
    {
        $this->owner_name = Auth::user()->name ? Auth::user()->name : '';
        return view('livewire.modals.module-modal');
    }
}
