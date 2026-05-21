<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XssProtection
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$val) {
            if (is_string($val)) {
                $val = $this->cleanXss($val);
            }
        });

        $request->merge($input);

        return $next($request);
    }

    /**
     * Sanitize string against XSS vectors.
     */
    protected function cleanXss(string $value): string
    {
        // Remove script tags and their content
        $value = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $value);

        // Remove event handlers (e.g., onclick, onload, onerror, etc.)
        $value = preg_replace('#\son[a-zA-Z]+=\s*["\'].*?["\']#is', '', $value);
        $value = preg_replace('#\son[a-zA-Z]+=\s*[^>\s]+#is', '', $value);

        // Remove javascript: and data: URI schemes in href or src attributes
        $value = preg_replace('#href=[\'"]\s*(javascript|data):.*?[\'"]#is', 'href="#"', $value);
        $value = preg_replace('#src=[\'"]\s*(javascript|data):.*?[\'"]#is', 'src=""', $value);

        return $value;
    }
}
