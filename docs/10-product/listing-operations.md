# Listing Operations

| | |
| --- | --- |
| **Document** | Listing Operations — lifecycle, permission, verification and the pilot |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** Bulbula's product is built by hand. This document specifies the
operational process that produces and maintains Listings, and the product
requirements that process places on the operations console. It is the
narrative companion to [`prd-v1.0.md`](prd-v1.0.md) capabilities C-19…C-29.

**Why this is a product document and not an internal note.** Under D-02 and
D-54 no business can create, claim or edit its own Listing. Every fact the
public sees arrives through this process. The process *is* the supply chain
of the product, and the operations console is a primary V1 product surface
(PRD §27).

---

## 1. The lifecycle

```text
 1. Discover        identify a business in the coverage area
 2. Approach        visit or call; explain Bulbula
 3. Permission      obtain and record agreement to collect and publish
 4. Collect         gather the defined field set, with provenance
 5. Verify          confirm the facts, with method and date
 6. Create          enter Business + Branch(es) + media as a draft
 7. Quality review  a second person checks before anything is public
 8. Publish         the Listing becomes visible
 9. Maintain        monitor reports, signals and freshness
10. Correct         apply changes with reason and source
11. Re-verify       confirm again at the defined interval
```

No step may be skipped. Steps 3, 5 and 7 are **gates**: the product
enforces them (C-19, C-21).

---

## 2. Stage specifications

### 2.1 Discover

| | |
| --- | --- |
| **Actor** | Operator |
| **Goal** | A prioritised list of businesses in the coverage area that are not yet listed |
| **Inputs** | Field walks, local knowledge, zero-result search queries (SRCH-8), reports suggesting a missing business |
| **Output** | A prospect record |
| **Product requirement** | The console **MUST** let an Operator record a prospect and check it against existing Listings for duplicates before any field work (C-19) |
| **Open** | Whether prospects are a tracked entity or an informal list — **Open — implementation/product detail (D-43 adjacent)** |

### 2.2 Approach

| | |
| --- | --- |
| **Actor** | Operator |
| **Goal** | Reach a person authorised to agree on the business's behalf |
| **Requirement** | The Operator **MUST** explain what Bulbula is, what will be published, that the Listing is free, and that the business will not control it directly (D-02) |
| **Requirement** | The Operator **MUST** explain the correction route, since the business cannot edit (PRD TS-3) |
| **Output** | Either a Permission record, a refusal, or a deferral |

### 2.3 Permission — a hard gate

| | |
| --- | --- |
| **Actor** | Operator |
| **Decision basis** | D-50 — collection is permission-based |
| **Product rule** | **No Listing may be published without a linked Permission record** (C-19, PROV-1) |

The Permission record evidences the agreement. Its exact contents and
retention are **Open (D-43)**; the minimum the product needs is:

| Element | Purpose |
| --- | --- |
| Business identity | What the permission covers |
| Person who agreed, and their role | Whether they could agree |
| Date | When |
| Method | In person, by telephone, in writing |
| Operator | Who obtained it |
| Scope | Information and media covered |

**Refusal.** If a business declines, the product **MUST NOT** publish a
Listing for it. This produces visible coverage holes in a small area — risk
**RK-20**. Whether Bulbula shows nothing at all, shows a minimal
non-permissioned entry, or records the refusal internally only, is an **open
product question routed to counsel** — recorded as such in v0.4 §8 and tied
to **D-46** and **L-5**. **Until it is decided, the product shows nothing.**

**Withdrawal.** A business may withdraw Permission. The product **MUST** be
able to unpublish promptly, with the request and action recorded (PRD TS-5,
C-29). Whether withdrawal is legally compelling in every case is **PENDING
COUNSEL** (L-5, D-46).

### 2.4 Collect

| | |
| --- | --- |
| **Actor** | Operator |
| **Goal** | The defined field set, captured once, accurately |
| **Decision basis** | D-50 field list; D-51 minimization |

