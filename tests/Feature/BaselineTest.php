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

it('ships an example environment file whose credential placeholders are empty', function (): void {
    $example = (string) file_get_contents(dirname(__DIR__, 2) . '/.env.example');

    expect($example)->toContain('APP_ENV=local')
        ->and($example)->toContain('DB_PASSWORD=')
        ->and($example)->not->toMatch('/^[A-Z_]*(PASSWORD|SECRET|TOKEN|API_KEY)=.+$/m')
        ->and($example)->not->toContain('ghp_');
});

it('documents every database setting the configuration reads', function (): void {
    $example = (string) file_get_contents(dirname(__DIR__, 2) . '/.env.example');

    expect($example)->toContain('DB_HOST=')
        ->and($example)->toContain('DB_PORT=')
        ->and($example)->toContain('DB_DATABASE=')
        ->and($example)->toContain('DB_USERNAME=')
        ->and($example)->toContain('DB_PASSWORD=');
});

it('keeps the pre-launch page in the public document root', function (): void {
    expect(dirname(__DIR__, 2) . '/public/index.html')->toBeReadableFile()
        ->and(dirname(__DIR__, 2) . '/public/index.php')->toBeReadableFile();
});
