<?php

namespace Mulaidarinull\Larascaff\Modules\Configuration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Mulaidarinull\Larascaff\Forms;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Models\Configuration\Role;
use Mulaidarinull\Larascaff\Modules\Module;
use Mulaidarinull\Larascaff\Tables;

class BaseRoleModule extends Module
{
    protected static ?string $model = Role::class;

    public static function formBuilder(Forms\Components\Form $form): Forms\Components\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name'),
            Forms\Components\TextInput::make('guard_name'),
        ]);
    }

    public function validationRules(): array
    {
        return [
            'name' => ['required', Rule::unique('roles')->ignore(static::getInstanceModel())],
            'guard_name' => 'required',
        ];
    }

    public static function routes(): array
    {
        return [
            static::makeRoute(url: '{role}/copy-permissions', action: 'getPermissionsByRole', name: 'copy-permissions.edit'),
            static::makeRoute(url: '{role}/permissions', action: 'editPermissions', name: 'permissions.edit'),
            static::makeRoute(url: '{role}/permissions', action: 'updatePermissions', method: 'put', name: 'permissions.update'),
        ];
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('permissions')
                    ->label('Permission')
                    ->path('{{id}}/permissions')
                    ->permission('update-permissions')
                    ->icon('tabler-shield'),
            ])
            ->columns(function (Tables\HtmlBuilder $builder) {
                $builder
                    ->columnsWithActions([
                        Tables\Column::make('name'),
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
            'action' => route($prefix . 'configuration.roles.permissions.update', $role->{$role->getRouteKeyName()}),
            'size' => 'lg',
        ]);
    }

    public function updatePermissions(Request $request, Role $role)
    {
        Gate::authorize('update-permissions ' . static::getUrl());

        $role->syncPermissions($request->permissions);

        return responseSuccess();
    }
}
