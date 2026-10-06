<?php

declare(strict_types=1);

/*
 * The front controller is the one file that talks to the web SAPI, so it is
 * exercised as a real process through the PHP built-in server.
 */

beforeEach(function (): void {
    $this->root = dirname(__DIR__, 2);
});

/**
 * @return array{status: int, headers: array<string, string>, body: string}
 */
function request(string $root, string $method, string $path): array
{
    $port = random_int(8300, 8999);
    $server = proc_open(
        sprintf('exec php -S 127.0.0.1:%d -t %s/public', $port, escapeshellarg($root)),
        [1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']],
        $pipes,
    );

    expect($server)->toBeResource();

    try {
        $socket = null;

        for ($attempt = 0; $attempt < 50 && $socket === false || $socket === null; ++$attempt) {
            usleep(100_000);
            $socket = @fsockopen('127.0.0.1', $port, $errno, $error, 0.2);
        }

        expect($socket)->not->toBeFalse();

        if (is_resource($socket)) {
            fclose($socket);
        }

        $context = stream_context_create([
            'http' => ['method' => $method, 'ignore_errors' => true, 'timeout' => 5],
        ]);

        $body = (string) file_get_contents(sprintf('http://127.0.0.1:%d%s', $port, $path), false, $context);
        $headers = [];
        $status = 0;

        foreach ($http_response_header ?? [] as $line) {
            if (preg_match('~^HTTP/\d(?:\.\d)? (\d{3})~', $line, $matches) === 1) {
                $status = (int) $matches[1];

                continue;
            }

            [$name, $value] = array_pad(explode(':', $line, 2), 2, '');
            $headers[strtolower(trim($name))] = trim($value);
        }

        return ['status' => $status, 'headers' => $headers, 'body' => $body];
    } finally {
        if (is_resource($server)) {
            proc_terminate($server);
            proc_close($server);
        }
    }
}

it('serves the landing page through the front controller', function (): void {
    $response = request($this->root, 'GET', '/');

    expect($response['status'])->toBe(200)
        ->and($response['headers']['content-type'] ?? null)->toBe('text/html; charset=utf-8')
        ->and($response['headers']['x-content-type-options'] ?? null)->toBe('nosniff')
        ->and($response['body'])->toContain('BULBULA.ET');
});

it('serves a real asset without routing it through the application', function (): void {
    $response = request($this->root, 'GET', '/assets/css/styles.css');

    expect($response['status'])->toBe(200)
        ->and($response['headers']['content-type'] ?? null)->toContain('text/css')
        // Application responses always carry the security headers; a file
        // served straight off disk does not go through the middleware.
        ->and($response['headers']['x-content-type-options'] ?? null)->toBeNull();
});

it('does not answer the stale pre-launch path', function (): void {
    $response = request($this->root, 'GET', '/public/index.html');

    expect($response['status'])->toBe(404)
        ->and($response['body'])->toContain('No route matches [/public/index.html]');
});

it('serves the health endpoint through the front controller', function (): void {
    $response = request($this->root, 'GET', '/health');

    expect($response['status'])->toBe(200)
        ->and($response['headers']['content-type'] ?? null)->toBe('application/json; charset=utf-8');

    $payload = json_decode($response['body'], true, 8, JSON_THROW_ON_ERROR);

    expect($payload['status'])->toBe('pass');
});

it('answers an unknown path with a 404 through the front controller', function (): void {
    $response = request($this->root, 'GET', '/definitely-missing');

    expect($response['status'])->toBe(404)
        ->and($response['body'])->toContain('404 Not Found');
});

it('answers the wrong verb with a 405 through the front controller', function (): void {
    $response = request($this->root, 'DELETE', '/health');

    expect($response['status'])->toBe(405)
        ->and($response['headers']['allow'] ?? null)->toBe('GET, HEAD');
});
