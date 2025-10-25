<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If user is not authenticated, let auth middleware handle it
        if (!$user) {
            return $next($request);
        }

        // Check if user has admin or super-admin role (skip verification for admins)
        if ($user->hasAnyRole(['admin', 'super-admin'])) {
            return $next($request);
        }

        // Check if user is verified
        if (!$user->is_verified) {
            // Redirect to verification method selection page
            return redirect()->route('verification.method')
                ->with('warning', 'Please verify your account to continue.');
        }

        return $next($request);
    }
}
