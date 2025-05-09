<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Support\Facades\Blade;

class Checkbox extends Field
{
    protected string $variant = 'primary';

    public function variant(string $variant): static
    {
        $this->variant = $variant;

        return $this;
    }

    public function unformat(): array
    {
        return [$this->getName() => request()->{$this->getName()} ?? 0];
    }

    public function view(): string
    {
        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.checkbox :columnSpan="$columnSpan" :disabled="$disabled" :readonly="$readonly" :variant="$variant" :value="$value" :checked="$checked" :name="$name" :label="$label" />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'value' => $this->value,
                'variant' => $this->variant,
                'checked' => getRecord($this->name) ? true : false,
                'disabled' => $this->disabled,
                'readonly' => $this->readonly,
                'columnSpan' => $this->columnSpan,
            ]
        );
    }
}
