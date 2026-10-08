# Bulbula Decision Register

| | |
| --- | --- |
| **Document** | Decision Register |
| **Version** | v1.1 |
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

### Counts at v1.1

| Category | Count |
| --- | --- |
| **Approved** | **23** |
| **Closed** | 1 |
| **Open — Class A** (all three also Pending external) | 3 |
| **Open — Class B** | 19 |
| **Open — Class C** | 7 |
| **Deferred — Class D** | 4 |
| **Scheduled** (pilot execution, D-31) | 1 |
| **Merged or split into other IDs** (D-07, D-22) | 2 |
| **Total tracked** | **60** |

Counts are recomputed from the entries themselves, not carried forward.
**v1.1 moved D-34, D-55, D-56 and D-57 from Class B to Approved**, so
Approved rose by four and Class B fell by four; the total is unchanged
because no identifier was created or retired.

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
- **Related:** D-55 (attribute boundary, **Approved**), D-34 (review
  attachment level — Reviews belong to the Branch, **Approved**)

### D-05 — V1 discovery surfaces

- **Decision:** The V1 discovery surfaces are those enumerated in the
  approved scope: homepage, search, autocomplete, category and subcategory
  browsing, location/area browsing, category × area pages, nearby/distance,
  business profiles, opening hours and open status, maps, contact actions,
  trust indicators, reviews, Saves, report/suggest correction,
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
- **Related:** D-56 (catalogue production, **Approved**), D-57 (category
  cardinality per listing, **Approved**)

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
- **Related:** D-34 (subject, edit window, deletion, rating-only —
  **Approved**). **Related open items:** D-36 (review photos), D-37 (helpful
  voting)

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

### D-34 — Review mechanics

- **Decision:** The V1 Review model is fixed as follows.
  **Subject:** a Review belongs to a **Branch**, never directly to a
  Business. A multi-branch Business therefore has branch-level Reviews, and
  a Business-level rating summary is an **aggregate** derived from its
  Branches' Reviews wherever the product specification calls for one.
  **Rating:** an integer from **1 to 5**, required for every Review.
  **Uniqueness:** one Customer may hold at most one active Review per
  Branch; reviewing the same Branch again is an **edit** of the existing
  Review, never a second Review.
  **Text:** optional — **rating-only Reviews are supported**. The maximum
  text length is an implementation/configuration parameter and is not fixed
  by this decision.
  **Editing:** a Customer may edit their own Review for **30 days** after
  creation; an edit **re-enters moderation** before it becomes or returns
  to a Published state.
  **Deletion:** author deletion is a **withdrawal / soft-deletion** model,
  not immediate destructive erasure. Public visibility ceases per the
  Review state model; an internal record may remain where retention, audit,
  abuse prevention or legal obligation requires it. **The retention period
  itself is governed by the privacy and legal documentation and is not
  decided here.**
  **Moderation:** V1 Review moderation is **pre-publication**. A submitted
  Review enters `pending` and becomes `published` only on moderation
  approval; rejected Reviews never become public. Post-publication removal
  remains available for later moderation cases.
  **Rating summary:** the **arithmetic mean of currently Published Review
  ratings** for the applicable Branch or Business scope. Rejected, removed
  and deleted Reviews do not contribute. **No weighting.**
  **Public ordering:** **newest Published Review first.** Any alternative
  ordering requires a later decision.
  **Account age:** there is **no minimum account-age requirement** before a
  Customer's first Review in V1; authentication and anti-abuse controls
  still apply.
  **Appeals:** handled by an Administrator. The appeal mechanism and any
  time limit remain an operational configuration detail and are **not**
  fixed here.
  **Rate limits and anomaly thresholds:** remain **configuration**, not
  schema, and no value is fixed here.
- **Status:** **Approved** · **Date:** 2026-10-07
- **Source:** Owner approval during M0 implementation/schema gate
- **Rationale:** Address, hours and contact are facts about a place, so the
  experience a Customer reviews is a place as well; attaching the Review to
  the Branch makes a multi-location rating honest and makes the aggregate a
  derivation rather than a fiction. Pre-publication moderation is the
  defensible posture for a directory that cannot yet absorb the liability of
  publishing unreviewed third-party content about named businesses.
- **Affected:** Data model (Review, ReviewModeration, ReviewReport, rating
  summary), review policy, moderation operations, API, UX, search ranking
  inputs, privacy and retention documentation
- **Resolves:** The LC-4 pre- versus post-publication question, in favour of
  **pre-publication**; LC-5 (an edit returns to moderation); LC-6 (author
  deletion is a withdrawal); AB-3 (the subject is the Branch); AB-8 (no
  account-age requirement); SUM-6 (arithmetic mean of Published Reviews)
- **Related open items:** D-36 (review photos, Deferred), D-37 (helpful
  voting, Deferred), D-39 (commercial/editorial integrity — **separate and
  unresolved; this decision does not close it**), D-46 and L-21 (retention
  periods — **PENDING COUNSEL**)

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
  Saves, privacy, operations console (see D-45)
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

### D-55 — Business versus Branch attribute boundary

- **Decision:** Attributes bind to the level at which they are true.
  **Business-level** attributes describe the brand or business identity as a
  whole: business name, business description, website, brand-level public
  social links, and other genuinely brand-level attributes.
  **Branch-level** attributes describe a physical location: address, Area,
  Sub-city, Landmark, latitude, longitude, phone, branch email, opening
  hours, branch-specific services, branch-specific products, branch-specific
  pricing, and other location-specific operational information.
  **Reviews belong to the Branch** (D-34); Business-level rating aggregates
  are derived from Branch Reviews.
  **Analytics:** measurements whose meaning is tied to a physical location —
  behavioural, contact and discovery events — belong to the **Branch**;
  Business-level analytics aggregate Branch-level data.
  **Media:** a media asset may attach to a **Business or a Branch**,
  according to whether the image represents the business identity or a
  physical location.
  **Rule:** an attribute **MUST NOT** be moved between Business and Branch
  for implementation convenience. This boundary is an approved product and
  data-model rule.
