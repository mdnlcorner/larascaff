<?php

namespace Mulaidarinull\Larascaff\Modules;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Mulaidarinull\Larascaff\Enums\ModalSize;
use Mulaidarinull\Larascaff\Forms;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Tables;
use Yajra\DataTables\EloquentDataTable;

class BaseUserModule extends Module
{
    protected static ?string $model = User::class;

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

    public static function formBuilder(Forms\Components\Form $form): Forms\Components\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('email')
                ->validations([
                    'required',
                    Rule::unique('users')->ignore(getRecord()),
                ])
                ->prependIcon('tabler-mail'),
            Forms\Components\Section::make('Credentials')
                ->collapsible()
                ->description('Secure your account with strong password combination!')
                ->schema([
                    Forms\Components\TextInput::make('password')
                        ->validations(['confirmed', Rule::requiredIf(function () {
                            return request()->routeIs('users.store');
                        })])->password()->revealable(),
                    Forms\Components\TextInput::make('password_confirmation')->password()->revealable(),
                ]),
            Forms\Components\Radio::make('gender')
                ->validations(['required', 'in:Male,Female'])
                ->options(['Male' => 'Male', 'Female' => 'Female']),
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
        ];
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->modifyFormData(function (array $data, User $record) {
                        if (! $data['password']) {
                            $data['password'] = $record->password;
                        }

                        return $data;
                    }),
                Tables\Actions\Action::make('permissions')
                    ->label('Permission')
                    ->form(function (Forms\Components\Form $form) {
                        return $form->schema([
                            UserPermissionFormComponent::make()
                                ->shareData(function (User $user) {
                                    $menus = Menu::with('permissions', 'subMenus.permissions', 'subMenus.subMenus.permissions')->whereNull('main_menu_id')->get();
                                    $users = User::query()->where('id', '!=', $user->id)->get()->map(fn ($user) => ['label' => $user->name, 'value' => $user->id]);

                                    return [
                                        'data' => $user,
                                        'users' => $users,
                                        'menus' => $menus,
                                    ];
                                }),
                        ])
                            ->modalSize(ModalSize::Lg)
                            ->columns(1);
                    })
                    ->action(function (Request $request, User $user) {
                        Gate::authorize('update-permissions ' . static::getUrl());

                        $user->syncPermissions($request->permissions);

                        return responseSuccess();
                    })
                    ->permission('update-permissions')
                    ->icon('tabler-shield'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->customizeColumn(function (EloquentDataTable $eloquentDataTable) {
                $eloquentDataTable
                    ->editColumn('created_at', fn (User $user) => $user->created_at->format('d-m-Y H:i'))
                    ->editColumn('updated_at', fn (User $user) => $user->updated_at->format('d-m-Y H:i'));
            })
            ->columns(function (Tables\HtmlBuilder $builder) {
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
}
