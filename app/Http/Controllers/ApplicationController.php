<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApplicationController extends Controller
{
    public function fetchApplicationDetails(Request $request)
    {
        $applicationNumber = $request->input('application_number');

        if (empty($applicationNumber)) {
            return response()->json([
                'success' => false,
                'message' => 'Application number is required.'
            ]);
        }

        $baseUrl = rtrim(config('app.api.base_url'), '/');

        // Use specific endpoint based on the 4th character
        if (strlen($applicationNumber) >= 4 && strtoupper($applicationNumber[3]) === 'W') {
            $url = 'http://localhost/occ_api/district_court_order_copy/track_district_court_order_copy_application.php';
        } else {
            $url = $baseUrl . '/track_district_court_application.php';
        }

        try {
            $response = Http::post($url, [
                'application_number' => $applicationNumber,
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch application details or application number is incorrect.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ]);
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