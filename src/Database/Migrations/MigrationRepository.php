<?php

declare(strict_types=1);

namespace Bulbula\Database\Migrations;

use Bulbula\Database\Connection;
use Closure;
use DateTimeImmutable;

use function is_int;
use function is_string;

/**
 * The tracking table: which migrations ran, in which batch, and when.
 *
 * The DDL is intentionally portable so the suite can exercise the real
 * migrator against SQLite while production runs MariaDB.
 */
final readonly class MigrationRepository
{
    public const string TABLE = 'migrations';

    /**
     * @param Closure(): DateTimeImmutable $clock
     */
    public function __construct(
        private Connection $connection,
        private Closure $clock,
    ) {
    }

    public function ensureTableExists(): void
    {
        $sql = <<<'SQL'
            CREATE TABLE IF NOT EXISTS %s (
                migration VARCHAR(255) NOT NULL,
                batch INTEGER NOT NULL,
                executed_at VARCHAR(19) NOT NULL,
                PRIMARY KEY (migration)
            )
            SQL;

        $this->connection->statement(sprintf($sql, self::TABLE));
    }

    /**
     * @return list<string>
     */
    public function applied(): array
    {
        $names = [];

        $rows = $this->connection->select(sprintf('SELECT migration FROM %s ORDER BY migration ASC', self::TABLE));

        foreach ($rows as $row) {
            $name = $row['migration'] ?? null;

            if (is_string($name)) {
                $names[] = $name;
            }
        }

        return $names;
    }

    /**
     * @return list<string>
     */
    public function batch(int $batch): array
    {
        $names = [];

        $rows = $this->connection->select(
            sprintf('SELECT migration FROM %s WHERE batch = :batch ORDER BY migration DESC', self::TABLE),
            ['batch' => $batch],
        );

        foreach ($rows as $row) {
            $name = $row['migration'] ?? null;

            if (is_string($name)) {
                $names[] = $name;
            }
        }

        return $names;
    }

    public function lastBatch(): int
    {
        $row = $this->connection->selectOne(sprintf('SELECT MAX(batch) AS batch FROM %s', self::TABLE));
        $batch = $row['batch'] ?? null;

        return is_int($batch) ? $batch : 0;
    }

    public function log(string $migration, int $batch): void
    {
        $this->connection->execute(
            sprintf(
                'INSERT INTO %s (migration, batch, executed_at) VALUES (:migration, :batch, :executed_at)',
                self::TABLE,
            ),
            [
                'migration' => $migration,
                'batch' => $batch,
                'executed_at' => ($this->clock)()->format('Y-m-d H:i:s'),
            ],
        );
    }

    public function forget(string $migration): void
    {
        $this->connection->execute(
            sprintf('DELETE FROM %s WHERE migration = :migration', self::TABLE),
            ['migration' => $migration],
        );
    }
}
