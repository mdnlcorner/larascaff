<?php

namespace Mulaidarinull\Larascaff\Tables\Columns;

class DateColumn extends Column
{
    protected string $format = 'd-m-Y';

    public function format(string $format = 'd-m-Y'): static
    {
        $this->format = $format;

        return $this;
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
