<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DcOtherCopyController extends Controller
{
    // Fetch DC Other Copy List
    public function listDcOtherCopy()
    {
        try {
            $apiUrl = config('app.api.admin_url') . '/fetch_dc_other_copy_application.php';
            $dcUserResponse = Http::timeout(10)->get($apiUrl);

            // Check for HTTP errors
            if ($dcUserResponse->failed()) {
                Log::error('Failed to fetch DC Other Copy Data', [
                    'status' => $dcUserResponse->status(),
                    'response' => $dcUserResponse->body()
                ]);

                session()->flash('error', 'Failed to retrieve data.');
                return view('admin.dc_other_copy.dc_other_copy_application_list', [
                    'dcuserdata' => []
                ]);
            }

            // Parse JSON response
            $dcuserdata = $dcUserResponse->json();

            // Ensure 'applications' key exists and is an array
            if (!isset($dcuserdata['applications']) || !is_array($dcuserdata['applications'])) {
                Log::error('Invalid API response format for DC Other Copy', ['response' => $dcuserdata]);

                session()->flash('error', 'Invalid API response.');
                return view('admin.dc_other_copy.dc_other_copy_application_list', [
                    'dcuserdata' => []
                ]);
            }

            // Return view with data
            return view('admin.dc_other_copy.dc_other_copy_application_list', [
                'dcuserdata' => $dcuserdata['applications']
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching DC Other Copy data', ['error' => $e->getMessage()]);

            session()->flash('error', 'An error occurred while fetching data.');
            return view('admin.dc_other_copy.dc_other_copy_application_list', [
                'dcuserdata' => []
            ]);
        }
    }
}
