# Bulbula Product Decision Brief

| | |
| --- | --- |
| **Document** | Product Decision Brief — Final Discovery Control Document |
| **Version** | v0.4 |
| **Status** | **Approved** |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | [product-decision-brief-v0.3.md](product-decision-brief-v0.3.md) (status → Superseded) |
| **Authoritative companion** | [docs/60-decisions/decision-register.md](../60-decisions/decision-register.md) — the single source of truth for decisions |
| **Phase** | 2.3 — Freeze approved decisions and prepare for PRD v1.0 |

**What this document is.** The closing control document of discovery. It
freezes what the owner has approved, states the exact V1 boundary, records
what remains genuinely open, and hands a clean source set to PRD v1.0.

**What this document is not.** It is not a new round of analysis. The
reasoning behind each decision lives in v0.1–v0.3 and is not repeated here;
this document records *what was decided*, not *why it was considered*.

**Certainty tags:** **[C]** Confirmed · **[P]** Proposed · **[U]** Unknown ·
**[X]** Conflict. Everything in §3, §4 and §5 is **[C]**.

---

## 1. Document lineage

| Version | Status | Role |
| --- | --- | --- |
| `project-understanding-v0.1.md` | Historical record | 37-section discovery report: market, users, capabilities, constraints, risks |
| `research-notes-v0.1.md` | Historical record | Findings R-01…R-15 with sources |
| `product-decision-brief-v0.2.md` | **Superseded** | First decision brief: company-managed listings; findings R-16…R-23 |
| `product-decision-brief-v0.3.md` | **Superseded** | Permission-based collection, auth model, Telegram as a surface; findings R-24…R-26 |
| **`product-decision-brief-v0.4.md`** | **Approved** | **This document** — frozen decisions and the approved V1 boundary |
| `docs/60-decisions/decision-register.md` | **Approved, living** | The authoritative decision record from here on |

**Rule:** v0.1–v0.3 are never edited again. Their control blocks carry a
`Superseded by` pointer; their bodies stand as the historical record of how
the decisions were reached. From this point, **decisions change only in the
register**, never by editing a brief.

---

## 2. How this document relates to the register

| Need | Go to |
| --- | --- |
| What was decided, and its current status | `60-decisions/decision-register.md` |
| What V1 contains and excludes | §4 and §5 of this document |
| Why a decision was made | The brief version named in the register entry |
| Research evidence | `research-notes-v0.1.md`, plus R-16…R-26 in v0.2 §Appendix A and v0.3 §Appendix A |

Other documents must reference decision IDs rather than restating decisions.
Where a future document conflicts with the register, the register wins.

---

## 3. Approved owner decisions

All **[C]**, approved 2026-10-07. Full entries — rationale, affected areas,
supersessions — are in the register; this is the index.

