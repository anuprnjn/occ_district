<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StoreHCCaseDataController extends Controller
{
    public function storeHCCaseDetails(Request $request)
    {
      
        $caseDetails = $request->input('caseDetails');
        Session::put('HcCaseDetailsNapix', $caseDetails);
      
        return response()->json([
            'message' => 'Case details stored in session successfully',
            'redirectLocation' => url('/caseInformation') 
        ]);
    }
}