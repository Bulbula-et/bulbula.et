# Operations Model

| | |
| --- | --- |
| **Document** | Operations Model — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

How Bulbula operates as a **company-operated directory**: the operating
lifecycle, who does what, how work is handed off, who owns an outcome,
how something escalates, what evidence is kept, and the controls that
keep published data honest.

This document is the root of `docs/40-operations/`. Every other
operations document is a specialisation of one stage of the lifecycle in
§2.

**Out of scope.** The listing workflow in detail
(`listing-operations-v1.0.md`), moderation
(`moderation-operations-v1.0.md`), advertising
(`advertising-operations-v1.0.md`), support
(`customer-support-v1.0.md`), analytics
(`analytics-operations-v1.0.md`), observability
(`observability-operations-v1.0.md`), recovery
(`backup-recovery-v1.0.md`), continuity
(`business-continuity-v1.0.md`), and everything in `docs/45-quality/`.

## Authority and precedence

```text
Owner decisions → decision-register.md → PRD → TRD → UX/UI
→ Platform specifications → Security / Privacy
→ Operations / Quality / Release  ← this document
→ Implementation
```

| ID | Rule |
| --- | --- |
| OM-0.1 | **This document introduces no product capability.** Where an operational need would require one, it is recorded as an open item, never assumed |
| OM-0.2 | **Where this document and any document above it disagree, the higher document wins and this one is wrong** |
| OM-0.3 | **No unresolved permission is silently assigned to an actor.** Where D-14 is open, the required *permission* is named and the role is not (OPX-0.1) |
| OM-0.4 | **No number is invented here** — no price, no SLA, no staffing level, no launch threshold, no retention period, no target time |
| OM-0.5 | Unresolved items are marked `Open — operational decision`, `Open — quality decision`, `Open — implementation detail`, `Open — product decision`, `Open — technical decision`, `PENDING COUNSEL` or `PENDING PILOT` |

---

## 1. What kind of company this is

| ID | Statement | Source |
| --- | --- | --- |
| OM-1.1 | **Bulbula builds its product by hand.** Under D-02 and D-54 no business can create, claim, edit or publish its own Listing. Every published fact arrives through a staff process | D-02, D-54 |
| OM-1.2 | **The operational process is therefore the supply chain of the product**, not an internal administrative matter | `listing-operations.md` |
| OM-1.3 | **The operations console is a primary V1 product surface**, specified, designed, built and tested to the same standard as public surfaces | OPS-1 |
| OM-1.4 | **Staff throughput is the binding constraint on coverage**, which is why the launch bar cannot be set before the pilot measures it | D-30, OPS-8 |
| OM-1.5 | **Businesses are not platform users.** They have no account, no login, no dashboard and no self-service surface | D-54 |
| OM-1.6 | **The correction route substitutes for owner editing.** Because owners cannot edit, the public correction path is a first-class capability, not a footnote | TS-1, TS-3 |
| OM-1.7 | **Bulbula is a small team on shared hosting.** Every control must be executable by a few people with a browser, a phone and cron | `deployment.md` §2 |

### 1.1 The asymmetry that defines the obligations

Bulbula publishes information about real businesses and real people who
have no account through which to object.

| ID | Consequence |
| --- | --- |
| OM-1.8 | **Every correction, removal and objection route must work without an account** |
| OM-1.9 | **Permission is a gate, not a formality.** Nothing publishes without one (D-50) |
| OM-1.10 | **Provenance is mandatory.** A fact with no recorded source must not be published (DQ-1) |
| OM-1.11 | **Refusal is respected.** A business that declines is not listed, and the resulting coverage hole is accepted rather than worked around |

---

## 2. The operating lifecycle

```text
 1  Discover business        identify a candidate in the coverage area
 2  Contact / visit          reach someone able to agree
 3  Obtain Permission        record the agreement              ← GATE
 4  Collect information      the defined field set, with provenance
 5  Verify information       confirm the facts, with method    ← GATE
 6  Create Listing           Business + Branch(es) + media, as a draft
 7  Quality review           a second person checks            ← GATE
 8  Publish                  the Listing becomes visible
 9  Maintain                 reports, signals, freshness
10  Re-verify                confirm again at the defined interval
11  Correct / update         apply changes with reason and source
```

