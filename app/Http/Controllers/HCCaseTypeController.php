<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Mews\Captcha\Facades\Captcha;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class HCCaseTypeController extends Controller
{
    public function showCases()
    {
        // Start session if not already started
        if (!Session::isStarted()) {
            Session::start();
        }
    
        // Fetch case types
        $caseTypeResponse = Http::get(config('app.api.hc_base_url') . '/high_court_case_type.php');
        $caseTypes = $caseTypeResponse->successful() ? $caseTypeResponse->json() : [];
    
        // Generate a random alphanumeric CAPTCHA string (letters and numbers)
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $captchaPhrase = substr(str_shuffle($characters), 0, 6); // Generate 6-character random alphanumeric string
    
        // Create CAPTCHA image
        $builder = new CaptchaBuilder($captchaPhrase);
        $builder->build(150, 48); // Set width & height
    
        // Store only CAPTCHA phrase in session for validation
        Session::put('captcha', $captchaPhrase);
    
        // Pass CAPTCHA image directly to the view (do not store in session)
        $captcha = $builder->inline();
    
        return view('hcPage', compact('caseTypes', 'captcha'));
    }
 
}
