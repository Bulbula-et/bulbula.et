<?php

declare(strict_types=1);

use Pest\Expectation;

arch('no debugging leftovers')
    ->expect(['dd', 'dump', 'ray', 'die', 'var_dump', 'sleep', 'exit'])
    ->not->toBeUsed()
    ->ignoring(Expectation::class);

arch('source declares strict types')
    ->expect('Bulbula')
    ->toUseStrictTypes();

arch('source classes are final')
    ->expect('Bulbula')
    ->classes()
    ->toBeFinal();

arch('the baseline stays framework-free')
    ->expect('Bulbula')
    ->not->toUse([
        'Illuminate',
        'Laravel',
        'Symfony\Component\HttpKernel',
        'Laminas',
        'Mezzio',
        'Slim',
        'Flight',
    ]);

arch('no business features are implemented yet')
    ->expect('Bulbula')
    ->not->toUse(['PDO', 'mysqli', 'curl_init']);
