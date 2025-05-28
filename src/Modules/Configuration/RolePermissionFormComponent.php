<?php

namespace Mulaidarinull\Larascaff\Modules\Configuration;

use Illuminate\Contracts\View\View;
use Mulaidarinull\Larascaff\Forms\Components\Component;

class RolePermissionFormComponent extends Component
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
        return view('larascaff::pages.role-permission-form', [
            'form' => $this,
            ...$this->getShareData(),
        ]);
    }
}
