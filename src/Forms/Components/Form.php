<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Mulaidarinull\Larascaff\Forms\Concerns;

class Form
{
    use Concerns\HasColumnSpan;
    use Concerns\HasComponent;
    use Concerns\HasModal;
    use Concerns\HasModule;

    public function render()
    {
        $view = '';
        foreach ($this->components as $component) {
            if (method_exists($component, 'module')) {
                $component->module($this->module);
            }
            $view .= $component->view();
        }

        return $view;
    }
}
