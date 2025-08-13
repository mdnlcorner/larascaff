<?php

namespace Mulaidarinull\Larascaff\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

final class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $route = larascaffConfig()->getPrefix() ? larascaffConfig()->getPrefix() . '.dashboard' : '';
        if (user()->hasVerifiedEmail()) {
            return redirect()->intended(route($route, absolute: false) . '?verified=1');
        }

        if (user()->markEmailAsVerified()) {
            /** @var \Illuminate\Contracts\Auth\MustVerifyEmail $user */
            $user = user();

            event(new Verified($user));
        }

        return redirect()->intended(route($route, absolute: false) . '?verified=1');
    }
}
