<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Mulaidarinull\Larascaff\Actions\Action;
use Mulaidarinull\Larascaff\Actions\RouteHandler;
use Mulaidarinull\Larascaff\Forms\Components\Repeater;
use Mulaidarinull\Larascaff\Forms\Components\Select;
use Mulaidarinull\Larascaff\Modules\Module;
use Mulaidarinull\Larascaff\Notifications\NotificationRoute;
use Mulaidarinull\Larascaff\Pages\FileUpload;
use Mulaidarinull\Larascaff\Pages\Page;

$config = larascaffConfig();
Route::middleware($config->getMiddleware())->group(function () use ($config) {
    Route::middleware([...$config->getAuthMiddleware(), 'verified'])->group(function () {
        Route::get('notifications/{notification}', NotificationRoute::class)->name('notifications');
        Route::post('temp-upload', [FileUpload::class, 'tempUploadHandler'])->middleware('signed')->name('temp-upload');
        Route::post('uploader', [FileUpload::class, 'uploadHandler'])->middleware('signed')->name('uploader');
        Route::get('select-options', [Select::class, 'serverSideOptionsHandler']);
        Route::post('repeater-items', [Repeater::class, 'repeaterHandler']);
        Route::post('module-action', RouteHandler::class);
        Route::post('handler', [Action::class, 'routeActionHandler']);

        // Pages route
        File::ensureDirectoryExists(app_path('Larascaff/Pages'));
        foreach (File::allFiles(app_path('Larascaff/Pages')) as $page) {
            /** @var Page */
            $page = 'App\\Larascaff\\Pages\\'.(str_replace([DIRECTORY_SEPARATOR, '.php'], ['\\', ''], $page->getRelativePathname()));
            $page::registerRoutes();
        }

        // Modules route
        File::ensureDirectoryExists(app_path('Larascaff/Modules'));
        foreach (File::allFiles(app_path('Larascaff/Modules')) as $module) {
            /** @var Module */
            $module = 'App\\Larascaff\\Modules\\'.(str_replace([DIRECTORY_SEPARATOR, '.php'], ['\\', ''], $module->getRelativePathname()));
            $module::registerRoutes();
        }
    });
    require __DIR__.'/auth.php';
});
