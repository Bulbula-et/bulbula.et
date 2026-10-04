<?php

declare(strict_types=1);

namespace Bulbula\Database\Migrations;

/**
 * One row of `php bin/console migration:status`.
 */
final readonly class MigrationStatus
{
    public function __construct(
        private string $name,
        private bool $applied,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isApplied(): bool
    {
        return $this->applied;
    }

    public function label(): string
    {
        return $this->applied ? 'applied' : 'pending';
    }
}
