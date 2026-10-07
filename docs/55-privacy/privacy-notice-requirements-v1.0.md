# Privacy Notice Requirements

| | |
| --- | --- |
| **Document** | Privacy Notice Requirements — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

What Bulbula's privacy notice must contain, where and when information
must be given, the standards it must meet, what it must not say, and
what remains blocked until counsel answers.

**This document is requirements only.**

> **It is not a privacy notice and must not be used as one.** No text
> here is publishable wording. The final notice is drafted or approved
> by **counsel** (L-6) and published by the **owner**. Publishing a
> notice drafted by engineering would be a legal statement made by
> people unqualified to make it — and an inaccurate notice is itself a
> breach of Art. 12 and Art. 24.

## Authority

Below counsel on every legal question; below the owner on every product
question. **Decides nothing; specifies what the notice must satisfy.**

---

## 1. Purpose

| ID | Statement | Source |
| --- | --- | --- |
| PNR-1.1 | **Art. 24 creates a right to be informed**, with fifteen enumerated items the controller must supply | Art. 24(1) |
| PNR-1.2 | **Art. 12 requires fairness and transparency** — concise, transparent, intelligible, easily accessible, in clear and plain language | Art. 12 |
| PNR-1.3 | **The notice is not the compliance.** It describes processing; it cannot cure processing that should not happen | PBD-1.4 |
| PNR-1.4 | **An inaccurate notice is worse than no notice.** It misleads data subjects and misrepresents Bulbula to the Authority | PNR-1.2 |
| PNR-1.5 | **The notice is a product surface**, subject to the same accessibility and bilingual standards as any other page | A11, D-18 |

---

## 2. Audiences

| Audience | Situation | Requirement |
| --- | --- | --- |
| **Visitors** | No account; still logged | Must be able to read the notice without signing in |
| **Customers** | Account, Saves, Reviews | Needs account-specific detail |
| **Business owners and named contacts** | **Data published about them; no account** (D-02, D-54) | **Must be told how data about them is obtained, published, corrected and removed — without an account** |
| **Reporters** | Identity held and protected | Must be told how their identity is protected and that it is not disclosed |
| **People in photographs** | Never interacted with Bulbula | Must be able to find a removal route |
| **Advertiser contacts** | Commercial relationship | Must be told how their data is used |
| **Staff** | Employment-context processing | Separate internal notice — **Open — implementation detail** |

| ID | Rule |
| --- | --- |
| PNR-2.1 | **The third and fifth rows are the hard ones.** Art. 24(2)–(3) govern data not obtained from the data subject, and Bulbula's listing data is largely of that kind |
| PNR-2.2 | **A single notice may serve several audiences**, but each must be able to find their part |
| PNR-2.3 | **Audience-specific obligations are not satisfied by a general statement** |

---

## 3. Art. 24 checklist

Art. 24(1) requires the controller to inform the data subject of the
following. **Every row must be answerable before the notice is
published.**

| # | Art. 24(1) item | Bulbula source | Blocked by |
| --- | --- | --- | --- |
| PNR-3.1 | **Identity and contact details of the controller** | Owner to supply | **Open — implementation detail** |
| PNR-3.2 | **Contact details of the DPO, where applicable** | PG §5 | **PENDING COUNSEL (L-4)** |
| PNR-3.3 | **Purposes of the processing** | `data-inventory-v1.0.md` purpose columns | Ready |
| PNR-3.4 | **The personal data concerned** | `data-inventory-v1.0.md` §3–§9 | Ready |
| PNR-3.5 | **Categories of recipients** | `vendor-and-transfer-register-v1.0.md` §3 | Vendors open (D-41, D-42b, D-21) |
| PNR-3.6 | **The lawful basis** | PG §7 | **PENDING COUNSEL (L-5)** |
| PNR-3.7 | **The retention period, or the criteria used to determine it** | `data-retention-v1.0.md` | **PENDING COUNSEL (L-21)** |
| PNR-3.8 | **The right to request access, rectification, erasure and restriction, and to object** | `data-subject-rights-v1.0.md` | **PENDING COUNSEL (L-7)** |
| PNR-3.9 | **The right to withdraw consent**, where processing is consent-based, and that withdrawal does not affect prior lawfulness | PG-7.8 | **PENDING COUNSEL (L-5)** |
| PNR-3.10 | **The right to lodge a complaint with the Authority** | ECA contact details | **Unknown** — ECA channel not verifiable in this phase |
| PNR-3.11 | **Whether provision of the data is a statutory or contractual requirement**, whether the subject must provide it, and the consequences of not providing it | Account creation requires an email; browsing requires nothing | **PENDING COUNSEL (L-5)** |
| PNR-3.12 | **The source of the data, where not obtained from the data subject** | Listing data, D-50 | Ready in substance |
| PNR-3.13 | **Whether the source was publicly accessible** | Listing provenance (C-29) | Ready in substance |
| PNR-3.14 | **The existence of automated decision-making, including profiling**, with meaningful information about the logic and consequences | PG §8 | **PENDING COUNSEL** |
| PNR-3.15 | **Intention to transfer to another country, and the safeguards** | VT §4, §5 | **PENDING COUNSEL (L-2, L-10, L-12)** |

