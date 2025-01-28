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
}