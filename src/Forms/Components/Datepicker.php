<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Forms\Contracts\HasDatepicker;
use Mulaidarinull\Larascaff\Forms\Contracts\IsComponent;

class Datepicker extends Field implements HasDatepicker, IsComponent
{
    protected bool $icon = true;

    protected string $formatPhp = 'Y-m-d';

    protected array $config = [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true,
        'autohide' => true,
        'todayButton' => true,
        'clearBtn' => true,
    ];

    protected ?string $type = 'date';

    public function config(array $config = []): static
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    public function icon($icon = true): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function format($format): static
    {
        $this->format = $format;

        $record = getRecord();

        $map = [
            'yyyy' => 'Y',
            'yy' => 'y',
            'm' => 'n',
            'D' => 'D',
            'DD' => 'l',
            'MM' => 'F',
            'M' => 'M',
            'mm' => 'm',
            'dd' => 'd',
            'd' => 'j',
        ];

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

    public function getFormatPhp(): string
    {
        return $this->formatPhp;
    }

    public function autoHide(bool $status = true): static
    {
        $this->config['autohide'] = $status;

        return $this;
    }

    public function todayHighlight(bool $status = true): static
    {
        $this->config['todayHighlight'] = $status;

        return $this;
    }

    public function todayButton(bool $status = true): static
    {
        $this->config['todayButton'] = $status;

        return $this;
    }

    public function clearButton(bool $status = true): static
    {
        $this->config['clearButton'] = $status;

        return $this;
    }

    public function datesDisabled(array $dates = []): static
    {
        $this->config['datesDisabled'] = $dates;

        return $this;
    }

    public function daysOfWeekDisabled(array $dates = []): static
    {
        $this->config['daysOfWeekDisabled'] = $dates;

        return $this;
    }

    public function startView(int $start = 0): static
    {
        $this->config['startView'] = $start;

        return $this;
    }

    public function maxView(int $start = 0): static
    {
        $this->config['maxView'] = $start;

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
                :attr="$attr"
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
                'attr' => $this->attr,
            ]
        );
    }
}
