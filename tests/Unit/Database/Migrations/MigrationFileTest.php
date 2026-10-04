<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Migrations;

use Bulbula\Database\Connection;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Database\Migrations\Migration;
use Bulbula\Database\Migrations\MigrationException;
use Bulbula\Database\Migrations\MigrationFile;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MigrationFile::class)]
#[CoversClass(Migration::class)]
final class MigrationFileTest extends TestCase
{
    public function test_it_exposes_its_name_and_path(): void
    {
        $file = new MigrationFile('2026_01_01_000000_create_first_table', $this->path('create_first_table'));

        self::assertSame('2026_01_01_000000_create_first_table', $file->name());
        self::assertSame($this->path('create_first_table'), $file->path());
    }

    public function test_it_loads_the_migration_the_file_returns(): void
    {
        $file = new MigrationFile('2026_01_01_000000_create_first_table', $this->path('create_first_table'));
        $connection = new Connection(DatabaseConfig::sqliteInMemory());

        $migration = $file->migration();

        self::assertInstanceOf(Migration::class, $migration);

        $migration->up($connection);

        self::assertNotNull($connection->selectOne(
            "SELECT name FROM sqlite_master WHERE type = 'table' AND name = 'first_table'",
        ));

        $migration->down($connection);

        self::assertNull($connection->selectOne(
            "SELECT name FROM sqlite_master WHERE type = 'table' AND name = 'first_table'",
        ));
    }

    public function test_a_file_that_returns_something_else_is_rejected(): void
    {
        $path = __DIR__ . '/../../../Fixtures/migrations/invalid/2026_01_01_000000_not_a_migration.php';

        $this->expectException(MigrationException::class);
        $this->expectExceptionMessage(sprintf('Migration file [%s] must return a Migration instance.', $path));

        new MigrationFile('2026_01_01_000000_not_a_migration', $path)->migration();
    }

    private function path(string $name): string
    {
        return __DIR__ . '/../../../Fixtures/migrations/valid/2026_01_01_000000_' . $name . '.php';
    }
}
