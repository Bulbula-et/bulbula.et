<?php

declare(strict_types=1);

namespace Bulbula\Http\Routing;

use Bulbula\Http\Method;
use Closure;

/**
 * A single registered route.
 */
final class Route
{
    private ?string $name = null;

    /**
     * @param list<Method>                  $methods
     * @param Closure(\Bulbula\Http\Request): \Bulbula\Http\Response $handler
     * @param list<\Bulbula\Http\Middleware\Middleware>              $middleware
     */
    public function __construct(
        private readonly array $methods,
        private readonly string $path,
        private readonly Closure $handler,
        private array $middleware = [],
    ) {
    }

    /**
     * Give the route a name so URLs can be generated for it.
     */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param list<\Bulbula\Http\Middleware\Middleware> $middleware
     */
    public function middleware(array $middleware): self
    {
        $this->middleware = [...$this->middleware, ...$middleware];

        return $this;
    }

    /**
     * @return list<Method>
     */
    public function methods(): array
    {
        return $this->methods;
    }

    public function path(): string
    {
        return $this->path;
    }

    /**
     * @return Closure(\Bulbula\Http\Request): \Bulbula\Http\Response
     */
    public function handler(): Closure
    {
        return $this->handler;
    }

    /**
     * @return list<\Bulbula\Http\Middleware\Middleware>
     */
    public function middlewares(): array
    {
        return $this->middleware;
    }

    public function nameOrNull(): ?string
    {
        return $this->name;
    }
}
