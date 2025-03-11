<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class CheckSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {

        if (!Session::has('responseData')) {
            return redirect('/')->with('error', 'Session not available');
        }

        return $next($request);
    }
}