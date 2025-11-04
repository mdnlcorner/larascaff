<x-larascaff-guest-layout title="Forgot password">
    @php
        $config = larascaffConfig();
    @endphp
    <a href="{{ route('login') }}" class="mb-6 text-center">
        @if (is_callable($config->renderBrand()))
            {{ $config->renderBrand()() }}
        @else
            @if ($config->getBrandName())
                {{ $config->getBrandName() }}
            @else
                <img style="height: {{ $config->getBrandHeight() }}" src="{{ $config->renderBrand() }}" class="w-full" alt="brand-logo">
            @endif
        @endif
    </a>
    <div class="text-2xl font-semibold">{{ __('larascaff::auth/password-reset/request-password-reset.title') }}</div>
    <p class="pb-4 text-sm text-muted-foreground">
        {{ __('larascaff::auth/password-reset/request-password-reset.heading') }}
    </p>
    @session('status')
        <div class="pb-4 text-sm text-success">{{ session('status') }}</div>
    @endsession
    <div class="pb-4">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <x-larascaff::forms.input-group :autofocus="true" :error="$errors->first('email')" name="email" prependIcon="mail"
                label="{{ __('larascaff::auth/password-reset/request-password-reset.form.email.label') }}" />
            <x-larascaff::button onclick="setTimeout(() => {
                    this.disabled = true
                    }, 0)" type="submit" class="w-full mt-6">{{ __('larascaff::auth/password-reset/request-password-reset.form.actions.request.label') }}</x-larascaff::button>
        </form>
        <div class="flex justify-center w-full mt-4">
            <a href="{{ route('login') }}" class="text-sm underline text-primary">{{ __('larascaff::auth/password-reset/request-password-reset.actions.login.label') }}</a>
        </div>
    </div>
</x-larascaff-guest-layout>
