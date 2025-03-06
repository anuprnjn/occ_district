<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Gregwar\Captcha\CaptchaBuilder;

class LoginController extends Controller
{
    public function getLoginCaptcha()
    {
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
    
        // Generate CAPTCHA image
        $captchaImage = $this->generateCaptchaImage($mathEquation);
    
        // Return the CAPTCHA image as a JSON response
        return response()->json([
            'captcha_src' => $captchaImage,
        ]);
    }
    private function generateCaptchaImage($text)
    {
        $width = 150;
        $height = 50;
    
        // Create an image canvas
        $image = imagecreatetruecolor($width, $height);
    
        // Define colors
        $backgroundColor = imagecolorallocate($image, 255, 255, 255); // White
        $textColor = imagecolorallocate($image, 0, 0, 0); // Black
        $lineColor = imagecolorallocate($image, 200, 200, 200); // Gray (for noise)
        
        // Fill the background
        imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColor);
    
        // Add some noise lines
        for ($i = 0; $i < 5; $i++) {
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
        }
    
        // Add the text to the image
        $fontSize = 20; // Built-in font size
        $textX = rand(20, 40);
        $textY = rand(10, 20);
        imagestring($image, $fontSize, $textX, $textY, $text, $textColor);
    
        // Start output buffering
        ob_start();
        imagepng($image); // Output image as PNG
        $imageData = ob_get_contents();
        ob_end_clean();
    
        // Destroy image
        imagedestroy($image);
    
        // Return base64-encoded image
        return 'data:image/png;base64,' . base64_encode($imageData);
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
            if($request->courtType=="HC"){
                $response = Http::post(config('app.api.admin_url') .'/login.php', $credentials);
            }
            else if($request->courtType=="DC"){
                $response = Http::post(config('app.api.admin_url') .'/login_dc.php', $credentials);
            }
           


            $data = $response->json();

            // Log response for debugging
            Log::info('Login API Response:', $data);

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
                    "redirect" => url('/admin/index') 
                ]);
            }

            // Handle unsuccessful login (invalid credentials)
            return response()->json([
                "success" => false,
                "message" => $data['error'] ?? "Invalid credentials. Please try again."
            ], 401);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Login API Error: ' . $e->getMessage());

            return response()->json([
                "success" => false,
                "message" => "Something went wrong. Please try again."
            ], 500);
        }
    }
}
