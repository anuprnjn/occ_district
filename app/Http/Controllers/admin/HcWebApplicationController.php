<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HcWebApplicationController extends Controller
{
    // Fetch HC User List
    public function listHcWebApplication()
{
    try {
        $apiUrl = config('app.api.admin_url') . '/fetch_hc_web_application.php';
        $hcUserResponse = Http::timeout(10)->get($apiUrl);

        // Check for HTTP errors
        if ($hcUserResponse->failed()) {
            Log::error('Failed to fetch HC User Data', [
                'status' => $hcUserResponse->status(),
                'response' => $hcUserResponse->body()
            ]);

            return view('admin.hc_web_copy.hc_web_application_list', [
                'hcuserdata' => []
            ])->with('error', 'Failed to retrieve data.');
        }

        // Parse JSON response
        $hcuserdata = $hcUserResponse->json();

        // Ensure 'applications' key exists
        if (!isset($hcuserdata['applications']) || !is_array($hcuserdata['applications'])) {
            Log::error('Invalid API response format', ['response' => $hcuserdata]);

            return view('admin.hc_web_copy.hc_web_application_list', [
                'hcuserdata' => []
            ])->with('error', 'Invalid API response.');
        }

        // Return view with data
        return view('admin.hc_web_copy.hc_web_application_list', [
            'hcuserdata' => $hcuserdata['applications']
        ]);

    } catch (\Exception $e) {
        Log::error('Error fetching HC User data', ['error' => $e->getMessage()]);

        return view('admin.hc_web_copy.hc_web_application_list', [
            'hcuserdata' => []
        ])->with('error', 'An error occurred while fetching data.');
    }
}




}
