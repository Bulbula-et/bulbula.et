# Bulbula — Technical Requirements Document V1

## 1. Document control

| | |
| --- | --- |
| **Document** | Technical Requirements Document — Bulbula V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | `docs/architecture.md` and `docs/deployment.md` as the top-level technical reference (both relocated to `docs/30-technical/`) |

### 1.1 Purpose

The TRD is the technical equivalent of the PRD. It translates the approved
product requirements into an architecture precise enough that implementation
can proceed without rediscovering the design, and without re-litigating
decisions that are already made.

It specifies **what the system must be**, not the code that implements it.

### 1.2 Companion technical documents

| Document | Authority over |
| --- | --- |
| [`architecture.md`](architecture.md) | Canonical component architecture, layering and boundaries |
| [`data-model.md`](data-model.md) | Conceptual and logical data model |
| [`api-spec-v1.0.md`](api-spec-v1.0.md) | The `/api/v1` contract |
| [`auth-identity.md`](auth-identity.md) | Authentication and identity design |
| [`search-design.md`](search-design.md) | Search and ranking pipeline |
| [`performance-and-caching.md`](performance-and-caching.md) | Performance budget and caching strategy |
| [`deployment.md`](deployment.md) | Deployment, environments and operations |

**No two technical documents claim authority over the same subject.** Where
this TRD summarises a companion document, the companion document is
authoritative for the detail and the TRD is authoritative for the constraint.

---

## 2. Authority and precedence

```text
Owner decisions
    ↓
docs/60-decisions/decision-register.md        ← authority for decisions
    ↓
docs/10-product/prd-v1.0.md                   ← authority for requirements
    ↓
this TRD and the documents in docs/30-technical/   ← authority for design
    ↓
UX / platform specifications
    ↓
implementation
```

| Rule | Statement |
| --- | --- |
| A-1 | This document **MUST NOT** contradict an approved `D-xx` decision. If it appears to, this document is wrong |
| A-2 | This document **MAY** resolve implementation-level questions the register deliberately leaves open, and records each as a **technical decision (TD-xx)** |
| A-3 | A TD **MUST NOT** change product behaviour. If a technical choice would change what a User can do, it is a product decision and belongs in the register |
| A-4 | Unresolved items are marked `Open — technical decision`, `Open — implementation detail`, `PENDING COUNSEL` or `PENDING PILOT`, and are listed in §40 |
| A-5 | Every significant requirement cites its source: a `D-xx` decision, a `C-xx` capability, or a PRD section |

### 2.1 Notation

| Marker | Meaning |
| --- | --- |
| **TR-xx** | A technical requirement of this document |
| **TD-xx** | A technical decision taken by this document under A-2 |
| **OT-xx** | An open technical question, listed in §40 |
| **MUST / SHOULD / MAY** | As in the PRD §1.3 |
| **[P]** | Proposed value, not approved |

---

## 3. Technical goals

| ID | Goal | Source |
| --- | --- | --- |
| G-1 | Deliver all 40 approved capabilities (C-01…C-40) on one backend serving two client surfaces | D-01, D-49 |
| G-2 | Run correctly and affordably on low-cost shared hosting, with no mandatory external infrastructure service for core operation | PRD NFR-SC1, deployment reality |
| G-3 | Stay framework-free plain PHP, extending the Phase 1 foundation rather than replacing it | Standing architectural rule; `tests/Arch/ArchTest.php` |
| G-4 | Serve fast, crawlable, server-rendered HTML on mid-range Android devices over constrained networks | D-52, C-37, PRD NFR-P1 |
| G-5 | Keep one implementation of every domain rule, shared by Web, the Telegram Mini App and any later client | D-49, PRD FL-2 |
| G-6 | Make listing provenance, verification and every privileged action auditable by construction | C-21, C-29, D-50 |
| G-7 | Keep organic ranking computationally and structurally separate from paid placement | D-10, PRD RANK-2 |
| G-8 | Collect and store the minimum personal data, classified, locatable and deletable | D-51, C-36, C-40 |
| G-9 | Keep the system comprehensible: explicit construction, no hidden machinery, readable request flow | Phase 1 principles |
| G-10 | Preserve the existing quality gates — static analysis, 100% coverage, mutation testing — as the domain grows | D-26 (scope open), CI workflows |

---

## 4. Technical non-goals

| ID | Non-goal | Reason |
| --- | --- | --- |
| NG-1 | A full-stack framework (Laravel, Symfony, Laminas, Mezzio, Slim, Flight) | Standing architectural rule; enforced by architecture test |
| NG-2 | A single-page application or a JavaScript front-end framework | SPA rejected (D-16 context); server-rendered required by C-37 and D-52 |
| NG-3 | A separate search cluster (Elasticsearch, OpenSearch, Meilisearch, Typesense) | MariaDB-backed search is the V1 baseline (§16) |
| NG-4 | A message broker (Kafka, RabbitMQ, SQS) | Cron-driven work is sufficient at V1 volume (§28) |
| NG-5 | An in-memory cache service (Redis, Memcached) as a V1 requirement | Not available on the target hosting; §24 must work without it |
| NG-6 | Microservices, containers as a deployment requirement, Kubernetes | One deployable unit (§32) |
| NG-7 | An ORM, query builder or Active Record layer | Enforced by architecture test; PDO with prepared statements |
| NG-8 | A service container, auto-wiring, annotation scanning or reflection-based magic | Phase 1 principle; explicit composition |
| NG-9 | A native mobile client | Flutter is a later client (D-15, D-15r) |
| NG-10 | Horizontal scaling, multi-region, read replicas | Not justified by V1 volume; §37 records the triggers instead |
| NG-11 | A payment gateway integration | No payment processing in V1 (D-10, D-11) |
| NG-12 | Third-party analytics or advertising SDKs | PRD NFR-PR4; no behavioural tracking |

---

## 5. System scope

### 5.1 In technical scope for V1

| Area | Capabilities |
| --- | --- |
| Public discovery web application, server-rendered | C-01 … C-18 |
| Telegram Mini App surface on the same backend | C-01 … C-18 (except C-37), D-49 |
| Operations console, Web only, staff only | C-19 … C-29 |
| Customer accounts and identity | C-30 … C-36 |
| Cross-cutting: SEO, analytics, notifications, privacy | C-37 … C-40 |
| Public JSON API under `/api/v1` serving both surfaces | D-49 |
| Scheduled background work (rollups, queues, freshness) | C-28, C-39 |

### 5.2 Out of technical scope

Everything in `scope-v1.md` §2, plus the infrastructure non-goals in §4. In
particular, **no component may be built that only makes sense once business
accounts exist** (D-54, PRD H-4). Seams are permitted; features are not.

### 5.3 Deliberate consequence of the product model

Because businesses have no accounts (D-54) and cannot edit their data (D-02),
there is **no untrusted write path from the business side at all**. The only
writers in V1 are:

| Writer | Scope of writes |
| --- | --- |
| Staff (Operator, Administrator) | All Listing, taxonomy, media, moderation and Campaign data |
| Customer | Own Reviews, own Saves, own profile, own account deletion |
| Guest | Reports only (rate-limited, no stored identity) |
| The system | Events, rollups, notifications, audit entries |

This materially reduces the V1 attack surface and simplifies authorization
(§15).

---

## 6. Existing foundation

Phase 1 delivered a working, tested, framework-free foundation. **V1 extends
it; it does not replace it.** The TRD takes the following as given.

### 6.1 Runtime and dependencies

