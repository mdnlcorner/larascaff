<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Support\Facades\Blade;

class Checkbox extends Field
{
    public function view(): string
    {
        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.checkbox 
                :columnSpan="$columnSpan" 
                :disabled="$disabled" 
                :readonly="$readonly" 
                :value="$value" 
                :checked="$checked" 
                :name="$name" 
                :label="$label"
                :attr="$attr"
            />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'value' => $this->value,
                'checked' => getRecord($this->name) ? true : false,
                'disabled' => $this->disabled,
                'readonly' => $this->readonly,
                'columnSpan' => $this->columnSpan,
                'attr' => $this->attr,
            ]
        );
    }
}
