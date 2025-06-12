<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SubMenuController extends Controller
{
    public function SubMenuList()
{
    // Fetch submenu data with associated menu name
    $submenudata = DB::table('submenu_master')
        ->select('submenu_master.*', 'menu_master.menu_name')
        ->join('menu_master', 'submenu_master.menu_id', '=', 'menu_master.menu_id')
        ->orderBy('submenu_master.menu_id', 'asc')
        ->orderBy('submenu_master.submenu_id', 'asc')
        ->get();

    // Fetch all menu data
    $menudata = DB::table('menu_master')
        ->orderBy('menu_name', 'asc')
        ->get();

        //dd($menudata);

    return view('admin.submenu.sub_menu_list', compact('submenudata', 'menudata'));
}

    public function addSubMenu(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|integer',
            'submenu_name' => 'required|string|max:255',
            'submenu_url' => 'required|string|max:255',
        ]);

        $data = [
            'menu_id' => $validated['menu_id'],
            'submenu_name' => $validated['submenu_name'],
            'submenu_url' => $validated['submenu_url'],
        ];

        try {
            $response = Http::post(config('app.api.admin_url') . '/add_submenu.php', $data);

            if ($response->successful()) {
                return redirect()->route('submenu_list')->with('success', 'Sub Menu added successfully!');
            } else {
                return redirect()->route('submenu_list')->with('error', 'Failed to add Sub Menu. ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->route('submenu_list')->with('error', 'Error while connecting to API: ' . $e->getMessage());
        }
    }

    public function updateSubMenu(Request $request)
    {
        $validated = $request->validate([
            'submenu_id' => 'required|integer',
            'menu_id' => 'required|integer',
            'submenu_name' => 'required|string|max:255',
            'submenu_url' => 'required|string|max:255',
        ]);

        $data = [
            'submenu_id' => $validated['submenu_id'],
            'menu_id' => $validated['menu_id'],
            'submenu_name' => $validated['submenu_name'],
            'submenu_url' => $validated['submenu_url'],
        ];

        try {
            $response = Http::post(config('app.api.admin_url') . '/update_submenu.php', $data);

            if ($response->successful()) {
                return redirect()->route('submenu_list')->with('success', 'Sub Menu updated successfully!');
            } else {
                return redirect()->route('submenu_list')->with('error', 'Failed to update Sub Menu. ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->route('submenu_list')->with('error', 'Error while connecting to API: ' . $e->getMessage());
        }
    }

    public function deleteSubMenu(Request $request)
{
    $validated = $request->validate([
        'submenu_id' => 'required|integer',
    ]);

    try {
        $response = Http::post(config('app.api.admin_url') . '/delete_submenu.php', [
            'submenu_id' => $validated['submenu_id'],
        ]);

        if ($response->successful()) {
            return redirect()->route('submenu_list')->with('success', 'Sub Menu deleted successfully!');
        } else {
            return redirect()->route('submenu_list')->with('error', 'Failed to delete Sub Menu. ' . $response->body());
        }
    } catch (\Exception $e) {
        return redirect()->route('submenu_list')->with('error', 'Error while connecting to API: ' . $e->getMessage());
    }
}

}
