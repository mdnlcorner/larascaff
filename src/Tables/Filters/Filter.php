<?php

namespace Mulaidarinull\Larascaff\Tables\Filters;

use Mulaidarinull\Larascaff\Forms\Components\Checkbox;
use Mulaidarinull\Larascaff\Tables\Filters\Concerns\HasQuery;
use Mulaidarinull\Larascaff\Tables\Filters\Contracts\HasFilter;

class Filter extends Checkbox implements HasFilter
{
    use HasQuery;

    protected bool $toogle = false;

    public function toggle(bool $toggle = true): static
    {
        $this->toggle = $toggle;

        return $this;
    }

    public static function make(mixed $name): static
    {
        $static = parent::make($name);

        $static->checked = request()->get($static->getName());

        $static->query(function ($query, array $data) use ($static) {
            $query->where($static->getName(), $data[$static->getName()]);
        });

        return $static;
    }
}
