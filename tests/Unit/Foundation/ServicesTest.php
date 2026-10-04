<?php

declare(strict_types=1);

namespace Tests\Unit\Foundation;

use Bulbula\Config\Config;
use Bulbula\Database\Connection;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Diagnostics\HealthChecker;
use Bulbula\Foundation\Application;
use Bulbula\Foundation\Services;
use Bulbula\View\PageRenderer;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(Services::class)]
final class ServicesTest extends TestCase
{
    public function test_it_exposes_the_application_and_its_collaborators(): void
    {
        $application = $this->application();
        $connection = new Connection(DatabaseConfig::sqliteInMemory());

        $services = Services::forApplication($application, $connection);

        self::assertSame($application, $services->application());
        self::assertInstanceOf(Config::class, $services->config());
        self::assertInstanceOf(LoggerInterface::class, $services->logger());
        self::assertSame($connection, $services->connection());
        self::assertInstanceOf(HealthChecker::class, $services->health());
        self::assertInstanceOf(PageRenderer::class, $services->pages());
    }

    public function test_it_builds_a_connection_from_the_configuration_when_none_is_given(): void
    {
        $services = Services::forApplication($this->application());

        self::assertSame('mysql', $services->connection()->driver());
        self::assertFalse($services->connection()->isConnected());
    }

    public function test_the_default_clock_returns_the_current_time(): void
    {
        $clock = Services::forApplication($this->application())->clock();

        $before = new DateTimeImmutable();
        $now = $clock();

        self::assertGreaterThanOrEqual($before->getTimestamp(), $now->getTimestamp());
    }

    public function test_the_clock_can_be_replaced(): void
    {
        $frozen = new DateTimeImmutable('2026-10-04 08:30:00');

        $services = Services::forApplication($this->application(), null, static fn (): DateTimeImmutable => $frozen);

        self::assertSame($frozen, ($services->clock())());
    }

    public function test_the_health_service_reports_the_configured_application(): void
    {
        $services = Services::forApplication(
            $this->application(),
            new Connection(DatabaseConfig::sqliteInMemory()),
            static fn (): DateTimeImmutable => new DateTimeImmutable('2026-10-04 08:30:00'),
        );

        $report = $services->health()->liveness()->toArray();

        self::assertSame('Bulbula', $report['application']);
        self::assertSame('testing', $report['environment']);
    }

    private function application(): Application
    {
        return Application::boot(dirname(__DIR__, 3), registerErrorHandler: false);
    }
}
