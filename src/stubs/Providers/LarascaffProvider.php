<?php

namespace App\Providers;

use Mulaidarinull\Larascaff\LarascaffConfig;
use Mulaidarinull\Larascaff\Providers\LarascaffProvider as Provider;

class LarascaffProvider extends Provider
{
    public function config(LarascaffConfig $config): LarascaffConfig
    {
        return $config->prefix('{{ prefix }}')
            ->login();
    }
}
