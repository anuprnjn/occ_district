<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class   HcUserController extends Controller
{
    // Fetch HC User List
    // Fetch HC User List
    public function listHcUser()
    {
        try {
            $hcUserResponse = Http::get(config('app.api.admin_url') . '/fetch_hc_user.php');

            if ($hcUserResponse->failed()) {
                Log::error('Failed to fetch HC User Data', ['response' => $hcUserResponse->body()]);
                return view('admin.hc_user.hc_user_list', ['hcuserdata' => []]);
            }

            $hcuserdata = $hcUserResponse->json();
            if (!isset($hcuserdata['users'])) {
                Log::error('Invalid API response format', ['response' => $hcuserdata]);
                return view('admin.hc_user.hc_user_list', ['hcuserdata' => []]);
            }

            return view('admin.hc_user.hc_user_list', ['hcuserdata' => $hcuserdata['users']]);
        } catch (\Exception $e) {
            Log::error('Error fetching HC User data', ['error' => $e->getMessage()]);
            return redirect()->route('hc_user_list')->with('error', 'Error fetching HC User data.');
        }
    }

    // Show Add User Form
    public function showHcUser()
    {
        try {
            $roleResponse = Http::get(config('app.api.admin_url') . '/fetch_role_hc.php');

            if ($roleResponse->failed()) {
                Log::error('Failed to fetch Role Data', ['response' => $roleResponse->body()]);
                return view('admin.hc_user.hc_user_add', ['roledata' => []]);
            }

            $roledata = $roleResponse->json();
            return view('admin.hc_user.hc_user_add', compact('roledata'));
        } catch (\Exception $e) {
            Log::error('Error fetching role data', ['error' => $e->getMessage()]);
            return redirect()->route('hc_user_list')->with('error', 'Error fetching role data.');
        }
    }

    // Add HC User
    public function addHcUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_no' => 'required|digits:10',
            'role_id' => 'required|integer',
            'username' => 'required|string|max:255',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);
  

        try {
            $response = Http::post(config('app.api.admin_url') . '/add_hc_user.php', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile_no' => $validated['mobile_no'],
                'role_id' => $validated['role_id'],
                'username' => $validated['username'],
                'password' => $validated['password'],
            ]);

            if ($response->status() == 409) {
                return redirect()->route('hc_user_list')->with('error', 'User already exists!');
            }

            if ($response->successful()) {
                return redirect()->route('hc_user_list')->with('success', 'User added successfully!');
            } else {
                return redirect()->route('hc_user_list')->with('error', 'Failed to add user. ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Error adding user', ['error' => $e->getMessage()]);
            return redirect()->route('hc_user_list')->with('error', 'Error while connecting to API.');
        }
    }

    // Show Edit User Form
    public function editHcUser($user_id)
{
    try {
        // Fetch user details by user_id
        $userResponse = Http::get(config('app.api.admin_url') . '/fetch_hc_user_id.php', [
            'user_id' => $user_id
        ]);

        if ($userResponse->failed()) {
            return redirect()->route('hc_user_list')->with('error', 'Failed to fetch user data.');
        }

        $userData = $userResponse->json();

        // Check if user data exists
        if (!isset($userData['user'])) {
            return redirect()->route('hc_user_list')->with('error', 'User not found.');
        }

        $hcUser = $userData['user'];

        // Fetch roles
        $roleResponse = Http::get(config('app.api.admin_url') . '/fetch_role_hc.php');
        $roledata = $roleResponse->json() ?? [];

        return view('admin.hc_user.hc_user_edit', compact('hcUser', 'roledata'));
    } catch (\Exception $e) {
        Log::error('Error fetching user data', ['error' => $e->getMessage()]);
        return redirect()->route('hc_user_list')->with('error', 'Error fetching user data.');
    }
}


    // Update User
    // Update User
    public function updateHcUser(Request $request, $user_id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_no' => 'required|digits:10',
            'role_id' => 'required|integer',
            'username' => 'required|string|max:255',
        ]);
    
        try {
            $response = Http::post(config('app.api.admin_url') . '/update_hc_user.php', [
                'id' => $user_id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile_no' => $validated['mobile_no'],
                'role_id' => $validated['role_id'],
                'username' => $validated['username'], 
            ]);
    
            if ($response->successful()) {
                return redirect()->route('hc_user_list')->with('success', 'User updated successfully!');
            } else {
                return redirect()->route('hc_user_list')->with('error', 'Failed to update user. ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Error updating user', ['error' => $e->getMessage()]);
            return redirect()->route('hc_user_list')->with('error', 'Error while connecting to API.');
        }
    }
    

    // Delete User
    public function deleteHcUser($user_id)
    {
        try {
            $response = Http::delete(config('app.api.admin_url') . '/delete_hc_user.php', ['user_id' => $user_id]);

            if ($response->successful()) {
                return redirect()->route('hc_user_list')->with('success', 'User deleted successfully!');
            } else {
                return redirect()->route('hc_user_list')->with('error', 'Failed to delete user. ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Error deleting user', ['error' => $e->getMessage()]);
            return redirect()->route('hc_user_list')->with('error', 'Error deleting user.');
        }
    }
}
