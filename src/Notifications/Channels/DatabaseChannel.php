<?php

namespace Mulaidarinull\Larascaff\Notifications\Channels;

class DatabaseChannel
{
    /**
     * store notifications data to database
     */
    public function send($notifiable, $notification)
    {
        $data = array_merge(
            $notification->toDatabase($notifiable),
            ['id' => $notification->id, 'type' => get_class($notification), 'priority' => $notification->getPriority()],
        );

        return $notifiable->routeNotificationFor('database')->create($data);
    }
}
