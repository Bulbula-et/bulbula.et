<?php

declare(strict_types=1);

namespace Bulbula\Http;

use Bulbula\Http\Exception\HttpException;
use Throwable;

use function sprintf;

/**
 * The last line of defence for the front controller.
 *
 * The kernel renders anything that fails inside a request, but a failure
 * while booting — an unreadable configuration file, an unwritable log
 * directory, a request the superglobals cannot describe — happens before the
 * kernel exists. Without this the web server answers with a blank 500, which
 * is invisible to anyone who cannot read the server log.
 *
 * It therefore depends on nothing: no configuration, no logger, no container.
 */
final readonly class BootstrapFailure
{
    public static function response(Throwable $throwable, bool $debug): Response
    {
        $status = $throwable instanceof HttpException ? $throwable->status() : Status::InternalServerError;

        return Response::text(self::body($throwable, $debug, $status), $status, [
            'Cache-Control' => 'no-store',
        ]);
    }

    private static function body(Throwable $throwable, bool $debug, Status $status): string
    {
        $summary = sprintf("%d %s\n", $status->value, $status->reasonPhrase());

        if (! $debug) {
            return $summary . "The application could not start.\n";
        }

        return $summary . sprintf(
            "%s: %s\nin %s:%d\n",
            $throwable::class,
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine(),
        );
    }
}
