# Data Subject Rights

| | |
| --- | --- |
| **Document** | Data Subject Rights — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

The rights Bulbula must be able to honour in V1, and the capabilities
the system needs to honour them: being informed, access, rectification,
erasure, objection, restriction, portability, automated-decision
rights, and post-mortem rights — plus intake, identity verification,
records and the limits that must be stated honestly.

**Out of scope.** Notice content (`privacy-notice-requirements-v1.0.md`),
periods (`data-retention-v1.0.md`), breach handling
(`incident-response-v1.0.md` §7).

## Authority

**Requirements, not legal procedure.** The statutory rights are quoted
from the enacted text and classified. **Response windows, exceptions,
refusal grounds and identity-verification standards are PENDING COUNSEL
(L-7).** No procedure here is a legal determination.

> **The hardest fact about V1:** the people most likely to need these
> rights — business owners whose personal phone number is published, and
> individuals named in a Review — **have no account** (D-02, D-54).
> Every route must therefore work without one.

---

## 1. The rights

| Art. | Right | Applies to Bulbula | Status |
| --- | --- | --- | --- |
| 24 | **To be informed** | Yes — 15 enumerated items | **Open (L-6)** |
| 25 | **Access** | Yes | **Open (L-7)** |
| 26 | Access exceptions | Yes | **PENDING COUNSEL** |
| 27 | **Rectification** | Yes — the most frequently exercised in practice | **Open** |
| 28 | **Erasure** | Yes | **PENDING COUNSEL (L-21, D-46)** — the Review mechanism is settled (D-34, DSR-6.3); the retention period is not |
| 29 | **Object** | Yes | **Open** |
| 30 | **Restriction** | Yes | **Open** |
| 31 | **Automated decisions** | Analysis in PG §8 | **PENDING COUNSEL** |
| 32 | **Portability** | Yes — Customer-provided data | **Open (L-7)** |
| 23 | **Post-mortem (10 years)** | Yes | **PENDING COUNSEL** |

| ID | Rule |
| --- | --- |
| DSR-1.1 | **A right that cannot be exercised does not exist.** The test is whether a real person can actually get the outcome |
| DSR-1.2 | **Rights apply to every data subject class** (DI §2), including business contacts, reporters, staff and people in photographs |
| DSR-1.3 | **Art. 25(1) says access is free of charge.** No fee is charged for any right in V1 |
| DSR-1.4 | **Art. 25(2) and Art. 32(2) both require response "without excessive delay".** The Proclamation uses that standard rather than a fixed day count for these rights; **the operational window is PENDING COUNSEL (L-7)** |
| DSR-1.5 | **No fixed day count is published until counsel sets one.** Inventing "30 days" would be a commitment Bulbula has not verified it can meet |

---

## 2. Intake

| ID | Requirement | Source |
| --- | --- | --- |
| DSR-2.1 | **A single, published contact route exists for privacy requests** | Art. 24(1)(a) |
| DSR-2.2 | **No account is required to make a request** | DSR intro |
| DSR-2.3 | The route is **reachable from the privacy notice and from any listing page** carrying personal data | TS-1 |
| DSR-2.4 | **A request need not use the word "right", cite an article, or use a form.** "Please take my number off this page" is a valid erasure or objection request | Art. 12 |
| DSR-2.5 | **Every request is logged on receipt** with date, channel, subject, what is asked | DSR §9 |
| DSR-2.6 | **Receipt is acknowledged** | Art. 12 |
| DSR-2.7 | **Staff are trained to recognise a request**, including one arriving through a support or correction channel | PG-10.6 |
| DSR-2.8 | **A request is never silently dropped**, even when refused | Art. 26(2) |
| DSR-2.9 | The contact route must not itself collect more than it needs to answer | PBD-4.1 |
| DSR-2.10 | **Requests may arrive in Amharic or English** and both are handled | D-18 |

---

## 3. Identity verification

