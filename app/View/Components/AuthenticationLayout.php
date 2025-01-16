<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AuthenticationLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render()
    {
        return view('components.layouts.authentication');//old layouts.authentication
    }
}
