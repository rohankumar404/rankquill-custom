<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Bypass if no users exist in the database (fresh setup safety mechanism)
        if (\App\Models\User::count() === 0) {
            return $next($request);
        }

        // Redirect to login if not authenticated
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Restrict to authenticated users with the Spatie 'admin' role
        if (! $request->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. You must be an administrator to view this page.');
        }

        return $next($request);
    }
}
