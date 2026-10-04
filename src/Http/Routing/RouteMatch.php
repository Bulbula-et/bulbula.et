<?php

declare(strict_types=1);

namespace Bulbula\Http\Routing;

/**
 * The result of matching a request against the route table.
 */
final readonly class RouteMatch
{
    /**
     * @param array<string, string> $parameters
     */
    public function __construct(
        private Route $route,
        private array $parameters = [],
    ) {
    }

    public function route(): Route
    {
        return $this->route;
    }

    /**
     * @return array<string, string>
     */
    public function parameters(): array
    {
        return $this->parameters;
    }
}
