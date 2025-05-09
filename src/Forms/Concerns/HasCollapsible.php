<?php

namespace Mulaidarinull\Larascaff\Forms\Concerns;

trait HasCollapsible
{
    protected bool $collapsible = false;

    protected bool $collapsed = false;

    public function collapsible(bool $collapsible = true)
    {
        $this->collapsible = $collapsible;

        return $this;
    }

    public function collapsed(bool $collapsed = true)
    {
        $this->collapsed = $collapsed;

        return $this;
    }
}
