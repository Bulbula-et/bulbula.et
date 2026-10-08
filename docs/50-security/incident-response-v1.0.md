# Incident Response

| | |
| --- | --- |
| **Document** | Incident Response — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

How Bulbula handles an incident in V1: the lifecycle, incident classes,
the severity model, evidence, containment, communication, restoration
and post-incident review — including the distinct path a **personal-data
breach** must follow.

**Out of scope.** Preventive controls (the other `docs/50-security/`
documents), data-subject rights (`data-subject-rights-v1.0.md`), and
the content of any public statement, which is counsel-controlled.

## Authority

Below the TRD, above operational practice. **Decides no legal
obligation.** Statutory deadlines are quoted only where they are
directly confirmed in the enacted text; everything about their
*application to Bulbula* is **PENDING COUNSEL**.

**Proportionality.** This is a small company on shared hosting. The
process below is deliberately simple enough to be followed under
pressure by two or three people. An enterprise SOC playbook that nobody
executes is worse than a short one that is.

---

## 1. Lifecycle

```text
detect → triage → contain → investigate → eradicate
       → recover → notify → document → improve
```

| Stage | Question | Must not happen |
| --- | --- | --- |
| **Detect** | Did something happen? | Noticing and not recording it |
| **Triage** | How bad, what class, who leads? | Debating severity for hours before containing |
| **Contain** | How do we stop it getting worse? | Destroying the evidence while containing |
| **Investigate** | What actually happened, and to what? | Guessing the blast radius |
| **Eradicate** | Is the cause gone? | Restoring onto the same hole |
| **Recover** | Is service correct again? | Declaring recovery without verification |
| **Notify** | Who must be told, and by when? | Deciding a legal question without counsel |
| **Document** | What is the record? | A fixed incident with no record |
| **Improve** | What changes? | A post-mortem with no owned action |

| ID | Rule |
| --- | --- |
| IR-1.1 | **Containment precedes investigation** when personal data is actively exposed |
| IR-1.2 | **Evidence preservation precedes eradication** (§5) |
| IR-1.3 | **Notification is never skipped because the incident was fixed quickly.** The Art. 43 clock runs from awareness, not from resolution |
| IR-1.4 | **Every incident is documented**, including those that turn out to be nothing |
| IR-1.5 | **No incident is closed without a named follow-up owner** where an action was agreed |

---

## 2. Incident classes

| Class | Definition | Typical first signal | Default severity |
| --- | --- | --- | --- |
| **Account compromise** | A Customer or staff account used by someone other than its owner | Anomalous sign-in; Customer report; unexpected staff action | **High**; **Critical** if staff |
| **Personal-data incident** | Personal data destroyed, lost, altered, disclosed or accessed without authorisation (Art. 2(7)) | Any of the others; a report; a log finding | **Critical** until bounded |
| **Application compromise** | Attacker influences application behaviour — RCE, stored XSS at scale, injection | Unexpected files; anomalous queries; defacement | **Critical** |
| **Infrastructure outage** | Service unavailable or degraded without compromise | Monitoring; user report | **Medium**; **High** if prolonged |
| **Malicious content** | Harmful, illegal, defamatory or abusive content published | Report (TS-8); moderation queue | **High** if harm to a person |
| **Media attack** | A malicious or illegal file uploaded or served | Integrity check (SO-12.3); report | **High** |
| **Staff privilege abuse** | Staff act outside their mandate, deliberately or not | Audit review; anomaly; complaint | **High**; **Critical** if data taken |
| **Advertising integrity incident** | Paid placement affects ranking; unlabelled sponsorship; fabricated reporting | Review; advertiser or user complaint | **High** — it breaks the core promise |

| ID | Rule |
| --- | --- |
| IR-2.1 | **An incident may belong to several classes.** An application compromise touching Customer records is *also* a personal-data incident and follows §7 |
| IR-2.2 | **The personal-data question is asked for every incident**, not only obvious ones |
| IR-2.3 | **Availability alone is not a personal-data breach** — unless the loss is of the data itself, since Art. 2(7) includes accidental destruction and loss |
| IR-2.4 | An advertising integrity incident is a **real incident**, not a commercial dispute (TS-14, TS-16) |

