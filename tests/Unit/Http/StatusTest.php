<?php

declare(strict_types=1);

namespace Tests\Unit\Http;

use Bulbula\Http\Status;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Status::class)]
final class StatusTest extends TestCase
{
    /**
     * @return iterable<string, array{Status, string, bool, bool, bool, bool}>
     */
    public static function statuses(): iterable
    {
        yield '200' => [Status::Ok, 'OK', true, false, false, false];
        yield '201' => [Status::Created, 'Created', true, false, false, false];
        yield '204' => [Status::NoContent, 'No Content', true, false, false, false];
        yield '301' => [Status::MovedPermanently, 'Moved Permanently', false, true, false, false];
        yield '302' => [Status::Found, 'Found', false, true, false, false];
        yield '303' => [Status::SeeOther, 'See Other', false, true, false, false];
        yield '400' => [Status::BadRequest, 'Bad Request', false, false, true, false];
        yield '401' => [Status::Unauthorized, 'Unauthorized', false, false, true, false];
        yield '403' => [Status::Forbidden, 'Forbidden', false, false, true, false];
        yield '404' => [Status::NotFound, 'Not Found', false, false, true, false];
        yield '405' => [Status::MethodNotAllowed, 'Method Not Allowed', false, false, true, false];
        yield '415' => [Status::UnsupportedMediaType, 'Unsupported Media Type', false, false, true, false];
        yield '422' => [Status::UnprocessableContent, 'Unprocessable Content', false, false, true, false];
        yield '429' => [Status::TooManyRequests, 'Too Many Requests', false, false, true, false];
        yield '500' => [Status::InternalServerError, 'Internal Server Error', false, false, false, true];
        yield '503' => [Status::ServiceUnavailable, 'Service Unavailable', false, false, false, true];
    }
    #[DataProvider('statuses')]
    public function test_every_status_describes_itself(
        Status $status,
        string $phrase,
        bool $successful,
        bool $redirect,
        bool $clientError,
        bool $serverError,
    ): void {
        self::assertSame($phrase, $status->reasonPhrase());
        self::assertSame($successful, $status->isSuccessful());
        self::assertSame($redirect, $status->isRedirect());
        self::assertSame($clientError, $status->isClientError());
        self::assertSame($serverError, $status->isServerError());
    }

    public function test_only_no_content_forbids_a_body(): void
    {
        self::assertFalse(Status::NoContent->allowsBody());

        foreach (Status::cases() as $status) {
            if ($status !== Status::NoContent) {
                self::assertTrue($status->allowsBody(), $status->name . ' may carry a body.');
            }
        }
    }

    public function test_the_provider_covers_every_case(): void
    {
        self::assertCount(count(Status::cases()), iterator_to_array(self::statuses()));
    }
}
