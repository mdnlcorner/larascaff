<?php

namespace Mulaidarinull\Larascaff\Tables\Filters\Concerns;

use Closure;

trait HasQuery
{
    protected ?Closure $query = null;

    public function query(Closure $cb): static
    {
        $this->query = $cb;

        return $this;
    }

    public function getQuery(): ?Closure
    {
        return $this->query;
    }
}
