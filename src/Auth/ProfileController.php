<?php

namespace Mulaidarinull\Larascaff\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Pluralizer;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Mulaidarinull\Larascaff\Auth\Requests\ProfileUpdateRequest;
use Mulaidarinull\Larascaff\Forms\Components\FileUpload;

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

        $viewData = [
            'config' => larascaffConfig(),
            'hasAvatar' => user() instanceof \Mulaidarinull\Larascaff\Models\Contracts\HasAvatar,
        ];

        if ($viewData['hasAvatar']) {
            $viewData['avatarInput'] = FileUpload::make('avatar')
                ->name(user()->getAvatarField())
                ->allowImagePreview()
                ->linkPreview()
                ->avatar()
                ->disk(user()->getAvatarDisk())
                ->path(user()->getAvatarPath())
                ->imageResizeTargetHeight(200)
                ->imageResizeTargetWidth(200)
                ->imageCropAspectRatio('1:1')
                ->allowImageResize()
                ->imageEditor();
        }

        $view = view('larascaff::pages.profile', $viewData);

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
        user()->fill($request->validated());

        if (user()->isDirty('email')) {
            user()->email_verified_at = null;
        }

        user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateAvatar(Request $request)
    {
        user()->updateMedia(user()->getAvatarPath(), $request->{user()->getAvatarField()}, user()->getAvatarField(), user()->getAvatarDisk());

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

        Auth::logout();

        user()->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
