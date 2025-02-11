<?php

namespace Mulaidarinull\Larascaff\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueueResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;   
}
