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
is stated. Where it does not, the item is marked open and left open. **No
policy is invented here.** A reader looking for a rule that is marked open
must obtain a decision, not a guess.

**D-34 is approved (2026-10-07).** The Review subject, rating scale,
rating-only support, edit window, edit-returns-to-moderation rule, deletion
semantics, moderation order, rating-summary computation, default ordering and
the account-age question are **settled** and are stated below as rules. What
remains open is listed in §9 and is deliberately narrow: configuration values
and the **PENDING COUNSEL** retention questions.

---

## 1. Who may participate

| Action | Who | Decision |
| --- | --- | --- |
| Read Reviews and rating summaries | Everyone, including Guests | D-05 |
| Write a Review | Authenticated Customers only | D-12 |
| Edit own Review | The author, **within 30 days of creation**; the edit re-enters moderation | D-12, D-34 |
| Delete own Review | The author, at any time; deletion is a **withdrawal**, not destructive erasure | D-12, D-34 |
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
| **Rating** | **Required.** An integer from **1 to 5** | D-12, D-34 |
| Rating scale | **1–5, whole numbers.** Consistent with structured-data expectations (R-15) | D-34 |
| **Review text** | **Optional**, free text | D-12, D-34 |
| Text limits | Maximum length is an **implementation/configuration parameter**; no value is fixed by D-34 and none is invented here | D-34; **Open — implementation detail** |
| **Author attribution** | The Customer's display name | D-12 |
| **Date** | Submission date, and edit date where edited | — |
| **Photos** | **Not in V1** | **Deferred (D-36)** |
| **Helpful voting** | **Not in V1** | **Deferred (D-37)** |
| **Business reply** | **Not in V1** | **D-12, D-54** |
| Rating-only Reviews (no text) | **Permitted.** A Rating alone is a valid Review | D-34 |
| Structured sub-ratings (service, value, …) | **Not in V1** — one overall Rating only | D-12 |

### 2.1 Subject of a Review

| Question | Position |
| --- | --- |
| Is a Review attached to a **Business** or to a **Branch**? | **A Review belongs to a Branch.** Never directly to a Business | D-34, D-55 |
| How a multi-branch rating summary is composed | A Business-level summary is an **aggregate derived from its Branches' Reviews** (§3) | D-34 |

| ID | Rule |
| --- | --- |
| SUBJ-1 | A Review **MUST** reference exactly one **Branch** |
| SUBJ-2 | A single-location Business is **not** a special case: it has one Branch, and its Reviews attach there |
| SUBJ-3 | A Business-level rating summary is **derived**, never stored as the primary truth |
| SUBJ-4 | A Customer **MUST** be able to tell which Branch they are reviewing, and which Branch a published Review concerns |

---

## 3. Rating summary

| ID | Rule |
| --- | --- |
| SUM-1 | A rating summary is displayed only where at least one published Review exists |
| SUM-2 | A Business with no published Reviews **MUST NOT** display a rating value, a default value, or a zero |
| SUM-3 | The summary **MUST** state the number of Reviews it is based on |
| SUM-4 | Only **published** Reviews contribute; pending, rejected, removed and deleted Reviews do not |
| SUM-5 | The summary **MUST** update when a Review is published, removed or deleted |
| SUM-6 | The computation method is the **arithmetic mean of currently Published Review ratings** for the applicable Branch or Business scope. **No weighting of any kind** — not by volume, recency, author or any other factor (D-34) |
| SUM-7 | A displayed rating **MUST** match any rating published in structured data (R-15, SEO-6) |
| SUM-8 | A **Branch** summary covers that Branch's Published Reviews. A **Business** summary aggregates the Published Reviews of all its Branches (D-34, D-55) |
| SUM-9 | Default public ordering of Reviews is **newest Published Review first**. Any alternative ordering requires a later decision (D-34) |

---

## 4. Review lifecycle and states

```text
submitted → pending → [moderation] → published
                           ↓
                       rejected        (author notified, reason given;
                                        never public)

published → edited (within 30 days) → pending → [moderation] → published
published → removed   (by Staff, with policy basis)
published → deleted   (withdrawn by the author; public visibility ceases)
```

