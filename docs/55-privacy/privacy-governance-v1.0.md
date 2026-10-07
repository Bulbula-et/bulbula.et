# Privacy Governance

| | |
| --- | --- |
| **Document** | Privacy Governance — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

How Bulbula governs personal data in V1: its role as controller and
processor, responsibility boundaries, the governance owner, the DPO
question, records of processing, vendor oversight, the privacy review
process, access control, auditability, lawful basis, transparency,
minimisation, minors, automated decision-making — and the **compliance
register** (§12).

**Out of scope.** The data itself (`data-inventory-v1.0.md`), design
controls (`privacy-by-design-v1.0.md`), periods
(`data-retention-v1.0.md`), request handling
(`data-subject-rights-v1.0.md`), vendors
(`vendor-and-transfer-register-v1.0.md`), notice content
(`privacy-notice-requirements-v1.0.md`).

## Authority

Below owner decisions, the register, the PRD, the TRD, UX/UI and
platform specifications. **This document changes no product decision.**

> **No statement here is a claim of legal compliance.** Every legal
> statement is classified. Where applicability to Bulbula is uncertain,
> the item is **PENDING COUNSEL** and **must not** be resolved by
> engineering judgement.

---

## 1. Privacy is not security

| ID | Statement | Source |
| --- | --- | --- |
| PG-1.1 | **Security protects data. Privacy limits it.** Encrypting data that should never have been collected is a security success and a privacy failure | SEC-1.1 |
| PG-1.2 | A Bulbula system is privacy-correct when it collects the minimum for a stated purpose, uses it only for that purpose, keeps it only as long as needed, shares it with the fewest parties, and lets people exercise their rights | Art. 6 |
| PG-1.3 | **"We secured it" is never an answer to "why do you have it?"** | PG-1.1 |
| PG-1.4 | Privacy obligations apply regardless of breach. Most privacy failures are quiet and lawful-looking | PG-1.2 |

---

## 2. Does the law apply to Bulbula?

| Question | Answer | Classification |
| --- | --- | --- |
| Is Bulbula processing personal data? | **Yes** — Customer email addresses, Reviews, account data; personal contact points in listings; staff records; logs containing online identifiers | Confirmed — statute/regulation (Art. 2(2)) |
| Is Bulbula within territorial scope? | **Yes** — established and operating in Ethiopia, processing data of people in Ethiopia | Confirmed — statute/regulation (Art. 3) |
| Does any Art. 3(4) exclusion apply? | **No exclusion plausibly applies.** The four exclusions are purely personal/household activity, inter-agency need-to-know exchange, restricted application, and mere transit | Confirmed — statute/regulation (Art. 3(4)); **applicability: Counsel interpretation required** |
| Does Bulbula process **sensitive** personal data? | **Not by design.** No field solicits any Art. 2(5) category. It may arrive unsolicited in Review or report text | Confirmed — statute/regulation (Art. 2(5), Art. 9); **PENDING COUNSEL** on consequences |

| ID | Rule |
| --- | --- |
| PG-2.1 | **Bulbula does not claim an exemption.** No exemption is assumed, relied on or stated anywhere |
| PG-2.2 | **"Business data" is not outside the law.** The Proclamation protects natural persons; much business data relates to natural persons (PCP-1, D-51) |
| PG-2.3 | Unsolicited sensitive data in free text is handled as a **moderation and deletion matter**, never normalised into a field |

---

## 3. Roles

### 3.1 Bulbula as controller

| Processing | Role | Why |
| --- | --- | --- |
| Customer accounts, Saves, Reviews, reports | **Controller** | Bulbula determines purposes and means |
| Listing data including personal contact points | **Controller** | Bulbula decides what to collect and publish (D-50) |
| Staff operational records, audit, provenance | **Controller** | — |
| Security and application logs | **Controller** | — |
| Analytics events and aggregates | **Controller** | — |
| Advertising campaign records | **Controller** | — |

| ID | Statement |
| --- | --- |
| PG-3.1 | **Bulbula is a data controller for all V1 processing** |
| PG-3.2 | **Bulbula is not a processor for anyone** in V1. No capability processes data on another organisation's behalf and instructions |
| PG-3.3 | **Businesses are not joint controllers.** A business supplies information about itself under Permission (D-50); it does not determine purposes or means. Art. 51 joint control does not arise |
| PG-3.4 | Whether any arrangement nonetheless creates joint control is **Counsel interpretation required** |
| PG-3.5 | **Registration obligations attach to controllers *and* processors** (Art. 33), so PG-3.2 does not reduce the registration duty |

### 3.2 Bulbula's processors

Detailed in `vendor-and-transfer-register-v1.0.md`.

| Processor | Processes | Art. 16 contract required |
| --- | --- | --- |
| Hosting provider | Everything at rest | **Yes** |
| Transactional email provider (D-41) | Email addresses, message content | **Yes** |
| Google (authentication) | Email, provider identifier | **Relationship requires counsel classification** |
| Maps provider (D-21) | Potentially location/request data | **Assessment required** |
| Telegram | Hosts the surface | **Relationship requires counsel classification** |

