<?php

namespace Mulaidarinull\Larascaff\Components\Forms;

use Illuminate\Support\Facades\Blade;

class Radio extends Field
{
    public array $options = [];

    public function options($options): static
    {
        $this->options = $options;

        return $this;
    }

    public function view(): string
    {
        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.radio-group 
                :columnSpan="$columnSpan" 
                :disabled="$disabled" 
                :readonly="$readonly" 
                :options="$options" 
                :name="$name" 
                :label="$label" 
            />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'options' => $this->options,
                'disabled' => $this->disabled,
                'readonly' => $this->readonly,
                'columnSpan' => $this->columnSpan,
            ]
        );
    }
}
