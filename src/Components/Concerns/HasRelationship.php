<?php

namespace Mulaidarinull\Larascaff\Components\Concerns;

trait HasRelationship
{
    protected string | null $relationship = null;

    public function relationship(string|null $name = null)
    {
        $this->relationship = $name;
        if (is_null($name)) {
            $this->relationship = $this->name;
        }
        return $this;
    }

    public function getRelationship()
    {
        return $this->relationship ?? null;
    }
}
