<?php

namespace App\Larascaff\Modules;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Mulaidarinull\Larascaff\BaseModule;
use Mulaidarinull\Larascaff\Components\Forms\{Form, TextInput, Radio, Select};
use Mulaidarinull\Larascaff\Components\Layouts\Section;
use Mulaidarinull\Larascaff\Datatable\BaseDatatable;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class UserModule extends BaseModule
{
    protected $model = User::class;
    protected string $modalSize = 'md';

    public function __construct()
    {
        parent::__construct();
        $this->tableActions(permission: 'update-permissions', action: url($this->url . '/{{id}}/permissions'), label: 'Permissions', icon: 'tabler-shield');
    }

    public function validationRules()
    {
        return [
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->model)],
            'password' => ['confirmed', Rule::requiredIf(function () {
                return request()->routeIs('users.store');
            })],
            'roles' => 'nullable',
            'gender' => ['required', 'in:Male,Female']
        ];
    }

    public function getPermissionsByUser(User $user)
    {
        return view('larascaff::pages.user-permission-items', [
            'data' => $user,
            'menus' => Menu::active()->with(['subMenus' => function ($query) {
                $query->active()->orderBy('orders');
            }])->whereNull('main_menu_id')
                ->orderBy('orders')
                ->get(),
        ]);
    }

    public function editPermissions(User $user)
    {
        $menus = Menu::with('permissions', 'subMenus.permissions', 'subMenus.subMenus.permissions')->whereNull('main_menu_id')->get();
        $users = User::query()->where('id', '!=', $user->id)->get()->map(fn($user) => ['label' => $user->name, 'value' => $user->id]);
        $view = view('larascaff::pages.user-permission-form', [
            'data' => $user,
            'menus' => $menus,
            'users' => $users,
        ]);
        $prefix = getPrefix();
        if ($prefix) $prefix .= '.';
        return $this->form($view, [
            'method' => 'PUT',
            'title' => 'Permission User',
            'action' => route($prefix . 'users.permissions.update', $user->{$user->getRouteKeyName()}),
            'size' => 'lg'
        ]);
    }

    public function updatePermissions(Request $request, User $user)
    {
        Gate::authorize('update-permissions ' . $this->url);

        $user->syncPermissions($request->permissions);
        return responseSuccess();
    }

    public function formBuilder(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name'),
            TextInput::make('email')->prependIcon('tabler-mail'),
            Section::make('Credentials')
                ->collapsible()
                ->description('Secure your account with strong password combination!')
                ->schema([
                    TextInput::make('password')->password()->revealable(),
                    TextInput::make('password_confirmation')->password()->revealable(),
                ]),
            Radio::make('gender')->options(['Male' => 'Male', 'Female' => 'Female']),
            Select::make('roles')
                ->label('Roles')
                ->searchable()
                ->placeholder('Choose Roles')
                ->multiple()
                ->relationship('roles', 'name')
                ->columnLabel('name')
                ->columnValue('id')
        ]);
    }

    public function filterTable()
    {
        return [
            [
                'type' => 'select',
                'name' => 'gender',
                'options' => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                ]
            ],
            [
                'type' => 'nullable',
                'name' => 'email_verified_at',
                'label' => 'Is Verified'
            ]
        ];
    }

    public function routes()
    {
        return [
            $this->makeRoute(url: '{user}/copy-permissions', action: 'getPermissionsByUser', name: 'copy-permissions.edit'),
            $this->makeRoute(url: '{user}/permissions', action: 'editPermissions', name: 'permissions.edit'),
            $this->makeRoute(url: '{user}/permissions', action: 'updatePermissions', method: 'put', name: 'permissions.update'),
        ];
    }

    public function table(BaseDatatable $table)
    {
        $table
            ->customizeColumn(function (EloquentDataTable $eloquentDataTable) {
                $eloquentDataTable
                    ->editColumn('created_at', fn(User $user) => $user->created_at->format('d-m-Y H:i'))
                    ->editColumn('updated_at', fn(User $user) => $user->updated_at->format('d-m-Y H:i'))
                ;
            })
            ->columns(function (\Mulaidarinull\Larascaff\Datatable\HtmlBuilder $builder) {
                $builder
                    ->columnsWithActions([
                        Column::make('name'),
                        Column::make('email'),
                        Column::make('gender'),
                        Column::make('created_at'),
                        Column::make('updated_at'),
                    ]);
            });
    }

    public function beforeStore(Request $request, User $user)
    {
        $request->merge([
            'password' => bcrypt($request->password)
        ]);
    }

    public function beforeUpdate(Request $request, User $user)
    {
        if (!$request->password) {
            $request->merge(['password' => $user->password]);
        } else {
            $request->merge(['password' => bcrypt($request->password)]);
        }
    }
}
