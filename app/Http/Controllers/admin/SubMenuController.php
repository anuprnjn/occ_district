<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubMenuController extends Controller
{
    public function SubMenuList()
    {
        // Fetch submenu list
        $submenuResponse = Http::get(config('app.api.admin_url') . '/fetch_submenu.php');
        $menuResponse = Http::get(config('app.api.admin_url') . '/fetch_menu.php'); // Fetch menu list

        $submenudata = $submenuResponse->successful() ? $submenuResponse->json() : [];
        $menudata = $menuResponse->successful() ? $menuResponse->json() : [];

        if ($submenuResponse->failed()) {
            Log::error('Failed to fetch SubMenu Data', ['response' => $submenuResponse->body()]);
        }

        if ($menuResponse->failed()) {
            Log::error('Failed to fetch Menu Data', ['response' => $menuResponse->body()]);
        }

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
