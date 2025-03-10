<?php

namespace Mulaidarinull\Larascaff\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('larascaff::layouts.guest-layout');
    }
}
