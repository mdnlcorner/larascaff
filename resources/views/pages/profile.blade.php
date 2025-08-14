<div class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-12">
    @if ($hasAvatar)
    <div class="self-start top-4 md:col-span-4 md:sticky">
        <section class="card">
            <form method="post" enctype="multipart/form-data" action="{{ route('profile.avatar') }}">
                <div class="flex flex-col items-center justify-center">
                    @csrf
                    @method('patch')
                    <div class="relative w-36 h-36">
                        {!! $avatarInput->view() !!}
                    </div>
                    <div class="relative font-semibold underline">{{ user('name') }}</div>
                    <div class="text-sm text-muted-foreground">{{ user()->roles->pluck('name')->implode(',') }}</div>
                    <x-larascaff::button type="submit">{{ __('larascaff::auth/edit-profile.form.actions.save.label') }}</x-larascaff::button>
                </div>
            </form>
        </section>
    </div>
    @endif
    <div class="{{ twMerge($hasAvatar ? 'md:col-span-8' : 'md:col-span-12') }}">
        <section class="card">
            <form method="post" enctype="multipart/form-data" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')
                <div class="mb-6">
                    <div class="font-semibold">{{ __('larascaff::auth/edit-profile.form.personal_information.title') }}</div>
                    <div class="text-sm text-muted-foreground">{{ __('larascaff::auth/edit-profile.form.personal_information.subtitle') }}</div>
                </div>
                <div class="grid grid-cols-1 gap-x-4 gap-y-6 md:grid-cols-2">
                    <x-larascaff::forms.input :error="$errors->first('name')" name="name" label="name" :value="old('name', user('name'))" />
                    <x-larascaff::forms.input :error="$errors->first('email')" name="email" label="Email" :value="old('email', user('email'))"
                        type="email" />
                </div>
                <div class="flex justify-end gap-4 mt-4">
                    <x-larascaff::button type="submit">{{ __('larascaff::auth/edit-profile.form.actions.save.label') }}</x-larascaff::button>
                </div>
            </form>
        </section>
        <section class="card">
            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')
                <div class="mb-6">
                    <div class="font-semibold">{{ __('larascaff::auth/edit-profile.form.update_password.title') }}</div>
                    <div class="text-sm text-muted-foreground">{{ __('larascaff::auth/edit-profile.form.update_password.subtitle') }}</div>
                </div>
                <div class="grid grid-cols-1 gap-x-4 gap-y-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <x-larascaff::forms.input revealable :autofocus="$errors->updatePassword->first('current_password') ? true : false" :value="old('current_password')" :error="$errors->updatePassword->first('current_password')"
                            name="current_password" label="{{ __('larascaff::auth/edit-profile.form.current_password.label') }}" type="password" />
                    </div>
                    <x-larascaff::forms.input revealable :autofocus="$errors->updatePassword->first('password') ? true : false" value="" :error="$errors->updatePassword->first('password')"
                        name="password" label="{{ __('larascaff::auth/edit-profile.form.password.label') }}" type="password" />
                    <x-larascaff::forms.input revealable name="password_confirmation" label="{{ __('larascaff::auth/edit-profile.form.password_confirmation.label') }}"
                        type="password" />
                </div>
                <div class="flex justify-end gap-4 mt-4">
                    <x-larascaff::button type="submit">{{ __('larascaff::auth/edit-profile.form.actions.save.label') }}</x-larascaff::button>
                </div>
            </form>
        </section>

        @if ($config->hasDeleteProfile())
            <section class="card">
                <div class="mb-6">
                    <div class="font-semibold">{{ __('larascaff::auth/edit-profile.form.delete.title') }}</div>
                    <div class="text-sm text-muted-foreground">{{ __('larascaff::auth/edit-profile.form.delete.subtitle') }}</div>
                </div>
                <form id="formDelete" action="{{ route('profile.destroy') }}" method="post">
                    @csrf
                    @method('delete')
                    <x-larascaff::forms.input value="" :autofocus="$errors->userDeletion->first('password') ? true : false" :error="$errors->userDeletion->first('password')" name="password"
                        label="{{ __('larascaff::auth/edit-profile.form.current_password.label') }}" type="password" />
                    <div class="flex justify-end gap-4 mt-4">
                        <x-larascaff::button type="submit" variant="danger">{{ __('larascaff::auth/edit-profile.form.actions.delete.label') }}</x-larascaff::button>
                    </div>
                </form>
            </section>
        @endif
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