| ID | Rule |
| --- | --- |
| PNR-3.16 | **Twelve of fifteen rows are blocked.** The notice cannot be written today, and this table is the honest reason why |
| PNR-3.17 | **No row is answered with a plausible-sounding placeholder.** An unverified answer in a published notice is a false statement |
| PNR-3.18 | **Art. 24(2)–(3) impose timing duties where data is not obtained from the subject** — the information must be given within a reasonable period, at latest when first communicating with them or first disclosing the data. **Bulbula discloses by publishing, so the timing question is acute. PENDING COUNSEL** |
| PNR-3.19 | **Art. 24(4) exceptions** — including where provision proves impossible or involves disproportionate effort — may be relevant to PNR-3.18. **Their application is counsel's** |

---

## 4. Required disclosures beyond the checklist

Matters Bulbula must state plainly because they are consequential or
non-obvious, even where Art. 24 does not expressly enumerate them.

| ID | Disclosure | Why |
| --- | --- | --- |
| PNR-4.1 | **Your display name is published with your Review and is visible to anyone** | Customers choose a name before knowing this (DI-3.14) |
| PNR-4.2 | **Reviews are public and indexed by search engines** | DI-5.11 |
| PNR-4.3 | **Saved listings are private, always, with no public option** | PRIV-3. A positive commitment worth stating |
| PNR-4.4 | **Reporter identity is never disclosed to the business reported** | TS-8 |
| PNR-4.5 | **Business contact details published may be personal data**, and the individual concerned has rights over them | PCP-1 |
| PNR-4.6 | **How to request correction or removal without an account** | DSR-2.2 |
| PNR-4.7 | **Photographs may incidentally include people**, and how to request removal | PBD-9.7 |
| PNR-4.8 | **Precise location is used only when you ask for it and is not stored** | LOC-2 |
| PNR-4.9 | **There is no behavioural advertising, no tracking and no profiling** | PBD §7 |
| PNR-4.10 | **Bulbula does not sell, rent or share customer data** | DI-10.13 |
| PNR-4.11 | **Advertising is clearly labelled and never affects organic ranking** | D-05, D-10 |
| PNR-4.12 | **Sign-in uses Google or an emailed code; there are no passwords** | D-48 |
| PNR-4.13 | **Choosing Google means Google is involved in your sign-in** | VT §3.2 |
| PNR-4.14 | **Using the Telegram Mini App means Telegram sees that usage** | VT-3.14 |
| PNR-4.15 | **Deleted data persists in backups until they expire** | RET-8.7 |
| PNR-4.16 | **Content already copied by search engines or others cannot be recalled by Bulbula** | DSR-10.2 |
| PNR-4.17 | **Staff access to personal data is restricted and logged** | PG-10.1 |
| PNR-4.18 | **What happens to your Reviews if you delete your account** — they stop being publicly visible and no longer count towards any rating (D-34); **how long an internal record is kept is still to be confirmed** | D-34; period **PENDING COUNSEL** (L-21, D-46) |

| ID | Rule |
| --- | --- |
| PNR-4.19 | **PNR-4.9, PNR-4.10 and PNR-4.11 are commitments, not marketing.** Once published they constrain the product permanently |
| PNR-4.20 | **PNR-4.15 and PNR-4.16 are limits.** Stating them is uncomfortable and necessary (DSR-10.8) |
| PNR-4.21 | **PNR-4.18 can now state the outcome** — withdrawal from public view (D-34) — and the user must be told it **before** confirming deletion, not after. It **must not** state a retention period while **L-21 and D-46 are PENDING COUNSEL**; a vague promise is worse than an honest "still being confirmed" |

---

## 5. Layering and placement

