<?php

declare(strict_types=1);

namespace Bulbula\Diagnostics;

enum HealthStatus: string
{
    case Pass = 'pass';

    case Fail = 'fail';

    public function isPassing(): bool
    {
        return $this === self::Pass;
    }
}
