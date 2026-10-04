<?php

declare(strict_types=1);

namespace Bulbula\Database;

use Closure;
use PDO;
use PDOException;
use PDOStatement;
use Throwable;

/**
 * A lazily-opened PDO connection with the few helpers the application needs.
 *
 * No query builder and no ORM: callers write SQL, bind parameters and get
 * arrays back. Connecting is deferred so booting never requires a database.
 */
final class Connection
{
    private ?PDO $pdo = null;

    public function __construct(
        private readonly DatabaseConfig $config,
        private readonly ConnectionFactory $factory = new ConnectionFactory(),
    ) {
    }

    /**
     * @throws DatabaseException when the connection cannot be established
     */
    public function pdo(): PDO
    {
        return $this->pdo ??= $this->factory->create($this->config);
    }

    public function isConnected(): bool
    {
        return $this->pdo instanceof PDO;
    }

    public function driver(): string
    {
        return $this->config->driver();
    }

    /**
     * @param array<string, scalar|null> $bindings
     *
     * @return list<array<string, mixed>>
     *
     * @throws DatabaseException
     */
    public function select(string $sql, array $bindings = []): array
    {
        /** @var list<array<string, mixed>> $rows */
        $rows = $this->run($sql, $bindings)->fetchAll();

        return $rows;
    }

    /**
     * @param array<string, scalar|null> $bindings
     *
     * @return array<string, mixed>|null
     *
     * @throws DatabaseException
     */
    public function selectOne(string $sql, array $bindings = []): ?array
    {
        /** @var array<string, mixed>|false $row */
        $row = $this->run($sql, $bindings)->fetch();

        return $row === false ? null : $row;
    }

    /**
     * Run a write statement and return the number of affected rows.
     *
     * @param array<string, scalar|null> $bindings
     *
     * @throws DatabaseException
     */
    public function execute(string $sql, array $bindings = []): int
    {
        return $this->run($sql, $bindings)->rowCount();
    }

    /**
     * Run a schema statement. DDL takes no parameters and, on MariaDB, causes
     * an implicit commit, so it is never part of a transaction.
     *
     * @throws DatabaseException
     */
    public function statement(string $sql): void
    {
        try {
            $this->pdo()->exec($sql);
        } catch (PDOException $exception) {
            throw DatabaseException::queryFailed($exception);
        }
    }

    /**
     * Run a callback inside a transaction, committing on success and rolling
     * back on any failure. Already being inside a transaction is respected:
     * the callback then joins the outer one.
     *
     * @template TReturn
     *
     * @param Closure(self): TReturn $callback
     *
     * @return TReturn
     */
    public function transaction(Closure $callback): mixed
    {
        $pdo = $this->pdo();

        if ($pdo->inTransaction()) {
            return $callback($this);
        }

        $pdo->beginTransaction();

        try {
            $result = $callback($this);
        } catch (Throwable $throwable) {
            $pdo->rollBack();

            throw $throwable;
        }

        $pdo->commit();

        return $result;
    }

    /**
     * Whether the database answers. Used by the readiness check.
     */
    public function ping(): bool
    {
        try {
            $this->select('SELECT 1');

            return true;
        } catch (DatabaseException) {
            return false;
        }
    }

    /**
     * @param array<string, scalar|null> $bindings
     *
     * @throws DatabaseException
     */
    private function run(string $sql, array $bindings): PDOStatement
    {
        try {
            $statement = $this->pdo()->prepare($sql);
            $statement->execute($bindings);

            return $statement;
        } catch (PDOException $exception) {
            throw DatabaseException::queryFailed($exception);
        }
    }
}
