<?php

namespace Mulaidarinull\Larascaff\Notifications;

use Mulaidarinull\Larascaff\Enums\NotificationPosition;

class Notification
{
    protected string $title = 'Saved';

    protected string $body = '';

    protected NotificationPosition $position = NotificationPosition::TopRight;

    public static function make(): static
    {
        $static = app(static::class);

        return $static;
    }

    public function title(?string $title): static
    {
        if ($title) {
            $this->title = $title;
        }

        return $this;
    }

    public function body(?string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function position(NotificationPosition $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getNotification(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'position' => $this->position->value,
        ];
    }
}
