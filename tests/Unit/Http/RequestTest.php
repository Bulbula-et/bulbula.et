<?php

declare(strict_types=1);

namespace Tests\Unit\Http;

use Bulbula\Http\Exception\MalformedRequestException;
use Bulbula\Http\Method;
use Bulbula\Http\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Request::class)]
final class RequestTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string}>
     */
    public static function paths(): iterable
    {
        yield 'plain' => ['/health', '/health'];
        yield 'with query' => ['/health?ready=1', '/health'];
        yield 'encoded' => ['/caf%C3%A9', '/café'];
        yield 'empty' => ['', '/'];
        yield 'query only' => ['?a=b', '/'];
    }

    /**
     * @return iterable<string, array{string, bool}>
     */
    public static function secureServers(): iterable
    {
        yield 'on' => ['on', true];
        yield 'uppercase' => ['ON', true];
        yield 'one' => ['1', true];
        yield 'off' => ['off', false];
        yield 'mixed case off' => ['Off', false];
        yield 'empty' => ['', false];
    }

    /**
     * @return iterable<string, array{Request, bool}>
     */
    public static function jsonExpectations(): iterable
    {
        yield 'api path' => [new Request(Method::Get, '/api/v1/health'), true];
        yield 'json content type' => [
            new Request(Method::Post, '/contact', [], [], ['content-type' => 'application/json']),
            true,
        ];
        yield 'json accept header' => [
            new Request(Method::Get, '/contact', [], [], ['accept' => 'text/html, application/json;q=0.9']),
            true,
        ];
        yield 'browser request' => [
            new Request(Method::Get, '/contact', [], [], ['accept' => 'text/html']),
            false,
        ];
        yield 'no hints' => [new Request(Method::Get, '/contact'), false];
        yield 'api in the middle of the path' => [new Request(Method::Get, '/docs/api/v1'), false];
    }
    public function test_it_reads_the_method_path_query_and_headers_from_the_server_array(): void
    {
        $request = Request::fromGlobals(
            [
                'REQUEST_METHOD' => 'POST',
                'REQUEST_URI' => '/api/v1/listings?page=2',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_X_REQUEST_ID' => 'abc-123',
                'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
                'CONTENT_LENGTH' => '12',
                'REMOTE_ADDR' => '127.0.0.1',
            ],
            ['page' => '2'],
            ['name' => 'Bulbula'],
            'name=Bulbula',
        );

        self::assertSame(Method::Post, $request->method());
        self::assertSame('/api/v1/listings', $request->path());
        self::assertSame('2', $request->query('page'));
        self::assertSame(['page' => '2'], $request->queryParameters());
        self::assertSame('application/json', $request->header('Accept'));
        self::assertSame('abc-123', $request->header('x-request-id'));
        self::assertSame('12', $request->header('content-length'));
        self::assertSame('name=Bulbula', $request->body());
        self::assertSame(['name' => 'Bulbula'], $request->parsedBody());
    }

    public function test_it_ignores_server_entries_that_are_not_headers(): void
    {
        $request = Request::fromGlobals(
            ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/', 'REMOTE_ADDR' => '10.0.0.1'],
            [],
            [],
            '',
        );

        self::assertSame([], $request->headers());
    }

    public function test_an_array_valued_server_entry_does_not_stop_header_parsing(): void
    {
        $request = Request::fromGlobals(
            ['argv' => ['script.php'], 'HTTP_ACCEPT' => 'text/html', 'CONTENT_TYPE' => 'text/plain'],
            [],
            [],
            '',
        );

        self::assertSame(['accept' => 'text/html', 'content-type' => 'text/plain'], $request->headers());
    }

    public function test_numeric_header_values_are_exposed_as_strings(): void
    {
        $request = Request::fromGlobals(
            ['HTTP_X_RETRY_COUNT' => 3, 'CONTENT_LENGTH' => 128],
            [],
            [],
            '',
        );

        self::assertSame('3', $request->header('x-retry-count'));
        self::assertSame('128', $request->header('content-length'));
    }

    public function test_it_defaults_to_a_get_request_for_the_root_path(): void
    {
        $request = Request::fromGlobals([], [], [], '');

        self::assertSame(Method::Get, $request->method());
        self::assertSame('/', $request->path());
        self::assertFalse($request->isSecure());
    }

    #[DataProvider('paths')]
    public function test_it_normalises_the_path(string $uri, string $expected): void
    {
        self::assertSame($expected, Request::fromGlobals(['REQUEST_URI' => $uri], [], [], '')->path());
    }

    #[DataProvider('secureServers')]
    public function test_it_detects_a_secure_connection(string $https, bool $expected): void
    {
        self::assertSame($expected, Request::fromGlobals(['HTTPS' => $https], [], [], '')->isSecure());
    }

    public function test_it_parses_a_json_body(): void
    {
        $request = Request::fromGlobals(
            ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/api/v1/x', 'CONTENT_TYPE' => 'application/json; charset=utf-8'],
            [],
            [],
            '{"name":"Bulbula","tags":["one"],"count":3}',
        );

        self::assertSame('Bulbula', $request->input('name'));
        self::assertSame(['one'], $request->input('tags'));
        self::assertSame(3, $request->input('count'));
        self::assertNull($request->input('missing'));
        self::assertSame('fallback', $request->input('missing', 'fallback'));
    }

    public function test_it_keeps_the_form_body_when_the_content_type_is_not_json(): void
    {
        $request = Request::fromGlobals(
            ['CONTENT_TYPE' => 'application/x-www-form-urlencoded'],
            [],
            ['name' => 'form'],
            '{"name":"json"}',
        );

        self::assertSame('form', $request->input('name'));
    }

    public function test_it_keeps_the_form_body_when_a_json_request_has_no_body(): void
    {
        $request = Request::fromGlobals(['CONTENT_TYPE' => 'application/json'], [], ['name' => 'form'], '');

        self::assertSame('form', $request->input('name'));
    }

    public function test_it_rejects_a_malformed_json_body(): void
    {
        $this->expectException(MalformedRequestException::class);
        $this->expectExceptionMessage('Request body is not valid JSON.');

        Request::fromGlobals(['CONTENT_TYPE' => 'application/json'], [], [], '{"name":');
    }

    public function test_it_rejects_a_json_body_that_is_not_an_object_or_array(): void
    {
        $this->expectException(MalformedRequestException::class);

        Request::fromGlobals(['CONTENT_TYPE' => 'application/json'], [], [], '"a string"');
    }

    public function test_it_normalises_json_and_superglobal_keys_to_strings(): void
    {
        $json = Request::fromGlobals(['CONTENT_TYPE' => 'application/json'], [], [], '["first","second"]');
        $form = Request::fromGlobals([], [7 => 'seven'], [4 => 'four'], '');

        self::assertSame('first', $json->input('0'));
        self::assertSame('seven', $form->query('7'));
        self::assertSame('four', $form->input('4'));
    }

    public function test_query_parameters_are_strings_and_array_values_are_ignored(): void
    {
        $request = Request::fromGlobals([], ['page' => 3, 'tags' => ['a'], 'ok' => true], [], '');

        self::assertSame(['page' => '3', 'ok' => '1'], $request->queryParameters());
        self::assertSame('3', $request->query('page'));
        self::assertSame('1', $request->query('ok'));
        self::assertNull($request->query('tags'));
        self::assertSame('none', $request->query('tags', 'none'));
    }

    public function test_it_accepts_a_json_body_nested_up_to_the_supported_depth(): void
    {
        $request = Request::fromGlobals(
            ['CONTENT_TYPE' => 'application/json'],
            [],
            [],
            $this->nestedJson(31),
        );

        self::assertIsArray($request->input('0'));
    }

    public function test_it_rejects_a_json_body_nested_beyond_the_supported_depth(): void
    {
        $this->expectException(MalformedRequestException::class);
        $this->expectExceptionMessage('Request body is not valid JSON.');

        Request::fromGlobals(['CONTENT_TYPE' => 'application/json'], [], [], $this->nestedJson(32));
    }

    public function test_it_rejects_an_unsupported_method(): void
    {
        $this->expectException(MalformedRequestException::class);

        Request::fromGlobals(['REQUEST_METHOD' => 'TRACE'], [], [], '');
    }

    public function test_route_parameters_are_added_without_mutating_the_original(): void
    {
        $request = new Request(Method::Get, '/businesses/42');

        $matched = $request->withRouteParameters(['id' => '42']);

        self::assertSame([], $request->routeParameters());
        self::assertNull($request->routeParameter('id'));
        self::assertSame(['id' => '42'], $matched->routeParameters());
        self::assertSame('42', $matched->routeParameter('id'));
        self::assertSame('none', $matched->routeParameter('slug', 'none'));
        self::assertSame('/businesses/42', $matched->path());
        self::assertSame(Method::Get, $matched->method());
    }

    public function test_it_preserves_every_property_when_route_parameters_are_added(): void
    {
        $request = new Request(
            Method::Put,
            '/api/v1/x',
            ['q' => '1'],
            ['body' => 'value'],
            ['accept' => 'application/json'],
            'raw',
            [],
            true,
        );

        $matched = $request->withRouteParameters(['id' => '9']);

        self::assertSame(Method::Put, $matched->method());
        self::assertSame(['q' => '1'], $matched->queryParameters());
        self::assertSame(['body' => 'value'], $matched->parsedBody());
        self::assertSame(['accept' => 'application/json'], $matched->headers());
        self::assertSame('raw', $matched->body());
        self::assertTrue($matched->isSecure());
    }

    public function test_a_request_is_insecure_unless_stated_otherwise(): void
    {
        self::assertFalse(new Request(Method::Get, '/')->isSecure());
    }

    public function test_a_missing_header_falls_back_to_the_default(): void
    {
        $request = new Request(Method::Get, '/');

        self::assertNull($request->header('accept'));
        self::assertSame('*/*', $request->header('Accept', '*/*'));
    }

    #[DataProvider('jsonExpectations')]
    public function test_it_knows_when_the_client_expects_json(Request $request, bool $expected): void
    {
        self::assertSame($expected, $request->expectsJson());
    }

    private function nestedJson(int $levels): string
    {
        $json = '1';

        for ($level = 0; $level < $levels; ++$level) {
            $json = '[' . $json . ']';
        }

        return $json;
    }
}
