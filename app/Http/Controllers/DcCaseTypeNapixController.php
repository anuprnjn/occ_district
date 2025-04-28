<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $basicAuth = base64_encode($apiKey . ':' . $secretKey);
    
        $tokenResponse = $this->getNapixAccessToken($basicAuth);
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
        $encrypted_str = $this->encryptString($input_str, $aes_key, $iv);
        $request_str = urlencode($encrypted_str);
    
        // === Call the API ===
        $url = "https://delhigw.napix.gov.in/nic/ecourts/dc-casetype-master-api/casetypeMaster?dept_id={$dept_id}&request_str={$request_str}&request_token={$request_token}&version={$version}";
    
        $response = $this->makeNapixApiCall($url, $accessToken);
    
        $responseArray = json_decode($response, true);
    
        if (!isset($responseArray['response_str'])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid response from NAPIX API',
                'data' => $responseArray
            ], 500);
        }
    
        $decryptedData = $this->decryptString($responseArray['response_str'], $aes_key, $iv);
    
        return response()->json([
            'status' => true,
            'data' => json_decode($decryptedData, true)
        ]);
    }
    
    private function getNapixAccessToken($basicAuth)
    {
        $url = env('NAPIX_TOKEN_URL');
        $postFields = "grant_type=client_credentials&scope=napix";
    
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic {$basicAuth}",
                "Content-Type: application/x-www-form-urlencoded"
            ],
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        dd($response);
        exit();
        return $response;
    }
    
    private function makeNapixApiCall($url, $accessToken)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$accessToken}",
            ],
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
    
        return $response;
    }
    
    private function encryptString($string, $key, $iv)
    {
        $encrypted = openssl_encrypt($string, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($encrypted);
    }
    
    private function decryptString($encrypted_base64, $key, $iv)
    {
        $encryptedData = base64_decode($encrypted_base64);
        return openssl_decrypt($encryptedData, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }
}