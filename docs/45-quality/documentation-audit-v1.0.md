# Documentation Audit

| | |
| --- | --- |
| **Document** | Final Documentation Audit and Reconciliation — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

Whether the Bulbula documentation system is internally consistent, complete
for V1, traceable, non-contradictory, technically coherent, legally honest,
implementation-ready and correctly reflected in `.ai/`. This is an audit and
reconciliation document. It designs nothing, decides nothing and adds no
capability.

## Authority

This document has **no authority over any document it audits**. Where it
records a correction, the correction was made by applying a
higher-authority source to a lower-authority document, and the source is
named. Where it records a finding it did not fix, the finding stays open.

---

## 1. Audit scope

| Audited | Count |
| --- | --- |
| Formal and historical documents under `docs/` | **69 files, 33,559 lines** |
| Derived context files under `.ai/` | **12** |
| Repository tooling under `tools/` | **2** |
| Application source read for comparison | **49 files in `src/`** |
| Tests read for comparison | **49 files**, including all 18 architecture assertions |
| Configuration read | `composer.json`, `composer.lock`, `phpstan.neon.dist`, `pint.json`, `rector.php`, `phpunit.xml.dist`, `infection.json5`, `phpinsights.php`, `.github/workflows/` ×3 |

Every file under all eleven `docs/` directories and all twelve `.ai/` files
was read in this phase. No finding below is carried forward from an earlier
phase report; the two defects earlier work had *reported* were re-tested
against the current tree and **both were still present** (§19, F-01 and
F-02).

## 2. Source baseline

| | |
| --- | --- |
| **Branch** | `docs/phase-3-final-audit` |
| **Audited from** | `3ee771d` — head of `docs/phase-3-ai-context` |
| **Corrections committed at** | `3b502eb` |
| **`.ai/` rebaselined to** | `3b502eb` |
| **`main`** | `fc6188b` — untouched |

## 3. Documentation inventory

### 3.1 By directory

| Directory | Files | Lines | Layer | Produced by |
| --- | --- | --- | --- | --- |
| `00-discovery/` | 5 | 6,492 | Historical | Phase 2 |
| `10-product/` | 7 | 4,153 | Product | Phase 3.1 |
| `15-business/` | 2 | 445 | Business | Phase 3.1 |
| `20-ux-ui/` | 10 | 6,036 | Interface | Phase 3.3 |
| `30-technical/` | 8 | 4,584 | Technical | Phase 3.2 |
| `35-platforms/` | 9 | 2,807 | Platform | Phase 3.4 |
| `40-operations/` | 9 | 2,853 | Operations | Phase 3.6 |
| `45-quality/` | 6 | 1,393 | Quality | Phase 3.6, this document |
| `50-security/` | 6 | 1,956 | Security | Phase 3.5 |
| `55-privacy/` | 7 | 2,287 | Privacy | Phase 3.5 |
| `60-decisions/` | 1 | 553 | **Authority** | Phase 2.3, living |

### 3.2 Control metadata

| Property | Result |
| --- | --- |
| Documents carrying `Version` | **69 / 69** |
| Documents carrying `Status` | **69 / 69** |
| Current formal documents at `v1.0` / `Draft` | **64 / 64** |
| Decision register | `v1.0` / **Approved** — correct; it is the authority |
| Discovery documents | v0.2 and v0.3 **Superseded**; v0.4 **Approved** (final discovery control document); the two v0.1 documents **Approved — historical record (frozen)** |
| Documents carrying `## Decision references` | **64 / 64** of those required (PRD, TRD and the register are exempt; each carries decision traceability inline) |

### 3.3 Duplicate, orphaned and missing documents

| Check | Result |
| --- | --- |
| Duplicate canonical documents | **None.** No topic has two current owners |
| Overlapping authority claims | **None.** Only `decision-register.md` claims to be a source of truth, and only for decisions |
| Orphaned documents | **None.** Every current document is reachable from at least one other |
| Documents referenced but missing | **Three names, all correctly framed** — see below |
| Superseded files presented as current | **None** |

`docs/architecture.md` and `docs/deployment.md` are named only in the
`Supersedes` fields of their replacements in `docs/30-technical/`, which is
the correct historical reference to a removed file.
`docs/40-operations/pilot-benchmark-v1.0.md` is named by five documents and
is consistently described as **produced when the pilot runs, and not
pre-filled** (LO-9.8, LO-9.9, ANO-7.5, MNT-5.7); production-readiness
criterion 27 uses its existence as the gate. That is a correct forward
reference to a deliberately unwritten artifact, not a broken link.

## 4. Authority model

```text
Owner-approved decisions
        ↓
docs/60-decisions/decision-register.md          ← Approved, authoritative
        ↓
formal documentation (10 … 55)                  ← v1.0, Draft
        ↓
.ai/ derived context                            ← derived, never authoritative
        ↓
implementation
```

The model holds. Three observations.

| ID | Observation |
| --- | --- |
| AU-1 | The register states the rule itself: *"Every other document references a decision by its ID and must not restate or contradict it."* Verified across 64 documents — **no document restates a decision's content in a way that could drift** |
| AU-2 | `00-discovery/` sits **outside** the authority chain. v0.4 names the register as its *"Authoritative companion — the single source of truth for decisions"* in its control block. Authority is therefore unambiguous even where discovery numbering differs |
| AU-3 | `.ai/` claims no authority anywhere, states in its own README that it is **not** a source of truth, and is mechanically checked for authority claims |

### 4.1 Reconciliation performed under this model

One formal conflict was found and reconciled (F-05). Terminology authority
belongs to `glossary.md`; the register's *decision content* was untouched and
only two words of descriptive prose were aligned. The reconciliation is
recorded in §20 and was not performed silently.

## 5. Completeness assessment

| Area | Assessment |
| --- | --- |
| Product | **Complete.** C-01…C-40 defined once, with actor, exclusions and open sub-decisions |
| Business | **Complete for V1 scope.** Revenue model, packages and integrity rules exist; **no price is approved**, which is correct, not a gap |
| UX/UI | **Complete.** Ten documents covering principles, research, IA, components, flows, content, design system, accessibility, public web and console |
| Technical | **Complete.** TRD (TR-01…TR-224), architecture, data model, API, auth, search, caching, deployment |
| Platform | **Complete.** Shared contract plus Web and Telegram, with five cross-cutting platform documents |
| Operations | **Complete.** Nine documents from operating model to business continuity |
| Quality | **Complete.** Five documents plus this audit |
| Security | **Complete.** Six documents including a threat model with STRIDE coverage and an accepted-risk register |
| Privacy | **Complete as a question set.** Seven documents; the answers to eighteen of them are **PENDING COUNSEL**, which is the honest state, not an omission |
| Legal | **Not complete, and cannot be.** See §15 and §21 |

No V1 capability lacks documentation. The gaps that exist are **decisions and
legal answers**, not missing documents.

## 6. Cross-document consistency

| Check | Result |
| --- | --- |
| Relative links resolving | **All resolve** |
| Backticked `docs/` paths | All exist, or are framed as future or historical |
| `TR-xxx` cited anywhere but undefined in the TRD | **None.** TR-01…TR-224 contiguous, plus TR-01a…TR-01e |
| `D-xx` cited but absent from the register | **None remaining** (two were found and corrected — F-02) |
| `C-xx` beyond C-40 | **None** |
| Contradictions between formal documents | **One** (F-05), reconciled |
| Terminology drift | **None remaining** |

## 7. Decision traceability

The register tracks **60 identifiers**: 19 Approved · 1 Closed (D-32) ·
3 Open Class A (all also Pending external) · 23 Open Class B · 7 Open
Class C · 4 Deferred Class D · 1 Scheduled (D-31) · 2 merged or split
(D-07 → D-44, D-22 → D-46 + L-xx). The counts table in the register matches
the entries beneath it.

