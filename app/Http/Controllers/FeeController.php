<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FeeController extends Controller
{
    public function setUrgentFee(Request $request)
    {
        $urgentFee = $request->input('urgent_fee'); 
        Session::put('urgent_fee', $urgentFee);
        Session::save();
        dd(Session::all());
        
        return response()->json([
            'message' => 'Urgent fee stored in session',
        ]);
    }
    public function getUrgentFee()
    {
        $urgentFee = Session::get('urgent_fee', 5.00); 
        // dd(Session::all());
        return response()->json([
            'urgent_fee' => $urgentFee,
        ]);
    }
}