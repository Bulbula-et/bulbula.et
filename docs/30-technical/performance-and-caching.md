# Performance and Caching

| | |
| --- | --- |
| **Document** | Performance and Caching Strategy — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Scope.** How Bulbula stays fast on shared hosting with no extra services:
rendering strategy, SQL discipline, HTTP caching, application caching,
invalidation, assets, images, compression, and bounded operations.

**Separation.** §3–§12 are the **V1 baseline** — what is built now. §13 is
**future scaling** — what is deliberately *not* built, with the evidence that
would justify it.

**The hard constraint (TRD TD-05).** **No cache service is required for V1.**
Not Redis, not Memcached, not Varnish. Any of them may be a future option;
none may be a dependency (TRD NG-7, TR-183).

---

## 1. What "fast" means here

| ID | Target | Status | Source |
| --- | --- | --- | --- |
| PT-1 | LCP ≤ 2.5 s at p75 | **`[P]` proposed** | PRD NFR |
| PT-2 | INP ≤ 200 ms at p75 | **`[P]` proposed** | PRD NFR |
| PT-3 | CLS ≤ 0.1 at p75 | **`[P]` proposed** | PRD NFR |
| PT-4 | Critical HTML + CSS ≤ 150 KB | **`[P]` proposed** | PRD NFR |
| PT-5 | JavaScript ≤ 100 KB | **`[P]` proposed** | PRD NFR |
| PT-6 | Search p95 above 300 ms triggers re-evaluation | **`[P]` proposed** | PRD NFR |
| PT-7 | Approaching ~50 000 listings triggers re-evaluation | **`[P]` proposed** | PRD NFR |

**All seven are `[P]`.** They are proposals until the owner approves them;
this document **MUST NOT** present them as commitments (TRD TR-130).

**The real context.** Users are on mobile devices over mobile networks,
often on constrained data (PRD §22, R-02). Every kilobyte and every round
trip is a cost paid by the User.

---

## 2. The strategy in one line

**Be fast by not doing work**, rather than by caching work that should never
have happened (TRD P-3).

| Order of preference | Technique |
| --- | --- |
| 1 | Do not do it at all — no needless query, no needless request, no needless asset |
| 2 | Do it once at write time — the maintained search document (`search-design.md` §3) |
| 3 | Let the client or an intermediary avoid asking — HTTP caching (§6) |
| 4 | Cache the result server-side — only where measured and justified (§7) |
| 5 | Add infrastructure — **not in V1** (§13) |

---

## 3. Rendering

| ID | Rule | Source |
| --- | --- | --- |
| RN-1 | Pages are **server-rendered HTML**. The server sends a complete, useful document (PRD SUR-2) | D-16 |
| RN-2 | JavaScript is **progressive enhancement**. Every core capability works without it (PRD SUR-3) | D-16 |
| RN-3 | **No SPA, no client-side router, no hydration step, no build-time framework runtime** | D-16 (SPA rejected), TRD NG-1 |
| RN-4 | No render-blocking third-party script in the critical path | PT-1 |
| RN-5 | The view layer composes strings and touches no database and no HTTP (ArchTest: `View` free of `Database`/`Http`/`PDO`) | architecture §2 |
| RN-6 | Content comes first in the document order; chrome and enhancements follow | PT-1 |
| RN-7 | Elements that change size when they load reserve their space | PT-3 |

**Why server rendering is the fast choice here.** A directory page is mostly
text that the server already has. Sending JSON plus a framework to rebuild
that text in the browser means shipping the data twice and the renderer
once — on the slowest device on the slowest network.

**The frontend JS approach (htmx + Alpine versus vanilla) is Open (D-16).**
Both satisfy RN-1 to RN-3 and both fit within PT-5; this document does not
choose.

---

## 4. Database discipline

This is where a directory actually gets slow.

| ID | Rule | Source |
| --- | --- | --- |
| DB-1 | **Every query a page issues is known.** A page that cannot state its query count is not understood | TRD TR-127 |
| DB-2 | **No N+1.** A list page loads its related data in a bounded number of queries, never one per row | TRD TR-129 |
| DB-3 | Every query in a request path uses an index. A full scan on a growing table is a defect | TRD TR-127 |
| DB-4 | **No unbounded result set.** Every list query has a `LIMIT` | TRD TR-30 |
| DB-5 | `SELECT *` is not used in request paths; columns are named | TRD TR-169 |
| DB-6 | Counts for pagination are bounded or approximate at scale — an exact count over a large table is itself the slow query | TRD TR-47 |
| DB-7 | Expensive aggregates (ratings, completeness, counts) are **maintained**, not computed per request | `data-model.md` §6.5 |
| DB-8 | Search uses the maintained search document, never a six-way join | `search-design.md` §3 |
| DB-9 | Write-heavy work (reindex, rollups) runs in console commands, never in a request | TRD TD-06 |
| DB-10 | SQL lives in the repository layer only — never in controllers, views or `Bulbula\Http` (ArchTest) | architecture §2 |

