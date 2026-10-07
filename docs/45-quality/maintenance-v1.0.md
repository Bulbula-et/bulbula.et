# Maintenance

| | |
| --- | --- |
| **Document** | Maintenance — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

How Bulbula stays correct after launch: dependency and platform
upkeep, defect handling, data-freshness upkeep, documentation upkeep,
decision-debt management, and the triggers that say the current design
has been outgrown.

**Out of scope.** Release mechanics (`release-management-v1.0.md`),
day-to-day operations (`../40-operations/`), security patching as an
incident (`../50-security/vulnerability-management-v1.0.md`).

## Authority and precedence

| ID | Rule |
| --- | --- |
| MNT-0.1 | **This document introduces no product capability and no roadmap item.** Future capability lives in `../10-product/roadmap.md` |
| MNT-0.2 | **No maintenance window, frequency commitment or support period is invented** |
| MNT-0.3 | **No infrastructure change is proposed.** Growth triggers are recorded as **[P]** proposals, not plans | TR-215 |
| MNT-0.4 | **Maintenance follows the release process.** There is no lighter path for a "small" change | REL §1 |

---

## 1. Why maintenance is a first-class subject

| ID | Statement |
| --- | --- |
| MNT-1.1 | **A directory decays by default.** Businesses move, close, change numbers and change hours, and nothing about the software notices |
| MNT-1.2 | **The decay is invisible from inside.** The product looks exactly as healthy on the day it becomes wrong (OM-7.26) |
| MNT-1.3 | **So maintenance is not overhead on the product; for this product it is most of the work** (OM-2.3) |
| MNT-1.4 | **The same is true of the code.** An unmaintained dependency set becomes an unpatchable one |
| MNT-1.5 | **And of the documentation.** A document that no longer describes reality is worse than no document, because it is trusted |

---

## 2. Data maintenance

The largest and most important category. Mechanics are in
`../40-operations/listing-operations-v1.0.md` §8.

| ID | Rule | Source |
| --- | --- | --- |
| MNT-2.1 | **Re-verification is a standing loop, not a project.** The queue is derived at read time and is ordered oldest-first | OPX-5.3, LO-8.1 |
| MNT-2.2 | **The interval is Open (D-08)**, read from configuration | D-08 |
| MNT-2.3 | **Staleness is shown publicly as an honest date, never hidden** | OPX-5.3, OM-7.24 |
| MNT-2.4 | **Every report reaches a recorded outcome, indefinitely** | TS-4 |
| MNT-2.5 | **Zero-result queries drive coverage work continuously**, not only before launch | SRCH-8, LO-1.2 |
| MNT-2.6 | **Closure is recorded as a state, never a deletion** | COR-3 |
| MNT-2.7 | **A Permission withdrawal is honoured at any point in the product's life** | TS-5 |
| MNT-2.8 | **A directory that stops being maintained must be marked stale or taken down** | BC-7.2 |

---

## 3. Dependency and platform maintenance

| ID | Rule | Source |
| --- | --- | --- |
| MNT-3.1 | **The dependency surface is deliberately tiny — four runtime libraries.** Keeping it tiny is itself the maintenance strategy | TRD §6.1 |
| MNT-3.2 | **Adding a dependency is a decision**, weighed against that baseline | REL-4.8 |
| MNT-3.3 | **`composer audit --locked` runs on pull requests, on `main`, and weekly** | `security.yml` |
| MNT-3.4 | **Package versions and compatibility are verified against the actual PHP requirement, never guessed** | Owner instruction |
| MNT-3.5 | **CI tests against highest and lowest dependency sets**, so an update's blast radius is visible | `ci.yml` |
| MNT-3.6 | **A security advisory follows `vulnerability-management-v1.0.md`**, not the ordinary update rhythm | VT §5 |
| MNT-3.7 | **A dependency update is a release** and follows the release process | REL-5.14 |
| MNT-3.8 | **PHP is pinned at `^8.4`.** A PHP version change is a platform migration, planned deliberately, including the host's available version | `composer.json`, `deployment.md` §2 |
| MNT-3.9 | **An unmaintained upstream package is a risk to record**, and replacing it is a decision |
| MNT-3.10 | **Update frequency is Open — operational decision.** No cadence is committed here beyond the weekly audit that already exists |

---

## 4. Defect handling

### 4.1 Classification

