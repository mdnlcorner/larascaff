<?php

namespace Mulaidarinull\Larascaff\Info\Components;

use Closure;
use Illuminate\Support\Facades\Blade;

class Icon extends Info
{
    protected ?string $color = null;

    protected Closure | string | null $icon = null;

    protected bool $boolean = false;

    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function boolean(bool $status = true): static
    {
        $this->boolean = $status;

        return $this;
    }

    public function icon(Closure | string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function view(): string
    {
        if (!$this->show) {
            return '';
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
                :isBoolean="$isBoolean"
            />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'columnSpan' => $this->columnSpan,
                'value' => $this->value,
                'color' => $this->color,
                'icon' => $this->icon,
                'isBoolean' => $this->boolean,
            ]
        );
    }
}
