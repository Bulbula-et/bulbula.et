<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Migrations;

use Bulbula\Database\Connection;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Database\DatabaseException;
use Bulbula\Database\Migrations\MigrationRepository;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MigrationRepository::class)]
final class MigrationRepositoryTest extends TestCase
{
    private Connection $connection;

    private MigrationRepository $repository;

    protected function setUp(): void
    {
        $this->connection = new Connection(DatabaseConfig::sqliteInMemory());
        $this->repository = new MigrationRepository(
            $this->connection,
            static fn (): DateTimeImmutable => new DateTimeImmutable('2026-10-04 08:30:00'),
        );

        parent::setUp();
    }

    public function test_it_creates_the_tracking_table_once(): void
    {
        $this->repository->ensureTableExists();
        $this->repository->ensureTableExists();

        self::assertSame([], $this->repository->applied());
        self::assertSame('migrations', MigrationRepository::TABLE);
    }

    public function test_it_records_a_migration_with_its_batch_and_timestamp(): void
    {
        $this->repository->ensureTableExists();
        $this->repository->log('2026_01_01_000000_first', 1);

        self::assertSame(
            [['migration' => '2026_01_01_000000_first', 'batch' => 1, 'executed_at' => '2026-10-04 08:30:00']],
            $this->connection->select('SELECT migration, batch, executed_at FROM migrations'),
        );
    }

    public function test_the_tracking_table_keeps_its_documented_column_order(): void
    {
        $this->repository->ensureTableExists();
        $this->repository->log('2026_01_01_000000_first', 1);

        $row = $this->connection->selectOne('SELECT * FROM migrations');

        self::assertNotNull($row);
        self::assertSame(['migration', 'batch', 'executed_at'], array_keys($row));
    }

    public function test_the_migration_column_is_the_primary_key(): void
    {
        $this->repository->ensureTableExists();
        $this->repository->log('2026_01_01_000000_first', 1);

        $this->expectException(DatabaseException::class);

        $this->repository->log('2026_01_01_000000_first', 2);
    }

    public function test_applied_migrations_are_listed_in_order(): void
    {
        $this->repository->ensureTableExists();
        $this->repository->log('2026_02_01_000000_second', 1);
        $this->repository->log('2026_01_01_000000_first', 1);

        self::assertSame(['2026_01_01_000000_first', '2026_02_01_000000_second'], $this->repository->applied());
    }

    public function test_a_batch_is_listed_newest_first(): void
    {
        $this->repository->ensureTableExists();
        $this->repository->log('2026_01_01_000000_first', 1);
        $this->repository->log('2026_02_01_000000_second', 2);
        $this->repository->log('2026_03_01_000000_third', 2);

        self::assertSame(
            ['2026_03_01_000000_third', '2026_02_01_000000_second'],
            $this->repository->batch(2),
        );
        self::assertSame(['2026_01_01_000000_first'], $this->repository->batch(1));
        self::assertSame([], $this->repository->batch(3));
    }

    public function test_the_last_batch_is_zero_until_something_runs(): void
    {
        $this->repository->ensureTableExists();

        self::assertSame(0, $this->repository->lastBatch());
    }

    public function test_the_last_batch_is_the_highest_recorded_one(): void
    {
        $this->repository->ensureTableExists();
        $this->repository->log('2026_01_01_000000_first', 1);
        $this->repository->log('2026_02_01_000000_second', 3);
        $this->repository->log('2026_03_01_000000_third', 2);

        self::assertSame(3, $this->repository->lastBatch());
    }

    public function test_the_last_batch_is_read_as_an_integer_even_when_the_driver_returns_a_string(): void
    {
        $this->repository->ensureTableExists();
        $this->connection->execute(
            'INSERT INTO migrations (migration, batch, executed_at) VALUES (:m, :b, :e)',
            ['m' => 'legacy', 'b' => '7', 'e' => '2026-10-04 08:30:00'],
        );

        self::assertSame(7, $this->repository->lastBatch());
    }

    public function test_a_migration_can_be_forgotten(): void
    {
        $this->repository->ensureTableExists();
        $this->repository->log('2026_01_01_000000_first', 1);
        $this->repository->log('2026_02_01_000000_second', 1);

        $this->repository->forget('2026_01_01_000000_first');

        self::assertSame(['2026_02_01_000000_second'], $this->repository->applied());
    }
}
