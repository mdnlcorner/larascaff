<?php

namespace Mulaidarinull\Larascaff\Facades;

use Illuminate\Support\Facades\Facade;
use Mulaidarinull\Larascaff\AssetManager;

/**
 * @method static \Mulaidarinull\Larascaff\AssetManager renderStyle(array $data = [], array $mergeData=[])
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
        return AssetManager::class;
    }
}