| ID | Approved decision |
| --- | --- |
| **D-01** | The V1 scope boundary is the 40 capabilities in §4, with the exclusions in §5 |
| **D-02** | All V1 listings are created, verified, published and maintained by Bulbula; owner creation and claims are Future and will be approval-gated |
| **D-03** | Canonical conceptual model `Business → one or more Branch records`; single-location businesses have exactly one branch. No schema yet |
| **D-05** | The V1 discovery surfaces are those enumerated in §4.1 |
| **D-06** | Bulbula owns and centrally controls the taxonomy: `Category → Subcategory` plus controlled aliases/synonyms, used consistently across navigation, search, SEO, listings, ad targeting and operations. Never user-generated. The catalogue itself is a later data task |
| **D-10** | V1 advertising is staff-managed, fixed-package sponsored placement. No self-service dashboard, no business account required. No auction, CPC, CPM, CPA, bidding, exchanges or programmatic. Paid placement stays separate from organic ranking and is clearly labelled |
| **D-12** | Reviews require an authenticated customer account; guests may read. Rating, written review, editing under approved rules, moderation, reporting, anti-abuse and auditability are all required. Owner responses are not V1 |
| **D-15** | First launch is Web + Telegram Mini App, coordinated. Flutter is a later client |
| **D-18** | **English-first, bilingual-ready** (see §12) |
| **D-24** | Email is the V1 notification channel and a first-class product capability, delivered by a professional transactional email system |
| **D-29** | Documentation uses `Draft / Review / Approved / Superseded` and `vMAJOR.MINOR`; decisions live only in the register |
| **D-30** | A 20-business operational pilot must precede the numeric launch threshold (see §9). No launch numbers may be invented before it |
| **D-48** | V1 customer authentication = **Google + email verification/OTP**. **No passwords. No Apple Sign In.** Guests browse without an account |
| **D-49** | Telegram Mini App is a first-class client surface on the same backend and domain model; Web and Mini App share one frontend implementation as far as practical, with Telegram logic isolated behind an adapter. No separate backend, no duplicated business logic |
| **D-50** | V1 business data is collected directly by Bulbula through contact/visits **with the business's permission** |
| **D-51** | Minimize unnecessary personal data; never treat data as non-personal merely because it appears on a business listing |
| **D-52** | The web frontend is mobile-first from the beginning; desktop-first-then-shrunk is not acceptable |
| **D-53** | Brand direction: primary orange, secondary blue, white-dominant light mode. **Logo not finalized**; no placeholder is final |
| **D-54** | **No business accounts in V1.** Future business accounts will require strict Bulbula administrator approval of every submission. No dormant business-account features in V1 |

**Closed:** **D-32** (Apple Developer Program dependency) — closed by D-48.

---

## 4. The approved V1 boundary

**[C]** The 40 capabilities below are the approved V1 product. A capability
may not be dropped without owner approval. Where an item needs a detailed
sub-decision, the sub-decision is recorded in the register and the capability
stays in scope.

### 4.1 Public discovery

| # | Capability | Open sub-decisions |
| --- | --- | --- |
| 1 | Homepage | — |
| 2 | Search | D-09 (completeness weight) |
| 3 | Search autocomplete | — |
| 4 | Category / subcategory browsing | D-56, D-57 |
| 5 | Location / area browsing | D-40 (launch-area boundary) |
| 6 | Category × area discovery pages | D-56 |
| 7 | Nearby / distance discovery | — |
| 8 | Business profile pages | D-55 (branch attribute boundary), D-44 (services/products/pricing) |
| 9 | Opening hours / open status | D-04 (hours model), D-55 |
| 10 | Google Maps embed + "Open in Google Maps" | D-21 |
| 11 | Business contact actions | D-55 |
| 12 | Trust indicators | D-08 (verification rules) |
| 13 | Reviews | D-34, D-36, D-37 |
| 14 | Favourites / saves / likes where approved | — |
| 15 | Report a problem / suggest a correction | D-35 (guest structured suggestions) |
| 16 | Sponsored placements | D-10 detail: inventory counts, positions, label wording → PRD |
| 17 | Sharing | D-49 (Telegram share via adapter) |
| 18 | Static information / policy pages | §8 legal items |

### 4.2 Bulbula internal operations

| # | Capability | Open sub-decisions |
| --- | --- | --- |
| 19 | Listing creation | D-50 lifecycle, D-43 (permission record) |
| 20 | Listing editing | D-14 (roles) |
| 21 | Listing verification / quality control | D-08 |
| 22 | Media management | D-25 (storage) |
| 23 | Category management | D-06, D-56, D-57 |
| 24 | Location management | D-40 |
| 25 | Review moderation | D-34 |
| 26 | Report management | D-35 |
| 27 | Advertising / campaign management | D-11 (billing records), D-39 (integrity controls) |
| 28 | Operational analytics | D-27 |
| 29 | Audit logs | D-14 |

### 4.3 Customer accounts

| # | Capability | Open sub-decisions |
| --- | --- | --- |
| 30 | Google authentication | — |
| 31 | Email OTP authentication | D-41 (provider) |
| 32 | Unified customer identity | D-13 (linking rules), D-33 (Telegram identity — open) |
| 33 | Customer profile | D-51 (minimization) |
| 34 | Favourites / saves | — |
| 35 | Reviews | D-34 |
| 36 | Account deletion / privacy controls | D-46 (retention), §8 |

