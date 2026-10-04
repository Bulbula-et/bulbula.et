<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bulbula\Support\Env;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Env::class)]
final class EnvTest extends TestCase
{
    private const string KEY = 'BULBULA_TEST_VALUE';

    protected function tearDown(): void
    {
        unset($_ENV[self::KEY], $_SERVER[self::KEY]);
        putenv(self::KEY);

        parent::tearDown();
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function truthyValues(): iterable
    {
        yield 'true' => ['true'];
        yield 'uppercase true' => ['TRUE'];
        yield 'one' => ['1'];
        yield 'yes' => ['yes'];
        yield 'on' => ['on'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function falsyValues(): iterable
    {
        yield 'false' => ['false'];
        yield 'uppercase false' => ['FALSE'];
        yield 'zero' => ['0'];
        yield 'no' => ['no'];
        yield 'off' => ['off'];
        yield 'null' => ['null'];
    }

    public function test_raw_returns_null_when_the_variable_is_absent(): void
    {
        self::assertNull(Env::raw(self::KEY));
    }

    public function test_raw_reads_from_the_env_superglobal(): void
    {
        $_ENV[self::KEY] = 'from-env';

        self::assertSame('from-env', Env::raw(self::KEY));
    }

    public function test_raw_falls_back_to_the_server_superglobal(): void
    {
        $_SERVER[self::KEY] = 'from-server';

        self::assertSame('from-server', Env::raw(self::KEY));
    }

    public function test_raw_falls_back_to_getenv(): void
    {
        putenv(self::KEY . '=from-getenv');

        self::assertSame('from-getenv', Env::raw(self::KEY));
    }

    public function test_raw_prefers_env_over_server(): void
    {
        $_ENV[self::KEY] = 'from-env';
        $_SERVER[self::KEY] = 'from-server';

        self::assertSame('from-env', Env::raw(self::KEY));
    }

    public function test_raw_ignores_non_string_values(): void
    {
        $_ENV[self::KEY] = 42;

        self::assertNull(Env::raw(self::KEY));
    }

    public function test_raw_trims_surrounding_whitespace(): void
    {
        $_ENV[self::KEY] = "  spaced  ";

        self::assertSame('spaced', Env::raw(self::KEY));
    }

    public function test_string_returns_the_default_when_missing(): void
    {
        self::assertSame('fallback', Env::string(self::KEY, 'fallback'));
    }

    public function test_string_returns_an_empty_default_without_arguments(): void
    {
        self::assertSame('', Env::string(self::KEY));
    }

    public function test_string_returns_the_default_for_an_empty_value(): void
    {
        $_ENV[self::KEY] = '   ';

        self::assertSame('fallback', Env::string(self::KEY, 'fallback'));
    }

    public function test_string_returns_the_configured_value(): void
    {
        $_ENV[self::KEY] = 'production';

        self::assertSame('production', Env::string(self::KEY, 'fallback'));
    }

    #[DataProvider('truthyValues')]
    public function test_bool_recognises_truthy_values(string $value): void
    {
        $_ENV[self::KEY] = $value;

        self::assertTrue(Env::bool(self::KEY));
    }

    #[DataProvider('falsyValues')]
    public function test_bool_recognises_falsy_values(string $value): void
    {
        $_ENV[self::KEY] = $value;

        self::assertFalse(Env::bool(self::KEY, true));
    }

    public function test_bool_returns_the_default_when_missing(): void
    {
        self::assertTrue(Env::bool(self::KEY, true));
        self::assertFalse(Env::bool(self::KEY));
    }

    public function test_bool_returns_the_default_for_unrecognised_values(): void
    {
        $_ENV[self::KEY] = 'maybe';

        self::assertTrue(Env::bool(self::KEY, true));
        self::assertFalse(Env::bool(self::KEY, false));
    }

    public function test_int_parses_integers(): void
    {
        $_ENV[self::KEY] = '8080';

        self::assertSame(8080, Env::int(self::KEY, 1));
    }

    public function test_int_parses_negative_integers(): void
    {
        $_ENV[self::KEY] = '-5';

        self::assertSame(-5, Env::int(self::KEY, 1));
    }

    public function test_int_returns_the_default_for_non_integers(): void
    {
        $_ENV[self::KEY] = '12.5';

        self::assertSame(1, Env::int(self::KEY, 1));
    }

    public function test_int_returns_the_default_when_missing(): void
    {
        self::assertSame(3, Env::int(self::KEY, 3));
        self::assertSame(0, Env::int(self::KEY));
    }
}
