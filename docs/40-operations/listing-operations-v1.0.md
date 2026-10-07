# Listing Operations

| | |
| --- | --- |
| **Document** | Listing Operations — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

The **operational companion** to
[`../10-product/listing-operations.md`](../10-product/listing-operations.md).
That document states the product requirements the lifecycle places on the
console. This one states **how staff actually do the work**: intake,
collection, provenance, verification, quality review, publication,
corrections, and the pilot.

**Out of scope.** Customer-generated content
(`moderation-operations-v1.0.md`), campaigns
(`advertising-operations-v1.0.md`), inbound contact
(`customer-support-v1.0.md`), console UX
(`../20-ux-ui/operations-console-ux-v1.0.md`).

## Authority and precedence

Below `../10-product/listing-operations.md`, the PRD and the register;
above day-to-day practice.

| ID | Rule |
| --- | --- |
| LO-0.1 | **This document introduces no product capability and changes no product rule.** It describes work within the approved process |
| LO-0.2 | **Where it appears to require something the console does not provide, that is an open item**, not an instruction to build |
| LO-0.3 | **No target time, throughput figure or quality threshold is stated.** All are **PENDING PILOT** (D-30n, D-31) |
| LO-0.4 | **Where a rule is open in the product document, it is open here.** Nothing is resolved by operational convenience |

---

## 1. Intake

### 1.1 Identify the Business

| ID | Practice | Source |
| --- | --- | --- |
| LO-1.1 | Candidates come from field walks, local knowledge, **zero-result search queries**, and reports suggesting a missing business | `listing-operations.md` §2.1, SRCH-8 |
| LO-1.2 | **Zero-result queries are the highest-yield source** — they are demand with no supply | OPX-11.3 |
| LO-1.3 | Candidates are prioritised by demand signal and by coverage gap, not by convenience of the walking route | ANO §4 |
| LO-1.4 | A candidate outside the confirmed coverage area is not worked. The boundary is **Open (D-40)** | D-40 |

### 1.2 Check for an existing Listing — before any field work

| ID | Practice | Source |
| --- | --- | --- |
| LO-1.5 | **The duplicate check happens before data entry, not after** | OPX-3.1, TR-55 |
| LO-1.6 | The check is by name and Area, and considers **Aliases and Amharic spellings** — the same business may already exist under a different transliteration | D-18, D-06 |
| LO-1.7 | A Business operating from several places is **one Business with several Branches**, not several Businesses | D-03 |
| LO-1.8 | **Proceeding past a duplicate candidate is allowed and is recorded** | OPX-3.1 |
| LO-1.9 | A duplicate confirmed as the same Business routes to the existing record; a second record is not created | OPX-3.1 |
| LO-1.10 | Duplicates found after publication are a **merge**, handled as a Correction with its history preserved | DQ-4, COR-3 |

### 1.3 Identify the Branch

| ID | Practice |
| --- | --- |
| LO-1.11 | Every Business has at least one Branch; **exactly one is primary** (OPX-3.6, D-03) |
| LO-1.12 | Each Branch requires an **Area and a Sub-city** (OPX-3.5, GEO-6) |
| LO-1.13 | Which attributes belong to the Branch rather than the Business — hours, contact, reviews, analytics — is **Open (D-55)**. Collection records the fact and its place; it does not decide the model |
| LO-1.14 | A Landmark is optional and is recorded where it genuinely helps a User find the place |

### 1.4 Contact or visit

| ID | Practice | Source |
| --- | --- | --- |
| LO-1.15 | The Operator reaches **a person able to agree on the business's behalf**, and records what role that person claimed | `listing-operations.md` §2.2 |
| LO-1.16 | The Operator **must** explain: what Bulbula is, what will be published, that the Listing is free, and that **the business will not control it directly** | D-02 |
| LO-1.17 | The Operator **must** explain the correction route, since the business cannot edit | TS-3 |
| LO-1.18 | The Operator does not promise ranking, visibility, traffic or outcomes. **There is nothing to promise** — ranking is organic and unpurchasable | IN-1, MS-5 |
| LO-1.19 | Advertising is **not** sold during a collection visit as a condition of listing. Listing is free and unconditional | PK-6, IN-4 |
| LO-1.20 | The outcome is one of three: **Permission**, **refusal**, or **deferral**. All three are recorded | `listing-operations.md` §2.2 |

### 1.5 Permission — the first hard gate

