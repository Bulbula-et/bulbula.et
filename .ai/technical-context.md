# Technical Context

```text
Source baseline:   33f58e0e1619c7e5b952eede2382ae3c5e2ccf8c
Last derived from: 2026-10-07
Context status:    Needs review
```

**Derived from** `docs/30-technical/trd-v1.0.md` · `architecture.md` ·
`api-spec-v1.0.md` · `data-model.md` · `search-design.md` ·
`auth-identity.md` · `performance-and-caching.md` · `deployment.md` ·
`composer.json` · `composer.lock`.
**Authority:** those documents. This omits most of them deliberately.

---

## 1. Runtime

| Item | Value | Source |
| --- | --- | --- |
| Language | **PHP `^8.4`** | `composer.json` |
| Required extensions | `ext-pdo`, `ext-pdo_mysql` | `composer.json` |
| Test-only extension | `ext-pdo_sqlite` | `require-dev` |
| Package manager | **Composer 2** | — |
| Database | **MariaDB 10.6+** via PDO, `utf8mb4` / `utf8mb4_unicode_ci` | TRD §6.1 |
| Test database | **In-memory SQLite** | TRD §6.1 |
| Autoloading | **PSR-4**, `Bulbula\` → `src/` | `composer.json` |
| Hosting target | **Shared hosting (cPanel-class)** — no root, no daemon supervision, cron only | TR-189 |

### Runtime dependencies — exactly four

`nikic/fast-route` (routing) · `monolog/monolog` (PSR-3 logging) ·
`psr/log` · `vlucas/phpdotenv` (env parsing).

**Keeping this list tiny is the maintenance strategy.** Adding a fifth is a
decision, weighed against that baseline. 9 production packages are locked
(the four plus their transitive dependencies).

### Shared-hosting consequences

- No daemon, no supervisor, no always-on worker. **Scheduling is cron.**
- No root. No system-level installs.
- Disk and sending limits are real; jobs must be bounded (TR-151).
- Deployment is a Git checkout plus `composer install --no-dev`.

---

## 2. Architecture summary

Full detail in `architecture-context.md`. In one line:

```text
framework-free · PSR-4 · FastRoute · PDO · explicit construction
· thin controllers · application services · one front controller
```

| Property | Statement |
| --- | --- |
| **Framework-free** | No Laravel, Symfony full-stack, Laminas, Mezzio, Dotkernel, Slim, Flight. Enforced by `tests/Arch/ArchTest.php` |
| **No ORM or DBAL** | Repositories issue SQL through `Connection`. Enforced |
| **Explicit dependency construction** | `Bulbula\Foundation\Application` is a composition root, not a container. No auto-wiring, no annotation scanning, no reflection magic |
| **Thin controllers** | Read input, call **one** application service, render. No SQL, no rules, no HTML string building |
| **Application services** | One method per meaningful operation; callable from Web, API and CLI (TR-04) |
| **Domain is I/O-free** | No database, filesystem, network, or clock access except through an injected clock (TR-05) |
| **One front controller** | `public/index.php` — the only executable entry point in the document root (TR-09, AC-3) |

---

## 3. HTTP

### 3.1 Two route files, two contracts

| File | Contract | Exists today |
| --- | --- | --- |
| `routes/web.php` | HTML (and the operational JSON monitors poll) | `GET /`, `GET /health`, `GET /health/ready` |
| `routes/api.php` | JSON only; no HTML, no redirects | `GET /api/v1/health`, `GET /api/v1/health/ready` |

### 3.2 The central API decision — TD-01

> **The Web surface does not call its own HTTP API.**

Web controllers call application services **directly, in-process**. Routing
page rendering through an internal HTTP call would double latency on shared
hosting, duplicate auth handling and turn an internal call into a network
failure mode. Rejected explicitly.

**Every behaviour reachable through `/api/v1` must be the same application
service the Web surface uses** (TR-21).

### 3.3 API conventions

| Item | Rule |
| --- | --- |
| Base path | `/api/v1`, versioned from the first endpoint |
| Compatibility | **Additive only.** A breaking change introduces `/api/v2` (TR-23) |
| Content type | `application/json; charset=utf-8`, HTTPS only |
| Naming | Plural collections, stable slug or opaque id, sub-resources express containment, lowercase hyphenated, no extensions, no trailing slash |
| Operations namespace | **Separate path** `/api/v1/ops/…`, separate authorization and rate-limit policy (TR-29) |
| Query params | `snake_case`; **unknown parameters are rejected, not ignored** |
| Bodies | JSON objects; **unknown fields rejected** |
| Booleans | `true` / `false`, never `1` / `0` |
| Dates | ISO 8601 with offset; stored UTC, rendered for `Africa/Addis_Ababa` |
| Surface header | `X-Bulbula-Surface: web \| telegram` — **informational; must not change domain behaviour** (SUR-6) |
| Pagination | `page` + `per_page`, clamped to a documented maximum, bounded maximum offset. Offset-based in V1; keyset **Open (OT-08)** |
| Filtering | Explicit named parameters only — no generic query-object syntax |
| Sorting | `sort` with an allow-list; unknown values rejected |
| Not an export surface | No endpoint returns the whole directory (TR-30) |

### 3.4 Response envelope

```json
{ "data": [ ... ], "meta": { "page": 1, "per_page": 20, "total": 134, "has_more": true },
  "links": { "self": "...", "next": "...", "prev": null } }