### 4.4 Cross-cutting

| # | Capability | Open sub-decisions |
| --- | --- | --- |
| 37 | SEO | D-18 (URL/language strategy), D-56 |
| 38 | Analytics | D-27 |
| 39 | Notifications | D-24 approved; D-41 (provider) |
| 40 | Privacy / data handling | D-42, D-46, §8 |

### 4.5 Capabilities that carry a surface obligation

**[C]** Every capability above exists on **both** first-launch surfaces (Web
and Telegram Mini App) unless the PRD states otherwise with a reason, except:
**#37 SEO** (web only — Telegram content is not crawled) and **#19–#29
operations** (web only — staff tooling). This is a direct consequence of
D-49 and must be stated in the PRD rather than left to inference.

---

## 5. Explicit V1 exclusions

**[C]** The following are **not** V1. Future capabilities must not leak into
V1 requirements; any requirement implying one of these is a defect in the PRD.

| Excluded | Note |
| --- | --- |
| Business accounts | D-54 |
| Owner-created listings | D-02 |
| Owner claims | D-02 |
| Owner listing management | D-02 |
| Owner profile control | D-02 |
| Owner review replies | D-12 — no account to reply from |
| Self-service advertising | D-10 |
| Self-service advertiser billing | D-10 |
| Auction / CPC / CPM / CPA | D-10 |
| Password authentication | D-48 |
| Apple Sign In | D-48; revisit for iOS (D-47) |
| Flutter client | D-15 |
| Online ordering | Out of product scope |
| Reservations | Out of product scope |
| Loyalty | Out of product scope |
| Messaging / leads | Future |
| Jobs | Out of product scope |
| Local news | Out of product scope |
| Subscriptions | Out of product scope |
| Advanced AI recommendations | Future |
| Afaan Oromo interface | D-18 |
| National-scale geographic expansion | Location model stays expandable (D-40), but V1 covers one area |

### 5.1 Exclusion hygiene rules for the PRD — **[P] process recommendation**

1. Every requirement cites the decision ID that authorises it.
2. No requirement may reference a business-owner actor; V1 has three actors
   only: **guest**, **registered customer**, **Bulbula staff**.
3. Architecture seams for the future owner model (ownership state, single
   audited write path, provenance) are permitted and expected; **features**
   for it are not.
4. Any requirement that would need one of the excluded capabilities to be
   useful is out of scope by definition.

---

## 6. Decision status after this phase

| Status | Count | Where |
| --- | --- | --- |
| **Approved** | 19 | §3 and the register §1 |
| **Closed** | 1 | D-32 |
| **Open — Class A** (all Pending external) | 3 | D-46, D-30n, D-40 |
| **Open — Class B** (before UX/data model) | 23 | Register §2.2 |
| **Open — Class C** (implementation/TRD) | 7 | Register §2.3 |
| **Deferred — Class D** | 4 | Register §2.4 |
| **Scheduled** | 1 | D-31 pilot execution |

### 6.1 Confirmed decisions vs open implementation choices

The distinction the PRD must respect:

```text
APPROVED PRODUCT DECISIONS            OPEN IMPLEMENTATION CHOICES
(binding; PRD states them as fact)    (PRD states the requirement, not the mechanism)
──────────────────────────────────    ────────────────────────────────────────────
Google + email OTP, no passwords      which transactional email provider (D-41)
Business → Branch model               which attributes bind to Branch (D-55)
Category → Subcategory + aliases      the catalogue and its depth (D-56, D-57)
Staff-managed fixed-package ads       inventory counts, positions, wording (PRD)
Reviews require authentication        edit window, rating-only, per-branch (D-34)
English-first, bilingual-ready        when the Amharic UI ships (separate approval)
Mobile-first web                      breakpoints, components (UX phase)
Shared Web/Telegram frontend          htmx/Alpine vs vanilla (D-16), view layer (D-17)
Personal data minimized               retention periods (D-46), hosting (D-42)
```

