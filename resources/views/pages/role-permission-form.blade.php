<h5 class="mb-6">Role: {{ $data->name }}</h5>
<div class="grid grid-cols-1 gap-x-6 gap-y-4">
    <x-larascaff::forms.select name="copy_permission" class="copy" label="Copy permissions" data-url="{{ url($form->getModule()::getUrl()) }}" placeholder="Choose role"
    :options="$roles"
    searchable="true"
    />
    <x-larascaff::forms.input name="search" class="search" label="Search Menu" placeholder="Search..." />
</div>
<div class="overflow-x-auto">
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="menu_permissions">
            @include('larascaff::pages.role-permission-items')
        </tbody>
    </table>
</div>