| ID | Rule | Source |
| --- | --- | --- |
| PG-3.6 | **Art. 16(3) requires a written contract**, processing on the controller's instructions only, and obligations equivalent to the controller's | Art. 16(3) |
| PG-3.7 | **Art. 16(2) requires choosing a processor giving sufficient guarantees** on technical and organisational measures, and taking reasonable steps to verify compliance | Art. 16(2) |
| PG-3.8 | **No personal data is sent to a vendor that has not been assessed and registered** | VT §1 |
| PG-3.9 | Whether a large platform's standard terms satisfy Art. 16(3) is **PENDING COUNSEL (L-10, L-12)** | — |

### 3.3 Responsibility boundaries

| Boundary | Bulbula's side | The other side |
| --- | --- | --- |
| **Bulbula / Customer** | Lawful processing, security, rights, transparency | The accuracy of what they voluntarily write |
| **Bulbula / Business** | Accuracy of what is published; prompt correction; Permission records | Accuracy of what it supplies; whether it may share a named individual's details |
| **Bulbula / Processor** | Instructions, contract, assessment, oversight | Executing only on instruction; notifying breaches (Art. 43(3)) |
| **Bulbula / Authority** | Registration, records, cooperation, notification | Supervision and enforcement |
| **Bulbula / Counsel** | Factual description of processing | Legal determinations |

---

## 4. Governance owner

| ID | Statement |
| --- | --- |
| PG-4.1 | **The project owner is accountable for privacy.** Accountability is not delegable (Art. 52) |
| PG-4.2 | **Art. 52(2) requires Bulbula to be able to *demonstrate* compliance**, not merely to be compliant. Evidence is the deliverable |
| PG-4.3 | Day-to-day operation may be delegated; **the decisions in this document set are the owner's** |
| PG-4.4 | **No engineer, operator or contractor may decide a lawful basis, a retention period, an age threshold, a transfer mechanism or a notification question** |
| PG-4.5 | Every open privacy item in this document set has a named decision-maker: **owner**, **counsel**, or both |

### 4.1 Records of processing — Art. 46

Mandatory. Art. 46(2) requires: controller name and contact; purposes;
categories of data subjects and personal data; categories of recipients
including those in other countries; transfers and safeguards; envisaged
erasure time limits where possible; and the description of data-security
mechanisms.

| ID | Rule |
| --- | --- |
| PG-4.6 | **`data-inventory-v1.0.md` is the substrate of the Art. 46 record.** It is structured to carry every Art. 46(2) field |
| PG-4.7 | **It is not yet the record itself.** The formal record is produced and maintained by the owner, informed by counsel, with registration (L-3) |
| PG-4.8 | The record is **made available to the Authority on request** (Art. 46(3)) |
| PG-4.9 | **Art. 46(4) additionally requires logging** of personal-data processing activities including **reading**, with enough detail to establish reasoning, date, time, actor and recipients. This is a strong statutory basis for TS-20 and PRIV-6 |
| PG-4.10 | **The Authority sets log retention periods (Art. 46(4)(e)).** Those periods are **Unknown** |
| PG-4.11 | **The record is kept current.** A new field, purpose, vendor or transfer updates it before the change ships |

---

## 5. The DPO question

| Art. 40(1) trigger | Does it apply to Bulbula? | Classification |
| --- | --- | --- |
| (a) Processing by a **government body** | **No.** Bulbula is private | Confirmed — statute/regulation |
| (b) Core activities require **regular and systematic monitoring of data subjects on a large scale** | **Argument against:** Bulbula's core activity is publishing business information, not monitoring people; there is no behavioural advertising, no profiling, and minimal analytics (AN-3). **Argument for:** a public directory with accounts and analytics could be characterised as systematic. **"Large scale" is undefined in the Proclamation** | **PENDING COUNSEL (L-4)** |
| (c) Core activities are **large-scale processing of sensitive personal data** | **No by design.** No sensitive category is solicited (PG-2.3) | Confirmed — statute/regulation; **applicability: Counsel interpretation required** |

| ID | Rule |
| --- | --- |
| PG-5.1 | **Whether Bulbula must appoint a DPO is PENDING COUNSEL (L-4).** It is not decided here |
| PG-5.2 | **The preliminary view that no trigger clearly applies is not a legal conclusion** and must not be cited as one |
| PG-5.3 | **A contact point for privacy matters exists regardless of the DPO question.** Art. 24(1)(a) requires controller contact details and Art. 43(4)(b) requires a breach contact point |
| PG-5.4 | If a DPO is appointed: Art. 40(5) requires **publishing the contact details and communicating them to the Authority**; Art. 41 sets the duties; Art. 41(2) permits a staff member provided there is **no conflict of interest** |
| PG-5.5 | **A DPO who also decides product and commercial priorities would be a conflict of interest** under Art. 41(2). In a company this small that constraint is real and is flagged now |

