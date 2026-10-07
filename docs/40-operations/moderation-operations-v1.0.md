# Moderation Operations

| | |
| --- | --- |
| **Document** | Moderation Operations — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

The operational handling of Customer-generated content: Review
moderation, Review reports, Listing reports, abuse reports, escalation,
evidence, decisions, reconsideration, and the audit trail.

**Out of scope.** The policy itself
([`../10-product/review-policy.md`](../10-product/review-policy.md) —
**this document does not change it**), listing corrections
(`listing-operations-v1.0.md` §7), privacy requests
(`customer-support-v1.0.md` §7), security incidents
(`../50-security/incident-response-v1.0.md`).

## Authority and precedence

Below `review-policy.md`, the PRD and the register.

| ID | Rule |
| --- | --- |
| MO-0.1 | **`review-policy.md` is the policy. This document is the practice.** Where they differ, the policy wins |
| MO-0.2 | **No moderation ground is invented here.** The published grounds in `review-policy.md` §5.1 are exhaustive |
| MO-0.3 | **D-34 settled the review mechanics on 2026-10-07** and this document now follows them. Where something genuinely remains open — the maximum text length, rate-limit values, anomaly thresholds and the appeal time limit — **it stays open**, and operations must not settle it by habit |
| MO-0.4 | **No response-time target is stated.** Targets are **PENDING PILOT** |

---

## 1. What is being moderated

| Object | Who creates it | Public? | Governing policy |
| --- | --- | --- | --- |
| **Review** (Rating + optional text) | Authenticated Customer (D-12) | Yes | `review-policy.md` |
| **Review report** | Authenticated Customer or Staff | No | `review-policy.md` §6 |
| **Listing report** | **Anyone, including Guests** (TS-2) | No | `listing-operations-v1.0.md` §7 |
| **Abuse report** on any public surface | Anyone | No | TS-8 |

| ID | Fixed ground | Source |
| --- | --- | --- |
| MO-1.1 | **Guests may read Reviews. Only authenticated Customers write them** | D-12, D-05 |
| MO-1.2 | **There are no business-owner replies in V1** and no surface to moderate them | D-12, D-54, OPX-8.8 |
| MO-1.3 | **There are no business accounts.** A business reports content through the public contact route and receives no priority | REP-7, D-54 |
| MO-1.4 | **Staff must not write Reviews from staff accounts**, and the console offers no affordance to do so | PART-1, OPX-8.6 |
| MO-1.5 | **Bulbula must not create, commission, incentivise or fabricate Reviews**, in either direction | PART-2, TS-13, AB-11 |
| MO-1.6 | **Review photos and helpful voting do not exist in V1** | D-36, D-37 |
| MO-1.7 | **Moderation is independent of advertising.** A commercial relationship is never a ground, a priority or a factor | MOD-3, IN-3, TS-14 |

---

## 2. The queue model

**No vendor, tool or product is selected or implied.** This describes
the shape of the work, which the console already supports (C-25, C-26).

### 2.1 Queues

| Queue | Contains | Default order |
| --- | --- | --- |
| **Reviews awaiting decision** | Pending Reviews, and published Reviews that have been reported | **Oldest first** |
| **Reports awaiting triage** | Listing reports, abuse reports, and reports grouped by target | **Oldest first** |
| **Escalated** | Items routed to an Administrator: legal, appeals, policy questions | Oldest first |
| **Reconsideration** | Items where an author or business has contested an outcome | Oldest first |

| ID | Rule | Source |
| --- | --- | --- |
| MO-2.1 | **Queues are worked, not browsed.** Default order is oldest first | OPX-2.4 |
| MO-2.2 | **An empty queue is a complete, non-error state** | OPX-2.7 |
| MO-2.3 | **Several reports describing one issue are grouped**, so one decision closes them all | OPX-9.1 |
| MO-2.4 | **Report volume is context only, never an automatic trigger.** A report alone must not unpublish anything | OPX-8.3, REP-3 |
| MO-2.5 | **Filter and sort state is in the URL**, so one moderator can hand another an exact view | OPX-0.10 |
| MO-2.6 | **Timestamps are absolute with a timezone.** Moderation work needs exact times | OPX-2.8 |
| MO-2.7 | **There is no per-moderator leaderboard or performance score** | OPX §2.2 |
| MO-2.8 | **No queue is prioritised by the subject's commercial relationship** | IN-4 |