**Moderation precedes publication (LC-4).** Nothing a Customer submits is
public before a moderator approves it.

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
| LC-4 | **Moderation is pre-publication.** A submitted Review enters `Pending` and becomes `Published` only on moderation approval. A Rejected Review **MUST NEVER** become public. Post-publication removal remains available for later moderation cases (D-34) |
| LC-5 | **An edit returns the Review to moderation.** The edited Review re-enters `Pending` and becomes `Published` again only on approval (D-34) |
| LC-6 | **Author deletion is a withdrawal, not a destructive erasure.** Public visibility ceases immediately; an internal record may remain where retention, audit, abuse prevention or legal obligation requires it. **How long such a record is retained is PENDING COUNSEL (L-21, D-46)** and is not decided by D-34 |
| LC-7 | A Customer may hold **at most one active Review per Branch**. A second submission for the same Branch is an **edit** of the existing Review, never a new one (D-34, AB-3) |
| LC-8 | The edit window is **30 days from creation** (D-34). After it closes the Review stands as published; the author may still withdraw it under LC-6 |

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
| MOD-6 | Appeals are handled by an Administrator. **D-34 confirms the Administrator as the appeal authority**; the appeal mechanism and any time limit remain an **operational configuration detail** and no deadline is invented here |
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
| AB-2 | Rate limits on submissions per Customer per period | **[C]** required; values are **configuration**, not schema, and are deliberately unset (D-34) |
| AB-3 | **One active Review per Customer per Branch**, edited rather than re-posted | **[C]** required; the subject is the **Branch** (D-34, §2.1) |
| AB-4 | Duplicate and near-duplicate text detection across Reviews | **[C]** required; method is a TRD matter |
| AB-5 | Anomaly detection: bursts of Reviews on one Branch or Business, or from one account, surfaced to Staff | **[C]** required; thresholds are **configuration**, not schema, and are deliberately unset (D-34) |
| AB-6 | Staff review of anomalous patterns before summaries shift materially | **[C]** |
| AB-7 | Separation of commercial relationships from moderation — **Open (D-39)**, and **MUST** be resolved before the first paid Campaign | Open |
| AB-8 | Account-age or activity requirement before a first Review | **None in V1.** D-34 imposes no minimum account age; authentication (AB-1), rate limits (AB-2) and anomaly detection (AB-5) remain the controls |
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

**D-34 closed thirteen of the fourteen items this section previously
carried.** What follows is what genuinely remains.

### 9.1 Settled by D-34 on 2026-10-07

| Former item | Settled position |
| --- | --- |
| Review subject | **Branch** (§2.1, SUBJ-1) |
| Rating scale | **Integer 1–5, required** (§2) |
| Rating-only Reviews | **Permitted** (§2) |
| Edit window | **30 days from creation** (LC-8) |
| Edit returns to moderation | **Yes** (LC-5) |
| Author deletion | **Withdrawal / soft deletion** (LC-6) |
| Moderation order | **Pre-publication** (LC-4) |
| Rating summary computation | **Arithmetic mean of Published Reviews, no weighting** (SUM-6) |
| Default ordering | **Newest Published first** (SUM-9) |
| Account-age requirement | **None** (AB-8) |
| Appeal authority | **Administrator** (MOD-6) |
| One Review per Customer per subject | **Per Branch** (LC-7, AB-3) |
| Multi-branch summary composition | **Aggregate of Branch Reviews** (SUM-8) |

### 9.2 Still open

| # | Open item | Status |
| --- | --- | --- |
| 1 | Maximum review-text length | **Open — implementation detail.** Configuration; D-34 deliberately sets no value |
| 2 | Rate-limit values (AB-2) and anomaly thresholds (AB-5) | **Open — implementation detail.** Configuration, not schema |
| 3 | Appeal mechanism and any time limit (MOD-6) | **Open — operational decision.** No legal or product deadline is invented |
| 4 | Duplicate-detection method (AB-4) | **Open — technical decision** |
| 5 | Retention of a withdrawn Review's internal record, and the treatment of Reviews after the author deletes their **account** | **PENDING COUNSEL** (L-21, D-46). D-34 fixes the *mechanism*, not the *period* |
| 6 | Legal removal and defamation process (§5.2) | **PENDING COUNSEL** (L-15) |
| 7 | Commercial-editorial separation controls | **Open (D-39)** — **D-34 does not close D-39** |

---

## Decision references

D-05, D-12, D-34, D-36, D-37, D-39, D-46, D-54, D-55.