**[P] Guidance for the PRD author:** where an item is an open implementation
choice, the PRD states the **behaviour and the acceptance criteria**, never
the mechanism. "A login code is delivered to the user's email within 60
seconds" is a requirement; "use provider X" is not.

---

## 7. What is confirmed about the operating model

**[C] D-50 lifecycle** — the V1 production line:

```text
discover → obtain permission → collect business information → verify
   → create → publish → maintain → correct → re-verify
```

**[C]** Business owners do not directly edit published information in V1.

**[C] D-51 collection target** — business-related information: name,
category, subcategory, description, address, business phone, business email
where applicable, website, public social links, opening hours, services,
products, pricing where appropriate, photos, coordinates and other genuinely
business-related information.

**[C]** Avoid unnecessary personal information. **[C]** Never treat a field
as non-personal merely because it appears on a business listing — classify by
whether it identifies or relates to a natural person.

---

## 8. Legal and compliance — pending

**[C]** These are **not solved**. Business permission for listing collection
is confirmed (D-50) and improves Bulbula's position, but **it does not
automatically establish legal compliance for every processing activity.**
This document gives no legal advice.

**[C] Standing architectural principle:**

> Minimize unnecessary personal data, and keep personal data in appropriately
> Ethiopian-hosted infrastructure where required.

> **Historical numbering — see the register.** `D-46a` and `D-46b` below are
> the discovery-era split. They were **consolidated into a single `D-46`**
> when [`decision-register.md`](../60-decisions/decision-register.md) was
> created, where D-46 reads: *"Minimum account age, retention schedule per
> data class, and confirmation of the data-location and cross-border transfer
> basis"* (Open — Class A, Pending external). **`D-46a` and `D-46b` are not
> live identifiers and must not be cited outside this historical record.**

| ID | Pending item | Needs |
| --- | --- | --- |
| **D-46a** | Minimum account age | Counsel |
| **D-46b** | Retention periods per data class (accounts, OTP records, reviews, permission records, audit logs, analytics) | Counsel |
| **L-2 / D-42** | Precise data-location policy and whether it is legally sufficient | Counsel + architecture |
| **L-10 / L-12** | Cross-border transfer assessment for Google, the email provider, Telegram, Maps and the CDN | Counsel |
| **L-3** | ECA registration requirements and process | Counsel |
| **L-4** | DPO appointment requirement | Counsel |
| **L-5 / L-6 / L-7** | Lawful basis per purpose, privacy notice, data-subject rights procedures | Counsel + product |
| **L-8** | 72-hour breach notification procedure | Operations |
| **L-15** | Review-content liability and takedown obligations | Counsel |
| **L-16 / L-17 / L-20** | Advertising disclosure, invoicing, VAT/tax, trade licence | Counsel + finance |
| **L-18** | Photography of premises and people | Counsel |
| **L-19** | Google Maps Platform terms | Review |
| **L-21** | Retention schedule document | Counsel + product |
| **L-22** | DPIA covering accounts, reviews and cross-border transfers | Counsel |

The full register with context is in v0.3 §20.5; the items above are the
live list.

**[P] One product question that must go to counsel with the rest:** if a
business declines permission, does Bulbula show nothing, a minimal stub, or
record the refusal internally only? It is simultaneously a legal and a
coverage question (risk RK-20).

---

## 9. Launch definition and the 20-business pilot

**[C] D-30.** The numeric launch bar is:

```text
PENDING PILOT
```

### 9.1 Pilot measurement specification — **[C] metric list, [P] method**

