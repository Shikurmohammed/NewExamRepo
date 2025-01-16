<?php

namespace App\Livewire;

use Livewire\Component;

class LazyLoader extends Component
{
    public $isLoaded = false;
    public $component;
    public $params = [];

    public function load()
    {
        $this->isLoaded = true;
    }
    public function render()
    {
        return view('livewire.lazy-loader');
    }
}