---

## 3. Severity model

| Level | Definition | Examples | Response expectation |
| --- | --- | --- | --- |
| **Critical** | Personal data exposed or lost; or an attacker controls the application or a staff account | Database disclosure; `/ops/*` reachable unauthenticated; RCE; staff account takeover; backup leaked | **Drop other work.** Owner informed immediately. Contain first. Start the Art. 43 assessment at once |
| **High** | A single account or a privileged boundary compromised, bounded; or real harm to a person | One Customer account taken over; privilege-escalation path found; doxxing published; malicious media served; paid placement affected ranking | Same day. Owner informed. Contain, then investigate |
| **Medium** | A control is weak or absent but no exploitation is known; or meaningful degradation | Missing authorization on a low-sensitivity route; missing CSRF token on one form; prolonged outage | Days. Scheduled fix with a date |
| **Low** | Limited realistic impact | Missing hardening header; verbose non-sensitive error | Next normal cycle |
| **Informational** | No impact; worth recording | Advisory for a dependency path Bulbula does not reach | Record and move on |

| ID | Rule |
| --- | --- |
| IR-3.1 | **When unsure, rate it higher.** Downgrading later is cheap; discovering it was Critical a week later is not |
| IR-3.2 | **Any confirmed exposure of personal data is at least High, and Critical until the scope is bounded** |
| IR-3.3 | **Severity is about impact, not about effort to fix** |
| IR-3.4 | **Only the owner may accept a risk** rather than fix it, and the acceptance is recorded (THR §10) |
| IR-3.5 | A Medium that has been open past its date **escalates**; it does not quietly persist |

---

## 4. Roles

With a team this small, roles are hats, not people.

| Role | Responsibility |
| --- | --- |
| **Incident lead** | Owns the incident end to end; decides containment; keeps the record. One person, named at triage |
| **Owner (project owner)** | Informed for High and Critical. **Sole authority** to accept risk, approve public communication, and instruct counsel |
| **Counsel** | **Sole authority** on whether an incident is notifiable, on notification content, and on regulatory contact |
| **Technical responder** | Investigates and remediates |
| **Operations** | Handles content, moderation and user-facing consequences |

| ID | Rule |
| --- | --- |
| IR-4.1 | **The incident lead is named at triage**, before investigation starts |
| IR-4.2 | **No engineer decides a notification question.** That is counsel's, via the owner |
| IR-4.3 | The person who caused an incident **may investigate but must not be the sole assessor** of its severity |
| IR-4.4 | If Bulbula appoints a DPO (**PENDING COUNSEL**, L-4), the DPO is the Authority's contact point under Art. 43(4)(b) | 

---

## 5. Evidence

| ID | Requirement | Source |
| --- | --- | --- |
| IR-5.1 | **Preserve before you fix.** Copy logs, affected files and relevant database state before eradication | IR-1.2 |
| IR-5.2 | Capture **timestamps, correlation ids, affected identifiers and the actor** where known | TR-134 |
| IR-5.3 | **Do not delete the attacker's artefacts** until they are copied | IR-5.1 |
| IR-5.4 | **Suspend log rotation** for relevant logs if rotation would destroy evidence | TR-140 |
| IR-5.5 | Evidence is stored **access-controlled, outside the web root**, and is itself personal data if it contains any | THR-65 |
| IR-5.6 | **Evidence is retained for the incident record** and then disposed of under the retention schedule — **PENDING COUNSEL (L-21)** | RET §7 |
| IR-5.7 | Art. 43(6) requires documenting **the facts, the effects and the remedial action**; evidence collection is built to produce exactly that | Art. 43(6) |
| IR-5.8 | **A contemporaneous timeline is kept from the first minute.** Reconstructing it later is unreliable and looks bad to a regulator | IR-5.7 |

---

## 6. Containment and eradication