| ID | Rule | Source |
| --- | --- | --- |
| DSR-3.1 | **Verification is necessary** — disclosing someone's data to an impostor is itself a breach | THR-21 |
| DSR-3.2 | **Verification must be proportionate.** Demanding an ID document to remove a phone number collects far more data than the request concerns | PBD-1.1 |
| DSR-3.3 | **For an account holder**, control of the registered email is ordinarily sufficient | AS-3 |
| DSR-3.4 | **For a non-account holder**, verification is harder and must be judged case by case — the standard rises with the sensitivity of the outcome | DSR-3.5 |
| DSR-3.5 | **Risk-proportionate ladder**: removing a published personal phone number is low-risk to get wrong in the subject's favour; disclosing a full copy of someone's data is high-risk | Art. 26 |
| DSR-3.6 | **Identity documents collected for verification are deleted immediately after verification**, never retained | Art. 15 |
| DSR-3.7 | **Verification must not become a deterrent.** A process so onerous that people give up is a denial of the right | Art. 12 |
| DSR-3.8 | **When in doubt on an erasure-type request, act in the subject's favour.** Removing a contact point wrongly is recoverable; publishing it wrongly is not | PBD-1.3 |
| DSR-3.9 | **The verification standard is PENDING COUNSEL (L-7)** | L-7 |

---

## 4. Access — Art. 25

Art. 25(1): on request, free of charge, the controller shall confirm
whether personal data is being processed and provide the data and the
Art. 24 information. Art. 25(2): at reasonable intervals, without
excessive delay, **electronically or in hard copy** as requested.

| ID | Capability needed | Status |
| --- | --- | --- |
| DSR-4.1 | Retrieve **all** personal data about a subject across account, content, operational and audit stores | **Open — implementation detail** |
| DSR-4.2 | Include data not held under an account — a personal contact point on a listing, a mention in a Review | **Open — implementation detail** |
| DSR-4.3 | Present it in a form a non-technical person can understand | **Open — implementation detail** |
| DSR-4.4 | Supply the accompanying Art. 24 information | PNR |
| DSR-4.5 | Offer **electronic or hard copy** per the request (Art. 25(2)) | Confirmed requirement |
| DSR-4.6 | **Do not disclose other people's data in the response** — a Review may name a third party; a report contains a reporter's identity | **Binding** |
| DSR-4.7 | Redact where necessary and **record that redaction occurred and why** | Art. 26 |

| ID | Rule |
| --- | --- |
| DSR-4.8 | **DSR-4.6 is absolute.** Reporter identity is never disclosed to the subject of a report (TS-8) |
| DSR-4.9 | **Art. 26 exceptions exist** — including where disclosure would affect another person's rights — and their application is **PENDING COUNSEL** |
| DSR-4.10 | **Art. 26(2) requires a refusal to be in writing with detailed reasons.** A silent or vague refusal is itself a violation |
| DSR-4.11 | **"Reasonable intervals" (Art. 25(2)) permits declining an abusive cadence** of repeated identical requests — but the bar is **PENDING COUNSEL** |
| DSR-4.12 | **Logs are included only where they actually contain the subject's data** and are redacted of other subjects (RET-7.5) |

---

## 5. Rectification — Art. 27

Art. 27 requires correction of inaccurate or incomplete data, and
**notification to third parties to whom the data was disclosed within
the preceding year**.

| ID | Rule | Source |
| --- | --- | --- |
| DSR-5.1 | **A correction route exists for anyone, with no account** — this already exists as the listing correction flow | TS-1…TS-5 |
| DSR-5.2 | **Corrections are actioned promptly**; stale wrong data about a real business is the directory's core failure mode | TS-2 |
| DSR-5.3 | **A correction is auditable**: who requested, who actioned, what changed | C-29 |
| DSR-5.4 | **Caches, search indexes and derived content are updated** or the correction is cosmetic | PBD-10.6 |
| DSR-5.5 | **Art. 14(3) requires recording that accuracy is contested** where it cannot be resolved immediately | Art. 14 |
| DSR-5.6 | **Art. 27(2)'s one-year downstream notification duty requires knowing who was told.** Bulbula's main disclosure is publication to the world, which cannot be individually notified — **PENDING COUNSEL on what is required in that case** | Art. 27(2) |
| DSR-5.7 | **A Review is an opinion.** Correcting a factual error inside a Review is not the same as deleting an unwelcome opinion; the distinction is a moderation question (L-15) | L-15 |

---

## 6. Erasure — Art. 28

