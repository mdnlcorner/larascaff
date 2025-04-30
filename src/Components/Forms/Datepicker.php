<?php

namespace Mulaidarinull\Larascaff\Components\Forms;

use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Components\Contracts\HasDatepicker;
use Mulaidarinull\Larascaff\Components\Contracts\IsComponent;

class Datepicker extends Field implements HasDatepicker, IsComponent
{
    protected bool $icon = true;

    protected string $format = 'yyyy-mm-dd';

    protected string $formatPhp = 'Y-m-d';

    protected array $config = [];

    protected ?string $type = 'date';

    public function icon($icon = true): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function format($format): static
    {
        $this->format = $format;
        $record = getRecord();
        $map = ['yyyy' => 'Y', 'yy' => 'y', 'm' => 'n', 'D' => 'D', 'DD' => 'l', 'MM' => 'F', 'M' => 'M', 'mm' => 'm', 'dd' => 'd', 'd' => 'j'];
        foreach (['-', '/'] as $separator) {
            if (str_contains($format, $separator)) {
                $expFormat = explode($separator, $format);
                $formatPhp = '';
                foreach ($expFormat as $i) {
                    $formatPhp .= ($formatPhp ? $separator.$map[$i] : $map[$i]);
                }
                $this->formatPhp = $formatPhp;
            }
        }

        $this->value = convertDate($record->{$this->name}, $this->formatPhp);

        return $this;
    }

    public function unformat(): array
    {
        return [$this->getName() => convertDate(request()->{$this->getName()}, 'Y-m-d')];
    }

    public function getformatPhp(): string
    {
        return $this->formatPhp;
    }

    public function config(array $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function view(): string
    {
        $this->config['format'] = $this->format;

        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.datepicker 
                :value="$value" 
                :columnSpan="$columnSpan" 
                :config="$config" 
                :icon="$icon" 
                :name="$name" 
                :label="$label" 
                :placeholder="$placeholder" 
            />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'placeholder' => $this->placeholder,
                'icon' => $this->icon,
                'config' => $this->config,
                'value' => $this->value,
                'columnSpan' => $this->columnSpan,
            ]
        );
    }
}
