<?php

declare(strict_types=1);

namespace Bulbula\Database;

use PDO;
use PDOException;

/**
 * Creates PDO handles. The only place in the application allowed to do so.
 */
final readonly class ConnectionFactory
{
    /**
     * @throws DatabaseException when the connection cannot be established
     */
    public function create(DatabaseConfig $config): PDO
    {
        try {
            $pdo = new PDO($config->dsn(), $config->username(), $config->password(), $config->options());
        } catch (PDOException $exception) {
            throw DatabaseException::connectionFailed($config->driver(), $config->target(), $exception);
        }

        if ($config->driver() === DatabaseConfig::DRIVER_SQLITE) {
            $pdo->exec('PRAGMA foreign_keys = ON');
        }

        return $pdo;
    }
}
