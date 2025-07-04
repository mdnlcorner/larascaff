<?php

namespace Mulaidarinull\Larascaff\Tables\Filters;

use Mulaidarinull\Larascaff\Forms\Components\Datepicker;
use Mulaidarinull\Larascaff\Tables\Filters\Concerns\HasQuery;
use Mulaidarinull\Larascaff\Tables\Filters\Contracts\HasFilter;

class DatepickerFilter extends Datepicker implements HasFilter
{
    use HasQuery;

    public static function make(mixed $name): static
    {
        $static = parent::make($name);

        $static->value(request()->get($static->getName()));

        return $static;
    }
}
