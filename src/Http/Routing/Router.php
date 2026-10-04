<?php

declare(strict_types=1);

namespace Bulbula\Http\Routing;

use Bulbula\Http\Exception\MethodNotAllowedException;
use Bulbula\Http\Exception\NotFoundException;
use Bulbula\Http\Method;
use Bulbula\Http\Middleware\Middleware;
use Bulbula\Http\Request;
use Closure;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

/**
 * Route registration and matching, backed by nikic/fast-route.
 *
 * Only what the application needs: registration, groups, named routes and a
 * match result that distinguishes "no route" from "wrong method".
 */
final class Router
{
    /** @var list<Route> */
    private array $routes = [];

    private string $prefix = '';

    /** @var list<Middleware> */
    private array $groupMiddleware = [];

    /**
     * @param Closure(Request): \Bulbula\Http\Response $handler
     */
    public function get(string $path, Closure $handler): Route
    {
        return $this->map([Method::Get, Method::Head], $path, $handler);
    }

    /**
     * @param Closure(Request): \Bulbula\Http\Response $handler
     */
    public function post(string $path, Closure $handler): Route
    {
        return $this->map([Method::Post], $path, $handler);
    }

    /**
     * @param Closure(Request): \Bulbula\Http\Response $handler
     */
    public function put(string $path, Closure $handler): Route
    {
        return $this->map([Method::Put], $path, $handler);
    }

    /**
     * @param Closure(Request): \Bulbula\Http\Response $handler
     */
    public function patch(string $path, Closure $handler): Route
    {
        return $this->map([Method::Patch], $path, $handler);
    }

    /**
     * @param Closure(Request): \Bulbula\Http\Response $handler
     */
    public function delete(string $path, Closure $handler): Route
    {
        return $this->map([Method::Delete], $path, $handler);
    }

    /**
     * @param list<Method>                             $methods
     * @param Closure(Request): \Bulbula\Http\Response $handler
     */
    public function map(array $methods, string $path, Closure $handler): Route
    {
        $route = new Route($methods, $this->prefix . $path, $handler, $this->groupMiddleware);

        $this->routes[] = $route;

        return $route;
    }

    /**
     * Register routes sharing a path prefix and middleware stack.
     *
     * @param list<Middleware>     $middleware
     * @param Closure(self): void  $routes
     */
    public function group(string $prefix, Closure $routes, array $middleware = []): void
    {
        $previousPrefix = $this->prefix;
        $previousMiddleware = $this->groupMiddleware;

        $this->prefix = $previousPrefix . $prefix;
        $this->groupMiddleware = [...$previousMiddleware, ...$middleware];

        $routes($this);

        $this->prefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }

    /**
     * @return list<Route>
     */
    public function routes(): array
    {
        return $this->routes;
    }

    public function urls(): UrlGenerator
    {
        return UrlGenerator::fromRoutes($this->routes);
    }

    /**
     * @param array<string, string|int> $parameters
     */
    public function url(string $name, array $parameters = []): string
    {
        return $this->urls()->url($name, $parameters);
    }

    /**
     * @throws NotFoundException         when no route matches the path
     * @throws MethodNotAllowedException when the path matches but the method does not
     */
    public function match(Request $request): RouteMatch
    {
        /**
         * FastRoute returns [FOUND, handler, params], [METHOD_NOT_ALLOWED, methods]
         * or [NOT_FOUND]; the handler is always the Route this class registered.
         *
         * @var array{0: int, 1: Route|list<string>, 2: array<string, string>} $result
         */
        $result = $this->dispatcher()->dispatch($request->method()->value, $request->path());

        if ($result[0] === Dispatcher::FOUND) {
            /** @var Route $route */
            $route = $result[1];

            return new RouteMatch($route, $result[2]);
        }

        if ($result[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            /** @var list<string> $methods */
            $methods = $result[1];

            throw MethodNotAllowedException::forPath(
                $request->path(),
                $request->method(),
                array_map(Method::from(...), $methods),
            );
        }

        throw NotFoundException::forPath($request->path());
    }

    private function dispatcher(): Dispatcher
    {
        $routes = $this->routes;

        return simpleDispatcher(
            static function (RouteCollector $collector) use ($routes): void {
                foreach ($routes as $route) {
                    foreach ($route->methods() as $method) {
                        $collector->addRoute($method->value, $route->path(), $route);
                    }
                }
            },
        );
    }
}
