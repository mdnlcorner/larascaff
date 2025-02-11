<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div class="p-6 card">
        <div class="flex items-center justify-between">
            <div>
                <h5>Welcome </h5>
                <p class="mt-1 text-muted-foreground">{{ user('name') }}</p>
            </div>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <x-larascaff::button type='submit' variant="primary">@svg('tabler-logout', 'w-5 h-5') Logout</x-larascaff::button>
            </form>
        </div>
    </div>
</div>