| # | Metric | How to capture |
| --- | --- | --- |
| 1 | Time to approach | Minutes from leaving for / dialling the business to starting the conversation |
| 2 | Permission rate | Businesses that agreed ÷ businesses approached |
| 3 | Collection time | Minutes spent gathering information on site or by phone |
| 4 | Verification time | Minutes to confirm the facts by the chosen method |
| 5 | Listing creation time | Minutes in the console from start to "ready for review" |
| 6 | Quality-review time | Minutes for the second pair of eyes |
| 7 | Photo / media effort | Shots taken, usable shots, minutes including upload |
| 8 | Coordinate accuracy | Method used and confidence; whether a second visit was needed |
| 9 | Second-contact frequency | Share of listings needing a follow-up contact to complete |
| 10 | Data completeness | Share of the approved field set obtained on the first pass |
| 11 | Refusal rate | Businesses declining, with reason categories |
| 12 | Duplicate rate | Candidates found to already exist |

### 9.2 Pilot output

**[P]** A documented operational benchmark at
`docs/40-operations/pilot-benchmark-v1.0.md` containing: the twelve measures
above with ranges and medians, the derived **listings per staff-day**, the
**permission rate**, the **field set actually obtainable on a first pass**
(as opposed to the wished-for set), and ~20 real seed listings.

### 9.3 Dimensions the eventual launch bar will cover — **[C]**

Geographic coverage · listing quality · freshness · verification · search
quality · technical reliability · moderation readiness · operational
capacity. **[C]** The numbers themselves are set by the owner after the
pilot; nothing in the PRD may pre-empt them.

---

## 10. Telegram identity — explicitly still open

```text
D-33 — Telegram identity integration
Status: Open evaluation (Class B)
```

**[C]** The Mini App is confirmed V1 (D-15, D-49). **[C]** Telegram account
authentication is a **separate question** and is **not** part of the
confirmed provider list (D-48 = Google + email only). It must not be added
silently. Analysis: v0.3 §10.4; decide with the Mini App authentication
prototype in hand.

---

## 11. Staff authentication — explicitly still open

```text
D-45 — Staff / admin authentication strength
Status: Open (Class B, security)
```

**[C]** Because the customer system is passwordless (D-48), the operations
console is treated separately. **[C]** Staff security must not be weakened
merely because customers are passwordless. TOTP or a stronger mechanism is to
be evaluated in the TRD/security phase. Context: the console can publish
listings, approve campaigns and read permission records (risk RK-19).

---

## 12. Language and content requirements for the PRD

**[C] D-18 — English-first, bilingual-ready.** The PRD must specify:

| Requirement | Detail |
| --- | --- |
| **English-first UI** | All V1 public and operations interfaces are English |
| **Bilingual-ready data model** | Business names, category labels and area/location names can store Amharic; no name-like field may be single-language by design |
| **Amharic searchable content** | Search aliases may contain Amharic; Amharic content is findable at launch even though the interface is English |
| **No hard-coded user-visible strings** | Every piece of copy is externalised from the first line of view code |
| **Typography compatibility** | Font strategy must not preclude Ethiopic support later |
| **Future localization capability** | URL, routing and content design must not block a later bilingual UI |
| **Out of scope** | A full translation workflow, a bilingual interface (needs separate approval), and Afaan Oromo |

---

## 13. Documentation architecture status

**[C] D-29.** The hierarchy is approved in principle:

```text
docs/
├── 00-discovery/     ✅ exists — frozen historical record
├── 10-product/       ⬜ created with PRD v1.0
├── 15-business/      ⬜ later
├── 20-ux-ui/         ⬜ created in the UX research phase
├── 30-technical/     ⬜ created with the TRD
├── 35-platforms/     ⬜ created with the TRD
├── 40-operations/    ⬜ created with the pilot benchmark
├── 45-quality/       ⬜ later
├── 50-security/      ⬜ created with the TRD
├── 55-privacy/       ⬜ created when legal items resolve
└── 60-decisions/     ✅ exists — decision-register.md v1.0
```

**[P] Deliberate choice:** directories are created when their first real
document exists. Empty placeholder files would add noise and would be the
first thing to go stale.

**Pending mechanical task — not performed in this phase:** `docs/architecture.md`
and `docs/deployment.md` still sit at the `docs/` root and belong in
`30-technical/`. Moving them also requires updating three links in `README.md`.
This is deliberately deferred to the TRD phase so that this documentation-only
PR contains no changes outside `00-discovery/` and `60-decisions/`.

