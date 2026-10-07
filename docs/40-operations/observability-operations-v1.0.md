# Observability Operations

| | |
| --- | --- |
| **Document** | Observability Operations — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

How Bulbula knows the system is working: logging practice, health and
readiness, what is watched, how a failure is noticed, triaged and
closed, and what is deliberately not built.

**Out of scope.** Product and operational measurement
(`analytics-operations-v1.0.md` — **a different thing**), security
monitoring (`../50-security/security-operations-v1.0.md`), incident
response (`../50-security/incident-response-v1.0.md`), backups
(`backup-recovery-v1.0.md`).

## Authority and precedence

| ID | Rule |
| --- | --- |
| OBS-0.1 | **No tooling is selected here.** Observability tooling beyond logs is **Open (OT-07)**, and V1 requires none | TR-141, NFR-O3 |
| OBS-0.2 | **No alerting infrastructure, pager rotation or on-call schedule is invented.** None is committed by any approved decision |
| OBS-0.3 | **No availability percentage or latency target is stated.** No availability percentage is committed in V1 | NFR-A2 |
| OBS-0.4 | **The constraint is shared hosting**: no root, no daemon supervision, cron only | TR-189 |

---

## 1. The operating principle

| ID | Principle | Source |
| --- | --- | --- |
| OBS-1.1 | **Failures must be visible to Staff through monitoring, not discovered by Users** | NFR-A3, TR-213 |
| OBS-1.2 | **Errors, failed jobs and failed notifications must be observable by Staff** | NFR-O1 |
| OBS-1.3 | **Staff must be able to answer "is this Listing published, verified, and when was it last changed, by whom" from the console** | NFR-O2 |
| OBS-1.4 | **Public discovery must survive the failure of non-essential subsystems** — analytics, maps, email | NFR-A1, TR-02 |
| OBS-1.5 | **V1 observability is logs, health endpoints, the console and a person looking.** That is a deliberate choice, not an omission | TR-141, OT-07 |
| OBS-1.6 | **An unread signal is not observability.** Every source in §4 has a named reader and a cadence | OM §9 |

---

## 2. Logging

### 2.1 Requirements already fixed

| ID | Requirement | Source |
| --- | --- | --- |
| OBS-2.1 | **Every log line carries the request correlation id, the environment and a severity** | TR-134 |
| OBS-2.2 | **Logs must not contain credentials, session identifiers, bearer tokens, OTP codes or full personal records** | TR-135, LG-3 |
| OBS-2.3 | **Operational events that need review are logged at a level that surfaces them** — failed logins, authorization denials, moderation actions, campaign changes, email failures, job failures | TR-136 |
| OBS-2.4 | **Background jobs record start, end, outcome and item counts** | TR-139 |
| OBS-2.5 | **Log rotation and retention are configured so logs cannot exhaust shared-host disk** | TR-140, LG-5 |
| OBS-2.6 | **Logs are written outside the document root and are never web-accessible** | LG-1, PS-2 |
| OBS-2.7 | **Logs are not a backup and must not be relied on to reconstruct state** | TR-224, BK-8 |
| OBS-2.8 | **No secret appears in logs** | TR-198, AC-14 |

### 2.2 Operational practice

| ID | Practice |
| --- | --- |
| OBS-2.9 | **The correlation id is the unit of investigation.** An error report with no correlation id is a much weaker report, which is why the user-facing error page exposes a reference (TR-142) |
| OBS-2.10 | **Log severity is used honestly.** If everything is an error, nothing is |
| OBS-2.11 | **A log line is written for a reader.** It states what happened and what was being attempted, not only that an exception occurred |
| OBS-2.12 | **Logs are read on the daily cadence**, not only after a complaint (OM §9) |
| OBS-2.13 | **Log retention interacts with the retention schedule where lines contain personal data — PENDING COUNSEL (L-21)** (TR-140) |
| OBS-2.14 | **A log line containing personal data is a privacy event.** It is removed and the cause is fixed, not tolerated (TR-135) |
| OBS-2.15 | **Whether logs are shipped off-host is Open — technical decision**, constrained by D-42 and D-42b |

---

## 3. Health and readiness

