<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Mulaidarinull\Larascaff\Actions\Action;
use Mulaidarinull\Larascaff\Actions\RouteHandler;
use Mulaidarinull\Larascaff\Forms\Components\Repeater;
use Mulaidarinull\Larascaff\Forms\Components\Select;
use Mulaidarinull\Larascaff\Forms\Components\Uploader;
use Mulaidarinull\Larascaff\Notifications\NotificationRoute;

Route::middleware(larascaffConfig()->getMiddleware())->group(function () {
    Route::middleware(larascaffConfig()->getAuthMiddleware())->group(function () {
        Route::get('notifications/{notification}', [NotificationRoute::class, 'show'])->name('notifications');
        Route::post('temp-upload', [Uploader::class, 'tempUploadHandler'])->middleware('signed')->name('temp-upload');
        Route::post('uploader', [Uploader::class, 'uploadHandler'])->middleware('signed')->name('uploader');
        Route::get('options', [Select::class, 'serverSideOptionsHandler']);
        Route::post('repeater-items', [Repeater::class, 'repeaterHandler']);
        Route::post('module-action', RouteHandler::class);
        Route::post('larascaff', [Action::class, 'actionHandler']);

        // Pages route
        File::ensureDirectoryExists(app_path('Larascaff/Pages'));
        foreach (File::allFiles(app_path('Larascaff/Pages')) as $page) {
            $class = 'App\\Larascaff\\Pages\\' . (str_replace([DIRECTORY_SEPARATOR, '.php'], ['\\', ''], $page->getRelativePathname()));
            $class::registerRoutes();
        }

        // Modules route
        File::ensureDirectoryExists(app_path('Larascaff/Modules'));
        foreach (File::allFiles(app_path('Larascaff/Modules')) as $module) {
            $class = 'App\\Larascaff\\Modules\\' . (str_replace([DIRECTORY_SEPARATOR, '.php'], ['\\', ''], $module->getRelativePathname()));
            $class::registerRoutes();
        }
    });
    require __DIR__ . '/auth.php';
});
