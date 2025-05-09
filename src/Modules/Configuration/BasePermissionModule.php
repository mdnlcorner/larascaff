<?php

namespace Mulaidarinull\Larascaff\Modules\Configuration;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mulaidarinull\Larascaff\Forms;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Models\Configuration\Permission;
use Mulaidarinull\Larascaff\Modules\Module;
use Mulaidarinull\Larascaff\Tables;

class BasePermissionModule extends Module
{
    protected static ?string $model = Permission::class;

    public function validationRules(): array
    {
        return [
            'name' => ['required', Rule::unique('permissions')->ignore(static::getInstanceModel())],
            'guard_name' => 'required',
            'menu_id' => 'required',
        ];
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->customQuery(function (Builder $query) {
                $query->with('menu');
            })
            ->columns(function (Tables\HtmlBuilder $builder) {
                $builder
                    ->columnsWithActions([
                        Tables\Column::make('name'),
                        Tables\Column::make('menu.name')->name('menu.name')->title('Menu'),
                    ]);
            });
    }

    public static function formBuilder(Forms\Components\Form $form): Forms\Components\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name'),
            Forms\Components\TextInput::make('guard_name'),
            Forms\Components\Select::make('menu_id')
                ->label('Menu')
                ->searchable()
                ->placeholder('Choose Menu')
                ->serverSide(Menu::class)
                ->relationship('menu', 'name'),
        ]);
    }

    public function shareData(Request $request)
    {
        $this->addDataToview([
            'menus' => Menu::active()->get()
                ->map(fn ($menu) => ['label' => $menu->name, 'value' => $menu->id]),
        ]);
    }
}
