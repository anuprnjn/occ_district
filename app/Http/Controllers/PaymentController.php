<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\clsEncrypt; 
use Illuminate\Support\Facades\Session;


class PaymentController extends Controller
{
    public function fetchMerchantDetails(Request $request)
    {
        // $depositerId = 'DRID' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $depositerId = "DRID001";
        $response = Http::get(config('app.api.admin_url') . '/fetch_jegras_merchent_details.php');

        if (!$response->successful()) {
            return response()->json(['error' => 'Unable to fetch merchant details'], 500);
        }

        $merchantDetails = $response->json();
        $userData = $request->input('userData');
        $urgent_fee = Session::get('urgent_fee');
        $paybleAmount = $request->input('paybleAmount');
        $applicationNumber = $request->input('applicationNumber');
        $transaction_no = $merchantDetails[0]['TransactionNumber'];
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
            $userData['name'] ?? $userData['applicant_name'] ?? '', 
            $transaction_no,
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
            'depositername' => $userData['name'] ?? $userData['applicant_name'] ?? '',
            'depttranid' => $transaction_no ?? '',
            'depositerid'=> $depositerId ?? '',
            'amount' => $paybleAmount ?? '',
            'panno' => $PAN ?? '',
            'urgent_fee' => $urgent_fee ?? "5.00" ?? '',
            'addinfo1' => $ADDINFO1 ?? '',
            'addinfo2' => $ADDINFO2 ?? '',
            'addinfo3' => $ADDINFO3 ?? '',
            'treascode' => $merchantDetails[0]['treascode'] ?? '',
            'ifmsofficecode' => $merchantDetails[0]['ifmsofficecode'] ?? '',
            'securitycode' => $merchantDetails[0]['securitycode'] ?? '',
            'response_url' => $responseURL ?? ''
        ];
        dd($entryData);
        exit();
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
    
}