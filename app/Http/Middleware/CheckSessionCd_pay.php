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

        if (!Session::has('caseInfoDetails')) {
            return redirect('/caseInformation')->with('error', 'Session not available');
        }
        if (!Session::has('PendingCaseInfoDetails')) {
            return redirect('/pendingPayments')->with('error', 'Session not available');
        }

        return $next($request);
    }
}