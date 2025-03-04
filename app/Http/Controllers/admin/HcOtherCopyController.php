<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HcOtherCopyController extends Controller
{
    // Fetch HC Other Copy List
    public function listHcOtherCopy()
    {
        try {
            $apiUrl = config('app.api.admin_url') . '/fetch_hc_other_copy_application.php';
            $hcUserResponse = Http::timeout(10)->get($apiUrl);

            // Check for HTTP errors
            if ($hcUserResponse->failed()) {
                Log::error('Failed to fetch HC Other Copy Data', [
                    'status' => $hcUserResponse->status(),
                    'response' => $hcUserResponse->body()
                ]);

                session()->flash('error', 'Failed to retrieve data.');
                return view('admin.hc_other_copy.hc_other_copy_application_list', [
                    'hcuserdata' => []
                ]);
            }

            // Parse JSON response
            $hcuserdata = $hcUserResponse->json();

            // Ensure 'applications' key exists and is an array
            if (!isset($hcuserdata['applications']) || !is_array($hcuserdata['applications'])) {
                Log::error('Invalid API response format for HC Other Copy', ['response' => $hcuserdata]);

                session()->flash('error', 'Invalid API response.');
                return view('admin.hc_other_copy.hc_other_copy_application_list', [
                    'hcuserdata' => []
                ]);
            }

            // Return view with data
            return view('admin.hc_other_copy.hc_other_copy_application_list', [
                'hcuserdata' => $hcuserdata['applications']
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching HC Other Copy data', ['error' => $e->getMessage()]);

            session()->flash('error', 'An error occurred while fetching data.');
            return view('admin.hc_other_copy.hc_other_copy_application_list', [
                'hcuserdata' => []
            ]);
        }
    }
}
