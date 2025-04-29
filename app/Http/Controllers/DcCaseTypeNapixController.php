<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\Utility;

class DcCaseTypeNapixController extends Controller
{
    public function fetchNapixDcCaseType(Request $request)
    {
        $dc_est_code = $request->input('est_code');
    
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
        $input_str = "est_code={$dc_est_code}";
        $request_token = hash_hmac('sha256', $input_str, $hmac_secret);
        $encrypted_str = Utility::encryptString($input_str, $aes_key, $iv);
        $request_str = urlencode($encrypted_str);
    
        // === Call the API ===
        $url = "https://delhigw.napix.gov.in/nic/ecourts/dc-casetype-master-api/casetypeMaster?dept_id={$dept_id}&request_str={$request_str}&request_token={$request_token}&version={$version}";
    
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
        
    
        return response()->json([
            'status' => true,
            'data' => json_decode($decryptedData, true)
        ]);
    }
    
}