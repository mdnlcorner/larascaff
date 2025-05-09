<?php

namespace Mulaidarinull\Larascaff\Info\Components;

use Illuminate\Support\Facades\Blade;

class Media extends Info
{
    protected bool $rounded = false;

    public static function make(?string $name = null): static
    {
        $static = app(static::class);
        $static->name = $name;

        return $static;
    }

    public function rounded(bool $rounded = true)
    {
        $this->rounded = $rounded;

        return $this;
    }

    public function view()
    {
        return Blade::render(
            <<<'HTML'
            <x-larascaff::info.media 
                :name="$name" 
                :label="$label" 
                :columnSpan="$columnSpan" 
                :value="$value" 
                :rounded="$rounded" 
            />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'columnSpan' => $this->columnSpan,
                'value' => $this->value,
                'rounded' => $this->rounded,
            ]
        );
    }
}