| Check | Result |
| --- | --- |
| Every `D-xx` used anywhere is real | **Yes**, after F-02 |
| `D-46a` / `D-46b` | **Removed from current documents.** They survive only in `product-decision-brief-v0.4.md`, now annotated as historical |
| Retired legal identifiers (`L-1`, `L-9`, `L-11`, `L-13`, `L-14`) | **Not cited in any current document.** Present only in the discovery briefs |
| An Open decision described as Approved | **None** |
| A Deferred decision described as V1-required | **None.** D-15r, D-36, D-37, D-47 are consistently future |
| A Superseded decision treated as current | **None** |

### 7.1 Decision leakage

A requirement that changes product behaviour, an actor, authentication,
platform behaviour, storage location, the advertising model, ranking
behaviour or the business-ownership model must cite a decision. Every such
statement found carries one. **No untraceable behavioural requirement was
found in any current document.**

## 8. Capability traceability

Matrix legend: **R** required · **A** applicable · **n/a** not applicable ·
**M** missing · **O** open. Security, privacy and quality govern V1
cross-cutting obligations rather than enumerating each capability; that is
by design and is not counted as missing.

| C-xx | Capability | PRD | UX | Tech | Plat | Sec | Priv | Ops | Qual |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| C-01 | Homepage | R | R | R | R | A | A | A | A |
| C-02 | Search | R | R | R | R | A | A | A | A |
| C-03 | Autocomplete | R | R | R | R | A | A | A | A |
| C-04 | Category browsing | R | R | R | R | A | A | A | A |
| C-05 | Area browsing | R | R | R | R | A | A | A | A |
| C-06 | Category × area | R | R | R | R | A | A | A | A |
| C-07 | Nearby / distance | R | R | R | R | A | **R** | A | A |
| C-08 | Business profile | R | R | R | R | A | **R** | R | A |
| C-09 | Hours and open status | R | R | R | R | A | A | R | A |
| C-10 | Maps | R | R | R | R | A | **R** | A | A |
| C-11 | Contact actions | R | R | R | R | A | A | R | A |
| C-12 | Trust indicators | R | R | R | R | A | A | R | A |
| C-13 | Reviews | R | R | R | R | **R** | **R** | R | A |
| C-14 | Save | R | R | R | R | **R** | **R** | A | A |
| C-15 | Report / suggest correction | R | R | R | R | **R** | **R** | R | A |
| C-16 | Sponsored placements | R | R | R | R | **R** | A | R | A |
| C-17 | Sharing | R | R | R | R | A | A | A | A |
| C-18 | Static pages | R | R | R | R | A | **R** | R | A |
| C-19 | Listing creation | R | R | R | R | R | R | R | R |
| C-20 | Listing editing | R | R | R | n/a | R | R | **A** | A |
| C-21 | Verification / quality control | R | R | R | n/a | R | R | R | A |
| C-22 | Media management | R | R | R | n/a | **R** | **R** | **A** | A |
| C-23 | Category management | R | R | R | n/a | R | A | **A** | A |
| C-24 | Location management | R | R | R | n/a | A | A | **A** | A |
| C-25 | Review moderation | R | R | R | n/a | R | R | R | A |
| C-26 | Report management | R | R | R | n/a | R | R | R | A |
| C-27 | Advertising / campaign management | R | R | R | n/a | R | A | R | R |
| C-28 | Operational analytics | R | R | R | n/a | A | **R** | R | A |
| C-29 | Audit logs | R | R | R | R | R | R | R | R |
| C-30 | Google sign-in | R | R | R | R | **R** | **R** | A | A |
| C-31 | Email OTP | R | R | R | R | **R** | **R** | A | A |
| C-32 | One identity, two surfaces | R | R | R | R | **R** | **R** | A | A |
| C-33 | Account management | R | R | R | R | R | R | A | A |
| C-34 | Saved list | R | R | R | R | R | R | A | A |
| C-35 | Own reviews | R | R | R | R | R | R | R | A |
| C-36 | Data export and deletion | R | R | R | R | **R** | **R** | R | A |
| C-37 | SEO | R | R | R | R (Web) | A | A | A | R |
| C-38 | Analytics | R | R | R | R | A | **R** | R | A |
| C-39 | Notifications | R | R | R | R | R | R | A | A |
| C-40 | Privacy and data handling | R | R | R | R | **R** | **R** | R | R |

**No capability is Missing.** The only observation is editorial: four
console capabilities (**C-20, C-22, C-23, C-24**) are covered *in substance*
by `docs/40-operations/` — listing editing, media handling, category and area
management all appear there — but are not cited by their `C-xx` identifier in
that directory. Recorded as F-07 (Low).

The two capability exceptions to the two-surface rule are correctly and
identically stated everywhere: **C-37** (Web only — Telegram is not crawled)
and **C-19…C-29** (Web only — staff tooling). SCC-4.3 requires an owner
decision for a third. No third exists.

## 9. Terminology audit

`docs/10-product/glossary.md` is the canonical source (~70 terms). Scanned
across all 69 documents.

| Pair | Result |
| --- | --- |
| Save vs Favorite / Like / Bookmark / Wishlist | **Clean after F-05.** Every remaining occurrence is a prohibition, a glossary synonym column, or an unrelated use ("bookmarkable URL") |
| Business vs Business owner | **Clean.** No current document implies an owner relationship |
| Listing vs Business profile | **Clean.** The internal record and the public page are consistently distinguished |
| Permission vs consent | **Clean, and deliberately so.** PG-7.6 states that Permission (business) is not consent (personal data), and PG-7.7 that a business's Permission is not an individual's consent to publish their contact details |
| Verification vs certification | **Clean.** "Certification" appears once, in PNR-7.1, forbidding any unobtained compliance claim |
| Sponsored vs Promoted / Featured | **Clean.** Every occurrence of the forbidden words is a prohibition (DSN-9.3, CDN-11.4, content-design §banned terms) |
| Customer vs Member | **Clean.** "Member" appears only as "staff member" |
| Operator vs Agent | **Clean.** "Agent" appears only as "user agent" |
| Administrator vs Admin | **Clean** |

Historical documents retain their original vocabulary and were not edited to
erase it.

## 10. V1 boundary audit

Every hard exclusion was scanned for affirmative use in current documents.

| Exclusion | Result |
| --- | --- |
| Business accounts · claims · owner editing · owner replies · owner dashboards | **Held.** D-54, D-02, D-12 |
| Self-service advertising · auctions · CPC/CPM/CPA · paid ranking · paid verification · paid inclusion | **Held.** D-10; business-model §4.3 argues the fixed-package choice explicitly |
| Passwords · Apple sign-in | **Held.** D-48, AS-1.2, AS-1.3. "Password" survives only as *passwordless* and as *database password* (an infrastructure credential) |
| Flutter in V1 | **Held.** D-15r, deferred Class D |
| Ordering · bookings · jobs · local news · subscriptions | **Held.** "Ordering" occurs only as sort order; "overbooking" only as advertising inventory |
| Behavioural advertising · sale of customer data | **Held.** DI-10.13 states there is no commercial data flow out of Bulbula at all |
| Unapproved geographic expansion | **Held.** D-40 (launch-area boundary) remains open and no document assumes an answer |
| Unapproved language scope | **Held.** D-18: data is bilingual-ready, the V1 interface is English |

Every occurrence of a forbidden term in a current document is an exclusion, a
prohibition, a future option, an open question or a risk analysis. **No
forbidden feature appears as an active V1 requirement.**

## 11. Technical coherence audit

Compared against the real repository.

