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
5. **No hidden machinery.** There is no service container, no auto-wiring, no
   annotation scanning and no reflection-based magic. Reading a route file
   tells you exactly which object handles a request.

## Layout

```
bin/console            Command line entry point (migrate, rollback, status)
config/                Environment-driven configuration, each file returns an array
database/migrations/   Ordered schema migrations
docs/                  Project documentation
public/                Web document root (front controller, .htaccess, assets)
resources/views/       HTML templates, outside the document root
routes/                web.php and api.php route definitions
src/                   Application source, PSR-4 under the `Bulbula\` namespace
storage/               Runtime artefacts (logs); contents are git-ignored
tests/                 Unit (PHPUnit style), Feature and Arch (Pest style) suites
```

Directories for future modules are *not* created in advance: a directory
appears when the code that belongs in it does.

### `src/`

| Namespace              | Responsibility                                                   |
| ---------------------- | ---------------------------------------------------------------- |
| `Bulbula\Foundation`   | `Application` composition root and the `Environment` enum         |
| `Bulbula\Config`       | Immutable configuration repository with dot-notation access       |
| `Bulbula\Logging`      | PSR-3 logger factory (Monolog)                                    |
| `Bulbula\Error`        | Error-to-exception promotion, logging and safe rendering          |
| `Bulbula\Support`      | Typed environment variable reader                                 |
| `Bulbula\Exception`    | Configuration failures                                            |
| `Bulbula\Http`         | Request, Response, Status, Method, Kernel, emitter, HTTP errors   |
| `Bulbula\Http\Routing` | Route registration, FastRoute matching, named-route URLs          |
| `Bulbula\Http\Middleware` | Middleware contract, pipeline and the secure-header middleware |
| `Bulbula\Http\Controller` | Thin HTTP boundary: input in, service call, response out      |
| `Bulbula\Database`     | PDO connection, configuration and failure translation             |
| `Bulbula\Database\Migrations` | Migration contract, locator, repository and migrator       |
| `Bulbula\Console`      | The tiny command runner behind `bin/console`                      |
| `Bulbula\Diagnostics`  | Health checks and the health report                               |
| `Bulbula\View`         | Static page rendering for the pre-launch site                     |

### Boot sequence

`public/index.php` → `Application::boot($basePath)`:

1. Load `.env` when present (never committed; see `.env.example`).
2. Build the `Config` repository from `config/*.php`.
3. Resolve the `Environment` and apply the configured timezone.
4. Build the PSR-3 logger — JSON lines in production, human readable elsewhere.
5. Register the error handler — verbose while developing, opaque in production
   (the client only sees a reference id that is also written to the log).

## Request lifecycle

Apache serves `public/` and resolves `DirectoryIndex index.php`; anything that
is not an existing file or directory is rewritten internally to `index.php`,
which keeps `REQUEST_URI` intact for the router. There is exactly one entry
point — no static `index.html` competes with it, and templates live in
`resources/views/` where no web server can reach them.

```
public/index.php
  └─ Application::boot(basePath)          .env → Config → Environment → logger → error handler
  └─ Services::forApplication(app)        lazy Connection, HealthChecker, PageRenderer, clock
  └─ HttpKernelFactory::create(app)       Router ← routes/web.php then routes/api.php
  └─ Request::fromGlobals()
  └─ Kernel::handle(Request)
        ├─ global pipeline   SecureHeaders → …
        ├─ Router::match()   FastRoute: found | 405 | 404
        ├─ route pipeline    per-route middleware
        └─ handler           Closure(Request): Response → controller → service
  └─ ResponseEmitter::emit(Response)      status line, headers, body
```

Every object is constructed explicitly. `Services` is a small factory of
shared collaborators, not a container: it has typed accessors, no `get(string
$id)`, and resolving a service is a method call you can follow in an IDE.

## Routing

Routes live in `routes/web.php` and `routes/api.php`. Each file returns a
`Closure(Router, Services): void`, so a route file cannot do anything except
register routes against the services it is handed.

```php
return static function (Router $router, Services $services): void {
    $health = new HealthController($services->health());

    $router->get('/health', static fn (): Response => $health->live())->name('health');
};
```

- `get()`, `post()`, `put()`, `patch()`, `delete()` register a handler of type
  `Closure(Request): Response`.
- `group('/api/v1', …)` prefixes every route registered inside it. API routes
  are versioned from the very first endpoint.
- `name()` registers the route for `UrlGenerator`, which builds URLs from a
  name plus parameters instead of hard-coded strings.
- Matching is delegated to `nikic/fast-route`. A miss throws
  `NotFoundException` (404); a path that exists under another verb throws
  `MethodNotAllowedException` (405) carrying the allowed methods, which the
  error handler turns into an `Allow` header.

The router is deliberately thin: it registers, matches and generates URLs.
Anything richer (model binding, controller resolution by string, middleware
aliases) would be the beginning of a framework.

## Middleware

A middleware implements one method:

```php
public function process(Request $request, Closure $next): Response;
```

`Pipeline` composes a list of middleware back to front, so the first entry is
the outermost: the request travels down the list to the handler and the
response bubbles back up, giving each middleware a chance to act on both. A
middleware may short-circuit by returning a response without calling `$next`.

There are two pipelines. The global one runs in `Kernel::handle()` *around*
error rendering, so even a 500 response carries the security headers. The
per-route one runs after a successful match. Phase 1 ships exactly one
middleware — `SecureHeaders`. Authentication, CSRF and rate limiting are
later phases; the mechanism is here, the policies are not.

