<?php

declare(strict_types=1);

use Bulbula\Foundation\Application;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Front controller.
 *
 * The engineering baseline only boots configuration, logging and error
 * handling. Until the first Bulbula feature lands, every request is answered
 * with the static pre-launch page that lives next to this file.
 */
$app = Application::boot(dirname(__DIR__));

$app->logger()->debug('Request handled by the pre-launch front controller', [
    'environment' => $app->environment()->value,
]);

header('Content-Type: text/html; charset=utf-8');

require __DIR__ . '/index.html';
