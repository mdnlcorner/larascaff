<?php

namespace App\Larascaff\Modules;

use Mulaidarinull\Larascaff\Modules\BaseUserModule;

final class UserModule extends BaseUserModule
{
    protected static ?string $menuIcon = 'tabler-users-group';

    protected static ?string $menuCategory = 'CONFIGURATION';
}
