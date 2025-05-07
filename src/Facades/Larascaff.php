<?php

namespace Mulaidarinull\Larascaff\Facades;

use Illuminate\Support\Facades\Facade;
use Mulaidarinull\Larascaff\LarascaffHandler;

/**
 * @method static \Illuminate\View\View content(array $data = [], array $mergeData=[])
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
