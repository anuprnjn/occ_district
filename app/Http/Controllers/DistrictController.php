<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Mews\Captcha\Facades\Captcha;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class DistrictController extends Controller
{
    public function showDistricts()
    {

        // Fetch districts
        $districtResponse = Http::get(config('app.api.base_url') . '/district_dropdown.php');
        if ($districtResponse->failed()) {
            \Log::error('Failed to fetch districts', $districtResponse->json());
            $districts = [];
        } else {
            \Log::info('Districts fetched:', $districtResponse->json());
            $districts = $districtResponse->json();
        }

        // Fetch case types
        $caseTypeResponse = Http::get(config('app.api.base_url') . '/case_type.php');
        if ($caseTypeResponse->failed()) {
            \Log::error('Failed to fetch case types', $caseTypeResponse->json());
            $caseTypes = [];
        } else {
            \Log::info('Case types fetched:', $caseTypeResponse->json());
            $caseTypes = $caseTypeResponse->json();
        }

        // Generate the CAPTCHA using Gregwar Captcha
        $builder = new CaptchaBuilder();
        $builder->setPhrase(strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6))); // Generate a 6-character alphanumeric phrase
        $builder->build(150, 48); // Set width & height

        // Store CAPTCHA phrase in session
        Session::put('captcha', $builder->getPhrase());

        // Get CAPTCHA as inline image
        $captcha = $builder->inline();

        // Return data to the view
        return view('dcPage', compact('districts', 'caseTypes', 'captcha'));
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
