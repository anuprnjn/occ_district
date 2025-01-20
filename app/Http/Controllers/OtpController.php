<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtpController extends Controller
{
    private $otpStore = [];

    public function sendOtp(Request $request)
{
    $mobile = $request->mobile;

    if (!preg_match('/^[0-9]{10}$/', $mobile)) {
        return response()->json(['success' => false, 'message' => 'Invalid mobile number']);
    }

    $otp = rand(10000, 99999);
    session([$mobile => $otp]); // Store OTP in session

    return response()->json(['success' => true, 'otp' => $otp, 'message' => 'OTP sent successfully']);
}

    public function verifyOtp(Request $request)
    {
        $mobile = $request->mobile;
        $otp = $request->otp;

        if (session($mobile) == $otp) {
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
    
        $otp = rand(10000, 99999);
        session([$mobile => $otp]); // Update OTP in session
    
        return response()->json(['success' => true, 'otp' => $otp, 'message' => 'OTP resent successfully']);
    }
}