---

## 6. Registration — Art. 33

| ID | Statement | Classification |
| --- | --- | --- |
| PG-6.1 | **Controllers and processors must be registered with the Authority in order to process personal data** (Art. 33(1)) | Confirmed — statute/regulation |
| PG-6.2 | **Separate register entries are made per purpose** where there are two or more (Art. 33(2)) | Confirmed — statute/regulation |
| PG-6.3 | **The Authority may set registration requirements by Directive** (Art. 33(3)) | Confirmed — statute/regulation |
| PG-6.4 | A certificate is **valid two years and renewable** (Art. 35(2)) | Confirmed — statute/regulation |
| PG-6.5 | **Register contents are publicly inspectable** (Art. 39) | Confirmed — statute/regulation |
| PG-6.6 | The Authority may **refuse** registration for insufficient particulars (Art. 34) and **cancel** it for false information or non-compliance (Art. 38) | Confirmed — statute/regulation |
| PG-6.7 | **Whether the registration Directive has been issued, and the current process, portal, fees and timescales** | **Unknown.** The ECA platform was not reachable during this phase; secondary commentary indicated the Directive was still awaited as of mid-2025. **PENDING COUNSEL (L-3)** |

| ID | Rule |
| --- | --- |
| PG-6.8 | **Registration is a launch dependency, not a post-launch task.** Art. 33(1) frames it as a precondition to processing |
| PG-6.9 | **No launch date may assume a registration timescale** that has not been confirmed |
| PG-6.10 | **Renewal is tracked** so the certificate does not lapse (Art. 35(2)) |
| PG-6.11 | Because purposes are registered separately (Art. 33(2)), **the purpose list in `data-inventory-v1.0.md` must be stable and accurate before registration** |

---

## 7. Lawful basis

Art. 7(2) provides six bases: consent; contract or pre-contractual
steps; legal obligation; vital interests; public health, national
emergency or public authority function; and legitimate interests not
overridden by the data subject's fundamental rights.

### 7.1 Preliminary mapping — **none of this is settled**

| Purpose | Candidate basis | Status |
| --- | --- | --- |
| Operating a Customer account | Contract (7(2)(b)) | **PENDING COUNSEL (L-5)** |
| Sending an OTP to sign in | Contract or pre-contractual steps (7(2)(b)) | **PENDING COUNSEL (L-5)** |
| Publishing a Review attributed to a display name | Contract or legitimate interests (7(2)(f)) | **PENDING COUNSEL (L-5)** |
| Publishing a **personal contact point** on a listing | Legitimate interests (7(2)(f)), or consent (7(2)(a)) | **PENDING COUNSEL (L-5)** — the hardest question in this set |
| Handling a report or correction | Legitimate interests; legal obligation | **PENDING COUNSEL (L-5)** |
| Security logging and abuse prevention | Legitimate interests (7(2)(f)) | **PENDING COUNSEL (L-5)** |
| Audit and accountability records | Legal obligation (7(2)(c)) via Art. 46 and Art. 52 | **PENDING COUNSEL (L-5)** |
| Aggregate, non-identifying analytics | Legitimate interests — **arguably outside scope once genuinely non-identifying** | **PENDING COUNSEL (L-5)** |
| Advertising campaign records | Contract with the advertiser; legitimate interests | **PENDING COUNSEL (L-5)** |

| ID | Rule |
| --- | --- |
| PG-7.1 | **No lawful basis is confirmed in this document.** The table is a structured question for counsel, not an answer |
| PG-7.2 | **A basis must be identified per purpose before launch**, because Art. 24(1)(f) requires telling the data subject the lawful basis |
| PG-7.3 | **Consent is not Bulbula's default basis.** Art. 8 makes it demanding: free, informed, specific, clear, **active action**, withdrawable at any time, **unbundled**, with the **burden of proof on the controller** (Art. 8(5)) |
| PG-7.4 | **Where consent is used, it must be evidenced.** A product that cannot prove consent has no basis |
| PG-7.5 | **Art. 8(4) forbids conditioning a service on consent to processing not necessary for it.** Bulbula must not gate browsing, search or reading on any consent |
| PG-7.6 | **Permission (business) is not consent (personal data).** The glossary already separates them; that separation has a legal reason and must hold |
| PG-7.7 | Where a personal contact point is published, **the business's Permission is not automatically that individual's consent** if the individual is a different person |
| PG-7.8 | **Withdrawal of consent must be as available as giving it** (Art. 8(3)), and the withdrawal route must be told to the person beforehand |

---

## 8. Automated decision-making

Art. 31(1) gives the right **not to be subject to a decision based
solely on automated processing, including profiling, which produces
legal effects or significantly affects** the data subject, plus rights
to human intervention and to express a view.

