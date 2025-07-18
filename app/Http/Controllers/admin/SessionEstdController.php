<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionEstdController extends Controller
{
    public function updateEstdCode(Request $request)
    {
        $request->validate([
            'est_code' => 'required|string'
        ]);

        Session::put('user.estd_code', $request->est_code);

        return redirect('/admin/index'); 
    }
}
