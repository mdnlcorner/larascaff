<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Support\Facades\Blade;

class Checkbox extends Field
{
    protected ?bool $checked = null;

    public function view(): string | null
    {
        if (!$this->getShow()) {
            return null;
        }

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
                'name' => $this->getName(),
                'label' => $this->getLabel(),
                'value' => $this->value,
                'checked' => is_null($this->checked) ? (getRecord($this->name) ? true : false) : $this->checked,
                'disabled' => $this->getDisabled(),
                'readonly' => $this->getReadonly(),
                'columnSpan' => $this->columnSpan,
                'attr' => $this->attr,
            ]
        );
    }
}
