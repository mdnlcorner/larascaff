<?php

namespace Mulaidarinull\Larascaff\Modules\Configuration;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mulaidarinull\Larascaff\BaseModule;
use Mulaidarinull\Larascaff\Components\Forms;
use Mulaidarinull\Larascaff\DataTables\BaseDataTable;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Models\Configuration\Permission;
use Mulaidarinull\Larascaff\Tables;
use Yajra\DataTables\Html\Column;

class BasePermissionModule extends BaseModule
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

    public static function table(BaseDatatable $table): BaseDatatable
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
            ->columns(function (\Mulaidarinull\Larascaff\DataTables\HtmlBuilder $builder) {
                $builder
                    ->columnsWithActions([
                        Column::make('name'),
                        Column::make('menu.name')->name('menu.name')->title('Menu'),
                    ]);
            });
    }

    public static function formBuilder(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\TextInput::make('name'),
            Forms\TextInput::make('guard_name'),
            Forms\Select::make('menu_id')
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
