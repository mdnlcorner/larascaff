<?php

namespace Mulaidarinull\Larascaff\Concerns;

use Closure;
use Illuminate\Support\Str;
use Mulaidarinull\Larascaff\Auth;

trait Login
{
    protected bool $hasLogin = false;

    protected string $loginUrl = 'login';

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $loginForm = null;

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $loginAction = null;

    protected string $logoutUrl = 'logout';

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $logoutAction = null;

    public function login(?string $url = null, Closure | array | string | null $form = null, Closure | array | string | null $action = null): static
    {
        $this->hasLogin = true;

        $this->loginUrl = $url ?? $this->loginUrl;

        $this->loginForm = $form ?? [Auth\AuthenticatedSessionController::class, 'create'];

        $this->loginAction = $action ?? [Auth\AuthenticatedSessionController::class, 'store'];

        $this->logoutAction = [Auth\AuthenticatedSessionController::class, 'destroy'];

        return $this;
    }

    public function logout(?string $url, Closure | array | string | null $action)
    {
        $this->logoutUrl = $url ?? $this->logoutUrl;

        $this->logoutAction = $action ?? $this->logoutAction;
    }

    public function getLogoutUrl(): string
    {
        return Str::start($this->logoutUrl, '/');
    }

    public function getLogoutAction()
    {
        return $this->logoutAction;
    }

    public function hasLogin(): bool
    {
        return $this->hasLogin;
    }

    public function getLoginUrl(): string
    {
        return Str::start($this->loginUrl, '/');
    }

    public function getLoginForm()
    {
        return $this->loginForm;
    }

    public function getLoginAction()
    {
        return $this->loginAction;
    }
}
