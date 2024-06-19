<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureLandlordIsAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('landlord')->check()) {
            // Not authenticated, redirect to landlord login
            return redirect()->route('login-landlord');
            // return redirect()->route('homepage');
        }

        return $next($request);
    }
}