| V1 system | Solely automated? | Decision about a **person**? | Legal or significant effect on that person? | Preliminary view |
| --- | --- | --- | --- | --- |
| **Organic search ranking** | Yes | **No** — it orders *businesses*, and a business is not a natural person (and in V1 is not a user at all, D-02/D-54) | Not on the searcher | **Likely outside Art. 31.** The searcher is not the subject of a decision; the ranked entity is usually a legal person |
| **Filtering** | Yes | No | No | Likely outside |
| **Nearby / distance** | Yes | No — it sorts places | No | Likely outside |
| **Campaign delivery** | Yes | No — fixed packages, no targeting of individuals (D-10) | No | Likely outside |
| **Review anti-abuse / rate limiting** | Partly | **Yes** — about a Customer | **Possibly** — blocking someone from reviewing affects them | **The strongest candidate. PENDING COUNSEL** |
| **Account suspension or moderation** | **No** — staff decide (TS-9) | Yes | Yes | **Human intervention is already the design.** Keeping it that way is the control |
| **Fraud detection** | Not in V1 | — | — | Not applicable |
| **Personalised recommendation** | **Does not exist in V1** | — | — | Not applicable |

| ID | Rule | Source |
| --- | --- | --- |
| PG-8.1 | **Whether any V1 system constitutes Art. 31 automated decision-making is PENDING COUNSEL** | Art. 31 |
| PG-8.2 | **Where an automated control affects a person, a human route must exist.** Automated anti-abuse must be appealable to a person — this is the design regardless of the legal answer | Art. 31(1)(b) |
| PG-8.3 | **Moderation remains a human decision with a recorded policy basis** (TS-9). Automated moderation would change the analysis and is a **product decision**, not a technical one | TS-9 |
| PG-8.4 | **This phase must not introduce automated decision-making.** No scoring of people, no risk profiling, no behavioural segmentation is added by any security or privacy control here | Phase brief §20 |
| PG-8.5 | **No personalised recommendation system exists or is implied.** Introducing one is a product decision with a fresh Art. 31 and Art. 47 analysis | PG-8.4 |
| PG-8.6 | **Art. 31(3) forbids automated evaluation based on sensitive personal data.** Bulbula processes none by design (PG-2.3) | Art. 31(3) |
| PG-8.7 | **Art. 11(4) prohibits profiling of minors entirely** — reinforcing that no profiling is built | Art. 11(4) |
| PG-8.8 | If Art. 31 applies anywhere, **Art. 24(1)(m) requires disclosing it in the notice** | PNR §3 |

---

## 9. Minors

**The age question is open and must not be answered here.**

| Art. 11 provision | Content | Classification |
| --- | --- | --- |
| 11(1) | Processing must **protect and advance the minor's rights and best interests**; the **controller bears the burden of proof** | Confirmed — statute/regulation |
| 11(2) | Lawful where **consent is given or authorised by a parent, guardian or tutor**, or processing is necessary to the minor's vitally important interest | Confirmed — statute/regulation |
| 11(3) | The controller shall make **reasonable efforts to verify age** and that parental consent was given, **taking available technology into account** | Confirmed — statute/regulation |
| 11(4) | **Processing a minor's personal data for marketing, profiling or merging of profiles is prohibited** | Confirmed — statute/regulation |
| 12(2) | Information addressed to a minor requires **special attention** | Confirmed — statute/regulation |
| **Who is a "minor"** | **The Proclamation's threshold is not established in the text reviewed.** Secondary summaries citing "under 16" appear to derive from an earlier draft and are **not relied on** | **PENDING COUNSEL** |

| ID | Rule |
| --- | --- |
| PG-9.1 | **Whether Bulbula permits minors to create Customer accounts is PENDING COUNSEL and Open — product decision (D-46)** |
| PG-9.2 | **The applicable age threshold is PENDING COUNSEL.** No number is written anywhere in Bulbula's documentation or product until counsel confirms it |
| PG-9.3 | **Whether parental or guardian involvement is required, and how it could be evidenced, is PENDING COUNSEL** |
| PG-9.4 | **No date-of-birth field is introduced to solve this technically** (AS-2.4). Collecting a birth date from everyone to manage a minority of cases is itself a minimisation failure |
| PG-9.5 | **Art. 11(3)'s "reasonable efforts … taking into account available technology" is a proportionality test**, not a mandate for identity verification. What is reasonable for Bulbula is **PENDING COUNSEL** |
| PG-9.6 | **Bulbula already satisfies Art. 11(4) structurally**: no marketing to accounts, no profiling, no profile merging (PBD §7) |
| PG-9.7 | **Browsing requires no account** (GS-1), so a minor can use the core product without any processing beyond ordinary logs |
| PG-9.8 | **Nothing in V1 is directed at children.** A business directory is a general-audience service — relevant to, but not determinative of, the analysis |
| PG-9.9 | Until resolved, **no claim about minimum age appears in the privacy notice, terms or any interface** |

