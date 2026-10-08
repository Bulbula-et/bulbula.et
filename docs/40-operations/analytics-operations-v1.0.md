# Analytics Operations

| | |
| --- | --- |
| **Document** | Analytics Operations — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

How Bulbula operates analytics: what is measured, how events become
metrics, who reads them, what decisions they inform, how they are
protected, and what is deliberately not measured.

**Out of scope.** The event schema and storage model (TRD §35,
`data-model.md`), the console presentation
(`../20-ux-ui/operations-console-ux-v1.0.md` §11), campaign reporting
(`advertising-operations-v1.0.md` §8–§9), system health
(`observability-operations-v1.0.md` — **a different thing**).

## Authority and precedence

| ID | Rule |
| --- | --- |
| ANO-0.1 | **No event, metric or dimension is added here.** The V1 sets are fixed by PRD §26.2 and §26.3 |
| ANO-0.2 | **No retention period is stated.** Granularity, retention and raw-event policy are **Open (D-27)**, with a legal dimension that is **PENDING COUNSEL (L-21)** |
| ANO-0.3 | **No target, benchmark or threshold is stated.** All operational numbers are **PENDING PILOT** |
| ANO-0.4 | **Analytics must not identify an individual Guest.** This constrains every other rule in this document | AN-3, NFR-PR3 |

---

## 1. What analytics is for

| ID | Purpose | Informs |
| --- | --- | --- |
| ANO-1.1 | **Find the coverage gaps.** Zero-result queries are demand with no supply | `listing-operations-v1.0.md` §1.1 |
| ANO-1.2 | **Find the stale and incomplete data** before a User does | `listing-operations-v1.0.md` §8 |
| ANO-1.3 | **Measure the operation itself** — throughput, rework, report volume | `operations-model-v1.0.md` §9 |
| ANO-1.4 | **Report campaign delivery honestly** | `advertising-operations-v1.0.md` §9 |
| ANO-1.5 | **Supply the pilot its measurements**, which is where the launch threshold comes from | D-30n, D-31 |
| ANO-1.6 | **Show whether the product is actually useful** — contact actions are the core value event | C-11 |

| ID | Non-purpose |
| --- | --- |
| ANO-1.7 | **Analytics is not advertising measurement of individuals.** No behavioural profile is built (D-10, NFR-PR4) |
| ANO-1.8 | **Analytics is not a staff performance system.** No per-operator leaderboard or score (OPX §2.2) |
| ANO-1.9 | **Analytics is not system monitoring.** Health, errors and job failures are `observability-operations-v1.0.md` |
| ANO-1.10 | **Analytics is not a growth-hacking surface.** It informs operational work, not engagement engineering |

---

## 2. Events and metrics

### 2.1 The distinction

| Term | Definition |
| --- | --- |
| **Event** | A single recorded occurrence of a defined interaction, at the time it happens |
| **Metric** | A value computed by aggregating events over a dimension and period |

| ID | Rule | Source |
| --- | --- | --- |
| ANO-2.1 | **Events and metrics stay distinct in specification, storage and presentation** | AN-1 |
| ANO-2.2 | **Staff-facing views read aggregated metrics; they never compute from raw events on demand** | AN-2, OPX-11.1 |
| ANO-2.3 | **An event must not store anything identifying an individual Guest** | AN-3, TR-202 |
| ANO-2.4 | **Analytics is never on the critical path of a User action.** A failure to record must not fail the action | AN-4, C-38 |
| ANO-2.5 | **Granularity, retention, raw-event storage and tooling are Open (D-27)** | AN-5 |

### 2.2 The V1 event set — exhaustive

Search performed · zero-result search · category/area/combination page
viewed · business profile viewed · search impression · contact action ·
save performed · review submitted · report submitted · sponsored
impression · sponsored click · share performed.

| ID | Rule |
| --- | --- |
| ANO-2.6 | **This list is complete.** Adding an event is a decision for the register, not an implementation convenience |
| ANO-2.7 | **An event that would require identifying a Guest to be useful must not be added** (AN-3) |
| ANO-2.8 | **Each event has a stated primary use.** An event with no decision attached to it is data collected without a purpose, which the privacy design forbids (PRIV-2) |

