<?php

namespace Mulaidarinull\Larascaff\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Mulaidarinull\Larascaff\Notifications\Channels\DatabaseChannel;

class NotificationHandler extends Notification
{
    protected $user;

    protected array $channels = [];

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Model $model, protected string $action, protected string $actionLabel = 'Action', protected $title = '', protected $message = '', protected int $priority = 1)
    {
        $this->model = $model;
        $this->action = $action;
        $this->title = $title;
        $this->message = $message;
        $this->user = user();
        $this->channels = [DatabaseChannel::class];
    }

    public function setPriority(int $priority)
    {
        $this->priority = $priority;

        return $this;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Add notification channel
     */
    public function addChannel(string $channel)
    {
        $this->channels[] = $channel;

        return $this;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $this->channels;
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'model_id' => $this->model->id,
            'model_type' => get_class($this->model),
            'user_id' => $this->user->id,
            'user_type' => get_class($this->user),
            'priority' => $this->priority,
            'data' => [
                'title' => $this->title,
                'message' => $this->message,
                'action' => $this->action,
                'actionLabel' => $this->actionLabel,
            ],
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line($this->title ?? 'Notification title')
            ->line($this->message)
            ->action($this->actionLabel, $this->action);
    }
}