**Indexing follows the access path, not intuition:** the composite index for
Category × Area browsing (C-06), the bounding-box index for nearby (C-07),
full-text indexes on the search document, and an index on every foreign key
used in a join.

---

## 5. Avoiding needless work in the request path

| ID | Rule |
| --- | --- |
| NW-1 | **No external HTTP call in a page render.** Not for maps, not for email, not for analytics (TRD TR-205) |
| NW-2 | Maps load client-side and only when needed; the page is complete and useful before any map appears (C-10, D-21) |
| NW-3 | Email is queued, never sent inline during a request (TRD TR-149) |
| NW-4 | Analytics are recorded cheaply and asynchronously; measurement **MUST NOT** slow the thing it measures (PRD AN) |
| NW-5 | **The Web surface does not call its own HTTP API** — an internal round trip per page would be pure overhead (TRD TD-01) |
| NW-6 | Boot work is minimal: load configuration, construct a logger, register an error handler. No eager service graph, no scanning, no reflection sweep (architecture §4) |
| NW-7 | Autoloading is optimised in production (`--optimize-autoloader`, `deployment.md` §4) |
| NW-8 | A failing optional feature degrades; it never blocks the page (TRD TR-205) |

---

## 6. HTTP caching — the first and cheapest layer

### 6.1 Policy by response class

| Response class | `Cache-Control` | Validator |
| --- | --- | --- |
| Static assets with a content hash in the filename | `public, max-age=31536000, immutable` | Filename is the validator |
| Static assets without a hash | `public, max-age` short | `ETag` + `Last-Modified` |
| Public pages — profiles, Category and Area pages | `public, max-age=<minutes>, stale-while-revalidate=<window>` | `ETag` |
| Search results | `public, max-age=<short>` keyed on every filter | `ETag` |
| Images | `public, max-age` long | `ETag` |
| **Any authenticated response** | **`no-store`** | None |
| Staff console, `/api/v1/ops/*` | **`no-store`** | None |
| Health endpoints | `no-store` | None (existing behaviour) |

| ID | Rule |
| --- | --- |
| HC-1 | **No response containing personal data is ever cacheable by a shared cache** (TRD TR-120, TR-123) |
| HC-2 | `Vary` names every input that changes the body — `Accept-Language`, `Accept-Encoding`, and the surface header where it affects output |
| HC-3 | Conditional requests (`If-None-Match`, `If-Modified-Since`) are honoured and answer `304` |
| HC-4 | `ETag` is derived from content, so a rebuild that changes nothing does not invalidate caches |
| HC-5 | Freshness windows are **configuration**, not literals (TRD TR-196) |
| HC-6 | A page whose data just changed **MUST NOT** serve stale content to the staff member who changed it (§8) |

### 6.2 Why this layer first

It costs one header, needs no infrastructure, and removes the request
entirely — browser, mobile-network intermediary and any future CDN all
benefit. No server-side cache can beat a request that is never sent.

---

## 7. Application caching — only where justified

### 7.1 The rule

| ID | Rule |
| --- | --- |
| CA-1 | An application cache is added **only** when a specific measured cost justifies it (TRD TR-121) |
| CA-2 | **The backend is the filesystem or a database table. No cache service.** (TD-05) |
| CA-3 | Everything cached **MUST** be regenerable from source. A cache is never a source of truth (TRD TR-124) |
| CA-4 | **No personal data in any shared cache** (TRD TR-123) |
| CA-5 | Every entry has an explicit expiry. No unbounded growth |
| CA-6 | Cache keys include **every** input that changes the value — a key missing a filter is a correctness bug, not a performance bug (TRD TR-122) |
| CA-7 | The cache is behind one interface, so the backend is swappable (§13) |
| CA-8 | A cold or wiped cache is **correct and merely slower**, and that path is tested (TRD TR-125) |
| CA-9 | Whether the V1 backend is filesystem or database is **`Open — technical decision` (TRD OT-03)** |

### 7.2 Candidates, in order of justification

