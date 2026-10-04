<?php

declare(strict_types=1);

namespace Tests\Unit\Console;

use Bulbula\Console\Console;
use Bulbula\Database\Connection;
use Bulbula\Database\DatabaseConfig;
use Bulbula\Database\Migrations\MigrationLocator;
use Bulbula\Database\Migrations\MigrationRepository;
use Bulbula\Database\Migrations\Migrator;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Console::class)]
final class ConsoleTest extends TestCase
{
    private const string FIRST = '2026_01_01_000000_create_first_table';

    private const string SECOND = '2026_02_01_000000_create_second_table';

    /** @var list<string> */
    private array $output = [];

    private Connection $connection;

    protected function setUp(): void
    {
        $this->output = [];
        $this->connection = new Connection(DatabaseConfig::sqliteInMemory());

        parent::setUp();
    }

    public function test_migrate_applies_and_reports_every_migration(): void
    {
        $exitCode = $this->console()->run(['console', 'migrate']);

        self::assertSame(Console::SUCCESS, $exitCode);
        self::assertSame(
            [
                'Migrated: ' . self::FIRST,
                'Migrated: ' . self::SECOND,
                'Applied 2 migration(s).',
            ],
            $this->output,
        );
    }

    public function test_migrate_is_idempotent(): void
    {
        $console = $this->console();
        $console->run(['console', 'migrate']);
        $this->output = [];

        self::assertSame(Console::SUCCESS, $console->run(['console', 'migrate']));
        self::assertSame(['Nothing to migrate.'], $this->output);
    }

    public function test_rollback_reverts_the_last_batch(): void
    {
        $console = $this->console();
        $console->run(['console', 'migrate']);
        $this->output = [];

        self::assertSame(Console::SUCCESS, $console->run(['console', 'rollback']));
        self::assertSame(
            ['Rolled back: ' . self::SECOND, 'Rolled back: ' . self::FIRST, 'Reverted 2 migration(s).'],
            $this->output,
        );
    }

    public function test_rollback_reports_when_there_is_nothing_to_revert(): void
    {
        self::assertSame(Console::SUCCESS, $this->console()->run(['console', 'rollback']));
        self::assertSame(['Nothing to roll back.'], $this->output);
    }

    public function test_rollback_accepts_a_step_count(): void
    {
        $console = $this->console();
        $repository = $this->repository();
        $repository->ensureTableExists();
        $repository->log(self::FIRST, 1);
        $console->run(['console', 'migrate']);
        $this->output = [];

        self::assertSame(Console::SUCCESS, $console->run(['console', 'rollback', '--steps=2']));
        self::assertSame(
            ['Rolled back: ' . self::SECOND, 'Rolled back: ' . self::FIRST, 'Reverted 2 migration(s).'],
            $this->output,
        );
    }

    public function test_rollback_without_a_step_count_reverts_only_the_last_batch(): void
    {
        $console = $this->console();
        $repository = $this->repository();
        $repository->ensureTableExists();
        $repository->log(self::FIRST, 1);
        $console->run(['console', 'migrate']);
        $this->output = [];

        self::assertSame(Console::SUCCESS, $console->run(['console', 'rollback']));
        self::assertSame(
            ['Rolled back: ' . self::SECOND, 'Reverted 1 migration(s).'],
            $this->output,
        );
    }

    public function test_rollback_rejects_an_unknown_option(): void
    {
        self::assertSame(Console::FAILURE, $this->console()->run(['console', 'rollback', '--dry-run']));
        self::assertSame(['Error: --steps must be a positive integer.'], $this->output);
    }

    public function test_rollback_rejects_a_step_count_with_trailing_characters(): void
    {
        self::assertSame(Console::FAILURE, $this->console()->run(['console', 'rollback', '--steps=2x']));
    }

    public function test_rollback_rejects_a_step_option_with_a_prefix(): void
    {
        self::assertSame(Console::FAILURE, $this->console()->run(['console', 'rollback', 'x--steps=2']));
    }

    public function test_rollback_rejects_an_invalid_step_count(): void
    {
        self::assertSame(Console::FAILURE, $this->console()->run(['console', 'rollback', '--steps=zero']));
        self::assertSame(['Error: --steps must be a positive integer.'], $this->output);
    }

    public function test_rollback_rejects_a_zero_step_count(): void
    {
        self::assertSame(Console::FAILURE, $this->console()->run(['console', 'rollback', '--steps=0']));
        self::assertSame(['Error: --steps must be a positive integer.'], $this->output);
    }

    public function test_rollback_rejects_a_negative_step_count(): void
    {
        self::assertSame(Console::FAILURE, $this->console()->run(['console', 'rollback', '--steps=-1']));
    }

    public function test_status_lists_applied_and_pending_migrations(): void
    {
        $console = $this->console();

        self::assertSame(Console::SUCCESS, $console->run(['console', 'migration:status']));
        self::assertSame(['[pending] ' . self::FIRST, '[pending] ' . self::SECOND], $this->output);

        $console->run(['console', 'migrate']);
        $this->output = [];
        $console->run(['console', 'migration:status']);

        self::assertSame(['[applied] ' . self::FIRST, '[applied] ' . self::SECOND], $this->output);
    }

    public function test_status_reports_an_empty_migration_directory(): void
    {
        self::assertSame(Console::SUCCESS, $this->console('empty')->run(['console', 'migration:status']));
        self::assertSame(['No migrations found.'], $this->output);
    }

    public function test_help_is_the_default_command(): void
    {
        self::assertSame(Console::SUCCESS, $this->console()->run(['console']));
        self::assertSame($this->usage(), $this->output);
    }

    public function test_help_can_be_requested(): void
    {
        self::assertSame(Console::SUCCESS, $this->console()->run(['console', 'help']));
        self::assertSame($this->usage(), $this->output);
    }

    public function test_an_unknown_command_fails_and_shows_the_usage(): void
    {
        self::assertSame(Console::FAILURE, $this->console()->run(['console', 'deploy']));
        self::assertSame(['Unknown command: deploy', ...$this->usage()], $this->output);
    }

    public function test_a_failure_is_reported_without_a_stack_trace(): void
    {
        $console = $this->console('nowhere');

        self::assertSame(Console::FAILURE, $console->run(['console', 'migrate']));
        self::assertCount(1, $this->output);
        self::assertStringStartsWith('Error: Migration directory [', $this->output[0]);
    }

    /**
     * @return list<string>
     */
    private function usage(): array
    {
        return [
            'Bulbula console',
            '',
            'Usage: php bin/console <command>',
            '',
            '  migrate                    Run every pending migration',
            '  rollback [--steps=N]       Revert the last N batches (default 1)',
            '  migration:status           Show applied and pending migrations',
            '  help                       Show this message',
        ];
    }

    private function repository(): MigrationRepository
    {
        return new MigrationRepository(
            $this->connection,
            static fn (): DateTimeImmutable => new DateTimeImmutable('2026-10-04 08:30:00'),
        );
    }

    private function console(string $directory = 'valid'): Console
    {
        $migrator = new Migrator(
            $this->connection,
            new MigrationLocator(__DIR__ . '/../../Fixtures/migrations/' . $directory),
            $this->repository(),
        );

        return new Console($migrator, function (string $line): void {
            $this->output[] = $line;
        });
    }
}
