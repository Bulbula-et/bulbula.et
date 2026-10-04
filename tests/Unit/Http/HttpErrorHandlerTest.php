<?php

declare(strict_types=1);

namespace Tests\Unit\Http;

use Bulbula\Error\ErrorHandler;
use Bulbula\Http\Exception\MethodNotAllowedException;
use Bulbula\Http\Exception\NotFoundException;
use Bulbula\Http\HttpErrorHandler;
use Bulbula\Http\Method;
use Bulbula\Http\Request;
use Bulbula\Http\Status;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(HttpErrorHandler::class)]
final class HttpErrorHandlerTest extends TestCase
{
    private TestHandler $log;

    protected function setUp(): void
    {
        $this->log = new TestHandler();

        parent::setUp();
    }

    public function test_a_not_found_is_rendered_as_plain_text_for_a_browser(): void
    {
        $response = $this->handler(debug: false)->render($this->webRequest(), NotFoundException::forPath('/missing'));

        self::assertSame(Status::NotFound, $response->status());
        self::assertSame('text/plain; charset=utf-8', $response->header('Content-Type'));
        self::assertSame(
            "404 Not Found\nNo route matches [/missing].\n",
            $response->body(),
        );
    }

    public function test_a_not_found_is_rendered_as_json_for_an_api_client(): void
    {
        $response = $this->handler(debug: false)->render($this->apiRequest(), NotFoundException::forPath('/api/v1/x'));

        self::assertSame(Status::NotFound, $response->status());
        self::assertSame('application/json; charset=utf-8', $response->header('Content-Type'));
        self::assertSame(
            ['error' => [
                'status' => 404,
                'title' => 'Not Found',
                'message' => 'No route matches [/api/v1/x].',
            ]],
            $this->decode($response->body()),
        );
    }

    public function test_client_errors_are_never_logged(): void
    {
        $this->handler(debug: false)->render($this->apiRequest(), NotFoundException::forPath('/api/v1/x'));

        self::assertSame([], $this->log->getRecords());
    }

    public function test_the_allow_header_of_a_method_not_allowed_survives_rendering(): void
    {
        $response = $this->handler(debug: false)->render(
            $this->webRequest(),
            MethodNotAllowedException::forPath('/health', Method::Post, [Method::Get]),
        );

        self::assertSame(Status::MethodNotAllowed, $response->status());
        self::assertSame('GET', $response->header('Allow'));
    }

    public function test_a_server_error_is_opaque_in_production_and_carries_a_reference(): void
    {
        $response = $this->handler(debug: false)->render($this->apiRequest(), new RuntimeException('database exploded'));

        $payload = $this->decode($response->body());
        $error = $payload['error'] ?? null;

        self::assertSame(Status::InternalServerError, $response->status());
        self::assertIsArray($error);
        self::assertSame('Something went wrong. Please try again later.', $error['message'] ?? null);
        self::assertSame('Internal Server Error', $error['title'] ?? null);
        self::assertArrayNotHasKey('debug', $error);
        self::assertIsString($error['reference'] ?? null);
        self::assertStringNotContainsString('database exploded', $response->body());
    }

    public function test_a_server_error_is_logged_with_the_reference_shown_to_the_client(): void
    {
        $response = $this->handler(debug: false)->render($this->apiRequest(), new RuntimeException('database exploded'));

        $error = $this->decode($response->body())['error'] ?? null;
        $records = $this->log->getRecords();

        self::assertIsArray($error);
        self::assertCount(1, $records);
        self::assertSame('database exploded', $records[0]->message);
        self::assertSame($error['reference'] ?? null, $records[0]->context['reference'] ?? null);
    }

    public function test_debug_mode_adds_diagnostics_to_the_json_payload(): void
    {
        $response = $this->handler(debug: true)->render($this->apiRequest(), new RuntimeException('database exploded'));

        $debug = $this->decode($response->body())['error']['debug'] ?? null;

        self::assertIsArray($debug);
        self::assertSame(RuntimeException::class, $debug['exception'] ?? null);
        self::assertSame('database exploded', $debug['message'] ?? null);
        self::assertSame(__FILE__, $debug['file'] ?? null);
        self::assertIsInt($debug['line'] ?? null);
        self::assertIsString($debug['trace'] ?? null);
    }

    public function test_debug_mode_adds_diagnostics_to_the_web_response(): void
    {
        $response = $this->handler(debug: true)->render($this->webRequest(), new RuntimeException('database exploded'));

        self::assertStringContainsString('500 Internal Server Error', $response->body());
        self::assertStringContainsString('Reference: ', $response->body());
        self::assertStringContainsString('RuntimeException: database exploded', $response->body());
        self::assertStringContainsString(__FILE__, $response->body());
    }

    public function test_the_web_response_stays_opaque_in_production(): void
    {
        $response = $this->handler(debug: false)->render($this->webRequest(), new RuntimeException('database exploded'));

        self::assertSame(Status::InternalServerError, $response->status());
        self::assertStringStartsWith('500 Internal Server Error', $response->body());
        self::assertStringEndsWith("\n", $response->body());
        self::assertStringContainsString('Something went wrong. Please try again later.', $response->body());
        self::assertStringContainsString('Reference: ', $response->body());
        self::assertStringNotContainsString('database exploded', $response->body());
        self::assertStringNotContainsString('RuntimeException', $response->body());
    }

    public function test_a_client_error_has_no_reference_in_the_web_response(): void
    {
        $response = $this->handler(debug: false)->render($this->webRequest(), NotFoundException::forPath('/missing'));

        self::assertStringNotContainsString('Reference:', $response->body());
    }

    private function handler(bool $debug): HttpErrorHandler
    {
        return new HttpErrorHandler(
            new ErrorHandler(new Logger('tests', [$this->log]), $debug),
            $debug,
        );
    }

    private function webRequest(): Request
    {
        return new Request(Method::Get, '/missing', [], [], ['accept' => 'text/html']);
    }

    private function apiRequest(): Request
    {
        return new Request(Method::Get, '/api/v1/x');
    }

    /**
     * @return array<string, mixed>
     */
    private function decode(string $body): array
    {
        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($body, true, 32, JSON_THROW_ON_ERROR);

        return $decoded;
    }
}
