<?php

namespace Mulaidarinull\Larascaff\Components\Forms;

use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Components\Contracts\{HasDatepicker, IsComponent};

class DatepickerRange extends Field implements HasDatepicker, IsComponent
{
    protected bool $icon = true;
    protected string $format = 'yyyy-mm-dd';
    protected string $formatPhp = 'Y-m-d';
    protected array $config = [];
    protected string $type = 'daterange';

    public static function make($name): static
    {
        $static = app(static::class, ['name' => $name]);
        $static->name($name);

        return $static;
    }

    public function icon($icon = true)
    {
        $this->icon = $icon;
        return $this;
    }

    public function format($format)
    {
        $this->format = $format;
        $record = getRecord();
        $map = ['yyyy' => 'Y', 'yy' => 'y', 'm' => 'n', 'D' => 'D', 'DD' => 'l', 'MM' => 'F', 'M' => 'M', 'mm' => 'm', 'dd' => 'd', 'd' => 'j'];
        foreach (['-', '/'] as $separator) {
            if (str_contains($format, $separator)) {
                $expFormat = explode($separator, $format);
                $formatPhp = '';
                foreach ($expFormat as $i) {
                    $formatPhp .= ($formatPhp ? $separator . $map[$i] : $map[$i]);
                }
                $this->formatPhp = $formatPhp;
            }
        }

        $this->value = [convertDate($record->{$this->name[0]}, $this->formatPhp), convertDate($record->{$this->name[1]}, $this->formatPhp)];
        return $this;
    }

    public function unformat()
    {
        $unformat[$this->getName()[0]] = convertDate(request()->{$this->getName()[0]}, 'Y-m-d');
        $unformat[$this->getName()[1]] = convertDate(request()->{$this->getName()[1]}, 'Y-m-d');
        return $unformat;
    }

    public function getformatPhp()
    {
        return $this->formatPhp;
    }

    public function config(array $config): self
    {
        $this->config = $config;
        return $this;
    }

    public function view()
    {
        $this->config['format'] = $this->format;
        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.datepicker-range 
                :columnSpan="$columnSpan" 
                :value1="$value[0] ?? null" 
                :value2="$value[1] ?? null" 
                :config="$config" 
                :icon="$icon" 
                :name1="$name[0] ?? null" 
                :name2="$name[1] ?? null" 
                :label="$label" 
                :placeholder1="$placeholder[0] ?? ''" 
                :placeholder2="$placeholder[1] ?? ''" />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'placeholder' => $this->placeholder,
                'icon' => $this->icon,
                'config' => $this->config,
                'value' => $this->value,
                'columnSpan' => $this->columnSpan
            ]
        );
    }
}
