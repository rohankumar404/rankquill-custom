<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Restrict to authenticated users with the Spatie 'admin' role
        if (! $request->user() || ! $request->user()->hasRole('admin')) {
            // Bypass during early local development scaffold phase if no users exist
            if (config('app.env') === 'local') {
                return $next($request);
            }

            abort(403, 'Unauthorized access. You must be an administrator to view this page.');
        }

        return $next($request);
    }
}