| Item | Value |
| --- | --- |
| Language | PHP `^8.4` (`composer.json`) |
| Required extensions | `ext-pdo`, `ext-pdo_mysql` |
| Autoloading | PSR-4, `Bulbula\` → `src/` |
| Runtime libraries | `nikic/fast-route`, `monolog/monolog`, `psr/log`, `vlucas/phpdotenv` — four, all single-purpose |
| Database | MariaDB via PDO, `utf8mb4` / `utf8mb4_unicode_ci` |
| Test database | In-memory SQLite (`ext-pdo_sqlite`, dev only) |

### 6.2 Directory layout (unchanged)

```text
bin/console            CLI entry point (migrate, rollback, migration:status)
config/                app.php, database.php, logging.php — arrays from env
database/migrations/   ordered, reversible migrations
docs/                  documentation
public/                document root: index.php, .htaccess, assets
resources/views/       templates, outside the document root
routes/                web.php, api.php
src/                   PSR-4 source
storage/               runtime artefacts (logs), git-ignored contents
tests/                 Unit, Feature, Arch
```

### 6.3 Existing components

| Namespace | Responsibility | V1 impact |
| --- | --- | --- |
| `Bulbula\Foundation` | `Application` composition root, `Environment`, `Services`, `HttpKernelFactory` | Extended with new services |
| `Bulbula\Config` | Immutable dot-notation config repository | Extended with new config files |
| `Bulbula\Http` | Request, Response, Status, Method, Kernel, emitter, error handler | Extended with middleware |
| `Bulbula\Http\Routing` | Route registration, FastRoute matching, named URLs | Extended with new routes |
| `Bulbula\Http\Middleware` | Contract, `Pipeline`, `SecureHeaders` | Extended (§15, §34) |
| `Bulbula\Http\Controller` | Thin HTTP boundary | Extended per capability |
| `Bulbula\Database` | `Connection`, `ConnectionFactory`, `DatabaseConfig`, exceptions | Reused as-is |
| `Bulbula\Database\Migrations` | Locator, repository, migrator | Reused as-is (§31) |
| `Bulbula\Console` | Command runner behind `bin/console` | Extended with scheduled commands (§28) |
| `Bulbula\Diagnostics` | Health checks and report | Reused; extended checks |
| `Bulbula\Logging` | PSR-3 Monolog factory | Reused (§26) |
| `Bulbula\View` | `PageRenderer` for static pages | Replaced or extended by the view layer — **Open (D-17)** |
| `Bulbula\Error`, `Bulbula\Exception`, `Bulbula\Support` | Error promotion, configuration failures, typed env reader | Reused as-is |

### 6.4 Constraints the architecture tests already enforce

These are not aspirations; `tests/Arch/ArchTest.php` fails the build on
violation, and **V1 code must satisfy them**:

| Enforced rule | Consequence for V1 design |
| --- | --- |
| No framework namespaces (`Illuminate`, `Laravel`, `Symfony\Component\HttpKernel`, `Laminas`, `Mezzio`, `Slim`, `Flight`) | New capabilities use plain PHP and the four approved libraries |
| No ORM or DBAL (`Doctrine`, `Illuminate\Database`, `Propel`, `Cycle`) | Repositories issue SQL through `Connection` |
| `PDO`, `PDOStatement`, `PDOException`, `mysqli`, `curl_init` only inside `Bulbula\Database` | **External HTTP calls must be wrapped**, see TD-07 below |
| `Bulbula\Http` contains no SQL | Controllers and middleware never query |
| Controllers never use `Bulbula\Database` or `PDO` | Controllers depend on application services only |
| Controllers are suffixed `Controller`; HTTP exceptions suffixed `Exception` | Naming is mandatory |
| `Bulbula\Database` independent of `Http`, `View`, `Console` | Data access is presentation-agnostic |
| Service namespaces independent of `Http`, `View`, `Console` | Domain and application services are HTTP-free |
| `Bulbula\View` uses no database, no HTTP, no PDO | Views receive prepared data only |
| `Env` is used only inside `Bulbula\Config`; `Http`/`Database`/service layers never read superglobals or `getenv` | All settings arrive through `Config` |
| All source classes are `final`, strict types everywhere; `Request`, `Response`, `DatabaseConfig` readonly | Value objects are immutable |
| No `dd`, `dump`, `die`, `var_dump`, `sleep`, `exit` in source | `bin/console` is the only file that may `exit()` |

**TR-01.** Every new namespace introduced in V1 **MUST** be placed on the
correct side of these boundaries, and the architecture test suite **MUST** be
extended to cover the new namespaces rather than leaving them unguarded.

**TD-07 — Outbound HTTP is a wrapped capability, not an ambient one.** The
enforced rule above confines `curl_init` to `Bulbula\Database`, which was
written for a system whose only outbound dependency was MariaDB. V1 adds
genuine outbound calls: Google token verification (§14), the email provider
(§21, D-41), and the Telegram Bot API (§23, D-33). Rather than relax the
architecture test, V1 introduces a **single outbound HTTP gateway** —
one narrow namespace that owns the transport, and the only place where a
client library may live. Every integration depends on an interface it
provides, never on the transport directly.

| TR | Requirement | Source |
| --- | --- | --- |
| TR-01a | Exactly **one** namespace **MUST** own outbound HTTP; every other namespace reaches it through an interface | TD-07 |
| TR-01b | The architecture test **MUST** be extended to name that namespace, so the confinement stays enforced rather than widened | TR-01 |
| TR-01c | Every outbound call **MUST** have a timeout, a bounded retry policy, and a defined degraded behaviour when the dependency is unavailable | TR-205 |
| TR-01d | **No outbound HTTP call may occur in a page-render path** (TR-205). Outbound work belongs in console commands and queued jobs (TD-06) | TR-205, TD-06 |
| TR-01e | Credentials for outbound calls arrive from configuration only (TR-189) and **MUST NOT** appear in logs (TR-25) | TR-189 |

**Relaxing the architecture test to allow `curl_init` anywhere is
explicitly rejected.** The rule is weakened by that change, and weakening
an enforced boundary to accommodate a foreseeable requirement is the
failure mode the boundary exists to prevent.

---

## 7. Architectural principles

| ID | Principle | Rationale |
| --- | --- | --- |
| P-1 | **Framework-free, library-light.** Plain PHP with a small number of single-purpose libraries | Standing rule; keeps the deployable small and the behaviour legible on shared hosting |
| P-2 | **Explicit composition.** Dependencies are constructed by hand in a composition root and passed in | No container, no auto-wiring; a reader can follow every dependency |
| P-3 | **Layered, one direction.** HTTP → application → domain → persistence. Inner layers never know about outer layers | Enforced by architecture tests |
| P-4 | **One implementation of every rule.** Web, API and Telegram share application services; nothing is reimplemented per surface | D-49, G-5 |
| P-5 | **Server-rendered first.** HTML is produced on the server; JavaScript enhances, never enables | C-37, D-52, NG-2 |
| P-6 | **The database is the system of record.** State lives in MariaDB; caches and derived structures are rebuildable | Shared hosting; no durable external store |
| P-7 | **Boring persistence.** Explicit SQL with bound parameters, explicit indexes, explicit transactions | NG-7; predictable performance |
| P-8 | **Auditability by construction.** A privileged action that cannot be recorded does not happen | C-29, G-6 |
| P-9 | **Privacy by construction.** Personal data is classified at the schema level, minimised, locatable and deletable | D-51, C-36 |
| P-10 | **Fail loudly in private, quietly in public.** Operators get detail and references; Users get safe messages | Existing `ErrorHandler` behaviour |
| P-11 | **Deterministic, reversible deployment.** The repository is the source of truth; nothing exists only on a server | §32 |
| P-12 | **Degrade, don't collapse.** Maps, media, analytics, email and search auxiliaries may fail without taking discovery down | PRD NFR-A1 |
| P-13 | **Simplest thing compatible with the PRD.** Complexity must be justified by a requirement, not by anticipation | G-2, NG-10 |

---

## 8. System context

```text
                        ┌──────────────────────────────┐
   Guest / Customer ───▶ │  Web (mobile-first HTML)     │
                        └──────────────┬───────────────┘
                                       │
   Telegram user  ─────▶ ┌─────────────▼───────────────┐
                        │  Telegram Mini App (web)     │
                        └──────────────┬───────────────┘
                                       │  HTTPS
   Staff         ──────▶ ┌─────────────▼───────────────┐
                        │  Operations console (HTML)   │
                        └──────────────┬───────────────┘
                                       │
                        ┌──────────────▼───────────────┐
                        │     Bulbula application       │
                        │  one PHP deployable unit      │
                        └───┬───────┬───────┬───────┬──┘
                            │       │       │       │
                   MariaDB ─┘       │       │       └─ Local filesystem
                                    │       │           (logs, cache, uploads)
                  Google Identity ──┘       └── Transactional email provider
                                                 (Open — D-41)
                        ┌──────────────────────────────┐
                        │  Google Maps (client-side)   │  Open — D-21
                        │  Object storage / CDN        │  Open — D-25, D-42
                        │  Telegram platform            │  validates Mini App context
                        └──────────────────────────────┘
```

### 8.1 External dependencies and failure posture

| Dependency | Used for | Required for core discovery? | Failure behaviour | Decision |
| --- | --- | --- | --- | --- |
| MariaDB | System of record | **Yes** | Hard failure; readiness probe reports unhealthy | — |
| Google Identity | Customer sign-in | No | Email OTP remains available (C-31) | D-48 |
| Transactional email | OTP delivery, notifications | No, but blocks new email sign-ins | Queue, retry, alert Staff | D-24, D-41 |
| Google Maps | Map view and directions hand-off | No | Static fallback: address, Area, landmark, open-in-maps link (C-10) | D-21 |
| Object storage / CDN | Media delivery | No | Media missing; pages render without broken frames (C-22) | D-25, D-42 |
| Telegram platform | Mini App host and context validation | No (Web unaffected) | Mini App unavailable; Web unaffected | D-15, D-33 |

**TR-02.** No external dependency may sit on the critical path of public
discovery. Search, browsing, profiles and contact actions **MUST** work when
every external service in the table above is unavailable (P-12).

---

## 9. Major application components

Components are described by responsibility. Namespaces are indicative; the
canonical component map is in [`architecture.md`](architecture.md).

### 9.1 Component map

| # | Component | Responsibility | Depends on |
| --- | --- | --- | --- |
| 1 | **Foundation** | Boot, configuration, environment, logging, error handling, service composition | — |
| 2 | **HTTP kernel** | Request/response, routing, middleware pipeline, emission, error rendering | Foundation |
| 3 | **Web controllers** | HTML endpoints for discovery, accounts and the operations console | Application services, View |
| 4 | **API controllers** | JSON endpoints under `/api/v1` | Application services |
| 5 | **View layer** | Server-rendered templates, escaping, layout, partials | — (receives prepared data) |
| 6 | **Application services** | Use-case orchestration: one service method per meaningful operation | Domain, repositories |
| 7 | **Domain** | Entities, value objects and rules: Listing state, verification, review states, campaign eligibility, ranking inputs | — |
| 8 | **Repositories** | SQL persistence and retrieval, one per aggregate | Database |
| 9 | **Search** | Query parsing, candidate retrieval, ranking pipeline, suggestion lookup | Repositories, Database |
| 10 | **Identity** | Authentication flows, sessions, provider identities, OTP issuance and verification | Repositories, Mailer |
| 11 | **Authorization** | Actor resolution and permission checks | Identity |
| 12 | **Media** | Upload intake, validation, derivative generation, storage adapter | Storage adapter |
| 13 | **Mailer** | Message composition and dispatch through a provider adapter | Provider adapter, queue |
| 14 | **Advertising** | Campaign eligibility, placement selection, delivery recording | Repositories |
| 15 | **Analytics** | Event recording and scheduled aggregation | Repositories |
| 16 | **Audit** | Append-only recording of privileged actions | Repositories |
| 17 | **Telegram adapter** | Mini App context validation and surface-specific presentation | Identity, View |
| 18 | **Console** | CLI commands: migrations, scheduled jobs, maintenance | Application services |
| 19 | **Diagnostics** | Liveness and readiness checks | Database, storage |

### 9.2 Component rules

| ID | Rule |
| --- | --- |
| TR-03 | A controller (3, 4) **MUST** contain no business rules, no SQL and no HTML construction; it validates shape, calls one application service, and renders |
| TR-04 | An application service (6) **MUST** be callable from Web, API and CLI without modification, and **MUST NOT** reference `Bulbula\Http` |
| TR-05 | Domain logic (7) **MUST** be free of I/O: no database, no filesystem, no network, no clock access except through an injected clock |
| TR-06 | A repository (8) **MUST** be the only path to its aggregate's tables; no other component issues SQL against them |
| TR-07 | External services (Google, email, storage, Telegram, maps) **MUST** be reached through an adapter interface defined by the application layer, with the concrete adapter constructed in the composition root |
| TR-08 | Every component that performs a privileged action **MUST** record it through Audit (16) in the same transaction as the change where the storage engine allows it |

---

## 10. Request lifecycle

### 10.1 Web (HTML) request

```text
Apache (public/.htaccess)
  └─ rewrite non-file, non-directory → public/index.php
       └─ pre-boot guards        PHP version, vendor/autoload.php present
       └─ Application::boot()    .env → Config → Environment → timezone → logger → error handler
       └─ Services::for…()       explicit construction of shared collaborators
       └─ HttpKernelFactory      Router ← routes/web.php, routes/api.php
       └─ Request::fromGlobals()
       └─ Kernel::handle()
             ├─ global pipeline   SecureHeaders → RequestId → Session → …
             ├─ Router::match()   FastRoute: found | 405 | 404
             ├─ route pipeline    auth, CSRF, rate limit, surface adapter
             └─ handler           controller → application service → domain → repository
       └─ ResponseEmitter::emit()
