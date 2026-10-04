<?php

declare(strict_types=1);

namespace Bulbula\Logging;

use Bulbula\Exception\ConfigurationException;
use Bulbula\Foundation\Environment;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Builds the PSR-3 logger used across the application.
 *
 * Production logs are JSON (machine readable, ready for shipping to a log
 * aggregator); every other environment gets human readable lines.
 */
final readonly class LoggerFactory
{
    /**
     * Permissions used when the log directory has to be created.
     */
    public const int DIRECTORY_MODE = 0o775;

    /**
     * @throws ConfigurationException when the level is unknown or the log directory is unusable
     */
    public static function create(
        string $channel,
        string $path,
        string $level,
        Environment $environment,
    ): LoggerInterface {
        $handler = new StreamHandler(self::prepare($path), self::level($level));

        $handler->setFormatter(
            $environment->isProduction()
                ? new JsonFormatter()
                : new LineFormatter(null, null, true, true),
        );

        return new Logger($channel, [$handler]);
    }

    /**
     * Resolve a PSR-3 level name (case-insensitive) to a Monolog level.
     *
     * @throws ConfigurationException when the level does not exist
     */
    public static function level(string $level): Level
    {
        foreach (Level::cases() as $case) {
            if (strcasecmp($case->getName(), $level) === 0) {
                return $case;
            }
        }

        throw ConfigurationException::invalidType('logging.level', 'valid PSR-3 level', $level);
    }

    /**
     * Ensure the directory of the log file exists and is writable.
     *
     * @throws ConfigurationException when the directory cannot be created
     */
    private static function prepare(string $path): string
    {
        $directory = dirname($path);

        // The failure is reported through an exception, so the native warning
        // of mkdir() is suppressed on purpose.
        if (! is_dir($directory) && ! @mkdir($directory, self::DIRECTORY_MODE, true)) {
            throw ConfigurationException::uncreatableLogDirectory($directory);
        }

        if (! is_writable($directory)) {
            throw ConfigurationException::unwritableLogDirectory($directory);
        }

        return $path;
    }
}