| ID | Rule | Source |
| --- | --- | --- |
| DSR-6.1 | **A Customer can delete their account**, and the route is discoverable, not hidden | PRIV-5 |
| DSR-6.2 | **Deletion removes account data, Saves and session records** | RET-4.1, RET-4.12 |
| DSR-6.3 | **The option taken is withdrawal** (D-34): the Review stops being publicly visible and leaves every rating summary, while an internal record may be retained for retention, audit, abuse and legal purposes. This must be **told to the user before they confirm**, in those terms | D-34 |
| DSR-6.3a | **It is not yet erasure.** How long the withdrawn record is retained, and whether it is finally anonymised or destroyed, is **PENDING COUNSEL (L-21, D-46)**. The product **must not** describe withdrawal as permanent deletion, and **must not** state a period it does not have | L-21, D-46 |
| DSR-6.3b | A Review concerns a **Branch** (D-34, D-55); an erasure request about a Review is handled against that Review, not against the Business as a whole | D-55 |
| DSR-6.4 | **Removing a published personal contact point is an erasure-type request** and must work with no account | DSR-2.2 |
| DSR-6.5 | **Removing an identifiable person from a photograph** is honoured by removing or replacing the photograph | PBD-9.9 |
| DSR-6.6 | **Deletion propagates** to caches, derived media, search indexes and exports | PBD-11.6 |
| DSR-6.7 | **Art. 28(2): where the data was made public, the controller shall inform third parties processing it** — Bulbula requests de-indexing where it can, and **states honestly that it cannot purge third-party caches** | PBD-10.8 |
| DSR-6.8 | **Backups are the honest limit** and are disclosed as such in the deletion confirmation | RET-8.7 |
| DSR-6.9 | **Art. 50's destruction standard applies**: no reconstruction in intelligible form | Art. 50 |
| DSR-6.10 | **What survives deletion is enumerated and minimal** — staff audit of actions taken, statutory records, incident records — and the subject is told what survives and why | SO-4.4 |
| DSR-6.11 | **Art. 28 exceptions exist** and their application is **PENDING COUNSEL** |
| DSR-6.12 | **Deletion is confirmed to the subject**, stating what was deleted, what survives, and the backup limit | DSR-6.8 |

---

## 7. Objection and restriction — Art. 29, Art. 30

| ID | Rule | Source |
| --- | --- | --- |
| DSR-7.1 | **Art. 29(2): objection to processing for direct marketing is absolute** — no balancing | Art. 29(2) |
| DSR-7.2 | **Bulbula sends no marketing in V1** (D-24), so the absolute right is satisfied by design. **If marketing is ever introduced, an opt-out is a legal precondition, not a feature** | D-24 |
| DSR-7.3 | **Objection to legitimate-interests processing requires a balancing assessment** — counsel's call, recorded | Art. 29 |
| DSR-7.4 | **Restriction (Art. 30) means the data is stored but not otherwise processed.** The system must be able to suspend publication without deleting | DSR-7.5 |
| DSR-7.5 | **Unpublish-without-delete must exist as a capability** — it is also how a contested accuracy case is handled pending resolution (DSR-5.5) | TS-9 |
| DSR-7.6 | **A restricted item is clearly marked internally** so it is not accidentally republished | SO-4.1 |
| DSR-7.7 | **Lifting a restriction is a recorded decision** | SO-4.1 |

---

## 8. Portability — Art. 32

Art. 32(1): the right to receive personal data **provided to** the
controller in a **structured, commonly used and machine-readable
format**, and to transmit it to another controller. Art. 32(2): free of
charge and without excessive delay.

| ID | Rule |
| --- | --- |
| DSR-8.1 | **Portability covers data the subject provided** — email, display name, Reviews they wrote, Saves — **not** data Bulbula derived or observed |
| DSR-8.2 | **A machine-readable export format is required.** JSON or CSV satisfies "structured, commonly used and machine-readable" |
| DSR-8.3 | **Free of charge** (Art. 32(2)) |
| DSR-8.4 | **The export must not contain other people's data** (DSR-4.6) |
| DSR-8.5 | **The export is delivered securely**, not as an unauthenticated public link | THR-21 |
| DSR-8.6 | **An export file is itself a concentrated personal-data artefact**: short-lived, access-controlled, and deleted after collection | RET-2.1 |
| DSR-8.7 | **Direct controller-to-controller transmission** (Art. 32(1)) is **PENDING COUNSEL** as to whether it is required where technically feasible |
| DSR-8.8 | **Generating an export is a privileged, audited action** | PG-10.8 |

---

## 9. Records

| ID | Requirement | Source |
| --- | --- | --- |
| DSR-9.1 | **Every request is recorded**: identifier, date received, channel, subject class, right invoked, verification performed, outcome, date closed, who handled it | Art. 52 |
| DSR-9.2 | **Refusals record the ground and the written reasons given** | Art. 26(2) |
| DSR-9.3 | **The record is the evidence of compliance.** Art. 52(2) requires demonstrability | Art. 52 |
| DSR-9.4 | **The request record is itself personal data** and has a retention period (**PENDING COUNSEL, L-21**) | RET §4 |
| DSR-9.5 | **Volumes and outcomes are reviewed periodically.** A pattern of the same request type signals a design problem, not a workload problem | SO §14 |
| DSR-9.6 | **A request that revealed a systemic failure gets a post-mortem** on the same terms as an incident — blameless, with owned actions | IR §10 |
| DSR-9.7 | **Repeated requests to remove the same personal contact point indicate PCP-2 is being applied too loosely**, and the design is revisited |

