<?php

namespace Mulaidarinull\Larascaff\Modules\Configuration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Mavinoo\Batch\BatchFacade;
use Mulaidarinull\Larascaff\BaseModule;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Yajra\DataTables\Html\Column;

class BaseMenuModule extends BaseModule
{
    protected $model = Menu::class;
    protected string $viewShow = 'larascaff::pages.menu-form';
    protected string $viewAction = 'larascaff::pages.menu-form';
    protected string $modalSize = 'md';

    public function __construct() {
        parent::__construct();
        $this->actions('sort', url($this->url. '/sort'), 'Sort menu', 'POST');
    }

    public function routes(): array
    {
        return [
            $this->makeRoute(url: 'sort', action: 'sort', method: 'post'),
        ];
    }

    public function sort()
    {
        $menus = $this->model->getMenus();

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

        BatchFacade::update(new Menu(), $data, 'id');
        return responseSuccess();
    }

    public function validationRules()
    {
        return [
            'name' => 'required',
            'url' => ['required', Rule::unique($this->model->getTable())->ignore($this->model)],
        ];
    }

    public function table(\Mulaidarinull\Larascaff\Datatable\BaseDatatable $table)
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
            'mainMenus' => Menu::where('id', '!=', $menu->id)->get()->map(fn($menu) => ['label' => $menu->name, 'value' => $menu->id]),
            'permissions' => $menu->permissions->pluck('name')->map(fn($item) => explode(' ', $item)[0]),
        ]);
    }

    public function afterStore(Request $request, Menu $menu)
    {
        foreach ($request->permissions ?? [] as $permission) {
            $menu->permissions()->create(['name' => $permission . " {$menu->url}"]);
        }
        Cache::forget('menus');
        Cache::forget('urlMenu');
    }

    public function afterUpdate(Request $request, Menu $menu)
    {
        $menu->load('permissions');

        // basic permission , create read update delete
        $basic = ['create', 'read', 'update', 'delete'];
        $ownedBasicPermission = $menu->permissions->filter(function ($item) use ($basic) {
            return in_array($item->action, $basic);
        });


        // detach and delete if user remove permission prof menu
        foreach ($ownedBasicPermission as $permission) {
            if (!in_array($permission->action, $request->permissions)) {
                $permission->delete();
            }
        }
        // attach and create if not exist
        foreach ($request->permissions as $permission) {
            if ($ownedBasicPermission->pluck('action')->doesntContain($permission)) {
                $menu->permissions()->create(['name' => $permission . ' ' . $menu->url]);
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
