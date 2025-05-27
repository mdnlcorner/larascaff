<?php

namespace Mulaidarinull\Larascaff\Modules;

use Mulaidarinull\Larascaff\Forms\Components\Component;

class PermissionFormComponent extends Component
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

    public function view()
    {
        return view('larascaff::pages.user-permission-form', [
            'form' => $this,
            ...$this->getShareData(),
        ]);
    }
}
