<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionDataController extends Controller
{
    public function updateEstdCode(Request $request)
    {
        $request->validate([
            'est_code' => 'required|string'
        ]);

        Session::put('user.estd_code', $request->est_code);

        return redirect()->back(); // Redirect back after setting session
    }
    public function setCaseInfoData(Request $request) { 
        $caseInfoDetails = $request->input('caseInfoDetails'); 
        Session::put('caseInfoDetails', $caseInfoDetails);
        Session::save();  
        return response()->json([
            'message' => 'Case Info Data Stored Successfully!',
            'session_data' => Session::all() 
        ]);
    }
    public function getCaseInfoData() {
        // Ensure session data is saved before retrieving it
        Session::save();
        return response()->json([
            'session_id' => Session::getId(),
            'session_data' => Session::all() // Retrieves fresh session data
        ]);
    }
    public function setPaybleAmount(Request $request) { 
        $paybleAmount = $request->input('paybleAmount'); 
        Session::put('paybleAmount', $paybleAmount);
        Session::save(); 
        
        return response()->json(Session::all());
    }
    public function getPaybleAmount(){
        Session::save();
        $paybleAmount = Session::get('paybleAmount');
        return response()->json(['paybleAmount' => $paybleAmount]);
    }
    
}