---

## 14. AI context architecture status

**[C]** Approved in principle, **not created in this phase**:

```text
.ai/
├── ai-workflow-rules.md
├── progress-tracker.md
├── ui-context.md
├── code-standards.md
├── architecture.md
├── project-overview.md
├── decision-log.md
└── current-state.md
```

**[C]** These are created only after the formal product and technical
documentation exists — a context file that summarises documents which do not
yet exist would be fabrication. The content specification for each file is in
v0.3 §25 and remains valid.

---

## 15. Phase 3 — Product Requirements Document v1.0

**Not started. Not written in this phase.**

### 15.1 Source material

```text
Approved owner decisions      (60-decisions/decision-register.md)
+ Product Understanding v0.1  (00-discovery/project-understanding-v0.1.md)
+ Product Decision Brief v0.4 (this document)
+ Research Notes              (research-notes-v0.1.md + R-16…R-26)
+ Decision Register           (the authority on every decision ID)
```

### 15.2 Required PRD coverage

Product goals · users · personas · user journeys · V1 capabilities (the 40 in
§4) · permissions · business/listing model · search · locations · categories
· reviews · favourites · authentication · notifications · advertising ·
analytics · SEO · trust · operations · privacy · launch criteria ·
non-functional requirements · V1 exclusions · future roadmap boundaries.

### 15.3 PRD construction rules — **[P]**

1. Output to `docs/10-product/prd-v1.0.md`, status `Draft`.
2. Every requirement cites its decision ID.
3. Three actors only: guest, registered customer, Bulbula staff.
4. Behaviour and acceptance criteria, never mechanisms, for anything that is
   an open implementation choice (§6.1).
5. Launch criteria section states the dimensions and marks the numbers
   **PENDING PILOT**.
6. Privacy and retention requirements marked **PENDING COUNSEL** where D-46
   is unresolved.
7. No requirement may imply an excluded capability (§5).

### 15.4 Parallel workstreams

| Workstream | Blocks the PRD draft? | Blocks PRD approval? |
| --- | --- | --- |
| 20-business pilot (D-31 → D-30n) | No | **Yes** |
| Legal/compliance confirmation (D-46, L-items) | No | **Yes** |
| Launch-area boundary confirmation (D-40) | No | **Yes** |
| Telegram prototype (informs D-33, D-38) | No | No |
| UX research phase (D-53 tokens, D-19) | No | No |

---

## 16. PRD readiness

```text
PRD readiness: READY FOR DRAFTING
```

### Remaining external items

- **Legal/compliance confirmation** — D-46 and the L-items in §8.
- **20-business operational pilot** — D-31.
- **Exact pilot-derived launch metrics** — D-30n.

These may proceed **in parallel** and do **not** prevent a Draft PRD.

### Condition on approval

```text
PRD v1.0 must remain in status: Draft
until the legal items and the pilot-derived launch criteria
are sufficiently resolved.
```

**No PRD is written in this phase.**

---

## Appendix — Document control and change log

| Version | Date | Status | Summary |
| --- | --- | --- | --- |
| v0.2 | 2026-10-07 | Superseded | Company-managed listings; V1 scope; shared-frontend validation; D-01…D-40; R-16…R-23 |
| v0.3 | 2026-10-07 | Superseded | Permission-based collection; data minimization; Google + email OTP (Apple removed); Telegram as a first-class surface; staff-managed advertising; four PRD blockers; R-24…R-26 |
| **v0.4** | **2026-10-07** | **Approved** | Owner approvals frozen (19 decisions); exact V1 boundary (40 capabilities) and exclusions recorded; decision register created as the new source of truth; D-33 and D-45 preserved as open; legal items and launch numbers kept pending; **PRD readiness: READY FOR DRAFTING** |

**Supersession rule:** this document is superseded only by a decision
recorded in `60-decisions/decision-register.md`. There is no v0.5 — the
register, the PRD and the TRD carry the work forward from here.
