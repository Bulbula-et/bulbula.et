<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use Bulbula\Http\Method;
use Bulbula\Http\Middleware\Middleware;
use Bulbula\Http\Middleware\Pipeline;
use Bulbula\Http\Request;
use Bulbula\Http\Response;
use Bulbula\Http\Status;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(Pipeline::class)]
final class PipelineTest extends TestCase
{
    /** @var list<string> */
    private array $calls = [];

    protected function setUp(): void
    {
        $this->calls = [];

        parent::setUp();
    }

    public function test_an_empty_pipeline_runs_the_destination(): void
    {
        $response = new Pipeline()->run($this->request(), static fn (): Response => Response::text('handled'));

        self::assertSame('handled', $response->body());
    }

    public function test_middleware_runs_in_registration_order_around_the_handler(): void
    {
        $pipeline = new Pipeline([$this->recording('first'), $this->recording('second')]);

        $response = $pipeline->run($this->request(), function (): Response {
            $this->calls[] = 'handler';

            return Response::text('handled');
        });

        self::assertSame(
            ['first:before', 'second:before', 'handler', 'second:after', 'first:after'],
            $this->calls,
        );
        self::assertSame('handled', $response->body());
    }

    public function test_middleware_can_change_the_request_for_the_rest_of_the_pipeline(): void
    {
        $pipeline = new Pipeline([
            new readonly class () implements Middleware {
                public function process(Request $request, Closure $next): Response
                {
                    return $next($request->withRouteParameters(['id' => '7']));
                }
            },
        ]);

        $response = $pipeline->run(
            $this->request(),
            static fn (Request $request): Response => Response::text((string) $request->routeParameter('id')),
        );

        self::assertSame('7', $response->body());
    }

    public function test_middleware_can_change_the_response_on_the_way_out(): void
    {
        $pipeline = new Pipeline([
            new readonly class () implements Middleware {
                public function process(Request $request, Closure $next): Response
                {
                    return $next($request)->withHeader('X-Stamped', 'yes');
                }
            },
        ]);

        $response = $pipeline->run($this->request(), static fn (): Response => Response::text('handled'));

        self::assertSame('yes', $response->header('X-Stamped'));
    }

    public function test_middleware_can_short_circuit_the_pipeline(): void
    {
        $pipeline = new Pipeline([
            $this->recording('outer'),
            new readonly class () implements Middleware {
                public function process(Request $request, Closure $next): Response
                {
                    return Response::text('blocked', Status::Forbidden);
                }
            },
            $this->recording('never'),
        ]);

        $response = $pipeline->run($this->request(), function (): Response {
            $this->calls[] = 'handler';

            return Response::text('handled');
        });

        self::assertSame(Status::Forbidden, $response->status());
        self::assertSame('blocked', $response->body());
        self::assertSame(['outer:before', 'outer:after'], $this->calls);
    }

    public function test_an_exception_from_the_handler_bubbles_out(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('boom');

        new Pipeline([$this->recording('outer')])->run($this->request(), static function (): Response {
            throw new RuntimeException('boom');
        });
    }

    public function test_appending_returns_a_new_pipeline_with_the_extra_middleware_last(): void
    {
        $base = new Pipeline([$this->recording('first'), $this->recording('second')]);

        $extended = $base->append([$this->recording('third'), $this->recording('fourth')]);

        $extended->run($this->request(), static fn (): Response => new Response());
        self::assertSame(
            [
                'first:before', 'second:before', 'third:before', 'fourth:before',
                'fourth:after', 'third:after', 'second:after', 'first:after',
            ],
            $this->calls,
        );

        $this->calls = [];
        $base->run($this->request(), static fn (): Response => new Response());
        self::assertSame(['first:before', 'second:before', 'second:after', 'first:after'], $this->calls);
    }

    private function recording(string $label): Middleware
    {
        $record = function (string $call): void {
            $this->calls[] = $call;
        };

        return new readonly class ($label, $record) implements Middleware {
            /**
             * @param Closure(string): void $record
             */
            public function __construct(private string $label, private Closure $record)
            {
            }

            public function process(Request $request, Closure $next): Response
            {
                ($this->record)($this->label . ':before');

                $response = $next($request);

                ($this->record)($this->label . ':after');

                return $response;
            }
        };
    }

    private function request(): Request
    {
        return new Request(Method::Get, '/health');
    }
}
