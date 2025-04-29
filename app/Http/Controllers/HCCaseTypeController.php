<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Mews\Captcha\Facades\Captcha;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Session;
use App\Helpers\Utility;

class HCCaseTypeController extends Controller
{
    public function showCases()
    {
        // Start session if not already started
        if (!Session::isStarted()) {
            Session::start();
        }
    
        $caseTypes = $this->fetchNapixHcCaseType();
        // Fallback to empty array if failed
        $caseTypes = $caseTypes ?? [];

    
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



    public function fetchNapixHcCaseType()
    {
        $hc_est_code = env('HC_EST_CODE');
    
        // === Static credentials ===
        $dept_id = env('DEPT_ID');
        $version = env('VERSION');
        $hmac_secret = env('NAPIX_HMAC_SECRET');
        $aes_key = env('NAPIX_AES_KEY');
        $iv = env('NAPIX_AES_IV');
    
        // === Generate access token ===
        $apikey = env('NAPIX_API_KEY');
        $secret_key = env('NAPIX_SECRET_KEY');
        $basicAuth = base64_encode($apikey . ':' . $secret_key);
    
        $tokenResponse = Utility::getNapixAccessToken($basicAuth);
        $tokenData = json_decode($tokenResponse, true);
    
        if (!isset($tokenData['access_token'])) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch access token',
                'data' => $tokenData
            ], 500);
        }
    
        $accessToken = $tokenData['access_token'];
    
        // === Prepare request string ===
        $input_str = "est_code={$hc_est_code}";
        $request_token = hash_hmac('sha256', $input_str, $hmac_secret);
        $encrypted_str = Utility::encryptString($input_str, $aes_key, $iv);
        $request_str = urlencode($encrypted_str);
    
        // === Call the API ===
        $url = "https://delhigw.napix.gov.in/nic/ecourts/hc-case-type-master-api/casetypemaster?dept_id={$dept_id}&request_str={$request_str}&request_token={$request_token}&version={$version}";
    
        $response = Utility::makeNapixApiCall($url, $accessToken);
        $responseArray = json_decode($response, true);
    
        if (!isset($responseArray['response_str'])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid response from NAPIX API',
                'data' => $responseArray
            ], 500);
        }
    
        $decryptedData = Utility::decryptString($responseArray['response_str'], $aes_key, $iv);
        
        $parsedData = json_decode($decryptedData, true);

        if (!is_array($parsedData)) {
            return [];
        }
        
        return array_values($parsedData); 
    }
 
}
