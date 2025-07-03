<?php

use Illuminate\Support\Facades\Storage;
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
use App\Http\Controllers\admin\HcOtherCopyPaidController;
use App\Http\Controllers\admin\DcOtherCopyController;
use App\Http\Controllers\admin\HcWebApplicationController;
use App\Http\Controllers\admin\PaymentParameterController;
use App\Http\Controllers\admin\DCPaymentParameterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\SessionDataController;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\PdfController;
use App\Http\Middleware\CheckSession;
use App\Http\Middleware\CheckSessionCd_pay;
use App\Http\Controllers\PendingPaymentController;
use App\Http\Controllers\admin\DcOtherCopyPaidController;
use App\Http\Controllers\admin\GetPdfController;
use App\Http\Controllers\admin\HcPdfController;
use App\Http\Controllers\admin\DigitalSignatureController;
use App\Http\Controllers\admin\RawPdfController;
use App\Http\Controllers\DcCaseTypeNapixController;
use App\Http\Controllers\DcGetCaseNapixController;
use App\Http\Controllers\StoreDCCaseDataController;
use App\Http\Controllers\DcOrderNapixController;
use App\Http\Controllers\StoreHCCaseDataController;
use App\Http\Controllers\DCOrderController;
use App\Http\Controllers\HcOrderNapixController;
use App\Http\Controllers\admin\DcWebApplicationController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\DownloadCertifiedCopyController;
use App\Http\Controllers\mobileNumberTrackController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Middleware\RolePermissionMiddleware;


Route::get('/', function () {
    if (session()->has('trackDetailsMobileHC')) {
        return redirect()->route('trackStatusMobileHC');
    }
    if (session()->has('trackDetailsMobileDC')) {
        return redirect()->route('trackStatusMobileDC');
    }
    return view('index');
})->name('index');

Route::get('/hcPage', function () {
    return view('hcPage');
})->name('hcPage');

Route::get('/dcPage', function () {
    return view('dcPage');
})->name('dcPage');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/trackStatus', [mobileNumberTrackController::class, 'showTrackStatusPage'])->name('trackStatus');

// Route::get('/trackStatusDetails', [mobileNumberTrackController::class, 'showTrackStatusDetails'])->name('trackStatusDetails');
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

Route::get('/caseInformationDc', function () {
    return view('caseInformationDC');
})->name('caseInformationDC');

// Route::middleware([CheckSession::class])->group(function () {
    Route::get('/caseInformation', function () {
        return view('caseInformation');
    })->name('caseInformation');
// });
// Route::middleware([CheckSessionCd_pay::class])->group(function () {
    Route::get('/occ/cd_pay', function () {
        return view('caseInformationDetails');
    })->name('caseInformationDetails');
// });

Route::get('/screenReader', function () {
    return view('screenReader');
})->name('screenReader');

// Route::get('/debug-php', function () {
//     phpinfo();
// });

Route::get('/downloadCC', function () {
    return view('downloadCC');
})->name('downloadCC');

Route::get('/trackStatusMobileHC', function () {
    return view('trackStatusMobileHC');
})->name('trackStatusMobileHC');

Route::get('/trackStatusMobileDC', function () {
    return view('trackStatusMobileDC');
})->name('trackStatusMobileDC');


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

Route::match(['get', 'post'], '/fetch-merchant-details', [PaymentController::class, 'fetchMerchantDetails']);

Route::get('/refresh-captcha', [CaptchaController::class, 'refreshCaptcha']);

Route::post('/validate-captcha', [CaptchaController::class, 'validateCaptcha']);

Route::post('/fetch-application-details', [ApplicationController::class, 'fetchApplicationDetails'])->name('fetch_application_details');

Route::post('/hc-register-application', [HCApplicationRegistrationController::class, 'hcRegisterApplication']);

Route::post('/fetch-hc-application-details', [ApplicationController::class, 'fetchHcApplicationDetails'])->name('fetch_hc_application_details');

Route::post('/get-hc-case-search-napix', [JudgementController::class, 'fetchCaseDetailsNapixHcOrderCopy']);

Route::post('/submit-order-copy', [OrderCopyController::class, 'submitOrderCopy']);

Route::post('/set-response-data', [SessionDataController::class, 'setResponseData'])->middleware('web');

Route::post('/set-paybleAmount', [SessionDataController::class, 'setPaybleAmount'])->middleware('web');

Route::get('/get-case-data', [SessionDataController::class, 'getCaseData'])->middleware('web');

Route::get('/get-paybleAmount', [SessionDataController::class, 'getPaybleAmount']);

Route::post('/set-caseInformation-data', [StoreHCCaseDataController::class, 'setHcCaseInfoData'])->middleware('web');

