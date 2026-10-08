# Data Inventory

| | |
| --- | --- |
| **Document** | Data Inventory — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

Every category of personal data Bulbula processes in V1: what it is,
why it exists, where it comes from, where it goes, who can see it, and
how long it stays. Structured to carry the Art. 46(2) record-of-
processing fields.

**Out of scope.** Periods themselves (`data-retention-v1.0.md`), lawful
basis (`privacy-governance-v1.0.md` §7), vendor detail
(`vendor-and-transfer-register-v1.0.md`), security controls
(`docs/50-security/`).

## Authority

Descriptive, not decisional. **It records what the approved
specifications already say.** Where a specification is silent the gap is
marked open; **no field is invented here**, and nothing in this document
authorises collecting anything.

---

## 1. Classification method

**D-51** is binding: *data is classified by its relation to a natural
person, not by the label on the field.*

| ID | Rule | Source |
| --- | --- | --- |
| DI-1.1 | **Personal data** means information relating to an identified or identifiable natural person, including **location data and online identifiers** (Art. 2(2)) | Art. 2(2) |
| DI-1.2 | **"Business contact information is not automatically non-personal."** A sole trader's mobile number, an owner's name in a business name, a personal email used for the shop — all relate to a natural person | PCP-1, D-51 |
| DI-1.3 | A field is classified by **what it actually contains in practice**, not what the form label says | D-51 |
| DI-1.4 | **Free text is unclassifiable in advance.** Any free-text field may contain personal data, including sensitive data, whatever its purpose | DI §6 |
| DI-1.5 | **Aggregation changes classification.** Individually innocuous fields can identify a person together | Art. 2(2) |
| DI-1.6 | Data is **not** personal once identification is genuinely impossible — a high bar, and not met by removing a name | Art. 50 |

### 1.1 Vocabulary

| Term | Meaning |
| --- | --- |
| **Personal** | Relates to an identified or identifiable natural person |
| **Possibly personal** | Depends on content or context; must be treated as personal unless demonstrated otherwise |
| **Not personal** | Relates to a legal person or a place with no link to an individual |
| **Sensitive** | An Art. 2(5) category |
| **Public** | Visible to anyone without signing in |
| **Internal** | Visible to permitted staff only |
| **Private** | Visible to the person it belongs to, and to permitted staff for a task |

---

## 2. Data subjects

| ID | Category | Notes |
| --- | --- | --- |
| DI-2.1 | **Customers** | Hold accounts. Email, display name, Saves, Reviews, reports |
| DI-2.2 | **Visitors** | No account. Still produce logs with IP addresses — **personal data under Art. 2(2)** |
| DI-2.3 | **Business owners, proprietors and named contacts** | Appear in listing data. **Not users in V1** (D-02, D-54) — they cannot see or control their own appearance through an account |
| DI-2.4 | **Staff members** | Operators and administrators. Named accounts, audit trails |
| DI-2.5 | **Advertiser contacts** | Named individuals at businesses buying placements (D-11) |
| DI-2.6 | **Reporters** | People who report a listing or a Review. **Identity is never exposed** (TS-8) |
| DI-2.7 | **People incidentally in listing photography** | Passers-by, staff, customers in a shopfront image. **They are data subjects who never interacted with Bulbula** | 
| DI-2.8 | **Deceased persons** | Art. 23 extends privacy rights **ten years** after death, invocable by heirs | 

| ID | Rule |
| --- | --- |
| DI-2.9 | **DI-2.3 is the structural asymmetry of V1.** Bulbula publishes data about people who have no account through which to object. Correction and removal routes must therefore work **without an account** (TS-1…TS-5) |
| DI-2.10 | **DI-2.7 people have the weakest position of all** — they did not even supply the data. PBD §9 governs |

---

## 3. Customer account data

