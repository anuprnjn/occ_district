<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Gregwar\Captcha\CaptchaBuilder;

class LoginController extends Controller
{
    public function getLoginCaptcha(){
        // Start session if not already started
        if (!Session::isStarted()) {
            Session::start();
        }
    
        // Generate a simple math equation
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $mathEquation = "{$num1} + {$num2}";
    
        // Store the correct answer in session
        Session::put('captcha', $num1 + $num2);
    
        // Create CAPTCHA image with math equation
        $builder = new CaptchaBuilder($mathEquation);
        $builder->build(150, 48);
    
        // Get the CAPTCHA image as inline
        $captcha = $builder->inline();
    
        // Return the CAPTCHA image as a JSON response
        return response()->json([
            'captcha_src' => $captcha,
        ]);
    }
}
