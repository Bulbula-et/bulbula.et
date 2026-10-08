# Operations and Quality Context

```text
Source baseline:   b5d606612c10e2a7a3c284b69fafab507f0b92fb
Last derived from: 2026-10-08
Context status:    Current
```

**Derived from** `docs/40-operations/` (nine documents) ·
`docs/45-quality/` (seven documents) ·
`docs/30-technical/deployment.md` · `trd-v1.0.md` §39.
**Authority:** those documents.

---

## 1. The operating premise

Bulbula is **company-operated**. Nobody outside the company edits a Listing.
Every fact on the platform got there because a staff member put it there and
can say where it came from. That makes operations part of the product, not
overhead.

| Rule | Source |
| --- | --- |
| **No business accounts, no self-service, no owner editing** | D-54, D-02 |
| Every published fact carries **provenance** — internally, never publicly | C-07, TR-25 |
| **Permission precedes publication**; a Listing without a Permission record is not published | D-50 |
| Verification records **method, date, scope and actor** | C-08 |
| Every staff action that changes data is **audited in the same transaction** | TR-08 |
| The operations console is **Web only** — the single approved surface exception alongside SEO | C-19…C-29, SCC-4.3 |

---

## 2. Listing lifecycle

```text
Sourced → Drafted → Permission obtained → Verified → Published
                                               ↓
                             Re-verification  ·  Correction  ·  Unpublish
```

| Stage | Rule | Source |
| --- | --- | --- |
| **Sourced** | Candidate recorded with where it came from | LO |
| **Drafted** | Internal record; not public at any point | LO |
| **Permission** | A Business's agreement to be listed. **A publication gate**, not a nicety (D-50). Not the same thing as an individual's consent to publish their personal contact details (PG-7.7) | LO, PG |
| **Verified** | Staff confirmation of specific facts, with method, date, scope, actor | C-08 |
| **Published** | Visible. Shows the **Verified indicator with its date** | DSN-9.13 |
| **Re-verification** | Scheduled refresh. A stale record **shows its date honestly** rather than hiding the badge | UXP-7.3, DSN-9.16 |
| **Correction** | A change to published data **with reason and source** | Glossary |
| **Unpublish** | Reversible removal with a recorded reason | LO |

> **LO-0.3 — No target time, throughput figure or quality threshold is
> stated anywhere. All are PENDING PILOT (D-30n, D-31).**
> **Do not invent one.** Not in a config default, not in a test fixture,
> not in a comment.

---

## 3. Moderation, support, advertising operations

| Area | Prefix | What it governs |
| --- | --- | --- |
| Operations model | `OM-` | Roles, hours, escalation, the shape of the operating function |
| Listing operations | `LO-` | Sourcing, permission, verification, correction, re-verification |
| Moderation | `MO-` | Review moderation, reports, takedowns, appeals |
| Advertising ops | `AO-` | Campaign setup, placement fulfilment, integrity checks |
| Customer support | `SUP-` | Intake, triage, response, escalation to privacy or security |
| Analytics ops | `ANO-` | What is measured, by whom, and for what decision |
| Observability | `OBS-` | Logs, metrics, health, alerting, on-call |
| Backup and recovery | `BR-` | Backup scope, schedule, restore testing |
| Business continuity | `BC-` | Loss-of-person, loss-of-access, loss-of-provider scenarios |

Key standing rules:

| Rule | Source |
| --- | --- |
| **Reporter identity is never revealed**, including to the business | PCP-5 |
| A Review is moderated against published policy, not case by case | MO, `review-policy.md` |
| **Owner replies to Reviews do not exist in V1** | D-12 |
| **Severity is used honestly** — if everything is an error, nothing is | OBS-2.10 |
| **Logs are not a backup** | TR-224, BK-8 |
| **An untested backup is not a backup** — restores are exercised | BR |
| **A single Administrator is a recorded continuity risk** (BC §3.3). It is documented, not engineered around | BC-3.3 |

---

## 4. Quality strategy

