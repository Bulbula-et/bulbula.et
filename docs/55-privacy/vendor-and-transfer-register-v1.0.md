# Vendor and Transfer Register

| | |
| --- | --- |
| **Document** | Vendor and Transfer Register — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

Every third party that receives, stores or can access personal data in
V1; the transfer and residency questions each raises; the contract
terms required; and the onboarding process for new ones.

**Out of scope.** What the data is (`data-inventory-v1.0.md`),
retention (`data-retention-v1.0.md`), security controls
(`docs/50-security/`), the register of legal requirements
(`privacy-governance-v1.0.md` §12).

## Authority

**Descriptive and procedural.** It selects no vendor and approves no
transfer. Vendor selection is the owner's (D-41, D-42b, D-21); the
lawfulness of each transfer is counsel's (L-10, L-12).

> **The sharpest unresolved tension in Phase 3.5.** Art. 22(1) requires
> locally collected personal data to be stored in Ethiopia. The approved
> authentication model (D-48) requires Google. Transactional email
> (D-24) requires an email provider. Both are foreign. **This is not a
> documentation problem; it is a real conflict that counsel must
> resolve.**

---

## 1. Who counts

| ID | Rule |
| --- | --- |
| VT-1.1 | **Any party that receives, stores, transmits or can access personal data is in this register** — paid or free, contracted or merely used |
| VT-1.2 | **"We just use their free API" does not exempt a vendor** |
| VT-1.3 | **A new vendor is assessed and registered before any personal data reaches it**, not after | 
| VT-1.4 | **Adding a vendor is a privacy change** requiring review (PG §11) |
| VT-1.5 | **A vendor that only receives aggregate, non-identifying data is still listed**, with that noted |
| VT-1.6 | **A CDN, font host, script host or analytics endpoint sees IP addresses** — online identifiers under Art. 2(2) — and is therefore a vendor |
| VT-1.7 | **Bulbula loads no third-party front-end resources in V1** (CSP `default-src 'self'`), which is the reason the register is as short as it is |
| VT-1.8 | **The register is reviewed quarterly** (SO §14) |

---

## 2. Vendor roles

| Role | Meaning | Obligation |
| --- | --- | --- |
| **Processor** | Processes on Bulbula's instructions for Bulbula's purposes | Art. 16(2), 16(3): selection with sufficient guarantees, written contract, instruction-only processing, breach notification |
| **Independent controller** | Determines its own purposes for the same data | Not Bulbula's processor; a disclosure, with its own basis |
| **Conduit** | Mere transit without access to content | Art. 3(4)(d) excludes mere transit |

| ID | Rule |
| --- | --- |
| VT-2.1 | **Classification is counsel's, not engineering's.** Large platforms frequently act as independent controllers for their own purposes while calling themselves processors |
| VT-2.2 | **The classification determines the contract, the transfer basis and the notice wording** — it is consequential, not academic |
| VT-2.3 | **Where classification is unresolved, the stricter treatment applies** pending counsel |

---

## 3. The register

### 3.1 Hosting provider — **D-42b, open**

| Field | Entry |
| --- | --- |
| **Data received** | **Everything at rest**: accounts, Reviews, listings, media, logs, backups |
| **Purpose** | Running the application |
| **Role** | **Processor** |
| **Location** | **Undecided — the single most consequential open item in this register** |
| **Transfer / residency** | **Art. 22(1) applies directly.** If the host is outside Ethiopia, locally collected personal data is stored outside Ethiopia |
| **Contract required** | **Art. 16(3) written contract — yes** |
| **Status** | **Open (D-42, D-42b); PENDING COUNSEL (L-2)** |

| ID | Rule |
| --- | --- |
| VT-3.1 | **The hosting decision is a legal decision as much as a technical one.** It cannot be made on price and performance alone |
| VT-3.2 | **Backups follow the same analysis** (SO-8.7). A local host with foreign backups has not solved residency |
| VT-3.3 | **Whether Ethiopian shared hosting can meet Bulbula's technical requirements is itself unverified** — and if it cannot, the conflict is structural, not administrative |

