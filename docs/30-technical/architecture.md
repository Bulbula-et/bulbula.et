# Architecture

| | |
| --- | --- |
| **Document** | System Architecture |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | `docs/architecture.md` (Phase 1; relocated here and extended to V1) |

**Scope.** The canonical description of how Bulbula is built: layers,
components, boundaries and the rules that keep them honest. Constraints and
their sources live in [`trd-v1.0.md`](trd-v1.0.md); this document is the map.

**Relationship to Phase 1.** The foundation described here is real and
running — front controller, router, middleware pipeline, PDO layer,
migrations, console, health checks, error handling. V1 extends it with domain
code. Nothing in Phase 1 is replaced.

---

## 1. Principles

1. **Framework-free.** Plain PHP. No Laravel, Symfony full-stack, Laminas,
   Mezzio, Dotkernel, Slim or Flight. Small, single-purpose libraries are
   welcome; application frameworks are not. `tests/Arch/ArchTest.php`
   enforces this on every run.
2. **Composition over magic.** `Application` is a composition root, not a
   service container. Dependencies are constructed explicitly and passed in.
   There is no auto-wiring, no annotation scanning, no reflection magic.
3. **One direction.** HTTP → application → domain → persistence. Inner layers
   never reference outer ones.
4. **One implementation of every rule.** Web, API and Telegram are adapters
   over the same application services (D-49).
5. **Server-rendered first.** HTML is produced on the server; JavaScript
   enhances. Core content works without it (C-37, D-52).
6. **Everything typed.** PHPStan at `level: max`, 100% type coverage.
7. **Tests that actually test.** Coverage alone is not a signal; mutation
   testing gates the real strength of the suite. Thresholds are never
   lowered (D-26).
8. **Auditability and privacy by construction**, not by policy alone
   (C-29, D-51).
9. **Simplest thing compatible with the PRD.** Complexity needs a
   requirement behind it, not an anticipation.

---

## 2. Layers

```text
            Browser (mobile-first)        Telegram Mini App         Future Flutter
                     │                            │                        │
                     └──────────────┬─────────────┘                        │
                                    │ HTTPS                                │
┌───────────────────────────────────▼───────────────────────────────────────▼──────┐
│ HTTP layer            Request · Response · Status · Method · emitter              │
├──────────────────────────────────────────────────────────────────────────────────┤
│ Router / middleware   FastRoute matching · global and per-route pipelines         │
├──────────────────────────────────────────────────────────────────────────────────┤
│ Controller            HTML controllers · JSON controllers · thin, no rules        │
├──────────────────────────────────────────────────────────────────────────────────┤
│ Application services  Use cases: one method per meaningful operation              │
├──────────────────────────────────────────────────────────────────────────────────┤
│ Domain                Entities, value objects, state machines, invariants         │
├──────────────────────────────────────────────────────────────────────────────────┤
│ Repositories          SQL per aggregate, prepared statements only                 │
├──────────────────────────────────────────────────────────────────────────────────┤
│ MariaDB  ·  filesystem  ·  external adapters (identity, mail, storage, maps)      │
└──────────────────────────────────────────────────────────────────────────────────┘
```

### 2.1 Responsibilities

| Layer | Does | Must not |
| --- | --- | --- |
| **HTTP** | Model the protocol: immutable `Request`/`Response`, status and method enums, emission | Contain application logic |
| **Router / middleware** | Match routes, run cross-cutting concerns (headers, correlation id, session, auth, CSRF, rate limit) | Contain business rules or SQL |
| **Controller** | Read input, delegate to one application service, render a response | SQL, business rules, HTML string building, validation that belongs in a service |
| **Application service** | Orchestrate a use case: authorize, validate, call domain, persist, audit, notify | Know about HTTP, sessions, templates |
| **Domain** | Hold the rules: listing state transitions, review states, campaign eligibility, ranking inputs, value objects | Perform I/O of any kind |
| **Repository** | Translate between the domain and SQL; own its aggregate's tables | Contain business rules; be bypassed by another component |
| **Adapters** | Speak to the outside: identity provider, mail transport, object storage, Telegram validation | Leak vendor types into the domain |

### 2.2 Enforced boundaries

