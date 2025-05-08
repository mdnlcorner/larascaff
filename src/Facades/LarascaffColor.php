<?php

namespace Mulaidarinull\Larascaff\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void register(array $colors)
 * @method static array<string, array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string}> | string processColor(array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | string $color)
 * @method static array<string, array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string}> getColors()
 * @method static void overrideShades(string $alias, array<int> $shades)
 * @method static array<int> | null getOverridingShades(string $alias)
 * @method static void addShades(string $alias, array<int> $shades)
 * @method static array<int> | null getAddedShades(string $alias)
 * @method static void removeShades(string $alias, array<int> $shades)
 * @method static array<int> | null getRemovedShades(string $alias)
 *
 * @see \Mulaidarinull\Larascaff\Colors\ColorManager
 */
class LarascaffColor extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'larascaff.color';
    }
}
