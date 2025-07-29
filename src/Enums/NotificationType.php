<?php

namespace Mulaidarinull\Larascaff\Enums;

enum NotificationType: string
{
    case Success = 'success';

    case Info = 'info';

    case Warning = 'warning';

    case Error = 'error';
}
