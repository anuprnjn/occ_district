<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class CaptchaController extends Controller
{
    public function refreshCaptcha()
    {
        // Generate two random numbers for the CAPTCHA
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $mathEquation = "{$num1} + {$num2}";

        // Store the correct answer in the session
        Session::put('captcha', $num1 + $num2);

        // Generate CAPTCHA image
        $captchaSrc = $this->generateCaptchaImage($mathEquation);

        // Return the CAPTCHA image as a JSON response
        return response()->json(['captcha_src' => $captchaSrc]);
    }

    // Helper function to generate CAPTCHA image
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

    public function validateCaptcha(Request $request)
    {
        // Get the stored CAPTCHA from session
        $storedCaptcha = Session::get('captcha');

        // Check if CAPTCHA exists in session
        if ($storedCaptcha === null) {
            return response()->json([
                'success' => false,
                'message' => 'CAPTCHA expired. Please refresh and try again.',
            ], 400);
        }

        // Validate the CAPTCHA input
        $validator = Validator::make($request->all(), [
            'captcha' => [
                'required',
                'numeric', // Ensure the input is a number
                function ($attribute, $value, $fail) use ($storedCaptcha) {
                    // Check if the input matches the stored CAPTCHA
                    if ((int)$value !== (int)$storedCaptcha) {
                        $fail('Invalid CAPTCHA');
                    }
                }
            ],
        ]);

        // If validation fails, return an error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid CAPTCHA. Please try again.',
            ], 422);
        }

        // Clear CAPTCHA from session after successful validation
        Session::forget('captcha');

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'CAPTCHA validation successful.',
        ]);
    }
}