| ID | Requirement | Source |
| --- | --- | --- |
| PNR-5.1 | **A full notice exists at a stable, publicly reachable URL**, with no sign-in required | PNR-2.1 |
| PNR-5.2 | **Short, in-context explanations appear at the point of collection**, linking to the full notice | Art. 12 |
| PNR-5.3 | **Layering is permitted; hiding is not.** A summary must not omit something a reasonable person would find material | PNR-1.4 |
| PNR-5.4 | **Contextual points required**: at sign-in (what the email is used for, what Google receives); at account creation (what the display name means); before posting a Review (that it is public and indexed); before a location request (what is used and that it is not stored); before deletion (what is deleted, what survives, the backup limit); on the correction route (what happens to the request) | PBD §13 |
| PNR-5.5 | **Location explanation precedes the browser permission prompt** | LOC-1 |
| PNR-5.6 | **The notice is reachable from the footer of every page** and from the Mini App | PN |
| PNR-5.7 | **Art. 24(2)–(3) timing applies to people whose data was not obtained from them**; a published notice may not discharge it. **PENDING COUNSEL** | PNR-3.18 |

---

## 6. Quality standards

| ID | Standard | Source |
| --- | --- | --- |
| PNR-6.1 | **Clear and plain language.** Art. 12 is explicit | Art. 12 |
| PNR-6.2 | **Available in both Amharic and English**, with equal completeness — not an English notice with a partial translation | D-18 |
| PNR-6.3 | **Accessible**: proper headings, real text, sufficient contrast, keyboard navigable, screen-reader usable | A11 |
| PNR-6.4 | **Readable on a phone.** Most users are mobile | D-52 |
| PNR-6.5 | **Dated and versioned**, with material changes summarised | D-29 |
| PNR-6.6 | **Concrete, not hedged.** "We may share your data with partners" is not a disclosure | PNR-1.4 |
| PNR-6.7 | **Specific recipients named**, not "service providers" | PNR-3.5 |
| PNR-6.8 | **Art. 12(2) requires special attention where information is addressed to a minor.** Until the age question resolves, the notice must be plain enough for a young reader without making an age claim | Art. 12(2), PG-9.9 |
| PNR-6.9 | **No legalese for its own sake.** The test is whether a Bole Bulbula shopkeeper can understand it | PNR-6.1 |

---

## 7. What the notice must not say

| ID | Prohibition | Why |
| --- | --- | --- |
| PNR-7.1 | **No claim of compliance, certification or approval** that has not been obtained | Art. 52; PG §12 preamble |
| PNR-7.2 | **No retention period that has not been set by counsel** | RET-5.4 |
| PNR-7.3 | **No minimum age** until counsel sets one | PG-9.9 |
| PNR-7.4 | **No lawful basis** until counsel determines it | PG-7.1 |
| PNR-7.5 | **No response window** until counsel sets one | DSR-1.5 |
| PNR-7.6 | **No promise of complete erasure** | RET-8.7 |
| PNR-7.7 | **No claim that data stays in Ethiopia** while D-42 is open and VT-5.4 unresolved | VT §5 |
| PNR-7.8 | **No blanket "we may share with third parties"** reserving undisclosed future sharing | PNR-6.6 |
| PNR-7.9 | **No consent-bundling** — one acceptance covering unrelated processing | Art. 8(2) |
| PNR-7.10 | **No claim that business data is outside the law** | PG-2.2 |
| PNR-7.11 | **No statement implying the notice itself creates a lawful basis** | PNR-1.3 |
| PNR-7.12 | **No reservation of a right to change the notice at any time without telling anyone** | Art. 12 |
| PNR-7.13 | **No security claim that overstates the controls** — "bank-grade", "fully encrypted", "unhackable" | SEC-3.12 |
| PNR-7.14 | **No DPO contact details unless a DPO is actually appointed** | PG-5.4 |

---

## 8. Related documents

| Document | Status |
| --- | --- |
| **Privacy notice** | Blocked on L-6; counsel-drafted |
| **Terms of service** | **Out of scope for this phase**; counsel-drafted. Interacts with L-15 |
| **Cookie or local-storage notice** | **Open.** Whether one is needed depends on what the application stores client-side. PD-05 means no session token; a strictly necessary preference or CSRF value may still exist. **Open — implementation detail** |
| **Advertiser terms** | Out of scope; counsel-drafted. L-16, L-17, L-20 |
| **Staff privacy notice** | **Open — implementation detail.** PG-10.5 requires staff to know about access logging |
| **Review and content policy** | Exists (`review-policy.md`); the notice links to it |
| **Security disclosure policy** | **Open — product decision** (IR §11) |

