<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class  DcUserController extends Controller
{
    // Fetch dc User List
    // Fetch dc User List
    public function listDcUser()
    {
        try {
            $dcUserResponse = Http::get(config('app.api.admin_url') . '/fetch_dc_user.php');

            if ($dcUserResponse->failed()) {
                Log::error('Failed to fetch dc User Data', ['response' => $dcUserResponse->body()]);
                return view('admin.dc_user.dc_user_list', ['dcuserdata' => []]);
            }

            $dcuserdata = $dcUserResponse->json();
            if (!isset($dcuserdata['users'])) {
                Log::error('Invalid API response format', ['response' => $dcuserdata]);
                return view('admin.dc_user.dc_user_list', ['dcuserdata' => []]);
            }

            return view('admin.dc_user.dc_user_list', ['dcuserdata' => $dcuserdata['users']]);
        } catch (\Exception $e) {
            Log::error('Error fetching dc User data', ['error' => $e->getMessage()]);
            return redirect()->route('dc_user_list')->with('error', 'Error fetching dc User data.');
        }
    }

    // Show Add User Form
    public function showDcUser()
    {
        try {
            $roleResponse = Http::get(config('app.api.admin_url') . '/fetch_role_dc.php');
            $distrcitResponse = Http::get(config('app.api.admin_url') . '/district_dropdown.php');

            if ($roleResponse->failed()) {
                Log::error('Failed to fetch Role Data', ['response' => $roleResponse->body()]);
                return view('admin.dc_user.dc_user_add', ['roledata' => []]);
            }
            if ($distrcitResponse->failed()) {
                Log::error('Failed to fetch District Data', ['response' => $distrcitResponse->body()]);
                return view('admin.dc_user.dc_user_add', ['districtdata' => []]);
            }

            $roledata = $roleResponse->json();
            $districtdata = $distrcitResponse->json();
            return view('admin.dc_user.dc_user_add', compact('roledata','districtdata'));
        } catch (\Exception $e) {
            Log::error('Error fetching role data', ['error' => $e->getMessage()]);
            return redirect()->route('dc_user_list')->with('error', 'Error fetching role data.');
        }
    }

    public function fetchEstablishments(Request $request)
{
    if (!$request->has('dist_code')) {
        return response()->json(['error' => 'District code is required'], 400);
    }

    try {
        $establishmentResponse = Http::post(config('app.api.admin_url') . '/establishment.php', [
            'dist_code' => $request->dist_code
        ]);

        if ($establishmentResponse->failed()) {
            return response()->json(['error' => 'Failed to fetch establishments'], 500);
        }

        return response()->json($establishmentResponse->json());
    } catch (\Exception $e) {
        Log::error('Error fetching establishments', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Internal server error'], 500);
    }
 }

    // Add dc User
    public function addDcUser(Request $request)
{
    $validated = $request->validate([
        'dist_code' => 'required|string',
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
        'est_code' => 'nullable|array', // Ensure it's an array
        'est_code.*' => 'string' // Each establishment code should be a string
    ], [
        'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
    ]);

    try {
        $response = Http::post(config('app.api.admin_url') . '/add_dc_user.php', [
            'dist_code' => $validated['dist_code'], // Include district code
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile_no' => $validated['mobile_no'],
            'role_id' => $validated['role_id'],
            'username' => $validated['username'],
            'password' => $validated['password'],
            'est_code' => $validated['est_code'] ?? [], // Send selected establishments
        ]);

        if ($response->status() == 409) {
            return redirect()->route('dc_user_list')->with('error', 'User already exists using This mobile or email or username !');
        }

        if ($response->successful()) {
            return redirect()->route('dc_user_list')->with('success', 'User added successfully!');
        } else {
            return redirect()->route('dc_user_list')->with('error', 'Failed to add user. ' . $response->body());
        }
    } catch (\Exception $e) {
        Log::error('Error adding user', ['error' => $e->getMessage()]);
        return redirect()->route('dc_user_list')->with('error', 'Error while connecting to API.');
    }
}

    // Show Edit User Form
    public function editDcUser($user_id)
{
    try {
        // Fetch user details by user_id
        $userResponse = Http::get(config('app.api.admin_url') . '/fetch_dc_user_id.php', [
            'user_id' => $user_id
        ]);

        if ($userResponse->failed()) {
            return redirect()->route('dc_user_list')->with('error', 'Failed to fetch user data.');
        }

        $userData = $userResponse->json();

        // Check if user data exists
        if (!isset($userData['user'])) {
            return redirect()->route('dc_user_list')->with('error', 'User not found.');
        }

        $dcUser = $userData['user'];

        // Fetch roles
        $roleResponse = Http::get(config('app.api.admin_url') . '/fetch_role_dc.php');
        $roledata = $roleResponse->json() ?? [];

        // Fetch districts
        $districtResponse = Http::get(config('app.api.admin_url') . '/district_dropdown.php');
        $districtdata = $districtResponse->json() ?? [];

        // Fetch establishments for selected district
        $establishmentResponse = Http::post(config('app.api.admin_url') . '/establishment.php', [
            'dist_code' => $dcUser['dist_code'] ?? '',
        ]);
        $establishments = $establishmentResponse->json() ?? [];
        // Fetch user’s selected establishments with both dist_code and user_id
        $userEstablishmentResponse = Http::post(config('app.api.admin_url') . '/user_establishment.php', [
            'dist_code' => $dcUser['dist_code'] ?? '',
            'user_id' => $user_id,
        ]);
        
        $userEstablishments = $userEstablishmentResponse->json() ?? [];
        
        // Ensure we are accessing the correct part of the response
        $selectedEstCodes = collect($userEstablishments['establishments'] ?? [])->pluck('est_code')->toArray();
        
        //dd($selectedEstCodes); // Now should return ["JHBK03", "JHBK04", "JHBK02"]

        return view('admin.dc_user.dc_user_edit', compact('dcUser', 'roledata', 'districtdata', 'establishments', 'selectedEstCodes'));

    } catch (\Exception $e) {
        Log::error('Error fetching user data', ['error' => $e->getMessage()]);
        return redirect()->route('dc_user_list')->with('error', 'Error fetching user data.');
    }
}

    // Update User
    // Update User
    public function updateDcUser(Request $request, $user_id)
    {
        
        $validated = $request->validate([
            'dist_code' => 'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_no' => 'required|digits:10',
            'role_id' => 'required|integer', // Ensure it's not missing
            'username' => 'required|string|max:255',
            'est_code' => 'nullable|array', // Ensure it's an array
        ]);
    
        // Debugging to check received values
        //dd($validated); // Remove this after testing
    
        try {
            $response = Http::post(config('app.api.admin_url') . '/update_dc_user.php', [
                'id' => $user_id,
                'dist_code' => $validated['dist_code'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile_no' => $validated['mobile_no'],
                'role_id' => $validated['role_id'], // Ensure this is included
                'username' => $validated['username'], 
                'est_code' => $validated['est_code'] ?? [], // Fix: Ensure correct field name
            ]);

            if ($response->status() == 409) {
            return redirect()->route('dc_user_list')->with('error', 'User already exists using This mobile or email or username !');
        }
    
            if ($response->successful()) {
                return redirect()->route('dc_user_list')->with('success', 'User updated successfully!');
            } else {
                return redirect()->route('dc_user_list')->with('error', 'Failed to update user. ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Error updating user', ['error' => $e->getMessage()]);
            return redirect()->route('dc_user_list')->with('error', 'Error while connecting to API.');
        }
    }
    

    // Delete User
    public function deleteDcUser($user_id)
    {
        try {
            $response = Http::delete(config('app.api.admin_url') . '/delete_dc_user.php', ['user_id' => $user_id]);

            if ($response->successful()) {
                return redirect()->route('dc_user_list')->with('success', 'User deleted successfully!');
            } else {
                return redirect()->route('dc_user_list')->with('error', 'Failed to delete user. ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Error deleting user', ['error' => $e->getMessage()]);
            return redirect()->route('dc_user_list')->with('error', 'Error deleting user.');
        }
    }
}
