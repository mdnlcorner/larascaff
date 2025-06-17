<?php

namespace Mulaidarinull\Larascaff\Widgets;

use Mulaidarinull\Larascaff\Enums\ColorVariant;
use Mulaidarinull\Larascaff\Enums\IconPosition;

class StatWidget
{
    protected array $options = [
        'color' => ColorVariant::Primary,
    ];

    public function __construct(protected string $label, protected mixed $value)
    {
        $this->options['label'] = $label;
        $this->options['value'] = $value;
    }

    public static function make(string $label, mixed $value): static
    {
        $static = app(static::class, ['label' => $label, 'value' => $value]);

        return $static;
    }

    public function color(ColorVariant $color): static
    {
        $this->options['color'] = $color;

        return $this;
    }

    public function chart(array $options): static
    {
        $this->options['chart'] = $options;

        return $this;
    }

    public function description(string $description): static
    {
        $this->options['description'] = $description;

        return $this;
    }

    public function descriptionIcon(string $icon, ?IconPosition $position = null): static
    {
        $this->options['descriptionIcon'] = ['icon' => $icon, 'position' => $position ?? IconPosition::After];

        return $this;
    }

    public function toArray(): array
    {
        return $this->options;
    }
}
