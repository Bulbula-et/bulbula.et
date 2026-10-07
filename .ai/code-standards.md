# Code Standards

```text
Source baseline:   33f58e0e1619c7e5b952eede2382ae3c5e2ccf8c
Last derived from: 2026-10-07
Context status:    Current
```

**Derived from the actual repository**: `composer.json` · `pint.json` ·
`phpstan.neon.dist` · `rector.php` · `phpunit.xml.dist` · `infection.json5` ·
`phpinsights.php` · `.github/workflows/` · `tests/` · `src/`.
**The repository is the authority. Nothing here is invented.**

---

## 1. Language and autoloading

| Item | Value |
| --- | --- |
| PHP | **`^8.4`** — `composer.json` `require` |
| Required extensions | `ext-pdo`, `ext-pdo_mysql` |
| Dev extension | `ext-pdo_sqlite` |
| Autoloading | **PSR-4**, `Bulbula\` → `src/` |
| Test autoloading | `Tests\` → `tests/` |
| Runtime dependencies | `nikic/fast-route`, `monolog/monolog`, `psr/log`, `vlucas/phpdotenv` — **four** |

**Every file** begins:

```php
<?php

declare(strict_types=1);
```

Enforced twice: Pint (`declare_strict_types`) and the architecture test
(`source declares strict types`).

---

## 2. Formatting — Pint

Preset **`psr12`**, with these rules on top (`pint.json`):

| Rule | Effect |
| --- | --- |
| `declare_strict_types` | Mandatory strict types declaration |
| `strict_comparison` | `===` / `!==` only |
| `concat_space: one` | `'a' . 'b'`, spaced |
| `explicit_string_variable` | `"{$var}"`, not `"$var"` |
| `fully_qualified_strict_types` | Imports rather than inline FQCNs |
| `global_namespace_import` | Import classes, constants **and** functions |
| `array_push` | `$a[] = $x` over `array_push` |
| `backtick_to_shell_exec` | No backtick operator |
| `protected_to_private` | Prefer `private` |
| `ordered_class_elements` | Fixed member order (below) |
| `ordered_interfaces`, `ordered_traits` | Alphabetical |
| `single_trait_insert_per_statement` | One `use` per statement |
| `no_unreachable_default_argument_value: false` | Disabled |
| `not_operator_with_successor_space: false` | `!$x`, not `! $x` |

**Class member order:** `use_trait` → `case` → constants (public, protected,
private) → properties (public, protected, private) → `__construct` →
`__destruct` → magic → phpunit → abstract methods → public static → public →
protected static → protected → private static → private.

```bash
composer pint          # fix
composer test:pint     # check only (CI)
```

---

## 3. Static analysis — PHPStan

```yaml
level: max
paths: [src]
reportUnmatchedIgnoredErrors: true
```

| Rule |
| --- |
| **`level: max`.** It is not lowered |
| **Type coverage is 100 %**, enforced separately at `--min=100` |
| `reportUnmatchedIgnoredErrors` is on, so a stale ignore fails the build |
| A baseline file is **not** used. Do not introduce one |
| An `@phpstan-ignore` needs a written reason and is a last resort |

```bash
composer phpstan
```

---

## 4. Automated refactoring — Rector

Paths: `config/`, `public/`, `src/`, `tests/`.
PHP set: **8.4**. Prepared sets: `deadCode`, `codeQuality`, `earlyReturn`,
`typeDeclarations`, `privatization`.

One deliberate skip: `RemovePhpVersionIdCheckRector` in `public/index.php` —
the front controller's version guard is the one place a version check is not
dead code, because it must run on an older interpreter a misconfigured host
may hand the request to.

```bash
composer rector        # apply
composer test:rector   # dry-run (CI)
```

---

## 5. Testing — Pest 4

### Suites

| Suite | Path | Style | Note |
| --- | --- | --- | --- |
| `unit` | `tests/Unit` | PHPUnit-style | **This is what Infection mutates** |
| `feature` | `tests/Feature` | Pest-style | HTTP in, response out |
| `arch` | `tests/Arch` | Pest arch | Boundary enforcement |

Support: `tests/Pest.php`, `tests/TestCase.php`, `tests/Fixtures/`.

**Current baseline: 424 tests, 1 033 assertions, 100 % line coverage,
100 % type coverage.**

### Conventions

| Rule |
| --- |
| Test files end `Test.php` and mirror the `src/` path |
| **Behaviour lives in `tests/Unit`** or Infection cannot protect it (TST-5.5) |
| **No test reaches a real external service.** Outbound HTTP goes through the gateway interface and is substituted |
| **No test depends on wall-clock time.** The clock is injected (TR-05) |
| **No test depends on another test's state or ordering** |
| A test needing `sleep` is a design problem — `sleep` is banned in source |
| The suite runs against **in-memory SQLite** and needs no database |
| **No production personal data in any fixture.** No real name, phone, email or photograph (TST-8.1, TST-8.2) |
| **No credential, token or key in a fixture** (TR-198, AC-14) |

```bash
composer pest
composer pest:coverage        # --min=100
composer pest:type-coverage   # --min=100
composer pest:parallel        # 4 processes
composer pest:profiling
```

---

## 6. Mutation testing — Infection

```json5
minMsi: 100, minCoveredMsi: 100, testFramework: "phpunit", --testsuite=unit
```

| Rule |
| --- |
| **A surviving mutant is a weak test. Strengthen the test** |
| **Mutants are never suppressed, ignored or excluded to reach the score** |
| Thresholds are never lowered (D-26) |
| A genuinely equivalent mutant is **documented with its reasoning**, not silently configured away |
| Runs on `main`, nightly at 02:00 and on dispatch — **it does not gate a pull request today** |

```bash
composer infection
```

---

## 7. Quality commands

### The gate — run this before every commit

```bash
composer test
```

Which runs, in order:

```text
composer validate --strict      →  test:composer
vendor/bin/rector --dry-run     →  test:rector
vendor/bin/pint --test          →  test:pint
vendor/bin/phpstan analyse      →  phpstan
vendor/bin/pest                 →  pest
vendor/bin/pest --type-coverage --min=100
```

### The full gate

```bash
composer test:all     # test + test:insights + infection
```

### Individual

```bash
composer validate --strict
composer pint · rector · phpstan · phpinsights
composer pest · pest:coverage · pest:type-coverage · pest:parallel
composer infection
composer migrate · migrate:status · rollback · console · serve
```

### CI — `.github/workflows/`

| Workflow | Jobs |
| --- | --- |
| **`ci.yml`** | dependencies (`validate --strict`, `check-platform-reqs --lock`, `audit --locked`) · coding-standards · static-analysis (**matrix: highest + lowest**) · refactoring · tests (**matrix: highest + lowest**, `--bail`) · type-coverage (`--min=100`) · quality (PHPInsights over `src config public routes database bin`) · code-coverage (pcov, `--min=100`) |
| **`mutation.yml`** | Infection on `main`, nightly 02:00, dispatch. 30-min timeout, 14-day artifact. **Never gates a PR** |
| **`security.yml`** | `composer audit --locked` + **gitleaks 8.30.1** over the working tree **and full git history**, on PRs, `main`, and Mondays 03:00 |

**Never disable, skip or weaken any of these.**

---

## 8. Architecture tests

`tests/Arch/ArchTest.php` — 18 assertions, summarised in
`architecture-context.md` §3.

> **TR-01: every new namespace is added to these tests rather than left
> unguarded.** This is acceptance criterion AC-1. A namespace without
> assertions is an incomplete change.

---

## 9. Naming and typing

| Element | Convention |
| --- | --- |
| Namespace | `Bulbula\<Area>[\<SubArea>]`, PSR-4 to `src/` |
| Classes | `PascalCase`, **`final`** (except `Migration`, `HttpException`) |
| Controllers | Suffix **`Controller`**, in `Bulbula\Http\Controller` — enforced |
| HTTP exceptions | Suffix **`Exception`**, in `Bulbula\Http\Exception` — enforced |
| Middleware | Implements `Bulbula\Http\Middleware\Middleware` — enforced |
| Value objects | **`readonly`** (`Request`, `Response`, `DatabaseConfig` enforced) |
| Enums | Backed enums for closed sets (`Method`, `Status`, `HealthStatus` enforced) |
| Methods / properties | `camelCase` |
| Constants | `UPPER_SNAKE_CASE` |
| Migrations | `YYYY_MM_DD_HHMMSS_snake_case_description.php` |
| Routes | Named, dot-separated: `home`, `health.ready`, `api.v1.health.ready` |
| Config keys | Dot notation through the `Config` repository |

**Typing:** every parameter, return and property is typed. 100 % type
coverage is enforced. `mixed` needs a reason. Generics go in PHPDoc for
PHPStan at max.

---

## 10. Error handling

| Rule | Source |
| --- | --- |
| Controllers **throw**; the kernel renders. No catching for presentation | TR-143 |
| `HttpException` subclasses render as themselves | TR-142 |
| Any other throwable is logged with a reference id and rendered as an opaque 500 in production | TR-142 |
| Production responses carry **no stack trace, SQL, file path or internal identifier** | TR-144, EN-1 |
| A dependency failure degrades **that feature only** | TR-147 |
| Validation failures preserve user input | TR-148 |
| `ConfigurationException` for configuration failures; misconfiguration **fails the boot loudly** | DP-6 |

---

## 11. Logging

| Rule | Source |
| --- | --- |
| PSR-3 through `Bulbula\Logging\LoggerFactory` (Monolog) | — |
| Every line carries the **request correlation id**, the environment and a severity | TR-134 |
| **Never log** a credential, session id, bearer token, OTP code or full personal record | TR-135 |
| Operational events that need review are logged at a surfacing level: failed logins, authorization denials, moderation actions, campaign changes, email failures, job failures | TR-136 |
| Background jobs log start, end, outcome and item counts | TR-139 |
| Logs live in `storage/logs/`, **outside the document root**, never web-accessible | LG-1, PS-2 |
| **Logs are not a backup** | TR-224, BK-8 |
| Severity is used honestly — if everything is an error, nothing is | OBS-2.10 |

---

## 12. Configuration

| Rule | Source |
| --- | --- |
| Configuration is read **exclusively** through the `Config` repository | TR-192 |
| **Only `Bulbula\Config` may read the environment.** `Env` is confined there — enforced | ArchTest |
| No `getenv`, `putenv`, `$_ENV`, `$_SERVER`, `$_GET`, `$_POST` in `Http`, `Database` or service namespaces — enforced | ArchTest |
| Config files are `config/app.php`, `config/database.php`, `config/logging.php` — arrays built from env | — |
| A new variable is added to **`.env.example` in the same change** that uses it, or deployment fails at step 5 | REL-7.3 |
| Secrets live in `.env` on the server, **never committed, never logged** | SC-2, TR-186 |
| The environment is configuration, **never a code branch**. No `if (production)` scattered through code | DP-5 |

---

## 13. Database access

| Rule | Source |
| --- | --- |
| `PDO`, `PDOStatement`, `PDOException`, `mysqli`, `curl_init` **only in `Bulbula\Database`** — enforced | ArchTest |
| **Prepared statements only.** No string-concatenated SQL | TRD §9 |
| Repositories own their aggregate's tables and are never bypassed | `architecture.md` §2.1 |
| Controllers never touch the database — enforced | ArchTest |
| `Bulbula\Database` never depends on `Http`, `View` or `Console` — enforced | ArchTest |

---

## 14. Migrations

| Rule | Source |
| --- | --- |
| Schema changes **only** through migrations. No manual DDL, ever | MG-1, TR-174 |
| **Forward-only in production** — no automated down-migration against live data | MG-2 |
| **Run deliberately**, never automatically by deploy or cron | MG-3, TR-180 |
| **Re-runnable-safe**: already applied is a no-op, not an error | MG-4 |
| **Expand-then-contract** for breaking changes | MG-5 |
| A data-moving migration is **idempotent and resumable** | MG-6 |
| A migration never depends on application classes that may change | MG-8 |
| **Portable SQL**, or an engine-specific branch — the suite runs on SQLite | TR-173 |
| No business data beyond reference data the system needs | TR-177 |
| A backup is taken before running migrations in production | MG-7, BK-8 |

```bash
composer migrate · composer migrate:status · composer rollback
```

---

## 15. Routes

| Rule |
| --- |
| `routes/web.php` returns HTML (plus the operational JSON monitors poll) |
| `routes/api.php` returns JSON only — no HTML, no redirects |
| API routes are grouped under `/api/v1` and named `api.v1.*` |
| Route files **wire controllers and nothing more** — no SQL, no logic |
| Every route is **named**; URLs are generated through `UrlGenerator`, never hard-coded |
| Operations endpoints live under `/api/v1/ops/…` with separate authorization and rate-limit policy (TR-29) |

---

## 16. Controller and service responsibilities

| Controller may | Controller must not |
| --- | --- |
| Read input from `Request` | Contain SQL |
| Call **one** application service | Contain business rules |
| Map the result to a `Response` | Build HTML strings |
| Throw an `HttpException` | Catch exceptions for presentation |
| | Perform ownership or permission checks (those belong in the service — TR-38) |

| Application service must | Application service must not |
| --- | --- |
| Authorize | Know about HTTP |
| Validate | Know about sessions |
| Call domain logic | Know about templates |
| Persist through repositories | Read superglobals |
| Write the audit entry **in the same transaction** (TR-08) | — |
| Be callable from Web, API **and** CLI (TR-04) | — |

---

## 17. Views

| Rule | Source |
| --- | --- |
| Templates live in `resources/views/`, **outside the document root** | TR-184 |
| `Bulbula\View` uses **no database, no HTTP, no PDO** — enforced | ArchTest |
| Views receive prepared data only | `architecture.md` §2.1 |
| Server-rendered first; JavaScript enhances; core content works without it | D-52, NFR-C3 |
| The view layer may be replaced or extended — **Open (D-17)** | D-17 |

---

## 18. Commit and release discipline

| Rule | Source |
| --- | --- |
| **Work on a feature branch. Never directly on `main`** | Owner instruction |
| Every change reaches `main` through a **pull request** | Owner instruction |
| Logical commit structure, so history is readable and a rollback target is identifiable | Owner instruction |
| **Do not guess package versions or compatibility** — verify against the actual PHP requirement | Owner instruction |
| `composer.lock` is committed; deployments install from it | `deployment.md` §4 |
| A gitleaks finding means **rotation**, not merely removal from history | SC-5 |
| **Never patch a server by hand.** Change the repository and deploy | DP-7 |

Full process: `docs/45-quality/release-management-v1.0.md`.
