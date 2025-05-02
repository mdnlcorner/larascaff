<h5 class="mb-6">User: {{ $data->name }}</h5>
<div class="grid gap-x-6 gap-y-4">
    <x-larascaff::forms.select searchable="true" :options="$users" class="copy" label="Copy permissions"
        placeholder="Choose user" />
    <x-larascaff::forms.input name="search" class="search" label="Cari menu" placeholder="Cari.." />
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
