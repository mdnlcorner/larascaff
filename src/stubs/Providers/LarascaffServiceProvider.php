<?php

namespace App\Providers;

use Mulaidarinull\Larascaff\LarascaffConfig;
use Mulaidarinull\Larascaff\Providers\LarascaffProvider;

class LarascaffServiceProvider extends LarascaffProvider
{
    public function config(LarascaffConfig $config)
    {
        $config->prefix('{{ prefix }}');
    }
}
