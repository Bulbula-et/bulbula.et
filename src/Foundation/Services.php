<?php

declare(strict_types=1);

namespace Bulbula\Foundation;

use Bulbula\Config\Config;
use Bulbula\Database\Connection;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Diagnostics\HealthChecker;
use Bulbula\View\PageRenderer;
use Closure;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;

/**
 * The composition root's service list.
 *
 * Explicit construction instead of a container: every dependency is visible
 * here, route files receive this object and wire their controllers by hand.
 */
final readonly class Services
{
    /**
     * @param Closure(): DateTimeImmutable $clock
     */
    private function __construct(
        private Application $application,
        private Connection $connection,
        private HealthChecker $health,
        private PageRenderer $pages,
        private Closure $clock,
    ) {
    }

    /**
     * @param (Closure(): DateTimeImmutable)|null $clock
     */
    public static function forApplication(
        Application $application,
        ?Connection $connection = null,
        ?Closure $clock = null,
    ): self {
        $config = $application->config();
        $clock ??= static fn (): DateTimeImmutable => new DateTimeImmutable();
        $connection ??= new Connection(DatabaseConfig::fromConfig($config));

        return new self(
            $application,
            $connection,
            new HealthChecker(
                $config->string('app.name'),
                $config->string('app.env'),
                $clock,
                $connection,
            ),
            new PageRenderer($application->path('public')),
            $clock,
        );
    }

    public function application(): Application
    {
        return $this->application;
    }

    public function config(): Config
    {
        return $this->application->config();
    }

    public function logger(): LoggerInterface
    {
        return $this->application->logger();
    }

    public function connection(): Connection
    {
        return $this->connection;
    }

    public function health(): HealthChecker
    {
        return $this->health;
    }

    public function pages(): PageRenderer
    {
        return $this->pages;
    }

    /**
     * @return Closure(): DateTimeImmutable
     */
    public function clock(): Closure
    {
        return $this->clock;
    }
}