| Claim | Repository | Match |
| --- | --- | --- |
| Framework-free plain PHP | No framework package in `composer.json`; architecture test *"the application stays framework-free"* bans `Illuminate`, `Laravel`, `Symfony\Component\HttpKernel`, `Laminas`, `Mezzio`, `Slim`, `Flight` | **Yes** |
| PHP 8.4+ | `"php": "^8.4"` | **Yes** |
| Composer, PSR-4 | `Bulbula\` → `src/` | **Yes** |
| FastRoute | `nikic/fast-route` | **Yes** |
| PDO / MariaDB | `ext-pdo`, `ext-pdo_mysql`; `PDO` confined to `Bulbula\Database` by architecture test | **Yes** |
| Explicit composition, no container magic | `HttpKernelFactory` wires by hand | **Yes** |
| Thin controllers, application services | Enforced by three architecture assertions | **Yes** |
| Front controller | `public/index.php`, the only PHP entry point | **Yes** |
| Four runtime dependencies only | `fast-route`, `monolog`, `psr/log`, `phpdotenv` | **Yes** |

No formal document requires Laravel, Symfony, a React SPA, a Node backend,
Kubernetes, Redis, Elasticsearch, Kafka, RabbitMQ or microservices. Every
mention is a rejection (TRD NG-1…NG-8), a hard constraint
(`performance-and-caching.md` §1, `search-design.md` §1), a historical note in
discovery, or an escape-hatch row explicitly marked *not required, not
assumed*.

## 12. Technical architecture invariants

| ID | Invariant | Stated in TRD | Contradicted anywhere |
| --- | --- | --- | --- |
| **TD-01** | The Web surface does not call its own HTTP API | §21 | **No.** Reinforced by WP-1.2, PA-3.1, PEH-9.6, SEC-4.1, APP-1.5, NW-5 |
| **TD-02** | One session concept, two transports | §23 | **No.** Reinforced by S-1, PAU-6.1, PA-4.2, TM-8.5, SCC-2.11, AS-7.1 |
| **TD-03** | Named permissions, never role checks | §24 | **No.** Reinforced by SF-4, AS-9.7, PAU-9.4, OPX-0.1, O-6, DI-9.2 |
| **TD-04** | V1 search runs inside MariaDB | §25 | **No.** Reinforced by `search-design.md` §1, OPX-4.7, public-web-ux |
| **TD-05** | No cache service in V1 | §34 | **No.** Reinforced by CA-2, SEC-6.4, SO-1.3, DSN §12 |
| **TD-06** | Cron-driven work, no broker | §37 | **No.** Reinforced by DB-9, SO-11.1, RET-6.1, OPX-16.3, deployment §cron |
| **TD-07** | Outbound HTTP through one gateway namespace | §9 | **No.** Reinforced by TR-01a, TR-01d, SEC-4.4, TST-2.3, REL-4.7 |

Searched specifically for direct Web→API requirements, independent Telegram
business logic, `curl` outside the gateway rule, Redis becoming mandatory, a
mandatory broker and a mandatory search cluster. **None found.**

One structural note carried forward from the TRD rather than discovered here:
the architecture test confines `curl_init` to `Bulbula\Database`, so the
outbound gateway namespace required by TD-07 **does not yet exist** and must
be added deliberately, with its own assertion, under TR-01/AC-1. The
documentation already says this. It is a build instruction, not a defect.

## 13. Data-model coherence

| Relationship | Data model | Elsewhere | Consistent |
| --- | --- | --- | --- |
| Business → one or more Branch, exactly one primary | §3.1–3.2 | D-03, glossary, PRD | **Yes** |
| Listing as a publication concept, not an account | §3.3 | D-54, D-50 | **Yes** |
| Review boundary (per business vs per branch) | §6.1 | **Open (D-34)** everywhere at the time of audit; **resolved to Branch on 2026-10-07** | **Yes** |
| Save privacy — no counts, no social signal | §6.4 | C-14, SCC-2.9, UXP | **Yes** |
| PermissionRecord as a publication gate | §4.1 | D-50, LO | **Yes** |
| Provenance internal only | §3, §9 | TR-25, PCP-3 | **Yes** |
| VerificationRecord: method, date, scope, actor | §4.2 | C-08, DSN-9.13 | **Yes** |
| ListingChange (Correction) with reason and source | §4.3 | glossary, LO | **Yes** |
| AuditEntry written in the same transaction | §8.1 | TR-08 | **Yes** |
| Campaign isolated from ranking | §7.3–7.5 | TR-43, IN-7, PRR 69 | **Yes** |
| Customer identity, one across surfaces | §5.1–5.2 | C-32, AS-1.8 | **Yes** |

### 13.1 Privacy classification against the model

`data-inventory-v1.0.md` classifies by **purpose category** rather than by
entity class name, which is the correct shape for an Art. 46(2) record. Every
personal-data-bearing entity in the data model maps to an inventory category:
Customer and ProviderIdentity → §3; OtpRequest → §6 and DI-6.5; Session →
DI-7.2; Review, Save, Report → §5; PermissionRecord and VerificationRecord →
§4; MediaAsset → §4.1; AuditEntry → §7; AnalyticsEvent → §8; StaffUser → §9.

| Check | Result |
| --- | --- |
| Personal data in the model but missing from the inventory | **None** |
| Entity in the inventory but missing from the model | **None** |
| Retention defined for data that does not exist | **None** |
| Vendor processing data not reflected in the inventory | **None** |
| Public API exposing internal or personal data | **None.** TR-25 and PCP-3/PCP-5 forbid it, and the API spec's public responses carry no provenance, Permission, verification evidence or reporter identity |

One register gap was found and corrected: **media storage / CDN** (F-04).

## 14. API audit

`api-spec-v1.0.md` was compared against the PRD, data model, auth model,
platform contract and the actual routes.

| Dimension | Result |
| --- | --- |
| Endpoint-family coverage | Complete: discovery, profiles, search, reports, auth, `/me`, saves, reviews, review reports, data rights, `/ops/*` |
| Every endpoint traced to a `C-xx` | **Yes** |
| Authentication rules | Consistent with D-48 and `auth-identity.md`. `/auth/telegram/context` is explicitly **not** a login (AU-4) |
| Authorization | Server-side, named permission, per request (O-6, TR-29, TR-36) |
| Error conventions | E-1…E-6 envelope, consistent with `platform-error-handling-v1.0.md` |
| Pagination | Defined once, bounded page size, stable ordering (SRCH-6) |
| Caching | Consistent with TD-05 — no cache service assumed |
| Idempotency | `PUT`/`DELETE` on saves declared idempotent |
| Rate limiting | Present; the shared-hosting implementation is correctly left **Open — implementation detail** |
| Correlation IDs | TR-134; every log line carries one |
| Public/private boundary | `/api/v1/ops/*` separated, with its own authorization and rate-limit policy |
| Endpoints implied by UX but missing | **None found** |
| API behaviour contradicting the platform contract | **None** |
| API behaviour creating an excluded feature | **None.** There is no business-authentication endpoint, no claim endpoint, no owner-reply endpoint |

Current routes in the repository are `GET /`, `/health`, `/health/ready`,
`/api/v1/health`, `/api/v1/health/ready`. **No business feature is
implemented**, which matches the Phase 1 boundary.

## 15. Authentication audit

The V1 Customer model remains **Google + email OTP**, with **no passwords**
and **no Apple**, stated identically in D-48, AS-1.1…AS-1.3,
`auth-identity.md`, `platform-auth-v1.0.md`, the PRD and the API spec.

| Element | Result |
| --- | --- |
| Web flow | Specified |
| Telegram flow | Specified; launch payload validated server-side; establishes a surface, not an identity (TM-7.3, TM-7.9, TM-8.8) |
| Opaque token / session model | TD-02, one record, two transports |
| Secure session handling | `HttpOnly`, `Secure`, `SameSite=Lax`, rotation on privilege change |
| CSRF | All state-changing HTML form submissions; `GET` never changes state |
| OTP replay prevention | Single-use, short-lived, rate-limited, hashed at rest |
| Enumeration resistance | AU-2: `/auth/otp/request` returns the same response regardless of account existence |
| Task preservation across sign-in | Specified in `user-flows-v1.0.md` and `platform-auth-v1.0.md` |
| Account deletion | C-36; `POST /me/deletion`, `DELETE /me` |
| Provider linking | **Open (D-13)**, explicitly not decidable by implementation (AS-6.4) |

| Decision | Required state | Actual state |
| --- | --- | --- |
| **D-13** | Open | **Open.** No document closes it |
| **D-33** | Open | **Open.** Six documents state it must not be decided by implementation |
| **D-45** | Open | **Open.** AS-9.2 calls it *"the single highest-impact unresolved security decision in V1"* |

## 16. Web / Telegram coherence

The two V1 surfaces remain Web and Telegram Mini App (D-15, D-49).

| Shared, and verified identical | Adaptation permitted |
| --- | --- |
| Product behaviour · domain rules · API · terminology · Business and Listing data · Reviews · Saves · search semantics · organic ranking · sponsorship rules | Host navigation · viewport · safe area · theme · external links · sharing · authentication handoff · lifecycle |

`shared-client-contract-v1.0.md` fixes this with SCC-1.x (a difference in
*capability* or in *what the user is told* is a product decision), SCC-2.1…2.22
(the identical list) and SCC-3.1 (a **closed list of nine** adapter entries; a
tenth requires an owner decision). **No accidental product divergence was
found.** The only two capability exceptions are C-37 and C-19…C-29, and
neither removes anything a Guest or Customer can do.

## 17. UX/UI coherence

| Requirement | Result |
| --- | --- |
| Mobile-first | D-52, UXP-2; designed at 360 px before 1280 px |
| WCAG 2.2 AA status represented correctly | **Yes, and carefully.** The individual requirements bind and are explicitly **not** `[P]` (A11-1.4); whether AA becomes a hard *contractual* requirement is NFR-AC1, open. These are kept distinct |
| Zero-friction discovery | UXP-3, GS-1…GS-5 |
| No sign-in barrier to public browsing | TR-34, SCC-2.20 — no modal, teaser or truncation |
| Save remains private | C-14, SCC-2.9; no counts anywhere |
| Sponsored clearly distinct | DSN-9.1: label **and** container **and** segregation; DSN-9.2 states shading alone is insufficient |
| Verified not colour-only | DSN-9.12: icon + word + colour, any two sufficient alone |
| Open / Closed / Hours not confirmed not colour-only | DSN-9.17, DSN-9.18 |
| Business-profile hierarchy coherent | `public-web-ux-v1.0.md` and `component-spec-v1.0.md` agree |
| No invented logo | **None.** D-53 open; `design-system-v1.0.md` §11 declines to create one |
| Proposed visual values remain proposed | **Yes.** 43 `[P]` markers in the design system; no exact colour is fixed |

**No implementation-like CSS or HTML is presented as a product decision.**
There is no `css`, `scss`, `html` or `js` code block anywhere in
`20-ux-ui/` or `35-platforms/`. Tokens are named semantically
(`color.brand`, never `color.orange`), exactly as DSN-1.2 requires.

## 18. Security / privacy coherence

| Requirement | Result |
| --- | --- |
| No sale of customer data | DI-10.13: *"There is no commercial data flow out of Bulbula at all"* |
| No behavioural advertising | D-10, NFR-PR4, ADV exclusions |
| Location only when needed | C-07 Nearby only; coordinates approximate in responses |
| Save private | Consistent across product, UX, platform, data model |
| Public caches contain no personal data | PBD-10.3: signed-in and anonymous responses never share a cache entry |
| Business information can still be personal data | PG-7.7, DI §4.2 *"the central problem"*, L-8 |
| Telegram client data not blindly trusted | TM-8.8, TM-7.9, TM-7.15, VT-3.12 |
| Authorization server-side | TR-38, TR-40, SEC-4.1 |
| Secrets not logged | TR-135, SC-2; gitleaks over tree **and** full history |
| Uploads secured | APP §6: type, size and content checks; outside document root; never executed |
| Incident response exists | `incident-response-v1.0.md` (IR-), with the statutory clock |
| Rights handling exists | `data-subject-rights-v1.0.md` (DSR-); windows **PENDING COUNSEL (L-7)** |
| Vendor / transfer register exists | `vendor-and-transfer-register-v1.0.md`; completed in this phase (F-04) |
| Privacy notice requirements exist | `privacy-notice-requirements-v1.0.md`, mapped to Art. 24's fifteen items |

SEC-1.1…SEC-1.4 keep the two sets correctly related: *"A perfectly secure
system can still violate privacy… Both document sets are binding; neither
overrides the other."*

## 19. Legal-source audit

Governing instrument: **Proclamation No. 1321/2024**, Federal Negarit Gazette
No. 35, 24 July 2024.

| Check | Result |
| --- | --- |
| Incorrect article numbers | **One found and corrected** — F-01 |
| Draft-era article numbers | **None in use.** Two documents *quote* the draft numbering in order to warn against it (`security-architecture-v1.0.md`, `privacy-governance-v1.0.md`), which is correct practice |
| Stale interpretations | **None** |
| Invented obligations | **None** |
| Invented exemptions | **None** |
| Invented deadlines | **None.** The 72-hour clock is statutory (Art. 43, 44) and is always cited as such |
| Invented retention periods | **None.** Every retention period is **PENDING COUNSEL (L-21)** |
| Invented lawful bases | **None.** PG-7.1 states outright: *"No lawful basis is confirmed in this document. The table is a structured question for counsel, not an answer"* |
| Unsupported cross-border conclusions | **None.** VT §4 assesses Art. 20(1)(a)–(d) as candidates and explicitly declines to conclude |

### 19.1 The two previously reported defects — both were still present

**F-01 — residency cited as Art. 20.** `deployment.md` EN-4 and BK-6 cited
*"Proclamation 1321/2024 Art. 20's residency requirement"*. The enacted text
places **data sovereignty at Art. 22(1)**; Art. 20 governs the *bases for
cross-border transfer*. `security-architecture-v1.0.md` and
`vendor-and-transfer-register-v1.0.md` had already **identified** this defect
in Phase 3.5 and recorded the correct citation — but the defect was never
fixed in its source file. **Corrected**, citing REG-15 and VT §5. Both
articles are now named, each for its own obligation, and the pivotal question
(whether Art. 22(1) imposes an independent local-storage duty beyond Art. 20)
is preserved as **PENDING COUNSEL (L-2, VT-5.4)**.

**F-02 — stale `D-46b` sub-identifiers.** Seven citations of `D-46b` and one
of `D-46a` existed in `prd-v1.0.md` and `review-policy.md`. Neither exists in
the live register, which consolidated them into **D-46**. **Corrected.**

### 19.2 PENDING COUNSEL preserved

All eighteen remain open and none was converted into a compliance claim:
**L-2** (transfer basis) · **L-3** (registration) · **L-4** (DPO) ·
**L-5** (lawful basis per purpose) · **L-6** (legitimate interests) ·
**L-7** (rights procedures and windows) · **L-8** (publishing business and
personal contact data) · **L-10** (hosting, email, backups) ·
**L-12** (Google, Telegram) · **L-15** (reviews as personal data, defamation) ·
**L-16** (takedown) · **L-17** (advertising disclosure) ·
**L-18** (photography of premises and people) · **L-19** (maps terms) ·
**L-20** (entity, tax, invoicing) · **L-21** (retention periods) ·
**L-22** (breach mechanics) · **D-46** (minimum account age).

Specifically preserved as required: the **Art. 22 / Art. 20 interaction**,
**D-42 / D-42b**, **Art. 24 notice timing** where data was not obtained from
the subject (PNR-3.18, PNR-5.7 — acute for Bulbula, because it discloses by
publishing), **minors** (Art. 11, D-46), **automated decision-making**
(PNR-3.14), **registration** (L-3), **DPO applicability** (L-4, Art. 40
"large scale" undefined), **cross-border processing** and **advertising
obligations** (L-17).

PNR-7.1 forbids any claim of compliance, certification or approval that has
not been obtained. **No document claims compliance.**

## 20. Privacy data-flow audit

End-to-end flows constructed and compared against the inventory,
privacy-by-design, retention, subject rights, the vendor register and notice
requirements.

| Flow | Collection → Deletion | Mismatch |
| --- | --- | --- |
| Customer account | Sign-in → account record → staff/self access → no sharing → **L-21** → C-36 deletion | None |
| Google identity | OAuth → ProviderIdentity → internal → **Google is a recipient (DI-10.2)** → with account → with account | None |
| Email OTP | Request → hashed OTP → single-use → **email provider (DI-10.1)** → short → on expiry | None |
| Review | Submission → moderation → **published, irreversibly indexable (DI-10.8)** → **L-21** → DSR §5 limits | None; the irreversibility is stated, not hidden |
| Save | Action → private record → never shared → with account → with account | None |
| Report | Submission → staff only → **reporter identity never exposed (PCP-5)** → **L-21** → with resolution | None |
| Nearby location | Explicit request → query only → **maps provider sees IP (DI-10.4)** → not stored | None |
| Staff-collected business information | Field/desk → Listing + Permission + provenance → published → **L-8** on personal contact points → correction/unpublish | None |
| Photos and media | Staff capture → MediaAsset → published → **L-18 PENDING COUNSEL** → PBD-9.2/9.3 strict defaults apply meanwhile | None |
| Campaign measurement | Delivery counts → internal only → never public, never a ranking signal (MS-2) | None |
| Logs | Request → correlation id, no credentials (TR-135) → `storage/logs/` → **Art. 46(4), Authority sets retention — Unknown** | None |
| Backups | Snapshot → **residency Art. 22(1)** → restore-tested → **L-21** → BR-3.7 | None after F-01 |

**Every flow with a vendor appears in the vendor register** (DI-10.11),
true after F-04. **No flow sends personal data to a destination not listed**
(DI-10.12).

## 21. Vendor and transfer audit

| Dependency | In the register | Personal data | Location | Transfer status |
| --- | --- | --- | --- | --- |
| Hosting | §3.1 | **All data at rest** | **Open (D-42b)** | **Art. 22(1) — PENDING COUNSEL (L-2, L-10)** |
| Google (auth) | §3.2 | Email, account identifier | Foreign | Cross-border — **PENDING COUNSEL (L-12)** |
| Transactional email | §3.3 | Address, OTP, content | **Open (D-41)** | Probably cross-border — **PENDING COUNSEL (L-10)** |
| Telegram | §3.4 | Mini App usage signal | Foreign | Telegram's own processing — reading **PENDING COUNSEL** |
| Maps | §3.5 | IP on page load | **Open (D-21)** | **PENDING COUNSEL (L-19)** |
| CDN / fonts / scripts / analytics | §3.6 | **None in V1** | — | CSP is `default-src 'self'`; adding one is a full assessment |
| **Media storage / object storage** | **§3.7 — added by this audit (F-04)** | Imagery; delivery IPs | **Open (D-25)** | **No vendor in V1** — the local-filesystem adapter is the default (TR-84) |
| Payment provider | §3.7 | — | — | Not in V1 (D-11) |
| Monitoring / APM | §3.7 | — | — | Not in V1 (OT-07) |
| DNS | §3.7 | Query metadata | — | Open — implementation detail |

**No external dependency now appears only in a technical document.**
Cloudflare and Cloudflare R2 appear only in the **discovery layer** as
historical options; no current document commits to them.

## 22. Advertising integrity audit

`paid ≠ organic` is enforced consistently in **all** of PRD §19, ADV-1…ADV-12,
IN-1…IN-7, LB-1…LB-8, PL-1…PL-7, DSN §9, CDN-11.4, PA-15, OPX §10, MO §5,
AO §11 and OM §8.

| Rule | Held |
| --- | --- |
| Fixed packages, staff-managed | **Yes** (D-10) |
| No auction, no bidding | **Yes**; business-model §4.3 argues the choice |
| No CPC / CPM / CPA | **Yes** |
| No paid ranking | **Yes.** PA-15.3; TR-43 requires identical organic order with and without a Campaign |
| No paid verification | **Yes.** ADV-9, DSN-9.15 |
| No paid inclusion | **Yes** |
| Explicit Sponsored labelling | **Yes.** Three mechanisms together; the word is exactly "Sponsored" |
| Campaign eligibility, approval, audit | **Yes.** AO §5, OPX §10, TS-15 |
| Measurement isolated from ranking | **Yes.** MS-2: campaign metrics are internal, never public, never a ranking input |

### 22.1 The launch / first-Campaign distinction

**Represented consistently.** PRR-3.11 states D-39 *"blocks the first paid
Campaign, not the launch itself"*, and PRR-3.12 makes it a separate gate:
*"The directory may launch without advertising; it may not run a paid
Campaign without D-39."* The same framing appears verbatim in
`advertising-products.md` IN-6, `business-model.md` §4, `operations-model`
OM-8.7, `moderation-operations` MO-5.7, `advertising-operations` AO-0.5 and
TRD TR-78. **No document contradicts it.**

Production-readiness §4.7 (criteria 65–70) is correspondingly scoped to the
first paid Campaign. Criterion 69 is the one with an immediate build
obligation: organic ordering must be provably identical with and without an
active Campaign.

## 23. Operations audit

| Requirement | Result |
| --- | --- |
| Company-operated listings | **Held** (D-54, D-50) |
| Permission as a publication gate | **Held** (D-50, LO) |
| Provenance recorded, internal only | **Held** (C-07, TR-25) |
| Verification with method, date, scope, actor | **Held** (C-08) |
| Second-person quality review | **Present**, and honestly qualified: LO and `interaction-permissions.md` record that with a single Staff member this is an **accepted, recorded operational gap**, not a solved problem |
| Correction with reason and source | **Held** |
| Re-verification scheduled | **Held**; a stale record shows its date rather than hiding the badge |
| Moderation independence | **Specified**, with MO-9.5 naming the structural conflict where one person both sells and moderates — exactly what D-39 must address |
| Campaign controls | **Held** (AO §5, §11) |
| Backup and recovery | **Held**; BR-2.9 *"an untested backup is not a backup"* |
| Business continuity | **Held**; BC §3.3 records the single-Administrator risk rather than designing around it |
| Customer support routing | **Held** (SUP §3), including escalation to privacy and security |

**D-14 (operator / administrator split) remains unresolved**, and no document
assigns a role to close an operational gap. OM-0.3 states the rule: *"No
unresolved permission is silently assigned to an actor. Where D-14 is open,
the required permission is named and the role is not."* OPX-0.1 repeats it for
every console screen.

## 24. Pilot and launch audit

| Item | Required state | Actual state |
| --- | --- | --- |
| **D-31** | 20-business pilot intact | **Intact.** Stated identically in the register, glossary, scope, PRD, PRR and `listing-operations.md` §6 |
| **D-30n** | Numeric launch threshold unresolved | **Unresolved.** PENDING PILOT everywhere |
| **D-40** | Launch-area boundary unresolved | **Unresolved.** Open Class A |

**No fabricated launch number exists.** Scanned for hidden percentages,
minimum listing counts, minimum coverage, throughput targets, staffing
targets and support SLAs.

| Scan | Result |
| --- | --- |
| Percentages | Only `[P]`-tagged design proposals (viewport share, string expansion) and the 100 % quality gates that genuinely exist in CI |
| Minimum listing / coverage counts | **None.** LO-0.3: *"No target time, throughput figure or quality threshold is stated. All are PENDING PILOT"* |
| Throughput | **None** |
| Staffing | **None.** OM-3.7: *"Nothing in this model assumes a headcount"* |
| Support SLA | **None.** OBS-7.4: no availability percentage is committed; QS-0.3: no defect-rate, uptime or satisfaction target is approved |
| Prices | **None.** PRR criterion 66: *"No price is approved"* |

## 25. Status and version audit

| Check | Result |
| --- | --- |
| Current formal documents at `Version: v1.0`, `Status: Draft` | **64 / 64** |
| A current v1 document accidentally marked Approved | **None.** The register is Approved by design, as the authority |
| A superseded document appearing current | **None** |
| A document referencing a nonexistent version | **None** |
| A document claiming to supersede a file it does not replace | **None.** Every `Supersedes` entry names a file that was genuinely replaced or relocated |

## 26. Cross-reference audit

| Reference class | Checked | Result |
| --- | --- | --- |
| Relative markdown links | All, across 69 documents | **All resolve** |
| Backticked `docs/` paths | All | All exist or are framed as future/history |
| Section references | Spot-checked across layers | Consistent |
| `D-xx` | All occurrences | Real, after F-02 |
| `C-xx` | All occurrences | C-01…C-40 only |
| `TR-xxx` | All occurrences | All defined in the TRD |
| `TD-xx` | All occurrences | TD-01…TD-07, no contradictions |
| Local IDs | All prefixes | No collision; every prefix owned by exactly one document |
| Legal references | All | Enacted numbering only, after F-01 |

## 27. `.ai` consistency audit

| Check | Result |
| --- | --- |
| Formal sources still match | **Yes**, after rebaselining |
| Context baselines valid | **Yes.** All eleven derived files at `3b502eb`, verified to be a real commit |
| Staleness indicators accurate | **Yes.** `Context status: Current` is now mechanically defensible |
| Any `.ai` file claiming authority | **None** |
| Any open decision presented as resolved | **None** |
| Any excluded feature become a requirement | **None** |
| Terminology matches the glossary | **Yes** |
| Context architecture matches the formal architecture | **Yes.** `architecture-context.md` §3 reproduces the 18 real assertions |
| Progress tracker matches Git | **Yes.** Updated this phase: 3.7 → PR #22, 3.8 in progress |
| Source map points to real files | **Yes** |
| `.ai` restrictions still accurate | **Yes** |

Because formal documentation changed in this phase, **all eleven derived
`.ai/` files were refreshed** from `9730b54` to `3b502eb` (F-08). The
`.ai/` verifier's check 11 was rewritten to enforce exactly this: a file may
not claim `Context status: Current` when `docs/` has moved since its recorded
baseline. That is a stronger check than the Phase 3.7 one it replaces.

## 28. Stale-information audit

| Source | Finding |
| --- | --- |
| `00-discovery/` | Contains Apple sign-in, Flutter-first assumptions, claim flows, Cloudflare R2, `L-1`/`L-9`/`L-11`/`L-13`/`L-14`, `D-46a`/`D-46b` and Phase-2 path plans (`docs/10-product/prd-v1.md`). **All historical. None leaks into a current document.** v0.4 names the register as its authoritative companion in its control block |
| `project-understanding-v0.1.md` §rate-limiting | Mentions "claim submission" — an obsolete V1 assumption, inside a frozen historical record. **Recorded, not edited** (F-09, Informational) |
| Current documents | **No stale assumption copied forward.** No superseded decision table is treated as authoritative |

Discovery material was not deleted to make a scan pass.

## 29. Findings

| ID | Finding | Severity | Status |
| --- | --- | --- | --- |
| **F-01** | `deployment.md` EN-4 and BK-6 cited the residency requirement as **Art. 20**; the enacted text places data sovereignty at **Art. 22(1)**. Phase 3.5 had identified the defect but never corrected the source file | **Critical** — legal misrepresentation in the document that governs where data is hosted | **Fixed** |
| **F-02** | Seven citations of `D-46b` and one of `D-46a` in `prd-v1.0.md` and `review-policy.md`. Neither identifier exists in the live register | **High** — fictional decision IDs break traceability in two product documents | **Fixed** |
| **F-03** | Four current documents cited Proclamation articles without a `## Legal and regulatory references` section, so their legal claims were unsourced | **High** — legal sourcing convention applied only in `50-security/` and `55-privacy/` | **Fixed** |
| **F-04** | Media storage / object storage is named as an external dependency in the TRD (TR-84, §8 degradation table) but had **no entry in the vendor and transfer register** | **Medium** — a dependency visible in one technical document only, contrary to VT §1 | **Fixed** |
| **F-05** | `decision-register.md` used "favourites" in two descriptive prose lines; the glossary canonicalises **Save** | **Low** — terminology drift in the authority document | **Fixed, and reported, not silent** (§20) |
| **F-06** | The Phase 3.5 and 3.6 verifiers were never committed to the repository, so their checks could not be re-run by anyone else | **Medium** — QA that exists only in one working session is not QA | **Fixed** by `tools/verify-docs.py` |
| **F-07** | C-20, C-22, C-23 and C-24 are covered in substance by `docs/40-operations/` but not cited there by `C-xx` identifier | **Low** — identifier traceability only; no coverage gap | **Not fixed.** Adding identifiers to Phase 3.6 deliverables is an enhancement, not a correction |
| **F-08** | `.ai/` claimed `Context status: Current` against the pre-audit baseline once `docs/` changed | **Medium** — would have been a true staleness defect if left | **Fixed**, and now mechanically prevented |
| **F-09** | `project-understanding-v0.1.md` mentions rate-limiting "claim submission", an obsolete V1 assumption | **Informational** — frozen historical record; D-02 excludes claims | **Not fixed, by policy** |
| **F-10** | `pilot-benchmark-v1.0.md` is referenced by five documents and does not exist | **Informational** — correctly framed as produced when the pilot runs; PRR criterion 27 gates on its existence | **No action** |
| **F-11** | The outbound-HTTP gateway namespace required by TD-07 does not yet exist in `src/`; `curl_init` is currently confined to `Bulbula\Database` | **Informational** — a build instruction the documentation already states (TR-01a, AC-1) | **No action in this phase** |

**Critical 1 · High 2 · Medium 3 · Low 2 · Informational 3.**
All Critical, High and Medium findings are fixed.

## 30. Corrections made

| File | Problem | Correction | Source used |
| --- | --- | --- | --- |
| `docs/30-technical/deployment.md` | EN-4, BK-6 cited Art. 20 for residency | Cite **Art. 22(1)** for data sovereignty and **Art. 20(1)** for transfer bases; preserve the open question | `privacy-governance-v1.0.md` REG-15, REG-13; `vendor-and-transfer-register-v1.0.md` §4, §5 |
| `docs/10-product/prd-v1.0.md` | 4 citations of `D-46a`/`D-46b` | Replaced with **D-46** | `decision-register.md` §2.1 |
| `docs/10-product/review-policy.md` | 3 citations of `D-46b` | Replaced with **D-46** | `decision-register.md` §2.1 |
| `docs/00-discovery/product-decision-brief-v0.4.md` | §8 presented `D-46a`/`D-46b` without saying they are historical | Added a note recording the consolidation into D-46 and that they are not live identifiers. **Table left intact** | `decision-register.md` §2.1 |
| `docs/55-privacy/vendor-and-transfer-register-v1.0.md` | Media storage absent | Added a §3.7 row and a §8 unresolved row; added D-25 to the decision references | TRD TR-84 and §8; D-25; VT-3.20 |
| `docs/45-quality/production-readiness-v1.0.md` | No legal-references section | Added, covering Art. 11, 20, 22, 40, 43, 44 | `privacy-governance-v1.0.md` §11 |
| `docs/40-operations/business-continuity-v1.0.md` | No legal-references section | Added, covering Art. 16, 43, 44, 50 | `privacy-governance-v1.0.md` §11 |
| `docs/40-operations/backup-recovery-v1.0.md` | No legal-references section | Added, covering Art. 15, 20(1), 22(1), 44(3)(a) | `privacy-governance-v1.0.md` §11 |
| `docs/60-decisions/decision-register.md` | "favourites" in two prose lines | "Saves". **No decision content changed** | `glossary.md` §3 |
| `tools/verify-ai-context.py` | Check 11 forbade `docs/` changes — correct for Phase 3.7, wrong afterwards | Replaced with a baseline-freshness check | §26 of the audit brief |
| `.ai/` ×11 | Baseline predated the corrections | Rebaselined to `3b502eb`; README, progress tracker and source map updated | This audit |

### 30.1 What was deliberately **not** changed

- **No decision was made, closed, reopened or reinterpreted.**
- **No legal interpretation was supplied.** Every `PENDING COUNSEL` marker
  survives.
- **No V1 scope was expanded to resolve a contradiction.** The
  single-Staff-member quality-review gap and the single-Administrator
  continuity risk remain recorded limitations, not new roles or features.
- **No historical document was rewritten to erase history.**
- **No quality gate was weakened.** Two verifier false positives were fixed by
  making the checks *more precise*, never by removing a check; the one check
  that was replaced was replaced with a stricter one.

## 31. Remaining blockers

| ID | Blocker | Type | Owner | Impact | Must resolve before |
| --- | --- | --- | --- | --- | --- |
| **L-5** | Lawful basis for each processing purpose | Legal | Counsel | Art. 24(1)(f) requires telling the subject; the notice cannot be written without it | **Production launch** |
| **L-2 / D-42** | Whether Art. 22(1) imposes an independent local-storage duty | Legal | Counsel | Decides whether a foreign host is available at all | **Production launch**; also blocks the hosting purchase |
| **L-10 / L-12** | Cross-border assessment: hosting, email, backups, Google, Telegram | Legal | Counsel | Determines whether Google sign-in and the email channel are lawful as designed | **Production launch** |
| **L-7** | Rights procedures, windows, identity-verification standard | Legal | Counsel | C-36 cannot be given a response time | **Production launch** |
| **L-21 / D-46** | Retention periods per data class; minimum account age | Legal | Counsel | Retention jobs have no schedule to enforce | **Production launch** (schema may proceed — see §32) |
| **L-3 / L-4** | Registration obligation; whether a DPO is required | Legal | Counsel | Organisational, not architectural | **Production launch** |
| **L-8** | Publishing business and personal contact data without the individual's consent | Legal | Counsel | Goes to the core of the directory model | **Production launch** |
| **L-15 / L-16** | Reviews as personal data, defamation, takedown | Legal | Counsel | Shapes moderation obligations | **Production launch** |
| **L-18** | Photography of premises and people | Legal | Counsel | Media pipeline policy; PBD-9.2/9.3 strict defaults hold meanwhile | **Production launch** |
| **L-17 / L-20** | Advertising disclosure; entity, tax, invoicing | Legal | Counsel + finance | Billing cannot be arranged | **First paid Campaign** |
| **D-31** | Execute the 20-business pilot | Pilot | Bulbula operations | Produces every operational baseline | **Production launch** |
| **D-30n** | Numeric launch bar | Pilot → owner | Owner | No definition of "ready to launch" exists | **Production launch** |
| **D-40** | Launch-area boundary | Product | Local confirmation | Area taxonomy seed data | **Content production**; does not block code |
| **D-39** | Commercial / editorial integrity controls | Product | Owner | Structural conflict where one person sells and moderates | **First paid Campaign** — explicitly **not** the launch |
| **D-11** | Billing and invoicing mechanics | Product | Owner | — | **First paid Campaign** |
| **D-45** | Staff authentication strength | Security | Owner | *"The single highest-impact unresolved security decision in V1"* (AS-9.2). Until resolved, the console must not be treated as safe to operate | **Operations console implementation** |
| **D-14** | Operator / Administrator split | Operations | Owner | Permissions exist as data, so the split is configuration; screens name permissions, not roles | **Does not block implementation** |
| **D-13** | Identity-linking rules | Product | Owner | Account-model edge cases | **Account implementation** |
| **D-33** | Telegram identity integration | Product | Owner | Not needed for V1; Telegram is a surface, not a provider | **Does not block implementation** |
| **D-08** | Verification rules, tiers, re-verification interval | Product | Owner | Verification record shape is defined; the policy is not | **Content production** |
| **D-34** | Review mechanics: per-business vs per-branch, edit window | Product | Owner | **Affects the data model.** The Review table's parent is not settled | **Review implementation** |
| **D-55** | Which attributes bind to Branch vs Business | Product | Owner | **Affects the data model** | **Directory implementation** |
| **D-56 / D-57** | Category catalogue production and cardinality | Product | Owner | Seed data and the listing↔category relation shape | **Directory implementation** |
| **D-04** | Opening-hours model | Product | Owner | `data-model.md` §3.9 defers the structure deliberately | **Hours implementation** |
| **D-53** | Exact brand colours and the logo | UX | Owner | Components are specified with semantic tokens, so structure can proceed | **Visual finish**; does not block structure |
| **D-16 / D-17** | Frontend JS approach; view layer | Technical | Owner | SPA already rejected; both are Class B/C | **Frontend implementation** |
| **D-21 / D-25 / D-41 / D-42b** | Maps, media storage, email provider, hosting vendor | Technical | Owner | Each has a documented local/no-vendor default | **Integration work**; local defaults unblock development |
| **D-23** | Production configuration and secret custody | Technical | Owner | *"The central deployment decision"* | **Deployment** |
| **D-20** | Host limits and MariaDB tuning | Technical | Owner | — | **Deployment** |

### 31.1 Blocker classification

| Class | Items |
| --- | --- |
| **Blocks implementation** | **D-45** (operations console only). *At the time of this audit this class also held D-34, D-55, D-56 and D-57; all four were approved on 2026-10-07 — see the addendum at §34.* |
| **Does not block implementation** | D-14, D-33, D-53, D-40, D-08, D-13, D-04, D-16, D-17, D-20, D-21, D-23, D-25, D-41, D-42b — each has a documented default, an interface, or affects only a later surface |
| **Blocks production launch** | **All legal items** (L-2, L-3, L-4, L-5, L-7, L-8, L-10, L-12, L-15, L-16, L-18, L-21) · **D-31**, **D-30n**, **D-42** |
| **Blocks the first paid Campaign** | **D-39**, **D-11**, **L-17**, **L-20**, and production-readiness criteria 65–70 |

## 32. Implementation-readiness matrix

| Domain | Status | Reason |
| --- | --- | --- |
| **Product** | `READY WITH OPEN ITEMS` | C-01…C-40 defined, traced and bounded. At the time of audit **D-34, D-55, D-56 and D-57** had to be answered before the corresponding tables were built; **all four were approved on 2026-10-07** (§34). The remaining open items do not shape tables |
| **Business** | `READY WITH OPEN ITEMS` | Packages, placements and integrity rules specified. No price approved; D-11 open. Neither blocks building the directory |
| **UX/UI** | `READY WITH OPEN ITEMS` | Components, states, flows, content and accessibility are specified. D-53 leaves exact colours and the logo open; semantic tokens make structure implementable now |
| **Technical** | `READY` | TRD, architecture, data model, API and the seven TD invariants are complete, mutually consistent and match the repository. Every external dependency has a local or no-vendor default |
| **Web** | `READY` | `web-platform-v1.0.md` plus five cross-cutting platform documents; nothing unresolved blocks a Web surface |
| **Telegram** | `READY WITH OPEN ITEMS` | The surface is fully specified. D-38 (navigation model) and D-33 (identity) are open; neither is required for V1, since Telegram is a surface and not a provider |
| **Security** | `BLOCKED` | **D-45 is unresolved and AS-9.3 states the console must not be treated as safe to operate until it is.** The public surface is ready; the operations console is not |
| **Privacy** | `BLOCKED` | **L-5 (lawful basis) and L-21 (retention) are unanswered.** Data-protection-by-default design can proceed; collecting real personal data cannot |
| **Operations** | `READY WITH OPEN ITEMS` | Nine documents, no gap. D-14 open but explicitly non-blocking; D-08 and D-31 gate content, not code |
| **Quality** | `READY` | Gate is green and enforced: 424 tests, 1,033 assertions, 100 % line and type coverage, PHPStan at max, 18 architecture assertions, Infection at MSI 100 |
| **Legal** | `BLOCKED` | Eighteen `PENDING COUNSEL` items. No lawful basis is confirmed for any purpose |
| **AI context** | `READY` | 12 files, baselined at `3b502eb`, 28 checks passing, freshness now mechanically enforced |

### 32.1 How to read this

**Three domains are BLOCKED, and none of them blocks writing code today.**
Security is blocked only for the operations console; Privacy and Legal block
*processing real personal data*, which means they block **launch**, not
development. Building the schema, the directory, search, the public Web
surface and the Telegram surface against synthetic data is unblocked —
and the condition attached here at the time of audit, that D-34, D-55, D-56
and D-57 be answered before the tables they shape are created, **has since
been satisfied** (§34).

## 33. Final audit conclusion

### 33.1 Scorecard

| Category | Result | Evidence |
| --- | --- | --- |
| **Authority** | **PASS** | One source of truth for decisions; no competing claims; `.ai/` subordinate and mechanically checked |
| **Completeness** | **PASS** | Every V1 capability documented across product, UX and technical layers; no missing document |
| **Traceability** | **PASS** | D-xx, C-xx, TR-xxx and TD-xx all resolve, after F-02 |
| **Scope integrity** | **PASS** | No excluded feature appears as a V1 requirement anywhere |
| **Terminology** | **PASS** | Canonical after F-05; deprecated terms appear only as prohibitions |
| **Architecture** | **PASS** | Seven invariants uncontradicted; documentation matches the repository exactly |
| **UX** | **PASS WITH OPEN ITEMS** | Fully specified; D-53 leaves exact colours and the logo open by design |
| **Platform** | **PASS** | Two surfaces, one product; closed adaptation list; no divergence |
| **Security** | **PASS WITH OPEN ITEMS** | Controls specified and consistent; **D-45 unresolved** |
| **Privacy** | **PASS WITH OPEN ITEMS** | Inventory, flows, rights, retention structure and register complete; **the answers are PENDING COUNSEL** |
| **Legal sourcing** | **PASS** | Enacted numbering only, after F-01; every citation sourced, after F-03; no compliance claimed |
| **Operations** | **PASS WITH OPEN ITEMS** | Complete; D-14 open and honestly handled |
| **Quality** | **PASS** | Gate green, verifiers repo-resident after F-06 |
| **`.ai`** | **PASS** | Refreshed, verified, freshness now enforced |

**No FAIL.**

### 33.2 Conclusion

The documentation system is **internally consistent, complete for V1,
traceable, non-contradictory, technically coherent and legally honest.** It
is **conditionally implementation-ready**: the technical, Web, quality and
AI-context domains are ready without qualification; product, business, UX,
Telegram and operations are ready with named open items; security, privacy
and legal are blocked on decisions and counsel that no documentation effort
can resolve.

Two things deserve emphasis. First, **the system's honesty is its strongest
property**: it states twice as often what it has *not* decided as what it has,
and the audit found no instance of a number, a lawful basis or a threshold
being invented to fill a gap. Second, **the single most dangerous remaining
defect was the one this audit was told to re-check** — a wrong article number
in the document that governs where personal data is stored, which a previous
phase had correctly diagnosed and then left in place. That is the failure mode
to watch: findings recorded but not applied.

**Four decisions should be taken before any schema work begins: D-34, D-55,
D-56 and D-57.** They are the only open items that shape tables.

> **This recommendation was acted on.** All four were approved on
> **2026-10-07**. See the addendum at §34.

---

## 34. Addendum — 2026-10-07, the M0 schema gate

This audit is a record of what was true when it was carried out, and its
body is left as written. This addendum records what changed afterwards, so
that the document is not read as current where it is not.

| Decision | Outcome approved 2026-10-07 |
| --- | --- |
| **D-34** | A Review belongs to a **Branch**; rating is a required integer **1–5**; text optional; **one active Review per Customer per Branch**; **30-day** edit window with re-moderation; **pre-publication** moderation; author deletion is **withdrawal**; summary is the **unweighted mean of Published ratings**; **newest first**; no minimum account age |
| **D-55** | Brand-level attributes on the **Business**, location-specific operational attributes on the **Branch**; Reviews and location analytics are Branch-level with Business figures derived; an attribute **must not** move level for convenience |
| **D-56** | The catalogue is **centrally curated reference data** over exactly two levels, with no user-created entries |
| **D-57** | Exactly **one primary Category** per published Listing, **zero or more secondaries**, no duplicates, **no artificial maximum** |

**The audit's central finding therefore stands resolved:** the four
decisions that shaped tables have been taken, and schema work is no longer
gated on them. Nothing else in this audit is changed by that. **D-45** still
blocks the operations console for production use, the legal items still
block launch, and **D-39**, **D-11**, **L-17** and **L-20** still block the
first paid Campaign.

---

## Legal and regulatory references

| Reference | Relevance | Classification |
| --- | --- | --- |
| Proclamation No. 1321/2024, Federal Negarit Gazette No. 35, 24 July 2024 | The governing instrument for every privacy statement audited | Confirmed — statute/regulation |
| Art. 22(1) | Data sovereignty; the subject of correction F-01 | Confirmed — statute/regulation |
| Art. 20(1) | Cross-border transfer bases; distinguished from Art. 22(1) in F-01 | Confirmed — statute/regulation |
| Art. 24 | Right to be informed, fifteen items; timing under 24(2)–(3) where data is not obtained from the subject | Confirmed — statute/regulation; application **PENDING COUNSEL** |
| Art. 11 | Minors | Confirmed — statute/regulation; threshold **not established in the enacted text reviewed** |
| Art. 40 | DPO triggers; "large scale" undefined | Confirmed — statute/regulation; applicability **Unknown** |
| Art. 43, 44 | Breach notification | Confirmed — statute/regulation |
| Art. 46(2), 46(4) | Record of processing; logging | Confirmed — statute/regulation |
| Whether any statement in the audited corpus is legally sufficient | — | **PENDING COUNSEL. This audit makes no legal determination** |

The article map is maintained in
[`../55-privacy/privacy-governance-v1.0.md`](../55-privacy/privacy-governance-v1.0.md)
§11. This audit verified citations against it; it did not interpret them.

---

## Decision references

D-01, D-02, D-03, D-04, D-05, D-06, D-08, D-10, D-11, D-12, D-13, D-14,
D-15, D-15r, D-16, D-17, D-18, D-19, D-20, D-21, D-23, D-24, D-25, D-26,
D-27, D-29, D-30, D-30n, D-31, D-32, D-33, D-34, D-35, D-36, D-37, D-38,
D-39, D-40, D-41, D-42, D-42b, D-43, D-44, D-45, D-46, D-47, D-48, D-49,
D-50, D-51, D-52, D-53, D-54, D-55, D-56, D-57.
