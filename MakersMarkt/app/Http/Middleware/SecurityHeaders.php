<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // ! Pure kutzooi dit, weghouwe anders laden de cdns niet meer
        // More secure CSP configuration
        // $response->header('Content-Security-Policy',
        //     "default-src 'self'; " .
        //     "script-src 'self' https://cdn.jsdelivr.net; " .
        //     "style-src 'self' https://fonts.googleapis.com; " .
        //     "font-src 'self' https://fonts.gstatic.com; " .
        //     "img-src 'self' data:; " .
        //     "connect-src 'self'; " .
        //     "base-uri 'self'; " .
        //     "form-action 'self'; " .
        //     "object-src 'none'; " .
        //     "frame-ancestors 'none';"
        // );

        // If Alpine.js or Tailwind requires unsafe-inline, use nonces instead
        // Example with nonce for a specific scripts:
        // $nonce = base64_encode(random_bytes(16));
        // $response->header('Content-Security-Policy',
        //     "script-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net;");

        // Other security headers
        $response->header('X-XSS-Protection', '1; mode=block');
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('Referrer-Policy', 'same-origin');
        $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->header('X-Frame-Options', 'DENY');
        $response->header('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
