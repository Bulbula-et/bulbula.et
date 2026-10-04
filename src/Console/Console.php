<?php

declare(strict_types=1);

namespace Bulbula\Console;

use Bulbula\Database\Migrations\Migrator;
use Closure;
use Throwable;

use function count;
use function sprintf;

/**
 * The migration command line.
 *
 * Three commands, no command framework: argument parsing this small does not
 * justify a dependency, and every path is covered by tests.
 */
final readonly class Console
{
    public const int SUCCESS = 0;

    public const int FAILURE = 1;

    /**
     * @param Closure(string): void $output
     */
    public function __construct(
        private Migrator $migrator,
        private Closure $output,
    ) {
    }

    /**
     * @param list<string> $argv the raw arguments, including the script name
     *
     * @return int the process exit code
     */
    public function run(array $argv): int
    {
        $command = $argv[1] ?? 'help';

        try {
            return match ($command) {
                'migrate' => $this->migrate(),
                'rollback' => $this->rollback($argv[2] ?? null),
                'migration:status' => $this->status(),
                'help' => $this->help(self::SUCCESS),
                default => $this->unknown($command),
            };
        } catch (Throwable $throwable) {
            $this->write(sprintf('Error: %s', $throwable->getMessage()));

            return self::FAILURE;
        }
    }

    private function migrate(): int
    {
        $ran = $this->migrator->migrate();

        if ($ran === []) {
            $this->write('Nothing to migrate.');

            return self::SUCCESS;
        }

        foreach ($ran as $migration) {
            $this->write(sprintf('Migrated: %s', $migration));
        }

        $this->write(sprintf('Applied %d migration(s).', count($ran)));

        return self::SUCCESS;
    }

    private function rollback(?string $option): int
    {
        $steps = $this->steps($option);

        if ($steps === null) {
            $this->write('Error: --steps must be a positive integer.');

            return self::FAILURE;
        }

        $reverted = $this->migrator->rollback($steps);

        if ($reverted === []) {
            $this->write('Nothing to roll back.');

            return self::SUCCESS;
        }

        foreach ($reverted as $migration) {
            $this->write(sprintf('Rolled back: %s', $migration));
        }

        $this->write(sprintf('Reverted %d migration(s).', count($reverted)));

        return self::SUCCESS;
    }

    private function status(): int
    {
        $statuses = $this->migrator->status();

        if ($statuses === []) {
            $this->write('No migrations found.');
        }

        foreach ($statuses as $status) {
            $this->write(sprintf('[%s] %s', $status->label(), $status->name()));
        }

        return self::SUCCESS;
    }

    private function unknown(string $command): int
    {
        $this->write(sprintf('Unknown command: %s', $command));

        return $this->help(self::FAILURE);
    }

    private function help(int $exitCode): int
    {
        $this->write('Bulbula console');
        $this->write('');
        $this->write('Usage: php bin/console <command>');
        $this->write('');
        $this->write('  migrate                    Run every pending migration');
        $this->write('  rollback [--steps=N]       Revert the last N batches (default 1)');
        $this->write('  migration:status           Show applied and pending migrations');
        $this->write('  help                       Show this message');

        return $exitCode;
    }

    /**
     * @return int|null null when the option is present but invalid
     */
    private function steps(?string $option): ?int
    {
        if ($option === null) {
            return 1;
        }

        if (preg_match('/^--steps=([1-9][0-9]*)$/', $option, $matches) !== 1) {
            return null;
        }

        return (int) $matches[1];
    }

    private function write(string $line): void
    {
        ($this->output)($line);
    }
}