| ID | Field | Classification | Purpose | Source | Visibility | Retention |
| --- | --- | --- | --- | --- | --- | --- |
| DI-3.1 | **Email address** | **Personal** | Identity, sign-in, transactional notification | Customer, or Google (D-48) | **Private** | While account exists; then **PENDING COUNSEL (L-21)** |
| DI-3.2 | **Display name** | **Personal** | Attribution of Reviews | Customer | **Public where a Review is published** | With the account; Review attribution per D-34 |
| DI-3.3 | **Google account identifier** | **Personal** — an online identifier | Linking a Google sign-in to an account | Google | **Internal** | While the link exists |
| DI-3.4 | **Account creation timestamp** | **Personal** in context | Support, abuse investigation | System | **Internal** | With the account |
| DI-3.5 | **Account status** | **Personal** in context | Access control | System/staff | **Internal** | With the account |
| DI-3.6 | **Locale preference** | **Possibly personal** in context | Language selection (D-18) | Customer | Private | With the account |
| DI-3.7 | **Last sign-in time** | **Personal** | Security, support | System | Internal | **Open — privacy decision** |
| DI-3.8 | **Password** | **Not collected** | — | — | — | **D-48: no passwords exist** |
| DI-3.9 | **Date of birth** | **Not collected** | — | — | — | **Not introduced** (PG-9.4) |
| DI-3.10 | **Phone number** | **Not collected for accounts** | — | — | — | Not an authentication factor (D-48) |
| DI-3.11 | **Profile photo** | **Not collected** | — | — | — | Not in V1 |

| ID | Rule |
| --- | --- |
| DI-3.12 | **The account record is deliberately tiny.** Email plus a display name is nearly the whole of it, and that is a design achievement to be defended |
| DI-3.13 | **A Google sign-in must not import more than is needed** — no profile photo, no contact list, no Google profile fields beyond email and the identifier (PBD §4) |
| DI-3.14 | **The display name is published.** Customers must be told this before they choose one (PNR §4) |

---

## 4. Listing and business data

| ID | Field | Classification | Notes |
| --- | --- | --- | --- |
| DI-4.1 | **Business name** | **Possibly personal** | "Almaz Coffee" may name a real person |
| DI-4.2 | **Category and attributes** | Not personal | — |
| DI-4.3 | **Description** | **Possibly personal** — free text | May name individuals (DI-1.4) |
| DI-4.4 | **Street address** | **Possibly personal** | A home-based business's address is a person's home address |
| DI-4.5 | **Coordinates** | **Possibly personal** | Same reasoning; **location data is expressly personal data** (Art. 2(2)) |
| DI-4.6 | **Phone number** | **Possibly personal — frequently personal** | In Ethiopia a business number is very often an owner's mobile (PCP-1) |
| DI-4.7 | **Email address** | **Possibly personal** | A personal address used for the business is personal data |
| DI-4.8 | **Named contact person** | **Personal** | A named individual |
| DI-4.9 | **Opening hours** | Not personal | Though it reveals when a sole trader is at a place |
| DI-4.10 | **Social or web links** | **Possibly personal** | A personal profile link is personal data |
| DI-4.11 | **Verification status and basis** | **Possibly personal** | May record who confirmed what (D-08 open) |
| DI-4.12 | **Permission record** | **Personal** | Records **who** gave permission and when (D-43 open) |
| DI-4.13 | **Provenance and change history** | **Possibly personal** | Who supplied or changed the data (C-29) |

### 4.1 Media

| ID | Field | Classification | Notes |
| --- | --- | --- | --- |
| DI-4.14 | **Listing photograph** | **Possibly personal** | A shopfront with an identifiable person in it is personal data about that person |
| DI-4.15 | **EXIF metadata** | **Personal where present** | GPS coordinates, device identifiers, timestamps. **Stripped on upload** (APP-6.11) |
| DI-4.16 | **Original uploaded file** | As above | Retained for re-derivation (TR-219) — **so the stripped original must also be stripped** |
| DI-4.17 | **Derived renditions** | As the source | Regenerable |
| DI-4.18 | **Alt text** | **Possibly personal** — free text | Required for accessibility |
| DI-4.19 | **Uploader identity and upload time** | **Personal** | Staff audit (C-29) |

