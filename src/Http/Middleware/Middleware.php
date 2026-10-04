<?php

declare(strict_types=1);

namespace Bulbula\Http\Middleware;

use Bulbula\Http\Request;
use Bulbula\Http\Response;
use Closure;

/**
 * A single step of the request pipeline.
 *
 * Implementations either return a response themselves (short-circuiting) or
 * call `$next` to hand the request to the rest of the pipeline.
 */
interface Middleware
{
    /**
     * @param Closure(Request): Response $next
     */
    public function process(Request $request, Closure $next): Response;
}
