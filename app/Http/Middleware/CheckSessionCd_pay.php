<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class CheckSessionCd_pay
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('caseInfoDetails') && !Session::has('PendingCaseInfoDetails')) {
            // You might want to customize the redirect based on the route
            return $request->is('pendingPayments*') 
                ? redirect('/pendingPayments')->with('error', 'Session not available')
                : redirect('/caseInformation')->with('error', 'Session not available');
        }
        
        return $next($request);
    }
}