### 2.2 Prioritisation within a queue

Ordering is by age, with two exceptions that reflect harm, not
commerce:

| Priority | Class | Why |
| --- | --- | --- |
| **First** | Content that may be illegal, threatening, or discloses a person's personal information | Active harm to a person (TS-8, TS-11) |
| **Second** | Factual errors that materially mislead Users — wrong number, wrong location, closed business | Active harm to a User (TS-6) |
| **Then** | Everything else, oldest first | MO-2.1 |

| ID | Rule |
| --- | --- |
| MO-2.9 | **These two exceptions are the only permitted deviations from age ordering.** Target times for each remain **PENDING PILOT** (TS-6) |
| MO-2.10 | **A moderator who deprioritises an item records why** |

---

## 3. Review moderation

### 3.1 The decision

| ID | Rule | Source |
| --- | --- | --- |
| MO-3.1 | **The only outcomes are publish, reject and remove.** Moderators must not edit the content of a Review | MOD-4 |
| MO-3.2 | **Every outcome cites one of the published grounds.** A decision cannot be submitted without one | MOD-1, OPX-8.1 |
| MO-3.3 | **A negative Review is not a ground for removal.** Dissatisfaction, criticism and low ratings are legitimate content | MOD-2 |
| MO-3.4 | **A business's objection, commercial relationship or advertising spend is not a ground** | MOD-3, TS-14 |
| MO-3.5 | **No automated removal.** Adverse outcomes are taken by a person | AB-9 |
| MO-3.6 | **Decisions record policy basis, actor and time** | MOD-5, TS-9 |
| MO-3.7 | **The author is notified of rejection or removal, with the reason** | LC-2, OPX-8.5 |
| MO-3.8 | **Rejected and removed Reviews do not contribute to the rating summary** | LC-3, SUM-4 |
| MO-3.9 | **Moderation precedes publication** (D-34, LC-4). The flow is `submit → Pending → moderation → Published or Rejected`. A Review in `Pending` is **not publicly visible**, and a **Rejected** Review is never published | LC-4, OPX-8.7 |
| MO-3.10a | **Removal after publication remains available.** A Published Review may later be removed on a published ground; pre-publication moderation does not make publication final | MOD-2, LC-3 |
| MO-3.10b | **A rating-only Review is a valid Review.** The absence of text is not a ground for rejection (D-34) | MOD-2 |
| MO-3.10c | **A Review concerns a Branch.** The queue and every moderation record identify the Branch, not only the Business (D-34, D-55) | SUBJ-1 |

### 3.2 The published grounds

Exhaustive; from `review-policy.md` §5.1.

| Ground | What a moderator is actually deciding |
| --- | --- |
| **Not about the Business** | Is the content about a different business, or no business at all? |
| **No experience** | Is it clearly not based on an interaction with this Business? **"Clearly" is the bar — suspicion is not** |
| **Abuse or harassment** | Threats, slurs, targeted harassment of staff or Users |
| **Personal information** | Does it disclose a person's personal information, including a named individual's contact details? |
| **Illegal content** | Is it unlawful to publish? → **escalate** (§6) |
| **Spam or promotion** | Advertising, links, repeated promotional content |
| **Conflict of interest** | Authored by the Business, a competitor, or a related party |
| **Fabrication indicators** | Patterns consistent with coordinated or purchased reviews → often **anomaly review** (§5) |
| **Unreadable** | No interpretable meaning |

| ID | Rule |
| --- | --- |
| MO-3.10 | **The ground is chosen before the outcome, not justified afterwards** |
| MO-3.11 | **Where no ground fits, the Review stays.** The absence of a fitting ground is the answer |
| MO-3.12 | **A public summary of these grounds is published as a static page** (MOD-7, C-18) |

### 3.3 What the moderator sees

Full text · subject Business · author display name · reports with their
grounds · prior decisions on this author's Reviews `[P]`.

| ID | Rule | Source |
| --- | --- | --- |
| MO-3.13 | **Reporter identity is never shown** to the author or the Business | OPX-8.2, REP-4, TS-10 |
| MO-3.14 | **The author's email is not part of the moderation view.** The display name is the attribution | DI-3.2 |
| MO-3.15 | **Staff access to personal information in this view is logged** | TS-20, PG-10.1 |

### 3.4 Edits and author deletion