| Class | Typical containment | Notes |
| --- | --- | --- |
| **Account compromise** | Revoke sessions (S-7); disable the account; invalidate outstanding OTPs | Revocation is server-side and immediate |
| **Staff compromise** | Disable the staff account; **rotate every secret that account could reach**; review the audit trail for that actor | SO-2.6 |
| **Application compromise** | Take the affected path out of service; **rotate all secrets**; redeploy from a known commit | SO-10.1 |
| **Personal-data incident** | Stop the exposure; remove the exposed artefact; invalidate anything leaked | Then §7 immediately |
| **Media attack** | Unpublish the media; remove from storage; check for siblings; verify no PHP in storage | SO-12.4 |
| **Malicious content** | Unpublish; record the policy basis (TS-9); preserve a copy as evidence | Removal is not evidence destruction if preserved first |
| **Advertising integrity** | Suspend the campaign; restore correct ranking and labelling; preserve the delivery record | TS-14 |
| **Outage** | Restore service; **a restore from backup is a last resort and a decision** | RB-4 |

| ID | Rule |
| --- | --- |
| IR-6.1 | **Redeploy from a known commit; never clean a compromised tree in place** |
| IR-6.2 | **Rotate every secret the attacker could plausibly have read**, not only the one known to be used |
| IR-6.3 | **Never restore onto the unpatched cause.** Eradication precedes recovery |
| IR-6.4 | Containment that destroys evidence requires the incident lead's explicit decision, recorded |
| IR-6.5 | **Taking the site down is an available option** and is better than continuing to leak |

---

## 7. Personal-data breaches

**This section states what the law says and what Bulbula must do
internally. It does not determine Bulbula's legal obligations.**

### 7.1 Three distinct questions

| Question | Who decides | Status |
| --- | --- | --- |
| **1. Internal handling** — contain, investigate, fix, record | Incident lead | Bulbula's own process, below |
| **2. Regulatory notification** — must the Authority be told, and what is said | **Counsel**, instructed by the owner | **PENDING COUNSEL (L-8)** |
| **3. Data-subject notification** — must affected people be told, and what is said | **Counsel**, instructed by the owner | **PENDING COUNSEL (L-8)** |

| ID | Rule |
| --- | --- |
| IR-7.1 | **These three are never conflated.** Internal handling starts immediately and does not wait for counsel |
| IR-7.2 | **Counsel is instructed as soon as a personal-data breach is suspected**, not once it is confirmed. The clock in Art. 43(1) runs from awareness |
| IR-7.3 | **Nobody but counsel decides that notification is not required** |

### 7.2 What the enacted text says

`Confirmed — statute/regulation`, from Federal Negarit Gazette No. 35,
24 July 2024:

| Provision | Content |
| --- | --- |
| **Art. 2(7)** | A personal data breach is a **breach of security leading to accidental or unlawful destruction, loss, alteration, unauthorised disclosure of, or access to** personal data transmitted, stored or otherwise processed |
| **Art. 43(1)** | The controller shall, **within 72 hours after becoming aware of it**, notify the breach to the Authority |
| **Art. 43(2)** | A late notification **must be accompanied by reasons for the delay** |
| **Art. 43(3)** | A **processor** shall notify the controller **without undue delay** after becoming aware |
| **Art. 43(4)** | The notification shall describe the **nature** of the breach including categories and approximate numbers of data subjects and records; give the **DPO or other contact point**; describe **likely consequences**; and describe **measures taken or proposed**, including mitigation |
| **Art. 43(5)** | Where information cannot all be provided at once, it **may be provided in phases without undue further delay** |
| **Art. 43(6)** | The controller shall **document any personal data breach** — facts, effects, remedial action — to facilitate the Authority's assessment |
| **Art. 44(1)** | The controller shall **communicate the breach to the data subject within 72 hours** after becoming aware |
| **Art. 44(2)** | The communication shall be **in clear language** and set out Art. 43(4)(b)–(d) |
| **Art. 44(3)** | Communication to the data subject is **not required** where (a) appropriate protection measures were applied rendering the data **unintelligible**, e.g. **encryption**; or (b) subsequent measures mean the high risk is **no longer likely to materialise**; or (c) it would involve **disproportionate effort** and a **public communication** is made instead |
| **Art. 44(4)** | The Authority **may require** communication where the controller has not made it |

