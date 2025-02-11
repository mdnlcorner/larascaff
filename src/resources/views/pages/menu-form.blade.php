<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <x-larascaff::forms.input name="name" label="Name" />
    <x-larascaff::forms.input name="url" label="Url" />
    <x-larascaff::forms.input name="icon" label="Icon" />
    <x-larascaff::forms.input name="category" label="Category" />
    <x-larascaff::forms.input name="orders" label="Orders" />
    
    <x-larascaff::forms.select value="{{ getRecord('main_menu_id') }}" searchable="true" :options="$mainMenus" name="main_menu_id" label="Main Menu" placeholder="Choose Main Menu" />
    <div class="mb-3 md:col-span-2">
        <label for="" class="text-xs">Permissions</label>
        <div class="flex items-center gap-3">        
            @foreach (['create', 'read', 'update', 'delete'] as $item)
                <x-larascaff::forms.checkbox id="{{ $item }}_permissions" value="{{ $item }}" label="{{ $item }}" name="permissions[]" :checked="$permissions->contains($item)" />
            @endforeach
        </div>
    </div>
    @if (getRecord('id'))
    <x-larascaff::forms.radio-group name="active" :value="getRecord('active')" label="Active" :options="['Active' => '1', 'Non Active' =>'0']" />
    @endif
</div>
