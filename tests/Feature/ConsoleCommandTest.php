<?php

declare(strict_types=1);

use Bulbula\Console\Console;
use Bulbula\Console\ConsoleFactory;
use Bulbula\Database\Connection;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Foundation\Application;
use Bulbula\Foundation\Services;

/**
 * Runs the real project migrations through the real console wiring, against an
 * in-memory database so the suite needs no server.
 *
 * @param list<string> $argv
 *
 * @return array{int, list<string>}
 */
function runConsole(Connection $connection, array $argv): array
{
    $output = [];
    $application = Application::boot(dirname(__DIR__, 2), registerErrorHandler: false);
    $console = ConsoleFactory::create(
        $application,
        Services::forApplication($application, $connection),
        static function (string $line) use (&$output): void {
            $output[] = $line;
        },
    );

    return [$console->run([...['console'], ...$argv]), $output];
}

it('migrates, reports status and rolls back the project migrations', function (): void {
    $connection = new Connection(DatabaseConfig::sqliteInMemory());

    [$status, $output] = runConsole($connection, ['migration:status']);
    expect($status)->toBe(Console::SUCCESS)
        ->and($output[0])->toStartWith('[pending] ');

    [$status, $output] = runConsole($connection, ['migrate']);
    expect($status)->toBe(Console::SUCCESS)
        ->and($output)->toContain('Applied 1 migration(s).')
        ->and(tableExists($connection, 'health_checks'))->toBeTrue();

    [$status, $output] = runConsole($connection, ['migration:status']);
    expect($output[0])->toStartWith('[applied] ');

    [$status, $output] = runConsole($connection, ['rollback']);
    expect($status)->toBe(Console::SUCCESS)
        ->and($output)->toContain('Reverted 1 migration(s).')
        ->and(tableExists($connection, 'health_checks'))->toBeFalse();
});

it('records the migration in the tracking table', function (): void {
    $connection = new Connection(DatabaseConfig::sqliteInMemory());

    runConsole($connection, ['migrate']);

    $rows = $connection->select('SELECT migration, batch FROM migrations');

    expect($rows)->toHaveCount(1)
        ->and($rows[0]['migration'])->toBe('2026_10_04_120000_create_health_checks_table')
        ->and((int) $rows[0]['batch'])->toBe(1);
});

it('is safe to run twice', function (): void {
    $connection = new Connection(DatabaseConfig::sqliteInMemory());

    runConsole($connection, ['migrate']);
    [$status, $output] = runConsole($connection, ['migrate']);

    expect($status)->toBe(Console::SUCCESS)->and($output)->toBe(['Nothing to migrate.']);
});

it('exposes the commands documented in the readme', function (): void {
    $connection = new Connection(DatabaseConfig::sqliteInMemory());

    [$status, $output] = runConsole($connection, ['help']);

    expect($status)->toBe(Console::SUCCESS)
        ->and(implode("\n", $output))->toContain('migrate')
        ->and(implode("\n", $output))->toContain('rollback [--steps=N]')
        ->and(implode("\n", $output))->toContain('migration:status');
});

function tableExists(Connection $connection, string $table): bool
{
    return $connection->selectOne(
        "SELECT name FROM sqlite_master WHERE type = 'table' AND name = :name",
        ['name' => $table],
    ) !== null;
}
