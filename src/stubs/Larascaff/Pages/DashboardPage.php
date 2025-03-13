<?php

namespace App\Larascaff\Pages;

use Mulaidarinull\Larascaff\BasePage;

final class DashboardPage extends BasePage
{
    protected string $view = 'larascaff::pages.dashboard';

    protected string $menuIcon = 'tabler-home';

    protected string $url = 'dashboard';

    public function __construct()
    {
        parent::__construct();
        $this->permissions = ['read' => true];
    }

    public function viewData(): array
    {
        return [];
    }
}