### 3.2 Google — authentication (**D-48, approved**)

| Field | Entry |
| --- | --- |
| **Data received** | Sign-in request context; Google returns email address and account identifier |
| **Data sent by Bulbula** | Minimal: the OAuth request. **No Bulbula content is sent** |
| **Purpose** | Customer authentication — an approved product decision |
| **Role** | **Likely independent controller for its own purposes; possibly processor for the sign-in itself. PENDING COUNSEL** |
| **Location** | Outside Ethiopia |
| **Transfer** | **Cross-border. Art. 18, 19, 20 apply** |
| **Possible Art. 20(1) bases** | (a) Authority determination of adequate protection — **Unknown**; (b) **explicit informed consent** after being told the risks; (c) necessity for a contract **at the data subject's request** — arguably strong, since the user chooses the Google button; (d) public-register data — not applicable |
| **Contract** | Google's standard terms. **Whether they satisfy Art. 16(3) is PENDING COUNSEL** |
| **Status** | **PENDING COUNSEL (L-12)** |

| ID | Rule |
| --- | --- |
| VT-3.4 | **D-48 is approved and this document does not reopen it.** The transfer question is raised, not the product decision |
| VT-3.5 | **Art. 20(1)(c) looks like the most promising basis** — the user affirmatively chooses Google — but that is an observation for counsel, not a conclusion |
| VT-3.6 | **Email OTP exists as the alternative** (D-48), so no user is forced into a Google transfer. That materially strengthens the position and must be preserved |
| VT-3.7 | **Scopes stay minimal** (PBD-4.4). The less Google is asked for, the smaller the transfer |

### 3.3 Transactional email provider — **D-41, open**

| Field | Entry |
| --- | --- |
| **Data received** | **Recipient email address, OTP codes, notification content, delivery metadata** |
| **Purpose** | Delivering OTP codes and transactional notifications (D-24) |
| **Role** | **Processor** |
| **Location** | **Undecided; most candidates are outside Ethiopia** |
| **Transfer** | **Cross-border if foreign. Art. 18, 19, 20 apply** |
| **Sensitivity escalation** | **If DI-6.5 resolves that message content and metadata are Art. 2(5) communications data, then Art. 22(3) requires prior Authority approval for the transfer.** That would be decisive for vendor choice |
| **Contract** | **Art. 16(3) written contract — yes** |
| **Status** | **Open (D-41); PENDING COUNSEL (L-10)** |

| ID | Rule |
| --- | --- |
| VT-3.8 | **The email provider carries the authentication channel.** Its compromise is account compromise at scale (THR-17), so it is both the highest security-dependency and a live transfer question |
| VT-3.9 | **DI-6.5 must be resolved before D-41 is decided**, or the decision may have to be unwound |
| VT-3.10 | **Vendor-side retention of message content is a contract term**, not a Bulbula configuration (RET-7.4) |
| VT-3.11 | **An Ethiopian or self-hosted sending path should be evaluated** against deliverability — **Open — technical decision** |

### 3.4 Telegram — Mini App surface (**D-49, approved**)

| Field | Entry |
| --- | --- |
| **Data received** | Usage of the Mini App surface; Telegram inherently knows which of its users opened it |
| **Data sent by Bulbula** | Page content. **No Customer account data is sent to Telegram** |
| **Purpose** | Delivering an approved client surface |
| **Role** | **Platform / likely independent controller. PENDING COUNSEL** |
| **Location** | Outside Ethiopia |
| **Transfer** | **The usage signal is Telegram's own processing, not a Bulbula transfer** — but that reading is **PENDING COUNSEL** |
| **Status** | **PENDING COUNSEL (L-12); D-33 open** |

| ID | Rule |
| --- | --- |
| VT-3.12 | **D-33 is open and Telegram identity is not a login mechanism** (AS-10). **No Telegram identity data is stored** until D-33 is decided |
| VT-3.13 | **Deciding D-33 affirmatively would create a new transfer and a new register entry**, and must trigger a fresh assessment |
| VT-3.14 | **Users are told that using the Mini App means Telegram sees that usage** (PNR §5) |

