<?php

namespace Mulaidarinull\Larascaff\Modules\Configuration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Mulaidarinull\Larascaff\BaseModule;
use Mulaidarinull\Larascaff\Components\Forms\Form;
use Mulaidarinull\Larascaff\Components\Forms\TextInput;
use Mulaidarinull\Larascaff\DataTables\BaseDataTable;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Models\Configuration\Role;
use Mulaidarinull\Larascaff\Tables\Actions\Action;
use Mulaidarinull\Larascaff\Tables\Actions\DeleteAction;
use Mulaidarinull\Larascaff\Tables\Actions\EditAction;
use Mulaidarinull\Larascaff\Tables\Actions\ViewAction;
use Yajra\DataTables\Html\Column;

class BaseRoleModule extends BaseModule
{
    protected static ?string $model = Role::class;

    public static function formBuilder(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name'),
            TextInput::make('guard_name'),
        ]);
    }

    public function validationRules(): array
    {
        return [
            'name' => ['required', Rule::unique('roles')->ignore(static::getInstanceModel())],
            'guard_name' => 'required',
        ];
    }

    public static function routes()
    {
        return [
            static::makeRoute(url: '{role}/copy-permissions', action: 'getPermissionsByRole', name: 'copy-permissions.edit'),
            static::makeRoute(url: '{role}/permissions', action: 'editPermissions', name: 'permissions.edit'),
            static::makeRoute(url: '{role}/permissions', action: 'updatePermissions', method: 'put', name: 'permissions.update'),
        ];
    }

    public static function table(BaseDatatable $table): BaseDatatable
    {
        return $table
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                Action::make(
                    permission: 'update-permissions',
                    url: '/{{id}}/permissions',
                    label: 'Permissions',
                    icon: 'tabler-shield'
                ),
            ])
            ->columns(function (\Mulaidarinull\Larascaff\Datatable\HtmlBuilder $builder) {
                $builder
                    ->columnsWithActions([
                        Column::make('name'),
                    ]);
            });
    }

    public function getPermissionsByRole($id)
    {
        $role = Role::findOrNew($id);

        return view('larascaff::pages.role-permission-items', [
            'data' => $role,
            'menus' => Menu::active()->with(['subMenus' => function ($query) {
                $query->active()->orderBy('orders');
            }])->whereNull('main_menu_id')
                ->orderBy('orders')
                ->get(),
        ]);
    }

    public function editPermissions(Role $role)
    {
        $menus = Menu::with('permissions', 'subMenus.permissions', 'subMenus.subMenus.permissions')->whereNull('main_menu_id')->get();
        $roles = Role::query()->where('id', '!=', $role->id)->get()->map(fn ($role) => ['label' => $role->name, 'value' => $role->id]);
        $view = view('larascaff::pages.role-permission-form', [
            'data' => $role,
            'menus' => $menus,
            'roles' => $roles,
        ]);
        $prefix = getPrefix();
        if ($prefix) {
            $prefix .= '.';
        }

        return $this->form($view, [
            'method' => 'PUT',
            'title' => 'Permission Role',
            'action' => route($prefix.'configuration.roles.permissions.update', $role->{$role->getRouteKeyName()}),
            'size' => 'lg',
        ]);
    }

    public function updatePermissions(Request $request, Role $role)
    {
        Gate::authorize('update-permissions '.static::getUrl());

        $role->syncPermissions($request->permissions);

        return responseSuccess();
    }
}
