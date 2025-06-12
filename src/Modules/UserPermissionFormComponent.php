<?php

namespace Mulaidarinull\Larascaff\Modules;

use Illuminate\Contracts\View\View;
use Mulaidarinull\Larascaff\Forms\Components\Component;

class UserPermissionFormComponent extends Component
{
    protected ?\Closure $shareData = null;

    public function shareData(\Closure $cb): static
    {
        $this->shareData = $cb;

        return $this;
    }

    public function getShareData(): array
    {
        $cb = $this->shareData;

        return $cb(getRecord());
    }

    public function view(): View
    {
        return view('larascaff::pages.user-permission-form', [
            'form' => $this,
            ...$this->getShareData(),
        ]);
    }
}
