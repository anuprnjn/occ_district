<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Mews\Captcha\Facades\Captcha;

class HCCaseTypeController extends Controller
{
    public function showCases()
    {
        $caseTypeResponse = Http::get(config('app.api.hc_base_url') . '/high_court_case_type.php');
        if ($caseTypeResponse->failed()) {
            \Log::error('Failed to fetch case types', $caseTypeResponse->json());
            $caseTypes = [];
        } else {
            \Log::info('Case types fetched:', $caseTypeResponse->json());
            $caseTypes = $caseTypeResponse->json();
        }

        return view('hcPage', compact('caseTypes'));
    }
 
}
