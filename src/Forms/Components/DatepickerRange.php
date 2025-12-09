<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Forms\Concerns;
use Mulaidarinull\Larascaff\Forms\Contracts\HasDatepicker;
use Mulaidarinull\Larascaff\Forms\Contracts\IsComponent;

class DatepickerRange implements HasDatepicker, IsComponent
{
    use Concerns\HasColumnSpan;
    use Concerns\HasModule;
    use Concerns\HasRelationship;
    use Concerns\HasValidation;
    use Concerns\ResolveClosureParam;

    protected \Closure | bool $show = true;

    public function show(\Closure | bool $status): static
    {
        $this->show = $status;

        return $this;
    }

    public function getShow(): bool
    {
        return $this->resolveClosureParams($this->show);
    }

    protected ?array $name = null;

    protected ?array $placeholder = null;

    protected ?string $label = null;

    protected ?string $attr = null;

    protected bool $icon = true;

    protected string $formatPhp = 'Y-m-d';

    protected \Closure | string | null $type = 'daterange';

    protected ?array $value = null;

    protected ?array $options = [];

    public function __construct()
    {
        $this->options([
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'autohide' => true,
            'todayButton' => true,
            'clearBtn' => true,
            'language' => app()->getLocale(),
        ]);
    }

    public static function make(array $name): static
    {
        $static = resolve(static::class);

        $static->name = $name;

        return $static;
    }

    public function getFormatPicker(): string
    {
        return $this->options['format'];
    }

    public function getFormat(): string
    {
        return $this->formatPhp;
    }

    public function options(array $options = []): static
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    public function name(array $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(?int $index = null): array | string | null
    {
        if (!is_null($index)) {
            return $this->name[$index] ?? null;
        }

        return $this->name;
    }

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function attr(string $attr): static
    {
        $this->attr = $attr;

        return $this;
    }

    public function getAttr(): ?string
    {
        return $this->attr;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function icon($icon = true): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function value($value): static
    {
        $this->value = $value;

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

        $this->value[0] = convertDate(getRecord($this->name[0]), $format);
        $this->value[0] = convertDate(getRecord($this->name[1]), $format);

        return $this;
    }

    public function placeholder(array $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder(): ?array
    {
        return $this->placeholder;
    }

    public function unformat(): array
    {
        $unformat[$this->getName()[0]] = convertDate(request()->{$this->getName()[0]}, 'Y-m-d');
        $unformat[$this->getName()[1]] = convertDate(request()->{$this->getName()[1]}, 'Y-m-d');

        return $unformat;
    }

    public function getformatPhp(): string
    {
        return $this->formatPhp;
    }

    public function getName1(): string
    {
        return $this->name[0];
    }

    public function getName2(): string
    {
        return $this->name[1];
    }

    public function view(): string | null
    {
        if (!$this->getShow()) {
            return null;
        }

        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.datepicker-range 
                :columnSpan="$columnSpan" 
                :value1="$value[0] ?? null" 
                :value2="$value[1] ?? null" 
                :options="$options" 
                :icon="$icon" 
                :name1="$name1 ?? null" 
                :name2="$name2 ?? null" 
                :label="$label" 
                :placeholder1="$placeholder[0] ?? ''" 
                :placeholder2="$placeholder[1] ?? ''" 
                :attr="$attr"
            />
            HTML,
            [
                'name1' => $this->name[0],
                'name2' => $this->name[1],
                'label' => $this->getLabel(),
                'placeholder' => $this->placeholder,
                'icon' => $this->icon,
                'options' => $this->options,
                'value' => $this->value,
                'columnSpan' => $this->columnSpan,
                'attr' => $this->attr,
            ]
        );
    }
}
