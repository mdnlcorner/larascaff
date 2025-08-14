<?php

namespace Mulaidarinull\Larascaff\Concerns;

use Closure;
use Illuminate\Support\Str;
use Mulaidarinull\Larascaff\Auth;

trait Registration
{
    protected bool $hasRegistration = false;

    protected string $registrationUrl = 'register';

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $registrationForm = null;

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $registrationAction = null;

    public function registration(?string $url = null, Closure | array | string | null $form = null, Closure | array | string | null $action = null): static
    {
        $this->hasRegistration = true;

        $this->registrationUrl = $url ?? $this->registrationUrl;

        $this->registrationForm = $form ?? [Auth\RegisteredUserController::class, 'create'];

        $this->registrationAction = $action ?? [Auth\RegisteredUserController::class, 'store'];

        return $this;
    }

    public function hasRegistration(): bool
    {
        return $this->hasRegistration;
    }

    public function getRegistrationUrl(): string
    {
        return Str::start($this->registrationUrl, '/');
    }

    public function getRegistrationForm(): Closure | string | array
    {
        return $this->registrationForm;
    }

    public function getRegistrationAction(): Closure | string | array
    {
        return $this->registrationAction;
    }
}
