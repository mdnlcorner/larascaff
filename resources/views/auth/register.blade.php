<x-larascaff-guest-layout title="Register">
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
    <div class="text-2xl font-semibold">{{ __('larascaff::auth/register.title') }}</div>
    <p class="text-sm text-muted-foreground">{{ __('larascaff::auth/register.heading') }}</p>
    <div class="py-4">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <x-larascaff::forms.input-group :error="$errors->first('name')" :value="old('name')" name="name" prependIcon="user" label="{{ __('larascaff::auth/register.form.name.label') }}" />
                <x-larascaff::forms.input-group :error="$errors->first('email')" :value="old('email')" name="email" prependIcon="mail" label="{{ __('larascaff::auth/register.form.email.label') }}" />
                <x-larascaff::forms.input-group :error="$errors->first('password')" :value="old('password')" name="password" prependIcon="lock" type="password" label="{{ __('larascaff::auth/register.form.password.label') }}" />
                <x-larascaff::forms.input-group prependIcon="lock" type="password" name="password_confirmation" label="{{ __('larascaff::auth/register.form.password_confirmation.label') }}" />
                {{-- <div class="flex gap-2">
                    <x-larascaff::forms.checkbox :error="$errors->first('term')" :checked="old('term')" name="term" label="I Accept" />
                    <a href="#" class="flex justify-end w-full text-sm underline text-primary">Terms & condition</a>
                </div> --}}
            </div>
            <x-larascaff::button onclick="setTimeout(() => {
                this.disabled = true
                }, 0)" class="w-full mt-4">{{ __('larascaff::auth/register.form.actions.register.label') }}</x-larascaff::button>
        </form>
        <div class="mt-6 text-sm text-center">
            <span>{{ __('larascaff::auth/register.actions.login.question') }} </span> <a href="{{ route('login') }}" class="underline text-primary">
                {{ __('larascaff::auth/register.actions.login.label') }}
            </a>
        </div>
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
