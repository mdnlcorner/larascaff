<?php

namespace Mulaidarinull\Larascaff\Forms\Concerns;

trait HasRelationship
{
    protected ?string $relationship = null;

    public function relationship(?string $name = null)
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
