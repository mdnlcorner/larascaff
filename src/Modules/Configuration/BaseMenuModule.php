<?php

namespace Mulaidarinull\Larascaff\Modules\Configuration;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Mavinoo\Batch\BatchFacade;
use Mulaidarinull\Larascaff\Actions\Action;
use Mulaidarinull\Larascaff\Actions\CreateAction;
use Mulaidarinull\Larascaff\Enums\ColorVariant;
use Mulaidarinull\Larascaff\Forms;
use Mulaidarinull\Larascaff\Models\Configuration\Menu;
use Mulaidarinull\Larascaff\Modules\Module;
use Mulaidarinull\Larascaff\Tables;

class BaseMenuModule extends Module
{
    /**
     * @var Illuminate\Database\Eloquent\Model|string
     */
    protected static ?string $model = Menu::class;

    public static function actions(): array
    {
        return [
            CreateAction::make()
                ->afterSave(function () {
                    Cache::forget('menus');
                    Cache::forget('urlMenu');
                }),
            Action::make('sort')->permission('sort')->path('sort')
                ->label('Sort Menu')->color(ColorVariant::Info)
                ->form(false)
                ->withValidations(false)
                ->action(function (Menu $record) {
                    static::sort($record);
                })
                ->method('post'),
        ];
    }

    public static function sort(Menu $record)
    {
        $menus = $record->getMenus();

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
        Cache::forget('urlMenu');

        BatchFacade::update(new Menu, $data, 'id');
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->afterSave(function () {
                        Cache::forget('menus');
                        Cache::forget('urlMenu');
                    }),
                Tables\Actions\DeleteAction::make()
                    ->afterSave(function () {
                        Cache::forget('menus');
                        Cache::forget('urlMenu');
                    }),
            ])
            ->query(function (Builder $query) {
                $query->with('mainMenu', 'permissions');
            })
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('url'),
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('icon'),
                Tables\Columns\TextColumn::make('permissions')->searchable(false)->orderable(false)
                    ->editColumn(function (Menu $menu) {
                        return $menu->permissions->pluck('name')->map(function ($item) {
                            $item = explode(' ', $item)[0];

                            return $item;
                        })->implode(', ');
                    }),
            ]);
    }

    public static function formBuilder(Forms\Components\Form $form): Forms\Components\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->validations(['required']),
            Forms\Components\TextInput::make('url')->validations(['required']),
            Forms\Components\TextInput::make('icon'),
            Forms\Components\TextInput::make('category'),
            Forms\Components\TextInput::make('orders'),
            Forms\Components\Select::make('main_menu_id')
                ->relationship('mainMenu', 'name')
                ->searchable()
                ->placeholder('Choose Main Menu')
                ->modifyQuery(fn ($query) => $query->active()),
            Forms\Components\Radio::make('active')->options(['Y' => 1, 'N' => 0]),
        ]);
    }
}