| ID | Practice | Source |
| --- | --- | --- |
| LO-1.21 | **No Listing is published without a linked Permission record** | D-50, TR-49 |
| LO-1.22 | The record captures business identity, the person who agreed and their claimed role, date, method, Operator and scope. **Exact contents are Open (D-43)** | OPX-3.2 |
| LO-1.23 | **Scope matters.** Permission to publish a business phone number is not permission to publish a named individual's mobile, and not permission to photograph a person | PCP-2, PBD-9.12 |
| LO-1.24 | **Refusal means nothing is published.** The coverage hole is accepted (risk RK-20) | `listing-operations.md` §2.3 |
| LO-1.25 | Whether refusals are recorded internally, and for how long, is **Open — operational decision** and interacts with **PENDING COUNSEL (L-5, D-46)** | D-46 |
| LO-1.26 | **Withdrawal of Permission is honoured by prompt unpublication**, with the request and the action recorded | TS-5 |
| LO-1.27 | A business withdrawing Permission is **not argued with**. Whether withdrawal is legally compelling in every case is **PENDING COUNSEL (L-5)** | D-46 |

---

## 2. Data collection

### 2.1 Collection rules

| ID | Rule | Source |
| --- | --- | --- |
| LO-2.1 | **Only fields with a stated product purpose are collected** | COL-1, PRIV-2 |
| LO-2.2 | **Collection must be completable on a phone, in the field, in one visit** | COL-7, OPS-6 |
| LO-2.3 | **Provenance is recorded with the data, not reconstructed later** | COL-4, OPX-3.7 |
| LO-2.4 | **A draft saves without validation** so field work is never lost; validation applies at submission | OPX-3.10 |
| LO-2.5 | **"Unknown" is recorded as unknown.** It is a valid state, distinct from empty, and far better than a guess | OPX-3.4, DQ-2 |
| LO-2.6 | **Nothing is copied from another directory** and presented as collected | DQ-3 |

### 2.2 Field-by-field operational handling

| Field | Operational handling | Open |
| --- | --- | --- |
| **Business name** | As the business writes it. Amharic forms recorded where they exist; transliterations captured as **Aliases**, not as the name | D-18 |
| **Description** | Factual, written by the Operator from what the business says. **Not marketing copy, not a review** | — |
| **Category / Subcategory** | Chosen from the controlled taxonomy only. **Never invented in the field.** A missing category is a taxonomy request to an Administrator | D-06, D-56, D-57 |
| **Area** | Chosen from curated Areas; **never free text** | GEO-6 |
| **Sub-city** | Recorded for correctness even though it is secondary in the interface | — |
| **Address** | As a person would give directions locally, plus the structured fields. A **home-based business's address is a person's home address** and is treated as personal data | DI-4.4 |
| **Coordinates** | Captured at the place where possible, not geocoded from a guess. Accuracy is a pilot measure | D-30 |
| **Opening hours** | Captured as the business states them, including split shifts and exceptions where they exist. The hours **model** is **Open (D-04)**; collection records reality and does not pre-empt the model | D-04 |
| **Business phone** | **Flagged as a personal contact point whenever it is a natural person's number** — in this market a business number very often is | COL-2, PCP-1 |
| **Business email** | Same flag, same reasoning | PCP-1 |
| **Named contact person** | Collected **only where necessary**, and flagged | COL-3 |
| **Website / social links** | Recorded as given; a personal profile link is a personal contact point | DI-4.10 |
| **Services / products / pricing** | Captured where offered. **No structured field exists yet — specifying one would decide D-44.** Until then this is recorded as description-level information and the gap is visible | D-44, OPX-3.9 |
| **Media** | §2.4 | D-25, L-18 |

### 2.3 Personal contact points

| ID | Rule | Source |
| --- | --- | --- |
| LO-2.7 | **Business contact information is not automatically non-personal.** Classification is by relation to a natural person | D-51, PCP-1 |
| LO-2.8 | **The flag is set at collection time**, by the person who can see the context — not inferred later from the data | COL-2 |
| LO-2.9 | **The flag is internal and never surfaces publicly** | OPX-3.11 |
| LO-2.10 | **A business alternative is preferred wherever one exists.** A shop line beats an owner's mobile | PCP-2 |
| LO-2.11 | **The person behind a personal contact point may not be the person who gave Permission.** Where they differ, that is recorded | PG-7.7 |

### 2.4 Media

