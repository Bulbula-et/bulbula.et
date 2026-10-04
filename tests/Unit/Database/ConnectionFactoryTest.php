<?php

declare(strict_types=1);

namespace Tests\Unit\Database;

use Bulbula\Database\ConnectionFactory;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Database\DatabaseException;
use PDO;
use PDOException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConnectionFactory::class)]
#[CoversClass(DatabaseException::class)]
final class ConnectionFactoryTest extends TestCase
{
    public function test_it_creates_a_working_handle(): void
    {
        $pdo = new ConnectionFactory()->create(DatabaseConfig::sqliteInMemory());

        self::assertSame('1', (string) $pdo->query('SELECT 1')?->fetchColumn());
    }

    public function test_it_applies_the_configured_attributes(): void
    {
        $pdo = new ConnectionFactory()->create(DatabaseConfig::sqliteInMemory());

        self::assertSame(PDO::ERRMODE_EXCEPTION, $pdo->getAttribute(PDO::ATTR_ERRMODE));
        self::assertSame(PDO::FETCH_ASSOC, $pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE));
    }

    public function test_sqlite_handles_enforce_foreign_keys(): void
    {
        $pdo = new ConnectionFactory()->create(DatabaseConfig::sqliteInMemory());

        self::assertSame('1', (string) $pdo->query('PRAGMA foreign_keys')?->fetchColumn());
    }

    public function test_a_failing_connection_is_reported_without_the_driver_message(): void
    {
        $config = new DatabaseConfig('sqlite', '/this/path/does/not/exist/bulbula.sqlite');

        try {
            new ConnectionFactory()->create($config);
        } catch (DatabaseException $exception) {
            self::assertSame(
                'Could not connect to the [sqlite] database [/this/path/does/not/exist/bulbula.sqlite].',
                $exception->getMessage(),
            );
            self::assertInstanceOf(PDOException::class, $exception->getPrevious());
            self::assertSame(0, $exception->getCode());

            return;
        }

        self::fail('An unreachable database must raise a DatabaseException.');
    }

    public function test_a_failing_mysql_connection_never_leaks_the_password(): void
    {
        $config = new DatabaseConfig('mysql', 'bulbula', '127.0.0.1', 1, 'user', 'super-secret');

        try {
            new ConnectionFactory()->create($config);
        } catch (DatabaseException $exception) {
            self::assertStringNotContainsString('super-secret', $exception->getMessage());
            self::assertSame(
                'Could not connect to the [mysql] database [127.0.0.1:1/bulbula].',
                $exception->getMessage(),
            );

            return;
        }

        self::fail('An unreachable database must raise a DatabaseException.');
    }

    public function test_an_unsupported_driver_never_reaches_pdo(): void
    {
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Unsupported database driver [oracle].');

        new ConnectionFactory()->create(new DatabaseConfig('oracle', 'bulbula'));
    }

    public function test_a_query_failure_is_wrapped_without_details(): void
    {
        $exception = DatabaseException::queryFailed(new PDOException('SQLSTATE[42S02]: table missing'));

        self::assertSame('The database query failed.', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertInstanceOf(PDOException::class, $exception->getPrevious());
    }
}
