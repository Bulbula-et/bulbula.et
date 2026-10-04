<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Migrations;

use Bulbula\Database\Migrations\Migration;
use Bulbula\Database\Migrations\MigrationException;
use Bulbula\Database\Migrations\MigrationLocator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MigrationLocator::class)]
#[CoversClass(MigrationException::class)]
final class MigrationLocatorTest extends TestCase
{
    public function test_it_lists_php_files_in_filename_order(): void
    {
        $files = new MigrationLocator($this->path('valid'))->files();

        self::assertCount(2, $files);
        self::assertSame('2026_01_01_000000_create_first_table', $files[0]->name());
        self::assertSame('2026_02_01_000000_create_second_table', $files[1]->name());
        self::assertSame($this->path('valid') . '/2026_01_01_000000_create_first_table.php', $files[0]->path());
    }

    public function test_an_empty_directory_has_no_migrations(): void
    {
        self::assertSame([], new MigrationLocator($this->path('empty'))->files());
    }

    public function test_a_missing_directory_is_reported(): void
    {
        $this->expectException(MigrationException::class);
        $this->expectExceptionMessage('does not exist.');

        new MigrationLocator($this->path('nowhere'))->files();
    }

    public function test_it_finds_a_migration_by_name(): void
    {
        $file = new MigrationLocator($this->path('valid'))->file('2026_02_01_000000_create_second_table');

        self::assertSame('2026_02_01_000000_create_second_table', $file->name());
        self::assertInstanceOf(Migration::class, $file->migration());
    }

    public function test_a_recorded_migration_without_a_file_is_reported(): void
    {
        $this->expectException(MigrationException::class);
        $this->expectExceptionMessage('Migration [2020_01_01_000000_gone] is recorded as run but its file is missing.');

        new MigrationLocator($this->path('valid'))->file('2020_01_01_000000_gone');
    }

    public function test_a_file_that_does_not_return_a_migration_is_rejected(): void
    {
        $this->expectException(MigrationException::class);
        $this->expectExceptionMessage('must return a Migration instance.');

        new MigrationLocator($this->path('invalid'))->files()[0]->migration();
    }

    private function path(string $directory): string
    {
        return __DIR__ . '/../../../Fixtures/migrations/' . $directory;
    }
}
