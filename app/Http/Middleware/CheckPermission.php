<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $permission
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permission = null): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'You must be logged in to access this page.');
        }

        $user = Auth::user();
        
        // Super Admin has access to everything
        if ($user->hasRole('Super Admin')) {
            return $next($request);
        }

        // Check specific permission if provided
        if ($permission && !$user->hasPermission($permission)) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
