<?php

declare(strict_types=1);

namespace Bulbula\Foundation;

use Bulbula\Error\ErrorHandler;
use Bulbula\Http\HttpErrorHandler;
use Bulbula\Http\Kernel;
use Bulbula\Http\Middleware\Pipeline;
use Bulbula\Http\Middleware\SecureHeaders;
use Bulbula\Http\Routing\Router;
use Closure;
use RuntimeException;

use function sprintf;

/**
 * Builds the HTTP kernel: routes, global middleware and error rendering.
 *
 * The front controller and the feature tests both go through this factory, so
 * the tests exercise the wiring that actually ships.
 */
final readonly class HttpKernelFactory
{
    public static function create(Application $application, ?Services $services = null): Kernel
    {
        $services ??= Services::forApplication($application);

        $router = new Router();

        foreach (['routes/web.php', 'routes/api.php'] as $file) {
            self::load($application->path($file))($router, $services);
        }

        return new Kernel(
            $router,
            new Pipeline([new SecureHeaders()]),
            new HttpErrorHandler(
                new ErrorHandler($application->logger(), $application->isDebug()),
                $application->isDebug(),
            ),
        );
    }

    /**
     * A route file returns a closure that registers its routes on the router.
     */
    private static function load(string $path): Closure
    {
        /** @var mixed $definition */
        $definition = require $path;

        if (! $definition instanceof Closure) {
            throw new RuntimeException(sprintf('Route file [%s] must return a closure.', $path));
        }

        return $definition;
    }
}
