<?php

namespace Mulaidarinull\Larascaff\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Mulaidarinull\Larascaff\Modules\Module;
use Mulaidarinull\Larascaff\Notifications\Channels\DatabaseChannel;

abstract class NotificationHandler extends Notification implements ShouldQueue
{
    use Queueable;

    protected User $user;

    protected array $channels = [];

    protected ?User $notifiable = null;

    protected int $priority = 1;

    /** @var class-string<Module> */
    protected ?string $module;

    /**
     * @param  class-string<Module>  $module
     */
    public function __construct(protected Model $record, string $module)
    {
        $this->user = user();

        $this->module = $module;

        $this->channels = [DatabaseChannel::class];
    }

    public function path(string $path = ''): string
    {
        return url($this->module::getUrl() . $path);
    }

    public function getRecord(): Model
    {
        return $this->record;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getModule(): string
    {
        return $this->module;
    }

    public function getNotifiable(): User
    {
        return $this->notifiable;
    }

    final public function via(User $notifiable): array
    {
        $this->notifiable = $notifiable;

        return array_merge($this->channels, $this->resolveMethodParams('channels') ?? []);
    }

    public function toDatabase(User $notifiable): array
    {
        return [
            'data' => [
                'title' => $this->resolveMethodParams('title'),
                'message' => $this->resolveMethodParams('message'),
                'action' => $this->resolveMethodParams('action'),
                'actionLabel' => $this->resolveMethodParams('actionLabel'),
            ],
        ];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->resolveMethodParams('subject'))
            ->line($this->resolveMethodParams('title'))
            ->line($this->resolveMethodParams('message'))
            ->action($this->resolveMethodParams('actionLabel'), $this->resolveMethodParams('action'));
    }

    public function resolveMethodParams($method)
    {
        if (! method_exists($this, $method)) {
            return null;
        }

        $parameters = [];
        foreach ((new \ReflectionMethod($this, $method))->getParameters() as $parameter) {
            $default = match ($parameter->getName()) {
                'record' => [$parameter->getName() => $this->record],
                'user' => [$parameter->getName() => $this->user],
                'notifiable' => [$parameter->getName() => $this->notifiable],
                'module' => [$parameter->getName() => $this->module],
                default => []
            };

            $type = match ($parameter->getType()?->getName()) {
                get_class($this->record) => [$parameter->getName() => getRecord()],
                $this->module => [$parameter->getName() => $this->module],
                default => []
            };

            $parameters = [...$parameters, ...$type, ...$default];
        }

        return app()->call([$this, $method], $parameters);
    }
}
