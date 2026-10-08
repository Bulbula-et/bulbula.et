# Customer Support

| | |
| --- | --- |
| **Document** | Customer Support — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

The V1 support model: how inbound contact arrives, how it is
classified, who owns it, what evidence is kept, when it escalates, how
it is resolved, how Bulbula communicates, and how it closes.

Covers eleven request classes: account problems · OTP problems · Google
authentication problems · Review problems · Listing corrections · abuse
reports · privacy requests · deletion requests · data-subject requests ·
security incidents · general inquiries.

**Out of scope.** The mechanics of correcting a Listing
(`listing-operations-v1.0.md` §7), of moderating content
(`moderation-operations-v1.0.md`), of executing a DSR
(`../55-privacy/data-subject-rights-v1.0.md`), and of running an
incident (`../50-security/incident-response-v1.0.md`). **This document
routes to those; it does not duplicate them.**

## Authority and precedence

| ID | Rule |
| --- | --- |
| SUP-0.1 | **Support introduces no product capability** and gives no one a route to an outcome the product does not already support |
| SUP-0.2 | **No response-time or resolution-time target is stated.** All are **PENDING PILOT** (TS-6, COR-5) |
| SUP-0.3 | **No support channel is committed beyond those the product already defines**: the public contact page (C-18), the report control (C-15), the privacy route (C-36), and email (D-24) |
| SUP-0.4 | **Statutory response periods are not stated here.** Where a request has a legal deadline, that deadline is owned by `data-subject-rights-v1.0.md` and is **PENDING COUNSEL** |
| SUP-0.5 | **Support must not become a back door.** A request that can only be satisfied by breaking a rule is refused and recorded, not quietly accommodated |

---

## 1. Principles

| ID | Principle | Source |
| --- | --- | --- |
| SUP-1.1 | **Most support contact is a product defect in disguise.** Volume per class is a quality signal, not just workload | QS §6 |
| SUP-1.2 | **Support reaches everyone, including people with no account.** Guests report, businesses contact, and third parties object | TS-2, C-18 |
| SUP-1.3 | **The correction route is the safety valve for businesses that cannot edit.** It must work, visibly and promptly | TS-1, TS-3, D-02 |
| SUP-1.4 | **Identity is not required to report a problem, and must not be demanded as a gate** | TS-2 |
| SUP-1.5 | **Identity is required to act on an account.** Verification protects the account holder; the method is defined in `data-subject-rights-v1.0.md` | DSR §3 |
| SUP-1.6 | **Every contact reaches a recorded outcome** | TS-4, REP-6 |
| SUP-1.7 | **A commercial relationship buys no priority** | IN-4 |
| SUP-1.8 | **Support replies are transactional email and carry no marketing** | EM-2, NOT-4 |
| SUP-1.9 | **Support must not disclose a reporter's identity**, in any reply, to anyone | TS-10, REP-4 |
| SUP-1.10 | **Support is not a legal function.** A legal question goes to an Administrator and then to counsel | OM-5.3 |

---

## 2. Intake

### 2.1 The routes

| Route | Who can use it | Account | Lands as |
| --- | --- | --- | --- |
| **Report control on a Business profile** (C-15) | Anyone, incl. Guests | No | Listing report |
| **Report control on a Review** (REP-1) | Authenticated Customers | Yes | Review report |
| **Contact page** (C-18) | Anyone, incl. businesses | No | General inbound |
| **Privacy / deletion controls in the account** (C-36) | Authenticated Customers | Yes | Privacy or deletion request |
| **Privacy contact point in the privacy notice** (PNR) | Anyone, incl. non-users | No | Data-subject request |
| **Direct contact with an Operator in the field** | Businesses | No | General inbound, logged on return |
| **Security disclosure route** (`vulnerability-management`) | Anyone | No | Security report |

| ID | Rule | Source |
| --- | --- | --- |
| SUP-2.1 | **Every public page offers a route to a human.** A dead end is a defect | C-18 |
| SUP-2.2 | **Anonymous routes stay anonymous.** A Guest report carries no identity, and the queue must not imply one exists | OPX-9.6, TR-202 |
| SUP-2.3 | **A contact that arrives on the wrong route is re-classified, not refused** | §3 |
| SUP-2.4 | **Contact received verbally in the field is recorded in the console on return.** A conversation is not a record | OM-4.9 |
| SUP-2.5 | **The email address a person supplies is personal data** and is minimised, used only for the request, and retained under the schedule — **PENDING COUNSEL (L-21)** | EM-5, RET §4 |
| SUP-2.6 | **Whether a general-inbound item is a console entity or an inbox is Open — implementation detail.** Either way it must reach a recorded outcome | SUP-1.6 |

