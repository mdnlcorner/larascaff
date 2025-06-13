<?php

namespace Mulaidarinull\Larascaff\Tables\Filters;

use Mulaidarinull\Larascaff\Forms\Components\Checkbox;
use Mulaidarinull\Larascaff\Forms\Contracts\HasField;
use Mulaidarinull\Larascaff\Tables\Filters\Concerns\HasQuery;
use Mulaidarinull\Larascaff\Tables\Filters\Contracts\HasFilter;

class Filter extends Checkbox implements HasField, HasFilter
{
    use HasQuery;

    protected bool $toogle = false;

    public function toggle(bool $toggle): static
    {
        $this->toggle = $toggle;

        return $this;
    }
}
