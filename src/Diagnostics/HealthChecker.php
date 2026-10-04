<?php

declare(strict_types=1);

namespace Bulbula\Diagnostics;

use Bulbula\Database\Connection;
use Bulbula\Database\Migrations\MigrationRepository;
use Closure;
use DateTimeImmutable;
use Throwable;

/**
 * Answers two different questions.
 *
 * Liveness: is this process running and able to respond? It must never touch
 * the database, otherwise a database outage would make an orchestrator kill
 * perfectly healthy processes.
 *
 * Readiness: can this process actually serve traffic? That one does check the
 * database, and the schema behind it.
 */
final readonly class HealthChecker
{
    /**
     * @param Closure(): DateTimeImmutable $clock
     */
    public function __construct(
        private string $application,
        private string $environment,
        private Closure $clock,
        private ?Connection $connection = null,
    ) {
    }

    public function liveness(): HealthReport
    {
        return new HealthReport(
            $this->application,
            $this->environment,
            ($this->clock)(),
            [HealthCheck::pass('application')],
        );
    }

    public function readiness(): HealthReport
    {
        return new HealthReport(
            $this->application,
            $this->environment,
            ($this->clock)(),
            [HealthCheck::pass('application'), $this->databaseCheck()],
        );
    }

    private function databaseCheck(): HealthCheck
    {
        $connection = $this->connection;

        if (! $connection instanceof Connection) {
            return HealthCheck::fail('database', 'No database connection is configured.');
        }

        if (! $connection->ping()) {
            return HealthCheck::fail('database', 'The database did not respond.');
        }

        try {
            $connection->select(sprintf('SELECT COUNT(*) AS total FROM %s', MigrationRepository::TABLE));
        } catch (Throwable) {
            return HealthCheck::fail('database', 'The database is reachable but not migrated.');
        }

        return HealthCheck::pass('database');
    }
}
