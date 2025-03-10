<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\clsEncrypt; 
class PaymentController extends Controller
{
    public function fetchMerchantDetails(Request $request)
    {
        $departmentId = '';
        for ($i = 0; $i < 19; $i++) {
            $departmentId .= mt_rand(0, 9);
        }

        // $depositerId = 'DRID' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $depositerId = "DRID001";
        $response = Http::get(config('app.api.admin_url') . '/fetch_jegras_merchent_details.php');

        if (!$response->successful()) {
            return response()->json(['error' => 'Unable to fetch merchant details'], 500);
        }

        $merchantDetails = $response->json();
        $userData = $request->input('userData');
        $paybleAmount = $request->input('paybleAmount');
        $applicationNumber = $request->input('applicationNumber');

        $PAN = 'N/A';
        $ADDINFO1 = $applicationNumber;
        $ADDINFO2 = 'N/A';
        $ADDINFO3 = 'N/A';
        $responseURL = 'http://10.134.9.45/api/occ/gras_resp_cc';
        $key = 'Ky@5432#';

        // Constructing `Requestparameter` string
        $Requestparameter = implode('|', [
            $merchantDetails[0]['deptid'],
            $merchantDetails[0]['recieptheadcode'],
            $userData['name'],
            $departmentId,
            $paybleAmount,
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
            'application_number' => $applicationNumber, 
            'recieptheadcode' => $merchantDetails[0]['recieptheadcode'] ?? '',
            'depositername' => $userData['name'] ?? '',
            'depttranid' => $departmentId ?? '',
            'depositerid'=> $depositerId ?? '',
            'amount' => $paybleAmount ?? '',
            'panno' => $PAN ?? '',
            'addinfo1' => $ADDINFO1 ?? '',
            'addinfo2' => $ADDINFO2 ?? '',
            'addinfo3' => $ADDINFO3 ?? '',
            'treascode' => $merchantDetails[0]['treascode'] ?? '',
            'ifmsofficecode' => $merchantDetails[0]['ifmsofficecode'] ?? '',
            'securitycode' => $merchantDetails[0]['securitycode'] ?? '',
            'response_url' => $responseURL ?? ''
        ];
        // Send data to entryPayDetails API
        $entryResponse = Http::post(config('app.api.transaction_url') . '/jegras_payment_request.php', $entryData);

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

        // Return JSON response to frontend
        return response()->json([
            'enc_val' => $enc_val,
            'application_number' => $applicationNumber
        ]);
    }
    // public function testEncryption()
    // {
    //     $key = 'key1234'; // Your test key (should be at least 16 characters)
    //     $data = "COMTAX|003900800020101|ABC CONSTRUCTION|1234567891234567891|2|DRID001|NA|NA|NA|NA|PRJ|PRJFIN001|sec1234";
    
    //     $enc = new clsEncrypt();
    //     $encrypted = $enc->encrypt($data, $key);
    //     $decrypted = $enc->decrypt($encrypted, $key);
    //     dd($decrypted);
    //     exit();
    //     return response()->json([
    //         'original' => $data,
    //         'encrypted' => $encrypted,
    //         'decrypted' => $decrypted
    //     ]);
    // }
}