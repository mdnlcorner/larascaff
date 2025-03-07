<?php

namespace Mulaidarinull\Larascaff\Components\Info;

use Illuminate\Support\Facades\Blade;

class Text extends Info
{
    protected ?array $numberFormat = null;

    protected bool $html = false;

    protected ?string $appendIcon = null;

    protected ?string $prependIcon = null;

    public function appendIcon(string $appendIcon)
    {
        $this->appendIcon = $appendIcon;

        return $this;
    }

    public function prependIcon(string $prependIcon)
    {
        $this->prependIcon = $prependIcon;

        return $this;
    }

    public function numberFormat(?string $thousandSeparator = '.', ?string $decimalSeparator = ',')
    {
        $this->numberFormat = [$thousandSeparator, $decimalSeparator];

        return $this;
    }

    public function html(bool $html = true)
    {
        $this->html = $html;

        return $this;
    }

    public function view()
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