| Document | Prefix | Role |
| --- | --- | --- |
| `quality-strategy-v1.0.md` | `QS-` | What "good" means and who decides |
| `test-strategy-v1.0.md` | `TST-` | How it is proven. **§3 is 20 invariants** |
| `release-management-v1.0.md` | `REL-` | How a change reaches production |
| `production-readiness-v1.0.md` | `PRR-` | **§4 is 70 criteria** — the launch gate |
| `maintenance-v1.0.md` | `MNT-` | What happens after launch |

### The automated gate — current, green, and not to be weakened

```bash
composer test
```

`composer validate --strict` → Rector dry-run → Pint `--test` → PHPStan
`level: max` → Pest → type coverage `--min=100`.

**Baseline at `9730b54`: 424 tests · 1 033 assertions · 100 % line coverage ·
100 % type coverage · exit 0.**

`composer test:all` adds PHPInsights and Infection
(`minMsi: 100`, `minCoveredMsi: 100`, `--testsuite=unit`). Infection runs on
`main`, nightly and on dispatch — **it does not gate a pull request today**.

| Rule | Source |
| --- | --- |
| **Gates go up or stay. They never come down** | D-26, AI-G-09 |
| **A surviving mutant is a weak test.** Strengthen the test; never suppress the mutant | TST, owner instruction |
| **Never hide or suppress a failure to make CI green** | Owner instruction |
| **Behaviour lives in `tests/Unit`** or Infection cannot protect it | TST-5.5 |
| **No test touches a real external service**; the gateway is substituted | TST |
| **No test depends on wall-clock time** — the clock is injected | TR-05 |
| **No production personal data in any fixture** — no real name, phone, email or photograph | TST-8.1, TST-8.2 |
| **No credential or token in a fixture** | TR-198, AC-14 |
| Every new namespace is added to the architecture tests | TR-01, AC-1 |

---

## 5. Release and deployment

| Rule | Source |
| --- | --- |
| Every change reaches `main` through a **pull request** on a feature branch | Owner instruction |
| **Never patch a server by hand.** Change the repository and deploy | DP-7 |
| **The environment is configuration, not a code branch.** No `if (production)` scattered through the code | DP-5 |
| **Misconfiguration fails the boot loudly** rather than starting degraded | DP-6 |
| A new env variable enters `.env.example` **in the same change**, or deployment fails at step 5 | REL-7.3 |
| Deployments install from the committed `composer.lock` | `deployment.md` §4 |
| **Migrations run deliberately**, never automatically on deploy or by cron | MG-3, TR-180 |
| **Forward-only in production**; expand-then-contract for breaking changes | MG-2, MG-5 |
| A backup is taken **before** running migrations in production | MG-7, BK-8 |
| A rollback target is identifiable from the commit history | Owner instruction |

---

## 6. Production readiness — 70 criteria

`production-readiness-v1.0.md` §4 lists **70** criteria in seven groups:

| § | Group | Note |
| --- | --- | --- |
| 4.1 | Technical — TRD §39 | |
| 4.2 | Data and content | |
| 4.3 | Operational | |
| 4.4 | Security | |
| 4.5 | Privacy | Several items block on `PENDING COUNSEL` |
| 4.6 | Accessibility and performance | |
| **4.7** | **Advertising — items 65–70** | **Gates the first paid Campaign, not the launch** |

### §4.7 — items 65–70 verbatim in substance

| # | Criterion | Source |
| --- | --- | --- |
| 65 | **D-39** integrity controls resolved and implemented | IN-6, ADV-12 |
| 66 | Prices approved by the owner | **No price is approved** |
| 67 | Inventory counts and density values approved | **`[P]`** in `advertising-products.md` §2, §5 |
| 68 | Sponsored labels verified on **every** placement, on **both** surfaces | ADV-1, ADV-2, LB-1…LB-7 |
| 69 | **Identical organic ordering with and without an active Campaign, proven by test** | TR-43, IN-7 |
| 70 | Billing and invoicing arrangements settled | **Open (D-11)**; **PENDING COUNSEL (L-17, L-20)** |

