<?php

declare(strict_types=1);

namespace Bulbula\Database;

use Bulbula\Config\Config;
use PDO;
use SensitiveParameter;

/**
 * Connection settings, resolved from configuration (never from the environment
 * directly, so every setting has one documented source).
 */
final readonly class DatabaseConfig
{
    public const string DRIVER_MYSQL = 'mysql';

    public const string DRIVER_SQLITE = 'sqlite';

    public function __construct(
        private string $driver,
        private string $database,
        private string $host = '127.0.0.1',
        private int $port = 3306,
        private string $username = '',
        #[SensitiveParameter]
        private string $password = '',
        private string $charset = 'utf8mb4',
        private string $collation = 'utf8mb4_unicode_ci',
    ) {
    }

    /**
     * Build the connection settings from the application configuration, which
     * is the only place environment variables are read.
     */
    public static function fromConfig(Config $config): self
    {
        return new self(
            $config->string('database.driver'),
            $config->string('database.database'),
            $config->string('database.host'),
            $config->int('database.port'),
            $config->string('database.username'),
            $config->string('database.password'),
            $config->string('database.charset'),
            $config->string('database.collation'),
        );
    }

    /**
     * An in-memory SQLite database, used by the test suite.
     */
    public static function sqliteInMemory(): self
    {
        return new self(self::DRIVER_SQLITE, ':memory:');
    }

    public function driver(): string
    {
        return $this->driver;
    }

    public function database(): string
    {
        return $this->database;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }

    /**
     * @throws DatabaseException when the configured driver is not supported
     */
    public function dsn(): string
    {
        return match ($this->driver) {
            self::DRIVER_MYSQL => sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $this->host,
                $this->port,
                $this->database,
                $this->charset,
            ),
            self::DRIVER_SQLITE => sprintf('sqlite:%s', $this->database),
            default => throw DatabaseException::unsupportedDriver($this->driver),
        };
    }

    /**
     * PDO attributes: exceptions on failure, associative rows, real prepared
     * statements and no silent type juggling.
     *
     * @return array<int, mixed>
     */
    public function options(): array
    {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false,
        ];

        if ($this->driver === self::DRIVER_MYSQL) {
            $options[PDO::MYSQL_ATTR_INIT_COMMAND] = sprintf(
                'SET NAMES %s COLLATE %s',
                $this->charset,
                $this->collation,
            );
        }

        return $options;
    }

    /**
     * A description of the target that is safe to log: never the password.
     */
    public function target(): string
    {
        return $this->driver === self::DRIVER_SQLITE
            ? $this->database
            : sprintf('%s:%d/%s', $this->host, $this->port, $this->database);
    }
}
