<?php

declare(strict_types=1);

use Bulbula\Foundation\Services;
use Bulbula\Http\Controller\HealthController;
use Bulbula\Http\Response;
use Bulbula\Http\Routing\Router;

/*
 * API routes. Versioned from the first endpoint so that clients never depend
 * on an unversioned URL, and kept separate from the web routes because the
 * two have different contracts: JSON errors, no HTML, no redirects.
 */
return static function (Router $router, Services $services): void {
    $health = new HealthController($services->health());

    $router->group('/api/v1', static function (Router $router) use ($health): void {
        $router->get('/health', static fn (): Response => $health->live())->name('api.v1.health');

        $router->get('/health/ready', static fn (): Response => $health->ready())->name('api.v1.health.ready');
    });
};
