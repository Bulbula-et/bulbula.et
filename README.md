# Bulbula.et 🇪🇹

Engineering baseline for **Bulbula** — a business discovery platform for Ethiopia.

> **Status:** application infrastructure. HTTP foundation, routing, middleware,
> controllers, PDO database access, migrations, configuration, health checks
> and error handling are in place. No business features (businesses, users,
> auth, listings, search, maps, ads, reviews, payments) are implemented yet —
> this repository exists to make building them safe and fast.

[![CI](https://github.com/Bulbula-et/bulbula.et/actions/workflows/ci.yml/badge.svg)](https://github.com/Bulbula-et/bulbula.et/actions/workflows/ci.yml)
[![Mutation testing](https://github.com/Bulbula-et/bulbula.et/actions/workflows/mutation.yml/badge.svg)](https://github.com/Bulbula-et/bulbula.et/actions/workflows/mutation.yml)
[![Security](https://github.com/Bulbula-et/bulbula.et/actions/workflows/security.yml/badge.svg)](https://github.com/Bulbula-et/bulbula.et/actions/workflows/security.yml)

---

## Architectural rule

**Bulbula is plain PHP.** No Laravel, Symfony, Mezzio, Laminas, Dotkernel,
Flight or any other application framework. Focused libraries (Monolog for
PSR-3 logging, phpdotenv for environment parsing, FastRoute for route
matching) are fine — frameworks are not. The rule is enforced by an architecture test, not just by convention.

See [docs/architecture.md](docs/architecture.md).

## Requirements

- **PHP 8.4+** with `dom`, `mbstring`, `json`, `libxml`, `pdo`, `pdo_mysql`
  (`pdo_sqlite` for the test suite)
- **MariaDB 10.6+** (only to run the application; the tests use in-memory SQLite)
- [Composer 2](https://getcomposer.org)
- **pcov** or **Xdebug** for coverage and mutation testing

## Getting started

```bash
git clone https://github.com/Bulbula-et/bulbula.et.git
cd bulbula.et

composer install
cp .env.example .env     # never commit .env
# set DB_DATABASE / DB_USERNAME / DB_PASSWORD for your local MariaDB

composer migrate         # create the schema
composer serve           # http://localhost:8000
```

Check that it is up:

```bash
curl -s localhost:8000/health          # liveness, never touches the database
curl -s localhost:8000/health/ready    # readiness, pings MariaDB
curl -s localhost:8000/api/v1/health   # same contract, versioned API
```

The test suite needs no database: `composer test` runs against in-memory
SQLite.

## Project layout

```
bin/console            CLI entry point: migrate · rollback · migration:status
config/                app.php, logging.php, database.php — environment-driven
database/migrations/   ordered schema migrations (up/down)
docs/                  architecture notes
public/                document root: front controller + pre-launch landing page
routes/                web.php (browser) · api.php (/api/v1)
src/                   PSR-4 source (Bulbula\)
storage/               runtime logs (git-ignored)
tests/                 Unit (PHPUnit style) · Feature & Arch (Pest style)
```

| Namespace                  | Responsibility                                     |
| -------------------------- | -------------------------------------------------- |
| `Bulbula\Foundation`       | `Application` composition root, `Services` factory  |
| `Bulbula\Http`             | Request, Response, Status, Method, Kernel, emitter  |
| `Bulbula\Http\Routing`     | Routes, FastRoute matching, named-route URLs        |
| `Bulbula\Http\Middleware`  | Middleware contract, pipeline, secure headers       |
| `Bulbula\Http\Controller`  | Thin HTTP boundary — no SQL, no business rules      |
| `Bulbula\Database`         | PDO connection, config, failure translation         |
| `Bulbula\Database\Migrations` | Locator, tracking repository, migrator          |
| `Bulbula\Console`          | The small command runner behind `bin/console`       |
| `Bulbula\Diagnostics`      | Health checks and report                            |
| `Bulbula\Config` `Logging` `Error` `Support` | configuration, PSR-3 logging, error handling, env reader |

Full details — request lifecycle, routing, middleware, database rules,
migration workflow — are in [docs/architecture.md](docs/architecture.md).

## Routing and endpoints

| Method & path                  | Description                              |
| ------------------------------ | ---------------------------------------- |
| `GET /`                        | Pre-launch landing page                  |
| `GET /health`                  | Liveness JSON — no database dependency   |
| `GET /health/ready`            | Readiness JSON — pings MariaDB, 503 when down |
| `GET /api/v1/health`           | Liveness, API contract                   |
| `GET /api/v1/health/ready`     | Readiness, API contract                  |

Web routes are declared in `routes/web.php`, API routes in `routes/api.php`
under the `/api/v1` prefix. Unknown paths return `404`, a known path with the
wrong verb returns `405` with an `Allow` header — as JSON for API requests and
as plain text for browser requests.

## Database and migrations

MariaDB is accessed through **PDO only** — no ORM, no query builder.
Connection settings come from `DB_*` environment variables via
`config/database.php`, and `Bulbula\Database\ConnectionFactory` is the single
place that constructs a `PDO` instance.

```bash
php bin/console migrate              # apply everything pending
php bin/console migration:status     # applied / pending, in order
php bin/console rollback             # revert the last batch
php bin/console rollback --steps=2   # revert the last two batches
```

Migrations live in `database/migrations/`, are named
`YYYY_MM_DD_HHMMSS_description.php` and return an anonymous class with `up()`
and `down()`. Applied migrations are tracked in the `migrations` table.

## Development commands

Every check is a Composer script, so local and CI runs are identical.

| Command                        | What it does                                                     |
| ------------------------------ | ---------------------------------------------------------------- |
| `composer test`                | **The full fast suite** — everything a pull request must pass     |
| `composer test:all`            | `test` + PHPInsights + mutation testing                           |
| `composer serve`               | Serve `public/` on `0.0.0.0:8000`                                 |
| `composer pint`                | Fix coding standards (PSR-12 + project rules)                     |
| `composer test:pint`           | Check coding standards without writing                            |
| `composer phpstan`             | Static analysis at `level: max`                                   |
| `composer rector`              | Apply automated refactorings                                      |
| `composer test:rector`         | Rector dry-run (fails if changes are pending)                     |
| `composer pest`                | Run the test suite                                                |
| `composer pest:parallel`       | Run tests in parallel                                             |
| `composer pest:coverage`       | Line coverage, gated at 100 %                                     |
| `composer pest:type-coverage`  | Type coverage, gated at 100 %                                     |
| `composer phpinsights`         | Code quality, architecture, complexity and style report           |
| `composer test:insights`       | The same, as a CI gate                                            |
| `composer infection`           | **Mutation testing** with surviving mutants printed               |
| `composer infection:ci`        | Mutation testing formatted for GitHub annotations                 |
| `composer test:composer`       | `composer validate --strict`                                      |
| `composer console <command>`   | Run a console command (`bin/console`)                             |
| `composer migrate`             | Apply pending migrations                                          |
| `composer migrate:status`      | Show applied and pending migrations                               |
| `composer rollback`            | Revert the last migration batch                                   |
| `composer core:update`         | Validate, update, bump and audit dependencies                     |

### Mutation testing

Infection measures whether the tests actually detect changed behaviour.

```bash
composer infection
```

Coverage driver required (`pcov` recommended; `XDEBUG_MODE=coverage` works too).
Configuration lives in [`infection.json5`](infection.json5); reports are written
to `build/infection/`. The gate is **MSI 100 % / covered MSI 100 %**.

Infection has no Pest adapter, and plain PHPUnit cannot execute Pest-style
test files. The `unit` suite is therefore written as classic PHPUnit test
classes — Pest runs them as well — and Infection targets that suite through
`--testsuite=unit`.

## Quality gates

| Tool                        | Gate                                   |
| --------------------------- | -------------------------------------- |
| PHPStan                     | `level: max`, zero errors              |
| Pest                        | all tests green                        |
| Line coverage               | 100 %                                  |
| Type coverage               | 100 %                                  |
| Infection (MSI)             | 100 %                                  |
| Laravel Pint                | PSR-12 + project rules, zero diff      |
| Rector                      | no pending refactorings                |
| PHPInsights                 | quality ≥ 95, architecture ≥ 95, style 100, complexity ≥ 80 |
| `composer audit`            | no known vulnerabilities               |

## CI

| Workflow                   | Trigger                              | Contains                                                                                   |
| -------------------------- | ------------------------------------ | ------------------------------------------------------------------------------------------ |
| `ci.yml` ⚡                 | pull requests + push to `main`       | dependency validation, coding standards, PHPStan, Rector, tests (highest/lowest), type coverage, PHPInsights, coverage |
| `mutation.yml` 🧬           | push to `main`, nightly, manual      | Infection + report artifact — expensive, never blocks a pull request                        |
| `security.yml` 🔒           | pull requests, push, weekly          | `composer audit` + Gitleaks secret scan                                                     |

Dependabot keeps Composer packages and GitHub Actions up to date weekly.

## Configuration & secrets

Configuration is read from the environment through `config/*.php`.
`.env` is git-ignored; [`.env.example`](.env.example) documents every variable
with safe placeholder values. **Never commit secrets, tokens or credentials.**

## The pre-launch page

`public/index.html` is the public coming-soon page (GSAP + Motion, no build
step). `public/index.php` boots the application and the HTTP kernel serves it
through the normal request lifecycle, so the routing, middleware, logging and
error-handling foundation is exercised by a real request.

## Credits

The tooling baseline started from
[benjaminhaeberli/php-skeleton](https://github.com/benjaminhaeberli/php-skeleton)
(MIT) and was adapted for an application: Pest, PHPStan, Pint, Rector and
PHPInsights were kept, while mutation testing, configuration/logging/error
foundations and the CI split were added.

Released under the [MIT license](LICENSE.md).
