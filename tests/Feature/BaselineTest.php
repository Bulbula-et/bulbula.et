<?php

declare(strict_types=1);

use Bulbula\Foundation\Application;
use Bulbula\Foundation\Environment;

it('boots the real project configuration', function (): void {
    $app = Application::boot(dirname(__DIR__, 2), registerErrorHandler: false);

    expect($app->environment())->toBe(Environment::Testing)
        ->and($app->config()->string('app.name'))->not->toBeEmpty()
        ->and($app->config()->string('app.timezone'))->toBe('Africa/Addis_Ababa');
});

it('ships an example environment file without secrets', function (): void {
    $example = (string) file_get_contents(dirname(__DIR__, 2) . '/.env.example');

    expect($example)->toContain('APP_ENV=local')
        ->and($example)->not->toContain('PASSWORD=')
        ->and($example)->not->toContain('SECRET=')
        ->and($example)->not->toContain('ghp_');
});

it('keeps the pre-launch page in the public document root', function (): void {
    expect(dirname(__DIR__, 2) . '/public/index.html')->toBeReadableFile()
        ->and(dirname(__DIR__, 2) . '/public/index.php')->toBeReadableFile();
});