### 3.5 Maps provider — **D-21, open**

| Field | Entry |
| --- | --- |
| **Data received** | Tile and geocoding requests, including **IP address** and potentially **user coordinates** |
| **Purpose** | Map display and Nearby |
| **Role** | **Likely independent controller. PENDING COUNSEL** |
| **Location** | Outside Ethiopia |
| **Transfer** | **Cross-border, and includes location data — expressly personal under Art. 2(2)** |
| **Contract** | Platform terms (L-19) |
| **Status** | **Open (D-21); PENDING COUNSEL (L-19)** |

| ID | Rule |
| --- | --- |
| VT-3.15 | **A map embed discloses the user's IP to the provider on page load**, before any interaction. That is a transfer that happens by rendering |
| VT-3.16 | **Loading maps only on interaction reduces the transfer**, and is preferred — **Open — implementation detail** |
| VT-3.17 | **Server-side proxying of map requests would change the analysis** and is worth evaluating, bearing in mind TD-07 |
| VT-3.18 | **Admitting a maps origin requires a CSP change** (APP §7), which is itself a reviewed change |

### 3.6 CDN, fonts, scripts, analytics

| Field | Entry |
| --- | --- |
| **Data received** | **None in V1** |
| **Status** | **No third-party front-end resources exist.** CSP is `default-src 'self'` |

| ID | Rule |
| --- | --- |
| VT-3.19 | **This is a deliberate privacy position**, not an accident of simplicity, and it should be defended against convenience |
| VT-3.20 | **Adding a CDN, web font or hosted script creates a vendor relationship and a transfer** and requires full assessment |
| VT-3.21 | **No third-party analytics** (AN-1). Introducing one would be the single largest privacy regression available to the project |

### 3.7 Other

| Party | Data | Status |
| --- | --- | --- |
| **Media storage / CDN** | Business and Branch imagery; request IP addresses on delivery | **No vendor in V1.** The storage adapter has a **local-filesystem implementation** that is the V1 default (TRD TR-84), so media never leaves the host. **D-25 is open**: selecting object storage or a delivery CDN creates a vendor, a processor relationship and a transfer, and triggers VT-3.20 and §7 in full |
| **Payment provider** | Advertiser payment data | **Not in V1** — payments are handled offline (D-11). Would be a major new entry |
| **Monitoring / APM** | Logs, error context | **Not in V1** (OT-07). Would be a processor (SO-5.10) |
| **Domain and DNS** | Query metadata | Infrastructure; **Open — implementation detail** |
| **Counsel and accountants** | Whatever is shared for advice | Professional relationships; outside this register but not outside the law |

---

## 4. Transfer basis

Art. 20(1) permits cross-border transfer only where one of four
conditions is met:

| Basis | Art. 20(1) | Bulbula assessment |
| --- | --- | --- |
| (a) **Authority determination** that the third-party jurisdiction ensures appropriate protection | 20(1)(a) | **Whether any determination exists is Unknown.** The ECA platform was unreachable in this phase |
| (b) **Explicit, informed consent** after being told of the risks | 20(1)(b) | Possible for Google sign-in; **weak for the hosting provider**, since a user cannot meaningfully consent to where the database lives |
| (c) **Necessary for a contract concluded in the data subject's interest or at their request** | 20(1)(c) | **Strongest candidate for Google (VT-3.5) and arguably for OTP email** |
| (d) Data from a **public register** | 20(1)(d) | Not applicable |

| ID | Rule |
| --- | --- |
| VT-4.1 | **A transfer basis is identified per vendor, per purpose** — not once for the whole project |
| VT-4.2 | **Art. 19 additionally requires assessing whether the third-party jurisdiction ensures an appropriate level of protection**, considering its law, rights and enforcement |
| VT-4.3 | **Art. 21 lets the Authority demand evidence of safeguards and suspend or prohibit a transfer.** Bulbula must be able to produce that evidence |
| VT-4.4 | **Art. 48(1) prior authorization applies where appropriate safeguards cannot be provided** — this is the live risk for Google, email and Maps, and it can **gate launch** (PG-11.8) |
| VT-4.5 | **Consent-based transfer is fragile**: it is withdrawable, must be unbundled (Art. 8(2)), and cannot be a condition of a service that does not need it (Art. 8(4)) |
| VT-4.6 | **No transfer basis is selected in this document** |

