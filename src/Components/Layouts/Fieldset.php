<?php

namespace Mulaidarinull\Larascaff\Components\Layouts;

use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Components\Concerns\HasColumnSpan;
use Mulaidarinull\Larascaff\Components\Concerns\HasComponent;
use Mulaidarinull\Larascaff\Components\Concerns\HasModule;
use Mulaidarinull\Larascaff\Components\Concerns\HasRelationship;

class Fieldset
{
    use HasColumnSpan;
    use HasComponent;
    use HasModule;
    use HasRelationship;

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
