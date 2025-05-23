<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderCopyController extends Controller
{
    public function submitOrderCopy(Request $request)
    {
        try {
            Log::info('Received API request:', $request->all());

            // Convert numeric values to string before validation
            $request->merge([
                'case_type' => (string) $request->case_type,
            ]);

            // Validate request data
            $validatedData = $request->validate([
                'applicant_name' => 'required|string',
                'mobile_number' => 'required|string',
                'email' => 'required|email',
                'petitioner_name' => 'required|string',
                'respondent_name' => 'required|string',
                'case_type' => 'required|string',
                'case_number' => 'nullable|string',
                'case_year' => 'nullable|string',
                'filing_number' => 'nullable|string',
                'filing_year' => 'nullable|string',
                'case_status' => 'nullable|string',
                'request_mode' => 'required|string',
                'applied_by' => 'required|string',
                'cino' => 'required|string',
                'advocate_registration_number' => 'nullable|string',
                'order_details' => 'required|array',
                'order_details.*.order_no' => 'required|integer',
                'order_details.*.order_date' => 'required|date',
                'order_details.*.case_number' => 'nullable|string',
                'order_details.*.filing_number' => 'nullable|string',
                'order_details.*.page_count' => 'required|integer',
                'order_details.*.amount' => 'required|numeric'
            ]);

            Log::info('Validated Data:', $validatedData);


            // Make external API call
            $apiResponse = Http::post('http://localhost/occ_api/high_court_order_copy/hc_order_copy_applicant_registration.php', $validatedData);

            Log::info('API Response:', ['status' => $apiResponse->status(), 'body' => $apiResponse->body()]);

            if ($apiResponse->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to connect to external API',
                    'error' => $apiResponse->body()
                ], 500);
            }

            return response()->json($apiResponse->json(), $apiResponse->status());
        } catch (\Throwable $e) {
            Log::error('Error in submitOrderCopy:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}