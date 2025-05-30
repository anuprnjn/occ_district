<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class mobileNumberTrackController extends Controller
{
    public function trackMobileNumberHC(Request $request)
    {
        // Validate the request
        $request->validate([
            'mobile_number' => 'required|digits:10',
        ]);

        // Prepare the API endpoint and data
        $apiUrl = 'http://localhost/occ_api/high_court/get_all_application_applied_user_from_mobile_hc.php';
        $mobileNumber = $request->input('mobile_number');

        try {
            // Make POST request to the external PHP API
            $response = Http::post($apiUrl, [
                'mobile_number' => $mobileNumber,
            ]);

            // Decode and return the response as JSON
            $data = $response->json();
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from external API.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function trackMobileNumberDC(Request $request)
    {
        // Validate the request
        $request->validate([
            'mobile_number' => 'required|digits:10',
        ]);

        // Prepare the API endpoint and data
        $apiUrl = 'http://localhost/occ_api/district_court/get_all_application_applied_user_from_mobile_dc.php';
        $mobileNumber = $request->input('mobile_number');

        try {
            // Make POST request to the external PHP API
            $response = Http::post($apiUrl, [
                'mobile_number' => $mobileNumber,
            ]);

            // Decode and return the response as JSON
            $data = $response->json();
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from external API.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function setTrackDetailsHC(Request $request)
    {
        $trackData = $request->input('data');

        // Save in Laravel session
        session(['trackDetailsMobileHC' => $trackData]);

        return response()->json([
            'success' => true,
            'message' => 'Track details of HC saved in session.'
        ]);
    }
    public function setTrackDetailsDC(Request $request)
    {
        $trackData = $request->input('data');

        // Save in Laravel session
        session(['trackDetailsMobileDC' => $trackData]);

        return response()->json([
            'success' => true,
            'message' => 'Track details of DC saved in session.'
        ]);
    }
}
