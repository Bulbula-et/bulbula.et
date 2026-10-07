# Implementation Plan

| | |
| --- | --- |
| **Document** | Implementation Plan and Build Readiness — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

**In scope.** The order in which Bulbula V1 is built, the boundaries between
its modules, what each piece depends on, what is blocked and by what, which
slice comes first, how each slice is tested, and how an implementation agent
is expected to work.

**Not in scope.** Requirements, product decisions, schema, SQL, PHP, dates,
estimates and capacity. This document plans work that other documents have
already specified. **It is not a second requirements document and it creates
no requirement of its own.**

## Authority

This document sits **below** every formal document and **above** code:

```text
Owner decisions
        ↓
docs/60-decisions/decision-register.md
        ↓
formal documentation (10-product … 55-privacy)
        ↓
.ai/ context
        ↓
this implementation plan
        ↓
code
```

| Rule | Statement |
| --- | --- |
| IMP-0.1 | **Where this plan and a formal document disagree, the formal document wins** and this plan is wrong |
| IMP-0.2 | **This plan states no requirement.** Every row traces to a `C-xx`, `D-xx`, `TR-xxx`, `TD-xx` or a named rule in a formal document |
| IMP-0.3 | **This plan resolves no decision.** An item that is `Open`, `PENDING COUNSEL` or `PENDING PILOT` is recorded here in exactly that state |
| IMP-0.4 | **This plan contains no date, duration, estimate, sprint count, velocity or capacity figure.** It describes order and dependency, not schedule |
| IMP-0.5 | Sequencing is a planning judgement and may be revised without an owner decision; **the dependencies it is derived from may not** |

---

## 1. Document control

| Item | Position |
| --- | --- |
| **Produced by** | Phase 3.9 — implementation planning. No application code was written in that phase |
| **Inputs** | The complete `docs/` tree, `.ai/`, [`documentation-audit-v1.0.md`](documentation-audit-v1.0.md), and a direct inspection of the repository at the baseline below |
| **Changes when** | A blocking decision is resolved, a milestone completes, or the dependency order is found to be wrong in practice |
| **Does not change when** | An implementation finds a requirement inconvenient. That path runs through §13 |
| **Status meaning** | `Draft` — the plan is usable for build sequencing; only the owner moves it to `Approved` (D-29) |

---

## 2. Purpose

The coding team should finish this document able to answer four questions:

```text
What do we build first?
What does it depend on?
What is blocked, and by what?
How do we know a piece is finished?
```

It should **not** yet answer *what exact SQL or PHP to write*. That is decided
when the slice is built, under the TRD and the data model, with the decision
gate in §9 already cleared.

**The problem this plan exists to solve.** Bulbula has seventy specification
documents, forty capabilities and sixty tracked decisions, of which
thirty-eight are open. Without an explicit order, an implementation agent will
either build in document order — which is not dependency order — or stall on a
blocker that does not actually block the work in front of it. §6 and §9 exist
to prevent both failures.

---

## 3. Authority

Repeated here because it is the rule most likely to be broken under build
pressure.

| Situation | Correct action | Source |
| --- | --- | --- |
| A requirement is unclear | Find its formal source; check the register; identify the dependency; **leave it open if unresolved** | AI-G-02 |
| No formal source exists for a change | That is a finding. **Stop and report** | AI-G-01 |
| Two formal documents disagree | **Stop and report both.** Do not pick one | AI-G-08 |
| This plan disagrees with a formal document | The formal document wins; correct this plan | IMP-0.1 |
| A product decision is needed | **Propose it. Do not make it** | AI-G-06 |
| Legal meaning is unclear | **PENDING COUNSEL.** Do not reason toward an answer | AI-G-04 |
| A threshold depends on the pilot | **PENDING PILOT.** Do not invent a number | AI-G-03 |

The full binding ruleset is [`../../.ai/ai-workflow-rules.md`](../../.ai/ai-workflow-rules.md).
§20 adds only what is specific to implementation sequencing; it does not
restate those rules.

---

## 4. Current repository baseline

Read from the repository during Phase 3.9, not from a previous phase report.

### 4.1 Runtime and dependencies

