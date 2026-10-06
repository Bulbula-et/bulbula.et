# Bulbula — Product Requirements Document V1

## 1. Document control

| | |
| --- | --- |
| **Document** | Product Requirements Document — Bulbula V1 |
| **Version** | v1.0 |
| **Status** | **Draft** |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — (first PRD; derived from `docs/00-discovery/product-decision-brief-v0.4.md`) |

### 1.1 Authority and precedence

```text
Owner-approved decisions
        ↓
docs/60-decisions/decision-register.md      ← authoritative for decisions
        ↓
this PRD                                    ← authoritative for requirements
        ↓
TRD / UX / platform specifications
        ↓
implementation
```

This document **specifies requirements**; it does not make decisions. Where a
requirement rests on a decision, the decision ID is cited. If this PRD and the
decision register ever disagree, the register is correct and this PRD is
defective.

### 1.2 Companion documents

| Document | Role |
| --- | --- |
| [`scope-v1.md`](scope-v1.md) | Authoritative V1 in/out list; this PRD references rather than restates it |
| [`glossary.md`](glossary.md) | Canonical terminology; binding on this document |
| [`interaction-permissions.md`](interaction-permissions.md) | Definitive actor × capability matrix |
| [`review-policy.md`](review-policy.md) | Full review and moderation policy |
| [`listing-operations.md`](listing-operations.md) | The listing lifecycle and the pilot |
| [`roadmap.md`](roadmap.md) | V1 / V2+ / exploratory boundaries |
| [`../15-business/business-model.md`](../15-business/business-model.md) | Value proposition and revenue model |
| [`../15-business/advertising-products.md`](../15-business/advertising-products.md) | Advertising product definitions |
| [`../60-decisions/decision-register.md`](../60-decisions/decision-register.md) | **Decision authority** |

### 1.3 Reading conventions

| Convention | Meaning |
| --- | --- |
| **MUST** | A V1 requirement. Absence is a defect |
| **SHOULD** | Strongly expected; deviation requires a recorded reason |
| **MAY** | Permitted, not required |
| **[C]** | Confirmed by an approved decision |
| **[P]** | Proposed — not yet approved; must not be implemented as a requirement |
| **[U]** | Unknown / open |
| **Open — implementation/product detail (D-xx)** | Deliberately unspecified here; resolved before UX/data model or in the TRD |
| **PENDING PILOT** | Awaits the 20-business operational pilot (D-30, D-31) |
| **PENDING COUNSEL** | Awaits professional legal confirmation (D-46 and the L-items) |

### 1.4 Status conditions on this document

This PRD **MUST remain in status `Draft`** until:

1. legal and compliance items are sufficiently resolved (D-46 and the L-items);
2. the 20-business operational pilot is complete (D-31); and
3. the pilot-derived numeric launch threshold is set by the owner (D-30n).

Items 1–3 may proceed in parallel with design and technical work. No
unresolved item in this document may be silently converted into an invented
requirement.

---

## 2. Executive summary

Bulbula V1 is a **Bulbula-operated local business directory for Bole Bulbula,
Addis Ababa**, delivered simultaneously on the Web and as a Telegram Mini App.

Three characteristics define it:

1. **Bulbula builds the data.** Every Listing is discovered, collected with
   the Business's permission, verified, published and maintained by Bulbula
   staff. Businesses have no accounts, cannot create or claim Listings, and
   cannot edit published information (D-02, D-50, D-54).
2. **The product has three actors, not four.** Guest, Customer and Staff
   (Operator, Administrator). A Business is the *subject* of a Listing and,
   separately, a commercial counterparty for advertising — never a platform
   user in V1.
3. **The internal operations console is a primary product surface**, not
   back-office tooling. In a company-managed directory it is the production
   line that produces the product itself.

V1 succeeds if a resident of Bole Bulbula can find a specific business —
correct phone number, current hours, accurate location — on a mid-range
Android phone over a slow connection, in under a minute, without an account.

**Monetization** in V1 is staff-managed, fixed-package sponsored placement:
no auction, no CPC/CPM/CPA, no self-service dashboard, and organic ranking
strictly separated from paid placement (D-10).

---

## 3. Product vision

> Every business in Bole Bulbula is findable, correct and current — on the
> device people actually carry, in the apps they already use.

Bulbula aims to become the reference answer to "where do I get X near me?"
in its coverage area, and to extend that coverage area only once the model is
proven. Accuracy is the product; breadth without accuracy is a worse product
than depth with it.

---

## 4. Product mission

Bulbula's mission in V1 is to:

1. **Collect** business information directly from businesses, with their
   permission, rather than assembling it from unreliable secondary sources
   (D-50).
2. **Verify** that information and keep it fresh through a defined,
   auditable operational lifecycle (D-08).
3. **Present** it through fast, mobile-first discovery on both the Web and
   Telegram (D-15, D-49, D-52).
4. **Sustain** the operation through clearly labelled sponsorship that never
   distorts organic results (D-10).
5. **Respect** the people involved by collecting the minimum personal
   information necessary (D-51).

---

## 5. Problem statement

### 5.1 For residents and visitors

Finding a specific local business in Addis Ababa is unreliable. Information
is scattered across Telegram channels, word of mouth, Facebook pages and
national directories whose data is frequently wrong, stale or duplicated.
Phone numbers do not answer; hours are guesses; locations are approximate.
The cost of a wrong answer is a wasted trip in heavy traffic.

### 5.2 For businesses

Small local businesses have limited digital presence and limited means to
create one. Most cannot maintain a website, and many do not have a laptop.
Their reachable customers are nearby, but there is no dependable local
channel through which those customers can find them.

### 5.3 Why existing options do not solve it

National directories optimise for breadth, which in practice means thin,
unverified records. Social channels optimise for recency, not retrieval:
there is no way to ask "which pharmacies near me are open now?". Map products
cover the area unevenly and depend on the business itself to maintain data —
exactly the step that does not happen.

### 5.4 Bulbula's thesis

Accuracy in a small, well-covered area beats breadth across a large one. That
is achievable only if the operator — not the business — owns data quality,
which is precisely what D-02 and D-50 establish.

**Evidence basis.** The market characteristics above are supported by
research findings R-06 (mobile-first, Telegram-centric market) and R-07
(crowded low-quality national directory landscape) in
[`research-notes-v0.1.md`](../00-discovery/research-notes-v0.1.md). Statements
about *this* neighbourhood specifically are **[P]** until the pilot (D-31)
provides field evidence.

---

## 6. Market and launch context

| Factor | Statement | Source |
| --- | --- | --- |
| Device profile | Predominantly Android, mid- to low-range, with constrained bandwidth and data cost sensitivity | R-06 |
| Messaging behaviour | Telegram is unusually dominant and is a primary discovery and sharing channel | R-06 |
| Directory landscape | Several national directories exist with low data quality; local depth is the differentiator | R-07 |
| Language | Amharic is the urban lingua franca; English is widely used in business contexts. Five federal working languages exist nationally | R-21 |
| Payments | Mobile-money-first; card and gateway integration carries real friction | R-08 |
| Regulation | Proclamation 1321/2024 applies and is GDPR-shaped, including residency and registration duties | R-05, R-26 |

**Interpretation (Bulbula's, not the sources'):** the combination of a
mobile-first, Telegram-centric market and poor incumbent data quality is what
makes a hand-built, permission-based directory with a Telegram surface a
credible entrant. This interpretation is **[P]** and is tested by the pilot
and by launch metrics, not asserted as fact.

**Competitor features are not requirements.** No requirement in this document
exists because a competitor has it.

---

## 7. Target geography

| | |
| --- | --- |
| **V1 coverage** | **Bole Bulbula, Addis Ababa** |
| **Administrative parent** | To be confirmed locally — **Open (D-40)**. Public sources place Bole Bulbula in Bole sub-city, but boundaries were redrawn in 2020 and sources conflict (R-20, R-22) |
| **Practical boundary** | Defined by Bulbula as part of D-40; the coverage metric in the launch criteria is expressed against it |

### 7.1 Requirements

| ID | Requirement |
| --- | --- |
| GEO-1 | The location model **MUST** support the hierarchy Country → City/Region → Sub-city → Area → optional Landmark, with Area as the user-facing unit |
| GEO-2 | Areas **MUST** be curated records with controlled Aliases, never free text |
| GEO-3 | Areas **MUST** support Amharic labels and Amharic Aliases (D-18) |
| GEO-4 | No application behaviour, URL, label or rule **MAY** be hard-coded to a specific Area, Sub-city or City. Adding a new Area **MUST** be a data operation |
| GEO-5 | V1 **MUST** populate the hierarchy one branch deep (the launch area and its immediate neighbours) and **MUST NOT** attempt national geographic coverage |
| GEO-6 | Sub-city **MUST** be recorded on every Branch for administrative correctness even though it is secondary in the interface |

**Out of scope:** polygon geometry, national geographic datasets, routing,
and any geographic administration beyond the launch area (`scope-v1.md` §2).

---

## 8. Target users

V1 has **three platform actors**. A Business is not a platform user (D-54).

### 8.1 Guest

An unauthenticated User. The default state for most traffic, and the state in
which most product value is delivered.

| Attribute | Statement |
| --- | --- |
| Goal | Find a specific business, or orient themselves in the area |
| Access | All public discovery (`interaction-permissions.md` §2) |
| Constraint | Cannot perform any action that must be attributed to a person |
| Requirement | GUEST-1: Discovery **MUST NOT** require an account, a cookie banner interaction, or any registration prompt that blocks content (D-48) |

### 8.2 Customer

An authenticated end user.

| Attribute | Statement |
| --- | --- |
| Goal | The Guest's goals, plus keeping a Saved list and contributing Reviews |
| Authentication | Google or email OTP only; no passwords; no Apple (D-48) |
| Requirement | CUST-1: Every Customer-only capability **MUST** be reachable from the equivalent Guest state with a single, contextual sign-in step that returns the Customer to the original task |

### 8.3 Operator

A Bulbula staff member who runs the listing lifecycle.

| Attribute | Statement |
| --- | --- |
| Goal | Produce and maintain complete, verified Listings at a sustainable rate |
| Scope | Listing creation and editing, collection, verification, media, Corrections, Review moderation, Report handling |
| Constraint | Cannot change taxonomy, Campaigns, roles or policy without Administrator rights — **Open (D-14)** for the exact split |
| Requirement | OPR-1: Every Operator action that changes published data **MUST** be attributable in the Audit log (C-29) |

### 8.4 Administrator

A Bulbula staff member with higher-risk permissions.

| Attribute | Statement |
| --- | --- |
| Goal | Govern taxonomy, locations, Campaigns, policy, roles and platform health |
| Scope | Everything an Operator can do, plus taxonomy and location management, campaign approval, user management and audit access |
| Requirement | ADM-1: Higher-risk actions (publish policy changes, taxonomy changes, campaign activation, role changes) **MUST** be restricted to Administrators and **MUST** be audited |

### 8.5 Business — subject, not user

| Relationship | Status in V1 |
| --- | --- |
| Subject of a Listing | **Yes.** Bulbula collects and publishes its information with Permission (D-50) |
| Counterparty for advertising | **Yes**, through Staff. No account, no login, no dashboard (D-10) |
| Platform user | **No** (D-54) |
| Able to edit published data | **No.** Changes are requested through Staff and applied as Corrections (D-02) |

---

## 9. Personas

Personas are planning aids, not requirements. Each characteristic below is
marked **[R]** research-supported or **[A]** assumption. Assumptions **MUST**
be validated or replaced after the pilot (D-31).

### 9.1 Customer personas

**P1 — The resident** (primary)

| | |
| --- | --- |
| Profile | Lives or works in Bole Bulbula; 20–45; Android phone **[R]** (R-06); daily Telegram user **[R]** (R-06) |
| Behaviour | Searches for a specific need, now. Mixes Amharic and transliterated English **[A]** |
| Needs | A working phone number, current hours, an accurate location |
| Success | A phone call placed or a visit made |
| Product implications | Search speed, contact actions, open-now status, verified data |

**P2 — The newcomer** (secondary)

| | |
| --- | --- |
| Profile | Recently moved to or regularly visits the area **[A]** |
| Behaviour | Browses categories and areas more than searching |
| Needs | Orientation: what exists here, what is good, what is open |
| Product implications | Category and area browsing, category × area pages, trust indicators |

**P3 — The deliberate buyer** (secondary)

| | |
| --- | --- |
| Profile | Planning a considered purchase or service — clinic, gym, contractor **[A]** |
| Behaviour | Compares several businesses on rating, services, photos and price before contacting |
| Needs | Profile depth and credible reviews |
| Product implications | Profile completeness, Reviews, services/products (D-44), photos |

### 9.2 Business profiles (subjects, not users)

These exist because operations and advertising need them. They are **not**
platform user personas (D-54).

**B1 — The single-shop business** (primary subject)

| | |
| --- | --- |
| Profile | One location, owner-operated, digital presence is a phone number and perhaps a Telegram channel **[A]** |
| Relevance to operations | Permission is granted in person; data must be collectable in one visit; the owner's mobile may be the only business number — see §14.4 |
| Relevance to monetization | Price-sensitive; most likely to buy the cheapest fixed Package, if anything |

**B2 — The established local business** (primary advertising counterparty)

| | |
| --- | --- |
| Profile | Two to five Branches; a manager handles marketing; may already advertise via Telegram channels or billboards **[A]** |
| Relevance to operations | Multi-branch data; a named manager contact; likelier to request Corrections |
| Relevance to monetization | **This is the counterparty that pays** for category sponsorship or homepage promotion |

### 9.3 Staff personas

**S1 — The Operator.** Needs queues, fast capture, duplicate detection and
clarity about what a Listing still lacks. In the first period this may be the
founder **[A]**.

**S2 — The Administrator.** Needs taxonomy and location governance, campaign
control, moderation oversight, and audit visibility **[A]**.

---

## 10. User journeys

### 10.1 Customer discovery journey

```text
Discover → Search → Filter → Evaluate → Business profile
        → Contact / Visit → Save / Review → Return
```

| Step | What happens | Requirements and capabilities |
| --- | --- | --- |
| Discover | Arrives via organic search, a shared link, a Telegram share, or direct entry | C-01, C-17, C-37 |
| Search | Enters a term, or taps a Category or Area | C-02, C-03, C-04, C-05 |
| Filter | Narrows by category, area, open now, rating, verified; sorts by relevance, distance or rating | C-02 |
| Evaluate | Scans result cards: name, category, area, rating, open status, verified marker, distance | C-02, C-12 |
| Business profile | Opens a profile: hours, contact, location, services, photos, reviews | C-08, C-09, C-10, C-13 |
| Contact / Visit | Calls, opens the website, requests directions, or opens a social link | C-11 |
| Save / Review | Saves the Business, or signs in to write a Review | C-14, C-13, C-30, C-31 |
| Return | Returns via the Saved list, a share, or organic search | C-34, C-37 |

**Journey-level acceptance criteria**

```text
Given a Guest on a mobile device
When the Guest searches for a business category and applies an area filter
Then results are displayed without any authentication step
And each result shows enough information to decide whether to open it.

Given a Guest viewing a Business profile
When the Guest taps the call action
Then a phone call is initiated using the Business's published number
And the action is recorded as an analytics event without identifying the Guest.

Given a Guest who attempts to Save a Business
When the Guest is not authenticated
Then the product offers sign-in in context
And on successful sign-in the Save completes without losing the Guest's place.
```