### 2.3 The V1 metric set — exhaustive

Coverage by Category and Area · verification age distribution ·
stale-listing count · listing completeness distribution · listings
created, published and returned per period · report volume and
resolution time · review volume and moderation outcomes · zero-result
rate and top zero-result queries · profile views and contact actions
per Business · campaign impressions and clicks per Campaign.

| ID | Rule |
| --- | --- |
| ANO-2.9 | **Metrics are rollups.** The console reads the rollup (OPX-11.1) |
| ANO-2.10 | **Rollups are produced by bounded, idempotent, manually runnable jobs** (TR-150, TR-151) |
| ANO-2.11 | **No product behaviour may depend on a rollup job having run.** A missing rollup degrades a staff view; it must never change what a User sees (TR-154) |
| ANO-2.12 | **A rollup that cannot complete in its cron window leaves consistent state and resumes** (TR-152), and the overrun is itself a recorded growth trigger (TR-215) |

---

## 3. Privacy constraints

Analytics is the part of the product most likely to drift into
surveillance. These constraints are not advisory.

| ID | Constraint | Source |
| --- | --- | --- |
| ANO-3.1 | **No analytics event identifies an individual Guest** | AN-3, TR-202 |
| ANO-3.2 | **No third-party tracking for advertising purposes** | NFR-PR4, D-10 |
| ANO-3.3 | **No behavioural profile of any individual is constructed**, including for internal use | D-10 |
| ANO-3.4 | **No cross-session identifier is created for Guests** | GS-1 |
| ANO-3.5 | **A search query is content and is handled with care.** Query text is used for zero-result analysis and must not be tied to a person | DI §4 |
| ANO-3.6 | **IP addresses and user agents are not analytics dimensions.** Where they exist for security purposes, that is a different purpose with a different lifetime | DI-4.13, SO §6 |
| ANO-3.7 | **Where a derived value could re-identify a small population, it is not published** — a "contact actions" count on a business with one customer is a thin disguise |
| ANO-3.8 | **Analytics on Customers is still personal data** and is subject to minimisation, retention and data-subject rights | DI §3, DSR §4 |
| ANO-3.9 | **Analytics must not become a purpose in itself.** A new question does not justify a new field; it justifies a decision request | PBD §4 |
| ANO-3.10 | **Retention is Open (D-27) and PENDING COUNSEL (L-21).** Until settled, analytics data is not treated as indefinitely held | RET §4 |

---

## 4. How the metrics are used

| Metric | Operational decision it drives |
| --- | --- |
| **Zero-result rate and top zero-result queries** | What to go and list next (`listing-operations-v1.0.md` §1.1) |
| **Coverage by Category and Area** | Where the directory is thin |
| **Verification age distribution, stale count** | Re-verification queue priority (§8) |
| **Completeness distribution** | Which Listings need a follow-up visit |
| **Listings created, published, returned** | Throughput and rework (pilot measures 3, 6, 12) |
| **Report volume and resolution time** | Whether corrections are keeping up (COR-5) |
| **Review volume and moderation outcomes** | Moderation load; manipulation signals (§5.4 of moderation ops) |
| **Profile views and contact actions per Business** | Whether listings are actually useful (C-11) |
| **Campaign impressions and clicks** | Campaign reporting only (MS-2) |

| ID | Rule |
| --- | --- |
| ANO-4.1 | **Every metric above has a named reader and a cadence** (`operations-model-v1.0.md` §9). A metric nobody reads is not maintained |
| ANO-4.2 | **A metric must not be used for a purpose it was not collected for.** Campaign measurement must not feed ranking (MS-2, RANK-2) |
| ANO-4.3 | **Profile views must not feed organic ranking.** Popularity is not relevance, and making it so would make ranking purchasable by proxy (IN-1) |
| ANO-4.4 | **Completeness weighting in ranking is Open (D-09).** Completeness is computed; using it to rank is not decided |
| ANO-4.5 | **Operational metrics must not be used to rank or rate individual staff** (ANO-1.8) |

---

## 5. Reading and interpretation

