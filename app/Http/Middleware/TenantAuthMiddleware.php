<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('tenants')->check()) {
            Log::debug('TenantAuthMiddleware: tenant not authenticated');
            return redirect('login-tenant');
        }

        Log::debug('TenantAuthMiddleware: tenant authenticated', ['user_id' => Auth::guard('tenants')->id()]);
        return $next($request);
    }


}
