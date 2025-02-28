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
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'courtType' => 'required|in:HC,DC', 
        ]);

        // Prepare credentials
        $credentials = [
            "username" => $request->username,
            "password" => $request->password,
            "caseType" => $request->courtType 
        ];

        try {
            // Call external API
            $response = Http::post("http://localhost/occ_api/admin/login.php", $credentials);
            $data = $response->json();

            // Log response for debugging
            \Log::info('Login API Response:', $data);

            // Check if API returned user data correctly
            if ($response->successful() && isset($data['user'])) {
                // Store user session
                Session::put('user', $data['user']);
                Session::put('logged_in', true);
                // dd(Session::all());
                // exit();
                // Return JSON response
                return response()->json([
                    "success" => true,
                    "message" => "Login successful!",
                    "redirect" => url('/admin') 
                ]);
            }

            // Handle unsuccessful login (invalid credentials)
            return response()->json([
                "success" => false,
                "message" => $data['error'] ?? "Invalid credentials. Please try again."
            ], 401);

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Login API Error: ' . $e->getMessage());

            return response()->json([
                "success" => false,
                "message" => "Something went wrong. Please try again."
            ], 500);
        }
    }
}
