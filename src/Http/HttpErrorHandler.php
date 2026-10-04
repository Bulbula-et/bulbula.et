<?php

declare(strict_types=1);

namespace Bulbula\Http;

use Bulbula\Error\ErrorHandler;
use Bulbula\Http\Exception\HttpException;
use Throwable;

/**
 * Turns any throwable into a response, exactly once, in one place.
 *
 * Controllers never catch infrastructure failures themselves: they throw, and
 * this class decides the status, the representation (JSON or web) and how much
 * may be revealed.
 */
final readonly class HttpErrorHandler
{
    public function __construct(
        private ErrorHandler $errors,
        private bool $debug,
    ) {
    }

    public function render(Request $request, Throwable $throwable): Response
    {
        $status = $throwable instanceof HttpException ? $throwable->status() : Status::InternalServerError;
        $headers = $throwable instanceof HttpException ? $throwable->headers() : [];

        // Client errors are expected traffic, not incidents: only report the
        // failures the team can actually act on.
        $reference = $status->isServerError() ? $this->errors->report($throwable) : null;

        $message = $throwable instanceof HttpException
            ? $throwable->getMessage()
            : $status->reasonPhrase();

        return $request->expectsJson()
            ? $this->json($throwable, $status, $headers, $message, $reference)
            : $this->web($throwable, $status, $headers, $message, $reference);
    }

    /**
     * @param array<string, string> $headers
     */
    private function json(
        Throwable $throwable,
        Status $status,
        array $headers,
        string $message,
        ?string $reference,
    ): Response {
        $error = [
            'status' => $status->value,
            'title' => $status->reasonPhrase(),
            'message' => $this->publicMessage($status, $message),
        ];

        if ($reference !== null) {
            $error['reference'] = $reference;
        }

        if ($this->debug) {
            $error['debug'] = $this->diagnostics($throwable);
        }

        return Response::json(['error' => $error], $status, $headers);
    }

    /**
     * @param array<string, string> $headers
     */
    private function web(
        Throwable $throwable,
        Status $status,
        array $headers,
        string $message,
        ?string $reference,
    ): Response {
        $lines = [
            sprintf('%d %s', $status->value, $status->reasonPhrase()),
            $this->publicMessage($status, $message),
        ];

        if ($reference !== null) {
            $lines[] = sprintf('Reference: %s', $reference);
        }

        if ($this->debug) {
            $diagnostics = $this->diagnostics($throwable);
            $lines[] = sprintf('%s: %s', $diagnostics['exception'], $diagnostics['message']);
            $lines[] = sprintf('in %s:%d', $diagnostics['file'], $diagnostics['line']);
            $lines[] = $diagnostics['trace'];
        }

        return Response::text(implode("\n", $lines) . "\n", $status, $headers);
    }

    /**
     * Server errors never leak their message; client errors describe themselves.
     */
    private function publicMessage(Status $status, string $message): string
    {
        return $status->isServerError()
            ? 'Something went wrong. Please try again later.'
            : $message;
    }

    /**
     * @return array{exception: string, message: string, file: string, line: int, trace: string}
     */
    private function diagnostics(Throwable $throwable): array
    {
        return [
            'exception' => $throwable::class,
            'message' => $throwable->getMessage(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTraceAsString(),
        ];
    }
}
