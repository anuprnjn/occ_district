<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DistrictController extends Controller
{
    /**
     * Display the index page with districts and case types.
     */
    public function index()
    {
        // Fetch districts
        $apiUrl = config('app.api.base_url') . '/district_dropdown.php';
        $districtResponse = Http::get($apiUrl);

        if ($districtResponse->failed()) {
            \Log::error('Failed to fetch districts: ' . $districtResponse->body());
            return back()->withErrors(['message' => 'Unable to fetch districts.']);
        }

        $districts = $districtResponse->json();

        // Fetch case types
        $caseTypeApiUrl = config('app.api.base_url') . '/case_type.php';
        $caseTypeResponse = Http::get($caseTypeApiUrl);

        if ($caseTypeResponse->failed()) {
            \Log::error('Failed to fetch case types: ' . $caseTypeResponse->body());
            return back()->withErrors(['message' => 'Unable to fetch case types.']);
        }

        $caseTypes = $caseTypeResponse->json();

        // Pass both districts and case types to the view
        return view('index', compact('districts', 'caseTypes'));
    }

    /**
     * Fetch establishments for the given district code.
     */
    public function getEstablishments(Request $request)
    {
        $request->validate(['dist_code' => 'required|string']);

        $response = Http::asForm()->post(config('app.api.base_url') . '/establishment.php', [
            'dist_code' => $request->dist_code,
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Unable to fetch establishments.'], 500);
        }

        return response()->json($response->json());
    }

    /**
     * Fetch case types from the external API.
     */
    public function getCaseTypes()
    {
        $caseTypeApiUrl = config('app.api.base_url') . '/case_type.php';
        $response = Http::get($caseTypeApiUrl);

        if ($response->failed()) {
            return response()->json(['error' => 'Unable to fetch case types.'], 500);
        }

        return response()->json($response->json());
    }
}
