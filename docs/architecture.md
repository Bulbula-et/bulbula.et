# Architecture

## Principles

1. **Framework-free.** Bulbula is plain PHP. No Laravel, Symfony full-stack,
   Laminas, Mezzio, Dotkernel, Slim or Flight. Small, single-purpose libraries
   (PSR-3 logging, dotenv parsing) are welcome; application frameworks are not.
   An architecture test (`tests/Arch/ArchTest.php`) enforces this on every run.
2. **Composition over magic.** `Application` is a composition root, not a
   service container or a kernel. Dependencies are passed explicitly.
3. **Everything typed.** PHPStan runs at `level: max` and the type coverage
   gate is 100 %.
4. **Tests that actually test.** Line coverage alone is not a quality signal,
   so mutation testing (Infection) gates the real strength of the suite.

## Layout

```
config/      Environment-driven configuration files, each returning an array
docs/        Project documentation
public/      Web document root (front controller + pre-launch page)
src/         Application source, PSR-4 under the `Bulbula\` namespace
storage/     Runtime artefacts (logs); contents are git-ignored
tests/       Unit (PHPUnit style), Feature and Arch (Pest style) suites
```

### `src/`

| Namespace              | Responsibility                                                   |
| ---------------------- | ---------------------------------------------------------------- |
| `Bulbula\Foundation`   | `Application` composition root and the `Environment` enum         |
| `Bulbula\Config`       | Immutable configuration repository with dot-notation access       |
| `Bulbula\Logging`      | PSR-3 logger factory (Monolog)                                    |
| `Bulbula\Error`        | Error-to-exception promotion, logging and safe rendering          |
| `Bulbula\Support`      | Typed environment variable reader                                 |
| `Bulbula\Exception`    | Configuration failures                                            |

### Boot sequence

`public/index.php` → `Application::boot($basePath)`:

1. Load `.env` when present (never committed; see `.env.example`).
2. Build the `Config` repository from `config/*.php`.
3. Resolve the `Environment` and apply the configured timezone.
4. Build the PSR-3 logger — JSON lines in production, human readable elsewhere.
5. Register the error handler — verbose while developing, opaque in production
   (the client only sees a reference id that is also written to the log).

## Development vs production

| Concern        | Local / testing                   | Production                        |
| -------------- | --------------------------------- | --------------------------------- |
| `APP_DEBUG`    | `true`                            | forced off — see `isDebug()`      |
| Error output   | class, message, file, line, trace | generic message + reference id    |
| Log format     | readable lines                    | JSON                              |
| Log level      | `debug`                           | `warning` or higher (recommended) |

`Application::isDebug()` combines configuration with the environment, so
`APP_DEBUG=true` in production still yields `false`. Debug output cannot be
switched on by accident.

## Not implemented yet (on purpose)

Businesses, users, authentication, listings, search, maps, advertising,
reviews, database schema, APIs, frontend application, mobile app and payments
are all out of scope for this baseline.
