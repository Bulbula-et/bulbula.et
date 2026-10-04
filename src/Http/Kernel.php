<?php

declare(strict_types=1);

namespace Bulbula\Http;

use Bulbula\Http\Middleware\Pipeline;
use Bulbula\Http\Routing\Router;
use Throwable;

/**
 * The HTTP entry point: request in, response out.
 *
 * Global middleware runs around everything, including error responses, so a
 * failed request still gets the security headers. The outer catch is the
 * safety net for a middleware that fails before the inner one can run.
 */
final readonly class Kernel
{
    public function __construct(
        private Router $router,
        private Pipeline $pipeline,
        private HttpErrorHandler $errors,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            return $this->pipeline->run($request, fn (Request $passed): Response => $this->dispatchSafely($passed));
        } catch (Throwable $throwable) {
            return $this->errors->render($request, $throwable);
        }
    }

    private function dispatchSafely(Request $request): Response
    {
        try {
            return $this->dispatch($request);
        } catch (Throwable $throwable) {
            return $this->errors->render($request, $throwable);
        }
    }

    private function dispatch(Request $request): Response
    {
        $match = $this->router->match($request);
        $route = $match->route();
        $handler = $route->handler();

        return new Pipeline($route->middlewares())->run(
            $request->withRouteParameters($match->parameters()),
            $handler,
        );
    }
}
