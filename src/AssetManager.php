<?php

namespace Mulaidarinull\Larascaff;

use Mulaidarinull\Larascaff\Facades\LarascaffColor;

class AssetManager
{
    public function renderStyles()
    {
        $variables = [];
        $colorVariants = [];
        foreach (LarascaffColor::getColors() as $name => $shades) {
            foreach ($shades as $shade => $color) {
                $colorVariants[$name]["{$name}-{$shade}"] = $color;
                $variables["{$name}-{$shade}"] = $color;
            }
        }

        dd(LarascaffColor::getColors());
        dd($colorVariants, $variables);

        return view('larascaff::assets', [
            'cssVariables' => $variables,
        ])->render();
    }

    public function colorVariants()
    {

    }
}
