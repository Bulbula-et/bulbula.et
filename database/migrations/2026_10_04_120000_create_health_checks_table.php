<?php

declare(strict_types=1);

use Bulbula\Database\Connection;
use Bulbula\Database\Migrations\Migration;

/*
 * The first migration deliberately creates infrastructure, not domain schema.
 * `health_checks` gives operators a place to record a deployment smoke check
 * and gives this phase a real, reversible migration to run. Domain tables
 * arrive with the features that own them.
 */
return new class () extends Migration {
    public function up(Connection $connection): void
    {
        $connection->statement(<<<'SQL'
            CREATE TABLE IF NOT EXISTS health_checks (
                id INTEGER NOT NULL,
                checked_at VARCHAR(19) NOT NULL,
                PRIMARY KEY (id)
            )
            SQL);
    }

    public function down(Connection $connection): void
    {
        $connection->statement('DROP TABLE IF EXISTS health_checks');
    }
};
