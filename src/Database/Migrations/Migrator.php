<?php

declare(strict_types=1);

namespace Bulbula\Database\Migrations;

use Bulbula\Database\Connection;

use function in_array;

/**
 * Applies and reverts migrations, one batch at a time.
 *
 * Each `migrate` run gets its own batch number so a `rollback` can revert
 * exactly what the previous run applied, newest file first.
 */
final readonly class Migrator
{
    public function __construct(
        private Connection $connection,
        private MigrationLocator $locator,
        private MigrationRepository $repository,
    ) {
    }

    /**
     * Run every migration that has not been applied yet.
     *
     * @return list<string> the migrations that ran, in execution order
     */
    public function migrate(): array
    {
        // pending() creates the tracking table, so the batch number is read
        // only once it is guaranteed to exist.
        $pending = $this->pending();
        $batch = $this->repository->lastBatch() + 1;
        $ran = [];

        foreach ($pending as $file) {
            $file->migration()->up($this->connection);
            $this->repository->log($file->name(), $batch);
            $ran[] = $file->name();
        }

        return $ran;
    }

    /**
     * Revert the given number of batches, most recent first.
     *
     * @return list<string> the migrations that were reverted
     */
    public function rollback(int $steps = 1): array
    {
        $this->repository->ensureTableExists();

        $reverted = [];

        // An exhausted history simply yields an empty batch, so no guard is needed.
        for ($step = 0; $step < $steps; ++$step) {
            foreach ($this->repository->batch($this->repository->lastBatch()) as $name) {
                $this->locator->file($name)->migration()->down($this->connection);
                $this->repository->forget($name);
                $reverted[] = $name;
            }
        }

        return $reverted;
    }

    /**
     * @return list<MigrationFile>
     */
    public function pending(): array
    {
        $this->repository->ensureTableExists();

        $applied = $this->repository->applied();

        return array_values(array_filter(
            $this->locator->files(),
            static fn (MigrationFile $file): bool => ! in_array($file->name(), $applied, true),
        ));
    }

    /**
     * @return list<MigrationStatus>
     */
    public function status(): array
    {
        $this->repository->ensureTableExists();

        $applied = $this->repository->applied();

        return array_map(
            static fn (MigrationFile $file): MigrationStatus => new MigrationStatus(
                $file->name(),
                in_array($file->name(), $applied, true),
            ),
            $this->locator->files(),
        );
    }
}
