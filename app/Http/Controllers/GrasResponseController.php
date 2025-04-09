<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Helpers\ClsEncrypt;

class GrasResponseController extends Controller
{
    // public function handleResponse(Request $request)
    // {
    //     $encryptionHelper = new ClsEncrypt();
    //     $secretKey = 'Ky@5432#';
    //     // $secretKey = 'key1234';

    //     // Encrypted text
    //     $encryptedText = 'kdUDf+/ZxxRnRGDotlk6x/2CsbIONnFDTwNIPjhSjjteczI4hC/C52WkSbgvDSss1Evdyh0GidObr2c1WTdWTts7UZv1Fs/uqFfbbgqa6X2ijLynIYilBTqx0u0zazotp67Chxhv7u9nXKhT7YQcNA7G3RJN49Ss151+bBe/pR7P3+zqwQpOBmruVLJqnxeFdWtZGgAuDox6RqbpbYrDhjpGFECiq+DtBUYZO21oknnZIDjbOBSs6DzE1l2VDB5uqJOgi1nZF1r0pC+V0xuzqvkjzcl0xzsJNTQ3J1aV/U4=';
    //     // $encryptedText = "/LafgEmh7gCGMPfS6EAhct1MXISJpu1e8xLblg8eYznRAi82w/Sk6qnKilt/kcltU0HQlgxXGs3O+R4lHTa8dXFa0OYSuWx5Dd8r7YwlVOZvRRzFbOxRVXp2RBKXF457eJJUZEn0v1iCRQxNJyY5nObjkG8FJiYDBgYdwxfCiASw1mUd5qLPImxnS9rW2BXpm64c1Pj5Gr8iO8olYcrtf9efX2b9jcu+rePa5IOc96BUgz2WzM2VrEF81ckkDvs/GJAY3NExDEKcMKLJYtkqr2dtil0seYtm0MvzdphLP1TVaX8VoKwzncOwi/aj/6RhraV3DUXidCcNSOKwuWWEXaz3lLA/lNjFWnxwJI1jKH+aGIBIfQBJTmnqHOuq7Oez";
    //     // Decrypt the data
    //     $decryptedData = $encryptionHelper->decrypt($encryptedText, $secretKey);

    //     if ($decryptedData === false) {
    //         Log::error('Decryption Failed');
    //         return view('transactionStatus')->with('responseData', []);
    //     }

    //     Log::info('Decrypted Data:', ['decryptedData' => $decryptedData]);

    //     // Convert pipe-separated string to an array
    //     $fields = explode('|', $decryptedData);

    //     // Define keys (based on expected structure)
    //     $keys = [
    //         'deptid', 'recieptheadcode', 'depositername', 'depttranid', 'amount', 'depositerid',
    //         'panno', 'addinfo1', 'addinfo2', 'addinfo3', 'treascode',
    //         'ifmsofficecode', 'status', 'paymentstatusmessage', 'grn', 'cin', 'ref_no', 
    //         'txn_date', 'txn_amount', 'challan_url', 'pmode', 'addinfo5'
    //     ];

    //     // Create an associative array from decrypted data
    //     $decodedResponse = array_combine($keys, array_slice($fields, 0, count($keys))) ?? [];

    //     Log::info('Final Decoded Response:', $decodedResponse);

    //     // **Send API Request**
    //     $apiUrl = 'http://localhost/occ_api/transaction/jegras_payment_response_hc.php';

    //     $apiResponse = Http::post($apiUrl, $decodedResponse);

    //     // **Handle API Response**
    //     if ($apiResponse->successful()) {
    //         $responseData = $apiResponse->json();
    //         Log::info('API Response:', $responseData);
    //     } else {
    //         Log::error('API Request Failed:', ['response' => $apiResponse->body()]);
    //         $responseData = ['error' => 'API request failed'];
    //     }

    //     // Return the decrypted & API response data to the view
    //     return view('transactionStatus')->with([
    //         'responseData' => $decodedResponse,
    //         'apiResponse' => $responseData
    //     ]);
    // }
    public function handleResponse(Request $request)
{
    $encryptionHelper = new clsEncrypt();
    $secretKey = 'Ky@5432#';
    $encTest = 'kdUDf+/ZxxRnRGDotlk6x/2CsbIONnFDTwNIPjhSjjteczI4hC/C52WkSbgvDSss1Evdyh0GidObr2c1WTdWTts7UZv1Fs/uqFfbbgqa6X2ijLynIYilBTqx0u0zazotp67Chxhv7u9nXKhT7YQcNA7G3RJN49Ss151+bBe/pR7P3+zqwQpOBmruVLJqnxeFdWtZGgAuDox6RqbpbYrDhjpGFECiq+DtBUYZO21oknnZIDjbOBSs6DzE1l2VDB5uqJOgi1nZF1r0pC+V0xuzqvkjzcl0xzsJNTQ3J1aV/U4=';
    $nullEnc = null;

    // Check if encrypted text is provided
    $encryptedText = $request->input('encryptedText', $encTest); 
    // $encryptedText = $request->input('encryptedText', null); 

    if (empty($encryptedText)) {
        return redirect('/')->with('error', 'No encrypted text provided.');
    }

    // Decrypt the data
    $decryptedData = $encryptionHelper->decrypt($encryptedText, $secretKey);

    if ($decryptedData === false) {
        Log::error('Decryption Failed');
        return redirect('/')->with('error', 'Decryption failed.');
    }

    Log::info('Decrypted Data:', ['decryptedData' => $decryptedData]);

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

    // **Send API Request**
    $apiUrl = 'http://localhost/occ_api/transaction/jegras_payment_response_hc.php';
    $apiResponse = Http::post($apiUrl, $decodedResponse);

    // **Handle API Response**
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