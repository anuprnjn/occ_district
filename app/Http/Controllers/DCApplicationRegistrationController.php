<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DCApplicationRegistrationController extends Controller
{
    public function registerApplication(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'district_code' => 'required|integer',
            'establishment_code' => 'required|string',
            'applicant_name' => 'required|string',
            'mobile_number' => 'required|numeric',
            'email' => 'required|email',
            'case_type' => 'required|integer',
            'case_number' => 'required|string',
            'case_year' => 'required|integer',
            'request_mode' => 'required|string',
            'required_document' => 'required|string',
            'applied_by' => 'required|string',
            'advocate_registration_number' => 'required|string',
            'selected_method' => 'required|string',
        ]);

        // Prepare the data for the external API
        $data = [
            'district_code' => (int) $validated['district_code'],
            'establishment_code' =>  $validated['establishment_code'],
            'applicant_name' => $validated['applicant_name'],
            'mobile_number' => $validated['mobile_number'],
            'email' => $validated['email'],
            'case_type' => (int) $validated['case_type'],
            'case_number' => $validated['case_number'],
            'case_year' => (int) $validated['case_year'],
            'request_mode' => $validated['request_mode'],
            'required_document' => $validated['required_document'],
            'applied_by' => $validated['applied_by'],
            'advocate_registration_number' => $validated['advocate_registration_number'],
            'selected_method' => $validated['selected_method'],
        ];

        // Get the API base URL from the config
        $baseUrl = config('app.api.base_url');

        // Call the external API
        try {
            $response = Http::post($baseUrl . '/district_court_applicant_registration.php', $data);

            if ($response->successful()) {
                // Forward the success response to the frontend
                return response()->json([
                    'success' => true,
                    'message' => 'Application registered successfully!',
                    'application_number' => $response->json('application_number'),
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to register application.',
                    'error' => $response->body(),
                ], $response->status());
            }
        } catch (\Exception $e) {
            // Handle exceptions and forward the error message
            return response()->json([
                'success' => false,
                'message' => 'Error while connecting to external API.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}