| Rule | Statement |
| --- | --- |
| COL-1 | Only fields with a stated product purpose are collected (PRIV-2) |
| COL-2 | A contact point that is a natural person's personal detail **MUST** be flagged at collection time (PCP-2) |
| COL-3 | Named individuals are collected only where necessary (PCP-4) |
| COL-4 | Provenance — source, method, Operator, date — is recorded with the data, not reconstructed later (PRD §14.2) |
| COL-5 | Media is collected under the same Permission (C-22) |
| COL-6 | Services, products and pricing are captured where offered; their representation is **Open (D-44)** |
| COL-7 | Collection **MUST** be completable on a phone in the field, in one visit (OPS-6) |

### 2.5 Verify

| | |
| --- | --- |
| **Actor** | Operator |
| **Goal** | Confidence that each published fact is true |
| **Decision basis** | D-08 — **the verification method mix, evidence standard and interval are Open** |

What is settled: Verification **MUST** be recorded with method, date and the
person who performed it, and a Listing **MUST NOT** be published without it
(C-21). What is open (D-08): which methods count, whether tiers exist, the
re-verification interval, and how a stale Listing is presented publicly.
Established practice offers a known method mix — physical presence, a
successful call to the published number, documentary evidence (R-02) — but
**no method is adopted here**; that is D-08's decision to make.

### 2.6 Create

| | |
| --- | --- |
| **Actor** | Operator |
| **Output** | A draft Listing: one Business, at least one Branch, media, provenance, Permission link |
| **Model** | `Business → Branch`; a single-location business is one Business with one Branch (D-03) |
| **Rules** | Duplicate check before submission; required-field completeness before submission; no publication path that bypasses review (C-19) |

### 2.7 Quality review — a hard gate

| | |
| --- | --- |
| **Actor** | A second Operator, or an Administrator |
| **Goal** | Nothing reaches the public unchecked |

| Check | Question |
| --- | --- |
| Completeness | Are the required fields present? |
| Plausibility | Do the hours, address and category make sense together? |
| Duplication | Does this Business already exist? |
| Classification | Is the Category correct and specific? |
| Media | Are the photographs of this business, usable, and permitted? |
| Provenance | Is the source recorded and the Permission linked? |
| Personal data | Are personal contact points flagged, and is nothing unnecessary present? |

Outcome: **approve and publish**, or **return with specific reasons**. The
creator should not approve their own work where a second person exists; where
Bulbula runs with one Staff member this is an accepted, recorded operational
risk rather than a removed control (C-21).

### 2.8 Publish

The Listing becomes publicly visible, enters the sitemap (SEO-7), and
becomes eligible to be sponsored (ADV-10).

### 2.9 Maintain

| Signal | Source |
| --- | --- |
| Public reports | C-15 → C-26 |
| Verification ageing | C-21 → re-verification queue |
| Operational analytics | C-28: completeness, stale counts, zero-result queries |
| Direct contact from the business | Contact page (C-18) |

### 2.10 Correct

| Rule | Statement |
| --- | --- |
| COR-1 | Every Correction records what changed, why, the source and the Operator (C-20, C-29) |
| COR-2 | Material Corrections — contact details, location, hours, name, closure — **SHOULD** trigger re-verification (C-21) |
| COR-3 | A permanent closure is recorded as a closure, never a deletion; the URL and history survive (C-20) |
| COR-4 | Every report that produced a Correction is linked to it (C-26) |
| COR-5 | Target correction times are **PENDING PILOT** — no number is invented here |

### 2.11 Re-verify

Published Listings re-enter verification at the interval set by **D-08**.
Until that decision exists, the product requirement is only that the console
**MUST** expose verification age and produce a queue ordered by it (C-21,
C-28).

---

## 3. Data quality rules

| ID | Rule |
| --- | --- |
| DQ-1 | A fact with no recorded source **MUST NOT** be published |
| DQ-2 | Uncertain information **MUST** be omitted rather than guessed. "Hours not confirmed" beats wrong hours (C-09) |
| DQ-3 | Nothing may be copied from another directory and presented as collected — it would be unsourced, unpermissioned and probably wrong (D-50) |
| DQ-4 | Duplicates **MUST** be prevented at creation and detectable afterwards |
| DQ-5 | Completeness is measured, and its operational use is defined; any ranking influence is **Open (D-09)** |
| DQ-6 | Personal information in business data is flagged, not assumed away (PCP-1) |

---

## 4. Operations console requirements

