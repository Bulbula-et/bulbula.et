<?php

declare(strict_types=1);

namespace Tests\Unit\Http;

use Bulbula\Http\BootstrapFailure;
use Bulbula\Http\Exception\MalformedRequestException;
use Bulbula\Http\Exception\NotFoundException;
use Bulbula\Http\Status;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(BootstrapFailure::class)]
final class BootstrapFailureTest extends TestCase
{
    public function test_a_boot_failure_is_a_server_error(): void
    {
        $response = BootstrapFailure::response(new RuntimeException('Log directory is not writable.'), false);

        self::assertSame(Status::InternalServerError, $response->status());
        self::assertSame('text/plain; charset=utf-8', $response->header('Content-Type'));
        self::assertSame('no-store', $response->header('Cache-Control'));
    }

    public function test_the_production_body_explains_nothing(): void
    {
        $response = BootstrapFailure::response(new RuntimeException('Database password is hunter2.'), false);

        self::assertSame("500 Internal Server Error\nThe application could not start.\n", $response->body());
    }

    public function test_the_debug_body_names_the_failure_and_its_origin(): void
    {
        $throwable = new RuntimeException('Log directory is not writable.');

        $response = BootstrapFailure::response($throwable, true);

        self::assertSame(sprintf(
            "500 Internal Server Error\nRuntimeException: Log directory is not writable.\nin %s:%d\n",
            $throwable->getFile(),
            $throwable->getLine(),
        ), $response->body());
    }

    public function test_a_malformed_request_keeps_its_own_status(): void
    {
        $response = BootstrapFailure::response(MalformedRequestException::invalidJsonBody(), false);

        self::assertSame(Status::BadRequest, $response->status());
        self::assertSame("400 Bad Request\nThe application could not start.\n", $response->body());
    }

    public function test_an_unsupported_method_is_rejected_before_the_kernel_exists(): void
    {
        $response = BootstrapFailure::response(MalformedRequestException::unsupportedMethod('BREW'), false);

        self::assertSame(Status::MethodNotAllowed, $response->status());
        self::assertSame("405 Method Not Allowed\nThe application could not start.\n", $response->body());
    }

    public function test_an_http_exception_status_also_drives_the_debug_body(): void
    {
        $throwable = NotFoundException::forPath('/missing');

        $response = BootstrapFailure::response($throwable, true);

        self::assertSame(Status::NotFound, $response->status());
        self::assertStringStartsWith("404 Not Found\n", $response->body());
        self::assertStringContainsString('No route matches [/missing].', $response->body());
    }
}
