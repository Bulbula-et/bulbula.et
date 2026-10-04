<?php

declare(strict_types=1);

namespace Tests\Unit\Database;

use Bulbula\Config\Config;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Database\DatabaseException;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DatabaseConfig::class)]
final class DatabaseConfigTest extends TestCase
{
    public function test_it_builds_a_mysql_dsn_with_the_configured_charset(): void
    {
        $config = new DatabaseConfig('mysql', 'bulbula', 'db.internal', 3307, 'user', 'secret');

        self::assertSame('mysql:host=db.internal;port=3307;dbname=bulbula;charset=utf8mb4', $config->dsn());
        self::assertSame('mysql', $config->driver());
        self::assertSame('bulbula', $config->database());
        self::assertSame('user', $config->username());
        self::assertSame('secret', $config->password());
    }

    public function test_it_defaults_to_the_standard_mysql_host_and_port(): void
    {
        self::assertSame(
            'mysql:host=127.0.0.1;port=3306;dbname=bulbula;charset=utf8mb4',
            new DatabaseConfig('mysql', 'bulbula')->dsn(),
        );
        self::assertSame('127.0.0.1:3306/bulbula', new DatabaseConfig('mysql', 'bulbula')->target());
    }

    public function test_it_builds_a_sqlite_dsn(): void
    {
        self::assertSame('sqlite:/tmp/bulbula.sqlite', new DatabaseConfig('sqlite', '/tmp/bulbula.sqlite')->dsn());
    }

    public function test_the_in_memory_helper_is_an_empty_sqlite_database(): void
    {
        $config = DatabaseConfig::sqliteInMemory();

        self::assertSame('sqlite::memory:', $config->dsn());
        self::assertSame('sqlite', $config->driver());
        self::assertSame('', $config->username());
        self::assertSame('', $config->password());
    }

    public function test_an_unsupported_driver_is_rejected(): void
    {
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Unsupported database driver [postgres].');

        new DatabaseConfig('postgres', 'bulbula')->dsn();
    }

    public function test_mysql_connections_are_strict_and_utf8mb4(): void
    {
        $options = new DatabaseConfig('mysql', 'bulbula')->options();

        self::assertSame(PDO::ERRMODE_EXCEPTION, $options[PDO::ATTR_ERRMODE]);
        self::assertSame(PDO::FETCH_ASSOC, $options[PDO::ATTR_DEFAULT_FETCH_MODE]);
        self::assertFalse($options[PDO::ATTR_EMULATE_PREPARES]);
        self::assertFalse($options[PDO::ATTR_STRINGIFY_FETCHES]);
        self::assertSame(
            'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            $options[PDO::MYSQL_ATTR_INIT_COMMAND],
        );
    }

    public function test_the_init_command_follows_the_configured_charset_and_collation(): void
    {
        $options = new DatabaseConfig(
            'mysql',
            'bulbula',
            charset: 'utf8mb3',
            collation: 'utf8mb3_general_ci',
        )->options();

        self::assertSame('SET NAMES utf8mb3 COLLATE utf8mb3_general_ci', $options[PDO::MYSQL_ATTR_INIT_COMMAND]);
    }

    public function test_sqlite_connections_have_no_mysql_options(): void
    {
        $options = DatabaseConfig::sqliteInMemory()->options();

        self::assertArrayNotHasKey(PDO::MYSQL_ATTR_INIT_COMMAND, $options);
        self::assertSame(PDO::ERRMODE_EXCEPTION, $options[PDO::ATTR_ERRMODE]);
    }

    public function test_the_logged_target_never_contains_the_password(): void
    {
        $mysql = new DatabaseConfig('mysql', 'bulbula', 'db.internal', 3307, 'user', 'super-secret');

        self::assertSame('db.internal:3307/bulbula', $mysql->target());
        self::assertStringNotContainsString('super-secret', $mysql->target());
        self::assertSame(':memory:', DatabaseConfig::sqliteInMemory()->target());
    }

    public function test_it_is_built_from_the_application_configuration(): void
    {
        $config = DatabaseConfig::fromConfig(Config::fromArray([
            'database' => [
                'driver' => 'mysql',
                'host' => 'mariadb',
                'port' => 3306,
                'database' => 'bulbula_test',
                'username' => 'bulbula',
                'password' => 'secret',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
        ]));

        self::assertSame('mysql:host=mariadb;port=3306;dbname=bulbula_test;charset=utf8mb4', $config->dsn());
        self::assertSame('bulbula', $config->username());
        self::assertSame('secret', $config->password());
        self::assertSame('mariadb:3306/bulbula_test', $config->target());
    }
}
