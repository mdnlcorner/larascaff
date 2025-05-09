<?php

namespace Mulaidarinull\Larascaff\Forms\Concerns;

trait HasModule
{
    protected $module = null;

    public function module($module)
    {
        $this->module = $module;

        return $this;
    }

    public function getModule()
    {
        return $this->module;
    }
}
