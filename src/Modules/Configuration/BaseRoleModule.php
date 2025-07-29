<?php

namespace Mulaidarinull\Larascaff\Modules\Configuration;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mulaidarinull\Larascaff\Enums\ColorVariant;
use Mulaidarinull\Larascaff\Enums\ModalSize;
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
            Forms\Components\TextInput::make('name')
                ->validations(['required', Rule::unique('roles')->ignore(getRecord())]),
            Forms\Components\TextInput::make('guard_name')
                ->validations(['required']),
        ]);
    }

    public static function routes(): array
    {
        return [
            static::makeRoute(url: '{role}/copy-permissions', action: 'getPermissionsByRole', name: 'copy-permissions.edit'),
        ];
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ReplicateAction::make()
                    ->color(ColorVariant::Info)
                    ->form(function (Forms\Components\Form $form) {
                        return $form->schema([
                            Forms\Components\TextInput::make('name')
                                ->validations(['required', Rule::unique('roles')]),
                            Forms\Components\TextInput::make('guard_name')
                                ->validations(['required']),
                        ]);
                    })
                    ->afterSave(function ($record, Role $replica) {
                        $replica->permissions()->attach($record->permissions->pluck('id'));
                    }),
                Tables\Actions\Action::make('permissions')
                    ->label('Permission')
                    ->form(function (Forms\Components\Form $form) {
                        return $form->schema([
                            RolePermissionFormComponent::make()
                                ->shareData(function (Role $role) {
                                    $menus = Menu::with('permissions', 'subMenus.permissions', 'subMenus.subMenus.permissions')->whereNull('main_menu_id')->get();
                                    $roles = Role::query()->where('id', '!=', $role->id)->get()->map(fn ($role) => ['label' => $role->name, 'value' => $role->id]);

                                    return [
                                        'data' => $role,
                                        'roles' => $roles,
                                        'menus' => $menus,
                                    ];
                                }),
                        ])
                            ->modalSize(ModalSize::Lg)
                            ->columns(1);
                    })
                    ->action(function (Request $request, Role $role) {
                        $role->syncPermissions($request->permissions);

                        return responseSuccess();
                    })
                    ->permission('update-permissions')
                    ->icon('tabler-shield'),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ]);
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
}
