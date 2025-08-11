<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Mulaidarinull\Larascaff\Enums\NotificationPosition;
use Mulaidarinull\Larascaff\Enums\NotificationType;
use Mulaidarinull\Larascaff\Notifications\Notification;

trait HasNotification
{
    protected ?string $notificationTitle = null;

    protected ?string $notificationBody = null;

    protected ?NotificationType $notificationType = null;

    protected ?NotificationPosition $notificationPosition = null;

    protected ?Notification $notification = null;

    public function notification(Notification $notification): static
    {
        $this->notification = $notification;

        return $this;
    }

    public function notificationTitle(string $title): static
    {
        $this->notificationTitle = $title;

        return $this;
    }

    public function notificationBody(string $body): static
    {
        $this->notificationBody = $body;

        return $this;
    }

    public function notificationType(NotificationType $type): static
    {
        $this->notificationType = $type;

        return $this;
    }

    public function notificationPosition(NotificationPosition $position): static
    {
        $this->notificationPosition = $position;

        return $this;
    }

    public function getNotification(): Notification
    {
        if (is_null($this->notification)) {
            $this->notification = Notification::make()
                ->title($this->notificationTitle)
                ->body($this->notificationBody)
                ->type($this->notificationType)
                ->position($this->notificationPosition);
        }

        return $this->notification;
    }
}
