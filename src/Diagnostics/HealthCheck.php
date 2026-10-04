<?php

declare(strict_types=1);

namespace Bulbula\Diagnostics;

/**
 * The outcome of one named check.
 */
final readonly class HealthCheck
{
    public function __construct(
        private string $name,
        private HealthStatus $status,
        private string $detail = '',
    ) {
    }

    public static function pass(string $name, string $detail = ''): self
    {
        return new self($name, HealthStatus::Pass, $detail);
    }

    public static function fail(string $name, string $detail = ''): self
    {
        return new self($name, HealthStatus::Fail, $detail);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function status(): HealthStatus
    {
        return $this->status;
    }

    public function detail(): string
    {
        return $this->detail;
    }

    /**
     * @return array{status: string, detail?: string}
     */
    public function toArray(): array
    {
        $payload = ['status' => $this->status->value];

        if ($this->detail !== '') {
            $payload['detail'] = $this->detail;
        }

        return $payload;
    }
}
