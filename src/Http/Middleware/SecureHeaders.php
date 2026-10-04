<?php

declare(strict_types=1);

namespace Bulbula\Http\Middleware;

use Bulbula\Http\Request;
use Bulbula\Http\Response;
use Closure;

/**
 * Adds the baseline security headers to every response.
 *
 * Deliberately conservative: only headers that are correct for an application
 * that serves its own assets and has no third-party embedding requirements.
 */
final readonly class SecureHeaders implements Middleware
{
    public const string STRICT_TRANSPORT_SECURITY = 'max-age=31536000; includeSubDomains';

    /**
     * The site serves its own assets only; inline styles are still allowed
     * because the pre-launch page ships with them.
     */
    private const array CONTENT_SECURITY_POLICY = [
        "default-src 'self'",
        "img-src 'self' data:",
        "style-src 'self' 'unsafe-inline'",
        "object-src 'none'",
        "base-uri 'self'",
        "frame-ancestors 'none'",
    ];

    public static function contentSecurityPolicy(): string
    {
        return implode('; ', self::CONTENT_SECURITY_POLICY);
    }

    public function process(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
            'Content-Security-Policy' => self::contentSecurityPolicy(),
        ];

        if ($request->isSecure()) {
            $headers['Strict-Transport-Security'] = self::STRICT_TRANSPORT_SECURITY;
        }

        return $response->withHeaders($headers);
    }
}
