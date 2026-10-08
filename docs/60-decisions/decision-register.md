# Bulbula Decision Register

| | |
| --- | --- |
| **Document** | Decision Register |
| **Version** | v1.0 |
| **Status** | Approved |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | The decision tables in `docs/00-discovery/product-decision-brief-v0.2.md` §16 and `product-decision-brief-v0.3.md` §26 |

**This file is the single source of truth for Bulbula product and
architecture decisions.** Every other document references a decision by its
ID and must not restate or contradict it. If another document disagrees with
this register, this register wins and the other document is wrong.

---

## How to use this register

### Status vocabulary

| Status | Meaning |
| --- | --- |
| **Approved** | Decided by the owner. Binding. Changing it requires a new decision that supersedes this one |
| **Open** | Not yet decided. Carries a class (below) saying when it must be decided |
| **Pending external** | Cannot be decided internally — awaits legal counsel, a pilot result or a third party |
| **Deferred** | Deliberately postponed beyond V1 |
| **Closed** | No longer applicable (e.g. the feature it governed left scope) |
| **Superseded** | Replaced by a later decision, named in the entry |

### Class (for Open decisions only)

| Class | Meaning |
| --- | --- |
| **A** | Must be decided before PRD v1.0 can be finalized |
| **B** | Must be decided before UX design or the data model |
| **C** | Can be decided during implementation / in the TRD |
| **D** | Future / deferred beyond V1 |

### Certainty tags used in referenced documents

**[C]** Confirmed · **[SI]** Strongly implied · **[P]** Proposed ·
**[U]** Unknown · **[X]** Conflict. A decision recorded as **Approved** here
is **[C]** everywhere else.

### Counts at v1.0

| Category | Count |
| --- | --- |
| **Approved** | **19** |
| **Closed** | 1 |
| **Open — Class A** (all three also Pending external) | 3 |
| **Open — Class B** | 23 |
| **Open — Class C** | 7 |
| **Deferred — Class D** | 4 |
| **Scheduled** (pilot execution, D-31) | 1 |
| **Merged or split into other IDs** (D-07, D-22) | 2 |
| **Total tracked** | **60** |

---

## 1. Approved decisions

Each entry: decision · status · date · source · rationale · affected areas ·
supersedes.

---

### D-01 — V1 scope boundary

- **Decision:** V1 consists of the 40 approved capabilities across public
  discovery, Bulbula internal operations, customer accounts and cross-cutting
  concerns, as enumerated in `product-decision-brief-v0.4.md` §4, together
  with the explicit exclusion list in §5. A capability may not be removed
  without owner approval; sub-decisions within a capability are recorded
  separately in this register.
- **Status:** **Approved** · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.3 §17–18
- **Rationale:** The scope follows directly from company-managed listings
  (D-02): removing the business-facing surface makes a complete,
  high-quality discovery product achievable in one release.
- **Affected:** PRD, all product areas, launch criteria
- **Supersedes:** The provisional scope in `project-understanding-v0.1.md`
  §25 and the Cut A / Cut B question in §25.4

### D-02 — Listing ownership in V1

- **Decision:** All V1 listings are created, verified, published and
  maintained by Bulbula. Business owners cannot create, claim, edit or
  publish listing information in V1. Owner-created listings and claims are
  **Future**, and when introduced every owner submission must pass Bulbula
  administrator approval before publication.
- **Status:** **Approved** · **Date:** 2026-10-07 (first confirmed Phase 2.1)
- **Source:** Owner decision, Phase 2.1 §2; reconfirmed Phase 2.3 §8–9
- **Rationale:** Guarantees data quality and a consistent product at launch,
  removes the cold-start dependency on owner participation, and avoids
  building a two-sided platform before there is demand for one.
- **Affected:** Scope, data model (ownership and provenance seams),
  operations, moderation, advertising, legal posture
- **Supersedes:** The owner-created/claim assumptions in
  `project-understanding-v0.1.md` §7 and §25

### D-03 — Business / Branch model

- **Decision:** Adopt the canonical conceptual model `Business → one or more
  Branch records`. A single-location business has exactly one branch; a
  multi-location business has several. Which attributes bind to the branch
  boundary — reviews, opening hours, location, contact information,
  analytics — is evaluated during design. No schema in this phase.
