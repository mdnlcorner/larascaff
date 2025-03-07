<?php

namespace Mulaidarinull\Larascaff\Components\Concerns;

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
