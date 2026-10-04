<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Routing;

use Bulbula\Http\Exception\RouteNotDefinedException;
use Bulbula\Http\Request;
use Bulbula\Http\Response;
use Bulbula\Http\Routing\Router;
use Bulbula\Http\Routing\UrlGenerator;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UrlGenerator::class)]
final class UrlGeneratorTest extends TestCase
{
    public function test_it_builds_a_static_url(): void
    {
        self::assertSame('/health', $this->router(['health' => '/health'])->url('health'));
    }

    public function test_it_substitutes_parameters(): void
    {
        $url = $this->router(['businesses.show' => '/businesses/{id:\d+}'])->url('businesses.show', ['id' => '42']);

        self::assertSame('/businesses/42', $url);
    }

    public function test_it_accepts_integer_parameters(): void
    {
        self::assertSame(
            '/businesses/42',
            $this->router(['businesses.show' => '/businesses/{id:\d+}'])->url('businesses.show', ['id' => 42]),
        );
    }

    public function test_it_keeps_every_segment_around_a_parameter(): void
    {
        $url = $this->router(['reviews.index' => '/businesses/{id:\d+}/reviews'])
            ->url('reviews.index', ['id' => '42']);

        self::assertSame('/businesses/42/reviews', $url);
    }

    public function test_it_keeps_every_segment_of_a_multi_parameter_route(): void
    {
        $url = $this->router(['reviews.show' => '/businesses/{id:\d+}/reviews/{slug}/detail'])
            ->url('reviews.show', ['id' => '42', 'slug' => 'great-coffee']);

        self::assertSame('/businesses/42/reviews/great-coffee/detail', $url);
    }

    public function test_it_encodes_parameter_values(): void
    {
        self::assertSame(
            '/search/bole%20bulbula',
            $this->router(['search' => '/search/{term}'])->url('search', ['term' => 'bole bulbula']),
        );
    }

    public function test_it_builds_the_shortest_variant_of_an_optional_segment(): void
    {
        $router = $this->router(['listings' => '/listings[/{page:\d+}]']);

        self::assertSame('/listings', $router->url('listings'));
        self::assertSame('/listings/3', $router->url('listings', ['page' => '3']));
    }

    public function test_it_rejects_an_unknown_name(): void
    {
        $this->expectException(RouteNotDefinedException::class);
        $this->expectExceptionMessage('No route is registered under the name [missing].');

        $this->router(['health' => '/health'])->url('missing');
    }

    public function test_it_rejects_a_missing_parameter(): void
    {
        $this->expectException(RouteNotDefinedException::class);
        $this->expectExceptionMessage('Route [businesses.show] cannot be built with the given parameters.');

        $this->router(['businesses.show' => '/businesses/{id:\d+}'])->url('businesses.show');
    }

    public function test_it_rejects_a_parameter_that_does_not_match_the_pattern(): void
    {
        $this->expectException(RouteNotDefinedException::class);

        $this->router(['businesses.show' => '/businesses/{id:\d+}'])->url('businesses.show', ['id' => 'abc']);
    }

    public function test_it_rejects_unknown_extra_parameters(): void
    {
        $this->expectException(RouteNotDefinedException::class);

        $this->router(['health' => '/health'])->url('health', ['id' => '1']);
    }

    public function test_it_rejects_two_routes_sharing_a_name(): void
    {
        $router = new Router();
        $router->get('/one', $this->handler())->name('duplicate');
        $router->get('/two', $this->handler())->name('duplicate');

        $this->expectException(RouteNotDefinedException::class);
        $this->expectExceptionMessage('Route name [duplicate] is already registered.');

        $router->urls();
    }

    public function test_unnamed_routes_are_not_registered(): void
    {
        $router = new Router();
        $router->get('/anonymous', $this->handler());
        $router->get('/named', $this->handler())->name('named');

        self::assertSame('/named', $router->url('named'));
    }

    /**
     * @param array<string, string> $routes name => path
     */
    private function router(array $routes): Router
    {
        $router = new Router();

        foreach ($routes as $name => $path) {
            $router->get($path, $this->handler())->name($name);
        }

        return $router;
    }

    /**
     * @return Closure(Request): Response
     */
    private function handler(): Closure
    {
        return static fn (Request $request): Response => new Response();
    }
}