```

Single resources return the object under `data`. `meta` and `links` are
omitted when they carry no information.

### 3.5 Error envelope

```json
{ "error": { "code": "validation_failed",
             "message": "The request could not be processed.",
             "request_id": "01JB2K7W3Q8X5R0M4D9F6T1C2A",
             "details": [ { "field": "rating", "code": "out_of_range", "message": "..." } ] } }
```

| Rule | Statement |
| --- | --- |
| E-1 | `code` is stable and machine-readable; clients branch on it, never on `message` |
| E-2 | `message` is safe: no stack trace, no SQL, no file path, no internal identifier (TR-144) |
| E-3 | `request_id` is always present and matches the log correlation id (TR-12) |
| E-4 | `details` appears only for validation failures |
| E-5 | Authentication failures **must not reveal whether an account exists** |
| E-6 | Rate-limit responses **must not disclose the limit** |

### 3.6 Status conventions

`200` read / write-returning-resource · `201` created + `Location` ·
`204` write with nothing to return · `304` conditional GET matched ·
`400` malformed (bad JSON, unknown field, wrong type) · `401` auth required ·
`403` authenticated but not permitted · **`404` also instead of `403` where
existence itself is privileged (TR-35)** · `405` + `Allow` ·
`409` conflict (duplicate, concurrent edit, overlapping Campaign) ·
`410` permanently removed · `422` validation failed · `429` + `Retry-After` ·
`500` opaque + correlation id · `503` dependency unavailable.

### 3.7 Request handling rules

| Rule | Source |
| --- | --- |
| Controllers **must not catch exceptions for presentation** — they throw, the kernel renders | TR-143 |
| `HttpException` subclasses render as themselves; any other throwable is logged with a reference id and rendered as an opaque 500 in production | TR-142 |
| User-facing error copy is non-technical and offers a route forward | TR-146 |
| Validation failures preserve user input | TR-148 |
| A dependency failure (maps, media, email, analytics) degrades **that feature only** | TR-147, TR-02 |
| Every request carries a correlation id, surfaced as `request_id` and in every log line | TR-12, TR-134 |

---

## 4. Authentication and authorization

Detail in `security-privacy-context.md` §1–§2 and
`docs/30-technical/auth-identity.md`.

| Item | V1 |
| --- | --- |
| Customer providers | **Google** and **email OTP** only (D-48) |
| Passwords | **None.** Not stored, not hashed, not supported |
| Staff auth | Stronger than Customer; **must not be email OTP alone**. Strength **Open (D-45)** |
| Telegram | Launch context validated **server-side**; establishes a **surface, not an identity** (TR-112, TR-113) |
| Authorization | **Server-side on every request.** Hidden UI is presentation, never protection (TR-33) |
| Guest-safe capabilities | Require no session and **must not be degraded** (TR-34) |
| Ops endpoints | Deny Guests and Customers **without distinguishing forbidden from not-found** (TR-35) |
| Permission model | **Named permissions in data**, not hard-coded roles — the split is **Open (D-14)** (TR-37) |
| Ownership checks | In the **application service**, never the controller (TR-38) |
| Business role | **Does not exist.** No permission may be granted to a Business (TR-40, D-54) |

---

## 5. Search

| Item | V1 |
| --- | --- |
| Engine | **MariaDB.** No Elasticsearch, no external search service |
| Mechanism | A **maintained search document**, rebuildable from source tables by a console command, updated when a Listing, Category, Area or Alias changes (TR-45) |
| Multilingual | Amharic script and Latin transliteration matched through **controlled Aliases, never machine translation** (TR-44, D-18) |
| Filters | Explicit: category, area, open_now, min_rating, verified, distance |
| Ranking inputs | Only those listed in PRD §16.2. **The ranking pipeline must not read any Advertising table** (TR-41) |
| Ranking weights | **None defined in V1.** Weights are tunable configuration produced from real query data, not a constant invented in documentation (TR-42, D-09) |
| Sponsored | Selected by a **separate code path**, merged into documented slots **after organic ordering is final**, so removing all Campaigns leaves organic order byte-identical (TR-43) |
| Zero results | Recorded for operational review (TR-46, SRCH-8) |
| Bounds | Maximum candidate set, maximum page size, maximum offset — no query may degrade the shared host (TR-47) |
| Abstraction | Search is a **port with one V1 adapter (MariaDB)**, so a future engine can be introduced without touching application services (TR-48) |
| Refresh strategy | Synchronous on write, queued, or both — **Open (OT-04)** |

**No mandatory search engine. Adding one is a decision, not an optimisation.**

---

## 6. Background work

| Item | V1 |
| --- | --- |
| Scheduling | **Cron** on the shared host, invoking the PHP 8.4 binary explicitly |
| Entry point | `bin/console` — the only file permitted to call `exit()` |
| Queue | Database-backed where needed. **No broker** (NG-8) |
| Idempotency | Every job is **runnable manually, idempotent, and safe to run concurrently or not at all** (TR-150) |
| Bounds | Maximum item count **and** wall-clock budget per run, so a cron window cannot be exceeded (TR-151) |
| Resumability | A job that cannot finish leaves consistent state and resumes next run (TR-152) |
| Overlap | Prevented by a lock whose expiry cannot deadlock the schedule (TR-153) |
| **The critical rule** | **No product behaviour may depend on a job having run** (TR-154). Campaign start/stop is evaluated at request time (TR-71); verification age is derivable by query (TR-54) |
| Observability | Start, end, outcome and item counts recorded; failures visible to Staff (TR-139, TR-155) |
| Email | Tolerates provider outages with retry and a failure state — **never an infinite retry loop** (TR-156) |

---

## 7. Caching

**HTTP first. Application cache only where measurement justifies it.**

| Layer | V1 approach | Invalidation |
| --- | --- | --- |
| HTTP (public GETs) | `Cache-Control` short shared max-age + `ETag` / `Last-Modified` | Time + content change |
| Static assets | Long max-age with **content-hashed filenames** | Filename change |
| Application cache | **Filesystem-backed**, keyed and versioned; only where justified | Explicit on write + TTL |
| Derived tables (rating summary, search document, rollups) | Maintained on write, rebuildable by console command | Explicit |
| Per-request memoisation | Within one request only | End of request |

| Rule | Source |
| --- | --- |
| Authenticated and staff responses are **`no-store`** | TR-120, TR-27 |
| **Any cache must be optional**: cold or disabled, behaviour is identical and only slower | TR-121 |
| Cache keys include **every** input that changes the output — area, category, filters, page, surface | TR-122 |
| **No personal data in a shared cache**; no cached fragment specific to one Customer | TR-123 |
| A Listing state change invalidates the cached representations containing it | TR-124 |

**No mandatory Redis.** The backend choice (filesystem vs database table) is
**Open (OT-03)**, to be settled by measurement during build.

---

## 8. Data access

| Rule | Source |
| --- | --- |
| **Relational MariaDB model.** No ORM, no DBAL, no active record | ArchTest |
| **`PDO`, `PDOStatement`, `PDOException`, `mysqli`, `curl_init` only inside `Bulbula\Database`** | ArchTest |
| **Prepared statements only.** No string-concatenated SQL | TRD §9 |
| Repositories own their aggregate's tables and are never bypassed | `architecture.md` §2.1 |
| Schema changes **only** through migrations — no manual DDL, ever | MG-1, TR-174 |
| Migrations are **forward-only in production** | MG-2 |
| Migrations are **run deliberately**, never automatically by deploy or cron | MG-3, TR-180 |
| Migrations are re-runnable-safe (already applied is a no-op) | MG-4 |
| **Expand-then-contract** for breaking changes | MG-5 |
| Migrations stay in **portable SQL** (or branch by engine) because tests run on SQLite | TR-173 |
| A migration must not carry business data beyond reference data the system needs | TR-177 |
| A migration never depends on application classes that may change | MG-8 |
| Referential rules are explicit; deleting a Customer **must not** silently delete audit or moderation history | TR-167 |
| Soft deletion only where the product requires history (Listings, Reviews, audit); elsewhere deletion is real | TR-171 |

Entity map: `docs/30-technical/data-model.md` §2 (Directory · Operations ·
Identity · Community · Advertising · Platform), §9 data classification,
§10 entities deliberately **not** created.

---

## 9. Outbound HTTP — one gateway

The architecture test confines `curl_init` to `Bulbula\Database`, which was
written when MariaDB was the only outbound dependency. V1 adds real ones:
Google token verification, the email provider (D-41), the Telegram Bot API
(D-33).

**The answer is a single outbound HTTP gateway** — one narrow namespace that
owns the transport and is the only place a client library may live (TD-07).

| Rule | Source |
| --- | --- |
| Exactly **one** namespace owns outbound HTTP; everything else reaches it through an interface | TR-01a |
| The architecture test is **extended to name that namespace**, so the confinement stays enforced rather than widened | TR-01b |
| Every outbound call has a **timeout, a bounded retry policy and a defined degraded behaviour** | TR-01c |
| **No outbound HTTP call may occur in a page-render path.** Outbound work belongs in console commands and queued jobs | TR-01d, TR-205 |
| Credentials arrive from configuration only and must not appear in logs | TR-01e |

> **Relaxing the architecture test to allow `curl_init` anywhere is
> explicitly rejected.**

---

## 10. Open technical decisions

| ID | Question | Resolution point |
| --- | --- | --- |
| **OT-01** | Session lifetime, idle timeout, rotation values | `auth-identity.md` + security phase |
| **OT-02** | OTP code length, validity window, attempt and request limits | Security phase |
| **OT-03** | Application cache backend: filesystem vs database table | Measurement during build |
| **OT-04** | Search document refresh: synchronous on write, queued, or both | `search-design.md` during build |
| **OT-05** | CSP `frame-ancestors` admitting the Telegram host without weakening the Web policy | Platform phase |
| **OT-06** | Backup frequency, retention, off-host location | Operations, constrained by D-42 |
| **OT-07** | Whether any observability tooling beyond logs is introduced | Post-launch; **V1 needs none** |
| **OT-08** | Pagination style: offset vs keyset | `api-spec-v1.0.md` during build |
| **OT-09** | Whether derivative media generation is synchronous or queued | Build |

**Rule:** an open item is a visible hole. Implementation must either resolve
it through the decision process and record it in the register, or keep it
visibly open. **Silent resolution in code is a process failure** (TRD §40).

---

## 11. What V1 deliberately does not add

No framework · no ORM · no DI container · no message broker · no Redis ·
no Elasticsearch · no SPA · no microservices · no external APM ·
no payment gateway · no native client · no offline store.

Each is a recorded choice, not an oversight. Adding one requires a decision.
