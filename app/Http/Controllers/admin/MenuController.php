<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function MenuList()
    {
        $menuResponse = Http::get(config('app.api.admin_url') . '/fetch_menu.php');

        if ($menuResponse->failed()) {
            Log::error('Failed to fetch Menu Data', ['response' => $menuResponse->body()]);
            $menudata = [];
        } else {
            Log::info('Menu fetched:', ['data' => $menuResponse->json()]);
            $menudata = $menuResponse->json();
        }

        return view('admin.menu.menu_list', compact('menudata'));
    }

    public function addMenu(Request $request)
    {
        $validated = $request->validate([
            'menu_name' => 'required|string|max:255',
            'menu_icon' => 'required|string|max:255'
        ]);

        $data = [
            'menu_name' => $validated['menu_name'],
            'menu_icon' => $validated['menu_icon']
          ];

        try {
            $response = Http::post(config('app.api.admin_url') . '/add_menu.php', $data);

            if ($response->successful()) {
                return redirect()->route('menu_list')->with('success', 'Menu added successfully!');
            } else {
                return redirect()->route('menu_list')->with('error', 'Failed to add menu. ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->route('menu_list')->with('error', 'Error while connecting to API: ' . $e->getMessage());
        }
    }

    public function updateMenu(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|integer',
            'menu_name' => 'required|string|max:255',
            'menu_icon' => 'required|string|max:255'
        ]);

        $data = [
            'menu_id' => $validated['menu_id'],
            'menu_name' => $validated['menu_name'],
            'menu_icon' => $validated['menu_icon']
        ];

        try {
            $response = Http::post(config('app.api.admin_url') . '/update_menu.php', $data);

            if ($response->successful()) {
                return redirect()->route('menu_list')->with('success', 'Menu updated successfully!');
            } else {
                return redirect()->route('menu_list')->with('error', 'Failed to update menu. ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->route('menu_list')->with('error', 'Error while connecting to API: ' . $e->getMessage());
        }
    }
}
