<?php

declare(strict_types=1);

namespace Tests\Unit\Http;

use Bulbula\Http\Response;
use JsonException;
use Bulbula\Http\Status;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(Response::class)]
final class ResponseTest extends TestCase
{
    public function test_it_defaults_to_an_empty_successful_response(): void
    {
        $response = new Response();

        self::assertSame(Status::Ok, $response->status());
        self::assertSame('', $response->body());
        self::assertSame([], $response->headers());
    }

    public function test_it_builds_a_text_response(): void
    {
        $response = Response::text('plain', Status::NotFound, ['X-Trace' => 'abc']);

        self::assertSame(Status::NotFound, $response->status());
        self::assertSame('plain', $response->body());
        self::assertSame('text/plain; charset=utf-8', $response->header('Content-Type'));
        self::assertSame('abc', $response->header('X-Trace'));
    }

    public function test_it_builds_an_html_response(): void
    {
        $response = Response::html('<p>hi</p>');

        self::assertSame(Status::Ok, $response->status());
        self::assertSame('<p>hi</p>', $response->body());
        self::assertSame('text/html; charset=utf-8', $response->header('Content-Type'));
    }

    public function test_it_builds_a_json_response(): void
    {
        $response = Response::json(['status' => 'pass', 'count' => 2], Status::Created);

        self::assertSame(Status::Created, $response->status());
        self::assertSame('{"status":"pass","count":2}', $response->body());
        self::assertSame('application/json; charset=utf-8', $response->header('Content-Type'));
    }

    public function test_json_encoding_escapes_characters_that_are_unsafe_in_html(): void
    {
        $response = Response::json(['html' => '<script>&"\'</script>', 'path' => '/a/b', 'text' => 'ሰላም']);

        self::assertStringNotContainsString('<script>', $response->body());
        self::assertStringContainsString('\u003C', $response->body());
        self::assertStringContainsString('\u0026', $response->body());
        self::assertStringContainsString('\u0027', $response->body());
        self::assertStringContainsString('\u0022', $response->body());
        self::assertStringContainsString('/a/b', $response->body());
        self::assertStringContainsString('ሰላም', $response->body());
    }

    public function test_it_refuses_to_encode_an_invalid_payload(): void
    {
        try {
            Response::json(fopen('php://memory', 'rb'));
        } catch (RuntimeException $exception) {
            self::assertSame('Response payload could not be encoded as JSON.', $exception->getMessage());
            self::assertSame(0, $exception->getCode());
            self::assertInstanceOf(JsonException::class, $exception->getPrevious());

            return;
        }

        self::fail('An unencodable payload must be rejected.');
    }

    public function test_a_no_content_response_never_carries_a_body(): void
    {
        self::assertSame(Status::NoContent, Response::noContent()->status());
        self::assertSame('', Response::noContent()->body());
        self::assertSame('', new Response(Status::NoContent, 'ignored')->body());
    }

    public function test_it_builds_a_redirect(): void
    {
        $response = Response::redirect('/health');

        self::assertSame(Status::Found, $response->status());
        self::assertSame('/health', $response->header('Location'));
        self::assertSame('', $response->body());
    }

    public function test_a_redirect_can_be_permanent_and_carry_extra_headers(): void
    {
        $response = Response::redirect('/health', Status::MovedPermanently, ['Cache-Control' => 'no-store']);

        self::assertSame(Status::MovedPermanently, $response->status());
        self::assertSame('no-store', $response->header('Cache-Control'));
    }

    public function test_a_missing_header_returns_the_default(): void
    {
        self::assertNull(new Response()->header('X-Missing'));
        self::assertSame('fallback', new Response()->header('X-Missing', 'fallback'));
    }

    public function test_headers_can_be_added_without_mutating_the_original(): void
    {
        $response = Response::text('body');

        $tagged = $response->withHeader('X-Trace', 'abc');

        self::assertNull($response->header('X-Trace'));
        self::assertSame('abc', $tagged->header('X-Trace'));
        self::assertSame('body', $tagged->body());
        self::assertSame('text/plain; charset=utf-8', $tagged->header('Content-Type'));
    }

    public function test_several_headers_can_be_added_at_once(): void
    {
        $response = new Response(Status::Ok, 'body', ['Content-Type' => 'text/plain'])
            ->withHeaders(['X-One' => '1', 'X-Two' => '2']);

        self::assertSame('1', $response->header('X-One'));
        self::assertSame('2', $response->header('X-Two'));
        self::assertSame('text/plain', $response->header('Content-Type'));
    }

    public function test_existing_headers_win_over_defaults_but_lose_to_explicit_overrides(): void
    {
        $explicit = Response::text('body', Status::Ok, ['Content-Type' => 'text/csv']);

        self::assertSame('text/plain; charset=utf-8', $explicit->header('Content-Type'));
        self::assertSame('text/csv', $explicit->withHeader('Content-Type', 'text/csv')->header('Content-Type'));
        self::assertSame(
            'text/csv',
            $explicit->withHeaders(['Content-Type' => 'text/csv'])->header('Content-Type'),
        );
    }

    public function test_the_status_can_be_replaced(): void
    {
        $response = Response::json(['a' => 1])->withStatus(Status::ServiceUnavailable);

        self::assertSame(Status::ServiceUnavailable, $response->status());
        self::assertSame('{"a":1}', $response->body());
        self::assertSame('application/json; charset=utf-8', $response->header('Content-Type'));
    }
}
