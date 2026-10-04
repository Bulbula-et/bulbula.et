<?php

declare(strict_types=1);

namespace Tests\Unit\Http;

use Bulbula\Http\Response;
use Bulbula\Http\ResponseEmitter;
use Bulbula\Http\Status;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResponseEmitter::class)]
final class ResponseEmitterTest extends TestCase
{
    /** @var list<int> */
    private array $statuses = [];

    /** @var array<string, string> */
    private array $headers = [];

    protected function setUp(): void
    {
        $this->statuses = [];
        $this->headers = [];

        parent::setUp();
    }

    public function test_it_sends_the_status_the_headers_and_the_body(): void
    {
        $this->expectOutputString('{"status":"pass"}');

        $returned = $this->emitter()->emit(Response::json(['status' => 'pass']));

        self::assertSame([200], $this->statuses);
        self::assertSame(['Content-Type' => 'application/json; charset=utf-8'], $this->headers);
        self::assertSame('{"status":"pass"}', $returned);
    }

    public function test_it_emits_every_header(): void
    {
        $this->expectOutputString('');

        $this->emitter()->emit(new Response(Status::NoContent, '', ['X-One' => '1', 'X-Two' => '2']));

        self::assertSame([204], $this->statuses);
        self::assertSame(['X-One' => '1', 'X-Two' => '2'], $this->headers);
    }

    public function test_it_emits_an_error_status(): void
    {
        $this->expectOutputString('404 Not Found');

        $this->emitter()->emit(Response::text('404 Not Found', Status::NotFound));

        self::assertSame([404], $this->statuses);
    }

    private function emitter(): ResponseEmitter
    {
        return new ResponseEmitter(
            function (int $status): void {
                $this->statuses[] = $status;
            },
            function (string $name, string $value): void {
                $this->headers[$name] = $value;
            },
        );
    }
}
