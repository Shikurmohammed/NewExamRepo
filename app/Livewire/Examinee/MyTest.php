<?php

namespace App\Livewire\Examinee;

use App\Models\Test;
use Livewire\Attributes\Computed;
use Livewire\Component;

class MyTest extends Component
{
    #[Computed()]
    public  function tests()
    {
        return Test::all();
    }
    public function startExam($id)
    {
        //dd($id);

        return redirect()->route('mytest.list');
    }
    public function render()
    {
        return view('livewire.examinee.my-test');
    }
}
