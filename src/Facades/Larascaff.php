<?php

namespace Mulaidarinull\Larascaff\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\View\View content(array $data = [], array $mergeData=[])
 *
 * @see \Mulaidarinull\Larascaff\LarascaffHandler
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
        return 'larascaff';
    }
}
