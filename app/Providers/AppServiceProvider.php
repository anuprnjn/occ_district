<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $role_id = session('user.role_id');
        
            $rawMenus = DB::table('role_permissions')
                ->leftJoin('menu_master', 'menu_master.menu_id', '=', 'role_permissions.menu_id')
                ->leftJoin('submenu_master', 'submenu_master.submenu_id', '=', 'role_permissions.submenu_id')
                ->select(
                    'role_permissions.menu_id',
                    'menu_master.menu_name',
                    'menu_master.menu_icon',
                    'submenu_master.submenu_id',
                    'submenu_master.submenu_name',
                    'submenu_master.url'
                )
                ->where('role_permissions.role_id', $role_id)
                ->orderBy('menu_master.menu_id') // optional
                ->orderBy('submenu_master.submenu_id') // optional
                ->get();
        
            $menus = [];
        
            foreach ($rawMenus as $item) {
                $menuId = $item->menu_id;
        
                if (!isset($menus[$menuId])) {
                    $menus[$menuId] = [
                        'menu_name' => $item->menu_name,
                        'menu_icon' => $item->menu_icon,
                        'submenus' => [],
                    ];
                }
        
                if (!empty($item->submenu_id)) {
                    $menus[$menuId]['submenus'][] = (object)[
                        'name' => $item->submenu_name,
                        'url'  => $item->url,
                    ];
                }
            }
        
            $view->with('menus', $menus);
        });
    }
}
