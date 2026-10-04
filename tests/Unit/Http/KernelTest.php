<?php

declare(strict_types=1);

namespace Tests\Unit\Http;

use Bulbula\Error\ErrorHandler;
use Bulbula\Http\HttpErrorHandler;
use Bulbula\Http\Kernel;
use Bulbula\Http\Method;
use Bulbula\Http\Middleware\Middleware;
use Bulbula\Http\Middleware\Pipeline;
use Bulbula\Http\Middleware\SecureHeaders;
use Bulbula\Http\Request;
use Bulbula\Http\Response;
use Bulbula\Http\Routing\Router;
use Bulbula\Http\Status;
use Closure;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(Kernel::class)]
final class KernelTest extends TestCase
{
    private TestHandler $log;

    protected function setUp(): void
    {
        $this->log = new TestHandler();

        parent::setUp();
    }

    public function test_it_dispatches_a_matching_route(): void
    {
        $router = new Router();
        $router->get('/health', static fn (): Response => Response::json(['status' => 'pass']));

        $response = $this->kernel($router)->handle(new Request(Method::Get, '/health'));

        self::assertSame(Status::Ok, $response->status());
        self::assertSame('{"status":"pass"}', $response->body());
    }

    public function test_it_passes_route_parameters_to_the_handler(): void
    {
        $router = new Router();
        $router->get(
            '/businesses/{id:\d+}',
            static fn (Request $request): Response => Response::text((string) $request->routeParameter('id')),
        );

        self::assertSame('42', $this->kernel($router)->handle(new Request(Method::Get, '/businesses/42'))->body());
    }

    public function test_global_middleware_wraps_every_response(): void
    {
        $router = new Router();
        $router->get('/health', static fn (): Response => Response::text('ok'));

        $response = $this->kernel($router)->handle(new Request(Method::Get, '/health'));

        self::assertSame('nosniff', $response->header('X-Content-Type-Options'));
    }

    public function test_global_middleware_also_wraps_error_responses(): void
    {
        $response = $this->kernel(new Router())->handle(new Request(Method::Get, '/missing'));

        self::assertSame(Status::NotFound, $response->status());
        self::assertSame('nosniff', $response->header('X-Content-Type-Options'));
    }

    public function test_route_middleware_only_wraps_its_own_route(): void
    {
        $router = new Router();
        $router->get('/guarded', static fn (): Response => Response::text('guarded'))
            ->middleware([$this->stamping()]);
        $router->get('/open', static fn (): Response => Response::text('open'));

        $guarded = $this->kernel($router)->handle(new Request(Method::Get, '/guarded'));
        $open = $this->kernel($router)->handle(new Request(Method::Get, '/open'));

        self::assertSame('yes', $guarded->header('X-Route-Middleware'));
        self::assertNull($open->header('X-Route-Middleware'));
    }

    public function test_an_unknown_path_becomes_a_404_response(): void
    {
        $response = $this->kernel(new Router())->handle(new Request(Method::Get, '/missing'));

        self::assertSame(Status::NotFound, $response->status());
        self::assertStringContainsString('404 Not Found', $response->body());
    }

    public function test_a_wrong_verb_becomes_a_405_response_with_an_allow_header(): void
    {
        $router = new Router();
        $router->get('/health', static fn (): Response => Response::text('ok'));

        $response = $this->kernel($router)->handle(new Request(Method::Post, '/health'));

        self::assertSame(Status::MethodNotAllowed, $response->status());
        self::assertSame('GET, HEAD', $response->header('Allow'));
    }

    public function test_a_failing_handler_becomes_a_500_response(): void
    {
        $router = new Router();
        $router->get('/boom', static function (): Response {
            throw new RuntimeException('handler exploded');
        });

        $response = $this->kernel($router)->handle(new Request(Method::Get, '/boom'));

        self::assertSame(Status::InternalServerError, $response->status());
        self::assertStringNotContainsString('handler exploded', $response->body());
        self::assertSame('nosniff', $response->header('X-Content-Type-Options'));
        self::assertTrue($this->log->hasErrorRecords());
    }

    public function test_a_failing_global_middleware_becomes_a_500_response(): void
    {
        $router = new Router();
        $router->get('/health', static fn (): Response => Response::text('ok'));

        $kernel = new Kernel(
            $router,
            new Pipeline([$this->failing()]),
            new HttpErrorHandler(new ErrorHandler(new Logger('tests', [$this->log]), false), false),
        );

        $response = $kernel->handle(new Request(Method::Get, '/health'));

        self::assertSame(Status::InternalServerError, $response->status());
        self::assertStringNotContainsString('middleware exploded', $response->body());
        self::assertTrue($this->log->hasErrorRecords());
    }

    private function kernel(Router $router): Kernel
    {
        return new Kernel(
            $router,
            new Pipeline([new SecureHeaders()]),
            new HttpErrorHandler(new ErrorHandler(new Logger('tests', [$this->log]), false), false),
        );
    }

    private function stamping(): Middleware
    {
        return new readonly class () implements Middleware {
            public function process(Request $request, Closure $next): Response
            {
                return $next($request)->withHeader('X-Route-Middleware', 'yes');
            }
        };
    }

    private function failing(): Middleware
    {
        return new readonly class () implements Middleware {
            public function process(Request $request, Closure $next): Response
            {
                throw new RuntimeException('middleware exploded');
            }
        };
    }
}
