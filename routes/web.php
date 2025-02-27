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
use App\Http\Controllers\LoginController;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Controllers\admin\RoleController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\admin\MenuController;
use App\Http\Controllers\admin\SubMenuController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FeeController;

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

Route::get('/transactionStatus', function () {
    return view('transactionStatus');
})->name('transactionStatus');

Route::get('/screenReader', function () {
    return view('screenReader');
})->name('screenReader');

Route::get('/dcPage', [DistrictController::class, 'showDistricts']);
Route::get('/hcPage', [HCCaseTypeController::class, 'showCases']);
Route::post('/get-establishments', [DistrictController::class, 'getEstablishments'])->name('get-establishments');
Route::post('/send-otp', [OtpController::class, 'sendOtp']);
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);
Route::post('/resend-otp', [OtpController::class, 'resendOtp']);
Route::post('/application-mobile-track', [OtpController::class, 'getApplicationDetails']);
Route::post('/application-mobile-track-hc', [OtpController::class, 'getHCApplicationDetailsForMobile']);
Route::get('/get-login-captcha', [LoginController::class, 'getLoginCaptcha']);
Route::post('/register-application', [DCApplicationRegistrationController::class, 'registerApplication']);
Route::post('/fetch-merchant-details', [PaymentController::class, 'fetchMerchantDetails']);
Route::post('/set-urgent-fee', [FeeController::class, 'setUrgentFee']);
Route::get('/get-urgent-fee', [FeeController::class, 'getUrgentFee']);
Route::get('/refresh-captcha', function () {
    $num1 = rand(1, 9);
    $num2 = rand(1, 9);
    $mathEquation = "{$num1} + {$num2}";
    Session::put('captcha', $num1 + $num2);
    $builder = new CaptchaBuilder($mathEquation);
    $builder->build(150, 48);
    return response()->json(['captcha_src' => $builder->inline()]);
});

Route::post('/validate-captcha', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'captcha' => [
            'required',
            function ($attribute, $value, $fail) {
                if ((int)$value !== Session::get('captcha')) {
                    $fail('Invalid CAPTCHA');
                }
            }
        ],
    ]);
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid CAPTCHA. Please try again.',
        ], 422);
    }
    Session::forget('captcha');
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
})->name('index');


Route::get('/admin/menu-list', [MenuController::class, 'MenuList'])->name('menu_list');
Route::post('/admin/menu-add', [MenuController::class, 'addMenu'])->name('menu_add');
Route::post('/admin/menu-update', [MenuController::class, 'updateMenu'])->name('menu_update');

Route::get('/admin/submenu-list', [SubMenuController::class, 'SubMenuList'])->name('submenu_list');
Route::post('/admin/submenu-add', [SubMenuController::class, 'addSubMenu'])->name('submenu_add');
Route::post('/admin/submenu-update', [SubMenuController::class, 'updateSubMenu'])->name('submenu_update');
Route::post('/submenu/delete', [SubMenuController::class, 'deleteSubMenu'])->name('submenu_delete');

Route::get('/admin/roles', [RoleController::class, 'RoleList'])->name('role_list');
Route::get('/admin/roles/add', [RoleController::class, 'showAddRoleForm'])->name('add_role'); // Fixed method reference
Route::post('/admin/roles/add', [RoleController::class, 'addRole'])->name('role_add'); // Post request for adding role
Route::get('/admin/role/edit/{role_id}', [RoleController::class, 'editRole'])->name('role_edit');
Route::post('/admin/role/update/{role_id}', [RoleController::class, 'updateRole'])->name('role_update');

Route::get('/admin/payment-parameter-list', [PaymentParameterController::class, 'parameterList'])->name('payment_parameter_list');

Route::post('/admin/payment-parameter/update', [PaymentParameterController::class, 'update'])->name('payment_parameter_update');

