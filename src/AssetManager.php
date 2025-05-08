<?php

namespace Mulaidarinull\Larascaff;

use Mulaidarinull\Larascaff\Facades\LarascaffColor;

class AssetManager
{
    public function renderStyles()
    {
        $variables = [];
        foreach (LarascaffColor::getColors() as $name => $shades) {
            foreach ($shades as $shade => $color) {
                $variables["{$name}-{$shade}"] = $color;
            }
        }

        return view('larascaff::assets', [
            'cssVariables' => $variables,
        ])->render();
    }
}
