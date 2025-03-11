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
//     public function sendOtp(Request $request)
//     {
//         $mobile = $request->mobile;
    
//         if (!preg_match('/^[0-9]{10}$/', $mobile)) {
//             return response()->json(['success' => false, 'message' => 'Invalid mobile number']);
//         }
    
//         // Generate OTP
//         $otp = rand(100000, 999999);
//         session([$mobile => $otp]);
    
//         // Create OTP message
//         $message = "Verification%20OTP%20is%20" . $otp . ".HCJ"; // Matches your format
    
//         // Send OTP via SMS
//         $this->send_sms($message, $mobile, 1); // msg_type = 1 for OTP
    
//         return response()->json(['success' => true, 'message' => 'OTP sent successfully']);
//     }

// public function verifyOtp(Request $request)
// {
//     $mobile = $request->mobile;
//     $otp = $request->otp;

//     if (session($mobile) && session($mobile) == $otp) {
//         session()->forget($mobile); // Remove OTP after verification
//         return response()->json(['success' => true, 'message' => 'OTP verified']);
//     }

//     return response()->json(['success' => false, 'message' => 'Incorrect OTP']);
// }

// public function resendOtp(Request $request)
// {
//     $mobile = $request->mobile;

//     if (!session($mobile)) {
//         return response()->json(['success' => false, 'message' => 'No OTP was sent earlier']);
//     }

//     // Generate new OTP
//     $otp = rand(100000, 999999);
//     session([$mobile => $otp]);

//     // Prepare message
//     $message = "Your new OTP for verification is: $otp. Please do not share it with anyone.";

//     // Resend OTP via SMS
//     $this->send_sms($message, $mobile, 1); // Assuming 1 is the correct template ID

//     return response()->json(['success' => true, 'message' => 'OTP resent successfully']);
// }

// /**
//  * Function to send SMS using the live SMS API
//  */
// private function send_sms($message, $mobileno, $msg_type = 0)
// {
//     $username = "courts-jhr.sms";
//     $senderid = "jcourt";
//     $password = "N%24g67tDP%23F8";
//     $dlt_entity_id = '1001560520000017581';

//     // Set DLT Template ID based on message type
//     switch ($msg_type) {
//         case 1: $dlt_template_id = "1007160803260452203"; break;
//         case 2: $dlt_template_id = "1007160803254424724"; break;
//         case 3: $dlt_template_id = "1007160803169787569"; break;
//         case 4: $dlt_template_id = "1007160803160613680"; break;
//         case 5: $dlt_template_id = "1007160803200666921"; break;
//         case 6: $dlt_template_id = "1007161485396973921"; break;
//         case 7: $dlt_template_id = "1007161624111949829"; break;
//         case 8: $dlt_template_id = "1007161624116473065"; break;
//         case 9: $dlt_template_id = "1007161624106971074"; break;
//         case 10: $dlt_template_id = "1007161624122961453"; break;
//         default: $dlt_template_id = "";
//     }

//     // Ensure message is URL encoded
//     $message = urlencode($message);

//     // Construct API URL
//     $url = "http://smsgw.sms.gov.in/failsafe/HttpLink?username=".$username.
//            "&pin=".$password."&message=".$message.
//            "&mnumber=".$mobileno."&signature=".$senderid.
//            "&dlt_entity_id=".$dlt_entity_id."&dlt_template_id=".$dlt_template_id;

//     // Send request via cURL
//     $ch = curl_init($url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     $result = curl_exec($ch);
//     curl_close($ch);
// }
    public function getApplicationDetails(Request $request)
    {
        $request->validate([
            'TrackMobileNumber' => 'required|string',
        ]);
        $mobileNumber = $request->input('TrackMobileNumber');
        $baseUrl = config('app.api.base_url');

        $response = Http::post($baseUrl. '/get_application_details_from_mobile.php', [
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

    public function getHCApplicationDetailsForMobile(Request $request)
    {
        $request->validate([
            'TrackMobileNumber' => 'required|string',
        ]);
        $mobileNumber = $request->input('TrackMobileNumber');
        $baseUrl = config('app.api.hc_base_url');


        $response = Http::post($baseUrl. '/get_application_details_from_mobile_hc.php', [
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
