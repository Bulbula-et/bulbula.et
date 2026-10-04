<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bulbula\Config\Config;
use Bulbula\Exception\ConfigurationException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Config::class)]
#[CoversClass(ConfigurationException::class)]
final class ConfigTest extends TestCase
{
    public function test_it_reads_nested_values_with_dot_notation(): void
    {
        $config = Config::fromArray(['app' => ['nested' => ['key' => 'value']]]);

        self::assertSame('value', $config->get('app.nested.key'));
    }

    public function test_it_returns_the_default_for_unknown_keys(): void
    {
        $config = Config::fromArray(['app' => ['name' => 'Bulbula']]);

        self::assertSame('fallback', $config->get('app.missing', 'fallback'));
        self::assertNull($config->get('missing'));
    }

    public function test_it_returns_the_default_when_traversing_a_scalar(): void
    {
        $config = Config::fromArray(['app' => ['name' => 'Bulbula']]);

        self::assertSame('fallback', $config->get('app.name.deeper', 'fallback'));
    }

    public function test_it_distinguishes_a_null_value_from_a_missing_key(): void
    {
        $config = Config::fromArray(['app' => ['debug' => null]]);

        self::assertTrue($config->has('app.debug'));
        self::assertNull($config->get('app.debug', 'fallback'));
        self::assertFalse($config->has('app.nope'));
    }

    public function test_it_exposes_every_item(): void
    {
        $items = ['app' => ['name' => 'Bulbula']];

        self::assertSame($items, Config::fromArray($items)->all());
    }

    public function test_it_reads_typed_values(): void
    {
        $config = Config::fromArray([
            'app' => ['name' => 'Bulbula', 'debug' => true, 'workers' => 4],
        ]);

        self::assertSame('Bulbula', $config->string('app.name'));
        self::assertTrue($config->bool('app.debug'));
        self::assertSame(4, $config->int('app.workers'));
    }

    public function test_string_rejects_a_non_string_value(): void
    {
        $config = Config::fromArray(['app' => ['name' => 42]]);

        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Configuration key [app.name] must be of type string, integer given.');

        $config->string('app.name');
    }

    public function test_bool_rejects_a_non_boolean_value(): void
    {
        $config = Config::fromArray(['app' => ['debug' => 'yes']]);

        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('must be of type bool');

        $config->bool('app.debug');
    }

    public function test_int_rejects_a_non_integer_value(): void
    {
        $config = Config::fromArray(['app' => ['workers' => '4']]);

        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('must be of type int');

        $config->int('app.workers');
    }

    public function test_typed_readers_reject_missing_keys(): void
    {
        $config = Config::fromArray([]);

        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Configuration key [app.name] is not defined.');

        $config->string('app.name');
    }

    public function test_it_loads_a_configuration_directory(): void
    {
        $directory = $this->makeDirectory();
        file_put_contents($directory . '/app.php', "<?php return ['name' => 'Bulbula'];");
        file_put_contents($directory . '/logging.php', "<?php return ['level' => 'debug'];");

        $config = Config::fromDirectory($directory);

        self::assertSame('Bulbula', $config->string('app.name'));
        self::assertSame('debug', $config->string('logging.level'));
    }

    public function test_it_tolerates_a_trailing_slash_in_the_directory(): void
    {
        $directory = $this->makeDirectory();
        file_put_contents($directory . '/app.php', "<?php return ['name' => 'Bulbula'];");

        self::assertSame('Bulbula', Config::fromDirectory($directory . '/')->string('app.name'));
    }

    public function test_it_loads_an_empty_directory(): void
    {
        self::assertSame([], Config::fromDirectory($this->makeDirectory())->all());
    }

    public function test_it_rejects_a_missing_directory(): void
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('does not exist');

        Config::fromDirectory('/does/not/exist/bulbula');
    }

    public function test_it_rejects_a_file_that_does_not_return_an_array(): void
    {
        $directory = $this->makeDirectory();
        file_put_contents($directory . '/broken.php', '<?php return "nope";');

        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('must return an array');

        Config::fromDirectory($directory);
    }

    private function makeDirectory(): string
    {
        $directory = sys_get_temp_dir() . '/bulbula-config-' . bin2hex(random_bytes(6));
        mkdir($directory, 0o775, true);

        return $directory;
    }
}
