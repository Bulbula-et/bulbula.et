<?php

declare(strict_types=1);

use Bulbula\Database\Migrations\Migration;
use Bulbula\Http\Exception\HttpException;
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
    ->toBeFinal()
    // Abstract base classes cannot be final; they are the two extension points
    // of the application: a migration and a client-visible HTTP failure.
    ->ignoring([Migration::class, HttpException::class]);

arch('the application stays framework-free')
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

arch('no orm is introduced behind the pdo layer')
    ->expect('Bulbula')
    ->not->toUse([
        'Doctrine\ORM',
        'Doctrine\DBAL',
        'Illuminate\Database',
        'Propel',
        'Cycle\ORM',
    ]);

arch('only the database layer talks to the driver')
    ->expect(['PDO', 'PDOStatement', 'PDOException', 'mysqli', 'curl_init'])
    ->toOnlyBeUsedIn('Bulbula\Database');

arch('the http layer contains no sql')
    ->expect('Bulbula\Http')
    ->not->toUse(['PDO', 'PDOStatement', 'mysqli']);

arch('controllers never reach the database directly')
    ->expect('Bulbula\Http\Controller')
    ->not->toUse(['Bulbula\Database', 'PDO']);

arch('controllers are named after their role')
    ->expect('Bulbula\Http\Controller')
    ->toHaveSuffix('Controller');

arch('the database layer is independent of presentation')
    ->expect('Bulbula\Database')
    ->not->toUse(['Bulbula\Http', 'Bulbula\View', 'Bulbula\Console']);

arch('services are independent of presentation')
    ->expect('Bulbula\Diagnostics')
    ->not->toUse(['Bulbula\Http', 'Bulbula\View', 'Bulbula\Console']);

arch('the view layer renders and nothing else')
    ->expect('Bulbula\View')
    ->not->toUse(['Bulbula\Database', 'Bulbula\Http', 'PDO']);

arch('configuration is read in one place')
    ->expect(Bulbula\Support\Env::class)
    ->toOnlyBeUsedIn('Bulbula\Config');

arch('the http and database layers read settings through the config repository')
    ->expect(['Bulbula\Http', 'Bulbula\Database', 'Bulbula\Diagnostics'])
    ->not->toUse([Bulbula\Support\Env::class, 'getenv', 'putenv', '$_ENV', '$_SERVER', '$_GET', '$_POST']);

arch('value objects stay immutable')
    ->expect([Bulbula\Http\Request::class, Bulbula\Http\Response::class, Bulbula\Database\DatabaseConfig::class])
    ->toBeReadonly();

arch('enums describe the http protocol')
    ->expect([Bulbula\Http\Method::class, Bulbula\Http\Status::class, Bulbula\Diagnostics\HealthStatus::class])
    ->toBeEnums();

arch('middleware implements the pipeline contract')
    ->expect('Bulbula\Http\Middleware')
    ->classes()
    ->toImplement(Bulbula\Http\Middleware\Middleware::class)
    ->ignoring(Bulbula\Http\Middleware\Pipeline::class);

arch('http exceptions carry a status')
    ->expect('Bulbula\Http\Exception')
    ->toHaveSuffix('Exception');
