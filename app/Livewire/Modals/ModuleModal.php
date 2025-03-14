<?php

namespace App\Livewire\Modals;

use App\Imports\ModulesImport;
use App\Models\Module;
use Flasher\Noty\Prime\NotyInterface;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ModuleModal extends Modal
{
    use WithFileUploads;
    public $file; //module_file
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
                noty()->livewire()
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
    public function importModules()
    {

        //dd($this->file);

        try {
            $this->validate([
                'file' => 'required|mimes:csv,xlsx,xls |max:2048',
            ], [
                'file.required' => 'Please upload a file.',
                'file.mimes' => 'The file must be a CSV, XLSX, or XLS file.',
            ]);


            $reader = Excel::toArray(new ModulesImport, $this->file);
            $headers = array_keys($reader[0][0]); // Get the headers from the first row

            // Validate the headers
            $requiredHeaders = ['name', 'enabled', 'created_by']; //ser_id
            $missingHeaders = array_diff($requiredHeaders, $headers);
            // dd($headers);
            if (!empty($missingHeaders)) {
                noty()->livewire()
                    ->addError("The uploaded file is missing the following required columns: "
                        . implode(",", $missingHeaders));
                return;
            }
            $path = $this->file->store('modules');
            Excel::import(new ModulesImport, $path);
            $this->file = null;
        } catch (\Throwable $th) {
            noty()
                ->livewire()
                ->addError('Error occured:: ' . $th->getMessage());
        }
    }
}