---

## 10. Access control and auditability

| ID | Requirement | Source |
| --- | --- | --- |
| PG-10.1 | **Access to personal information is restricted and logged** | PRIV-6, TS-20 |
| PG-10.2 | Staff see personal data **because a task requires it**, under a named permission (TD-03) | SEC-3.1 |
| PG-10.3 | **Art. 46(4) requires logging reading, disclosure and transmission** with reasoning, date, time, actor and recipients | Art. 46(4) |
| PG-10.4 | Access logs are **reviewable**, and reviewing them is a scheduled activity | SO-5.5 |
| PG-10.5 | **Staff are told their access to personal data is logged.** Covert staff monitoring is itself a privacy problem | SO-3.8 |
| PG-10.6 | **Art. 16(1) requires reasonable steps to ensure the reliability of employees with access** — briefing and access review are those steps | Art. 16(1) |
| PG-10.7 | **Art. 16(4) requires that individuals acting under the controller's authority process personal data only on instruction** | Art. 16(4) |
| PG-10.8 | **Bulk export of personal data is a privileged, audited action**, not a routine one | SO-4.2 |
| PG-10.9 | **A privacy incident is distinguishable from a security incident** and escalates on its own path | SAC-42, IR §7 |

---

## 11. Privacy review process

A change needs privacy review when it: introduces a new personal-data
field; changes a purpose; adds a recipient or vendor; changes retention;
publishes something previously internal; adds a new surface or
collection point; or adds automated evaluation of a person.

```text
proposed change
   → does it touch personal data?      no  → normal review
   → yes: state purpose, fields, basis, recipients, retention, rights impact
   → does it increase risk to people?  no  → update the inventory, proceed
   → yes: Art. 47 DPIA test (§11.1)
   → DPIA indicates high risk?         yes → Art. 48(2) prior consultation
   → owner decision; counsel where the question is legal
```

| ID | Rule |
| --- | --- |
| PG-11.1 | **A change that adds a personal-data field without a stated purpose is rejected** (PRIV-2) |
| PG-11.2 | **The inventory is updated as part of the change**, not afterwards (PG-4.11) |
| PG-11.3 | **Data protection by design and by default is a statutory duty** (Art. 49), not a style preference |
| PG-11.4 | **A security control that collects more personal data is a privacy change** and goes through this process (PG-1.1) |

### 11.1 DPIA — Art. 47

Art. 47(1) requires a DPIA **prior to processing** where operations may
risk rights and freedoms. Art. 47(2) enumerates triggers; Art. 47(3)
sets the required content; Art. 47(4) says the views of data subjects
should be sought where appropriate.

| Art. 47(2) trigger | Bulbula V1 | Classification |
| --- | --- | --- |
| (a) Systematic extensive evaluation based on automated processing, including profiling, with legal or significant effects | **No profiling in V1** (PBD §7) | Preliminary: not triggered — **Counsel interpretation required** |
| (b) **Large-scale** processing of **sensitive** personal data | **None by design** (PG-2.3) | Preliminary: not triggered — **Counsel interpretation required** |
| (c) Systematic monitoring of a publicly accessible area on a large scale | **No.** Bulbula does not monitor physical areas | Preliminary: not triggered |
| (d) Any other operations for which consultation with the Authority is required | **The Authority publishes this list** (Art. 48(4)); its current content is **Unknown** | **Unknown — PENDING COUNSEL** |

| ID | Rule |
| --- | --- |
| PG-11.5 | **L-22 already requires a DPIA covering accounts, Reviews and cross-border transfers.** That requirement stands regardless of the Art. 47(2) analysis |
| PG-11.6 | **A DPIA is cheap insurance and demonstrates accountability** (Art. 52(2)). The preliminary view that no trigger clearly applies is **not** a reason to skip it |
| PG-11.7 | **The DPIA is counsel-reviewed**, not an engineering artefact |
| PG-11.8 | **Art. 48(1) prior authorization is a separate and more pressing question**: it applies where the controller **cannot provide appropriate safeguards for transfer to a third-party jurisdiction** — directly relevant to Google, email and Maps (VT §5). **PENDING COUNSEL** |
| PG-11.9 | **Art. 48(3) allows the Authority to prohibit intended processing.** Prior authorization is therefore a launch risk, not a formality |

---

## 12. Compliance register

**Status vocabulary:** `Confirmed` · `Open` · `PENDING COUNSEL` ·
`Not applicable` · `Future`.

> **A design document is not compliance.** No row is marked `Confirmed`
> because a Bulbula document addresses it. `Confirmed` here means **the
> legal requirement itself is confirmed from the enacted text** — never
> that Bulbula is compliant with it. Bulbula's compliance position is
> counsel-controlled and is not asserted anywhere in this document set.

