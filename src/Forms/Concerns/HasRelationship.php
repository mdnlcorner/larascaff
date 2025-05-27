<?php

namespace Mulaidarinull\Larascaff\Forms\Concerns;

trait HasRelationship
{
    protected ?string $relationship = null;

    protected ?string $parentRelationship = null;

    public function relationship(?string $name = null, ?string $label = 'name'): static
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

    public function parentRelationship(string $name): static
    {
        $this->parentRelationship = $name;

        return $this;
    }

    public function getParentRelationship(): ?string
    {
        return $this->parentRelationship;
    }
}