| ID | Rule | Source |
| --- | --- | --- |
| DI-4.20 | **EXIF is stripped before storage, not before display.** Keeping a GPS-tagged original on disk is still processing location data | APP-6.11 |
| DI-4.21 | **Incidental individuals in photographs are data subjects** (DI-2.7). A removal route must exist | PBD §9 |
| DI-4.22 | **Photography lawfulness is PENDING COUNSEL (L-18)** | L-18 |
| DI-4.23 | **No facial recognition, no image analysis, no automated person detection** exists or is implied | PG-8.4 |

### 4.2 The central problem

| ID | Statement |
| --- | --- |
| DI-4.24 | **Most of a listing is business data; several of its most useful fields are also personal data about a specific human being.** This is not an edge case — it is the normal case for small businesses in Bole Bulbula |
| DI-4.25 | Consequently **PCP-1…PCP-5 are not optional refinements**; they are the mechanism by which the directory stays lawful |
| DI-4.26 | **The person behind a personal contact point may not be the person who gave Permission** (PG-7.7). D-43's record contents must make this answerable |
| DI-4.27 | **Correction and removal must work for someone with no account** (DI-2.9) |

---

## 5. Customer-generated content

| ID | Item | Classification | Visibility | Notes |
| --- | --- | --- | --- | --- |
| DI-5.1 | **Review text** | **Personal** — about the author; **possibly about others** | **Public** | Free text; may name staff or other customers |
| DI-5.2 | **Rating** | **Personal** in context | Public | Tied to the author |
| DI-5.3 | **Review timestamp** | **Personal** in context | Public | — |
| DI-5.4 | **Author display name** | **Personal** | Public | DI-3.2 |
| DI-5.5 | **Review status and moderation history** | **Personal** | Internal | Policy basis recorded (TS-9) |
| DI-5.6 | **Saved listings** | **Personal** | **Private — permanently** | Saves are never public (PRIV-3). A Save list is a revealing behavioural record |
| DI-5.7 | **Report submissions** | **Personal** | **Internal; reporter identity never exposed** | TS-8 |
| DI-5.8 | **Correction requests** | **Personal** | Internal | May come from a non-account holder (TS-1) |
| DI-5.9 | **Review responses from businesses** | **Not in V1** | — | D-02/D-54: no business accounts |

| ID | Rule |
| --- | --- |
| DI-5.10 | **A Review is personal data about at least two parties** — the author and anyone named in it. Deletion requests may come from either |
| DI-5.11 | **Reviews are public and indexed.** That is the point of them, and it raises the stakes of every moderation decision |
| DI-5.12 | **Saves must never leak into a public cache, a share URL, a sitemap or an aggregate small enough to identify** (PCP-4) |
| DI-5.13 | **D-34 (Reviews after account deletion) is open** and is the single largest unresolved question in this section |

---

## 6. Sensitive personal data

Art. 2(5) categories: racial or ethnic origin; political opinion;
religious or philosophical belief; trade-union membership; genetic data;
biometric data; health data; data concerning sex life or sexual
orientation; criminal convictions and offences; **and communications
data, content and metadata**.

| ID | Statement |
| --- | --- |
| DI-6.1 | **Bulbula solicits no sensitive personal data.** No field asks for any Art. 2(5) category |
| DI-6.2 | **It can nevertheless arrive**, unsolicited, in Review text, report text, correction requests and photographs |
| DI-6.3 | Realistic examples: a Review mentioning a medical condition; a listing for a church or mosque implying religious affiliation of its contact; a report alleging a criminal offence; a photograph revealing health or ethnicity |
| DI-6.4 | **A business category can imply a sensitive attribute of its named contact.** A religious institution's listed contact person is a concrete case |
| DI-6.5 | **Art. 2(5) treats communications data, content and metadata as sensitive.** Whether OTP delivery records and email logs fall within this is **PENDING COUNSEL** — if they do, the consequences are significant (Art. 9, Art. 22(3)) |