- **Status:** **Approved** (conceptual model) · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.3 §2
- **Rationale:** Address, coordinates and hours are facts about a place, not
  a brand. Modelling the branch from the start costs one table and makes
  multi-location support a data task rather than a migration.
- **Affected:** Data model, profiles, search, reviews, analytics, SEO, URLs
- **Related open items:** D-55 (branch attribute boundary), D-34 (review
  attachment level)

### D-05 — V1 discovery surfaces

- **Decision:** The V1 discovery surfaces are those enumerated in the
  approved scope: homepage, search, autocomplete, category and subcategory
  browsing, location/area browsing, category × area pages, nearby/distance,
  business profiles, opening hours and open status, maps, contact actions,
  trust indicators, reviews, favourites, report/suggest correction,
  sponsored placements, sharing and static pages.
- **Status:** **Approved** · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.3 §17
- **Rationale:** Discovery is the product; these surfaces are also the SEO
  surface.
- **Affected:** PRD, information architecture, SEO, UX

### D-06 — Category strategy

- **Decision:** Bulbula owns and centrally controls the taxonomy. V1
  supports `Category → Subcategory` plus controlled aliases/synonyms. The
  taxonomy must be usable consistently across navigation, search, SEO,
  listings, advertising targeting and internal operations. It is never
  user-generated. The actual category catalogue is a later product/data
  task, not part of this decision.
- **Status:** **Approved** (strategy) · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.3 §3
- **Rationale:** A controlled two-level taxonomy with aliases is
  maintainable by a small team, keeps URLs stable, and is the cheapest lever
  on search quality. User-generated categories would fragment navigation and
  SEO irreversibly.
- **Affected:** Navigation, search, SEO/URLs, listings, ad targeting,
  operations console
- **Related open items:** D-56 (catalogue production), D-57 (category
  cardinality per listing)

### D-10 — Advertising model for V1

- **Decision:** V1 advertising is **staff-managed, fixed-package sponsored
  placement**. No business self-service dashboard and no business account are
  required. Staff may create campaigns on behalf of businesses. Approved
  product types: sponsored business placement, category sponsorship,
  homepage promotion and other explicitly approved fixed placements.
  **Prohibited in V1:** auctions, CPC, CPM, CPA, bidding, ad exchanges,
  programmatic. Paid placement stays separate from organic ranking and is
  clearly labelled. Exact inventory counts, positions and label wording are
  specified in the PRD.
- **Status:** **Approved** · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.3 §4
- **Rationale:** Businesses have no accounts in V1, so the sales motion is
  already a human conversation; fixed packages are explainable to a
  first-time advertiser and remove any incentive to inflate traffic.
- **Affected:** Advertising subsystem, ranking integrity, operations console,
  billing records, PRD
- **Related open items:** D-39 (editorial/commercial integrity controls)

### D-12 — Reviews

- **Decision:** Reviews require an authenticated customer account; guests may
  read them. The system must support rating, written review, editing under
  approved rules, moderation, reporting, anti-abuse controls and
  auditability. Business-owner responses are **not V1** because no business
  account exists.
- **Status:** **Approved** (framework) · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.3 §5
- **Rationale:** Authentication is the single most effective anti-spam
  control; auditability is required to defend against both fake reviews and
  accusations of suppression.
- **Affected:** Reviews, moderation, ranking, accounts, trust
- **Related open items:** D-34 (per-business vs per-branch, edit window,
  deletion, rating-only), D-36 (review photos), D-37 (helpful voting)

### D-15 — First-launch client surfaces

- **Decision:** Web and the Telegram Mini App launch together as the
  coordinated first release. Flutter is a later client that shares the
  backend, API contracts, product rules and terminology but **not** frontend
  code.
- **Status:** **Approved** · **Date:** 2026-10-07 (first confirmed Phase 2.1)
- **Source:** Owner decision, Phase 2.1 §2; reconfirmed Phase 2.3 §13
- **Rationale:** Telegram is the cheapest acquisition channel in this market
  and shares one implementation with the web, so the second surface is
  nearly free. A native client before product-market fit is not.
- **Affected:** Scope, frontend architecture, API design, roadmap
- **Related open items:** D-15r (Flutter timing, Deferred)

### D-18 — Language strategy

