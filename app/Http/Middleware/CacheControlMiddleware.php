<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheControlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Apply cache control to successful GET requests
        if ($request->isMethod('GET') && $response->getStatusCode() === 200) {
            $path = $request->getPathInfo();
            
            // Check if it is a build asset, storage conversion or other static asset path
            if (str_contains($path, '/build/') || str_contains($path, '/assets/') || str_contains($path, '/storage/')) {
                $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            } else {
                // For dynamic pages, we want browser cache revalidation
                $response->headers->set('Cache-Control', 'no-cache, private, must-revalidate');
            }
        }

        return $response;
    }
}