| ID | Rule | Source |
| --- | --- | --- |
| LO-2.12 | **Media is collected under the same Permission** as the data | COL-5 |
| LO-2.13 | **Photographs are of premises, not of people.** Where an individual is identifiable and incidental, the Operator reframes, waits, or returns later | PBD-9.2, PBD-9.3 |
| LO-2.14 | **A photograph must not reveal what the business did not intend** — a home interior, a vehicle plate, a document on a counter, a child | PBD-9.8 |
| LO-2.15 | **Each media item records its provenance and the right to publish it** | OPX-6.1 |
| LO-2.16 | **Alt text is required**, not optional | OPX-6.2 |
| LO-2.17 | **EXIF including GPS is stripped before storage**, and the retained original is stripped too | APP-6.11, DI-4.20 |
| LO-2.18 | **A removal request for an identifiable person is honoured by removing or replacing the photograph**, not by arguing about identifiability | PBD-9.9 |
| LO-2.19 | **Photography lawfulness is PENDING COUNSEL (L-18).** Until resolved, LO-2.13 applies strictly | L-18 |
| LO-2.20 | Storage, formats, dimensions and caps are **Open (D-25)** | D-25 |

---

## 3. Provenance

| ID | Rule | Source |
| --- | --- | --- |
| LO-3.1 | **Every collected fact is traceable**: source, method, Operator, date | COL-4, DQ-1 |
| LO-3.2 | **A fact with no recorded source must not be published** | DQ-1 |
| LO-3.3 | **Provenance is recorded as the Listing is built**, not added at review time | OPX-3.7 |
| LO-3.4 | **"The owner told me" is a valid source** and is recorded as such, with who and when |
| LO-3.5 | **"I saw the sign" is a valid source** for a name or hours, and is recorded as such |
| LO-3.6 | **"I found it online" is a source that requires care**, because it is neither permissioned nor verified, and must never be the sole basis for publication | DQ-3 |
| LO-3.7 | **Provenance is visible to the reviewer next to the fact it justifies** | OC-5, OPX-5.2 |
| LO-3.8 | **Provenance is internal and never published** | PCP-5 |
| LO-3.9 | **Provenance survives correction.** Updating a fact records the new source without erasing the old | COR-1, OPX-4.4 |

---

## 4. Verification

### 4.1 What is settled

| ID | Rule | Source |
| --- | --- | --- |
| LO-4.1 | **Verification must be recorded with method, date, scope and the person who performed it** | C-21, OPX-5.2 |
| LO-4.2 | **A Listing must not be published without verification** | C-21, OPX-5.1 |
| LO-4.3 | **Verification age is exposed and drives a queue ordered by staleness** | C-21, C-28 |
| LO-4.4 | **Staleness is shown publicly as an honest date** | OPX-5.3 |
| LO-4.5 | **Sponsorship cannot buy or influence verification** | ADV-9, IN-2 |
| LO-4.6 | **The verification form reads methods and intervals from configuration and hard-codes nothing** | OPX-5.3 |

### 4.2 What is open — D-08

| Question | Status |
| --- | --- |
| Which methods count as verification | **Open (D-08)** |
| Whether tiers of verification exist | **Open (D-08)** |
| The evidence standard per method | **Open (D-08)** |
| The re-verification interval | **Open (D-08)**, informed by **PENDING PILOT** |
| How a stale Listing is presented publicly | **Open (D-08)** |

| ID | Rule |
| --- | --- |
| LO-4.7 | **No verification method is adopted in this document.** Established practice offers a known mix — physical presence, a successful call to the published number, documentary evidence — but **choosing among them is D-08's decision** |
| LO-4.8 | **Operators must not invent a local standard** while D-08 is open. What they do is recorded as method text so the pilot can report the actual mix |
| LO-4.9 | **The pilot measures the verification method mix**, which is a direct input to D-08 |

### 4.3 What gets verified

| ID | Rule |
| --- | --- |
| LO-4.10 | **The facts a User will act on matter most**: that the business exists, is at that place, and is reachable on that number |
| LO-4.11 | **Verification has a scope.** Verifying existence is not verifying opening hours; the scope is recorded (OPX-5.2) |
| LO-4.12 | **A material Correction flags re-verification** rather than inheriting the old verification (COR-2, OPX-4.3) |

---

## 5. Quality review

### 5.1 The gate

