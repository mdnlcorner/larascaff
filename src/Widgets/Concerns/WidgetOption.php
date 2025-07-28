<?php

namespace Mulaidarinull\Larascaff\Widgets\Concerns;

trait WidgetOption
{
    protected static ?string $heading = null;

    protected static ?string $description = null;

    public static function getHeading(): ?string
    {
        return static::$heading;
    }

    public static function getDescription(): ?string
    {
        return static::$description;
    }
}
