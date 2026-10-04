<?php

declare(strict_types=1);

use Bulbula\Support\Env;

return [
    'name' => Env::string('APP_NAME', 'Bulbula'),
    'env' => Env::string('APP_ENV', 'local'),
    'debug' => Env::bool('APP_DEBUG', false),
    'url' => Env::string('APP_URL', 'http://localhost:8000'),
    'timezone' => Env::string('APP_TIMEZONE', 'Africa/Addis_Ababa'),
];
