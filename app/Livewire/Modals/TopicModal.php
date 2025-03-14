<?php

namespace App\Livewire\Modals;

use App\Imports\TopicsImport;
use App\Models\Module;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class TopicModal extends Modal
{
    //Importing question from csv,excel,...
    use WithFileUploads;
    public $file; //

    #[Rule('required')]
    public $module_id;
    public $topic_name;
    public $description;
    public $enabled = 0;
    public $owner_name = '';
    public function create()
    {
        $this->validate();

        try {
            $topic = new Topic();
            $topic->module_id = $this->module_id;
            $topic->name = $this->topic_name;
            $topic->description = $this->description;
            $topic->enabled = $this->enabled;

            //Append the creator
            $user_id = Auth::user()->id;
            $topic->user_id = $user_id;

            $countTopicByname = Topic::where('name', $this->topic_name)->count();

            if ($countTopicByname < 1) {
                Topic::create([
                    'module_id' => $this->module_id,
                    'name' => $this->topic_name,
                    'description' => $this->description,
                    'enabled' => $this->enabled,
                    'user_id' => $user_id,
                ]);

                noty()->livewire()
                    ->addSuccess('Topic added successfully!');
            } else {
                noty()->livewire()
                    ->addWarning('Topic already exists!');
            }
        } catch (\Exception $ex) {
            noty()->livewire()
                ->addError('error' . $ex->getMessage());
        }
    }
    //Edit topic
    public function edit($id)
    {
        dd($id);
    }
    #[Computed()]
    public function modules()
    {
        return Module::all();
    }
    public function render()
    {
        return view('livewire.modals.topic-modal');
    }

    public function importTopics()
    {


        $this->validate([
            'file' => 'required|mimes:csv,xlsx,xls |max:2048',
        ], [
            'file.required' => 'Please upload a file.',
            'file.mimes' => 'The file must be a CSV, XLSX, or XLS file.',
        ]);
        $path = $this->file->store('topics');
        try {
            // dd($this->file);
            // Excel::import(new QuestionImport, $this->file);
            Excel::import(new TopicsImport, $path);
            $this->file = null;
        } catch (\Exception $e) {
            noty()
                ->livewire()
                ->addError('Import Failed!' . $e->getMessage());
        }
    }
}
