<?php

declare(strict_types=1);

namespace Bulbula\Http\Exception;

use LogicException;

/**
 * A URL was requested for a route name that was never registered, or with
 * parameters that do not satisfy the route pattern. This is a programming
 * error, never a client error.
 */
final class RouteNotDefinedException extends LogicException
{
    public static function named(string $name): self
    {
        return new self(sprintf('No route is registered under the name [%s].', $name));
    }

    public static function missingParameters(string $name): self
    {
        return new self(sprintf('Route [%s] cannot be built with the given parameters.', $name));
    }

    public static function duplicateName(string $name): self
    {
        return new self(sprintf('Route name [%s] is already registered.', $name));
    }
}
