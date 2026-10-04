<?php

declare(strict_types=1);

namespace Bulbula\Console;

use Bulbula\Database\Migrations\MigrationLocator;
use Bulbula\Database\Migrations\MigrationRepository;
use Bulbula\Database\Migrations\Migrator;
use Bulbula\Foundation\Application;
use Bulbula\Foundation\Services;
use Closure;

/**
 * Wires the console the same way the HTTP kernel factory wires the web entry
 * point, so `bin/console` stays a three-line script.
 */
final readonly class ConsoleFactory
{
    /**
     * @param (Closure(string): void)|null $output
     */
    public static function create(
        Application $application,
        ?Services $services = null,
        ?Closure $output = null,
    ): Console {
        $services ??= Services::forApplication($application);
        $output ??= static function (string $line): void {
            echo $line . PHP_EOL;
        };

        $migrator = self::migrator($services, $application->path('database/migrations'));

        return new Console($migrator, $output);
    }

    private static function migrator(Services $services, string $directory): Migrator
    {
        $connection = $services->connection();

        return new Migrator(
            $connection,
            new MigrationLocator($directory),
            new MigrationRepository($connection, $services->clock()),
        );
    }
}
