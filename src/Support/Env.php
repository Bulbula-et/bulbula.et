<?php

declare(strict_types=1);

namespace Bulbula\Support;

use function in_array;
use function is_string;

/**
 * Typed reader for environment variables.
 *
 * Values are resolved from $_ENV, then $_SERVER, then getenv(), so the reader
 * works with Dotenv-loaded files as well as real process environments.
 */
final class Env
{
    private const array TRUTHY = ['true', '1', 'yes', 'on'];

    private const array FALSY = ['false', '0', 'no', 'off', 'null', ''];

    /**
     * Read the raw value of an environment variable.
     */
    public static function raw(string $key): ?string
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? null;

        if (! is_string($value)) {
            $value = getenv($key);
        }

        if (! is_string($value)) {
            return null;
        }

        return trim($value);
    }

    /**
     * Read an environment variable as a string.
     */
    public static function string(string $key, string $default = ''): string
    {
        $value = self::raw($key);

        return $value === null || $value === '' ? $default : $value;
    }

    /**
     * Read an environment variable as a boolean.
     */
    public static function bool(string $key, bool $default = false): bool
    {
        $value = self::raw($key);

        if ($value === null) {
            return $default;
        }

        $normalized = strtolower($value);

        if (in_array($normalized, self::TRUTHY, true)) {
            return true;
        }

        if (in_array($normalized, self::FALSY, true)) {
            return false;
        }

        return $default;
    }

    /**
     * Read an environment variable as an integer.
     */
    public static function int(string $key, int $default = 0): int
    {
        $value = self::raw($key);

        if ($value === null || ! self::isInteger($value)) {
            return $default;
        }

        return (int) $value;
    }

    private static function isInteger(string $value): bool
    {
        return preg_match('/^-?\d+$/', $value) === 1;
    }
}
