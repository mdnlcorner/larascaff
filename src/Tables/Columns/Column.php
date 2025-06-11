<?php

namespace Mulaidarinull\Larascaff\Tables\Columns;

use Closure;
use Yajra\DataTables\Html\Column as HtmlColumn;

class Column extends HtmlColumn
{
    protected ?Closure $addColumn = null;

    protected ?Closure $editColumn = null;

    protected ?string $removeColumn = null;

    protected array $columnEditing = [];

    public function removeColumn(string $column): static
    {
        $this->columnEditing['removeColumn'] = $column;

        return $this;
    }

    public function addColumn(Closure $cb): static
    {
        $this->columnEditing['addColumn'] = $cb;

        return $this;
    }

    public function editColumn(Closure $cb): static
    {
        $this->columnEditing['editColumn'] = $cb;

        return $this;
    }

    public function getColumnEditing(): array
    {
        return $this->columnEditing;
    }
}
