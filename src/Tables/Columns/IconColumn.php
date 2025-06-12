<?php

namespace Mulaidarinull\Larascaff\Tables\Columns;

class IconColumn extends Column
{
    protected bool $boolean = false;

    public function boolean(bool $status = true): static
    {
        $this->boolean = $status;

        return $this;
    }

    public function isBoolean(): bool
    {
        return $this->boolean;
    }
}