- **Status:** **Approved** · **Date:** 2026-10-07
- **Source:** Owner approval during M0 implementation/schema gate
- **Rationale:** A single-location business is then never a special case: it
  simply has one Branch carrying the location facts. Placing location facts
  on the brand would make multi-location support a migration instead of a
  data task, which is precisely what D-03 was approved to avoid.
- **Affected:** Data model (Business, Branch, MediaAttachment, analytics
  keying), business profile presentation, contact actions, opening hours,
  listing operations, search
- **Related open items:** D-04 (the opening-hours **structure** — this
  decision fixes only that hours live on the Branch), D-44 (the
  representation of services, products and pricing — this decision fixes
  only that branch-specific ones live on the Branch), D-09 (completeness
  definition)

### D-56 — Category catalogue production

- **Decision:** **Bulbula centrally owns and manually curates** the
  Category and Subcategory catalogue. The catalogue is produced from the
  real launch-area business inventory and from genuine discovery and search
  needs. Rules: exactly **two levels**, Category → Subcategory; **no
  user-created Categories or Subcategories**; an **English label is
  required**; an **Amharic label is supported** where available; controlled
  Aliases are supported; the catalogue must be expressive enough to classify
  the launch-area Businesses; taxonomy gaps found during operations are
  resolved by **Administrator-controlled catalogue changes**. **Catalogue
  content is reference data, not application schema** — adding, removing or
  reclassifying catalogue content **MUST NOT** require a code change or a
  schema migration where the existing model already supports it.
- **Status:** **Approved** · **Date:** 2026-10-07
- **Source:** Owner approval during M0 implementation/schema gate
- **Rationale:** A curated catalogue built from observed local supply is the
  cheapest lever on search quality and keeps URLs stable; separating
  catalogue content from schema means the taxonomy can grow with the launch
  area without a deployment.
- **Affected:** Taxonomy data, navigation, search, SEO and URLs, operations
  console category management, listing classification, migration policy
  (TR-177), release management
- **Deliberately not decided:** the number of Categories or Subcategories,
  any coverage percentage, a catalogue completion date, and the catalogue
  list itself. Those are **content and operations matters**, tracked through
  listing operations, not through this decision
- **Related open items:** D-40 (launch-area boundary, which shapes the
  inventory the catalogue is drawn from), D-09 (completeness definition)

### D-57 — Category cardinality per Listing

- **Decision:** Every Listing has **exactly one Primary Category** and
  **zero or more Secondary Categories**. Rules: exactly one Primary Category
  is **required for every published Listing**; zero Secondary Categories is
  permitted; one or more Secondary Categories is permitted; the **same
  Category MUST NOT appear twice** for one Listing; the Primary Category
  **MUST NOT** simultaneously appear as a Secondary Category; **no
  artificial numeric maximum** is imposed on Secondary Categories in V1; the
  taxonomy remains **two levels** deep; and assignment must remain
  compatible with D-56. The relational design **MUST** support this without
  inventing an arbitrary secondary-category limit.
- **Status:** **Approved** · **Date:** 2026-10-07
- **Source:** Owner approval during M0 implementation/schema gate
- **Rationale:** One required Primary Category gives every Listing exactly
  one stable breadcrumb, URL path and ranking classification, while optional
  Secondary Categories let a genuinely multi-trade business be found without
  fragmenting navigation. An invented maximum would be a guess with no
  evidence behind it.
- **Affected:** Data model (the Listing-to-Category join and its
  constraints), category browsing, breadcrumbs, search filtering,
  advertising targeting, operations console classification
- **Related open items:** D-09 (whether classification contributes to
  completeness or ranking weight)

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

### 2.2 Class B — before UX design or the data model (19)

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
| **D-35** | Whether guests may submit structured edit suggestions | v0.3 §11.1 |
| **D-38** | Mini App navigation model: full page loads vs fragment swaps | v0.3 §12.3 |
| **D-39** | Integrity controls separating ad sales from staff-controlled listing data | v0.3 §15.3 |
| **D-42** | Which data classes must be Ethiopia-hosted, and the hosting approach | v0.3 §20.2 |
| **D-43** | Permission record contents and retention | v0.3 §5.4 |
| **D-44** | Services / products / pricing representation (absorbs the former D-07) | v0.3 §5.3 |
| **D-45** | **Staff/admin authentication strength** — TOTP or stronger (see §6) | v0.3 §8.3 |

*(Nineteen rows. **D-34, D-55, D-56 and D-57 left this table on 2026-10-07**,
when the owner approved them at the M0 schema gate; they are now recorded in
§1.)*

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
| v1.1 | 2026-10-07 | **M0 schema gate.** Owner approved the four schema-shaping decisions: **D-34** (Review mechanics — Reviews belong to the Branch, 1–5 integer rating, optional text, 30-day edit window returning to moderation, withdrawal-style author deletion, pre-publication moderation, mean of Published ratings, newest first, no account-age bar), **D-55** (Business versus Branch attribute boundary), **D-56** (centrally curated two-level Category catalogue as reference data), **D-57** (exactly one Primary Category plus zero or more Secondary Categories, no artificial maximum). Each moved from Open Class B to Approved under its existing ID. Approved 19 → 23; Class B 23 → 19; total unchanged at 60. D-39, D-46 and every `L-` item remain unaffected |