---

## 5. Residency — Art. 22

| ID | Statement | Classification |
| --- | --- | --- |
| VT-5.1 | **Art. 22(1): personal data collected locally shall be stored on a server or data centre located in Ethiopia** | Confirmed — statute/regulation |
| VT-5.2 | **Art. 22(2): the Authority may designate critical personal data to be processed only in Ethiopia.** Current designations **Unknown** | Confirmed — statute/regulation |
| VT-5.3 | **Art. 22(3): cross-border transfer of sensitive personal data requires prior Authority approval** | Confirmed — statute/regulation |
| VT-5.4 | **The relationship between Art. 22(1) storage and Art. 20 transfer is not resolved on the face of the text** — whether a lawful Art. 20 transfer satisfies Art. 22(1), or whether Art. 22(1) imposes an independent local-storage duty regardless | **Counsel interpretation required — the pivotal question** |

| ID | Consequence |
| --- | --- |
| VT-5.5 | **If Art. 22(1) is an independent duty, the hosting provider must be in Ethiopia** — including backups |
| VT-5.6 | **Google and the email provider would still receive data in use** even with local storage, so Art. 20 remains in play regardless of how VT-5.4 resolves |
| VT-5.7 | **`deployment.md` EN-4 and BK-6 cite residency as "Art. 20". The correct citation is Art. 22.** The requirement is right; the reference is wrong and should be corrected in a future revision of that document. **This phase does not modify it** |
| VT-5.8 | **D-42 cannot be decided without VT-5.4**, and L-2 is therefore a launch dependency |

---

## 6. Required contract terms

For any processor, Art. 16(3) requires a written contract. The terms
Bulbula must obtain:

| ID | Term | Source |
| --- | --- | --- |
| VT-6.1 | Process **only on Bulbula's documented instructions** | Art. 16(3) |
| VT-6.2 | **Obligations equivalent to the controller's** under the Proclamation | Art. 16(3) |
| VT-6.3 | **Confidentiality** of personnel with access | Art. 16(1) |
| VT-6.4 | **Appropriate technical and organisational measures** | Art. 17 |
| VT-6.5 | **Breach notification to Bulbula without undue delay** | **Art. 43(3)** |
| VT-6.6 | **Assistance with data-subject rights** | Art. 24–32 |
| VT-6.7 | **Sub-processor transparency and control** | Art. 16(2) |
| VT-6.8 | **Deletion or return of data at end of service**, and notification of the destruction obligation | **Art. 50(2)** |
| VT-6.9 | **Location of processing and storage specified** | Art. 22 |
| VT-6.10 | **Audit or evidence rights sufficient to verify compliance** | Art. 16(2) |
| VT-6.11 | **Retention limits for data the vendor holds independently** | RET-7.4 |

| ID | Rule |
| --- | --- |
| VT-6.12 | **A small company cannot negotiate a global platform's terms.** The realistic question is whether the standard terms are acceptable — and that is counsel's judgement, not a procurement formality |
| VT-6.13 | **VT-6.5 is non-negotiable in substance**: without it Bulbula cannot meet Art. 43(1), because the clock runs from Bulbula's awareness and a silent processor makes awareness impossible |
| VT-6.14 | **Where a term cannot be obtained, the gap is recorded as an accepted risk by the owner**, with counsel's view — not quietly ignored |

---

## 7. Onboarding a vendor