| Item | Verified value |
| --- | --- |
| PHP constraint | `^8.4` (toolchain in use: 8.4.26) |
| Autoloading | PSR-4, `Bulbula\` → `src/` |
| Runtime dependencies | `nikic/fast-route`, `monolog/monolog`, `psr/log`, `vlucas/phpdotenv`, plus `ext-pdo` and `ext-pdo_mysql` |
| Framework | **None**, by architecture rule and by test |
| ORM or query builder | **None** (TR-164) |
| Static analysis | PHPStan `level: max`, no baseline file |
| Style and refactoring | Pint (psr12), Rector over `config,public,src,tests` |
| Mutation testing | Infection, `minMsi` and `minCoveredMsi` both at 100, over the `unit` suite |

### 4.2 Code present

| Area | State |
| --- | --- |
| `src/` | **49 files.** `Foundation`, `Config`, `Logging`, `Error`, `Support`, `Exception`, `Http`, `Http\Routing`, `Http\Middleware`, `Http\Controller`, `Database`, `Database\Migrations`, `Console`, `Diagnostics`, `View` |
| Business features | **None.** No Business, Branch, Listing, Category, Area, Review, Save, Search, Campaign, Customer or staff entity exists in code |
| `routes/` | `web.php`, `api.php` — `GET /`, `/health`, `/health/ready`, `/api/v1/health`, `/api/v1/health/ready` |
| `database/migrations/` | **One** migration, `2026_10_04_120000_create_health_checks_table.php` |
| `resources/views/` | One template, `home.html` |
| `public/` | One entry point `index.php`, `.htaccess`, and the coming-soon assets |
| `config/` | `app.php`, `database.php`, `logging.php` |
| `bin/console` | CLI entry point for migrations and commands |
| `.github/workflows/` | `ci.yml`, `mutation.yml`, `security.yml`, plus `dependabot.yml` |
| `tools/` | Four read-only verifiers: `verify-docs.py`, `verify-ai-context.py`, `qa35.py`, `qa36.py` |

### 4.3 Quality baseline

| Measure | Value at this baseline |
| --- | --- |
| Tests | **424** |
| Assertions | **1,033** |
| Line coverage | **100 %**, enforced at `--min=100` |
| Type coverage | **100 %**, enforced at `--min=100` |
| Architecture assertions | **18**, in `tests/Arch/ArchTest.php` |
| Test files | 49, across `Unit`, `Feature`, `Arch` |
| Gate | `composer test` = validate → Rector → Pint → PHPStan → Pest → type coverage |

| Rule | Statement |
| --- | --- |
| IMP-4.1 | **These numbers are a floor, not a target.** They grow with the product |
| IMP-4.2 | **No threshold may be lowered and no mutant may be suppressed** to accommodate implementation. A surviving mutant is a weak test (D-26) |
| IMP-4.3 | **Every new namespace is added to `tests/Arch/ArchTest.php`** in the same change that creates it (TR-01). An unguarded namespace is an incomplete slice |

### 4.4 What the baseline means for planning

The foundation is real but deliberately empty: there is an HTTP kernel, a
router, a migration runner, a view renderer and a health check, and **nothing
of the product**. Consequently the first product slice is also the first real
exercise of every seam the architecture promises. That is an argument for
making it narrow and complete rather than wide and partial (§11).

---

## 5. Implementation principles

These are not new. Each is restated here because a build plan that omits them
invites the exact drift the architecture tests exist to catch.

### 5.1 Architecture

| Principle | Binding form | Source |
| --- | --- | --- |
| Framework-free PHP | Rejected and never added: no Laravel, no Symfony full-stack, no Laminas, no Mezzio, no Dotkernel, no Slim, no Flight, and no home-grown framework | Owner instruction; architecture test |
| PHP 8.4+ | Pinned by `composer.json` and by the handler block in `public/.htaccess` | TR-191 |
| Composer, PSR-4 | One namespace root, `Bulbula\` | Existing foundation |
| FastRoute | Routing is matching only; it holds **no** business rule | `architecture.md` §2.1 |
| PDO / MariaDB | Prepared statements only; **no** ORM, **no** DBAL, **no** query builder | TR-164, TR-170 |
| Explicit composition | `Application` is a composition root, **not** a container. **No** auto-wiring, **no** annotation scanning, **no** reflection magic | `architecture.md` §1.2 |
| Thin controllers | Read input, call **one** application service, render. **No** SQL, **no** rules, **no** HTML string building | `architecture.md` §2.1 |
| Application services | One method per meaningful use case; authorize, validate, call domain, persist, audit, notify | TR-04 |
| Repositories | SQL per aggregate; **no** component bypasses another area's repository | TR-16 |
| Front controller | `public/` contains exactly **one** executable entry point | TR-09 |
| Shared logic | **One** implementation of every rule, shared by Web, API and Telegram | D-49, TR-04 |

### 5.2 Clients

| Surface | Position |
| --- | --- |
| **Web** | First-class, server-rendered, mobile-first | D-52 |
| **Telegram Mini App** | First-class, **same** backend and domain model, Telegram specifics behind an adapter | D-49 |
| Flutter / native | **Not V1** | D-15, D-15r |

Every capability except SEO (C-37) and the operations capabilities
(C-19…C-29) must work on both surfaces, which makes **surface parity a test
concern from the first slice**, not a later integration task (TST-4.3).

### 5.3 Web delivery

| Principle | Binding form |
| --- | --- |
| Server-rendered | HTML is produced on the server |
| Progressive enhancement | Core content and the complete discovery journey work with JavaScript unavailable |
| **No** SPA | A single-page application was rejected; **nothing** in the build may reintroduce one (D-16) |

### 5.4 Background work

| Principle | Binding form | Source |
| --- | --- | --- |
| Cron-driven | Scheduling is cron; there is **no** daemon and **no** supervisor on the target host | TD-06, TR-189 |
| Database-backed queue | Asynchronous work is rows in a table drained by a console command | TD-06 |
| **No** mandatory broker | **No** message broker is required or assumed | TD-06 |
| Idempotent jobs | Every scheduled job is safe to run concurrently, twice, or not at all | TR-150 |

### 5.5 Search

| Principle | Binding form | Source |
| --- | --- | --- |
| MariaDB only | V1 search runs inside the database | TD-04 |
| **No** external engine | **No** Elasticsearch, **no** OpenSearch, **no** Meilisearch, **no** Typesense, **no** Solr is required or permitted in V1 | TD-04, NG-3 |
| Behind a port | The search interface is a port with one MariaDB adapter, so a future engine needs **no** application-service change | TR-48 |

### 5.6 Cache

| Principle | Binding form | Source |
| --- | --- | --- |
| HTTP caching first | Correct cache headers before any server-side cache | TD-05 |
| No mandatory cache service | Rejected for V1: no Redis and no Memcached. The application cache, where one is needed, is filesystem- or database-backed | TD-05, OT-03 |
| Measured, not assumed | A cache is added where measurement justifies it, never because it is familiar | TD-05 |

### 5.7 Outbound HTTP

| Principle | Binding form | Source |
| --- | --- | --- |
| One gateway | Exactly **one** namespace owns outbound HTTP; every other namespace reaches it through an interface | TR-01a, TD-07 |
| **Not** in a render path | **No** outbound HTTP call may occur while rendering a page; it belongs in console commands and queued jobs | TR-01d, TR-205 |
| Substituted in tests | **No** test reaches a real external service | TST-2.3 |

> **This one is not yet built, and it is a trap.** `curl_init` is currently
> confined to `Bulbula\Database` by an architecture assertion. The gateway
> namespace TD-07 describes **does not exist in `src/`**. The first slice that
> needs an outbound call must create that namespace **and** extend
> `tests/Arch/ArchTest.php` to permit it there and nowhere else. Relaxing the
> existing assertion without adding the replacement is the precise failure the
> boundary exists to prevent.

---

## 6. Implementation readiness

### 6.1 Readiness matrix

`READY` = specified, unblocked, buildable now. `READY WITH OPEN ITEMS` =
buildable now, with named gaps that must be closed before the area is
complete. `BLOCKED` = do not proceed past the stated line.

| Area | Status | Can code start? | Can production use? | Blocking dependency |
| --- | --- | ---: | ---: | --- |
| Foundation | `READY` | **Yes** | **Yes** | — |
| Database | `READY` | **Yes** | **No** | Migration machinery is ready and **M0 cleared the four schema decisions**. Production use follows the launch gate |
| Public Web | `READY` | **Yes** | **No** | Structure is specified; exact colours and the logo are open (D-53); production use follows the launch gate |
| Telegram | `READY WITH OPEN ITEMS` | **Yes** | **No** | D-38 navigation model; D-33 identity — neither is required for a surface |
| Customer Auth | `READY WITH OPEN ITEMS` | **Yes** | **No** | Providers fixed (D-48); linking rules open (D-13); OTP and session values open (OT-01, OT-02) |
| Reviews | `READY` | **Yes** | **No** | **D-34 approved 2026-10-07.** Subject, scale, states, window and deletion semantics are specified (`data-model.md` §6.1, TRD TR-59…TR-74) |
| Operations Console | `READY WITH OPEN ITEMS` | **Yes, in development** | **No** | **D-45.** Privileged staff functionality must not be treated as safe to operate until it is resolved |
| Security | `READY WITH OPEN ITEMS` | **Yes** | **No** | Public-surface controls are specified; **the staff mechanism is D-45** |
| Privacy | `BLOCKED` | **Yes, against synthetic data** | **No** | **L-5** lawful basis; **L-21 / D-46** retention. Real personal data may not be processed |
| Advertising | `READY WITH OPEN ITEMS` | **Yes, disabled** | **No** | **D-39** gates the first paid Campaign; **D-11**, L-17 and L-20 gate billing |
| SEO | `READY` | **Yes** | **No** | Web only (C-37); production indexing follows the launch gate |
| Observability | `READY` | **Yes** | **No** | Logging is specified; OT-07 deliberately adds nothing further in V1 |
| Launch | `BLOCKED` | n/a | **No** | All launch-class legal items, plus **D-31** pilot and **D-30n** numeric bar |

| Rule | Statement |
| --- | --- |
| IMP-6.1 | **`Can code start` and `Can production use` are different questions.** Nine areas can be built today; **none** can be used in production today |
| IMP-6.2 | A `BLOCKED` row is **not** an instruction to idle. It is an instruction to build something else from §6.2 |
| IMP-6.3 | The matrix is conservative by rule. Where readiness was arguable, the lower status was recorded |

### 6.2 What can begin safely, now

Each item below is genuinely independent of **every remaining** open
decision, including D-45. Nothing here creates a product table, a product
endpoint or a product rule.

**Since M0, this is no longer the only safe list.** The work in §6.3 is now
also available, because the four decisions that gated it were approved on
**2026-10-07**.

| # | Work | Why it is independent | Traces to |
| --- | --- | --- | --- |
| 1 | **Outbound-HTTP gateway namespace** and its architecture assertion | Pure infrastructure; no entity, no column | TR-01a, TD-07 |
| 2 | **Clock abstraction** and its injection | Required by TST-2.4 before any time-dependent rule exists | TR-05 |
| 3 | **Request-correlation id** middleware and its log field | Cross-cutting; no domain knowledge | TR-134, TR-136 |
| 4 | **CSRF token** issuance and verification for HTML form submissions | A property of the HTTP layer, not of any entity | TD-02 |
| 5 | **Input validation primitives** — typed readers, length and shape rules, rejection semantics | Generic; the rules they express come later | APP-2 |
| 6 | **Output encoding** in the view layer, context-correct by default | A renderer property | APP-4 |
| 7 | **Security headers** completion against the known required changes | Already partly shipped; no product dependency | APP-7.2 |
| 8 | **Error and problem-response shapes** for HTML and JSON, with no internal detail in production | Contract-level, entity-free | TR-144 |
| 9 | **Pagination, filtering and sorting primitives** with clamped bounds | Shape is fixed; OT-08 only chooses offset versus keyset internally | TR-30, TR-47 |
| 10 | **Database-backed queue table and drain command**, idempotent | Platform table, not a product table | TD-06, TR-150 |
| 11 | **Audit-entry writer**, append-only, failing its action on write failure | The schema is settled; it has no product columns | TR-08 |
| 12 | **Migration discipline** — reversibility, portability to SQLite, chunking helper | Machinery, not schema | TR-173, TR-175, TR-181 |
| 13 | **Test helpers** — in-memory database bootstrap, request builder, fixture factories for platform tables | Test infrastructure | §12 |
| 14 | **View infrastructure and layout primitives** against the design system's semantic tokens | D-53 leaves colour values open; structure does not depend on them | DSN tokens, D-53 |
| 15 | **Accessibility harness** — keyboard traversal, landmark and contrast assertions over the layout primitives | Independent of content | A11 rules |
| 16 | **Logging policy enforcement** — a test proving no credential, token, OTP or full personal record is logged | Invariant 15 | TR-135 |
| 17 | **Static page delivery** (C-18) with the policy pages left unwritten | The mechanism is independent; the *content* is `PENDING COUNSEL` | C-18 |

| Rule | Statement |
| --- | --- |
| IMP-6.4 | **Only genuinely independent work belongs in this list.** If an item needs to know what a Business, Branch, Category or Review *is*, it belongs in §6.3 |
| IMP-6.5 | Items 1–17 are permitted, **not required**. An abstraction still needs a requirement behind it, and §11 forbids building infrastructure for a feature that does not yet exist |

### 6.3 Unblocked by M0 — 2026-10-07

Everything in this table was previously gated on D-34, D-55, D-56 or D-57.
All four were approved on 2026-10-07, so **this work may now begin**, within
the boundaries the approved decisions set.

| Work | Was blocked by | Now proceeds within |
| --- | --- | --- |
| `business` and `branch` tables and their repositories | D-55 | The attribute boundary in `data-model.md` §3.2, including DM-R1 — no attribute may change level for convenience |
| `category`, `subcategory`, `alias` tables and the listing↔category relation | D-56, D-57 | Exactly two levels; one primary Category, zero or more secondaries, no duplicates, **no artificial maximum** |
| Taxonomy **reference-data framework** | D-56 | The framework may be built. The **catalogue content** is curated by Bulbula as an operations task and **must not** be invented or shipped in a migration (TR-177) |
| Category filtering | D-56, D-57 | The primary/secondary distinction is available to filtering; **ranking weights remain unspecified** (D-09) |
| Public Business profile composition | D-55 | Brand attributes from the Business, operational attributes from the selected Branch |
| Review table, `ReviewModeration`, `ReviewReport` | D-34 | `data-model.md` §6.1 and TRD TR-59…TR-74 |
| Review repository and service planning | D-34 | The same |
| Rating summary and its computation | D-34 | Unweighted mean of `published` ratings; Business figures aggregate Branch Reviews |
| Review APIs | D-34 | `api-spec-v1.0.md` §3.4, RV-1…RV-9 |
| Review moderation model | D-34 | Pre-publication: `pending` → `published` or `rejected`, with later removal still available |
| Search document and its refresh | D-55, D-56, D-57 | It denormalises the fields those decisions now place definitively |

**Still genuinely gated, and not by these four:**

| Work | Blocked by | Why |
| --- | --- | --- |
| Opening-hours **structure** | **D-04** | `data-model.md` §3.9 is deliberately unspecified. D-55 settled only that hours live on the **Branch** |
| Services, products and pricing **structure** | **D-44** | Same pattern: D-55 settled the level, not the shape |
| `area`, `sub_city` **seed content** | **D-40** | The structure is settled; the boundary is not |
| A **retention period** for a withdrawn Review | **L-21 / D-46** | **PENDING COUNSEL.** D-34 settled the mechanism, not the duration |
| Operations console for **production use** | **D-45** | Unchanged by M0 |

### 6.4 What must remain blocked

| Item | Blocked by | Nature of the block |
| --- | --- | --- |
| **Production launch** | All launch-class legal items, D-31, D-30n, D-42 | **Absolute.** Not a code state |
| **Processing real personal data in any environment** | **L-5**, **L-21 / D-46** | **Absolute.** Development uses synthetic data only |
| **Operating the operations console on real data** | **D-45** | The console may exist in development; it may not be *operated* |
| **The first paid Campaign** | **D-39**, D-11, L-17, L-20, readiness criteria 65–70 | Infrastructure may be built; delivery may not be activated |
| **Publishing a policy page with written legal content** | The counsel items | The page mechanism is unblocked; its text is not |
| **Any retention period, response window or minimum age in code** | **L-21 / D-46** | Configuration keys may exist; **values may not be invented** |
| **A staff authentication mechanism chosen by the implementer** | **D-45** | §15 |

| Rule | Statement |
| --- | --- |
| IMP-6.6 | **§6.2 is not a route around §6.3.** If a task in §6.2 starts needing a product column, it has become a §6.3 task and stops |
| IMP-6.7 | **A blocked item is never "temporarily implemented with a sensible default".** A guessed retention period, age or staff factor in code is a silent decision and a process failure |

---

## 7. Dependency graph

### 7.1 Derived order

Derived from the data model, the API contract, the architecture, the UX flows,
the security model and the operations model — not copied from a template.

```text
                         ┌──────────────────────────────┐
                         │ M0  Decision / schema gate   │  D-34 ✓ D-55 ✓ D-56 ✓ D-57 ✓
                         └───────────────┬──────────────┘
                                         │
        ┌────────────────────────────────┼────────────────────────────────┐
        │                                │                                │
┌───────▼────────┐              ┌────────▼─────────┐            ┌─────────▼────────┐
│ Foundation     │              │ View / layout    │            │ Test harness     │
│ (HTTP, gateway,│              │ primitives       │            │ + accessibility  │
│  queue, audit) │              │ (semantic tokens)│            │   harness        │
└───────┬────────┘              └────────┬─────────┘            └─────────┬────────┘
        │                                │                                │
        └────────────────┬───────────────┴────────────────────────────────┘
                         │                        these three are PARALLEL
             ┌───────────▼────────────┐
             │ Database + migrations  │   machinery ready today;
             │ discipline             │   product tables wait on M0
             └───────────┬────────────┘
                         │
             ┌───────────▼────────────┐
             │ Taxonomy + Locations   │   Category, Subcategory, Alias, Area, Sub-city
             │ (reference data)       │   D-56 ✓ D-57 ✓ · D-40 for content only
             └───────────┬────────────┘
                         │
             ┌───────────▼────────────┐
             │ Directory core         │   Business, Branch, Listing state, media refs
             │                        │   D-55 ✓ · D-04, D-44 for shape
             └───────┬────────┬───────┘
                     │        │
        ┌────────────▼──┐  ┌──▼──────────────┐
        │ Public profile│  │ Operations      │   Permission, Verification,
        │ + SEO + share │  │ lifecycle       │   Corrections, duplicates
        └────────┬──────┘  └──┬──────────────┘
                 │            │
        ┌────────▼────────────▼──────┐
        │ Search + autocomplete      │   needs taxonomy, directory, publication state
        │ (search document)          │
        └────────┬───────────────────┘
                 │
        ┌────────▼───────────────────┐        ┌──────────────────────────┐
        │ Identity (Customer)        │◄───────┤ Email / notifications    │
        │ Google · email OTP · session│       │ (adapter, no provider)   │
        └────────┬───────────────────┘        └──────────────────────────┘
                 │
        ┌────────▼────────┐   ┌──────────────────┐
        │ Saves           │   │ Reviews + reports│  D-34 ✓ (gate cleared)
        └────────┬────────┘   └────────┬─────────┘
                 │                     │
                 └──────────┬──────────┘
                            │
                 ┌──────────▼──────────┐
                 │ Moderation pipeline │
                 └──────────┬──────────┘
                            │
                 ┌──────────▼──────────┐
                 │ Operations console  │  ← D-45 for production use
                 │ (staff identity,    │
                 │  authorization,     │
                 │  audit, queues)     │
                 └──────────┬──────────┘
                            │
                 ┌──────────▼──────────┐
                 │ Advertising         │  built disabled · D-39 gates activation
                 └──────────┬──────────┘
                            │
                 ┌──────────▼──────────┐
                 │ Analytics + rollups │
                 └──────────┬──────────┘
                            │
                 ┌──────────▼──────────┐
                 │ Production hardening│
                 └──────────┬──────────┘
                            │
                 ┌──────────▼──────────┐
                 │ Launch              │  legal + pilot + D-30n
                 └─────────────────────┘