These are architecture tests, not conventions. A violation fails the build.

| Rule | Enforced today |
| --- | --- |
| No framework namespaces | ✅ |
| No ORM/DBAL namespaces | ✅ |
| `PDO`/`mysqli`/`curl_init` only in `Bulbula\Database` | ✅ |
| `Bulbula\Http` contains no SQL | ✅ |
| Controllers never touch `Bulbula\Database` or `PDO` | ✅ |
| Controllers suffixed `Controller`, HTTP exceptions suffixed `Exception` | ✅ |
| Database layer independent of `Http`, `View`, `Console` | ✅ |
| Service namespaces independent of `Http`, `View`, `Console` | ✅ |
| `Bulbula\View` touches no database and no HTTP | ✅ |
| `Env` read only inside `Bulbula\Config`; no superglobals elsewhere | ✅ |
| All classes final, strict types, value objects readonly | ✅ |
| No `dd`, `dump`, `die`, `var_dump`, `sleep`, `exit` in `src/` | ✅ |

**V1 obligation.** Every new namespace is added to these tests rather than
left unguarded (TRD TR-01). Two consequences are easy to miss:

- `curl_init` is restricted to `Bulbula\Database`, so **every outbound HTTP
  call must sit behind an adapter** whose transport lives in an allowed
  place; the test list is updated deliberately when that adapter arrives.
- Service namespaces must be added to the "independent of presentation"
  expectation as they are created.

---

## 3. Layout

```text
bin/console            CLI entry point: migrations, scheduled jobs, maintenance
config/                app.php, database.php, logging.php — arrays built from env
database/migrations/   ordered, reversible migrations
docs/                  documentation (this file lives in docs/30-technical/)
public/                document root: index.php, .htaccess, assets
resources/views/       templates, outside the document root
routes/                web.php, api.php
src/                   PSR-4 source under the Bulbula\ namespace
storage/               runtime artefacts (logs, cache, uploads) — contents git-ignored
tests/                 Unit, Feature, Arch
```

Directories for future modules are not created in advance: a directory
appears when the code that belongs in it does.

### 3.1 Namespaces

**Existing (Phase 1):**

| Namespace | Responsibility |
| --- | --- |
| `Bulbula\Foundation` | `Application` composition root, `Environment`, `Services`, `HttpKernelFactory` |
| `Bulbula\Config` | Immutable configuration repository with dot-notation access |
| `Bulbula\Logging` | PSR-3 logger factory (Monolog) |
| `Bulbula\Error` | Error-to-exception promotion, logging, safe rendering |
| `Bulbula\Support` | Typed environment reader |
| `Bulbula\Exception` | Configuration failures |
| `Bulbula\Http` | Request, Response, Status, Method, Kernel, emitter, HTTP errors |
| `Bulbula\Http\Routing` | Route registration, FastRoute matching, named-route URLs |
| `Bulbula\Http\Middleware` | Middleware contract, pipeline, `SecureHeaders` |
| `Bulbula\Http\Controller` | Thin HTTP boundary |
| `Bulbula\Database` | PDO connection, configuration, failure translation |
| `Bulbula\Database\Migrations` | Migration contract, locator, repository, migrator |
| `Bulbula\Console` | The command runner behind `bin/console` |
| `Bulbula\Diagnostics` | Health checks and report |
| `Bulbula\View` | Server-side rendering |

**Indicative V1 additions** — shape, not a mandate; each lands with the
capability that needs it:

| Namespace | Responsibility | Capabilities |
| --- | --- | --- |
| `Bulbula\Directory` | Business, Branch, Listing state, taxonomy, Areas | C-04…C-12, C-19…C-24 |
| `Bulbula\Operations` | Permission, provenance, verification, corrections, reports, queues | C-19…C-21, C-26 |
| `Bulbula\Identity` | Customers, provider identities, sessions, OTP, staff users, roles | C-30…C-33, C-36 |
| `Bulbula\Community` | Reviews, ratings, saves, moderation | C-13, C-14, C-25, C-34, C-35 |
| `Bulbula\Advertising` | Packages, placements, campaigns, delivery | C-16, C-27 |
| `Bulbula\Search` | Query interpretation, candidates, ranking pipeline | C-02, C-03, C-07 |
| `Bulbula\Media` | Upload validation, derivatives, storage adapter | C-22 |
| `Bulbula\Mail` | Message composition and transport adapter | C-31, C-39 |
| `Bulbula\Analytics` | Event recording and rollups | C-28, C-38 |
| `Bulbula\Audit` | Append-only privileged-action log | C-29 |
| `Bulbula\Telegram` | Mini App context validation and surface adapter | D-49, D-33 |

