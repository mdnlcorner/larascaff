<?php

namespace Mulaidarinull\Larascaff\Concerns;

trait HasBrand
{
    protected $logo;

    protected $brandName;

    protected $brandHeigh = '2.7rem';

    /**
     * Path location of your brand logo
     */
    public function brandLogo(\Closure|string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getBrandLogo()
    {
        return $this->logo;
    }

    public function brandName(\Closure|string|\Illuminate\Contracts\View\View $brandName): static
    {
        $this->brandName = $brandName;

        return $this;
    }

    public function getBrandName()
    {
        return $this->brandName;
    }

    public function brandHeigh(\Closure|string $height): static
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
}