## Controllers and services

A controller is an HTTP boundary and nothing else: it reads input from the
`Request`, calls a service, and turns the result into a `Response`. It must
not contain SQL, business rules, HTML construction or validation logic that
belongs in a service. `HealthController` is the reference example — it asks
`HealthChecker` for a `HealthReport` and serialises it.

Services know nothing about HTTP. They accept and return plain values or
domain objects, which keeps them testable without a request and reusable from
`bin/console`. Architecture tests enforce both directions: controllers may not
touch `PDO`, and service-layer namespaces may not reference `Bulbula\Http`.

## Database access

MariaDB through PDO. No ORM, no query builder, no Active Record.

- `DatabaseConfig` is built from configuration (`config/database.php`, driven
  by `DB_*` environment variables) and produces the DSN and PDO options:
  exceptions on error, associative fetch mode, native prepared statements off
  emulation, `utf8mb4`.
- `ConnectionFactory` is the only place in the codebase that calls `new PDO`.
- `Connection` opens lazily on first use and exposes `select()`,
  `selectOne()`, `execute()`, `statement()`, `transaction()` and `ping()`.
  Every query is prepared with bound parameters; string interpolation of user
  input into SQL is never done.
- `transaction()` commits on success and rolls back on any throwable, then
  rethrows — no silent swallowing.
- PDO failures are translated into `DatabaseException` with a message that
  names the driver and target database but **never** the user, password or
  DSN, so a leaked stack trace cannot leak credentials.
- The password is marked `#[\SensitiveParameter]` so it is redacted in traces.

The test suite runs against in-memory SQLite, which keeps it fast and
hermetic; the schema is written in portable SQL so the same migrations run on
both engines.

## Migrations

Migration files live in `database/migrations/` and are named
`YYYY_MM_DD_HHMMSS_description.php`. Each returns an anonymous class extending
`Migration` with `up(Connection)` and `down(Connection)`:

```php
return new class () extends Migration {
    public function up(Connection $connection): void { /* … */ }
    public function down(Connection $connection): void { /* … */ }
};
```

`MigrationLocator` discovers files in filename order, `MigrationRepository`
owns the `migrations` tracking table (`migration` primary key, `batch`,
`executed_at`) and `Migrator` applies or reverts them. Applying a batch
records each migration with the same batch number; a rollback reverts the last
batch, or the last *n* batches with `--steps=n`. DDL is never wrapped in a
transaction, because MariaDB commits implicitly on DDL anyway.

```bash
php bin/console migrate              # apply everything pending
php bin/console migration:status     # applied / pending, in order
php bin/console rollback             # revert the last batch
php bin/console rollback --steps=2   # revert the last two batches
```

`bin/console` is the only file in the project that calls `exit()`; everything
below it returns an exit code.

## Web and API

| | Web | API |
| --- | --- | --- |
| File | `routes/web.php` | `routes/api.php` |
| Prefix | `/` | `/api/v1` |
| Success | HTML or operational JSON | JSON |
| Errors | `text/plain` body with status line | JSON `{"error": {...}}` |

`Request::expectsJson()` decides the error format: true for any path under
`/api/`, a JSON content type, or a JSON `Accept` header. That is the single
place where the distinction lives — controllers never branch on it.

## Error handling

`HttpErrorHandler` sits between the kernel and `ErrorHandler`. `HttpException`
subclasses (`NotFoundException`, `MethodNotAllowedException`,
`MalformedRequestException`) carry their own status and are rendered as-is.
Any other throwable is reported through the existing `ErrorHandler` (logged
with a reference id) and rendered as a 500: full details while debugging, an
opaque message plus the reference id in production. Controllers contain no
try/catch for this — they throw, the kernel renders.

## Health checks

| Endpoint | Checks | Depends on the database |
| --- | --- | --- |
| `GET /health`, `GET /api/v1/health` | process is alive | no |
| `GET /health/ready`, `GET /api/v1/health/ready` | `ping()` + tracking table readable | yes |

Liveness must never depend on the database, otherwise an orchestrator restarts
a healthy process during a database blip. Readiness returns `503` with a
per-check detail when a check fails. Both send `Cache-Control: no-store`.

## Document root

Only `public/` is web accessible. `src/`, `config/`, `database/`,
`resources/`, `routes/`, `tests/`, `vendor/`, `composer.json`,
`composer.lock` and `.env` stay above it, and feature tests assert that the
document root holds a single executable entry point. Deployment details are
in [deployment.md](deployment.md).

## Security posture

- `SecureHeaders` sets a restrictive `Content-Security-Policy`,
  `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy` and
  `Permissions-Policy` on every response; HSTS is added only over HTTPS.
- JSON is encoded with `HEX_TAG|HEX_AMP|HEX_APOS|HEX_QUOT`, so a response
  embedded in HTML cannot break out of its context.
- Malformed JSON bodies and unsupported methods are rejected before any
  handler runs.
- Credentials are never logged, never put in an exception message and never
  committed; `.env.example` ships placeholders only.
- Production error output is opaque: a reference id, nothing else.

Authentication, CSRF protection, rate limiting and sessions are deliberately
absent — they belong to the phase that introduces users.

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

Businesses, users, authentication, listings, categories, reviews, search,
maps, advertising, payments, the mobile API, the admin dashboard and the
frontend redesign are all out of scope. This phase delivers the infrastructure
they will be built on, and nothing else.
