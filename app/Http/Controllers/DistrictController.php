<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class DistrictController extends Controller
{
    public function index()
    {
        // Fetch data from the API
        $response = Http::get('http://localhost/occ_api/dropdown.php');

        if ($response->failed()) {
            // If the API call fails, log the error and return a meaningful response
            \Log::error('Failed to fetch districts: ' . $response->body());
            return back()->withErrors(['message' => 'Unable to fetch districts.']);
        }

        // Decode the JSON response
        $districts = $response->json(); // This should return an array of districts

        // Pass the districts data to the view
        return view('index', compact('districts'));
    }
}