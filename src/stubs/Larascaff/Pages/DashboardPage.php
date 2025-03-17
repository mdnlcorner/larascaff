<?php

namespace App\Larascaff\Pages;

use Mulaidarinull\Larascaff\BasePage;

final class DashboardPage extends BasePage
{
    protected static ?string $view = 'larascaff::pages.dashboard';

    protected static ?string $menuIcon = 'tabler-home';

    protected static ?string $url = 'dashboard';

    public static function permissions()
    {
        return ['read'];
    }

    public static function viewData(): array
    {
        return [];
    }
}
