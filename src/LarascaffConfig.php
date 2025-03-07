<?php

namespace Mulaidarinull\Larascaff;

use Closure;
use Mulaidarinull\Larascaff\Facades\LarascaffColor;

class LarascaffConfig
{
    protected ?string $prefix = null;

    protected static $instance = null;

    protected $logo;

    protected $brandName;

    protected $brandHeigh = '2.7rem';

    protected $footer;

    protected $favicon;

    public static function make(): static
    {
        if (! static::$instance) {
            static::$instance = app(static::class);
        }

        return static::$instance;
    }

    public function prefix(string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Path location of your brand logo
     */
    public function brandLogo(Closure|string $logo)
    {
        $this->logo = $logo;

        return $this;
    }

    public function getBrandLogo()
    {
        return $this->logo;
    }

    public function brandName(Closure|string|\Illuminate\Contracts\View\View $brandName)
    {
        $this->brandName = $brandName;

        return $this;
    }

    public function getBrandName()
    {
        return $this->brandName;
    }

    public function brandHeigh(Closure|string $height)
    {
        $this->brandHeigh = $height;

        return $this;
    }

    public function getBrandHeight()
    {
        return $this->brandHeigh;
    }

    public function renderBrand()
    {
        if ($this->brandName) {
            return $this->brandName;
        }
        if (! $this->logo) {
            return fn () => view('larascaff::logo');
        }

        return $this->logo;
    }

    public function footer(Closure|string|\Illuminate\Contracts\View\View $footer)
    {
        $this->footer = $footer;

        return $this;
    }

    public function getFooter()
    {
        if (! $this->footer) {
            return view('larascaff::footer');
        }

        return $this->footer;
    }

    public function favicon(string $favicon)
    {
        $this->favicon = $favicon;

        return $this;
    }

    public function getFavicon()
    {
        return $this->favicon;
    }

    public function colors(array $colors)
    {
        LarascaffColor::register($colors);

        return $this;
    }
}
