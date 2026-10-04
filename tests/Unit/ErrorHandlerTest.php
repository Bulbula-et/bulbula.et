<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bulbula\Error\ErrorHandler;
use ErrorException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use RuntimeException;
use Stringable;

#[CoversClass(ErrorHandler::class)]
final class ErrorHandlerTest extends TestCase
{
    /**
     * @return iterable<string, array{bool, string, bool}>
     */
    public static function statusCodeCases(): iterable
    {
        yield 'web sapi, headers pending' => [false, 'fpm-fcgi', true];
        yield 'web sapi, headers already sent' => [true, 'fpm-fcgi', false];
        yield 'cli, headers pending' => [false, 'cli', false];
        yield 'cli, headers already sent' => [true, 'cli', false];
    }
    public function test_it_promotes_errors_to_exceptions(): void
    {
        $handler = new ErrorHandler($this->logger(), true);
        $previous = error_reporting(E_ALL);

        try {
            $handler->handleError(E_WARNING, 'boom', '/app/file.php', 42);
            self::fail('Expected an ErrorException to be thrown.');
        } catch (ErrorException $exception) {
            self::assertSame('boom', $exception->getMessage());
            self::assertSame(E_WARNING, $exception->getSeverity());
            self::assertSame('/app/file.php', $exception->getFile());
            self::assertSame(42, $exception->getLine());
            self::assertSame(0, $exception->getCode());
        } finally {
            error_reporting($previous);
        }
    }

    public function test_it_defaults_the_error_origin_to_an_unknown_location(): void
    {
        $handler = new ErrorHandler($this->logger(), true);
        $previous = error_reporting(E_ALL);

        try {
            $handler->handleError(E_NOTICE, 'no origin');
            self::fail('Expected an ErrorException to be thrown.');
        } catch (ErrorException $exception) {
            self::assertSame('', $exception->getFile());
            self::assertSame(0, $exception->getLine());
        } finally {
            error_reporting($previous);
        }
    }

    #[DataProvider('statusCodeCases')]
    public function test_it_only_sends_a_status_code_from_a_web_sapi(bool $headersSent, string $sapi, bool $expected): void
    {
        self::assertSame($expected, ErrorHandler::shouldSetStatusCode($headersSent, $sapi));
    }

    public function test_it_ignores_silenced_errors(): void
    {
        $handler = new ErrorHandler($this->logger(), true);
        $previous = error_reporting(0);

        try {
            self::assertFalse($handler->handleError(E_WARNING, 'silenced'));
        } finally {
            error_reporting($previous);
        }
    }

    public function test_report_logs_the_throwable_and_returns_its_reference(): void
    {
        $logger = $this->logger();

        $reference = new ErrorHandler($logger, false)->report(new RuntimeException('something broke'));

        self::assertCount(1, $logger->records);
        self::assertSame('error', $logger->records[0]['level']);
        self::assertSame('something broke', $logger->records[0]['message']);
        self::assertSame($reference, $logger->records[0]['context']['reference']);
        self::assertSame(RuntimeException::class, $logger->records[0]['context']['exception']);
    }

    public function test_it_renders_details_in_debug_mode(): void
    {
        $handler = new ErrorHandler($this->logger(), true);

        $output = $handler->render(new RuntimeException('database is down'), 'abc123');

        self::assertStringContainsString(RuntimeException::class, $output);
        self::assertStringContainsString('database is down', $output);
        self::assertStringContainsString(__FILE__, $output);
        self::assertStringContainsString('abc123', $output);
    }

    public function test_it_hides_details_outside_debug_mode(): void
    {
        $handler = new ErrorHandler($this->logger(), false);

        $output = $handler->render(new RuntimeException('database is down'), 'abc123');

        self::assertStringNotContainsString('database is down', $output);
        self::assertStringNotContainsString(RuntimeException::class, $output);
        self::assertStringContainsString('Something went wrong', $output);
        self::assertStringContainsString('abc123', $output);
    }

