<?php

namespace Mulaidarinull\Larascaff\Modules\Configuration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Mavinoo\Batch\BatchFacade;
use Mulaidarinull\Larascaff\BaseModule;
use Mulaidarinull\Larascaff\Components\Forms\Form;
use Mulaidarinull\Larascaff\Components\Forms\Radio;
use Mulaidarinull\Larascaff\Components\Forms\Select;
use Mulaidarinull\Larascaff\Components\Forms\TextInput;
use Mulaidarinull\Larascaff\Concerns\ModuleAction;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Yajra\DataTables\Html\Column;

class BaseMenuModule extends BaseModule
{
    /**
     * @var Illuminate\Database\Eloquent\Model|string
     */
    protected static ?string $model = Menu::class;

    protected static ?string $viewShow = 'larascaff::pages.menu-form';

    protected static ?string $viewAction = 'larascaff::pages.menu-form';

    public static function actions()
    {
        return [
            ModuleAction::make(permission: 'sort', url: '/sort', label: 'Sort menu', method: 'POST'),
        ];
    }

    public static function routes(): array
    {
        return [
            static::makeRoute(url: 'sort', action: 'sort', method: 'post'),
        ];
    }

    public function sort()
    {
        $menus = static::getInstanceModel()->getMenus();

        $data = [];
        $i = 0;
        foreach ($menus as $mm) {
            $i++;
            $data[] = ['id' => $mm->id, 'orders' => $i];
            foreach ($mm->subMenus as $sm) {
                $i++;
                $data[] = ['id' => $sm->id, 'orders' => $i];
            }
        }

        Cache::forget('menus');

        BatchFacade::update(new Menu, $data, 'id');

        return responseSuccess();
    }

    public function validationRules()
    {
        return [
            'name' => 'required',
            'url' => ['required', Rule::unique(static::getInstanceModel()->getTable())->ignore(static::getInstanceModel())],
        ];
    }

    public static function table(\Mulaidarinull\Larascaff\Datatable\BaseDatatable $table)
    {
        $table
            ->customQuery(function (\Illuminate\Database\Eloquent\Builder $query) {
                $query->with('mainMenu', 'permissions');
            })
            ->customizeColumn(function (\Yajra\DataTables\EloquentDataTable $eloquentDataTable) {
                $eloquentDataTable
                    ->addColumn('permission', function (Menu $menu) {
                        return $menu->permissions->pluck('name')->map(function ($item) {
                            $item = explode(' ', $item)[0];

                            return $item;
                        })->implode(', ');
                    });
            })
            ->columns(function (\Mulaidarinull\Larascaff\Datatable\HtmlBuilder $builder) {
                $builder
                    ->columnsWithActions([
                        Column::make('name'),
                        Column::make('url'),
                        Column::make('category'),
                        Column::make('icon'),
                        Column::make('permission')->searchable(false)->orderable(false),
                    ]);
            });
    }

    public function shareData(Menu $menu)
    {
        $this->addDataToview([
            'mainMenus' => Menu::where('id', '!=', $menu->id)->get()->map(fn ($menu) => ['label' => $menu->name, 'value' => $menu->id]),
            'permissions' => $menu->permissions->pluck('name')->map(fn ($item) => explode(' ', $item)[0]),
        ]);
    }

    // public function formBuilder(Form $form): Form
    // {
    //     return $form->schema([
    //         TextInput::make('name')->required(),
    //         TextInput::make('url')->required(),
    //         TextInput::make('icon'),
    //         TextInput::make('category'),
    //         TextInput::make('orders'),
    //         Select::make('main_menu_id')
    //             ->relationship('mainMenu', 'name')
    //             ->searchable()
    //             ->placeholder('Choose Main Menu')
    //             ->modifyQuery(fn ($query) => $query->active()),
    //         Radio::make('active')->options(['Y' => 1, 'N' => 0]),
    //     ]);
    // }

    public static function afterStore(Request $request, Menu $menu)
    {
        foreach ($request->permissions ?? [] as $permission) {
            $menu->permissions()->create(['name' => $permission." {$menu->url}"]);
        }
        Cache::forget('menus');
        Cache::forget('urlMenu');
    }

    public static function afterUpdate(Request $request, Menu $menu)
    {
        $menu->load('permissions');

        // basic permission , create read update delete
        $basic = ['create', 'read', 'update', 'delete'];
        $ownedBasicPermission = $menu->permissions->filter(function ($item) use ($basic) {
            return in_array($item->action, $basic);
        });

        // detach and delete if user remove permission prof menu
        foreach ($ownedBasicPermission as $permission) {
            if (! in_array($permission->action, $request->permissions)) {
                $permission->delete();
            }
        }
        // attach and create if not exist
        foreach ($request->permissions as $permission) {
            if ($ownedBasicPermission->pluck('action')->doesntContain($permission)) {
                $menu->permissions()->create(['name' => $permission.' '.$menu->url]);
            }
        }
        Cache::forget('menus');
        Cache::forget('urlMenu');
    }

    public function afterDelete()
    {
        Cache::forget('menus');
        Cache::forget('urlMenu');
    }
}