| ID | Rule |
| --- | --- |
| DI-6.6 | **No sensitive field is ever added** without counsel and an Art. 9 analysis |
| DI-6.7 | **Moderation removes unsolicited sensitive content**; it is not normalised into structure |
| DI-6.8 | **No inference of a sensitive attribute is made or stored** — not by search, not by analytics, not by moderation tooling |
| DI-6.9 | **Art. 22(3) forbids cross-border transfer of sensitive personal data without prior Authority approval.** Keeping sensitive data out of the system is therefore also a transfer control |
| DI-6.10 | If DI-6.5 resolves against Bulbula, **email and OTP records become sensitive data**, with direct consequences for the email vendor (D-41) and residency (D-42) |

---

## 7. Operational and technical data

| ID | Item | Classification | Purpose | Visibility | Notes |
| --- | --- | --- | --- | --- | --- |
| DI-7.1 | **Session record** | **Personal** | Authenticated state | Internal | Server-side; **no client-side token** (PD-05) |
| DI-7.2 | **Session metadata** — IP, user agent, timestamps | **Personal** | Security, anomaly detection | Internal | Art. 2(2) online identifiers |
| DI-7.3 | **OTP records** | **Personal** | Authentication | Internal | Hashed; short-lived (AS-3) |
| DI-7.4 | **Rate-limit counters** | **Personal** where keyed to an identifier | Abuse prevention | Internal | Short-lived |
| DI-7.5 | **Application logs** | **Personal where they contain identifiers** | Diagnosis | Internal | APP §9 |
| DI-7.6 | **Security event logs** | **Personal** | Detection | Internal | SO §5 |
| DI-7.7 | **Access logs (Art. 46(4))** | **Personal** | Statutory | Internal | Reading, disclosure, transmission |
| DI-7.8 | **Audit trail** | **Personal** | Accountability | Internal | Append-only (SO-4.3) |
| DI-7.9 | **Email delivery records** | **Personal** | Deliverability | Internal + **vendor** | DI-6.5 may make these sensitive |
| DI-7.10 | **Web server access logs** | **Personal** — IP addresses | Operations | Internal + **host** | Partly the host's processing |
| DI-7.11 | **Backups** | **Contains everything above** | Recovery | Internal | SO §8 |
| DI-7.12 | **Error reports** | **Possibly personal** | Diagnosis | Internal | Must not embed records (APP-9.1) |

| ID | Rule |
| --- | --- |
| DI-7.13 | **Logs are personal data.** Treating them as "just technical" is the most common privacy failure in systems like this |
| DI-7.14 | **An IP address is an online identifier under Art. 2(2)** — the Proclamation says so expressly |
| DI-7.15 | **Backups are the honest limit of deletion** (RET §8) |
| DI-7.16 | **PD-05 means there is no bearer of identity on the client** to be stolen or inspected — a real privacy benefit, and a reason not to revisit it |

---

## 8. Analytics

| ID | Item | Classification | Notes |
| --- | --- | --- | --- |
| DI-8.1 | **Search terms** | **Possibly personal** | People type names, phone numbers and their own address into search boxes |
| DI-8.2 | **Page and listing view counts** | Not personal **in aggregate** | Per-user view history would be personal — **not built** |
| DI-8.3 | **Campaign impression and click counts** | Not personal in aggregate | Fixed packages, no per-person targeting (D-10) |
| DI-8.4 | **Raw event rows, if any** | **Personal** | Whether raw events exist at all is **D-27, open** |
| DI-8.5 | **Coarse location signals** | **Possibly personal** | LOC-2: no unnecessary precise-location persistence |