| ID | Rule | Source |
| --- | --- | --- |
| OM-2.1 | **No step may be skipped** | `listing-operations.md` §1 |
| OM-2.2 | **Steps 3, 5 and 7 are hard gates enforced by the product**, not by discipline | C-19, C-21 |
| OM-2.3 | **Steps 9, 10 and 11 are a loop, not a tail.** A directory that is only created and never maintained is wrong within months | COR-2 |
| OM-2.4 | **The lifecycle is identical for every Listing**, including pilot Listings and including any future bulk source. No import path bypasses a gate | OC-10 |
| OM-2.5 | **Each stage has one accountable actor at a time.** Work is never in two places | §4 |
| OM-2.6 | **Every stage transition is recorded** with actor, time and — where it changes published data — reason and source | C-29, OPX-0.3 |

### 2.1 Where the other operations documents attach

| Stage | Specialised by |
| --- | --- |
| 1–8, 10–11 | `listing-operations-v1.0.md` |
| 9 (reports, Reviews, abuse) | `moderation-operations-v1.0.md` |
| Commercial overlay on a published Listing | `advertising-operations-v1.0.md` |
| Inbound contact at any stage | `customer-support-v1.0.md` |
| Measurement of every stage | `analytics-operations-v1.0.md` |
| Whether the system is healthy enough to run the lifecycle | `observability-operations-v1.0.md` |
| What happens when the data or the service is lost | `backup-recovery-v1.0.md`, `business-continuity-v1.0.md` |

---

## 3. Actors

V1 has exactly three platform actors. There is no fourth.

| Actor | Authentication | Role in operations |
| --- | --- | --- |
| **Guest** | None | Reports problems; requests corrections; no account required (TS-2) |
| **Customer** | Google or email OTP (D-48) | Writes Reviews, reports Reviews, raises privacy requests |
| **Operator** | Staff credentials — strength **Open (D-45)** | Performs collection, creation, verification, moderation, report handling, campaign creation |
| **Administrator** | Staff credentials — strength **Open (D-45)** | Higher-risk actions: taxonomy, locations, campaign approval, staff accounts, audit access, legal escalation, data requests |
| **Business owner** | — | **Not a platform actor (D-54).** Interacts offline or through the public correction route |

| ID | Rule | Source |
| --- | --- | --- |
| OM-3.1 | **Staff accounts are created by Administrators.** There is no staff self-registration | ST-1 |
| OM-3.2 | **Staff accounts are separate from Customer accounts** | ST-2 |
| OM-3.3 | **Staff must not write Reviews from staff accounts** | PART-1 |
| OM-3.4 | **Authorisation is enforced server-side for every action** | ST-4, ENF-1 |
| OM-3.5 | **Least privilege applies.** The precise Operator/Administrator boundary is **Open (D-14)** | ST-6, TS-18 |
| OM-3.6 | **Staff authentication is stronger than Customer authentication** and must not be email OTP alone — **Open (D-45)** | ST-3, TS-17 |

### 3.1 The one-person case

| ID | Rule |
| --- | --- |
| OM-3.7 | **Nothing in this model assumes a headcount.** It must work with one person and remain correct with ten |
| OM-3.8 | **Where a control requires two people — quality review, separation of duties — operating with one person is an accepted, recorded operational risk, not a removed control** |
| OM-3.9 | **The acceptance is the owner's and is written down.** It is not a standing permission to self-approve indefinitely |
| OM-3.10 | **No staffing number appears anywhere in this documentation set.** Capacity is **PENDING PILOT** (D-31) |

---

## 4. Ownership and handoffs

### 4.1 Ownership rules

| ID | Rule |
| --- | --- |
| OM-4.1 | **Every queue has a named owner.** A queue nobody owns is not worked |
| OM-4.2 | **Every item in a queue has one current actor**, visible on the record |
| OM-4.3 | **Ownership of an outcome does not move when the task is delegated.** The Administrator who approves remains accountable for the approval |
| OM-4.4 | **The owner of a correction is whoever can make it true**, not whoever received it |
| OM-4.5 | **An unowned item is a defect in the process**, surfaced by the dashboard rather than discovered later |

### 4.2 Handoff requirements

A handoff is the point at which most operational information is lost.

| ID | Requirement | Source |
| --- | --- | --- |
| OM-4.6 | **A handoff carries its evidence.** The receiving person sees provenance, Permission and verification next to the data they justify | OPX-5.2, OC-5 |
| OM-4.7 | **A return is specific.** Returning a Listing for rework names the reasons; a generic rejection is not a handoff | OC-9, OPX-5.5 |
| OM-4.8 | **A handoff is recorded**, so the history of an item is reconstructable | C-29 |
| OM-4.9 | **No handoff happens outside the console.** A verbal "I've done that one" is not a state change | OPS-6 |
| OM-4.10 | **Queue state is shareable.** Filter and sort state lives in the URL so one staff member can hand another an exact view | OPX-0.10 |

