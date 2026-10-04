<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controller;

use Bulbula\Database\Connection;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Diagnostics\HealthChecker;
use Bulbula\Http\Controller\HealthController;
use Bulbula\Http\Status;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HealthController::class)]
final class HealthControllerTest extends TestCase
{
    public function test_liveness_answers_200_even_without_a_database(): void
    {
        $response = $this->controller(connection: null)->live();

        self::assertSame(Status::Ok, $response->status());
        self::assertSame('application/json; charset=utf-8', $response->header('Content-Type'));
        self::assertSame('no-store', $response->header('Cache-Control'));
        self::assertSame(
            [
                'status' => 'pass',
                'application' => 'Bulbula',
                'environment' => 'testing',
                'time' => '2026-10-04T08:30:00+00:00',
                'checks' => ['application' => ['status' => 'pass']],
            ],
            $this->decode($response->body()),
        );
    }

    public function test_readiness_answers_503_when_a_dependency_is_down(): void
    {
        $response = $this->controller(connection: null)->ready();

        self::assertSame(Status::ServiceUnavailable, $response->status());
        self::assertSame('fail', $this->decode($response->body())['status'] ?? null);
    }

    public function test_readiness_answers_200_when_every_dependency_is_up(): void
    {
        $connection = new Connection(DatabaseConfig::sqliteInMemory());
        $connection->statement('CREATE TABLE migrations (migration VARCHAR(255) PRIMARY KEY)');

        $response = $this->controller($connection)->ready();

        self::assertSame(Status::Ok, $response->status());
        self::assertSame('pass', $this->decode($response->body())['status'] ?? null);
    }

    private function controller(?Connection $connection): HealthController
    {
        return new HealthController(new HealthChecker(
            'Bulbula',
            'testing',
            static fn (): DateTimeImmutable => new DateTimeImmutable('2026-10-04 08:30:00', new DateTimeZone('UTC')),
            $connection,
        ));
    }

    /**
     * @return array<string, mixed>
     */
    private function decode(string $body): array
    {
        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($body, true, 32, JSON_THROW_ON_ERROR);

        return $decoded;
    }
}