---

## 3. Classification

Classification is the first and most consequential step: it fixes the
owner, the evidence standard and the clock.

| Class | Default owner | Routes to |
| --- | --- | --- |
| **1. Account problem** | Operator | §4 |
| **2. OTP problem** | Operator | §5 |
| **3. Google authentication problem** | Operator | §5 |
| **4. Review problem** | Operator (`review.moderate`) | `moderation-operations-v1.0.md` |
| **5. Listing correction** | Operator | `listing-operations-v1.0.md` §7 |
| **6. Abuse report** | Operator, escalating | `moderation-operations-v1.0.md` §4 |
| **7. Privacy request** | **Administrator** | `data-subject-rights-v1.0.md` |
| **8. Deletion request** | **Administrator** | `data-subject-rights-v1.0.md` §6 |
| **9. Data-subject request** | **Administrator** | `data-subject-rights-v1.0.md` |
| **10. Security incident / disclosure** | **Incident lead** | `incident-response-v1.0.md` |
| **11. General inquiry** | Operator | §8 |

| ID | Rule |
| --- | --- |
| SUP-3.1 | **Classification is recorded and may be changed, with the change recorded.** Misclassification is normal; hiding it is not |
| SUP-3.2 | **When a contact spans classes, the highest-obligation class governs.** A correction request that also demands erasure is a data-subject request |
| SUP-3.3 | **Anything that might be a personal-data breach is treated as class 10 until ruled out**, because the assessment clock starts at awareness (IR §7) |
| SUP-3.4 | **Anything that might be a legal demand is escalated to an Administrator before any substantive reply** (OPX-9.7, **PENDING COUNSEL — L-15**) |
| SUP-3.5 | **A request to remove a photograph of a person is class 7**, not a cosmetic correction (PBD-9.9) |
| SUP-3.6 | **A business asking to be removed is a Permission withdrawal** (TS-5), routed to an Administrator |

---

## 4. Account problems

| ID | Handling | Source |
| --- | --- | --- |
| SUP-4.1 | **Verify identity before acting on an account.** The method is defined in `data-subject-rights-v1.0.md` §3 and is not improvised here | DSR §3 |
| SUP-4.2 | **Verification must not demand more personal data than Bulbula already holds.** Demanding an ID document to prove ownership of an email address collects more than it protects | DSR-3.6 |
| SUP-4.3 | **Staff must never ask for, accept or repeat an OTP code.** There is no circumstance in which support needs one | §5 |
| SUP-4.4 | **Staff must not sign in as a Customer.** No impersonation mechanism exists and none is created | ST-5 |
| SUP-4.5 | **Account changes made by Staff are audited with actor, time and reason** | C-29 |
| SUP-4.6 | **Linking two identities to one person is Open (D-13).** Support must not merge accounts manually to work around it; the request is recorded against D-13 | D-13 |
| SUP-4.7 | **A person locked out with no access to their email address cannot be let in by support.** That is the correct outcome and is explained plainly | NFR-S3 |

---

## 5. Authentication problems

### 5.1 Email OTP

| Symptom | Handling |
| --- | --- |
| Code not received | Confirm the address, check the delivery-failure record (EM-4), ask them to retry. **Never read a code out** |
| Code expired | Ask them to request another. Validity values are **Open (OT-02)** |
| Too many attempts | The limit is working. Explain it and give the wait. Values are **Open (OT-02)** |
| Code rejected as wrong | Re-request. Do not widen the window, raise the limit or bypass the control for one person |
| Address typo at entry | A new request to the correct address; no record is amended on their word alone |

| ID | Rule | Source |
| --- | --- | --- |
| SUP-5.1 | **An authentication control is never relaxed for an individual.** Rate limits, expiry and attempt caps exist precisely for the case where someone asks | AS §5 |
| SUP-5.2 | **OTP codes must never appear in logs, tickets, replies or screenshots** | TR-135, DI-4.14 |
| SUP-5.3 | **Email delivery failures are recorded and visible to Staff**, which is what makes "not received" answerable | EM-4, NOT-7 |
| SUP-5.4 | **Repeated failures to one domain are an operational signal, not eleven tickets** — raised under `observability-operations-v1.0.md` | NFR-O1 |

