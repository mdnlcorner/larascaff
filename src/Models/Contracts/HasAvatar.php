<?php

namespace Mulaidarinull\Larascaff\Models\Contracts;

interface HasAvatar
{
    public function getAvatar(): string;

    public function getAvatarField(): string;

    public function getAvatarPath(): string;

    public function getAvatarDisk(): string;
}