```

### 10.2 API (JSON) request

Identical, except that the route is matched under `/api/v1`, the route
pipeline applies token authentication instead of session authentication where
required, and errors render as JSON. `Request::expectsJson()` already
distinguishes the two in exactly one place.

### 10.3 Lifecycle requirements

| ID | Requirement |
| --- | --- |
| TR-09 | There **MUST** remain exactly one executable entry point in the document root (`public/index.php`), as the existing feature test asserts |
| TR-10 | The pre-boot guards in `public/index.php` (interpreter version, autoloader presence) **MUST** be preserved; they report conditions that occur before any handler exists |
| TR-11 | Global middleware **MUST** wrap error rendering so that a 500 response still carries security headers |
| TR-12 | Every request **MUST** be assigned a correlation identifier at the outermost middleware, included in every log line for that request, and returned to the client in a response header and in error payloads (§26, §27) |
| TR-13 | A handler **MUST** return a `Response`; it **MUST NOT** emit output directly |
| TR-14 | Session establishment **MUST NOT** occur for Guest requests that do not need it — an anonymous discovery request creates no session and sets no cookie beyond what is strictly required (§35) |
| TR-15 | Request handling **MUST** be stateless between requests apart from the session store and the database; no in-process state may be assumed to survive |

---

## 11. Domain boundaries

The domain is divided into six bounded areas. Each owns its tables, its
invariants and its vocabulary (which is the glossary's vocabulary).

| Area | Owns | Core invariants |
| --- | --- | --- |
| **Directory** | Business, Branch, Listing state, Category, Subcategory, Alias, Area, Sub-city, Landmark, media association | A Business has ≥ 1 Branch (D-03); a Listing is published only if permissioned and verified (C-19, C-21) |
| **Operations** | Permission records, provenance, verification records, corrections, reports, work queues | No publication without a linked Permission record; every state change attributable |
| **Identity** | Customer, provider identities, sessions, OTP state, staff users, roles | One person → one Customer identity (C-32); no passwords (D-48) |
| **Community** | Reviews, ratings, saves, review reports, moderation records | Only authenticated Customers write Reviews (D-12); only published Reviews affect the rating summary |
| **Advertising** | Package, Placement, Campaign, targeting, delivery measurements | A Campaign serves only when approved, in period, and its Business is eligible (C-27, ADV-10) |
| **Platform** | Audit log, analytics events and rollups, notifications and delivery state, background jobs, cache entries | Audit entries are append-only; analytics never identifies a Guest |

### 11.1 Boundary rules

| ID | Rule |
| --- | --- |
| TR-16 | A component **MUST NOT** read another area's tables directly; it goes through that area's repository or application service |
| TR-17 | Cross-area operations **MUST** be orchestrated in an application service, not inside a domain entity |
| TR-18 | **Advertising MUST NOT be able to influence Directory or Community state**, and the ranking pipeline **MUST NOT** read Advertising tables (D-10, PRD RANK-2, G-7) |
| TR-19 | Community (Reviews) depends on Directory and Identity, never the reverse: a Business is not aware of its Reviews beyond a derived rating summary |
| TR-20 | Platform services are infrastructure for the other areas and **MUST NOT** contain domain rules |

---

## 12. Data ownership principles

| ID | Principle | Source |
| --- | --- | --- |
| DO-1 | **Bulbula owns all Listing data.** There is no owner-submitted write path in V1 | D-02, D-54 |
| DO-2 | **A Customer owns their own contributions**: Reviews, Saves, profile. They may edit and delete within the published policy | C-35, C-36 |
| DO-3 | **Every published fact has recorded provenance**: source, method, collecting Operator, date, Permission reference | PRD PROV-1, D-50 |
| DO-4 | **Operational information is never published**, except the verification date surfaced by C-12 | PRD PROV-2 |
| DO-5 | **Classification is by subject, not by location.** A field is personal data because it relates to an identifiable natural person, wherever it sits | D-51, PRD PRIV-3 |
| DO-6 | **Personal contact points are flagged at the schema level**, so a rights request can locate them precisely | PRD PCP-2, PCP-3 |
| DO-7 | **Derived data is rebuildable.** Rating summaries, search documents, rollups and caches can be regenerated from source tables | P-6 |
| DO-8 | **Audit records are append-only** and are never edited or deleted by application code | C-29 |
| DO-9 | **Retention is per data class**, and the schedule is **PENDING COUNSEL** (L-21, D-46). The schema **MUST** make deletion possible without making it mandatory before the schedule exists | D-46 |
| DO-10 | **Personal data location** follows the policy in PRD §21.3: personal data on Ethiopia-hosted infrastructure; non-personal business media may be served from a foreign CDN. Vendor selection is **Open (D-42, D-42b)** | D-42 |

---

## 13. API architecture

Authority for the contract: [`api-spec-v1.0.md`](api-spec-v1.0.md). This
section fixes the architectural constraints.

### 13.1 The central decision

**TD-01 — The Web surface does not call its own HTTP API.**

Web controllers and API controllers are two thin adapters over the *same*
application services. The Web surface calls those services in-process; it
never issues an HTTP request to `/api/v1` to render a page.

| Rejected alternative | Why |
| --- | --- |
| Web renders by calling its own API over HTTP | Doubles latency per page on shared hosting, duplicates auth handling, and turns an internal call into a network failure mode |
| Separate business logic per surface | Violates D-49 and G-5 |

```text
                 ┌──────────────── Web controller (HTML) ─────┐
                 │                                            ▼
Telegram Mini App ─▶ API controller (JSON) ─▶ Application service ─▶ Domain ─▶ Repository
                 │                                            ▲
Future Flutter ──┘                                            │
                                                     one implementation