- **Decision:** **English-first, bilingual-ready.** The V1 public UI is
  English. From the beginning: no hard-coded user-visible strings; business
  names, category labels and area/location names can store Amharic; search
  aliases may contain Amharic; the content/data design must not prevent a
  future bilingual UI; typography must be compatible with future Ethiopic
  support. A full bilingual interface requires separate approval. Afaan
  Oromo is not V1.
- **Status:** **Approved** · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.3 §6; options evaluated in v0.3 §22
- **Rationale:** Bilingual-from-launch would double the per-listing content
  cost at the exact point where operational capacity is the binding
  constraint, while bilingual-ready architecture preserves the end state at
  near-zero cost.
- **Affected:** Data model (every name-like field), search and aliases,
  taxonomy, typography, URLs, SEO, content operations, PRD
- **Supersedes:** The open language question in v0.1 §30 and v0.2 §16.4

### D-24 — Notification channel for V1

- **Decision:** Email is the V1 notification channel and a first-class
  product capability — not only an authentication mechanism. It must be
  delivered by a professional transactional email system covering
  verification/OTP, account and security notifications, review
  notifications, moderation notifications, support communication and
  important service notifications.
- **Status:** **Approved** (channel and requirement) · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.2 §8; reconfirmed Phase 2.3 §12
- **Rationale:** Shared-hosting local mail has no sending reputation; OTP
  mail that lands in spam is indistinguishable from a broken login.
- **Affected:** Authentication, accounts, moderation, support, infrastructure
  cost, privacy (foreign processor)
- **Related open items:** D-41 (provider selection, Class C)

### D-29 — Documentation versioning and approval authority

- **Decision:** All documentation carries a control block with
  status (`Draft` / `Review` / `Approved` / `Superseded`) and a `vMAJOR.MINOR`
  version. Decisions live only in this register. The documentation hierarchy
  of `product-decision-brief-v0.4.md` §13 is approved in principle. Only the
  owner moves a document to `Approved`.
- **Status:** **Approved** · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.2 §25 and Phase 2.3 §25
- **Rationale:** Prevents two documents disagreeing and makes supersession
  explicit rather than implied.
- **Affected:** All documentation, AI context system

### D-30 — Launch definition (direction)

- **Decision:** A **20-business operational pilot must be completed before
  the numeric launch threshold is set.** The pilot measures time to approach,
  permission rate, collection time, verification time, listing creation time,
  quality-review time, photo/media effort, coordinate accuracy, second-contact
  frequency, data completeness, refusal rate and duplicate rate, and produces
  a documented operational benchmark. Launch criteria will eventually include
  geographic coverage, listing quality, freshness, verification, search
  quality, technical reliability, moderation readiness and operational
  capacity. **No numeric bar may be invented before the pilot.**
- **Status:** **Approved** (direction) · numeric bar **Pending external
  (pilot)** · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.3 §7
- **Rationale:** In a company-managed model, staff throughput is the binding
  constraint on coverage; a launch bar not grounded in measured throughput is
  a guess.
- **Affected:** Launch criteria, PRD, operations, roadmap, resourcing
- **Related open items:** D-31 (pilot execution), D-40 (launch-area boundary)

### D-32 — Apple Developer Program dependency

- **Decision:** Closed. Sign in with Apple is not in V1, so the Apple
  Developer Program membership, Services ID, domain verification and
  six-monthly client-secret rotation are not V1 concerns.
- **Status:** **Closed** · **Date:** 2026-10-07
- **Source:** Consequence of D-48
- **Rationale:** The dependency existed only to support Apple login.
- **Affected:** Authentication, cost, launch risk
- **Related open items:** D-47 (Apple login for a future iOS client,
  Deferred)

### D-48 — Customer authentication providers for V1

- **Decision:** V1 customer authentication supports **Google** and **email
  verification / OTP**. **Passwords are not V1. Apple Sign In is not V1.**
  Guests may browse, search and view listings, categories, locations and
  reviews without an account. Authenticated accounts are required for
  identity-dependent actions.
- **Status:** **Approved** · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.2 §6 and Phase 2.3 §11
- **Rationale:** Two providers cover the realistic Ethiopian user base with
  the least implementation surface; passwordless removes password storage,
  reset flows and credential-stuffing exposure entirely.
- **Affected:** Accounts, identity model, email subsystem, reviews,
  favourites, privacy, operations console (see D-45)
