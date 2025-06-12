<?php

namespace Mulaidarinull\Larascaff\Forms\Concerns;

trait HasModule
{
    protected ?string $module = null;

    public function module($module): static
    {
        $this->module = $module;

        return $this;
    }

    public function getModule()
    {
        return $this->module;
    }
}
