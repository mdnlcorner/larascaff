<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Support\Facades\Blade;

class Radio extends Field
{
    public array $options = [];

    public function options($options): static
    {
        $this->options = $options;

        return $this;
    }

    public function view(): string | null
    {
        if (!$this->getShow()) {
            return null;
        }

        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.radio-group 
                :columnSpan="$columnSpan" 
                :disabled="$disabled" 
                :readonly="$readonly" 
                :options="$options" 
                :name="$name" 
                :label="$label"
                :attr="$attr"
            />
            HTML,
            [
                'name' => $this->getName(),
                'label' => $this->getLabel(),
                'options' => $this->options,
                'disabled' => $this->getDisabled(),
                'readonly' => $this->getReadonly(),
                'columnSpan' => $this->columnSpan,
                'attr' => $this->attr,
            ]
        );
    }
}