| ID | Practice |
| --- | --- |
| ANO-5.1 | **Small numbers are read as small numbers.** At launch volumes, most movements are noise, and treating them as trends produces confident wrong decisions |
| ANO-5.2 | **A metric is read against the operational event that explains it.** A spike in reports after a field day is a collection problem, not a user-behaviour change |
| ANO-5.3 | **A metric that contradicts a belief is investigated, not adjusted** |
| ANO-5.4 | **An absent metric is an absent metric.** The gap is recorded rather than filled with an estimate |
| ANO-5.5 | **No number from this system is published externally without the owner's decision**, and none is published at all before the pilot establishes what is real |
| ANO-5.6 | **Dashboard definitions are written down.** "Active listings" must mean exactly one thing |

---

## 6. Data quality of analytics itself

| ID | Rule | Source |
| --- | --- | --- |
| ANO-6.1 | **A rollup job records start, end, outcome and item counts**, so a silent failure is visible | TR-139 |
| ANO-6.2 | **Job outcomes are observable and failures are visible to Staff** | TR-155, NFR-O1 |
| ANO-6.3 | **A metric computed from an incomplete rollup is labelled as such**, never presented as final |
| ANO-6.4 | **Staff activity must not be counted as User activity.** Whether staff traffic is excluded, and how, is **Open — implementation detail** |
| ANO-6.5 | **Bot and crawler traffic distorts impressions and views.** Whether and how it is excluded is **Open — implementation detail**, and whatever is done is stated in any report | MS-6 |
| ANO-6.6 | **A change to a metric definition breaks its history.** The change is recorded and the series is annotated, not silently restated |

---

## 7. The pilot

| ID | Rule | Source |
| --- | --- | --- |
| ANO-7.1 | **The pilot's twelve measures are the first serious use of this system** | `listing-operations.md` §6.3 |
| ANO-7.2 | **Measurements are recorded as observed, including unflattering ones** | PIL-3 |
| ANO-7.3 | **The launch threshold is derived from pilot data by the owner.** It must not be anticipated, quoted or planned against | D-30n, PIL-4, **PENDING PILOT** |
| ANO-7.4 | **Several pilot measures are operational and not derivable from product events** — Operator hours, refusal reasons, time per stage. How they are captured is **Open — operational decision** |
| ANO-7.5 | **The output is `docs/40-operations/pilot-benchmark-v1.0.md`, produced when the pilot runs, and not pre-filled** | LO-9.9 |

---

## 8. Traceability

| This document | Traces to |
| --- | --- |
| §1 purpose | C-11, C-28, C-38; OPS-8; D-30n, D-31 |
| §2 events and metrics | PRD §26.1–§26.3; AN-1…AN-5; OPX-11.1; TR-150…TR-155, TR-215 |
| §3 privacy | AN-3; NFR-PR3, NFR-PR4; D-10, D-27; GS-1; TR-202; DI §4; RET §4; L-21 |
| §4 use | C-11; COR-5; MS-2; RANK-2; D-09; IN-1 |
| §5 interpretation | PIL-3; D-30n |
| §6 quality | TR-139, TR-155; NFR-O1; MS-6 |
| §7 pilot | `listing-operations.md` §6; PIL-1…PIL-5; D-30, D-30n, D-31 |

---

## 9. Open items

| ID | Item | Marker |
| --- | --- | --- |
| D-27 | Analytics granularity, retention, raw-event policy, tooling | **Open (D-27)** — product decision |
| D-09 | Whether completeness weights ranking | **Open (D-09)** — product decision |
| L-21 / D-46 | Retention of analytics data | **PENDING COUNSEL** |
| D-30n / D-31 | Launch threshold from pilot data | **PENDING PILOT** |
| — | Rollup frequency and cron windows | **Open — implementation detail** |
| — | Exclusion of staff traffic (ANO-6.4) | **Open — implementation detail** |
| — | Exclusion of bot and crawler traffic (ANO-6.5) | **Open — implementation detail** |
| — | How operational pilot measures are captured (ANO-7.4) | **Open — operational decision** |
| — | Named reader and cadence per metric (ANO-4.1) | **Open — operational decision** |
| — | Whether any aggregate is ever published externally (ANO-5.5) | **Open — operational decision** |

---

## Decision references

D-09, D-10, D-27, D-30, D-30n, D-31, D-46.
