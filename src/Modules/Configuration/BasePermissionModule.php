<?php

namespace Mulaidarinull\Larascaff\Modules\Configuration;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Mulaidarinull\Larascaff\Forms;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Models\Configuration\Permission;
use Mulaidarinull\Larascaff\Modules\Module;
use Mulaidarinull\Larascaff\Tables;

class BasePermissionModule extends Module
{
    protected static ?string $model = Permission::class;

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->query(function (Builder $query) {
                $query->with('menu');
            })
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('menu.name')->name('menu.name')->title('Menu'),
            ]);
    }

    public static function formBuilder(Forms\Components\Form $form): Forms\Components\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->validations([
                    'required',
                    Rule::unique('permissions')->ignore(getRecord()),
                ]),
            Forms\Components\TextInput::make('guard_name')->validations(['required']),
            Forms\Components\Select::make('menu_id')
                ->label('Menu')
                ->searchable()
                ->validations(['required'])
                ->placeholder('Choose Menu')
                ->serverSide(Menu::class)
                ->relationship('menu', 'name'),
        ]);
    }
}
