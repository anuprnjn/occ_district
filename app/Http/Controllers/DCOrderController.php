<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DCOrderController extends Controller
{
    public function submit(Request $request)
    {
        // Send data to external API
        $response = Http::post('http://localhost/occ_api/district_court_order_copy/dc_order_copy_applicant_registration.php', $request->all());

        // Return the response to frontend
        return response()->json($response->json());
    }
}