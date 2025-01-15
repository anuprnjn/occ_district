<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class DistrictController extends Controller
{
    public function index()
    {
        // Get the base URL from the config or .env file
        $baseUrl = config('app.api.base_url'); // Using config for better maintainability
        $apiUrl = $baseUrl . '/district_dropdown.php';

        // Fetch data from the API
        $response = Http::get($apiUrl);

        if ($response->failed()) {
            \Log::error('Failed to fetch districts: ' . $response->body());
            return back()->withErrors(['message' => 'Unable to fetch districts.']);
        }

        // Decode the JSON response
        $districts = $response->json(); // This should return an array of districts

        // Pass the districts data to the view
        return view('index', compact('districts'));
    }
}
