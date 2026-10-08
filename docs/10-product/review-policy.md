# Review and Rating Policy

| | |
| --- | --- |
| **Document** | Review and Rating Policy |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The complete product policy for Reviews: who may write and read
them, what they contain, how they are moderated, how abuse is handled, and
what Bulbula will not do. Referenced by [`prd-v1.0.md`](prd-v1.0.md) §15 and
capabilities C-13, C-25, C-35.

**Discipline of this document.** Where an approved decision exists, the rule
is stated. Where it does not, the item is marked **Open —
implementation/product detail (D-34)** and left open. **No policy is invented
here.** A reader looking for a rule that is marked open must obtain a
decision, not a guess.

---

## 1. Who may participate

| Action | Who | Decision |
| --- | --- | --- |
| Read Reviews and rating summaries | Everyone, including Guests | D-05 |
| Write a Review | Authenticated Customers only | D-12 |
| Edit own Review | The author, within the permitted window | D-12; window **Open (D-34)** |
| Delete own Review | The author | D-12; semantics **Open (D-34)** |
| Report a Review | Authenticated Customers and Staff | `interaction-permissions.md` §3 |
| Moderate Reviews | Operator; Administrator for policy and appeals | D-12 |
| **Reply to a Review** | **Nobody in V1** — Businesses have no accounts | **D-12, D-54** |

### 1.1 Prohibited participation

| Rule | Statement |
| --- | --- |
| PART-1 | Staff **MUST NOT** write Reviews from staff accounts |
| PART-2 | Bulbula **MUST NOT** create, commission, incentivise or fabricate Reviews. Fake-review regulation targets fabrication and suppression alike (R-01) |
| PART-3 | A Business **MUST NOT** be able to pay for Review creation, removal, reordering or suppression (PRD ADV-9, TS-14) |
| PART-4 | Reviews **MUST NOT** be solicited selectively from satisfied customers only |

---

## 2. Structure of a Review

| Component | V1 position | Decision |
| --- | --- | --- |
| **Rating** | Required. A numeric score on a fixed scale | D-12 |
| Rating scale | Five points, whole numbers | **[P]** — conventional and consistent with structured-data expectations (R-15); confirm under D-34 |
| **Review text** | Optional, free text | D-12 |
| Text limits | Minimum and maximum length | **Open (D-34)** |
| **Author attribution** | The Customer's display name | D-12 |
| **Date** | Submission date, and edit date where edited | — |
| **Photos** | **Not in V1** | **Deferred (D-36)** |
| **Helpful voting** | **Not in V1** | **Deferred (D-37)** |
| **Business reply** | **Not in V1** | **D-12, D-54** |
| Rating-only Reviews (no text) | Whether permitted | **Open (D-34)** |
| Structured sub-ratings (service, value, …) | **Not in V1** — one overall Rating only | D-12 |

### 2.1 Subject of a Review

| Question | Status |
| --- | --- |
| Is a Review attached to a **Business** or to a **Branch**? | **Open — implementation/product detail (D-34)** |
| How a multi-branch rating summary is composed | Follows from D-34; **Open** |

This is the single most consequential open item in this document: it affects
the data model, the profile layout and the rating summary. It **MUST** be
resolved before the UX and data-model phase and recorded in the register.

---

## 3. Rating summary

| ID | Rule |
| --- | --- |
| SUM-1 | A rating summary is displayed only where at least one published Review exists |
| SUM-2 | A Business with no published Reviews **MUST NOT** display a rating value, a default value, or a zero |
| SUM-3 | The summary **MUST** state the number of Reviews it is based on |
| SUM-4 | Only **published** Reviews contribute; pending, rejected, removed and deleted Reviews do not |
| SUM-5 | The summary **MUST** update when a Review is published, removed or deleted |
| SUM-6 | The computation method (plain mean, or a method that accounts for volume) is **Open (D-34)**. Until resolved, nothing downstream may assume a weighting |
| SUM-7 | A displayed rating **MUST** match any rating published in structured data (R-15, SEO-6) |

