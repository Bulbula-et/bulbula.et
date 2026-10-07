# Architecture Context

```text
Source baseline:   b68ce5acf32afd6b525226a90343e3196f258a0e
Last derived from: 2026-10-07
Context status:    Current
```

**Derived from** `docs/30-technical/architecture.md` · `trd-v1.0.md` §6–§12 ·
`tests/Arch/ArchTest.php` · the actual `src/` tree.
**Authority:** those documents and that test file.

> **This is the guardrail file.** If you read nothing else before writing
> code, read §2 and §3.

---

## 1. The request path

```text
Request
   → Middleware           headers, correlation id, session, auth, CSRF, rate limit
   → Router               FastRoute matching
   → Controller           thin: read input, delegate once, render
   → Application Service  authorize · validate · call domain · persist · audit · notify
   → Domain logic         entities, value objects, state machines, invariants — no I/O
   → Repository           SQL per aggregate, prepared statements only
   → Database / Gateway   MariaDB · filesystem · outbound HTTP gateway
```

**One direction only.** Inner layers never reference outer ones.

### Layer responsibilities

| Layer | Does | Must not |
| --- | --- | --- |
| **HTTP** | Model the protocol: immutable `Request`/`Response`, `Status`/`Method` enums, emission | Contain application logic |
| **Router / middleware** | Match routes; run cross-cutting concerns | Contain business rules or SQL |
| **Controller** | Read input, delegate to **one** application service, render a response | SQL, business rules, HTML string building, validation that belongs in a service |
| **Application service** | Orchestrate a use case | Know about HTTP, sessions or templates |
| **Domain** | Hold the rules: listing state transitions, review states, campaign eligibility, ranking inputs, value objects | **Perform I/O of any kind** |
| **Repository** | Translate between domain and SQL; own its aggregate's tables | Contain business rules; be bypassed |
| **Adapters** | Speak to the outside: identity, mail, storage, maps, Telegram validation | Leak vendor types into the domain |

---

## 2. Forbidden architectural shortcuts

Each of these is either already blocked by a test or must be treated as if it
were.

| Shortcut | Status |
| --- | --- |
| **Controller → PDO** | **Forbidden** — enforced (`controllers never reach the database directly`) |
| **Controller → `Bulbula\Database`** | **Forbidden** — enforced |
| **SQL anywhere in `Bulbula\Http`** | **Forbidden** — enforced |
| **Service → HTTP layer** | **Forbidden** — enforced for service namespaces |
| **View → SQL / PDO / HTTP** | **Forbidden** — enforced |
| **Route file → SQL** | **Forbidden** — route files wire controllers, nothing more |
| **Arbitrary global service locator** | **Forbidden** — `Application` is a composition root, not a container |
| **Hidden DI container / auto-wiring / annotation scanning / reflection magic** | **Forbidden** — composition over magic |
| **Duplicated business rules for Web and Telegram** | **Forbidden** — one implementation, two adapters (D-49) |
| **Telegram-specific logic scattered through domain code** | **Forbidden** — confined to the adapter inventory |
| **Direct outbound `curl` outside the gateway** | **Forbidden** — enforced; one namespace owns transport (TD-07, TR-01a) |
| **Database access outside `Bulbula\Database`** | **Forbidden** — enforced |
| **Reading `getenv`, `$_ENV`, `$_SERVER`, `$_GET`, `$_POST` outside `Bulbula\Config`** | **Forbidden** — enforced |
| **Business logic in a migration** | **Forbidden** — a migration is a historical record (MG-8, TR-177) |
| **Catching exceptions in a controller for presentation** | **Forbidden** — throw; the kernel renders (TR-143) |
| **A second executable entry point in `public/`** | **Forbidden** — exactly one (TR-09, AC-3) |

### The rule about relaxing a rule

> **An enforced boundary is never weakened to accommodate a foreseeable
> requirement. That is the exact failure mode the boundary exists to
> prevent** (TD-07).

If a boundary genuinely blocks a legitimate need, the answer is a **narrow
new namespace added to the test**, not a widened exclusion. The outbound HTTP
gateway is the worked example.

---

## 3. The enforced rules — `tests/Arch/ArchTest.php`

These fail the build. They are the actual assertions, summarised.

