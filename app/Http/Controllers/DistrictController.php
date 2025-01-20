<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DistrictController extends Controller
{
    public function showDistricts()
    {
         $response = Http::get('http://192.168.137.48/occ_api/district_dropdown.php');
         $response_case = Http::get('http://192.168.137.48/occ_api/case_type.php');

         if ($response->successful()) {
             $districts = $response->json();  
         } else {
             $districts = [];  
         }
         if ($response_case->successful()) {
            $caseTypes = $response_case->json(); 
        } else {
            $caseTypes = [];  
        }

         return view('dcPage', compact('districts','caseTypes'));
    }
    
    public function getEstablishments(Request $request)
    {
        \Log::info('Request data:', $request->all());
    
        $response = Http::asForm()->post(config('app.api.base_url') . '/establishment.php', [
            'dist_code' => $request->dist_code,
        ]);
    
        if ($response->failed()) {
            \Log::error('Failed to fetch establishments', $response->json());
            return response()->json(['error' => 'Unable to fetch establishments.'], 500);
        }
    
        \Log::info('Establishments fetched:', $response->json());
        return response()->json($response->json());
    }
 
}
