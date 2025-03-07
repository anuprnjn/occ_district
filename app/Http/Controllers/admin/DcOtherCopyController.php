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
            $dist_code = session('user.dist_code');
            $estd_code = session('user.estd_code');

            // Retrieve data using Laravel Query Builder
            $dcuserdata = DB::table('district_court_applicant_registration as dc')
                ->select(
                    'dc.*', // Select all columns from dc
                    'ct.type_name as case_type_name' // Fetch case type name from case type table
                )
                ->leftJoin('districts_case_type as ct', 'dc.case_type', '=', 'ct.case_type')
                ->where('dc.district_code', $dist_code) // Filter by district_code
                ->where('dc.establishment_code', $estd_code) // Filter by establishment_code
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