| ID | Consequence for Bulbula |
| --- | --- |
| IR-7.4 | **72 hours is a real, confirmed statutory figure** — it is not invented here. **Whether and how it applies to a given Bulbula incident is PENDING COUNSEL** |
| IR-7.5 | Bulbula must be able to state **categories and approximate numbers** quickly. That capability depends on `data-inventory-v1.0.md` and PRIV-7 being real |
| IR-7.6 | **Phased notification is explicitly permitted** (Art. 43(5)) — incomplete knowledge is not a reason to miss the window |
| IR-7.7 | **Encryption at rest has a direct, documented legal benefit** via Art. 44(3)(a). This is the strongest argument for resolving the open encryption question (SO-8.9) |
| IR-7.8 | A **contact point must exist** for Art. 43(4)(b) — the DPO if appointed (L-4), otherwise a named role |
| IR-7.9 | **Processor notification obligations flow to Bulbula** (Art. 43(3)): contracts with Google, the email provider and the host must require prompt notification (VT §6) |

### 7.3 Internal breach runbook

```text
0  Suspect a personal-data breach
1  Record the time of awareness          ← the Art. 43(1) clock starts here
2  Name the incident lead
3  Contain                               ← do not wait for legal advice
4  Instruct counsel immediately          ← in parallel with step 3
5  Scope: whose data, which categories, how many, how long
6  Preserve evidence; build the timeline
7  Assemble the Art. 43(4) content: nature · contact · consequences · measures
8  Counsel decides: notify the Authority? notify data subjects?
9  Act on counsel's decision; keep proof of what was sent and when
10 Document per Art. 43(6)
11 Post-incident review
```

| ID | Rule |
| --- | --- |
| IR-7.10 | **Step 1 is written down at the moment it happens.** "When did you become aware?" is the first question a regulator asks |
| IR-7.11 | **Steps 3 and 4 run in parallel.** Containing is never delayed for legal advice; instructing counsel is never delayed for containment |
| IR-7.12 | **No deadline other than those quoted in §7.2 is asserted anywhere in Bulbula's documentation or communications** |
| IR-7.13 | **No exemption is assumed.** Art. 44(3) exceptions are **counsel's to apply**, not engineering's |

---

## 8. Communication

| Audience | Who approves | Principles |
| --- | --- | --- |
| **Internal team** | Incident lead | Immediate, factual, no blame |
| **Project owner** | — | Immediately for High and Critical |
| **Counsel** | Owner | As soon as personal data may be involved |
| **The Authority** | **Counsel** | Art. 43(4) content; phased if necessary |
| **Affected data subjects** | **Counsel** | Art. 44(2): clear language, nature, contact, consequences, measures |
| **Affected businesses** | Owner | Where their listing or data is affected |
| **The public** | Owner, with counsel | Only where genuinely warranted or where Art. 44(3)(c) applies |

| ID | Rule | Source |
| --- | --- | --- |
| IR-8.1 | **No engineer communicates externally about an incident** | IR-4.2 |
| IR-8.2 | **No speculation.** State what is known, what is not yet known, and when more will be said | CDN §6 |
| IR-8.3 | **No false reassurance.** "No data was accessed" is said only when it is actually established | PEH-2.8 |
| IR-8.4 | A communication to a data subject **must not itself leak** another person's data | TR-201 |
| IR-8.5 | Public statements are **not** made to manage perception ahead of fact | IR-8.2 |
| IR-8.6 | **A security incident is never hidden to protect a commercial relationship** | TS-16 |

---

## 9. Documentation

Every incident, regardless of severity, produces a record containing:

| Field | Notes |
| --- | --- |
| Identifier and title | — |
| **Time of awareness** | The Art. 43(1) anchor |
| Class and severity | §2, §3; with any re-rating and why |
| Incident lead | Named |
| Timeline | Contemporaneous (IR-5.8) |
| Affected assets and data categories | Including approximate counts (Art. 43(4)(a)) |
| **Was personal data involved?** | **Always answered explicitly, even when the answer is no** |
| Containment, eradication, recovery actions | — |
| Counsel instructed? | Yes/no, when |
| Notifications made | To whom, when, by whom, with proof |
| Root cause | — |
| Follow-up actions | Each with a named owner and a date |
| Status | Open / closed |

