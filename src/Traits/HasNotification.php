<?php

namespace Mulaidarinull\Larascaff\Traits;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Pluralizer;
use Mulaidarinull\Larascaff\Notifications\NotificationHandler;
use Mulaidarinull\Larascaff\Notifications\QueueNotification;

trait HasNotification
{
    protected $channels = [];

    public function sendNotification($targetUser, $action, $actionLabel = 'Action', $title = '', $message = '', $priority = 1, $queue = false)
    {
        if ($queue) {
            $notification = QueueNotification::class;
        } else {
            $notification = NotificationHandler::class;
        }
        $notification = new $notification($this, $action, $actionLabel, $title, $message, $priority);
        foreach ($this->channels as $channel) {
            $notification->addChannel($channel);
        }
        Notification::send($targetUser, $notification);
    }

    public function routeNotification()
    {
        if ($this->route) {
            return $this->route;
        }

        $route = explode('App\\Models\\', get_class($this));
        array_shift($route);

        return Pluralizer::plural(strtolower(str_replace('\\', '/', $route[0])));
    }

    public function addChannel($channel)
    {
        $this->channels[] = $channel;
    }
}
