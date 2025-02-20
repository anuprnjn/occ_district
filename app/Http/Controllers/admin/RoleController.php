<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function RoleList()
    {
        $roleResponse = Http::get(config('app.api.admin_url') . '/fetch_role.php');

        if ($roleResponse->failed()) {
            Log::error('Failed to fetch Role Data', ['response' => $roleResponse->body()]);
            $roledata = [];
        } else {
            Log::info('Roles fetched:', ['data' => $roleResponse->json()]);
            $roledata = $roleResponse->json();
        }

        return view('admin.role.role_list', compact('roledata'));
    }

    public function addRole(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|max:255',
        ]);

        $data = ['role_name' => $validated['role_name']];

        try {
            $response = Http::post(config('app.api.admin_url') . '/add_role.php', $data);

            if ($response->successful()) {
                return redirect()->route('role_list')->with('success', 'Role added successfully!');
            } else {
                return redirect()->route('role_list')->with('error', 'Failed to add role. ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->route('role_list')->with('error', 'Error while connecting to API: ' . $e->getMessage());
        }
    }

    public function updateRole(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|integer',
            'role_name' => 'required|string|max:255',
        ]);

        $data = [
            'role_id' => $validated['role_id'],
            'role_name' => $validated['role_name'],
        ];

        try {
            $response = Http::post(config('app.api.admin_url') . '/update_role.php', $data);

            if ($response->successful()) {
                return redirect()->route('role_list')->with('success', 'Role updated successfully!');
            } else {
                return redirect()->route('role_list')->with('error', 'Failed to update role. ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->route('role_list')->with('error', 'Error while connecting to API: ' . $e->getMessage());
        }
    }
}