<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Routing;

use Bulbula\Http\Exception\MethodNotAllowedException;
use Bulbula\Http\Exception\NotFoundException;
use Bulbula\Http\Method;
use Bulbula\Http\Middleware\Middleware;
use Bulbula\Http\Request;
use Bulbula\Http\Response;
use Bulbula\Http\Routing\Route;
use Bulbula\Http\Routing\Router;
use Bulbula\Http\Routing\UrlGenerator;
use Bulbula\Http\Status;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Router::class)]
#[CoversClass(Route::class)]
final class RouterTest extends TestCase
{
    /**
     * @return iterable<string, array{string, Method}>
     */
    public static function verbs(): iterable
    {
        yield 'get' => ['get', Method::Get];
        yield 'post' => ['post', Method::Post];
        yield 'put' => ['put', Method::Put];
        yield 'patch' => ['patch', Method::Patch];
        yield 'delete' => ['delete', Method::Delete];
    }
    #[DataProvider('verbs')]
    public function test_it_registers_each_verb(string $verb, Method $method): void
    {
        $router = new Router();
        $router->{$verb}('/businesses', self::handler('ok'));

        $match = $router->match(new Request($method, '/businesses'));

        self::assertSame('ok', ($match->route()->handler())(new Request($method, '/businesses'))->body());
        self::assertContains($method, $match->route()->methods());
    }

    public function test_a_get_route_also_answers_head_requests(): void
    {
        $router = new Router();
        $router->get('/health', self::handler('ok'));

        self::assertSame([Method::Get, Method::Head], $router->routes()[0]->methods());
        self::assertSame('/health', $router->match(new Request(Method::Head, '/health'))->route()->path());
    }

    public function test_it_exposes_route_parameters(): void
    {
        $router = new Router();
        $router->get('/businesses/{id:\d+}/reviews/{slug}', self::handler('ok'));

        $match = $router->match(new Request(Method::Get, '/businesses/42/reviews/great-coffee'));

        self::assertSame(['id' => '42', 'slug' => 'great-coffee'], $match->parameters());
    }

    public function test_a_route_without_parameters_matches_with_an_empty_parameter_list(): void
    {
        $router = new Router();
        $router->get('/health', self::handler('ok'));

        self::assertSame([], $router->match(new Request(Method::Get, '/health'))->parameters());
    }

    public function test_an_unknown_path_is_a_not_found(): void
    {
        $router = new Router();
        $router->get('/health', self::handler('ok'));

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('No route matches [/missing].');

        $router->match(new Request(Method::Get, '/missing'));
    }

    public function test_a_parameter_that_does_not_match_its_pattern_is_a_not_found(): void
    {
        $router = new Router();
        $router->get('/businesses/{id:\d+}', self::handler('ok'));

        $this->expectException(NotFoundException::class);

        $router->match(new Request(Method::Get, '/businesses/abc'));
    }

    public function test_a_known_path_with_the_wrong_verb_is_a_method_not_allowed(): void
    {
        $router = new Router();
        $router->get('/health', self::handler('ok'));
        $router->put('/health', self::handler('ok'));

        try {
            $router->match(new Request(Method::Delete, '/health'));
        } catch (MethodNotAllowedException $exception) {
            self::assertSame(Status::MethodNotAllowed, $exception->status());
            self::assertSame('Method [DELETE] is not allowed for [/health].', $exception->getMessage());
            self::assertSame(['Allow' => 'GET, HEAD, PUT'], $exception->headers());

            return;
        }

        self::fail('The wrong verb must be rejected.');
    }

    public function test_groups_apply_a_prefix_and_middleware_and_then_restore_the_previous_scope(): void
    {
        $router = new Router();
        $outer = $this->middleware();

        $router->group('/api/v1', static function (Router $router): void {
            $router->get('/health', self::handler('api'));
        }, [$outer]);

        $router->get('/health', self::handler('web'));

        $api = $router->match(new Request(Method::Get, '/api/v1/health'));
        $web = $router->match(new Request(Method::Get, '/health'));

        self::assertSame([$outer], $api->route()->middlewares());
        self::assertSame([], $web->route()->middlewares());
    }

    public function test_groups_can_be_nested_and_keep_every_middleware_in_order(): void
    {
        $router = new Router();
        $first = $this->middleware();
        $second = $this->middleware();
        $third = $this->middleware();
        $fourth = $this->middleware();

        $router->group('/api', function (Router $router) use ($third, $fourth): void {
            $router->group('/v1', function (Router $router): void {
                $router->get('/health', $this->handler('nested'));
            }, [$third, $fourth]);
        }, [$first, $second]);

        $match = $router->match(new Request(Method::Get, '/api/v1/health'));

        self::assertSame([$first, $second, $third, $fourth], $match->route()->middlewares());
    }

    public function test_routes_registered_after_a_match_are_still_matched(): void
    {
        $router = new Router();
        $router->get('/health', self::handler('ok'));
        $router->match(new Request(Method::Get, '/health'));

        $router->get('/later', self::handler('later'));

        self::assertSame('/later', $router->match(new Request(Method::Get, '/later'))->route()->path());
    }

    public function test_it_generates_urls_for_named_routes(): void
    {
        $router = new Router();
        $router->get('/businesses/{id:\d+}', self::handler('ok'))->name('businesses.show');

        self::assertInstanceOf(UrlGenerator::class, $router->urls());
        self::assertSame('/businesses/7', $router->url('businesses.show', ['id' => 7]));
    }

    public function test_a_route_can_collect_extra_middleware_after_the_group_middleware(): void
    {
        $router = new Router();
        $group = $this->middleware();
        $otherGroup = $this->middleware();
        $extra = $this->middleware();
        $otherExtra = $this->middleware();

        $router->group('/api', function (Router $router) use ($extra, $otherExtra): void {
            $router->get('/health', $this->handler('ok'))->middleware([$extra, $otherExtra]);
        }, [$group, $otherGroup]);

        self::assertSame(
            [$group, $otherGroup, $extra, $otherExtra],
            $router->routes()[0]->middlewares(),
        );
    }

    public function test_a_route_reports_its_name_only_when_it_has_one(): void
    {
        $router = new Router();
        $named = $router->get('/health', self::handler('ok'))->name('health');
        $anonymous = $router->get('/other', self::handler('ok'));

        self::assertSame('health', $named->nameOrNull());
        self::assertNull($anonymous->nameOrNull());
        self::assertCount(2, $router->routes());
    }

    public function test_arbitrary_method_lists_can_be_mapped(): void
    {
        $router = new Router();
        $router->map([Method::Options], '/health', self::handler('options'));

        self::assertSame([Method::Options], $router->match(new Request(Method::Options, '/health'))->route()->methods());
    }

    /**
     * @return Closure(Request): Response
     */
    private static function handler(string $body): Closure
    {
        return static fn (Request $request): Response => Response::text($body);
    }

    private function middleware(): Middleware
    {
        return new readonly class () implements Middleware {
            public function process(Request $request, Closure $next): Response
            {
                return $next($request);
            }
        };
    }
}
