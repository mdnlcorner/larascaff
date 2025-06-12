<?php

namespace Mulaidarinull\Larascaff\Info\Components;

use Illuminate\Support\Facades\Blade;

class Text extends Info
{
    protected ?array $numberFormat = null;

    protected bool $html = false;

    protected ?string $appendIcon = null;

    protected ?string $prependIcon = null;

    public function appendIcon(string $appendIcon): static
    {
        $this->appendIcon = $appendIcon;

        return $this;
    }

    public function prependIcon(string $prependIcon): static
    {
        $this->prependIcon = $prependIcon;

        return $this;
    }

    public function numberFormat(?string $thousandSeparator = '.', ?string $decimalSeparator = ','): static
    {
        $this->numberFormat = [$thousandSeparator, $decimalSeparator];

        return $this;
    }

    public function getNumberFormat(): ?array
    {
        return $this->numberFormat;
    }

    public function html(bool $html = true): static
    {
        $this->html = $html;

        return $this;
    }

    public function view(): string
    {
        return Blade::render(
            <<<'HTML'
            <x-larascaff::info.text 
                :name="$name" 
                :label="$label" 
                :columnSpan="$columnSpan" 
                :value="$value" 
                :numberFormat="$numberFormat" 
                :html="$html" 
                :appendIcon="$appendIcon" 
                :prependIcon="$prependIcon" 
            />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'columnSpan' => $this->columnSpan,
                'value' => $this->value,
                'numberFormat' => $this->numberFormat,
                'html' => $this->html,
                'appendIcon' => $this->appendIcon,
                'prependIcon' => $this->prependIcon,
            ]
        );
    }
}
