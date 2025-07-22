<?php

namespace Mulaidarinull\Larascaff\Widgets;

abstract class StatWidget extends Widget
{
    /** @return list<Stat> */
    public static function getStats(): array
    {
        return [];
    }

    public static function count(): int
    {
        return count(static::getStats());
    }

    public static function getWidgetType(): string
    {
        return 'statistic';
    }
}