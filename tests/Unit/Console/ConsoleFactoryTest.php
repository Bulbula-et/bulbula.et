<?php

declare(strict_types=1);

namespace Tests\Unit\Console;

use Bulbula\Console\Console;
use Bulbula\Console\ConsoleFactory;
use Bulbula\Database\Connection;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Foundation\Application;
use Bulbula\Foundation\Services;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConsoleFactory::class)]
final class ConsoleFactoryTest extends TestCase
{
    public function test_it_wires_the_project_migrations(): void
    {
        $output = [];

        $console = ConsoleFactory::create(
            $this->application(),
            $this->services(),
            static function (string $line) use (&$output): void {
                $output[] = $line;
            },
        );

        self::assertSame(Console::SUCCESS, $console->run(['console', 'migration:status']));
        self::assertSame(['[pending] 2026_10_04_120000_create_health_checks_table'], $output);
    }

    public function test_it_runs_the_project_migrations_against_the_wired_connection(): void
    {
        $connection = new Connection(DatabaseConfig::sqliteInMemory());
        $console = ConsoleFactory::create(
            $this->application(),
            Services::forApplication($this->application(), $connection),
            static function (string $line): void {
            },
        );

        self::assertSame(Console::SUCCESS, $console->run(['console', 'migrate']));
        self::assertNotNull($connection->selectOne(
            "SELECT name FROM sqlite_master WHERE type = 'table' AND name = 'health_checks'",
        ));
    }

    public function test_it_defaults_to_echoing_each_line(): void
    {
        $this->expectOutputString('[pending] 2026_10_04_120000_create_health_checks_table' . PHP_EOL);

        ConsoleFactory::create($this->application(), $this->services())->run(['console', 'migration:status']);
    }

    public function test_it_builds_its_own_services_when_none_are_given(): void
    {
        $console = ConsoleFactory::create($this->application(), output: static function (string $line): void {
        });

        self::assertSame(Console::FAILURE, $console->run(['console', 'migrate']));
    }

    private function application(): Application
    {
        return Application::boot(dirname(__DIR__, 3), registerErrorHandler: false);
    }

    private function services(): Services
    {
        return Services::forApplication($this->application(), new Connection(DatabaseConfig::sqliteInMemory()));
    }
}
