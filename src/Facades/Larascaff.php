<?php

namespace Mulaidarinull\Larascaff\Facades;

use Illuminate\Support\Facades\Facade;
use Mulaidarinull\Larascaff\LarascaffHandler;

/**
 * @method static \Mulaidarinull\Larascaff\LarascaffHanlder content(array $data = [], array $mergeData=[])
 * @method static \Mulaidarinull\Larascaff\LarascaffHanlder registerRoutes()
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
        return LarascaffHandler::class;
    }
}
