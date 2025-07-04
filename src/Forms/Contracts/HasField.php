<?php

namespace Mulaidarinull\Larascaff\Forms\Contracts;

interface HasField
{
    public function name(string $name): static;

    public function getName(): string;
}
