# Project Overview

```text
Source baseline:   33f58e0e1619c7e5b952eede2382ae3c5e2ccf8c
Last derived from: 2026-10-07
Context status:    Needs review
```

**Derived from** `docs/10-product/prd-v1.0.md` §1–§11 · `scope-v1.md` ·
`docs/15-business/business-model.md` · `docs/60-decisions/decision-register.md` ·
`README.md`. Not a substitute for any of them.

---

## 1. In one line

```text
Bulbula = company-operated local business directory
```

A directory for **Bole Bulbula, Addis Ababa, Ethiopia**, where a resident can
find a real local business and act on it — call, visit, open in maps — in
seconds, with no account.

## 2. Surfaces

```text
V1:
Web + Telegram Mini App
```

Both are **one application**, not two products kept in step by discipline
(D-49). Every capability ships on both, with exactly two exceptions:

| Exception | Reason |
| --- | --- |
| C-37 SEO | Web only — Telegram content is not crawled |
| C-19…C-29 operations console | Web only — staff tooling |

A Flutter client is **post-V1** (D-15), reusing the same backend and the same
`/api/v1` contracts.

## 3. The two rules that shape everything

```text
Business ≠ platform account
```

Businesses have **no account, no login, no dashboard, no self-service
surface** (D-54). They cannot create, claim, edit or publish their own
Listing (D-02). Bulbula staff collect, verify and publish every fact by hand.

Consequences an implementer will hit constantly:

- The **authorization model contains no business role** (TR-40).
- The **public correction route is the safety valve** that substitutes for
  owner editing, and is a first-class capability (TS-1, TS-3).
- **Permission is a gate**: nothing publishes without a linked Permission
  record (D-50, TR-49).

```text
Guest discovery is first-class
```

The complete discovery journey — search, browse, open a profile, call, get
directions, read reviews, share, report a problem — works with **no session,
no cookie interaction and no account** (GS-1, AC-8). Authentication is
required only to *contribute*: write a Review, Save, manage an account.

Guest-safe capabilities must not be degraded, truncated or teased to push
sign-in (TR-34, GS-1…GS-5).

## 4. Core user journey

```text
arrive (search / category / area / shared link)
   → find candidate businesses
   → open a Business profile
   → judge it (verified date, rating + count, hours, photos)
   → act: call · directions · website · social
```

The **contact action is the core value event** (C-11). Everything else exists
to get a person there faster and with more confidence.

## 5. Users and actors

### Public users

| Actor | Authentication | Can |
| --- | --- | --- |
| **Guest** | None | The entire discovery journey; report a problem on a Listing |
| **Customer** | Google **or** email OTP (D-48) | Everything a Guest can, plus write/manage Reviews, Save, report Reviews, manage and delete their account |

### Internal actors

| Actor | Can |
| --- | --- |
| **Operator** | Collect, create, verify, moderate, handle reports, create Campaigns |
| **Administrator** | Higher-risk actions: taxonomy, locations, campaign approval/activation, staff accounts, audit read, data-request execution, policy content, legal escalation |

The exact Operator/Administrator split is **Open (D-14)**. Until it closes,
code names **permissions**, never roles (TR-37).

There is **no business-owner actor** (D-54).

## 6. Business model

| Line | Status |
| --- | --- |
| **Advertising — staff-managed, fixed-package sponsorship** | Approved for V1 (D-10) |
| Everything else | Not in V1 |

Fixed packages · sold offline · staff-managed · no auction · no performance
pricing · invoiced manually, no payment gateway (D-11) · every Campaign
time-bounded and automatically expiring (ADV-4).

**Not monetised, ever:** listing inclusion, verification, trust markers,
organic ranking, review outcomes, correction priority (ADV-8, ADV-9, IN-1…IN-5).

**No price is approved.** No price, inventory count or density value may be
stated as fact.

## 7. Operating model

Bulbula builds its product by hand. The 11-step lifecycle is the supply chain:

```text
discover → contact/visit → Permission → collect → verify → create
  → quality review → publish → maintain → re-verify → correct
```

Steps 3 (Permission), 5 (Verification) and 7 (quality review) are **hard
gates enforced by the product**, not by discipline.

Staff throughput is the binding constraint on coverage, which is why the
launch threshold cannot be set before the **20-business pilot** measures it
(D-30n, D-31 — **PENDING PILOT**).

## 8. Architectural philosophy

| Principle | Consequence |
| --- | --- |
| **Framework-free plain PHP** | No Laravel, Symfony, Laminas, Mezzio, Slim, Flight. Enforced by an architecture test |
| **Composition over magic** | `Application` is a composition root, not a container. No auto-wiring, no annotations, no reflection magic |
| **One direction** | HTTP → application → domain → persistence. Inner layers never reference outer ones |
| **One implementation of every rule** | Web, API and Telegram are adapters over the same application services (D-49) |
| **Server-rendered first** | HTML on the server; JavaScript enhances; core content works without it (D-52, NFR-C3) |
| **Everything typed** | PHPStan `level: max`, 100 % type coverage |
| **Tests that actually test** | Mutation testing gates suite strength; thresholds never lowered (D-26) |
| **Auditability and privacy by construction** | Not by policy alone (C-29, D-51) |
| **Simplest thing compatible with the PRD** | Complexity needs a requirement behind it, not an anticipation |

## 9. Current development state

**The repository is an engineering foundation. No business feature exists
yet.**

| Present | Absent |
| --- | --- |
| HTTP layer (Request, Response, Status, Method, Kernel, emitter) | Users, auth, sessions |
| Routing (FastRoute) + named URLs | Businesses, Branches, Listings |
| Middleware pipeline + `SecureHeaders` | Categories, Areas, taxonomy |
| Thin controllers (`Home`, `Health`) | Search |
| PDO `Connection` + migrations + `bin/console` | Reviews, moderation |
| Config repository, typed env reader | Advertising, campaigns |
| Monolog logging, error handling, health checks | Media, maps, analytics |
| 49 source files, 424 tests, 100 % coverage | Operations console |

Routes that exist today: `GET /`, `GET /health`, `GET /health/ready`,
`GET /api/v1/health`, `GET /api/v1/health/ready`. One migration
(`create_health_checks_table`).

Documentation is far ahead of code — by design. Phases 3.1–3.6 produced the
full specification set; **Phase 3.9 is the first implementation phase**, and
it has not started. See `progress-tracker.md`.

## 10. Geography and language

| Item | Position |
| --- | --- |
| Launch area | **Bole Bulbula**, Addis Ababa. Exact boundary **Open (D-40)** |
| Expansion | The location model stays expandable; the data does not expand in V1 (D-01, NFR-SC1) |
| Interface language | **English-first** (D-18) |
| Amharic | Data stores Amharic names and Aliases; a full Amharic *interface* requires separate approval (D-18) |
| Afaan Oromo | Not in V1 (D-18) |
| Timezone | Stored UTC, rendered for `Africa/Addis_Ababa` |

## 11. Where to go next

`product-context.md` for the capability set and product rules ·
`technical-context.md` for the runtime and subsystems ·
`architecture-context.md` for the boundaries you must not cross ·
`source-map.md` to find the formal authority for any topic.
