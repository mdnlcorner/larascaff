<?php

namespace Mulaidarinull\Larascaff\Facades;

use Illuminate\Support\Facades\Facade;
use Mulaidarinull\Larascaff\Colors\ColorManager;

/**
 * @method static \Mulaidarinull\Larascaff\Colors\ColorManager getColors()
 * @method static \Mulaidarinull\Larascaff\Colors\ColorManager register(array $colors)
 */
class LarascaffColor extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ColorManager::class;
    }
}
