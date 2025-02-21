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

    // Generate a simple math equation
    $num1 = rand(1, 9);
    $num2 = rand(1, 9);
    $mathEquation = "{$num1} + {$num2}";

    // Store the correct answer in session
    Session::put('captcha', $num1 + $num2);

    // Create CAPTCHA image with math equation
    $builder = new CaptchaBuilder($mathEquation);
    $builder->build(150, 48);

    // Pass CAPTCHA image directly to the view
    $captcha = $builder->inline();

    return view('hcPage', compact('caseTypes', 'captcha'));
}
 
}
