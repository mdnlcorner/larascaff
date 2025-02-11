<?php

namespace Mulaidarinull\Larascaff\Components\Forms;

use Illuminate\Support\Facades\Blade;

class Textarea extends Field
{
    protected int $rows = 3;

    public function rows(int $rows)
    {
        $this->rows = $rows;
        return $this;
    }

    public function view()
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
            />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'placeholder' => $this->placeholder,
                'columnSpan' => $this->columnSpan,
                'disabled' => $this->disabled,
                'readonly' => $this->readonly,
                'rows' => $this->rows,
            ]
        );
    }
}
