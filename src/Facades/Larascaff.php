<?php

namespace Mulaidarinull\Larascaff\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Mulaidarinull\Larascaff\Larascaff content(array $data = [], array $mergeData=[])
 * @method static \Mulaidarinull\Larascaff\Larascaff registerRoutes()
 */
class Larascaff extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Larascaff';
    }
}
