<?php

use App\Http\Controllers\Admin\SessionEstdController;
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
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\MenuController;
use App\Http\Controllers\admin\SubMenuController;
use App\Http\Controllers\admin\HcUserController;
use App\Http\Controllers\admin\DcUserController;
use App\Http\Controllers\admin\HcOtherCopyController;
use App\Http\Controllers\admin\DcOtherCopyController;
use App\Http\Controllers\admin\HcWebApplicationController;
use App\Http\Controllers\admin\PaymentParameterController;
use App\Http\Controllers\admin\DCPaymentParameterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\SessionDataController;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Controllers\admin\AuthController;
use App\Http\Middleware\CheckSession;
use App\Http\Middleware\CheckSessionCd_pay;
use App\Http\Controllers\PendingPaymentController;

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


Route::middleware([CheckSession::class])->group(function () {
    Route::get('/caseInformation', function () {
        return view('caseInformation');
    })->name('caseInformation');
});
// Route::middleware([CheckSessionCd_pay::class])->group(function () {
    Route::get('/occ/cd_pay', function () {
        return view('caseInformationDetails');
    })->name('caseInformationDetails');
// });

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
Route::post('/api/login', [LoginController::class, 'login']);
Route::post('/register-application', [DCApplicationRegistrationController::class, 'registerApplication']);
Route::post('/fetch-merchant-details', [PaymentController::class, 'fetchMerchantDetails']);
Route::get('/refresh-captcha', [CaptchaController::class, 'refreshCaptcha']);
Route::post('/validate-captcha', [CaptchaController::class, 'validateCaptcha']);
Route::post('/fetch-application-details', [ApplicationController::class, 'fetchApplicationDetails'])->name('fetch_application_details');
Route::post('/hc-register-application', [HCApplicationRegistrationController::class, 'hcRegisterApplication']);
Route::post('/fetch-hc-application-details', [ApplicationController::class, 'fetchHcApplicationDetails'])->name('fetch_hc_application_details');
Route::post('/fetch-judgement-data', [JudgementController::class, 'fetchJudgementData']);
Route::post('/submit-order-copy', [OrderCopyController::class, 'submitOrderCopy']);
Route::post('/set-response-data', [SessionDataController::class, 'setResponseData'])->middleware('web');
Route::post('/set-paybleAmount', [SessionDataController::class, 'setPaybleAmount'])->middleware('web');
Route::get('/get-case-data', [SessionDataController::class, 'getCaseData'])->middleware('web');
Route::get('/get-paybleAmount', [SessionDataController::class, 'getPaybleAmount']);
Route::post('/set-caseInformation-data', [SessionDataController::class, 'setCaseInfoData'])->middleware('web');
Route::get('/get-caseInformation-data', [SessionDataController::class, 'getCaseInfoData'])->middleware('web');
Route::get('/clear-session', function () {
    session()->flush(); 
    return response()->json(['success' => true]);
})->name('clear.session');
Route::post('/fetch-pending-payments-hc', [PendingPaymentController::class,'fetchPendingPaymentsHC']);
Route::post('/set-caseInformation-PendingData-HC', [SessionDataController::class, 'setPendingCaseInfoData'])->middleware('web');

//admin routes **************************************************************

Route::middleware([AuthenticateUser::class])->group(function () {
    Route::get('/admin/index', function () {
        return view('admin.dashboard');
    })->name('index');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
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

Route::get('/admin/hc-user-list', [HcUserController::class, 'listHcUser'])->name('hc_user_list');
Route::get('/admin/hc-user-add', [HcUserController::class, 'showHcUser'])->name('hc_user_add');
Route::post('/admin/hc-user-add', [HcUserController::class, 'addHcUser'])->name('hc_user_add');
Route::get('/admin/hc-user-edit/{id}', [HcUserController::class, 'editHcUser'])->name('hc_user_edit');
Route::post('/admin/hc-user-update/{id}', [HcUserController::class, 'updateHcUser'])->name('hc_user_update'); // Keep it as POST

Route::get('/admin/dc-user-list', [DcUserController::class, 'listDcUser'])->name('dc_user_list');
Route::get('/admin/dc-user-add', [DcUserController::class, 'showDcUser'])->name('dc_user_add');
Route::post('/admin/fetch-establishments', [DcUserController::class, 'fetchEstablishments'])->name('fetch_establishments');
Route::post('/admin/dc-user-add', [DcUserController::class, 'addDcUser'])->name('dc_user_add');
Route::get('/admin/dc-user-edit/{id}', [DcUserController::class, 'editDcUser'])->name('dc_user_edit');
Route::post('/admin/dc-user-update/{id}', [DcUserController::class, 'updateDcUser'])->name('dc_user_update'); // Keep it as POST

Route::get('/admin/hc-web-application', [HcWebApplicationController::class, 'listHcWebApplication'])->name('hc_web_application_list');
Route::get('/admin/hc-web-application/{encryptedAppNumber}', [HcWebApplicationController::class, 'viewHcWebApplication'])
    ->name('hc-web-application.view');
Route::get('/admin/hc-other-copy', [HcOtherCopyController::class, 'listHcOtherCopy'])->name('hc_other_copy');
Route::get('/admin/dc-other-copy', [DcOtherCopyController::class, 'listDcOtherCopy'])->name('dc_other_copy');

Route::get('/admin/payment-parameter-list', [PaymentParameterController::class, 'parameterList'])->name('payment_parameter_list');  
Route::post('/admin/payment-parameter/update', [PaymentParameterController::class, 'update'])->name('payment_parameter_update');

Route::post('/update-session-estd-code', [SessionEstdController::class, 'updateEstdCode'])->name('update.session.estd_code');

Route::post('/admin/upload-order-copy', [HcWebApplicationController::class, 'uploadOrderCopy'])->name('admin.uploadOrderCopy');
Route::get('/admin/download-order-copy/{fileName}', [HcWebApplicationController::class, 'downloadOrderCopy'])->name('admin.downloadOrderCopy');
Route::get('/admin/delete-order-copy/{orderNumber}', [HcWebApplicationController::class, 'deleteOrderCopy'])->name('admin.deleteOrderCopy');
Route::get('/admin/payment-parameter-list-dc', [DCPaymentParameterController::class, 'parameterList'])->name('payment_parameter_list_dc');  
Route::post('/admin/payment-parameter-dc/update', [DCPaymentParameterController::class, 'update'])->name('payment_parameter_update_dc');
Route::get('/delete-order-copy/{application_number}/{order_number}', [HcWebApplicationController::class, 'deleteOrderCopy'])->name('admin.deleteOrderCopy');
Route::post('/hc-web-application/send-deficit-notification', [HcWebApplicationController::class, 'sendDeficitNotification'])
    ->name('hc-web-application.send-deficit-notification');
    Route::post('/hc-web-application/send-ready-notification', [HcWebApplicationController::class, 'sendReadyNotification'])
    ->name('hc-web-application.send-ready-notification');  
    
});
