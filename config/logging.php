<?php

declare(strict_types=1);

use Bulbula\Support\Env;

return [
    'channel' => Env::string('LOG_CHANNEL', 'bulbula'),
    'path' => Env::string('LOG_PATH', 'storage/logs/app.log'),
    'level' => Env::string('LOG_LEVEL', 'debug'),
];
