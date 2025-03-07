<?php

namespace Mulaidarinull\Larascaff;

use Illuminate\Support\Arr;
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

    public function getStyles(?array $packages = null): array
    {
        return $this->getAssets($this->styles, $packages);
    }

    protected function getAssets(array $assets, ?array $packages = null): array
    {
        if ($packages !== null) {
            $assets = Arr::only($assets, $packages);
        }

        return Arr::flatten($assets);
    }
}