```

**TR-21.** Every behaviour reachable through `/api/v1` **MUST** be
implemented in an application service, and the API controller **MUST**
contain nothing but request shaping, authorization delegation and response
serialisation.

### 13.2 Architectural constraints

| ID | Requirement | Source |
| --- | --- | --- |
| TR-22 | The API base path is `/api/v1`; it is versioned from the first endpoint, as the existing `routes/api.php` already establishes | Existing foundation |
| TR-23 | A breaking change **MUST** introduce `/api/v2`; `/api/v1` responses **MUST** remain additive-only | PRD FL-2 (a later client depends on contract stability) |
| TR-24 | The API **MUST** serve the Telegram Mini App and **MUST** be sufficient for a later Flutter client without new domain rules | D-49, PRD FL-2 |
| TR-25 | API responses **MUST NOT** expose internal identifiers of other actors, staff identities, provenance, Permission records or moderation internals to public clients | DO-4, PRD TS-10 |
| TR-26 | Errors **MUST** use one JSON envelope with a machine-readable code, a safe message and the request correlation id | §27 |
| TR-27 | Public read endpoints **MUST** be cacheable (§24); authenticated endpoints **MUST** be `no-store` | §24 |
| TR-28 | Write endpoints that could be retried **MUST** be idempotent or protected by an idempotency mechanism (§29) | §29 |
| TR-29 | Operations endpoints **MUST** be separated by path (`/api/v1/ops/…`), authorization and rate-limit policy from public endpoints | §15 |
| TR-30 | The API **MUST NOT** become a public data-export surface: list endpoints are paginated with bounded page sizes and no endpoint returns the whole directory | PRD §13, D-50 (permission scope) |

---

## 14. Authentication and identity architecture

Authority: [`auth-identity.md`](auth-identity.md). Architectural summary:

| Element | V1 design | Source |
| --- | --- | --- |
| Customer sign-in methods | Google, and email OTP. **Nothing else** | D-48 |
| Passwords | **None.** No password field exists anywhere in the schema | D-48 |
| Apple Sign In | Not implemented | D-48, D-47 |
| Customer session (Web) | Server-side session record keyed by an opaque, high-entropy cookie value; `HttpOnly`, `Secure`, `SameSite=Lax` | TD-02 |
| Customer session (API / Mini App) | Opaque bearer token bound to the same session record | TD-02 |
| Identity resolution | One Customer identity across surfaces; provider identities attach to it | C-32 |
| Provider linking rules | **Open (D-13)** — the schema supports multiple identities per Customer; the automatic-linking rule is not invented here | D-13 |
| Telegram identity | **Open (D-33)**. Telegram is **not** a login provider. Mini App context is validated server-side and is a *surface* signal, not an identity | D-33, D-48 |
| Staff authentication | Separate from Customer accounts; **MUST** be stronger and **MUST NOT** be email OTP alone. Exact strength is **Open (D-45)** | D-45, PRD ACC-9 |

**TD-02 — One session concept, two transports.** A single `session` record
is addressed either by cookie (Web) or by bearer token (API clients). This
avoids two session systems and lets the Mini App work where third-party
cookie behaviour inside an embedded webview is unreliable (R-23).

**TR-31.** The identity schema **MUST** accommodate the eventual D-45
outcome (a second factor for staff) without a migration that rewrites
existing identity tables: staff authentication factors are modelled as rows,
not as columns on the staff user.

**TR-32.** No authentication mechanism may be added that is not in D-48.
Adding one is a product decision, not a technical one.

---

## 15. Authorization architecture

### 15.1 Actors and resolution

Exactly four actors exist (PRD §28, `interaction-permissions.md`): Guest,
Customer, Operator, Administrator. Authorization resolves the actor once per
request and attaches it to the request context.

```text
request → session lookup → actor:
            none                 → Guest
            customer session     → Customer(id)
            staff session        → Operator(id) | Administrator(id)
