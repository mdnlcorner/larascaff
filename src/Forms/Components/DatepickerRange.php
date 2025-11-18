<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Forms\Concerns\HasColumnSpan;
use Mulaidarinull\Larascaff\Forms\Concerns\HasModule;
use Mulaidarinull\Larascaff\Forms\Concerns\HasRelationship;
use Mulaidarinull\Larascaff\Forms\Concerns\HasValidation;
use Mulaidarinull\Larascaff\Forms\Contracts\HasDatepicker;
use Mulaidarinull\Larascaff\Forms\Contracts\IsComponent;

class DatepickerRange implements HasDatepicker, IsComponent
{
    use HasColumnSpan;
    use HasModule;
    use HasRelationship;
    use HasValidation;

    protected ?array $name = null;

    protected ?array $placeholder = null;

    protected ?string $label = null;

    protected ?string $attr = null;

    protected bool $icon = true;

    protected string $format = 'yyyy-mm-dd';

    protected string $formatPhp = 'Y-m-d';

    protected array $config = [];

    protected ?string $type = 'daterange';

    protected ?array $value = null;

    public static function make(array $name): static
    {
        $static = resolve(static::class);

        $static->name = $name;

        return $static;
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

    public function format($format): static
    {
        $this->format = $format;

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

    public function config(array $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function view(): string
    {
        // ====== FORMATING VALUE ======
        $record = getRecord();
        if ($this->value) {
            $record = (object) [
                $this->name[0] => $this->value[0],
                $this->name[0] => $this->value[1],
            ];
        }

        $map = ['yyyy' => 'Y', 'yy' => 'y', 'm' => 'n', 'D' => 'D', 'DD' => 'l', 'MM' => 'F', 'M' => 'M', 'mm' => 'm', 'dd' => 'd', 'd' => 'j'];
        foreach (['-', '/'] as $separator) {
            if (str_contains($this->format, $separator)) {
                $expFormat = explode($separator, $this->format);
                $formatPhp = '';
                foreach ($expFormat as $i) {
                    $formatPhp .= ($formatPhp ? $separator . $map[$i] : $map[$i]);
                }
                $this->formatPhp = $formatPhp;
            }
        }

        $this->value = $this->value ?? [convertDate($record->{$this->name[0]}, $this->formatPhp), convertDate($record->{$this->name[1]}, $this->formatPhp)];

        // ====== END FORMATING VALUE ======

        $this->config['format'] = $this->format;

        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.datepicker-range 
                :columnSpan="$columnSpan" 
                :value1="$value[0] ?? null" 
                :value2="$value[1] ?? null" 
                :config="$config" 
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
