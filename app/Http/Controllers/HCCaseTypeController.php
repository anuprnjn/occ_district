<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Mews\Captcha\Facades\Captcha;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Session;
use App\Helpers\Utility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class HCCaseTypeController extends Controller
{
    public function showCases()
    {
        // Start session if not already started
        if (!Session::isStarted()) {
            Session::start();
        }

        $caseTypes = DB::table('high_court_case_master')->get()->map(function ($item) {
            return (array) $item;
        })->toArray();
         
    
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

    // public function updateCaseTypeMasterNapix()
    // {
    //     $caseTypesNapix = $this->fetchNapixHcCaseType();
    //     // Fallback to empty array if failed
    //     $caseTypesNapix = $caseTypesNapix ?? [];

    //      // Get the API base URL from the config
    //       $baseUrl = config('app.api.hc_base_url');

    //         try {
    //             $response = Http::post($baseUrl . '/update_high_court_case_master.php', $caseTypes);

    //             if ($response->successful()) {
    //                 return response()->json([
    //                     'success' => true,
    //                     'message' => 'Application registered successfully!',
    //                     'data' => $response->json(),
    //                 ]);
    //             } else {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Failed to register application.',
    //                     'error' => $response->body(),
    //                 ], $response->status());
    //             }
    //         } catch (\Exception $e) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Error while connecting to external API.',
    //                 'error' => $e->getMessage(),
    //             ], 500);
    //         }

    // }
    public function updateCaseTypeMasterNapix()
    {
        $result = $this->syncCaseTypesFromApi();

        return response()->json([
            'success' => true,
            'message' => "Update complete. New records inserted: {$result['inserted']}",
        ]);
    }
    // public function updateCaseTypeMasterNapix()
    // {
    //     $caseTypesNapix = $this->fetchNapixHcCaseType();
    //     $caseTypesNapix = $caseTypesNapix ?? [];

    //     $insertedCount = 0;

    //     foreach ($caseTypesNapix as $napixCaseType) {
    //         // Check if the case type already exists
    //         $exists = DB::table('high_court_case_master')->where('type_code', $napixCaseType['type_code'])->exists();

    //         if (!$exists) {
    //             DB::table('high_court_case_master')->insert([
    //                 'type_code' => $napixCaseType['type_code'],
    //                 'type_name' => $napixCaseType['type_name'],
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);
    //             $insertedCount++;
    //         }
    //     }

    //     \Log::info("Napix Scheduler: Added {$insertedCount} new case types to the database.");

    //     return response()->json([
    //         'success' => true,
    //         'message' => "Update complete. New records inserted: {$insertedCount}",
    //     ]);
    // }
    public function syncCaseTypesFromApi()
    {
        $caseTypesNapix = $this->fetchNapixHcCaseType();
        $caseTypesNapix = $caseTypesNapix ?? [];
    
        $insertedCount = 0;
    
        foreach ($caseTypesNapix as $napixCaseType) {
            // Skip if essential keys are missing
            if (!isset($napixCaseType['case_type']) || !isset($napixCaseType['type_name'])) {
                Log::warning('[Scheduler] Skipped invalid case type record: ' . json_encode($napixCaseType));
                continue;
            }
    
            $exists = DB::table('high_court_case_master')
                ->where('case_type', $napixCaseType['case_type'])
                ->exists();
    
            if (!$exists) {
                DB::table('high_court_case_master')->insert([
                    'case_type'  => $napixCaseType['case_type'],
                    'type_name'  => $napixCaseType['type_name'],
                ]);
                $insertedCount++;
            }
        }
    
        Log::info("Napix Scheduler: Added {$insertedCount} new case types to the database.");
    
        return [
            'inserted' => $insertedCount,
            'data'     => $caseTypesNapix
        ];
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
