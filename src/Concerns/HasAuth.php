<?php

namespace Mulaidarinull\Larascaff\Concerns;

use Closure;
use Illuminate\Support\Str;
use Mulaidarinull\Larascaff\Auth;

trait HasAuth
{
    use EmailVerification;
    use Login;

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

    protected bool $hasPasswordReset = false;

    protected string $passwordResetUrl = 'forgot-password';

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $passwordResetForm = null;

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $passwordResetAction = null;

    protected string $newPasswordUrl = 'reset-password';

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $newPasswordForm = null;

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $newPasswordAction = null;

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

    public function passwordReset(?string $passwordResetUrl = null, Closure | string | array | null $passwordResetForm = null, Closure | string | array | null $passwordResetAction = null, ?string $newPasswordUrl = null, Closure | string | array | null $newPasswordForm = null, Closure | string | array | null $newPasswordAction = null): static
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

    public function getPasswordResetForm(): Closure | string | array
    {
        return $this->passwordResetForm;
    }

    public function getPasswordResetAction(): Closure | string | array
    {
        return $this->passwordResetAction;
    }

    public function getNewPasswordUrl(): string
    {
        return Str::start($this->newPasswordUrl, '/');
    }

    public function getNewPasswordForm(): Closure | string | array
    {
        return $this->newPasswordForm;
    }

    public function getNewPasswordAction(): Closure | string | array
    {
        return $this->newPasswordAction;
    }
}
