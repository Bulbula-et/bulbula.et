<?php

declare(strict_types=1);

namespace Bulbula\Http;

use Closure;

/**
 * Writes a response to the client.
 *
 * The two native side effects (status line and headers) are injected, which
 * keeps this class free of global state and fully testable; `public/index.php`
 * supplies the real `http_response_code()` and `header()` calls.
 */
final readonly class ResponseEmitter
{
    /**
     * @param Closure(int): void            $sendStatus
     * @param Closure(string, string): void $sendHeader
     */
    public function __construct(
        private Closure $sendStatus,
        private Closure $sendHeader,
    ) {
    }

    public function emit(Response $response): string
    {
        ($this->sendStatus)($response->status()->value);

        foreach ($response->headers() as $name => $value) {
            ($this->sendHeader)($name, $value);
        }

        $body = $response->body();

        echo $body;

        return $body;
    }
}
