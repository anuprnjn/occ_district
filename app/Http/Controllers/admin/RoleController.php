<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    // Fetch Role List
    public function RoleList()
    {
        try {
            $roleResponse = Http::get(config('app.api.admin_url') . '/fetch_role.php');

            if ($roleResponse->failed()) {
                Log::error('Failed to fetch Role Data', ['response' => $roleResponse->body()]);
                return view('admin.role.role_list', ['roledata' => []]);
            }

            $roledata = $roleResponse->json();
            Log::info('Fetched Role Data', ['roledata' => $roledata]);

            return view('admin.role.role_list', compact('roledata'));
        } catch (\Exception $e) {
            Log::error('Error fetching roles', ['error' => $e->getMessage()]);
            return redirect()->route('role_list')->with('error', 'Error fetching role data.');
        }
    }

    // Show Add Role Form
    public function showAddRoleForm()
    {
        try {
            $menuResponse = Http::get(config('app.api.admin_url') . '/fetch_submenu_permission.php');

            if ($menuResponse->failed()) {
                Log::error('Failed to fetch Menu Data', ['response' => $menuResponse->body()]);
                $menudata = [];
            } else {
                $menudata = $menuResponse->json();
            }

            return view('admin.role.role_add', compact('menudata'));
        } catch (\Exception $e) {
            Log::error('Error fetching menu permissions', ['error' => $e->getMessage()]);
            return redirect()->route('role_list')->with('error', 'Error fetching menu permissions.');
        }
    }

    // Add Role
    public function addRole(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        try {
            $response = Http::post(config('app.api.admin_url') . '/add_role.php', [
                'role_name' => $validated['role_name'],
                'permissions' => $validated['permissions'] ?? [],
            ]);

            if ($response->status() == 409) {
                return redirect()->route('role_list')->with('error', 'Role name already exists!');
            }

            if ($response->successful()) {
                return redirect()->route('role_list')->with('success', 'Role added successfully!');
            } else {
                return redirect()->route('role_list')->with('error', 'Failed to add role. ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Error adding role', ['error' => $e->getMessage()]);
            return redirect()->route('role_list')->with('error', 'Error while connecting to API.');
        }
    }

    // Show Edit Role Form
    public function editRole($role_id)
    {
        try {
            // Fetch Role Data
            $roleResponse = Http::get(config('app.api.admin_url') . '/fetch_role.php', [
                'role_id' => $role_id
            ]);

            if ($roleResponse->failed()) {
                return redirect()->route('role_list')->with('error', 'Failed to fetch role data.');
            }

            $role = collect($roleResponse->json())->firstWhere('role_id', $role_id);

            // Fetch Permissions Data
            $menuResponse = Http::get(config('app.api.admin_url') . '/fetch_submenu_permission.php');

            if ($menuResponse->failed()) {
                $menudata = [];
            } else {
                $menudata = $menuResponse->json();
            }

            return view('admin.role.role_edit', compact('role', 'menudata'));
        } catch (\Exception $e) {
            Log::error('Error fetching role data', ['error' => $e->getMessage()]);
            return redirect()->route('role_list')->with('error', 'Error fetching role data.');
        }
    }

    // Update Role
    public function updateRole(Request $request, $role_id)
{
    $validated = $request->validate([
        'role_name' => 'required|string|max:255',
        'permissions' => 'nullable|array',
    ]);

    try {
        // Prepare the data for API request
        $payload = [
            'role_id' => $role_id,
            'role_name' => $validated['role_name'],
            'permissions' => $validated['permissions'] ?? [],
        ];

        // Send the update request to the API
        $response = Http::post(config('app.api.admin_url') . '/update_role.php', $payload);

        // Check API response
        if ($response->successful()) {
            return redirect()->route('role_list')->with('success', 'Role updated successfully!');
        } elseif ($response->status() == 400) {
            return redirect()->route('role_list')->with('error', 'Invalid data provided.');
        } else {
            return redirect()->route('role_list')->with('error', 'Failed to update role. ' . $response->body());
        }
    } catch (\Exception $e) {
        return redirect()->route('role_list')->with('error', 'Error connecting to API: ' . $e->getMessage());
    }
}

   
}
