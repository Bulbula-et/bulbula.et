<?php

declare(strict_types=1);

namespace Tests\Unit\Foundation;

use Bulbula\Database\Connection;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Foundation\Application;
use Bulbula\Foundation\HttpKernelFactory;
use Bulbula\Foundation\Services;
use Bulbula\Http\Kernel;
use Bulbula\Http\Method;
use Bulbula\Http\Request;
use Bulbula\Http\Status;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(HttpKernelFactory::class)]
final class HttpKernelFactoryTest extends TestCase
{
    public function test_it_wires_the_real_route_files(): void
    {
        $kernel = HttpKernelFactory::create($this->application(), $this->services());

        self::assertInstanceOf(Kernel::class, $kernel);
        self::assertSame(Status::Ok, $kernel->handle(new Request(Method::Get, '/health'))->status());
        self::assertSame(Status::Ok, $kernel->handle(new Request(Method::Get, '/api/v1/health'))->status());
    }

    public function test_it_applies_the_global_middleware(): void
    {
        $response = HttpKernelFactory::create($this->application(), $this->services())
            ->handle(new Request(Method::Get, '/health'));

        self::assertSame('nosniff', $response->header('X-Content-Type-Options'));
    }

    public function test_it_builds_its_own_services_when_none_are_given(): void
    {
        $response = HttpKernelFactory::create($this->application())->handle(new Request(Method::Get, '/health'));

        self::assertSame(Status::Ok, $response->status());
    }

    public function test_it_rejects_a_route_file_that_does_not_return_a_closure(): void
    {
        $base = __DIR__ . '/../../Fixtures/badroutes';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Route file [' . $base . '/routes/web.php] must return a closure.');

        HttpKernelFactory::create(
            Application::boot($base, registerErrorHandler: false),
            $this->services(),
        );
    }

    private function application(): Application
    {
        return Application::boot(dirname(__DIR__, 3), registerErrorHandler: false);
    }

    private function services(): Services
    {
        return Services::forApplication($this->application(), new Connection(DatabaseConfig::sqliteInMemory()));
    }
}