### 10.2 Bulbula listing operations journey

```text
Discover business → Contact / visit → Permission → Collect information
   → Verify → Create listing → Quality review → Publish
   → Maintain → Correct → Re-verify
```

Specified in full in [`listing-operations.md`](listing-operations.md).
Capability requirements: C-19 … C-29. Decision basis: D-02, D-50, D-08,
D-43.

**Journey-level acceptance criteria**

```text
Given a business that has not granted Permission
When an Operator attempts to publish a Listing for it
Then the system prevents publication
And records the reason.

Given a published Listing
When its Verification age exceeds the policy interval
Then it is reported as stale in operational analytics
And appears in the Operator's re-verification queue.
```

### 10.3 Advertising operations journey

```text
Business interest → Staff discussion → Campaign creation → Admin approval
   → Sponsored placement → Customer interaction → Measurement → Campaign end
```

| Step | What happens | Requirements |
| --- | --- | --- |
| Business interest | A Business expresses interest, or Staff approach it — always offline | §19, `advertising-products.md` |
| Staff discussion | Staff explain available Packages and fixed prices | — |
| Campaign creation | An Operator creates a Campaign on the Business's behalf: Package, target, period | C-27 |
| Admin approval | An Administrator reviews eligibility, inventory availability and activates | C-27, ADM-1 |
| Sponsored placement | The Business appears in the purchased Placement, clearly labelled | C-16 |
| Customer interaction | Users see and may interact with the Sponsored placement | C-16, C-38 |
| Measurement | Impressions and clicks are recorded for reporting, never for pricing | C-28, C-38 |
| Campaign end | The Campaign ends automatically at period end; Staff may produce a report for the Business | C-27 |

**Journey-level acceptance criteria**

```text
Given an active Campaign for a Business
When a User views a surface containing the purchased Placement
Then the Business appears in that Placement with a Sponsored label
And the organic result ordering on the same surface is unchanged by the Campaign.

Given a Placement whose inventory for a period is fully sold
When Staff attempt to create an overlapping Campaign for the same Placement
Then the system prevents it.
```

---

## 11. V1 product boundary

The V1 boundary is defined by D-01 and enumerated in
[`scope-v1.md`](scope-v1.md). This PRD specifies the 40 approved capabilities
as **C-01 … C-40** in §12.

| Rule | Statement |
| --- | --- |
| BND-1 | No capability outside `scope-v1.md` §1 is in V1 |
| BND-2 | No approved capability may be removed without naming the reason and the affected decision ID |
| BND-3 | No requirement may reference a Business as a platform actor (D-54) |
| BND-4 | Architecture seams that keep the future owner model possible are permitted; **features** for it are not (D-02, D-54) |
| BND-5 | Any requirement that would only be useful in combination with an excluded capability is out of scope by definition |

### 11.1 Deviations from the approved capability list

**None.** All 40 approved capabilities are specified. No capability has been
added, removed or merged.

---

## 12. V1 capability specifications

Each capability uses the same template: **Purpose · Actor · Preconditions ·
Main flow · Alternative flows · Error and empty states · Acceptance criteria ·
Dependencies · Out of scope · Decisions.**

Acceptance criteria are behavioural and implementation-independent. Where a
detail is unresolved it is marked **Open — implementation/product detail
(D-xx)** and **MUST NOT** be invented downstream.

### 12.1 Public discovery

---

#### C-01 — Homepage

- **Purpose:** Give an arriving User an immediate route into discovery:
  search, categories, areas and a sense of what the directory contains.
- **Actor:** Guest, Customer.
- **Preconditions:** At least one published Listing exists.
- **Main flow:**
  1. User opens the homepage on either surface.
  2. A prominent search entry point is presented first.
  3. Discovery blocks follow: categories, areas, and a selection of
     Businesses (selection basis defined by C-02 ranking signals).
  4. Sponsored placements appear in their defined slots, labelled (C-16).
  5. User proceeds to search, a category, an area or a profile.
- **Alternative flows:** A Customer sees an entry point to their Saved list.
  On the Telegram surface the page is presented within Telegram chrome
  (§22).
- **Error and empty states:** If no Listings are published, the homepage
  **MUST** present an honest empty state rather than fabricated content. If
  sponsored inventory is unsold, the slot **MUST** collapse — never show a
  placeholder advertisement.
- **Acceptance criteria:**
  ```text
  Given a Guest opening the homepage
  When the page loads
  Then a search entry point is visible without scrolling on a mobile viewport
  And no authentication is requested.

  Given no active Campaign for a homepage Placement
  When the homepage renders
  Then no sponsored slot, placeholder or empty frame is displayed.
  ```
- **Dependencies:** C-02, C-04, C-05, C-16, C-37.
- **Out of scope:** Personalised recommendation feeds; editorial collections.
- **Decisions:** D-01, D-05, D-10.

---

#### C-02 — Search

- **Purpose:** Let a User find Businesses by what they need, in words they
  would naturally use.
- **Actor:** Guest, Customer.
- **Preconditions:** Published Listings exist and are indexed.
- **Main flow:**
  1. User enters a query, optionally arriving with a Category or Area
     pre-applied.
  2. The product returns matching Businesses ordered by organic ranking
     (§16).
  3. User refines with filters: Category, Subcategory, Area, open now,
     minimum rating, verified.
  4. User changes sort: relevance, distance (when location is available),
     rating.
  5. User opens a result (C-08).
- **Alternative flows:** Query matches a Category or Area Alias → the product
  **MAY** surface that Category or Area as a suggested destination alongside
  results. Query is empty → browse experience (C-04, C-05).
- **Error and empty states:**
  - Zero results **MUST** offer recovery: relax the most restrictive filter,
    broaden the Area, show nearby alternatives in the same Category, and
    offer the "suggest a business" path where it exists (C-15).
  - Search backend unavailable → explicit, non-technical failure message and
    an intact navigation path to categories and areas.
- **Acceptance criteria:**
  ```text
  Given a Guest
  When the Guest searches for a term matching a published Business
  Then results are returned without authentication
  And each result shows name, primary Category, Area, rating summary,
      open status and verified state where known.

  Given a search that matches nothing
  When results are rendered
  Then the zero-result state offers at least one concrete recovery action
  And the query is recorded for operational review (C-28).

  Given a Customer with location permission granted
  When the Customer sorts by distance
  Then results are ordered by distance from the Customer's position.
  ```
- **Dependencies:** C-03, C-07, C-09, C-12, C-16, C-38; ranking §16.
- **Out of scope:** Query implementation, index structure, scoring formulas
  (TRD). Natural-language or AI-driven search (`scope-v1.md` §2).
- **Decisions:** D-01, D-05, D-06, D-09, D-18.

---

#### C-03 — Search autocomplete

- **Purpose:** Reduce typing on mobile and guide Users toward queries that
  return results.
- **Actor:** Guest, Customer.
- **Preconditions:** Search is available.
- **Main flow:**
  1. User begins typing in the search field.
  2. Suggestions appear, drawn from Business names, Categories,
     Subcategories, Areas and their Aliases.
  3. User selects a suggestion and is taken directly to the corresponding
     result set or entity.
- **Alternative flows:** User ignores suggestions and submits the raw query
  (C-02).
- **Error and empty states:** No suggestions → the field stays usable and
  submission still works. Suggestion service unavailable → degrade silently
  to plain search; **MUST NOT** block input.
- **Acceptance criteria:**
  ```text
  Given a User typing in the search field
  When at least two characters have been entered
  Then suggestions matching names, Categories, Subcategories, Areas or
       their Aliases are offered.

  Given suggestions are unavailable
  When the User submits the query
  Then search executes normally.

  Given a User types an Amharic term that exists as an Alias
  When suggestions are produced
  Then the matching Category, Subcategory or Area is suggested.
  ```
- **Dependencies:** C-02, C-04, C-05, C-23, C-24.
- **Out of scope:** Personalised or history-based suggestions; typo
  correction algorithms (TRD).
- **Decisions:** D-01, D-06, D-18.

---

#### C-04 — Category and subcategory browsing

- **Purpose:** Let Users discover by what a Business *is*, not only by what
  they typed.
- **Actor:** Guest, Customer.
- **Preconditions:** Taxonomy is populated; Listings are classified.
- **Main flow:**
  1. User opens the category index, or a Category from the homepage or
     search.
  2. The Category page lists its Subcategories and Businesses classified
     under it.
  3. User narrows to a Subcategory, or filters by Area.
  4. User opens a Business profile.
- **Alternative flows:** Entry from a Business profile breadcrumb; entry from
  a category × area page (C-06).
- **Error and empty states:** A Category with no published Listings **MUST
  NOT** be presented as a navigable destination in the index. If reached
  directly, it **MUST** show an empty state and suggest related Categories.
- **Acceptance criteria:**
  ```text
  Given a published Category containing at least one published Listing
  When a Guest opens it
  Then its Subcategories and Businesses are listed
  And the page is reachable by a stable, human-readable URL (C-37).

  Given a Category with no published Listings
  When the category index is rendered
  Then that Category is not offered as a navigable destination.
  ```
- **Dependencies:** C-02, C-05, C-06, C-23, C-37.
- **Out of scope:** The category catalogue itself — **Open (D-56)**; the
  number of Categories a Listing may carry — **Open (D-57)**.
- **Decisions:** D-01, D-06, D-56, D-57.

---

#### C-05 — Location / area browsing

- **Purpose:** Let Users discover by place, which is how people in Addis
  Ababa actually navigate.
- **Actor:** Guest, Customer.
- **Preconditions:** Areas exist and Branches are assigned to them.
- **Main flow:**
  1. User opens the area index or selects an Area.
  2. The Area page presents Businesses with Branches in that Area, and the
     Categories most represented there.
  3. User narrows by Category (producing C-06) or opens a profile.
- **Alternative flows:** Arrival from a profile's Area link or from search
  filters.
- **Error and empty states:** Empty Area → honest empty state plus
  neighbouring Areas. An Area **MUST NOT** be presented as navigable when it
  contains no published Listings.
- **Acceptance criteria:**
  ```text
  Given an Area containing published Listings
  When a Guest opens it
  Then Businesses with a Branch in that Area are listed
  And the Area's Amharic label is displayed where one exists.
  ```
- **Dependencies:** C-02, C-06, C-24, C-37; GEO-1…GEO-6.
- **Out of scope:** Map-based area drawing; administrative boundary display.
- **Decisions:** D-01, D-18, D-40.

---

#### C-06 — Category × area pages

