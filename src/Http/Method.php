<?php

declare(strict_types=1);

namespace Bulbula\Http;

use Bulbula\Http\Exception\MalformedRequestException;

/**
 * The HTTP methods the application accepts.
 */
enum Method: string
{
    case Get = 'GET';

    case Head = 'HEAD';

    case Post = 'POST';

    case Put = 'PUT';

    case Patch = 'PATCH';

    case Delete = 'DELETE';

    case Options = 'OPTIONS';

    /**
     * @throws MalformedRequestException when the verb is unknown or malformed
     */
    public static function fromName(string $method): self
    {
        $resolved = self::tryFrom(strtoupper(trim($method)));

        if (! $resolved instanceof self) {
            throw MalformedRequestException::unsupportedMethod($method);
        }

        return $resolved;
    }

    /**
     * Whether a request of this method is expected to carry a body.
     */
    public function expectsBody(): bool
    {
        return match ($this) {
            self::Post, self::Put, self::Patch => true,
            default => false,
        };
    }
}
