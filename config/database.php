<?php

declare(strict_types=1);

use Bulbula\Support\Env;

return [
    'driver' => Env::string('DB_CONNECTION', 'mysql'),
    'host' => Env::string('DB_HOST', '127.0.0.1'),
    'port' => Env::int('DB_PORT', 3306),
    'database' => Env::string('DB_DATABASE', 'bulbula'),
    'username' => Env::string('DB_USERNAME', 'bulbula'),
    'password' => Env::string('DB_PASSWORD', ''),
    'charset' => Env::string('DB_CHARSET', 'utf8mb4'),
    'collation' => Env::string('DB_COLLATION', 'utf8mb4_unicode_ci'),
];
