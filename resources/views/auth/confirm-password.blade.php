<x-larascaff-guest-layout>
    <div class="mb-4 text-sm">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <x-larascaff::forms.input id="password" class="block w-full mt-1" type="password" name="password" :error="$errors->first('password')"
            autocomplete="current-password" />
        <div class="flex justify-end mt-6">
            <x-larascaff::button onclick="setTimeout(() => {
                this.disabled = true
                }, 0)" type="submit">
                {{ __('Confirm') }}
            </x-larascaff::button>
        </div>
    </form>
</x-larascaff-guest-layout>