    public function test_it_logs_and_renders_uncaught_throwables(): void
    {
        $logger = $this->logger();
        $handler = new ErrorHandler($logger, false);

        $exception = new RuntimeException('exploded');
        $line = $exception->getLine();

        ob_start();
        $handler->handleThrowable($exception);
        $output = (string) ob_get_clean();

        self::assertCount(1, $logger->records);
        self::assertSame('error', $logger->records[0]['level']);
        self::assertSame('exploded', $logger->records[0]['message']);
        self::assertSame(RuntimeException::class, $logger->records[0]['context']['exception']);
        self::assertSame(__FILE__, $logger->records[0]['context']['file']);
        self::assertSame($line, $logger->records[0]['context']['line']);

        $reference = $logger->records[0]['context']['reference'];
        self::assertIsString($reference);
        self::assertStringContainsString($reference, $output);
    }

    public function test_it_sends_a_server_error_status_from_a_web_sapi(): void
    {
        $sent = [];
        $handler = new ErrorHandler(
            $this->logger(),
            false,
            static function (int $code) use (&$sent): void {
                $sent[] = $code;
            },
            'fpm-fcgi',
        );

        ob_start();
        $handler->handleThrowable(new RuntimeException('exploded'));
        ob_end_clean();

        self::assertSame([500], $sent);
    }

    public function test_it_sends_no_status_code_from_the_cli(): void
    {
        $sent = [];
        $handler = new ErrorHandler(
            $this->logger(),
            false,
            static function (int $code) use (&$sent): void {
                $sent[] = $code;
            },
            'cli',
        );

        ob_start();
        $handler->handleThrowable(new RuntimeException('exploded'));
        ob_end_clean();

        self::assertSame([], $sent);
    }

    public function test_it_falls_back_to_the_native_status_code_emitter(): void
    {
        $handler = new ErrorHandler($this->logger(), false, null, 'fpm-fcgi');

        ob_start();
        $handler->handleThrowable(new RuntimeException('exploded'));
        $output = (string) ob_get_clean();

        self::assertStringContainsString('Something went wrong', $output);
    }

    public function test_it_emits_an_explicit_status_code_through_the_injected_emitter(): void
    {
        $sent = [];
        $handler = new ErrorHandler(
            $this->logger(),
            false,
            static function (int $code) use (&$sent): void {
                $sent[] = $code;
            },
        );

        $handler->sendStatusCode(503);

        self::assertSame([503], $sent);
    }

    public function test_references_are_random_and_hexadecimal(): void
    {
        $handler = new ErrorHandler($this->logger(), false);

        $first = $handler->reference();
        $second = $handler->reference();

        self::assertMatchesRegularExpression('/^[0-9a-f]{12}$/', $first);
        self::assertNotSame($first, $second);
    }

    public function test_it_registers_itself_with_the_runtime(): void
    {
        $handler = new ErrorHandler($this->logger(), true);
        $handler->register();

        $registeredError = set_error_handler(null);
        $registeredException = set_exception_handler(null);

        restore_error_handler();
        restore_error_handler();
        restore_exception_handler();
        restore_exception_handler();

        self::assertIsCallable($registeredError);
        self::assertIsCallable($registeredException);
    }

    /**
     * @return AbstractLogger&object{records: list<array{level: string, message: string, context: array<string, mixed>}>}
     */
    private function logger(): AbstractLogger
    {
        return new class () extends AbstractLogger {
            /** @var list<array{level: string, message: string, context: array<string, mixed>}> */
            public array $records = [];

            /**
             * @param array<string, mixed> $context
             */
            public function log($level, string|Stringable $message, array $context = []): void
            {
                $this->records[] = [
                    'level' => (string) $level,
                    'message' => (string) $message,
                    'context' => $context,
                ];
            }
        };
    }
}
