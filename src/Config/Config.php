<?php

declare(strict_types=1);

namespace Bulbula\Config;

use Bulbula\Exception\ConfigurationException;

use function array_key_exists;
use function gettype;
use function is_array;
use function is_bool;
use function is_int;
use function is_string;

/**
 * Immutable configuration repository with dot-notation access.
 */
final readonly class Config
{
    /**
     * @param array<string, mixed> $items
     */
    private function __construct(private array $items)
    {
    }

    /**
     * @param array<string, mixed> $items
     */
    public static function fromArray(array $items): self
    {
        return new self($items);
    }

    /**
     * Load every `*.php` file of a directory, keyed by file name.
     *
     * @throws ConfigurationException when the directory is missing or a file does not return an array
     */
    public static function fromDirectory(string $directory): self
    {
        if (! is_dir($directory)) {
            throw ConfigurationException::missingDirectory($directory);
        }

        $files = glob($directory . '/*.php');
        $items = [];

        foreach ($files === false ? [] : $files as $file) {
            /** @var mixed $loaded */
            $loaded = require $file;

            if (! is_array($loaded)) {
                throw ConfigurationException::invalidFile($file);
            }

            $items[basename($file, '.php')] = $loaded;
        }

        return new self($items);
    }

    public function has(string $key): bool
    {
        return $this->find($key) !== null;
    }

    /**
     * Read a value using dot notation, e.g. `app.timezone`.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $found = $this->find($key);

        return $found === null ? $default : $found[0];
    }

    /**
     * @throws ConfigurationException when the key is missing or not a string
     */
    public function string(string $key): string
    {
        $value = $this->require($key);

        if (! is_string($value)) {
            throw ConfigurationException::invalidType($key, 'string', gettype($value));
        }

        return $value;
    }

    /**
     * @throws ConfigurationException when the key is missing or not a boolean
     */
    public function bool(string $key): bool
    {
        $value = $this->require($key);

        if (! is_bool($value)) {
            throw ConfigurationException::invalidType($key, 'bool', gettype($value));
        }

        return $value;
    }

    /**
     * @throws ConfigurationException when the key is missing or not an integer
     */
    public function int(string $key): int
    {
        $value = $this->require($key);

        if (! is_int($value)) {
            throw ConfigurationException::invalidType($key, 'int', gettype($value));
        }

        return $value;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @throws ConfigurationException when the key is missing
     */
    private function require(string $key): mixed
    {
        $found = $this->find($key);

        if ($found === null) {
            throw ConfigurationException::missingKey($key);
        }

        return $found[0];
    }

    /**
     * @return array{0: mixed}|null a single-element wrapper so that `null` values stay distinguishable
     */
    private function find(string $key): ?array
    {
        $value = $this->items;

        foreach (explode('.', $key) as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return null;
            }

            $value = $value[$segment];
        }

        return [$value];
    }
}
