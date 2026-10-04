<?php

declare(strict_types=1);

use Bulbula\Database\Connection;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Foundation\Application;
use Bulbula\Foundation\HttpKernelFactory;
use Bulbula\Foundation\Services;
use Bulbula\Http\Request;
use Bulbula\Http\Status;

/**
 * @param array<string, mixed> $server
 */
function handle(string $method, string $path, ?Connection $connection = null, array $server = []): Bulbula\Http\Response
{
    $application = Application::boot(dirname(__DIR__, 2), registerErrorHandler: false);
    $services = Services::forApplication($application, $connection ?? new Connection(DatabaseConfig::sqliteInMemory()));

    return HttpKernelFactory::create($application, $services)->handle(Request::fromGlobals(
        ['REQUEST_METHOD' => $method, 'REQUEST_URI' => $path, ...$server],
        [],
        [],
        '',
    ));
}

function migratedConnection(): Connection
{
    $connection = new Connection(DatabaseConfig::sqliteInMemory());
    $connection->statement('CREATE TABLE migrations (migration VARCHAR(255) PRIMARY KEY)');

    return $connection;
}

it('answers the liveness probe without a database', function (): void {
    $response = handle('GET', '/health');

    expect($response->status())->toBe(Status::Ok)
        ->and($response->header('Content-Type'))->toBe('application/json; charset=utf-8')
        ->and($response->header('Cache-Control'))->toBe('no-store');

    $payload = json_decode($response->body(), true, 8, JSON_THROW_ON_ERROR);

    expect($payload)->toHaveKeys(['status', 'application', 'environment', 'time', 'checks'])
        ->and($payload['status'])->toBe('pass')
        ->and($payload['checks'])->toBe(['application' => ['status' => 'pass']]);
});

it('answers the liveness probe under the versioned api prefix', function (): void {
    expect(handle('GET', '/api/v1/health')->status())->toBe(Status::Ok);
});

it('reports a database that is not migrated as not ready', function (): void {
    $response = handle('GET', '/health/ready');

    expect($response->status())->toBe(Status::ServiceUnavailable);

    $payload = json_decode($response->body(), true, 8, JSON_THROW_ON_ERROR);

    expect($payload['status'])->toBe('fail')
        ->and($payload['checks']['database']['status'])->toBe('fail');
});

it('reports a migrated database as ready', function (): void {
    $response = handle('GET', '/api/v1/health/ready', migratedConnection());

    $payload = json_decode($response->body(), true, 8, JSON_THROW_ON_ERROR);

    expect($response->status())->toBe(Status::Ok)
        ->and($payload['checks']['database'])->toBe(['status' => 'pass']);
});

it('serves the pre-launch page on the home route', function (): void {
    $response = handle('GET', '/');

    expect($response->status())->toBe(Status::Ok)
        ->and($response->header('Content-Type'))->toBe('text/html; charset=utf-8')
        ->and($response->body())->toContain('BULBULA.ET');
});

it('secures every response with the baseline headers', function (string $path): void {
    $response = handle('GET', $path);

    expect($response->header('X-Content-Type-Options'))->toBe('nosniff')
        ->and($response->header('X-Frame-Options'))->toBe('DENY')
        ->and($response->header('Referrer-Policy'))->toBe('strict-origin-when-cross-origin')
        ->and($response->header('Content-Security-Policy'))->toBe(Bulbula\Http\Middleware\SecureHeaders::contentSecurityPolicy())
        ->and($response->header('Strict-Transport-Security'))->toBeNull();
})->with(['/', '/health', '/api/v1/health', '/missing']);

it('adds strict transport security to https requests', function (): void {
    $response = handle('GET', '/health', null, ['HTTPS' => 'on']);

    expect($response->header('Strict-Transport-Security'))->toBe('max-age=31536000; includeSubDomains');
});

it('answers an unknown web path with a plain-text 404', function (): void {
    $response = handle('GET', '/does-not-exist');

    expect($response->status())->toBe(Status::NotFound)
        ->and($response->header('Content-Type'))->toBe('text/plain; charset=utf-8')
        ->and($response->body())->toContain('404 Not Found');
});

it('answers an unknown api path with a json 404', function (): void {
    $response = handle('GET', '/api/v1/does-not-exist');

    expect($response->status())->toBe(Status::NotFound)
        ->and($response->header('Content-Type'))->toBe('application/json; charset=utf-8');

    $payload = json_decode($response->body(), true, 8, JSON_THROW_ON_ERROR);

    expect($payload['error']['status'])->toBe(404)
        ->and($payload['error']['title'])->toBe('Not Found');
});

it('rejects the wrong verb with a 405 and an allow header', function (string $method): void {
    $response = handle($method, '/health');

    expect($response->status())->toBe(Status::MethodNotAllowed)
        ->and($response->header('Allow'))->toBe('GET, HEAD');
})->with(['POST', 'PUT', 'PATCH', 'DELETE']);

it('rejects an unsupported http method before routing', function (): void {
    expect(fn (): mixed => handle('TRACE', '/health'))
        ->toThrow(Bulbula\Http\Exception\MalformedRequestException::class);
});

it('answers a head request to a get route', function (): void {
    expect(handle('HEAD', '/health')->status())->toBe(Status::Ok);
});
