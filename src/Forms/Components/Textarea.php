<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Support\Facades\Blade;

class Textarea extends Field
{
    protected int $rows = 3;

    public function rows(int $rows): static
    {
        $this->rows = $rows;

        return $this;
    }

    public function view(): string | null
    {
        if (!$this->getShow()) {
            return null;
        }

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
                :attr="$attr"
            />
            HTML,
            [
                'name' => $this->getName(),
                'label' => $this->getLabel(),
                'value' => $this->getValue(),
                'placeholder' => $this->getPlaceholder(),
                'columnSpan' => $this->columnSpan,
                'disabled' => $this->getDisabled(),
                'readonly' => $this->getReadonly(),
                'rows' => $this->rows,
                'attr' => $this->attr,
            ]
        );
    }
}
