<?php

namespace Mulaidarinull\Larascaff;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Mulaidarinull\Larascaff\Notifications\NotificationRoute;

class LarascaffHandler
{
    public function content(array $data = [], array $mergeData = [])
    {
        return view('larascaff::main-content', $data, $mergeData);
    }

    public function registerRoutes()
    {
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
                $class = getFileNamespace($page->getContents()).'\\'.$page->getFilenameWithoutExtension();

                if (method_exists($class, 'routes')) {
                    $routeName = explode('/', $class::getUrl());
                    $implodeRouteName = (implode('.', $routeName)).'.';

                    foreach ($class::routes() as $route) {
                        $url = $class::getUrl().(str_starts_with($route['url'], '/') ? $route['url'] : '/'.$route['url']);
                        $action = is_string($route['action']) ? [$class, $route['action']] : $route['action'];
                        Route::{$route['method'] ?? 'get'}($url, $action)->name($route['name'] ? $implodeRouteName.$route['name'] : null);
                    }
                }

                Route::get($class::getUrl(), [$class, 'index'])->name(implode('.', explode('/', $class::getUrl())));
            }

            // Modules route
            File::ensureDirectoryExists(app_path('Larascaff/Modules'));
            foreach (File::allFiles(app_path('Larascaff/Modules')) as $modules) {
                $class = getFileNamespace($modules->getContents()).'\\'.$modules->getFilenameWithoutExtension();
                
                $routeName = explode('/', $class::getUrl());
                if (method_exists($class, 'routes')) {
                    $implodeRouteName = (implode('.', $routeName)).'.';

                    foreach ($class::routes() as $route) {
                        $url = $class::getUrl().(str_starts_with($route['url'], '/') ? $route['url'] : '/'.$route['url']);
                        $action = is_string($route['action']) ? [$class, $route['action']] : $route['action'];
                        Route::{$route['method'] ?? 'get'}($url, $action)->name($route['name'] ? $implodeRouteName.$route['name'] : null);
                    }
                }

                array_pop($routeName);
                Route::name(implode('.', $routeName).(count($routeName) ? '.' : ''))->group(function () use ($class) {
                    Route::resource($class::getUrl(), $class);
                });
            }
        });
    }
}
