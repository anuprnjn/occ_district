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
}
