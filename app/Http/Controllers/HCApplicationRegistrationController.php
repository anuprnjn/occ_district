<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HCApplicationRegistrationController extends Controller
{
    public function refreshCaptcha(Request $request)
    {
        // Generate a simple math equation
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $mathEquation = "{$num1} + {$num2}";

        // Store the correct answer in session
        Session::put('captcha', $num1 + $num2);

        // Create a CAPTCHA image with the equation
        $builder = new CaptchaBuilder($mathEquation);
        $builder->build(150, 48);

        // Return new CAPTCHA image as JSON
        return response()->json(['captcha_src' => $builder->inline()]);
    }

    public function hcRegisterApplication(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'applicant_name' => 'required|string',
            'mobile_number' => 'required|digits:10',
            'email' => 'required|email',
            'case_type' => 'required|integer',
            'case_filling_number' => 'required|string',
            'case_filling_year' => 'required|digits:4|integer',
            'request_mode' => 'required|in:urgent,ordinary',
            'required_document' => 'required|string',
            'applied_by' => 'required|string|in:petitioner,respondent,advocate,others',
            'advocate_registration_number' => 'nullable|string',
            'selected_method' => 'required|in:C,F',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        // Prepare the data for the external API
        $data = [
            'applicant_name' => $validated['applicant_name'],
            'mobile_number' => $validated['mobile_number'],
            'email' => $validated['email'],
            'case_type' => (int) $validated['case_type'],
            'case_filling_number' => $validated['case_filling_number'],
            'case_filling_year' => (int) $validated['case_filling_year'],
            'request_mode' => $validated['request_mode'],
            'required_document' => $validated['required_document'],
            'applied_by' => $validated['applied_by'],
            'advocate_registration_number' => $validated['advocate_registration_number'],
            'selected_method' => $validated['selected_method'],
        ];

        // Get the API base URL from the config
        $baseUrl = config('app.api.hc_base_url');

        // Call the external API
        try {
            $response = Http::post($baseUrl . '/high_court_applicant_registration.php', $data);

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