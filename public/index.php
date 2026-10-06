<?php

declare(strict_types=1);

use Bulbula\Foundation\Application;
use Bulbula\Foundation\HttpKernelFactory;
use Bulbula\Http\BootstrapFailure;
use Bulbula\Http\Request;
use Bulbula\Http\ResponseEmitter;
use Bulbula\Support\Env;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Front controller.
 *
 * Boot, build a request from the superglobals, hand it to the kernel and emit
 * the response. The two native side effects - the status line and the headers
 * - are injected into the emitter so that nothing inside src/ depends on the
 * web SAPI, and every layer stays testable from the command line.
 *
 * Everything before the kernel exists is wrapped as well: a failure there
 * would otherwise reach the browser as a blank 500 that only a server log
 * can explain.
 */
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

new ResponseEmitter(
    static function (int $status): void {
        http_response_code($status);
    },
    static function (string $name, string $value): void {
        header($name . ': ' . $value, true);
    },
)->emit($response);