### 5.2 Google authentication

| ID | Rule | Source |
| --- | --- | --- |
| SUP-5.5 | **Where Google is unavailable, email OTP is the fallback.** Both methods exist so neither is a single point of failure | D-48 |
| SUP-5.6 | **A Google account problem is Google's to solve.** Bulbula explains the fallback and does not attempt to intervene | — |
| SUP-5.7 | **Whether a Google identity and an email identity are automatically linked is Open (D-13).** Support must not assert an answer | D-13 |
| SUP-5.8 | **An outage of the Google path is an availability matter**, handled under `observability-operations-v1.0.md`, not one ticket at a time | NFR-A1 |

---

## 6. Content and listing requests

| Class | Route | Note |
| --- | --- | --- |
| **Review problem** | `moderation-operations-v1.0.md` §3 | Decided on a published ground only (MOD-1, MOD-2) |
| **Business objects to a Review** | `moderation-operations-v1.0.md` §8.7 | Handled as a report; **not** an appeal; no priority (MOD-3, IN-4) |
| **Listing correction** | `listing-operations-v1.0.md` §7 | Verified before applied (REP-3) |
| **Business requests a change** | `listing-operations-v1.0.md` §7.1 | Same queue, same standard (REP-7) |
| **Business requests removal** | Permission withdrawal | **Administrator**; prompt unpublication (TS-5) |
| **Abuse report** | `moderation-operations-v1.0.md` §4.2 | Harm outranks queue order (MO-2.9) |
| **Content that may be illegal** | **Administrator → counsel** | **PENDING COUNSEL (L-15)** |

| ID | Rule |
| --- | --- |
| SUP-6.1 | **Support does not decide moderation outcomes in a reply.** It routes to the queue and reports the decision (MO-3.2) |
| SUP-6.2 | **Support must not promise an outcome before the decision is taken** |
| SUP-6.3 | **Support must not tell a business which Review to expect to be removed**, nor confirm that a specific report was received from a specific person (TS-10) |

---

## 7. Privacy, deletion and data-subject requests

| ID | Rule | Source |
| --- | --- | --- |
| SUP-7.1 | **These are Administrator-owned, always** | `interaction-permissions.md` §4 |
| SUP-7.2 | **The procedure is `data-subject-rights-v1.0.md` and is not restated, summarised or paraphrased here.** A paraphrase would drift | DSR |
| SUP-7.3 | **The route is open to anyone, not only account holders** — a business owner, a person in a photograph, a person named in a Review | PNR, DI §3 |
| SUP-7.4 | **Identity verification precedes execution** and is proportionate | DSR §3 |
| SUP-7.5 | **Statutory response periods are PENDING COUNSEL.** Support must not quote one | DSR §2 |
| SUP-7.6 | **Execution is audited; the audit record survives the deletion** | OPX-12.4, TR-167 |
| SUP-7.7 | **A refusal is a decision with a stated basis**, recorded, and reviewed by the Administrator — never a non-reply | DSR §8 |
| SUP-7.8 | **Reporter identity is not disclosed through an access request** | DSR-4.8 |
| SUP-7.9 | **A deletion request is not a route to erase a legitimate Review.** Where the two conflict, it is an Administrator decision under the DSR procedure, recorded with its reasoning | DSR §6 |

---

## 8. General inquiries and security reports

### 8.1 General inquiries

| ID | Rule |
| --- | --- |
| SUP-8.1 | **A question that is really a request is re-classified** (SUP-3.1) |
| SUP-8.2 | **"How do I get listed?" is answered honestly**: Bulbula lists businesses itself, listing is free, and there is no self-service (D-54, PK-6) |
| SUP-8.3 | **"How do I rank higher?" has one honest answer**: ranking is organic and unpurchasable, and sponsorship does not change it (IN-1, LB-8) |
| SUP-8.4 | **An advertising inquiry routes to `advertising-operations-v1.0.md` §2.1.** No price is quoted before one is approved (AO-2.6) |
| SUP-8.5 | **Press, partnership and investment inquiries route to the owner** |
| SUP-8.6 | **A question Bulbula cannot answer is answered with "we don't know", not with a guess** |

