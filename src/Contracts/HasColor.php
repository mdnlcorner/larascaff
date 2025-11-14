<?php

namespace Mulaidarinull\Larascaff\Contracts;

use Mulaidarinull\Larascaff\Enums\ColorVariant;

interface HasColor
{
    public function getColor(): string|ColorVariant;
}
