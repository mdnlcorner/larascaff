<?php

namespace Mulaidarinull\Larascaff\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\View\View renderStyles(array $data = [], array $mergeData=[])
 *
 * @see \Mulaidarinull\Larascaff\AssetManager
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
