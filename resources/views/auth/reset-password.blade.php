<x-larascaff-guest-layout title="Forgot password">
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="grid grid-cols-1 gap-6">
            <x-larascaff::forms.input label="Email" :error="$errors->first('email')" id="email" class="block w-full mt-1" type="email"
                name="email" :value="old('email', $request->email)" autofocus autocomplete="username" />
            <x-larascaff::forms.input :error="$errors->first('password')" label="Password" id="password" class="block w-full mt-1"
                type="password" name="password" autocomplete="new-password" />
            <x-larascaff::forms.input label="Password Confirmation" id="password_confirmation" class="block w-full mt-1"
                type="password" :error="$errors->first('password_conrifmation')" name="password_confirmation" autocomplete="new-password" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-larascaff::button onclick="setTimeout(() => {
                this.disabled = true
                }, 0)" type="submit">
                {{ __('Reset Password') }}
            </x-larascaff::button>
        </div>
    </form>
</x-larascaff-guest-layout>
