<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function fixturePath(string $relative = ''): string
    {
        $base = __DIR__ . '/../tests/Fixtures';

        return $relative === '' ? $base : $base . '/' . ltrim($relative, '/');
    }
}
