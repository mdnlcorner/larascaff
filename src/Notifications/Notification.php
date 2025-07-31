<?php

namespace Mulaidarinull\Larascaff\Notifications;

use ArrayAccess;
use Mulaidarinull\Larascaff\Enums\NotificationPosition;
use Mulaidarinull\Larascaff\Enums\NotificationType;
use Mulaidarinull\Larascaff\Traits\HasArrayAccess;

class Notification implements ArrayAccess
{
    use HasArrayAccess;

    public static function make(): static
    {
        $static = app(static::class);

        $static->options = [
            'title' => 'Saved',
            'body' => null,
            'position' => NotificationPosition::TopRight,
            'type' => NotificationType::Success,
        ];

        return $static;
    }

    public function success(): static
    {
        $this->options['type'] = NotificationType::Success;

        return $this;
    }

    public function error(): static
    {
        $this->options['type'] = NotificationType::Error;

        return $this;
    }

    public function info(): static
    {
        $this->options['type'] = NotificationType::Info;

        return $this;
    }

    public function warning(): static
    {
        $this->options['type'] = NotificationType::Warning;

        return $this;
    }

    public function title(?string $title): static
    {
        if ($title) {
            $this->options['title'] = $title;
        }

        return $this;
    }

    public function body(?string $body): static
    {
        if ($body) {
            $this->options['body'] = $body;
        }

        return $this;
    }

    public function position(?NotificationPosition $position): static
    {
        if ($position) {
            $this->options['position'] = $position;
        }

        return $this;
    }

    public function type(?NotificationType $type): static
    {
        if ($type) {
            $this->options['type'] = $type;
        }

        return $this;
    }
}
