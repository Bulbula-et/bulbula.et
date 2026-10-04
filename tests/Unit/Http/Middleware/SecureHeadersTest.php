<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use Bulbula\Http\Method;
use Bulbula\Http\Middleware\SecureHeaders;
use Bulbula\Http\Request;
use Bulbula\Http\Response;
use Bulbula\Http\Status;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(SecureHeaders::class)]
final class SecureHeadersTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string}>
     */
    public static function expectedHeaders(): iterable
    {
        yield 'nosniff' => ['X-Content-Type-Options', 'nosniff'];
        yield 'framing' => ['X-Frame-Options', 'DENY'];
        yield 'referrer' => ['Referrer-Policy', 'strict-origin-when-cross-origin'];
        yield 'permissions' => ['Permissions-Policy', 'geolocation=(), microphone=(), camera=()'];
        yield 'csp' => [
            'Content-Security-Policy',
            "default-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline'; "
                . "object-src 'none'; base-uri 'self'; frame-ancestors 'none'",
        ];
    }
    #[DataProvider('expectedHeaders')]
    public function test_it_adds_the_baseline_headers(string $header, string $value): void
    {
        $response = $this->handle(new Request(Method::Get, '/'));

        self::assertSame($value, $response->header($header));
    }

    public function test_hsts_is_only_sent_over_https(): void
    {
        $insecure = $this->handle(new Request(Method::Get, '/'));
        $secure = $this->handle(new Request(Method::Get, '/', [], [], [], '', [], true));

        self::assertNull($insecure->header('Strict-Transport-Security'));
        self::assertSame(SecureHeaders::STRICT_TRANSPORT_SECURITY, $secure->header('Strict-Transport-Security'));
    }

    public function test_the_policy_denies_framing_and_foreign_sources(): void
    {
        $policy = SecureHeaders::contentSecurityPolicy();

        self::assertStringContainsString("default-src 'self'", $policy);
        self::assertStringContainsString("frame-ancestors 'none'", $policy);
        self::assertStringContainsString("object-src 'none'", $policy);
    }

    public function test_it_keeps_the_response_of_the_next_handler(): void
    {
        $response = new SecureHeaders()->process(
            new Request(Method::Get, '/'),
            static fn (): Response => Response::text('body', Status::Created, ['X-Existing' => 'kept']),
        );

        self::assertSame('body', $response->body());
        self::assertSame(Status::Created, $response->status());
        self::assertSame('kept', $response->header('X-Existing'));
    }

    public function test_the_baseline_headers_are_enforced_over_weaker_values(): void
    {
        $response = new SecureHeaders()->process(
            new Request(Method::Get, '/'),
            static fn (): Response => Response::text('body', Status::Ok, ['X-Frame-Options' => 'SAMEORIGIN']),
        );

        self::assertSame('DENY', $response->header('X-Frame-Options'));
    }

    private function handle(Request $request): Response
    {
        return new SecureHeaders()->process($request, static fn (): Response => Response::text('body'));
    }
}
