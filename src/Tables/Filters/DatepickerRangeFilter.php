<?php

namespace Mulaidarinull\Larascaff\Tables\Filters;

use Mulaidarinull\Larascaff\Forms\Components\DatepickerRange;
use Mulaidarinull\Larascaff\Tables\Filters\Concerns\HasQuery;
use Mulaidarinull\Larascaff\Tables\Filters\Contracts\HasFilter;

class DatepickerRangeFilter extends DatepickerRange implements HasFilter
{
    use HasQuery;

    public static function make(mixed $name): static
    {
        $static = parent::make($name);

        $static->value([request()->get($static->getName1()), request()->get($static->getName2())]);

        return $static;
    }
}
