<?php

namespace Mulaidarinull\Larascaff\Components\Forms;

use Illuminate\Support\Facades\Blade;

class Textarea extends Field
{
    protected int $rows = 3;

    public function rows(int $rows): static
    {
        $this->rows = $rows;

        return $this;
    }

    public function view(): string
    {
        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.textarea 
                :disabled="$disabled" 
                :readonly="$readonly" 
                :columnSpan="$columnSpan" 
                :name="$name" 
                :label="$label" 
                :placeholder="$placeholder" 
                :rows="$rows" 
                :value="$value"
            />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'value' => $this->value,
                'placeholder' => $this->placeholder,
                'columnSpan' => $this->columnSpan,
                'disabled' => $this->disabled,
                'readonly' => $this->readonly,
                'rows' => $this->rows,
            ]
        );
    }
}
