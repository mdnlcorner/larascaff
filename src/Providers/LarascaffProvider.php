<?php

namespace Mulaidarinull\Larascaff\Providers;

use Illuminate\Support\ServiceProvider;
use Mulaidarinull\Larascaff\LarascaffConfig;

abstract class LarascaffProvider extends ServiceProvider
{
    public function register()
    {
        $this->config(LarascaffConfig::make());
    }

    public function boot()
    {
        //
    }

    abstract public function config(LarascaffConfig $config): LarascaffConfig;
}
