<?php

declare(strict_types=1);

namespace Bulbula\Http\Controller;

use Bulbula\Diagnostics\HealthChecker;
use Bulbula\Diagnostics\HealthReport;
use Bulbula\Http\Response;
use Bulbula\Http\Status;

/**
 * Exposes the health service over HTTP.
 *
 * The controller only translates between HTTP and the service: no checks, no
 * SQL, no business rules.
 */
final readonly class HealthController
{
    public function __construct(private HealthChecker $health)
    {
    }

    /**
     * Liveness. Answers 200 as long as the process can serve a request.
     */
    public function live(): Response
    {
        return $this->respond($this->health->liveness());
    }

    /**
     * Readiness. Answers 503 when a dependency, such as the database, is down.
     */
    public function ready(): Response
    {
        return $this->respond($this->health->readiness());
    }

    private function respond(HealthReport $report): Response
    {
        return Response::json(
            $report->toArray(),
            $report->isHealthy() ? Status::Ok : Status::ServiceUnavailable,
            ['Cache-Control' => 'no-store'],
        );
    }
}