---

## 4. Review lifecycle and states

```text
submitted → [moderation] → published
                   ↓
               rejected  (author notified, reason given)

published → edited → [moderation] → published
published → removed  (by Staff, with policy basis)
published → deleted  (by the author)
```

| State | Meaning | Visible to |
| --- | --- | --- |
| **Pending** | Submitted, awaiting a moderation outcome | Author only |
| **Published** | Publicly visible, contributes to the summary | Everyone |
| **Rejected** | Refused at moderation; never published | Author only, with reason |
| **Removed** | Taken down by Staff after publication | Author only, with reason |
| **Deleted** | Withdrawn by the author | Nobody publicly |

| ID | Rule |
| --- | --- |
| LC-1 | Every state change **MUST** record actor, timestamp and reason (C-29) |
| LC-2 | The author **MUST** be notified of rejection or removal, with the reason (C-39) |
| LC-3 | Rejected and removed Reviews **MUST NOT** contribute to the rating summary |
| LC-4 | Whether moderation occurs **before** or **after** publication is **Open (D-34)** — the states above support either, and the product **MUST NOT** hard-code an assumption before it is decided |
| LC-5 | Whether an edit returns a Review to moderation is **Open (D-34)** |
| LC-6 | Whether author deletion is a hard delete or a withdrawal that retains an internal record is **Open (D-34)**, and interacts with the legal question of retention after account deletion (**PENDING COUNSEL**, L-21, D-46b) |

---

## 5. Moderation

### 5.1 Grounds for rejection or removal

A Review may be rejected or removed only on a published ground:

| Ground | Description |
| --- | --- |
| Not about the Business | Content concerning a different business, or no business at all |
| No experience | Content that is clearly not based on an interaction with the Business |
| Abuse or harassment | Threats, slurs, targeted harassment of staff or other Users |
| Personal information | Discloses a person's personal information, including a named individual's contact details |
| Illegal content | Content that is unlawful to publish |
| Spam or promotion | Advertising, links, or repeated promotional content |
| Conflict of interest | Authored by the Business, a competitor, or a related party |
| Fabrication indicators | Patterns consistent with coordinated or purchased reviews |
| Unreadable | Content with no interpretable meaning |

| ID | Rule |
| --- | --- |
| MOD-1 | A moderation outcome **MUST** cite one of the published grounds |
| MOD-2 | **A negative Review is not a ground for removal.** Dissatisfaction, criticism and low ratings are legitimate content |
| MOD-3 | A Business's objection, commercial relationship or advertising spend is **not** a ground (TS-14, ADV-9) |
| MOD-4 | Moderators **MUST NOT** edit the content of a Review. The only outcomes are publish, reject and remove |
| MOD-5 | Decisions **MUST** be recorded with the policy basis, the actor and the time (C-25, C-29) |
| MOD-6 | Appeals are handled by an Administrator; the appeal mechanism and any time limit are **Open (D-34)** |
| MOD-7 | A public summary of these grounds **MUST** be published as a static page (C-18) |

### 5.2 Defamation and legal demands

Legal removal demands and defamation complaints are escalated to an
Administrator and handled under a process that is **PENDING COUNSEL**
(L-15). This document **MUST NOT** be read as establishing that process.

---

## 6. Reporting a Review

| ID | Rule |
| --- | --- |
| REP-1 | Any authenticated Customer may report a published Review (`interaction-permissions.md` §3) |
| REP-2 | A report **MUST** require a reason selected from the published grounds, with optional detail |
| REP-3 | Reported Reviews enter the moderation queue (C-25); **a report alone MUST NOT unpublish a Review** |
| REP-4 | Reporter identity **MUST NOT** be disclosed to the author or to the Business (TS-10) |
| REP-5 | Repeated unfounded reporting is itself abuse and **MUST** be actionable |
| REP-6 | Every report **MUST** reach a recorded outcome (C-26) |
| REP-7 | Businesses, having no account, report Reviews through the contact route (`interaction-permissions.md` §5); such reports receive no priority over any other |