| # | Requirement | Source | Legal status | Bulbula applicability | Implementation implication | Owner | Evidence required | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| REG-01 | Processing principles: lawful, fair, transparent; purpose-limited; adequate and not excessive; accurate; storage-limited; secure; sovereign | Art. 6 | Confirmed | Applies | Every document in this set | Owner | Inventory, retention schedule, notice | **Open** |
| REG-02 | At least one lawful basis per processing | Art. 7 | Confirmed | Applies | Basis per purpose before launch | Counsel | Documented basis mapping | **PENDING COUNSEL (L-5)** |
| REG-03 | Consent must be free, informed, specific, clear, active, unbundled, withdrawable; **burden of proof on controller** | Art. 8 | Confirmed | Applies **where consent is used** | Consent evidence mechanism if used | Counsel | Consent records | **PENDING COUNSEL (L-5)** |
| REG-04 | Sensitive personal data processing prohibited save exceptions | Art. 9 | Confirmed | **None solicited** | Keep it out of fields; moderate free text | Owner | Field list; moderation policy | **Open** |
| REG-05 | Minors: best interests, parental consent, age verification efforts, **no marketing/profiling/profile merging** | Art. 11 | Confirmed | **Unresolved** | Age policy; no profiling | Counsel + Owner | Age policy; notice wording | **PENDING COUNSEL (D-46, L-4)** |
| REG-06 | Fairness and transparency; clear, plain, accessible information | Art. 12 | Confirmed | Applies | Notice and in-context disclosure | Owner | Published notice | **Open (L-6)** |
| REG-07 | Purpose limitation; purpose specified before further processing | Art. 13 | Confirmed | Applies | Purpose per field (PRIV-2) | Owner | Inventory | **Open** |
| REG-08 | Accuracy; reasonable steps; record contested inaccuracy | Art. 14 | Confirmed | Applies | Correction route (TS-1…TS-5); rectification (DSR §4) | Owner | Correction records | **Open** |
| REG-09 | Storage limitation — reasonable period necessary, or defined by law | Art. 15 | Confirmed | Applies | Retention schedule | Counsel | Schedule + pruning jobs | **PENDING COUNSEL (L-21, D-46)** |
| REG-10 | Integrity and confidentiality; employee reliability; **written processor contract**; instruction-only processing | Art. 16 | Confirmed | Applies | DPAs with host, email, others | Owner + Counsel | Signed contracts | **PENDING COUNSEL (L-10, L-12)** |
| REG-11 | Security measures appropriate to risk; pseudonymisation/encryption; CIA and resilience; **timely restoration**; **regular testing of effectiveness** | Art. 17 | Confirmed | Applies | `docs/50-security/`; restore tests | Owner | Test records; control review | **Open** |
| REG-12 | Transfer only where the third-party jurisdiction ensures appropriate protection | Art. 18, 19 | Confirmed | **Applies — Google, email, Maps, any CDN** | Per-vendor assessment | Counsel | Transfer assessment | **PENDING COUNSEL (L-10, L-12)** |
| REG-13 | Cross-border transfer conditions: Authority determination, explicit informed consent, necessity, or public register | Art. 20 | Confirmed | Applies | Choose and document a basis per vendor | Counsel | Documented basis | **PENDING COUNSEL (L-10, L-12)** |
| REG-14 | Authority may demand safeguard evidence; may prohibit or suspend transfers | Art. 21 | Confirmed | Applies | Be able to evidence safeguards | Owner | Vendor register | **Open** |
| REG-15 | **Data sovereignty — locally collected personal data stored on a server or data centre in Ethiopia** | **Art. 22(1)** | Confirmed | **Applies** | LOC-1; hosting choice; **backup location** | Owner + Counsel | Hosting contract; backup location | **PENDING COUNSEL (L-2, D-42)** |
| REG-16 | Authority may designate **critical personal data** for Ethiopia-only processing | Art. 22(2) | Confirmed | Current designations **Unknown** | Monitor | Counsel | — | **Unknown** |
| REG-17 | **Cross-border transfer of sensitive personal data requires prior Authority approval** | Art. 22(3) | Confirmed | Not triggered **by design** | Keep sensitive data out | Owner | Field list | **Open** |
| REG-18 | Privacy rights **survive death for ten years**; heirs may invoke | Art. 23 | Confirmed | Applies | Deletion and rights handling | Counsel | DSR procedure | **PENDING COUNSEL** |
| REG-19 | Right to be informed — fifteen enumerated items | Art. 24 | Confirmed | Applies | Notice requirements | Owner + Counsel | Published notice | **Open (L-6)** |
| REG-20 | Right of access — free, reasonable intervals, without excessive delay; electronic or hard copy | Art. 25 | Confirmed | Applies | DSR §3 | Owner | Request log | **Open (L-7)** |
| REG-21 | Exceptions to access; refusal **in writing with detailed reasons** | Art. 26 | Confirmed | Applies | DSR §3.1 | Counsel | Refusal records | **PENDING COUNSEL (L-7)** |
| REG-22 | Rectification; **notify third parties informed in the prior year** | Art. 27 | Confirmed | Applies | DSR §4; downstream notification | Owner | Correction records | **Open** |
| REG-23 | Erasure, with exceptions; **inform third parties where data was made public** | Art. 28 | Confirmed | Applies | DSR §5; C-36 | Owner + Counsel | Deletion records | **PENDING COUNSEL (L-21)** |
| REG-24 | Right to object; **direct marketing objection is absolute** | Art. 29 | Confirmed | Applies | DSR §6; no marketing in V1 | Owner | Objection records | **Open** |
| REG-25 | Restriction of processing | Art. 30 | Confirmed | Applies | DSR §7 | Owner | Restriction records | **Open** |
| REG-26 | Automated individual decision-making rights | Art. 31 | Confirmed | **Analysis in §8** | Human route for automated controls | Counsel | §8 analysis | **PENDING COUNSEL** |
| REG-27 | Data portability — structured, commonly used, machine-readable; free; without excessive delay | Art. 32 | Confirmed | Applies to Customer-provided data | DSR §8 | Owner | Export format | **Open (L-7)** |
| REG-28 | **Registration with the Authority before processing**; per-purpose entries | Art. 33 | Confirmed | **Applies** | Launch dependency | Owner + Counsel | Registration certificate | **PENDING COUNSEL (L-3)** |
| REG-29 | Certificate valid two years, renewable | Art. 35(2) | Confirmed | Applies once registered | Renewal tracking | Owner | Certificate | **Open** |
| REG-30 | Duty to notify changes to registered particulars | Art. 36 | Confirmed | Applies once registered | Change process | Owner | Notifications | **Open** |
| REG-31 | **DPO appointment** where triggers met | Art. 40 | Confirmed | **Triggers analysed, not settled** | Appoint or document why not | Counsel | Appointment or reasoned record | **PENDING COUNSEL (L-4)** |
| REG-32 | Technical and organisational measures: security, **records**, **DPIA**, **prior authorization**, **DPO** | Art. 42 | Confirmed | Applies | All five addressed in this set | Owner | Evidence per item | **Open** |
| REG-33 | **Breach notification to the Authority within 72 hours**; reasons if late | Art. 43 | Confirmed | Applies | IR §7 | Counsel | Incident records | **PENDING COUNSEL (L-8)** |
| REG-34 | **Breach communication to data subjects within 72 hours**, with exceptions | Art. 44 | Confirmed | Applies | IR §7 | Counsel | Incident records | **PENDING COUNSEL (L-8)** |
| REG-35 | Authority may conduct **prior security checks** and inspections | Art. 45 | Confirmed | Applies | Be inspectable | Owner | Control documentation | **Open** |
| REG-36 | **Record of processing operations**, including logging of reading/disclosure/transmission | Art. 46 | Confirmed | Applies | PG §4.1; access logging | Owner | The record; access logs | **Open** |
| REG-37 | **DPIA** prior to risky processing | Art. 47 | Confirmed | **Required by L-22 regardless** | Produce a DPIA | Counsel | DPIA document | **PENDING COUNSEL (L-22)** |
| REG-38 | **Prior authorization** where transfer safeguards cannot be provided; **prior consultation** on high-risk processing | Art. 48 | Confirmed | **Potentially applies to Google, email, Maps** | May gate launch | Counsel | Authorization or reasoned record | **PENDING COUNSEL** |
| REG-39 | **Data protection by design and by default**; by default only necessary data; **not accessible to an indefinite number of people without intervention** | Art. 49 | Confirmed | Applies | `privacy-by-design-v1.0.md` | Owner | Design records | **Open** |
| REG-40 | **Duty to destroy** when purpose lapses, **preventing reconstruction in intelligible form**; notify processors | Art. 50 | Confirmed | Applies | Deletion semantics; backup expiry | Owner + Counsel | Deletion evidence | **PENDING COUNSEL (L-21)** |
| REG-41 | Joint controllers determine responsibilities by contract | Art. 51 | Confirmed | **Not applicable** on the current analysis (PG-3.3) | — | Counsel | — | **Not applicable** |
| REG-42 | **Accountability — must be able to demonstrate compliance** | Art. 52 | Confirmed | Applies | Evidence for every row above | Owner | This register, maintained | **Open** |
| REG-43 | Research exemptions | Art. 54 | Confirmed | **Not applicable** — Bulbula does no research processing | — | — | — | **Not applicable** |
| REG-44 | Enforcement notices, administrative fines, criminal sanctions | Art. 55, 59, 60, 64 | Confirmed | Applies | Consequence of failure elsewhere | Owner | — | **Open** |
| REG-45 | ECA registration Directive, portal, forms, fees, timescales | ECA process | **Unknown** | Applies | Launch planning | Counsel | ECA confirmation | **Unknown — PENDING COUNSEL (L-3)** |
| REG-46 | ECA adequacy determinations for specific jurisdictions | Art. 5(10), 19 | **Unknown** | Directly affects Google, email, Maps | Vendor basis | Counsel | ECA confirmation | **Unknown — PENDING COUNSEL** |
| REG-47 | Art. 48(4) published list of operations requiring prior consultation | Art. 48(4) | **Unknown** | May apply | — | Counsel | ECA confirmation | **Unknown** |
| REG-48 | Review-content liability, takedown obligations, defamation | Outside Proclamation 1321/2024 | **Unknown** | Applies to Reviews | Moderation and takedown | Counsel | — | **PENDING COUNSEL (L-15)** |
| REG-49 | Photography of premises and people | Outside Proclamation 1321/2024 | **Unknown** | Applies to listing media | PBD §9 | Counsel | — | **PENDING COUNSEL (L-18)** |
| REG-50 | Advertising disclosure, invoicing, VAT and trade licence | Outside Proclamation 1321/2024 | **Unknown** | Applies | Commercial operations | Counsel | — | **PENDING COUNSEL (L-16, L-17, L-20)** |
| REG-51 | Google Maps Platform terms | Vendor contract | **Unknown** | Applies if Maps ships | D-21 | Counsel | — | **PENDING COUNSEL (L-19)** |

