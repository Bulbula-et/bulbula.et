<?php

declare(strict_types=1);

namespace Bulbula\Http;

use Bulbula\Http\Exception\MalformedRequestException;
use JsonException;

use function array_key_exists;
use function is_array;
use function is_scalar;
use function is_string;
use function str_starts_with;

/**
 * An immutable value object describing the incoming request.
 */
final readonly class Request
{
    /**
     * @param array<string, string> $query
     * @param array<string, mixed>  $parsedBody
     * @param array<string, string> $headers      header names are lower-cased
     * @param array<string, string> $routeParameters
     */
    public function __construct(
        private Method $method,
        private string $path,
        private array $query = [],
        private array $parsedBody = [],
        private array $headers = [],
        private string $body = '',
        private array $routeParameters = [],
        private bool $secure = false,
    ) {
    }

    /**
     * Build a request from the PHP superglobals.
     *
     * They are passed in explicitly so the request stays a pure value object
     * and remains trivially testable.
     *
     * @param array<string, mixed> $server
     * @param array<string, mixed> $query
     * @param array<string, mixed> $parsedBody
     *
     * @throws MalformedRequestException when the method or the JSON body is invalid
     */
    public static function fromGlobals(array $server, array $query, array $parsedBody, string $body): self
    {
        $headers = self::headersFromServer($server);
        $method = Method::fromName(self::stringValue($server, 'REQUEST_METHOD', 'GET'));
        $path = self::pathFromServer($server);
        $contentType = $headers['content-type'] ?? '';

        /** @var array<string, mixed> $parsed */
        $parsed = $parsedBody;

        if ($body !== '' && str_starts_with($contentType, 'application/json')) {
            $parsed = self::decodeJson($body);
        }

        return new self(
            $method,
            $path,
            self::queryMap($query),
            $parsed,
            $headers,
            $body,
            [],
            self::isSecureServer($server),
        );
    }

    public function method(): Method
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * @return array<string, string>
     */
    public function queryParameters(): array
    {
        return $this->query;
    }

    public function query(string $key, ?string $default = null): ?string
    {
        return $this->query[$key] ?? $default;
    }

    /**
     * @return array<string, mixed>
     */
    public function parsedBody(): array
    {
        return $this->parsedBody;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return array_key_exists($key, $this->parsedBody) ? $this->parsedBody[$key] : $default;
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
        return $this->headers[strtolower($name)] ?? $default;
    }

    /**
     * @return array<string, string>
     */
    public function routeParameters(): array
    {
        return $this->routeParameters;
    }

    public function routeParameter(string $key, ?string $default = null): ?string
    {
        return $this->routeParameters[$key] ?? $default;
    }

    /**
     * @param array<string, string> $parameters
     */
    public function withRouteParameters(array $parameters): self
    {
        return new self(
            $this->method,
            $this->path,
            $this->query,
            $this->parsedBody,
            $this->headers,
            $this->body,
            $parameters,
            $this->secure,
        );
    }

    /**
     * Whether the client is asking for a JSON representation.
     */
    public function expectsJson(): bool
    {
        if (str_starts_with($this->path, '/api/')) {
            return true;
        }

        if (str_starts_with($this->header('content-type', '') ?? '', 'application/json')) {
            return true;
        }

        return str_contains($this->header('accept', '') ?? '', 'application/json');
    }

    /**
     * @param array<string, mixed> $server
     *
     * @return array<string, string>
     */
    private static function headersFromServer(array $server): array
    {
        $headers = [];

        foreach ($server as $key => $value) {
            if (! is_scalar($value)) {
                continue;
            }

            if (str_starts_with($key, 'HTTP_')) {
                $headers[self::headerName(substr($key, 5))] = (string) $value;

                continue;
            }

            if ($key === 'CONTENT_TYPE' || $key === 'CONTENT_LENGTH') {
                $headers[self::headerName($key)] = (string) $value;
            }
        }

        return $headers;
    }

    private static function headerName(string $key): string
    {
        return strtolower(str_replace('_', '-', $key));
    }

    /**
     * @param array<string, mixed> $server
     */
    private static function pathFromServer(array $server): string
    {
        $uri = self::stringValue($server, 'REQUEST_URI', '/');
        $path = parse_url($uri, PHP_URL_PATH);

        if (! is_string($path) || $path === '') {
            return '/';
        }

        return rawurldecode($path);
    }

    /**
     * @param array<string, mixed> $server
     */
    private static function isSecureServer(array $server): bool
    {
        $https = self::stringValue($server, 'HTTPS', '');

        return $https !== '' && strtolower($https) !== 'off';
    }

    /**
     * @param array<string, mixed> $server
     */
    private static function stringValue(array $server, string $key, string $default): string
    {
        $value = $server[$key] ?? null;

        return is_string($value) && $value !== '' ? $value : $default;
    }

    /**
     * Query parameters are exposed as strings; array-shaped values are ignored.
     *
     * @param array<array-key, mixed> $values
     *
     * @return array<string, string>
     */
    private static function queryMap(array $values): array
    {
        /** @var array<string, string> $map */
        $map = array_map(
            static fn (mixed $value): string => (string) $value,
            array_filter($values, is_scalar(...)),
        );

        return $map;
    }

    /**
     * @return array<string, mixed>
     *
     * @throws MalformedRequestException
     */
    private static function decodeJson(string $body): array
    {
        try {
            /** @var array<string, mixed>|bool|float|int|string|null $decoded */
            $decoded = json_decode($body, true, 32, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw MalformedRequestException::invalidJsonBody();
        }

        if (! is_array($decoded)) {
            throw MalformedRequestException::invalidJsonBody();
        }

        return $decoded;
    }
}