---

## 10. Limits stated honestly

| ID | Limit | How it is communicated |
| --- | --- | --- |
| DSR-10.1 | **Backups retain deleted data until they expire** | Stated in the notice and the deletion confirmation |
| DSR-10.2 | **Third-party search caches are outside Bulbula's control** | Stated; de-indexing requested where possible |
| DSR-10.3 | **Content copied by scrapers cannot be recalled** (THR-08) | Stated where relevant |
| DSR-10.4 | **Some records survive deletion for accountability or statutory reasons** | Enumerated (DSR-6.10) |
| DSR-10.5 | **Reporter identity cannot be disclosed**, even in an access request | Stated with the reason |
| DSR-10.6 | **Processors hold copies subject to their own contractual periods** | Stated; Art. 50(2) notification applies |
| DSR-10.7 | **Verification may be required**, and why | Stated at intake |

| ID | Rule |
| --- | --- |
| DSR-10.8 | **No overstatement.** Bulbula never promises complete erasure it cannot deliver |
| DSR-10.9 | **No understatement either.** A limit is not used as a reason to avoid doing what can be done |

---

## 11. Unresolved items

| ID | Item | Status |
| --- | --- | --- |
| **L-7** | **Rights procedures, response windows, verification standards, refusal grounds** | **PENDING COUNSEL** — the central gap |
| L-6 | Notice content, including how rights are described | **PENDING COUNSEL** |
| L-21 / D-46 | **Retention period** for a withdrawn Review, and whether it ends in anonymisation or destruction | **PENDING COUNSEL.** D-34 closed the mechanism on 2026-10-07 and DSR-6.3 now states it |
| L-21 | Retention of request records | **PENDING COUNSEL** |
| L-15 | Review takedown and liability — interacts with erasure | **PENDING COUNSEL** |
| L-18 | Photography — interacts with DSR-6.5 | **PENDING COUNSEL** |
| — | Art. 27(2) downstream notification where data was published to the world | **PENDING COUNSEL** |
| — | Art. 32(1) direct controller-to-controller transmission | **PENDING COUNSEL** |
| — | Art. 23 post-mortem rights and how an heir is verified | **PENDING COUNSEL** |
| — | The published privacy contact address | **Open — implementation detail** |
| — | Whether export is self-service or staff-operated in V1 | **Open — product decision** |
| — | Whether unpublish-without-delete already exists for every content type | **Open — implementation detail** |

---

## Legal and regulatory references

| Reference | Provision | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, Art. 23 | Rights persist **ten years** after death; heirs may invoke | Confirmed — statute/regulation |
| Art. 24 | Right to be informed — fifteen items | Confirmed — statute/regulation |
| Art. 25 | Access — **free of charge**, reasonable intervals, without excessive delay, electronic or hard copy | Confirmed — statute/regulation |
| Art. 26 | Access exceptions; **refusal in writing with detailed reasons** | Confirmed — statute/regulation |
| Art. 27 | Rectification; **notify third parties informed in the preceding year** | Confirmed — statute/regulation |
| Art. 28 | Erasure and exceptions; **inform third parties where data was made public** | Confirmed — statute/regulation |
| Art. 29 | Objection; **direct-marketing objection is absolute** | Confirmed — statute/regulation |
| Art. 30 | Restriction of processing | Confirmed — statute/regulation |
| Art. 31 | Automated individual decision-making | Confirmed — statute/regulation |
| Art. 32 | Portability — structured, commonly used, machine-readable, free | Confirmed — statute/regulation |
| Art. 14(3) | Recording contested accuracy | Confirmed — statute/regulation |
| Art. 50 | Destruction preventing reconstruction | Confirmed — statute/regulation |
| Art. 52 | Accountability — records as evidence | Confirmed — statute/regulation |
| **Response windows, verification standards, exceptions** | — | **PENDING COUNSEL (L-7)** |
| Review takedown and defamation | Outside Proclamation 1321/2024 | **PENDING COUNSEL (L-15)** |

---

## Decision references

D-02, D-18, D-24, D-34, D-46, D-54, D-55.