| ID | Rule | Source |
| --- | --- | --- |
| LO-5.1 | **A second person checks before anything is public** | `listing-operations.md` §2.7 |
| LO-5.2 | **Where another reviewer exists, the publisher is not the creator** | OPX-5.4 |
| LO-5.3 | **Where Bulbula operates with one staff member, this is a recorded, accepted operational risk — not a removed control** | OPX-5.4, OM-3.8 |
| LO-5.4 | **The reviewer sees Permission, Provenance and Verification side by side with the data they justify** | OPX-5.2 |
| LO-5.5 | **Publication is refused with a named blocker** when a gate is unmet | OPX-5.1 |

### 5.2 The checklist

| # | Check | Fail condition |
| --- | --- | --- |
| 1 | **Completeness** | Required fields missing |
| 2 | **Plausibility** | Hours, address and category do not make sense together |
| 3 | **Duplication** | This Business already exists |
| 4 | **Classification** | Category wrong, or vaguer than it could be |
| 5 | **Media** | Not this business, unusable, unpermitted, or showing an incidental person |
| 6 | **Provenance** | A published fact has no recorded source |
| 7 | **Permission** | Absent, out of scope, or given by someone whose role is unclear |
| 8 | **Verification** | Absent, or its scope does not cover what is published |
| 9 | **Personal data** | Personal contact points unflagged, or unnecessary personal data present |
| 10 | **Tone** | Description reads as marketing or as a review rather than as fact |

| ID | Rule | Source |
| --- | --- | --- |
| LO-5.6 | **Any failed row blocks publication** |
| LO-5.7 | **Returning for rework carries specific reasons** and routes back to the creator | OC-9, OPX-5.5 |
| LO-5.8 | **Rework rate is a pilot measure**, not a performance score. There are no leaderboards | OPX §2.2 |
| LO-5.9 | **The reviewer may correct trivial errors directly**, recording the change; anything substantive goes back | COR-1 |

---

## 6. Publishing

### 6.1 Prerequisites

| # | Prerequisite | Enforced by |
| --- | --- | --- |
| 1 | A linked Permission record | TR-49 |
| 2 | A recorded Verification | OPX-5.1 |
| 3 | Quality review passed | OPX-5.4 |
| 4 | Required fields complete | OPX-3.4 |
| 5 | At least one Branch, exactly one primary | OPX-3.6 |
| 6 | Category and Subcategory assigned | D-06 |
| 7 | Media, where present, has alt text and recorded rights | OPX-6.1, OPX-6.2 |

### 6.2 Rules

| ID | Rule | Source |
| --- | --- | --- |
| LO-6.1 | **Publication is the point of no return.** Once published and indexed, content is effectively beyond full recall | PBD-1.3, PBD-5.4 |
| LO-6.2 | **On publication the Listing enters the sitemap and becomes eligible for sponsorship** | SEO-7, ADV-10 |
| LO-6.3 | **Only the enumerated public field set is published.** Internal fields — provenance, Permission, verification evidence, personal-contact flags — never render | PCP-3, PCP-5 |
| LO-6.4 | **Every publication is audited.** A failed audit write fails the action | OPX-5.6, TR-08 |
| LO-6.5 | **No bulk-import path may bypass Permission, provenance or quality review** | OC-10 |

### 6.3 Failure conditions and unpublication

| Condition | Action |
| --- | --- |
| A gate is unmet | Publication refused with the **named blocker** (OPX-5.1) |
| Permission withdrawn | **Unpublish promptly**, with the request and action recorded (TS-5) |
| Business permanently closed | Recorded as a **closure**, never a deletion. URL and history survive (COR-3) |
| Material inaccuracy found after publication | Correct, or unpublish while correcting (§7) |
| A legal demand | Escalate to **Administrator** then counsel — **PENDING COUNSEL (L-15)** |
| Media rights challenged | Remove or replace the media (LO-2.18) |

| ID | Rule |
| --- | --- |
| LO-6.6 | **Unpublishing always requires a reason** and is audited (OPX-4) |
| LO-6.7 | **Unpublishing removes the page from the sitemap**; the public response is a 404 with noindex (SEO-13) |
| LO-6.8 | **Unpublishing is never used to hide an inconvenient Review.** Reviews are moderated on their own grounds (MOD-2) |

---

## 7. Corrections

### 7.1 Where corrections come from

| Source | Route | Account needed |
| --- | --- | --- |
| **User report** | Report control on every profile (C-15, TS-1) | **No** (TS-2) |
| **Staff discovery** | Found during review, re-verification or other work | — |
| **Business contact** | Contact page or direct contact with an Operator (C-18) | **No** |
| **Privacy request** | Data-subject route (`data-subject-rights-v1.0.md`) | **No** |
| **Analytics signal** | Stale queue, completeness gaps, zero-result queries (C-28) | — |