| ID | Rule | Source |
| --- | --- | --- |
| DI-8.6 | **Analytics are aggregate and non-identifying** | AN-3 |
| DI-8.7 | **No third-party analytics, no advertising pixels, no cross-site tracking, no behavioural profiling** | AN-1, D-10 |
| DI-8.8 | **An aggregate small enough to identify one person is not an aggregate.** Minimum thresholds are required before any count is exposed | PCP-4 |
| DI-8.9 | **Search-term logging requires care**: raw query logs tied to sessions are a behavioural record. Granularity and retention are **D-27, open** | D-27 |
| DI-8.10 | **No analytics identifier is joined to an account identifier** | AN-3 |
| DI-8.11 | **Precise location is used transiently for Nearby and not persisted** unless a purpose is approved | LOC-2, LOC-3 |

---

## 9. Staff and advertiser data

| ID | Item | Classification | Notes |
| --- | --- | --- | --- |
| DI-9.1 | **Staff account identity** | **Personal** | One account per person (SO-3.1) |
| DI-9.2 | **Staff permissions** | **Personal** in context | Named permissions (TD-03) |
| DI-9.3 | **Staff action audit** | **Personal** | Retained independently of Customer deletion (SO-4.4) |
| DI-9.4 | **Advertiser contact details** | **Personal** | A named person at a business |
| DI-9.5 | **Campaign and order records** | **Possibly personal** | Commercial records naming individuals (D-11) |
| DI-9.6 | **Invoices and payment records** | **Possibly personal** | Tax retention may override privacy minimisation — **PENDING COUNSEL (L-17, L-20)** |

| ID | Rule |
| --- | --- |
| DI-9.7 | **Staff are data subjects too.** Monitoring staff is processing their personal data (PG-10.5) |
| DI-9.8 | **Advertiser contacts did not consent to be in a directory**; their data is commercial-relationship data and is not published |
| DI-9.9 | **Financial retention obligations may exceed privacy minimisation.** Where they conflict, counsel decides (L-21) |

---

## 10. Flows

| ID | Flow | Data | Destination | Transfer? |
| --- | --- | --- | --- | --- |
| DI-10.1 | OTP email | Email address, OTP, message content | **Email provider (D-41)** | **Probably cross-border — PENDING COUNSEL (L-10)** |
| DI-10.2 | Google sign-in | Email, Google identifier | **Google** | **Cross-border — PENDING COUNSEL (L-12)** |
| DI-10.3 | Telegram Mini App usage | Surface-level interaction; Telegram identity (D-33 open) | **Telegram** | **Cross-border — PENDING COUNSEL (L-12)** |
| DI-10.4 | Map tiles / geocoding | Request data, possibly coordinates and IP | **Maps provider (D-21)** | **Cross-border — PENDING COUNSEL (L-19)** |
| DI-10.5 | Hosting | **Everything at rest** | **Hosting provider (D-42b)** | **Residency question — Art. 22(1), L-2** |
| DI-10.6 | Backups | Everything | **Backup location (OT-06)** | **Transfer if off-shore (SO-8.7)** |
| DI-10.7 | Public pages | Listings, Reviews, display names | **Anyone, including crawlers** | Publication, not transfer — but **irreversible** |
| DI-10.8 | Search engines | Published content | **Search engines worldwide** | Deliberate (PSE) — **a deleted Review may persist in third-party caches** |
| DI-10.9 | Internal staff access | All of the above | **Staff** | Logged (Art. 46(4)) |

| ID | Rule |
| --- | --- |
| DI-10.10 | **Publication is the most consequential flow in the system.** Once a Review is indexed, Bulbula cannot fully retract it (DSR §5) |
| DI-10.11 | **Every row above with a vendor is also a row in the vendor register** (VT §1) |
| DI-10.12 | **No flow sends personal data anywhere not listed here.** A new destination is a change requiring privacy review (PG §11) |
| DI-10.13 | **No data is sold, rented or shared for marketing.** There is no commercial data flow out of Bulbula at all | 

---

## 11. Art. 46(2) coverage