- **Supersedes:** The Apple-inclusive provider set recorded in v0.2 (NC-5)

### D-49 — Web + Telegram shared frontend direction

- **Decision:** The Telegram Mini App is a **first-class client surface**
  running in Telegram's Mini App web runtime on the **same backend and domain
  model**. Web and Mini App reuse the same frontend implementation as far as
  technically practical — design tokens, components, business cards, search
  UI, profile presentation, layouts, interaction patterns, core API
  integration and product terminology are shared. Telegram-specific logic
  stays isolated behind an adapter boundary. **No separate backend. No
  duplicated business logic.**
- **Status:** **Approved** (direction) · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.2 §10–11 and Phase 2.3 §14
- **Rationale:** A Mini App is a web page Telegram opens; one implementation
  with an adapter yields two surfaces without a second codebase or a second
  product.
- **Affected:** Frontend architecture, design system, SEO, API, platform docs
- **Related open items:** D-16 (JS approach), D-17 (view layer), D-38 (Mini
  App navigation model), D-33 (Telegram identity — separate question)

### D-50 — V1 business-data collection model

- **Decision:** V1 business information is collected **directly by Bulbula
  through contact or visits, with the business's permission**. The lifecycle
  is discover → obtain permission → collect → verify → create → publish →
  maintain → correct → re-verify. The collection target is
  business-related information: name, category, subcategory, description,
  address, business phone, business email where applicable, website, public
  social links, opening hours, services, products, pricing where appropriate,
  photos, coordinates and other genuinely business-related information.
- **Status:** **Approved** · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.2 §4 and Phase 2.3 §10
- **Rationale:** First-party data with recorded permission is more accurate
  than desk research, gives every listing a named contact for freshness, and
  puts Bulbula in a materially better position on accuracy and removal
  requests.
- **Affected:** Operations, data model (provenance and permission records),
  listing quality, legal posture, advertising sales motion
- **Related open items:** D-43 (permission record contents and retention),
  D-44 (services/products/pricing representation)

### D-51 — Data minimization and information classification

- **Decision:** Collect the minimum personal information necessary and prefer
  business information wherever the product goal can be achieved without
  personal data. **Never treat data as non-personal solely because it appears
  on a business listing** — classify by whether it identifies or relates to a
  natural person. Do not collect owners' private phone numbers or emails,
  employee lists, national ID information, personal residential addresses,
  unnecessary personal documents or unnecessary demographic information.
- **Status:** **Approved** (principle) · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.2 §5 and Phase 2.3 §10
- **Rationale:** Shrinks the compliance surface, reduces breach impact, and
  is achievable because a directory's product goals are almost entirely
  served by business-level facts.
- **Affected:** Data model, operations console field design, privacy
  documentation, retention, DPIA
- **Related open items:** D-46 (retention and minimum age), D-42 (data
  location)

### D-52 — Mobile-first web

- **Decision:** The web frontend is designed mobile-first from the beginning
  — mobile screen sizes, touch interaction, thumb reach, low bandwidth,
  low-end hardware and compact layouts first, then tablet, desktop and large
  desktop. Desktop-first design that is merely shrunk is not acceptable. SEO,
  accessibility, deep linking, browser navigation, shareability and desktop
  usability must not be sacrificed.
- **Status:** **Approved** · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.1 §2 and Phase 2.3 §15
- **Rationale:** The Ethiopian market is overwhelmingly mobile and
  bandwidth-constrained; mobile-first is also the cheapest route to the
  performance budget.
- **Affected:** Design system, component design, performance budget, UX
  research scope

### D-53 — Brand direction

- **Decision:** Primary colour orange, secondary blue, light mode
  white-dominant. **The logo is not finalized**; no placeholder mark may be
  treated as final branding. The complete semantic colour/token system is
  determined in the later UI research phase.
- **Status:** **Approved** (direction) · logo **Open** · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.3 §16
- **Rationale:** Fixes enough of the brand to start token work without
  pre-empting design research.
- **Affected:** Design tokens, UI research scope, marketing assets
- **Related open items:** D-28 (temporary brand-mark policy), D-19 (dark
  mode)

### D-54 — Business accounts are not V1

- **Decision:** There are no business accounts in V1. Businesses are not
  customer accounts and receive no self-service listing management. Future
  versions may introduce business accounts, login, listing creation,
  claiming, editing, profile control and submissions — **always subject to
  strict Bulbula administrator approval before publication.** No dormant
  business-account features may be built in V1.
