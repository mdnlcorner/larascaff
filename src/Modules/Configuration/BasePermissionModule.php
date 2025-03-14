<?php

namespace Mulaidarinull\Larascaff\Modules\Configuration;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mulaidarinull\Larascaff\BaseModule;
use Mulaidarinull\Larascaff\Components\Forms\Form;
use Mulaidarinull\Larascaff\Components\Forms\Select;
use Mulaidarinull\Larascaff\Components\Forms\TextInput;
use Mulaidarinull\Larascaff\Datatable\BaseDatatable;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Models\Configuration\Permission;
use Yajra\DataTables\Html\Column;

class BasePermissionModule extends BaseModule
{
    protected $model = Permission::class;

    public function validationRules(): array
    {
        return [
            'name' => ['required', Rule::unique('permissions')->ignore($this->model)],
            'guard_name' => 'required',
            'menu_id' => 'required',
        ];
    }

    public function table(BaseDatatable $table)
    {
        $table
            ->customQuery(function (Builder $query) {
                $query->with('menu');
            })
            ->columns(function (\Mulaidarinull\Larascaff\Datatable\HtmlBuilder $builder) {
                $builder
                    ->columnsWithActions([
                        Column::make('name'),
                        Column::make('menu.name')->name('menu.name')->title('Menu'),
                    ]);
            });
    }

    public function formBuilder(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name'),
            TextInput::make('guard_name'),
            Select::make('menu_id')
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
