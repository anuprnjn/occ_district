<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\DCApplicationRegistrationController;
// use Mews\Captcha\Facades\Captcha;

Route::get('/', function () {
    return view('index');
});

Route::get('/hcPage', function () {
    return view('hcPage');
})->name('hcPage');

Route::get('/dcPage', function () {
    return view('dcPage');
})->name('dcPage');

Route::get('/show_data', function () {
    return view('show_data');
})->name('show_data');

Route::get('/dcPage', [DistrictController::class, 'showDistricts']);
Route::post('/get-establishments', [DistrictController::class, 'getEstablishments'])->name('get-establishments');
Route::post('/send-otp', [OtpController::class, 'sendOtp']);
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);
Route::post('/resend-otp', [OtpController::class, 'resendOtp']);
Route::post('/register-application', [DCApplicationRegistrationController::class, 'registerApplication']);