| Art. 46(2) field | Where it comes from |
| --- | --- |
| (a) Controller name and contact | Owner to supply; **Open — implementation detail** |
| (b) Purposes of processing | §3–§9 purpose columns; stable list needed for Art. 33(2) registration |
| (c) Categories of data subjects | **§2** |
| (d) Categories of personal data | **§3–§9** |
| (e) Categories of recipients, including in other countries | **§10** and the vendor register |
| (f) Transfers and safeguards | **§10**, VT §4 — **PENDING COUNSEL** |
| (g) Envisaged time limits for erasure | `data-retention-v1.0.md` — **PENDING COUNSEL (L-21)** |
| (h) Description of security mechanisms | `docs/50-security/` |

| ID | Rule |
| --- | --- |
| DI-11.1 | **This document is the substrate of the Art. 46 record, not the record** (PG-4.7) |
| DI-11.2 | **It is updated before a change ships**, not after (PG-4.11) |
| DI-11.3 | **Two Art. 46(2) fields cannot be completed today** — (f) and (g) — and both are PENDING COUNSEL. That is a launch dependency, not a documentation gap |

---

## 12. Unresolved items

| ID | Item | Status |
| --- | --- | --- |
| D-34 | Reviews after account deletion | **Open — product decision.** Biggest gap here |
| D-27 | Analytics granularity, raw events, search-term logging | **Open — privacy decision** |
| D-43 | Permission record contents and retention | **Open — privacy decision** |
| D-08 | Verification rules — what evidence is recorded about whom | **Open — product decision** |
| D-33 | Telegram identity — what Telegram data is stored | **Open — product decision** |
| D-25 | Media storage location and originals | **Open — technical decision** |
| D-21 | Maps provider and what it receives | **Open — technical decision** |
| D-41 | Email provider | **Open — technical decision** |
| D-42 / D-42b | Data location and hosting vendor | **Open — privacy decision** |
| L-18 | Photography lawfulness | **PENDING COUNSEL** |
| L-21 | Retention periods for every class above | **PENDING COUNSEL** |
| — | Whether OTP and email records are Art. 2(5) communications data (DI-6.5) | **PENDING COUNSEL** |
| — | Whether last-sign-in time is retained (DI-3.7) | **Open — privacy decision** |
| — | Minimum aggregate thresholds before a count is exposed (DI-8.8) | **Open — privacy decision** |
| — | Controller contact details for Art. 46(2)(a) | **Open — implementation detail** |

---

## Legal and regulatory references

| Reference | Relevance | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, Art. 2(2) | Personal data includes **location data and online identifiers** | Confirmed — statute/regulation |
| Art. 2(5) | Sensitive categories, **including communications data, content and metadata** | Confirmed — statute/regulation |
| Art. 9, 10 | Prohibition on sensitive processing; further restrictions | Confirmed — statute/regulation |
| Art. 13 | Purpose limitation | Confirmed — statute/regulation |
| Art. 22(1) | Local storage — binds DI-10.5, DI-10.6 | Confirmed — statute/regulation |
| Art. 22(3) | Prior approval for cross-border transfer of sensitive data | Confirmed — statute/regulation |
| Art. 23 | Rights persist **ten years** after death (DI-2.8) | Confirmed — statute/regulation |
| Art. 46(2) | Required contents of the record of processing | Confirmed — statute/regulation |
| Art. 46(4) | Logging of reading, disclosure and transmission | Confirmed — statute/regulation |
| Art. 50 | Destruction preventing reconstruction in intelligible form | Confirmed — statute/regulation |
| **Whether any specific field is personal data in a given case** | — | **Counsel interpretation required** |
| **Whether OTP/email records are communications data** | — | **PENDING COUNSEL** |
| Photography and image rights | Outside Proclamation 1321/2024 | **PENDING COUNSEL (L-18)** |

---

## Decision references

D-02, D-08, D-10, D-11, D-18, D-21, D-25, D-27, D-33, D-34, D-41, D-42,
D-42b, D-43, D-48, D-51, D-54.
