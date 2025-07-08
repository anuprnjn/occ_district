<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class mobileNumberTrackController extends Controller
{
    public function trackMobileNumberHC(Request $request)
    {
        // Validate the request
        $request->validate([
            'input_type' => 'required|in:mobile_number,application_number',
            'input_value' => 'required',
        ]);

        $inputType = $request->input('input_type'); // mobile_number or application_number
        $inputValue = $request->input('input_value');

        if ($inputType === 'mobile_number') {
            $request->validate([
                'input_value' => 'digits:10',
            ]);
        }

        $baseUrl = config('app.api.hc_base_url');
        // Prepare the API endpoint and data
        $apiUrl = $baseUrl . '/get_all_application_applied_user_from_mobile_hc.php';
        $mobileNumber = $request->input('mobile_number');

       try {
            // Make POST request to the external PHP API
            $response = Http::post($apiUrl, [
                $inputType => $inputValue, // ✅ dynamic payload
            ]);

         \Log::info('Raw response body: ' . $response->body());

            $data = $response->json();
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from external API.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function setTrackDetailsHC(Request $request)
    {
        session()->forget('trackDetailsMobileDC');
        $trackData = $request->input('data');

        // Save in Laravel session
        session(['trackDetailsMobileHC' => $trackData]);

        return response()->json([
            'success' => true,
            'message' => 'Track details of HC saved in session.'
        ]);
    }

    public function trackMobileNumberDC(Request $request)
    {
        // Validate the request
        $request->validate([
            'input_type' => 'required|in:mobile_number,application_number',
            'input_value' => 'required',
        ]);

        $inputType = $request->input('input_type'); // mobile_number or application_number
        $inputValue = $request->input('input_value');

        if ($inputType === 'mobile_number') {
            $request->validate([
                'input_value' => 'digits:10',
            ]);
        }

        // Correct API endpoint — same for both types
        $apiUrl = config('app.api.base_url') . '/get_all_application_applied_user_from_mobile_dc.php';

        try {
            // Make POST request to the external PHP API
            $response = Http::post($apiUrl, [
                $inputType => $inputValue, // ✅ dynamic payload
            ]);

         \Log::info('Raw response body: ' . $response->body());

            $data = $response->json();
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from external API.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function setTrackDetailsDC(Request $request)
    {
        
        session()->forget('trackDetailsMobileHC');
        $trackData = $request->input('data');

        // Save in Laravel session
        session(['trackDetailsMobileDC' => $trackData]);
\Log::info($trackData);
        return response()->json([
            'success' => true,
            'message' => 'Track details of DC saved in session.'
        ]);
    }

    public function refreshTrackDetailsHCFromSession()
    {
        // Step 1: Check if HC session exists
        if (session()->has('trackDetailsMobileHC')) {
            $sessionData = session('trackDetailsMobileHC');
            $mobile = null;

            // Step 2: Extract mobile number from either order_copy or other_copy
            if (!empty($sessionData['data']['order_copy'])) {
                $mobile = $sessionData['data']['order_copy'][0]['mobile_number'] ?? null;
            } elseif (!empty($sessionData['data']['other_copy'])) {
                $mobile = $sessionData['data']['other_copy'][0]['mobile_number'] ?? null;
            }

            // Step 3: If mobile number found, fetch updated data from external API
            if ($mobile) {
                $apiUrl = config('app.api.hc_base_url') . '/get_all_application_applied_user_from_mobile_hc.php';

                try {
                    $response = Http::post($apiUrl, [
                        'mobile_number' => $mobile
                    ]);

                    $data = $response->json();

                    // Step 4: Update session
                    session()->put('trackDetailsMobileHC', [
                        'success' => true,
                        'data' => $data,
                    ]);

                    return redirect()->back(); // or redirect to specific route if needed

                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Could not refresh data.');
                }
            }
        }

        return redirect()->back()->with('error', 'Session data not available.');
    }

    public function refreshTrackDetailsDCFromSession()
    {
        // Step 1: Check if HC session exists
        if (session()->has('trackDetailsMobileDC')) {
            $sessionData = session('trackDetailsMobileDC');
            $mobile = null;

            // Step 2: Extract mobile number from either order_copy or other_copy
            if (!empty($sessionData['data']['order_copy'])) {
                $mobile = $sessionData['data']['order_copy'][0]['mobile_number'] ?? null;
            } elseif (!empty($sessionData['data']['other_copy'])) {
                $mobile = $sessionData['data']['other_copy'][0]['mobile_number'] ?? null;
            }

            // Step 3: If mobile number found, fetch updated data from external API
            if ($mobile) {
                 $apiUrl = config('app.api.base_url') . '/get_all_application_applied_user_from_mobile_dc.php';

                try {
                    $response = Http::post($apiUrl, [
                        'mobile_number' => $mobile
                    ]);

                    $data = $response->json();

                    // Step 4: Update session
                    session()->put('trackDetailsMobileDC', [
                        'success' => true,
                        'data' => $data,
                    ]);

                    return redirect()->back(); // or redirect to specific route if needed

                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Could not refresh data.');
                }
            }
        }

        return redirect()->back()->with('error', 'Session data not available.');
    }
    public function logoutTracking(Request $request)
    {
        session(['isUserLoggedIn' => false]);
        session(['trackStatusApplication' => false]);
        session()->forget(['trackDetailsMobileHC', 'trackDetailsMobileDC']);

        // ✅ Redirect to /trackStatus instead of returning JSON
        return redirect('/trackStatus')->with('message', 'You have been logged out.');
    }
    public function showTrackStatusDetails(Request $request)
    {
        $hasHc = session()->has('trackDetailsMobileHC');
        $hasDc = session()->has('trackDetailsMobileDC');
        $hasTrackStatusApplication = session('trackStatusApplication') === true;

        if (!$hasHc && !$hasDc && !$hasTrackStatusApplication) {
            return redirect('/trackStatus')->with('error', 'Session expired. Please login again.');
        }

        return view('trackStatusDetails');
    }
   
   public function showTrackStatusPage()
    {
        if (session('isUserLoggedIn') === true) {

            if (session()->has('trackDetailsMobileHC')) {
                return redirect()->route('trackStatusMobileHC');
            }

            if (session()->has('trackDetailsMobileDC')) {
                return redirect()->route('trackStatusMobileDC');
            }

            // if (session('trackStatusApplication') === true) {
            //     return redirect()->route('trackStatus');
            // }

        }

        return view('trackStatus'); // Not logged in
    }

}
