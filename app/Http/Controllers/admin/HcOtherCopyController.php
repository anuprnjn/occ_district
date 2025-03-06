<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HcOtherCopyController extends Controller
{
    // Fetch HC Other Copy List from database
    public function listHcOtherCopy()
    {
        try {
            // Retrieve data using Laravel Query Builder
            $hcuserdata = DB::table('high_court_applicant_registration as hc')
                ->select(
                    'hc.application_id',
                    'hc.application_number',
                    'hc.applicant_name',
                    'hc.mobile_number',
                    'hc.email',
                    'hc.case_type',
                    'ct.type_name as case_type_name', // Fetch case type name from case type table
                    'hc.case_filling_number',
                    'hc.case_filling_year',
                    'hc.selected_method',
                    'hc.request_mode',
                    'hc.required_document',
                    'hc.applied_by',
                    'hc.advocate_registration_number',
                    'hc.status',
                    'hc.created_by',
                    'hc.updated_by',
                    'hc.created_at',
                    'hc.updated_at'
                )
                ->leftJoin('high_court_case_type as ct', 'hc.case_type', '=', 'ct.case_type')
                ->orderBy('hc.created_at', 'desc')
                ->get();

            // Return view with data using compact
            return view('admin.hc_other_copy.hc_other_copy_application_list', compact('hcuserdata'));

        } catch (\Exception $e) {
            Log::error('Error fetching HC Other Copy data', ['error' => $e->getMessage()]);

            return view('admin.hc_other_copy.hc_other_copy_application_list', ['hcuserdata' => []])
                ->with('error', 'An error occurred while fetching data.');
        }
    }
}
