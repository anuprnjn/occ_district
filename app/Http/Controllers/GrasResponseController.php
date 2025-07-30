<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Helpers\ClsEncrypt;

class GrasResponseController extends Controller
{

// public function handleResponse(Request $request)
//     {
//         $encryptionHelper = new clsEncrypt();

//         // test sec key for enc and dec 

//         // $secretKey = 'Ky@5432#';
        
//         // live test data for fail resp

//         $encTest = '/LafgEmh7gCGMPfS6EAhcksRvKMTFCKA5rogNcTcaeE12aCjoTxPabTBBsRKei+DiAceWeq1mqbSSxFqnilgwbvn5/LVdorRsUMH2jg7WusdyEEetEugAUABcP2n+cmk+wMsNRGrNuqZeMH2UuXF1b+GvI+kERWWZhpnae8z2jPQEpgdrySp8nVfVIRfbKK/dXHkYYNQdioOZ1KEyxakdzFKXlc0t9c7XnWouSojElcos3kh2psSqjF54IWmedAhb3y2jUBRvhD/OMGLfy1cS/lFCRHZThM0u/yCEQgsfDnCScpsDTbdom7nDerCgPBQuz/OXzB6L5YvJ1pQtQRFoHlUkyvDSoT46JP/WaLRGa5CZdqY0V/ytGMxcRDgXe8a';

//         // live sec key to enc and dec 
//         $secretKey = 'key1234';
        
//         // test data for success resp

//         // $encTest = 'kdUDf+/ZxxRnRGDotlk6x/2CsbIONnFDTwNIPjhSjjteczI4hC/C52WkSbgvDSss1Evdyh0GidObr2c1WTdWTts7UZv1Fs/uqFfbbgqa6X2ijLynIYilBTqx0u0zazotp67Chxhv7u9nXKhT7YQcNA7G3RJN49Ss151+bBe/pR7P3+zqwQpOBmruVLJqnxeFdWtZGgAuDox6RqbpbYrDhjpGFECiq+DtBUYZO21oknnZIDjbOBSs6DzE1l2VDB5uqJOgi1nZF1r0pC+V0xuzqvkjzcl0xzsJNTQ3J1aV/U4=';

//         $nullEnc = null;

//         // Check if encrypted text is provided
//         $encryptedText = $request->input('encryptedText', $encTest); 
//         // $encryptedText = $request->input('encryptedText', null); 

//         if (empty($encryptedText)) {
//             return redirect('/')->with('error', 'No encrypted text provided.');
//         }

//         // Decrypt the data
//         $decryptedData = $encryptionHelper->decrypt($encryptedText, $secretKey);

//         if ($decryptedData === false) {
//             Log::error('Decryption Failed');
//             return redirect('/')->with('error', 'Decryption failed.');
//         }

//         Log::info('Decrypted Data:', ['decryptedData' => $decryptedData]);

//         // exit();

//         // Convert pipe-separated string to an array
//         $fields = explode('|', $decryptedData);

//         // Define expected keys
//         $keys = [
//             'deptid', 'recieptheadcode', 'depositername', 'depttranid', 'amount', 'depositerid',
//             'panno', 'addinfo1', 'addinfo2', 'addinfo3', 'treascode',
//             'ifmsofficecode', 'status', 'paymentstatusmessage', 'grn', 'cin', 'ref_no', 
//             'txn_date', 'txn_amount', 'challan_url', 'pmode', 'addinfo5'
//         ];

//         // Create an associative array from decrypted data
//         $decodedResponse = array_combine($keys, array_slice($fields, 0, count($keys))) ?? [];

//         Log::info('Final Decoded Response:', $decodedResponse);

//         // **Send API Request**
//         $apiUrl = config('app.api.transaction_url') . '/jegras_payment_response_hc.php';
//         $apiResponse = Http::post($apiUrl, $decodedResponse);

//         // **Handle API Response**
//         if ($apiResponse->successful()) {
//             $responseData = $apiResponse->json();
//             Log::info('API Response:', $responseData);
//         } else {
//             Log::error('API Request Failed:', ['response' => $apiResponse->body()]);
//             $responseData = ['error' => 'API request failed'];
//         }

//         // Return the decrypted & API response data to the view
//         return view('transactionStatus')->with([
//             'responseData' => $decodedResponse,
//             'apiResponse' => $responseData
//         ]);
//     }

public function handleResponse(Request $request)
{
    $encryptionHelper = new clsEncrypt();
    $secretKey = 'key1234';
    
    // Get encrypted text from request parameters
    $encryptedText = $request->input('encryptedText');
    
    // Validate encrypted text presence
    if (empty($encryptedText)) {
        Log::warning('Request received without encrypted text', [
            'ip' => $request->ip(),
            'url' => $request->fullUrl()
        ]);
        return redirect('/')->with('error', 'No encrypted text provided.');
    }

    // Decrypt the data
    $decryptedData = $encryptionHelper->decrypt($encryptedText, $secretKey);

    if ($decryptedData === false) {
        Log::error('Decryption Failed', [
            'encrypted_length' => strlen($encryptedText),
            'ip' => $request->ip()
        ]);
        return redirect('/')->with('error', 'Decryption failed.');
    }

    Log::info('Successful decryption', ['ip' => $request->ip()]);

    // Convert pipe-separated string to an array
    $fields = explode('|', $decryptedData);

    // Define expected keys
    $keys = [
        'deptid', 'recieptheadcode', 'depositername', 'depttranid', 'amount', 'depositerid',
        'panno', 'addinfo1', 'addinfo2', 'addinfo3', 'treascode',
        'ifmsofficecode', 'status', 'paymentstatusmessage', 'grn', 'cin', 'ref_no', 
        'txn_date', 'txn_amount', 'challan_url', 'pmode', 'addinfo5'
    ];

    // Create an associative array from decrypted data
    $decodedResponse = array_combine($keys, array_slice($fields, 0, count($keys))) ?? [];

    Log::info('Final Decoded Response:', $decodedResponse);

    // Send API Request
    $apiUrl = config('app.api.transaction_url') . '/jegras_payment_response_hc.php';
    $apiResponse = Http::post($apiUrl, $decodedResponse);

    // Handle API Response
    if ($apiResponse->successful()) {
        $responseData = $apiResponse->json();
        Log::info('API Response:', $responseData);
    } else {
        Log::error('API Request Failed:', ['response' => $apiResponse->body()]);
        $responseData = ['error' => 'API request failed'];
    }

    // Return the decrypted & API response data to the view
    return view('transactionStatus')->with([
        'responseData' => $decodedResponse,
        'apiResponse' => $responseData
    ]);
}

}