### 8.2 Security reports and incidents

| ID | Rule | Source |
| --- | --- | --- |
| SUP-8.7 | **A security report routes to the disclosure process and is acknowledged**, never ignored | `vulnerability-management-v1.0.md` |
| SUP-8.8 | **A reporter acting in good faith is not threatened** | VT §8 |
| SUP-8.9 | **Suspected breach of personal data goes immediately to the Administrator and the owner.** The assessment clock starts at awareness | IR §7 |
| SUP-8.10 | **Support must not confirm, deny or characterise an incident publicly.** Communication is owned by the incident process | IR §8 |
| SUP-8.11 | **Support must not close a security report as "no action" alone.** That decision belongs to the vulnerability process | VT §5 |

---

## 9. Evidence and records

| Field | Always recorded |
| --- | --- |
| How it arrived | Route, date, time |
| What was asked | In the requester's own terms, not a staff summary of their intent |
| Classification | And any change to it, with the reason |
| Owner | Current, and every reassignment |
| Identity verification | Whether performed, by what method, with what result |
| Evidence considered | What was looked at to decide |
| Decision and reasoning | Including a refusal and its basis |
| Actions taken | With links to the Correction, moderation decision or DSR record |
| Communication sent | What was said and when |
| Closure | Outcome and date |

| ID | Rule | Source |
| --- | --- | --- |
| SUP-9.1 | **The record contains the minimum personal data needed to handle the request** | PRIV-2, DI §9 |
| SUP-9.2 | **Identity-verification material is not retained beyond its purpose** | DSR-3.7 |
| SUP-9.3 | **The record is access-controlled and reading it is logged** | PG-10.3, TS-20 |
| SUP-9.4 | **Support records are subject to the retention schedule — PENDING COUNSEL (L-21)** | RET §4 |
| SUP-9.5 | **A privileged action taken from support is audited like any other** | C-29, TR-08 |
| SUP-9.6 | **Records are written at the time**, not reconstructed at closure | OM-6.6 |

---

## 10. Escalation

| Trigger | To | Note |
| --- | --- | --- |
| Legal demand, defamation, takedown | **Administrator → counsel** | Before any substantive reply (SUP-3.4) |
| Privacy, deletion or data-subject request | **Administrator** | Always |
| Suspected personal-data breach | **Administrator → owner → counsel** | Clock starts at awareness |
| Security vulnerability report | **Incident lead** | VT §4 |
| Permission withdrawal | **Administrator** | TS-5 |
| Request to remove a person from a photograph | **Administrator** | PBD-9.9 |
| Threat, or risk to a person's safety | **Administrator, immediately** | MO-2.9 |
| A business disputing a moderation outcome | **Administrator** | MOD-6 |
| Commercial pressure applied through a support channel | **Administrator → owner** | IN-5 |
| A request that would require breaking a documented rule | **Administrator** | SUP-0.5 |
| Repeated contact about an unresolvable issue | **Administrator** | Likely a product defect (SUP-1.1) |

| ID | Rule |
| --- | --- |
| SUP-10.1 | **Escalation is recorded** (OM-5.2) |
| SUP-10.2 | **Escalation never pauses containment of active harm** (OM-5.4) |
| SUP-10.3 | **"I'll escalate it" is not a resolution.** The item stays open under its new owner until it closes (SUP-11.4) |

---

## 11. Communication and closure

### 11.1 Communication

| ID | Rule | Source |
| --- | --- | --- |
| SUP-11.1 | **Every substantive request is acknowledged where an address was supplied** | PRD §18 |
| SUP-11.2 | **Replies state what was decided and why**, in plain language, with no internal jargon and no identifier the person cannot act on | TR-146 |
| SUP-11.3 | **Bulbula does not invent a reason.** "We could not confirm this" is a complete and honest answer | MO-4.13 |
| SUP-11.4 | **A reply never discloses**: a reporter's identity, another person's personal data, internal provenance or verification evidence, or the existence of an unannounced security issue | TS-10, PCP-5, IR §8 |
| SUP-11.5 | **Support email is transactional, identifies Bulbula, states why the person received it, and carries no marketing** | EM-1, EM-2 |
| SUP-11.6 | **No response-time commitment is made to anyone** until targets exist — **PENDING PILOT** | SUP-0.2 |
| SUP-11.7 | **Where a request is refused, the refusal is stated clearly with its basis and, where applicable, the route to contest it** | DSR §8 |
| SUP-11.8 | **Material service communications — planned unavailability, material policy change — are a Service email**, sent by decision, not by support improvisation | PRD §18, NOT-5 |

