<?php

declare(strict_types=1);

use Bulbula\Foundation\Services;
use Bulbula\Http\Controller\HealthController;
use Bulbula\Http\Controller\HomeController;
use Bulbula\Http\Response;
use Bulbula\Http\Routing\Router;

/*
 * Browser-facing routes. Everything here answers with HTML, or with the
 * operational JSON that monitoring tools poll.
 */
return static function (Router $router, Services $services): void {
    $home = new HomeController($services->pages());
    $health = new HealthController($services->health());

    $router->get('/', static fn (): Response => $home->show())->name('home');

    $router->get('/health', static fn (): Response => $health->live())->name('health');

    $router->get('/health/ready', static fn (): Response => $health->ready())->name('health.ready');
};
