<?php

declare(strict_types=1);

namespace Bulbula\Database\Migrations;

/**
 * Finds migration files and keeps them in filename order, which is why the
 * naming convention is a sortable timestamp prefix.
 */
final readonly class MigrationLocator
{
    public function __construct(private string $directory)
    {
    }

    /**
     * @return list<MigrationFile>
     *
     * @throws MigrationException when the directory is missing
     */
    public function files(): array
    {
        if (! is_dir($this->directory)) {
            throw MigrationException::missingDirectory($this->directory);
        }

        // glob() returns its matches in alphabetical order, which is exactly the
        // execution order the timestamp prefix of a migration encodes.
        /** @var list<string> $paths */
        $paths = glob($this->directory . '/*.php') ?: [];

        return array_map(
            static fn (string $path): MigrationFile => new MigrationFile(basename($path, '.php'), $path),
            $paths,
        );
    }

    /**
     * @throws MigrationException when no file matches the recorded name
     */
    public function file(string $name): MigrationFile
    {
        foreach ($this->files() as $file) {
            if ($file->name() === $name) {
                return $file;
            }
        }

        throw MigrationException::unknownMigration($name);
    }
}
