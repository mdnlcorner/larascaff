<?php

namespace Mulaidarinull\Larascaff\Tables\Columns;

use Closure;
use Mulaidarinull\Larascaff\Enums\ColorVariant;

class TextColumn extends Column
{
    public function badge(bool $status = true): static
    {
        $this->attributes['badge'] = $status;

        return $this;
    }

    public function color(ColorVariant|Closure|string $color): static
    {
        $this->attributes['color'] = $color;

        return $this;
    }
}