Route::get('/get-caseInformation-data', [SessionDataController::class, 'getCaseInfoData'])->middleware('web');

Route::get('/clear-session', function () {
    session()->flush(); 
    return response()->json(['success' => true]);
})->name('clear.session');

Route::post('/fetch-pending-payments-hc', [PendingPaymentController::class,'fetchPendingPaymentsHC']);

Route::post('/set-caseInformation-PendingData-HC', [SessionDataController::class, 'setPendingCaseInfoData'])->middleware('web');

Route::post('/fetch-pending-payments-dc', [PendingPaymentController::class,'fetchPendingPaymentsDC']);

Route::post('/get-dc-case-type-napix', [DcCaseTypeNapixController::class, 'fetchNapixDcCaseType']);

Route::post('/get-dc-case-master', [DcCaseTypeNapixController::class, 'fetchDcCaseType']);

Route::post('/get-dc-case-search-napix', [DcGetCaseNapixController::class, 'fetchCaseDetailsNapixDc']);

Route::post('/get-dc-case-search-cnr-napix', [DcGetCaseNapixController::class, 'fetchCaseDetailsOrderDetailsNapixDc']);

Route::post('/get-hc-case-search-cnr-napix', [JudgementController::class, 'fetchCaseDetailsOrderDetailsNapixHc']);

Route::post('/store-case-details', [StoreDCCaseDataController::class, 'storeCaseDetails']);

Route::post('/get-order-pdf-napix', [DcOrderNapixController::class, 'getDcOrderPdf']);

Route::post('/store-hc-case-details', [StoreHCCaseDataController::class, 'storeHCCaseDetails']);

Route::post('/dc/store-case-session', [StoreDCCaseDataController::class, 'store'])->name('dc.store.session');

Route::post('/calculate-dc-final-amount', [StoreDCCaseDataController::class, 'calculateFinalAmount'])->name('calculate-dc-final-amount');

Route::post('/dc/initiate-payment', [StoreDCCaseDataController::class, 'initiatePayment'])->name('initiate.dc.payment');

Route::post('/submit-dc-order-details', [DCOrderController::class, 'submit'])->name('dc-order.submit');

Route::post('/get-hc-order-pdf-napix', [HcOrderNapixController::class, 'getHcOrderPdf']);

Route::post('/calculate-hc-final-amount', [StoreHCCaseDataController::class, 'calculateFinalPayableAmount'])->name('calculate-hc-final-amount');

Route::post('/initiate-payment', [StoreHCCaseDataController::class, 'initiatePayment'])->name('initiate.hc.payment');

Route::get('/get-districts', [DownloadCertifiedCopyController::class, 'getDistricts']);

Route::post('/certified-copy/high-court', [DownloadCertifiedCopyController::class, 'highCourt']);

Route::post('/certified-copy/civil-court', [DownloadCertifiedCopyController::class, 'civilCourt']);

Route::get('/download-file/{filename}', [DownloadCertifiedCopyController::class, 'downloadFile'])->where('filename', '.*');

Route::post('/check-mobile-number-hc', [mobileNumberTrackController::class, 'trackMobileNumberHC']);

Route::post('/check-mobile-number-dc', [mobileNumberTrackController::class, 'trackMobileNumberDC']);

Route::post('/set-track-response-hc', [mobileNumberTrackController::class, 'setTrackDetailsHC']);

Route::post('/set-track-response-dc', [mobileNumberTrackController::class, 'setTrackDetailsDC']);

Route::post('/certified-copy/download-zip', [DownloadCertifiedCopyController::class, 'downloadZip']);

Route::get('/download-district-file/{filename}', [DownloadCertifiedCopyController::class, 'downloadDistrictCourtFile']);

Route::post('/double-verification', [PaymentController::class, 'doubleVerification']);

Route::post('/verify-jegras-payment', [PaymentController::class, 'verifyJegrasPayment']);

Route::get('/refresh-track-status-hc', [mobileNumberTrackController::class, 'refreshTrackDetailsHCFromSession'])->name('refresh.track.status.hc');

Route::get('/refresh-track-status-dc', [mobileNumberTrackController::class, 'refreshTrackDetailsDCFromSession'])->name('refresh.track.status.dc');

Route::post('/logout-tracking', [mobileNumberTrackController::class, 'logoutTracking'])->name('logout.tracking');

//**********************************************************admin routes **************************************************************

Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::get('/admin/logout', function () {
    abort(405, 'Logout must be a POST request');
});

