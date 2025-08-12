<x-larascaff-guest-layout title="Forgot password">
    <div class="text-2xl font-semibold">{{ __('larascaff::auth/password-reset/reset-password.title') }}</div>
    <p class="pb-4 text-sm text-muted-foreground">
        {{ __('larascaff::auth/password-reset/reset-password.heading') }}
    </p>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="grid grid-cols-1 gap-6">
            <x-larascaff::forms.input-group :error="$errors->first('email')" :value="old('email', request('email'))" name="email" prependIcon="mail" label="{{ __('larascaff::auth/password-reset/reset-password.form.email.label') }}" />
            <x-larascaff::forms.input-group :error="$errors->first('password')" :value="old('password')" name="password" prependIcon="lock" type="password" label="{{ __('larascaff::auth/password-reset/reset-password.form.password.label') }}" />
            <x-larascaff::forms.input-group prependIcon="lock" type="password" name="password_confirmation" label="{{ __('larascaff::auth/password-reset/reset-password.form.password_confirmation.label') }}" />
        </div>
        <x-larascaff::button onclick="setTimeout(() => {
            this.disabled = true
            }, 0)" type="submit"
            class="w-full mt-4"
            >
            {{ __('larascaff::auth/password-reset/reset-password.form.actions.reset.label') }}
        </x-larascaff::button>
    </form>
    <div class="flex justify-center w-full mt-4">
        <a href="{{ route('login') }}" class="text-sm underline text-primary">{{ __('larascaff::auth/password-reset/reset-password.actions.login.label') }}</a>
    </div>
</x-larascaff-guest-layout>
