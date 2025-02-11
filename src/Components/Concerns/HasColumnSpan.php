<?php

namespace Mulaidarinull\Larascaff\Components\Concerns;

trait HasColumnSpan
{
    protected string|int $columnSpan = 1;
    protected int $columns = 2;

    public function columnSpan(string|int $span)
    {
        $this->columnSpan = $span;
        return $this;
    }

    public function columnSpanFull()
    {
        $this->columnSpan = 'full';
        return $this;
    }

    public function getColumnSpan()
    {
        return $this->columnSpan;
    }

    public function columns(int $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}