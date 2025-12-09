<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Closure;
use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Forms\Contracts\HasDatepicker;
use Mulaidarinull\Larascaff\Forms\Contracts\IsComponent;

class Datepicker extends Field implements HasDatepicker, IsComponent
{
    protected bool $icon = true;

    protected string $formatPhp = 'Y-m-d';

    protected array $options = [];

    protected Closure | bool $readonly = true;

    public function __construct()
    {
        $this->type('date');

        $this->options([
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'autohide' => true,
            'todayButton' => true,
            'clearBtn' => true,
            'language' => app()->getLocale(),
        ]);
    }

    public function options(array $options = []): static
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    public function icon($icon = true): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function mapDate(): array
    {
        return [
            'Y' => 'yyyy',
            'y' => 'yy',
            'n' => 'm',
            'D' => 'D',
            'l' => 'DD',
            'F' => 'MM',
            'M' => 'M',
            'm' => 'mm',
            'd' => 'dd',
            'j' => 'd',
        ];
    }

    public function format($format): static
    {
        $this->formatPhp = $format;

        $map = $this->mapDate();

        foreach (['-', '/'] as $separator) {
            if (str_contains($format, $separator)) {
                $expFormat = explode($separator, $format);
                $formatPicker = '';
                foreach ($expFormat as $i) {
                    if (!isset($map[$i])) {
                        throw new \Exception('Invalid date format');
                    }
                    $formatPicker .= ($formatPicker ? $separator . $map[$i] : $map[$i]);
                }
                $this->options['format'] = $formatPicker;
            }
        }

        $this->value = convertDate(getRecord($this->name), $format);

        return $this;
    }

    public function unformat(): array
    {
        return [$this->getName() => convertDate(request()->{$this->getName()}, 'Y-m-d')];
    }

    public function getFormatPicker(): string
    {
        return $this->options['format'];
    }

    public function getFormat(): string
    {
        return $this->formatPhp;
    }

    public function autoHide(bool $status = true): static
    {
        $this->options['autohide'] = $status;

        return $this;
    }

    public function todayHighlight(bool $status = true): static
    {
        $this->options['todayHighlight'] = $status;

        return $this;
    }

    public function todayButton(bool $status = true): static
    {
        $this->options['todayButton'] = $status;

        return $this;
    }

    public function clearButton(bool $status = true): static
    {
        $this->options['clearButton'] = $status;

        return $this;
    }

    public function datesDisabled(array $dates = []): static
    {
        $this->options['datesDisabled'] = $dates;

        return $this;
    }

    public function daysOfWeekDisabled(array $dates = []): static
    {
        $this->options['daysOfWeekDisabled'] = $dates;

        return $this;
    }

    public function startView(int $start = 0): static
    {
        $this->options['startView'] = $start;

        return $this;
    }

    public function maxView(int $start = 0): static
    {
        $this->options['maxView'] = $start;

        return $this;
    }

    public function title(string $title): static
    {
        $this->options['title'] = $title;

        return $this;
    }

    public function language(string $language): static
    {
        $this->options['language'] = $language;

        return $this;
    }

    public function pickLevel(int $start = 0): static
    {
        $this->options['pickLevel'] = $start;

        return $this;
    }

    public function view(): string | null
    {
        if (!$this->getShow()) {
            return null;
        }

        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.datepicker 
                :value="$value" 
                :columnSpan="$columnSpan" 
                :options="$options" 
                :icon="$icon" 
                :name="$name" 
                :label="$label" 
                :placeholder="$placeholder"
                :attr="$attr"
                :disabled="$disabled"
                :readonly="$readonly"
            />
            HTML,
            [
                'name' => $this->getName(),
                'label' => $this->getLabel(),
                'placeholder' => $this->getPlaceholder(),
                'icon' => $this->icon,
                'options' => $this->options,
                'value' => $this->value,
                'columnSpan' => $this->columnSpan,
                'attr' => $this->attr,
                'disabled' => $this->getDisabled(),
                'readonly' => $this->getReadonly(),
            ]
        );
    }
}
