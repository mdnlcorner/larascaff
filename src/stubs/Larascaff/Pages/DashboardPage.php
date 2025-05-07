<?php

namespace App\Larascaff\Pages;

use Mulaidarinull\Larascaff\Pages\Page;

final class DashboardPage extends Page
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
