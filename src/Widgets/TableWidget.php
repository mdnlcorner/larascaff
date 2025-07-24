<?php

namespace Mulaidarinull\Larascaff\Widgets;

use Mulaidarinull\Larascaff\Tables;
use Mulaidarinull\Larascaff\Widgets\Concerns\WidgetOption;

abstract class TableWidget extends Widget
{
    use WidgetOption;

    protected static ?string $model = null;

    public static function getWidgetType(): string
    {
        return 'table';
    }

    public static function getModel()
    {
        return static::$model;
    }

    abstract public static function table(Tables\Table $table): Tables\Table;
}
