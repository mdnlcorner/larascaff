<?php

namespace Mulaidarinull\Larascaff\Modules;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Mulaidarinull\Larascaff\Components\Layouts\Section;
use Mulaidarinull\Larascaff\Forms;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Tables;
use Yajra\DataTables\EloquentDataTable;

class BaseUserModule extends Module
{
    protected static ?string $model = User::class;

    public function validationRules()
    {
        return [
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore(static::getInstanceModel())],
            'password' => ['confirmed', Rule::requiredIf(function () {
                return request()->routeIs('users.store');
            })],
            'roles' => 'nullable',
            'gender' => ['required', 'in:Male,Female'],
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
        $users = User::query()->where('id', '!=', $user->id)->get()->map(fn ($user) => ['label' => $user->name, 'value' => $user->id]);
        $view = view('larascaff::pages.user-permission-form', [
            'data' => $user,
            'menus' => $menus,
            'users' => $users,
        ]);
        $prefix = getPrefix();
        if ($prefix) {
            $prefix .= '.';
        }

        return $this->form($view, [
            'method' => 'PUT',
            'title' => 'Permission User',
            'action' => route($prefix . 'users.permissions.update', $user->{$user->getRouteKeyName()}),
            'size' => 'lg',
        ]);
    }

    public function updatePermissions(Request $request, User $user)
    {
        Gate::authorize('update-permissions ' . static::getUrl());

        $user->syncPermissions($request->permissions);

        return responseSuccess();
    }

    public static function formBuilder(Forms\Components\Form $form): Forms\Components\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name'),
            Forms\Components\TextInput::make('email')->prependIcon('tabler-mail'),
            Section::make('Credentials')
                ->collapsible()
                ->description('Secure your account with strong password combination!')
                ->schema([
                    Forms\Components\TextInput::make('password')->password()->revealable(),
                    Forms\Components\TextInput::make('password_confirmation')->password()->revealable(),
                ]),
            Forms\Components\Radio::make('gender')->options(['Male' => 'Male', 'Female' => 'Female']),
            Forms\Components\Select::make('roles')
                ->label('Roles')
                ->searchable()
                ->placeholder('Choose Roles')
                ->multiple()
                ->relationship('roles', 'name')
                ->columnLabel('name')
                ->columnValue('id'),
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
                ],
            ],
            [
                'type' => 'nullable',
                'name' => 'email_verified_at',
                'label' => 'Is Verified',
            ],
        ];
    }

    public static function routes(): array
    {
        return [
            static::makeRoute(url: '{user}/copy-permissions', action: 'getPermissionsByUser', name: 'copy-permissions.edit'),
            static::makeRoute(url: '{user}/permissions', action: 'editPermissions', name: 'permissions.edit'),
            static::makeRoute(url: '{user}/permissions', action: 'updatePermissions', method: 'put', name: 'permissions.update'),
        ];
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make(
                    permission: 'update-permissions',
                    url: '/{{id}}/permissions',
                    label: 'Permissions',
                    icon: 'tabler-shield'
                ),
            ])
            ->customizeColumn(function (EloquentDataTable $eloquentDataTable) {
                $eloquentDataTable
                    ->editColumn('created_at', fn (User $user) => $user->created_at->format('d-m-Y H:i'))
                    ->editColumn('updated_at', fn (User $user) => $user->updated_at->format('d-m-Y H:i'));
            })
            ->columns(function (\Mulaidarinull\Larascaff\DataTables\HtmlBuilder $builder) {
                $builder
                    ->columnsWithActions([
                        Tables\Column::make('name'),
                        Tables\Column::make('email'),
                        Tables\Column::make('gender'),
                        Tables\Column::make('created_at'),
                        Tables\Column::make('updated_at'),
                    ]);
            });
    }

    public static function beforeStore(Request $request, User $user)
    {
        $request->merge([
            'password' => bcrypt($request->password),
        ]);
    }

    public static function beforeUpdate(Request $request, User $user)
    {
        if (! $request->password) {
            $request->merge(['password' => $user->password]);
        } else {
            $request->merge(['password' => bcrypt($request->password)]);
        }
    }
}
