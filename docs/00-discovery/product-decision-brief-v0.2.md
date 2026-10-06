# Bulbula Product Decision Brief

| | |
| --- | --- |
| **Document** | Product Decision Brief (Decision Workshop Preparation) |
| **Version** | 0.2 |
| **Status** | Discovery — awaiting owner decisions |
| **Date** | 2026-10-07 |
| **Builds on** | [project-understanding-v0.1.md](project-understanding-v0.1.md) @ `0610568` (PR #12, unmerged) · [research-notes-v0.1.md](research-notes-v0.1.md) |
| **Supersedes** | No prior decision brief exists. This document **amends**, and does not replace, v0.1: v0.1 remains the source material, and §18 below lists exactly which of its statements have changed. |
| **Repository baseline** | `main` @ `fc6188b`; discovery branch `docs/phase-2-discovery` @ `0610568` |

**Certainty tags are unchanged from v0.1 and are used throughout:**
**[C]** Confirmed · **[SI]** Strongly implied · **[P]** Proposed (needs approval) ·
**[U]** Unknown · **[X]** Conflict.

No recommendation in this document is a requirement. Where the owner has now
decided something, it is tagged **[C]** and attributed to this phase.

---

## Status at a glance

| Area | Current status |
| --- | --- |
| Listing ownership | **Confirmed: Bulbula-managed in V1** |
| Owner-created listings | **Future** |
| Owner claims | **Future** |
| Guest browsing | **Confirmed** |
| Google login | **Confirmed V1** |
| Apple login | **Confirmed V1** |
| Email login | **Confirmed V1** |
| Authenticated reviews | **Confirmed** |
| Authenticated likes/favorites/saves | **Confirmed direction** |
| First launch | **Web + Telegram Mini App** |
| Mobile-first Web | **Confirmed** |
| Shared Web/Telegram frontend | **Strong preference; validate technically** |
| Flutter | **Later client** |
| Final logo | **Not yet approved** |
| Primary color | **Orange** |
| Secondary color | **Blue** |
| Light mode | **White-dominant** |
| Framework-free PHP backend | **Confirmed** |
| Modular monolith | **Confirmed** |

### Additions this phase (not in the owner's table, proposed for the same list)

| Area | Proposed status |
| --- | --- |
| Business accounts / self-service dashboard | **[P] Future — not V1** (follows from company-managed listings) |
| Owner replies to reviews | **[P] Future — not V1** (there is no owner account to reply from) |
| Advertising in V1 | **[P] Internally administered only** — real campaigns, real labelling, real tracking, no self-service purchase |
| Telegram as an identity provider | **[P] Needed for V1** — Google/Apple OAuth inside the Telegram WebView is hostile; see §5.4 |
| Business/branch model | **[P] Canonical `Business → Branch`, with exactly one branch for single-location businesses** |
| Language strategy | **[P] Option A — English-first, bilingual-ready**, with Amharic names stored from day one; see §13 |
| Frontend approach | **[P] One server-rendered PHP frontend, two runtimes** (web, Telegram), progressive enhancement, no SPA |

---

## 1. Executive decision summary

### What changed

The owner's new decisions resolve the single most consequential open question
from discovery and, in doing so, **make V1 substantially smaller and more
achievable than either option sketched in v0.1 §25.4**.

In V1, Bulbula is **not a two-sided platform**. It is:

```text
a Bulbula-operated local directory product
        +
an internal operations console that Bulbula staff use to build and maintain it
        +
a customer account layer for reviews and saves
        +
an internally-administered advertising subsystem
```

Businesses are **subjects of the product, not users of it**. They have no
account, no login, no dashboard and no self-service surface in V1. Every
listing is created, verified, maintained and corrected by Bulbula staff.

### Why that matters more than it first appears

Four entire subsystems leave V1 as a direct consequence: owner onboarding,
the claim workflow, the business dashboard, and self-service advertising
purchase with its billing surface. Verification stops being a user-facing
workflow with evidence upload and adjudication and becomes an **internal data
quality process**. Moderation load drops, because the only user-generated
content in V1 is reviews, reports and saves.

What replaces them is one subsystem that v0.1 under-weighted: **a genuinely
good internal operations console**. In V1 the console is not back-office
plumbing, it is the *production line for the product itself*. If staff cannot
create a complete, verified, bilingual listing with photos in a few minutes,
the directory will not reach the coverage it needs to be worth searching.
This is the single biggest shift in emphasis between v0.1 and v0.2. **[P]**

### The strategic risk this creates

The cold-start problem from v0.1 §1 does not go away — it changes shape. In
the two-sided model, supply growth could eventually come from business owners
doing the work. In the company-managed model, **every listing costs Bulbula
staff time, forever**, including maintenance. V1 therefore has a hard
operational ceiling that must be designed for, measured, and used to set the
launch bar. A "listings per staff-hour" number is now a product metric.
**[P] — see risk RK-1 and decision D-31.**

### Top recommendations

1. **V1 scope**: public discovery product + operations console + customer
   accounts + internally-administered ads. No business-facing surface. **[P]**
2. **One frontend, two runtimes**: server-rendered PHP pages that the Telegram
   Mini App loads directly, with a thin Telegram adapter. Technically
   validated below — it works, with named caveats. **[P]**
3. **Add Telegram as an identity provider** alongside Google, Apple and email,
   because OAuth redirects inside the Telegram WebView are a poor experience.
   **[P]**
4. **English-first, bilingual-ready**, storing Amharic names and aliases from
   day one and shipping the Amharic interface when there is evidence it is
   needed. **[P]**
5. **Decide the operations capacity question (D-31) before the PRD.** It now
   determines V1's realistic scope more than any feature decision does.
   **[P]**

---

## 2. Newly confirmed owner decisions

Recorded verbatim in substance, tagged **[C]**, attributed to Phase 2.1.

| # | Decision | Effect on v0.1 |
| --- | --- | --- |
| **NC-1** | **V1 listings are created and managed by the Bulbula company.** Business owners cannot create listings, claim listings, or publish listing information. Bulbula discovers, creates, verifies, maintains, updates and quality-controls all listings. | **Resolves D-02.** Removes claims and owner onboarding from V1 |
| **NC-2** | Owner-created and owner-claimed listings may come later; the architecture must remain extensible to support them without a redesign. | Constrains the data model (provenance, ownership seams) |
| **NC-3** | V1 is **not** a marketplace where owners create listings. | Confirms positioning |
| **NC-4** | **Guest browsing** is supported: browse, search, view profiles, categories, locations and public information without an account. | Confirms v0.1 **[C]** |
| **NC-5** | **Registered accounts** via **Google, Apple and email**. Implementation details are a later technical decision. | Partially resolves D-13 |
| **NC-6** | Identity-dependent interactions (reviews, likes/favourites/saves, other user-attributed actions) **require authentication**. | Confirms v0.1 **[SI]** SI-1 |
| **NC-7** | Interactions must be classified as **guest-safe / authenticated-required / admin-business-only**, with unresolved ones named. | Produces §6 |
| **NC-8** | The platform should have **one coherent Bulbula identity** with linked email/Google/Apple identities, rather than separate per-provider accounts (to be evaluated, not finalised). | Shapes D-13 |
| **NC-9** | **First launch = Web + Telegram Mini App**, coordinated. Flutter later. | **Revises D-15 / PR-12** |
| **NC-10** | The Web application is **mobile-first from the foundation**, not responsive-desktop. | New confirmed constraint |
| **NC-11** | **Strong preference** for Web and Telegram Mini App sharing essentially the same frontend codebase, subject to technical validation; Telegram-specific behaviour isolated behind a clear boundary; no Telegram patterns forced into the normal website. | **Revises D-16** |
| **NC-12** | Telegram Mini Apps are web applications inside Telegram using the **same Bulbula backend and domain model**; `initData` must be validated server-side; not implemented now. | Confirms v0.1 §18.2 |
| **NC-13** | The web experience may feel polished and app-like on mobile **without becoming an artificial app imitation**; SEO, browser navigation, deep linking, accessibility, shareability and desktop usability are preserved. | New confirmed constraint |
| **NC-14** | Flutter is a **separate later client** that must not share the web frontend code, and must consume the same backend, API contracts, product rules, terminology and design principles. | Confirms + constrains |
| **NC-15** | Advertising remains a first-class subsystem; a simpler V1 model (sponsored placement, category sponsorship, homepage promotion, fixed packages) is to be evaluated, preserving separate organic ranking, clear labels, placement rules, campaign records and performance tracking. | Shapes D-10 |

---

## 3. V1 scope recommendation

**[P] throughout this section.** This is the recommendation the PRD should
accept, modify or reject.

### 3.1 The three V1 surfaces

```text
┌──────────────────────────────────────────────────────────────┐
│ 1. PUBLIC DISCOVERY PRODUCT          (web + Telegram Mini App)│
│    guests and registered customers                            │
├──────────────────────────────────────────────────────────────┤
│ 2. OPERATIONS CONSOLE                       (staff only, web) │
│    the production line that builds and maintains the directory│
├──────────────────────────────────────────────────────────────┤
│ 3. CUSTOMER ACCOUNT LAYER                   (web + Telegram)  │
│    identity, reviews, saves                                   │
└──────────────────────────────────────────────────────────────┘
          no business-facing surface exists in V1
```

### 3.2 V1 — recommended inclusions

**Public discovery**

| # | Capability | Note |
| --- | --- | --- |
| 1 | Homepage with discovery surfaces (featured, popular, new, categories, areas) | Sponsored slots labelled |
| 2 | Keyword search with filters (category, subcategory, area, open now, rating, verified) and sorting | Core verb |
| 3 | Autocomplete | Promoted from "candidate" in v0.1: on mobile, typing a full query on a slow connection is the main friction point |
| 4 | Category and subcategory browsing | SEO surface |
| 5 | Location/area browsing, plus `category × area` pages | The local SEO money pages |
| 6 | Nearby / distance sort | Requires geolocation permission UX; bounding-box + haversine (v0.1 PR-5) |
| 7 | Business profile pages (full model, §12) | The atomic unit |
| 8 | Opening hours → computed open/closed | Filter + trust signal |
| 9 | Google Maps embed + "Open in Google Maps" | Deferred-load (v0.1 §11.4) |
| 10 | Contact actions: call, website, directions, social — all tracked | Value proof + analytics |
| 11 | Trust indicators: verified badge, last-updated, data-source transparency | Replaces the claim/verification UX |
| 12 | Reviews: read as guest, write when authenticated | §11 |
| 13 | Favourites/saves when authenticated | §6 |
| 14 | "Report a problem / suggest a correction" | **The only inbound channel from reality in V1** — more important here than in v0.1, because no owner can fix their own data |
| 15 | Sponsored placements, clearly labelled | §10 |
| 16 | Share (web share + Telegram share) | Acquisition loop |
| 17 | Static pages: about, contact, policies, how ranking works, privacy | Legal + credibility |

**Operations console (staff)**

| # | Capability | Note |
| --- | --- | --- |
| 18 | Listing CRUD with a fast, mobile-capable capture flow | Staff may collect data on a phone in the field |
| 19 | Bulk/assisted entry helpers: duplicate detection, required-field checks, completeness score | Throughput is the constraint |
| 20 | Media upload + derivative generation | §3.4 of v0.1 media pipeline |
| 21 | Internal verification states and evidence notes | Internal process, not a user workflow |
| 22 | Category and location taxonomy management | §12, §14 |
| 23 | Review moderation queue + reports triage | Only UGC in V1 |
| 24 | Campaign/placement administration | §10 |
| 25 | Operational dashboards: coverage, freshness, staleness, zero-result searches, moderation latency | Runs the production line |
| 26 | Audit log of privileged actions | Confirmed in v0.1 |

**Customer accounts**

| # | Capability | Note |
| --- | --- | --- |
| 27 | Sign in with Google, Apple, email (+ Telegram inside the Mini App, **[P]**) | §5 |
| 28 | One Bulbula identity with linked providers | NC-8 |
| 29 | Minimal profile (display name, avatar optional, locale) | Keep PII collection minimal — §15 |
| 30 | Account deletion + data export path | Legal requirement, not polish |

**Cross-cutting**

| # | Capability |
| --- | --- |
| 31 | SEO foundation: URLs, canonicals, structured data, sitemaps, metadata |
| 32 | Analytics event capture + cron rollups |
| 33 | Notifications (at least one channel) for account and moderation flows |
| 34 | Privacy notice, consent capture, data-subject request handling |

### 3.3 Explicitly out of V1 (changed by this phase)

| Capability | Was in v0.1 V1 list | Now |
| --- | --- | --- |
| Business account / login | Yes (#10) | **Future** |
| Owner-created listings | Yes (#10) | **Future (NC-2)** |
| Claim workflow | Yes (#10, #11) | **Future** |
| Owner profile management | Yes | **Future** |
| Owner replies to reviews | V1 candidate | **Future** — no account to reply from |
| Business analytics dashboard | Yes (#16) | **Future** — staff-produced reports instead |
| Self-service ad purchase + invoicing | V1 candidate | **Future** |
| Owner-facing verification workflow | Yes (#11) | **Internal process only** |

### 3.4 V1 candidates (prioritise, do not assume)

Services/products with prices · FAQs · offers · collections · review photos ·
saved searches · "open now" push surfaces · Amharic interface · multi-branch
UI · operator mobile capture app (as opposed to responsive console) ·
business-facing monthly report PDF generated by staff.

### 3.5 What this scope is worth testing against

**[P]** A V1 defined this way can be stated in one sentence, which is a good
sign: *"Every business in Bole Bulbula, correct and current, searchable in
under a minute on a phone — plus the internal machinery to keep it that way."*

---

## 4. V1 listing model

### 4.1 The model

**[C — NC-1]** Listings are company-owned records. Provenance, editing rights
and publication are all internal.

```text
Bulbula staff
   │  discover → create → verify → publish → maintain → re-verify
   ▼
Listing (company-owned record)
   │
   ├── provenance: who created it, from what source, when
   ├── verification: method, evidence note, date, operator, expiry
   ├── freshness: last reviewed, last changed, next review due
   └── corrections: inbound reports from customers and businesses
```

### 4.2 Consequences, reassessed as instructed (§12 of the brief)

| Area | V1 position | Certainty |
| --- | --- | --- |
| **Owner onboarding** | Does not exist | **[C]** |
| **Business accounts** | Do not exist | **[P]** (follows from NC-1) |
| **Claim workflows** | Do not exist | **[C]** |
| **Verification workflows** | Internal only: an operator records *how* a listing was verified (phone call answered, visited in person, licence seen) with a date and a note. No evidence upload from third parties, therefore no document retention problem in V1 — which also removes the hardest legal question from the critical path | **[P]** |
| **Listing provenance** | Mandatory field from day one: `source` (field visit, phone, public web, partner), `created_by`, `verified_by`, `verified_at`. Without it, a company-managed directory cannot defend its own accuracy | **[P]** |
| **Moderation** | Scope shrinks to reviews + reports; listings need review only when a correction arrives | **[P]** |
| **Listing editing permissions** | Staff only, role-gated, audit-logged | **[P]** |
| **Data correction** | Public "report a problem / suggest a correction" form (guest-allowed, rate-limited) feeding a staff queue — the **only** external data signal in V1 | **[P]** |
| **Business communication** | Out-of-band (phone, Telegram, in person) by staff; no in-product messaging. Record contact history against the listing so sales and data quality share one view | **[P]** |
| **Advertising participation** | Staff-mediated: a campaign is created by an operator on behalf of a business. No business login required | **[P]** |

### 4.3 Extensibility for the future owner model (NC-2)

**[P]** Three seams make the future transition cheap, and all three cost
almost nothing now:

1. **An ownership seam.** Model `Listing` with an optional `owner_user_id`
   (always `NULL` in V1) and an `ownership_state` enum
   (`company_managed` → later `claimed`, `owner_managed`). The future claim
   feature then changes *state*, not *schema*.
2. **An editing seam.** Route every change through one application service
   (`UpdateListing`) that takes an actor and a reason, rather than letting
   console controllers write directly. Owner edits later become a second
   actor type behind the same service and the same audit log.
3. **A provenance seam.** Field-level `source` and `verified_at` metadata lets
   a future owner edit be distinguished from staff data, which is exactly the
   distinction a claim workflow needs.

**Anti-recommendation:** do **not** build a dormant claim workflow, a disabled
business dashboard, or permission scaffolding for roles that do not exist.
Seams are cheap; unused features are not. **[P]**

---

## 5. Customer account and authentication model

### 5.1 Confirmed

**[C]** Guest browsing for all public discovery. Registered accounts via
**Google**, **Apple**, **email**. Identity-dependent interactions require
authentication. One coherent Bulbula identity with linked provider identities
is the preferred direction (NC-8).

### 5.2 Recommended identity model

**[P]**

```text
User (Bulbula identity)
 ├── id, display name, locale, created_at, status
 ├── Identity(provider = google,   subject = <sub>,  email, verified)
 ├── Identity(provider = apple,    subject = <sub>,  email|relay, verified)
 ├── Identity(provider = email,    subject = <email>,            verified)
 └── Identity(provider = telegram, subject = <telegram_user_id>)   ← proposed
```

Rules proposed:

1. A `User` may have many `Identity` rows; an `Identity` belongs to exactly
   one `User`.
2. **Never auto-merge on email alone** unless the provider asserts the email
   is verified *and* the existing account's email is verified. Auto-merging on
   an unverified email is a documented account-takeover vector.
3. Linking a second provider happens **while signed in**, deliberately — not
   silently at login.
4. Store the provider subject (`sub`), never the provider's access token.
5. The display identity is Bulbula's own (display name), so a provider change
   never changes how a review is attributed.

### 5.3 Provider-specific realities to plan for

**[C — research R-17]** Sign in with Apple **for the web** requires a paid
Apple Developer Program membership (99 USD/year), a Services ID associated
with a primary App ID, registered domains and return URLs, a private key, and
a client secret that is a signed JWT valid at most six months. Apple's
private email relay means many users will arrive with an
`@privaterelay.appleid.com` address; sending to those addresses requires
configuring the relay service with verified domains.

**[P] Consequences:**

- Apple login has a **hard cost and an account dependency** that nothing else
  in V1 has. If the Apple Developer Program enrolment is not already in
  progress, Apple login is the most likely V1 slip. → **D-32**.
- The client secret **expires at most every six months**. A rotation
  procedure must exist or logins will break silently months after launch.
  This belongs in the runbook, not in someone's memory.
- Relay addresses break the naive "same email = same person" assumption,
  which is a second argument for rule 2 above.

**[P]** Email login: prefer **one-time codes or magic links over passwords**
(no password storage, no reset flow, no credential stuffing, and it satisfies
WCAG 2.2 Accessible Authentication). The dependency is email deliverability,
which is weak from shared cPanel hosting — so this binds to **D-24**
(notification provider). If deliverability cannot be solved, email login is
not viable and the V1 provider set shrinks. **[U]**

### 5.4 The Telegram gap — a decision this phase surfaces

**[P] New finding.** Inside the Telegram Mini App WebView, Google and Apple
OAuth redirect flows are a poor and sometimes broken experience: they open
external browsers or in-app browsers, lose the Mini App context, and return
users to a different session than the one they started in. Meanwhile Telegram
already hands the server a cryptographically signed user identity for free
(`initData`, v0.1 R-10).

Recommendation: **treat Telegram as a first-class identity provider for the
Mini App runtime**, mapped into the same `User` via the `Identity` table, with
account linking offered (not forced) when a Telegram user later signs in on
the web with Google/Apple/email.

This is **not** in the owner's confirmed provider list, so it is recorded as a
decision: **D-33**. If rejected, the Mini App's authenticated features
(reviews, saves) will have a materially worse conversion rate than on web.

### 5.5 Session model

**[P]** Web: server-side session with a secure, `HttpOnly`, `SameSite`
cookie. Mini App: short-lived bearer token minted after `initData`
validation, refreshed by re-presenting fresh `initData`. Flutter (later):
scoped, revocable bearer tokens. One identity model, three session adapters —
consistent with v0.1 §15.2.

---

## 6. Interaction permission model

**[P]** unless marked. "Business" has no account in V1; the column is kept to
show the future shape and is marked accordingly.

### 6.1 Discovery and content

| Interaction | Guest | Registered | Business (no account in V1) | Admin/Staff | Status |
| --- | --- | --- | --- | --- | --- |
| Browse home / categories / areas | Yes | Yes | n/a (V1) | Yes | **Confirmed** |
| Search, filter, sort | Yes | Yes | n/a | Yes | **Confirmed** |
| Autocomplete | Yes | Yes | n/a | Yes | **Confirmed** |
| View business profile | Yes | Yes | n/a | Yes | **Confirmed** |
| View reviews | Yes | Yes | n/a | Yes | **Confirmed** |
| Use "nearby" (device location) | Yes (with permission) | Yes | n/a | Yes | **Confirmed** |
| Open map / "Open in Google Maps" | Yes | Yes | n/a | Yes | **Confirmed** |
| Call / website / directions / social click | Yes | Yes | n/a | Yes | **Confirmed** |
| Share a profile (web share / Telegram) | Yes | Yes | n/a | Yes | **Confirmed** |

### 6.2 Identity-dependent interactions

| Interaction | Guest | Registered | Business | Admin/Staff | Status |
| --- | --- | --- | --- | --- | --- |
| Write a review | No | Yes | n/a (V1) | Moderate | **Confirmed** |
| Edit own review (within a window) | No | Yes | n/a | Yes | **Proposed** (window length: D-34) |
| Delete own review | No | Yes | n/a | Yes | **Proposed** — soft delete, retains moderation history |
| Rate without text | No | Yes | n/a | — | **Unknown** — allowing rating-only raises volume and lowers quality; D-34 |
| Favourite / save | No | Yes | n/a | — | **Confirmed direction (NC-6)** |
| Upload a review photo | No | Yes (if enabled) | n/a | Moderate | **Unknown** — V1 candidate; adds moderation + storage cost |
| Follow a business / get updates | No | Yes | n/a | — | **Future** |
| Vote a review helpful | **Unknown** | Yes | n/a | — | **Unknown** — guest voting is trivially gameable; recommend authenticated-only |

### 6.3 Correction, reporting, safety

| Interaction | Guest | Registered | Business | Admin/Staff | Status |
| --- | --- | --- | --- | --- | --- |
| Report a problem with listing data | **Yes (rate-limited, no account)** | Yes | Via staff contact | Process | **Proposed** — lowering this barrier is the point; it is the only correction channel |
| Suggest an edit (structured) | **Unknown** | Yes | Via staff | Process | **Unknown** — guest suggestions raise spam risk; D-35 |
| Report a review (abuse, fake) | No | Yes | Via staff | Process | **Proposed** |
| Report a business (closed, fraudulent) | Yes | Yes | Via staff | Process | **Proposed** |
| Request removal of own personal data | Yes (contact path) | Yes (in-account) | n/a | Process | **Confirmed (legal)** |

### 6.4 Staff-only

| Interaction | Status |
| --- | --- |
| Create / edit / unpublish a listing | **Confirmed — staff only (NC-1)** |
| Set verification state | **Confirmed — staff only** |
| Manage categories and locations | **Confirmed — staff only** |
| Moderate reviews and reports | **Confirmed — staff only** |
| Create / approve / pause campaigns | **Proposed — staff only in V1** |
| View platform analytics | **Confirmed — staff only** |
| Export business performance report for a client | **Proposed — staff-generated artefact, not a login** |

### 6.5 Decisions this matrix raises

**D-34** review mechanics (edit window, rating-only, one-per-business
enforcement, deletion semantics) · **D-35** whether guests may submit
structured edit suggestions · **D-36** whether review photos are V1 ·
**D-37** whether "helpful" voting exists at all in V1.

---

## 7. Web + Telegram shared frontend strategy

### 7.1 The question

**[C — NC-11]** Can Web and the Telegram Mini App share essentially one
frontend codebase while preserving SEO, server-rendered public pages,
responsive design, Telegram capabilities, accessibility, performance and
shared-hosting compatibility — without introducing an unnecessary framework?

### 7.2 Answer: yes, and the reason is structural

**[P], grounded in R-16.** A Telegram Mini App *is a URL that Telegram opens
in a WebView* with `telegram-web-app.js` injected. It is not a build target, a
bundle format or a platform SDK. Any page Bulbula already serves can be a Mini
App page. Nothing about the Mini App requires a JavaScript framework, a build
step, or client-side rendering.

Therefore the recommended architecture is **one server-rendered frontend with
two runtimes**:

```text
            ┌─────────────────────────────────────────┐
            │   Bulbula domain + application services  │   (PHP, framework-free)
            └───────────────────┬─────────────────────┘
                                │
                 ┌──────────────▼──────────────┐
                 │   ONE view layer             │
                 │   layouts · components ·     │
                 │   design tokens · copy       │
                 └───────┬─────────────┬────────┘
                         │             │
              ┌──────────▼───┐   ┌─────▼──────────────┐
              │ WEB RUNTIME  │   │ TELEGRAM RUNTIME   │
              │ full chrome  │   │ TG chrome adapter  │
              │ SEO + crawl  │   │ theme vars, back   │
              │ cookie auth  │   │ button, haptics,   │
              │ web share    │   │ initData auth,     │
              │              │   │ TG share, safe area│
              └──────────────┘   └────────────────────┘
                   same URLs · same HTML · same CSS · same components
```

### 7.3 How the runtime switch works

**[P]**

- Telegram appends `tgWebAppPlatform` / `tgWebAppData` parameters when it
  launches a Mini App URL. Detect at the edge of the request, store the
  runtime in the session, and expose it to the view layer as one flag
  (`runtime: web | telegram`).
- The flag controls **chrome, not content**: site header/footer vs Telegram
  header; browser back vs Telegram `BackButton`; a page's primary action
  rendered inline vs bound to Telegram's `MainButton`; web share vs
  `switchInlineQuery`/share link.
- All Telegram API calls live in **one adapter file** loaded only in the
  Telegram runtime. Nothing else in the codebase references
  `window.Telegram`. That is the "clear client/integration boundary" NC-12
  requires, expressed concretely.
- Theming: **[C — R-16]** Telegram injects CSS variables (`--tg-theme-*`,
  `--tg-viewport-*`, safe-area insets) and emits a `themeChanged` event. If
  Bulbula's design tokens are CSS custom properties, the Telegram runtime can
  map Telegram's palette onto a *subset* of Bulbula's tokens (surfaces,
  text, hints) while keeping brand orange/blue for identity elements. This is
  a token-architecture requirement, and it is the main reason the design
  system must be built as CSS variables from the start.

### 7.4 What this preserves, and what it costs

| Requirement | Preserved? | How |
| --- | --- | --- |
| SEO | **Yes** | The web runtime serves ordinary crawlable HTML at ordinary URLs. The Mini App is the same page, not a separate SPA; Telegram content is not crawled either way |
| Server-rendered public pages | **Yes** | Unchanged from v0.1 PR-11 |
| Responsive/mobile-first | **Yes** | One stylesheet, mobile-first, with the Telegram runtime constrained to the mobile layout |
| Telegram capabilities | **Yes** | Via the adapter: theme, back button, main button, haptics, share, viewport, `initData` |
| Accessibility | **Yes, with care** | Telegram's WebView is a browser; the same semantics apply. Theme-mapped colours must still pass contrast — Telegram themes are user-controlled, so contrast must be re-checked per mapped token |
| Performance | **Yes** | No framework, no hydration; the Mini App adds one Telegram script |
| Shared hosting | **Yes** | No build server, no Node runtime, no SSR process |
| Cost | — | One flag's worth of branching, one adapter, and discipline to keep Telegram concepts out of shared components |

### 7.5 Risks and the validation this needs before the TRD

**[P]**

| Risk | Validation |
| --- | --- |
| Telegram WebView quirks (older Android WebViews, viewport height with keyboard open, `100vh` bugs) | Build a throwaway Mini App that loads three real Bulbula pages and test on low-end Android |
| Telegram themes producing poor contrast against brand colours | Prototype the token mapping against Telegram's light, dark and a custom theme |
| Navigation model mismatch (Telegram's back button vs multi-page navigation) | Decide whether the Mini App uses full page loads or fragment swaps (D-38) |
| Shared components accumulating `if (telegram)` branches | Architectural rule: branching only in layout/chrome components, never in content components; enforce with a review checklist and, if practical, an architecture test |
| Scope creep into an SPA | Keep the JS budget explicit (§8.4) |

### 7.6 On a JavaScript layer

**[P]** The interactive needs of V1 are modest: autocomplete, filter
application, gallery, map deferral, infinite/"load more", save toggle, review
form. Three options:

| Option | Fit | Verdict |
| --- | --- | --- |
| Hand-written vanilla JS modules | No dependency; full control; more code to write and test | **Viable** |
| **htmx (~14–16 KB) + Alpine.js (~7 KB)**, no build step **[C — R-18]** | Server returns HTML fragments (htmx) and local UI state stays declarative (Alpine); both load from a script tag with no bundler, which suits shared hosting and a PHP view layer | **Recommended to evaluate first** |
| An SPA framework (React/Vue/Svelte) | Build pipeline, hydration, SEO complexity, Node tooling | **Rejected** — contradicts NC-13 and the hosting constraint |

Note explicitly: **htmx/Alpine are not application frameworks and do not
violate the framework-free rule**, which governs the PHP backend. They are
small single-purpose libraries of the same class as FastRoute or Monolog.
That said, adopting them is a decision (**D-16**, reframed), not an
assumption, and the fallback (vanilla modules) is genuinely acceptable.

---

## 8. Mobile-first strategy

### 8.1 Confirmed

**[C — NC-10, NC-13]** Mobile-first from the foundation — small touch screens,
thumb-friendly controls, adequate touch targets, simplified navigation, low
bandwidth, slower devices, compact layouts, progressive enhancement — then
expanding to tablet, desktop and large desktop. The result must still behave
like a website: SEO, browser navigation, deep linking, accessibility,
shareability and desktop usability intact.

### 8.2 What "mobile-first from the foundation" means concretely

**[P]**

1. **CSS is authored mobile-first**: base styles are the phone layout;
   `min-width` media queries add tablet and desktop. No desktop-first
   overrides.
2. **One column is the default.** Multi-column is an enhancement, never an
   assumption.
3. **Design tokens are CSS custom properties** (required anyway by §7.3), with
   spacing and type scales defined at the phone size first.
4. **Touch targets**: WCAG 2.2 AA requires ≥ 24×24 CSS px (v0.1 R-11);
   recommend **44×44 for primary actions** (call, directions, save, submit)
   and 24 px minimum with spacing for dense controls like filter chips.
5. **Thumb zone**: primary actions sit in the lower half of the screen on
   mobile — on the profile page, the call/directions actions should be
   reachable without a hand shuffle. A sticky action bar on the profile page
   is justified by use, not by app-imitation.
6. **Performance budget is part of the design**, not a later optimisation:
   v0.1's LCP ≤ 2.5 s / INP ≤ 200 ms / CLS ≤ 0.1 targets, ≤ 150 KB critical
   HTML+CSS, ≤ 100 KB JS on public pages, every image sized and lazy-loaded,
   fonts subset (Ethiopic subsets are large — see §13).
7. **Progressive enhancement is mandatory**: every core flow (search, filter,
   open profile, call) works with no JavaScript. This is what makes the same
   code both crawlable and resilient on bad connections.

### 8.3 App-like without being an app imitation

**[P]** A short rule that can be applied in design review:

> Adopt a mobile-app pattern when it makes the *task* faster on a phone.
> Reject it when it exists only to make the website *look* like an app.

| Pattern | Verdict | Reason |
| --- | --- | --- |
| Sticky bottom action bar on a business profile (call / directions / save) | **Adopt** | Task-driven; the whole point of the page |
| Large tap targets, generous spacing, swipeable galleries (with arrows too) | **Adopt** | Touch ergonomics; keep a non-swipe affordance for accessibility (WCAG 2.5.7) |
| Bottom tab navigation on the **web** | **Avoid on web, allow in Telegram runtime** | On the web it costs permanent screen height, confuses browser back, and is the clearest "fake app" tell; inside Telegram it matches the host |
| Pull-to-refresh | **Avoid on web** | Conflicts with native browser behaviour |
| Full-screen modal "screens" replacing navigation | **Avoid** | Breaks deep links, back button and sharing (NC-13) |
| Skeleton loaders | **Adopt sparingly** | Only where a real wait exists; must match final layout to protect CLS |
| Hiding URLs / disabling zoom | **Reject** | Breaks accessibility and shareability |

### 8.4 Breakpoints

**[P]** Content-driven, not device-driven: base (≤ 479), 480, 768, 1024,
1280. Desktop is a *wider arrangement of the same components*, with the
search-results page gaining a persistent filter rail and a two-column layout —
not a different design.

---

## 9. Flutter roadmap position

**[C — NC-9, NC-14]** Flutter is a later, separate client. It must not share
the web frontend code; it must consume the same backend, API contracts,
product rules, terminology and design principles.

**[P] The distinction the brief asks to document:**

| | Launch priority | Long-term client roadmap |
| --- | --- | --- |
| **Now** | Web + Telegram Mini App, released together as one product launch | Web, Telegram Mini App, Flutter (Android first, then iOS) |
| **Rationale** | They share one frontend and one backend, so the marginal cost of the second client is small; Telegram is the cheapest acquisition channel in this market (v0.1 R-06) | Native distribution, push notifications, better location UX, offline |
| **Gate to start Flutter** | — | **[P]** (a) the public API contract is stable and versioned; (b) web/Telegram usage justifies it; (c) there is capacity to maintain a third client. Not a date |

**[P] What V1 must do for Flutter, and nothing more:** keep the JSON API
versioned (already true), keep product rules server-side, keep terminology
consistent (§23 glossary binding), and avoid any rule that only exists in the
view layer. No Flutter-specific endpoints, no premature "mobile API" work.

**[P] One caution to record now:** Apple's App Store guidelines require apps
that offer third-party social login to also offer Sign in with Apple
(with narrow exemptions). Since Apple login is already a V1 decision (NC-5),
this alignment is free — but it is another reason the Apple Developer Program
dependency (D-32) should be resolved early rather than at app-submission time.

---

## 10. Advertising V1 recommendation

### 10.1 What company-managed listings change

**[P]** In a two-sided model, advertising needs a purchase funnel. In a
company-managed model, **the sales conversation is already a human
conversation**: a Bulbula operator who created the listing is the same person
who can sell a sponsorship. Self-service purchase would be machinery nobody
uses.

### 10.2 Recommended V1 model: "real subsystem, internal controls"

**[P]**

| Component | V1 | Later |
| --- | --- | --- |
| **Placements** (named, finite slots with position and cap rules) | **Yes** — `search.top`, `category.sponsor`, `home.promoted` | `profile.related`, `feed.inline`, banners |
| **Packages** (sellable bundles: placement + targeting + duration + price) | **Yes** — a small catalogue (3–5) | Larger catalogue, tiers |
| **Campaigns** with states and date ranges | **Yes** | Self-service creation |
| **Targeting** | **Yes, coarse**: category and/or area | Query intent, dayparting |
| **Availability/overselling guard** | **Yes** — essential with fixed packages | Waiting lists |
| **Approval** | **Yes**, but internal (a second operator or the administrator) | External advertiser submission |
| **Delivery with labelling and caps** | **Yes** | Rotation strategies, frequency caps |
| **Impression/click accounting** (deduplicated, bot-filtered) | **Yes** | Attribution modelling |
| **Performance reporting** | **Yes, internal** — staff export/send a report | Business-facing dashboard |
| **Billing** | **Record of order + invoice + payment received**, entered by staff (v0.1 PR-9) | Gateway, self-service |
| **Business login to manage campaigns** | **No** | Yes, when owner accounts exist |

### 10.3 Invariants that must hold even in the simplified model

**[C]** Organic ranking is computed separately; sponsored content is always
clearly labelled and distinguishable; placement rules exist; campaign records
exist; performance is tracked.

**[P]** Additionally: one consistent label word across both runtimes (D-10);
a documented maximum sponsored items per surface; sponsored businesses must
meet a quality bar (verified, complete, not under correction); and the public
"how ranking works" page states plainly that sponsorship never changes organic
order.

### 10.4 A conflict of interest this model creates — worth naming

**[X] New.** When Bulbula both *writes* the listing and *sells* the
placement, the integrity boundary that v0.1 drew between advertising and
organic ranking becomes an **internal** boundary rather than a structural one.
A staff member could improve a paying business's organic position by
"improving its data".

**[P] Mitigations, all cheap:** ranking inputs are objective and recorded
(completeness, hours, rating, distance, freshness); listing edits are
audit-logged with actor and reason; the admin "explain this ranking" view
(v0.1 PR-3) applies to staff too; and a periodic internal check compares the
organic positions of sponsored versus non-sponsored businesses. Record as
**D-39**.

---

## 11. Reviews and interactions recommendation

**[P]** throughout; the final policy is D-12 + D-34, not this section.

| Question | Recommendation | Why |
| --- | --- | --- |
| **Who may review** | Authenticated users only (**[C]** NC-6), account age ≥ some minutes, email/provider-verified | Lowest-friction defence against drive-by spam |
| **Verified visit required?** | **No** in V1 | Bulbula has no transaction data; requiring proof would yield almost no reviews |
| **One per business** | Yes — one active review per user per business; a second submission edits the first | Prevents rating stuffing; standard expectation |
| **Business or branch?** | Attach to **business** in V1, with an optional `branch_id` captured when the user came from a branch page | Most V1 listings are single-branch; keeping the column avoids a migration (§12) |
| **Edit** | Allowed, with an edit timestamp shown; re-enters moderation if text changes | Honest and simple |
| **Delete** | Soft delete by the author; the aggregate recalculates; moderation history retained | Data-subject rights + audit |
| **Rating without text** | **Decision needed (D-34)**. Recommendation: allow, because it dramatically increases volume for a young directory, but exclude rating-only entries from "review count" display and weight them lower in ranking | Volume vs signal |
| **Owner responses** | **Not in V1** — no owner account exists. Interim: a business may ask staff to post a clearly-marked "Response provided by the business, published by Bulbula" | Honest labelling beats silence |
| **Moderation** | Pre-publication for the first N reviews from a new account, post-publication thereafter, with sentiment-neutral criteria (v0.1 PR-7) | Balances speed and safety |
| **Reporting** | Authenticated users and staff; businesses via staff contact | §6.3 |
| **Rating aggregate** | Bayesian smoothing with a global prior (v0.1 PR-3) | A single 5★ must not beat 40 reviews at 4.6 |
| **Anti-spam** | Rate limits per account/IP, duplicate-text detection, velocity alerts, no links in review text, `rel="nofollow ugc"` where any user text is rendered | Cheap, effective |
| **Transparency** | Show rating distribution, total count, and the review policy link on every profile | FTC-grade integrity (v0.1 R-01), and it is also an SEO asset (v0.1 R-15) |

**Favourites/saves [P]:** authenticated-only (NC-6), private by default, no
social graph in V1, instantly reversible, and usable as a weak popularity
signal in ranking — but only in aggregate and only if resistant to
manipulation.

---

## 12. Business / branch model recommendation

### 12.1 Recommendation

**[P] Adopt `Business → Branch` as the canonical model, with exactly one
`Branch` row for a single-location business.** Reviews attach to `Business`
with an optional `branch_id`. This is v0.1 PR-2, now strengthened by the
company-managed model.

### 12.2 Why the company-managed model makes this easier, not harder

The usual objection to a mandatory branch row is onboarding friction: asking a
shop owner to create a "business" and then a "branch" is confusing. **In V1
there is no owner-facing form at all** — staff use an internal console, where
the console can hide the distinction entirely ("Add listing" creates both
records). The conceptual cost drops to zero while the structural benefit
remains. **[P]**

### 12.3 Where each concept attaches

| Concept | Attaches to | Reasoning |
| --- | --- | --- |
| Name, description, logo, category, social links, website | **Business** | Brand-level identity |
| Address, coordinates, phone, opening hours, photos of the place | **Branch** | Physically located facts |
| Services, products, prices | **Business**, with optional per-branch override later | Usually brand-level; overrides are rare |
| Reviews | **Business** (+ optional `branch_id`) | Most reviews are about the brand experience; keeping branch context allows a later split without data loss |
| Rating aggregate | **Business**, with per-branch aggregate computed when branches > 1 | Avoids an empty-looking new branch |
| Analytics events | **Branch** where location matters (directions, calls), **Business** otherwise | Distance and contact actions are location facts |
| Advertising campaigns | **Business**, targeted to area(s) where its branches are | Sales is brand-level |
| URL | `/b/{business-slug}` canonical; `/b/{business-slug}/{branch-slug}` only when branches > 1 | Avoids two URLs for the same thing — important for SEO |
| Structured data | `LocalBusiness` per branch; `Organization` for multi-branch parents | Matches schema.org semantics |

### 12.4 Open sub-decisions

**D-03** remains open only on these points: whether single-branch businesses
expose a branch URL at all (recommend no); whether per-branch hours may
diverge in V1 (recommend yes, since it is free once the model exists); and
whether per-branch ratings are displayed (recommend only above a review
threshold).

---

## 13. Category strategy

### 13.1 Recommendation

**[P]**

| Dimension | Recommendation |
| --- | --- |
| **Source** | Do not invent from scratch and do not import wholesale. Google's public category vocabulary is the de-facto standard with roughly 4,000 entries (**[C — R-19]**) — far too many for Bole Bulbula. Curate a **starting set of ~100–200** from (a) what actually exists in the area, (b) the Google vocabulary's naming, so later alignment and import are easy |
| **Depth** | **Two levels** (category → subcategory). Deeper taxonomies are unmaintainable by a small team and dilute per-page content for SEO |
| **Cardinality per listing** | One **primary** category (drives the URL, the breadcrumb and ranking) plus up to ~3 secondary categories. Google's own guidance warns against category stuffing (**[C — R-19]**), and the same dilution logic applies internally |
| **Naming** | English canonical name + Amharic label from day one; slugs are **English, ASCII, stable, never reused** (§ SEO) |
| **Synonyms/aliases** | A first-class alias table per category (`pharmacy` ≈ `drug store` ≈ `መድኃኒት ቤት` ≈ `farmacia` typos), editable by staff, folded into the search document (v0.1 PR-4). This is the cheapest search-quality lever Bulbula has |
| **Governance** | Staff-only; adding a category is a deliberate act with an owner, because every category is a potential indexable page |
| **Growth rule** | A new category is created only when ≥ N real listings justify it (recommend N = 3), to avoid empty pages (v0.1 §23.3 thin content) |

### 13.2 Why the taxonomy is a V1-blocking decision

It determines URLs (permanent), navigation, the search document, ad targeting
("category sponsorship") and the SEO surface simultaneously. **D-06 stays
P0.** What this phase adds is the *method*: curate from an established
vocabulary, two levels, alias-backed, growth-gated.

---

## 14. Location strategy

### 14.1 Research position

**[C — R-20]** Addis Ababa is administratively divided into **11 sub-cities**
since the 2020 addition of Lemi Kura (previously 10), which are further
divided into *woredas* and historically *kebeles*. Sub-city and woreda are
real administrative units, but everyday wayfinding in Addis uses
**neighbourhood/area names and landmarks** ("Bole Bulbula", "Ayat", "Megenagna",
"around the St. George church"), not woreda numbers.

**[U]** Public sources place "Bole Bulbula" in **Bole sub-city**, but
sub-city boundaries were redrawn in 2020 and some sources disagree; the exact
administrative parent, and the area's practical boundary, must be confirmed
locally before the location tree is seeded. → **D-40**.

### 14.2 Recommended hierarchy

**[P]**

```text
Country (Ethiopia)
  └── City / Region          (Addis Ababa)
        └── Sub-city          (Bole, Yeka, Akaki Kality, …)   ← administrative
              └── Area        (Bole Bulbula, Ayat, Summit, …) ← user-facing
                    └── Landmark (optional, text + coords)    ← how people navigate
```

| Rule | Reason |
| --- | --- |
| **Area is the user-facing unit** — filters, URLs, "category × area" pages and ad targeting all operate on Area | It is how customers think and search |
| Sub-city is stored for every listing but is **secondary** in the UI | Keeps the data administratively correct and enables later city-wide reporting |
| *Woreda* is an **optional operational field**, not a navigation level | Useful for staff and verification; meaningless to customers |
| Areas are **curated, not free text**, with aliases ("Bulbula", "ቡልቡላ", "Bole Bulbula") | Same alias mechanism as categories |
| Areas have an approximate centre point and optional radius, not polygons | Enough for distance sorting and "near me" without geospatial infrastructure (v0.1 PR-5) |
| The hierarchy is populated **one branch deep** in V1 (Ethiopia → Addis Ababa → Bole → Bole Bulbula + immediate neighbours) | v0.1 §4.1: expandable shape, no national-scale complexity |

### 14.3 Why "one area" is still the right launch scope

**[P]** Coverage beats breadth for a directory (v0.1 R-07). The hierarchy
exists so that adding the next area is a data task, not a migration. The
product should ship with one deeply-covered area and the *ability* to add the
next one the day it is justified.

---

## 15. Legal and compliance decision list

**[C]** Nothing here is resolved by this document. Items marked **⚖ counsel**
require professional legal advice and must not be answered by engineering.

| # | Item | Status | Note |
| --- | --- | --- | --- |
| L-1 | Applicability of Proclamation 1321/2024 to Bulbula | **⚖ counsel** | Assume it applies (v0.1 R-05) |
| L-2 | **Data residency** — personal data stored on servers in Ethiopia | **⚖ counsel**, **[X] X-01 unresolved** | Collides with Cloudflare R2, Cloudflare proxying, foreign hosting, and now with Google/Apple identity providers |
| L-3 | ECA registration of controller/processor | **⚖ counsel** | Timing and applicability unknown |
| L-4 | DPO appointment requirement | **⚖ counsel** | Unknown for a company of this size |
| L-5 | Lawful basis per processing purpose (accounts, reviews, analytics, ads) | **⚖ counsel** + product | Consent must be unbundled and withdrawable |
| L-6 | Privacy notice + cookie/consent mechanism | Product, **[C] V1 requirement** | Needed before launch |
| L-7 | Data-subject rights: access, rectification, erasure, portability | Product, **[C] V1 requirement** | Needs an operational path, not just a promise |
| L-8 | 72-hour breach notification procedure | Operations, **[C] requirement** | Belongs in the incident-response runbook |
| L-9 | Minors: minimum account age; prohibition on processing minors' data for marketing/profiling | **⚖ counsel** + product | Argues for a stated minimum age at signup |
| L-10 | **Third-party identity providers as cross-border transfers** (Google, Apple) | **⚖ counsel** — **new this phase** | Sign-in necessarily discloses data to a foreign processor; interacts with L-2 |
| L-11 | Apple private email relay handling and retention | Product + **⚖ counsel** | Relay addresses are still personal data |
| L-12 | **Telegram as a processor** (if Telegram identity is adopted) | **⚖ counsel** — new | Same question as L-10 |
| L-13 | Verification evidence: what staff may record, store and retain | **⚖ counsel** | *Reduced risk this phase*: V1 records internal notes, not uploaded third-party documents (§4.2) |
| L-14 | **Listing data about businesses without their consent** | **⚖ counsel** — **new and material this phase** | A company-managed directory publishes business names, phone numbers, photos and locations with no owner relationship. Business contact data can be personal data where the business is a sole trader. Needs: a lawful basis, a published removal/objection path, and a documented response time |
| L-15 | Review content liability: defamation exposure and takedown obligations | **⚖ counsel** | Ethiopian law position unknown |
| L-16 | Advertising disclosure requirements under Ethiopian consumer law | **⚖ counsel** | v0.1 used FTC guidance as a proxy standard |
| L-17 | Advertising records, invoices and tax/VAT obligations | **⚖ counsel** + finance | Interacts with D-11 |
| L-18 | Photography of business premises and people: consent and publication rights | **⚖ counsel** | New: staff will be taking photos in the field |
| L-19 | Google Maps Platform terms compliance (embedding, caching, attribution) | Product/legal review | Interacts with D-21 |
| L-20 | Trade licence / entity status required to sell advertising and invoice | **⚖ counsel** + finance | Blocks revenue, not launch |

**[P] The single most important addition this phase is L-14.** The shift to
company-managed listings moves Bulbula from "platform hosting owner-submitted
data" to "publisher of a business database". That is a different legal
posture, and it should be put to counsel explicitly rather than assumed to be
covered by L-1.

---

## 16. Open decisions (still required)

Every decision from v0.1's register (D-01…D-30) is reclassified below into
**A / B / C / D / E**, together with the new decisions this phase raised
(D-31…D-40). The classification is the operative output of this section.

**Classes**
**A** — must be decided **before the PRD** ·
**B** — must be decided **before UX design or the data model** ·
**C** — can be decided **during implementation** ·
**D** — **deferred** to a future version ·
**E** — **resolved**.

### 16.1 Class A — decide before the PRD (10)

| ID | Decision | Why it blocks the PRD | Owner input needed |
| --- | --- | --- | --- |
| **D-01** | Exact V1 scope — accept, trim or extend §3 | The PRD is a scope document; everything else hangs off it | Approve/modify the §3 list |
| **D-05** | Which discovery surfaces exist in V1 (home sections, nearby, collections) | Defines the page inventory | Pick from §3.2 |
| **D-06** | Category taxonomy: source, depth, naming, cardinality, growth rule | URLs and navigation are permanent | Approve §13 method + sign off the first list |
| **D-10** | V1 advertising products, inventory caps, label wording | Defines the revenue surface and the integrity rules | Approve §10 |
| **D-12** | Review eligibility and moderation criteria | Determines the only UGC subsystem in V1 | Approve §11 |
| **D-18** | Language strategy (see §16.4 for the A/B/C options and recommendation) | Affects every string, URL, font and content workflow | Choose A, B or C |
| **D-22** | Legal/compliance: entity, registration, residency, DPO, retention | Can invalidate hosting and identity choices (L-2, L-10, L-14) | Engage counsel |
| **D-24** | Notification channel + provider | **Email login (NC-5) cannot ship without reliable email delivery** | Approve a provider budget |
| **D-30** | Definition of launch (coverage, quality and readiness bar) | Decides when V1 is done | Set the numbers |
| **D-31** | **NEW — Operations capacity**: how many staff, how many listings per staff-day, and the coverage target at launch | In a company-managed model this sets the real scope ceiling (RK-1) | State the team and the target |
| **D-32** | **NEW — Apple Developer Program** enrolment owner, 99 USD/yr budget, and client-secret rotation procedure | Apple login (NC-5) is otherwise undeliverable | Confirm who enrols, when |
| **D-33** | **NEW — Telegram as a fourth identity provider** for the Mini App runtime | Determines whether Mini App users can review/save at all in practice (§5.4) | Yes / No |
| **D-40** | **NEW — Confirm "Bole Bulbula"'s administrative parent and the launch boundary** | Seeds the location tree and defines coverage for D-30 | Local confirmation |

*(13 entries: D-01, D-05, D-06, D-10, D-12, D-18, D-22, D-24, D-30, D-31, D-32, D-33, D-40.)*

### 16.2 Class B — decide before UX design or the data model (15)

| ID | Decision | Recommendation already on the table |
| --- | --- | --- |
| **D-03** | Business/branch canonical model | §12 — adopt `Business → Branch`, one branch minimum |
| **D-04** | Opening-hours model (regular, split shifts, exceptions, holidays, 24h) | v0.1 §11.3; needed for "open now" |
| **D-07** | Price representation and whether a price filter exists | Recommend "price range" indicator in V1, exact prices deferred |
| **D-08** | Verification rules, tiers, evidence, expiry — **now internal only** | §4.2 — method + date + operator + re-verification interval |
| **D-09** | Profile-completeness definition and its ranking weight | v0.1 PR-3; also the operations console's quality score |
| **D-13** | Identity model across clients | §5.2 — one `User`, many `Identity` rows, no email auto-merge |
| **D-16** | Frontend JS approach (reframed from "framework for dashboards") | §7.6 — evaluate htmx + Alpine; fallback vanilla modules; SPA rejected |
| **D-17** | View/template layer: in-house vs library | Must support §7's one-view-layer-two-runtimes model |
| **D-19** | Dark mode | **Upgraded in importance**: Telegram supplies a user-controlled dark theme (§7.3), so token architecture must handle it even if the website ships light-only |
| **D-21** | Google Maps key, billing owner, embed strategy, fallback | Affects profile-page UX and cost |
| **D-25** | Media storage provider, limits, formats, video | Interacts with L-2 residency and with staff field capture |
| **D-27** | Analytics granularity, retention, raw-event policy | Interacts with L-5 and D-22 |
| **D-34** | **NEW — Review mechanics**: edit window, rating-only allowed, deletion semantics | §11 |
| **D-35** | **NEW — May guests submit structured edit suggestions** (vs only free-text reports) | §6.3 — recommend free-text report for guests, structured for accounts |
| **D-38** | **NEW — Mini App navigation model**: full page loads vs fragment swaps | §7.5 — affects the Telegram back-button contract |
| **D-39** | **NEW — Integrity controls** separating ad sales from staff-controlled listing data | §10.4 |

### 16.3 Class C — decide during implementation (7)

**D-11** billing mechanics (staff-entered invoices in V1) · **D-14** admin
role split (`operator` + `administrator` is now more likely, since operators
are the primary product users) · **D-20** confirmed host resource limits and
MariaDB tuning · **D-23** production config management and secret custody ·
**D-26** coverage/MSI gate scope as the domain grows · **D-28** temporary
brand-mark policy · **D-29** documentation versioning and approval authority
(proposal in §21).

### 16.4 D-18 — language strategy: options and recommendation

**Research [C — R-21]:** Ethiopia elevated Afaan Oromo, Tigrinya, Somali and
Afar to federal working languages alongside Amharic in 2020 (five total).
Amharic remains the lingua franca of Addis Ababa; English is widely used in
business, signage, banking and education.

| Option | What it means | Cost | Risk |
| --- | --- | --- | --- |
| **A — English-first, bilingual-ready** *(recommended)* | UI ships in English; **Amharic business names, category labels and area aliases are stored and searchable from day one**; all UI strings externalised; locale in URLs reserved but unused | Lowest | Some users prefer an Amharic UI; mitigated by Amharic content and search working |
| **B — Full bilingual at launch** | Every string, page and piece of content in both languages | High: translation, review, Ethiopic web fonts (large files, hurts the §8.2 performance budget), duplicated SEO surfaces, two content workflows per listing | Doubles the content treadmill that D-31 already constrains |
| **C — Amharic-first** | Amharic is the default UI | Same as B plus weaker English SEO | Misaligned with business-facing and diaspora usage |

**[P] Recommendation: Option A**, with three non-negotiables so that B remains
cheap later: (1) no hard-coded user-visible strings, ever; (2) every name-like
field has an Amharic counterpart in the schema from the first migration;
(3) the alias/synonym tables (§13, §14) accept Ethiopic script so Amharic
*search* works at launch even though the *interface* is English.
Afaan Oromo is **Future** and only becomes relevant on expansion beyond Addis.

---

## 17. Deferred decisions (Class D)

| ID | Item | Defer until |
| --- | --- | --- |
| **D-15** *(residual)* | Whether Flutter happens within year one | Web/Telegram usage data exists (§9 gate) |
| **D-36** | Review photos | Moderation capacity and storage costs are known |
| **D-37** | "Helpful" voting on reviews | Review volume justifies ranking reviews at all |
| — | Owner-created listings | Post-V1 (NC-2); seams in place (§4.3) |
| — | Owner claim workflow | Post-V1 (NC-2) |
| — | Business accounts and dashboard | Post-V1 |
| — | Owner replies to reviews | With business accounts |
| — | Self-service ad purchase, payment gateway | With business accounts + L-17/L-20 resolved |
| — | Business-facing analytics | Staff-exported reports suffice in V1 |
| — | Messaging/leads between customers and businesses | Needs anti-abuse and legal review |
| — | Bookings, orders, delivery, payments | Explicitly out (v0.1 §2.3) |
| — | Multi-city expansion | Location tree is already shaped for it (§14) |
| — | Afaan Oromo and further languages | Expansion beyond Addis |
| — | Native push notifications | Flutter client |
| — | Polygon geography / PostGIS-class features | Area centroid + radius suffices (§14.2) |

---

## 18. Resolved decisions (Class E)

| ID / item | Resolution | Source |
| --- | --- | --- |
| **D-02 — pre-seeded vs owner-created listings** | **RESOLVED: Bulbula creates and manages all V1 listings.** Owner creation and claiming are Future | **NC-1, NC-2, NC-3** |
| **D-15 — client sequencing (launch part)** | **RESOLVED: Web + Telegram Mini App launch together; Flutter later** | **NC-9** |
| Owner onboarding in V1 | **RESOLVED: does not exist** | NC-1 |
| Claim workflow in V1 | **RESOLVED: does not exist** | NC-1 |
| Guest browsing | **RESOLVED: supported** | NC-4 |
| Auth providers for V1 | **RESOLVED: Google, Apple, email** (Telegram still open as D-33) | NC-5 |
| Authentication required for reviews and saves | **RESOLVED: yes** | NC-6 |
| Identity direction | **RESOLVED in direction: one Bulbula account with linked identities** (data model still B/D-13) | NC-8 |
| Mobile-first web | **RESOLVED: mobile-first from the foundation** | NC-10 |
| Shared Web/Telegram frontend | **RESOLVED in direction: share one frontend, isolate Telegram behind a boundary** — validated as technically sound in §7, pending the prototype in §7.5 | NC-11, NC-12 |
| Flutter code sharing | **RESOLVED: no frontend sharing; backend, contracts, rules and terminology shared** | NC-14 |
| Framework-free PHP backend, modular monolith | Previously confirmed, unchanged | v0.1 |

### 18.1 Off the blocker list — stated explicitly as required

- **Owner-created listings** — ❌ no longer a blocker. Future (NC-2).
- **Owner claims** — ❌ no longer a blocker. Future (NC-2).
- **First-launch client priority** — ❌ no longer a blocker. Web + Telegram
  Mini App together (NC-9).

**What replaces them at the top of the blocker list:** D-31 (operations
capacity), D-06 (taxonomy), D-18 (language), D-30 (definition of launch),
D-22 (legal, especially L-14), D-24 (email deliverability, which gates email
login), and D-32 (Apple Developer Program).

### 18.2 Delta against v0.1 — what in that document is now outdated

| v0.1 location | Statement | Status in v0.2 |
| --- | --- | --- |
| §2 V1 list items #10, #11, #16 | Business accounts, claims, owner dashboard in V1 | **Superseded** — Future (§3.3) |
| §25.4 Cut A vs Cut B | Open strategic choice | **Largely settled**: NC-1 makes V1 a directory-first product; the residual choice is only how much of §3.2 to include (D-01) |
| D-02 | Open P0 | **Resolved** |
| D-15 | Open P1 | **Resolved for launch**; residual Flutter timing deferred |
| D-16 | "May dashboards use a frontend framework" | **Reframed** as §7.6; SPA rejected |
| §33 status table | As published | **Carried forward verbatim** at the top of this document, with additions listed separately |
| v0.1 overall | — | **Remains valid as source material**; it is not rewritten, and this section is the authoritative list of changes |

---

## 19. Updated capability map

**V1** = in the first release · **Later** = planned, post-V1 ·
**Future** = conditional/unscheduled · **Unknown** = undecided.

| # | Capability area | Status | Note |
| --- | --- | --- | --- |
| 1 | **Guest browsing** | **V1** | Confirmed (NC-4) |
| 2 | **Search, filtering, sorting** | **V1** | Core verb; autocomplete included |
| 3 | **Business profiles** | **V1** | `Business → Branch` (§12) |
| 4 | **Categories / taxonomy** | **V1** | Two levels, curated, alias-backed (§13) |
| 5 | **Locations / area hierarchy** | **V1** | City → Sub-city → Area (§14) |
| 6 | **Maps and directions** | **V1** | Embed + deep link; deferred-load |
| 7 | **Opening hours / open-now** | **V1** | D-04 |
| 8 | **Customer accounts & auth** | **V1** | Google, Apple, email (+ Telegram pending D-33) |
| 9 | **Reviews & ratings** | **V1** | Authenticated, moderated (§11) |
| 10 | **Favourites / saves** | **V1** | Authenticated, private |
| 11 | **Reports & corrections** | **V1** | The only inbound data signal (§6.3) |
| 12 | **Listing management (internal console)** | **V1** | The production line (§3.2) |
| 13 | **Verification & provenance** | **V1** | Internal process (§4.2) |
| 14 | **Moderation** | **V1** | Reviews + reports only |
| 15 | **Advertising / sponsored placement** | **V1** | Internally administered (§10) |
| 16 | **Billing & payments** | **V1 (manual records only)** | Invoice + payment recorded by staff; gateway is Later |
| 17 | **Media management** | **V1** | Upload + derivatives; video is Later |
| 18 | **Notifications** | **V1 (minimum one channel)** | Gated by D-24; blocks email login |
| 19 | **Analytics & reporting** | **V1 (internal)** | Business-facing dashboards are Later |
| 20 | **SEO & structured data** | **V1** | Non-negotiable for a directory |
| 21 | **Admin roles & audit** | **V1** | D-14 split likely needed |
| 22 | **Web frontend (mobile-first)** | **V1** | §8 |
| 23 | **Telegram Mini App** | **V1** | Same frontend, Telegram runtime (§7) |
| 24 | **Internationalisation readiness** | **V1 (architecture) / Later (Amharic UI)** | Option A (§16.4) |
| 25 | **Legal & privacy operations** | **V1** | DSR path, consent, breach procedure (§15) |
| 26 | **Flutter mobile app** | **Later** | §9 |
| 27 | **Business accounts & dashboard** | **Future** | Post-V1 (NC-2) |
| 28 | **Owner-created listings / claims** | **Future** | Seams only (§4.3) |
| 29 | **Owner replies to reviews** | **Future** | Needs owner accounts |
| 30 | **Self-service ad purchase** | **Future** | Needs owner accounts + L-17/L-20 |
| 31 | **Messaging / leads** | **Future** | Abuse + legal review |
| 32 | **Transactions (booking, ordering, payments)** | **Out of scope** | v0.1 §2.3 |
| 33 | **Multi-city expansion** | **Future** | Data model ready |
| 34 | **Dark mode (web)** | **Unknown** | D-19; required in the Telegram runtime regardless |
| 35 | **Review photos** | **Unknown** | D-36 |
| 36 | **Services / products with prices** | **Unknown** | D-07 |

---

## 20. Updated product loops

v0.1 described loops for a two-sided marketplace. Under NC-1 the engine
changes: **Bulbula itself is the supply side**.

### 20.1 Loop 1 — Discovery (customer value loop) — unchanged, now primary

```text
need → search → relevant, correct result → contact/visit → success
  → trust in Bulbula → return next time → more usage data → better ranking
```
**Health metrics [P]:** zero-result search rate, search→profile rate,
profile→contact-action rate, return rate within 30 days.

### 20.2 Loop 2 — Operations / data quality (NEW — the V1 engine)

```text
staff discovery (field, phone, web) → create listing → verify → publish
    → customer finds it → correction reports + staleness alerts
        → staff re-verify and update → coverage and freshness rise
            → more traffic → more corrections → compounding accuracy
```
**Why it replaces the supply loop:** in V1 nothing else grows the directory.
**Health metrics [P]:** listings added per staff-day, % verified in the last
N days, median correction turnaround, stale-listing backlog.
**Failure mode:** staff time is finite, so this loop plateaus — which is
exactly what D-31 must size.

### 20.3 Loop 3 — Trust

```text
accurate data + visible verification + honest reviews + clear ad labels
   → user trust → reviews and corrections contributed → more accuracy
```
**[P] Changed emphasis:** with no owner to vouch for data, the verified badge
and the *last-updated* date carry more weight than in a claim-based model.
Showing "verified by Bulbula on <date>" is a stronger signal than "claimed".

### 20.4 Loop 4 — Revenue (sales-led, offline-first)

```text
coverage + traffic → demonstrable value to a business → staff sells a package
   → campaign runs → staff reports performance → renewal
        → revenue funds more operations capacity → more coverage
```
**[P] This is the loop that closes the D-31 constraint**: revenue is what
converts into staff hours. The link between the revenue loop and the
operations loop should be stated in the PRD as a deliberate strategy, not left
implicit.

### 20.5 Loop 5 — Distribution / SEO + Telegram

```text
indexable category × area pages + shareable profiles
   → organic search traffic and Telegram shares
       → new users → more reviews and corrections → richer pages → better rank
```
**[P]** Telegram sharing is the fastest-acting half of this loop in the
Ethiopian market (v0.1 R-06); SEO is the slower, compounding half.

---

## 21. Documentation hierarchy proposal

**[P]** Carried forward from v0.1 §33 and extended for the V1 shape.

```text
docs/
├── 00-discovery/                  ← frozen, historical
│   ├── project-understanding-v0.1.md
│   ├── research-notes-v0.1.md
│   └── product-decision-brief-v0.2.md        ← this document
├── 10-product/
│   ├── prd-v1.md                             ← next phase
│   ├── scope-v1.md                           ← the agreed §3 list
│   ├── decision-register.md                  ← D-xx, living, supersedes §16
│   ├── glossary.md                           ← binding terminology (§22)
│   ├── interaction-permissions.md            ← living version of §6
│   ├── listing-operations.md                 ← NEW: how staff build listings
│   ├── review-policy.md                      ← public + internal criteria
│   ├── advertising-policy.md                 ← products, labels, integrity
│   ├── taxonomy/categories.md
│   └── taxonomy/locations.md
├── 20-design/
│   ├── design-principles.md
│   ├── design-tokens.md                      ← CSS variables; Telegram mapping
│   ├── mobile-first-guidelines.md            ← living version of §8
│   └── components.md
├── 30-technical/
│   ├── architecture.md                       ← relocated from docs/
│   ├── deployment.md                         ← relocated from docs/
│   ├── data-model.md
│   ├── api-contract.md
│   ├── web-telegram-runtime.md               ← NEW: the §7 boundary
│   ├── auth-architecture.md                  ← NEW: identity + sessions
│   ├── search-architecture.md
│   └── trd-v1.md                             ← later phase
├── 40-operations/
│   ├── runbook.md                            ← incl. Apple client-secret rotation
│   ├── quality-gates.md
│   ├── incident-response.md                  ← incl. 72-hour breach procedure
│   └── data-quality-sop.md                   ← NEW: verification + correction SOP
└── 50-legal/
    ├── compliance-register.md                ← living version of §15
    ├── privacy-notice.md
    ├── terms-of-service.md
    └── data-retention.md
```

**[P] Conventions:** one topic per file; every document carries
status/version/date/owner; decisions live **only** in the decision register
(other documents reference D-xx, never restate them); discovery documents are
never edited after supersession, only pointed at. **D-29** formalises the
approval authority.

---

## 22. AI context architecture proposal

**[P] Not created in this phase** (explicitly out of scope). This is the
proposed shape for when the owner approves it.

| File | Purpose | Source of truth it points to |
| --- | --- | --- |
| `.ai/README.md` | How to use this folder; reading order | — |
| `.ai/project.md` | What Bulbula is, who it serves, V1 boundaries in ~1 page | `10-product/scope-v1.md` |
| `.ai/architecture.md` | Modular monolith, layering, framework-free rule, module boundaries | `30-technical/architecture.md` |
| `.ai/conventions.md` | PHP style, naming, test conventions, commit/PR rules | `40-operations/quality-gates.md` |
| `.ai/domain.md` | Entities, relationships, invariants, lifecycle states | `30-technical/data-model.md` |
| `.ai/glossary.md` | Binding terminology (one word per concept, both languages) | `10-product/glossary.md` |
| `.ai/constraints.md` | Hard rules: no framework, shared hosting, mobile-first, no Telegram patterns in web, no lowered quality gates | this brief + v0.1 |
| `.ai/current-focus.md` | What is being worked on right now and what is explicitly not | updated per phase |

**[P] Principles:** each file is short enough to be pasted into a context
window; no file duplicates a decision (it links to the register); every file
states what is **forbidden** as explicitly as what is required, because that is
the instruction class AI assistants most often violate; and `current-focus.md`
is the only file expected to change frequently.

**[P] Terminology discipline** (the highest-value item in this list): fix one
word per concept now — *business*, *branch*, *listing*, *category*,
*subcategory*, *area*, *sub-city*, *review*, *rating*, *save*, *campaign*,
*placement*, *package*, *operator*, *administrator*, *verification*,
*correction*. Inconsistent naming between the PRD, the schema and the UI is
the single most common source of AI-assisted drift.

---

## 23. Risks

Severity × likelihood, with the owner action that would reduce each. New or
materially changed risks are marked **NEW**.

| ID | Risk | Sev | Lik | Mitigation / owner action |
| --- | --- | --- | --- | --- |
| **RK-1** | **NEW — Operations capacity ceiling.** Every listing costs staff time forever; coverage and freshness plateau at team size | **High** | **High** | Size it (D-31): measure listings per staff-day in a pilot before setting the launch bar (D-30). Design the console for throughput, not elegance |
| **RK-2** | **NEW — Data decay.** Company-managed data goes stale with no owner to correct it; a directory that is wrong once loses the user permanently | High | High | Freshness SLA per listing, staleness alerts, prominent correction path (§6.3), show "last verified" publicly |
| **RK-3** | **NEW — L-14 legal exposure.** Publishing business data without an owner relationship is a publisher posture, not a platform posture | High | Medium | Put L-14 to counsel explicitly; publish a removal/objection path from day one |
| **RK-4** | Cold start: too few listings to be useful | High | Medium | Unchanged from v0.1 — depth in one area first (§14.3) |
| **RK-5** | **NEW — Apple login undeliverable** (99 USD/yr programme, Services ID, domain verification, 6-month secret rotation) | Medium | **High** | D-32 now; otherwise drop Apple from V1 rather than slipping launch |
| **RK-6** | **NEW — Email login undeliverable** from shared hosting (spam filtering, no reputation) | Medium | **High** | D-24 now; budget a transactional email provider or drop email login |
| **RK-7** | **NEW — Telegram runtime quirks** break the shared frontend assumption on low-end Android WebViews | Medium | Medium | Build the §7.5 throwaway prototype **before** the TRD |
| **RK-8** | **NEW — Shared-component contamination**: `if (telegram)` branches spread through the view layer | Medium | Medium | Architectural rule + review checklist (§7.5); keep the adapter the only Telegram-aware file |
| **RK-9** | **NEW — Ad/editorial conflict of interest** (Bulbula writes the data *and* sells placement) | Medium | Medium | D-39 controls: audit log, objective ranking inputs, periodic sponsored-vs-organic position check |
| **RK-10** | Ranking quality is poor with sparse data | Medium | High | Bayesian priors, completeness weighting, zero-result monitoring (v0.1 PR-3/PR-4) |
| **RK-11** | Review spam / fake reviews | Medium | Medium | §11 anti-spam set; authenticated-only is already the biggest lever |
| **RK-12** | Shared-hosting limits (CPU, memory, no persistent workers) throttle search and image processing | Medium | Medium | D-20; keep cron-driven rollups; measure before optimising |
| **RK-13** | Data residency (L-2) invalidates hosting/storage/identity choices late | High | Medium | Resolve D-22 before committing to R2 and before launch |
| **RK-14** | Scope creep back toward a two-sided platform before V1 ships | Medium | Medium | §3.3 is an explicit *out* list; treat additions as PRD changes with a cost |
| **RK-15** | SEO underperformance from thin `category × area` pages | Medium | Medium | Growth-gate categories (§13), require minimum content per page |
| **RK-16** | Key-person dependency: one person holds product, ops and engineering context | High | Medium | The documentation hierarchy (§21) and AI context files (§22) exist partly for this |
| **RK-17** | Brand/logo not finalised delays launch assets | Low | Medium | D-28 temporary mark policy |
| **RK-18** | Google Maps billing surprise | Low | Medium | D-21: quota caps, deferred loading, static fallback |

---

## 24. PRD readiness assessment

### 24.1 Verdict

**[P] Not ready to write the full PRD today — but close, and closer than at
the end of Phase 2.** The owner's decisions removed the largest structural
unknown (D-02). What remains are **13 Class A decisions** (§16.1), of which
**five are genuinely blocking** and the rest can be answered in a single
workshop.

### 24.2 Readiness by PRD section

| PRD section | Ready? | Blocked by |
| --- | --- | --- |
| Product vision and positioning | **Yes** | — |
| Target users and personas | **Yes** | — (customers + staff; business owners are not users in V1) |
| V1 scope and non-goals | **Nearly** | D-01 (approve §3) |
| Functional requirements — discovery | **Nearly** | D-05, D-06 |
| Functional requirements — profiles | **Nearly** | D-03, D-04, D-07 |
| Functional requirements — accounts | **Nearly** | D-32, D-33, D-24 |
| Functional requirements — reviews | **Nearly** | D-12, D-34 |
| Functional requirements — operations console | **No** | **D-31** — the console's design depends on the throughput target |
| Functional requirements — advertising | **Nearly** | D-10 |
| Permissions matrix | **Yes** | §6 is usable as drafted; D-35/D-37 are details |
| Content model (categories, locations) | **Nearly** | D-06, D-40 |
| Language and content strategy | **No** | **D-18** |
| Non-functional requirements | **Yes** | v0.1 §16 stands |
| Launch criteria | **No** | **D-30**, which depends on **D-31** and **D-40** |
| Legal and privacy requirements | **No** | **D-22**, especially L-14 |
| Analytics and success metrics | **Nearly** | D-27; loop metrics drafted in §20 |
| Roadmap beyond V1 | **Yes** | §17 is the deferral list |

### 24.3 The five true blockers

1. **D-31** — operations capacity and coverage target.
2. **D-30** — definition of launch (depends on D-31 and D-40).
3. **D-18** — language strategy.
4. **D-06** — category taxonomy method and first list.
5. **D-22 / L-14** — legal posture of a company-published business database.

D-24 and D-32 are not blockers for *writing* the PRD, but they determine
whether the confirmed auth provider set survives contact with reality, so
they should be answered in the same session.

### 24.4 What the PRD should explicitly **not** contain

Schema DDL, API endpoint lists, framework or library choices, class designs,
or infrastructure decisions. Those belong in the TRD. Keeping the PRD
free of them is what makes the later technical validation (§7.5) honest.

---

## 25. Recommended next phase

### Phase 3 — Decision Workshop and PRD Preparation

**[P] Not started. No work beyond this document should begin until the
owner has reviewed it.**

**Step 1 — Decision workshop (owner + agent).** Walk §16.1 in order. Target:
all 13 Class A decisions answered or explicitly deferred with a named reason.
Output: `docs/10-product/decision-register.md` as a living document seeded
from §16.

**Step 2 — Two cheap validations, run in parallel with the workshop.**

- **Operations pilot (D-31):** create 20 real listings end-to-end by hand at
  the quality bar the product requires, and time it. This single exercise
  answers the scope ceiling, reveals the console's required fields, and
  produces the seed data for later development. It is the highest-value
  non-code activity available right now.
- **Telegram runtime prototype (§7.5, RK-7):** one throwaway page proving
  theme mapping, back button, `initData` round-trip and low-end Android
  behaviour. A day's work that de-risks the entire frontend strategy.

**Step 3 — Confirm the location facts (D-40).** Establish "Bole Bulbula"'s
administrative parent and its practical boundary locally; seed the first
branch of the location tree.

**Step 4 — Write the PRD** (`docs/10-product/prd-v1.md`) once Steps 1–3
land.

**Step 5 — Only then** the TRD, the data model, and implementation.

**Explicitly deferred to later phases:** the PRD itself, the TRD, `.ai/`
files, schemas, and any product code. **This phase ends here.**

---

## Appendix A — New research findings (R-16 … R-23)

Format per v0.1: **Finding** (fact) · **Why it matters** · **Applies to
Bulbula** · **Interpretation** (opinion, clearly separated) · **Source**.
Facts are **[C]**; interpretations are **[P]**.

### R-16 — A Telegram Mini App is a web page, not a build target

- **Finding [C]:** A Mini App is HTML/CSS/JS loaded in a WebView from a
  normal URL. Including `telegram-web-app.js` injects
  `window.Telegram.WebApp`, which exposes `initData`, `themeParams`,
  `MainButton`, `BackButton`, `HapticFeedback`, viewport metrics and events
  such as `themeChanged` and `viewportChanged`. Telegram also injects CSS
  variables — `--tg-theme-bg-color`, `--tg-theme-text-color`,
  `--tg-theme-hint-color`, `--tg-theme-link-color`,
  `--tg-theme-button-color`, `--tg-theme-button-text-color`,
  `--tg-theme-secondary-bg-color`, `--tg-viewport-height`,
  `--tg-viewport-stable-height` and safe-area insets.
- **Why it matters:** It removes the assumption that a Mini App needs its own
  frontend stack, SPA framework or build pipeline.
- **Applies to Bulbula:** Directly — it is the technical validation NC-11
  asked for.
- **Interpretation [P]:** Bulbula can serve one server-rendered frontend to
  both runtimes. The design system must therefore be built on CSS custom
  properties so Telegram's user-chosen theme can be mapped onto Bulbula's
  surface tokens while brand colours are preserved for identity elements.
- **Source:** Telegram Web Apps documentation and developer guides
  (telegram.org Mini Apps docs; community guides on `telegram-web-app.js`,
  theme parameters and viewport handling).

### R-17 — Sign in with Apple for the web has a cost and an operational tail

- **Finding [C]:** Sign in with Apple on the web requires membership of the
  Apple Developer Program (99 USD/year; free accounts cannot configure it), a
  **Services ID** used as the OAuth `client_id` and associated with a primary
  App ID, registered domains and return URLs (up to 10 for individual
  accounts, 100 for organisations), a private key, and a **client secret that
  is a signed JWT valid for at most six months**. Apple's private email relay
  may deliver `@privaterelay.appleid.com` addresses, and sending to them
  requires registering verified domains with Apple's relay service.
- **Why it matters:** Apple login is a confirmed V1 provider (NC-5) with a
  budget, an enrolment lead time and a recurring rotation task.
- **Applies to Bulbula:** Directly; also later for the Flutter iOS build,
  where App Store rules effectively require Apple login alongside Google.
- **Interpretation [P]:** Start enrolment immediately (D-32), put secret
  rotation in the runbook with a calendar reminder, and treat the relay-email
  case as proof that identity must be keyed on provider subject, not email
  (§5.2 rule 2).
- **Source:** Apple Developer documentation (Sign in with Apple for the web,
  Services ID configuration, client secret generation, program enrolment),
  plus independent implementation write-ups.

### R-18 — htmx + Alpine is a credible no-build interactivity layer

- **Finding [C]:** htmx is roughly 14–16 KB gzipped with no dependencies and
  works by swapping server-rendered HTML fragments in response to element
  attributes; Alpine.js is roughly 7 KB with ~15 directives for local
  client-side state. Neither requires a build step or a Node runtime; both
  load from a single script tag and work with any server language.
- **Why it matters:** V1 needs modest interactivity on shared hosting with no
  build pipeline, while preserving progressive enhancement and SEO.
- **Applies to Bulbula:** Directly to D-16 and §7.6.
- **Interpretation [P]:** This pairing fits the architecture better than any
  SPA framework and does not violate the framework-free rule, which governs
  the PHP backend. It should be *evaluated first*, with hand-written vanilla
  modules as an acceptable fallback. The deciding criterion should be whether
  fragment swapping genuinely simplifies search, filters and pagination.
- **Source:** htmx and Alpine.js project documentation and 2025–2026
  comparative write-ups on HTML-first stacks.

### R-19 — Category taxonomies: borrow the shape, not the size

- **Finding [C]:** Google Business Profile provides a fixed vocabulary of
  roughly 4,000 categories; custom categories are not permitted. A listing
  sets one primary category plus up to nine additional ones, and the primary
  category drives ranking, display and feature availability. Practitioner
  guidance consistently warns that adding loosely-related secondary
  categories dilutes relevance.
- **Why it matters:** It is the de-facto standard vocabulary users and
  businesses already recognise, and it validates the "one primary + a few
  secondary" model.
- **Applies to Bulbula:** D-06 directly; also ad targeting and SEO pages.
- **Interpretation [P]:** Curate ~100–200 categories relevant to Bole Bulbula
  using Google's naming conventions, cap secondary categories at about three,
  and gate new categories on real listing volume. Alignment with the standard
  vocabulary keeps a future import or integration cheap.
- **Source:** Google Business Profile category documentation and
  local-SEO practitioner analyses (BrightLocal and similar).

### R-20 — Addis Ababa's administrative hierarchy, and why it is not the UX

- **Finding [C]:** Ethiopia's structure is Region → Zone → Woreda → Kebele.
  Addis Ababa is a chartered city divided into **sub-cities** (*kifle
  ketema*), then woredas. The city had 10 sub-cities and 116 woredas until a
  2020 city-council decision created **Lemi Kura** as an 11th sub-city. Bole
  is the largest sub-city by area. Official datasets also record sub-woreda
  units below woreda level.
- **Why it matters:** The location model must be administratively correct and
  usable at the same time — residents navigate by area names and landmarks,
  not woreda numbers.
- **Applies to Bulbula:** D-40 and §14.
- **Interpretation [P]:** Store sub-city and (optionally) woreda for
  correctness and operations, but make the curated **Area** layer the
  user-facing unit for filters, URLs and ad targeting.
- **Source:** Addis Ababa city administration references, Ethiopian
  administrative-boundary datasets, and 2020 news coverage of the Lemi Kura
  sub-city creation.

### R-21 — Language: five federal working languages, but Addis runs on Amharic and English

- **Finding [C]:** Amharic was Ethiopia's sole federal working language until
  2020, when Afaan Oromo, Tigrinya, Somali and Afar were added, making five.
  Afaan Oromo has the largest number of native speakers nationally; Amharic
  remains the dominant urban lingua franca, especially in Addis Ababa, and
  English is widely used in business, education, banking and signage.
- **Why it matters:** It defines the realistic language pair for an
  Addis-only V1 and the expansion path beyond it.
- **Applies to Bulbula:** D-18 (§16.4).
- **Interpretation [P]:** English-first UI with Amharic names, labels and
  search aliases from day one; Amharic UI when evidence justifies the content
  workflow; Afaan Oromo only on expansion.
- **Source:** Ethiopian government language-policy reporting (2020) and
  language-demographics analyses.

### R-22 — "Bole Bulbula" is a place name, not an administrative unit

- **Finding [C]/[X]:** Property and community sources place Bole Bulbula in
  **Bole sub-city**, near the ring road and roughly five minutes from Bole
  International Airport, referenced by landmarks such as "93 Mazoria" and a
  nearby church. At least one listing source attributes the same name to a
  different sub-city and woreda, and sub-city boundaries were redrawn in
  2020.
- **Why it matters:** The launch area's definition determines coverage
  targets, the location tree seed, and what "every business in Bole Bulbula"
  actually means.
- **Applies to Bulbula:** D-40; also D-30, since the launch bar is expressed
  in coverage of this area.
- **Interpretation [P]:** Treat the conflict as evidence for the model rather
  than as a blocker: informal area names need alias support and an explicit,
  Bulbula-defined boundary. Confirm the administrative parent locally before
  seeding data.
- **Source:** Ethiopian property-listing sites and local organisation pages
  referencing Bole Bulbula; conflicting sub-city attributions noted.

### R-23 — OAuth inside the Telegram WebView is a known friction point

- **Finding [C]:** Telegram Mini Apps run inside an embedded WebView and
  supply a signed `initData` payload identifying the user, which the server
  validates with the bot token. Redirect-based third-party OAuth from inside
  an embedded WebView commonly leaves the host context — opening an external
  or in-app browser — and identity providers increasingly restrict
  authentication in embedded WebViews.
- **Why it matters:** The confirmed provider set (Google, Apple, email) is a
  web-browser assumption; the Mini App is not a browser.
- **Applies to Bulbula:** D-33 and §5.4.
- **Interpretation [P]:** Adopt Telegram as a first-class provider for the
  Telegram runtime, mapped to the same Bulbula identity, with optional
  linking when the user later signs in on the web. Without it, expect
  materially lower review and save conversion inside Telegram.
- **Source:** Telegram Mini Apps authentication documentation (`initData`
  validation) and identity-provider guidance on embedded WebView
  authentication.

---

## Appendix B — Workshop agenda (suggested, 90 minutes)

| Block | Minutes | Decisions |
| --- | --- | --- |
| 1. Scope confirmation | 15 | D-01, D-05 |
| 2. The operations question | 20 | **D-31**, then D-30 and D-40 |
| 3. Content model | 15 | D-06, D-18 |
| 4. Accounts reality check | 15 | D-32, D-33, D-24 |
| 5. Money and integrity | 15 | D-10, D-39 |
| 6. Legal posture | 10 | D-22, with L-14 named explicitly |

Everything in Class B can then be answered asynchronously during PRD and
design work.

---

## Appendix C — One-page summary for the owner

- **Your decisions made V1 much smaller.** No business accounts, no claims,
  no owner dashboard, no self-service ads. V1 is a directory Bulbula builds
  and keeps correct, plus accounts for customers who review and save.
- **The new centre of gravity is the internal tool.** How fast staff can
  create and maintain good listings now determines how big V1 can be. That is
  the first question to answer (**D-31**).
- **Web + Telegram can genuinely share one frontend.** A Mini App is just a
  web page Telegram opens; the only Telegram-specific code is a small adapter
  for theme, back button and login. A one-day prototype should confirm it.
- **Two confirmed decisions have hidden costs:** Apple login needs a paid
  developer account and a secret rotated twice a year; email login needs
  reliable email delivery, which shared hosting does not provide. Both are
  solvable with a small budget — but decide now, not at launch.
- **One new legal question matters more than the rest:** publishing a
  business database without the businesses' involvement is a different legal
  posture than hosting data they submitted. Put that to a lawyer
  specifically.
- **Five decisions block the PRD:** operations capacity, launch definition,
  language, category taxonomy, and the legal posture. Everything else can be
  decided while writing.
