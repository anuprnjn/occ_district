<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HcWebApplicationController extends Controller
{
    // Fetch HC User List from database
    public function listHcWebApplication()
    {
        try {
            // Retrieve data using Laravel Query Builder
            $hcuserdata = DB::table('hc_order_copy_applicant_registration as apr')
                ->select(
                    'apr.application_id',
                    'apr.application_number',
                    'apr.cino',
                    'apr.applicant_name',
                    'apr.mobile_number',
                    'apr.email',
                    DB::raw('COALESCE(ct1.type_name, ct2.type_name) AS type_name'), // Handles case type and filing case type
                    'apr.case_type',
                    'apr.case_number',
                    'apr.case_year',
                    'apr.filingcase_type',
                    'apr.filing_number',
                    'apr.filing_year',
                    'apr.request_mode',
                    'apr.applied_by',
                    'apr.advocate_registration_number',
                    'apr.status',
                    'apr.payment_status',
                    'apr.created_by',
                    'apr.updated_by',
                    'apr.created_at',
                    'apr.updated_at'
                )
                ->leftJoin('high_court_case_type as ct1', 'ct1.case_type', '=', 'apr.case_type')
                ->leftJoin('high_court_case_type as ct2', 'ct2.case_type', '=', 'apr.filingcase_type')
                ->orderBy('apr.created_at', 'desc')
                ->get();

            // Return view with data using compact
            return view('admin.hc_web_copy.hc_web_application_list', compact('hcuserdata'));

        } catch (\Exception $e) {
            Log::error('Error fetching HC User data', ['error' => $e->getMessage()]);

            return view('admin.hc_web_copy.hc_web_application_list', compact('hcuserdata'))
                ->with('error', 'An error occurred while fetching data.');
        }
    }
    public function viewHcWebApplication($encryptedAppNumber)
{
    try {
        // Decrypt the application number
        $appNumber = Crypt::decrypt($encryptedAppNumber);

        $hcuser = DB::table('hc_order_copy_applicant_registration as apr')
            ->select(
                'apr.application_id',
                'apr.application_number',
                'apr.cino',
                'apr.applicant_name',
                'apr.mobile_number',
                'apr.email',
                DB::raw('COALESCE(ct1.type_name, ct2.type_name) AS type_name'),
                'apr.case_type',
                'apr.case_number',
                'apr.case_year',
                'apr.filingcase_type',
                'apr.filing_number',
                'apr.filing_year',
                'apr.request_mode',
                'apr.applied_by',
                'apr.advocate_registration_number',
                'apr.status',
                'apr.payment_status',
                'apr.created_by',
                'apr.updated_by',
                'apr.created_at',
                'apr.updated_at'
            )
            ->leftJoin('high_court_case_type as ct1', 'ct1.case_type', '=', 'apr.case_type')
            ->leftJoin('high_court_case_type as ct2', 'ct2.case_type', '=', 'apr.filingcase_type')
            ->where('apr.application_number', $appNumber)
            ->first();

        if (!$hcuser) {
            return redirect()->route('hc-web-application.list')->with('error', 'Application not found.');
        }

        return view('admin.hc_web_copy.hc_web_application_view', compact('hcuser'));

    } catch (DecryptException $e) {
        return redirect()->route('hc-web-application.list')->with('error', 'Invalid request.');
    } catch (\Exception $e) {
        Log::error('Error fetching HC User details', ['error' => $e->getMessage()]);
        return redirect()->route('hc-web-application.list')->with('error', 'An error occurred while fetching details.');
    }
}
}
