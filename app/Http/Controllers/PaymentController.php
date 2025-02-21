<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class  PaymentController extends Controller
{
    public function fetchMerchantDetails()
    {
        $departmentId = '';
        for ($i = 0; $i < 19; $i++) {
            $departmentId .= mt_rand(0, 9); 
        }
        $depositerId = 'DRID' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT); 
        $response = Http::get(config('app.api.hc_base_url') .'/fetch_jegras_merchent_details.php');
        $PAN = 'N/A';
        $ADDINFO1 = 'N/A';
        $ADDINFO2 = 'N/A';
        $ADDINFO3 = 'N/A';
        $responseURL = 'http://127.0.0.1:8000/transactionStatus';
        if ($response->successful()) {
            return response()->json([
                'pan' => $PAN,
                'AdditionalInfo01' => $ADDINFO1,
                'AdditionalInfo02' => $ADDINFO2,
                'AdditionalInfo03' => $ADDINFO3,
                'departmentId' => $departmentId,
                'depositerId' => $depositerId,
                'responseurl' => $responseURL,
                'merchantDetails' => $response->json(),
            ]);
        }
        return response()->json(['error' => 'Unable to fetch merchant details'], 500);
    }
}