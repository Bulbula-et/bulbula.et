# Production Readiness

| | |
| --- | --- |
| **Document** | Production Readiness — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

What must be true before Bulbula is exposed to the public: the
readiness criteria, the decisions that block launch, the legal items
that cannot be self-assessed, and the measurement that the launch bar
itself depends on.

**Out of scope.** Deployment mechanics
(`release-management-v1.0.md`), test design
(`test-strategy-v1.0.md`), post-launch upkeep
(`maintenance-v1.0.md`).

## Authority and precedence

| ID | Rule |
| --- | --- |
| PRR-0.1 | **This document sets no launch date, coverage number or readiness score.** The numeric launch bar is **D-30n**, derived from the pilot — **PENDING PILOT** |
| PRR-0.2 | **It does not decide any open item.** It lists what must be decided and by whom |
| PRR-0.3 | **It makes no claim of legal compliance.** Legal determinations are **PENDING COUNSEL** |
| PRR-0.4 | **The owner declares readiness.** This document does not declare it, and a complete checklist is not a declaration |
| PRR-0.5 | **A blocker is a blocker.** A blocker cannot be waived to meet a date; it can only be resolved, or the launch moves |

---

## 1. What launch means

| ID | Statement |
| --- | --- |
| PRR-1.1 | **Launch is the moment Bulbula publishes information about real businesses to the public.** Everything before it is reversible; this is not (PBD-1.3) |
| PRR-1.2 | **At launch, Bulbula becomes a controller of personal data in public**, with obligations that run from that moment (DI §3) |
| PRR-1.3 | **At launch, businesses that cannot edit their own data become dependent on the correction route working** (TS-1, TS-3) |
| PRR-1.4 | **Launching with thin coverage is a product failure; launching with wrong data is a trust failure.** The second is worse and is harder to undo |
| PRR-1.5 | **There is no "soft launch" that suspends any obligation in §5** |

---

## 2. How to use this document

| ID | Rule |
| --- | --- |
| PRR-2.1 | **Each item is binary: met or not met.** There is no partial credit |
| PRR-2.2 | **"Not met" with a reason is an honest state.** "Met" without evidence is not |
| PRR-2.3 | **Each item names its evidence.** The evidence is what is reviewed, not an assertion that the item is done |
| PRR-2.4 | **An item that cannot be met is escalated to the owner as a decision**: resolve, descope, or accept the risk explicitly and in writing |
| PRR-2.5 | **An accepted risk is recorded with who accepted it and when.** Silent acceptance is the failure mode this document exists to prevent |

---

## 3. Blocking decisions

**Launch cannot proceed while any of these is open.** Each is already
recorded as blocking in a document above this one.

| ID | Decision | Why it blocks | Marker |
| --- | --- | --- | --- |
| PRR-3.1 | **D-30n** — the numeric launch coverage threshold | There is no definition of ready without it | **PENDING PILOT** |
| PRR-3.2 | **D-31** — the 20-business pilot is executed | It produces D-30n and every operational baseline | **PENDING PILOT** |
| PRR-3.3 | **D-42 / D-42b** — hosting and data location | **No production environment exists**; residency is unresolved | **Open — product decision** |
| PRR-3.4 | **D-41** — email provider | Email OTP is a sign-in path; no provider means no sign-in | **Open — product decision** |
| PRR-3.5 | **D-14** — Operator/Administrator split | Staff permission assignment is provisional | **Open — product decision** |
| PRR-3.6 | **D-45** — staff authentication strength | Staff accounts reach all personal data | **Open — product decision** |
| PRR-3.7 | **D-23** — production secret custody | Secrets must exist somewhere accountable before production does | **Open — technical decision** |
| PRR-3.8 | **D-40** — launch-area boundary | Area data is provisional without it | **Open — product decision** |
| PRR-3.9 | **D-08** — verification methods and interval | Verification is the central trust claim | **Open — product decision** |
| PRR-3.10 | **D-56 / D-57** — category catalogue and cardinality | Taxonomy is the backbone of browsing | **Open — product decision** |
| PRR-3.11 | **D-39** — commercial/editorial integrity controls | **Blocks the first paid Campaign**, not the launch itself | **Open — product decision** |

