<?php

use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix(larascaffConfig()->getPrefix())->group(function () {
    Route::middleware('guest')->group(function () {
        if (larascaffConfig()->hasLogin()) {
            Route::get(larascaffConfig()->getLoginUrl(), larascaffConfig()->getLoginForm())->name('login');
            Route::post(larascaffConfig()->getLoginUrl(), larascaffConfig()->getLoginAction());
        }
        if (larascaffConfig()->hasRegistration()) {
            Route::get(larascaffConfig()->getRegistrationUrl(), larascaffConfig()->getRegistrationForm())->name('register');
            Route::post(larascaffConfig()->getRegistrationUrl(), LarascaffConfig()->getRegistrationAction());
        }
        if (larascaffConfig()->hasPasswordReset()) {
            Route::get(larascaffConfig()->getPasswordResetUrl(), larascaffConfig()->getPasswordResetForm())->name('password.request');
            Route::post(larascaffConfig()->getPasswordResetUrl(), larascaffConfig()->getPasswordResetAction())->name('password.email');
            Route::get(larascaffConfig()->getNewPasswordUrl() . '/{token}', larascaffConfig()->getNewPasswordForm())->name('password.reset');
            Route::post(larascaffConfig()->getNewPasswordUrl(), larascaffConfig()->getNewPasswordAction())->name('password.store');
        }
    });

    Route::middleware(larascaffConfig()->getAuthMiddleware())->group(function () {
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('profile-avatar', [ProfileController::class, 'avatar'])->name('profile.avatar');
        Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        if (larascaffConfig()->hasEmailVerification()) {
            Route::get(larascaffConfig()->getEmailVerificationPromptUrl(), larascaffConfig()->getEmailVerificationPromptForm())->name('verification.notice');
            Route::get(larascaffConfig()->getEmailVerificationUrl(), larascaffConfig()->getEmailVerificationAction())->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
            Route::post(larascaffConfig()->getEmailVerificationNotificationUrl(), larascaffConfig()->getEmailVerificationNotificationAction())->middleware('throttle:6,1')->name('verification.send');
        }
        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');
        Route::post(larascaffConfig()->getLogoutUrl(), larascaffConfig()->getLogoutAction())->name('logout');
    });
});
