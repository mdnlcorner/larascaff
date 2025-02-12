<?php

namespace App\Larascaff\Pages;

use Mulaidarinull\Larascaff\BasePage;

class DashboardPage extends BasePage
{
    protected string $view = 'larascaff::pages.dashboard';
    protected string $menuIcon = 'tabler-home';

    public function viewData(): array
    {
        return [];
    }
}
