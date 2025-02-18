<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApplicationController extends Controller
{
    public function fetchApplicationDetails(Request $request)
    {
        $applicationNumber = $request->input('application_number');
        if (!$applicationNumber) {
            return response()->json(['success' => false, 'message' => 'Application number is required.']);
        }

        $baseUrl = config('app.api.base_url'); 

        try {
            $response = Http::post($baseUrl . '/track_district_court_application.php', [
                'application_number' => $applicationNumber,
            ]);
            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['success' => false, 'message' => 'Failed to fetch application details.']);
        } catch (\Exception $e) {
            // If an exception occurs, return an error message
            return response()->json(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    public function fetchHcApplicationDetails(Request $request)
    {
    
        $applicationNumber = $request->input('application_number');
    
        if (!$applicationNumber) {
            return response()->json(['success' => false, 'message' => 'Application number is required.']);
        }
        if(str_starts_with($applicationNumber, 'HCW')) {
            $baseUrl = config('app.api.hc_order_copy_base_url');
            $pagename=  '/track_high_court_order_copy_application.php';
        } else {
            $baseUrl = config('app.api.hc_base_url'); 
            $pagename=  '/track_high_court_application.php';
        }

        
        try {
            $response = Http::post($baseUrl . $pagename, [
                'application_number' => $applicationNumber,
            ]);
           
            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['success' => false, 'message' => 'Application number not found !']);
        } catch (\Exception $e) {
            // If an exception occurs, return an error message
            console.log($e);
            return response()->json(['success' => false, 'message' => 'An error occurred.']);
        }
    }
}