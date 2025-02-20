<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Session;

class HCApplicationRegistrationController extends Controller
{
    public function refreshCaptcha(Request $request)
    {
        // Generate a random alphanumeric CAPTCHA string
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $captchaPhrase = substr(str_shuffle($characters), 0, 6);
    
        // Create CAPTCHA image
        $builder = new CaptchaBuilder($captchaPhrase);
        $builder->build(150, 48);
    
        // Store only the CAPTCHA phrase in the session
        Session::put('captcha', $captchaPhrase);
    
        // Return new CAPTCHA image as JSON
        return response()->json(['captcha_src' => $builder->inline()]);
    }

    public function hcRegisterApplication(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'applicant_name' => 'required|string',
            'mobile_number' => 'required|numeric',
            'email' => 'required|email',
            'case_type' => 'required|integer',
            'case_filling_number' => 'required|string',
            'case_filling_year' => 'required|integer',
            'request_mode' => 'required|string',
            'required_document' => 'required|string',
            'applied_by' => 'required|string',
            'advocate_registration_number' => 'nullable|string',
            'selected_method' => 'required|string',
        ]);

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