<?php

namespace Mulaidarinull\Larascaff\Modules\Configuration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Mulaidarinull\Larascaff\BaseModule;
use Mulaidarinull\Larascaff\Components\Forms\Form;
use Mulaidarinull\Larascaff\Components\Forms\TextInput;
use Mulaidarinull\Larascaff\Datatable\BaseDatatable;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Models\Configuration\Role;
use Yajra\DataTables\Html\Column;

class BaseRoleModule extends BaseModule
{
    protected $model = Role::class;

    public function __construct()
    {
        parent::__construct();
        $this->tableActions(permission: 'update-permissions', action: url($this->url.'/{{id}}/permissions'), label: 'Permissions', icon: 'tabler-shield');
    }

    public function formBuilder(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name'),
            TextInput::make('guard_name'),
        ]);
    }

    public function validationRules(): array
    {
        return [
            'name' => ['required', Rule::unique('roles')->ignore($this->model)],
            'guard_name' => 'required',
        ];
    }

    public function routes()
    {
        return [
            $this->makeRoute(url: '{role}/copy-permissions', action: 'getPermissionsByRole', name: 'copy-permissions.edit'),
            $this->makeRoute(url: '{role}/permissions', action: 'editPermissions', name: 'permissions.edit'),
            $this->makeRoute(url: '{role}/permissions', action: 'updatePermissions', method: 'put', name: 'permissions.update'),
        ];
    }

    public function table(BaseDatatable $table)
    {
        $table
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
        Gate::authorize('update-permissions '.$this->url);

        $role->syncPermissions($request->permissions);

        return responseSuccess();
    }
}
