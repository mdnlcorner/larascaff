<?php

namespace Mulaidarinull\Larascaff\Tables\Columns;

class IconColumn extends Column
{
    protected bool $boolean = false;

    public static function make(array | string $data = [], string $name = ''): static
    {
        $static = parent::make($data, $name);
        $static->searchable(false);
        $static->orderable(false);

        return $static;
    }

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
