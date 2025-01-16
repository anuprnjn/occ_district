<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CaseTypeController extends Controller
{
    public function index()
    {
        try {
            // Fetch case types from the API
            $response = Http::get('http://192.168.137.48/occ_api/case_type.php');

            // Decode the JSON response into an array
            $caseTypes = $response->json();

            // Pass case types to the view
            return view('index', compact('caseTypes'));
        } catch (\Exception $e) {
            // Handle any errors (e.g., network issues)
            return view('index')->with('error', 'Unable to fetch case types. Please try again later.');
        }
    }
}
