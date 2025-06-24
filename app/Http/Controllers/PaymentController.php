<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\clsEncrypt; 
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{
    public function fetchMerchantDetails(Request $request)
    {
        // $depositerId = 'DRID' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $depositerId = env('DEPOSITERID');;
        $response = Http::get(config('app.api.admin_url') . '/fetch_jegras_merchent_details.php');

        if (!$response->successful()) {
            return response()->json(['error' => 'Unable to fetch merchant details'], 500);
        }

        $Dc_userName = $request->input('Dc_userName');
        $Dc_application_number = $request->input('Dc_application_number');
        $district_code = $request->input('district_code', null);
        $establishment_code = $request->input('establishment_code', null);
        $Dc_totalAmount = $request->input('Dc_totalAmount');

        $merchantDetails = $response->json();
        $userData = $request->input('userData');
        $paybleAmount = $request->input('paybleAmount');
        
        if (!empty($paybleAmount)) {
            $urgent_fee = Session::get('hc_final_amount_summary')['urgent_fee'] ?? "0";
        } elseif (!empty($Dc_totalAmount)) {
            $urgent_fee = Session::get('dc_final_amount_summary')['urgent_fee'] ?? "0";
        } else {
            $urgent_fee = "0";
        }
        $applicationNumber = $request->input('applicationNumber');
        $transaction_no = $merchantDetails[0]['TransactionNumber'];
        $PAN = 'N/A';
        $ADDINFO1 = $applicationNumber;
        $ADDINFO2 = isset($userData["payment_status"]) 
            ? ($userData["payment_status"] == "1" ? "deficit" : "normal") 
            : "normal";
        $ADDINFO3 = 'N/A';
        $responseURL = env('PAY_RESPONSE_URL');
        $key = env('VERIFICATION_KEY');

        // Constructing `Requestparameter` string
        $Requestparameter = implode('|', [
            $merchantDetails[0]['deptid'],
            $merchantDetails[0]['recieptheadcode'],
            $userData['name'] ?? $userData['applicant_name'] ?? $Dc_userName ?? '', 
            $transaction_no,
            $paybleAmount ?? $Dc_totalAmount,
            $depositerId,
            $PAN,
            $ADDINFO1,
            $ADDINFO2,
            $ADDINFO3,
            $merchantDetails[0]['treascode'],
            $merchantDetails[0]['ifmsofficecode'],
            $merchantDetails[0]['securitycode']
        ]);
        // Encrypt Requestparameter
        $enc = new clsEncrypt();
        $enc_val = $enc->Encrypt($Requestparameter, $key);

        // Prepare data for entryPayDetails API
        $entryData = [
            'deptid' => $merchantDetails[0]['deptid'] ?? '',
            'application_number' => $applicationNumber ?? $Dc_application_number, 
            'recieptheadcode' => $merchantDetails[0]['recieptheadcode'] ?? '',
            'depositername' => $userData['name'] ?? $userData['applicant_name'] ?? $Dc_userName ?? '',
            'depttranid' => $transaction_no ?? '',
            'depositerid'=> $depositerId ?? '',
            'amount' => $paybleAmount ?? $Dc_totalAmount ?? '',
            'panno' => $PAN ?? '',
            'urgent_fee' => $urgent_fee ?? '',
            'addinfo1' => $ADDINFO1 ?? '',
            'addinfo2' => $ADDINFO2 ?? '',
            'addinfo3' => $ADDINFO3 ?? '',
            'treascode' => $merchantDetails[0]['treascode'] ?? '',
            'ifmsofficecode' => $merchantDetails[0]['ifmsofficecode'] ?? '',
            'securitycode' => $merchantDetails[0]['securitycode'] ?? '',
            'response_url' => $responseURL ?? '',
            'district_code' => $district_code ?? '',
            'establishment_code' => $establishment_code ?? ''
        ];
       
        // Send data to entryPayDetails API
        if (str_starts_with($applicationNumber, 'HC')) {
            $entryResponse = Http::post(config('app.api.transaction_url') . '/jegras_payment_request.php', $entryData);
        }else{
            $entryResponse = Http::post(config('app.api.transaction_url') . '/jegras_payment_request_dc.php', $entryData);
        }

        // Check if entry API request was successful
        if (!$entryResponse->successful()) {
            return response()->json(['error' => 'Failed to store payment details'], 500);
        }

        $entryResponseData = $entryResponse->json();

        // Check if the response contains an application number
        if (!isset($entryResponseData['application_number'])) {
            return response()->json(['error' => 'Application number missing in API response'], 500);
        }

        $applicationNumber = $entryResponseData['application_number'];
        $district_code = $entryResponseData['district_code'] ?? '';
        $establishment_code = $entryResponseData['establishment_code'] ?? '';

        // Return JSON response to frontend
        return response()->json([
            'enc_val' => $enc_val,
            'application_number' => $applicationNumber,
            'district_code' => $district_code,
            'establishment_code' => $establishment_code,
        ]);
    }


    public function doubleVerification(Request $request)
    {
        $depid        = $request->input('depid');          
        $depttranid   = $request->input('depttranid');     
        $securitycode = $request->input('securitycode');   
        $grn          = $request->input('grn', '');        

        $secretKey = env('DOUBLE_VERIFICATION_SECRET'); 

        // Ensure key and IV are exactly 16 bytes
        $key = str_pad(substr($secretKey, 0, 16), 16, "\0");
        $iv  = str_pad(substr($secretKey, 0, 16), 16, "\0");

        // Step 1: Build plain string
        $plain = implode('|', [$grn, $depid, $depttranid, $securitycode]);

        // Step 2: Encrypt using AES-128-CBC
        $cipher = 'AES-128-CBC';
        $encrypted = openssl_encrypt($plain, $cipher, $key, 0, $iv);

        // Step 3: Build request data
        $requestData = [
            'EncryptTxt' => $encrypted,
            'REQDEPTID'  => $depid,
        ];

        // Step 4: Send JSON POST request
        $url = env('DOUBLE_VERIFICATION_URL');
        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n" .
                            "Accept: application/json\r\n",
                'content' => json_encode($requestData),
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ];
        $context = stream_context_create($options);
        $resultJson = file_get_contents($url, false, $context);

        // Step 5: Decode and decrypt response
        $resultArray = json_decode($resultJson, true);
        $encryptedResponse = $resultArray['SBIePayDoubleVerificationResult'] ?? null;

        $decryptedOutput = null;
        $parsedFields = [];

        if ($encryptedResponse) {
            // Some APIs return base64 encoded ciphertext
            $decryptedOutput = openssl_decrypt(base64_decode($encryptedResponse), $cipher, $key, OPENSSL_RAW_DATA, $iv);

            if ($decryptedOutput !== false) {
                $parsedFields = explode('|', $decryptedOutput);
            }
        }

        // Final output
        return response()->json([
            'success'          => $decryptedOutput !== false,
            'decrypted_data'    => $parsedFields,
        ]);
    }


    public function verifyJegrasPayment(Request $request)
    {
        $payload = $request->all();

        // Log incoming request payload
        Log::info('Incoming Jegras DV Payload:', $payload);

        $applicationNumber = $payload['APPLICATION_NUMBER'] ?? '';

        // Determine the correct API URL
        $apiUrl = str_starts_with($applicationNumber, 'HC')
            ? 'http://localhost/occ_api/transaction/jegras_dv_payment_response_hc.php'
            : 'http://localhost/occ_api/transaction/jegras_dv_payment_response_dc.php';

        Log::info('Selected API URL: ' . $apiUrl);

        try {
        Log::info('Original Payload:', $payload);

        $normalizedPayload = array_change_key_case($payload, CASE_LOWER);
        Log::info('Normalized Payload:', $normalizedPayload);

        $response = Http::post($apiUrl, $normalizedPayload);

        Log::info('DV API Response Status: ' . $response->status());
        Log::info('DV API Response Body: ' . $response->body());

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'data' => $payload,
            ]);
        }

        Log::error('Jegras DV API call failed. Status: ' . $response->status());

        return response()->json([
            'success' => false,
            'message' => 'API call failed',
            'status' => $response->status(),
            'response_body' => $response->body()
        ], 500);

    } catch (\Exception $e) {
        Log::error('Exception during Jegras DV API call: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Exception: ' . $e->getMessage(),
        ], 500);
    }
    }

    
}