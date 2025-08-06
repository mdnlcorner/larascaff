<?php

namespace Mulaidarinull\Larascaff\Notifications\Channels;

use App\Models\User;
use Mulaidarinull\Larascaff\Notifications\NotificationHandler;

class DatabaseChannel
{
    /**
     * store notifications data to database
     */
    public function send(User $notifiable, NotificationHandler $notification)
    {
        $data = array_merge(
            $notification->toDatabase($notifiable),
            [
                'id' => $notification->id,
                'type' => get_class($notification),
                'priority' => $notification->resolveMethodParams('priority') ?? 1,
                'model_id' => $notification->getModel()->id,
                'model_type' => get_class($notification->getModel()),
                'user_id' => $notification->getUser()->id,
                'user_type' => get_class($notification->getUser()),
            ],
        );

        return $notifiable->routeNotificationFor('database')->create($data);
    }
}
