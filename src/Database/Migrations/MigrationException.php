<?php

declare(strict_types=1);

namespace Bulbula\Database\Migrations;

use RuntimeException;

final class MigrationException extends RuntimeException
{
    public static function missingDirectory(string $directory): self
    {
        return new self(sprintf('Migration directory [%s] does not exist.', $directory));
    }

    public static function invalidMigrationFile(string $path): self
    {
        return new self(sprintf('Migration file [%s] must return a Migration instance.', $path));
    }

    public static function unknownMigration(string $name): self
    {
        return new self(sprintf('Migration [%s] is recorded as run but its file is missing.', $name));
    }
}