- **Status:** **Approved** · **Date:** 2026-10-07
- **Source:** Owner decision, Phase 2.3 §8
- **Rationale:** Separating the account decision from the listing-ownership
  decision keeps the exclusion explicit and testable during PRD review.
- **Affected:** Scope, permissions model, advertising, reviews (no owner
  replies), architecture seams
- **Related:** D-02 (listing ownership)

---

## 2. Open decisions

### 2.1 Class A — before PRD v1.0 is finalized (3)

All three are **Pending external**: none can be closed by internal decision
alone, and all three may proceed in parallel with PRD drafting.

| ID | Question | Owner | Where the analysis lives |
| --- | --- | --- | --- |
| **D-46** | Minimum account age, retention schedule per data class, and confirmation of the data-location and cross-border transfer basis | Legal counsel | v0.3 §20; v0.4 §8 |
| **D-30n** | The numeric launch bar (coverage, quality, freshness, verification, search quality, reliability, moderation readiness, capacity) | Owner, after the pilot | v0.4 §9 |
| **D-40** | Confirmed administrative parent and practical boundary of the Bole Bulbula launch area | Local confirmation | v0.3 §19.2 |

### 2.2 Class B — before UX design or the data model (23)

| ID | Question | Recommendation on record |
| --- | --- | --- |
| **D-04** | Opening-hours model: regular, split shifts, exceptions, 24 h | v0.1 §11.3 |
| **D-08** | Verification rules, tiers, evidence notes, re-verification interval (internal only) | v0.3 §5.1 |
| **D-09** | Profile-completeness definition and its ranking weight | v0.1 PR-3 |
| **D-13** | Identity-linking rules across Google and email identities | v0.3 §10.2 |
| **D-14** | Admin role split: `operator` + `administrator` | v0.3 §21.2 |
| **D-16** | Frontend JS approach: htmx + Alpine vs vanilla modules (SPA rejected) | v0.2 §7.6 |
| **D-17** | View/template layer: in-house vs library | v0.2 §16.3 |
| **D-19** | Dark mode on the website (Telegram surface is dark regardless) | v0.3 §13.3 |
| **D-21** | Google Maps key ownership, billing, embed strategy, fallback | v0.1 §11.4 |
| **D-25** | Media storage provider, limits, formats | v0.3 §20.2 |
| **D-27** | Analytics granularity, retention, raw-event policy | v0.3 §20 |
| **D-33** | **Telegram identity integration — open evaluation** (see §5) | v0.3 §10.4 |
| **D-34** | Review mechanics: per-business vs per-branch, edit window, deletion semantics, rating-only | v0.3 §16 |
| **D-35** | Whether guests may submit structured edit suggestions | v0.3 §11.1 |
| **D-38** | Mini App navigation model: full page loads vs fragment swaps | v0.3 §12.3 |
| **D-39** | Integrity controls separating ad sales from staff-controlled listing data | v0.3 §15.3 |
| **D-42** | Which data classes must be Ethiopia-hosted, and the hosting approach | v0.3 §20.2 |
| **D-43** | Permission record contents and retention | v0.3 §5.4 |
| **D-44** | Services / products / pricing representation (absorbs the former D-07) | v0.3 §5.3 |
| **D-45** | **Staff/admin authentication strength** — TOTP or stronger (see §6) | v0.3 §8.3 |
| **D-55** | Which attributes bind to Branch vs Business (hours, contact, reviews, analytics) | v0.3 §17.1 |
| **D-56** | Category catalogue production: sourcing, depth per branch, initial size | v0.3 §18 |
| **D-57** | Category cardinality per listing (one primary + N secondary) | v0.3 §18.2 |

*(Twenty-three rows; D-55…D-57 are new sub-decisions created by the approvals
in §1 and are counted within Class B.)*

### 2.3 Class C — during implementation / TRD (7)

| ID | Question |
| --- | --- |
| **D-11** | Billing mechanics for staff-entered advertising orders and invoices |
| **D-20** | Confirmed host resource limits and MariaDB tuning |
| **D-23** | Production configuration management and secret custody |
| **D-26** | Scope of coverage and mutation-score gates as the domain grows |
| **D-28** | Temporary brand-mark policy until the logo is finalized |
| **D-41** | Transactional email provider selection (criteria fixed in v0.3 §9.3) |
| **D-42b** | Specific Ethiopian hosting vendor (policy is D-42, Class B) |

