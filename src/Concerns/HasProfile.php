<?php

namespace Mulaidarinull\Larascaff\Concerns;

use Closure;
use Mulaidarinull\Larascaff\Auth\ProfileController;

trait HasProfile
{
    protected bool $hasProfile = false;

    protected bool $hasDeleteProfile = false;

    protected string $profileUrl = 'profile';

    protected string $updateAvatarUrl = 'profile-avatar';

    protected string $updatePasswordUrl = 'password';

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $updatePasswordAction = null;

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $updateAvatarAction = null;

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $profileForm = null;

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $profileAction = null;

    /**
     * @var Closure | string | array<class-string, string> | null
     */
    protected Closure | string | array | null $profileDeleteAction = null;

    public function getUpdatePasswordUrl(): string
    {
        return $this->updatePasswordUrl;
    }

    public function getUpdatePasswordAction()
    {
        return $this->updatePasswordAction;
    }

    public function getProfileDeleteAction()
    {
        return $this->profileDeleteAction;
    }

    public function getProfileAction()
    {
        return $this->profileAction;
    }

    public function getProfileUrl(): string
    {
        return $this->profileUrl;
    }

    public function getProfileForm()
    {
        return $this->profileForm;
    }

    public function hasProfile(): bool
    {
        return $this->hasProfile;
    }

    public function hasDeleteProfile(): bool
    {
        return $this->hasDeleteProfile;
    }

    public function getUpdateAvatarUrl(): string
    {
        return $this->updateAvatarUrl;
    }

    public function getUpdateAvatarAction()
    {
        return $this->updateAvatarAction;
    }

    public function profile(
        ?string $url = null,
        Closure | string | array | null $form = null,
        Closure | string | array | null $action = null,
        bool $hasDelete = false,
        Closure | string | array | null $deleteAction = null,
        ?string $updateAvatarUrl = null,
        Closure | string | array | null $udpateAvatarAction = null,
        ?string $updatePasswordUrl = null,
        Closure | string | array | null $updatePasswordAction = null,
    ): static {
        $this->hasProfile = true;

        $url ? $this->profileUrl = $url : null;

        $this->profileForm = $form ?? [ProfileController::class, 'edit'];

        $this->profileAction = $action ?? [ProfileController::class, 'update'];

        $this->hasDeleteProfile = $hasDelete;

        $this->profileDeleteAction = $deleteAction ?? [ProfileController::class, 'delete'];

        $updateAvatarUrl ? $this->updateAvatarUrl = $updateAvatarUrl : null;

        $this->updateAvatarAction = $udpateAvatarAction ?? [ProfileController::class, 'updateAvatar'];

        $updatePasswordUrl ? $this->updatePasswordUrl = $updatePasswordUrl : null;

        $this->updatePasswordAction = $updatePasswordAction ?? [ProfileController::class, 'updatePassword'];

        return $this;
    }
}
