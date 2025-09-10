<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Please login to access admin panel.');
        }

        $admin = Auth::guard('admin')->user();
        if (!$admin->isActive()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Your admin account has been deactivated.');
        }

        return $next($request);
    }
}

class CheckAdminPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();

        if (!$admin->hasPermission($permission)) {
            abort(403, 'Insufficient admin permissions.');
        }

        return $next($request);
    }
}

class AdminRedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check()) {
            return redirect('admin/dashboard');
        }

        return $next($request);
    }
}