Derived from this process; stated as product requirements on C-19…C-29.

| ID | Requirement |
| --- | --- |
| OC-1 | Work is presented as **queues**: awaiting review, reports to triage, Reviews to moderate, Listings due for re-verification |
| OC-2 | A Listing's state is answerable at a glance: published, verified when and by whom, last changed by whom, permission on file |
| OC-3 | Draft and submitted states exist; publication is reachable only through quality review |
| OC-4 | Duplicate candidates are surfaced during creation, not after |
| OC-5 | Provenance and Permission are visible to the reviewer at the moment of review |
| OC-6 | Field capture works on a phone (OPS-6) |
| OC-7 | Every consequential action writes an audit entry (C-29) |
| OC-8 | Throughput and quality metrics are available to Staff (C-28) |
| OC-9 | Returning a Listing for rework carries specific reasons, not a generic rejection |
| OC-10 | No bulk-import path may bypass Permission, provenance or quality review |

---

## 5. Staffing and roles

Operator and Administrator only (PRD §28). The precise split is **Open
(D-14)**. Nothing in this process assumes a headcount: it must work with one
person and remain correct with ten. Where a control depends on two people
(quality review), operating with one person is an **accepted recorded risk**,
not a reason to weaken the control.

---

## 6. The 20-business pilot

**The pilot is a launch-preparation activity, not a product feature**
(D-30, D-31). Nothing is built for it; it uses the console as specified.

### 6.1 Purpose

To replace guesses with measurements. Until the pilot runs, Bulbula does not
know how long a Listing takes, what proportion of businesses grant
Permission, or what a realistic launch coverage number is. **Therefore no
such number appears in this documentation set** (D-30n, **PENDING PILOT**).

### 6.2 Scope

Twenty businesses in the coverage area, taken through the full lifecycle
from discovery to publication, by the people who will do the work at scale,
using the real console.

### 6.3 Measurement set

The twelve measures specified in
[`../00-discovery/product-decision-brief-v0.4.md`](../00-discovery/product-decision-brief-v0.4.md)
§9.1, covering at least: approach-to-permission conversion, refusal reasons,
time per stage, time to a complete Listing, field-completeness achieved,
rework rate at quality review, duplicate rate, media captured per Listing,
verification method mix, correction volume in the first period, Operator
hours per Listing, and the resulting projected rate per Operator-week.

Output document: `docs/40-operations/pilot-benchmark-v1.0.md` — **to be
produced when the pilot runs**. It does not exist yet and **MUST NOT** be
pre-filled with estimates.

### 6.4 What the pilot decides

| Question | Decision |
| --- | --- |
| The numeric launch coverage threshold | **D-30n** — set by the owner from pilot data |
| Realistic verification interval | Input to **D-08** |
| Realistic correction turnaround | Input to COR-5 |
| Whether the field set is collectable in one visit | Input to **D-50** refinement and **D-44** |
| Operator capacity planning | Operational |

### 6.5 Rules

| ID | Rule |
| --- | --- |
| PIL-1 | The pilot **MUST** use the real process and the real console, not a spreadsheet substitute |
| PIL-2 | Pilot Listings are real and, once published, are part of the product |
| PIL-3 | Pilot measurements **MUST** be recorded as observed, including unflattering ones |
| PIL-4 | No launch threshold may be set, quoted or planned against before the pilot completes |
| PIL-5 | The pilot does not change scope; if it reveals a scope problem, that is a decision for the register, not an in-flight change |

---

## 7. Launch readiness

Launch requires, at minimum:

| Condition | Status |
| --- | --- |
| The operations console supports the full lifecycle | A V1 build requirement |
| The pilot is complete and measured | **PENDING** (D-31) |
| The coverage threshold is met | **PENDING PILOT** (D-30n) — the number does not exist yet |
| Legal minima are confirmed and the required policy pages are published | **PENDING COUNSEL** (D-46) |
| Both client surfaces deliver the V1 capability set | A V1 build requirement |
| The launch-area boundary is confirmed | **PENDING** (D-40) |

No launch date is proposed in this document.

---

## Decision references

D-02, D-03, D-08, D-09, D-14, D-30, D-31, D-40, D-43, D-44, D-46, D-50,
D-51, D-54.
