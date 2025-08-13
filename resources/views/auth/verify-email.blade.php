<x-larascaff-guest-layout>
    <div class="mb-4 text-sm leading-relaxed">
        {{ __('larascaff::auth/email-verification.title') }}
    </div> 
    @if (session('status') == 'verification-link-sent')
    <div class="mb-4 text-sm font-medium text-green-600" >
        {{ __('larascaff::auth/email-verification.messages.notification_sent', ['email' => user('email')]) }}
    </div> 
    @endif

    <div class="flex items-center justify-between mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-larascaff::button onclick="setTimeout(() => {
                this.disabled = true
                }, 0)" type="submit">
                {{ __('larascaff::auth/email-verification.actions.resend_notification.label') }}
            </x-larascaff::button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="text-sm underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 ">
                {{ __('larascaff::auth/email-verification.actions.logout.label') }}
            </button>
        </form>
    </div>
</x-larascaff-guest-layout>
