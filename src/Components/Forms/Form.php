<?php

namespace Mulaidarinull\Larascaff\Components\Forms;

use Mulaidarinull\Larascaff\Components\Concerns\HasColumnSpan;
use Mulaidarinull\Larascaff\Components\Concerns\HasComponent;
use Mulaidarinull\Larascaff\Components\Concerns\HasModule;

class Form
{
    use HasColumnSpan, HasComponent, HasModule;

    public static function make()
    {
        return new self;
    }

    public function render()
    {
        $view = '';
        foreach($this->components as $component) {
            if (method_exists($component, 'module')) {
                $component->module($this->module);
            }
            $view.= $component->view();
        }
        return $view;
    }
}