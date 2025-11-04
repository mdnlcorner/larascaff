<?php

use Illuminate\Support\Facades\Route;
use Mulaidarinull\Larascaff\Auth\ConfirmablePasswordController;

$config = larascaffConfig();
Route::prefix($config->getPrefix())->group(function () use ($config) {
    Route::middleware('guest')->group(function () use ($config) {
        if ($config->hasLogin()) {
            Route::get($config->getLoginUrl(), $config->getLoginForm())->name('login');
            Route::post($config->getLoginUrl(), $config->getLoginAction());
        }
        if ($config->hasRegistration()) {
            Route::get($config->getRegistrationUrl(), $config->getRegistrationForm())->name('register');
            Route::post($config->getRegistrationUrl(), $config->getRegistrationAction());
        }
        if ($config->hasPasswordReset()) {
            Route::get($config->getPasswordResetUrl(), $config->getPasswordResetForm())->name('password.request');
            Route::post($config->getPasswordResetUrl(), $config->getPasswordResetAction())->name('password.email');
            Route::get($config->getNewPasswordUrl() . '/{token}', $config->getNewPasswordForm())->name('password.reset');
            Route::post($config->getNewPasswordUrl(), $config->getNewPasswordAction())->name('password.store');
        }
    });

    Route::middleware($config->getAuthMiddleware())->group(function () use ($config) {
        if ($config->hasProfile()) {
            Route::get($config->getProfileUrl(), $config->getProfileForm())->name('profile.edit');
            Route::patch($config->getProfileUrl(), $config->getProfileAction())->name('profile.update');
            if ($config->hasDeleteProfile()) {
                Route::delete($config->getProfileUrl(), $config->getProfileDeleteAction())->name('profile.destroy');
            }
            Route::patch($config->getUpdateAvatarUrl(), $config->getUpdateAvatarAction())->name('profile.avatar');
        }
        Route::put($config->getUpdatePasswordUrl(), $config->getUpdatePasswordAction())->name('password.update');

        if ($config->hasEmailVerification()) {
            Route::get($config->getEmailVerificationPromptUrl(), $config->getEmailVerificationPromptForm())->name('verification.notice');
            Route::get($config->getEmailVerificationUrl(), $config->getEmailVerificationAction())->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
            Route::post($config->getEmailVerificationNotificationUrl(), $config->getEmailVerificationNotificationAction())->middleware('throttle:6,1')->name('verification.send');
        }
        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
        Route::post($config->getLogoutUrl(), $config->getLogoutAction())->name('logout');
    });
});
