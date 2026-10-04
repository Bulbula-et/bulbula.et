<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenDefineFunctions;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\NoSilencedErrorsSniff;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousExceptionNamingSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\AlphabeticallySortedUsesSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseSpacingSniff;

/*
 * PHPInsights configuration.
 *
 * Laravel Pint (see pint.json) is the single source of truth for formatting,
 * so every PHPInsights rule that duplicates or contradicts it is disabled
 * here. What remains is what Pint cannot judge: code quality, architecture
 * and complexity.
 */
return [
    'preset' => 'default',

    'ide' => null,

    'exclude' => [
        'build',
        'public/assets',
        'storage',
        'vendor',
    ],

    'add' => [],

    'remove' => [
        // Formatting is owned by Pint (PSR-12 + project rules).
        AlphabeticallySortedUsesSniff::class,
        OrderedImportsFixer::class,
        UseSpacingSniff::class,
        // PSR-compatible, intention-revealing exception names are wanted.
        SuperfluousExceptionNamingSniff::class,
        // `define()` is not used; the insight only adds noise to a config-free app.
        ForbiddenDefineFunctions::class,
    ],

    'config' => [
        // Match the line length the rest of the toolchain (Pint/PSR-12) allows.
        LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 120,
            'ignoreComments' => false,
        ],
        CyclomaticComplexityIsHigh::class => [
            'maxComplexity' => 10,
        ],
        // A configuration repository returns values of unknown shape by
        // definition; `mixed` is the honest signature and PHPStan (level max)
        // keeps every call site safe.
        DisallowMixedTypeHintSniff::class => [
            'exclude' => [
                'src/Config/Config.php',
            ],
        ],
        // LoggerFactory converts a failing mkdir() into an exception, so the
        // native warning is suppressed there on purpose. The rule stays on
        // everywhere else.
        NoSilencedErrorsSniff::class => [
            'exclude' => [
                'src/Logging/LoggerFactory.php',
            ],
        ],
        ForbiddenNormalClasses::class => [
            'exclude' => [
                'src/Exception',
            ],
        ],
    ],

    'requirements' => [
        'min-quality' => 95,
        'min-complexity' => 80,
        'min-architecture' => 95,
        'min-style' => 100,
        'disable-security-check' => false,
    ],

    'threads' => null,
];