---

## 7. Anti-abuse

The integrity threats are: fake positive Reviews bought by a Business, fake
negative Reviews placed by a competitor, review bombing, and staff or
commercial interference.

| ID | Control | Status |
| --- | --- | --- |
| AB-1 | Authentication is required to write (D-12) | **[C]** |
| AB-2 | Rate limits on submissions per Customer per period | **[C]** required; values **Open (D-34)** |
| AB-3 | One Review per Customer per subject, with editing rather than re-posting | **[C]** required; the *subject* (Business or Branch) is **Open (D-34)** |
| AB-4 | Duplicate and near-duplicate text detection across Reviews | **[C]** required; method is a TRD matter |
| AB-5 | Anomaly detection: bursts of Reviews on one Business, or from one account, surfaced to Staff | **[C]** required; thresholds **Open (D-34)** |
| AB-6 | Staff review of anomalous patterns before summaries shift materially | **[C]** |
| AB-7 | Separation of commercial relationships from moderation — **Open (D-39)**, and **MUST** be resolved before the first paid Campaign | Open |
| AB-8 | Account-age or activity requirements before a first Review | **[P]** — not approved; evaluate under D-34 |
| AB-9 | No automated removal; adverse outcomes are taken by a person | **[C]** |

| ID | Prohibition |
| --- | --- |
| AB-10 | Bulbula **MUST NOT** suppress or down-rank Reviews to protect a commercial relationship. Suppression carries the same regulatory exposure as fabrication (R-01) |
| AB-11 | Bulbula **MUST NOT** publish Reviews it knows to be fabricated, in either direction |
| AB-12 | Bulbula **MUST NOT** offer removal, reordering or "reputation management" as a product or a favour |

---

## 8. Business response policy

**V1 position: Businesses cannot respond to Reviews.** There is no business
account from which to respond (D-54), and D-12 confirms no owner replies in
V1.

| Consequence | Handling in V1 |
| --- | --- |
| A Business disputes a factual claim in a Review | Report through the contact route (REP-7); moderated against §5.1 on its merits only |
| A Business wants to give its side publicly | **Not possible in V1.** This is a known, accepted limitation |
| A Business asks for removal because the Review is negative | Refused (MOD-2) |

**Future.** Business responses are a **Future** capability, conditional on
business accounts with mandatory Administrator approval of every submission
(D-54). No V1 artefact may anticipate them: no reply field, no placeholder,
no "owner has not responded" state (PRD H-4).

---

## 9. Open items

Every item below is **Open — implementation/product detail (D-34)** unless
marked otherwise. Each **MUST** be resolved through the decision process and
recorded in the register before the UX and data-model phase.

| # | Open item |
| --- | --- |
| 1 | Is a Review attached to a Business or to a Branch? (§2.1) |
| 2 | Rating scale confirmation (currently **[P]** five points) |
| 3 | Minimum and maximum review-text length |
| 4 | Whether rating-only Reviews are permitted |
| 5 | Edit window length, and whether an edit returns the Review to moderation |
| 6 | Whether author deletion is a hard delete or a withdrawal (interacts with **L-21 / D-46b**, PENDING COUNSEL) |
| 7 | Pre-publication versus post-publication moderation (LC-4) |
| 8 | Rating summary computation method (SUM-6) |
| 9 | Rate-limit values (AB-2) and anomaly thresholds (AB-5) |
| 10 | Whether an account-age or activity requirement applies (AB-8, currently **[P]**) |
| 11 | Appeal mechanism and any time limit (MOD-6) |
| 12 | Default ordering of Reviews on a profile |
| 13 | Treatment of published Reviews after the author deletes their account (**L-21 / D-46b**, PENDING COUNSEL) |
| 14 | Commercial-editorial separation controls (**D-39**) |

---

## Decision references

D-05, D-12, D-34, D-36, D-37, D-39, D-46, D-48, D-54.