Each area keeps its own `Domain`, service and `Repository` seams internally;
cross-area work is orchestrated by an application service, never by reaching
into another area's tables (TRD TR-16).

---

## 4. Boot sequence

`public/index.php` is the single entry point. Before the application exists
it performs two version-agnostic guards — interpreter version and presence of
`vendor/autoload.php` — because a failure at that stage happens before any
handler could report it.

`Application::boot($basePath)` then:

1. Loads `.env` when present (never committed; see `.env.example`).
2. Builds the `Config` repository from `config/*.php`.
3. Resolves the `Environment` and applies the configured timezone.
4. Builds the PSR-3 logger — JSON lines in production, readable elsewhere.
5. Registers the error handler — verbose while developing, opaque in
   production, where the client receives only a reference id that is also
   written to the log.

`Services::forApplication()` constructs the shared collaborators explicitly.
It is a typed factory, not a container: there is no `get(string $id)`, and
every dependency can be followed in an IDE. As V1 services arrive they are
added here with typed accessors.

---

## 5. Request lifecycle

Apache serves `public/` and resolves `DirectoryIndex index.php`; anything
that is not an existing file or directory is rewritten internally to
`index.php`, keeping `REQUEST_URI` intact for the router.

```text
public/index.php
  └─ pre-boot guards                    interpreter version, autoloader present
  └─ Application::boot(basePath)        .env → Config → Environment → logger → errors
  └─ Services::forApplication(app)      explicit construction of collaborators
  └─ HttpKernelFactory::create(app)     Router ← routes/web.php then routes/api.php
  └─ Request::fromGlobals()
  └─ Kernel::handle(Request)
        ├─ global pipeline    SecureHeaders → RequestId → Session → …
        ├─ Router::match()    FastRoute: found | 405 | 404
        ├─ route pipeline     authentication, authorization, CSRF, rate limit, surface
        └─ handler            controller → application service → domain → repository
  └─ ResponseEmitter::emit(Response)    status line, headers, body
```

The global pipeline runs *around* error rendering, so even a 500 carries the
security headers. The per-route pipeline runs after a successful match.

---

## 6. Routing

Routes live in `routes/web.php` and `routes/api.php`. Each file returns a
`Closure(Router, Services): void`, so a route file cannot do anything except
register routes against the services it is handed.

```php
return static function (Router $router, Services $services): void {
    $health = new HealthController($services->health());

    $router->get('/health', static fn (): Response => $health->live())->name('health');
};
```

- `get()`, `post()`, `put()`, `patch()`, `delete()` register a handler of
  type `Closure(Request): Response`.
- `group('/api/v1', …)` prefixes every route inside it. The API is versioned
  from the first endpoint.
- `name()` registers the route for `UrlGenerator`, which builds URLs from a
  name plus parameters instead of hard-coded strings.
- Matching is delegated to `nikic/fast-route`. A miss throws
  `NotFoundException` (404); a path that exists under another verb throws
  `MethodNotAllowedException` (405) carrying the allowed methods, which the
  error handler turns into an `Allow` header.

The router stays thin: register, match, generate. Model binding, controller
resolution by string and middleware aliases would be the beginning of a
framework.

### 6.1 V1 route families

| Prefix | Surface | Authentication |
| --- | --- | --- |
| `/` | Public web (discovery, profiles, static pages) | None |
| `/account/*` | Customer web | Customer session |
| `/ops/*` | Operations console | Staff session + permission |
| `/api/v1/*` | Public JSON API | None, or bearer token |
| `/api/v1/ops/*` | Operations JSON API | Staff token + permission |

Separate prefixes exist so that authorization, rate limiting, caching and
indexability are set per family rather than per route (TRD TR-29).

---

## 7. Middleware

A middleware implements one method:

```php
public function process(Request $request, Closure $next): Response;
```

`Pipeline` composes a list back to front, so the first entry is the
outermost: the request travels down to the handler and the response bubbles
back up. A middleware may short-circuit by returning without calling
`$next`.

Phase 1 ships exactly one — `SecureHeaders`. V1 adds, in outermost-first
order:

| Middleware | Responsibility |
| --- | --- |
| `SecureHeaders` | CSP, `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy`; HSTS over HTTPS |
| Request id | Generate or accept a correlation id, attach to the request and to every log line, return it in a header |
| Session | Resolve a session from cookie or bearer token; attach the actor (Guest, Customer, Operator, Administrator) |
| CSRF | Verify a per-session token on state-changing HTML submissions |
| Rate limit | Apply configured limits to authentication, OTP, review, report and search paths |
| Surface | Detect and validate the Telegram Mini App context; mark the surface on the request |
| Authorization | Per-route permission requirement for `/ops/*` and account routes |

**The mechanism exists in Phase 1; V1 supplies the policies.**

---

## 8. Controllers, services and domain

A controller is an HTTP boundary and nothing else: read input from the
`Request`, call one application service, turn the result into a `Response`.
`HealthController` is the reference example — it asks `HealthChecker` for a
`HealthReport` and serialises it.

An application service is the use case. It authorizes, validates, invokes
domain logic, persists through repositories, writes the audit entry and
enqueues notifications. It knows nothing about HTTP, which is what lets the
same service back a web page, an API endpoint and a console command.

Domain objects hold the rules and perform no I/O: listing state transitions,
review state transitions, campaign eligibility, rating computation, ranking
inputs, value objects such as opening hours and coordinates. Time enters
through an injected clock, as `Services` already provides.

```text
HomeController ─▶ DiscoveryService ─▶ Listing (domain) ─▶ ListingRepository ─▶ Connection
                        │
                        ├─▶ AuditRecorder
                        └─▶ Mailer (queued)
```

---

## 9. Database access

MariaDB through PDO. No ORM, no query builder, no Active Record.

- `DatabaseConfig` is built from `config/database.php` (driven by `DB_*`
  variables) and produces the DSN and PDO options: exceptions on error,
  associative fetch, native prepared statements, `utf8mb4`.
- `ConnectionFactory` is the only place that calls `new PDO`.
- `Connection` opens lazily and exposes `select()`, `selectOne()`,
  `execute()`, `statement()`, `transaction()` and `ping()`. Every query is
  prepared with bound parameters; input is never interpolated into SQL.
- `transaction()` commits on success, rolls back on any throwable and
  rethrows — no silent swallowing.
- PDO failures become `DatabaseException` naming the driver and database but
  never the user, password or DSN. The password is marked
  `#[\SensitiveParameter]` so it is redacted in traces.

V1 adds **repositories** above this: one per aggregate, owning its tables,
returning domain objects or plain data structures, batching to avoid N+1
queries, and declaring the indexes its queries depend on in the migration
that creates the table.

The test suite runs against in-memory SQLite, which keeps it fast and
hermetic, so migrations stay portable or branch explicitly where they cannot.

---

## 10. Migrations

Files live in `database/migrations/` named
`YYYY_MM_DD_HHMMSS_description.php`, each returning an anonymous class
extending `Migration` with `up(Connection)` and `down(Connection)`.

`MigrationLocator` discovers files in filename order, `MigrationRepository`
owns the `migrations` tracking table (`migration` primary key, `batch`,
`executed_at`) and `Migrator` applies or reverts them. A batch records each
migration with the same batch number; a rollback reverts the last batch, or
the last *n* with `--steps=n`. DDL is never wrapped in a transaction, because
MariaDB commits implicitly on DDL.

```bash
php bin/console migrate              # apply everything pending
php bin/console migration:status     # applied / pending, in order
php bin/console rollback             # revert the last batch
php bin/console rollback --steps=2   # revert the last two batches
```

`bin/console` is the only file in the project that calls `exit()`.

---

## 11. Web and API

| | Web | API |
| --- | --- | --- |
| File | `routes/web.php` | `routes/api.php` |
| Prefix | `/` | `/api/v1` |
| Success | HTML, or operational JSON | JSON |
| Errors | `text/plain` or an HTML error page | JSON `{"error": {...}}` |