| Candidate | Why | Invalidation |
| --- | --- | --- |
| Category tree with counts | Read on nearly every page, changes rarely | On taxonomy change or publication state change |
| Area list with counts | Same | On location change or publication state change |
| Homepage discovery blocks | Expensive composition, identical for all Guests | On publication change or scheduled refresh |
| Rendered fragments of popular public pages | Highest cost per request | On the underlying entity's update |
| Common search result sets | Only if measurement shows repetition | Short expiry; no explicit invalidation needed |

**Not cached:** anything per-Customer, anything in the staff console,
anything containing a Review's moderation state, and anything whose
staleness would mislead (TRD TR-120).

### 7.3 Filesystem caching on shared hosting

Workable with care: files under `storage/`, outside the document root
(`deployment.md` §3); atomic writes via write-to-temp-then-rename; bounded
entry counts; pruning by `bin/console maintenance:prune` (TRD TD-06); no
reliance on a single server's local state being shared — V1 has one host.

---

## 8. Invalidation

**The rule that matters: a staff edit is visible immediately** (TRD TR-45).
A directory whose corrections take an hour to appear has lost its point.

| Change | Immediate effect |
| --- | --- |
| Listing published, edited, unpublished, closed | Search document updated synchronously; entity caches for that Listing cleared; `ETag` changes |
| Review published or removed | Rating summary recomputed; profile cache cleared |
| Category or Area created, renamed, merged, hidden | Taxonomy caches cleared; affected search documents queued for rebuild |
| Campaign approved, suspended, ended | No invalidation needed — Campaign state is evaluated at request time (TRD TR-71) |
| Media attached or removed | Profile cache cleared; derivatives regenerated |

| ID | Rule |
| --- | --- |
| IV-1 | Invalidation is **explicit and immediate** at the point of change, not left to expiry |
| IV-2 | When in doubt, invalidate more broadly. A needless cache miss is cheap; stale published data is not |
| IV-3 | Cached data has an expiry **as well as** explicit invalidation — a missed invalidation self-corrects |
| IV-4 | Invalidation failure **MUST NOT** fail the write. It is logged and the entry expires |
| IV-5 | `bin/console cache:clear` clears everything safely at any time (CA-8) |

---

## 9. Static assets

| ID | Rule |
| --- | --- |
| SA-1 | Assets are served by the web server from `public/assets/`, never routed through PHP (`deployment.md` §3) |
| SA-2 | Asset filenames carry a content hash so they can be cached immutably and change by name |
| SA-3 | CSS is small, hand-written and scoped to what the page needs; no utility framework shipped wholesale (PT-4) |
| SA-4 | **No webfont is loaded in the critical path.** Any webfont must survive a swap without layout shift (PT-3) — and must render Amharic correctly (D-18) |
| SA-5 | Icons are inline SVG or a sprite, never an icon font and never one request per icon |
| SA-6 | **No CDN dependency for core rendering.** Assets are first-party; a blocked or slow CDN cannot break the site (TRD TR-183) |
| SA-7 | JavaScript is deferred and never blocks the parser (RN-2) |
| SA-8 | No build step is required to serve the site; any tooling is a developer convenience, not a runtime dependency |

---

## 10. Images

Photographs are the heaviest thing a directory serves (C-22).

| ID | Rule |
| --- | --- |
| IM-1 | Derivatives are generated **at upload**, never per request (TRD TR-82) |
| IM-2 | A small set of defined sizes serves thumbnails, cards and detail views — not one size scaled by the browser |
| IM-3 | Dimensions are always declared so nothing shifts as images load (PT-3) |
| IM-4 | Below-the-fold images are lazy-loaded; the primary image is not |
| IM-5 | Originals are retained so derivatives can be regenerated (TRD TR-83) |
| IM-6 | Uploads are bounded in byte size and dimensions, validated by inspection rather than by filename (TRD TR-80) |
| IM-7 | Images are served with long cache lifetimes and a content-addressed path |
| IM-8 | A missing image degrades to a neutral placeholder — never a broken element, never a layout collapse |
| IM-9 | Formats, size limits and storage are **Open (D-25)**; the rules above hold for any answer |

---

## 11. Compression and transfer

| ID | Rule |
| --- | --- |
| CP-1 | Text responses (HTML, CSS, JS, JSON, SVG) are compressed by the web server |
| CP-2 | Compression is configured in `public/.htaccess` or the host panel — **no PHP-level compression**, which wastes the application's time |
| CP-3 | Already-compressed formats (JPEG, PNG, WebP) are not recompressed |
| CP-4 | `Accept-Encoding` is in `Vary` (HC-2) |
| CP-5 | HTTPS everywhere; HTTP/2 where the host offers it (not required) |
| CP-6 | Responses carry no debugging payload, no inlined source map, no commented-out markup in production |

