<?php

namespace Mulaidarinull\Larascaff\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Pluralizer;
use Illuminate\View\View;

final class ProfileController extends Controller
{
    protected string $url = 'profile';

    protected string $pageTitle = 'Profile';

    /**
     * Display the user's profile form.
     */
    public function edit(): View
    {
        setRecord(user());
        $view = view('larascaff::pages.profile', [
            'config' => larascaffConfig(),
            'hasAvatar' => user() instanceof \Mulaidarinull\Larascaff\Models\Contracts\HasAvatar,
            'avatarInput' => \Mulaidarinull\Larascaff\Forms\Components\Uploader::make('avatar')
                ->allowImagePreview(true)
                ->linkPreview()
                ->avatar()
                ->path(user()->getAvatarPath())
                ->imageResizeTargetHeight(100)
                ->imageResizeTargetWidth(100)
                ->imageEditor()
                ->label(''),
        ]);

        return view('larascaff::main-content', [
            'view' => $view,
            'pageTitle' => $this->pageTitle,
            'url' => Pluralizer::singular($this->url),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateAvatar(Request $request)
    {
        $request->user()->updateMedia('profile', $request->avatar, 'avatar');

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