| Test | Rule |
| --- | --- |
| `no debugging leftovers` | `dd`, `dump`, `ray`, `die`, `var_dump`, `sleep`, `exit` are not used |
| `source declares strict types` | `declare(strict_types=1)` throughout `Bulbula` |
| `source classes are final` | All classes `final`, except `Migration` and `HttpException` (the two deliberate extension points) |
| `the application stays framework-free` | No `Illuminate`, `Laravel`, `Symfony\Component\HttpKernel`, `Laminas`, `Mezzio`, `Slim`, `Flight` |
| `no orm is introduced behind the pdo layer` | No `Doctrine\ORM`, `Doctrine\DBAL`, `Illuminate\Database`, `Propel`, `Cycle\ORM` |
| `only the database layer talks to the driver` | `PDO`, `PDOStatement`, `PDOException`, `mysqli`, `curl_init` used **only** in `Bulbula\Database` |
| `the http layer contains no sql` | `Bulbula\Http` does not use `PDO`, `PDOStatement`, `mysqli` |
| `controllers never reach the database directly` | `Bulbula\Http\Controller` does not use `Bulbula\Database` or `PDO` |
| `controllers are named after their role` | `Bulbula\Http\Controller` classes have suffix `Controller` |
| `the database layer is independent of presentation` | `Bulbula\Database` does not use `Http`, `View`, `Console` |
| `services are independent of presentation` | `Bulbula\Diagnostics` does not use `Http`, `View`, `Console` |
| `the view layer renders and nothing else` | `Bulbula\View` does not use `Database`, `Http`, `PDO` |
| `configuration is read in one place` | `Bulbula\Support\Env` used **only** in `Bulbula\Config` |
| `…read settings through the config repository` | `Http`, `Database`, `Diagnostics` do not use `Env`, `getenv`, `putenv`, `$_ENV`, `$_SERVER`, `$_GET`, `$_POST` |
| `value objects stay immutable` | `Request`, `Response`, `DatabaseConfig` are `readonly` |
| `enums describe the http protocol` | `Method`, `Status`, `HealthStatus` are enums |
| `middleware implements the pipeline contract` | `Bulbula\Http\Middleware` classes implement `Middleware` (except `Pipeline`) |
| `http exceptions carry a status` | `Bulbula\Http\Exception` classes have suffix `Exception` |

### Two consequences that are easy to miss

1. **`curl_init` is restricted to `Bulbula\Database`.** Every outbound HTTP
   call must sit behind an adapter whose transport lives in an allowed place.
   The test list is updated **deliberately** when that namespace arrives.
2. **`services are independent of presentation` currently names only
   `Bulbula\Diagnostics`.** Every new service namespace must be added to that
   expectation as it is created — otherwise it is unguarded.

### The V1 obligation

> **TR-01 — Every new namespace introduced in V1 must be placed on the
> correct side of these boundaries, and the architecture test suite must be
> extended to cover the new namespaces rather than leaving them unguarded.**

This is acceptance criterion **AC-1**. Adding a namespace without adding its
architecture assertions is an incomplete change.

---

## 4. Current namespaces

| Namespace | Responsibility | V1 direction |
| --- | --- | --- |
| `Bulbula\Foundation` | `Application` composition root, `Environment`, `Services`, `HttpKernelFactory` | Extended with new services |
| `Bulbula\Config` | Immutable dot-notation config repository | Extended with new config files |
| `Bulbula\Http` | `Request`, `Response`, `Status`, `Method`, `Kernel`, emitter, error handler | Extended with middleware |
| `Bulbula\Http\Routing` | Route registration, FastRoute matching, named URLs | Extended with new routes |
| `Bulbula\Http\Middleware` | `Middleware` contract, `Pipeline`, `SecureHeaders` | Extended |
| `Bulbula\Http\Controller` | Thin HTTP boundary | Extended per capability |
| `Bulbula\Http\Exception` | `HttpException` and subclasses | Extended |
| `Bulbula\Database` | `Connection`, `ConnectionFactory`, `DatabaseConfig`, exceptions | Reused as-is |
| `Bulbula\Database\Migrations` | Locator, repository, migrator, `Migration` | Reused as-is |
| `Bulbula\Console` | Command runner behind `bin/console` | Extended with scheduled commands |
| `Bulbula\Diagnostics` | Health checks and report | Reused; extended checks |
| `Bulbula\Logging` | PSR-3 Monolog factory | Reused |
| `Bulbula\View` | `PageRenderer` for static pages | Replaced or extended — **Open (D-17)** |
| `Bulbula\Error`, `Bulbula\Exception`, `Bulbula\Support` | Error promotion, configuration failures, typed env reader | Reused as-is |

49 source files today. Nothing above contains a business feature.

---

## 5. Directory layout

```text
bin/console            CLI entry point: migrate · rollback · migration:status
config/                app.php, database.php, logging.php — arrays built from env
database/migrations/   ordered, reversible migrations
docs/                  formal documentation (authoritative)
.ai/                   derived context layer (this directory)
public/                document root: index.php + .htaccess + static assets
resources/views/       templates, deliberately OUTSIDE the document root
routes/                web.php (browser) · api.php (/api/v1)
src/                   PSR-4 source (Bulbula\)
storage/               runtime artefacts (logs) — git-ignored contents
tests/                 Unit · Feature · Arch · Fixtures
```

