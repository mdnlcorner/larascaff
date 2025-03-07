<?php

namespace Mulaidarinull\Larascaff\Components\Info;

use Illuminate\Support\Facades\Blade;

class Icon extends Info
{
    protected ?string $color = null;

    protected ?string $icon = null;

    public function color(string $color)
    {
        $this->color = $color;

        return $this;
    }

    public function icon(string $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    public function view()
    {
        if (! $this->show) {
            return;
        }

        return Blade::render(
            <<<'HTML'
            <x-larascaff::info.icon 
                :name="$name" 
                :label="$label" 
                :columnSpan="$columnSpan" 
                :value="$value" 
                :color="$color" 
                :icon="$icon" 
            />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'columnSpan' => $this->columnSpan,
                'value' => $this->value,
                'color' => $this->color,
                'icon' => $this->icon,
            ]
        );
    }
}
