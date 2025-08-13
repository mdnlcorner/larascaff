<?php

namespace Mulaidarinull\Larascaff\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse | View
    {
        $route = larascaffConfig()->getPrefix() ? larascaffConfig()->getPrefix() . '.dashboard' : '';

        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route($route, absolute: false))
                    : view('larascaff::auth.verify-email');
    }
}
