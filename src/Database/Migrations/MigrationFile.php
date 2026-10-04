<?php

declare(strict_types=1);

namespace Bulbula\Database\Migrations;

/**
 * A migration file on disk, identified by its filename without the extension.
 */
final readonly class MigrationFile
{
    public function __construct(
        private string $name,
        private string $path,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function path(): string
    {
        return $this->path;
    }

    /**
     * @throws MigrationException when the file does not return a Migration
     */
    public function migration(): Migration
    {
        /** @var mixed $migration */
        $migration = require $this->path;

        if (! $migration instanceof Migration) {
            throw MigrationException::invalidMigrationFile($this->path);
        }

        return $migration;
    }
}