| Class | Definition | Response |
| --- | --- | --- |
| **Harmful** | Harms a person, exposes personal data, or publishes something false and consequential | Immediate; may be an incident (IR §4, §7) |
| **Broken** | A capability does not work for Users | Next release; rollback if introduced by the last one (REL §9) |
| **Degraded** | Works, but wrongly or slowly | Scheduled |
| **Cosmetic** | Visible, not consequential | Backlog |
| **Internal** | Staff-only inconvenience | Backlog, weighted by its effect on throughput (OPS-6) |

| ID | Rule |
| --- | --- |
| MNT-4.1 | **Classification is by effect on Users and on data truth**, not by how hard it is to fix |
| MNT-4.2 | **A defect that causes published data to be wrong is a data incident as well as a software one** (REL-9.11) |
| MNT-4.3 | **A defect found by a User rather than by a watched signal produces a new signal, or a recorded decision not to add one** (OBS-6.6) |
| MNT-4.4 | **A fix ships with the test that would have caught it** (TST-7.2) |
| MNT-4.5 | **A defect that cannot be fixed is recorded as an accepted limitation with an owner**, not left silently in a backlog |

### 4.2 Recurrence

| ID | Rule |
| --- | --- |
| MNT-4.6 | **A recurring defect is treated as a design problem on its third occurrence**, not as three incidents |
| MNT-4.7 | **A recurring support class is a product defect in disguise** (SUP-12.2) |
| MNT-4.8 | **Tolerating a recurrence is a decision, and is recorded as one** (OBS-6.8) |

---

## 5. Documentation maintenance

| ID | Rule | Source |
| --- | --- | --- |
| MNT-5.1 | **When a decision closes, the register is updated and every document citing it as open is corrected** | `decision-register.md` |
| MNT-5.2 | **A change that resolves an open item in code without updating the register is a process failure** | TRD §40, QS-2.7 |
| MNT-5.3 | **A document that no longer describes reality is corrected or superseded**, and its control block records that |
| MNT-5.4 | **Version and status in the control block are maintained.** A `Draft` that has been in force for a year is mislabelled |
| MNT-5.5 | **A superseded document says what superseded it** |
| MNT-5.6 | **Counsel's answers are recorded against the specific L-item they resolve**, and every **PENDING COUNSEL** marker citing it is updated |
| MNT-5.7 | **Pilot results are recorded in `pilot-benchmark-v1.0.md`, and every PENDING PILOT marker that it resolves is updated** | LO-9.8 |
| MNT-5.8 | **Documentation changes follow the release process** | REL-2.1, REL-2.2 |

---

## 6. Decision debt

Bulbula carries a large, deliberate stock of open decisions. That is
honest, but it is also debt.

| ID | Rule |
| --- | --- |
| MNT-6.1 | **Every open item has a resolution point recorded**, as the TRD already does for `OT-01…OT-09` |
| MNT-6.2 | **An item whose resolution point has passed is re-dated or re-decided**, not left drifting |
| MNT-6.3 | **An open item is reviewed on the cadence in `operations-model-v1.0.md` §9** |
| MNT-6.4 | **An open item resolved in practice but not in the register is the worst state**: the system behaves one way and the documentation says the question is open. These are actively hunted |
| MNT-6.5 | **Opening a new decision is normal.** Pretending there are fewer than there are is not |
| MNT-6.6 | **The blocking items in `production-readiness-v1.0.md` §3 take priority over all other decision debt** |

---

## 7. Growth triggers

Recorded in TR-215 as **[P]** proposals. They are measured, not
anticipated.

| # | Trigger | Would suggest |
| --- | --- | --- |
| 1 | Search latency at the database | The search design has been outgrown (`search-design.md`, OT-04) |
| 2 | Listing volume | Data-model or indexing attention |
| 3 | Rollup jobs exceeding the cron window | Scheduling or aggregation redesign (TR-151) |
| 4 | Sustained host throttling | The shared host has been outgrown (D-20, D-42) |
| 5 | Notification volume beyond host sending limits | Provider attention (D-41) |

| ID | Rule | Source |
| --- | --- | --- |
| MNT-7.1 | **These are proposals carried from earlier analysis, not commitments** | TR-215 |
| MNT-7.2 | **No capacity number is committed in V1** | NFR-SC3 |
| MNT-7.3 | **A trigger is acted on when it is measured, not when it is feared.** Premature infrastructure is the failure this list exists to prevent | NG-7, QS-2.4 |
| MNT-7.4 | **The threshold value for each trigger is Open — operational decision**, and will be set from real production behaviour |
| MNT-7.5 | **The product must not contain design decisions that prevent adding areas** | NFR-SC1, GEO-4 |
| MNT-7.6 | **Crossing a trigger produces a decision request, not an upgrade** |

