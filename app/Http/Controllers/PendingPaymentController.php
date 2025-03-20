<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;


class PendingPaymentController extends Controller
{
    public function fetchPendingPaymentsHC(Request $request)
    {
        $application_number = $request->input('application_number');

        // Send a POST request to the external API
        $response = Http::post('http://localhost/occ_api/transaction/pending_payment_hc_order_copy.php', [
            'application_number' => $application_number,
        ]);

        $responseData = $response->json();

        if ($responseData['success']) {
            return response()->json([
                'success' => true,
                'case_info' => $responseData['case_info'],
                'order_details' => $responseData['order_details'],
                'location' => '/occ/cd_pay',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No data found for this application number.',
            ]);
        }
    }
    
}