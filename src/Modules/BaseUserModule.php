<?php

namespace Mulaidarinull\Larascaff\Modules;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Mulaidarinull\Larascaff\Enums\ModalSize;
use Mulaidarinull\Larascaff\Forms;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Tables;

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
            Forms\Components\TextInput::make('name')->validations(['required']),
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

    public static function routes(): array
    {
        return [
            static::makeRoute(url: '{user}/copy-permissions', action: 'getPermissionsByUser', name: 'copy-permissions.edit'),
        ];
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->filters([
                Tables\Filters\Filter::make('email_verified_at')
                    ->label('Is Verified')
                    ->query(function (Builder $query, array $data) {
                        if ($data['email_verified_at'] == '1') {
                            $query->whereNotNull('email_verified_at');
                        } elseif ($data['email_verified_at'] == '0') {
                            $query->whereNull('email_verified_at');
                        }
                    }),
                Tables\Filters\SelectFilter::make('gender')
                    ->options(['Choose Gender' => '', 'Male' => 'male', 'Female' => 'female'])
                    ->query(function (Builder $query, array $data) {
                        $query->where('gender', $data['gender']);
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->editFormData(function (array $data, User $record) {
                        if (!$data['password']) {
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
                            ->columns(1);
                    })
                    ->modalSize(ModalSize::Lg)
                    ->action(function (Request $request, User $user) {
                        Gate::authorize('update-permissions ' . static::getUrl());

                        $user->syncPermissions($request->permissions);

                        return responseSuccess();
                    })
                    ->permission('update-permissions')
                    ->icon('tabler-shield'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\DateColumn::make('created_at')->format('d-m-Y H:i'),
                Tables\Columns\DateColumn::make('updated_at')->format('d-m-Y H:i'),
            ]);
    }
}