| ID | Rule | Source |
| --- | --- | --- |
| IR-9.1 | **The record is created at detection**, not written up afterwards | IR-5.8 |
| IR-9.2 | The record is **access-controlled**; it contains sensitive detail | IR-5.5 |
| IR-9.3 | The record satisfies Art. 43(6) for personal-data incidents | Art. 43(6) |
| IR-9.4 | **Incident records are kept as accountability evidence** (Art. 52); retention is **PENDING COUNSEL (L-21)** | RET §7 |
| IR-9.5 | **A security incident is recorded; a privacy incident is identifiable as such and escalated on its own path** | SAC-41, SAC-42 |

---

## 10. Post-incident review

| ID | Requirement |
| --- | --- |
| IR-10.1 | Every **High and Critical** incident gets a review, within a short and fixed period of closure |
| IR-10.2 | The review is **blameless**: it examines the system that allowed the failure, not the person at the keyboard |
| IR-10.3 | It answers: what happened, why, why it was not caught earlier, what changes, who owns each change, by when |
| IR-10.4 | **Detection is reviewed as rigorously as prevention.** "We found out from a user" is itself a finding |
| IR-10.5 | Actions go into the normal work queue with owners; **a review with no owned action has not finished** |
| IR-10.6 | The **threat model is updated** if the incident revealed a threat it missed |
| IR-10.7 | If a control was missing, the relevant `docs/50-security/` document is amended — **documentation is part of remediation** |
| IR-10.8 | **Recurrence of the same root cause escalates severity** on the second occurrence |

---

## 11. Unresolved items

| ID | Item | Status |
| --- | --- | --- |
| L-8 | Breach notification procedure and its application to Bulbula | **PENDING COUNSEL** |
| L-4 | Whether a DPO must be appointed — Art. 43(4)(b) contact point | **PENDING COUNSEL** |
| L-21 | Retention of incident records and evidence | **PENDING COUNSEL** |
| L-15 | Review-content liability and takedown — malicious-content incidents | **PENDING COUNSEL** |
| D-45 | Staff authentication strength — the likeliest Critical incident path | **Owner decision, open** |
| D-42 / D-42b | Data location — a foreign backup leak is also a transfer breach | **Owner decision, open** |
| — | Backup and at-rest encryption, engaging Art. 44(3)(a) | **Open — security decision** |
| — | Named incident lead and deputy at launch | **Open — implementation detail** |
| — | Counsel engagement route and out-of-hours contact | **Open — implementation detail.** Must exist before launch |
| — | Whether Bulbula publishes a security contact or disclosure policy | **Open — product decision** |

---

## Legal and regulatory references

| Reference | Provision | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, Art. 2(7) | Definition of personal data breach | Confirmed — statute/regulation |
| Art. 43(1) | **72 hours** to notify the Authority from awareness | Confirmed — statute/regulation |
| Art. 43(2) | Reasons required for late notification | Confirmed — statute/regulation |
| Art. 43(3) | Processor notifies controller without undue delay | Confirmed — statute/regulation |
| Art. 43(4) | Required content of the notification | Confirmed — statute/regulation |
| Art. 43(5) | Phased notification permitted | Confirmed — statute/regulation |
| Art. 43(6) | Duty to document breaches | Confirmed — statute/regulation |
| Art. 44(1)–(2) | **72 hours** to communicate to data subjects; required content | Confirmed — statute/regulation |
| Art. 44(3) | Exceptions, including unintelligibility/encryption and disproportionate effort | Confirmed — statute/regulation |
| Art. 44(4) | Authority may compel communication | Confirmed — statute/regulation |
| Art. 52 | Accountability — ability to demonstrate compliance | Confirmed — statute/regulation |
| Art. 5(11), 5(14), 5(15) | Authority's investigation, enforcement-notice and fining powers | Confirmed — statute/regulation |
| **Whether any given Bulbula incident is notifiable** | — | **PENDING COUNSEL (L-8)** |
| **Whether an Art. 44(3) exception applies** | — | **PENDING COUNSEL** |
| ECA breach-reporting channel, forms and operational process | — | **Unknown** — not verifiable in this phase |

---

## Decision references

D-42, D-42b, D-45.