| ID | Rule |
| --- | --- |
| LO-7.1 | **Every route reaches the same queue and the same standard.** A business's request is not privileged over a Guest's report, and a sponsored business's is not privileged over either (IN-4) |
| LO-7.2 | **Guest reports carry no identity**, and the queue must not imply one exists (OPX-9.6) |
| LO-7.3 | **Businesses report through the contact route and receive no priority** (REP-7) |

### 7.2 Correction review

| ID | Rule | Source |
| --- | --- | --- |
| LO-7.4 | **A correction is verified before it is applied.** A report is a signal, not an instruction | REP-3 |
| LO-7.5 | **Changing a published field requires a reason and a source** | OPX-4.1, TR-52 |
| LO-7.6 | **The record captures field, previous value, new value, reason, source, actor and time** | COR-1, OPX-4.2 |
| LO-7.7 | **Material changes flag re-verification** — contact details, location, hours, name, closure | COR-2, OPX-4.3 |
| LO-7.8 | **A correction that resolves a report links to that report** | COR-4, OPX-9.5 |
| LO-7.9 | **Factual errors that materially mislead are prioritised above cosmetic ones.** Target times are **PENDING PILOT** | TS-6, COR-5 |
| LO-7.10 | **A stale edit produces a conflict message showing what changed and who changed it.** The edit is never silently overwritten | OPX-4.6 |
| LO-7.11 | **Correction history is visible on the record, newest first** | OPX-4.4 |

### 7.3 Correction publication and audit

| ID | Rule | Source |
| --- | --- | --- |
| LO-7.12 | **The search document updates synchronously**; the change is visible immediately | OPX-4.7 |
| LO-7.13 | **Caches and derived content are invalidated**, or the correction is cosmetic | PBD-10.6 |
| LO-7.14 | **Every report reaches a recorded outcome** — resolved, closed-unverified, or rejected | OPX-9.4, TS-4 |
| LO-7.15 | **Every correction is audited** | C-29 |
| LO-7.16 | **A correction that removes personal data is also a privacy action**, handled under `data-subject-rights-v1.0.md` §5 | DSR §5 |

---

## 8. Maintain and re-verify

| Signal | Source | Action |
| --- | --- | --- |
| Public reports | C-15 → C-26 | Triage, correct, close |
| Verification ageing | C-21 | Re-verification queue, oldest first |
| Completeness gaps | C-28 | Targeted follow-up |
| Zero-result queries | SRCH-8 | Coverage work (LO-1.2) |
| Direct business contact | C-18 | Correction or unpublication |
| Closure signals | Reports, field observation | Record a closure (COR-3) |

| ID | Rule |
| --- | --- |
| LO-8.1 | **The stale queue is derived at read time; no job is required** (OPX-5.3, TR-154) |
| LO-8.2 | **Re-verification follows the same recording rules as first verification** (LO-4.1) |
| LO-8.3 | **The interval is Open (D-08)** and read from configuration |
| LO-8.4 | **Maintenance is the loop that keeps the product true.** A directory that is only built is wrong within months |

---

## 9. The pilot

**The pilot is a launch-preparation activity, not a product feature**
(D-30, D-31). Nothing is built for it; it uses the real console.

### 9.1 What it is for

| ID | Statement |
| --- | --- |
| LO-9.1 | **To replace guesses with measurements.** Until it runs, Bulbula does not know how long a Listing takes, what proportion of businesses grant Permission, or what a realistic coverage number is |
| LO-9.2 | **Therefore no such number appears anywhere in this documentation set** (D-30n, **PENDING PILOT**) |

### 9.2 Scope

Twenty businesses in the coverage area, taken through the **full
lifecycle** from discovery to publication, by the people who will do the
work at scale, using the real console.

### 9.3 What it measures

The twelve measures fixed in `../10-product/listing-operations.md` §6.3:

| # | Measure |
| --- | --- |
| 1 | Approach-to-Permission conversion rate |
| 2 | Refusal reasons |
| 3 | Time per lifecycle stage |
| 4 | Time to a complete Listing |
| 5 | Field completeness actually achieved |
| 6 | Rework rate at quality review |
| 7 | Duplicate rate |
| 8 | Media captured per Listing |
| 9 | Verification method mix |
| 10 | Correction volume in the first period |
| 11 | Operator hours per Listing |
| 12 | Resulting projected rate per Operator-week |

Supporting observations recorded alongside: time to approach, collection
time, verification time, listing creation time, quality-review time,
photo and media effort, coordinate accuracy, second-contact frequency.