| ID | Rule |
| --- | --- |
| PRR-3.12 | **PRR-3.11 is a separate gate.** The directory may launch without advertising; it may not run a paid Campaign without D-39 (IN-6, ADV-12) |

---

## 4. Readiness criteria

### 4.1 Technical — TRD §39

| # | Criterion | Evidence | Source |
| --- | --- | --- | --- |
| 1 | The full quality gate passes on the release commit | CI run | TST §1 |
| 2 | Architecture tests pass, extended to every new namespace | `tests/Arch` | AC-1, TR-01 |
| 3 | No framework, ORM, container or broker introduced | ArchTest; dependency list | AC-2 |
| 4 | `public/` has exactly one executable entry point | ArchTest / feature test | AC-3, TR-09 |
| 5 | Every application service is callable from Web, API and CLI | Tests | AC-4 |
| 6 | No controller contains SQL, business rules or HTML construction | ArchTest | AC-5 |
| 7 | All 40 capabilities implemented; each PRD acceptance criterion passes | Test results | AC-6 |
| 8 | Surface parity holds per `scope-v1.md` §1.5 | Manual + tests | AC-7 |
| 9 | Discovery works with no session, no cookie interaction, no account | Test | AC-8, GS-1 |
| 10 | No excluded capability exists in any form | Review + tests | AC-9 |
| 11 | A deploy from a clean checkout succeeds on the target host and passes the smoke test | Deployment record | AC-10, TR-190 |
| 12 | Every scheduled job runs within its cron window and is idempotent | Job logs | AC-11, TR-150, TR-151 |
| 13 | **A database restore has been performed successfully in a rehearsal** | Rehearsal record | AC-12, TR-218, BK-4 |
| 14 | Health and readiness reflect real dependency state | Manual | AC-13, TR-138 |
| 15 | No secret appears in the repository, logs or error output | gitleaks; log review | AC-14, TR-198 |
| 16 | Coverage and mutation thresholds are not lower than Phase 1's | Config diff | D-26, QS-2.2 |

### 4.2 Data and content

| # | Criterion | Evidence |
| --- | --- | --- |
| 17 | The coverage threshold **D-30n** is met | Count against the threshold — **PENDING PILOT** |
| 18 | Every published Listing has a linked Permission record | Query; enforced by TR-49 |
| 19 | Every published Listing has a recorded Verification | Query; enforced by OPX-5.1 |
| 20 | Every published fact has recorded provenance | Query; DQ-1 |
| 21 | Every published Listing passed second-person quality review | Review records; LO-5.1 |
| 22 | Personal contact points are flagged where applicable | Query; COL-2, PCP-1 |
| 23 | Media has alt text and recorded rights; EXIF stripped | Query; OPX-6.1, OPX-6.2, APP-6.11 |
| 24 | The Category catalogue is approved and populated | **Open (D-56, D-57)** |
| 25 | Areas for the launch boundary are curated | **Open (D-40)** |
| 26 | No fabricated Listing, Review, rating or activity exists | TS-13 — a statement of fact, verifiable by provenance |

### 4.3 Operational

| # | Criterion | Evidence |
| --- | --- | --- |
| 27 | The pilot has been executed and reported | `pilot-benchmark-v1.0.md` exists (LO-9.8) |
| 28 | Operators are trained on the lifecycle and the console | Training record — **Open — operational decision** |
| 29 | Every queue has a named owner | OM-4.1 |
| 30 | Every cadence row in `operations-model-v1.0.md` §9 has a named owner | OM-9.1 |
| 31 | Every watched signal in `observability-operations-v1.0.md` §4 has a named owner | OBS-4.1 |
| 32 | The support routes are live and reach a person | SUP §2 |
| 33 | The escalation path to Administrator and to counsel is known and reachable | OM §5 |
| 34 | The single-Administrator continuity gap is resolved or explicitly accepted | BC-3.8 |
| 35 | Staff accounts exist with least privilege; no shared accounts | ST-1, BC-3.9 |
| 36 | Backups run on a defined schedule and the restore rehearsal passed | BR §2, §4 |

### 4.4 Security