```

### 7.2 Why this order, and where it departs from the obvious

| Placement | Reason |
| --- | --- |
| **Taxonomy and Locations before Directory** | A Business cannot be classified or placed without them. They are also the cheapest real tables to build and the first honest test of the migration discipline. *This ordering was originally reinforced by Business and Branch being gated on D-55 and D-57; that gate is gone, and the ordering now rests only on the genuine dependency* |
| **Directory before Search** | The search document denormalises directory and taxonomy fields; building it first would mean rebuilding it (TR-45) |
| **Public profile before Search** | A profile page is reachable by slug with **no** search; search without a destination is untestable. The profile is the smaller and more complete slice |
| **Operations lifecycle alongside the public profile, not after** | Publication state is **on Business** (data model §3.3). Nothing can be published without the Permission and Verification gates, so the gates arrive with the entity (TR-49, TR-50) |
| **Identity after the public surface** | The complete discovery journey must work with **no** session and **no** cookie (invariant 12). Building identity first invites a design where anonymity is an afterthought |
| **Saves before Reviews** | Saves are the smaller slice — a two-column relation — so they prove the authenticated path end to end at the lowest cost. *The original reason, that Reviews were blocked on D-34, no longer applies: D-34 was approved on 2026-10-07. The ordering is kept on its own merits, not as a workaround* |
| **Reviews before Moderation** | A moderation queue with nothing to moderate cannot be tested meaningfully |
| **Console after the public domain** | The console is a view over domain that must already exist. Building it first produces screens over nothing and tempts direct SQL |
| **Advertising late** | It touches ranking integrity, which is the single most important invariant. It should land on a stable, well-tested ranking path |
| **Analytics late** | Rollups aggregate events the rest of the product emits |

### 7.3 Independent branches

These pairs share no table and no service, and may proceed concurrently:

| A | B | Shared risk |
| --- | --- | --- |
| View and layout primitives | Database and migration discipline | None |
| Accessibility harness | Outbound-HTTP gateway | None |
| Email adapter (no provider) | Taxonomy tables | None |
| Telegram surface adapter | Search ranking internals | None — the adapter consumes the same services |
| SEO metadata and sitemap mechanics | Operations lifecycle | None |
| Static page delivery mechanism | Identity | None |

Synchronization points are in §10.4.

---

## 8. Module boundaries

Module names follow the glossary and the namespaces already indicated in
`architecture.md` §3.1. **This section creates no directory.** A directory
appears when the code that belongs in it does.

### 8.1 Foundation

| Field | Value |
| --- | --- |
| **Purpose** | Boot, configuration, logging, error handling, the console runner, health |
| **Source requirements** | `architecture.md` §4, §12, §13; TR-192…TR-194 |
| **Depends on** | Nothing |
| **Entities** | None |
| **Services** | `Application`, `Services`, `Environment`, `HttpKernelFactory` |
| **APIs** | `/api/v1/health`, `/api/v1/health/ready` |
| **Tests** | Unit for config and environment; feature for boot failure; arch for `Env` confinement |
| **Open decisions** | D-23 (secret custody, deployment-time), D-20 (host limits) |
| **Downstream** | Everything |

### 8.2 HTTP

| Field | Value |
| --- | --- |
| **Purpose** | Request, Response, Status, Method, routing, middleware pipeline, emission, CSRF, correlation, rate limiting, security headers, problem responses |
| **Source requirements** | `architecture.md` §5–§7, §12; APP-5, APP-7; TR-30, TR-47, TR-144 |
| **Depends on** | Foundation |
| **Entities** | None |
| **Services** | Middleware set; pagination primitives; error handler |
| **APIs** | The envelope and error contract of every endpoint |
| **Tests** | Unit per middleware; feature for the pipeline; arch for "no SQL in `Bulbula\Http`" |
| **Open decisions** | OT-08 (pagination style) — internal, contract-neutral |
| **Downstream** | Every surface module |

### 8.3 Identity

| Field | Value |
| --- | --- |
| **Purpose** | Customers, provider identities, sessions, OTP, staff users, roles and named permissions, staff factors |
| **Source requirements** | `auth-identity.md`; TRD §14–§15; `authentication-security-v1.0.md`; C-30…C-33, C-36 |
| **Depends on** | Foundation, HTTP, Mail, outbound-HTTP gateway (for Google) |
| **Entities** | Customer, ProviderIdentity, OtpRequest, Session, StaffUser, Role, Permission, StaffAuthFactor |
| **Services** | Sign-in with Google; request and verify OTP; session issue, rotate, revoke; permission resolution |
| **APIs** | `/auth/*`, `/me`, `/auth/session` |
| **Tests** | Unit on token and OTP logic with an injected clock; feature on the full sign-in path; security tests for enumeration resistance and single-use codes |
| **Open / blocked** | **D-45 for staff** · D-13 linking · D-33 Telegram identity · OT-01, OT-02 values · D-46 minimum age **PENDING COUNSEL** |
| **Downstream** | Authorization, Saves, Reviews, Reports, Customer account, Console |

### 8.4 Authorization

| Field | Value |
| --- | --- |
| **Purpose** | Server-side permission enforcement for every Customer and staff action |
| **Source requirements** | TRD §15; TD-03; TR-33, TR-35, TR-36, TR-40 |
| **Depends on** | Identity |
| **Entities** | Permission (named strings), Role assignment |
| **Services** | Permission check at the application-service boundary |
| **APIs** | Every non-public endpoint |
| **Tests** | Feature tests asserting denial for the wrong actor; a test that **no** Business principal exists (TR-40) |
| **Open decisions** | D-14 — **non-blocking**: permissions exist as data, screens name permissions, not roles |
| **Downstream** | Operations console, Customer account, Advertising |

### 8.5 Taxonomy

| Field | Value |
| --- | --- |
| **Purpose** | Category, Subcategory and controlled Aliases — exactly two levels, Bulbula-owned |
| **Source requirements** | D-06; `data-model.md` §3.4–§3.5; C-04, C-06, C-23 |
| **Depends on** | Database |
| **Entities** | Category, Subcategory, Alias |
| **Services** | Tree read; create, edit, hide, merge with redirect |
| **APIs** | `/categories`, `/categories/{slug}`, `/ops/categories*` |
| **Tests** | Unit on slug stability and two-level enforcement; feature on merge-and-redirect; integrity test that deletion is refused while classified |
| **Blocked by** | **Nothing.** D-56 and D-57 were approved on 2026-10-07. The **catalogue content** remains an operations deliverable and **must not** be shipped in a migration (TR-177) |
| **Downstream** | Directory, Search, Advertising targeting, SEO |

### 8.6 Locations

| Field | Value |
| --- | --- |
| **Purpose** | Area and Sub-city reference data, with Aliases |
| **Source requirements** | `data-model.md` §3.6; GEO rules; C-05, C-06, C-24 |
| **Depends on** | Database, Taxonomy (Alias is shared) |
| **Entities** | Area, SubCity, Landmark |
| **Services** | Area read; area management |
| **APIs** | `/areas`, `/areas/{slug}`, `/ops/areas*` |
| **Tests** | Unit on slug and hierarchy; feature on empty-area suppression |
| **Open decisions** | **D-40** — affects **seed content**, not structure |
| **Downstream** | Directory, Search, Nearby, SEO |

### 8.7 Directory

| Field | Value |
| --- | --- |
| **Purpose** | Business, Branch, Listing publication state, classification, media references, the public profile |
| **Source requirements** | D-02, D-03, D-50, D-54; `data-model.md` §3; TRD §17; C-08…C-12 |
| **Depends on** | Taxonomy, Locations, Media, Database |
| **Entities** | Business, Branch (Listing is the **published projection**, not a table — TRD A-2) |
| **Services** | Profile composition; lifecycle transitions; duplicate detection; correction recording |
| **APIs** | `/businesses/{slug}`, `/businesses/{slug}/branches`, `/businesses/nearby`, `/ops/businesses*`, `/ops/branches*` |
| **Tests** | State-machine unit tests; feature tests for PR-1…PR-6; integrity tests for TR-49, TR-50, TR-52, TR-53, TR-57 |
| **Blocked by** | D-04 (hours **structure**) · D-44 (services and products **structure**) · D-09 (completeness definition) |
| **No longer blocked by** | **D-55** — closed at the M0 gate on 2026-10-07; the attribute boundary is specified in `data-model.md` §3.2 |
| **Downstream** | Search, Reviews, Saves, Advertising, Analytics, SEO |

### 8.8 Operations (provenance and quality)

| Field | Value |
| --- | --- |
| **Purpose** | Permission records, Verification records, Corrections, Reports, work queues |
| **Source requirements** | D-50; `listing-operations.md`; `data-model.md` §4; C-19…C-21, C-26 |
| **Depends on** | Directory, Identity, Authorization, Audit |
| **Entities** | PermissionRecord, VerificationRecord, ListingChange, Report, queue projections |
| **Services** | Record permission; record verification; publish with gates; resolve report |
| **APIs** | `/ops/businesses/{id}/permission`, `/verifications`, `/ops/queues/*`, `/reports` |
| **Tests** | **Invariant 2** — publication refused with no Permission, **by every path** (TST-3.3); verification-age query without a batch job (TR-54) |
| **Open decisions** | D-43 (record contents) · D-08 (rules and interval) · D-35 (structured suggestions) |
| **Downstream** | Console, Analytics, Trust indicators |

### 8.9 Media

| Field | Value |
| --- | --- |
| **Purpose** | Upload validation, derivatives, attachment, storage behind an adapter |
| **Source requirements** | TRD §20; APP-6; PBD photography rules; C-22 |
| **Depends on** | Foundation, Authorization, Audit |
| **Entities** | MediaAsset, MediaAttachment |
| **Services** | Validate, store, derive, attach, remove |
| **APIs** | `/ops/media*` |
| **Tests** | Security tests for type and content validation and execution prevention; unit tests on the storage-key abstraction |
| **Open decisions** | D-25 (provider, limits, formats) — **local filesystem adapter is the V1 default**, so this does **not** block · OT-09 (sync or queued derivatives) · **L-18 PENDING COUNSEL** for photography policy |
| **Downstream** | Directory, public profile |

### 8.10 Search

| Field | Value |
| --- | --- |
| **Purpose** | Query interpretation, candidate retrieval, filtering, ranking, autocomplete, the maintained search document |
| **Source requirements** | `search-design.md`; TRD §16; TD-04; C-02, C-03, C-07 |
| **Depends on** | Directory, Taxonomy, Locations |
| **Entities** | SearchDocument (derived, rebuildable) |
| **Services** | Search port with one MariaDB adapter; suggestion service; rebuild command |
| **APIs** | `/search`, `/search/suggest` |
| **Tests** | **Invariant 1** — identical organic ordering with and without an active Campaign (TST-3.2); Amharic alias matching; bounded queries; suggestion failure must **not** break plain search |
| **Open decisions** | TR-42 — **no ranking weights are defined in V1**; they are tunable configuration, never invented here · OT-04 (refresh strategy) |
| **Downstream** | Homepage, category and area pages, Nearby |

### 8.11 Community

| Field | Value |
| --- | --- |
| **Purpose** | Reviews, rating summary, Saves, review moderation and review reports |
| **Source requirements** | D-12; `review-policy.md`; TRD §18; C-13, C-14, C-25, C-34, C-35 |
| **Depends on** | Identity, Directory, Notifications, Audit |
| **Entities** | Review, ReviewModeration, ReviewReport, Save, rating summary (derived) |
| **Services** | Submit, edit, delete own Review; moderate; report; save and unsave |
| **APIs** | `/branches/{id}/reviews`, `/me/reviews`, `/me/saves`, `/reviews/{id}/reports`, `/ops/queues/reviews` |
| **Tests** | Invariants 9, 10 and 11; uniqueness enforced in the **database**, not only in code (TR-160); **no** reply affordance anywhere (TR-67) |
| **Blocked by** | **Nothing.** D-34 was approved on 2026-10-07. Maximum text length, rate limits and anomaly thresholds are **configuration** and **must not** be pinned in a test (TST-4.5); the **retention period** for a withdrawn Review remains **PENDING COUNSEL** (L-21, D-46) |
| **Note** | Saves remain independent of Reviews and are implementable as soon as Identity and Directory exist |
| **Downstream** | Trust indicators, Analytics, Moderation operations |

### 8.12 Notifications and email

| Field | Value |
| --- | --- |
| **Purpose** | Message composition and a transport adapter |
| **Source requirements** | D-24; TRD §21; C-31, C-39 |
| **Depends on** | Foundation, queue, outbound-HTTP gateway |
| **Entities** | NotificationMessage, DeliveryAttempt |
| **Services** | Compose, enqueue, send, record attempt |
| **APIs** | None public |
| **Tests** | Unit on composition; feature on queueing; **no** test reaches a real provider |
| **Open decisions** | **D-41** provider — the adapter is built without one; a null or log transport serves development |
| **Downstream** | Identity (OTP), Community, Operations, Customer account |

### 8.13 Advertising

| Field | Value |
| --- | --- |
| **Purpose** | Packages, Placements, Campaigns, targeting, delivery records |
| **Source requirements** | D-10; `advertising-products.md`; `advertising-operations-v1.0.md`; TRD §19; C-16, C-27 |
| **Depends on** | Directory, Authorization, Audit, Search (slot merge only) |
| **Entities** | Package, Placement, Campaign, CampaignTarget, CampaignDelivery |
| **Services** | Create, approve, suspend, cancel; inventory availability; slot selection |
| **APIs** | `/ops/campaigns*`, `/ops/placements/{key}/availability`; the separate `sponsored` array on `/search` and `/discovery/home` |
| **Tests** | Invariants 1, 7, 8; CR-1 and CR-3 — **no** endpoint accepts a bid, budget or performance-pricing value |
| **Blocked by** | **D-39** for the first paid Campaign · **D-11**, L-17, L-20 for billing · no price and no inventory count is approved |
| **Downstream** | Analytics |

### 8.14 Customer account

| Field | Value |
| --- | --- |
| **Purpose** | Minimal profile, Save management, own-review management, export and deletion |
| **Source requirements** | C-32…C-36; `data-subject-rights-v1.0.md` |
| **Depends on** | Identity, Community, Notifications |
| **Entities** | Reuses Customer, Save, Review |
| **Services** | Read and update profile; export; request and confirm deletion |
| **APIs** | `/me`, `/me/saves`, `/me/reviews`, `/me/data`, `/me/deletion` |
| **Tests** | Deletion cascade correctness — Saves go, **audit and moderation history stay** (TR-167) |
| **Blocked by** | **L-7 PENDING COUNSEL** for windows and export format · **L-21 / D-46 PENDING COUNSEL** for how long a withdrawn Review is retained. The deletion *behaviour* itself — withdrawal — is settled by D-34 |
| **Downstream** | Privacy compliance |

### 8.15 Audit

| Field | Value |
| --- | --- |
| **Purpose** | Append-only log of privileged actions |
| **Source requirements** | C-29; TR-08; OPX-0.3 |
| **Depends on** | Foundation, Identity |
| **Entities** | AuditEntry |
| **Services** | Write entry; read with Administrator permission only |
| **APIs** | `/ops/audit` |
| **Tests** | **Invariants 3 and 6** — every staff action that changes published data writes an entry, and a failed audit write fails its action |
| **Open decisions** | D-14 for which permission reads it — non-blocking |
| **Downstream** | Console, Operations, Advertising |

### 8.16 Analytics

| Field | Value |
| --- | --- |
| **Purpose** | Event recording and rollups for operational metrics |
| **Source requirements** | C-28, C-38; TRD §26; `analytics-operations-v1.0.md` |
| **Depends on** | Most modules as emitters; queue for rollups |
| **Entities** | AnalyticsEvent, AnalyticsRollup |
| **Services** | Record event; roll up; read operational metrics |
| **APIs** | `/ops/analytics/*` |
| **Tests** | **Invariant 4** — an event stored for a Guest contains nothing identifying that Guest (TR-202) |
| **Open decisions** | **D-27** granularity, retention, raw-event policy |
| **Downstream** | Console, launch readiness evidence |

### 8.17 Operations console

| Field | Value |
| --- | --- |
| **Purpose** | The staff surface over Directory, Operations, Taxonomy, Locations, Community moderation, Advertising, Analytics and Audit |
| **Source requirements** | `operations-console-ux-v1.0.md`; `operations-model-v1.0.md`; C-19…C-29 |
| **Depends on** | Every module it presents, plus Identity, Authorization, Audit |
| **Entities** | None of its own |
| **Services** | None of its own — it calls the same application services the API calls |
| **APIs** | `/api/v1/ops/*` |
| **Tests** | O-1…O-8; **no** Customer token reaches an operations endpoint; Administrator-only endpoints check the **named permission**, never console membership (TR-36) |
| **Blocked by** | **D-45 for production use** (§15) |
| **Downstream** | None |

### 8.18 Platform adapters

| Field | Value |
| --- | --- |
| **Purpose** | Web surface specifics and Telegram Mini App context validation |
| **Source requirements** | D-49; `web-platform-v1.0.md`; `telegram-mini-app-v1.0.md`; `shared-client-contract-v1.0.md` |
| **Depends on** | HTTP, View, and whatever capability is being surfaced |
| **Entities** | None |
| **Services** | Launch-context validation, surface resolution, navigation adaptation |
| **APIs** | `/auth/telegram/context` — establishes a **surface**, **not** an identity |
| **Tests** | Surface parity per capability; validation failure degrades to the full Guest experience, not an error |
| **Open decisions** | D-38 navigation model · D-33 identity · OT-05 frame-ancestors policy |
| **Rule** | **The adapter never duplicates a business rule.** A behaviour that differs between Web and Telegram is a product decision, not an implementation choice |

---

## 9. Data-model gate

> ## `M0 — COMPLETE`
>
> | Decision | Status |
> | --- | --- |
> | **D-34** Review mechanics | ✓ **Approved 2026-10-07** |
> | **D-55** Business versus Branch attribute boundary | ✓ **Approved 2026-10-07** |
> | **D-56** Category catalogue production | ✓ **Approved 2026-10-07** |
> | **D-57** Category cardinality per Listing | ✓ **Approved 2026-10-07** |
>
> **The four decisions that blocked schema definition have now been
> approved. Schema implementation may proceed within the boundaries defined
> by the approved decisions and the remaining open decisions.**

The gate was cleared the way IMP-9.4 requires — by **register entries**, not
by a conversation. The four entries are in
[`../60-decisions/decision-register.md`](../60-decisions/decision-register.md)
§1, approved on **2026-10-07** under "Owner approval during M0
implementation/schema gate".

This section is kept rather than deleted. The analysis below is what made
the decisions answerable, and §9.2 now records **what was decided** so that
an implementation agent reads the boundary, not the history of the question.

### 9.1 Why these four and not the other thirty-four

The test that selected them, unchanged:

| Test | Result |
| --- | --- |
| Does the decision change which **columns** exist? | D-34, D-55 — yes |
| Does it change a **relationship, its cardinality or its constraints**? | D-34, D-57 — yes |
| Does it change **reference-data structure**? | D-56 — yes |
| Does it only change a **value, threshold, policy or seed row**? | Every other open decision — the schema accommodates it already |

Decisions such as D-04, D-08, D-09, D-13, D-14, D-21, D-25, D-27, D-41, D-43
and D-44 are open but **non-blocking**, because the data model already records
them as deliberate holes with a documented default, an adapter, or a
configuration key. Changing them later is a data or configuration change, not
a migration of a shipped table.

**That analysis held.** The four that shaped tables were taken; the rest
remain open and remain non-blocking.

### 9.2 What was decided — the schema boundary

This is the operative subsection. An implementation agent needs **this**,
not the pre-decision analysis.

#### D-34 — Review mechanics

| Aspect | Approved position |
| --- | --- |
| **Subject** | A Review belongs to a **Branch**. No Review is stored against a Business; Business-level figures are **aggregates** across its Branches |
| **Rating** | A **required integer from 1 to 5**, enforced in the database |
| **Text** | **Optional** — a rating-only Review is valid and complete |
| **Uniqueness** | **At most one active Review per Customer per Branch**, enforced in the database over `(customer_id, branch_id)`. Re-reviewing is an **edit**, never a second row |
| **Text length** | **Not decided, deliberately.** A maximum is **configuration**, not schema |
| **Edit window** | **30 days from creation**, enforced server-side. An accepted edit returns the Review to `pending` for re-moderation |
| **Author deletion** | **Withdrawal — a soft delete.** Public visibility ceases and the Review leaves every summary; the internal record may be retained for retention, audit, abuse and legal purposes |
| **Retention of that record** | **Not decided — PENDING COUNSEL (L-21, D-46).** It **must not** be invented in code or in a test |
| **Moderation order** | **Pre-publication.** `pending` → approval → `published`; a `rejected` Review is never public. Post-publication **removal** remains available |
| **Rating summary** | **Arithmetic mean of currently `published` ratings, unweighted.** `pending`, `rejected`, `removed` and `deleted` are excluded |
| **Default ordering** | **Newest `published` first** |
| **Minimum account age** | **None** |
| **Appeals** | Administrator-handled. **No deadline is set** |
| **Rate limits, anomaly thresholds** | **Configuration.** No values are set |
| **D-39** | **Not closed by D-34.** Commercial–editorial integrity remains open and still blocks the first paid Campaign |

**Specified in:** `data-model.md` §6.1 and §6.5 · TRD TR-59…TR-74 ·
`api-spec-v1.0.md` §3.4 RV-1…RV-9 · `review-policy.md` §2, §4, §9.

#### D-55 — Business versus Branch attribute boundary

| Level | Attributes |
| --- | --- |
| **Business** | Name, description, website, brand-level and public social links, and other genuinely brand-level attributes |
| **Branch** | Address, Area, Sub-city, Landmark, latitude, longitude, phone, branch email, opening hours, branch-specific services, products and pricing, and other location-specific operational information |

Reviews attach to a **Branch**; Business rating figures are derived.
Location-meaningful analytics attach to a **Branch**; Business analytics are
aggregates. **Media may attach to either.**

> **The rule that outlives the list:** an attribute **must not** be moved
> between the Business and Branch levels for implementation convenience. A
> move requires a decision (`data-model.md` DM-R1).

**D-04 and D-44 are not closed by this.** D-55 settles the *level* at which
hours and services live; their *structure* remains open.

#### D-56 — Category catalogue production

| Aspect | Approved position |
| --- | --- |
| **Ownership** | Bulbula **centrally owns and manually curates** the catalogue |
| **Source** | Produced from the real launch-area business inventory and genuine discovery and search needs |
| **Depth** | **Exactly two levels** — Category → Subcategory (consistent with D-06) |
| **User-created entries** | **None.** No User or Operator creates a Category outside administrative catalogue management |
| **Labels** | **English required**; Amharic supported where available; controlled Aliases supported |
| **Gaps** | Resolved by an **Administrator-controlled catalogue change**, not by a field invention |
| **Nature** | **Reference data, not application schema.** A catalogue change **must not** require a code change or a migration where the model already supports it |
| **Not decided** | The number of Categories or Subcategories, any coverage percentage, a launch date, and the catalogue list itself. **None of these may be invented** |

**Consequence for implementation:** the taxonomy tables and the
reference-data framework may be built now. A seed migration that ships a
catalogue is a **defect** (TR-177, REL-6.10). Development fixtures must be
obviously synthetic.

#### D-57 — Category cardinality per Listing

| Constraint | Approved position |
| --- | --- |
| **Primary** | Every Listing has **exactly one** Primary Category, **required** for every published Listing |
| **Secondary** | **Zero or more.** Zero is permitted |
| **Duplicates** | A Category **must not** appear twice for one Listing |
| **Overlap** | The Primary **must not** also be a Secondary |
| **Maximum** | **No artificial numeric maximum.** None may be invented, and none is exposed to Users |
| **Depth** | The taxonomy stays two levels; compatible with D-56 |

**Specified in:** `data-model.md` §3.4 · PRD BM-7 · `listing-operations-v1.0.md`
LO-6.9, which makes a Primary Category mandatory before publication.

#### Dependency table — after M0

| Decision | Entity impact | Migration impact | Can code proceed? |
| --- | --- | --- | --- |
| **D-34** | Review, ReviewModeration, ReviewReport, rating summary | Review tables may be created, with `branch_id`, the 1–5 check and the per-Branch uniqueness index | **Yes** |
| **D-55** | Business, Branch | Both migrations may be finalised against the §3.2 boundary | **Yes** |
| **D-56** | Category, Subcategory, Alias | Tables yes; **catalogue content never in a migration** | **Yes** for the tables and the framework. **No** for the content |
| **D-57** | `business_category` join | The join and its constraints may be created together, including the single-primary constraint | **Yes** |

### 9.3 Rules that survive the gate

Clearing M0 removes four blockers. It does not relax the discipline that
made them visible.

| Rule | Statement |
| --- | --- |
| IMP-9.1 | **Do not create a table whose columns are the subject of an open decision.** D-04 and D-44 are still open: the hours structure and the services, products and pricing structure **must not** be invented. Create the tables above and below them instead |
| IMP-9.2 | **Do not encode a provisional answer in a migration.** A documented default is *documentation of a pending question*, not permission to ship it |
| IMP-9.3 | **Do not pin an open value in a test.** The rating scale, the 30-day window and the single-primary constraint are now **settled** and may be asserted. The maximum text length, rate limits, anomaly thresholds and any retention period are **not**, and asserting them converts an open decision into a silent one (TST-4.5) |
| IMP-9.4 | The gate is cleared by a **register entry**, not by a conversation, a comment or this document. For these four, those entries exist |
| IMP-9.5 | **Closing four decisions closes four decisions.** It does not close the remaining product and technical decisions, the legal items, staff authentication (D-45), Telegram identity (D-33), the launch threshold (D-30n) or production readiness |

### 9.4 Migration strategy

Documented here as sequencing discipline. **No SQL migration is created in
this phase**, and none may be written for a table whose shape is open.

| Aspect | Rule | Source |
| --- | --- | --- |
| **Ordering** | Filename order, `YYYY_MM_DD_HHMMSS_description.php`; batches tracked in the `migrations` table | Existing system |
| **Dependency order** | Platform tables (queue, audit, analytics) → reference data (taxonomy, locations) → directory (business, branch, join) → operations records → identity → community → advertising | §7.1 |
| **Forward migrations** | Every migration is reversible, **or** states in a comment why it is not and what the recovery procedure is | TR-175 |
| **Rollback** | By batch, through the existing migrator. Rollback is expected to work in development and is **not** the production recovery strategy — that is backup and restore | TR-175; `backup-recovery-v1.0.md` |
| **Additive first** | A destructive change is split expand → migrate data → contract across **separate deployments** | TR-176 |
| **Seed and reference data** | A migration carries only reference data the system needs to **function**. The Category catalogue and the Area set are **content**, seeded by console command | TR-177 |
| **Determinism** | **No** dependence on the current date, environment or existing production content | TR-178 |
| **Integrity constraints** | Uniqueness that matters is enforced by the **database**, not only by application code | DM-4, TR-160 |
| **Indexes** | Added in the **same migration** as the table, designed from the queries that need them | TR-169, TR-179 |
| **Foreign keys** | Explicit, with explicit referential rules. Deleting a Customer **must not** silently delete audit or moderation history | TR-166, TR-167 |
| **Portability** | The suite runs on in-memory SQLite, so migrations stay in portable SQL or branch per engine. Anything collation-, full-text- or strictness-dependent is verified against MariaDB **outside** the suite | TR-173, TST-1.3 |
| **Production safety** | Migrations are run as a deliberate step, never by the deployment cron; a long migration is chunked or run as a console command | TR-180, TR-181 |
| **Character set** | `utf8mb4` throughout, so Amharic stores and compares correctly | TR-165, D-18 |
| **Time** | Stored in UTC, rendered in `Africa/Addis_Ababa` | TR-168, DM-9 |

| Rule | Statement |
| --- | --- |
| IMP-9.5 | **One concern per migration.** A migration that creates three unrelated tables cannot be reverted usefully |
| IMP-9.6 | **A migration is accompanied by a test that proves the constraint it claims to add** — uniqueness, foreign key, nullability. An unverified constraint is a comment |
| IMP-9.7 | Personal-data columns are identifiable from the schema documentation so a rights request can be executed completely (TR-172) |

---

## 10. Implementation sequence

### 10.1 Milestones

**No dates. No durations. No estimates.** A milestone completes when its exit
criteria are met.

| ID | Milestone | Content | Entry condition | Exit condition |
| --- | --- | --- | --- | --- |
| **M0** | **Decision and schema gate** — ✅ **COMPLETE 2026-10-07** | Resolve **D-34, D-55, D-56, D-57** | This plan is read | **Met.** All four are Approved register entries; §9.2 records the resulting schema boundary |
| **M1** | **Foundation** | §6.2 items: outbound-HTTP gateway, clock, correlation, CSRF, validation primitives, output encoding, error shapes, pagination, queue, audit writer, migration discipline, test and accessibility harnesses, view primitives | None — may start **before** M0 | All new namespaces guarded by architecture assertions; gate green |
| **M2** | **Core directory** | Taxonomy, Locations, Business, Branch, classification, media references, **public Business profile** | **Met** — M0 cleared D-55, D-56 and D-57 on 2026-10-07 | A real Business profile renders on Web and in the Mini App, from the database, with the publication gates enforced |
| **M3** | **Discovery** | Search, autocomplete, filters, category and area pages, category × area, nearby, open-now, homepage | M2 | Invariant 1 is an executing test; zero-result recovery works |
| **M4** | **Customer identity** | Google, email OTP, sessions, profile, **Saves** | M1; M2 for a Business to save | A Guest can complete the whole discovery journey with **no** session, and an authenticated Customer can Save |
| **M5** | **Reviews and reports** | Review submission, edit, delete, moderation pipeline, review reports, guest problem reports | **M0 cleared D-34 on 2026-10-07**; M4 | Invariants 9, 10 and 11 execute; **per-Branch** uniqueness is enforced in the database |
| **M6** | **Operations console** | Staff identity and authorization, listing lifecycle, verification, taxonomy, locations, moderation, report triage, audit reader | M2, M5; **D-45 for production use** | Every mutating request audited; O-1…O-8 tested; **operational use still gated** |
| **M7** | **Web and Telegram completion** | Surface-specific behaviour, navigation model, SEO, sharing, static pages, host adapters | M3 | Surface parity tested per capability; the Mini App degrades to Guest on validation failure |
| **M8** | **Advertising** | Packages, placements, campaigns, inventory, delivery records, labelled sponsored slots — **built disabled** | M3, M6 | Invariants 1, 7 and 8 execute; activation is impossible without the §16 checks |
| **M9** | **Production hardening** | Security headers and rate limiting at production settings, secrets, logging review, performance budget, observability, backup and recovery rehearsal, accessibility audit, SEO verification | M7 | The readiness criteria that are self-assessable are met |
| **M10** | **Launch** | Nothing is built. Readiness is reviewed | M9; pilot complete | Every launch-gate item in §17 is satisfied |

| Rule | Statement |
| --- | --- |
| IMP-10.1 | **M1 does not wait for M0.** Foundation work is independent of the four decisions, and starting it is the correct response to the gate |
| IMP-10.2 | **M0 is not a build milestone.** It is an owner action, and it is the only milestone an implementation agent cannot advance |
| IMP-10.3 | **A milestone is not complete because its code exists.** It is complete when its exit condition is demonstrated by a test or a documented manual verification |
| IMP-10.4 | **M6 completing does not make the console usable.** §15 |

### 10.2 Capability coverage by milestone

| Milestone | Capabilities |
| --- | --- |
| M1 | C-18 mechanism only |
| M2 | C-04, C-05, C-08, C-09, C-11, C-12, C-22 |
| M3 | C-01, C-02, C-03, C-06, C-07 |
| M4 | C-30, C-31, C-32, C-33, C-14, C-34 |
| M5 | C-13, C-15, C-25, C-26, C-35 |
| M6 | C-19, C-20, C-21, C-23, C-24, C-28, C-29 |
| M7 | C-10, C-17, C-18, C-37, C-39 |
| M8 | C-16, C-27 |
| M9 | C-38, C-40, C-36 |

C-36 appears at M9 because account deletion and export depend on every table
that holds customer data existing first, and on **L-7** for its windows and
format.

### 10.3 Parallel work

| Track A | Track B | Track C | Safe because |
| --- | --- | --- | --- |
| Backend foundation (M1 items 1–12) | View and layout primitives (M1 item 14) | Test and accessibility harness (M1 items 13, 15) | No shared table, no shared service |
| Taxonomy tables | Locations tables | Media validation | Three independent reference areas; only Alias is shared, and it is written once |
| Search internals | Telegram surface adapter | SEO metadata mechanics | The adapter and SEO consume services; they do not define them |
| Email adapter | Queue drain command | Audit writer | Platform-level, mutually independent |
| Console screens for an area | The API endpoints for that same area | — | **Not safe.** See §10.4 |

| Rule | Statement |
| --- | --- |
| IMP-10.5 | **Do not parallelize two tracks that can create incompatible schema or API assumptions.** Two people inventing the same table is worse than one person waiting |
| IMP-10.6 | Parallel tracks merge through the same gate. A track that cannot pass `composer test` on its own does not merge |

### 10.4 Synchronization points

| # | Point | What must be agreed before the tracks diverge again |
| --- | --- | --- |
| S1 | **After M0** | The four decisions, read back into §9 |
| S2 | **Before the first product migration** | Table and column naming, timestamp conventions, soft-delete policy per table, the slug strategy |
| S3 | **Before the first public endpoint** | The response envelope, error envelope, pagination style (OT-08) and cache headers, applied identically everywhere |
| S4 | **Before the Telegram adapter does anything real** | The shared client contract, so the adapter consumes services rather than forking rules |
| S5 | **Before the first staff endpoint** | The named-permission vocabulary (TD-03) — permissions as data, never role checks in code |
| S6 | **Before the first sponsored slot renders** | The organic-ordering invariant test must already exist and pass |
| S7 | **Before any real personal data is entered anywhere** | **L-5 and L-21.** Until then, synthetic data only |

---

## 11. Vertical-slice plan

### 11.1 The first slice

**Selected slice — the Taxonomy and Locations reference spine, with a
read-only public Category and Area browse surface.**

```text
Database foundation
→ Category
→ Subcategory
→ Alias
→ Area
→ Sub-city
→ public Category and Area browse pages (Web + Mini App)
→ /categories and /areas API
```

**Why not the obvious slice.** The intuitive first slice is
`Business → Branch → Listing → public profile`. It remains the right
**second** slice — but for a different reason than when this plan was
written.

*Originally* it was unavailable, because Business and Branch columns were
the direct subject of **D-55** and the listing↔category relation the subject
of **D-57**. **M0 removed that gate on 2026-10-07**, so the second slice is
now merely *second*, not *blocked*.

It stays second on its own merits: a Business cannot be classified or placed
until Categories and Areas exist, so taxonomy and locations are a genuine
prerequisite rather than a workaround. The first slice is also the cheaper
and safer first exercise of the full stack.

**Why this slice is nonetheless a real slice and not busywork.**

| Criterion | How this slice meets it |
| --- | --- |
| Genuinely useful | Category and Area browsing are C-04 and C-05 — two of the forty approved capabilities, and part of the SEO surface |
| Small enough to be safe | Five reference tables, no personal data, no authentication, no mutation on the public path |
| Large enough to prove the architecture | It exercises **every** layer: migration → repository → domain → application service → controller → server-rendered view → JSON API → both surfaces → tests |
| Unblocked | **D-56** was approved on 2026-10-07 and in any case affects the *catalogue content*, not the table. **D-40** affects *area content*, not the structure. Neither blocks the schema (§9.2) |
| Produces reusable ground | Slug handling, Amharic-capable text storage, "hide without unclassifying", empty-state suppression and the redirect record are all needed by every later slice |

**What the slice must contain**

| Layer | Content | Traces to |
| --- | --- | --- |
| **Migration** | `category`, `alias`, `area`, `sub_city`, `redirect`; `utf8mb4`; slug unique within level; parent nullable with exactly two levels enforced; indexes written with the queries | TR-165, TR-166, TR-169, DM-3 |
| **Domain** | Slug value object; a two-level invariant that cannot be violated by construction; visibility as a state | DM-2, D-06 |
| **Data access** | One repository per aggregate, prepared statements only, no cross-area reads | TR-16, TR-164 |
| **Service** | Read the tree; read one Category with its Subcategories; read Areas with Sub-city | C-04, C-05 |
| **Controller** | Thin: read slug, call one service, render | `architecture.md` §2.1 |
| **Web rendering** | Server-rendered category and area index and detail pages, mobile-first, working with JavaScript unavailable | D-52, C-37 |
| **API** | `GET /categories`, `GET /categories/{slug}`, `GET /areas`, `GET /areas/{slug}` — **only** these; no endpoint is added "because it will be needed" | api-spec §2.1 |
| **Empty-state rule** | A Category or Area with **no** published Listings is not a navigable entry — which in this slice means every entry, and the test must say so honestly | C-04, C-05 |
| **Tests** | Unit on slug and the two-level invariant; feature on both surfaces; contract tests against the API spec; a migration test proving each constraint; accessibility and keyboard traversal | §12 |
| **Security** | Output encoding; no SQL string building; bounded page size; correct cache headers on a public, cacheable response | APP-3, APP-4, TR-47 |
| **Accessibility** | Landmarks, heading order, keyboard operability, non-colour-only meaning, legibility at increased text size | A11 rules |
| **SEO** | Stable slug URLs, canonical, title and description, and the redirect record on a merge — Web only | C-37, SEO-4, TR-100 |
| **Platform** | Identical content through the Mini App surface, with the adapter adding navigation only | D-49, TST-4.3 |

**What the slice must not contain**

| Excluded | Reason |
| --- | --- |
| A real category catalogue | **D-56.** Development uses an obviously synthetic fixture set |
| A confirmed Area set | **D-40.** Same |
| A listing↔category join | **There is no Business yet.** The join belongs to the second slice; D-57 no longer blocks it |
| A cardinality constraint | **Belongs with the join**, in the second slice. D-57 settled the constraint set (§9.2); it is created **with** the join, not retrofitted |
| Search over categories | M3. Browsing is not search |
| Any ops endpoint for taxonomy | M6, and it needs staff identity |

### 11.2 The second slice

Named here so the first slice is built toward it. **M0 cleared D-55 on
2026-10-07, so this slice is unblocked** and may follow the first
immediately. It is built against the attribute boundary in `data-model.md`
§3.2 — and **D-04** and **D-44** are still open, so the hours structure and
the services, products and pricing structure are **not** invented here.

```text
Business → Branch → publication state → public Business profile
         → classification into the taxonomy from slice 1
         → Permission and Verification gates on publish
```

Its defining test is **invariant 2**: publication is refused where no
Permission record is linked, **by every path** (TR-49, TST-3.3).

### 11.3 Vertical-slice rules

Every slice crosses the stack where relevant:

```text
Requirement (C-xx / TR-xxx)
      ↓
Data (migration + constraints)
      ↓
Domain (invariants, value objects, state)
      ↓
Service (one use case, authorized, audited where privileged)
      ↓
HTTP / API (thin controller, documented contract)
      ↓
UI (server-rendered, accessible, both surfaces)
      ↓
Tests (unit, feature, contract, integrity, accessibility)
      ↓
Documentation impact checked
```

| Rule | Statement |
| --- | --- |
| IMP-11.1 | **A slice is a capability, not a layer.** "Build all the repositories" is not a slice |
| IMP-11.2 | **Avoid creating abstractions before a real feature needs them.** An interface with one implementation and one caller is premature |
| IMP-11.3 | **Avoid implementing a framework inside plain PHP.** If a change starts adding auto-wiring, annotation scanning, a service locator or a generic model layer, it has become framework creep and stops |
| IMP-11.4 | **A slice that cannot be tested end to end is too big or too vague.** Split it |
| IMP-11.5 | **A slice ends green.** `composer test` passes, the diff has been read, and the architecture assertions cover any new namespace |
| IMP-11.6 | **One implementation of every rule.** If Web and Telegram need different behaviour, stop — that is a product decision (D-49) |
| IMP-11.7 | **Do not build the next forty capabilities' scaffolding inside the first one.** Complexity needs a requirement behind it, not an anticipation |

---

## 12. Testing strategy per slice

The authority is [`test-strategy-v1.0.md`](test-strategy-v1.0.md) and
[`quality-strategy-v1.0.md`](quality-strategy-v1.0.md). This section maps
those levels onto the milestone sequence; it adds **no** new testing rule.

### 12.1 Levels applied to every slice

| Level | Applies when | Where |
| --- | --- | --- |
| **Architecture** | A namespace is added or a boundary is touched — **always, for a new namespace** | `tests/Arch` |
| **Unit** | Domain logic, value objects, state machines, pure calculation. **This is what Infection mutates**, so behaviour that matters must have unit coverage | `tests/Unit` |
| **Feature** | The capability end to end, in-process: HTTP in, response out | `tests/Feature` |
| **Contract** | An endpoint exists — assert against `api-spec-v1.0.md`, not against the implementation | `tests/Feature` |
| **Migration / integrity** | A table or constraint is added — prove the constraint rejects what it claims to reject | `tests/Feature` |
| **Accessibility** | A rendered surface changes | Feature-level assertions plus the manual list |
| **Security** | Authentication, authorization, input, output, upload, logging or headers are touched | Feature + unit |
| **Regression** | A defect is fixed — the test comes **first** and fails before the fix | Nearest level |
| **Browser** | Only where progressive enhancement or a real browser behaviour cannot be asserted in-process | Manual, recorded |
| **Manual** | Judgement, content sense, map rendering, real-device Telegram behaviour | `quality-strategy-v1.0.md` §5 |

### 12.2 Per-milestone test focus

| Milestone | Test focus that is specific to it |
| --- | --- |
| **M1** | Architecture assertions for every new namespace; the outbound-HTTP gateway substituted everywhere; **invariant 16** — one executable entry point; **invariant 15** — nothing sensitive logged; **invariant 20** — jobs idempotent; clock injection proven |
| **M2** | Migration constraint tests; two-level taxonomy invariant; slug stability across rename; **invariant 18** — closure is a state and the URL survives; PR-1…PR-6; unknown data absent, never "false" (TR-56) |
| **M3** | **Invariant 1** — identical organic ordering with and without an active Campaign. Written at M3 even though Advertising arrives at M8, because it must exist *before* there is anything to compromise it. Bounded queries; Amharic alias matching; suggestion failure must not break plain search; zero-result recovery |
| **M4** | **Invariant 12** — the whole discovery journey with no session, no cookie interaction, no account; enumeration resistance on OTP request; single-use, rate-limited verification; session rotation on privilege change; **no** password and **no** Apple endpoint exists (CR-8) |
| **M5** | **Invariants 9, 10, 11**; one Review per Customer per subject enforced in the database; **no** reply field, endpoint or affordance (TR-67); reporter identity never exposed |
| **M6** | **Invariants 2, 3, 6, 13**; O-1…O-8; stale concurrency token returns `409`; a Customer token never reaches an operations endpoint; Administrator-only endpoints check the named permission (TR-36) |
| **M7** | Surface parity per capability (TST-4.3); Mini App validation failure degrades to Guest; SEO metadata and canonical correctness; **invariant 5** — Guest search and profile succeed with every external dependency unavailable |
| **M8** | **Invariants 1, 7, 8, 17**; CR-1 and CR-3; sponsored items always in a separate labelled array; activation impossible without the §16 checks |
| **M9** | **Invariant 14** — production errors contain no stack trace, SQL, path or internal identifier; rate limiting; header policy; backup restore rehearsal; accessibility audit; performance budget |

### 12.3 Test data and privacy

| Rule | Statement | Source |
| --- | --- | --- |
| IMP-12.1 | **Fixtures are synthetic.** No real business, no real person, no real phone number, no real address | `test-strategy-v1.0.md` §8 |
| IMP-12.2 | **No production data is copied into a test or a development environment** — doing so would process real personal data while **L-5** is unanswered | §14 |
| IMP-12.3 | A fixture that looks like a real Bole Bulbula business is a privacy risk, not a convenience | PBD principles |

### 12.4 What the suite cannot cover

Recorded so it is not mistaken for covered: the correctness of a real
Category catalogue, map rendering quality, Telegram behaviour on real devices,
content sense, the accessibility of real content, deliverability of real
email, and whether a legal statement is sufficient. These belong to manual
verification and, for the last, to counsel.

### 12.5 Quality gates per slice

| Gate | When |
| --- | --- |
| `composer test` | Before every commit that is proposed for merge |
| Infection | Before a PR that changes domain logic; **never** with a suppressed mutant |
| `tools/verify-docs.py`, `tools/verify-ai-context.py` | Before a PR that touches `docs/` or `.ai/` |
| Manual accessibility pass | Before a PR that changes a rendered surface |

### 12.6 Definition of Done

**A feature is not complete because code exists.** A slice is done when
**all** of the following are true:

| # | Criterion |
| --- | --- |
| 1 | **Requirement traced** — every behaviour maps to a `C-xx`, `D-xx`, `TR-xxx` or a numbered rule in a formal document |
| 2 | **Decision checked** — no open decision was silently resolved; any open item touched is still open and is named in the report |
| 3 | **Implementation follows the architecture** — layer direction respected, boundaries intact, new namespaces guarded by assertions |
| 4 | **Tests pass** — `composer test` green; coverage and type coverage still at 100 %; no threshold lowered; no mutant suppressed |
| 5 | **Security reviewed** — authorization server-side, input validated, output encoded, nothing sensitive logged, no secret added |
| 6 | **Privacy reviewed where applicable** — data minimisation honoured, no personal data in caches, logs or analytics events, no invented retention period |
| 7 | **Accessibility reviewed** — keyboard operable, non-colour-only meaning, text alternatives, legible at increased text size |
| 8 | **UX and platform behaviour verified** — matches the UX specification, and behaves identically on Web and the Mini App unless a decision says otherwise |
| 9 | **Documentation impact checked** — a formal document updated only under §13; `.ai/` marked *Needs review* if it drifted |
| 10 | **Diff reviewed** — file by file, not the summary |
| 11 | **Unresolved items reported** — open questions hit, assumptions made, contradictions found, and what was deliberately not done |

---

## 13. Documentation update rules

Formal documentation remains authoritative throughout implementation.

| Situation | Correct response | Never |
| --- | --- | --- |
| **Bug in the implementation** | Fix the code. Add the regression test first | Change the specification to match the bug |
| **Documentation is ambiguous** | Report the ambiguity and name both readings. Ask | **Silently reinterpret it** |
| **A formal requirement appears wrong** | Report it with evidence. The owner decides | **Change it to justify the code** |
| **A product decision is needed** | Open or record the decision **before** changing behaviour | Decide it in code |
| **An implementation detail needs clarification** | Update the technical document where appropriate, citing the change | Invent a rule in a comment |
| **`.ai/` becomes stale** | Refresh the affected context files and rebaseline them | Leave a file claiming `Current` against a moved baseline |
| **A new document seems needed** | Propose it. Most "missing" documents are a section in an existing one | Create a parallel source of truth |

| Rule | Statement |
| --- | --- |
| IMP-13.1 | **The implementation agent must never rewrite the specification to make an implementation easier.** This is the single most damaging thing it can do |
| IMP-13.2 | The documentation defect pattern this corpus has already produced once is **"finding recorded but not applied"**. When a change reveals a defect in another document, **fix that document in the same change**, or the finding will still be there three phases later |
| IMP-13.3 | `.ai/` is derived. When `docs/` moves, `.ai/` is refreshed and rebaselined — this is now mechanically enforced by `tools/verify-ai-context.py` |
| IMP-13.4 | A decision is recorded in **`decision-register.md`** and nowhere else (D-29) |

---

## 14. Security and privacy gates

Mapped to milestones. **None of this is implemented in this phase.**

### 14.1 Security gates

| Gate | Must be in place before | Content | Source |
| --- | --- | --- | --- |
| **G-SEC-1** | Any authenticated request exists (**M4**) | Session model, token model, rotation, expiry, idle timeout, revocation; CSRF for HTML form submissions; OTP brute-force, resend and enumeration resistance; constant-time comparison | TD-02; `authentication-security-v1.0.md` §3, §7 |
| **G-SEC-2** | Any upload exists (**M2** for media) | Media type and content validation, execution prevention, size and type controls, safe storage location outside the document root | `application-security-v1.0.md` §6; TR-184 |
| **G-SEC-3** | Any privileged operation exists (**M6**) | Server-side authorization on every request, named permission strings, audit on every mutation, **staff authentication strength — D-45** | TD-03; TR-08, TR-33, TR-36 |
| **G-SEC-4** | Production (**M9**) | Security headers at production policy, secret custody, logging review, rate limiting, dependency security in CI, incident readiness | `application-security-v1.0.md` §7, §8, §9; `incident-response-v1.0.md` |

| Rule | Statement |
| --- | --- |
| IMP-14.1 | A security control is **built with the feature that needs it**, never retrofitted in M9. M9 verifies and tunes; it does not introduce |
| IMP-14.2 | **No** authorization decision is made in a template, a route definition or a client. Server-side, at the application-service boundary |
| IMP-14.3 | The staff mechanism is **not** an implementation choice (§15) |

### 14.2 Privacy gates

| Gate | Milestone | Requirement | Status |
| --- | --- | --- | --- |
| **G-PRV-1 Data minimisation** | Every slice that stores a field | A field exists only with a stated purpose; prefer business data over personal data | Specified and binding (D-51) |
| **G-PRV-2 Lawful basis** | Before **any** real personal data is processed | A lawful basis per processing purpose | **PENDING COUNSEL (L-5)** |
| **G-PRV-3 Retention** | Before a retention job runs | A period per data class, and the minimum account age | **PENDING COUNSEL (L-21, D-46)** |
| **G-PRV-4 Privacy notice** | Before launch | The notice cannot be written without G-PRV-2 | **PENDING COUNSEL** |
| **G-PRV-5 Vendor and transfer review** | Before any third party receives data — Google, the email provider, hosting, backups, maps | Transfer assessment and the residency question | **PENDING COUNSEL**; also D-42, D-42b |
| **G-PRV-6 Rights handling** | M9 (C-36) | Export format, response windows, identity-verification standard | **PENDING COUNSEL (L-7)** |
| **G-PRV-7 Account deletion** | M9 | Deletion cascade correct: Saves removed; Reviews **withdrawn** (D-34); **audit and moderation history preserved** | Specified (TR-167, DL-4). The **retention period** for the withdrawn record is **PENDING COUNSEL** (L-21, D-46) |
| **G-PRV-8 Location handling** | M3 (nearby) | Coordinates used in-request and **never stored**; no Guest identifier | Specified and testable (TR-201, TR-202) |

| Rule | Statement |
| --- | --- |
| IMP-14.4 | **A configuration key may exist with no value.** A guessed retention period, response window or minimum age in code is a silent legal interpretation |
| IMP-14.5 | **PENDING COUNSEL stays PENDING COUNSEL.** An implementation agent does not reason toward a legal answer |
| IMP-14.6 | Development and testing use **synthetic data only** until G-PRV-2 is answered |

---

## 15. Operations-console gate

The audit records Security as `BLOCKED` **for the console only**, because of
**D-45**. The precise position:

| Layer | Position |
| --- | --- |
| **Public implementation** | **May proceed.** The public surface needs no staff authentication, and its security foundations are specified and unblocked |
| **Console implementation** | **May proceed in development.** Screens, services, endpoints, authorization checks, audit writes and tests may all be built, against synthetic data, behind a real but provisional staff login |
| **Console production use** | **Blocked.** Until D-45 is resolved, privileged staff functionality is **not** production-ready, and the console must not be treated as safe to operate |

| Rule | Statement | Source |
| --- | --- | --- |
| IMP-15.1 | **Do not build a fake staff security model to make the console functional.** A provisional development login is clearly labelled as such and is not a candidate for production | AS-9.2, AS-9.3 |
| IMP-15.2 | Staff must use **stronger** authentication than Customers and **must not** rely on email alone. The mechanism is the open decision; the *requirement* is not | AS-9.1 |
| IMP-15.3 | The identity schema already accommodates a staff factor (TR-31), so resolving D-45 should be an implementation, not a migration of the identity model | TR-31 |
| IMP-15.4 | **No shared staff account.** Attribution is impossible without individual accounts | AS-9.10 |
| IMP-15.5 | Authorization uses **named permission strings**, never a role check in code — which is why **D-14 does not block** the console | TD-03, AS-9.7 |
| IMP-15.6 | "Code exists" and "safe to operate" are separate states, and the console is the clearest case of the difference | §6.1 |

---

## 16. First paid Campaign gate

**D-39 gates the first paid Campaign. It does not gate the launch, and it does
not gate building the advertising infrastructure** — provided that
infrastructure cannot deliver paid placement by accident.

### 16.1 What may be implemented

| Permitted | Condition |
| --- | --- |
| Package, Placement, Campaign, CampaignTarget and CampaignDelivery tables | Inventory counts and prices are **configuration with no approved value** |
| Campaign lifecycle services — create, approve, suspend, cancel | Approval is Administrator-only by named permission |
| Inventory availability checks | `409` when a Placement and period are sold out (TR-72) |
| The separate `sponsored` array in the API, and the labelled slots in both surfaces | Rendered from synthetic campaigns in development |
| The organic-ordering invariant test | **Required before any slot renders** (S6) |
| Start and stop evaluated at request time, not by a job | Invariant 17, TR-71, TR-154 |

### 16.2 What must remain disabled

| Disabled | Why |
| --- | --- |
| **Any paid delivery** | **D-39** is unresolved |
| **Any price** | No price is approved; **do not invent one** |
| **Any inventory count, slot position or density value** | Recorded as proposed, not approved |
| **Billing and invoicing** | **D-11** open; **L-17 and L-20 PENDING COUNSEL** |
| **Self-service advertising purchase of any kind** | Excluded from V1 (D-10, D-54) |
| **Auctions, bidding, CPC, CPM, CPA, programmatic** | Excluded from V1 (D-10) |

### 16.3 Activation checks

Before a Campaign may deliver paid placement, **all** must hold:

| # | Check | Source |
| --- | --- | --- |
| 1 | **D-39** integrity controls resolved **and** implemented | readiness criterion 65 |
| 2 | Prices approved by the owner | criterion 66 |
| 3 | Inventory counts and density values approved | criterion 67 |
| 4 | Sponsored labels verified on every placement, on both surfaces | criterion 68 |
| 5 | Identical organic ordering with and without an active Campaign, **proven by test** | criterion 69 |
| 6 | Billing and invoicing arrangements settled | criterion 70 |

### 16.4 What prevents accidental paid delivery

| Control | Form |
| --- | --- |
| **Separate code path** | Sponsored selection never touches the ranking pipeline; the ranking pipeline never reads an Advertising table (TR-41, TR-43) |
| **Separate response field** | Sponsored items are never interleaved into `data` by the server (CR-2) |
| **Explicit activation state** | A Campaign requires Administrator approval by named permission; there is no implicit activation |
| **A deliberate switch** | Paid delivery is off by default and turning it on is an explicit, audited action, not a side effect of creating a Campaign |
| **The invariant test** | Invariant 1 fails loudly if organic ordering ever becomes campaign-sensitive |

| Rule | Statement |
| --- | --- |
| IMP-16.1 | **Invariant 1 is the single most important test in the system.** It is the mechanical proof that organic results are not for sale |
| IMP-16.2 | **Do not invent a price or an inventory number**, including as a "development default" that could ship |

---

## 17. Production-launch gate

```text
code complete
      ≠
production legal readiness
```

| Class | Items | Nature |
| --- | --- | --- |
| **Legal — blocks launch** | Lawful basis per purpose (**L-5**); the residency question (**L-2 / D-42**); cross-border assessment for hosting, email, backups, Google and Telegram (**L-10 / L-12**); rights procedures and windows (**L-7**); retention and minimum age (**L-21 / D-46**); registration and whether a data-protection officer is required (**L-3 / L-4**); publishing contact data (**L-8**); reviews, defamation and takedown (**L-15 / L-16**); photography (**L-18**) | **PENDING COUNSEL.** No code change resolves any of them |
| **Pilot — blocks launch** | Execute the twenty-business pilot (**D-31**); the numeric launch bar (**D-30n**) | **PENDING PILOT.** No number may be invented first |
| **Product — blocks content, not code** | Launch-area boundary (**D-40**); verification rules and interval (**D-08**) | Gates content production |
| **Deployment** | Configuration and secret custody (**D-23**); host limits and tuning (**D-20**); hosting vendor (**D-42b**) | Gates the deployment, not the build |

| Rule | Statement |
| --- | --- |
| IMP-17.1 | **Implementation proceeds while these remain unresolved.** They block **launch**, not development |
| IMP-17.2 | **Do not build temporary legal behaviour based on a guess and call it final.** A policy page with invented text, a retention job with an invented period, or a consent flow built on an assumed lawful basis are all worse than an obvious gap |
| IMP-17.3 | A launch-readiness claim is made against [`production-readiness-v1.0.md`](production-readiness-v1.0.md), not against this plan |
| IMP-17.4 | Several criteria **cannot be self-assessed** — legal sufficiency is one of them |

---

## 18. Open implementation dependencies

### 18.1 Dependency summary

| Class | Items | Effect |
| --- | --- | --- |
| **Blocks implementation** | **D-45** (console production use only). *D-34, D-55, D-56 and D-57 left this class on 2026-10-07 — §9* | §9, §15 |
| **Does not block implementation** | D-04, D-08, D-09, D-13, D-14, D-16, D-17, D-19, D-20, D-21, D-23, D-25, D-27, D-33, D-35, D-38, D-40, D-41, D-42b, D-43, D-44, D-53 — each has a documented default, an adapter, a configuration key, or affects only content or a later surface | Build proceeds |
| **Blocks production launch** | All counsel items · **D-31**, **D-30n**, **D-42** | §17 |
| **Blocks the first paid Campaign** | **D-39**, **D-11**, L-17, L-20, readiness criteria 65–70 | §16 |
| **Open technical, owned by the build** | OT-01…OT-09 — resolved by measurement or by the simplest option consistent with the TRD, and **recorded when chosen** | §20 |

### 18.2 Implementation risk register

| Risk | Impact | Dependency | Mitigation | Status |
| --- | --- | --- | --- | --- |
| **IMR-1** Schema built on a guessed answer to **D-34** | User-authored content re-parented later; every displayed rating silently changes | D-34 | **Risk closed 2026-10-07.** D-34 is approved and §9.2 states the schema boundary; the Review table is built from the decision, not ahead of it | **Closed — decision approved** |
| **IMR-2** Directory tables built on a guessed **D-55** | Expand → migrate → contract on the most-read table in the product | D-55 | **Risk closed 2026-10-07.** The boundary is approved and stated in `data-model.md` §3.2. The residual risk is an attribute being moved later for convenience, which DM-R1 forbids without a decision | **Closed — decision approved** |
| **IMR-3** Catalogue **content** fabricated and shipped as if it were the curated catalogue | A fabricated taxonomy becomes the de-facto catalogue | D-56 | **Still live, and now the main taxonomy risk.** D-56 approved the *model*, not the content: the catalogue is centrally curated reference data, never a migration (TR-177, REL-6.10), and development fixtures must be obviously synthetic | **Open — operational decision** |
| **IMR-4** Cardinality constraint added after data exists (**D-57**) | The constraint fails to apply, or silently is not enforced | D-57 | **Largely closed 2026-10-07.** D-57 is approved, so the constraints — one primary, no duplicates, no maximum — are created **with** the join rather than retrofitted. The residual risk is only an implementation that defers them | **Closed — decision approved** |
| **IMR-5** **D-45** unresolved while console code grows | A provisional staff login becomes permanent by inertia | D-45 | Provisional login explicitly labelled; console production use gated; schema already accommodates a factor | **Open — security decision** |
| **IMR-6** Counsel items unresolved at code-complete | A finished product that cannot lawfully launch | L-5, L-21, L-7, L-2, L-8, L-10, L-12, L-15, L-16, L-18, L-3, L-4 | Counsel engaged in parallel with the build; synthetic data throughout; no invented legal behaviour | **PENDING COUNSEL** |
| **IMR-7** Data residency unresolved | The hosting purchase cannot be made; architecture may need a different target | L-2, D-42, D-42b | Deployment is a late milestone; the design assumes a constrained shared host either way | **PENDING COUNSEL** |
| **IMR-8** External identity dependency (Google) | Sign-in unavailable or unlawful as designed | D-48 approved; L-10, L-12 open | Email OTP is a complete second path; the Guest journey requires no account at all | **PENDING COUNSEL** |
| **IMR-9** Telegram platform dependency | Platform change breaks the surface | D-49, D-38 | Telegram specifics behind an adapter; validation failure degrades to the full Guest experience | **Open — product decision** |
| **IMR-10** Maps dependency | Key ownership, billing or availability | D-21 | Map is an enhancement; the profile degrades to address and landmark (PR-6) | **Open — product decision** |
| **IMR-11** Email dependency | OTP mail undeliverable means a broken login | D-24 approved; D-41 open | Adapter with no provider; a log transport in development; deliverability verified before launch | **Open — technical decision** |
| **IMR-12** Shared-hosting limitations | No daemon, no root, cron-only scheduling, unknown resource limits | D-20 | Cron and a database queue by design (TD-06); bounded queries (TR-47); the design assumes nothing generous | **Open — technical decision** |
| **IMR-13** Single-Administrator continuity | One person holds publication, moderation and approval | D-14 | Single-operator mode is permitted but recorded as an audited override (TR-51); continuity is documented in the operations layer | **Open — operational decision** |
| **IMR-14** Pilot-dependent launch threshold | No definition of "ready to launch" exists | D-31, D-30n | No number is invented; launch readiness is a separate review | **PENDING PILOT** |
| **IMR-15** Advertising integrity | One person sells and moderates | D-39 | Infrastructure built disabled; six activation checks; the organic-ordering invariant proven by test | **Open — product decision** |
| **IMR-16** Framework creep under build pressure | The architecture rule quietly erodes | — | Eighteen architecture assertions, extended per namespace (TR-01); IMP-11.3 | **Open — implementation detail** |
| **IMR-17** Outbound-HTTP boundary relaxed instead of replaced | Ambient HTTP returns and TD-07 is lost | TD-07 | The gateway namespace is M1 item 1, built **before** the first external call needs it | **Open — implementation detail** |
| **IMR-18** Quality thresholds eroded as the domain grows | Coverage and mutation gates stop meaning anything | D-26 | Thresholds never lowered, mutants never suppressed; weak tests are fixed | **Open — quality decision** |

---

## 19. Recommended first milestone

**Run M1 and M0 at the same time. They do not compete.**

| Track | Owner | Content |
| --- | --- | --- |
| **M0 — decision gate** | **Owner** | ✅ **Satisfied 2026-10-07.** **D-34, D-55, D-56 and D-57** were approved and recorded in the register; §9.2 now carries the resulting schema boundary instead of the question |
| **M1 — foundation** | **Implementation** | The seventeen items in §6.2. None of them touches a product table, so none of them waits |

**Then, and only then, the first vertical slice** in §11.1 — the taxonomy and
locations reference spine with a read-only public browse surface — which is
itself unblocked, and which produces the slug, Amharic-text, visibility,
empty-state and redirect ground that every later slice reuses.

**Why this is the right first move**

| Reason | Detail |
| --- | --- |
| It respects the gate | No table whose columns are open gets created |
| It wastes nothing | Every M1 item is needed regardless of how the four decisions land |
| It de-risks the architecture early | The outbound-HTTP gateway, the clock, the queue and the audit writer are the four seams most likely to be done badly under pressure later |
| It gives the owner a short, concrete decision list | Four decisions, each with its consequences enumerated — not thirty-eight open items |
| It produces a demonstrable capability | C-04 and C-05 render from the database on both surfaces at the end of the first slice |

**What must not happen first:** creating `business`, `branch`,
`business_category` or any `review` table; building authentication before the
Guest journey is proven to need none; or standing up the console over a domain
that does not yet exist.

---

## 20. Implementation-agent workflow

The binding ruleset is [`../../.ai/ai-workflow-rules.md`](../../.ai/ai-workflow-rules.md).
What follows is the **sequencing-specific** addition: when to run which check,
so that correctness is continuous and the full gate is not reduced to a
ritual.

### 20.1 Before coding

```text
read .ai/  (project-overview, plus the area context via source-map)
   ↓
identify the formal source   (no source → stop and report)
   ↓
inspect the decisions        (register; including decisions that FORBID it)
   ↓
inspect the existing implementation   (the repository, not a phase report)
   ↓
define the affected boundaries        (which namespaces, which tables)
   ↓
identify the blockers        (§6, §9 — is this gated?)
   ↓
plan the tests               (which levels, which invariants)
```

| Rule | Statement |
| --- | --- |
| IMP-20.1 | **Check §9 before writing a migration.** D-34, D-55, D-56 and D-57 are settled and §9.2 states the boundary to build to. If the table's columns are the subject of a **still-open** decision — notably **D-04** (hours structure) or **D-44** (services, products and pricing) — stop |
| IMP-20.2 | **Check §6.2 and §6.3 before starting.** Knowing which of the three categories the work falls in is the whole point of this plan |
| IMP-20.3 | **Read the repository, not a previous report.** The sandbox has rolled back repeatedly; GitHub and the working tree are the truth |

### 20.2 During coding

```text
small change
   ↓
run targeted tests      (the affected test files, plus tests/Arch)
   ↓
inspect the diff
   ↓
run broader tests       (the full suite) at a natural boundary:
                         a migration, a new namespace, a new endpoint,
                         or before leaving a file set
   ↓
continue
```

| Rule | Statement |
| --- | --- |
| IMP-20.4 | **Targeted after every edit; full suite at a boundary.** Running everything after each keystroke is theatre; running nothing until the end is worse |
| IMP-20.5 | **`tests/Arch` is always in the targeted set.** It is the cheapest suite and the one that catches the most damaging mistakes |
| IMP-20.6 | **A new namespace is not finished until its architecture assertion exists** (TR-01) |
| IMP-20.7 | **Write the failing test first** for a defect fix |

### 20.3 Before opening a pull request

```text
composer validate --strict
   ↓
composer test            (validate → Rector → Pint → PHPStan → Pest → type coverage)
   ↓
architecture checks      (tests/Arch green; every new namespace covered)
   ↓
security checks          (no secret; authorization server-side; nothing sensitive logged)
   ↓
documentation checks     (tools/verify-docs.py and tools/verify-ai-context.py
                          when docs/ or .ai/ changed)
   ↓
Infection                (when domain logic changed)
   ↓
inspect the diff         file by file
   ↓
report unresolved items
```

| Rule | Statement |
| --- | --- |
| IMP-20.8 | **Never disable or bypass a CI check**, including secret scanning and dependency audit |
| IMP-20.9 | **Work on a feature branch.** Never commit to `main`. Open a pull request. Do not merge without instruction |
| IMP-20.10 | **Report what was not done, and why**, as plainly as what was done |
| IMP-20.11 | An open technical item resolved during the build (OT-01…OT-09) is **recorded in the change description**, and in the technical document if it is durable |

### 20.4 The four questions an agent answers before every slice

```text
1. What formal document requires this?
2. Which decisions touch it, and is any of them open?
3. Which boundary does it live behind, and is that boundary tested?
4. What proves it works, and what proves it did not break anything else?
```

If question 1 or question 2 cannot be answered, the correct output is a
**report**, not code.

---

## Open items

| ID | Item | Status |
| --- | --- | --- |
| Category **catalogue content** | Curated centrally under D-56; the model is settled, the content is not produced | **Open — operational decision** |
| D-45 | Staff authentication strength — gates console **production use** | **Open — security decision** |
| D-39 | Advertising integrity controls — gates the first paid Campaign | **Open — product decision** |
| D-11 | Billing mechanics | **Open — product decision** |
| D-14 | Operator and Administrator split — explicitly non-blocking | **Open — product decision** |
| D-13 | Identity-linking rules | **Open — product decision** |
| D-33 | Telegram identity — not a login mechanism | **Open — product decision** |
| D-38 | Mini App navigation model | **Open — product decision** |
| D-04, D-08, D-09, D-43, D-44 | Hours, verification, completeness, permission records, services and products | **Open — product decision** |
| D-16, D-17, D-19, D-53 | Client approach, view layer, dark mode, brand values | **Open — product decision** |
| D-20, D-21, D-23, D-25, D-41, D-42b | Host limits, maps, secrets, media storage, email provider, hosting vendor | **Open — technical decision** |
| D-27 | Analytics granularity and retention | **Open — product decision** |
| D-35 | Structured guest suggestions | **Open — product decision** |
| D-40 | Launch-area boundary — gates area content | **Open — product decision** |
| OT-01…OT-09 | Session values, OTP values, cache backend, search refresh, frame policy, backup schedule, observability, pagination style, derivative generation | **Open — technical decision** |
| D-26 | Coverage and mutation gates as the domain grows — never downward | **Open — quality decision** |
| D-46, L-2, L-5, L-7, L-8, L-10, L-12, L-15, L-16, L-17, L-18, L-20, L-21, L-3, L-4 | Legal minima, lawful basis, retention, rights, residency, transfers, publication, reviews, photography, advertising tax and invoicing, registration, data-protection officer | **PENDING COUNSEL** |
| D-30n, D-31 | Numeric launch bar and the operational pilot | **PENDING PILOT** |
| D-42 | Data-location policy | **PENDING COUNSEL** |
| — | Whether the milestone order in §10 survives contact with the build | **Open — implementation detail**; revisable without an owner decision (IMP-0.5) |

## Traceability

| This plan's section | Derived from |
| --- | --- |
| §4 Repository baseline | Direct inspection of the working tree; `composer.json`; `tests/Arch/ArchTest.php` |
| §5 Implementation principles | [`../30-technical/architecture.md`](../30-technical/architecture.md) §1–§3; [`../30-technical/trd-v1.0.md`](../30-technical/trd-v1.0.md) TD-01…TD-07 |
| §6 Readiness | [`documentation-audit-v1.0.md`](documentation-audit-v1.0.md) §31–§32 |
| §7 Dependency graph | [`../30-technical/data-model.md`](../30-technical/data-model.md); [`../30-technical/api-spec-v1.0.md`](../30-technical/api-spec-v1.0.md); [`../20-ux-ui/user-flows-v1.0.md`](../20-ux-ui/user-flows-v1.0.md) |
| §8 Module boundaries | [`../30-technical/architecture.md`](../30-technical/architecture.md) §3.1; [`../10-product/scope-v1.md`](../10-product/scope-v1.md) |
| §9 Data-model gate | [`../60-decisions/decision-register.md`](../60-decisions/decision-register.md) §2.2; [`../30-technical/data-model.md`](../30-technical/data-model.md) §3, §6, §11; [`../30-technical/trd-v1.0.md`](../30-technical/trd-v1.0.md) §30–§31 |
| §10 Sequence | §7, plus [`../10-product/roadmap.md`](../10-product/roadmap.md) §1 |
| §11 Vertical slices | [`../10-product/prd-v1.0.md`](../10-product/prd-v1.0.md) §12 (C-04, C-05, C-08); [`../20-ux-ui/information-architecture-v1.0.md`](../20-ux-ui/information-architecture-v1.0.md) |
| §12 Testing | [`test-strategy-v1.0.md`](test-strategy-v1.0.md); [`quality-strategy-v1.0.md`](quality-strategy-v1.0.md) |
| §13 Documentation rules | [`../../.ai/ai-workflow-rules.md`](../../.ai/ai-workflow-rules.md) §5; D-29 |
| §14 Security and privacy gates | [`../50-security/`](../50-security/application-security-v1.0.md); [`../55-privacy/privacy-by-design-v1.0.md`](../55-privacy/privacy-by-design-v1.0.md) |
| §15 Console gate | [`../50-security/authentication-security-v1.0.md`](../50-security/authentication-security-v1.0.md) §9 |
| §16 Campaign gate | [`../40-operations/advertising-operations-v1.0.md`](../40-operations/advertising-operations-v1.0.md) §4.1, §11; [`production-readiness-v1.0.md`](production-readiness-v1.0.md) §4.7 |
| §17 Launch gate | [`production-readiness-v1.0.md`](production-readiness-v1.0.md); [`documentation-audit-v1.0.md`](documentation-audit-v1.0.md) §31 |
| §18 Risks | [`documentation-audit-v1.0.md`](documentation-audit-v1.0.md) §31; [`../50-security/threat-model-v1.0.md`](../50-security/threat-model-v1.0.md) |
| §20 Workflow | [`../../.ai/ai-workflow-rules.md`](../../.ai/ai-workflow-rules.md); [`../../.ai/code-standards.md`](../../.ai/code-standards.md) |

## Legal and regulatory references

| Reference | Relevance | Classification |
| --- | --- | --- |
| Proclamation No. 1321/2024, Federal Negarit Gazette No. 35, 24 July 2024 | The governing instrument behind every privacy gate in §14 and every launch blocker in §17 | Confirmed — statute/regulation |
| Art. 22(1) | Data sovereignty — the residency question that gates the hosting decision | Confirmed — statute/regulation; application **PENDING COUNSEL** |
| Art. 20(1) | Cross-border transfer bases — distinct from residency, and relevant to Google, the email provider and backups | Confirmed — statute/regulation; application **PENDING COUNSEL** |
| Whether any milestone in this plan produces a lawful processing activity | — | **PENDING COUNSEL. This plan makes no legal determination** |

The article map is maintained in
[`../55-privacy/privacy-governance-v1.0.md`](../55-privacy/privacy-governance-v1.0.md)
§11. This plan cites it; it does not interpret it.

## Decision references

D-02, D-03, D-04, D-06, D-08, D-09, D-10, D-11, D-12, D-13, D-14, D-15,
D-15r, D-16, D-17, D-18, D-19, D-20, D-21, D-23, D-24, D-25, D-26, D-27,
D-29, D-30n, D-31, D-33, D-34, D-35, D-38, D-39, D-40, D-41, D-42,
D-42b, D-43, D-44, D-45, D-46, D-48, D-49, D-50, D-51, D-52, D-53, D-54,
D-55, D-56, D-57.
