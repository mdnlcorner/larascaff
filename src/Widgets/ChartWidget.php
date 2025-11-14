<?php

namespace Mulaidarinull\Larascaff\Widgets;

use Mulaidarinull\Larascaff\Enums\ChartType;
use Mulaidarinull\Larascaff\Enums\ColorVariant;
use Mulaidarinull\Larascaff\Widgets\Concerns\WidgetOption;

abstract class ChartWidget extends Widget
{
    use WidgetOption;

    protected static string|ColorVariant $color = ColorVariant::Primary;

    /** @return array<string,array> */
    public static function getData(): array
    {
        return [];
    }

    public static function getColor(): string|ColorVariant
    {
        return static::$color;
    }

    public static function count(): int
    {
        return count(static::getData());
    }

    abstract public static function getType(): string|ChartType;

    final public static function getWidgetType(): string
    {
        return 'chart';
    }
}
