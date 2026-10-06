<?php

declare(strict_types=1);

use Bulbula\Foundation\Application;
use Bulbula\Foundation\HttpKernelFactory;
use Bulbula\Http\BootstrapFailure;
use Bulbula\Http\Request;
use Bulbula\Http\ResponseEmitter;
use Bulbula\Support\Env;

/*
 * Front controller.
 *
 * Boot, build a request from the superglobals, hand it to the kernel and emit
 * the response. The two native side effects - the status line and the headers
 * - are injected into the emitter so that nothing inside src/ depends on the
 * web SAPI, and every layer stays testable from the command line.
 *
 * Nothing here is allowed to answer with an empty body. The two guards below
 * run before a single class is loaded and are written in syntax that every
 * PHP version can parse, because the two ways a correct deployment still
 * breaks - the host serving this file with an older interpreter, and
 * dependencies that were never installed - both kill the process before any
 * error handler of ours exists. Everything after them is wrapped in a catch.
 */

if (PHP_VERSION_ID < 80400) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    header('Cache-Control: no-store');

    echo "500 Internal Server Error\n";
    echo 'Bulbula requires PHP 8.4 or newer; this request was served by PHP ' . PHP_VERSION . ".\n";

    return;
}

$autoloader = __DIR__ . '/../vendor/autoload.php';

if (! is_file($autoloader)) {
    http_response_code(503);
    header('Content-Type: text/plain; charset=utf-8');
    header('Cache-Control: no-store');

    echo "503 Service Unavailable\n";
    echo "Dependencies are missing: run composer install in the project root.\n";

    return;
}

require_once $autoloader;

try {
    $application = Application::boot(dirname(__DIR__));

    $request = Request::fromGlobals($_SERVER, $_GET, $_POST, (string) file_get_contents('php://input'));

    $response = HttpKernelFactory::create($application)->handle($request);
} catch (Throwable $throwable) {
    error_log(sprintf(
        'Bulbula failed to start: %s: %s in %s:%d',
        $throwable::class,
        $throwable->getMessage(),
        $throwable->getFile(),
        $throwable->getLine(),
    ));

    $response = BootstrapFailure::response($throwable, Env::string('APP_ENV', 'local') !== 'production');
}

$emitter = new ResponseEmitter(
    static function (int $status): void {
        http_response_code($status);
    },
    static function (string $name, string $value): void {
        header($name . ': ' . $value, true);
    },
);

$emitter->emit($response);
