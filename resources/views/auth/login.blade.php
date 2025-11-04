<x-larascaff-guest-layout>
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
    <div class="text-2xl font-semibold">{{ __('larascaff::auth/login.title') }}</div>
    <p class="text-sm text-muted-foreground">{{ __('larascaff::auth/login.heading') }}</p>
    <div class="py-4">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <x-larascaff::forms.input-group autofocus prependIcon="mail" label="{{ __('larascaff::auth/login.form.email.label') }}" name="email"
                    :error="$errors->first('email')" value="{{ old('email') }}" />
                <x-larascaff::forms.input-group type="password" prependIcon="lock" name="password" :error="$errors->first('password')"
                    label="{{ __('larascaff::auth/login.form.password.label') }}" />
                <div class="flex justify-between">
                    <x-larascaff::forms.checkbox name="remember" label="{{ __('larascaff::auth/login.form.remember.label') }}" />
                    @if (larascaffConfig()->hasPasswordReset())
                    <a href="{{ route('password.request') }}" class="flex justify-end w-full text-sm underline text-primary">{{ __('larascaff::auth/login.actions.request_password_reset.label') }}</a>
                    @endif
                </div>
            </div>
            <x-larascaff::button onclick="setTimeout(() => {
            this.disabled = true
            }, 0)"
                type="submit" class="w-full mt-4">{{ __('larascaff::auth/login.form.actions.authenticate.label') }}</x-larascaff::button>
            @if (larascaffConfig()->hasRegistration())
            <div class="mt-6 text-sm text-center">
                <span>{{ __('larascaff::auth/login.actions.register.question') }} </span> <a href="{{ route('register') }}"
                    class="underline text-primary">{{ __('larascaff::auth/login.actions.register.label') }}</a>
            </div>
            @endif
        </form>
    </div>
    {{-- <div class="relative flex items-center justify-center pt-4 pb-8">
        <div class="h-[1px] dark:bg-dark-800 bg-dark-200 w-full"></div>
        <div class="absolute px-4 text-sm text-center bg-white dark:bg-dark-900 text-muted-foreground">or</div>
    </div>
    <div class="flex justify-center mb-6 item-center">
        <x-larascaff::button class="w-full text-foreground dark:border-dark-700" variant="outline-dark">
            Login with @svg('tabler-brand-github', 'w-5 h-5')
        </x-larascaff::button>
    </div> --}}
</x-larascaff-guest-layout>