| ID | Rule | Status |
| --- | --- | --- |
| MO-3.16 | **An edit returns the Review to moderation** (D-34, LC-5). The edited Review re-enters `Pending` and is assessed afresh. The author may edit for **30 days from creation** | LC-5, LC-8 |
| MO-3.17 | **Author deletion is a withdrawal, not a hard delete** (D-34, LC-6). Public visibility ceases and the Review leaves every rating summary; the internal record may be retained for retention, audit, abuse and legal purposes. **How long it is retained, and the treatment of Reviews after account deletion, remain PENDING COUNSEL (L-21, D-46)** | LC-6 |
| MO-3.18 | **Operations must not invent a retention period** for a withdrawn Review. Until counsel answers, the record is kept and no deletion schedule is applied by habit | MO-0.3, L-21 |

---

## 4. Reports

### 4.1 Review reports

| ID | Rule | Source |
| --- | --- | --- |
| MO-4.1 | **Any authenticated Customer may report a published Review** | REP-1 |
| MO-4.2 | **A report requires a reason from the published grounds**, with optional detail | REP-2 |
| MO-4.3 | **Reported Reviews enter the queue; a report alone never unpublishes** | REP-3 |
| MO-4.4 | **Reporter identity is never disclosed** to the author or the Business | REP-4 |
| MO-4.5 | **Repeated unfounded reporting is itself abuse and is actionable** | REP-5 |
| MO-4.6 | **Every report reaches a recorded outcome** | REP-6, TS-4 |

### 4.2 Listing and abuse reports

| ID | Rule | Source |
| --- | --- | --- |
| MO-4.7 | **Listing reports are available to Guests, without an account** | TS-2 |
| MO-4.8 | **Guest reports carry no identity**, and the queue must not imply one exists | OPX-9.6, TR-202 |
| MO-4.9 | **A reporting route exists for abusive, defamatory or illegal content on any public surface** | TS-8 |
| MO-4.10 | **Resolution routes to the right tool**: a Correction, a moderation decision, or closure with an outcome | OPX-9.3 |
| MO-4.11 | **A report resolved by a Correction links to that Correction** | OPX-9.5, COR-4 |
| MO-4.12 | **The target's report history is visible**, so a pattern is apparent | OPX-9.2 |

### 4.3 Outcomes

Every report closes in exactly one of three states.

| Outcome | Meaning |
| --- | --- |
| **Resolved** | The problem was real and has been acted on |
| **Closed — unverified** | The problem could not be confirmed. Recorded, not dismissed silently |
| **Rejected** | The report does not describe a problem under any published ground |

| ID | Rule |
| --- | --- |
| MO-4.13 | **"Closed — unverified" is an honest outcome, not a failure.** It must not be used to avoid work, and a pattern of it on one target is itself a signal (OPX-9.4) |
| MO-4.14 | **No report is closed without an outcome being recorded** (OPX-9.4) |

---

## 5. Anti-abuse and integrity

### 5.1 The threats

Fake positive Reviews bought by a Business · fake negative Reviews
placed by a competitor · review bombing · staff or commercial
interference.

### 5.2 Operational controls

| ID | Control | Status |
| --- | --- | --- |
| MO-5.1 | **Authentication is required to write** | AB-1 — settled |
| MO-5.2 | **Rate limits on submissions per Customer per period.** The values are **configuration**, not schema, and D-34 deliberately sets none; they are tuned operationally and recorded when set | AB-2 |
| MO-5.3 | **At most one active Review per Customer per Branch**, edited rather than re-posted (D-34) | AB-3 |
| MO-5.4 | **Duplicate and near-duplicate text detection** across Reviews | AB-4 |
| MO-5.5 | **Anomaly surfacing**: bursts on one Branch or Business, or from one account. Thresholds are **configuration** and D-34 deliberately sets none | AB-5 |
| MO-5.6 | **Staff review anomalous patterns before summaries shift materially** | AB-6, TS-12 |
| MO-5.7 | **Separation of commercial relationships from moderation is Open (D-39)** and **must be resolved before the first paid Campaign** | AB-7, ADV-12 |
| MO-5.8 | **No automated removal** | AB-9 |

### 5.3 Prohibitions

