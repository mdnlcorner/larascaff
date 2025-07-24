<?php

namespace Mulaidarinull\Larascaff\Widgets;

use ArrayAccess;
use Mulaidarinull\Larascaff\Enums\ColorVariant;
use Mulaidarinull\Larascaff\Enums\IconPosition;

class Stat implements ArrayAccess
{
    protected array $options = [];

    public function __construct(protected string $label, protected mixed $value)
    {
        $this->options['label'] = $label;
        $this->options['value'] = $value;
        $this->options['color'] = ColorVariant::Primary;
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

    /**
     * Determine if an item exists at an offset.
     *
     * @param  TKey  $key
     */
    public function offsetExists($key): bool
    {
        return isset($this->options[$key]);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  TKey  $key
     * @return TValue
     */
    public function offsetGet($key): mixed
    {
        return $this->options[$key];
    }

    /**
     * Set the item at a given offset.
     *
     * @param  TKey|null  $key
     * @param  TValue  $value
     */
    public function offsetSet($key, $value): void
    {
        if (is_null($key)) {
            $this->options[] = $value;
        } else {
            $this->options[$key] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  TKey  $key
     */
    public function offsetUnset($key): void
    {
        unset($this->options[$key]);
    }
}
