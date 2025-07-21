<?php

namespace Mulaidarinull\Larascaff\Widgets;

class StatWidget extends Widget
{
    /** @return list<Stat> */
    public function getStats(): array
    {
        return $this->stats;
    }

    public function count(): int
    {
        return count($this->getStats());
    }
}