| # | Criterion | Evidence |
| --- | --- | --- |
| 37 | All traffic over encrypted connections | Manual; NFR-S1 |
| 38 | Authorisation enforced server-side for every staff and Customer action | Tests; NFR-S3, ENF-1 |
| 39 | Production never displays exception detail to a client | Manual; EN-1, TR-144 |
| 40 | Logs carry no credential, session id, token, OTP or full personal record | Log review; TR-135 |
| 41 | Nothing outside `public/` is reachable over HTTP | Manual; DP-8, DR-1 |
| 42 | Uploaded media is never executable and never served from a PHP-executing path | Manual; PM-6, TR-81 |
| 43 | Staff authentication meets the standard set by **D-45** | **Open (D-45)** |
| 44 | Incident response has a named lead and a reachable escalation path | IR §4 |
| 45 | The vulnerability disclosure route is published and monitored | VT §4 |
| 46 | Security headers applied as specified | `security-architecture-v1.0.md` |

### 4.5 Privacy

| # | Criterion | Evidence |
| --- | --- | --- |
| 47 | A privacy notice is published and accurate | PNR; **PENDING COUNSEL** |
| 48 | The data inventory matches what the system actually collects | DI; verified, not assumed |
| 49 | Data-subject request routes are live and reachable without an account | DSR; PNR |
| 50 | The breach assessment and notification procedure is written and the owner knows it | IR §7 |
| 51 | Analytics events contain nothing identifying a Guest | Test; TR-202, AN-3 |
| 52 | The vendor and transfer register is complete and current | VT §2 |
| 53 | Retention periods are set per data class | **PENDING COUNSEL (L-21, D-46)** |
| 54 | Residency position for production data and backups is settled | **PENDING COUNSEL (L-2, L-10)**; **Open (D-42)** |
| 55 | Registration status with the Authority is determined | **PENDING COUNSEL**; REG |
| 56 | Whether a DPO is required is determined | **PENDING COUNSEL**; Art. 40 |

### 4.6 Accessibility and performance

| # | Criterion | Evidence |
| --- | --- | --- |
| 57 | All functionality keyboard-operable on the Web surface | Manual; NFR-AC2 |
| 58 | Colour is not the sole carrier of meaning, including the Sponsored label | Manual; NFR-AC3, LB-4 |
| 59 | Text alternatives exist for meaningful images | Query; NFR-AC4 |
| 60 | Interface legible and functional at increased text size | Manual; NFR-AC5 |
| 61 | Third-party components do not block first render | Manual; NFR-P4 |
| 62 | Core content reachable without JavaScript | Manual; NFR-C3, SEO-1 |
| 63 | Works on current major mobile browsers, prioritising Android | Manual; NFR-C1 |
| 64 | The Mini App works in Telegram's current runtime on Android and iOS | Manual; NFR-C2 |

### 4.7 Advertising — gates the first paid Campaign, not the launch

| # | Criterion | Evidence |
| --- | --- | --- |
| 65 | **D-39** integrity controls resolved and implemented | IN-6, ADV-12 |
| 66 | Prices approved by the owner | No price is approved |
| 67 | Inventory counts and density values approved | **[P]** in `advertising-products.md` §2, §5 |
| 68 | Sponsored labels verified on every placement, both surfaces | ADV-1, ADV-2, LB-1…LB-7 |
| 69 | Identical organic ordering with and without an active Campaign, proven by test | TR-43, IN-7 |
| 70 | Billing and invoicing arrangements settled | **Open (D-11)**; **PENDING COUNSEL (L-17, L-20)** |

---

## 5. What cannot be self-assessed

| ID | Item | Status |
| --- | --- | --- |
| PRR-5.1 | Whether Bulbula must register with the Authority | **PENDING COUNSEL** |
| PRR-5.2 | Whether a Data Protection Officer is required | **PENDING COUNSEL** (Art. 40; "large scale" undefined) |
| PRR-5.3 | The lawful basis for each processing purpose | **PENDING COUNSEL** (L-5) |
| PRR-5.4 | Retention periods per data class | **PENDING COUNSEL** (L-21, D-46) |
| PRR-5.5 | Whether personal data may leave Ethiopia, and on what basis | **PENDING COUNSEL** (L-2, L-10; Art. 20, 22) |
| PRR-5.6 | Breach notification timing and content in practice | **PENDING COUNSEL** (Art. 43, 44) |
| PRR-5.7 | Publishing business contact details that are personal data without consent | **PENDING COUNSEL** |
| PRR-5.8 | Photography of premises and incidental individuals | **PENDING COUNSEL** (L-18) |
| PRR-5.9 | Liability for Review content; takedown obligations | **PENDING COUNSEL** (L-15) |
| PRR-5.10 | Tax and regulatory obligations on advertising revenue | **PENDING COUNSEL** (L-17, L-20) |
| PRR-5.11 | Whether minors' data is in scope and what that requires | **PENDING COUNSEL** (D-46; Art. 11) |

