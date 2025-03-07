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
                $namespace = getFileNamespace($page->getContents()).'\\'.$page->getFilenameWithoutExtension();
                $page = new $namespace;
                $routeName = explode('/', $page->getUrl());

                if (method_exists($page, 'routes')) {
                    $implodeRouteName = (implode('.', $routeName)).'.';

                    foreach ($page->routes() as $route) {
                        $url = $page->getUrl().(str_starts_with($route['url'], '/') ? $route['url'] : '/'.$route['url']);
                        $action = is_string($route['action']) ? [$namespace, $route['action']] : $route['action'];
                        Route::{$route['method'] ?? 'get'}($url, $action)->name($route['name'] ? $implodeRouteName.$route['name'] : null);
                    }
                }

                Route::get($page->getUrl(), [$namespace, 'index'])->name(implode('.', $routeName));
            }

            // Modules route
            File::ensureDirectoryExists(app_path('Larascaff/Modules'));
            foreach (File::allFiles(app_path('Larascaff/Modules')) as $modules) {
                $namespace = getFileNamespace($modules->getContents()).'\\'.$modules->getFilenameWithoutExtension();
                $module = new $namespace;
                $routeName = explode('/', $module->getUrl());

                if (method_exists($module, 'routes')) {
                    $implodeRouteName = (implode('.', $routeName)).'.';

                    foreach ($module->routes() as $route) {
                        $url = $module->getUrl().(str_starts_with($route['url'], '/') ? $route['url'] : '/'.$route['url']);
                        $action = is_string($route['action']) ? [$namespace, $route['action']] : $route['action'];
                        Route::{$route['method'] ?? 'get'}($url, $action)->name($route['name'] ? $implodeRouteName.$route['name'] : null);
                    }
                }

                array_pop($routeName);
                Route::name(implode('.', $routeName).(count($routeName) ? '.' : ''))->group(function () use ($module, $namespace) {
                    Route::resource($module->getUrl(), $namespace);
                });
            }
        });
    }
}
