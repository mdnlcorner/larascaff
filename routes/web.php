<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Mulaidarinull\Larascaff\ModuleAction;
use Mulaidarinull\Larascaff\Notifications\NotificationRoute;
use Mulaidarinull\Larascaff\OptionsSelectServerSide;
use Mulaidarinull\Larascaff\RepeaterController;
use Mulaidarinull\Larascaff\TempUpload;
use Mulaidarinull\Larascaff\Uploader;

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('notifications/{notification}', [NotificationRoute::class, 'show'])->name('notifications');
        Route::post('temp-upload', TempUpload::class)->middleware('signed')->name('temp-upload');
        Route::post('uploader', Uploader::class)->middleware('signed')->name('uploader');
        Route::get('options', OptionsSelectServerSide::class);
        Route::post('repeater-items', RepeaterController::class);
        Route::post('module-action', ModuleAction::class);

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