- **Purpose:** Serve the highest-intent query shape ("pharmacy in Bole
  Bulbula") with a dedicated, indexable destination.
- **Actor:** Guest, Customer.
- **Preconditions:** A Category and an Area each contain qualifying published
  Listings.
- **Main flow:**
  1. User arrives from search, navigation, or an external search engine.
  2. The page lists Businesses in that Category with a Branch in that Area.
  3. Standard filters and sorting apply.
- **Alternative flows:** Subcategory × Area pages follow the same rules.
- **Error and empty states:** A combination with too few published Listings
  **MUST NOT** be generated as an indexable page (thin-content prevention,
  §23). Direct access **MUST** return a useful page or a redirect to the
  parent Category or Area — never an empty indexable page.
- **Acceptance criteria:**
  ```text
  Given a Category × Area combination that meets the minimum-content rule
  When the page is generated
  Then it is indexable, has a canonical URL and unique metadata.

  Given a combination below the minimum-content threshold
  When a User navigates to it
  Then the product responds with the parent Area or Category page
  And no thin page is exposed to search engines.
  ```
- **Dependencies:** C-04, C-05, C-37.
- **Out of scope:** The minimum-content threshold value — **Open
  (D-56)**.
- **Decisions:** D-01, D-06, D-56.

---

#### C-07 — Nearby / distance discovery

- **Purpose:** Answer "what is near me right now?" — the highest-value mobile
  question.
- **Actor:** Guest, Customer.
- **Preconditions:** Branch coordinates exist; the User grants device
  location permission.
- **Main flow:**
  1. User chooses "nearby" or sorts search results by distance.
  2. The product requests device location permission with a clear reason.
  3. Results are ordered by distance, each showing an approximate distance.
- **Alternative flows:** Permission denied → the product falls back to Area
  selection and **MUST NOT** re-prompt repeatedly. Location unavailable or
  inaccurate → same fallback.
- **Error and empty states:** No Businesses within a sensible radius →
  present the nearest available results with their distances, clearly
  labelled as farther away.
- **Acceptance criteria:**
  ```text
  Given a Guest who denies location permission
  When the Guest uses nearby discovery
  Then the product offers Area-based browsing instead
  And does not block access to any other capability.

  Given a Guest who grants location permission
  When results are displayed
  Then each result shows an approximate distance
  And ordering is by increasing distance.
  ```
- **Dependencies:** C-02, C-05, C-08.
- **Out of scope:** Turn-by-turn navigation; continuous location tracking;
  storing a Guest's precise location (D-51).
- **Decisions:** D-01, D-51.

---

#### C-08 — Business profile pages

- **Purpose:** Present everything a User needs to decide and act, on one
  page.
- **Actor:** Guest, Customer.
- **Preconditions:** The Listing is published.
- **Main flow:**
  1. User opens a profile from search, browsing, a share or an external
     search engine.
  2. The page presents: name, primary Category, description, Area and
     address, open status and hours, contact actions, location and map,
     photos, services/products where captured, rating summary and Reviews,
     and trust indicators.
  3. User performs a contact action (C-11), Saves (C-14), writes a Review
     (C-13), shares (C-17) or reports a problem (C-15).
- **Alternative flows:** Multi-branch Business → branch selection is
  presented; which attributes vary per Branch is **Open (D-55)**. Services,
  products and pricing are displayed when captured; their representation is
  **Open (D-44)**.
- **Error and empty states:** Missing optional data **MUST** be omitted
  silently rather than shown as "unknown" clutter. An unpublished or removed
  Listing **MUST** return a not-found response and **MUST NOT** be indexable.
- **Acceptance criteria:**
  ```text
  Given a published Listing
  When a Guest opens its profile
  Then name, Category, Area, contact actions and open status are presented
  And the page is reachable at a stable canonical URL.

  Given a Listing whose Verification is current
  When the profile is rendered
  Then a verified indicator and the date of last verification are shown.

  Given a Listing that has been unpublished
  When a User navigates to its URL
  Then a not-found response is returned
  And the page is excluded from indexing.
  ```
- **Dependencies:** C-09, C-10, C-11, C-12, C-13, C-14, C-15, C-17, C-37.
- **Out of scope:** Owner-managed content of any kind (D-02, D-54); booking,
  ordering or messaging (`scope-v1.md` §2).
- **Decisions:** D-01, D-02, D-03, D-44, D-55.

---

#### C-09 — Opening hours and open status

- **Purpose:** Answer "is it open now?", the most common cause of a wasted
  trip.
- **Actor:** Guest, Customer.
- **Preconditions:** Hours are recorded for the Branch.
- **Main flow:**
  1. The product computes open or closed from the Branch's hours and the
     local time zone.
  2. Status is displayed on the profile and on result cards.
  3. The full weekly schedule is available on the profile.
  4. Users may filter search results by "open now" (C-02).
- **Alternative flows:** Hours unknown → the product **MUST** show "hours not
  confirmed" rather than guessing, and **MUST NOT** include the Listing in
  "open now" filtering.
- **Error and empty states:** Conflicting or incomplete hours → treated as
  unknown and raised to operations as a data-quality item (C-28).
- **Acceptance criteria:**
  ```text
  Given a Branch with recorded hours
  When a User views it during a period inside those hours
  Then the Branch is shown as open.

  Given a Branch with no recorded hours
  When a User filters by "open now"
  Then that Branch is excluded from results
  And its profile shows that hours are not confirmed.
  ```
- **Dependencies:** C-02, C-08, C-20.
- **Out of scope:** The hours data model — split shifts, exceptions, public
  holidays, 24-hour operation — **Open (D-04)**. Which Branch-level hours
  may diverge is **Open (D-55)**.
- **Decisions:** D-01, D-04, D-55.

---

#### C-10 — Google Maps embed and open-in-maps

- **Purpose:** Let a User see where a Branch is and hand off to a map
  application for directions.
- **Actor:** Guest, Customer.
- **Preconditions:** Branch coordinates exist.
- **Main flow:**
  1. The profile presents a map view of the Branch location.
  2. The User may open the location in an external map application.
- **Alternative flows:** On the Telegram surface, the hand-off follows that
  runtime's conventions (§22).
- **Error and empty states:** Map service unavailable, quota exceeded or
  deliberately not loaded → a static fallback (address, Area, landmark and
  an open-in-maps action) **MUST** remain usable.
- **Acceptance criteria:**
  ```text
  Given a Branch with coordinates
  When a User opens its profile
  Then the location is presented
  And an action to open it in an external map application is available.

  Given the map component fails to load
  When the profile renders
  Then address, Area and the open-in-maps action remain available.

  Given a User who has not interacted with the map
  When the profile first loads
  Then the map component does not block or delay the primary content.
  ```
- **Dependencies:** C-08, C-11; performance §24.
- **Out of scope:** Key ownership, billing, embed strategy and caching —
  **Open (D-21)**.
- **Decisions:** D-01, D-21.

---

#### C-11 — Business contact actions

- **Purpose:** Convert discovery into real-world contact — the product's core
  value event.
- **Actor:** Guest, Customer.
- **Preconditions:** The Listing holds at least one contact point.
- **Main flow:**
  1. The profile presents available actions: call, website, directions,
     public social links.
  2. The User performs one.
  3. The product records an anonymous analytics event (C-38).
- **Alternative flows:** Multi-branch Business → actions apply to the
  selected Branch; which contact points are Branch-level is **Open (D-55)**.
- **Error and empty states:** Contact point absent → the action **MUST** be
  omitted, not shown disabled. All contact points absent → the profile
  **MUST** still present location and hours, and **SHOULD** surface the
  report path (C-15).
- **Acceptance criteria:**
  ```text
  Given a Listing with a published phone number
  When a Guest activates the call action on a mobile device
  Then a call to that number is initiated.

  Given a Listing without a website
  When the profile renders
  Then no website action is displayed.

  Given any contact action
  When it is performed
  Then an analytics event is recorded that does not identify the User.
  ```
- **Dependencies:** C-08, C-38, C-40.
- **Out of scope:** Call tracking numbers, lead capture, in-product messaging
  (`scope-v1.md` §2).
- **Decisions:** D-01, D-51, D-55.

---

#### C-12 — Trust indicators

- **Purpose:** Show Users why the information can be believed — the central
  differentiator of a company-managed directory.
- **Actor:** Guest, Customer.
- **Preconditions:** Verification and provenance data exist on the Listing.
- **Main flow:**
  1. Result cards and profiles display verified state where applicable.
  2. Profiles display the date information was last verified.
  3. Profiles link to a public explanation of how Bulbula collects and
     verifies information (C-18).
- **Alternative flows:** Stale Listings — the display treatment for a Listing
  past its verification interval is **Open (D-08)**.
- **Error and empty states:** Never-verified Listing → the product **MUST
  NOT** display any verification claim.
- **Acceptance criteria:**
  ```text
  Given a Listing with current Verification
  When a User views it
  Then a verified indicator and the verification date are displayed.

  Given a Listing that has never been verified
  When a User views it
  Then no verification claim of any kind is displayed.

  Given any Listing
  When a User opens the trust explanation link
  Then a public page describes how Bulbula collects, verifies and corrects
       information.
  ```
- **Dependencies:** C-08, C-18, C-21.
- **Out of scope:** Verification tiers, badges-for-payment of any kind
  (prohibited: verification **MUST NOT** be purchasable — D-10 integrity),
  and the verification interval — **Open (D-08)**.
- **Decisions:** D-01, D-08, D-10, D-50.

---

#### C-13 — Reviews (read and write)

- **Purpose:** Let Customers share experience and let Users weigh it.
- **Actor:** Guest (read), Customer (write), Operator/Administrator
  (moderate).
- **Preconditions:** The Listing is published; the author is authenticated.
- **Main flow:**
  1. Any User reads Reviews and the rating summary on a profile.
  2. A Customer submits a Rating and optional review text.
  3. The Review enters moderation as defined in
     [`review-policy.md`](review-policy.md).
  4. On publication it appears on the profile and contributes to the rating
     summary.
- **Alternative flows:** Guest attempts to write → contextual sign-in (CUST-1).
  Customer edits or deletes their own Review per the approved policy.
  Any Customer may report a Review (C-15, C-26).
- **Error and empty states:** No Reviews → the profile **MUST** present an
  honest empty state and **MUST NOT** display a fabricated or default rating.
  Rejected Review → the author is notified with a reason (C-39).
- **Acceptance criteria:**
  ```text
  Given a Guest
  When the Guest views a Business profile
  Then published Reviews and the rating summary are readable without an account.

  Given an authenticated Customer
  When the Customer submits a Review
  Then the Review is recorded, attributed to that Customer, and enters
       moderation according to the published policy.

  Given a Business with no published Reviews
  When its profile renders
  Then no rating value is displayed.

  Given any Review state change
  When it occurs
  Then it is recorded with actor, timestamp and reason.
  ```
- **Dependencies:** C-08, C-25, C-30, C-31, C-39; `review-policy.md`.
- **Out of scope:** Business-owner replies — **not V1** (D-12, D-54). Review
  photos — **Deferred (D-36)**. Helpful voting — **Deferred (D-37)**.
  One-per-Business vs one-per-Branch, edit window, deletion semantics and
  rating-only Reviews — **Open — implementation/product detail (D-34)**.
- **Decisions:** D-01, D-12, D-34, D-36, D-37, D-48, D-54.

---

#### C-14 — Save

- **Purpose:** Let a Customer keep a Business for later. **One capability,
  one name** — "like", "favorite" and "bookmark" are deprecated synonyms
  (`glossary.md` §3).
- **Actor:** Customer.
- **Preconditions:** Authenticated Customer; published Listing.
- **Main flow:**
  1. Customer activates Save on a result card or profile.
  2. The Business is added to the Customer's Saved list.
  3. The control reflects the saved state and is immediately reversible.
- **Alternative flows:** Guest attempts to Save → contextual sign-in, after
  which the Save completes without losing context (CUST-1).
- **Error and empty states:** Save fails → the control returns to its prior
  state with an explicit message; the product **MUST NOT** show a false
  success. Empty Saved list → an empty state pointing back into discovery.
- **Acceptance criteria:**
  ```text
  Given an authenticated Customer
  When the Customer Saves a Business
  Then it appears in the Customer's Saved list
  And the action is reversible from the same control.

  Given a Guest
  When the Guest activates Save
  Then sign-in is offered in context
  And after sign-in the Save is applied to the intended Business.
  ```
- **Dependencies:** C-08, C-30, C-31, C-34.
- **Out of scope:** Public lists, sharing of Saved lists, following a
  Business, collections.
- **Decisions:** D-01, D-48.

---

#### C-15 — Report a problem / suggest a correction

- **Purpose:** Provide the public correction channel. Because Businesses
  cannot edit their own data (D-02), this is the primary way reality reaches
  Bulbula from outside.
- **Actor:** Guest, Customer (report); Operator (process).
- **Preconditions:** A published Listing, Review or profile element exists.
- **Main flow:**
  1. User activates "report a problem" from a profile.
  2. User selects a problem type (for example wrong number, wrong hours,
     permanently closed, wrong location, inappropriate content) and may add
     a free-text description.
  3. The report is queued for Operators (C-26).
  4. The Operator triages, investigates, applies a Correction if warranted
     (C-20) and closes the report.
- **Alternative flows:** Reporting a Review follows the same mechanism but is
  routed to Review moderation (C-25) and **requires authentication**
  (`interaction-permissions.md`).
- **Error and empty states:** Submission failure → the User's input **MUST**
  be preserved and retry offered. Abuse or flooding → rate limiting applies
  without revealing thresholds.
- **Acceptance criteria:**
  ```text
  Given a Guest viewing a Business profile
  When the Guest submits a problem report
  Then the report is recorded with the Listing reference and problem type
  And no account is required.

  Given a submitted report
  When an Operator resolves it
  Then the resolution, the actor and the timestamp are recorded.

  Given repeated submissions from one source within a short period
  When the limit is exceeded
  Then further submissions are refused without disclosing the threshold.
  ```
- **Dependencies:** C-20, C-25, C-26, C-29.
- **Out of scope:** Whether Guests may submit *structured* field-level edit
  suggestions, as opposed to free-text reports — **Open (D-35)**.
- **Decisions:** D-01, D-02, D-35, D-50.

---

#### C-16 — Sponsored placements

- **Purpose:** Present paid placement in a way that is useful, clearly
  labelled, and incapable of distorting organic results.
- **Actor:** Guest, Customer (viewers); Operator/Administrator (management
  via C-27).
- **Preconditions:** An active Campaign exists for the Placement and period;
  the sponsored Business's Listing is published and eligible.
- **Main flow:**
  1. A surface containing a Placement is rendered.
  2. Eligible active Campaigns for that Placement are selected.
  3. The sponsored Business is displayed in the Placement with a Sponsored
     label and a consistent visual treatment.
  4. An impression is recorded; a click records a click event (C-38).
- **Alternative flows:** More sold Campaigns than slots → selection follows
  documented placement rules (`advertising-products.md`). No active Campaign
  → the slot collapses.
- **Error and empty states:** Sponsored Business becomes ineligible (listing
  unpublished, under Correction, verification lapsed) → it **MUST NOT** be
  served, and the condition is raised to operations.
- **Acceptance criteria:**
  ```text
  Given an active Campaign for a Placement
  When a User views the surface containing it
  Then the sponsored Business is displayed with a Sponsored label
  And the label is present on every surface and every client surface.

  Given the same search executed with and without an active Campaign
  When organic results are compared
  Then the organic ordering is identical.

  Given a sponsored Business that becomes unpublished
  When the Placement is rendered
  Then that Business is not served in the Placement.

  Given a Sponsored placement is displayed
  When an impression occurs
  Then the impression is recorded for reporting only and never affects price.
  ```
- **Dependencies:** C-02, C-01, C-04, C-27, C-38; §19.
- **Out of scope:** Auction, bidding, CPC/CPM/CPA, programmatic exchanges,
  self-service purchase (D-10, `scope-v1.md` §2). Exact inventory counts,
  positions and label wording are specified in
  [`advertising-products.md`](../15-business/advertising-products.md).
- **Decisions:** D-01, D-10, D-39.

---

#### C-17 — Sharing

- **Purpose:** Let Users pass a Business to someone else — the main organic
  growth loop in a Telegram-centric market (R-06).
- **Actor:** Guest, Customer.
- **Preconditions:** A published Listing with a canonical URL.
- **Main flow:**
  1. User activates share on a profile.
  2. The product offers the surface-appropriate share mechanism.
  3. The shared link resolves to the same Business profile for the
     recipient, on whichever surface they open it.
- **Alternative flows:** On the Telegram surface, sharing uses Telegram's
  mechanism and deep links (§22).
- **Error and empty states:** Share mechanism unavailable → a copyable link
  **MUST** remain available.
- **Acceptance criteria:**
  ```text
  Given a User viewing a Business profile
  When the User shares it
  Then a link is produced that resolves to the same Business profile.

  Given a shared link opened by a recipient without an account
  When the profile loads
  Then the full public profile is visible without authentication.

  Given a shared link
  When it is previewed in a messaging application
  Then title, description and image metadata describe that Business (C-37).
  ```
- **Dependencies:** C-08, C-37; §22.
- **Out of scope:** Referral tracking, share-based rewards, attribution of
  installs or sign-ups.
- **Decisions:** D-01, D-49.

---

#### C-18 — Static information and policy pages

- **Purpose:** Explain what Bulbula is, how it works, and what Users' rights
  are — required for both trust and compliance.
- **Actor:** Guest, Customer.
- **Preconditions:** None.
- **Main flow:**
  1. User reaches a static page from navigation, the footer, a profile link
     or an external link.
  2. The page presents current, versioned content.
- **Required pages (V1):**
  | Page | Purpose |
  | --- | --- |
  | About Bulbula | What the product is and which area it covers |
  | How listings are collected and verified | Explains D-50 and D-08 to the public; linked from trust indicators |
  | How ranking works | States plainly that sponsorship never changes organic ordering (D-10) |
  | Review policy (public summary) | Derived from `review-policy.md` |
  | Advertising information | What sponsorship is and how it is labelled |
  | Privacy notice | **PENDING COUNSEL** (D-46, L-6) |
  | Terms of use | **PENDING COUNSEL** (D-46) |
  | Contact and corrections | How to reach Bulbula, including removal and correction requests |
- **Alternative flows:** None.
- **Error and empty states:** A required page **MUST NOT** ship empty or with
  placeholder text; if content is pending, the page **MUST NOT** be linked as
  if complete.
- **Acceptance criteria:**
  ```text
  Given any public page
  When it renders
  Then links to the privacy notice and contact/corrections page are reachable.

  Given the "how ranking works" page
  When a User reads it
  Then it states that Sponsored placement does not alter organic ranking.
  ```
- **Dependencies:** C-12, C-37, §25.
- **Out of scope:** Legal drafting; final policy wording — **PENDING
  COUNSEL**.
- **Decisions:** D-01, D-10, D-46, D-50.

### 12.2 Bulbula internal operations

**The operations console is a primary V1 product surface.** In a
company-managed directory, the tooling that produces Listings *is* the
production line for the product. It is specified to the same standard as
public capabilities, and its absence blocks launch as surely as a missing
search page. It is Web-only (`scope-v1.md` §1.5) and staff-only
(`interaction-permissions.md`).

Full lifecycle narrative: [`listing-operations.md`](listing-operations.md).

---

#### C-19 — Listing creation

- **Purpose:** Turn a permitted, collected business into a published Listing.
- **Actor:** Operator.
- **Preconditions:** Staff authenticated; **Permission recorded** for the
  Business (D-50); collected information available.
- **Main flow:**
  1. Operator searches existing Listings to rule out a duplicate.
  2. Operator creates the Business record: name, description, Categories,
     contact points, links.
  3. Operator creates at least one Branch: address, Area, Sub-city,
     coordinates, landmark, hours, Branch contact points.
  4. Operator attaches media (C-22) and records provenance: source, date,
     collecting Operator.
  5. Operator links the Permission record (D-43).
  6. Operator submits for quality review (C-21).
- **Alternative flows:** Partial information → the Listing is saved as a
  draft and **MUST NOT** be publishable until required fields and Permission
  are present. Potential duplicate detected → the Operator merges or
  continues with a recorded justification.
- **Error and empty states:** Attempted publication without Permission or
  without required fields **MUST** be refused with a specific reason.
- **Acceptance criteria:**
  ```text
  Given a draft Listing with no linked Permission record
  When an Operator attempts to submit it for publication
  Then the submission is refused
  And the reason identifies the missing Permission.

  Given a new Business whose name and Area closely match an existing Listing
  When the Operator creates it
  Then the possible duplicate is surfaced before submission.

  Given a Listing created by an Operator
  When it is saved
  Then the creating Operator, timestamp and collection source are recorded.
  ```
- **Dependencies:** C-20, C-21, C-22, C-23, C-24, C-29.
- **Out of scope:** Owner-created Listings and claims (D-02). Permission
  record contents and retention — **Open (D-43)**. Services/products/pricing
  structure — **Open (D-44)**.
- **Decisions:** D-02, D-03, D-43, D-44, D-50.

---

#### C-20 — Listing editing

- **Purpose:** Keep published information accurate over time, including
  Corrections arising from reports.
- **Actor:** Operator; Administrator for restricted fields.
- **Preconditions:** The Listing exists; the Staff member holds the required
  permission.
- **Main flow:**
  1. Operator opens a Listing from a queue, a search or a report.
  2. Operator edits Business or Branch attributes.
  3. Operator records the reason and the source of the change.
  4. Changes are saved; whether a re-review is required before republication
     depends on the field class (C-21).
  5. The change is written to the Audit log (C-29).
- **Alternative flows:** Permanent closure → the Listing is marked closed
  rather than deleted, preserving the URL and history. Business renamed or
  relocated → handled as an edit with provenance, not as a new Listing.
- **Error and empty states:** Concurrent edits **MUST NOT** silently
  overwrite one another. An edit that would leave a published Listing below
  the required field set **MUST** be refused or force unpublication.
- **Acceptance criteria:**
  ```text
  Given a published Listing
  When an Operator changes a published field
  Then the previous value, new value, actor, reason and timestamp are recorded.

  Given two Operators editing the same Listing simultaneously
  When the second saves
  Then the conflict is detected and no change is silently lost.

  Given a Business reported as permanently closed and confirmed
  When the Operator records the closure
  Then the profile shows the Business as closed
  And the Listing is not deleted.
  ```
- **Dependencies:** C-19, C-21, C-26, C-29.
- **Out of scope:** Which fields require Administrator rights — **Open
  (D-14)**. Versioned public history is not a V1 user-facing feature.
- **Decisions:** D-02, D-14, D-50.

---

#### C-21 — Verification and quality control

- **Purpose:** Guarantee that nothing reaches the public without a second
  pair of eyes, and that published data does not quietly rot.
- **Actor:** Operator (submits), a second Operator or Administrator
  (reviews).
- **Preconditions:** A Listing has been submitted for review.
- **Main flow:**
  1. The reviewer opens the submission with provenance and Permission
     visible.
  2. The reviewer checks required fields, plausibility, duplicates, media
     suitability and category correctness.
  3. The reviewer approves (publishing the Listing) or returns it with
     specific reasons.
  4. On approval, Verification is recorded with method, date and reviewer.
  5. After the verification interval, the Listing re-enters the
     re-verification queue.
- **Alternative flows:** Re-verification after a Correction; spot checks
  initiated by an Administrator.
- **Error and empty states:** The same person who created a Listing **MUST
  NOT** be able to approve it when another reviewer is available; when
  Bulbula operates with a single Staff member this constraint is recorded as
  an accepted operational risk rather than removed from the product.
- **Acceptance criteria:**
  ```text
  Given a Listing submitted for review
  When it is approved
  Then Verification method, date and reviewer identity are recorded
  And the Listing becomes publicly visible.

  Given a Listing returned for rework
  When the creating Operator opens it
  Then the specific reasons are visible
  And the Listing remains unpublished.

  Given a published Listing whose verification age exceeds the interval
  When operational queues are generated
  Then that Listing appears in the re-verification queue.
  ```
- **Dependencies:** C-19, C-20, C-28, C-29.
- **Out of scope:** Verification methods, the interval, stale-state display
  and whether tiers exist — **Open (D-08)**.
- **Decisions:** D-08, D-50.

---

#### C-22 — Media management

- **Purpose:** Attach and manage the photographs that make a Listing
  credible.
- **Actor:** Operator.
- **Preconditions:** Permission covers the media (D-50).
- **Main flow:**
  1. Operator uploads images for a Business or Branch.
  2. Operator sets order and primary image, and records the source.
  3. Media is processed for delivery at appropriate sizes.
  4. Media appears on the profile after quality review.
- **Alternative flows:** Replacing outdated photographs during
  re-verification; removing media on request from the Business.
- **Error and empty states:** Upload failure **MUST** be explicit and
  retryable. A Listing without media **MUST** render correctly — no broken
  frames, no placeholder implying a missing photo.
- **Acceptance criteria:**
  ```text
  Given an Operator uploading an image
  When the upload completes
  Then the source and uploading Operator are recorded.

  Given a Listing with no media
  When its profile renders
  Then the layout is complete and no broken or placeholder image appears.

  Given a request from a Business to remove a photograph
  When an Operator removes it
  Then it is no longer served publicly
  And the removal is recorded with a reason.
  ```
- **Dependencies:** C-19, C-21, C-29; performance §24.
- **Out of scope:** Storage location, formats, size limits, CDN and
  processing pipeline — **Open (D-25)**; constrained by the data-location
  policy in §21.
- **Decisions:** D-25, D-50, D-51.

---

#### C-23 — Category management

- **Purpose:** Keep the taxonomy coherent, which is what makes browsing and
  search work at all.
- **Actor:** Administrator.
- **Preconditions:** Staff authenticated with administrative rights.
- **Main flow:**
  1. Administrator creates or edits a Category or Subcategory: name,
     Amharic label, Aliases, parent, display order, visibility.
  2. Changes propagate to browsing, search, autocomplete and SEO surfaces.
- **Alternative flows:** Merging two Categories reassigns Listings and
  **MUST** preserve or redirect the retired URL (§23). Hiding a Category
  removes it from navigation without deleting its Listings' classification.
- **Error and empty states:** Deleting a Category that still classifies
  Listings **MUST** be refused; reassignment is required first.
- **Acceptance criteria:**
  ```text
  Given a Category that classifies at least one Listing
  When an Administrator attempts to delete it
  Then the deletion is refused
  And the number of affected Listings is reported.

  Given a merged Category
  When a User requests the retired Category URL
  Then the request is redirected to the surviving Category.

  Given an Alias added to a Subcategory
  When a User searches using that Alias
  Then the Subcategory and its Listings are matched.
  ```
- **Dependencies:** C-02, C-03, C-04, C-06, C-29, C-37.
- **Out of scope:** The initial catalogue — **Open (D-56)**; categories per
  Listing — **Open (D-57)**. Taxonomy depth beyond two levels is out of V1
  (D-06).
- **Decisions:** D-06, D-18, D-56, D-57.

---

#### C-24 — Location management

- **Purpose:** Govern the Area hierarchy that location browsing, filtering
  and SEO depend on.
- **Actor:** Administrator.
- **Preconditions:** Staff authenticated with administrative rights.
- **Main flow:**
  1. Administrator creates or edits an Area: name, Amharic label, Aliases,
     Sub-city, City, display order, visibility.
  2. Branches are assigned to Areas during Listing creation and editing.
- **Alternative flows:** Adding a new Area to extend coverage — a data
  operation only (GEO-4).
- **Error and empty states:** Deleting an Area with assigned Branches **MUST**
  be refused.
- **Acceptance criteria:**
  ```text
  Given an Administrator adds a new Area
  When Listings are assigned to it
  Then area browsing, filtering and category × area pages work for it
       with no code change.

  Given an Area with assigned Branches
  When an Administrator attempts to delete it
  Then the deletion is refused.
  ```
- **Dependencies:** C-05, C-06, C-19, C-29, C-37; GEO-1…GEO-6.
- **Out of scope:** The launch-area boundary definition — **Open (D-40,
  pending local confirmation)**.
- **Decisions:** D-18, D-40.

---

#### C-25 — Review moderation

- **Purpose:** Enforce the published review policy consistently and
  accountably.
- **Actor:** Operator; Administrator for policy and appeals.
- **Preconditions:** A Review exists in a state requiring attention.
- **Main flow:**
  1. The Review appears in the moderation queue, by policy or by report.
  2. The moderator reads it with context: Business, author history, reports.
  3. The moderator approves, rejects or removes it, recording the policy
     basis.
  4. The author is notified of rejection or removal with the reason (C-39).
- **Alternative flows:** Bulk handling of a coordinated attack; escalation to
  an Administrator.
- **Error and empty states:** Empty queue → explicit empty state. A Review
  whose author has deleted their account is handled per the retention rule
  in §21.
- **Acceptance criteria:**
  ```text
  Given a reported Review
  When a moderator resolves it
  Then the decision, policy basis, actor and timestamp are recorded.

  Given a Review that is removed
  When the Business profile renders
  Then the Review is not displayed
  And it no longer contributes to the rating summary.

  Given a moderation decision
  When the author is notified
  Then the notification states the reason without exposing reporter identity.
  ```
- **Dependencies:** C-13, C-26, C-29, C-39; `review-policy.md`.
- **Out of scope:** Pre- vs post-publication moderation and the detailed
  rules — see `review-policy.md`; unresolved sub-details are marked **Open
  (D-34)** there.
- **Decisions:** D-12, D-34.

---

#### C-26 — Report management

- **Purpose:** Convert public signals into corrected data, with a traceable
  outcome for every report.
- **Actor:** Operator; Administrator for escalations.
- **Preconditions:** A report exists (C-15).
- **Main flow:**
  1. Reports arrive in a queue, categorised by type.
  2. The Operator triages: verify against the Listing, contact the Business
     if needed, decide.
  3. A warranted change is applied as a Correction (C-20); an unwarranted
     report is closed with a reason.
  4. The report outcome and timing are recorded (C-28).
- **Alternative flows:** Reports about Reviews are routed to C-25. Legal or
  removal demands are escalated to an Administrator and **MUST** follow the
  process confirmed by counsel — **PENDING COUNSEL** (L-15).
- **Error and empty states:** Duplicate reports about the same issue **SHOULD**
  be grouped. Unresolvable reports (for example the Business is unreachable)
  **MUST** be closed with an explicit "could not verify" outcome rather than
  left open indefinitely.
- **Acceptance criteria:**
  ```text
  Given a submitted report
  When it is resolved
  Then the outcome, actor, reason and resolution time are recorded.

  Given a report that results in a data change
  When the Correction is applied
  Then the report is linked to the resulting Listing change.

  Given multiple reports describing the same problem on one Listing
  When an Operator opens the queue
  Then the reports are presented together.
  ```
- **Dependencies:** C-15, C-20, C-25, C-28, C-29.
- **Out of scope:** Target resolution times — **PENDING PILOT** (D-30, D-31).
- **Decisions:** D-02, D-35, D-46.

---

#### C-27 — Advertising and campaign management

- **Purpose:** Let Staff sell, configure, approve and run fixed-package
  sponsorship entirely internally (D-10).
- **Actor:** Operator (creates); Administrator (approves and activates).
- **Preconditions:** The Business has a published, eligible Listing and has
  agreed offline to a Package.
- **Main flow:**
  1. Operator creates a Campaign: Business, Package, Placement, target
     (Category or Area where applicable), start and end dates.
  2. The system validates eligibility and inventory availability for the
     period.
  3. Administrator reviews and activates.
  4. The Campaign runs automatically between its start and end dates.
  5. Staff view delivery metrics (C-28) and produce a report for the
     Business.
  6. The Campaign ends automatically at period end.
- **Alternative flows:** Early termination by an Administrator with a
  recorded reason; renewal creates a new Campaign rather than extending
  history silently.
- **Error and empty states:** Overbooked inventory **MUST** be refused at
  creation. A Campaign referencing an unpublished Listing **MUST NOT**
  activate.
- **Acceptance criteria:**
  ```text
  Given an Operator creating a Campaign for a fully sold Placement and period
  When the Operator submits it
  Then creation is refused with the conflicting period identified.

  Given a Campaign created by an Operator
  When no Administrator has approved it
  Then it does not serve.

  Given a Campaign whose end date has passed
  When placements are served
  Then that Campaign no longer appears
  And no manual action was required to stop it.

  Given any Campaign state change
  When it occurs
  Then actor, timestamp and reason are recorded in the Audit log.
  ```
- **Dependencies:** C-16, C-28, C-29; `advertising-products.md`.
- **Out of scope:** Self-service purchase, auction, bidding, CPC/CPM/CPA,
  payment processing (D-10). Billing record structure — **Open (D-11)**.
  Integrity controls against editorial influence — **Open (D-39)**.
- **Decisions:** D-10, D-11, D-39, D-54.

---

#### C-28 — Operational analytics

- **Purpose:** Give Staff the numbers needed to run the operation and to
  conduct the pilot.
- **Actor:** Operator (own work), Administrator (all).
- **Preconditions:** Events and operational records exist.
- **Main flow:**
  1. Staff open the operations analytics view.
  2. The view presents coverage (published Listings by Category and Area),
     data quality (completeness, verification age, stale count), workflow
     throughput (created, published, returned, reports resolved), content
     signals (zero-result searches, most-searched terms) and campaign
     delivery.
  3. Staff act on queues derived from these numbers.
- **Alternative flows:** Export for pilot measurement
  (`listing-operations.md` §6).
- **Error and empty states:** Insufficient data **MUST** be shown as
  "insufficient data", never as zero or as an extrapolation.
- **Acceptance criteria:**
  ```text
  Given published Listings exist
  When an Administrator opens operational analytics
  Then coverage by Category and Area and verification age distribution
       are presented.

  Given searches that returned no results
  When Staff review content signals
  Then those queries are listed so that coverage gaps can be addressed.

  Given a metric with too little underlying data
  When it is displayed
  Then it is labelled as insufficient data rather than shown as zero.
  ```
- **Dependencies:** C-21, C-26, C-27, C-38.
- **Out of scope:** Granularity, retention and raw-event storage — **Open
  (D-27)**. Business-facing dashboards (requires business accounts — D-54).
- **Decisions:** D-27, D-30, D-31.

---

#### C-29 — Audit logs

- **Purpose:** Make every consequential internal action attributable. This is
  both an operational control and the evidence base for accountability
  obligations under the data-protection regime (R-26).
- **Actor:** Administrator (reads); the system (writes).
- **Preconditions:** Staff authentication is in place.
- **Main flow:**
  1. A Staff action changes published data, a Review state, a Campaign, the
     taxonomy, a location, a role, or exposes personal data.
  2. The system records actor, action, target, before/after where
     applicable, timestamp and reason.
  3. An Administrator can review the log filtered by actor, target or
     period.
- **Alternative flows:** Export for an investigation or a regulatory
  request.
- **Error and empty states:** If the audit record cannot be written, the
  action **MUST** fail rather than proceed unlogged.
- **Acceptance criteria:**
  ```text
  Given any Staff action that changes published data
  When the action completes
  Then an audit entry exists identifying actor, target, change and time.

  Given an attempt to modify or delete an audit entry
  When it is made
  Then it is refused.

  Given an Administrator reviewing the audit log
  When filtering by an Operator and a date range
  Then all matching entries are returned.
  ```
- **Dependencies:** C-19…C-28, C-36.
- **Out of scope:** Retention period — **Open (D-27)**, with a floor set by
  legal requirements — **PENDING COUNSEL** (L-21, D-46b). Storage design (TRD).
- **Decisions:** D-14, D-27, D-46.

### 12.3 Customer accounts

Authentication in V1 is **Google sign-in and email OTP only. No passwords.
No Apple Sign In** (D-48). The rationale and the full actor matrix are in
[`interaction-permissions.md`](interaction-permissions.md).

---

#### C-30 — Google authentication

- **Purpose:** Offer the lowest-friction sign-in for the dominant device
  population (R-06).
- **Actor:** Guest becoming Customer.
- **Preconditions:** The Guest attempts a capability requiring an account, or
  chooses to sign in.
- **Main flow:**
  1. Guest chooses Google sign-in.
  2. The Guest authenticates with Google and consents to share the minimum
     profile scope.
  3. Bulbula creates or matches a Customer identity (C-32).
  4. The Customer is returned to the originating task.
- **Alternative flows:** Returning Customer signs in again. On the Telegram
  surface the flow **MUST** account for in-app browser constraints — the
  known failure mode of OAuth inside embedded webviews (R-23); the resolution
  is a Mini App design question (§22, D-33).
- **Error and empty states:** Cancelled or failed authentication returns the
  User to the originating task unchanged, with no partial account created.
- **Acceptance criteria:**
  ```text
  Given a Guest who begins Google sign-in from a Business profile
  When authentication succeeds
  Then the Guest becomes an authenticated Customer
  And is returned to that Business profile.

  Given a Guest who cancels Google sign-in
  When the flow ends
  Then no Customer account exists
  And the Guest retains their previous context.

  Given Google sign-in on any surface
  When the account is created
  Then only the minimum profile information required is stored (D-51).
  ```
- **Dependencies:** C-32, C-33, C-40.
- **Out of scope:** Apple Sign In (D-48; revisit at D-47), passwords (D-48),
  Facebook, phone/SMS, and Telegram-account login (**not in the approved
  provider list**; see D-33).
- **Decisions:** D-48, D-51.

---

#### C-31 — Email OTP authentication

- **Purpose:** Give every User a sign-in route that does not require a Google
  account, without introducing passwords.
- **Actor:** Guest becoming Customer.
- **Preconditions:** The Guest can receive email.
- **Main flow:**
  1. Guest enters an email address.
  2. Bulbula sends a single-use numeric code to that address.
  3. Guest enters the code within its validity window.
  4. Bulbula creates or matches a Customer identity (C-32) and returns the
     Customer to the originating task.
- **Alternative flows:** Resend after a cooldown; sign-in on a second device.
- **Error and empty states:** Wrong code → a generic failure that does not
  reveal whether the address exists. Expired code → offer a new one. Rate
  limit reached → refuse without disclosing thresholds.
- **Required controls (V1):**
  | Control | Requirement |
  | --- | --- |
  | Code generation | Cryptographically secure random; the stored form **MUST NOT** be the plaintext code |
  | Validity | Short-lived, single-use, invalidated on use or on issuing a replacement |
  | Attempt limit | A small number of failed attempts invalidates the code |
  | Request limit | Per-address and per-source-network limits, plus a resend cooldown |
  | Session binding | The code is bound to the requesting session |
  | Enumeration | Responses **MUST NOT** reveal whether an address has an account |
  | Monitoring | Anomalous request patterns are logged for review |
  | Exact values | **Open — implementation/product detail (TRD/security)**; informed by R-25 |
- **Acceptance criteria:**
  ```text
  Given a Guest who requests a sign-in code
  When the code is delivered and entered within its validity window
  Then the Guest becomes an authenticated Customer.

  Given an expired or already-used code
  When it is submitted
  Then authentication fails
  And a new code must be requested.

  Given repeated code requests for one address beyond the limit
  When another request is made
  Then it is refused without revealing the limit or whether the address exists.

  Given a failed sign-in attempt
  When the response is returned
  Then it does not disclose whether the email address is registered.
  ```
- **Dependencies:** C-32, C-39, C-40.
- **Out of scope:** Email provider selection — **Open (D-41)**. Using email
  OTP as a **second factor** — explicitly rejected: it is a primary sign-in
  mechanism for Customers only, not a second factor (R-25). **Staff accounts
  MUST NOT rely on email OTP alone** — staff authentication strength is
  **Open (D-45)**, resolved in the TRD/security design.
- **Decisions:** D-24, D-41, D-45, D-48.

---

#### C-32 — Unified Bulbula identity

- **Purpose:** One person, one Customer identity, regardless of how they
  signed in or which surface they used.
- **Actor:** Customer.
- **Preconditions:** A successful authentication has occurred.
- **Main flow:**
  1. Authentication completes through any supported method.
  2. Bulbula resolves it to a single Customer identity.
  3. Saves, Reviews and preferences are the same across Web and Telegram.
- **Alternative flows:** A Customer who previously used Google signs in with
  email OTP at the same verified address — the linking rule is **Open
  (D-13)**. Identity on the Telegram surface, where a Telegram user is not an
  approved login provider, is **Open (D-33)**.
- **Error and empty states:** Ambiguous identity **MUST NOT** result in a
  silent merge of two accounts; merging is a deliberate, audited operation.
- **Acceptance criteria:**
  ```text
  Given a Customer who signed in on the Web
  When the same Customer signs in on the Telegram Mini App
  Then their Saved list and Reviews are the same.

  Given two authentications that cannot be confidently resolved to one person
  When the second completes
  Then the accounts are not automatically merged.
  ```
- **Dependencies:** C-30, C-31, C-33, C-34, C-35; §22.
- **Out of scope:** Linking rules — **Open (D-13)**. Telegram identity
  handling — **Open (D-33)**. Any account-merge user interface beyond what
  D-13 resolves.
- **Decisions:** D-13, D-33, D-48, D-49.

---

#### C-33 — Customer profile

- **Purpose:** Give a Customer a minimal place to see and control what
  Bulbula holds about them.
- **Actor:** Customer.
- **Preconditions:** Authenticated Customer.
- **Main flow:**
  1. Customer opens their profile.
  2. Display name, email address and sign-in method are shown.
  3. The Customer can edit the display name and reach their Saves (C-34),
     Reviews (C-35) and privacy controls (C-36).
- **Alternative flows:** None.
- **Error and empty states:** A new Customer with no activity sees a profile
  that routes into discovery rather than an empty shell.
- **Acceptance criteria:**
  ```text
  Given an authenticated Customer
  When the profile is opened
  Then the stored display name, email address and sign-in method are shown.

  Given the Customer changes their display name
  When the change is saved
  Then published Reviews show the updated name.
  ```
- **Dependencies:** C-32, C-34, C-35, C-36.
- **Out of scope:** Avatars, public Customer profiles, social features,
  follower relationships, profile pages visible to other Users. Data
  minimization rules in §21 govern what may be collected (D-51).
- **Decisions:** D-48, D-51.

---

#### C-34 — Save management

- **Purpose:** Let a Customer manage the list built by C-14.
- **Actor:** Customer.
- **Preconditions:** Authenticated Customer.
- **Main flow:**
  1. Customer opens the Saved list.
  2. Saved Businesses are listed with the same essential information as
     result cards.
  3. Customer opens one, or removes it from the list.
- **Alternative flows:** A Saved Business is later unpublished — see error
  states.
- **Error and empty states:** Empty list → an empty state that routes into
  discovery. A Saved Business that is unpublished or permanently closed
  **MUST** be shown with its current status rather than silently disappearing
  or linking to a dead page.
- **Acceptance criteria:**
  ```text
  Given a Customer with Saved Businesses
  When the Saved list is opened
  Then each entry shows current essential information and open status.

  Given a Saved Business that is subsequently unpublished
  When the Customer opens their Saved list
  Then the entry indicates that the Business is no longer listed.

  Given a Customer removes an entry
  When the list is reloaded
  Then the entry is absent.
  ```
- **Dependencies:** C-14, C-32, C-33.
- **Out of scope:** Multiple named lists, sharing, collaboration, notes.
- **Decisions:** D-01, D-48.

---

#### C-35 — Customer review management

- **Purpose:** Let a Customer see and control their own contributions.
- **Actor:** Customer.
- **Preconditions:** Authenticated Customer with at least one Review.
- **Main flow:**
  1. Customer opens "my reviews".
  2. Each Review is listed with its Business, Rating, text, date and status
     (published, pending, rejected, removed).
  3. The Customer may edit or delete their own Review within the published
     policy.
- **Alternative flows:** A rejected Review shows the reason and whether it
  can be revised.
- **Error and empty states:** No Reviews → empty state. A Review on a
  Business that has been unpublished **MUST** still be visible to its author
  with an explanation.
- **Acceptance criteria:**
  ```text
  Given a Customer with Reviews in different states
  When "my reviews" is opened
  Then each Review's current status is shown.

  Given a Customer edits their own published Review within the permitted window
  When the edit is saved
  Then the Review re-enters moderation according to the published policy.

  Given a Customer deletes their own Review
  When the Business profile renders
  Then the Review is no longer displayed
  And it no longer contributes to the rating summary.
  ```
- **Dependencies:** C-13, C-25, C-32; `review-policy.md`.
- **Out of scope:** Edit window length, deletion semantics (hard delete
  versus withdrawal) and whether edits require re-moderation — **Open
  (D-34)**, specified in `review-policy.md` as open items.
- **Decisions:** D-12, D-34.

---

#### C-36 — Account deletion and privacy controls

- **Purpose:** Let a Customer exercise control over their own data — a
  product requirement and a legal one (R-26).
- **Actor:** Customer; Administrator (executes exceptional requests).
- **Preconditions:** Authenticated Customer.
- **Main flow:**
  1. Customer opens privacy controls from their profile.
  2. The Customer may request account deletion and may obtain a copy of
     their data.
  3. Deletion is confirmed explicitly, with clear consequences stated.
  4. The account is deleted, and Reviews are handled per the retention rule
     in §21.
  5. The Customer is notified when deletion completes (C-39).
- **Alternative flows:** A request arriving by email or the contact page is
  executed by an Administrator and recorded (C-29).
- **Error and empty states:** A deletion that cannot complete automatically
  **MUST** be escalated to Staff, not silently dropped; the Customer **MUST**
  be told the request is in progress.
- **Acceptance criteria:**
  ```text
  Given an authenticated Customer
  When the Customer requests account deletion and confirms
  Then the account is deleted
  And the Customer is notified when the deletion is complete.

  Given a deleted account
  When the Customer's previous email address is used to sign in
  Then a new account is created with no prior Saves or profile data.

  Given any deletion or data-export request
  When it is executed
  Then the action is recorded in the Audit log.
  ```
- **Dependencies:** C-29, C-33, C-39; §21.
- **Out of scope:** The legally required response windows, the exact rights
  set, and whether exports must follow a prescribed format — **PENDING
  COUNSEL** (D-46, L-7). What happens to published Reviews after
  deletion is **Open — product/legal (D-34, L-21)**.
- **Decisions:** D-34, D-46, D-51.

### 12.4 Cross-cutting

---

#### C-37 — SEO

- **Purpose:** Make Bulbula findable by people who search the open web — the
  primary non-paid acquisition channel for a directory. Specified in §23.
- **Actor:** Guest (benefits); search engines (consume).
- **Preconditions:** Published Listings and a public Web surface.
- **Main flow:** Every public page is crawlable, has a canonical URL, unique
  metadata, structured data and sharing metadata, and is listed in the
  sitemap.
- **Alternative flows:** Telegram Mini App content is **not** an SEO surface
  (`scope-v1.md` §1.5).
- **Error and empty states:** Thin or empty pages **MUST NOT** be indexable.
  Unpublished Listings **MUST** return not-found and be removed from the
  sitemap.
- **Acceptance criteria:**
  ```text
  Given any published Business profile
  When a crawler requests it
  Then it is reachable without JavaScript execution, declares a canonical
       URL and includes structured data describing the Business.

  Given a Listing is unpublished
  When the sitemap is regenerated
  Then its URL is absent
  And requests for it return not-found.
  ```
- **Dependencies:** C-04, C-05, C-06, C-08, C-17, C-23, C-24; §23.
- **Out of scope:** Rendering strategy and framework choices (TRD);
  Amharic-language pages (D-18).
- **Decisions:** D-18, D-52.

---

#### C-38 — Analytics

- **Purpose:** Measure what the product does, for operations and for launch
  evaluation. Specified in §26.
- **Actor:** The system (records); Staff (consume via C-28).
- **Preconditions:** None.
- **Main flow:** Defined events are recorded as they occur and aggregated
  into metrics for reporting.
- **Alternative flows:** None.
- **Error and empty states:** Analytics failure **MUST NOT** break a User
  journey; events may be lost, requests may not be blocked.
- **Acceptance criteria:**
  ```text
  Given any public interaction defined as an event in §26
  When it occurs
  Then the event is recorded without storing information that identifies
       an individual Guest.

  Given the analytics pipeline is unavailable
  When a User performs a contact action
  Then the action still completes.

  Given raw events exist
  When Staff view a metric
  Then the metric is derived from aggregated data, not computed live from
       raw events.
  ```
- **Dependencies:** C-11, C-16, C-28; §26.
- **Out of scope:** Granularity, retention, raw-event storage and tooling —
  **Open (D-27)**. Third-party analytics vendors — not selected (D-42).
- **Decisions:** D-27, D-51.

---

#### C-39 — Notifications

- **Purpose:** Tell Customers and Staff what they need to know, through the
  only channel approved for V1. Specified in §25.
- **Actor:** Customer, Staff.
- **Preconditions:** A verified email address exists for the recipient.
- **Main flow:** A defined event occurs → the corresponding notification is
  sent by email.
- **Alternative flows:** Surface-native notification mechanisms are **not**
  part of V1 beyond what the Telegram runtime provides natively for the Mini
  App itself.
- **Error and empty states:** Delivery failure **MUST** be recorded and
  **MUST NOT** block the originating action. Repeated failures to one
  address are flagged.
- **Acceptance criteria:**
  ```text
  Given a Customer requests a sign-in code
  When the request is accepted
  Then the code is sent to the stated email address.

  Given a Review is rejected or removed
  When moderation completes
  Then the author is notified with the reason.

  Given any notification defined in §25
  When it is sent
  Then it identifies Bulbula, states why the recipient received it,
       and contains no unrelated marketing content.
  ```
- **Dependencies:** C-25, C-31, C-36; §25.
- **Out of scope:** Push notifications (require the Flutter client — D-15),
  SMS, Telegram bot messaging, marketing campaigns. Provider selection —
  **Open (D-41)**.
- **Decisions:** D-15, D-24, D-41.

---

#### C-40 — Privacy and data handling

- **Purpose:** Ensure the product collects the minimum, classifies it
  correctly, and can honour rights requests. Specified in §21.
- **Actor:** All.
- **Preconditions:** None.
- **Main flow:** Every capability that touches personal information applies
  the classification and minimization rules in §21.
- **Alternative flows:** Rights requests are executed through C-36 or by an
  Administrator.
- **Error and empty states:** If a required legal determination is not
  available, the product **MUST** mark it **PENDING COUNSEL** rather than
  assume a position.
- **Acceptance criteria:**
  ```text
  Given any new field added to any capability
  When it is specified
  Then it carries a classification under §21.2 and a stated purpose.

  Given a contact point that belongs to a natural person
  When it is stored
  Then it is flagged as a personal contact point.

  Given a public page
  When it renders
  Then the privacy notice is reachable from it.
  ```
- **Dependencies:** C-29, C-33, C-36; §21.
- **Out of scope:** Any claim of compliance with any law. Hosting and
  data-location implementation — **Open (D-42)**. Legal minima — **PENDING
  COUNSEL (D-46)**.
- **Decisions:** D-42, D-46, D-50, D-51.

---

## 13. V1 exclusions — read this before building anything

> **This section exists so that no future idea in this document, in the
> discovery documents, or in any research note can be mistaken for a V1
> requirement.** Everything below is **OUT OF SCOPE for V1**. If a
> specification, ticket or design implies one of these, it is a defect.

### 13.1 Business-side exclusions

| Excluded | Decision |
| --- | --- |
| Business accounts of any kind | D-54 |
| Business login | D-54 |
| Business-created Listings | D-02 |
| Listing claims by owners | D-02 |
| Owner management of Listing content | D-02 |
| Owner control of their public profile | D-02 |
| Owner replies to Reviews | D-12 |
| Owner-facing analytics or dashboards | D-54 |
| Self-service advertising purchase | D-10 |
| Self-service billing or payment | D-10, D-11 |

**Consequence to internalise:** in V1 the only route by which a Business can
change what the public sees is **through Bulbula staff** — a Correction
(C-20) triggered by a report (C-15) or by direct contact. Everything that
would normally be "owner self-service" is a staff workflow.

### 13.2 Advertising exclusions

| Excluded | Decision |
| --- | --- |
| Auction or bidding of any kind | D-10 |
| CPC pricing | D-10 |
| CPM pricing | D-10 |
| CPA / performance pricing | D-10 |
| Programmatic or third-party ad networks | D-10 |
| Automated targeting or optimisation | D-10 |
| Paid influence on organic ranking | D-10 |
| Purchasable verification or trust markers | D-10, D-50 |

### 13.3 Authentication exclusions

| Excluded | Decision |
| --- | --- |
| Passwords | D-48 |
| Sign in with Apple | D-48 (revisit: D-47) |
| Facebook, X, or other social providers | D-48 |
| Phone number / SMS authentication | D-48 |
| Telegram account as a login provider | Not in the approved provider list; identity handling is **Open (D-33)** |
| Two-factor authentication for Customers | Not required in V1 |

### 13.4 Platform and client exclusions

| Excluded | Decision |
| --- | --- |
| Flutter client (Android or iOS) | D-15 — later client, same backend |
| Native mobile applications of any kind in V1 | D-15 |
| Native push notifications | Requires a native client |
| Telegram bot features beyond the Mini App | D-15 |
| Desktop-first interface design | D-52 |
| Offline mode | Not in V1 |

### 13.5 Product-feature exclusions

| Excluded | Decision |
| --- | --- |
| Online ordering | Out of product scope |
| Reservations and bookings | Out of product scope |
| In-product messaging between Users and Businesses | Future |
| Lead generation or quote requests | Future |
| Loyalty programmes, coupons, deals | Out of product scope |
| Jobs listings | Out of product scope |
| Local news or editorial content | Out of product scope |
| Events | Out of product scope |
| Consumer subscriptions or paid tiers | Out of product scope |
| AI recommendations or AI-generated content | Future |
| Review photographs | **Deferred (D-36)** |
| "Helpful" voting on Reviews | **Deferred (D-37)** |
| Public Customer profiles, following, social graph | Out of product scope |
| Multiple named Save lists | Out of product scope |

### 13.6 Scale and language exclusions

| Excluded | Decision |
| --- | --- |
| National geographic coverage | D-01 — the model expands; V1 data does not |
| Multi-city operations | D-01 |
| Amharic user interface | D-18 — bilingual-*ready*, not bilingual |
| Afaan Oromo or other language interfaces | D-18 |
| Full content translation workflows | D-18 |

### 13.7 Hygiene rules (not features, but binding)

| Rule | Statement |
| --- | --- |
| H-1 | No hard-coded area, city, category, language or currency anywhere in the product |
| H-2 | No feature may assume a Business can log in |
| H-3 | No capability may depend on an excluded capability |
| H-4 | Architecture may leave seams for the future owner model; **no user-visible feature may anticipate it** |
| H-5 | No document may introduce a requirement that contradicts the decision register |
| H-6 | Where an approved decision is silent, the correct output is **"Open — implementation/product detail (D-xx)"**, not an invented rule |

---

## 14. Business and listing model

**This section defines product concepts, not a database schema.** Table
design, keys, column types and normalisation are TRD matters and **MUST NOT**
be inferred from this text.

### 14.1 Canonical structure

```text
Business  (the entity Bulbula describes)
   └── Branch  (a physical location; at least one, always)
             ↑
          Listing = the published representation of a Business
                    together with its Branches
```

| Rule | Statement | Decision |
| --- | --- | --- |
| BM-1 | Every Business **MUST** have at least one Branch | D-03 |
| BM-2 | A single-location business is modelled as **one Business with exactly one Branch** — never as a special case | D-03 |
| BM-3 | A Business **MUST NOT** be modelled as a Branch of itself, and the product **MUST NOT** expose "Business" and "Branch" as alternative entity types to Users | D-03 |
| BM-4 | Attributes are held at the level where they are true. Which attributes are Business-level and which are Branch-level is **Open (D-55)** | D-55 |
| BM-5 | A Listing exists only when a Business has been published. An unpublished Business has records, not a Listing | — |
| BM-6 | Services, products and pricing are captured where the Business provides them; their representation is **Open (D-44)** | D-44 |
| BM-7 | Category assignment is at Business level unless D-57 resolves otherwise | D-06, D-57 |

### 14.2 Provenance

Every Listing **MUST** carry recorded provenance, because the product's
central claim is that its data is trustworthy.

| Field class | Requirement |
| --- | --- |
| Origin | Which Business representative supplied the information, and in what role |
| Method | How it was collected (visit, call, supplied document) |
| Collector | Which Operator collected it |
| Date | When it was collected |
| Permission link | Reference to the Permission record (D-43) |
| Change history | Who changed what, when, and why (C-20, C-29) |

**PROV-1.** No Listing may be published without recorded provenance and a
linked Permission record (D-50).
**PROV-2.** Provenance is **operational information** (§21.2) and is not
published to Users, except for the verification date surfaced by C-12.

### 14.3 Permission, verification, freshness and correction

| Concept | Definition | Product rule |
| --- | --- | --- |
| **Permission** | The Business's agreement that Bulbula may collect and publish its information | Required before publication; evidenced by a Permission record (D-50, D-43) |
| **Verification** | Staff confirmation that the facts are accurate, with method, date and reviewer | Required before publication (C-21); repeated per interval — **Open (D-08)** |
| **Freshness** | Time since the last Verification or confirmation of no change | Drives the re-verification queue; public display treatment of stale Listings is **Open (D-08)** |
| **Correction** | A change applied to published data after publication | Logged with reason and source; may require re-verification (C-20, C-21) |
| **Completeness** | Proportion of the defined field set populated | Used operationally; its definition and any ranking influence are **Open (D-09)** |

**Withdrawal of permission.** If a Business withdraws Permission, Bulbula
**MUST** be able to unpublish the Listing promptly, and the request and
action **MUST** be recorded (C-29). Whether withdrawal is legally mandatory
in every case is **PENDING COUNSEL** (L-5, D-46). The product requirement stands
regardless: the capability to unpublish on request is in V1.

### 14.4 The personal contact point problem

A business phone number is frequently a person's personal mobile number.

| Rule | Statement |
| --- | --- |
| PCP-1 | The product **MUST NOT** assume that information published on a Listing is automatically non-personal |
| PCP-2 | A contact point that is also a natural person's personal contact detail **MUST** be flagged as a **personal contact point** at collection time |
| PCP-3 | Flagged contact points **MUST** be locatable when executing a rights or removal request |
| PCP-4 | Named individuals (for example a named doctor or manager) on a Listing are **personal information** and are collected only where necessary and permitted (D-51) |
| PCP-5 | Whether a specific published field constitutes personal data in Ethiopian law, and the lawful basis for publishing it, is **PENDING COUNSEL** (L-5, D-46); the product treats flagged items conservatively in the meantime |

---

## 15. Reviews — product summary

The full specification is [`review-policy.md`](review-policy.md). It governs
who may write and read Reviews, their structure, moderation, reporting,
anti-abuse measures, states, and the business-response policy. Summary of the
binding points:

| Point | V1 position | Decision |
| --- | --- | --- |
| Who may write | Authenticated Customers only | D-12 |
| Who may read | Everyone, including Guests | D-05 |
| Structure | Rating plus optional text | D-12 |
| Business replies | **Not in V1** — there is no account to reply from | D-12, D-54 |
| Review photos | **Deferred** | D-36 |
| Helpful voting | **Deferred** | D-37 |
| Moderation | Staff, against a published policy, with recorded reasons | D-12 |
| Suppression | Bulbula **MUST NOT** selectively suppress negative Reviews; suppression is a regulatory risk in its own right | R-01 |
| Unresolved details | Marked **Open — implementation/product detail (D-34)** in `review-policy.md`; **no policy is invented here** | D-34 |

---

## 16. Search, discovery and ranking

### 16.1 Search behaviour requirements

This section specifies **behaviour**. It deliberately contains no database,
index, query or storage design — those belong to the TRD.

| ID | Requirement |
| --- | --- |
| SRCH-1 | Search **MUST** match against Business names, descriptions, Categories, Subcategories, Areas and their Aliases |
| SRCH-2 | Search **MUST** tolerate common misspellings and partial words to a degree defined in the search design, and **MUST NOT** require exact matching |
| SRCH-3 | Search **MUST** handle both Amharic script and Latin transliteration of the same concept, through controlled Aliases rather than machine translation (R-09, D-18) |
| SRCH-4 | Filters **MUST** include Category, Subcategory, Area, open now, minimum rating and verified state |
| SRCH-5 | Sorting **MUST** include relevance, distance (when location is available) and rating |
| SRCH-6 | Results **MUST** be paginated or incrementally loaded, with stable ordering across pages |
| SRCH-7 | Every result **MUST** carry enough information to decide whether to open it: name, Category, Area, rating summary, open status, verified state, distance where applicable |
| SRCH-8 | Zero-result queries **MUST** be recorded for operational review (C-28) |
| SRCH-9 | Search **MUST** be available to Guests without authentication |
| SRCH-10 | Search behaviour **MUST** be identical on Web and Telegram Mini App, allowing for input-method differences (§22) |

### 16.2 Organic ranking inputs

Organic ordering is computed from product signals only. The **inputs** are
specified here; the **weights are not** — they are deferred to the technical
and search design, informed by real query data.

| Input | Rationale | Status |
| --- | --- | --- |
| Textual relevance to the query | The primary signal for a typed query | **[C]** input confirmed |
| Category and Subcategory match | Distinguishes an intent match from an incidental word match | **[C]** |
| Area match or proximity | Local intent dominates in this product (R-14) | **[C]** |
| Distance from the User, when available | Only when location permission is granted | **[C]** |
| Verification state and freshness | The product's differentiator is accuracy | **[C]**, interval **Open (D-08)** |
| Rating summary and review volume | A quality signal, subject to anti-abuse rules | **[C]**, mechanics **Open (D-34)** |
| Listing completeness | A proxy for usefulness of the destination page | **[C]** as an input; **weight Open (D-09)** |
| Open-now status | Being closed reduces usefulness at the moment of search (R-14) | **[C]** as an input |

| Rule | Statement |
| --- | --- |
| RANK-1 | **No weight, coefficient, multiplier or formula is specified or implied by this document.** Any number appearing downstream is a design decision, not a PRD requirement |
| RANK-2 | **Paid placement MUST NOT be a ranking input.** Sponsored placement is a separate, labelled surface element (C-16), never a boost | D-10 |
| RANK-3 | An identical query **MUST** produce identical organic ordering whether or not any Campaign is active |
| RANK-4 | Ranking changes **MUST** be explainable to Staff in plain language; the public explanation lives in the "how ranking works" page (C-18) |
| RANK-5 | No Business may buy, request or be granted an organic ranking advantage |

### 16.3 Discovery surfaces

Discovery is served by homepage (C-01), search (C-02), categories (C-04),
areas (C-05), category × area pages (C-06), nearby (C-07) and external search
engines (C-37). These are the approved discovery surfaces (D-05). No other
discovery mechanism — feeds, recommendations, notifications-as-discovery — is
in V1.

---

## 17. Customer account model

### 17.1 Capability access classes

Every capability falls into exactly one class. The complete matrix is in
[`interaction-permissions.md`](interaction-permissions.md).

| Class | Meaning | Examples |
| --- | --- | --- |
| **Guest-safe** | Works fully without an account; **MUST NOT** be gated, teased or interrupted by sign-in prompts | Search, browsing, profiles, hours, contact actions, maps, reading Reviews, sharing, reporting a problem |
| **Authenticated-required** | Requires a Customer account because the action is attributed to a person | Writing, editing and deleting Reviews; Save; Saved list; profile; reporting a Review |
| **Staff-only** | Requires Operator or Administrator rights; never exposed to Users | All of C-19…C-29 |
| **Administrator-only** | Higher-risk staff actions | Taxonomy, locations, campaign activation, roles, audit access — split **Open (D-14)** |
| **Future** | Not available to anyone in V1 | Business accounts, owner replies, self-service advertising |

### 17.2 Account rules

| ID | Requirement |
| --- | --- |
| ACC-1 | Account creation **MUST** be possible only through Google sign-in or email OTP (D-48) |
| ACC-2 | The product **MUST NOT** store or accept a password for any Customer (D-48) |
| ACC-3 | Sign-in **MUST** be offered contextually at the moment it is needed, and **MUST** return the Customer to the interrupted task |
| ACC-4 | The product **MUST NOT** interrupt discovery with unsolicited registration prompts, interstitials or modal walls |
| ACC-5 | One person **MUST** resolve to one Customer identity across both surfaces (C-32); linking rules are **Open (D-13)** |
| ACC-6 | The minimum viable profile is: identifier, email address, display name, sign-in method, creation date. Anything beyond this requires a stated purpose (D-51) |
| ACC-7 | A Customer **MUST** be able to delete their account from within the product (C-36) |
| ACC-8 | Staff accounts are **separate from Customer accounts** and are created by Administrators, never by self-registration |
| ACC-9 | Staff authentication **MUST** be stronger than Customer authentication; the mechanism is **Open (D-45)**, resolved in the TRD/security design |

---

## 18. Email requirements

Email is the only Bulbula-operated communication channel in V1 (D-24). The
product requires the following **categories** of email. **No provider is
selected here** — that is **Open (D-41)**.

| Category | Purpose | Trigger | Capability |
| --- | --- | --- | --- |
| **Authentication** | Deliver the single-use sign-in code | Customer requests email OTP | C-31 |
| **Account and security** | Notify of account events: new sign-in method linked, account deletion requested and completed | Account state change | C-36 |
| **Review lifecycle** | Notify an author when their Review is published, rejected or removed, with the reason | Moderation decision | C-25, C-35 |
| **Moderation and reports** | Acknowledge a report where the reporter supplied an address, and inform of the outcome where appropriate | Report lifecycle | C-15, C-26 |
| **Service** | Essential service messages: planned unavailability, material policy changes | Operational event | C-18 |
| **Support** | Replies to contact, correction and removal requests | Inbound contact | C-18, C-36 |
| **Staff operational** | Queue and escalation notifications to Staff | Operational event | C-28 |

### 18.1 Rules

| ID | Requirement |
| --- | --- |
| EM-1 | Every email **MUST** identify Bulbula and state why the recipient received it |
| EM-2 | Transactional email **MUST NOT** carry unrelated marketing content |
| EM-3 | Marketing email is **not in V1**; if it is ever introduced it requires its own lawful basis (**PENDING COUNSEL**, L-5) |
| EM-4 | Delivery failures **MUST** be recorded and surfaced to Staff; a failure **MUST NOT** block the action that triggered it |
| EM-5 | Email addresses are **personal information** (§21.2) and are subject to minimization and deletion rules |
| EM-6 | Authentication emails **MUST** follow the controls in C-31 |
| EM-7 | The product **MUST** be able to operate with a different email provider without product changes; provider selection is **Open (D-41)** |
| EM-8 | Sender domain authentication is required before launch; the mechanism is a TRD/deployment matter |

---

## 19. Advertising requirements

V1 advertising is **staff-managed, fixed-package sponsorship** (D-10). The
commercial definitions live in
[`../15-business/advertising-products.md`](../15-business/advertising-products.md);
the product requirements are here.

### 19.1 Approved sponsorship forms

| Form | What it is | Capability |
| --- | --- | --- |
| **Sponsored placement in search results** | A labelled position within or adjacent to a result list | C-16 |
| **Category sponsorship** | A labelled position on a specific Category or Subcategory surface | C-16 |
| **Homepage promotion** | A labelled position in a defined homepage slot | C-16, C-01 |

No other advertising form is in V1.

### 19.2 Mandatory properties of every sponsored surface

Each is a requirement, not a guideline. R-04 establishes that
distinguishability of paid results is a regulatory expectation, not only a
design preference.

| ID | Requirement |
| --- | --- |
| ADV-1 | **Label.** Every Sponsored placement **MUST** carry a visible label identifying it as sponsored, in the User's interface language, readable without interaction |
| ADV-2 | **Consistent treatment.** The label and visual treatment **MUST** be identical across every surface and both client surfaces |
| ADV-3 | **Defined placement.** Every Placement **MUST** have a documented position and a maximum number of slots; sponsored content **MUST NOT** appear anywhere undocumented |
| ADV-4 | **Campaign period.** Every Sponsored placement **MUST** derive from a Campaign with explicit start and end dates, and **MUST** stop automatically at the end |
| ADV-5 | **Business association.** Every Sponsored placement **MUST** be associated with exactly one published, eligible Business |
| ADV-6 | **Audit trail.** Every creation, approval, modification, suspension and termination of a Campaign **MUST** be recorded with actor, timestamp and reason (C-29) |
| ADV-7 | **Measurement.** Impressions and clicks **MUST** be recorded per Campaign for reporting; they **MUST NOT** affect price (fixed packages) and **MUST NOT** feed organic ranking |
| ADV-8 | **Separation.** Sponsored placement **MUST NOT** alter organic ordering, inclusion or exclusion (RANK-2, RANK-3) |
| ADV-9 | **No purchasable trust.** Verification, trust indicators and rating summaries **MUST NOT** be purchasable or influenced by a Campaign |
| ADV-10 | **Eligibility.** Only Businesses with a published, verified Listing in good standing may be sponsored; ineligibility stops delivery (C-16) |
| ADV-11 | **Density limits.** Each surface **MUST** define a maximum proportion of sponsored content; the values are specified in `advertising-products.md` |
| ADV-12 | **Editorial integrity.** Controls preventing commercial relationships from influencing moderation, verification or Corrections are **Open (D-39)** and **MUST** be resolved before the first paid Campaign runs |

### 19.3 Explicitly excluded from V1 advertising

Auction, bidding, CPC, CPM, CPA, performance pricing, programmatic,
third-party networks, automated targeting, self-service purchase,
self-service billing, payment processing, retargeting, and any form of
behavioural targeting of individuals. See §13.2.

---

## 20. Trust and safety

Bulbula publishes information about real businesses that those businesses
cannot edit themselves. That asymmetry creates obligations.

### 20.1 The correction mechanism substitutes for owner editing

Because D-02 removes owner editing, the correction path **is** the safety
valve, and it **MUST** be treated as a first-class capability rather than a
footnote.

| ID | Requirement |
| --- | --- |
| TS-1 | Every public Business profile **MUST** offer a visible way to report a problem or request a correction (C-15) |
| TS-2 | The report path **MUST** be available to Guests, without an account |
| TS-3 | Every published Listing **MUST** make clear how the Business itself can request a change — the contact page **MUST** describe the route explicitly (C-18) |
| TS-4 | Every report **MUST** reach a resolution state with a recorded outcome (C-26) |
| TS-5 | Bulbula **MUST** be able to unpublish a Listing promptly on a justified request from the Business, including withdrawal of Permission (§14.3) |
| TS-6 | Reported factual errors that materially mislead Users (wrong number, wrong location, closed business) **SHOULD** be prioritised above cosmetic corrections; target times are **PENDING PILOT** |

### 20.2 Content safety

| ID | Requirement |
| --- | --- |
| TS-7 | Review content **MUST** be moderated against a published policy (`review-policy.md`) |
| TS-8 | The product **MUST** provide a reporting route for abusive, defamatory or illegal content on any public surface |
| TS-9 | Moderation decisions **MUST** record the policy basis, the actor and the time (C-25, C-29) |
| TS-10 | Reporter identity **MUST NOT** be disclosed to the reported party |
| TS-11 | Bulbula **MUST NOT** publish user-generated content about named private individuals beyond what the review policy permits |

### 20.3 Integrity

| ID | Requirement |
| --- | --- |
| TS-12 | Anti-abuse measures for Reviews are required (`review-policy.md` §7): authentication, rate limits, duplicate detection, and staff review of anomalous patterns |
| TS-13 | Bulbula **MUST NOT** fabricate Listings, Reviews, ratings or activity of any kind |
| TS-14 | Bulbula **MUST NOT** selectively remove or suppress Reviews on commercial grounds (R-01, ADV-9) |
| TS-15 | Staff actions on Listings and Reviews **MUST** be attributable (C-29) |
| TS-16 | Separation between commercial relationships and editorial/moderation decisions **MUST** be enforced; the control design is **Open (D-39)** |

### 20.4 Staff and account security

| ID | Requirement |
| --- | --- |
| TS-17 | Staff accounts **MUST** use stronger authentication than Customer accounts and **MUST NOT** rely on email OTP alone — **Open (D-45)**, resolved in the TRD/security design (risk RK-19) |
| TS-18 | Staff permissions **MUST** follow least privilege; the Operator/Administrator split is **Open (D-14)** |
| TS-19 | Staff sessions **MUST** expire; values are a TRD matter |
| TS-20 | Access to personal information by Staff **MUST** be logged (C-29) |

---

## 21. Privacy and data handling

> **No statement in this document is a claim of legal compliance.** Items
> that require professional legal determination are marked **PENDING
> COUNSEL** and are tracked as L-items in
> [`product-decision-brief-v0.4.md`](../00-discovery/product-decision-brief-v0.4.md)
> §8 and as D-46 in the register. They **MUST NOT** be resolved by
> engineering judgement.

### 21.1 Principles

| ID | Principle |
| --- | --- |
| PRIV-1 | Collect the minimum personal information required for a stated purpose (D-51) |
| PRIV-2 | Every field **MUST** have a stated purpose before it is collected |
| PRIV-3 | Classification follows **whether the information relates to an identifiable natural person**, never where it appears |
| PRIV-4 | Business data **MUST NOT** be assumed non-personal (PCP-1) |
| PRIV-5 | Personal information **MUST** be deletable on request (C-36) |
| PRIV-6 | Access to personal information **MUST** be restricted and logged (TS-20) |
| PRIV-7 | The product **MUST** be able to locate all personal information relating to one person in order to execute a rights request |

### 21.2 Information classes

| Class | Contents | Treatment |
| --- | --- | --- |
| **Business information** | Trading name, Category, description, business address, hours, services, premises photographs, coordinates, business website and social links | Published. Usually not personal data — Ethiopian law protects natural persons, not legal persons (R-26). "Usually" is not "always": see personal contact points |
| **Personal information in business data** | A personal mobile used as the business number; a named individual associated with the Business; an owner's personal email | Flagged (PCP-2), collected only where necessary, locatable for rights requests, removable |
| **Staff-operational information** | Who collected, verified, edited, moderated or approved what, and when; Permission records | Internal only. Never published. Retained for accountability (C-29); retention floor **PENDING COUNSEL** (L-21, D-46b) |
| **Customer information** | Identifier, email address, display name, sign-in method, Saves, Reviews, account dates | Personal data. Minimised, deletable, never sold, never shared for advertising |
| **Derived and event data** | Analytics events and aggregates | **MUST NOT** identify an individual Guest (§26) |

### 21.3 Data location

| ID | Requirement | Basis |
| --- | --- | --- |
| LOC-1 | Personal information collected in Ethiopia **MUST** be stored on infrastructure located in Ethiopia unless a lawful transfer basis is confirmed | R-26 (Proclamation 1321/2024 residency clause) |
| LOC-2 | Non-personal business media **MAY** be served from a foreign CDN or object store, because it is not personal data | R-26; resolves the earlier hosting conflict |
| LOC-3 | Any cross-border transfer of personal information **MUST** rest on a confirmed lawful basis — **PENDING COUNSEL** (L-10, L-12) | R-26 |
| LOC-4 | Local hosting capacity exists and is practically available; **no vendor is selected in this document** — **Open (D-42)** | R-24 |
| LOC-5 | The product **MUST** keep data location a deployment-level decision, not an application-level assumption | D-42 |

### 21.4 Legal items pending professional confirmation

The following are **PENDING COUNSEL** and **MUST NOT** be treated as
resolved. This list mirrors the live register in v0.4 §8, which is
authoritative; the full historical register with context is in v0.3 §20.5.

| Item | Question |
| --- | --- |
| L-2 / D-42 | Precise data-location policy and whether it is legally sufficient |
| L-3 | ECA registration requirements and process |
| L-4 | Whether a data protection officer must be appointed |
| L-5 | Lawful basis per purpose — including publishing business data that contains personal information, and any future marketing email |
| L-6 | Required content of the privacy notice |
| L-7 | Data-subject rights procedures and response windows |
| L-8 | Breach notification procedure |
| L-10 / L-12 | Cross-border transfer assessment for Google, the email provider, Telegram, Maps and any CDN |
| L-15 | Review-content liability, takedown obligations and defamation complaints |
| L-16 / L-17 / L-20 | Advertising disclosure, invoicing, VAT/tax and trade licence |
| L-18 | Photography of premises and people |
| L-19 | Google Maps Platform terms |
| L-21 / D-46b | Retention schedule per data class, including audit records and Reviews after account deletion |
| L-22 | DPIA covering accounts, Reviews and cross-border transfers |
| D-46a | Minimum account age |
| D-46 | The overall legal minima set for launch |

Operational status of the regulator's machinery — registration portal,
adequacy determinations, implementing directives — **MUST** be confirmed by
counsel at the time of launch rather than assumed from this document (R-26).

---

## 22. Client surfaces: Web and Telegram Mini App

V1 launches on **two client surfaces simultaneously**: the Web and the
Telegram Mini App, on one backend, one domain model and one set of product
rules (D-15, D-49).

### 22.1 Shared product requirements

| ID | Requirement |
| --- | --- |
| SUR-1 | Both surfaces **MUST** deliver the same capabilities, except the documented exceptions in `scope-v1.md` §1.5 (SEO and operations are Web-only) |
| SUR-2 | Both surfaces **MUST** use the same backend, the same domain rules and the same API contracts (D-49) |
| SUR-3 | A Customer identity **MUST** be the same on both surfaces (C-32) |
| SUR-4 | Content **MUST** be identical: the same Listings, the same Reviews, the same ranking, the same Sponsored placements with the same labels |
| SUR-5 | Surface-specific differences **MUST** be confined to a documented adapter boundary — navigation chrome, share mechanism, authentication entry, map hand-off, viewport conventions (D-49) |
| SUR-6 | A surface-specific behaviour **MUST NOT** become a product difference; if it changes what a User can do, it is a scope change requiring a decision |
| SUR-7 | Neither surface may be treated as a port of the other; both are first-class |
| SUR-8 | **No JavaScript framework, library or rendering strategy is selected in this document** — **Open (D-16, D-17)** |

### 22.2 Telegram Mini App specifics

| ID | Requirement |
| --- | --- |
| TG-1 | The Mini App is a web application running in Telegram's runtime, not a separate build target (R-16) |
| TG-2 | The Mini App **MUST** work within Telegram's viewport, navigation and theme conventions |
| TG-3 | Telegram's user context **MUST** be validated server-side before being trusted (R-10) |
| TG-4 | A Telegram user is **not** an approved login provider (D-48); how Telegram context relates to Bulbula identity is **Open (D-33)** |
| TG-5 | Sign-in on the Mini App **MUST** account for OAuth friction inside embedded webviews (R-23); the resolution is a Mini App design question, not an assumption to be coded around |
| TG-6 | Sharing from the Mini App **MUST** produce links that resolve on the Web for recipients who are not Telegram users (C-17) |
| TG-7 | Mini App content is **not** an SEO surface (C-37) |

### 22.3 Mobile-first web requirements

Product-level requirements only. This is **not** a UI specification; layout,
components and visual design belong to the UX documents.

| ID | Requirement |
| --- | --- |
| MOB-1 | Every capability **MUST** be fully usable on a small touch screen; no capability may be desktop-only (D-52) |
| MOB-2 | Primary actions **MUST** be reachable with one hand on a typical phone |
| MOB-3 | The product **MUST** remain usable on mid- and low-range Android devices over constrained networks (R-06) |
| MOB-4 | Data usage **MUST** be treated as a cost to the User: images lazy-loaded and appropriately sized, no large payloads for small outcomes |
| MOB-5 | The product **MUST** be usable without a map loading, without device location, and without JavaScript for core content retrieval (C-37) |
| MOB-6 | Larger viewports **MUST** be supported, but **MUST NOT** be the design baseline |
| MOB-7 | Brand colours are orange, blue and white (D-53); the logo is **Open (D-53)** |

### 22.4 Flutter — later client, not V1

| ID | Statement |
| --- | --- |
| FL-1 | A Flutter client is a **later** client, not part of V1 (D-15) |
| FL-2 | When it ships it **MUST** consume the same backend, the same domain rules and the same API contracts as the Web and Mini App surfaces |
| FL-3 | It **MUST NOT** introduce product rules of its own; any behaviour it needs is a product requirement for all surfaces |
| FL-4 | No V1 requirement may be shaped by a hypothetical Flutter need; store-specific obligations (for example Apple Sign In, D-47) are addressed when that client is planned |
| FL-5 | API design **SHOULD** avoid decisions that would make a native client unreasonably difficult; this is a design preference, not a V1 feature |

---

## 23. SEO requirements

SEO applies to the Web surface only (`scope-v1.md` §1.5).

| ID | Requirement |
| --- | --- |
| SEO-1 | **Crawlability.** Every public page **MUST** be retrievable and readable by a crawler without executing JavaScript |
| SEO-2 | **Canonical URLs.** Every public page **MUST** declare a single canonical URL |
| SEO-3 | **URL structure.** URLs **MUST** be stable, human-readable and semantically meaningful for Business profiles, Categories, Subcategories, Areas and category × area pages |
| SEO-4 | **Profile URLs.** A Business profile URL **MUST** remain stable across edits; a name change **MUST NOT** break existing links (redirect where the URL changes) |
| SEO-5 | **Metadata.** Every indexable page **MUST** have a unique title and description derived from its content, never duplicated across pages |
| SEO-6 | **Structured data.** Business profiles **MUST** publish structured data describing the Business, its location, hours and rating summary where one exists; marked-up ratings **MUST** reflect what is visible on the page (R-15) |
| SEO-7 | **Sitemap.** A sitemap **MUST** be generated and kept current as Listings are published and unpublished |
| SEO-8 | **Indexability control.** Non-public pages (operations console, account pages, authentication flows) **MUST** be excluded from indexing |
| SEO-9 | **Thin content.** Pages below the minimum-content rule **MUST NOT** be indexable (C-06) |
| SEO-10 | **Duplicate prevention.** Filter, sort and pagination parameters **MUST NOT** create multiple indexable URLs for the same content; parameterised variants **MUST** canonicalise to the base page |
| SEO-11 | **Sharing metadata.** Every public page **MUST** provide title, description and image metadata so that shared links preview correctly in messaging applications (C-17) |
| SEO-12 | **Category, location and category × area pages** are first-class SEO destinations, not navigation by-products |
| SEO-13 | **Unpublished content.** Removing a Listing **MUST** remove it from the sitemap and return a not-found response |
| SEO-14 | **Language.** V1 pages are English-first; the URL and metadata model **MUST NOT** preclude adding Amharic pages later (D-18) |
| SEO-15 | **No manipulation.** Doorway pages, cloaking, hidden text, paid links and fabricated content are prohibited |

---

## 24. Non-functional requirements

> **Numbers discipline.** Only values traceable to an approved decision or an
> accepted external standard are stated as requirements. Everything else is
> marked **[P] Proposed** and **MUST** be approved before it becomes a
> target. No number in this section was invented to look rigorous.

### 24.1 Performance

| ID | Requirement | Status |
| --- | --- | --- |
| NFR-P1 | Public pages **SHOULD** meet the Core Web Vitals "good" thresholds at the 75th percentile: LCP ≤ 2.5 s, INP ≤ 200 ms, CLS ≤ 0.1 | **[P]** — external standard (R-13), not yet adopted as a contractual target |
| NFR-P2 | Page weight budgets for public pages | **[P]** — proposed in `project-understanding-v0.1.md` §22.1; **not approved** |
| NFR-P3 | Search **MUST** return results quickly enough to feel immediate on a mid-range device over a constrained network; a numeric target is **[P]** pending measurement | **[P]** |
| NFR-P4 | Third-party components (maps, fonts) **MUST NOT** block first render (C-10, MOB-5) | **[C]** derived from D-52 |
| NFR-P5 | Media **MUST** be delivered at sizes appropriate to the device | **[C]** |

### 24.2 Availability and reliability

| ID | Requirement | Status |
| --- | --- | --- |
| NFR-A1 | Public discovery **MUST** remain available when non-essential subsystems (analytics, maps, email) fail | **[C]** |
| NFR-A2 | No availability percentage is committed in V1 | **[U]** — would require infrastructure decisions (D-42) |
| NFR-A3 | Failures **MUST** be visible to Staff through monitoring, not discovered by Users | **[C]** |
| NFR-A4 | Data **MUST** be backed up and restorable; frequency and retention are TRD matters | **[C]** |
| NFR-A5 | A failed write **MUST NOT** leave a Listing, Review or Campaign in an inconsistent published state | **[C]** |

### 24.3 Accessibility

| ID | Requirement | Status |
| --- | --- | --- |
| NFR-AC1 | Public surfaces **SHOULD** conform to WCAG 2.2 level AA | **[P]** — standard identified (R-11); adoption as a hard requirement not yet approved |
| NFR-AC2 | All functionality **MUST** be operable by keyboard on the Web surface | **[C]** |
| NFR-AC3 | Colour **MUST NOT** be the sole carrier of meaning — including the Sponsored label (ADV-1) | **[C]** |
| NFR-AC4 | Text alternatives **MUST** exist for meaningful images | **[C]** |
| NFR-AC5 | Interface text **MUST** remain legible and functional at increased text sizes | **[C]** |

### 24.4 Security

| ID | Requirement | Status |
| --- | --- | --- |
| NFR-S1 | All traffic **MUST** be served over encrypted connections | **[C]** |
| NFR-S2 | Authentication **MUST** follow C-31's controls; staff authentication is stronger — **Open (D-45)** | **[C]** / Open |
| NFR-S3 | Authorisation **MUST** be enforced server-side for every staff and Customer action | **[C]** |
| NFR-S4 | User-supplied content **MUST** be treated as untrusted on input and on output | **[C]** |
| NFR-S5 | Secrets **MUST NOT** appear in source control or client code | **[C]** |
| NFR-S6 | Security-relevant events **MUST** be logged (C-29) | **[C]** |
| NFR-S7 | Specific mechanisms, headers, libraries and cryptographic choices are TRD matters | — |

### 24.5 Privacy

| ID | Requirement | Status |
| --- | --- | --- |
| NFR-PR1 | §21 applies as a non-functional constraint on every capability | **[C]** |
| NFR-PR2 | Personal data residency per LOC-1 | **[C]** requirement; basis **PENDING COUNSEL** |
| NFR-PR3 | Analytics **MUST NOT** identify individual Guests (§26) | **[C]** |
| NFR-PR4 | No third-party tracking for advertising purposes | **[C]** (D-10) |

### 24.6 SEO, scalability, compatibility, observability

| ID | Requirement | Status |
| --- | --- | --- |
| NFR-SEO1 | §23 applies as a non-functional constraint on the Web surface | **[C]** |
| NFR-SC1 | The product **MUST** support the launch area's data volume comfortably, and **MUST NOT** contain design decisions that prevent adding areas (GEO-4) | **[C]** |
| NFR-SC2 | Named triggers for re-evaluating infrastructure are proposed in `project-understanding-v0.1.md` §22.4 | **[P]** |
| NFR-SC3 | No capacity numbers are committed in V1 | **[U]** |
| NFR-C1 | The Web surface **MUST** work on current versions of the major mobile browsers, prioritising Android (R-06) | **[C]** |
| NFR-C2 | The Mini App **MUST** work within Telegram's current runtime on Android and iOS | **[C]** |
| NFR-C3 | Core content **MUST** be reachable without JavaScript (SEO-1, MOB-5) | **[C]** |
| NFR-O1 | Errors, failed jobs and failed notifications **MUST** be observable by Staff | **[C]** |
| NFR-O2 | Staff **MUST** be able to answer "is this Listing published, verified, and when was it last changed, by whom" from the console (C-28, C-29) | **[C]** |
| NFR-O3 | Observability tooling selection is a TRD matter | — |

---

## 25. Notifications

| ID | Requirement |
| --- | --- |
| NOT-1 | Email is the only Bulbula-operated notification channel in V1 (D-24) |
| NOT-2 | The notification set is exactly the categories in §18 |
| NOT-3 | Every notification **MUST** be triggered by a defined product event, never by a schedule invented at implementation time |
| NOT-4 | Notifications **MUST NOT** be used for marketing or re-engagement in V1 |
| NOT-5 | Transactional notifications (sign-in codes, moderation outcomes, account actions) **MUST** be sent and **MUST NOT** be opt-out, because they are required for the service to function |
| NOT-6 | Any future non-transactional category **MUST** be opt-in with a recorded lawful basis (**PENDING COUNSEL**, L-5) |
| NOT-7 | Notification failures **MUST** be recorded and visible to Staff (EM-4) |
| NOT-8 | Push notifications are excluded from V1 (§13.4) |

---

## 26. Analytics

### 26.1 The raw/aggregate distinction

| Term | Definition |
| --- | --- |
| **Event** | A single recorded occurrence of a defined interaction, at the time it happens |
| **Metric** | A value computed by aggregating events over a dimension and period |

| ID | Requirement |
| --- | --- |
| AN-1 | Events and metrics **MUST** be kept distinct in specification, storage and presentation |
| AN-2 | Staff-facing views **MUST** read from aggregated metrics, never compute from raw events on demand (C-28) |
| AN-3 | An event **MUST NOT** store information identifying an individual Guest (PRIV-3, NFR-PR3) |
| AN-4 | Analytics **MUST NOT** be on the critical path of any User action (C-38) |
| AN-5 | Granularity, retention, raw-event storage and tooling are **Open (D-27)** |

### 26.2 V1 event set

| Event | Recorded when | Primary use |
| --- | --- | --- |
| Search performed | A query is executed | Demand signals, zero-result analysis |
| Zero-result search | A query returns nothing | Coverage gaps (SRCH-8) |
| Category / Area / combination page viewed | The page is rendered | Demand by taxonomy and place |
| Business profile viewed | A profile is rendered | Listing performance |
| Search impression | A Business appears in a result set | Visibility measurement |
| Contact action | Call, website, directions or social link activated | The core value event (C-11) |
| Save performed | A Customer Saves a Business | Engagement |
| Review submitted | A Customer submits a Review | Contribution volume |
| Report submitted | A report is submitted | Data-quality signal |
| Sponsored impression | A Sponsored placement is rendered | Campaign reporting (ADV-7) |
| Sponsored click | A Sponsored placement is activated | Campaign reporting (ADV-7) |
| Share performed | A profile is shared | Distribution |

### 26.3 V1 metric set

Coverage by Category and Area · verification age distribution · stale-listing
count · listing completeness distribution · listings created, published and
returned per period · report volume and resolution time · review volume and
moderation outcomes · zero-result rate and top zero-result queries · profile
views and contact actions per Business · campaign impressions and clicks per
Campaign.

These feed C-28 and the pilot measurement set
(`listing-operations.md` §6).

---

## 27. The operations console as a product surface

| ID | Requirement |
| --- | --- |
| OPS-1 | The operations console is a **primary V1 product surface** and is specified, designed, built and tested to the same standard as public surfaces |
| OPS-2 | It is Web-only and staff-only (`scope-v1.md` §1.5, `interaction-permissions.md`) |
| OPS-3 | It **MUST** support the full lifecycle in `listing-operations.md`: collect → create → review → publish → maintain → correct → re-verify |
| OPS-4 | It **MUST** present work as queues: listings awaiting review, reports awaiting triage, reviews awaiting moderation, listings due for re-verification |
| OPS-5 | It **MUST** make the state of any Listing answerable at a glance: published or not, verified when, changed by whom, permission on file |
| OPS-6 | It **MUST** be usable at the pace of real fieldwork; an Operator **MUST NOT** need to leave the console to complete a Listing |
| OPS-7 | It **MUST** record every consequential action in the Audit log (C-29) |
| OPS-8 | Its throughput is the production constraint on launch readiness, and its metrics feed the pilot (C-28) |

**The 20-business pilot is a launch-preparation activity, not a product
feature** (D-30, D-31). It produces the operational benchmark from which the
owner sets the numeric launch threshold (D-30n, **PENDING**). Nothing in the
product may be built *for* the pilot, and the pilot's numbers **MUST NOT** be
invented in advance. Specification: `listing-operations.md` §6.

---

## 28. Roles and permissions

V1 has exactly three staff-relevant roles and no others.

| Role | Summary |
| --- | --- |
| **Customer** | Authenticated end user. No access to any operations capability |
| **Operator** | Day-to-day listing, moderation and report work |
| **Administrator** | Operator capabilities plus taxonomy, locations, campaign activation, roles, policy and audit |

| ID | Requirement |
| --- | --- |
| ROL-1 | No role beyond Customer, Operator and Administrator exists in V1 |
| ROL-2 | The exact capability split between Operator and Administrator is **Open (D-14)**; this document states only the principle of least privilege and the Administrator-only items in ADM-1 |
| ROL-3 | Role assignment is performed by an Administrator and is audited (C-29) |
| ROL-4 | Staff authentication strength is **Open (D-45)** and is resolved in the TRD/security design, not here |
| ROL-5 | A Business is not a role (D-54) |

Complete matrix: [`interaction-permissions.md`](interaction-permissions.md).

---

## 29. Open items and external dependencies

### 29.1 Pending external confirmation — these gate PRD approval

| Item | Nature | Owner |
| --- | --- | --- |
| **D-46 + the L-items** | Legal and compliance minima | Qualified Ethiopian counsel — **PENDING COUNSEL** |
| **D-31** | The 20-business operational pilot | Bulbula operations — **PENDING** |
| **D-30n** | Numeric launch threshold derived from the pilot | Project owner — **PENDING PILOT** |
| **D-40** | Launch-area administrative parent and practical boundary | Local confirmation — **PENDING** |

### 29.2 Open product and implementation details

Marked in place throughout §12 and listed in `scope-v1.md` §4: D-04, D-08,
D-09, D-11, D-13, D-14, D-16, D-17, D-19, D-20, D-21, D-23, D-25, D-26,
D-27, D-28, D-33, D-34, D-35, D-38, D-39, D-41, D-42, D-43, D-44, D-45,
D-55, D-56, D-57.

### 29.3 Deferred

D-15r (Flutter timing), D-36 (review photos), D-37 (helpful voting), D-47
(Apple Sign In for the iOS client).

### 29.4 Rule

> An open item is a **visible hole**, not a licence to invent. Downstream
> documents **MUST** either resolve an open item through the decision process
> and record it in the register, or keep it marked open. Silent resolution is
> a process failure.

---

## 30. Research handling

| Rule | Statement |
| --- | --- |
| RES-1 | Research findings are cited by ID (R-01…R-26) and live in `research-notes-v0.1.md` and the v0.2/v0.3 appendices |
| RES-2 | A source fact and Bulbula's interpretation of it are stated separately; interpretations are **[P]** |
| RES-3 | A competitor's feature is never a requirement |
| RES-4 | Research conducted in this phase is recorded only where it materially changes a requirement |
| RES-5 | No new research was required to draft this PRD. The findings it relies on (R-01, R-04, R-05, R-06, R-07, R-08, R-09, R-10, R-11, R-13, R-14, R-15, R-16, R-20, R-21, R-22, R-23, R-24, R-25, R-26) were all gathered and recorded in Phase 2 and are unchanged |

---

## 31. Decision references

**Approved decisions this PRD implements:**
D-01, D-02, D-03, D-05, D-06, D-10, D-12, D-15, D-18, D-24, D-29, D-30,
D-48, D-49, D-50, D-51, D-52, D-53, D-54.

**Open decisions this PRD marks and does not resolve:**
D-04, D-08, D-09, D-11, D-13, D-14, D-16, D-17, D-19, D-20, D-21, D-23,
D-25, D-26, D-27, D-28, D-33, D-34, D-35, D-38, D-39, D-40, D-41, D-42,
D-43, D-44, D-45, D-46, D-55, D-56, D-57.

**Deferred decisions referenced:** D-15r, D-36, D-37, D-47.

**Closed decision referenced:** D-32 (superseded by D-48).

**Scheduled:** D-31 (pilot execution).

Authority: [`../60-decisions/decision-register.md`](../60-decisions/decision-register.md).
