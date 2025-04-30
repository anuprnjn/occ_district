<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StoreDCCaseDataController extends Controller
{
    public function storeCaseDetails(Request $request)
    {

        $caseDetails = $request->input('caseDetails');
        Session::put('DcCaseDetailsNapix', $caseDetails);

        return response()->json([
            'message' => 'Case details stored in session successfully',
            'redirectLocation' => url('/caseInformationDc') 
        ]);
    }
}