### 9.4 What the pilot decides

| Question | Decision |
| --- | --- |
| The numeric launch coverage threshold | **D-30n** — owner, from pilot data |
| A realistic verification interval | Input to **D-08** |
| A realistic correction turnaround | Input to **COR-5** |
| Whether the field set is collectable in one visit | Input to **D-50** refinement and **D-44** |
| Operator capacity planning | Operational |

### 9.5 Rules

| ID | Rule | Source |
| --- | --- | --- |
| LO-9.3 | The pilot **must** use the real process and the real console, not a spreadsheet substitute | PIL-1 |
| LO-9.4 | Pilot Listings are **real** and, once published, are part of the product | PIL-2 |
| LO-9.5 | Measurements are **recorded as observed, including unflattering ones** | PIL-3 |
| LO-9.6 | **No launch threshold may be set, quoted or planned against before the pilot completes** | PIL-4 |
| LO-9.7 | The pilot **does not change scope**; a scope problem it reveals is a decision for the register | PIL-5 |

### 9.6 The output

| ID | Rule |
| --- | --- |
| LO-9.8 | The output is **`docs/40-operations/pilot-benchmark-v1.0.md`**, produced **when the pilot runs** |
| LO-9.9 | **That document does not exist yet and MUST NOT be pre-filled with estimates.** This phase deliberately does not create it |
| LO-9.10 | **It is a launch-blocking artefact**, not a retrospective write-up |

---

## 10. Traceability

| This document | Traces to |
| --- | --- |
| §1 intake | `listing-operations.md` §2.1–2.3; OPX-3.1…3.6; D-03, D-40, D-43, D-50 |
| §2 collection | COL-1…COL-7; OPX-3.4, 3.9, 3.10, 3.11; D-04, D-18, D-25, D-44, D-51; PCP-1, PCP-2 |
| §3 provenance | DQ-1, DQ-3; COL-4; OPX-3.7, OPX-5.2; PCP-5 |
| §4 verification | C-21; OPX-5.1…5.3; D-08; ADV-9 |
| §5 quality review | `listing-operations.md` §2.7; OPX-5.4, 5.5; OC-9 |
| §6 publishing | TR-49; OPX-5.1, 5.6; OC-10; COR-3; SEO-7, SEO-13; ADV-10 |
| §7 corrections | COR-1…COR-5; OPX-4.1…4.7, OPX-9.4…9.6; TS-1, TS-2, TS-4, TS-6 |
| §8 maintain | C-21, C-26, C-28; SRCH-8; TR-154 |
| §9 pilot | D-30, D-30n, D-31; PIL-1…PIL-5; `listing-operations.md` §6 |

---

## 11. Open items

| ID | Item | Marker |
| --- | --- | --- |
| D-08 | Verification methods, tiers, evidence, interval | **Open (D-08)** — product decision |
| D-04 | Opening-hours model | **Open (D-04)** — product decision |
| D-43 | Permission record contents and retention | **Open (D-43)** — product decision |
| D-44 | Services, products and pricing representation | **Open (D-44)** — product decision |
| D-55 | Branch versus Business attribute boundary | **Open (D-55)** — product decision |
| D-56 / D-57 | Category catalogue and cardinality per Listing | **Open** — product decision |
| D-40 | Launch-area boundary | **Open (D-40)** — product decision |
| D-25 | Media storage, formats, limits | **Open (D-25)** — technical decision |
| D-09 | Completeness definition and weighting | **Open (D-09)** — product decision |
| D-35 | Whether guest suggestions become structured corrections | **Open (D-35)** — product decision |
| D-30n / D-31 | Launch threshold; capacity | **PENDING PILOT** |
| COR-5 | Target correction times | **PENDING PILOT** |
| L-18 | Photography of premises and people | **PENDING COUNSEL** |
| L-5 / D-46 | Whether refusals may be recorded, and withdrawal's legal force | **PENDING COUNSEL** |
| — | Whether a prospect is a tracked entity | **Open — implementation detail** |
| — | How the single-person review risk is recorded and reviewed | **Open — operational decision** |
| — | Operational guidance on what counts as "I found it online" (LO-3.6) | **Open — operational decision** |

---

## Decision references

D-02, D-03, D-04, D-06, D-08, D-09, D-18, D-25, D-30, D-30n, D-31, D-35,
D-40, D-43, D-44, D-46, D-50, D-51, D-55, D-56, D-57.
