<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\Utility;
use Illuminate\Support\Facades\Validator;

class JudgementController extends Controller
{
    public function fetchJudgementData(Request $request)
    {
        $search_type = $request->input('search_type');
        $selectedCaseTypeHc = $request->input('selectedCaseTypeHc');
        $reg_no = $request->input('HcCaseNo');
        $reg_year = $request->input('HcCaseYear');
        $fil_no = $request->input('HcFillingNo');
        $fil_year = $request->input('HcFillingYear');

        // Prepare request payload based on search type
        if ($search_type === 'case') {
            $apiData = [
                'search_type' => 'case',
                'reg_no' => $reg_no,
                'reg_year' => $reg_year,
                'regcase_type' => $selectedCaseTypeHc,
            ];
        } elseif ($search_type === 'filling') {
            $apiData = [
                'search_type' => 'filling',
                'fil_no' => $fil_no,
                'fil_year' => $fil_year,
                'filcase_type' => $selectedCaseTypeHc,
            ];
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Invalid search type'], 400);
        }

        // Call the external API
        $apiUrl =  config('app.api.hc_order_copy_base_url') . '/case_search.php';
        $response = Http::post($apiUrl, $apiData);
        
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Failed to get the case details'], 500);
        }
        
    }

    // public function fetchCaseDetailsNapixHcOrderCopy(Request $request)
    // {

    //     $hc_est_code = env('HC_EST_CODE');

    //     //$requestData = $request->input('request_data');
    //     $search_type = $request->input('search_type');
    //     $case_type = $request->input('selectedCaseTypeHc');
    //     //$request->input('HcCaseNo');
    //     $reg_no = $request->input('HcCaseNo') ?? '';
    //     $reg_year = $request->input('HcCaseYear') ?? '';
    //     $fil_no = $request->input('HcFillingNo') ?? '';
    //     $fil_year = $request->input('HcFillingYear') ?? '';
    
    //     // === Static credentials ===
    //     $dept_id = env('DEPT_ID');
    //     $version = env('VERSION');
    //     $hmac_secret = env('NAPIX_HMAC_SECRET');
    //     $aes_key = env('NAPIX_AES_KEY');
    //     $iv = env('NAPIX_AES_IV');
    
    //     // === Generate access token ===
    //     $apikey = env('NAPIX_API_KEY');
    //     $secret_key = env('NAPIX_SECRET_KEY');
    //     $basicAuth = base64_encode($apikey . ':' . $secret_key);
    
    //     $tokenResponse = Utility::getNapixAccessToken($basicAuth);
    //     $tokenData = json_decode($tokenResponse, true);
    
    //     if (!isset($tokenData['access_token'])) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Failed to fetch access token',
    //             'data' => $tokenData
    //         ], 500);
    //     }
    
    //     $accessToken = $tokenData['access_token'];
    
    //     // === Prepare request string ===
    //     if($search_type === "filling"){
    //         $input_str = "est_code={$hc_est_code}|case_type={$case_type}|fil_no={$fil_no}|fil_year={$fil_year}";
    //     }else{
    //         $input_str = "est_code={$hc_est_code}|case_type={$case_type}|reg_no={$reg_no}|reg_year={$reg_year}";
    //     }

    //     $request_token = hash_hmac('sha256', $input_str, $hmac_secret);

    //     $encrypted_str = Utility::encryptString($input_str, $aes_key, $iv);
    //     $request_str = urlencode($encrypted_str);
    
    //     // === Call the API ===
    //     if($search_type === "filling"){

    //         $url = "https://delhigw.napix.gov.in/nic/ecourts/hc-filing-api/Filing?dept_id={$dept_id}&request_str={$request_str}&request_token={$request_token}&version={$version}";
    
    //     }else{
    //         $url = "https://delhigw.napix.gov.in/nic/ecourts/hc-case-search-api/casesearch?dept_id={$dept_id}&request_str={$request_str}&request_token={$request_token}&version={$version}";
    
    //     }
        
    //     $response = Utility::makeNapixApiCall($url, $accessToken);

    
    //     $responseArray = json_decode($response, true);

    
    //     if (!isset($responseArray['response_str'])) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Invalid response from NAPIX API',
    //             'data' => $responseArray
    //         ], 500);
    //     }
    
    //     $decryptedData = Utility::decryptString($responseArray['response_str'], $aes_key, $iv);

    //     return response()->json([
    //         'status' => true,
    //         'search_type' => $search_type,
    //         'case_type' => $case_type,
    //         'data' => json_decode($decryptedData, true)
    //     ]);
    // } 
    public function fetchCaseDetailsNapixHcOrderCopy(Request $request)
    {
        // === Step 1: Validate Input ===
        $request->merge([
        'HcCaseNo' => $request->input('HcCaseNo') ?: null,
        'HcCaseYear' => $request->input('HcCaseYear') ?: null,
        'HcFillingNo' => $request->input('HcFillingNo') ?: null,
        'HcFillingYear' => $request->input('HcFillingYear') ?: null,
        ]);

        // Now validate
        $validator = Validator::make($request->all(), [
            'HcCaseNo' => 'sometimes|nullable|numeric',
            'HcCaseYear' => 'sometimes|nullable|numeric',
            'HcFillingNo' => 'sometimes|nullable|numeric',
            'HcFillingYear' => 'sometimes|nullable|numeric',
        ], [
            'HcCaseNo.numeric' => 'Please enter correct case number',
            'HcCaseYear.numeric' => 'Please enter correct case year',
            'HcFillingNo.numeric' => 'Please enter correct filing number',
            'HcFillingYear.numeric' => 'Please enter correct filing year',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(), // Better message
                'errors' => $validator->errors(),
            ], 422);
        }

        // === Step 2: Prepare Input Values ===
        $hc_est_code = env('HC_EST_CODE');
        $search_type = $request->input('search_type');
        $case_type = $request->input('selectedCaseTypeHc');
        $reg_no = $request->input('HcCaseNo') ?? '';
        $reg_year = $request->input('HcCaseYear') ?? '';
        $fil_no = $request->input('HcFillingNo') ?? '';
        $fil_year = $request->input('HcFillingYear') ?? '';

        // === Step 3: Static credentials ===
        $dept_id = env('DEPT_ID');
        $version = env('VERSION');
        $hmac_secret = env('NAPIX_HMAC_SECRET');
        $aes_key = env('NAPIX_AES_KEY');
        $iv = env('NAPIX_AES_IV');

        // === Step 4: Generate access token ===
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

        // === Step 5: Build input string ===
        if ($search_type === "filling") {
            $input_str = "est_code={$hc_est_code}|case_type={$case_type}|fil_no={$fil_no}|fil_year={$fil_year}";
        } else {
            $input_str = "est_code={$hc_est_code}|case_type={$case_type}|reg_no={$reg_no}|reg_year={$reg_year}";
        }

        $request_token = hash_hmac('sha256', $input_str, $hmac_secret);
        $encrypted_str = Utility::encryptString($input_str, $aes_key, $iv);
        $request_str = urlencode($encrypted_str);

        // === Step 6: Build API URL ===
        if ($search_type === "filling") {
            $url = "https://delhigw.napix.gov.in/nic/ecourts/hc-filing-api/Filing?dept_id={$dept_id}&request_str={$request_str}&request_token={$request_token}&version={$version}";
        } else {
            $url = "https://delhigw.napix.gov.in/nic/ecourts/hc-case-search-api/casesearch?dept_id={$dept_id}&request_str={$request_str}&request_token={$request_token}&version={$version}";
        }

        // === Step 7: Call NAPIX API ===
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

        // === Step 8: Return Final Response ===
        return response()->json([
            'status' => true,
            'search_type' => $search_type,
            'case_type' => $case_type,
            'data' => json_decode($decryptedData, true)
        ]);
    }
    
    public function fetchCaseDetailsOrderDetailsNapixHc(Request $request)
    {
        $requestData = $request->input('request_data');
        $Hc_cino = $requestData['cino'];
    
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
        $input_str = "cino={$Hc_cino}";

        $request_token = hash_hmac('sha256', $input_str, $hmac_secret);

        $encrypted_str = Utility::encryptString($input_str, $aes_key, $iv);
        $request_str = urlencode($encrypted_str);

        $url = "https://delhigw.napix.gov.in/nic/ecourts/hc-cnr-api/CNR?dept_id={$dept_id}&request_str={$request_str}&request_token={$request_token}&version={$version}";
     
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
        // dd($decryptedData);
        // exit();
       
        return response()->json([
            'status' => true,
            'data' => json_decode($decryptedData, true)
        ]);
    }    
}