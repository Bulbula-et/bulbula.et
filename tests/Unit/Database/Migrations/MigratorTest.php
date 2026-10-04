<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Migrations;

use Bulbula\Database\Connection;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Database\Migrations\MigrationLocator;
use Bulbula\Database\Migrations\MigrationRepository;
use Bulbula\Database\Migrations\MigrationStatus;
use Bulbula\Database\Migrations\Migrator;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Migrator::class)]
#[CoversClass(MigrationStatus::class)]
final class MigratorTest extends TestCase
{
    private const string FIRST = '2026_01_01_000000_create_first_table';

    private const string SECOND = '2026_02_01_000000_create_second_table';

    private Connection $connection;

    protected function setUp(): void
    {
        $this->connection = new Connection(DatabaseConfig::sqliteInMemory());

        parent::setUp();
    }

    public function test_it_runs_every_pending_migration_in_order(): void
    {
        $ran = $this->migrator()->migrate();

        self::assertSame([self::FIRST, self::SECOND], $ran);
        self::assertTrue($this->tableExists('first_table'));
        self::assertTrue($this->tableExists('second_table'));
    }

    public function test_a_second_run_does_nothing(): void
    {
        $migrator = $this->migrator();
        $migrator->migrate();

        self::assertSame([], $migrator->migrate());
    }

    public function test_every_run_gets_its_own_batch(): void
    {
        $migrator = $this->migrator();
        $migrator->migrate();

        self::assertSame(
            [['migration' => self::FIRST, 'batch' => 1], ['migration' => self::SECOND, 'batch' => 1]],
            $this->connection->select('SELECT migration, batch FROM migrations ORDER BY migration'),
        );
    }

    public function test_pending_lists_only_what_has_not_run(): void
    {
        $migrator = $this->migrator();

        self::assertSame([self::FIRST, self::SECOND], $this->names($migrator->pending()));

        $migrator->migrate();

        self::assertSame([], $migrator->pending());
    }

    public function test_pending_is_a_re_indexed_list(): void
    {
        $repository = $this->repository();
        $repository->ensureTableExists();
        $repository->log(self::FIRST, 1);

        $pending = $this->migrator($repository)->pending();

        self::assertSame([0], array_keys($pending));
        self::assertSame(self::SECOND, $pending[0]->name());
    }

    public function test_rollback_reverts_the_last_batch_newest_first(): void
    {
        $migrator = $this->migrator();
        $migrator->migrate();

        $reverted = $migrator->rollback();

        self::assertSame([self::SECOND, self::FIRST], $reverted);
        self::assertFalse($this->tableExists('first_table'));
        self::assertFalse($this->tableExists('second_table'));
        self::assertSame([self::FIRST, self::SECOND], $this->names($migrator->pending()));
    }

    public function test_rollback_only_reverts_one_batch_by_default(): void
    {
        $repository = $this->repository();
        $migrator = $this->migrator($repository);
        $repository->ensureTableExists();
        $repository->log(self::FIRST, 1);

        $migrator->migrate();
        $reverted = $migrator->rollback();

        self::assertSame([self::SECOND], $reverted);
        self::assertSame([self::FIRST], $repository->applied());
    }

    public function test_rollback_can_revert_several_batches(): void
    {
        $repository = $this->repository();
        $migrator = $this->migrator($repository);
        $repository->ensureTableExists();
        $repository->log(self::FIRST, 1);

        $migrator->migrate();
        $reverted = $migrator->rollback(2);

        self::assertSame([self::SECOND, self::FIRST], $reverted);
        self::assertSame([], $repository->applied());
    }

    public function test_rollback_stops_when_nothing_is_left(): void
    {
        $migrator = $this->migrator();
        $migrator->migrate();

        self::assertSame([self::SECOND, self::FIRST], $migrator->rollback(5));
        self::assertSame([], $migrator->rollback());
    }

    public function test_status_reports_every_migration(): void
    {
        $repository = $this->repository();
        $migrator = $this->migrator($repository);
        $repository->ensureTableExists();
        $repository->log(self::FIRST, 1);

        $statuses = $migrator->status();

        self::assertCount(2, $statuses);
        self::assertSame(self::FIRST, $statuses[0]->name());
        self::assertTrue($statuses[0]->isApplied());
        self::assertSame('applied', $statuses[0]->label());
        self::assertSame(self::SECOND, $statuses[1]->name());
        self::assertFalse($statuses[1]->isApplied());
        self::assertSame('pending', $statuses[1]->label());
    }

    public function test_rollback_on_an_untouched_database_does_nothing(): void
    {
        self::assertSame([], $this->migrator()->rollback());
    }

    public function test_status_works_on_an_untouched_database(): void
    {
        $statuses = $this->migrator()->status();

        self::assertCount(2, $statuses);
        self::assertFalse($statuses[0]->isApplied());
    }

    public function test_an_empty_migration_directory_is_not_an_error(): void
    {
        $migrator = $this->migrator(directory: 'empty');

        self::assertSame([], $migrator->migrate());
        self::assertSame([], $migrator->status());
        self::assertSame([], $migrator->rollback());
    }

    /**
     * @param list<\Bulbula\Database\Migrations\MigrationFile> $files
     *
     * @return list<string>
     */
    private function names(array $files): array
    {
        return array_map(static fn (\Bulbula\Database\Migrations\MigrationFile $file): string => $file->name(), $files);
    }

    private function migrator(?MigrationRepository $repository = null, string $directory = 'valid'): Migrator
    {
        return new Migrator(
            $this->connection,
            new MigrationLocator(__DIR__ . '/../../../Fixtures/migrations/' . $directory),
            $repository ?? $this->repository(),
        );
    }

    private function repository(): MigrationRepository
    {
        return new MigrationRepository(
            $this->connection,
            static fn (): DateTimeImmutable => new DateTimeImmutable('2026-10-04 08:30:00'),
        );
    }

    private function tableExists(string $table): bool
    {
        return $this->connection->selectOne(
            "SELECT name FROM sqlite_master WHERE type = 'table' AND name = :name",
            ['name' => $table],
        ) !== null;
    }
}
