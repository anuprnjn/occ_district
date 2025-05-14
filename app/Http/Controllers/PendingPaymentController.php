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
       
        if (str_starts_with($application_number, 'HCW')) {
            $apiUrl = 'http://localhost/occ_api/transaction/pending_payment_hc_order_copy.php';
            $isOrderApi = true;
        } else {
            $apiUrl = 'http://localhost/occ_api/transaction/pending_payment_hc_other_copy.php';
            $isOrderApi = false;
        }
        // Send a POST request to the selected API
        $response = Http::post($apiUrl, [
            'application_number' => $application_number,
        ]);
    
        $responseData = $response->json();
        // dd($responseData);
        // exit();
    
        if ($responseData['success']) {
            $responsePayload = [
                'success' => true,
                'case_info' => $responseData['case_info'],
                'location' => '/occ/cd_pay',
            ];
    
            // Add order details if it came from the order copy API
            if ($isOrderApi) {
                $responsePayload['order_details'] = $responseData['order_details'];
                $responsePayload['transaction_details'] = $responseData['transaction_details'];
            } else {
                // Add document details if it came from the other copy API
                $responsePayload['document_details'] = $responseData['document_details'];
            }
    
            return response()->json($responsePayload);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No data found for this application number.',
            ]);
        }
    }
    // public function fetchPendingPaymentsDC(Request $request)
    // {
    //     $application_number = $request->input('application_number');
     
    //     $apiUrl = 'http://localhost/occ_api/transaction/pending_payment_dc_other_copy.php';
    //     // Send a POST request to the selected API
    //     $response = Http::post($apiUrl, [
    //         'application_number' => $application_number,
    //     ]);
    
    //     $responseData = $response->json();
    //     // dd($responseData);
    //     // exit();
    
    //     if ($responseData['success']) {
    //         $responsePayload = [
    //             'success' => true,
    //             'case_info' => $responseData['case_info'],
    //             'location' => '/occ/cd_pay',
    //         ];
           
    //         $responsePayload['document_details'] = $responseData['document_details'];
    
    //         return response()->json($responsePayload);
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No data found for this application number.',
    //         ]);
    //     }
    // }
    public function fetchPendingPaymentsDC(Request $request)
    {
        $application_number = $request->input('application_number');

        if (!$application_number) {
            return response()->json([
                'success' => false,
                'message' => 'Application number is required.',
            ]);
        }

        // Choose API endpoint based on whether the 4th character is 'W'
        $isOrderCopy = strlen($application_number) >= 4 && strtoupper($application_number[3]) === 'W';
        $apiEndpoint = $isOrderCopy ? 'pending_payment_dc_other_copy' : 'pending_payment_dc_other_copy.php';
        $apiUrl = 'http://localhost/occ_api/transaction/' . $apiEndpoint;

        try {
            $response = Http::post($apiUrl, [
                'application_number' => $application_number,
            ]);

            $responseData = $response->json();

            if ($responseData['success']) {
                $responsePayload = [
                    'success' => true,
                    'case_info' => $responseData['case_info'],
                    'location' => '/occ/cd_pay',
                    'document_details' => $responseData['document_details'],
                ];

                return response()->json($responsePayload);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found for this application number.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching pending payment data.',
            ]);
        }
    }
    
}