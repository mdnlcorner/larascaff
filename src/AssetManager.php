<?php

namespace Mulaidarinull\Larascaff;

use Mulaidarinull\Larascaff\Facades\LarascaffColor;

class AssetManager
{
    public function renderStyles()
    {
        $colorVariants = [];
        $view = '';
        foreach (LarascaffColor::getColors() as $name => $shades) {
            foreach ($shades as $shade => $color) {
                $view .= "--{$name}-{$shade}:{$color};";
                if ($shade == 500) {
                    $colorVariants[$name] = $color;
                }
            }
        }

        return '<style>:root {' . $view . '}</style>' . PHP_EOL .
        "<script data-color-variants type='application/json'>" . json_encode($colorVariants) . '</script>';
    }
}