| ID | Prohibition | Source |
| --- | --- | --- |
| MO-5.9 | **Must not suppress or down-rank Reviews to protect a commercial relationship.** Suppression carries the same regulatory exposure as fabrication | AB-10, TS-14 |
| MO-5.10 | **Must not publish Reviews known to be fabricated**, in either direction | AB-11 |
| MO-5.11 | **Must not offer removal, reordering or suppression as a commercial product** | PART-3 |
| MO-5.12 | **Must not solicit Reviews selectively from satisfied customers** | PART-4 |
| MO-5.13 | **Must not fabricate Listings, Reviews, ratings or activity of any kind** | TS-13 |

### 5.4 Anomaly handling

| ID | Practice |
| --- | --- |
| MO-5.14 | **Coordinated manipulation is detected by people, not by a rule.** An alert starts an investigation; it does not produce an outcome (THR-50, SO-13.2) |
| MO-5.15 | **An investigation examines the pattern, not only the individual Review.** Timing, account age, text similarity and target concentration are all context |
| MO-5.16 | **Each Review is still decided on a published ground.** "Part of a suspicious cluster" is not a ground; **fabrication indicators** is |
| MO-5.17 | **The investigation and its reasoning are recorded**, because this is the decision most likely to be challenged |

---

## 6. Escalation

| Trigger | Escalates to | Note |
| --- | --- | --- |
| Legal removal demand, defamation complaint | **Administrator**, then counsel | Process is **PENDING COUNSEL (L-15)**; this document does not establish it |
| Content that may be illegal | **Administrator**, then counsel | Preserve evidence first (§7) |
| Disclosure of a person's personal information | **Administrator** | Also a privacy matter (DSR §5) |
| Threats or content suggesting risk to a person | **Administrator**, immediately | Harm outranks queue order (MO-2.9) |
| A policy question the grounds do not cover | **Administrator** (`review.policy`) | Produces a policy decision, not an ad-hoc outcome |
| An appeal or reconsideration | **Administrator** | §8 |
| Commercial pressure on an outcome | **Administrator**, then owner | IN-5, OM-5.5 |
| Suspected coordinated manipulation at scale | **Administrator** | §5.4 |
| A moderator's own conflict of interest | **Administrator**; the moderator recuses | §9 |

| ID | Rule |
| --- | --- |
| MO-6.1 | **Legal escalation is an Administrator action** (OPX-9.7) |
| MO-6.2 | **No moderator decides a legal question** (OM-5.3) |
| MO-6.3 | **Escalation never pauses the removal of actively harmful content.** Unpublish first, preserving evidence; escalate in parallel (IR-1.1) |
| MO-6.4 | **Every escalation is recorded** with what was escalated, to whom, and when |

---

## 7. Evidence

| ID | Rule | Source |
| --- | --- | --- |
| MO-7.1 | **The content as it stood is preserved before removal.** Removal is not evidence destruction if a copy is kept | IR §6 |
| MO-7.2 | **The decision record carries the ground, the actor, the time and the reasoning** | MOD-5 |
| MO-7.3 | **Reports and their grounds are preserved with the decision**, so the context survives | REP-6 |
| MO-7.4 | **Reporter identity is preserved internally and never disclosed** — including in a data-subject access request | DSR-4.8, TS-10 |
| MO-7.5 | **Evidence containing personal data is personal data**, held under the retention schedule — **PENDING COUNSEL (L-21)** | RET §4 |
| MO-7.6 | **Removed Review text is destroyed at the end of its retention**, while the fact and the ground of the decision persist | RET-4.10 |
| MO-7.7 | **Evidence is access-controlled**; reading it is logged | PG-10.3 |

---

## 8. Reconsideration and appeals

Only to the extent policy already supports it. **No new appeal right is
created here.**

| ID | Rule | Source |
| --- | --- | --- |
| MO-8.1 | **Appeals are handled by an Administrator** | MOD-6 |
| MO-8.2 | **D-34 confirms the Administrator as the appeal authority but sets no deadline.** The appeal mechanism and any time limit remain an **open operational decision**, and this document does not supply them | MOD-6 |
| MO-8.3 | **The author is told the ground for the adverse outcome**, which is what makes contesting it possible at all | LC-2 |
| MO-8.4 | **An appeal is decided by someone other than the original decision-maker where one exists.** With one staff member, that is a recorded accepted risk, as elsewhere | OM-3.8 |
| MO-8.5 | **An appeal outcome is recorded with its own reasoning**, not as an amendment to the original |
| MO-8.6 | **Reversal is a legitimate outcome and is not treated as a staff failure** |
| MO-8.7 | **A business contesting a Review is not an appeal** — the business is not the author. It is a report, handled under §4, and the Review is judged on the published grounds | MOD-2, MOD-3 |

