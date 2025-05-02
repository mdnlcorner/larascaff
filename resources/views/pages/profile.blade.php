<div class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-8">
    <div class="self-start top-4 md:col-span-3 md:sticky">
        <div class="card">
            <form method="post" enctype="multipart/form-data" action="{{ route('profile.avatar') }}">
                <div class="flex flex-col items-center justify-center">
                    @csrf
                    @method('patch')
                    <div class="relative w-36 h-36">
                        {!! $avatarInput->view() !!}
                    </div>
                    <div class="relative font-semibold underline">{{ user('name') }}</div>
                    <div class="text-sm text-muted-foreground">{{ user()->roles->pluck('name')->implode(',') }}</div>
                    <x-larascaff::button type="submit">Update</x-larascaff::button>
                </div>
            </form>
        </div>
    </div>
    <div class="md:col-span-5">
        <div class="card">
            <form method="post" enctype="multipart/form-data" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')
                <div class="mb-6">
                    <div class="font-semibold">Personal information</div>
                    <div class="text-sm text-muted-foreground">Update your personal information</div>
                </div>
                <div class="grid grid-cols-1 gap-x-4 gap-y-6 md:grid-cols-2">
                    <x-larascaff::forms.input :error="$errors->first('name')" name="name" label="name" :value="old('name', user('name'))" />
                    <x-larascaff::forms.input :error="$errors->first('email')" name="email" label="Email" :value="old('email', user('email'))"
                        type="email" />
                </div>
                <div class="flex justify-end gap-4 mt-4">
                    <x-larascaff::button type="submit">Update</x-larascaff::button>
                </div>
            </form>
        </div>
        <div class="card">
            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')
                <div class="mb-6">
                    <div class="font-semibold">Update Password</div>
                    <div class="text-sm text-muted-foreground">Ensure your account is using a long, random password to
                        stay secure.</div>
                </div>
                <div class="grid grid-cols-1 gap-x-4 gap-y-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <x-larascaff::forms.input revealable :autofocus="$errors->updatePassword->first('current_password') ? true : false" :value="old('current_password')" :error="$errors->updatePassword->first('current_password')"
                            name="current_password" label="Current password" type="password" />
                    </div>
                    <x-larascaff::forms.input revealable :autofocus="$errors->updatePassword->first('password') ? true : false" value="" :error="$errors->updatePassword->first('password')"
                        name="password" label="Password" type="password" />
                    <x-larascaff::forms.input revealable name="password_confirmation" label="Confirm password"
                        type="password" />
                </div>
                <div class="flex justify-end gap-4 mt-4">
                    <x-larascaff::button type="submit">Update</x-larascaff::button>
                </div>
            </form>
        </div>
        <div class="card">
            <div class="mb-6">
                <div class="font-semibold">Delete Account</div>
                <div class="text-sm text-muted-foreground">Once your account is deleted, all of its resources and data
                    will be permanently deleted. Before deleting your account, please download any data or information
                    that you wish to retain.</div>
            </div>
            <form id="formDelete" action="{{ route('profile.destroy') }}" method="post">
                @csrf
                @method('delete')
                <x-larascaff::forms.input value="" :autofocus="$errors->userDeletion->first('password') ? true : false" :error="$errors->userDeletion->first('password')" name="password"
                    label="Your password" type="password" />
                <div class="flex justify-end gap-4 mt-4">
                    <x-larascaff::button type="submit" variant="danger">Delete</x-larascaff::button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.querySelector('#formDelete').addEventListener('submit', function(e) {
            e.preventDefault();
            confirmation(res => {
                this.submit()
            })
        })
    </script>
@endpush