### 11.2 Closure

| ID | Rule | Source |
| --- | --- | --- |
| SUP-11.9 | **Every item closes in exactly one recorded outcome**: resolved · closed-unverified · rejected · routed-and-closed-elsewhere | MO-4.13, TS-4 |
| SUP-11.10 | **Closure requires an outcome; it is never an absence of activity** | OPX-9.4 |
| SUP-11.11 | **"Routed and closed elsewhere" names the record it routed to** | COR-4 |
| SUP-11.12 | **Re-opening is a recorded act**, not a silent edit of a closed item |
| SUP-11.13 | **Nothing is closed because it got old** |

---

## 12. Support as a quality signal

| ID | Practice | Source |
| --- | --- | --- |
| SUP-12.1 | **Contact volume by class is reviewed on the weekly cadence** | OM §9 |
| SUP-12.2 | **A rise in one class is a defect hypothesis**: OTP contacts suggest a delivery problem; correction reports on recent Listings suggest a collection problem; repeated "how do I get listed" suggests the contact page is unclear | QS §6 |
| SUP-12.3 | **Correction volume in the first period after publication is one of the twelve pilot measures** | `listing-operations.md` §6.3 |
| SUP-12.4 | **Support volume must not be reduced by making contact harder** | SUP-2.1 |
| SUP-12.5 | **A recurring request that the product cannot satisfy is a decision request**, recorded against the register rather than absorbed indefinitely |

---

## 13. Traceability

| This document | Traces to |
| --- | --- |
| §1 principles | TS-1…TS-4, TS-10; C-15, C-18, C-36; D-02, D-24; IN-4 |
| §2 intake | C-15, C-18, C-36; REP-1; OPX-9.6; EM-5; PNR |
| §3 classification | `interaction-permissions.md` §4; IR §7; OPX-9.7; TS-5; L-15 |
| §4 account | DSR §3; ST-5; C-29; D-13 |
| §5 authentication | C-30, C-31; D-48; OT-02; EM-4; NOT-7; TR-135 |
| §6 content | MOD-1…MOD-3, MOD-6; REP-3, REP-7; TS-5 |
| §7 privacy | `data-subject-rights-v1.0.md`; C-36; DSR-3.6, DSR-4.8; TR-167 |
| §8 general and security | D-54; PK-6; IN-1; `vulnerability-management-v1.0.md`; IR §7, §8 |
| §9 records | PRIV-2; C-29; TR-08; PG-10.3; TS-20; L-21 |
| §10 escalation | OPX-9.7; TS-5; MOD-6; IN-5; PBD-9.9 |
| §11 communication | PRD §18; EM-1, EM-2; NOT-5; TR-146; TS-4 |
| §12 quality signal | `listing-operations.md` §6.3; OPS-8 |

---

## 14. Open items

| ID | Item | Marker |
| --- | --- | --- |
| D-13 | Identity-linking rules across Google and email identities | **Open (D-13)** — product decision |
| D-14 | Which permission covers which support action | **Open (D-14)** — product decision |
| OT-02 | OTP validity, attempt and request limits — affects what support can explain | **Open — technical decision** |
| L-15 | Legal demands and takedown process | **PENDING COUNSEL** |
| L-21 / D-46 | Retention of support records | **PENDING COUNSEL** |
| — | Statutory response periods for data-subject requests | **PENDING COUNSEL** |
| TS-6 / COR-5 | Response and resolution targets per class | **PENDING PILOT** |
| — | Whether general inbound is a console entity or an inbox (SUP-2.6) | **Open — implementation detail** |
| — | Whether a public support address is published separately from the contact page | **Open — operational decision** |
| — | Whether Bulbula publishes a support-volume transparency summary | **Open — operational decision** |
| — | Named owner per class where more than one staff member exists | **Open — operational decision** |

---

## Decision references

D-02, D-13, D-14, D-24, D-46, D-48, D-54.
