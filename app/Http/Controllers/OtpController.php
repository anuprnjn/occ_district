<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $mobile = $request->mobile;

        if (!preg_match('/^[0-9]{10}$/', $mobile)) {
            return response()->json(['success' => false, 'message' => 'Invalid mobile number']);
        }

        // Generate and store OTP in the session
        $otp = rand(100000, 999999);
        session([$mobile => $otp]);

        return response()->json(['success' => true, 'otp' => $otp, 'message' => 'OTP sent successfully']);
    }

    public function verifyOtp(Request $request)
    {
        $mobile = $request->mobile;
        $otp = $request->otp;

        // Check if OTP matches the one stored in session
        if (session($mobile) && session($mobile) == $otp) {
            session()->forget($mobile); // Remove OTP after successful verification
            return response()->json(['success' => true, 'message' => 'OTP verified']);
        }

        return response()->json(['success' => false, 'message' => 'Incorrect OTP']);
    }

    public function resendOtp(Request $request)
    {
        $mobile = $request->mobile;

        if (!session($mobile)) {
            return response()->json(['success' => false, 'message' => 'No OTP was sent earlier']);
        }

        // Generate a new OTP and update the session
        $otp = rand(100000, 999999);
        session([$mobile => $otp]);

        return response()->json(['success' => true, 'otp' => $otp, 'message' => 'OTP resent successfully']);
    }
    public function getApplicationDetails(Request $request)
    {
        $request->validate([
            'TrackMobileNumber' => 'required|string',
        ]);
        $mobileNumber = $request->input('TrackMobileNumber');

        $response = Http::post('http://localhost/occ_api/district_court/get_application_details_from_mobile.php', [
            'mobile_number' => $mobileNumber,
        ]);
        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'data' => $response->json(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch data',
            ], $response->status());
        }
    }
}
