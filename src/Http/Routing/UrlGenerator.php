<?php

declare(strict_types=1);

namespace Bulbula\Http\Routing;

use Bulbula\Http\Exception\RouteNotDefinedException;
use FastRoute\RouteParser\Std;

use function count;
use function is_string;

/**
 * Builds URLs for named routes.
 *
 * FastRoute only matches; the same parser it uses for matching is reused here
 * so generation can never drift from the registered patterns.
 */
final readonly class UrlGenerator
{
    /**
     * @param array<string, Route> $named
     */
    public function __construct(private array $named)
    {
    }

    /**
     * @param list<Route> $routes
     *
     * @throws RouteNotDefinedException when two routes share a name
     */
    public static function fromRoutes(array $routes): self
    {
        $named = [];

        foreach ($routes as $route) {
            $name = $route->nameOrNull();

            if ($name === null) {
                continue;
            }

            if (isset($named[$name])) {
                throw RouteNotDefinedException::duplicateName($name);
            }

            $named[$name] = $route;
        }

        return new self($named);
    }

    /**
     * @param array<string, string|int> $parameters
     *
     * @throws RouteNotDefinedException when the name is unknown or parameters are missing
     */
    public function url(string $name, array $parameters = []): string
    {
        $route = $this->named[$name] ?? null;

        if (! $route instanceof Route) {
            throw RouteNotDefinedException::named($name);
        }

        /** @var list<list<string|array{0: string, 1: string}>> $variants */
        $variants = new Std()->parse($route->path());

        foreach ($variants as $variant) {
            $url = $this->build($variant, $parameters);

            if ($url !== null) {
                return $url;
            }
        }

        throw RouteNotDefinedException::missingParameters($name);
    }

    /**
     * @param list<string|array{0: string, 1: string}> $variant
     * @param array<string, string|int>                $parameters
     */
    private function build(array $variant, array $parameters): ?string
    {
        $url = '';
        $used = 0;

        foreach ($variant as $segment) {
            if (is_string($segment)) {
                $url .= $segment;

                continue;
            }

            [$parameter, $pattern] = $segment;
            // A missing parameter yields an empty value, which no route pattern
            // accepts, so the variant is rejected by the check below.
            $value = (string) ($parameters[$parameter] ?? '');

            if (preg_match('~^' . $pattern . '$~', $value) !== 1) {
                return null;
            }

            $url .= rawurlencode($value);
            ++$used;
        }

        return $used === count($parameters) ? $url : null;
    }
}