---

## 8. What maintenance must not become

| ID | Prohibition | Source |
| --- | --- | --- |
| MNT-8.1 | **Maintenance must not add product capability.** A capability is a PRD matter | AC-9, MNT-0.1 |
| MNT-8.2 | **Maintenance must not introduce a framework, ORM, container or broker** | AC-2; NG-1, NG-4, NG-7, NG-8 |
| MNT-8.3 | **Maintenance must not weaken an architecture test, a threshold or a gate** | QS-2.2, QS-2.5, REL-4.7 |
| MNT-8.4 | **Maintenance must not quietly resolve an open decision** | MNT-6.4 |
| MNT-8.5 | **Maintenance must not expand data collection.** A new field needs a purpose and a privacy review | PRIV-2, PBD §11 |
| MNT-8.6 | **Maintenance must not extend a retention period by inaction** | RET §4 |
| MNT-8.7 | **Maintenance must not patch a server by hand** | DP-7 |
| MNT-8.8 | **Refactoring is maintenance; redesign is a decision** |

---

## 9. The maintenance cadence

Extends `../40-operations/operations-model-v1.0.md` §9; it does not
replace it.

| Cadence | Maintenance activity |
| --- | --- |
| **Daily** | Error log; job failures; email-delivery failures |
| **Weekly** | `composer audit` result; re-verification queue; zero-result queries; open-report ageing |
| **Monthly** | Dependency updates considered; staff accounts and permissions; audit sample |
| **Quarterly** | Restore rehearsal; open-decision review; vendor register; accepted-risk review; growth-trigger measurements |
| **Annually** | Threat model; continuity plan; this document; documentation accuracy sweep |
| **On event** | Security advisory; counsel's answer; pilot result; a crossed growth trigger |

| ID | Rule |
| --- | --- |
| MNT-9.1 | **Each row needs a named owner** — **Open — operational decision** (OM-9.1) |
| MNT-9.2 | **A missed cadence is recorded, not quietly skipped** (OM-9.2) |
| MNT-9.3 | **The cadence is sized for the team that exists, and is revised rather than abandoned** (OM-9.3) |

---

## 10. Traceability

| This document | Traces to |
| --- | --- |
| §1 rationale | OM-2.3, OM-7.26; `listing-operations.md` §1 |
| §2 data | D-08; TS-4, TS-5; COR-3; SRCH-8; OPX-5.3; BC-7.2 |
| §3 dependencies | TRD §6.1; `composer.json`; `ci.yml`, `security.yml`; `deployment.md` §2; VT §5 |
| §4 defects | IR §4, §7; REL §9; OBS-6.6, OBS-6.8; OPS-6; SUP-12.2 |
| §5 documentation | `decision-register.md`; TRD §40; LO-9.8 |
| §6 decision debt | TRD §40.1; PRR §3 |
| §7 growth | TR-215, TR-151; NFR-SC1, NFR-SC3; GEO-4; OT-04; D-20, D-41, D-42 |
| §8 prohibitions | AC-2, AC-9; NG-1, NG-4, NG-7, NG-8; DP-7; PRIV-2; RET §4 |
| §9 cadence | OM §9; BR §4 |

---

## 11. Open items

| ID | Item | Marker |
| --- | --- | --- |
| D-08 | Re-verification interval | **Open — product decision** |
| D-20 / D-42 / D-42b | Host limits, hosting and data location — set growth-trigger context | **Open — product decision** |
| D-26 | Thresholds as the domain grows | **Open — quality decision** |
| OT-04 | Search document refresh strategy — trigger 1's resolution point | **Open — technical decision** |
| L-21 / D-46 | Retention periods, which maintenance must not extend by inaction | **PENDING COUNSEL** |
| — | Dependency update cadence (MNT-3.10) | **Open — operational decision** |
| — | Threshold value for each growth trigger (MNT-7.4) | **Open — operational decision** |
| — | Named owner per cadence row (MNT-9.1) | **Open — operational decision** |
| — | Whether a public support or maintenance window is ever announced | **Open — operational decision** |
| — | How an accepted limitation (MNT-4.5) is recorded and reviewed | **Open — quality decision** |

---

## Decision references

D-08, D-20, D-26, D-41, D-42, D-42b, D-46.
