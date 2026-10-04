<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bulbula\Config\Config;
use Bulbula\Exception\ConfigurationException;
use Bulbula\Foundation\Application;
use Bulbula\Foundation\Environment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(Application::class)]
final class ApplicationTest extends TestCase
{
    private const string DOTENV_KEY = 'BULBULA_APP_NAME_FIXTURE';

    private string $timezone = 'UTC';

    protected function setUp(): void
    {
        $this->timezone = date_default_timezone_get();

        parent::setUp();
    }

    protected function tearDown(): void
    {
        unset($_ENV[self::DOTENV_KEY], $_SERVER[self::DOTENV_KEY]);
        putenv(self::DOTENV_KEY);
        date_default_timezone_set($this->timezone);

        parent::tearDown();
    }

    public function test_it_boots_from_a_project_root(): void
    {
        $app = $this->boot();

        self::assertSame(Environment::Testing, $app->environment());
        self::assertInstanceOf(Config::class, $app->config());
        self::assertInstanceOf(LoggerInterface::class, $app->logger());
        self::assertSame('Bulbula (fixture)', $app->config()->string('app.name'));
    }

    public function test_it_applies_the_configured_timezone(): void
    {
        $this->boot();

        self::assertSame('Africa/Addis_Ababa', date_default_timezone_get());
    }

    public function test_it_exposes_the_base_path_without_a_trailing_slash(): void
    {
        $app = $this->boot($this->basePath() . '/');

        self::assertSame($this->basePath(), $app->basePath());
        self::assertSame($this->basePath(), $app->path());
    }

    public function test_it_builds_paths_relative_to_the_base_path(): void
    {
        $app = $this->boot();

        self::assertSame($this->basePath() . '/config/app.php', $app->path('config/app.php'));
        self::assertSame($this->basePath() . '/config', $app->path('/config'));
    }

    public function test_debug_is_enabled_outside_production(): void
    {
        self::assertTrue($this->boot()->isDebug());
    }

    public function test_debug_is_disabled_in_production_even_when_configured(): void
    {
        $base = $this->temporaryProject(env: 'production', debug: true);

        self::assertFalse($this->boot($base)->isDebug());
    }

    public function test_debug_is_disabled_when_configuration_disables_it(): void
    {
        $base = $this->temporaryProject(env: 'local', debug: false);

        self::assertFalse($this->boot($base)->isDebug());
    }

    public function test_it_supports_an_absolute_log_path(): void
    {
        $log = sys_get_temp_dir() . '/bulbula-abs-' . bin2hex(random_bytes(6)) . '/app.log';
        $base = $this->temporaryProject(env: 'testing', debug: true, logPath: $log);

        $this->boot($base)->logger()->info('absolute path');

        self::assertFileExists($log);
    }

    public function test_it_resolves_a_relative_log_path_against_the_base_path(): void
    {
        $base = $this->temporaryProject(env: 'testing', debug: true);

        $this->boot($base)->logger()->info('relative path');

        self::assertFileExists($base . '/storage/logs/app.log');
    }

    public function test_it_loads_the_dotenv_file_of_the_project(): void
    {
        $base = $this->temporaryProject(env: 'testing', debug: true, dotenv: true);

        $app = $this->boot($base);

        self::assertSame('From dotenv', $app->config()->string('app.name'));
    }

    public function test_it_boots_without_a_dotenv_file(): void
    {
        $base = $this->temporaryProject(env: 'testing', debug: true);

        self::assertSame('Bulbula', $this->boot($base)->config()->string('app.name'));
    }

    public function test_it_registers_the_error_handler_by_default(): void
    {
        $base = $this->temporaryProject(env: 'testing', debug: true);

        $before = set_exception_handler(null);
        restore_exception_handler();

        Application::boot($base);

        $after = set_exception_handler(null);
        restore_exception_handler();
        restore_exception_handler();
        restore_error_handler();

        self::assertIsCallable($after);
        self::assertNotSame($before, $after);
    }

    public function test_it_fails_without_a_configuration_directory(): void
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('does not exist');

        $this->boot(sys_get_temp_dir() . '/bulbula-empty-' . bin2hex(random_bytes(6)));
    }

    private function boot(?string $basePath = null): Application
    {
        return Application::boot($basePath ?? $this->basePath(), registerErrorHandler: false);
    }

    private function basePath(): string
    {
        return __DIR__ . '/../Fixtures/app';
    }

    private function temporaryProject(
        string $env,
        bool $debug,
        ?string $logPath = null,
        bool $dotenv = false,
    ): string {
        $base = sys_get_temp_dir() . '/bulbula-app-' . bin2hex(random_bytes(6));
        mkdir($base . '/config', 0o775, true);

        if ($dotenv) {
            file_put_contents($base . '/.env', self::DOTENV_KEY . "=\"From dotenv\"\n");
        }

        file_put_contents($base . '/config/app.php', sprintf(
            "<?php return ['name' => \\Bulbula\\Support\\Env::string('%s', 'Bulbula'), 'env' => '%s', 'debug' => %s, 'timezone' => 'Africa/Addis_Ababa'];",
            self::DOTENV_KEY,
            $env,
            $debug ? 'true' : 'false',
        ));

        file_put_contents($base . '/config/logging.php', sprintf(
            "<?php return ['channel' => 'bulbula', 'path' => '%s', 'level' => 'debug'];",
            $logPath ?? 'storage/logs/app.log',
        ));

        return $base;
    }
}
