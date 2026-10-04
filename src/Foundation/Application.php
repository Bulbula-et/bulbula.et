<?php

declare(strict_types=1);

namespace Bulbula\Foundation;

use Bulbula\Config\Config;
use Bulbula\Error\ErrorHandler;
use Bulbula\Exception\ConfigurationException;
use Bulbula\Logging\LoggerFactory;
use Dotenv\Dotenv;
use Psr\Log\LoggerInterface;

/**
 * Minimal, framework-free composition root.
 *
 * It wires the three things every environment needs - configuration, logging
 * and error handling - and nothing else. Business features are deliberately
 * out of scope at this stage of the project.
 */
final readonly class Application
{
    private function __construct(
        private string $basePath,
        private Environment $environment,
        private Config $config,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * Boot the application from a project root directory.
     *
     * @throws ConfigurationException when configuration or logging is invalid
     */
    public static function boot(string $basePath, bool $registerErrorHandler = true): self
    {
        $basePath = rtrim($basePath, '/');

        if (is_file($basePath . '/.env')) {
            Dotenv::createImmutable($basePath)->load();
        }

        $config = Config::fromDirectory($basePath . '/config');
        $environment = Environment::fromName($config->string('app.env'));

        date_default_timezone_set($config->string('app.timezone'));

        $logger = LoggerFactory::create(
            $config->string('logging.channel'),
            self::absolute($basePath, $config->string('logging.path')),
            $config->string('logging.level'),
            $environment,
        );

        $application = new self($basePath, $environment, $config, $logger);

        if ($registerErrorHandler) {
            new ErrorHandler($logger, $application->isDebug())->register();
        }

        return $application;
    }

    public function basePath(): string
    {
        return $this->basePath;
    }

    public function path(string $relative = ''): string
    {
        return $relative === '' ? $this->basePath : $this->basePath . '/' . ltrim($relative, '/');
    }

    public function environment(): Environment
    {
        return $this->environment;
    }

    public function config(): Config
    {
        return $this->config;
    }

    public function logger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Debug output is opt-in through configuration and never allowed in production.
     */
    public function isDebug(): bool
    {
        return $this->config->bool('app.debug') && $this->environment->allowsDebugOutput();
    }

    private static function absolute(string $basePath, string $path): string
    {
        return str_starts_with($path, '/') ? $path : $basePath . '/' . $path;
    }
}
