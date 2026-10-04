<?php

declare(strict_types=1);

use Bulbula\Database\Connection;
use Bulbula\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(Connection $connection): void
    {
        $connection->statement('CREATE TABLE second_table (id INTEGER PRIMARY KEY)');
    }

    public function down(Connection $connection): void
    {
        $connection->statement('DROP TABLE IF EXISTS second_table');
    }
};