### 4.3 The handoff map

| From | To | Carries | Gate |
| --- | --- | --- | --- |
| Discover → Approach | Operator → Operator | Prospect, duplicate check result | — |
| Approach → Permission | Operator | Who agreed, role, method | **Permission gate** |
| Permission → Collect | Operator | Scope of what may be published | — |
| Collect → Verify | Operator | Field set with provenance | — |
| Verify → Create | Operator | Method, date, actor | **Verification gate** |
| Create → Quality review | Operator → second person | The full draft plus evidence | **Review gate** |
| Quality review → Publish | Reviewer | Approval, or specific rework reasons | — |
| Report → Correction | Any → Operator | The report and its grounds | — |
| Correction → Re-verification | Operator | What changed and why | COR-2 |
| Campaign created → approved | Operator → Administrator | Eligibility and inventory check | **Administrator-only** |
| Any → legal escalation | Any → Administrator | The facts, preserved | **Administrator-only** |

---

## 5. Escalation

### 5.1 Escalation triggers

| Trigger | Escalates to | Source |
| --- | --- | --- |
| Legal demand, defamation complaint, takedown notice | **Administrator**, then counsel | OPX-9.7, **PENDING COUNSEL (L-15)** |
| Privacy or data-subject request | **Administrator** | `data-subject-rights-v1.0.md` |
| Suspected personal-data breach | **Administrator**, then owner and counsel | `incident-response-v1.0.md` §7 |
| Security incident | **Incident lead**, then owner | `incident-response-v1.0.md` §4 |
| Commercial pressure on an editorial decision | **Administrator**, then owner | IN-5, TS-16 |
| A business disputes a moderation outcome | **Administrator** | MOD-6 |
| Withdrawal of Permission | **Administrator** | TS-5 |
| Request to remove a photograph or a person from one | **Administrator** | PBD-9.7 |
| Content that may be illegal | **Administrator**, then counsel | TS-8 |
| A Correction that cannot be made true | **Administrator** | OM-4.4 |
| An ineligible Business is running a Campaign | **Administrator** | ADV-10 |

| ID | Rule |
| --- | --- |
| OM-5.1 | **Escalation is never a transfer of blame.** It is a transfer of authority to someone entitled to decide |
| OM-5.2 | **An escalation that is not recorded did not happen** |
| OM-5.3 | **Nobody below Administrator decides a legal question**, and no staff member decides one that counsel owns |
| OM-5.4 | **Escalation never pauses containment** where harm is active — the harmful item is unpublished first, with evidence preserved | IR-1.1 |
| OM-5.5 | **A commercial relationship is never a reason to escalate differently**, faster or slower | IN-4 |

### 5.2 Who may decide what

| Decision | Who |
| --- | --- |
| Publish, unpublish, correct a Listing | Named permission; role split **Open (D-14)** |
| Moderation outcome against a published ground | `review.moderate` |
| Moderation policy change, appeal outcome | `review.policy` — **Administrator** |
| Taxonomy or location change | `taxonomy.manage`, `location.manage` — **Administrator** |
| Campaign approval, activation, suspension, early termination | `campaign.approve` etc. — **Administrator** |
| Read the audit log | `audit.read` — **Administrator** |
| Execute a data or deletion request | **Administrator**, audited |
| Accept an operational risk | **Owner only** |
| Any legal determination | **Counsel only** |

---

## 6. Operational records

Operations produce evidence. The evidence *is* the accountability.

| Record | Created at | Contents | Governed by |
| --- | --- | --- | --- |
| **Prospect record** | Discover | Candidate, area, duplicate-check result. Whether it is a tracked entity is **Open — implementation detail** | `listing-operations.md` §2.1 |
| **Permission record** | Permission | Business, person who agreed, role claimed, date, method, Operator, scope. Exact contents **Open (D-43)** | D-50, OPX-3.2 |
| **Provenance record** | Collect | Source, method, Operator, date — per fact, recorded as the Listing is built | OPX-3.7, DQ-1 |
| **Verification record** | Verify | Method, date, scope, actor. Methods and interval **Open (D-08)** | C-21, OPX-5.2 |
| **Quality-review record** | Review | Reviewer, outcome, rework reasons | OPX-5.5 |
| **Correction record** | Correct | Field, previous value, new value, reason, source, actor, time | COR-1, OPX-4.2 |
| **Moderation record** | Moderate | Policy ground, actor, time, outcome | MOD-5, TS-9 |
| **Report record** | Report | Grounds, target, outcome. Reporter identity protected | REP-6, TS-10 |
| **Campaign record** | Advertising | Business, Package, Placement, period, approvals, state changes | ADV-6 |
| **Audit entry** | Every privileged action | Actor, action, target, timestamp, reason | C-29, OPX-12.1 |
| **Support record** | Inbound contact | `customer-support-v1.0.md` §9 | — |
| **Incident record** | Incident | `incident-response-v1.0.md` §9 | — |

