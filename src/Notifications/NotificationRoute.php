<?php

namespace Mulaidarinull\Larascaff\Notifications;

use Illuminate\Notifications\DatabaseNotification;

class NotificationRoute
{
    public function __invoke(DatabaseNotification $notification)
    {
        if ($notification->priority != 1) {
            $notification->read_at = now();
            $notification->save();
        }

        return redirect($notification['data']['action']);
    }
}
