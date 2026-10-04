<?php

declare(strict_types=1);

namespace Bulbula\Error;

use Closure;
use ErrorException;
use Psr\Log\LoggerInterface;
use Throwable;

use function sprintf;

/**
 * Converts PHP errors into exceptions, logs every uncaught throwable and
 * renders a safe message: verbose while developing, opaque in production.
 */
final readonly class ErrorHandler
{
    /**
     * @param (Closure(int): void)|null $statusCodeEmitter overrides how the HTTP status code is sent (testing seam)
     * @param string $sapi the server API the handler runs under
     */
    public function __construct(
        private LoggerInterface $logger,
        private bool $debug,
        private ?Closure $statusCodeEmitter = null,
        private string $sapi = PHP_SAPI,
    ) {
    }

    /**
     * An HTTP status code can only be sent from a web SAPI before output started.
     */
    public static function shouldSetStatusCode(bool $headersSent, string $sapi): bool
    {
        return ! $headersSent && $sapi !== 'cli';
    }

    /**
     * Register the handler with the PHP runtime.
     */
    public function register(): void
    {
        set_error_handler($this->handleError(...));
        set_exception_handler($this->handleThrowable(...));
    }

    /**
     * Promote any non-silenced PHP error to an ErrorException.
     *
     * @throws ErrorException
     */
    public function handleError(int $severity, string $message, string $file = '', int $line = 0): bool
    {
        if ((error_reporting() & $severity) === 0) {
            return false;
        }

        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    /**
     * Log an uncaught throwable and emit a response body for it.
     */
    public function handleThrowable(Throwable $throwable): void
    {
        $reference = $this->report($throwable);

        if (self::shouldSetStatusCode(headers_sent(), $this->sapi)) {
            $this->sendStatusCode(500);
        }

        echo $this->render($throwable, $reference);
    }

    /**
     * Log a throwable and return the reference that correlates the log entry
     * with whatever is shown to the client.
     */
    public function report(Throwable $throwable): string
    {
        $reference = $this->reference();

        $this->logger->error($throwable->getMessage(), [
            'reference' => $reference,
            'exception' => $throwable::class,
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
        ]);

        return $reference;
    }

    /**
     * Send an HTTP status code.
     *
     * Tests run under the CLI SAPI, where http_response_code() is a no-op, so
     * the emitter is injectable to keep the behaviour observable.
     */
    public function sendStatusCode(int $code): void
    {
        $emitter = $this->statusCodeEmitter;

        if ($emitter instanceof Closure) {
            $emitter($code);

            return;
        }

        http_response_code($code);
    }

    /**
     * Build the message shown to the client.
     */
    public function render(Throwable $throwable, string $reference): string
    {
        if (! $this->debug) {
            return sprintf(
                "Something went wrong. Please try again later.\nReference: %s\n",
                $reference,
            );
        }

        return sprintf(
            "%s: %s\nin %s:%d\nReference: %s\n%s\n",
            $throwable::class,
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine(),
            $reference,
            $throwable->getTraceAsString(),
        );
    }

    /**
     * A short identifier correlating the client message with the log entry.
     */
    public function reference(): string
    {
        return bin2hex(random_bytes(6));
    }
}
