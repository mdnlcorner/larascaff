<?php

namespace Mulaidarinull\Larascaff\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;

class QueueNotification extends NotificationHandler implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Model $model, protected string $action, protected string $actionLabel = 'Action', protected $title = '', protected $message = '', protected int $priority = 1, protected $channels = null)
    {
        parent::__construct($model, $action, $actionLabel, $title, $message, $priority, $channels);
    }
}
