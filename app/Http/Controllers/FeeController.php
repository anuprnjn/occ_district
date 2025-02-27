<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FeeController extends Controller
{
    // Store urgent_fee in session
    public function setUrgentFee(Request $request)
    {
        $urgentFee = $request->input('urgent_fee'); 
        Session::put('urgent_fee', $urgentFee);

        return response()->json(['message' => 'Urgent fee stored in session']);
    }

    // Retrieve urgent_fee from session
    public function getUrgentFee()
    {
        $urgentFee = Session::get('urgent_fee'); 
        return response()->json(['urgent_fee' => $urgentFee]);
    }
}