| ID | Rule | Source |
| --- | --- | --- |
| OM-6.1 | **A privileged action that cannot be audited does not happen.** If the audit write fails, the action fails | OPX-0.3, TR-08 |
| OM-6.2 | **The audit trail is append-only.** No interface edits or deletes it | OPX-12.2 |
| OM-6.3 | **Audit entries survive Customer deletion** | OPX-12.4, TR-167 |
| OM-6.4 | **Operational records containing personal data are personal data**, with a retention period — **PENDING COUNSEL (L-21)** | `data-retention-v1.0.md` |
| OM-6.5 | **Staff access to personal information is logged** and reviewable | TS-20, PG-10.1 |
| OM-6.6 | **Records are written at the time of the act**, not reconstructed afterwards | DQ-1 |

---

## 7. Quality controls

| ID | Control | Mechanism |
| --- | --- | --- |
| OM-7.1 | **Permission gate** — nothing publishes without a linked Permission record | Product-enforced (TR-49) |
| OM-7.2 | **Verification gate** — nothing publishes unverified | Product-enforced (OPX-5.1) |
| OM-7.3 | **Second-person quality review** — nothing reaches the public unchecked | Process + product (OPX-5.4) |
| OM-7.4 | **Duplicate detection before data entry**, not after | OPX-3.1, DQ-4 |
| OM-7.5 | **Provenance per fact** — unsourced facts are not published | DQ-1 |
| OM-7.6 | **Omission over guessing** — "hours not confirmed" beats wrong hours | DQ-2 |
| OM-7.7 | **No copying from other directories** — it would be unsourced, unpermissioned and probably wrong | DQ-3 |
| OM-7.8 | **Personal contact points flagged at collection**, never assumed away | COL-2, DQ-6 |
| OM-7.9 | **Completeness measured and surfaced as missing fields**, never as a bare percentage | OPX-3.8 |
| OM-7.10 | **"Unknown" is a recordable state**, distinct from empty | OPX-3.4 |

### 7.1 Publication controls

| ID | Control |
| --- | --- |
| OM-7.11 | **Publication is refused with a named blocker**, never a generic failure (OPX-0.6, OPX-5.1) |
| OM-7.12 | **The creator does not publish their own work where a second person exists** (OPX-5.4) |
| OM-7.13 | **Every publish and unpublish is audited with a reason** (OPX-5.6) |
| OM-7.14 | **Unpublishing is always available and prompt**, including on withdrawal of Permission (TS-5) |
| OM-7.15 | **Closure is a state, never a deletion.** The URL and the history survive (COR-3, TR-53) |

### 7.2 Correction controls

| ID | Control |
| --- | --- |
| OM-7.16 | **Every Correction records what changed, why, the source and the actor** (COR-1) |
| OM-7.17 | **Material Corrections flag re-verification** — contact details, location, hours, name, closure (COR-2) |
| OM-7.18 | **Every report that produced a Correction links to it** (COR-4) |
| OM-7.19 | **Every report reaches a recorded outcome** (TS-4, REP-6) |
| OM-7.20 | **Factual errors that materially mislead are prioritised above cosmetic ones**; target times are **PENDING PILOT** (TS-6, COR-5) |
| OM-7.21 | **A commercial relationship buys no priority in correction handling** (IN-4) |

### 7.3 Freshness controls

| ID | Control |
| --- | --- |
| OM-7.22 | **Verification age is exposed and produces a queue ordered by staleness** (C-21, C-28) |
| OM-7.23 | **The stale queue is derived at read time.** No scheduled job is required (OPX-5.3, TR-154) |
| OM-7.24 | **Staleness is shown publicly as an honest date, never hidden** (OPX-5.3) |
| OM-7.25 | **The re-verification interval is Open (D-08)** and is read from configuration, never hard-coded |
| OM-7.26 | **Freshness degrades silently if nobody looks.** The queue is reviewed on a cadence (§9) |

---

## 8. Separation of commercial and editorial

