<x-larascaff-guest-layout title="Forgot password">
    <a href="/" class="py-6 text-center">LOGO</a>
    <div class="text-2xl font-semibold">Forgot your password?</div>
    <p class="pb-4 text-sm text-muted-foreground">Enter your email and we'll send you instructions to reset your
        password</p>
    @session('status')
        <div class="pb-4 text-sm text-success">{{ session('status') }}</div>
    @endsession
    <div class="pb-4">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <x-larascaff::forms.input-group :autofocus="true" :error="$errors->first('email')" name="email" prependIcon="mail"
                label="Enter your email" />
            <x-larascaff::button onclick="setTimeout(() => {
                    this.disabled = true
                    }, 0)" type="submit" class="w-full mt-6">Send reset link</x-larascaff::button>
        </form>
    </div>
</x-larascaff-guest-layout>