| Rule | Source |
| --- | --- |
| **The document root is `public/`.** `src/`, `config/`, `database/`, `resources/`, `routes/`, `tests/`, `vendor/`, `.env` all stay above it | TR-184, DR-1 |
| **`public/index.php` is the only PHP entry point** | TR-185, DR-2 |
| Nothing outside `public/` is reachable over HTTP | DP-8 |
| Directory listings are off; static assets under `public/assets/` are served by Apache and never routed through PHP | DR-4, DR-5 |
| **`storage/` is the only writable path the application requires**, and it is outside the document root | PM-1, PM-2 |
| Application code is **not** writable by the web-server user. No self-update, no plugin install, no runtime code generation | PM-4 |
| `storage/logs/` must be writable or the application **fails to boot loudly** | PM-3, DP-6 |

---

## 6. Boot and lifecycle

```text
public/index.php
  → PHP version guard (the one legitimate version check)
  → autoloader
  → Application (composition root)
      → Environment → Config → Services
      → HttpKernelFactory → Kernel
  → Router (routes/web.php + routes/api.php)
  → Middleware pipeline
  → Controller
  → Response → ResponseEmitter
```

| Rule |
| --- |
| **Failures are loud.** A misconfigured system fails to boot rather than running degraded in silence (DP-6) |
| The pre-boot failure path in `public/index.php` must keep reporting conditions that occur before the application exists — wrong interpreter, missing autoloader (TR-149) |
| Configuration is read **exclusively** through the `Config` repository; only `Bulbula\Config` may read the environment (TR-192) |
| The environment is **configuration, never a code branch**. No `if (production)` scattered through the code (DP-5, EN-3) |

---

## 7. Web, API and Telegram as adapters

```text
          Browser            Telegram Mini App         Future Flutter
             │                       │                        │
             └───────────┬───────────┘                        │
                         │ HTTPS                              │
              ┌──────────▼────────────────────────────────────▼──┐
              │  HTTP · Router · Middleware                      │
              │  Controllers (HTML)        Controllers (JSON)    │
              │             └──────────┬──────────┘              │
              │             Application Services                 │
              │                   Domain                         │
              │                 Repositories                     │
              │     MariaDB · filesystem · outbound gateway      │
              └──────────────────────────────────────────────────┘
```

| Rule | Source |
| --- | --- |
| **One implementation of every rule.** Web, API and Telegram are adapters over the same application services | D-49, `architecture.md` §1.4 |
| The Web surface **does not call its own HTTP API** — it calls services in-process | TD-01 |
| Every behaviour reachable through `/api/v1` is the same service the Web surface uses | TR-21 |
| A difference that changes **what a User can do** is a product decision, not a platform adaptation | SCC-1.2 |
| The surface header is **informational and must not change domain behaviour** | SUR-6 |
| Telegram adaptations are confined to a **closed adapter inventory**; a tenth entry requires an owner decision | SCC-3.1 |

---

## 8. Error handling posture

| Rule | Source |
| --- | --- |
| `HttpException` subclasses render as themselves | TR-142 |
| Any other throwable is logged with a reference id and rendered as an **opaque 500 in production** | TR-142 |
| Error responses carry **no stack trace, no SQL, no file path, no internal identifier** in production | TR-144, EN-1 |
| Controllers throw; they do not catch for presentation | TR-143 |
| A dependency failure degrades **that feature only** | TR-147 |
| Database unavailability is a **hard failure** surfaced by readiness, with a safe error page | TR-211 |

---

## 9. Health

| Endpoint | Semantics |
| --- | --- |
| `/health`, `/api/v1/health` | **Liveness** — no dependencies touched |
| `/health/ready`, `/api/v1/health/ready` | **Readiness** — database reachable |

They must reflect **real dependency state** (TR-138, AC-13) and must not leak
internal detail — no versions, paths, hostnames or dependency strings
(TR-144, HK-2).

The deploy smoke test exercises: home page · `/health` · `/health/ready` ·
a 404 · a static asset (TR-190).

---

## 10. Audit as an architectural constraint

| Rule | Source |
| --- | --- |
| Every component performing a privileged action records it through Audit **in the same transaction as the change**, where the storage engine allows | TR-08 |
| **If the audit write fails, the action fails** | OPX-0.3 |
| The audit trail is **append-only**; no interface edits or deletes it | OPX-12.2 |
| Audit entries **survive Customer deletion** | OPX-12.4, TR-167 |

This is not a cross-cutting nicety bolted on later. It shapes where
transactions begin and end.

---

## 11. Checklist before adding a namespace

1. Which layer does it belong to? (§1)
2. Does anything in it perform I/O that the domain must not? (TR-05)
3. Does it need outbound HTTP? → it goes through the **gateway**, not `curl`.
4. Does it need the database? → only through a repository, only through
   `Bulbula\Database`.
5. **Have you added it to `tests/Arch/ArchTest.php`?** (TR-01, AC-1)
6. Is it `final`, `strict_types`, fully typed, 100 % covered?
7. Is every new rule in it implemented **once**, for all surfaces?