### 2.4 Class D — deferred beyond V1 (4 tracked + the exclusion list)

| ID | Item |
| --- | --- |
| **D-15r** | Whether Flutter happens within year one |
| **D-36** | Review photos |
| **D-37** | "Helpful" voting on reviews |
| **D-47** | Sign in with Apple for a future iOS client (App Store rules make it likely) |
| **D-31** | Pilot execution is scheduled, not deferred — tracked as the input to D-30n |

Deferred capabilities without decision IDs are listed in
`product-decision-brief-v0.4.md` §5 (explicit V1 exclusions).

---

## 3. Pending external items (legal/compliance)

**None of these is resolved. None may be assumed.** See
`product-decision-brief-v0.4.md` §8 for the full register (L-1…L-22).

| ID | Item | Needs |
| --- | --- | --- |
| **D-46** | Minimum account age; retention periods per data class | Counsel |
| **L-2 / D-42** | Precise data-location policy and its legal sufficiency | Counsel + architecture |
| **L-10 / L-12** | Cross-border transfer assessment for Google, the email provider, Telegram, maps and CDN | Counsel |
| **L-3** | ECA registration requirements and process (register may not yet be operational) | Counsel |
| **L-4** | DPO appointment requirement | Counsel |
| **L-15** | Review-content liability and takedown obligations | Counsel |
| **L-17 / L-20** | Advertising invoicing, VAT/tax, trade licence to sell advertising | Counsel + finance |

---

## 4. Decisions requiring the pilot

| ID | Item | Blocked until |
| --- | --- | --- |
| **D-30n** | Numeric launch bar | 20-business pilot complete and benchmarked |
| **D-31** | Operational capacity assumptions (listings per staff-hour, permission rate) | Same |
| **D-40** | Confirmed administrative parent and practical boundary of the Bole Bulbula launch area | Local confirmation (can run alongside the pilot) |

---

## 5. D-33 — Telegram identity integration

- **Status:** **Open evaluation** (Class B). **Not approved.**
- **What is confirmed:** the Telegram Mini App is a V1 client surface (D-15,
  D-49).
- **What is not confirmed:** whether a Telegram account may be used to
  authenticate a Bulbula customer identity. The confirmed provider list
  (D-48) is Google and email only, and Telegram must not be silently added
  to it.
- **Why it is still open:** redirect-based OAuth inside an embedded WebView
  is a known friction point, so Mini App sign-in may be materially worse
  without it — but adopting it adds a third identity provider and a further
  processor relationship.
- **Decide with:** the Mini App authentication prototype in hand.
- **Analysis:** v0.3 §10.4.

---

## 6. D-45 — Staff / admin authentication strength

- **Status:** **Open** (Class B — security). **Not approved.**
- **Context:** customers are passwordless (D-48). The operations console can
  publish listings, approve campaigns and read permission records, so an
  email-only factor is not an adequate control for staff.
- **Direction to evaluate in the TRD/security phase:** TOTP or a stronger
  mechanism for all staff accounts.
- **Constraint:** staff security must not be weakened merely because the
  customer system is passwordless.
- **Analysis:** v0.3 §8.3; risk RK-19.

---

## 7. Superseded and renumbered decisions

| ID | Resolution |
| --- | --- |
| **D-07** | Merged into **D-44** (services/products/pricing representation) |
| **D-22** | Split: requirement-affecting minima → **D-46**; the remainder is tracked as L-1…L-22 in v0.4 §8 |
| **D-30** | Split: direction **Approved**; numeric bar → **D-30n** (Pending external) |
| **D-15** | Split: launch surfaces **Approved**; Flutter timing → **D-15r** (Deferred) |
| **D-24** | Split: channel and requirement **Approved**; provider → **D-41** (Class C) |
| **D-42** | Split: policy **D-42** (Class B); vendor **D-42b** (Class C) |

---

## 8. Change log

| Version | Date | Change |
| --- | --- | --- |
| v1.0 | 2026-10-07 | Register created from `product-decision-brief-v0.3.md` §26 and the Phase 2.3 owner approvals. 19 decisions Approved; D-48…D-57 assigned; D-32 Closed |
