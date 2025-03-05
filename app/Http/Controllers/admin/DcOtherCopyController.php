<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DcOtherCopyController extends Controller
{
    // Fetch DC Other Copy List from database
    public function listDcOtherCopy()
    {
        try {
            // Retrieve data using Laravel Query Builder
            $dcuserdata = DB::table('district_court_applicant_registration as dc')
                ->select(
                    'dc.application_id',
                    'dc.application_number',
                    'dc.applicant_name',
                    'dc.mobile_number',
                    'dc.email',
                    'dc.case_type',
                    'ct.type_name as case_type_name', // Fetch case type name from case type table
                    'dc.case_filling_number',
                    'dc.case_filling_year',
                    'dc.selected_method',
                    'dc.request_mode',
                    'dc.required_document',
                    'dc.applied_by',
                    'dc.advocate_registration_number',
                    'dc.status',
                    'dc.created_by',
                    'dc.updated_by',
                    'dc.created_at',
                    'dc.updated_at'
                )
                ->leftJoin('districts_case_type as ct', 'dc.case_type', '=', 'ct.case_type')
                ->orderBy('dc.created_at', 'desc')
                ->get();

            // Return view with data using compact
            return view('admin.dc_other_copy.dc_other_copy_application_list', compact('dcuserdata'));

        } catch (\Exception $e) {
            Log::error('Error fetching DC Other Copy data', ['error' => $e->getMessage()]);

            return view('admin.dc_other_copy.dc_other_copy_application_list', ['dcuserdata' => []])
                ->with('error', 'An error occurred while fetching data.');
        }
    }
}