---

## 13. Unresolved items

| ID | Item | Decision-maker | Status |
| --- | --- | --- | --- |
| L-3 | ECA registration requirements and process | Counsel | **PENDING COUNSEL** — launch dependency |
| L-4 | Whether a DPO must be appointed | Counsel | **PENDING COUNSEL** |
| L-5 | Lawful basis per purpose | Counsel | **PENDING COUNSEL** |
| L-6 | Required content of the privacy notice | Counsel | **PENDING COUNSEL** |
| L-7 | Rights procedures and response windows | Counsel | **PENDING COUNSEL** |
| L-8 | Breach notification procedure | Counsel | **PENDING COUNSEL** |
| L-10 / L-12 | Cross-border transfer assessment per vendor | Counsel | **PENDING COUNSEL** |
| L-15 | Review liability and takedown | Counsel | **PENDING COUNSEL** |
| L-18 | Photography of premises and people | Counsel | **PENDING COUNSEL** |
| L-21 | Retention schedule per data class | Counsel | **PENDING COUNSEL** |
| L-22 | DPIA | Counsel | **PENDING COUNSEL** |
| L-2 / D-42 / D-42b | Data location policy and vendor | Owner + Counsel | **Open — privacy decision** |
| D-46 | Minimum account age; retention schedule | Counsel | **PENDING COUNSEL** |
| D-27 | Analytics granularity, retention, raw events | Owner | **Open — privacy decision** |
| D-43 | Permission record contents and retention | Owner | **Open — privacy decision** |
| D-34 | Review treatment after account deletion | Owner | **Open — product decision** |
| D-51 | Minimisation principle | Owner | **Approved** — binding |
| — | Whether Bulbula publishes a privacy contact before a DPO decision | Owner | **Open — privacy decision.** Art. 24(1)(a) needs one |
| — | Whether a conflict-free DPO is feasible at Bulbula's size | Owner | **Open — privacy decision** (Art. 41(2)) |