| ID | Rule |
| --- | --- |
| PNR-8.1 | **This phase writes none of the above.** The stop condition is explicit |
| PNR-8.2 | **The cookie question must be answered before launch**, even if the answer is "nothing requiring a notice" |

---

## 9. Readiness

| Precondition | Blocking item | Status |
| --- | --- | --- |
| Lawful basis per purpose | L-5 | **PENDING COUNSEL** |
| Retention periods or criteria | L-21, D-46 | **PENDING COUNSEL** |
| Rights procedures and windows | L-7 | **PENDING COUNSEL** |
| Transfer bases and safeguards | L-2, L-10, L-12 | **PENDING COUNSEL** |
| Vendors actually chosen | D-41, D-42b, D-21 | **Open** |
| DPO decision | L-4 | **PENDING COUNSEL** |
| ECA complaint channel | — | **Unknown** |
| Automated decision-making conclusion | PG §8 | **PENDING COUNSEL** |
| Minimum age | D-46 | **PENDING COUNSEL** |
| Review retention after withdrawal | L-21 / D-46 | **PENDING COUNSEL.** The *semantics* are settled by D-34 — withdrawal — but no period may be published |
| Controller contact details | — | **Open — implementation detail** |
| Registration status | L-3 | **PENDING COUNSEL** |

| ID | Rule |
| --- | --- |
| PNR-9.1 | **The notice is on the critical path to launch**, and it depends on counsel output that has not been commissioned |
| PNR-9.2 | **Commissioning counsel is therefore itself a launch-blocking task**, and the longest-lead item in this entire document set |
| PNR-9.3 | **Launching without a notice is not an option.** Art. 24 is a right, not a formality |

---

## 10. Unresolved items

| ID | Item | Status |
| --- | --- | --- |
| **L-6** | **Required notice content and wording** | **PENDING COUNSEL** — the central gap |
| L-5 / L-7 / L-21 / L-2 / L-10 / L-12 / L-4 / L-3 | Each feeds a required notice element | **PENDING COUNSEL** |
| L-21 / D-46 | Retention period behind PNR-4.18 | **PENDING COUNSEL.** D-34 closed the semantics on 2026-10-07 |
| D-46 | Minimum age | **PENDING COUNSEL** |
| D-41 / D-42b / D-21 | Vendors to be named as recipients | **Open** |
| — | Whether a cookie or local-storage notice is required | **Open — implementation detail** |
| — | Whether a separate staff privacy notice is produced | **Open — implementation detail** |
| — | Art. 24(2)–(3) timing for people whose data was not obtained from them | **PENDING COUNSEL** |
| — | Whether an Art. 24(4) exception applies to listing data | **PENDING COUNSEL** |
| — | ECA complaint channel details for PNR-3.10 | **Unknown** |
| — | Controller identity and contact details as published | **Open — implementation detail** |
| — | Who drafts the Amharic text and how equivalence is assured | **Open — implementation detail** |

---

## Legal and regulatory references

| Reference | Provision | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, **Art. 12** | Fairness and transparency; clear and plain language; **special attention for minors** | Confirmed — statute/regulation |
| **Art. 24(1)** | The fifteen items the data subject must be informed of | Confirmed — statute/regulation |
| Art. 24(2)–(3) | Timing where data is not obtained from the data subject | Confirmed — statute/regulation |
| Art. 24(4) | Exceptions, including disproportionate effort | Confirmed — statute/regulation |
| Art. 8(2) | Consent must be unbundled and specific | Confirmed — statute/regulation |
| Art. 8(3) | Right to withdraw consent, told beforehand | Confirmed — statute/regulation |
| Art. 25–32 | Rights the notice must describe | Confirmed — statute/regulation |
| Art. 31 | Automated decision-making disclosure | Confirmed — statute/regulation |
| Art. 40(5) | Published DPO contact details, where appointed | Confirmed — statute/regulation |
| Art. 52 | Accountability — the notice is evidence and must be accurate | Confirmed — statute/regulation |
| **All notice wording** | — | **PENDING COUNSEL (L-6)** |
| ECA complaint channel for Art. 24(1) purposes | — | **Unknown** |

---

## Decision references

D-02, D-05, D-10, D-18, D-21, D-29, D-34, D-41, D-42, D-42b, D-46, D-48,
D-50, D-52, D-54.
