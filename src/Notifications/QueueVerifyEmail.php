<?php

namespace Mulaidarinull\Larascaff\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueueVerifyEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;
}
