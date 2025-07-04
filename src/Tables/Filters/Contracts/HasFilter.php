<?php

namespace Mulaidarinull\Larascaff\Tables\Filters\Contracts;

use Closure;

interface HasFilter
{
    public function query(Closure $cb): static;

    public function getQuery(): ?Closure;
}