---

## 9. Conflicts of interest

| ID | Rule | Source |
| --- | --- | --- |
| MO-9.1 | **A moderator with a relationship to the Business or the author recuses**, and the item is reassigned |
| MO-9.2 | **Staff must not write Reviews from staff accounts**; a staff member acting privately uses their own Customer account and must not review Businesses Bulbula lists | PART-1, `interaction-permissions.md` n.1 |
| MO-9.3 | **Advertising performance must not be a staff incentive that conflicts with moderation duties** | IN-5 |
| MO-9.4 | **The console gives no navigation path from a Campaign to the Business's content** | OPX-10.9 |
| MO-9.5 | **Where one person both sells and moderates, that is a structural conflict** and is exactly what D-39 must address before the first paid Campaign | IN-6, ADV-12 |

---

## 10. Audit

| ID | Requirement | Source |
| --- | --- | --- |
| MO-10.1 | **Every state change records actor, timestamp and reason** | LC-1, C-29 |
| MO-10.2 | **Decisions are append-only and visible to the author with the ground** | OPX-8.4 |
| MO-10.3 | **If the audit write fails, the action fails** | OPX-0.3, TR-08 |
| MO-10.4 | **Audit entries survive Customer deletion** | OPX-12.4, TR-167 |
| MO-10.5 | **Reading the audit log is an Administrator action and is itself subject to permission** | OPX-12.6 |
| MO-10.6 | **The audit trail is the defence against both accusations** — fabricating reviews and suppressing them | D-12 rationale |

---

## 11. Traceability

| This document | Traces to |
| --- | --- |
| §1 objects and fixed ground | D-05, D-12, D-36, D-37, D-54; PART-1…PART-4; TS-8, TS-13 |
| §2 queue model | C-25, C-26; OPX-2.4…2.8, OPX-8.3, OPX-9.1; TS-6 |
| §3 Review moderation | `review-policy.md` §5; MOD-1…MOD-7; LC-1…LC-6; OPX-8.1…8.8; TS-9, TS-10 |
| §4 reports | REP-1…REP-7; OPX-9.1…9.6; TS-2, TS-4 |
| §5 anti-abuse | AB-1…AB-11; TS-12, TS-13, TS-14; D-34, D-39; THR-50 |
| §6 escalation | OPX-9.7; TS-8, TS-11; L-15 |
| §7 evidence | MOD-5; RET-4.10; DSR-4.8; L-21 |
| §8 appeals | MOD-6; LC-2; D-34 |
| §9 conflicts | IN-3, IN-5, IN-6; PART-1; OPX-10.9; ADV-12 |
| §10 audit | C-29; LC-1; OPX-0.3, OPX-8.4, OPX-12.4, OPX-12.6; TR-08, TR-167 |

---

## 12. Open items

| ID | Item | Marker |
| --- | --- | --- |
| D-34 (residual) | Maximum review-text length, rate-limit values, anomaly thresholds | **Open — configuration.** D-34 closed the mechanics on 2026-10-07 and deliberately set no values |
| MOD-6 | Appeal mechanism and any time limit | **Open — operational decision.** The Administrator is the authority; no deadline is invented |
| D-39 | Separation of commercial relationships from moderation — **blocks the first paid Campaign** | **Open (D-39)** — product decision |
| D-14 | Which permission covers which moderation action | **Open (D-14)** — product decision |
| L-15 | Review liability, defamation and takedown process | **PENDING COUNSEL** |
| L-21 / D-46 | Retention of moderation evidence and removed content | **PENDING COUNSEL** |
| TS-6 | Priority response times | **PENDING PILOT** |
| — | How recusal is recorded and reassigned (MO-9.1) | **Open — operational decision** |
| — | Whether "prior decisions on this author's Reviews" is shown — currently `[P]` | **Open — product decision** |
| — | What a moderator does when a Review contains sensitive personal data about a third party | **Open — operational decision**; interacts with DI §6 |

---

## Decision references

D-05, D-12, D-14, D-34, D-36, D-37, D-39, D-46, D-54, D-55.
