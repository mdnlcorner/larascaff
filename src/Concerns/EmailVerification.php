<?php

namespace Mulaidarinull\Larascaff\Concerns;

use Closure;
use Mulaidarinull\Larascaff\Auth;

trait EmailVerification
{
    protected bool $hasEmailVerification = false;

    protected string $emailVerificationUrl = 'verify-email/{id}/{hash}';

    protected string $emailVerificationPromptUrl = 'verify-email';

    protected string $emailVerificationNotificationUrl = 'verify-email-notification';

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $emailVerificationNotificationAction = null;

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $emailVerificationForm = null;

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $emailVerificationPromptForm = null;

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $emailVerificationAction = null;

    public function getEmailVerificationPromptUrl(): string
    {
        return $this->emailVerificationPromptUrl;
    }

    public function getEmailVerificationPromptAction()
    {
        return $this->emailVerificationPromptAction;
    }

    public function getEmailVerificationPromptForm()
    {
        return $this->emailVerificationPromptForm;
    }

    public function getEmailVerificationUrl(): string
    {
        return $this->emailVerificationUrl;
    }

    public function getEmailVerificationAction()
    {
        return $this->emailVerificationAction;
    }

    public function hasEmailVerification(): bool
    {
        return $this->hasEmailVerification;
    }

    public function getEmailVerificationNotificationUrl(): string
    {
        return $this->emailVerificationNotificationUrl;
    }

    public function getEmailVerificationNotificationAction()
    {
        return $this->emailVerificationNotificationAction;
    }

    public function emailVerification(
        ?string $promptUrl = null,
        Closure | array | string | null $formPrompt = null,
        ?string $notificationUrl = null,
        Closure | array | string | null $notificationAction = null,
        ?string $url = null,
        Closure | array | string | null $action = null,
    ): static {
        $this->hasEmailVerification = true;

        $url ? $this->emailVerificationUrl = $url : null;

        $this->emailVerificationAction = $action ?? Auth\VerifyEmailController::class;

        $this->emailVerificationPromptForm = $formPrompt ?? Auth\EmailVerificationPromptController::class;

        $promptUrl ? $this->emailVerificationPromptUrl = $promptUrl : null;

        $notificationUrl ? $this->emailVerificationNotificationUrl = $notificationUrl : null;

        $this->emailVerificationNotificationAction = $notificationAction ?? [Auth\EmailVerificationNotificationController::class, 'store'];

        return $this;
    }
}
