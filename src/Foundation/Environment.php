<?php

declare(strict_types=1);

namespace Bulbula\Foundation;

use Bulbula\Exception\ConfigurationException;

/**
 * The runtime environments supported by the application.
 */
enum Environment: string
{
    case Local = 'local';

    case Testing = 'testing';

    case Production = 'production';

    /**
     * Resolve an environment from its (case-insensitive) name.
     *
     * @throws ConfigurationException when the name maps to no known environment
     */
    public static function fromName(string $name): self
    {
        $environment = self::tryFrom(strtolower(trim($name)));

        if (! $environment instanceof self) {
            throw ConfigurationException::unknownEnvironment($name);
        }

        return $environment;
    }

    public function isProduction(): bool
    {
        return $this === self::Production;
    }

    public function isLocal(): bool
    {
        return $this === self::Local;
    }

    public function isTesting(): bool
    {
        return $this === self::Testing;
    }

    /**
     * Whether verbose error output may be exposed in this environment.
     */
    public function allowsDebugOutput(): bool
    {
        return $this !== self::Production;
    }
}
