<?php

declare(strict_types=1);

namespace Bulbula\Diagnostics;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * The aggregate result of a health probe.
 */
final readonly class HealthReport
{
    /**
     * @param list<HealthCheck> $checks
     */
    public function __construct(
        private string $application,
        private string $environment,
        private DateTimeImmutable $time,
        private array $checks,
    ) {
    }

    public function isHealthy(): bool
    {
        return array_all($this->checks, static fn (HealthCheck $check): bool => $check->status()->isPassing());
    }

    public function status(): HealthStatus
    {
        return $this->isHealthy() ? HealthStatus::Pass : HealthStatus::Fail;
    }

    /**
     * @return list<HealthCheck>
     */
    public function checks(): array
    {
        return $this->checks;
    }

    /**
     * @return array{
     *     status: string,
     *     application: string,
     *     environment: string,
     *     time: string,
     *     checks: array<string, array{status: string, detail?: string}>,
     * }
     */
    public function toArray(): array
    {
        $checks = [];

        foreach ($this->checks as $check) {
            $checks[$check->name()] = $check->toArray();
        }

        return [
            'status' => $this->status()->value,
            'application' => $this->application,
            'environment' => $this->environment,
            'time' => $this->time->format(DateTimeInterface::ATOM),
            'checks' => $checks,
        ];
    }
}
