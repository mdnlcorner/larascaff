<?php

namespace Mulaidarinull\Larascaff\Tables\Components;

use Mulaidarinull\Larascaff\Enums\ColorVariant;
use Mulaidarinull\Larascaff\Enums\IconPosition;

class Tab
{
    protected ?\Closure $query = null;

    protected string | int | float | \Closure | null $badge = null;

    protected string | \Closure | ColorVariant | null $badgeColor = null;

    protected string | \Closure | null $badgeIcon = null;

    protected string | \Closure | IconPosition | null $badgeIconPosition = null;

    public static function make(): static
    {
        $static = app(static::class);

        return $static;
    }

    protected function resolve($prop)
    {
        return is_callable($prop) ? $prop() : $prop;
    }

    public function badge(string | int | float | \Closure | null $badge): static
    {
        $this->badge = $badge;

        return $this;
    }

    public function getBadge(): string | int | float | null
    {
        return $this->resolve($this->badge);
    }

    public function badgeIcon(string | \Closure | null $badgeIcon): static
    {
        $this->badgeIcon = $badgeIcon;

        return $this;
    }

    public function getBadgeIcon(): ?string
    {
        return $this->resolve($this->badgeIcon);
    }

    public function badgeColor(string | \Closure | ColorVariant | null $badgeColor): static
    {
        $this->badgeColor = $badgeColor;

        return $this;
    }

    public function getBadgeColor(): ?string
    {
        $prop = $this->resolve($this->badgeColor) ?? ColorVariant::Primary;
        if ($prop instanceof ColorVariant) {
            return $prop->value;
        }

        return $prop;
    }

    public function badgeIconPosition(string | \Closure | IconPosition | null $badgeIconPosition): static
    {
        $this->badgeIconPosition = $badgeIconPosition;

        return $this;
    }

    public function getBadgeIconPosition(): string
    {
        $prop = $this->resolve($this->badgeIconPosition) ?? IconPosition::Before;
        if ($prop instanceof IconPosition) {
            return $prop->value;
        }

        return $prop;
    }

    public function query(?\Closure $cb): static
    {
        $this->query = $cb;

        return $this;
    }

    public function getQuery(): ?\Closure
    {
        return $this->query;
    }
}