```text
1  What personal data would it receive? If none, confirm and record.
2  What purpose requires it? Could it be achieved without a vendor?
3  Controller, processor or conduit?                  → counsel
4  Where does it process and store?                   → Art. 22
5  Is it cross-border?                                → Art. 19, 20 basis → counsel
6  Can the Art. 16(3) terms be obtained?
7  Can it notify breaches without undue delay?        → Art. 43(3)
8  What is its retention and deletion behaviour?
9  Does it create a new security dependency?          → threat model
10 Update the inventory, this register and the notice.
11 Owner approves; counsel approves the legal questions.
12 Only then does personal data flow.
```

| ID | Rule |
| --- | --- |
| VT-7.1 | **Step 12 is a hard gate.** No pilot, trial or "just testing" sends real personal data to an unassessed vendor |
| VT-7.2 | **Step 2 is asked seriously.** The best outcome is often no vendor |
| VT-7.3 | **A free service is not a cheaper option; it is usually a vendor paid in data** |
| VT-7.4 | **Offboarding has its own steps**: confirm deletion, revoke credentials, remove from register, update the notice |

---

## 8. Unresolved items

| ID | Item | Status |
| --- | --- | --- |
| **L-2 / VT-5.4** | **Whether Art. 22(1) imposes an independent local-storage duty** | **PENDING COUNSEL — the pivotal question** |
| D-42 / D-42b | Data location policy and hosting vendor | **Open — privacy decision.** Blocked on L-2 |
| L-10 | Cross-border assessment: hosting, email, backups | **PENDING COUNSEL** |
| L-12 | Cross-border assessment: Google, Telegram | **PENDING COUNSEL** |
| L-19 | Maps provider terms | **PENDING COUNSEL** |
| D-41 | Email provider | **Open — technical decision.** Blocked on DI-6.5 |
| D-21 | Maps provider | **Open — technical decision** |
| D-33 | Telegram identity — would add a register entry | **Open — product decision** |
| D-25 | Media storage provider — would add a register entry if it is not the local filesystem (VT-3.7, TRD TR-84) | **Open — technical decision** |
| — | Whether any ECA adequacy determination exists | **Unknown** |
| — | Whether Art. 22(2) critical-data designations exist | **Unknown** |
| — | Whether Art. 48(1) prior authorization is required before launch | **PENDING COUNSEL** |
| — | Whether OTP/email content is Art. 2(5) communications data (DI-6.5) | **PENDING COUNSEL** |
| — | Whether a suitable Ethiopian host meets the technical requirements | **Open — technical decision** |
| — | Whether maps load only on interaction | **Open — implementation detail** |

---

## Legal and regulatory references

| Reference | Provision | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, Art. 16(2), 16(3) | Processor selection and mandatory written contract | Confirmed — statute/regulation |
| Art. 18 | Transfer principle | Confirmed — statute/regulation |
| Art. 19 | Assessment of the third-party jurisdiction's protection level | Confirmed — statute/regulation |
| Art. 20(1)(a)–(d) | The four cross-border transfer bases | Confirmed — statute/regulation |
| Art. 21 | Authority may demand safeguards; suspend or prohibit transfers | Confirmed — statute/regulation |
| **Art. 22(1)** | **Local storage of locally collected personal data** | Confirmed — statute/regulation |
| Art. 22(2) | Critical personal data designations | Confirmed — statute/regulation; designations **Unknown** |
| Art. 22(3) | Prior approval for cross-border transfer of sensitive data | Confirmed — statute/regulation |
| Art. 43(3) | Processor must notify the controller without undue delay | Confirmed — statute/regulation |
| Art. 48(1) | Prior authorization where safeguards cannot be provided | Confirmed — statute/regulation |
| Art. 50(2) | Notify processors of the destruction obligation | Confirmed — statute/regulation |
| **Relationship between Art. 20 and Art. 22(1)** | — | **PENDING COUNSEL (L-2)** |
| **Classification of Google and Telegram** | — | **PENDING COUNSEL (L-12)** |
| ECA adequacy determinations and prior-authorization practice | — | **Unknown** |
| Google Maps Platform terms | Vendor contract | **PENDING COUNSEL (L-19)** |

---

## Decision references

D-11, D-21, D-24, D-25, D-33, D-41, D-42, D-42b, D-48, D-49.
