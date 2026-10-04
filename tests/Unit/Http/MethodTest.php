<?php

declare(strict_types=1);

namespace Tests\Unit\Http;

use Bulbula\Http\Exception\MalformedRequestException;
use Bulbula\Http\Method;
use Bulbula\Http\Status;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Method::class)]
final class MethodTest extends TestCase
{
    /**
     * @return iterable<string, array{Method, bool}>
     */
    public static function bodyExpectations(): iterable
    {
        yield 'GET' => [Method::Get, false];
        yield 'HEAD' => [Method::Head, false];
        yield 'POST' => [Method::Post, true];
        yield 'PUT' => [Method::Put, true];
        yield 'PATCH' => [Method::Patch, true];
        yield 'DELETE' => [Method::Delete, false];
        yield 'OPTIONS' => [Method::Options, false];
    }
    public function test_it_resolves_a_known_verb(): void
    {
        self::assertSame(Method::Get, Method::fromName('GET'));
        self::assertSame(Method::Delete, Method::fromName('DELETE'));
    }

    public function test_it_normalises_case_and_surrounding_whitespace(): void
    {
        self::assertSame(Method::Post, Method::fromName('post'));
        self::assertSame(Method::Patch, Method::fromName("  Patch\n"));
    }

    public function test_it_rejects_an_unknown_verb(): void
    {
        $this->expectException(MalformedRequestException::class);
        $this->expectExceptionMessage('Unsupported HTTP method [TRACE].');

        Method::fromName('trace');
    }

    public function test_the_rejection_is_reported_as_method_not_allowed(): void
    {
        try {
            Method::fromName('BREW');
        } catch (MalformedRequestException $exception) {
            self::assertSame(Status::MethodNotAllowed, $exception->status());

            return;
        }

        self::fail('An unsupported method must be rejected.');
    }

    #[DataProvider('bodyExpectations')]
    public function test_it_knows_which_verbs_carry_a_body(Method $method, bool $expected): void
    {
        self::assertSame($expected, $method->expectsBody());
    }
}
