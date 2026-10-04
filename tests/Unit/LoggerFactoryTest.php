<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bulbula\Exception\ConfigurationException;
use Bulbula\Foundation\Environment;
use Bulbula\Logging\LoggerFactory;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;
use PHPUnit\Framework\TestCase;

#[CoversClass(LoggerFactory::class)]
#[CoversClass(ConfigurationException::class)]
final class LoggerFactoryTest extends TestCase
{
    /**
     * @return iterable<string, array{Environment}>
     */
    public static function humanReadableEnvironments(): iterable
    {
        yield 'local' => [Environment::Local];
        yield 'testing' => [Environment::Testing];
    }
    public function test_it_builds_a_named_logger(): void
    {
        $logger = LoggerFactory::create('bulbula', $this->logPath(), 'debug', Environment::Local);

        self::assertInstanceOf(Logger::class, $logger);
        self::assertSame('bulbula', $logger->getName());
    }

    public function test_it_uses_a_single_stream_handler_at_the_configured_level(): void
    {
        $path = $this->logPath();
        $logger = LoggerFactory::create('bulbula', $path, 'warning', Environment::Local);

        self::assertInstanceOf(Logger::class, $logger);
        $handlers = $logger->getHandlers();
        self::assertCount(1, $handlers);

        $handler = $handlers[0];
        self::assertInstanceOf(StreamHandler::class, $handler);
        self::assertSame(Level::Warning, $handler->getLevel());
        self::assertSame($path, $handler->getUrl());
    }

    public function test_it_formats_production_logs_as_json(): void
    {
        $logger = LoggerFactory::create('bulbula', $this->logPath(), 'info', Environment::Production);

        self::assertInstanceOf(Logger::class, $logger);
        $handler = $logger->getHandlers()[0];
        self::assertInstanceOf(StreamHandler::class, $handler);
        self::assertInstanceOf(JsonFormatter::class, $handler->getFormatter());
    }

    #[DataProvider('humanReadableEnvironments')]
    public function test_it_formats_non_production_logs_as_lines(Environment $environment): void
    {
        $logger = LoggerFactory::create('bulbula', $this->logPath(), 'info', $environment);

        self::assertInstanceOf(Logger::class, $logger);
        $handler = $logger->getHandlers()[0];
        self::assertInstanceOf(StreamHandler::class, $handler);
        self::assertInstanceOf(LineFormatter::class, $handler->getFormatter());
    }

    public function test_it_writes_to_the_log_file(): void
    {
        $path = $this->logPath();

        LoggerFactory::create('bulbula', $path, 'debug', Environment::Local)
            ->info('baseline ready', ['version' => 1]);

        self::assertFileExists($path);
        $contents = (string) file_get_contents($path);
        self::assertStringContainsString('baseline ready', $contents);
        self::assertStringContainsString('bulbula', $contents);
    }

    public function test_it_creates_a_missing_log_directory(): void
    {
        $path = sys_get_temp_dir() . '/bulbula-logs-' . bin2hex(random_bytes(6)) . '/nested/app.log';

        LoggerFactory::create('bulbula', $path, 'debug', Environment::Local)->info('created');

        self::assertDirectoryExists(dirname($path));
    }

    public function test_it_keeps_multi_line_messages_readable_and_omits_empty_context(): void
    {
        $path = $this->logPath();

        LoggerFactory::create('bulbula', $path, 'debug', Environment::Local)
            ->info("first line\nsecond line");

        $contents = (string) file_get_contents($path);

        // allowInlineLineBreaks: the newline stays a real newline instead of being escaped.
        self::assertStringContainsString("first line\nsecond line", $contents);
        self::assertStringNotContainsString('first line\\nsecond line', $contents);

        // ignoreEmptyContextAndExtra: no trailing empty brackets are rendered.
        self::assertStringNotContainsString('[] []', $contents);
    }

    public function test_it_renders_context_when_present(): void
    {
        $path = $this->logPath();

        LoggerFactory::create('bulbula', $path, 'debug', Environment::Local)
            ->info('with context', ['key' => 'value']);

        self::assertStringContainsString('{"key":"value"}', (string) file_get_contents($path));
    }

    public function test_it_creates_the_log_directory_with_group_writable_permissions(): void
    {
        $path = sys_get_temp_dir() . '/bulbula-perms-' . bin2hex(random_bytes(6)) . '/app.log';
        $umask = umask(0);

        try {
            LoggerFactory::create('bulbula', $path, 'debug', Environment::Local);

            self::assertSame(
                LoggerFactory::DIRECTORY_MODE,
                fileperms(dirname($path)) & 0o777,
            );
        } finally {
            umask($umask);
        }
    }

    public function test_it_reuses_an_existing_log_directory(): void
    {
        $directory = sys_get_temp_dir() . '/bulbula-existing-' . bin2hex(random_bytes(6));
        mkdir($directory, 0o775, true);

        LoggerFactory::create('bulbula', $directory . '/app.log', 'debug', Environment::Local)
            ->info('reused');

        self::assertFileExists($directory . '/app.log');
    }

    public function test_it_rejects_an_unknown_level(): void
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('valid PSR-3 level');

        LoggerFactory::create('bulbula', $this->logPath(), 'shout', Environment::Local);
    }

    public function test_it_resolves_levels_by_name(): void
    {
        self::assertSame(Level::Error, LoggerFactory::level('error'));
        self::assertSame(Level::Debug, LoggerFactory::level('debug'));
        self::assertSame(Level::Warning, LoggerFactory::level('WARNING'));
        self::assertSame(Level::Critical, LoggerFactory::level('Critical'));
    }

    #[WithoutErrorHandler]
    public function test_it_rejects_a_log_directory_that_cannot_be_created(): void
    {
        $parent = sys_get_temp_dir() . '/bulbula-locked-' . bin2hex(random_bytes(6));
        mkdir($parent, 0o500, true);

        try {
            $this->expectException(ConfigurationException::class);
            $this->expectExceptionMessage('could not be created.');

            LoggerFactory::create('bulbula', $parent . '/nested/app.log', 'debug', Environment::Local);
        } finally {
            chmod($parent, 0o700);
        }
    }

    public function test_it_rejects_an_unwritable_log_directory(): void
    {
        $directory = sys_get_temp_dir() . '/bulbula-readonly-' . bin2hex(random_bytes(6));
        mkdir($directory, 0o500, true);

        try {
            $this->expectException(ConfigurationException::class);
            $this->expectExceptionMessage('is not writable.');

            LoggerFactory::create('bulbula', $directory . '/app.log', 'debug', Environment::Local);
        } finally {
            chmod($directory, 0o700);
        }
    }

    private function logPath(): string
    {
        return sys_get_temp_dir() . '/bulbula-logs-' . bin2hex(random_bytes(6)) . '/app.log';
    }
}
