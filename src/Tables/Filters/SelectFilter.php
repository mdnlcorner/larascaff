<?php

namespace Mulaidarinull\Larascaff\Tables\Filters;

use Mulaidarinull\Larascaff\Forms\Components\Select;
use Mulaidarinull\Larascaff\Tables\Filters\Concerns\HasQuery;
use Mulaidarinull\Larascaff\Tables\Filters\Contracts\HasFilter;

class SelectFilter extends Select implements HasFilter
{
    use HasQuery;

    public static function make(string $name): static
    {
        $static = parent::make($name);

        $static->value(request()->get($static->getName()));

        $static->query(function ($query, array $data) use ($static) {
            $query->where($static->name, $data[$static->getName()]);
        });

        return $static;
    }
}