| ID | Rule |
| --- | --- |
| PRR-5.12 | **No item in §5 may be closed by staff judgement, by a web search, or by this documentation set** |
| PRR-5.13 | **Launching with items in §5 unresolved is an owner decision taken with knowledge of the risk**, recorded as such — it is not a default |

---

## 6. The readiness review

| ID | Rule |
| --- | --- |
| PRR-6.1 | **The review walks §3, §4 and §5 item by item, with evidence** |
| PRR-6.2 | **It happens before launch and is repeated if launch slips materially** |
| PRR-6.3 | **Its output is a written record**: which items are met, which are not, which risks the owner accepted and why |
| PRR-6.4 | **The owner declares readiness. Nobody else can** (PRR-0.4) |
| PRR-6.5 | **A failed item is reported as failed.** The purpose of the review is to find them |
| PRR-6.6 | **The review itself proves nothing about the data being true.** That is what the pilot and the operational gates are for |

---

## 7. Traceability

| This document | Traces to |
| --- | --- |
| §1 meaning | PBD-1.3; DI §3; TS-1, TS-3 |
| §3 blockers | D-08, D-14, D-23, D-30n, D-31, D-39, D-40, D-41, D-42, D-42b, D-45, D-56, D-57; IN-6; ADV-12 |
| §4.1 technical | TRD §39; AC-1…AC-14; TR-01, TR-09, TR-138, TR-150, TR-151, TR-190, TR-198, TR-218; D-26 |
| §4.2 data | TR-49; DQ-1; COL-2; OPX-5.1, OPX-6.1, OPX-6.2; TS-13; D-30n, D-40, D-56, D-57 |
| §4.3 operational | OM-4.1, OM-9.1; OBS-4.1; LO-9.8; BC-3.8; ST-1; BR §2, §4 |
| §4.4 security | NFR-S1, NFR-S3; EN-1; TR-135, TR-144, TR-81; DP-8; PM-6; D-45; IR §4; VT §4 |
| §4.5 privacy | PNR; DI; DSR; IR §7; VT §2; TR-202; AN-3; L-2, L-10, L-21; D-46 |
| §4.6 accessibility | NFR-AC2…AC5; NFR-P4; NFR-C1, C2, C3; SEO-1; LB-4 |
| §4.7 advertising | D-11, D-39; ADV-1, ADV-2, ADV-12; IN-6, IN-7; LB-1…LB-7; TR-43; L-17, L-20 |
| §5 legal | `privacy-governance-v1.0.md`; Proclamation 1321/2024 Art. 11, 20, 22, 40, 43, 44 |

---

## 8. Open items

| ID | Item | Marker |
| --- | --- | --- |
| D-30n / D-31 | The launch threshold and the pilot that produces it | **PENDING PILOT** |
| D-42 / D-42b, D-41, D-23, D-14, D-45, D-40, D-08, D-56, D-57 | The blocking decisions in §3 | **Open** — see §3 |
| D-39 | Blocks the first paid Campaign | **Open — product decision** |
| D-26 | Thresholds as the domain grows | **Open — quality decision** |
| D-11 | Billing | **Open — product decision** |
| All of §5 | Legal determinations | **PENDING COUNSEL** |
| — | Operator training record (criterion 28) | **Open — operational decision** |
| — | Whether a formal go/no-go meeting is held, and who attends | **Open — operational decision** |
| — | Whether any readiness criterion is published externally | **Open — operational decision** |

---

## Decision references

D-08, D-11, D-14, D-23, D-26, D-30n, D-31, D-39, D-40, D-41, D-42,
D-42b, D-45, D-46, D-56, D-57.