| ID | Rule | Source |
| --- | --- | --- |
| OBS-3.1 | **Liveness and readiness are distinct.** `/health` has no dependencies; `/health/ready` checks the database | TR-138 |
| OBS-3.2 | **They reflect real dependency state**, not a hard-coded "ok" | AC-13 |
| OBS-3.3 | **Database unavailability is a hard failure surfaced by readiness**, with a safe error page for Users | TR-211 |
| OBS-3.4 | **The deploy smoke test exercises home page, health, readiness, a 404 and a static asset** | TR-190, DS §4 |
| OBS-3.5 | **Health endpoints must not leak internal detail** — no versions, paths, hostnames or dependency strings | TR-144, HK-2 |
| OBS-3.6 | **How often the endpoints are polled, and by what, is Open — operational decision.** Shared hosting offers no supervisor; an external check is possible but not committed |

---

## 4. What is watched

| # | Signal | Source | Cadence | Means |
| --- | --- | --- | --- | --- |
| 1 | Readiness failing | `/health/ready` | Continuous or on report | Site is down or degraded |
| 2 | Unhandled exceptions | Application log | Daily | A defect or an attack |
| 3 | Background job failures | Job log (TR-139) | Daily | Rollups or pruning not running |
| 4 | Job overrunning its cron window | Job log | Weekly | A growth trigger (TR-215) |
| 5 | Email delivery failures | Delivery record (EM-4) | Daily | Sign-in and notifications broken |
| 6 | Authorization denials | Log (TR-136) | Weekly | A permission bug, or probing |
| 7 | Failed logins | Log (TR-136) | Weekly | Credential attack (SO §5) |
| 8 | Disk usage | Host | Weekly | Shared-host hard limit |
| 9 | Database errors | Log | Daily | Corruption, limits, connection exhaustion |
| 10 | Outbound dependency failures | Log (TR-01c) | Daily | Google, email or Telegram degraded |
| 11 | 404 and 500 rates | Log | Weekly | Broken links, or a bad deploy |
| 12 | Search latency at the database | Log / observation | Weekly | A growth trigger (TR-215) |

| ID | Rule |
| --- | --- |
| OBS-4.1 | **Each row has a named owner, assigned before launch** — **Open — operational decision** |
| OBS-4.2 | **A signal with no owner is removed from this table or given one.** It must not sit here unread |
| OBS-4.3 | **Rows 6 and 7 are shared with security operations.** The same log line serves both readings; they are not duplicated (SO §5) |
| OBS-4.4 | **A new signal is added when something goes wrong that nothing on this list would have caught** |

---

## 5. Degradation

| Dependency | Fails how | Required behaviour | Source |
| --- | --- | --- | --- |
| **Database** | Unreachable | Hard failure; readiness red; safe error page | TR-211 |
| **Analytics** | Write fails | **Silent.** The User action completes | AN-4, C-38 |
| **Maps** | Unavailable | Profile renders; map degrades to a fallback | NFR-P4, D-21 |
| **Email provider** | Send fails | Failure recorded and surfaced; **the triggering action is not blocked** | EM-4 |
| **Google sign-in** | Unavailable | Email OTP remains available | D-48 |
| **Telegram** | Unavailable | The Web surface is unaffected | TR-02 |
| **Rollup jobs** | Not run | Staff views degrade; **public behaviour unchanged** | TR-154 |

| ID | Rule |
| --- | --- |
| OBS-5.1 | **Guest discovery must work with every external dependency unavailable.** This is an acceptance criterion, not an aspiration (TR-02, AC-9) |
| OBS-5.2 | **Each outbound call has a timeout, a bounded retry policy and a defined degraded behaviour** (TR-01c) |
| OBS-5.3 | **No outbound HTTP call occurs in a page-render path** (TR-01d, TR-205) |
| OBS-5.4 | **Degradation is visible to Staff even when invisible to Users.** A silently failing analytics write is still a defect (OBS-1.2) |

---

## 6. Noticing, triage and closure

### 6.1 How a problem is noticed

| Route | Note |
| --- | --- |
| A watched signal (§4) | The intended route |
| A support contact | A User noticed first — itself a finding (OBS-1.1) |
| A staff member working the console | Common and legitimate |
| A deploy smoke test failure | Caught before Users (TR-190) |
| An external security report | `vulnerability-management-v1.0.md` |

### 6.2 Triage

