<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\OrderCopyController;
use App\Helpers\clsEncrypt;
use App\Http\Controllers\GrasResponseController;

// Route::match(['get', 'post'], '/occ/gras_resp_cc', function (Request $request) {
//     $encryptionHelper = new ClsEncrypt();
//     $data = $request->all();

//     if (isset($data['responseparam'])) {
//         $encryptedText = $data['responseparam'];
//         $secretKey = 'Ky@5432#';

//         // Decrypt the data
//         $decryptedData = $encryptionHelper->decrypt($encryptedText, $secretKey);

//         if ($decryptedData === false) {
//             Log::error('Decryption Failed');
//             $decryptedData = null;
//         } else {
//             Log::info('Decrypted Data:', ['decryptedData' => $decryptedData]);

//             // Convert pipe-separated string to array
//             $fields = explode('|', $decryptedData);

//             // Define keys (based on expected structure)
//             $keys = [
//                 'DEPTID', 'RECIEPTHEADCODE', 'DEPOSITERNAME', 'TRANSACTION_ID', 'AMOUNT', 'DEPOSITERID',
//                 'PANNO', 'APPLICATION_NO', 'ADDINFO2', 'ADDINFO3', 'TREASCODE',
//                 'IFMSOFFICECODE', 'STATUS', 'PAYMENTSTATUSMESSAGE', 'GRN','CIN','REF_NO', 'TXN_DATE','TXN_AMOUNT','CHALLAN_URL', 'MODE_OF_PAYMENT', 'ADDINFO5'
//             ];

//             // Create associative array
//             $decodedResponse = array_combine($keys, array_slice($fields, 0, count($keys))) ?? [];

//             Log::info('Final Decoded Response:', $decodedResponse);
//         }

//         // Send data to the view
//         $data['responseparam'] = $decodedResponse ?? [];
//     } else {
//         Log::warning('No responseparam found in the request.');
//         $data['responseparam'] = [];
//     }

//     return view('transactionStatus')->with('responseData', $data['responseparam']);
// })->name('transactionStatus');
// Route::match(['get', 'post'], '/occ/gras_resp_cc', function (Request $request) {
//     $encryptionHelper = new ClsEncrypt();
//     $secretKey = 'Ky@5432#';

//     // Encrypted text
//     $encryptedText = 'kdUDf+/ZxxRnRGDotlk6x/2CsbIONnFDTwNIPjhSjjteczI4hC/C52WkSbgvDSss1Evdyh0GidObr2c1WTdWTts7UZv1Fs/uqFfbbgqa6X2ijLynIYilBTqx0u0zazotp67Chxhv7u9nXKhT7YQcNA7G3RJN49Ss151+bBe/pR7P3+zqwQpOBmruVLJqnxeFdWtZGgAuDox6RqbpbYrDhjpGFECiq+DtBUYZO21oknnZIDjbOBSs6DzE1l2VDB5uqJOgi1nZF1r0pC+V0xuzqvkjzcl0xzsJNTQ3J1aV/U4=';

//     // Decrypt the data
//     $decryptedData = $encryptionHelper->decrypt($encryptedText, $secretKey);

//     if ($decryptedData === false) {
//         Log::error('Decryption Failed');
//         $decryptedData = null;
//     } else {
//         Log::info('Decrypted Data:', ['decryptedData' => $decryptedData]);

//         // Convert pipe-separated string to an array
//         $fields = explode('|', $decryptedData);

//         // Define keys (based on expected structure)
//         $keys = [
//             'DEPTID', 'RECIEPTHEADCODE', 'DEPOSITERNAME', 'TRANSACTION_ID', 'AMOUNT', 'DEPOSITERID',
//             'PANNO', 'APPLICATION_NO', 'ADDINFO2', 'ADDINFO3', 'TREASCODE',
//             'IFMSOFFICECODE', 'STATUS', 'PAYMENTSTATUSMESSAGE', 'GRN', 'CIN', 'REF_NO', 
//             'TXN_DATE', 'TXN_AMOUNT', 'CHALLAN_URL', 'MODE_OF_PAYMENT', 'ADDINFO5'
//         ];

//         // Create associative array
//         $decodedResponse = array_combine($keys, array_slice($fields, 0, count($keys))) ?? [];

//         Log::info('Final Decoded Response:', $decodedResponse);
//     }

//     // Return the decrypted data to the view
//     return view('transactionStatus')->with('responseData', $decodedResponse ?? []);
// })->name('transactionStatus');

// Route::match(['get', 'post'], '/occ/gras_resp_cc', [GrasResponseController::class, 'handleResponse'])
//     ->name('transactionStatus');

Route::match(['get', 'post'], '/occ/gras_resp_cc', [GrasResponseController::class, 'handleResponse'])
    ->middleware('web')  // ← Essential for session support
    ->name('transactionStatus');