Route::middleware([AuthenticateUser::class,'role.permission'])->group(function () {
    Route::get('/admin/index', function () {
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

Route::get('/admin/download-order-copy/{fileName}/{date}', [HcWebApplicationController::class, 'downloadOrderCopy'])->name('admin.downloadOrderCopy');

Route::get('/admin/delete-order-copy/{orderNumber}', [HcWebApplicationController::class, 'deleteOrderCopy'])->name('admin.deleteOrderCopy');

Route::get('/admin/payment-parameter-list-dc', [DCPaymentParameterController::class, 'parameterList'])->name('payment_parameter_list_dc');  

Route::post('/admin/payment-parameter-dc/update', [DCPaymentParameterController::class, 'update'])->name('payment_parameter_update_dc');

Route::get('/delete-order-copy/{application_number}/{order_number}', [HcWebApplicationController::class, 'deleteOrderCopy'])->name('admin.deleteOrderCopy');

Route::post('/hc-web-application/send-deficit-notification', [HcWebApplicationController::class, 'sendDeficitNotification'])
    ->name('hc-web-application.send-deficit-notification');

Route::post('/hc-web-application/send-ready-notification', [HcWebApplicationController::class, 'sendReadyNotification'])
->name('hc-web-application.send-ready-notification'); 

Route::get('/admin/hc-other-copy-view/{encryptedAppNumber}', [HcOtherCopyController::class, 'ViewHcOtherCopy'])->name('hc_other_copy_view');    

 Route::post('/admin/hc-other-copy/upload', [HcOtherCopyController::class, 'uploadDocument'])->name('upload.document');

Route::post('/admin/hc-other-copy/delete', [HcOtherCopyController::class, 'deleteDocument'])->name('delete.document');

Route::post('/admin/hc-other-copy/send-notification', [HcOtherCopyController::class, 'sendNotification'])->name('hc-other-copy.send-notification');

Route::post('/admin/hc-other-copy/reject', [HcOtherCopyController::class, 'rejectApplication'])->name('hc-other-copy.reject'); 

Route::get('/admin/hc-rejected-application', [HcOtherCopyController::class, 'rejectedHcOtherCopy'])->name('hc_other_copy_rejected_application');  

Route::get('/admin/hc-paid-application', [HcOtherCopyPaidController::class, 'paidHcOtherCopyList'])->name('hc_other_copy_paid_application');  

Route::get('/admin/hc-paid-copy-view/{encryptedAppNumber}', [HcOtherCopyPaidController::class, 'ViewHcOtherCopy'])->name('hc_paid_copy_view');   

// Route For DC other copy
Route::get('/admin/dc-other-copy-view/{encryptedAppNumber}', [DcOtherCopyController::class, 'ViewDcOtherCopy'])->name('dc_other_copy_view');  

Route::post('/admin/dc-other-copy/upload', [DcOtherCopyController::class, 'uploadDocument'])->name('upload.dcdocument');

Route::post('/admin/dc-other-copy/delete', [DcOtherCopyController::class, 'deleteDocument'])->name('delete.dcdocument');

Route::post('/admin/dc-other-copy/send-notification', [DcOtherCopyController::class, 'sendNotification'])->name('dc-other-copy.send-notification');

Route::post('/admin/dc-other-copy/reject', [DcOtherCopyController::class, 'rejectApplication'])->name('dc-other-copy.reject'); 

Route::get('/admin/dc-rejected-application', [DcOtherCopyController::class, 'rejectedDcOtherCopy'])->name('dc_other_copy_rejected_application'); 

Route::get('/admin/dc-paid-application', [DcOtherCopyController::class, 'paidDcOtherCopyList'])->name('dc_other_copy_paid_application');

Route::get('/admin/dc-paid-copy-view/{encryptedAppNumber}', [DcOtherCopyPaidController::class, 'ViewDcOtherCopy'])->name('dc_paid_copy_view'); 

Route::post('/upload-certified-copy/{id}', [DcOtherCopyPaidController::class, 'uploadCertifiedCopy'])->name('upload.certified.copy');

Route::post('/admin/process-pdf', [PdfController::class, 'attachStampAndHeader'])->name('admin.attachStampAndHeader');

Route::post('/admin/check-pdf-compatibility', [PdfController::class, 'checkPdfCompatibility'])->name('admin.checkPdfCompatibility');

Route::post('/delete-certified-copy/{id}', [DcOtherCopyPaidController::class, 'deleteCertifiedCopy'])->name('delete.certified.copy');

Route::post('/upload-hcoth-certified-copy/{id}', [HcOtherCopyPaidController::class, 'uploadCertifiedCopy'])->name('upload.hcothcertified.copy');

Route::post('/delete-hcoth-certified-copy/{id}', [HcOtherCopyPaidController::class, 'deleteCertifiedCopy'])->name('delete.hcothcertified.copy');

Route::post('/admin/get-pdf', [GetPdfController::class, 'fetchPdf']);

Route::post('/admin/hc-process-pdf', [HcPdfController::class, 'attachStampAndHeader'])->name('admin.attachStampAndHeader');

Route::post('/admin/hc-check-pdf-compatibility', [HcPdfController::class, 'checkPdfCompatibility'])->name('admin.checkPdfCompatibility');
 
Route::post('/admin/save-raw-pdf', [RawPdfController::class, 'save'])->name('admin.saveRawPdf');

Route::get('/admin/digital_signature', [DigitalSignatureController::class, 'addDigitalSignature'])->name('digital_signature');

Route::post('/admin/digital_signature/pdf', [DigitalSignatureController::class, 'generatePdf'])->name('digital_signature.pdf');


Route::post('/send-certified-notification', [DcOtherCopyPaidController::class, 'sendCertifiedCopyNotification'])->name('send.certified.notification');

Route::post('/send-hc-certified-notification', [HcOtherCopyPaidController::class, 'sendCertifiedCopyNotification'])->name('send.hc.certified.notification');

Route::post('/admin/get-dc-pdf', [GetPdfController::class, 'fetchDcPdf']);

Route::get('/admin/dc-web-application', [DcWebApplicationController::class, 'listDcWebApplication'])->name('dc_web_application_list');

Route::get('/admin/dc-web-pending-application', [DcWebApplicationController::class, 'listOfPendingDcWebApplication'])->name('dc_web_application_pending_list');

Route::get('/admin/dc-web-deliver-application', [DcWebApplicationController::class, 'listOfDeliveredDcWebApplication'])->name('dc_web_application_deliver_list');

Route::get('/admin/dc-web-application/{encryptedAppNumber}', [DcWebApplicationController::class, 'viewDcWebApplication'])
    ->name('dc-web-application.view');

Route::post('/admin/upload-dc-order-copy', [DcWebApplicationController::class, 'uploadDcOrderCopy'])->name('admin.uploadDcOrderCopy');

Route::get('/admin/download-dc-order-copy/{fileName}/{date}', [DcWebApplicationController::class, 'downloadDcOrderCopy'])->name('admin.downloadDcOrderCopy');

Route::get('/delete-dc-order-copy/{application_number}/{order_number}/{dist_name}', [DcWebApplicationController::class, 'deleteDcOrderCopy'])->name('admin.deleteDcOrderCopy');

Route::post('/dc-web-application/send-dc-deficit-notification', [DcWebApplicationController::class, 'sendDcDeficitNotification'])
->name('dc-web-application.send-dc-deficit-notification');

Route::post('/dc-web-application/send-dc-ready-notification', [DcWebApplicationController::class, 'sendReadyDcNotification']) ->name('dc-web-application.send-dc-ready-notification'); 

// Route::get('/admin/index', [DashboardController::class, 'index'])->name('admin.index');
Route::get('/admin/index', [DashboardController::class, 'index'])->name('index');

Route::get('/admin/hc-web-delivered-application', [HcWebApplicationController::class, 'listHcWebApplicationDelivered'])->name('hc_web_application_delivered');

Route::get('/admin/hc-web-pending-application', [HcWebApplicationController::class, 'listHcWebApplicationPending'])->name('hc_web_application_pending');

Route::get('/admin/hc-other-pending-copy', [HcOtherCopyController::class, 'listHcOtherCopyPending'])->name('hc_other_copy');

Route::get('/admin/hc-other-delivered-copy', [HcOtherCopyController::class, 'listHcOtherCopyDelivered'])->name('hc_other_copy');

Route::get('/admin/dc-other-pending-copy', [DcOtherCopyController::class, 'listDcOtherCopyPending'])->name('dc_other_pending_copy');

Route::get('/admin/dc-other-delivered-copy', [DcOtherCopyController::class, 'listDcOtherCopyDelivered'])->name('dc_other_delivered_copy');

// Report section admin pannel 
Route::get('/admin/payment-report', [ReportController::class, 'paymentReport'])->name('payment_report');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/delivered-report', [ReportController::class, 'deliveredReport'])->name('delivered.report');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/pending-report', [ReportController::class, 'pendingReport'])->name('pending.report');
});

Route::get('/admin/activity-log-report', [ReportController::class, 'logsReport'])->name('admin.logs.report');

// dc report section 

Route::get('/admin/payment-report-dc', [ReportController::class, 'paymentReportDC'])->name('payment_report_dc');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/delivered-report-dc', [ReportController::class, 'deliveredReportDC'])->name('delivered.report_dc');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/pending-report-dc', [ReportController::class, 'pendingReportDC'])->name('pending.report_dc');
});

Route::get('/admin/activity-log-report-dc', [ReportController::class, 'logsReportDC'])->name('admin.logs.report_dc');

});