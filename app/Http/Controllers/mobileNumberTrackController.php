<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

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

        Session::put('inputTypeHC', $inputType);
        Session::put('inputValueHC', $inputValue);

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

        Session::put('inputTypeDC', $inputType);
        Session::put('inputValueDC', $inputValue);

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
           
            $inputType = session('inputTypeHC');
            $inputValue = session('inputValueHC');

            // Step 3: If mobile number found, fetch updated data from external API
            if (!$inputType) {
                $apiUrl = config('app.api.hc_base_url') . '/get_all_application_applied_user_from_mobile_hc.php';

                try {
                    $response = Http::post($apiUrl, [
                        $inputType => $inputValue
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
            
            $inputType = session('inputTypeDC');
            $inputValue = session('inputValueDC');

            // Step 3: If mobile number found, fetch updated data from external API
            if (!$inputType) {
                 $apiUrl = config('app.api.base_url') . '/get_all_application_applied_user_from_mobile_dc.php';

                try {
                    $response = Http::post($apiUrl, [
                        $inputType => $inputValue
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
        session(['isUserLoggedInTransaction' => false]);
     
        session()->forget(['trackDetailsMobileHC', 'trackDetailsMobileDC', 'PendingCaseInfoDetails']);
       
        return redirect('/trackStatus')->with('message', 'You have been logged out.');
    }

    public function showTrackStatusDetails(Request $request)
    {
        $isUserLoggedIn = session('isUserLoggedIn') === true;
        $isUserLoggedInTransaction = session('isUserLoggedInTransaction') === true;
      
        if (!$isUserLoggedIn && !$isUserLoggedInTransaction) {
            return redirect('/trackStatus')->with('error', 'Unauthorized access. Please login.');
        }

        $encodedAppNo = $request->input('application_number');
        $applicationNumber = null;

        if ($encodedAppNo) {
            try {
                $applicationNumber = base64_decode($encodedAppNo, true); // true = strict mode
            } catch (\Exception $e) {
                return redirect('/trackStatus')->with('error', 'Invalid application number.');
            }
        }

        if (!$applicationNumber) {
            return redirect('/trackStatus')->with('error', 'Missing or invalid application number.');
        }

        return view('trackStatusDetails', compact('applicationNumber'));
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

        }

        return view('trackStatus'); // Not logged in
    }

}
