<?php

declare(strict_types=1);

namespace Bulbula\Database;

use RuntimeException;
use Throwable;

/**
 * A database failure that is safe to propagate.
 *
 * The driver message is deliberately dropped from connection failures: PDO
 * puts the DSN, and sometimes the credentials, in its own message.
 */
final class DatabaseException extends RuntimeException
{
    public static function connectionFailed(string $driver, string $target, Throwable $previous): self
    {
        return new self(
            sprintf('Could not connect to the [%s] database [%s].', $driver, $target),
            0,
            $previous,
        );
    }

    public static function unsupportedDriver(string $driver): self
    {
        return new self(sprintf('Unsupported database driver [%s].', $driver));
    }

    public static function queryFailed(Throwable $previous): self
    {
        return new self('The database query failed.', 0, $previous);
    }
}
