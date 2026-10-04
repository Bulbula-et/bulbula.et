<?php

declare(strict_types=1);

namespace Bulbula\Http;

use JsonException;
use RuntimeException;

/**
 * An immutable value object describing the outgoing response.
 */
final readonly class Response
{
    /**
     * Conservative flags: throw instead of emitting broken JSON, and escape
     * the characters that make JSON unsafe to embed in HTML.
     */
    public const int JSON_FLAGS = JSON_THROW_ON_ERROR
        | JSON_UNESCAPED_UNICODE
        | JSON_UNESCAPED_SLASHES
        | JSON_HEX_TAG
        | JSON_HEX_AMP
        | JSON_HEX_APOS
        | JSON_HEX_QUOT;

    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        private Status $status = Status::Ok,
        private string $body = '',
        private array $headers = [],
    ) {
    }

    /**
     * @param array<string, string> $headers
     */
    public static function text(string $body, Status $status = Status::Ok, array $headers = []): self
    {
        return new self($status, $body, ['Content-Type' => 'text/plain; charset=utf-8'] + $headers);
    }

    /**
     * @param array<string, string> $headers
     */
    public static function html(string $body, Status $status = Status::Ok, array $headers = []): self
    {
        return new self($status, $body, ['Content-Type' => 'text/html; charset=utf-8'] + $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws RuntimeException when the payload cannot be encoded
     */
    public static function json(mixed $data, Status $status = Status::Ok, array $headers = []): self
    {
        try {
            $body = json_encode($data, self::JSON_FLAGS);
        } catch (JsonException $exception) {
            throw new RuntimeException('Response payload could not be encoded as JSON.', 0, $exception);
        }

        return new self($status, $body, ['Content-Type' => 'application/json; charset=utf-8'] + $headers);
    }

    public static function noContent(): self
    {
        return new self(Status::NoContent);
    }

    /**
     * @param array<string, string> $headers
     */
    public static function redirect(string $location, Status $status = Status::Found, array $headers = []): self
    {
        return new self($status, '', ['Location' => $location] + $headers);
    }

    public function status(): Status
    {
        return $this->status;
    }

    public function body(): string
    {
        return $this->status->allowsBody() ? $this->body : '';
    }

    /**
     * @return array<string, string>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    public function header(string $name, ?string $default = null): ?string
    {
        return $this->headers[$name] ?? $default;
    }

    public function withHeader(string $name, string $value): self
    {
        return new self($this->status, $this->body, [$name => $value] + $this->headers);
    }

    /**
     * @param array<string, string> $headers
     */
    public function withHeaders(array $headers): self
    {
        return new self($this->status, $this->body, $headers + $this->headers);
    }

    public function withStatus(Status $status): self
    {
        return new self($status, $this->body, $this->headers);
    }
}
