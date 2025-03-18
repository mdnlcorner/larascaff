<?php

namespace Mulaidarinull\Larascaff\Components\Layouts;

class ListTab
{
    protected ?\Closure $query = null;
    protected string|int|float|\Closure|null $badge = null;
    protected string|null $badgeColor = null;

    public static function make(): static
    {
        $static = app(static::class);
        return $static;
    }

    public function badge(string|int|float|\Closure|null $badge): static
    {
        $this->badge = $badge;
        return $this;
    }

    public function getBadge()
    {
        return $this->badge;
    }

    public function badgeColor(string $badgeColor): static
    {
        $this->badgeColor = $badgeColor;
        return $this;
    }

    public function getBadgeColor()
    {
        return $this->badgeColor;
    }

    public function query(?\Closure $cb): static
    {
        $this->query = $cb;
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }
}