`Request::expectsJson()` decides the error format: true for any path under
`/api/`, a JSON content type, or a JSON `Accept` header. That is the single
place where the distinction lives; controllers never branch on it.

**The Web surface does not call its own API** (TRD TD-01). Both surfaces call
application services in-process. The API exists for the Telegram Mini App,
for progressive-enhancement fetches, and for the later Flutter client.

---

## 12. Error handling

`HttpErrorHandler` sits between the kernel and `ErrorHandler`. `HttpException`
subclasses (`NotFoundException`, `MethodNotAllowedException`,
`MalformedRequestException`) carry their own status and render as-is. Any
other throwable is reported through `ErrorHandler` (logged with a reference
id) and rendered as a 500: full detail while debugging, an opaque message
plus the reference id in production. Controllers contain no try/catch for
this — they throw, the kernel renders.

V1 adds a validation failure type that carries field-level detail for forms
and for the API envelope, and user-facing copy that offers a route forward.

---

## 13. Health checks

| Endpoint | Checks | Depends on the database |
| --- | --- | --- |
| `GET /health`, `GET /api/v1/health` | process is alive | no |
| `GET /health/ready`, `GET /api/v1/health/ready` | `ping()` + tracking table readable | yes |

Liveness must never depend on the database, otherwise a blip triggers a
restart of a healthy process. Readiness returns `503` with per-check detail.
Both send `Cache-Control: no-store`. V1 extends readiness with storage
writability.

---

## 14. Document root

Only `public/` is web accessible. `src/`, `config/`, `database/`,
`resources/`, `routes/`, `tests/`, `vendor/`, `composer.json`,
`composer.lock`, `storage/` and `.env` stay above it, and a feature test
asserts that the document root holds a single executable entry point.
Uploaded media is stored outside the document root and served through an
adapter or an explicit endpoint. Deployment details are in
[deployment.md](deployment.md).

---

## 15. Security posture

- `SecureHeaders` sets a restrictive CSP, `X-Content-Type-Options`,
  `X-Frame-Options`, `Referrer-Policy` and `Permissions-Policy` on every
  response; HSTS is added only over HTTPS. The Mini App needs a
  `frame-ancestors` policy admitting the Telegram host — **Open (TRD
  OT-05)**.
- JSON is encoded with `HEX_TAG|HEX_AMP|HEX_APOS|HEX_QUOT`, so a response
  embedded in HTML cannot break out of its context.
- Malformed JSON bodies and unsupported methods are rejected before any
  handler runs.
- Credentials are never logged, never placed in an exception message and
  never committed; `.env.example` ships placeholders only.
- Production error output is opaque: a reference id and nothing else.

V1 adds sessions, CSRF, rate limiting, authorization and upload validation —
the mechanisms described in TRD §34. The full security specification belongs
to `docs/50-security/` in a later phase.

---

## 16. Development versus production

| Concern | Local / testing | Production |
| --- | --- | --- |
| `APP_DEBUG` | `true` | forced off — see `isDebug()` |
| Error output | class, message, file, line, trace | generic message + reference id |
| Log format | readable lines | JSON |
| Log level | `debug` | `warning` or higher (recommended) |
| Database | in-memory SQLite for tests | MariaDB |

`Application::isDebug()` combines configuration with the environment, so
`APP_DEBUG=true` in production still yields `false`. Debug output cannot be
switched on by accident.

---

## 17. What V1 deliberately does not add

| Not added | Why |
| --- | --- |
| A framework, service container or auto-wiring | Standing rule; enforced by tests |
| An ORM or query builder | Enforced by tests |
| A SPA or front-end framework | Server-rendered requirement (C-37, D-52) |
| A search cluster | MariaDB-backed search is the V1 baseline |
| Redis or Memcached | Not available on the target host; caching must work without it |
| A message broker | Cron-driven jobs are sufficient |
| Containers or orchestration as a deployment requirement | Shared hosting is the target |
| Any business-account feature | D-54; seams only, never features |

---

## Decision references

D-26, D-33, D-49, D-51, D-52, D-54.
