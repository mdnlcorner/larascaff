<?php

namespace Mulaidarinull\Larascaff\Tables\Columns;

use Closure;
use Yajra\DataTables\Html\Column as HtmlColumn;

class Column extends HtmlColumn
{
    protected ?Closure $state = null;

    public function state(Closure $cb): static
    {
        $this->state = $cb;

        return $this;
    }

    public function getState(): ?Closure
    {
        return $this->state;
    }
}
