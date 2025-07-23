<?php

namespace Mulaidarinull\Larascaff\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string renderColorVariants()
 * @method static string register(array $plugins = [])
 *
 * @see \Mulaidarinull\Larascaff\Assets\AssetManager
 */
class LarascaffAsset extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'larascaff.asset';
    }
}