| ID | Rule | Source |
| --- | --- | --- |
| OM-8.1 | **Sponsorship never influences organic ranking, inclusion or exclusion** | IN-1, ADV-8 |
| OM-8.2 | **Sponsorship never influences verification, trust indicators or the rating summary** | IN-2, ADV-9 |
| OM-8.3 | **Sponsorship never influences moderation outcomes** | IN-3, MOD-3 |
| OM-8.4 | **Sponsorship never buys priority in correction or report handling** | IN-4 |
| OM-8.5 | **Advertising performance must not be a staff incentive that conflicts with moderation duties** | IN-5 |
| OM-8.6 | **The console provides no navigation path from a Campaign to editing that Business's data** | OPX-10.9 |
| OM-8.7 | **The specific controls enforcing OM-8.1…OM-8.6 are Open (D-39) and must be resolved before the first paid Campaign runs** | IN-6, ADV-12 |
| OM-8.8 | **An identical query must produce identical organic ordering with and without an active Campaign.** This is a testable property, not a promise | IN-7, TR-43 |

---

## 9. Operating cadence

A realistic rhythm for a small team. Each item has a named owner.

| Cadence | Activity | Document |
| --- | --- | --- |
| **Continuously** | Work the queues: review, moderation, reports | §2, `moderation-operations-v1.0.md` |
| **Daily** | Triage new reports and inbound contact; check job and email-delivery failures | `observability-operations-v1.0.md` |
| **Weekly** | Re-verification queue; zero-result queries; coverage gaps; security alerts | `analytics-operations-v1.0.md`, SO §14 |
| **Monthly** | Staff accounts and permissions; audit sample; backup confirmation; campaign eligibility sweep | SO §14, `advertising-operations-v1.0.md` |
| **Quarterly** | Restore rehearsal; vendor register; accepted risks; compliance register | `backup-recovery-v1.0.md`, PG §12 |
| **On change** | Privacy review for any new field, purpose, recipient or retention change | PG §11 |
| **Annually** | Threat model; this document; continuity plan | `business-continuity-v1.0.md` |

| ID | Rule |
| --- | --- |
| OM-9.1 | **A cadence with no owner is a wish.** Each row above is assigned before launch — **Open — implementation detail** |
| OM-9.2 | **A missed cadence is recorded**, not quietly skipped |
| OM-9.3 | **The cadence is sized for the team that exists**, and is revised rather than abandoned when it proves unrealistic |

---

## 10. Traceability

| This document | Traces to |
| --- | --- |
| §1 operating reality | D-02, D-54, OPS-1, OPS-8, `listing-operations.md` |
| §2 lifecycle | D-50, `listing-operations.md` §1, OPS-3 |
| §3 actors | D-48, D-54, `interaction-permissions.md` §1, §4; ST-1…ST-6 |
| §4 ownership and handoffs | OPS-4, OC-1, OC-5, OC-9, OPX-0.10 |
| §5 escalation | OPX-9.7, TS-16, MOD-6, IR §4, L-15 |
| §6 records | C-29, D-43, D-08, COR-1, MOD-5, ADV-6, TR-08 |
| §7 quality controls | C-19, C-21, DQ-1…DQ-6, COR-1…COR-5, TS-4, TS-6 |
| §8 separation | D-10, D-39, IN-1…IN-7, ADV-8, ADV-9, ADV-12, TR-43 |
| §9 cadence | SO §14, PG §11 |

---

## 11. Open items

| ID | Item | Marker |
| --- | --- | --- |
| D-14 | Operator / Administrator permission split | **Open (D-14)** — product decision |
| D-45 | Staff authentication strength | **Open (D-45)** — product decision |
| D-08 | Verification methods, tiers, re-verification interval | **Open (D-08)** — product decision |
| D-43 | Permission record contents and retention | **Open (D-43)** — product decision |
| D-39 | Commercial/editorial integrity controls — **blocks the first paid Campaign** | **Open (D-39)** — product decision |
| D-30n / D-31 | Launch threshold and operational capacity | **PENDING PILOT** |
| D-40 | Launch-area boundary | **Open (D-40)** — product decision |
| L-15 | Review liability and takedown process | **PENDING COUNSEL** |
| L-21 / D-46 | Retention of operational records | **PENDING COUNSEL** |
| — | Whether a prospect is a tracked entity or an informal list | **Open — implementation detail** |
| — | Named owner for each cadence row in §9 | **Open — operational decision** |
| — | How an accepted single-person separation-of-duties risk is recorded and reviewed | **Open — operational decision** |
| — | Whether refused businesses are recorded internally, and for how long | **Open — operational decision**; interacts with **PENDING COUNSEL (L-5)** |

---

## Decision references

D-02, D-08, D-10, D-14, D-30, D-30n, D-31, D-39, D-40, D-43, D-45, D-46,
D-48, D-50, D-54.
