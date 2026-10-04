<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bulbula\Exception\ConfigurationException;
use Bulbula\Foundation\Environment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Environment::class)]
#[CoversClass(ConfigurationException::class)]
final class EnvironmentTest extends TestCase
{
    /**
     * @return iterable<string, array{string, Environment}>
     */
    public static function names(): iterable
    {
        yield 'local' => ['local', Environment::Local];
        yield 'testing' => ['testing', Environment::Testing];
        yield 'production' => ['production', Environment::Production];
        yield 'uppercase' => ['PRODUCTION', Environment::Production];
        yield 'padded' => ['  local  ', Environment::Local];
    }
    #[DataProvider('names')]
    public function test_it_resolves_environments_by_name(string $name, Environment $expected): void
    {
        self::assertSame($expected, Environment::fromName($name));
    }

    public function test_it_rejects_an_unknown_environment(): void
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Unknown application environment [staging].');

        Environment::fromName('staging');
    }

    public function test_production_is_the_only_production_environment(): void
    {
        self::assertTrue(Environment::Production->isProduction());
        self::assertFalse(Environment::Local->isProduction());
        self::assertFalse(Environment::Testing->isProduction());
    }

    public function test_local_is_the_only_local_environment(): void
    {
        self::assertTrue(Environment::Local->isLocal());
        self::assertFalse(Environment::Testing->isLocal());
        self::assertFalse(Environment::Production->isLocal());
    }

    public function test_testing_is_the_only_testing_environment(): void
    {
        self::assertTrue(Environment::Testing->isTesting());
        self::assertFalse(Environment::Local->isTesting());
        self::assertFalse(Environment::Production->isTesting());
    }

    public function test_debug_output_is_forbidden_in_production_only(): void
    {
        self::assertFalse(Environment::Production->allowsDebugOutput());
        self::assertTrue(Environment::Local->allowsDebugOutput());
        self::assertTrue(Environment::Testing->allowsDebugOutput());
    }
}
