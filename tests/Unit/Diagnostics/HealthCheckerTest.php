<?php

declare(strict_types=1);

namespace Tests\Unit\Diagnostics;

use Bulbula\Database\Connection;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Diagnostics\HealthCheck;
use Bulbula\Diagnostics\HealthChecker;
use Bulbula\Diagnostics\HealthReport;
use Bulbula\Diagnostics\HealthStatus;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use DateTimeZone;

#[CoversClass(HealthChecker::class)]
#[CoversClass(HealthCheck::class)]
#[CoversClass(HealthReport::class)]
#[CoversClass(HealthStatus::class)]
final class HealthCheckerTest extends TestCase
{
    public function test_liveness_passes_without_any_dependency(): void
    {
        $report = $this->checker(connection: null)->liveness();

        self::assertTrue($report->isHealthy());
        self::assertSame(HealthStatus::Pass, $report->status());
        self::assertSame(
            [
                'status' => 'pass',
                'application' => 'Bulbula',
                'environment' => 'testing',
                'time' => '2026-10-04T08:30:00+00:00',
                'checks' => ['application' => ['status' => 'pass']],
            ],
            $report->toArray(),
        );
    }

    public function test_liveness_never_touches_the_database(): void
    {
        $connection = new Connection(new DatabaseConfig('sqlite', '/this/path/does/not/exist/bulbula.sqlite'));

        $report = $this->checker($connection)->liveness();

        self::assertTrue($report->isHealthy());
        self::assertFalse($connection->isConnected());
    }

    public function test_readiness_passes_when_the_database_is_migrated(): void
    {
        $connection = new Connection(DatabaseConfig::sqliteInMemory());
        $connection->statement('CREATE TABLE migrations (migration VARCHAR(255) PRIMARY KEY)');

        $report = $this->checker($connection)->readiness();

        self::assertTrue($report->isHealthy());
        self::assertSame(
            ['application' => ['status' => 'pass'], 'database' => ['status' => 'pass']],
            $report->toArray()['checks'],
        );
    }

    public function test_readiness_fails_when_the_database_is_unreachable(): void
    {
        $connection = new Connection(new DatabaseConfig('sqlite', '/this/path/does/not/exist/bulbula.sqlite'));

        $report = $this->checker($connection)->readiness();

        self::assertFalse($report->isHealthy());
        self::assertSame(HealthStatus::Fail, $report->status());
        self::assertSame(
            ['status' => 'fail', 'detail' => 'The database did not respond.'],
            $report->toArray()['checks']['database'] ?? null,
        );
    }

    public function test_readiness_fails_when_the_schema_is_missing(): void
    {
        $report = $this->checker(new Connection(DatabaseConfig::sqliteInMemory()))->readiness();

        self::assertFalse($report->isHealthy());
        self::assertSame(
            ['status' => 'fail', 'detail' => 'The database is reachable but not migrated.'],
            $report->toArray()['checks']['database'] ?? null,
        );
    }

    public function test_readiness_fails_when_no_database_is_configured(): void
    {
        $report = $this->checker(connection: null)->readiness();

        self::assertFalse($report->isHealthy());
        self::assertSame(
            ['status' => 'fail', 'detail' => 'No database connection is configured.'],
            $report->toArray()['checks']['database'] ?? null,
        );
    }

    public function test_the_application_check_always_passes_in_a_readiness_report(): void
    {
        $checks = $this->checker(connection: null)->readiness()->checks();

        self::assertCount(2, $checks);
        self::assertSame('application', $checks[0]->name());
        self::assertSame(HealthStatus::Pass, $checks[0]->status());
        self::assertSame('', $checks[0]->detail());
        self::assertSame('database', $checks[1]->name());
    }

    public function test_the_report_describes_the_application_and_the_moment_of_the_check(): void
    {
        $report = $this->checker(connection: null)->readiness()->toArray();

        self::assertSame('Bulbula', $report['application']);
        self::assertSame('testing', $report['environment']);
        self::assertSame('2026-10-04T08:30:00+00:00', $report['time']);
        self::assertSame('fail', $report['status']);
    }

    public function test_a_passing_status_is_recognised(): void
    {
        self::assertTrue(HealthStatus::Pass->isPassing());
        self::assertFalse(HealthStatus::Fail->isPassing());
    }

    private function checker(?Connection $connection): HealthChecker
    {
        return new HealthChecker(
            'Bulbula',
            'testing',
            static fn (): DateTimeImmutable => new DateTimeImmutable('2026-10-04 08:30:00', new DateTimeZone('UTC')),
            $connection,
        );
    }
}
