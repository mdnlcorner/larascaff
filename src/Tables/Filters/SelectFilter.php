<?php

namespace Mulaidarinull\Larascaff\Tables\Filters;

use Mulaidarinull\Larascaff\Forms\Components\Select;
use Mulaidarinull\Larascaff\Forms\Contracts\HasField;
use Mulaidarinull\Larascaff\Tables\Filters\Concerns\HasQuery;
use Mulaidarinull\Larascaff\Tables\Filters\Contracts\HasFilter;

class SelectFilter extends Select implements HasField, HasFilter
{
    use HasQuery;
}
