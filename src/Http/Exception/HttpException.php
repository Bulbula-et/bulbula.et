<?php

declare(strict_types=1);

namespace Bulbula\Http\Exception;

use Bulbula\Http\Status;
use RuntimeException;

/**
 * An exception that carries the HTTP status the client should receive.
 *
 * Throwing one of these is how any layer signals a client-visible failure;
 * the HTTP error handler turns it into a response exactly once.
 */
abstract class HttpException extends RuntimeException
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        private readonly Status $status,
        string $message,
        private readonly array $headers = [],
    ) {
        parent::__construct($message, $status->value);
    }

    public function status(): Status
    {
        return $this->status;
    }

    /**
     * @return array<string, string>
     */
    public function headers(): array
    {
        return $this->headers;
    }
}
