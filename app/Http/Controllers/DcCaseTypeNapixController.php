<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\Utility;
use Illuminate\Support\Facades\DB;

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
        
        // $parsedData = json_decode($decryptedData, true);
        // $flatCases = [];

        // foreach ($parsedData as $key => $value) {
        //     if (is_array($value) && isset($value['case_type'], $value['type_name'])) {
        //         $flatCases[] = [
        //             'case_type' => $value['case_type'],
        //             'type_name' => $value['type_name']
        //         ];
        //     }
        // }

        // // Now rebuild parsedData in expected format
        // $payload = [
        //     'est_code' => $dc_est_code,
        //     'cases' => $flatCases,
        // ];

        // $baseUrl = config('app.api.base_url');
        // \Log::info('Payload to API:', $payload);

        // try {
        //     $response = Http::post($baseUrl . '/update_district_court_case_master.php', $payload);
        //     $baseUrl = config('app.api.base_url');

        //     if ($response->successful()) {
        //         return response()->json([
        //             'success' => true,
        //             'message' => 'Application registered successfully!',
        //             'data' => $response->json(),
        //         ]);
        //     } else {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Failed to register application.',
        //             'error' => $response->body(),
        //         ], $response->status());
        //     }
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Error while connecting to external API.',
        //         'error' => $e->getMessage(),
        //     ], 500);
        // }
        return response()->json([
            'status' => true,
            'data' => json_decode($decryptedData, true)
        ]);
    }

    public function fetchDcCaseType(Request $request)
    {
        try {
            $dc_est_code = $request->input('est_code');
    
            // Validate input
            if (!$dc_est_code) {
                return response()->json(['success' => false, 'error' => 'est_code is required'], 400);
            }
    
            // Fetch data from the database
            $caseTypes = DB::table('district_court_case_master')
                        ->select('case_type', 'type_name')
                        ->where('est_code', $dc_est_code)
                        ->get();
    
            return response()->json(['success' => true, 'data' => $caseTypes]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong while fetching case types.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
}