> **Read 65–70 as a Campaign gate, not a launch gate.** The platform can
> launch with advertising unsold; it cannot take money until these pass.
> Criterion 69 is the one with a direct implementation obligation today:
> **organic ranking must be provably independent of advertising**, so ranking
> code must have no campaign input and a test must prove it.

---

## 7. Open and pending items that affect operations

| ID | Item | Status |
| --- | --- | --- |
| **D-31** | **Launch threshold — how many verified Listings before launch** | **Scheduled / PENDING PILOT.** Derived from pilot measurement (D-30n, ANO-1.5) |
| **D-30n** | Pilot design and measurement | PENDING PILOT |
| **D-39** | **Advertising integrity controls** | **Open — blocks the first paid Campaign** (PRR 65) |
| **D-11** | Billing and invoicing | Open; also PENDING COUNSEL (L-17, L-20) |
| **D-45** | Staff authentication strength | Open — must exceed Customer authentication |
| Prices | Any price, package price or rate card | **None approved.** Do not write one down |
| Throughput | Any verification-per-day, SLA or response-time number | **None stated.** PENDING PILOT |
| Staffing | Any headcount or shift pattern | **None stated** |

**PENDING PILOT means: the number does not exist yet.** The correct
implementation is a configurable value with **no default that looks like a
decision**, plus an explicit note that it is unset.

---

## 8. Prohibitions

| Never | Why |
| --- | --- |
| Invent a target time, throughput, SLA, uptime figure or quality threshold | LO-0.3, PENDING PILOT |
| Invent a price, package price, rate or discount | PRR 66, no price approved |
| Invent an inventory count or placement density | PRR 67, `[P]` |
| Invent a staffing number or shift pattern | Not stated anywhere |
| Treat D-31 as answered | Scheduled / PENDING PILOT |
| Let a Campaign influence organic ranking in any way | TR-43, IN-7, PRR 69 |
| Lower a coverage, PHPStan, Infection, insights or security threshold | D-26, AI-G-09 |
| Suppress a mutant, a PHPStan error, an audit finding or a gitleaks hit | Owner instruction |
| Skip, disable or `continue-on-error` a CI job | Owner instruction |
| Add a down-migration intended to run against production data | MG-2 |
| Run migrations automatically on deploy | MG-3, TR-180 |
| Hand-edit anything on a server | DP-7 |
| Branch code on the environment name | DP-5 |
| Treat logs as a backup | TR-224, BK-8 |
| Reveal reporter identity | PCP-5 |
| Build owner replies, claims, or any self-service surface | D-12, D-02, D-54 |

---

## 9. Where to open the formal document

| Question | Open |
| --- | --- |
| Who does what, and when | `docs/40-operations/operations-model-v1.0.md` |
| How a Listing is sourced, permitted, verified, corrected | `docs/40-operations/listing-operations-v1.0.md` |
| How Reviews and reports are handled | `docs/40-operations/moderation-operations-v1.0.md` |
| How a Campaign is set up and fulfilled | `docs/40-operations/advertising-operations-v1.0.md` |
| How support requests are handled | `docs/40-operations/customer-support-v1.0.md` |
| What is measured | `docs/40-operations/analytics-operations-v1.0.md` |
| Logs, metrics, alerts, on-call | `docs/40-operations/observability-operations-v1.0.md` |
| Backups and restore testing | `docs/40-operations/backup-recovery-v1.0.md` |
| Continuity scenarios | `docs/40-operations/business-continuity-v1.0.md` |
| What quality means here | `docs/45-quality/quality-strategy-v1.0.md` |
| How we test, and the 20 invariants | `docs/45-quality/test-strategy-v1.0.md` |
| How a change ships | `docs/45-quality/release-management-v1.0.md` |
| The 70 launch criteria | `docs/45-quality/production-readiness-v1.0.md` |
| Life after launch | `docs/45-quality/maintenance-v1.0.md` |
| Whether the documentation is consistent, and what still blocks | `docs/45-quality/documentation-audit-v1.0.md` |
| **What to build first, in what order, and what is gated** | **`docs/45-quality/implementation-plan-v1.0.md`** |
| Servers, environments, deploy steps | `docs/30-technical/deployment.md` |
