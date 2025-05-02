@foreach ($menus as $mm)
    <tr>
        <td><x-larascaff::forms.checkbox id="parent{{ $mm->id }}" label="{{ $mm->name }}" class="parent" /></td>
        <td>
            <div class="flex items-center gap-2">
                @foreach ($mm->permissions as $permission)
                    <x-larascaff::forms.checkbox label="{{ explode(' ',$permission->name)[0] }}" :checked="$data->hasDirectPermission($permission->name)" class="child"  name="permissions[]" value="{{ $permission->name }}" id="permission-{{ $mm->id.'-'.$permission->id }}" />
                @endforeach
            </div>
        </td>
    </tr>
    @foreach ($mm->subMenus as $sm)
        <tr>
            <td class="inline-flex">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <x-larascaff::forms.checkbox id="parent{{ $mm->id.$sm->id }}" label="{{ $sm->name }}" class="parent" /></td>
            <td>
                <div class="flex items-center gap-2">
                    @foreach ($sm->permissions as $permission)
                        <x-larascaff::forms.checkbox label="{{ explode(' ',$permission->name)[0] }}" :checked="$data->hasDirectPermission($permission->name)" class="child" name="permissions[]"  type="checkbox" value="{{ $permission->name }}" id="permission-{{ $sm->id.'-'.$permission->id }}" />                    
                        @foreach ($sm->subMenus as $ssm)
                            <tr>
                                <td class="inline-flex">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <x-larascaff::forms.checkbox id="parent2{{ $mm->id.$sm->id.$ssm->id }}" label="{{ $ssm->name }}" class="parent" /></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                    @foreach ($ssm->permissions as $smp)
                                        <x-larascaff::forms.checkbox label="{{ explode(' ',$smp->name)[0] }}" :checked="$data->hasDirectPermission($smp->name)" class="child"  name="permissions[]" value="{{ $smp->name }}" id="permission-{{ $sm->id.'-'.$smp->id }}" />
                                    @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </div>
            </td>
        </tr>
    @endforeach
@endforeach