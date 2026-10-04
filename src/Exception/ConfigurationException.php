<?php

declare(strict_types=1);

namespace Bulbula\Exception;

use RuntimeException;

/**
 * Thrown when the environment or configuration of the application is invalid.
 */
final class ConfigurationException extends RuntimeException
{
    public static function missingDirectory(string $directory): self
    {
        return new self(sprintf('Configuration directory [%s] does not exist.', $directory));
    }

    public static function invalidFile(string $file): self
    {
        return new self(sprintf('Configuration file [%s] must return an array.', $file));
    }

    public static function missingKey(string $key): self
    {
        return new self(sprintf('Configuration key [%s] is not defined.', $key));
    }

    public static function invalidType(string $key, string $expected, string $actual): self
    {
        return new self(sprintf('Configuration key [%s] must be of type %s, %s given.', $key, $expected, $actual));
    }

    public static function unknownEnvironment(string $name): self
    {
        return new self(sprintf('Unknown application environment [%s].', $name));
    }

    public static function uncreatableLogDirectory(string $directory): self
    {
        return new self(sprintf('Log directory [%s] could not be created.', $directory));
    }

    public static function unwritableLogDirectory(string $directory): self
    {
        return new self(sprintf('Log directory [%s] is not writable.', $directory));
    }
}
