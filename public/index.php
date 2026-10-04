<?php

declare(strict_types=1);

use Bulbula\Foundation\Application;
use Bulbula\Foundation\HttpKernelFactory;
use Bulbula\Http\Request;
use Bulbula\Http\ResponseEmitter;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Front controller.
 *
 * Boot, build a request from the superglobals, hand it to the kernel and emit
 * the response. The two native side effects - the status line and the headers
 * - are injected into the emitter so that nothing inside src/ depends on the
 * web SAPI, and every layer stays testable from the command line.
 */
$application = Application::boot(dirname(__DIR__));

$request = Request::fromGlobals($_SERVER, $_GET, $_POST, (string) file_get_contents('php://input'));

$response = HttpKernelFactory::create($application)->handle($request);

new ResponseEmitter(
    static function (int $status): void {
        http_response_code($status);
    },
    static function (string $name, string $value): void {
        header($name . ': ' . $value, true);
    },
)->emit($response);