| Question | Determines |
| --- | --- |
| Is anything actively broken for Users? | Whether to act now or record it |
| Is personal data involved? | Whether this is a privacy incident (IR §7) |
| Is it security-relevant? | Whether this is a security incident (IR §4) |
| Is it caused by the last deploy? | Whether to roll back (`release-management-v1.0.md` §8) |
| Is it one of the known growth triggers? | Whether this is capacity, not a defect (TR-215) |

| ID | Rule |
| --- | --- |
| OBS-6.1 | **Restoring service comes before diagnosing it, except where restoring would destroy the evidence needed to understand it** (IR §6) |
| OBS-6.2 | **A suspected personal-data breach is escalated immediately**; the assessment clock starts at awareness (IR §7) |
| OBS-6.3 | **A rollback is a legitimate first response, not a defeat** (RB §1) |
| OBS-6.4 | **A problem is closed when the cause is understood or the gap is recorded.** "It stopped happening" is a recorded open item, not a closure |

### 6.3 Closure

| ID | Rule |
| --- | --- |
| OBS-6.5 | **Every notable failure produces a written record**: what happened, how it was noticed, what was done, what the cause was, what prevents recurrence |
| OBS-6.6 | **A failure noticed by a User rather than by §4 produces a new signal or a recorded decision not to add one** |
| OBS-6.7 | **The record is blameless and factual.** Its purpose is the next failure, not this one |
| OBS-6.8 | **Recurrence without a change is a decision to tolerate**, and is recorded as one |

---

## 7. Deliberate non-goals

| ID | Not in V1 | Why |
| --- | --- | --- |
| OBS-7.1 | External APM | **Open (OT-07)**; V1 requires none (TR-141) |
| OBS-7.2 | Log aggregation service | Not required; interacts with D-42 residency |
| OBS-7.3 | Alerting and paging infrastructure | Not committed by any decision; the team is small and the cadence is manual |
| OBS-7.4 | Uptime SLA | No availability percentage is committed in V1 (NFR-A2) |
| OBS-7.5 | Distributed tracing | One application, one host |
| OBS-7.6 | Real-user monitoring | Would conflict with AN-3 unless carefully designed; not decided |
| OBS-7.7 | Synthetic monitoring | Possible and cheap, but not committed — **Open — operational decision** (OBS-3.6) |

| ID | Rule |
| --- | --- |
| OBS-7.8 | **These are recorded as choices, not oversights.** Each becomes a decision request if the operation outgrows the manual cadence |

---

## 8. Traceability

| This document | Traces to |
| --- | --- |
| §1 principle | NFR-A1, NFR-A3, NFR-O1, NFR-O2, NFR-O3; TR-02, TR-141, TR-213 |
| §2 logging | TR-134…TR-136, TR-139, TR-140, TR-198, TR-224; LG-1, LG-3, LG-5; PS-2; L-21 |
| §3 health | TR-138, TR-144, TR-190, TR-211; AC-13; HK-2 |
| §4 watched signals | TR-136, TR-139, TR-215; EM-4; SO §5 |
| §5 degradation | TR-01c, TR-01d, TR-02, TR-154, TR-205, TR-211; AN-4; EM-4; NFR-P4; D-21, D-48 |
| §6 triage | IR §4, §6, §7; RB §1; TR-190, TR-215 |
| §7 non-goals | OT-07; TR-141; NFR-A2; AN-3 |

---

## 9. Open items

| ID | Item | Marker |
| --- | --- | --- |
| OT-07 | Whether any observability tooling beyond logs is introduced | **Open — technical decision** |
| D-42 / D-42b | Hosting and data location — constrains off-host logging | **Open — product decision** |
| D-20 | Host limits and MariaDB tuning — determines what "normal" looks like | **Open — product decision** |
| L-21 / D-46 | Log retention where lines contain personal data | **PENDING COUNSEL** |
| — | How health endpoints are polled, and by what (OBS-3.6) | **Open — operational decision** |
| — | Whether logs are shipped off-host (OBS-2.15) | **Open — technical decision** |
| — | Named owner per watched signal (OBS-4.1) | **Open — operational decision** |
| — | Whether synthetic monitoring is adopted (OBS-7.7) | **Open — operational decision** |
| — | Thresholds at which a growth trigger becomes an action (TR-215) | **Open — operational decision**; **PENDING PILOT** for volume assumptions |

---

## Decision references

D-20, D-21, D-42, D-42b, D-46, D-48.