---

## 12. Bounded operations

**Nothing in a request path may be unbounded** (TRD TR-128).

| Operation | Bound |
| --- | --- |
| Search results | `per_page` clamped; maximum offset capped (API §1.7) |
| Autocomplete | Small fixed maximum; tighter latency budget |
| Nearby | Maximum radius and maximum candidates (`search-design.md` §8.2) |
| Any list endpoint | Mandatory `LIMIT` (DB-4) |
| Query text | Maximum length; over-length is rejected cleanly |
| Uploads | Maximum size and dimensions (IM-6) |
| Request body | Maximum size |
| Export of own data | Generated as a bounded job, not an unbounded synchronous query (C-36) |
| Staff list views | Paginated like everything else — the console is not exempt |
| Console commands | Batch size and a maximum runtime, so cron never overlaps itself (TRD TD-06) |

**Absence of a bound is a defect, even when current data is small.** The
bound is what keeps the failure mode graceful when it is not.

---

## 13. Future scaling — described, not built

| Option | Trigger that would justify it | V1 status |
| --- | --- | --- |
| Redis or Memcached | Filesystem/DB caching measurably insufficient **and** the host supports it | **Not required, not assumed** (TD-05, NG-7) |
| CDN for assets and images | Geographic spread or image bandwidth cost | Not needed; assets are first-party (SA-6) |
| Reverse proxy cache (Varnish, nginx) | Public-page throughput beyond the host | Not available on shared hosting |
| External search engine | Search p95 > 300 ms `[P]`, or corpus ≈ 50 000 listings `[P]` | **Not in V1** (`search-design.md` §12) |
| Read replicas | Read load beyond one MariaDB instance | Far beyond V1 |
| Dedicated image pipeline | Media volume beyond upload-time derivatives | Not in V1 |
| Queue broker | Volume beyond cron-driven draining | **Not in V1** (TD-06, NG-8) |
| Horizontal application scaling | Sustained CPU saturation on one host | Possible because the application holds no local session state in memory (TD-02) |

| ID | Rule |
| --- | --- |
| FS-1 | **None of these is a V1 dependency. None is designed here** (TRD NG-7, NG-8) |
| FS-2 | Each is adopted only against **measured** evidence of its trigger, recorded as a decision |
| FS-3 | The architecture keeps each option open — cache behind an interface (CA-7), search behind an interface (`search-design.md` §12), jobs behind a queue abstraction (TD-06), sessions in the database (TD-02) |
| FS-4 | Keeping an option open **MUST NOT** cost V1 complexity. An interface is cheap; a plugin system is not (TRD P-5) |

---

## 14. Measurement

Targets that are not measured are decoration.

| ID | Requirement |
| --- | --- |
| MS-1 | Core Web Vitals are measured in the field, not only in a lab tool (PT-1 to PT-3) |
| MS-2 | Server-side timing is logged per request with the correlation id (TRD TR-12) |
| MS-3 | Search p95 is tracked explicitly — it is a documented re-evaluation trigger (PT-6) |
| MS-4 | Query counts per page are observable in development; a regression is caught before release (DB-1) |
| MS-5 | Page weight is checked against PT-4 and PT-5 |
| MS-6 | Listing volume is tracked against PT-7 |
| MS-7 | Measurement **MUST NOT** itself require an external service in V1, and **MUST NOT** carry personal data (TRD TR-202) |
| MS-8 | Degradation is reported, not discovered by Users |

---

## 15. Open items

| ID | Item | Status |
| --- | --- | --- |
| PRD NFR | All seven performance targets | **`[P]` proposed** — not approved |
| D-16 | Frontend JS approach | Open — either satisfies §3 |
| D-17 | View layer | Open — RN-5 holds regardless |
| D-20 | Host limits and MariaDB tuning | Open — bounds the real budget |
| D-21 | Maps key, billing, embed and fallback | Open — NW-2 holds regardless |
| D-25 | Media storage, formats, size limits | Open — §10 holds regardless |
| D-27 | Analytics granularity and retention | Open — affects rollup cost |
| TRD OT-03 | Application-cache backend (filesystem vs database) | Open — technical decision |
| TRD OT-08 | Offset versus keyset pagination | Open — technical decision |

---

## Decision references

D-16, D-17, D-18, D-20, D-21, D-25, D-27.
