<?php

namespace Mulaidarinull\Larascaff;

use Illuminate\Contracts\View\View;
use Mulaidarinull\Larascaff\Facades\LarascaffColor;

class LarascaffConfig
{
    use Concerns\HasAuth;
    use Concerns\HasBrand;
    use Concerns\HasDatabaseTransactions;
    use Concerns\HasMiddleware;
    use Concerns\HasProfile;

    protected string $prefix = '';

    protected static ?LarascaffConfig $instance = null;

    protected \Closure|string|View|null $footer = null;

    protected \Closure|string|null $favicon = null;

    public static function make(): static
    {
        static::$instance = app()->make(static::class);

        return static::$instance;
    }

    public function prefix(string $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function footer(\Closure|string|View $footer): static
    {
        $this->footer = is_callable($footer) ? call_user_func($footer) : $footer;

        return $this;
    }

    public function getFooter()
    {
        return $this->footer ?? view('larascaff::footer');
    }

    public function favicon(string $url): static
    {
        $this->favicon = $url;

        return $this;
    }

    public function getFavicon()
    {
        return $this->favicon ?? url('favicon.ico');
    }

    public function colors(array $colors): static
    {
        LarascaffColor::register($colors);

        return $this;
    }
}
