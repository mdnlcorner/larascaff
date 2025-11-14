<?php

namespace Mulaidarinull\Larascaff\Concerns;

use Closure;
use Illuminate\Support\Str;
use Mulaidarinull\Larascaff\Auth;

trait ResetPassword
{
    protected bool $hasPasswordReset = false;

    protected string $passwordResetUrl = 'forgot-password';

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure|string|array|null $passwordResetForm = null;

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure|string|array|null $passwordResetAction = null;

    protected string $newPasswordUrl = 'reset-password';

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure|string|array|null $newPasswordForm = null;

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure|string|array|null $newPasswordAction = null;

    public function passwordReset(?string $passwordResetUrl = null, Closure|string|array|null $passwordResetForm = null, Closure|string|array|null $passwordResetAction = null, ?string $newPasswordUrl = null, Closure|string|array|null $newPasswordForm = null, Closure|string|array|null $newPasswordAction = null): static
    {
        $this->hasPasswordReset = true;

        $this->passwordResetUrl = $passwordResetUrl ?? $this->passwordResetUrl;

        $this->passwordResetForm = $passwordResetForm ?? [Auth\PasswordResetLinkController::class, 'create'];

        $this->passwordResetAction = $passwordResetAction ?? [Auth\PasswordResetLinkController::class, 'store'];

        $this->newPasswordUrl = $newPasswordUrl ?? $this->newPasswordUrl;

        $this->newPasswordForm = $newPasswordForm ?? [Auth\NewPasswordController::class, 'create'];

        $this->newPasswordAction = $newPasswordAction ?? [Auth\NewPasswordController::class, 'store'];

        return $this;
    }

    public function hasPasswordReset(): bool
    {
        return $this->hasPasswordReset;
    }

    public function getPasswordResetUrl(): string
    {
        return Str::start($this->passwordResetUrl, '/');
    }

    public function getPasswordResetForm()
    {
        return $this->passwordResetForm;
    }

    public function getPasswordResetAction()
    {
        return $this->passwordResetAction;
    }

    public function getNewPasswordUrl(): string
    {
        return Str::start($this->newPasswordUrl, '/');
    }

    public function getNewPasswordForm()
    {
        return $this->newPasswordForm;
    }

    public function getNewPasswordAction()
    {
        return $this->newPasswordAction;
    }
}
