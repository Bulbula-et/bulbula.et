<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets(
        php84: true,
    )
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        earlyReturn: true,
        typeDeclarations: true,
        privatization: true,
    )
    ->withSkip([
        // The front controller's PHP version guard is the one place where a
        // version check is not dead code: it has to run, and report, on the
        // older interpreter a misconfigured host may hand the request to.
        RemovePhpVersionIdCheckRector::class => [
            __DIR__ . '/public/index.php',
        ],
    ]);
