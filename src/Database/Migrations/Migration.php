<?php

declare(strict_types=1);

namespace Bulbula\Database\Migrations;

use Bulbula\Database\Connection;

/**
 * One reversible schema change.
 */
abstract class Migration
{
    abstract public function up(Connection $connection): void;

    abstract public function down(Connection $connection): void;
}
