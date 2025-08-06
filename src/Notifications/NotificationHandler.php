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
     * @param class-string<Module> $module
     */
    public function __construct(protected Model $model, string $module) {
        $this->user = user();

        $this->module = $module;
        
        $this->channels = [DatabaseChannel::class];
    }

    final public function getModel(): Model
    {
        return $this->model;
    }

    final public function getUser(): User
    {
        return $this->user;
    }

    public function channels(): array
    {
        return [];
    }

    public function path(string $path = ''): string
    {
        return url($this->module::getUrl() . $path);
    }

    final public function via(User $notifiable): array
    {
        $this->notifiable = $notifiable;

        return array_merge($this->channels, $this->channels());
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
                'model' => [$parameter->getName() => $this->model],
                'user' => [$parameter->getName() => $this->user],
                'notifiable' => [$parameter->getName() => $this->notifiable],
                'module' => [$parameter->getName() => $this->module],
                default => []
            };

            $type = match ($parameter->getType()?->getName()) {
                get_class($this->getModel()) => [$parameter->getName() => getRecord()],
                $this->module => [$parameter->getName() => $this->module],
                default => []
            };

            $parameters = [...$parameters, ...$type, ...$default];
        }

        return app()->call([$this, $method], $parameters);
    }
}
