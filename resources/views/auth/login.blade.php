<x-larascaff-guest-layout>
    @php
        $config = app(\Mulaidarinull\Larascaff\LarascaffConfig::class);
    @endphp
    <a href="{{ url(getPrefix()) }}" class="mb-6 text-center">
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
    <div class="text-2xl font-semibold">Welcome Back</div>
    <p class="text-sm text-muted-foreground">Please sign-in to your account and start the adventure</p>
    <div class="py-4">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <x-larascaff::forms.input-group autofocus prependIcon="mail" label="Enter your email" name="email"
                    :error="$errors->first('email')" value="{{ old('email') }}" />
                <x-larascaff::forms.input-group type="password" prependIcon="lock" name="password" :error="$errors->first('password')"
                    label="Enter your password" />
                <div class="flex justify-between">
                    <x-larascaff::forms.checkbox name="remember" label="Remember me" />
                    <a href="{{ route('password.request') }}" class="flex justify-end w-full text-sm">Forget
                        password</a>
                </div>
            </div>
            <x-larascaff::button onclick="setTimeout(() => {
            this.disabled = true
            }, 0)"
                type="submit" class="w-full mt-4">Login</x-larascaff::button>
            <div class="mt-6 text-sm text-center">
                <span>Don't have account? </span> <a href="{{ route('register') }}"
                    class="underline text-primary">Create account</a>
            </div>
        </form>
    </div>
    <div class="relative flex items-center justify-center pt-4 pb-8">
        <div class="h-[1px] dark:bg-dark-800 bg-dark-200 w-full"></div>
        <div class="absolute px-4 text-sm text-center bg-white dark:bg-dark-900 text-muted-foreground">or</div>
    </div>
    <div class="flex justify-center mb-6 item-center">
        <x-larascaff::button class="w-full text-foreground dark:border-dark-700" variant="outline-dark">
            Login with @svg('tabler-brand-github', 'w-5 h-5')
        </x-larascaff::button>
    </div>
</x-larascaff-guest-layout>