```

### 15.2 Rules

| ID | Requirement | Source |
| --- | --- | --- |
| TR-33 | Authorization **MUST** be enforced server-side on every request. Hidden UI is presentation, never protection | `interaction-permissions.md` ENF-1 |
| TR-34 | Guest-safe capabilities **MUST** require no session and **MUST NOT** be degraded for unauthenticated users | ENF-4, GS-1…GS-5 |
| TR-35 | The operations console and `/api/v1/ops/*` **MUST** deny Guests and Customers without distinguishing "forbidden" from "not found" to unauthenticated callers | ENF-2 |
| TR-36 | Administrator-only actions (taxonomy, locations, campaign activation, roles, audit read, policy content, executing data requests) **MUST** be checked against the role, not inferred from console access | ADM-1, `interaction-permissions.md` §4 |
| TR-37 | The Operator/Administrator capability split **MUST** be expressed as named permissions in data, not hard-coded in controllers, because the exact split is **Open (D-14)** | D-14 |
| TR-38 | Ownership checks (a Customer editing *their* Review, deleting *their* account) **MUST** be performed in the application service, not in the controller | TR-03 |
| TR-39 | A failed staff authorization attempt **MUST** be logged with actor, target and action | ENF-3, C-29 |
| TR-40 | **No permission may be granted to a Business**, because no Business principal exists (D-54). The authorization model **MUST NOT** contain a business role | D-54 |

**TD-03 — Permission naming.** Permissions are named strings grouped by
area (for example `listing.publish`, `taxonomy.manage`, `campaign.activate`,
`audit.read`), assigned to roles, and roles assigned to staff users. This
satisfies TR-37 while leaving the actual split to D-14.

---

## 16. Search architecture

Authority: [`search-design.md`](search-design.md). Architectural summary.

**TD-04 — V1 search runs inside MariaDB.** No external search service
(NG-3). The design uses a maintained, denormalised **search document** per
Listing plus explicit filter columns and indexes, rather than querying the
normalised tables directly with `LIKE`.

```text
query text
   ↓  normalise (trim, case-fold, Unicode normalise, strip punctuation)
   ↓  interpret (exact-ish phrase, prefix, token set; detect Alias matches)
   ↓  candidate retrieval       ← search_document + filter columns, bounded
   ↓  filter                    ← Category, Area, open-now, rating, verified
   ↓  score                     ← ranking inputs, PRD §16.2, no weights here
   ↓  order, paginate
   ↓  decorate                  ← rating summary, open status, distance
   ↓  Sponsored placements      ← selected separately, merged into slots, labelled
```

| ID | Requirement | Source |
| --- | --- | --- |
| TR-41 | The ranking pipeline **MUST** consume only the inputs listed in PRD §16.2 and **MUST NOT** read any Advertising table | PRD RANK-2, TR-18 |
| TR-42 | **No ranking weights are defined in V1 documentation.** Weights are a tunable, owner-reviewable configuration produced from real query data, not a constant invented here | PRD RANK-1, D-09 |
| TR-43 | Sponsored placements **MUST** be selected by a separate code path and merged into documented slots after organic ordering is final, so that removing all Campaigns leaves the organic order byte-identical | PRD RANK-3, ADV-8 |
| TR-44 | Search **MUST** match Amharic script and Latin transliteration through controlled Aliases, never machine translation | D-18, PRD SRCH-3, R-09 |
| TR-45 | The search document **MUST** be rebuildable from source tables by a console command, and **MUST** be updated when a Listing, Category, Area or Alias changes | DO-7 |
| TR-46 | Zero-result queries **MUST** be recorded for operational review | PRD SRCH-8, C-28 |
| TR-47 | Every search query **MUST** be bounded: a maximum candidate set, a maximum page size and a maximum offset, so that no query can degrade the shared host | G-2 |
| TR-48 | The search interface **MUST** be a port with one V1 adapter (MariaDB), so that a future engine can be introduced behind it without touching application services | §37 |

---

## 17. Listing and data-quality architecture

Implements the lifecycle in `listing-operations.md` and capabilities
C-19…C-24.

### 17.1 Listing state

```text
draft ──submit──▶ in_review ──approve──▶ published ──unpublish──▶ unpublished
   ▲                  │                      │                        │
   └─────return───────┘                      └──mark closed──▶ closed (URL preserved)
```

| ID | Requirement | Source |
| --- | --- | --- |
| TR-49 | Publication **MUST** be refused unless a Permission record is linked and the required field set is complete | C-19, D-50, PROV-1 |
| TR-50 | Publication **MUST** be refused unless a Verification record exists for the current content | C-21 |
| TR-51 | The transition to `published` **MUST** be performed by a different staff user than the one who submitted it, **where another staff user exists**; single-operator mode is permitted but **MUST** be recorded as an override in the audit log, not silently allowed | C-21, `listing-operations.md` §2.7 |
| TR-52 | Every write to published Listing data **MUST** record before/after values, actor, reason and source (a Correction) | C-20, COR-1 |
| TR-53 | Permanent closure **MUST** be a state, never a delete: the Listing, its URL and its history survive | COR-3, SEO-4 |
| TR-54 | Verification age **MUST** be derivable by query so that re-verification queues and stale reporting need no batch job to exist | C-21, C-28 |
| TR-55 | Duplicate candidates **MUST** be surfaced at creation time using a deterministic comparison of name, Area and contact points | C-19, OC-4, DQ-4 |
| TR-56 | Unknown data **MUST** be representable as absent, distinct from "false" or "zero" — in particular, unknown hours **MUST NOT** be treated as closed, and such a Listing is excluded from open-now filtering | C-09, DQ-2 |
| TR-57 | Concurrent edits **MUST** be detected (optimistic concurrency on a version or updated-at token) and **MUST NOT** silently overwrite | C-20 |
| TR-58 | No bulk-import path may bypass Permission, provenance or quality review | OC-10 |

**Open — product/data detail:** the opening-hours model (D-04), verification
rules and interval (D-08), completeness definition (D-09), Branch-vs-Business
attribute boundary (D-55), category catalogue and cardinality (D-56, D-57),
Permission record contents and retention (D-43), services/products/pricing
representation (D-44). The schema in `data-model.md` accommodates these
without pre-deciding them.

---

## 18. Review and moderation architecture

Implements `review-policy.md` and capabilities C-13, C-25, C-35.

| ID | Requirement | Source |
| --- | --- | --- |
| TR-59 | A Review **MUST** be written only by an authenticated Customer and **MUST** be attributed to that Customer | D-12 |
| TR-60 | Review state **MUST** be an explicit value (`pending`, `published`, `rejected`, `removed`, `deleted`) supporting **either** pre- or post-publication moderation, because the choice is **Open (D-34)**. No code may assume one of them | LC-4, D-34 |
| TR-61 | Only `published` Reviews **MUST** contribute to a rating summary; the summary **MUST** be recomputed on every state change | SUM-4, SUM-5 |
| TR-62 | The rating summary **MUST** be stored as a derived value for query performance and **MUST** be rebuildable from Reviews | DO-7 |
| TR-63 | A Business with no published Reviews **MUST** have no rating value — the schema **MUST** distinguish "no rating" from "rating of zero" | SUM-2 |
| TR-64 | Every moderation outcome **MUST** record the policy ground, the actor, the time and the reason | MOD-5, C-29 |
| TR-65 | Reporter identity **MUST NOT** be retrievable through any response visible to the author or the Business | TS-10, REP-4 |
| TR-66 | Anti-abuse controls — rate limiting, one Review per Customer per subject, duplicate-text detection, anomaly surfacing — **MUST** exist; **their thresholds are configuration, not constants, and the values are Open (D-34)** | AB-2…AB-5 |
| TR-67 | **No reply-to-review structure may be built**: there is no business principal to own it (D-12, D-54). A schema column, API field or template slot for it is a defect | D-12, D-54, PRD H-4 |
| TR-68 | Whether the review subject is a Business or a Branch is **Open (D-34)**; the data model **MUST** record the decision point explicitly rather than guessing | D-34 |

---

## 19. Advertising architecture

Implements `advertising-products.md` and capabilities C-16, C-27.

```text
Package (what is sellable)  ──▶ Campaign (one Business, one Placement,
                                           one period, one fixed price)
                                      │ approved by Administrator
                                      ▼
Placement (named slot) ──▶ delivery selection ──▶ labelled render ──▶ measurement
```

| ID | Requirement | Source |
| --- | --- | --- |
| TR-69 | Delivery selection **MUST** run after organic ordering and **MUST NOT** modify it (TR-43) | ADV-8, RANK-3 |
| TR-70 | A Campaign **MUST** serve only when: approved by an Administrator, inside its period, and its Business is published, verified and eligible | C-27, ADV-10 |
| TR-71 | Campaign start and end **MUST** be evaluated at request time from stored dates; no manual action and no scheduled job may be required to start or stop a Campaign | ADV-4 |
| TR-72 | Placement inventory **MUST** be enforced at Campaign creation: an overlapping Campaign for a sold-out Placement and period is refused | C-27 |
| TR-73 | Every Sponsored placement rendered **MUST** carry the label; the label **MUST** be produced by the same component that produces the placement, so it cannot be omitted by a template | ADV-1, LB-1 |
| TR-74 | Impressions and clicks **MUST** be recorded per Campaign for reporting only; they **MUST NOT** influence price or ranking | ADV-7, MS-2 |
| TR-75 | Every Campaign state change **MUST** be audited with actor, time and reason | ADV-6, C-29 |
| TR-76 | **No auction, bidding, budget pacing, CPC/CPM/CPA calculation, targeting engine or self-service purchase component may be built** | D-10, PRD §13.2 |
| TR-77 | Measurement **MUST** be resilient to being lost: a failure to record an impression **MUST NOT** fail the page | P-12 |
| TR-78 | Integrity controls separating advertising from moderation and verification are **Open (D-39)** and **MUST** be resolved before the first paid Campaign; the architecture **MUST** keep the two areas separable (TR-18) | D-39, ADV-12 |

---

## 20. Media architecture

| ID | Requirement | Source |
| --- | --- | --- |
| TR-79 | Media is uploaded only by Staff (C-22). There is no public or business upload path in V1 | D-02, D-54 |
| TR-80 | Uploads **MUST** be validated by actual content inspection, not by extension or client-supplied MIME type, and restricted to an allow-list of image formats | §34 |
| TR-81 | Uploaded files **MUST NOT** be stored in the document root and **MUST NOT** be executable; delivery happens through a storage adapter or an explicit streaming endpoint | §34 |
| TR-82 | Derivatives (sized variants) **MUST** be generated so that pages can request an appropriately sized image | PRD MOB-4, NFR-P5 |
| TR-83 | Original uploads **MUST** be retained so derivatives can be regenerated | DO-7 |
| TR-84 | The storage adapter **MUST** have a local-filesystem implementation for V1 so the system runs with no external dependency, and an object-storage implementation **MAY** be configured; vendor selection is **Open (D-25)** and constrained by the data-location policy (D-42) | G-2, D-25, D-42 |
| TR-85 | Business premises media is **business information**, not personal data; media depicting identifiable people is subject to the photography constraint and is **PENDING COUNSEL** (L-18) | D-51, L-18 |
| TR-86 | A Listing without media **MUST** render correctly; no placeholder implying a missing photograph | C-22 |

---

## 21. Email and notification architecture

| ID | Requirement | Source |
| --- | --- | --- |
| TR-87 | Email is the only Bulbula-operated notification channel in V1 | D-24, PRD NOT-1 |
| TR-88 | Message composition **MUST** be separate from delivery: the application enqueues a message; a transport adapter delivers it | §28 |
| TR-89 | The provider **MUST** be replaceable by configuration without product changes; **no provider is selected here (Open — D-41)** | D-41, PRD EM-7 |
| TR-90 | Delivery state (`queued`, `sent`, `failed`, `suppressed`) **MUST** be recorded per message, with failures visible to Staff | PRD EM-4, NOT-7 |
| TR-91 | A delivery failure **MUST NOT** fail the action that triggered it | PRD EM-4 |
| TR-92 | Authentication codes **MUST** be dispatched synchronously enough to be usable, and **MUST** follow the controls in §34 and `auth-identity.md` | C-31 |
| TR-93 | Message bodies **MUST NOT** contain unrelated marketing content, and no marketing category exists in V1 | PRD EM-2, EM-3 |
| TR-94 | The notification set **MUST** be exactly the categories in PRD §18; adding one is a product decision | PRD NOT-2 |
| TR-95 | Email addresses are personal data: stored minimally, deletable, and never exposed through any public endpoint | D-51, TR-25 |

---

## 22. Web architecture

| ID | Requirement | Source |
| --- | --- | --- |
| TR-96 | The Web surface **MUST** be server-rendered HTML. Core content **MUST** be present in the initial response without JavaScript execution | C-37 SEO-1, PRD MOB-5 |
| TR-97 | JavaScript **MUST** be progressive enhancement only: filters, autocomplete, map loading and share controls degrade to working HTML equivalents | PRD MOB-5, NG-2 |
| TR-98 | The interface **MUST** be mobile-first; larger viewports are supported but are not the design baseline | D-52, PRD MOB-6 |
| TR-99 | Templates **MUST** escape by default; any raw output **MUST** be explicit and reviewable | §34 |
| TR-100 | URLs **MUST** be stable, human-readable and canonical, and a Business profile URL **MUST** survive a name change (redirect where it changes) | SEO-3, SEO-4 |
| TR-101 | Pages **MUST** emit canonical links, unique metadata, structured data for Business profiles and sharing metadata | SEO-2, SEO-5, SEO-6, SEO-11 |
| TR-102 | Filter, sort and pagination parameters **MUST NOT** produce multiple indexable URLs for the same content | SEO-10 |
| TR-103 | A sitemap **MUST** be generated from published Listings and taxonomy, and regenerated as content changes | SEO-7 |
| TR-104 | Non-public surfaces (operations console, account pages, authentication flows) **MUST** be excluded from indexing | SEO-8 |
| TR-105 | The interface is English-first with a bilingual-ready content model: user-facing strings **MUST NOT** be hard-coded in a way that prevents adding Amharic later, and Amharic labels/Aliases on taxonomy and Areas are stored in V1 | D-18, GEO-3 |
| TR-106 | No design token, colour or logo asset is fixed by this document; brand colours are orange, blue and white (D-53), the logo is **Open (D-53)**, dark mode is **Open (D-19)** | D-53, D-19 |

**View layer — Open (D-17).** Whether the template layer is in-house or a
small library is unresolved. **TR-107:** whichever is chosen **MUST** satisfy
the architecture test that `Bulbula\View` touches neither the database nor
HTTP, and **MUST** escape by default.

**Client-side approach — Open (D-16).** htmx + Alpine versus vanilla ES
modules is unresolved; a SPA is rejected. **TR-108:** the choice **MUST NOT**
change what a page delivers without JavaScript.

---

## 23. Telegram Mini App integration architecture

| ID | Requirement | Source |
| --- | --- | --- |
| TR-109 | The Mini App is the **same application** served to a Telegram webview, not a separate build target or codebase | D-49, R-16 |
| TR-110 | Surface differences **MUST** be confined to a documented adapter: navigation chrome, share mechanism, authentication entry, map hand-off, viewport conventions | D-49, PRD SUR-5 |
| TR-111 | The adapter **MUST NOT** change domain behaviour. A difference that changes what a User can do is a product decision | PRD SUR-6 |
| TR-112 | Telegram-supplied context **MUST** be validated server-side before any trust is placed in it, using the documented signature check, with a freshness bound on the payload | R-10, §34 |
| TR-113 | Validated Telegram context establishes a **surface**, not an identity. A Telegram user is not a login provider | D-48, D-33 |
| TR-114 | How Telegram context relates to a Bulbula Customer identity is **Open (D-33)**. The architecture **MUST** support attaching it later as an additional provider identity without restructuring | D-33 |
| TR-115 | Sign-in inside the Mini App **MUST** account for OAuth friction in embedded webviews; the chosen flow is a Mini App design matter and **MUST NOT** be assumed to be a plain redirect | R-23 |
| TR-116 | Bearer-token session transport **MUST** be available so the Mini App does not depend on third-party cookie behaviour (TD-02) | TD-02, R-23 |
| TR-117 | Mini App pages **MUST NOT** be an SEO surface and **MUST** be excluded from indexing | `scope-v1.md` §1.5 |
| TR-118 | Links shared out of the Mini App **MUST** resolve on the Web for recipients who are not Telegram users | C-17, PRD TG-6 |
| TR-119 | The Mini App navigation model (full page loads versus fragment swaps) is **Open (D-38)**; both **MUST** remain possible under TR-96 | D-38 |

---

## 24. Caching strategy

Authority: [`performance-and-caching.md`](performance-and-caching.md).

**TD-05 — No cache service in V1.** Caching uses HTTP caching first, then a
filesystem- or database-backed application cache. Redis/Memcached are **not**
required for correct operation (NG-5).

| Layer | V1 approach | Invalidation |
| --- | --- | --- |
| HTTP (public pages and GETs) | `Cache-Control` with a short shared max-age plus validators (`ETag`/`Last-Modified`) | Time plus content change |
| Static assets | Long max-age with content-hashed filenames | Filename change |
| Application cache | Filesystem-backed, keyed and versioned; used only where measurement justifies it | Explicit on write, plus TTL |
| Derived tables (rating summary, search document, rollups) | Maintained on write, rebuildable by console command | Explicit |
| Per-request memoisation | Within one request only | End of request |

| ID | Requirement |
| --- | --- |
| TR-120 | Authenticated and staff responses **MUST** be `no-store` |
| TR-121 | Any cache **MUST** be optional: with the cache cold or disabled, behaviour is identical and only slower |
| TR-122 | Cache keys **MUST** include every input that changes the output, including Area, Category, filters, page and surface |
| TR-123 | **No personal data may be stored in a shared cache**, and no cached fragment may contain content specific to one Customer |
| TR-124 | A Listing state change (publish, unpublish, Correction, closure) **MUST** invalidate the cached representations that include it |

---

## 25. Performance strategy

| ID | Requirement | Source |
| --- | --- | --- |
| TR-125 | Public pages **MUST** be designed against the Core Web Vitals "good" thresholds as a **[P] proposed** target, not a committed one | PRD NFR-P1 |
| TR-126 | Every list query **MUST** be bounded and paginated; no endpoint may return unbounded rows | TR-47 |
| TR-127 | N+1 query patterns **MUST** be eliminated by batch loading in repositories | PRD NFR-P3 |
| TR-128 | Every query behind a public page **MUST** be supported by an index; index design is part of the migration that adds the table | §30 |
| TR-129 | Expensive derived values (rating summary, counts, search documents) **MUST** be precomputed, never computed per page view | §24 |
| TR-130 | Third-party components (maps, fonts) **MUST NOT** block first render | PRD NFR-P4, C-10 |
| TR-131 | Images **MUST** be lazy-loaded with explicit dimensions and served at device-appropriate sizes | PRD MOB-4 |
| TR-132 | Response compression **MUST** be enabled where the host supports it | `performance-and-caching.md` |
| TR-133 | Performance work **MUST** be driven by measurement on a mid-range Android device over a constrained network, not by assumption | R-06 |

**No performance number in this document is a commitment.** Proposed targets
inherit the PRD's `[P]` marking; committed targets require owner approval.

---

## 26. Observability and logging

Builds on the existing Monolog-based `LoggerFactory` (JSON in production,
readable elsewhere).

| ID | Requirement | Source |
| --- | --- | --- |
| TR-134 | Every log line **MUST** carry the request correlation id (TR-12), the environment and a severity | PRD NFR-O1 |
| TR-135 | Logs **MUST NOT** contain credentials, session identifiers, bearer tokens, OTP codes or full personal records | §34, D-51 |
| TR-136 | Operational events that need review — failed logins, authorization denials, moderation actions, campaign changes, email failures, job failures — **MUST** be logged at a level that surfaces them | PRD NFR-O1 |
| TR-137 | Staff **MUST** be able to answer, from the console: is this Listing published, verified when, changed by whom, and when | PRD NFR-O2 |
| TR-138 | Health endpoints **MUST** distinguish liveness (no dependencies) from readiness (database reachable), as the existing `/health` and `/health/ready` already do | Existing foundation |
| TR-139 | Background jobs **MUST** record start, end, outcome and item counts so a silent failure is visible | §28 |
| TR-140 | Log rotation and retention **MUST** be configured so logs cannot exhaust shared-host disk; the retention period interacts with the retention schedule (**PENDING COUNSEL**, L-21) | §38, L-21 |
| TR-141 | Observability tooling selection beyond logs is **Open — technical decision (OT-07)**; V1 requires no external APM | PRD NFR-O3 |

---

## 27. Error handling

| ID | Requirement | Source |
| --- | --- | --- |
| TR-142 | The existing posture is preserved: `HttpException` subclasses render as themselves; any other throwable is logged with a reference id and rendered as an opaque 500 in production | Existing foundation |
| TR-143 | Controllers **MUST NOT** catch exceptions for presentation; they throw and the kernel renders | Existing foundation |
| TR-144 | Error responses **MUST** be safe: no stack traces, no SQL, no file paths, no internal identifiers in production | §34 |
| TR-145 | API errors **MUST** use one envelope with a stable machine-readable code, a safe message, optional field-level validation detail and the correlation id | TR-26 |
| TR-146 | User-facing error copy **MUST** be non-technical and **MUST** offer a route forward (retry, broaden the search, contact) | C-02, C-15 |
| TR-147 | A dependency failure (maps, media, email, analytics) **MUST** degrade that feature only | P-12, TR-02 |
| TR-148 | Validation failures **MUST** preserve user input where re-entry would otherwise be required | C-15 |
| TR-149 | The pre-boot failure path in `public/index.php` **MUST** continue to report conditions that occur before the application exists (wrong interpreter, missing autoloader) | Existing foundation |

---

## 28. Background jobs and asynchronous work

**TD-06 — Cron-driven work, no broker.** Asynchronous work is a database
queue table drained by console commands invoked from cron. This satisfies
shared hosting and NG-4.

```text
cron (shared hosting scheduler)
  └─ php bin/console <command>
        ├─ queue:work        drain due queued work (email dispatch, derivative generation)
        ├─ analytics:rollup  aggregate raw events into daily metrics
        ├─ search:reindex    rebuild or refresh search documents
        ├─ listings:freshness  recompute verification age, populate queues
        └─ maintenance:prune   expire sessions, OTP state, old raw events per retention
```

| ID | Requirement | Source |
| --- | --- | --- |
| TR-150 | Every job **MUST** be runnable manually, be idempotent, and be safe to run concurrently or not at all | §29 |
| TR-151 | Jobs **MUST** be bounded per run (a maximum item count and a wall-clock budget) so a shared-host cron window cannot be exceeded | G-2 |
| TR-152 | A job that cannot finish **MUST** leave consistent state and resume on the next run | §29 |
| TR-153 | Overlapping runs **MUST** be prevented by a lock whose expiry cannot deadlock the schedule | §29 |
| TR-154 | **No product behaviour may depend on a job having run.** Campaign start/stop is evaluated at request time (TR-71); verification age is derivable by query (TR-54) | TR-71, TR-54 |
| TR-155 | Job outcomes **MUST** be observable (TR-139) and failures **MUST** be visible to Staff | PRD NFR-O1 |
| TR-156 | Email dispatch **MUST** tolerate provider outages with retry and a failure state, never an infinite retry loop | TR-90 |

---

## 29. Idempotency and retry principles

| ID | Requirement |
| --- | --- |
| TR-157 | Reads **MUST** be side-effect free. A `GET` **MUST NOT** change state, including analytics writes that would make a crawl mutate data beyond append-only counters |
| TR-158 | Destructive and state-changing operations **MUST** be addressed by a stable identifier so that a repeated request is detectable |
| TR-159 | Write endpoints that a client may retry (review submission, report submission, OTP verification, campaign creation) **MUST** be protected against duplicate application — by natural uniqueness where one exists, or by a client-supplied idempotency key where it does not |
| TR-160 | Natural uniqueness **MUST** be enforced in the database, not only in application code: one Save per Customer per Business, one Review per Customer per subject, one Campaign per Placement per overlapping period |
| TR-161 | Retries against external services (email, storage) **MUST** use bounded attempts with backoff and a terminal failure state |
| TR-162 | A partially failed multi-step operation **MUST** either complete or leave no partial published state; transactions are used where the engine supports them |
| TR-163 | OTP verification **MUST** be single-use: a replayed code **MUST** fail even if it is still inside its validity window |

---

## 30. Database strategy

| ID | Requirement | Source |
| --- | --- | --- |
| TR-164 | MariaDB is the system of record, accessed through PDO with prepared statements. No ORM, no query builder | Existing foundation, NG-7 |
| TR-165 | `utf8mb4` / `utf8mb4_unicode_ci` throughout, so Amharic text is stored and compared correctly | Existing config, D-18 |
| TR-166 | Every table **MUST** have an explicit primary key; relationships **MUST** be expressed as foreign keys where the engine and hosting permit | `data-model.md` |
| TR-167 | Referential rules **MUST** be explicit: what cascades, what restricts, what nullifies — in particular, deleting a Customer **MUST NOT** silently delete audit or moderation history | DO-8, DO-9 |
| TR-168 | Monetary, temporal and enumerated values **MUST** use precise column types; times are stored in UTC and rendered in `Africa/Addis_Ababa` | Existing config |
| TR-169 | Indexes **MUST** be designed with the queries that need them and added in the same migration as the table | TR-128 |
| TR-170 | **No EAV or generic attribute-bag design.** Entities are explicit relational structures | Task constraint, G-9 |
| TR-171 | Soft deletion **MUST** be used only where the product requires history (Listings, Reviews, audit); elsewhere deletion is real deletion | DO-9, COR-3 |
| TR-172 | Personal-data columns **MUST** be identifiable from the schema documentation so that a rights request can be executed completely | DO-5, DO-6, PRIV-7 |
| TR-173 | The test suite runs against in-memory SQLite; migrations **MUST** therefore stay in portable SQL, or provide an engine-specific branch where portability is impossible | Existing foundation |
| TR-174 | Host resource limits and MariaDB tuning are **Open (D-20)**; the design **MUST NOT** assume generous limits | D-20 |

---

## 31. Migration strategy

The existing migration system is sufficient and is reused unchanged:
`database/migrations/YYYY_MM_DD_HHMMSS_description.php`, each returning an
anonymous class extending `Migration` with `up()` and `down()`; applied in
filename order; batches tracked in the `migrations` table; rollback by batch.

| ID | Requirement |
| --- | --- |
| TR-175 | Every migration **MUST** be reversible, or **MUST** state in a comment why it is not and what the recovery procedure is |
| TR-176 | Migrations **MUST** be additive where possible; a destructive change is split into expand → migrate data → contract across separate deployments |
| TR-177 | A migration **MUST NOT** contain business data beyond reference data required for the system to function; the Category catalogue is content, not schema (**Open — D-56**) |
| TR-178 | Migrations **MUST** be deterministic and **MUST NOT** depend on the current date, environment or existing production content |
| TR-179 | Schema changes **MUST** be accompanied by the indexes the new queries require (TR-169) |
| TR-180 | Migrations are run deliberately, never automatically by the deployment cron (§32) |
| TR-181 | A migration that would take a long time on a shared host **MUST** be chunked or executed as a console command rather than inside a single DDL step |

---

## 32. Deployment architecture

Authority: [`deployment.md`](deployment.md). Architectural constraints:

| ID | Requirement |
| --- | --- |
| TR-182 | One deployable unit: the repository, installed on a host that serves `public/` only |
| TR-183 | The repository is the source of truth. Nothing a deployed environment needs may exist only on a server |
| TR-184 | The document root **MUST** be `public/`; `src/`, `config/`, `database/`, `resources/`, `routes/`, `tests/`, `vendor/`, `.env` stay above it |
| TR-185 | `vendor/` is built on the server with `--no-dev --optimize-autoloader` and is never committed |
| TR-186 | Secrets live in `.env` on the server, created from `.env.example`, never committed, never logged |
| TR-187 | Deployment **MUST** be repeatable and rollback-able to a previous commit; `git reset --hard` semantics mean server-side edits are always lost, which is intended |
| TR-188 | Migrations are a separate, deliberate step (TR-180) |
| TR-189 | The deployment **MUST** work on shared hosting (cPanel-class) with no root access, no daemon supervision and only cron for scheduling |
| TR-190 | A deploy **MUST** be verifiable by a documented smoke test (home page, health, readiness, a 404, a static asset) |
| TR-191 | The PHP interpreter version is pinned by the committed handler block in `public/.htaccess`; the front controller verifies the interpreter at runtime and reports a mismatch explicitly |

---

## 33. Environment and configuration management

| ID | Requirement |
| --- | --- |
| TR-192 | Configuration is read exclusively through the `Config` repository; only `Bulbula\Config` may read the environment, as the architecture test enforces |
| TR-193 | Every new setting **MUST** have a documented default in `config/*.php` and an entry in `.env.example` with a placeholder value |
| TR-194 | The application **MUST** fail loudly at boot on invalid or missing required configuration, rather than running with a silent fallback |
| TR-195 | `APP_DEBUG` **MUST** remain incapable of enabling debug output in production, as `Application::isDebug()` already guarantees |
| TR-196 | Feature-affecting configuration (rate limits, page sizes, cache TTLs, verification interval once D-08 resolves, abuse thresholds) **MUST** be configuration, not constants in code |
| TR-197 | Secret custody and production configuration management are **Open (D-23)**; until resolved, `.env` on the server is the mechanism and the rules in TR-186 apply |
| TR-198 | No credential, token or key may appear in the repository, in logs, in error output or in a test fixture |

---

## 34. Security constraints

Architectural constraints only. The complete security specification belongs
to `docs/50-security/` in a later phase and **MUST NOT** be pre-empted here.

| Area | Constraint | Source |
| --- | --- | --- |
| **Transport** | HTTPS everywhere; HSTS over HTTPS as `SecureHeaders` already does | PRD NFR-S1 |
| **Authentication** | Google and email OTP only; no passwords stored anywhere; OTP codes stored only as hashes, single-use, short-lived, attempt- and rate-limited, bound to the requesting session | D-48, C-31, R-25 |
| **Staff authentication** | Stronger than Customer authentication and never email OTP alone; exact strength **Open (D-45)** | D-45, PRD TS-17 |
| **Sessions and tokens** | High-entropy opaque identifiers; server-side session records; `HttpOnly`, `Secure`, `SameSite=Lax` cookies; rotation on privilege change; expiry and idle timeout; revocation on sign-out and on account deletion | TD-02 |
| **Authorization** | Enforced server-side on every request; least privilege; staff/Customer separation; deny by default | §15 |
| **Input validation** | Every input validated for type, range, length and allowed values at the application boundary; unknown fields rejected rather than ignored | PRD NFR-S4 |
| **Output encoding** | Escape by default in templates; JSON encoded with the existing hex flags so a response embedded in HTML cannot break context | Existing foundation |
| **CSRF** | All state-changing HTML form submissions protected by a per-session token; API token-authenticated requests are not cookie-authenticated and therefore not CSRF-exposed | TD-02 |
| **Rate limiting** | Applied to authentication, OTP issuance, review submission, report submission and search; limits are configuration (TR-196); responses **MUST NOT** disclose thresholds | C-15, C-31, AB-2 |
| **SQL** | Prepared statements with bound parameters only; no string interpolation of input; `PDO` confined to `Bulbula\Database` | Existing architecture test |
| **File uploads** | Staff-only; content-inspected; allow-listed image types; size-bounded; stored outside the document root; never executable | TR-80, TR-81 |
| **Media validation** | Re-encode or verify derivatives rather than trusting the uploaded bytes | TR-82 |
| **Secure headers** | The existing `SecureHeaders` middleware applies CSP, `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy`; the Mini App requires a frame-ancestors policy that permits the Telegram host — **Open — technical decision (OT-05)** | Existing foundation, D-49 |
| **Error handling** | Opaque in production with a reference id; no stack traces, no SQL, no paths | §27 |
| **Audit logging** | Every privileged action recorded with actor, action, target, time and reason; append-only; unwritable audit means the action fails | C-29, TR-08 |
| **Privileged actions** | Administrator-only operations gated by named permission and audited | TR-36 |
| **Secrets** | Never in Git, logs or error output; `.gitleaks.toml` scanning is already part of CI | TR-198 |
| **Dependencies** | `composer audit` in CI; the dependency surface stays deliberately small | Existing CI |

---

## 35. Privacy and data-minimization constraints

Architectural constraints only; the privacy specification belongs to
`docs/55-privacy/` in a later phase.

| ID | Constraint | Source |
| --- | --- | --- |
| TR-199 | Every field **MUST** have a stated purpose before it is stored; a field without a purpose is removed | D-51, PRIV-2 |
| TR-200 | The Customer record is minimal: identifier, email address, display name, sign-in method, timestamps. Anything more requires a recorded purpose | PRD ACC-6 |
| TR-201 | A Guest's precise location **MUST NOT** be stored; it is used in-request for distance ordering and discarded | C-07, PRD §13 |
| TR-202 | Analytics events **MUST NOT** store information identifying an individual Guest — no IP address retained as an identifier, no device fingerprint, no cross-session identifier | PRD AN-3, NFR-PR3 |
| TR-203 | Business data **MUST NOT** be assumed non-personal; personal contact points are flagged at the schema level (DO-6) | D-51, PCP-1, PCP-2 |
| TR-204 | All personal data relating to one person **MUST** be locatable through documented relationships so a rights request can be executed completely | PRIV-7 |
| TR-205 | Account deletion **MUST** remove or irreversibly detach personal data; the treatment of published Reviews afterwards is **Open — product/legal (D-34, L-21)** | C-36, D-34 |
| TR-206 | Personal data residency follows PRD LOC-1; the hosting approach is **Open (D-42, D-42b)** and the lawful basis for any transfer is **PENDING COUNSEL** (L-10, L-12) | D-42, L-10 |
| TR-207 | No third-party tracking, advertising SDK or behavioural profiling component may be integrated | NFR-PR4, NG-12 |
| TR-208 | Retention periods per data class are **PENDING COUNSEL** (L-21, D-46); the schema supports expiry, and the pruning job reads the period from configuration | D-46, TR-196 |

---

## 36. Availability and reliability expectations

| ID | Expectation | Status |
| --- | --- | --- |
| TR-209 | Public discovery remains available when analytics, maps, email, media or search auxiliaries fail | **Required** (P-12, NFR-A1) |
| TR-210 | No availability percentage is committed in V1 | **[U]** — depends on hosting (D-42) |
| TR-211 | Database unavailability is a hard failure surfaced by the readiness endpoint, with a safe error page for Users | Required |
| TR-212 | A failed write **MUST NOT** leave a Listing, Review or Campaign in an inconsistent published state | Required (NFR-A5) |
| TR-213 | Failures are observable by Staff rather than discovered by Users | Required (NFR-A3) |
| TR-214 | Recovery is by redeploying a known commit plus restoring a database backup; there is no clustering or failover in V1 | Required (§38) |

---

## 37. Scalability strategy

**V1 scales vertically and by efficiency, not by distribution.** The launch
area's data volume is small; the constraint is shared-host CPU, memory and
query time.

| Dimension | V1 approach | Future option (not V1) |
| --- | --- | --- |
| Read volume on profiles and category pages | HTTP caching and precomputed derived values | CDN in front of HTML |
| Search cost | Bounded candidate sets, indexed filters, maintained search documents | A dedicated search engine behind the port in TR-48 |
| Write volume (events) | Append-only rows, aggregated by cron | Counter tables or a separate analytics store |
| Media bytes | Derivatives plus an object-storage adapter | CDN (constrained by D-42) |
| Application capacity | One host, efficient code | A VPS, still a monolith |
| Database | One MariaDB instance | Tuning first (D-20), then read replicas |

| ID | Requirement |
| --- | --- |
| TR-215 | Growth triggers **MUST** be recorded and re-evaluated against measurement rather than anticipated: search latency at the database, listing volume, rollup jobs exceeding their cron window, sustained host throttling, and notification volume beyond host sending limits. These are **[P]** proposals carried from `project-understanding-v0.1.md` §22.4, not commitments |
| TR-216 | No V1 component may be designed in a way that requires rewriting to introduce the future options above — in particular the search port (TR-48), the storage adapter (TR-84) and the mail transport (TR-89) |
| TR-217 | Adding an Area or Category **MUST** remain a data operation with no code change (GEO-4) |

---

## 38. Backup and recovery considerations

| ID | Requirement |
| --- | --- |
| TR-218 | The database **MUST** be backed up on a schedule, and a restore **MUST** be tested before launch; an untested backup is not a backup |
| TR-219 | Uploaded media **MUST** be backed up independently of the database, because derivatives are rebuildable but originals are not (TR-83) |
| TR-220 | Code requires no backup beyond the Git remote (TR-183) |
| TR-221 | `.env` **MUST** be recorded in a secure location outside the repository, since it exists only on the server (D-23 open) |
| TR-222 | Backup retention, frequency and off-host storage location are **Open — technical decision (OT-06)**, constrained by the data-location policy (D-42) and the retention schedule (**PENDING COUNSEL**, L-21) |
| TR-223 | Recovery procedure: redeploy the commit, restore the database, restore media, run `migration:status`, run the smoke test (TR-190) |
| TR-224 | Logs are not a backup and **MUST NOT** be relied on to reconstruct state |

---

## 39. Technical acceptance criteria

The technical work of V1 is complete when all of the following hold. Each is
verifiable.

### 39.1 Architecture and quality

```text
Given the V1 codebase
When the full quality gate runs
Then composer validate, Rector, Pint, PHPStan at max, Pest, type coverage
     and the architecture suite all pass
And coverage and mutation thresholds are not lower than Phase 1's.
```

| ID | Criterion |
| --- | --- |
| AC-1 | The architecture tests in §6.4 pass, extended to cover every new namespace (TR-01) |
| AC-2 | No framework, ORM, container or broker has been introduced (NG-1, NG-4, NG-7, NG-8) |
| AC-3 | `public/` still contains exactly one executable entry point (TR-09) |
| AC-4 | Every application service is callable from Web, API and CLI (TR-04) |
| AC-5 | No controller contains SQL, business rules or HTML construction (TR-03) |

### 39.2 Capability coverage

| ID | Criterion |
| --- | --- |
| AC-6 | All 40 capabilities C-01…C-40 are implemented and each PRD acceptance criterion passes |
| AC-7 | Every capability except C-37 and C-19…C-29 is available on both Web and the Telegram Mini App (`scope-v1.md` §1.5) |
| AC-8 | The complete discovery journey works with no session, no cookie consent interaction and no account (GS-1) |
| AC-9 | No excluded capability exists in any form: no business account, no claim, no owner reply, no password, no auction |

### 39.3 Behavioural invariants

```text
Given an identical search query executed with and without active Campaigns
When the organic results are compared
Then the ordering is identical.                                    (TR-43)

Given a Listing with no linked Permission record
When publication is attempted by any path, including import
Then publication is refused.                                       (TR-49)

Given any staff action that changes published data
When the action completes
Then an audit entry exists with actor, target, change and time.     (TR-08)

Given an analytics event recorded for a Guest
When the stored row is inspected
Then it contains nothing that identifies that Guest.                (TR-202)

Given every external dependency is unavailable
When a Guest searches and opens a Business profile
Then both succeed.                                                  (TR-02)
```

### 39.4 Operational

| ID | Criterion |
| --- | --- |
| AC-10 | A deploy from a clean checkout succeeds on the target shared host and passes the smoke test (TR-190) |
| AC-11 | Every scheduled job runs within its cron window and is idempotent (TR-150, TR-151) |
| AC-12 | A database restore has been performed successfully in a rehearsal (TR-218) |
| AC-13 | Health and readiness endpoints reflect real dependency state (TR-138) |
| AC-14 | No secret appears in the repository, logs or error output (TR-198) |

---

## 40. Open technical decisions

Open items that affect architecture. Product-level open items remain in the
PRD and the register; they are referenced, not duplicated.

### 40.1 Open technical decisions owned by this phase

| ID | Question | Blocks | Proposed resolution point |
| --- | --- | --- | --- |
| **OT-01** | Session lifetime, idle timeout and rotation values | Identity implementation | `auth-identity.md` + security phase |
| **OT-02** | OTP code length, validity window, attempt and request limits (shape fixed by R-25; values not) | C-31 implementation | Security phase |
| **OT-03** | Application cache backend: filesystem versus database table | Caching implementation | Measurement during build |
| **OT-04** | Search document refresh strategy: synchronous on write, queued, or both | Search implementation | `search-design.md` during build |
| **OT-05** | CSP `frame-ancestors` policy that admits the Telegram host without weakening the Web policy | Mini App delivery | Platform phase |
| **OT-06** | Backup frequency, retention and off-host location | Launch readiness | Operations phase, constrained by D-42 |
| **OT-07** | Whether any observability tooling beyond logs is introduced | None (V1 needs none) | Post-launch |
| **OT-08** | Pagination style for public lists: offset versus keyset | API contract | `api-spec-v1.0.md` during build |
| **OT-09** | Whether derivative generation is synchronous on upload or queued | Media implementation | Build |

### 40.2 Product and external decisions that constrain architecture

| ID | Question | Effect if unresolved |
| --- | --- | --- |
| D-04 | Opening-hours model | Hours schema and open-now evaluation remain provisional |
| D-08 | Verification rules, tiers, interval | Freshness queue thresholds are configuration-only |
| D-09 | Completeness definition and ranking weight | Completeness is computed but unweighted |
| D-13 | Identity-linking rules | Multiple identities are supported; auto-linking is not implemented |
| D-14 | Operator/Administrator split | Permissions exist as data (TD-03); assignment is provisional |
| D-16 / D-17 | Client-side approach and view layer | Template and enhancement choices pending |
| D-19 | Dark mode | Theming not designed |
| D-20 | Host limits and MariaDB tuning | Capacity assumptions stay conservative |
| D-21 | Maps key ownership, billing, embed strategy, fallback | Map integration kept behind a fallback |
| D-23 | Production configuration and secret custody | `.env` on the server is the interim mechanism |
| D-25 | Media storage provider, limits, formats | Local adapter is the V1 default |
| D-26 | Coverage and mutation-score gates as the domain grows | Phase 1 thresholds hold; never lowered |
| D-27 | Analytics granularity, retention, raw-event policy | Event schema exists; retention is configuration |
| D-33 | Telegram identity | Mini App context is a surface signal only |
| D-34 | Review mechanics | Review subject, edit window, deletion semantics provisional |
| D-35 | Guest structured suggestions | Free-text reports only |
| D-38 | Mini App navigation model | Both models remain possible |
| D-39 | Advertising integrity controls | Blocks the first paid Campaign |
| D-40 | Launch-area boundary | Area data provisional |
| D-41 | Email provider | Adapter only, no provider |
| D-42 / D-42b | Data-location policy and hosting vendor | Deployment target not final |
| D-43 | Permission record contents and retention | Minimum viable fields only |
| D-44 | Services/products/pricing representation | Deferred structure in the data model |
| D-45 | Staff authentication strength | Schema accommodates factors (TR-31); policy not set |
| D-46 / D-46 | Legal minima, account age, retention schedule | **PENDING COUNSEL** |
| D-55 | Branch versus Business attributes | Attribute placement provisional |
| D-56 / D-57 | Category catalogue and cardinality | Taxonomy content and multiplicity pending |
| D-30n / D-31 | Launch threshold and pilot | **PENDING PILOT** — no capacity or coverage number assumed |

**Rule.** An open item is a visible hole. Implementation **MUST** either
resolve it through the decision process and record it in the register, or
keep it visibly open. Silent resolution in code is a process failure.

---

## 41. Decision references

**Approved decisions this TRD implements:**
D-01, D-02, D-03, D-10, D-12, D-15, D-18, D-24, D-48, D-49, D-50,
D-51, D-52, D-53, D-54.

**Open decisions this TRD accommodates without resolving:**
D-04, D-08, D-09, D-11, D-13, D-14, D-16, D-17, D-19, D-20, D-21, D-23,
D-25, D-26, D-27, D-33, D-34, D-35, D-38, D-39, D-40, D-41, D-42, D-42b, D-43,
D-44, D-45, D-46, D-55, D-56, D-57.

**Deferred decisions referenced:** D-15r, D-47.

**Pending external:** D-30n, D-31, D-40, D-46.

Authority: [`../60-decisions/decision-register.md`](../60-decisions/decision-register.md).
