<?php

namespace Mulaidarinull\Larascaff\Assets;

use Mulaidarinull\Larascaff\Facades\LarascaffColor;

class AssetManager
{
    protected array $plugins = [];

    public function renderColorVariants()
    {
        $colorVariants = [];
        $styles = '';
        foreach (LarascaffColor::getColors() as $name => $shades) {
            foreach ($shades as $shade => $color) {
                $styles .= "--{$name}-{$shade}:{$color};";
                if ($shade == 500) {
                    $colorVariants[$name] = $color;
                }
            }
        }

        return '<style>:root {' . $styles . '}</style>' . PHP_EOL .
        "<script data-color-variants type='application/json'>" . json_encode($colorVariants) . '</script>';
    }

    public function register(array $plugins)
    {
        $this->plugins = [...$this->plugins, ...$plugins];
    }

    public function getRegisteredPlugins()
    {
        $plugins = '';
        foreach($this->plugins as $plugin) {
            $plugins .= '<script type="module" src="'.$plugin->getPath().'"></script>';
        }

        return $plugins;
    }
}
