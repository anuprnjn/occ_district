<?php

namespace App\Http\Controllers\Admin; // Correct namespace for the admin controller

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller; // Ensure the correct Controller is imported

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        // Clear all session data
        Session::flush();

        // Return JSON response with success status and redirect URL
        return response()->json([
            "success" => true,
            "message" => "Logout successful!",
            "redirect" => url('/login') // Admin-specific redirect
        ]);
    }
}