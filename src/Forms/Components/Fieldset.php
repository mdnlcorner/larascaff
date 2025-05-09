<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Forms\Concerns;

class Fieldset
{
    use Concerns\HasColumnSpan;
    use Concerns\HasComponent;
    use Concerns\HasModule;
    use Concerns\HasRelationship;

    public function __construct()
    {
        $this->columnSpan = 'full';
    }

    public function view()
    {
        $slot = '';
        foreach ($this->components as $component) {
            $slot .= $component->view();
        }

        return Blade::render(
            <<<'HTML'
            <x-larascaff::layouts.fieldset 
                :name="$name"
                :columnSpan="$columnSpan"
                :columns="$columns"
            >{!! $slot !!}</x-larascaff::layouts.fieldset >
            HTML,
            [
                'name' => $this->name,
                // 'components' => $this->components,
                'columnSpan' => $this->columnSpan,
                'columns' => $this->columns,
                'slot' => $slot,
            ]
        );
    }
}