---

## Legal and regulatory references

All provisions are from **Personal Data Protection Proclamation
No. 1321/2024**, Federal Negarit Gazette No. 35, 24 July 2024.

| Articles | Subject | Classification |
| --- | --- | --- |
| 2, 3 | Definitions; scope and exclusions | Confirmed — statute/regulation |
| 6–17 | Principles, lawfulness, consent, sensitive data, minors, transparency, purpose, accuracy, storage, integrity, security | Confirmed — statute/regulation |
| 18–22 | Transfer, adequacy, cross-border conditions, safeguards, **data sovereignty** | Confirmed — statute/regulation |
| 23–32 | Data-subject rights | Confirmed — statute/regulation |
| 33–41 | Registration, register effects, DPO | Confirmed — statute/regulation |
| 42–52 | Controller and processor obligations | Confirmed — statute/regulation |
| 53–54 | Exemptions and research | Confirmed — statute/regulation |
| 55–64 | Monitoring, administrative decisions, sanctions | Confirmed — statute/regulation |
| **Applicability of any provision to Bulbula** | — | **Counsel interpretation required** |
| ECA directives, portal, adequacy determinations, Art. 48(4) list | — | **Unknown** — the ECA platform was not reachable in this phase |

> **Numbering caution.** Secondary summaries citing "consent at Art. 14",
> "adequacy at Art. 40" or "minors under 16" reflect an **earlier
> draft**. Only the enacted numbering above is used.

---

## Decision references

D-02, D-10, D-21, D-27, D-34, D-41, D-42, D-42b, D-43, D-46, D-50, D-51,
D-54.
