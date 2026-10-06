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

it('keeps the pre-launch template outside the document root', function (): void {
    $root = dirname(__DIR__, 2);

    expect($root . '/resources/views/home.html')->toBeReadableFile()
        ->and($root . '/public/index.php')->toBeReadableFile()
        ->and($root . '/public/index.html')->not->toBeFile();
});

it('exposes a single executable entry point in the document root', function (): void {
    $root = dirname(__DIR__, 2);
    $scripts = glob($root . '/public/*.php');

    expect($scripts)->toBe([$root . '/public/index.php']);
});

it('ships front-controller rules for the document root', function (): void {
    $htaccess = (string) file_get_contents(dirname(__DIR__, 2) . '/public/.htaccess');

    expect($htaccess)->toContain('DirectoryIndex index.php')
        ->and($htaccess)->toContain('RewriteEngine On')
        // Existing files and directories are served by Apache, not routed.
        ->and($htaccess)->toContain('RewriteCond %{REQUEST_FILENAME} !-f')
        ->and($htaccess)->toContain('RewriteCond %{REQUEST_FILENAME} !-d')
        ->and($htaccess)->toContain('RewriteRule ^ index.php [QSA,L]')
        // index.html must not come back as a competing directory index.
        ->and($htaccess)->not->toContain('index.html');
});

it('keeps the application out of the document root', function (): void {
    $root = dirname(__DIR__, 2);

    foreach (['src', 'config', 'database', 'routes', 'tests', 'vendor', 'resources'] as $directory) {
        expect($root . '/public/' . $directory)->not->toBeDirectory();
    }

    foreach (['.env', 'composer.json', 'composer.lock'] as $file) {
        expect($root . '/public/' . $file)->not->toBeFile();
    }
});
