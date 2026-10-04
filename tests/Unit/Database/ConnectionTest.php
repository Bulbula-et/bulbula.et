<?php

declare(strict_types=1);

namespace Tests\Unit\Database;

use Bulbula\Database\Connection;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Database\DatabaseException;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(Connection::class)]
final class ConnectionTest extends TestCase
{
    private Connection $connection;

    protected function setUp(): void
    {
        $this->connection = new Connection(DatabaseConfig::sqliteInMemory());
        $this->connection->statement('CREATE TABLE businesses (id INTEGER PRIMARY KEY, name VARCHAR(80) NOT NULL)');

        parent::setUp();
    }

    public function test_it_connects_lazily_and_reuses_the_handle(): void
    {
        $connection = new Connection(DatabaseConfig::sqliteInMemory());

        self::assertFalse($connection->isConnected());

        $pdo = $connection->pdo();

        self::assertTrue($connection->isConnected());
        self::assertSame($pdo, $connection->pdo());
        self::assertInstanceOf(PDO::class, $pdo);
    }

    public function test_it_exposes_the_driver(): void
    {
        self::assertSame('sqlite', $this->connection->driver());
    }

    public function test_it_executes_writes_and_reads_rows_back(): void
    {
        $affected = $this->connection->execute(
            'INSERT INTO businesses (id, name) VALUES (:id, :name)',
            ['id' => 1, 'name' => 'Bulbula Cafe'],
        );

        self::assertSame(1, $affected);
        self::assertSame(
            [['id' => 1, 'name' => 'Bulbula Cafe']],
            $this->connection->select('SELECT id, name FROM businesses'),
        );
    }

    public function test_select_returns_every_matching_row(): void
    {
        $this->insert(1, 'First');
        $this->insert(2, 'Second');
        $this->insert(3, 'Third');

        self::assertSame(
            [['id' => 1, 'name' => 'First'], ['id' => 2, 'name' => 'Second'], ['id' => 3, 'name' => 'Third']],
            $this->connection->select('SELECT id, name FROM businesses ORDER BY id'),
        );
    }

    public function test_select_returns_an_empty_list_when_nothing_matches(): void
    {
        self::assertSame([], $this->connection->select('SELECT id FROM businesses WHERE id = :id', ['id' => 99]));
    }

    public function test_select_one_returns_a_single_row_or_null(): void
    {
        $this->insert(1, 'Bulbula Cafe');

        self::assertSame(
            ['name' => 'Bulbula Cafe'],
            $this->connection->selectOne('SELECT name FROM businesses WHERE id = :id', ['id' => 1]),
        );
        self::assertNull($this->connection->selectOne('SELECT name FROM businesses WHERE id = :id', ['id' => 2]));
    }

    public function test_bindings_are_sent_as_parameters_and_not_interpolated(): void
    {
        $this->insert(1, "Robert'); DROP TABLE businesses;--");

        self::assertSame(
            [['name' => "Robert'); DROP TABLE businesses;--"]],
            $this->connection->select('SELECT name FROM businesses'),
        );
    }

    public function test_a_broken_query_raises_a_database_exception(): void
    {
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('The database query failed.');

        $this->connection->select('SELECT * FROM missing_table');
    }

    public function test_a_broken_schema_statement_raises_a_database_exception(): void
    {
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('The database query failed.');

        $this->connection->statement('CREATE TABLE (');
    }

    public function test_a_transaction_commits_the_callback_result(): void
    {
        $result = $this->connection->transaction(function (Connection $connection): string {
            $connection->execute('INSERT INTO businesses (id, name) VALUES (1, :name)', ['name' => 'Committed']);

            return 'done';
        });

        self::assertSame('done', $result);
        self::assertFalse($this->connection->pdo()->inTransaction());
        self::assertCount(1, $this->connection->select('SELECT id FROM businesses'));
    }

    public function test_a_failing_transaction_is_rolled_back_and_rethrown(): void
    {
        try {
            $this->connection->transaction(function (Connection $connection): never {
                $connection->execute('INSERT INTO businesses (id, name) VALUES (1, :name)', ['name' => 'Rolled back']);

                throw new RuntimeException('business rule violated');
            });
        } catch (RuntimeException $exception) {
            self::assertSame('business rule violated', $exception->getMessage());
            self::assertFalse($this->connection->pdo()->inTransaction());
            self::assertSame([], $this->connection->select('SELECT id FROM businesses'));

            return;
        }

        self::fail('The failure must be rethrown.');
    }

    public function test_a_nested_transaction_joins_the_outer_one(): void
    {
        $this->connection->transaction(function (Connection $connection): void {
            $inner = $connection->transaction(static function (Connection $connection): string {
                $connection->execute('INSERT INTO businesses (id, name) VALUES (1, :name)', ['name' => 'Nested']);

                return 'inner';
            });

            self::assertSame('inner', $inner);
            self::assertTrue($connection->pdo()->inTransaction());
        });

        self::assertCount(1, $this->connection->select('SELECT id FROM businesses'));
    }

    public function test_the_callback_receives_the_connection(): void
    {
        $received = $this->connection->transaction(static fn (Connection $connection): Connection => $connection);

        self::assertSame($this->connection, $received);
    }

    public function test_ping_answers_true_for_a_reachable_database(): void
    {
        self::assertTrue($this->connection->ping());
    }

    public function test_ping_answers_false_for_an_unreachable_database(): void
    {
        $connection = new Connection(new DatabaseConfig('sqlite', '/this/path/does/not/exist/bulbula.sqlite'));

        self::assertFalse($connection->ping());
    }

    private function insert(int $id, string $name): void
    {
        $this->connection->execute(
            'INSERT INTO businesses (id, name) VALUES (:id, :name)',
            ['id' => $id, 'name' => $name],
        );
    }
}
