<h5 class="mb-6">User: {{ $data->name }}</h5>
<div class="grid gap-x-6 gap-y-4">
    <x-larascaff::forms.select name="copy_permission" data-url="{{ url($form->getModule()::getUrl()) }}" searchable="true" :options="$users" class="copy" label="Copy Permissions"
        placeholder="Choose user" />
    <x-larascaff::forms.input name="search" class="search" label="Search Menu" placeholder="Search..." />
</div>
<div class="overflow-x-auto">
    <table class="table mt-6">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="menu_permissions">
            @include('larascaff::pages.user-permission-items')
        </tbody>
    </table>
</div>
