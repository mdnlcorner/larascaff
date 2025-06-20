<?php

namespace Mulaidarinull\Larascaff\Tables\Columns;

class ImageColumn extends Column
{
    protected string $disk = 'public';

    public function disk(string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }
}
