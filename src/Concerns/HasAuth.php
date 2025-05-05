<?php

namespace Mulaidarinull\Larascaff\Concerns;

use Mulaidarinull\Larascaff\Auth;

trait HasAuth
{
    protected bool $hasRegistration = false;

    protected string $registrationUrl = 'register';

    protected string | array $registrationCreateAction = [Auth\RegisteredUserController::class, 'create'];

    protected string | array $registrationStoreAction = [Auth\RegisteredUserController::class, 'store'];

    protected bool $hasPasswordReset = false;

    protected string $passwordResetUrl = 'forgot-password';

    protected string | array $passwordResetCreateAction = [Auth\PasswordResetLinkController::class, 'create'];

    protected string | array $passwordResetStoreAction = [Auth\PasswordResetLinkController::class, 'store'];

    protected string $newPasswordUrl = 'reset-password';

    public function hasRegistration(): bool
    {
        return $this->hasRegistration;
    }

    public function registrationUrl($registrationUrl): static
    {
        $this->registrationUrl = $registrationUrl;

        return $this;
    }

    public function getRegistrationUrl(): string
    {
        return $this->registrationUrl;
    }

    public function registration(): static
    {
        $this->hasRegistration = true;

        return $this;
    }

    public function registrationCreateAction(string | array $action): static
    {
        $this->registrationCreateAction = $action;

        return $this;
    }

    public function getRegistrationCreateAction(): string | array
    {
        return $this->registrationCreateAction;
    }

    public function registrationStoreAction(string | array $action): static
    {
        $this->registrationStoreAction = $action;

        return $this;
    }

    public function getRegistrationStoreAction(): string | array
    {
        return $this->registrationStoreAction;
    }

    public function passwordResetCreateAction(string | array $action): static
    {
        $this->passwordResetCreateAction = $action;

        return $this;
    }

    public function getPasswordResetCreateAction(): string | array
    {
        return $this->passwordResetCreateAction;
    }

    public function passwordResetStoreAction(string | array $action): static
    {
        $this->passwordResetStoreAction = $action;

        return $this;
    }

    public function getPasswordResetStoreAction(): string | array
    {
        return $this->passwordResetStoreAction;
    }

    public function hasPasswordReset(): bool
    {
        return $this->hasPasswordReset;
    }

    public function passwordReset(): static
    {
        $this->hasPasswordReset = true;

        return $this;
    }

    public function passwordResetUrl($passwordResetUrl): static
    {
        $this->passwordResetUrl = $passwordResetUrl;

        return $this;
    }

    public function getPasswordResetUrl(): string
    {
        return \Illuminate\Support\Str::start($this->passwordResetUrl, '/');
    }

    public function newPasswordUrl($newPasswordUrl): static
    {
        $this->newPasswordUrl = $newPasswordUrl;

        return $this;
    }

    public function getNewPasswordUrl(): string
    {
        return $this->newPasswordUrl;
    }
}
