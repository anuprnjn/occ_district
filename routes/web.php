<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\DCApplicationRegistrationController;
use App\Http\Controllers\HCApplicationRegistrationController;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\HCCaseTypeController;
use App\Http\Controllers\JudgementController;
use App\Http\Controllers\OrderCopyController;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Controllers\admin\RoleController;
use Illuminate\Support\Facades\Validator;

Route::get('/', function () {
    return view('index');
});

Route::get('/hcPage', function () {
    return view('hcPage');
})->name('hcPage');

Route::get('/dcPage', function () {
    return view('dcPage');
})->name('dcPage');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/trackStatus', function () {
    return view('trackStatus');
})->name('trackStatus');
Route::get('/trackStatusDetails', function () {
    return view('trackStatusDetails');
})->name('trackStatusDetails');
Route::get('/pendingPayments', function () {
    return view('pendingPayments');
})->name('pendingPayments');

Route::get('/application-details', function () {
    return view('application_details');
})->name('application_details');

Route::get('/hc-application-details', function () {
    return view('hc_application_details');
})->name('hc_application_details');

Route::get('/caseInformation', function () {
    return view('caseInformation');
})->name('caseInformation');

Route::get('/caseInformationDetails', function () {
    return view('caseInformationDetails');
})->name('caseInformationDetails');

Route::get('/dcPage', [DistrictController::class, 'showDistricts']);
Route::get('/hcPage', [HCCaseTypeController::class, 'showCases']);
Route::post('/get-establishments', [DistrictController::class, 'getEstablishments'])->name('get-establishments');
Route::post('/send-otp', [OtpController::class, 'sendOtp']);
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);
Route::post('/resend-otp', [OtpController::class, 'resendOtp']);
Route::post('/application-mobile-track', [OtpController::class, 'getApplicationDetails']);
Route::post('/application-mobile-track-hc', [OtpController::class, 'getHCApplicationDetailsForMobile']);
Route::post('/register-application', [DCApplicationRegistrationController::class, 'registerApplication']);
// Route::get('/refresh-captcha', function () {
//     return response()->json(['captcha_src' => captcha_src('default')]); 
// });
Route::get('/refresh-captcha', function () {
    // Generate a random alphanumeric CAPTCHA string
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $captchaPhrase = substr(str_shuffle($characters), 0, 6); // 6-character alphanumeric string

    // Create a new CAPTCHA image
    $builder = new CaptchaBuilder($captchaPhrase);
    $builder->build(150, 48); // Set width & height

    // Store the new CAPTCHA phrase in session
    Session::put('captcha', $captchaPhrase);
    Session::put('captcha_image', $builder->inline());

    // Return the new CAPTCHA image in JSON response
    return response()->json(['captcha_src' => $builder->inline()]);
});
// Route::post('/validate-captcha', function (Request $request) {
//     // Step 1: Validate the CAPTCHA input using Mews CAPTCHA's built-in validation rule
//     $request->validate([
//         'captcha' => 'required|captcha', // Mews CAPTCHA validation rule
//     ]);

//     // Step 2: If validation passes, return success response
//     return response()->json([
//         'success' => true,
//         'message' => 'CAPTCHA validation successful.',
//     ]);
// });
Route::post('/validate-captcha', function (Request $request) {
    // Step 1: Validate the CAPTCHA input against the stored session value
    $validator = Validator::make($request->all(), [
        'captcha' => [
            'required',
            function ($attribute, $value, $fail) {
                if ($value !== Session::get('captcha')) {
                    $fail('Invalid CAPTCHA');
                }
            }
        ],
    ]);

    // Step 2: If validation fails, return error response
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid CAPTCHA. Please try again.',
        ], 422);
    }

    // Step 3: Clear CAPTCHA session after successful validation to prevent reuse
    Session::forget('captcha');

    // Step 4: Return success response
    return response()->json([
        'success' => true,
        'message' => 'CAPTCHA validation successful.',
    ]);
});
Route::post('/fetch-application-details', [ApplicationController::class, 'fetchApplicationDetails'])->name('fetch_application_details');

Route::post('/hc-register-application', [HCApplicationRegistrationController::class, 'hcRegisterApplication']);

Route::post('/fetch-hc-application-details', [ApplicationController::class, 'fetchHcApplicationDetails'])->name('fetch_hc_application_details');

Route::post('/fetch-judgement-data', [JudgementController::class, 'fetchJudgementData']);

Route::post('/submit-order-copy', [OrderCopyController::class, 'submitOrderCopy']);



//admin routes **************************************************************

Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');


Route::get('/admin/role-list', [RoleController::class, 'RoleList'])->name('role_list');