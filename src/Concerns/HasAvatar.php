<?php

namespace Mulaidarinull\Larascaff\Concerns;

use Illuminate\Support\Facades\Storage;

trait HasAvatar
{
    public function getAvatar(): string
    {
        return Storage::disk('public')->url($this->getAvatarPath() . '/' . $this->avatar);
    }

    public function getAvatarPath(): string
    {
        return 'profile';
    }

    public function getAvatarDisk(): string
    {
        return 'public';
    }

    public function getAvatarField(): string
    {
        return 'avatar';
    }
}
