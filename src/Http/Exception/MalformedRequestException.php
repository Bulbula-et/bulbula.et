<?php

declare(strict_types=1);

namespace Bulbula\Http\Exception;

use Bulbula\Http\Status;

/**
 * The request could not be understood and must be rejected before routing.
 */
final class MalformedRequestException extends HttpException
{
    public static function unsupportedMethod(string $method): self
    {
        return new self(Status::MethodNotAllowed, sprintf('Unsupported HTTP method [%s].', strtoupper(trim($method))));
    }

    public static function invalidJsonBody(): self
    {
        return new self(Status::BadRequest, 'Request body is not valid JSON.');
    }
}
