<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Mews\Captcha\Facades\Captcha;
use Gregwar\Captcha\CaptchaBuilder;
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
    
        // Generate the CAPTCHA image
        $captcha = $this->generateCaptchaImage($mathEquation);
    
        return view('hcPage', compact('caseTypes', 'captcha'));
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
 
}
