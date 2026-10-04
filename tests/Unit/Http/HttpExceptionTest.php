<?php

declare(strict_types=1);

namespace Tests\Unit\Http;

use Bulbula\Http\Exception\MalformedRequestException;
use Bulbula\Http\Exception\MethodNotAllowedException;
use Bulbula\Http\Exception\NotFoundException;
use Bulbula\Http\Exception\RouteNotDefinedException;
use Bulbula\Http\Method;
use Bulbula\Http\Status;
use LogicException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(NotFoundException::class)]
#[CoversClass(MethodNotAllowedException::class)]
#[CoversClass(MalformedRequestException::class)]
#[CoversClass(RouteNotDefinedException::class)]
final class HttpExceptionTest extends TestCase
{
    public function test_a_not_found_carries_the_path_and_the_status(): void
    {
        $exception = NotFoundException::forPath('/missing');

        self::assertInstanceOf(RuntimeException::class, $exception);
        self::assertSame(Status::NotFound, $exception->status());
        self::assertSame(404, $exception->getCode());
        self::assertSame('No route matches [/missing].', $exception->getMessage());
        self::assertSame([], $exception->headers());
    }

    public function test_a_method_not_allowed_advertises_the_allowed_verbs(): void
    {
        $exception = MethodNotAllowedException::forPath('/health', Method::Post, [Method::Get, Method::Head]);

        self::assertSame(Status::MethodNotAllowed, $exception->status());
        self::assertSame(405, $exception->getCode());
        self::assertSame('Method [POST] is not allowed for [/health].', $exception->getMessage());
        self::assertSame(['Allow' => 'GET, HEAD'], $exception->headers());
    }

    public function test_a_malformed_json_body_is_a_bad_request(): void
    {
        $exception = MalformedRequestException::invalidJsonBody();

        self::assertSame(Status::BadRequest, $exception->status());
        self::assertSame('Request body is not valid JSON.', $exception->getMessage());
    }

    public function test_an_unsupported_method_is_reported_with_the_normalised_verb(): void
    {
        self::assertSame(
            'Unsupported HTTP method [TRACE].',
            MalformedRequestException::unsupportedMethod(' trace ')->getMessage(),
        );
    }

    public function test_url_generation_failures_are_programming_errors(): void
    {
        self::assertInstanceOf(LogicException::class, RouteNotDefinedException::named('x'));
        self::assertSame(
            'No route is registered under the name [x].',
            RouteNotDefinedException::named('x')->getMessage(),
        );
        self::assertSame(
            'Route [x] cannot be built with the given parameters.',
            RouteNotDefinedException::missingParameters('x')->getMessage(),
        );
        self::assertSame(
            'Route name [x] is already registered.',
            RouteNotDefinedException::duplicateName('x')->getMessage(),
        );
    }
}
