<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthenticateUser
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in
        if (!Session::has('logged_in')) {
            return redirect('/login')->with('error', 'You must be logged in to access this page.');
        }

        return $next($request);
    }
}