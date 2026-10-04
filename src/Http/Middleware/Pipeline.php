<?php

declare(strict_types=1);

namespace Bulbula\Http\Middleware;

use Bulbula\Http\Request;
use Bulbula\Http\Response;
use Closure;

/**
 * Runs middleware in registration order around a final handler.
 *
 * The pipeline is built back to front so that the first registered middleware
 * is the outermost one: request flows down, response bubbles back up.
 */
final readonly class Pipeline
{
    /**
     * @param list<Middleware> $middleware
     */
    public function __construct(private array $middleware = [])
    {
    }

    /**
     * @param list<Middleware> $middleware
     */
    public function append(array $middleware): self
    {
        return new self([...$this->middleware, ...$middleware]);
    }

    /**
     * @param Closure(Request): Response $destination
     */
    public function run(Request $request, Closure $destination): Response
    {
        $next = $destination;

        foreach (array_reverse($this->middleware) as $middleware) {
            $current = $next;
            $next = static fn (Request $passed): Response => $middleware->process($passed, $current);
        }

        return $next($request);
    }
}
