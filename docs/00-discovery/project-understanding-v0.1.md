# Bulbula Product Understanding Report

| | |
| --- | --- |
| **Document** | Bulbula Product Understanding Report |
| **Version** | 0.1 |
| **Status** | Discovery — awaiting review |
| **Date** | 2026-10-07 |
| **Repository baseline** | `main` @ `fc6188b` (139 tracked files) |
| **Supersedes** | — |
| **Companion** | [research-notes-v0.1.md](research-notes-v0.1.md) |

## How to read this document

This is **not** the PRD, the TRD or a design document. It is the controlled
source material those documents will be written from. Its job is to make
everything that is currently known about Bulbula explicit, and — just as
important — to make everything that is *not* known impossible to overlook.

Every substantive statement carries a certainty tag:

| Tag | Meaning |
| --- | --- |
| **[C] Confirmed** | Explicitly decided by the product owner, or verifiable in the repository. |
| **[SI] Strongly implied** | Follows logically from something confirmed, but has never been approved as such. |
| **[P] Proposed** | A recommendation from this analysis. Requires approval before it becomes a requirement. |
| **[U] Unknown** | Missing information. Named so it can be answered, not guessed. |
| **[X] Conflict** | Two pieces of information that cannot both be satisfied as stated. |

Rules this document follows, and that later documents must inherit:

1. An assumption is never promoted to a requirement silently. If it is not
   tagged **[C]**, it is not yet a requirement.
2. Researched facts are separated from recommendations. Facts with sources
   live in the companion research notes; the interpretation lives here.
3. Where a decision is genuinely open, it is recorded in the decision register
   (§32) instead of being answered here.

---

## 1. Executive understanding

Bulbula is a **local business discovery platform for Bole Bulbula, Addis
Ababa**, intended to grow into a professional, multi-client product that helps
residents find and evaluate nearby businesses, and helps those businesses hold
a credible digital presence and pay for visibility. **[C]**

Four facts define the shape of the whole project:

**It is a two-sided product with a hard cold-start problem.** Customers arrive
only if the listings are good; businesses invest only if customers arrive.
Everything in V1 should be judged by whether it shortens the path to a
directory that is genuinely worth searching in one neighbourhood. **[SI]**

**Revenue depends on an audience that does not exist yet.** Advertising is a
confirmed first-class subsystem, but advertising inventory is worthless before
there is traffic. The architecture must be ready for advertising early; the
*sales* of advertising is a later milestone than the *existence* of the
listing experience. Building the advertising subsystem first would be building
a billing system for zero impressions. **[P]**

**The engineering baseline is unusually strong for the product stage.** The
repository already enforces PHPStan max, 100 % line and type coverage, 100 %
mutation score, architecture tests, secret scanning and dependency auditing,
on a framework-free PHP 8.4 codebase with routing, middleware, migrations,
logging, error handling and health checks. There is no domain code at all yet:
no users, no businesses, no categories. The project is therefore in the rare
position of having *infrastructure maturity far ahead of product maturity* —
which is exactly why a discovery phase is the correct next step rather than
more code. **[C]**

**The deployment target is modest and must stay that way.** Shared cPanel
hosting, Apache/LiteSpeed, MariaDB, cron, Cloudflare in front, object storage
for media. No Redis, no Elasticsearch, no queue broker, no daemons. This is
not a temporary inconvenience to design around — it is a design constraint
that should visibly shape the search model, the analytics model and the media
pipeline. **[C]**

The central tension to manage through the whole project is this: Bulbula has
the *ambition* of a platform (three clients, advertising, analytics, trust and
safety, admin) and the *resources* of a small team on shared hosting. The way
through is a modular monolith with a small, sharp V1 and an explicit,
documented ladder of what gets built when.

---

## 2. Product definition

**Bulbula is a neighbourhood-first business directory and discovery platform:
a Bulbula-owned place where a customer can find, compare, trust and contact a
nearby business, and where a business can maintain a verified profile and buy
clearly-labelled promotion.** **[SI — a synthesis of confirmed statements, not
a quoted decision]**

### What it is

| Aspect | Position | Certainty |
| --- | --- | --- |
| Category | Local business discovery / directory platform | **[C]** |
| First market | Bole Bulbula, Addis Ababa, Ethiopia | **[C]** |
| Primary value to customers | Find and evaluate nearby businesses; contact or visit them | **[C]** |
| Primary value to businesses | Professional digital presence + a way to be discovered and promote | **[C]** |
| Monetisation | Organic discovery funnel, sponsored placement, advertising, later premium services | **[C]** |
| Ownership of the experience | The profile and the experience stay on Bulbula; Google Maps provides the map and hand-off to navigation | **[C]** |
| Clients | Web, Flutter mobile (Android + iOS), Telegram Mini App — one backend | **[C]** |
| Architecture | Framework-free plain PHP, modular monolith | **[C]** |

### What it is explicitly not

- Not a marketplace: Bulbula does not take orders, process carts or handle
  transactions between customers and businesses in V1. **[C — ordering and
  reservations are listed as future modules]**
- Not a mapping product: no tile server, routing engine, geospatial database
  or satellite infrastructure. Maps are embedded; navigation is handed off to
  Google Maps. **[C]**
- Not a review-only platform: reviews are one trust signal inside a discovery
  product, not the product itself. **[SI]**
- Not a clone of one existing product. Research informs decisions; interfaces
  are not copied. **[C]**
- Not a social network, a jobs board, a news site or a messaging app — though
  several of those are named as *possible* future modules. **[C]**

---

## 3. Problem being solved

### 3.1 The customer's problem

In Bole Bulbula today, finding a specific local business is mostly word of
mouth, Telegram channels, and physically walking the street. A resident who
wants "a dentist open now within walking distance, with a phone number that
works" has no reliable digital answer. **[P — this is the problem statement
implied by the brief; it has not been validated with research or interviews.]**

What the research does support:

- Ethiopia has a large but shallow online population: roughly 57 million
  internet users against 97 million mobile subscriptions, with urban mobile
  internet adoption around 48 % of adults and only ~29 % using it daily. The
  audience exists in Addis but is not uniformly "always online". **[C — see
  research notes R-06]**
- The existing Ethiopian directory landscape is wide and shallow: dozens of
  national "yellow pages" style sites with stale, unverified, thinly-populated
  listings, little neighbourhood granularity and no real product experience.
  This is both the opportunity (low quality bar to beat on depth and
  freshness) and the warning (many have tried the generic national directory
  and none has become the default). **[C — see R-07]**
- Telegram is unusually dominant in Ethiopia relative to the rest of Africa,
  and products that attach to existing messaging habits have a shorter path to
  adoption than standalone apps. This materially raises the strategic value of
  the Telegram Mini App. **[C — see R-06]**

The sharpest framing of the customer problem, which V1 should be measured
against: **a Bole Bulbula resident should be able to answer "who near me does
X, are they open, and how do I reach them" in under a minute, on a mid-range
Android phone, on a poor connection.** **[P]**

### 3.2 The business's problem

A small business in Bole Bulbula has, at best, a Telegram channel and a phone
number. It has no structured, searchable, trustworthy presence; no way to show
hours, services and prices; no way to be found by someone who does not already
know its name; and no affordable, legible way to buy local visibility. **[P —
same caveat: plausible and consistent with the brief, not yet validated.]**

### 3.3 What is unknown about the problem

- **[U]** No user research, interviews or survey data exists. Every persona
  and journey in this document is a hypothesis.
- **[U]** No estimate of the number of businesses in Bole Bulbula, or how many
  are reachable for onboarding.
- **[U]** No data on what customers currently search for, or in which language
  they would type it.
- **[U]** No evidence about willingness to pay for promotion, or price points.

These four unknowns are the highest-value things to resolve before the PRD,
because they determine V1 scope more than any technical decision does. **[P]**

---

## 4. Target market

### 4.1 Geographic strategy

| Stage | Scope | Status |
| --- | --- | --- |
| Stage 0 | **Bole Bulbula** — the first real market, optimised for | **[C]** |
| Stage 1 | Other Addis Ababa areas (Bole, CMC, Megenagna, Sarbet …) | **[C] as a direction** |
| Stage 2 | Other Ethiopian cities | **[C] as a direction** |
| Stage 3 | Broader geographic coverage | **[C] as a direction** |

The confirmed constraint is a *shape* constraint, not a feature: **the data
model must not hard-code one neighbourhood, and V1 must not carry
national-scale complexity.** **[C]**

The practical reading of that: locations are a **hierarchy** (country → city →
sub-city → area/neighbourhood) from day one, because retrofitting a hierarchy
onto flat text addresses is a painful migration; but V1 ships with exactly one
populated branch of that hierarchy and no multi-city UI, no city switcher, no
per-city SEO strategy and no regional sharding. **[P]**

### 4.2 Market characteristics that should shape the product

| Characteristic | Source | Design consequence |
| --- | --- | --- |
| Mobile-first, mid-range Android, metered data | R-06 | Strict page-weight budget; no heavy SPA for public pages; images aggressively optimised |
| Telegram is a primary channel | R-06 | Mini App is a genuine acquisition channel, not a checkbox |
| Mobile money (telebirr, M-Pesa) dominates; card penetration is low; gateways are developer-hostile without a business licence | R-08 | Advertising billing must tolerate **offline/manual payment** as a first-class path, not only an API |
| Bilingual reality: Amharic and English, with Latin-script transliteration of Amharic names | R-09 | Search, data model and typography must plan for two scripts; this is a V1-scope decision, not a later "i18n task" |
| Addresses are weak: few street numbers, landmark-based directions | General market knowledge **[P]** | Address model should accept landmark text + coordinates; do not design around postal addresses |

---

## 5. Target users and personas

All personas below are **[P]** — constructed from the brief, not from
research. They exist to make later documents concrete and must be validated or
replaced.

### Customer personas

**P1 — "The resident"** (primary). Lives or works in Bole Bulbula, 20–45,
Android phone, uses Telegram daily, searches in a mix of Amharic and
transliterated English. Needs: a specific service nearby, right now, with a
working phone number and current hours. Success = a phone call or a visit.

**P2 — "The newcomer"** (secondary). Recently moved to the area, or visits for
work. Needs orientation: what exists here at all, what is good, what is open.
Browses categories and collections more than she searches.

**P3 — "The deliberate buyer"** (secondary). Planning a purchase or a service
(a clinic, a gym, a contractor). Compares several businesses on rating,
services, price and photos before contacting. This is the persona that
justifies profile depth, FAQs and comparison.

### Business personas

**P4 — "The single-shop owner"** (primary). One location, runs the business
personally, may not own a laptop, digital presence is a Telegram channel.
Needs: a profile that takes ten minutes to create on a phone, and proof it
brings customers. Price sensitive; sceptical.

**P5 — "The established local business"** (secondary). Two to five branches, a
manager who handles marketing, may already advertise on Telegram channels or
billboards. Needs branch management, analytics and advertising. **This is the
persona that pays.**

### Platform personas

**P6 — "The moderator/operator"**. Reviews claims, verifications, reported
content and ad creatives. Needs queues, context and an audit trail. In the
first year this is probably the founder. **[P]**

**P7 — "The administrator"**. Manages taxonomy, locations, packages, pricing
and platform health. **[P]**

**[U]** No decision exists on whether P6 and P7 are distinct roles or one
`admin` role with permissions. See D-14.

---

## 6. Customer journey

### 6.1 The loop (confirmed shape)

```text
Discover → Search → Evaluate → Contact / Visit → Save / Review → Return
```
**[C]**

### 6.2 The journey in detail

| Stage | What the customer does | What the platform must provide | Certainty |
| --- | --- | --- | --- |
| **Entry** | Arrives from Google, a shared link, Telegram, or direct | Fast, indexable public pages; shareable URLs; Telegram deep links | **[SI]** |
| **Discover** | Scans the homepage: categories, popular, new, featured, offers | Curated and computed surfaces; clearly labelled sponsored items | **[C]** |
| **Search** | Types a query, possibly in Amharic or transliterated | Keyword search + autocomplete + spelling tolerance | **[C]** (tolerance **[P]**) |
| **Filter** | Narrows by category, area, distance, rating, open now, price, features, verified | Filter model wired into the same query path as search | **[C]** |
| **Compare** | Scans result cards: name, rating, category, distance, open/closed, photo | A result card contract that carries exactly the comparison signals | **[SI]** |
| **Evaluate** | Opens the business profile: photos, services, prices, hours, reviews, FAQs, map | The full profile model (§11) | **[C]** |
| **Act** | Taps call, website, directions, or social links | Tracked interaction events (also the business's analytics) | **[C]** |
| **Keep** | Saves to favourites, writes a review | Accounts, favourites, review submission + moderation | **[C]** |
| **Return** | Comes back for the next need | Notifications, saved lists, habit surfaces | **[SI]** |

### 6.3 Journey observations

- **Guest browsing must remain possible** — confirmed. Accounts are therefore
  required only at the "Keep" stage (favourites, reviews). This is a load-
  bearing decision: it keeps the discovery funnel frictionless and means the
  account system is *not* on the critical path for a usable V1. **[C + SI]**
- The **Act** stage is where value is created for the business and where every
  meaningful analytics metric originates (phone clicks, direction requests,
  website clicks). Instrumenting it is not an analytics "nice to have" — it is
  the evidence that later justifies the advertising price. **[P]**
- **Missing loop identified:** there is no *content* loop that brings a
  customer back when they have no immediate need. Offers, events and
  collections are the obvious candidates, which is why they appear under
  discovery in the brief. Whether any of them is in V1 is open (D-05). **[P]**
- **Missing loop identified:** there is no *correction* loop — a customer who
  notices wrong hours or a disconnected phone number has no way to tell the
  platform. For a directory, data decay is the primary quality risk, and
  customers are the cheapest sensor. A "suggest an edit / report a problem"
  path is strongly recommended for V1. **[P]**

---

## 7. Business-owner journey

### 7.1 The loop (confirmed shape)

```text
Create / Claim → Complete profile → Verify → Get discovered
→ Receive customers → Promote → Analyze → Improve
```
**[C]**

### 7.2 The journey in detail

| Stage | Owner action | Platform capability | Certainty |
| --- | --- | --- | --- |
| **Arrive** | Hears about Bulbula, or finds their business already listed | Business account registration; "is this your business?" prompt on every unclaimed profile | **[SI]** |
| **Create** | Adds a business that does not exist | Submission form + admin approval before publication | **[C] feature, [P] approval gate |
| **Claim** | Asserts ownership of a listing Bulbula created | Claim workflow with evidence and a decision | **[C]** |
| **Verify** | Proves the business is real and they represent it | Verification workflow + a visible verified state | **[C]** |
| **Complete** | Adds hours, photos, services, prices, FAQs, social links | Full profile management; a completeness indicator | **[C]** feature, **[P]** completeness score |
| **Be discovered** | Appears in search, category and area browsing | Organic ranking that rewards completeness and freshness | **[C]** |
| **Receive** | Gets calls, visits, direction requests | Interaction tracking | **[C]** |
| **Promote** | Buys a package for featured/top placement | Advertising subsystem + billing | **[C]** |
| **Analyze** | Reads views, impressions, clicks, campaign performance | Business analytics | **[C]** |
| **Improve** | Responds to reviews, updates offers, renews campaigns | Review replies, offers, renewal | **[C]** |

### 7.3 Journey observations

- **Seeding changes everything.** If Bulbula pre-creates listings (so the
  directory is useful on day one), then *claim* is the primary entry path and
  *create* is secondary. If it does not, the directory launches empty and the
  business loop has no starting point. This single decision (D-02) reshapes
  onboarding, verification, data provenance and admin workload, and it is
  currently unanswered. It is the most consequential open question in this
  document. **[U]**
- **Verification is a product feature, not a backend detail.** The verified
  badge is the main trust signal a young directory can offer, and verification
  status is also a confirmed search filter and ranking input. The *rules*
  (what evidence, who decides, how long it lasts) are open (D-08). **[C +
  U]**
- **Missing loop identified:** nothing in the brief returns the business to
  the platform between campaigns. A business that completes its profile and
  buys nothing has no reason to log in again, and its data decays. A periodic
  "your information may be out of date / here is what happened this month"
  touch (email, Telegram, SMS) is the retention mechanism that keeps the
  directory fresh. **[P]**

---

## 8. Revenue model

### 8.1 Confirmed model

```text
Organic discovery (free, builds the audience)
        +
Sponsored placement (paid visibility inside discovery)
        +
Advertising (banners, in-feed, sponsorships)
        +
Future premium business services
```
**[C]**

Initial monetisation preference: **fixed packages**. No auction, no CPC, CPM
or CPA complexity unless a future requirement justifies it. **[C]**

### 8.2 What "fixed packages" implies

A package is a **named, priced bundle of placement for a fixed period**, for
example "Category Sponsor — Restaurants — Bole Bulbula — 30 days". The
platform sells availability, not auctions. Consequences: **[SI]**

- Inventory must be **finite and explicit** per placement, per period, per
  targeting dimension — otherwise every sponsor appears everywhere and
  sponsorship loses value and credibility.
- The system needs **availability checking** ("is this slot free for these
  dates?") rather than bid resolution.
- Pricing is a managed catalogue, not a computed market.
- Performance reporting is still required (impressions, clicks, actions), but
  for *proof of value and renewal*, not for billing. Billing is by package.

### 8.3 The sequencing risk

**[P]** Advertising revenue requires audience. A reasonable sequence is:

| Phase | Revenue posture |
| --- | --- |
| V1 launch | Nothing is sold. Measure traffic and interactions. Data model supports promotion. |
| V1.x | Manually-arranged, admin-created sponsorships for a handful of businesses at introductory prices. Tests willingness to pay with minimal machinery. |
| V2 | Self-service package purchase, approval workflow, automated scheduling, invoicing. |

This lets the advertising subsystem be *designed* as first-class from the
start (as required) while the *selling* machinery arrives when there is
something worth buying.

### 8.4 Billing reality

**[C — research]** Ethiopian payment rails are dominated by mobile money
(telebirr, M-Pesa Ethiopia) with local gateways (Chapa, ArifPay, SantimPay)
providing developer APIs; integration typically requires a business licence
and a formal onboarding process, and cash remains common.

**[P]** Therefore the billing model should be: an **invoice/record-of-payment
model inside Bulbula** with payment captured out-of-band (bank transfer,
telebirr, cash) and marked as received by an administrator, with an *optional*
gateway integration later. Designing V1 around a gateway API would block
revenue on an integration the team may not be licensed to complete.

**[U]** Pricing levels, currency handling (ETB only?), VAT/receipts, and
whether the platform must issue a legal invoice are all unknown (D-11).

---

## 9. Product capability map

### 9.1 The map

```text
                        ┌─────────────────────────────┐
                        │        PLATFORM CORE        │
                        │  identity · accounts · auth │
                        │  configuration · audit log  │
                        └──────────────┬──────────────┘
                                       │
        ┌──────────────────────────────┼──────────────────────────────┐
        │                              │                              │
┌───────▼────────┐            ┌────────▼────────┐            ┌────────▼────────┐
│    CUSTOMER    │            │    BUSINESS     │            │      ADMIN      │
│ browse·search  │            │ create · claim  │            │ moderation      │
│ favourites     │            │ manage · verify │            │ taxonomy        │
│ reviews        │            │ promote·analyze │            │ platform config │
└───────┬────────┘            └────────┬────────┘            └────────┬────────┘
        │                              │                              │
        └──────────────┬───────────────┴──────────────┬───────────────┘
                       │                              │
              ┌────────▼────────┐            ┌────────▼────────┐
              │     SEARCH      │            │   ADVERTISING   │
              │ index·rank·filter│◄──────────│ campaign·package│
              │ autocomplete    │  placement │ placement·limits│
              └────────┬────────┘   (labelled,│ approval·billing│
                       │          separate)   └────────┬────────┘
                       │                               │
   ┌───────────────────┼───────────────┬───────────────┼─────────────┐
   │                   │               │               │             │
┌──▼──────┐  ┌─────────▼──────┐  ┌─────▼─────┐  ┌──────▼─────┐ ┌─────▼─────┐
│ CONTENT │  │ TRUST & SAFETY │  │ ANALYTICS │  │   MEDIA    │ │  BILLING  │
│business │  │ verification   │  │ events    │  │ upload     │ │ invoices  │
│profiles │  │ claims·reports │  │ rollups   │  │ transform  │ │ payments  │
│taxonomy │  │ moderation     │  │ dashboards│  │ CDN/R2     │ │ records   │
│offers   │  │ anti-abuse     │  └─────┬─────┘  └──────┬─────┘ └─────┬─────┘
└──┬──────┘  └────────┬───────┘        │               │             │
   │                  │                │               │             │
   └──────────────────┴────────────────┴───────┬───────┴─────────────┘
                                               │
                                     ┌─────────▼─────────┐
                                     │   NOTIFICATIONS   │
                                     │ email·Telegram·push│
                                     └─────────┬─────────┘
                                               │
                      ┌────────────────────────┼────────────────────────┐
                      │                        │                        │
                 ┌────▼────┐             ┌─────▼─────┐           ┌──────▼─────┐
                 │   WEB   │             │  FLUTTER  │           │  TELEGRAM  │
                 │ SSR·SEO │             │ Android/iOS│          │  MINI APP  │
                 └─────────┘             └───────────┘           └────────────┘
                        all three consume the same domain + API
```

### 9.2 Capability definitions and relationships

| Capability | Owns | Depends on | Consumed by |
| --- | --- | --- | --- |
| **Platform core** | Identity, accounts, sessions, authorisation, audit log, configuration | — | everything |
| **Content** | Business, branch, category, location, services, products, offers, events, collections, media references | Platform core | Customer, Search, Admin, all clients |
| **Search** | Query parsing, index, relevance, filters, sorting, autocomplete, zero-result capture | Content, Location | Customer, all clients |
| **Business** | Ownership, profile management, hours, replies, dashboard | Platform core, Content, Trust | Business users |
| **Trust & safety** | Verification, claims, moderation, reports, duplicate/spam detection, audit | Content, Platform core | Admin, Customer confidence, Search (as a signal) |
| **Advertising** | Packages, campaigns, placements, targeting, scheduling, approval, delivery, caps | Content, Business, Billing | Search/Discovery surfaces, Business dashboard, Admin |
| **Analytics** | Event capture, aggregation, business dashboards, platform dashboards | Content, Advertising, Search | Business, Admin |
| **Media** | Upload, validation, transformation, storage, delivery | Platform core, object storage | Content, all clients |
| **Billing** | Orders, invoices, payment records, package entitlements | Advertising, Business | Admin, Business |
| **Notifications** | Channel-agnostic message dispatch | Platform core | Trust, Advertising, Business, Customer |
| **Customer** | Favourites, reviews, activity, preferences | Platform core, Content | Clients |
| **Admin** | Operational control over every other capability | all | Operators |
| **Web / Flutter / Telegram** | Presentation, platform-specific interaction | API, domain | Users |

### 9.3 Three relationships that carry most of the risk

1. **Search ↔ Advertising must be separable.** Confirmed requirement: paid
   placement must not secretly manipulate organic relevance. The structural
   expression of that is a *composition* step: organic results are computed
   by the ranking pipeline, sponsored items are selected by the ad delivery
   pipeline, and a presentation layer interleaves them under explicit rules
   with explicit labels. Two pipelines, one surface. **[C requirement, [P]
   mechanism]**
2. **Content ↔ Trust is bidirectional.** Trust state (verified, claimed,
   reported, duplicate) is both produced by moderation and consumed by search
   ranking and the UI. It must live on the content entities, not in a side
   system. **[SI]**
3. **Analytics is downstream of everything and must not slow anything.** Every
   surface produces events; nothing should write synchronously to an analytics
   store on the request path beyond a cheap append. On shared hosting with
   cron-only background work, this forces an append-now / aggregate-later
   design. **[P]**

---

## 10. Search and discovery model

### 10.1 Confirmed surface area

Search must eventually support: keyword, category, subcategory, location,
distance/radius, rating, open now, price, services, features/amenities and
verification status. Sorting: relevance, distance, rating, popularity, newest,
plus other documented ranking factors. Sponsored results must be clearly
distinguishable, and paid placement must not secretly manipulate organic
ranking. **[C]**

### 10.2 Query types the model must serve

| Type | Example | Dominant signal |
| --- | --- | --- |
| Named lookup | "Kaldi's", "ካልዲስ" | Exact/prefix name match |
| Category intent | "pharmacy", "መድኃኒት ቤት" | Category match + distance |
| Service intent | "passport photo", "car wash" | Service/product text |
| Attribute intent | "open now", "delivery", "parking" | Filters |
| Exploratory | "things near me", empty query + browse | Curation + popularity |
**[P — a taxonomy proposed to structure the ranking work; not yet approved.]**

### 10.3 Ranking model

Confirmed conceptual factors: relevance, distance, rating, review quality,
profile completeness, popularity, freshness, open status, engagement. **[C]**

**[P] Proposed structure** — a two-stage model, deliberately simple and
inspectable:

```text
stage 1  CANDIDATE SELECTION   (cheap, index-driven)
         text match AND filters AND geographic bounding box
                         │
stage 2  SCORING           score = Σ (weight × normalised signal)
         text relevance · proximity · rating(Bayesian) · completeness
         · freshness · popularity · open-now · verification
                         │
         ORDER BY score, with every weight named in configuration
```

Design rules proposed for the ranking layer:

1. **Every weight is configuration, not code.** Ranking will be tuned
   repeatedly; tuning must not require a deployment.
2. **Scores are explainable.** The admin must be able to see why a business
   ranked where it did. A directory that cannot explain its ordering cannot
   credibly claim that advertising does not corrupt it.
3. **Ratings are smoothed.** A single 5★ review must not outrank 40 reviews
   averaging 4.6 (Bayesian average with a global prior).
4. **Advertising never enters the score.** Sponsorship changes *what is
   inserted into the result page*, never the organic score. (Confirmed
   principle; this is the mechanism.)
5. **Zero-result queries are logged and reviewed.** They are the cheapest
   source of truth about missing listings, missing categories and language
   gaps — the brief already lists "zero-result searches" in platform
   analytics.

### 10.4 The search engine constraint — and what it means

**[C — research, R-03]** The available engine is MariaDB. InnoDB `FULLTEXT`
supports natural-language and boolean modes with relevance scoring, but it
ignores tokens shorter than `innodb_ft_min_token_size` (default 3), applies a
stopword list, and has no built-in fuzzy matching, stemming or synonym
handling. Dedicated engines (Elasticsearch, OpenSearch, Meilisearch) are
explicitly out of scope for this stage.

**[C — research, R-09]** Amharic uses the Ge'ez script; MariaDB's default
tokeniser splits on whitespace and punctuation, which works for Amharic word
separation but gives no stemming for Amharic morphology, and users frequently
type Latin transliterations ("bet", "beth", "bét").

**[P] Interpretation for Bulbula:**

- Full-text search over a **purpose-built search document** per business (name
  + alternate names + transliterations + category names + service names +
  area names, in both scripts) rather than over the raw profile columns. One
  indexed text column that the application composes and refreshes on write.
- Keep an **alias/synonym table** (`pharmacy` ≈ `መድኃኒት ቤት` ≈ `farmacy`) which
  the application expands at query time. This is the cheapest substitute for
  a real analyser and it is editable by an administrator.
- Lower `innodb_ft_min_token_size` is **not** assumable on shared hosting
  (it requires a server restart and index rebuild) — so short tokens must be
  handled by prefix/`LIKE` fallbacks for autocomplete, not by configuration.
  **[U] whether the host allows any MariaDB tuning at all (D-20).**
- Autocomplete should be served from a **small, dedicated suggestions table**
  (names, categories, areas, popular queries) with a prefix index — not from
  the full-text index.
- Revisit a dedicated search engine only when a named trigger is hit (see
  §22.4), not because it would be nicer.

### 10.5 Distance without geospatial infrastructure

Distance/radius filtering and distance sorting are confirmed, while PostGIS
and mapping infrastructure are confirmed out of scope. These are compatible:
store `latitude`/`longitude` as decimals, pre-filter with a **bounding box**
on indexed columns, then compute great-circle distance in SQL for the small
candidate set, and sort. At neighbourhood scale this is a well-understood,
cheap technique. **[P — the resolution of an apparent conflict; see §31
X-02.]**

### 10.6 Sponsored results on the result page

**[C — research, R-04]** The FTC's standing guidance to search engines —
including local-business verticals — is that including or ranking a result
based on payment is advertising, and that failing to *clearly and prominently*
distinguish it from natural results is deceptive. Recommended techniques:
explicit unambiguous text labels, consistent wording for all ad types, placed
immediately before the ad or at the top-left of an ad block, plus visual
separation such as shading or a border.

**[P] Interpretation for Bulbula** (Bulbula is not US-regulated, but this is
the clearest available standard and it matches the confirmed principle that
sponsorship must be labelled):

- One consistent label across every surface and every client — **one word,
  decided once**, used identically on web, Flutter and Telegram (D-10).
- Visual separation in addition to the label.
- A fixed, documented maximum number of sponsored items per surface and their
  fixed positions, so that organic results are never crowded out arbitrarily.
- A public, plain-language page explaining how ranking and sponsorship work.
  For a directory whose credibility *is* the product, this is cheap and
  unusually valuable.

---

## 11. Business profile model

### 11.1 Confirmed profile contents

name · logo · cover image · description · category · subcategories · address ·
latitude · longitude · Google Maps location · opening hours · open/closed
status · phone · website · social links · services · products · prices · photo
gallery · videos · offers · reviews · FAQs · verification information · claim
information · branches. **[C]**

The profile must remain a Bulbula-owned experience, with an embedded Google
Map and an explicit "Open in Google Maps" hand-off. **[C]**

### 11.2 Entity sketch

**[P] — a conceptual sketch to expose the decisions, not a schema.**

```text
Business ──< Branch ──< OpeningHours
    │           │
    │           ├──< ContactMethod (phone, website, social)
    │           └──< Address (text + landmark + lat/lng + Location ref)
    │
    ├──< BusinessCategory >── Category (one primary + n secondary)
    ├──< Service / Product (name, description, price?, currency)
    ├──< Media (logo, cover, gallery, video reference)
    ├──< Offer (time-bounded)
    ├──< Faq
    ├──< Review ──< ReviewReply
    ├──< Claim ──> decision + evidence
    ├──< Verification ──> state + method + evidence + expiry
    └──< Ownership ──> User (business account)

Location (self-referencing hierarchy: city → sub-city → area)
```

### 11.3 The decisions hidden inside the profile

These look like data-modelling details; each is a product decision:

| # | Question | Why it matters | Register |
| --- | --- | --- | --- |
| 1 | Is a single-location business a `Business` with one implicit `Branch`, or a `Business` with no branch? | Determines whether every query joins through branches, and how addresses, hours and analytics attach. Getting it wrong is a painful migration. | D-03 |
| 2 | Are reviews attached to the business or the branch? | Two branches of one brand can be very different; merging their reputation may be dishonest, splitting it may look empty. | D-03 |
| 3 | Is the category taxonomy fixed, two-level, and curated — or open and growing? | Drives navigation, SEO structure, ad targeting ("category sponsorship") and search relevance. | D-06 |
| 4 | Do prices live on services/products as free text, numeric ranges, or "from" values? | Numeric enables a price filter (confirmed as a filter) and comparison; free text does not. | D-07 |
| 5 | What exactly makes a profile "complete"? | Completeness is a confirmed ranking factor and the main lever for getting owners to finish onboarding. | D-09 |
| 6 | How are opening hours modelled (regular + holiday + temporary closure + 24h + by appointment)? | "Open now" is a confirmed filter and a ranking factor; research shows being listed as closed measurably reduces visibility, so correctness matters. | D-04 |
| 7 | Who may edit what after verification, and does an edit re-trigger review? | Trust decays silently if a verified profile can be rewritten freely. | D-08 |

### 11.4 Maps

**[C]** Google Maps embedding for the location; "Open in Google Maps" for
navigation; no self-hosted mapping infrastructure.

**[P]** Two cautions to carry into the TRD: an embedded Google Map is a
third-party script with a real cost in page weight and Core Web Vitals, and it
may require an API key with billing. The profile page should therefore render
a **static, lightweight map placeholder** (or a simple image/link) that is
upgraded to an interactive embed only on interaction. **[U]** whether a Google
Maps API key/billing account is available (D-21).

---

## 12. Advertising model

### 12.1 Confirmed position

Advertising is a **first-class subsystem**, explicitly not a `featured = true`
column. Products may include featured business, top search placement, category
sponsorship, location sponsorship, homepage promotion, sponsored collection,
sponsored offer, banner advertising and in-feed advertising. Campaign concepts
include campaign, targeting, placement, dates, budget, package, approval,
pause/resume, expiration, billing and performance (impressions, clicks, CTR,
profile visits, phone clicks, website clicks, direction actions,
conversions). Fixed packages preferred; sponsored content always labelled.
**[C]**

### 12.2 Decomposition

**[P] — proposed subsystem anatomy, derived from the confirmed concepts:**

| Component | Responsibility |
| --- | --- |
| **Placement** | A named, finite surface slot: `search.top`, `category.sponsor`, `home.promoted`, `profile.banner`, `feed.inline`. Each defines its max items, position rules and allowed creative shape. |
| **Package** | The sellable product: placement(s) + targeting scope + duration + price. The catalogue administrators manage. |
| **Campaign** | A business's purchase of a package for a date range, with a state machine. |
| **Targeting** | The scope a campaign applies to: category, subcategory, location, query intent. Deliberately coarse for fixed packages. |
| **Creative** | What is shown (usually the business profile itself; banners add an image + destination) — subject to approval. |
| **Scheduling / availability** | Prevents overselling a finite slot for overlapping dates. |
| **Delivery** | At request time, selects which campaigns are eligible for this surface + context, applies caps and rotation, returns labelled items. |
| **Accounting** | Records impressions, clicks and downstream actions, de-duplicated and bot-filtered. |
| **Approval & policy** | Human review before a campaign goes live; policy for disallowed content. |
| **Billing** | Order → invoice → payment record → entitlement. |
| **Reporting** | Campaign performance for the business; revenue and fill for the platform. |

### 12.3 Campaign state machine

**[P]**

```text
draft → submitted → approved → scheduled → active ⇄ paused
                 ↘ rejected                     ↓
                                            completed → (renewed → draft)
                                            cancelled
```
Every transition is audit-logged with actor and timestamp. **[P]**

### 12.4 Non-negotiable rules

| Rule | Status |
| --- | --- |
| Sponsored items are always clearly labelled, with one consistent label platform-wide | **[C]** |
| Paid placement never alters organic relevance scoring | **[C]** |
| Sponsored items occupy fixed, capped positions on each surface | **[P]** |
| A sponsored business must still meet a minimum quality bar (verified, complete, not under moderation) | **[P]** |
| Impression and click accounting is de-duplicated, bot-filtered and immutable once aggregated | **[P]** |
| Campaign state changes and approvals are audit-logged | **[P]** |

### 12.5 Anti-scope

**[C]** No auction, no real-time bidding, no CPC/CPM/CPA pricing, no
third-party ad networks, no behavioural tracking across sites, no retargeting.
**[P]** Also no personalised ad targeting based on user profiles in V1 —
targeting is contextual (category, location, query), which keeps Bulbula clear
of the consent and profiling obligations in Ethiopia's data protection law
(R-05) and avoids building a profile store it does not need.

---

## 13. Trust and safety model

### 13.1 Confirmed capabilities

Business verification · claim verification · review moderation · user
reporting · business reporting · spam detection · duplicate detection ·
fake-review detection · admin approval · content moderation · abuse reporting
· user blocking · audit logging. Explicitly "trust mechanisms, not optional
decorations". **[C]**

### 13.2 Proposed structure

**[P] — four layers, each with a different cost profile:**

| Layer | Mechanism | Cost |
| --- | --- | --- |
| **Prevention** | Rate limits, account age/email verification before reviewing, one review per user per business, upload validation, honeypots | Cheap, automatic |
| **Detection** | Heuristics: review velocity spikes, duplicate text, same-device bursts, name/phone/coordinate collision for duplicates, link spam | Moderate |
| **Human decision** | Moderation queues with context and one-click actions; everything irreversible requires a human | Expensive — must be minimised |
| **Accountability** | Immutable audit log of every moderation and verification decision, with actor, reason and timestamp | Cheap to write, invaluable later |

### 13.3 Verification

**[P]** A verification *tier*, not a boolean, because the evidence available
in this market varies:

| Tier | Evidence | Badge |
| --- | --- | --- |
| Unverified | Listing exists (platform-created or self-submitted) | none |
| Contact-verified | Code delivered to the listed phone number answered correctly | basic |
| Owner-verified | Contact verification + claim approved by an operator | verified |
| Document-verified | Business licence / trade registration reviewed | fully verified |

**[C — research, R-02]** Mature platforms use phone/SMS, email, video and
postal verification, and treat verification as the gateway to profile control.
**[P]** Postal verification is not realistic in Addis Ababa addressing; phone
verification plus a physical/photo check is the practical analogue, and an
in-person visit is viable *because the first market is one neighbourhood* —
a genuine competitive advantage of starting hyper-local.

**[U]** What evidence is legally meaningful in Ethiopia (trade licence
numbers, TIN) and whether Bulbula may store it — see §24 and D-08/D-22.

### 13.4 Reviews

**[C — research, R-01]** The FTC's 2024 Consumer Review Rule — now in active
enforcement — bans fake and insider reviews, incentives conditioned on
sentiment, and the suppression or selective display of negative reviews. It
does *not* require platforms that merely host reviews to verify their
truthfulness, but platforms that curate or filter are squarely in scope.

**[P] Interpretation:** Bulbula is not subject to the FTC, but these are the
right operating principles for a directory whose value is trust, and adopting
them now is cheaper than retrofitting them:

1. Never delete or hide a review for being negative. Moderation criteria must
   be sentiment-neutral and published.
2. Removal reasons are limited (personal data, abuse, off-topic, fake,
   conflict of interest) and are logged.
3. Business owners may reply, flag and dispute — never delete.
4. Reviews by anyone connected to the business are prohibited; if ever
   permitted, the connection must be disclosed on the review.
5. Incentivised reviews, if ever allowed, must be disclosed and must not be
   conditioned on sentiment.
6. Show the real distribution of ratings, not a curated selection.

**[U]** Whether a review requires a verified account, a verified visit, or
nothing at all (D-12) — this single rule determines how exposed the platform
is to review fraud, and how hard it is to get the first 100 reviews.

### 13.5 Duplicates

**[P]** Duplicate detection deserves naming as a first-class job: in every
directory, duplicates arrive through seeding, owner submission and claims of
the same real business. A candidate-pair rule (similar name within a small
radius, or identical phone number) feeding a merge queue with a canonical
record and redirects is the standard answer, and the redirect behaviour has
direct SEO consequences.

---

## 14. Admin model

### 14.1 Confirmed scope

Administration of: dashboard, users, businesses, categories, locations,
services, products, reviews, reports, claims, verification, media, offers,
events. Advertising administration: campaigns, packages, placements, pricing,
approvals, billing, revenue, analytics. Platform analytics: users, businesses,
searches, popular searches, zero-result searches, profile views, interactions,
reviews, revenue, campaign performance. **[C]**

### 14.2 Proposed organising principle

**[P]** The admin product is **queues first, CRUD second**. Operators spend
their day deciding, not browsing: claims awaiting decision, verifications
awaiting evidence, reviews awaiting moderation, reports awaiting triage,
campaigns awaiting approval, duplicates awaiting merge, submissions awaiting
publication. Every queue item needs: full context on one screen, a bounded set
of actions, a mandatory reason on rejection, and an audit entry.

Structuring the admin as queues also produces the operational metric that
actually matters for a young marketplace: **decision latency** — how long a
business waits for its claim, verification or campaign approval.

### 14.3 Access model

**[C]** No additional business roles are to be invented yet.
**[P]** For the platform side, the smallest defensible model is two roles —
`operator` (works the queues) and `administrator` (operator + taxonomy,
packages, pricing, user management) — with every privileged action
audit-logged. **[U]** Whether that split is wanted at all (D-14).

---

## 15. Platform model (shared core, three clients)

### 15.1 Confirmed principle

One product, three clients, one backend; the same business, user, search,
advertising and core concepts; no three independent backends; no duplicated
business logic for Telegram. **[C]**

### 15.2 What is shared versus what is client-specific

| Layer | Shared? | Notes |
| --- | --- | --- |
| Domain model and rules (what a business is, when a review is valid, how ranking works, what a campaign may do) | **Always shared** | Lives in `src/`, never in a client |
| Authorisation rules | **Always shared** | Clients may hide a button; the server always decides |
| API contract (`/api/v1/...`) | **Shared** | Versioned from the first endpoint — already true in the baseline |
| Content: categories, taxonomy, copy of policy pages | **Shared** | One source, localised |
| Search ranking and ad delivery | **Shared** | Clients must not re-rank |
| Session/auth *mechanism* | **Client-specific** | Cookie session (web) vs bearer token (Flutter) vs Telegram `initData` → token |
| Navigation, layout, interaction | **Client-specific** | A Telegram Mini App should feel like Telegram; the web should be a website |
| SEO and structured data | **Web only** | Meaningless in Flutter/Telegram |
| Push notifications | **Flutter (+ Telegram messages)** | Web may use email |
| Offline behaviour | **Flutter mainly** | |
| Media capture (camera upload) | **Flutter/Telegram advantage** | |

### 15.3 Proposed rule for parity

**[P]** *Feature parity is a decision per feature, not a default.* The rule
that prevents drift is narrower and enforceable: **no client may implement a
product rule the backend does not implement.** A client may omit a feature; it
may not invent one.

### 15.4 API posture

**[P]** The web application should consume the **same domain services**
in-process (server-rendered for SEO and speed) rather than calling its own
HTTP API over the network — one domain, two delivery mechanisms. The JSON API
exists for Flutter and Telegram. This avoids both duplicated logic and a
pointless HTTP hop on a single shared host, and it is exactly what the current
`routes/web.php` + `routes/api.php` split already anticipates.

---

## 16. Web application

### 16.1 Confirmed expectations

Public discovery, search, business profiles, SEO, structured data, responsive
layouts, accessibility, account experiences, business dashboard, admin
experience where appropriate; fast and highly optimised for shared hosting;
SEO is a major concern. **[C]**

### 16.2 Proposed technical posture

**[P]**

- **Server-rendered HTML** for every public page (home, search, category,
  area, business profile, offers, collections). This is the only posture that
  satisfies "SEO is a major concern" + "fast on shared hosting" + "framework-
  free PHP" simultaneously.
- **Progressive enhancement** with small amounts of vanilla JavaScript for
  autocomplete, filters, galleries and the map upgrade. No SPA framework for
  public pages. (Consistent with the standing instruction that the current
  pre-launch page uses plain HTML/CSS/JS.)
- **Dashboards (business, admin)** are behind login, not indexed, and may use
  heavier client-side interaction if justified — but introducing a frontend
  framework is a decision in its own right (D-16), not an implementation
  detail.
- **Template layer:** the baseline has a deliberately minimal `PageRenderer`.
  A real view layer (layouts, partials, escaping by default) is needed before
  any product page is built. Whether that is a small in-house renderer or a
  single-purpose library (e.g. a template engine) is D-17 — and must respect
  the no-framework rule.

### 16.3 URL and information architecture

**[P] — proposed, because URLs are effectively permanent once indexed:**

```text
/                                   home
/search?q=&category=&area=&open=    search results (indexable, parameterised)
/c/{category}                       category browse
/c/{category}/{subcategory}         subcategory browse
/a/{area}                           area browse
/c/{category}/a/{area}              the money page for local SEO
/b/{business-slug}                  business profile
/b/{business-slug}/{branch-slug}    branch profile (if branches are separate)
/offers, /collections/{slug}        content surfaces
/about, /how-ranking-works, /policies/...
```

Rules: slugs are stable and never reused; the canonical URL for a business
never changes when its name changes (slug history + redirects); filter
permutations beyond a documented whitelist are `noindex` to prevent
combinatorial thin-content pages.

---

## 17. Flutter mobile application

### 17.1 Confirmed

A Flutter application for Android and iOS, consuming the same API/domain
model, not inventing different product rules. Push notifications, offline-
friendly behaviour and mobile-specific interactions are *possible* future
capabilities, not assumed for V1. **[C]**

### 17.2 Observations

**[P]**

- The mobile app is where **distance/"near me"** becomes genuinely good
  (continuous GPS, permissions, background-free but accurate), which makes it
  the natural home for the nearby/proximity experience.
- It is also the client that most needs a **stable, complete, versioned API**.
  Shipping the app before the API contract is settled guarantees a painful
  v1/v1.1 split, because mobile clients cannot be force-upgraded.
- **Sequencing recommendation:** web first, Telegram Mini App second, Flutter
  third. The web proves the domain and the content; Telegram is the cheapest
  acquisition channel in this market; the store-distributed app is the most
  expensive to iterate and should arrive once the contract is stable. **[P —
  requires approval; see D-15.]**
- Android should be prioritised over iOS given the market. **[P]**
- Mid-range Android on metered data means image sizes, payload sizes and
  caching matter as much in the app as on the web.

---

## 18. Telegram Mini App

### 18.1 Confirmed

A real product client, not a web page in a frame. To investigate later:
Telegram-specific authentication, navigation patterns, native UX
opportunities, performance limitations, account linking, shareability, deep
links, acquisition loops. No separate backend, no duplicated business logic.
**[C]**

### 18.2 What the research establishes

**[C — research, R-10]** A Mini App receives a signed `initData` payload. The
server validates it by rebuilding the data-check string from the sorted
key/value pairs (excluding `hash`), deriving an HMAC-SHA256 key from the bot
token with the constant `WebAppData`, recomputing the hash, comparing in
constant time, and rejecting stale payloads using `auth_date`. The
alternative Ed25519 flow uses Telegram's public key. `initDataUnsafe` is for
UI hints only and must never be trusted server-side.

**[P] Interpretation:** this is a small, well-defined, dependency-free piece
of PHP (`hash_hmac`, `hash_equals`) that fits the framework-free rule
comfortably. It becomes a third authentication adapter feeding the *same*
identity model: `TelegramIdentity → User`.

### 18.3 Strategic reading

**[P]** Given Telegram's unusual prominence in Ethiopia (R-06), the Mini App
is plausibly the **highest-leverage acquisition channel**, not the third
client. A shared business profile that opens inside Telegram — no install, no
account creation, instant — matches how this market already shares
information. That argues for treating "share to Telegram" as a first-class
feature of the *web* product too, and for designing business profiles to be
shareable artefacts (good preview card, deep link, fast first paint).

### 18.4 Constraints to respect

**[P]** No SEO value; constrained viewport and navigation; theme parameters
must be honoured so the app looks native in light and dark Telegram themes;
tight performance budget; account linking (Telegram identity ↔ Bulbula
account) must be designed deliberately to avoid duplicate accounts (D-13).

---

## 19. Brand and UI direction

### 19.1 Confirmed

Primary **orange**; secondary **blue**; in light mode **white is the dominant
surface**; **no finalised logo** and none is to be invented as approved; a
temporary mark may exist during development. The accent word **BULBULA.ET**,
the contact address **bulbula.et@gmail.com** and the headline "Every business
in Bole Bulbula is one search away on BULBULA.ET" are in use on the pre-launch
page; no placeholder statistics. The final design must be modern,
professional, clean, fast, usable, mobile-first where appropriate, responsive,
accessible and visually coherent — and must not imitate one existing product.
**[C]**

### 19.2 Implications

**[P]**

- Orange is a strong accent and a poor body colour; blue is the natural
  interactive/link colour; white dominates. The risk to manage is **orange
  versus the sponsored-content label** — the ad label must not compete with
  the brand accent, or "sponsored" becomes visually indistinguishable from
  "important". This is a real design constraint, not an aesthetic preference.
- **Contrast is a hard requirement, not a style choice.** Orange on white
  rarely reaches 4.5:1 for text; the design system must define which orange is
  for text, which is for large text only, and which is decoration only.
- **Two scripts.** Amharic (Ge'ez) and Latin have different vertical metrics
  and available weights; the type system must specify an Ethiopic-capable face
  (e.g. the Noto Sans Ethiopic family) and a consistent line-height strategy
  before any page design is finalised. **[U] language strategy (D-18) must be
  answered before typography is.**
- **Accessibility target:** WCAG 2.2 level AA is the appropriate target
  (**[P]**). Research (R-11) highlights the cheap-if-designed-in criteria:
  24×24 CSS px minimum target size, focus indicators that are visible and not
  obscured by sticky headers, alternatives to dragging, consistent help
  placement, no redundant re-entry, and authentication that does not depend on
  memory puzzles. Designing to these from day one costs little; retrofitting
  them is expensive.
- **No logo** means the design system must be built on **type, colour and
  layout**, with the wordmark as a placeholder slot that can be replaced
  without reflowing the UI.
- **Dark mode is unresolved** (D-19). It affects token structure, so it should
  be decided before the design system is built, even if implementation is
  deferred.

---

## 20. Technical constraints

### 20.1 Confirmed environment

| Constraint | Detail |
| --- | --- |
| Hosting | Inexpensive shared hosting (cPanel), Apache/LiteSpeed |
| Language | PHP 8.4 (cPanel `ea-php84`) |
| Database | MariaDB |
| Composer | Local PHAR (`/home/arkeonet/composer.phar`) |
| Background work | **cron only** — no daemons, no supervisors, no workers |
| CDN/DNS/WAF | Cloudflare-compatible |
| Media | Object storage / CDN such as Cloudflare R2 "where appropriate" |
| Deployment | `git fetch && git reset --hard origin/main` + `composer install --no-dev` every 10 minutes |
| Explicitly unavailable | dedicated servers, Kubernetes, Docker-only deployment, Redis, Elasticsearch/OpenSearch, RabbitMQ/Kafka, PostGIS, WebSockets, multiple app servers |
**[C]**

### 20.2 Consequences that must shape the design

**[P]**

1. **Every background job is a cron job.** No queue daemon means a
   `jobs`/`outbox` table drained by a scheduled PHP process: notification
   sending, analytics rollups, sitemap generation, search-document refresh,
   campaign expiry, image post-processing. This should be designed once,
   early, as a small internal mechanism — not reinvented per feature.
2. **No shared in-memory cache.** Caching is: Cloudflare at the edge, HTTP
   caching headers, and a **file or database cache** on the host. Cache
   invalidation strategy must be explicit because the edge is the main
   performance lever.
3. **The deploy wipes untracked files in the working tree.** Already learned
   the hard way: the cPanel PHP-handler block in `public/.htaccess` had to be
   committed, because the deployment resets the tree. **Anything the server
   needs must be in git or outside the repository directory.** User-uploaded
   media therefore *cannot* live in the repository tree — which is the
   strongest argument for object storage, independent of cost.
4. **`.env` is not in git and not created by the deploy.** The production host
   currently has no `.env`, so the app runs with defaults (`APP_ENV=local`,
   no database). Configuration provisioning is an unsolved operational step
   (D-23).
5. **Resource ceilings are unknown**: PHP memory limit, `max_execution_time`,
   concurrent connection limits, MariaDB `max_connections`, disk quota, cron
   frequency limits, whether `exec`/image libraries (GD/Imagick) are
   available. Image processing and bulk jobs depend on all of these. **[U] —
   D-20.**
6. **Email deliverability from shared cPanel hosting is poor.** Verification
   codes, claim decisions and notifications need a real sending path (an SMTP
   relay/API), or Telegram as the primary channel. **[U] — D-24.**

### 20.3 Media pipeline

**[C — research, R-12]** Cloudflare R2 charges zero egress, $0.015/GB-month
standard storage, with a 10 GB + 1 M/10 M operations monthly free tier — so a
photo-heavy directory's bandwidth is effectively free, and early-stage storage
costs are negligible.

**[P]** Proposed shape: upload → validate (type, size, dimensions, strip EXIF
including GPS) → generate a small set of fixed sizes → store originals and
derivatives in R2 → serve via a CDN domain → store only keys and metadata in
MariaDB. Never serve user media from the application host; never store it in
the repository tree. **[U]** who holds the Cloudflare account and whether R2
is provisioned (D-25); **[X]** see §31 X-01 on data residency.


---

## 21. Current engineering architecture (as built)

Verified by inspection of `main` @ `fc6188b`. **[C]**

### 21.1 What exists

| Area | Implementation |
| --- | --- |
| Entry point | `public/index.php` — single front controller; guards for PHP < 8.4 and a missing autoloader; all boot/handle failures caught and rendered (never a blank 500) |
| Document root | `public/` only; `src/ config/ routes/ database/ resources/ tests/ vendor/ .env` are outside it, asserted by tests |
| Web server config | `public/.htaccess`: tracked cPanel `ea-php84` handler block, `DirectoryIndex index.php`, rewrite of non-file/non-directory requests to the front controller. No `Options` directive (it 500s the whole site on this host) |
| Composition | `Bulbula\Foundation\Application::boot()` → dotenv (optional) → `Config` → `Environment` → timezone → Monolog logger → error handler. `Services` is a typed lazy factory, **not** a container |
| HTTP | `Request`, `Response`, `Status`, `Method`, `Kernel`, `ResponseEmitter`, `HttpErrorHandler`, `BootstrapFailure` |
| Routing | `nikic/fast-route` behind a thin `Router`; `routes/web.php` and `routes/api.php` each return `Closure(Router, Services): void`; named routes + `UrlGenerator`; 404/405 as typed exceptions |
| Middleware | `Middleware` contract + `Pipeline` (global and per-route); one implementation: `SecureHeaders` (CSP, nosniff, frame options, referrer policy, permissions policy, HSTS on HTTPS) |
| Controllers | `HomeController`, `HealthController` — thin HTTP boundaries; arch tests forbid PDO in controllers and `Bulbula\Http` in service namespaces |
| Database | PDO/MariaDB: `DatabaseConfig`, `ConnectionFactory` (the only `new PDO`), `Connection` with `select/selectOne/execute/statement/transaction/ping`; prepared statements only; credentials never in messages or logs; tests run on in-memory SQLite |
| Migrations | `Migration`, `MigrationLocator`, `MigrationRepository`, `Migrator`, batch tracking, `--steps` rollback; one migration exists (`health_checks`, deliberately infrastructural) |
| Console | `bin/console` (`migrate`, `migration:status`, `rollback`); the only file that calls `exit()` |
| Diagnostics | `/health`, `/api/v1/health` (liveness, no DB) and `/health/ready`, `/api/v1/health/ready` (DB ping + migrations table, 503 on failure, `no-store`) |
| Views | `resources/views/home.html` rendered by `PageRenderer`; outside the document root |
| Quality gates | PHPStan **max**, Pest (424 tests / 1033 assertions), 100 % line coverage, 100 % type coverage, Infection **MSI 100 %**, Pint, Rector, PHPInsights, architecture tests (incl. a forbidden-framework test), `composer audit`, gitleaks |
| CI | `.github/workflows/ci.yml`, `mutation.yml` (main pushes), `security.yml` |
| Dependencies | Runtime: `monolog/monolog`, `psr/log`, `nikic/fast-route`, `vlucas/phpdotenv`, `ext-pdo`, `ext-pdo_mysql`. That is the entire runtime dependency list |

### 21.2 What does not exist

No users, accounts, authentication, sessions, CSRF protection, rate limiting,
authorisation, businesses, categories, locations, reviews, search, media
handling, notifications, advertising, billing, analytics, admin, API beyond
health, pagination, validation layer, form handling, view layer beyond static
page rendering, i18n, or caching. **[C]**

### 21.3 Assessment

**[P]** The baseline is well-suited to what comes next, with three gaps that
the first product phase will immediately hit, and which should be planned as
deliberate work rather than discovered mid-feature:

1. **No input validation / form handling layer.** Every product feature needs
   it; writing it ad hoc in controllers would violate the controller contract
   the architecture tests enforce.
2. **No session/auth primitives.** Documented as intentional ("they belong to
   the phase that introduces users") — that phase is next.
3. **No view layer beyond static rendering.** Needed before the first dynamic
   page.

A fourth, softer risk: **100 % coverage and 100 % MSI are easy to hold on
3 000 lines of infrastructure and much harder on a large domain**. The
standing instruction not to weaken the gates is understood; what should be
decided consciously, before the domain grows, is whether the thresholds apply
to the whole codebase forever or whether some categories (e.g. thin
view/template code) are excluded by configuration rather than by lowering a
number. **[P] — D-26.**

---

## 22. Performance and scalability considerations

### 22.1 Targets

**[P]** Adopt the Core Web Vitals "good" thresholds as product requirements
for public pages, measured at the 75th percentile (R-13): **LCP ≤ 2.5 s, INP
≤ 200 ms, CLS ≤ 0.1**. On a mid-range Android device over a 3G-class
connection, which is the realistic field condition for this market, this
implies a hard page budget: **[P]** ≤ 150 KB of critical HTML/CSS, ≤ 100 KB of
JavaScript on public pages, images lazy-loaded with explicit dimensions, fonts
subset and preloaded, and no third-party script before first interaction
(including the map embed).

### 22.2 Where the load actually falls

| Surface | Load profile | Mitigation |
| --- | --- | --- |
| Business profile pages | High read volume, slow-changing | Edge-cacheable for anonymous visitors; the single best Cloudflare win |
| Search results | Dynamic, parameterised, the most expensive query | Index design + candidate limiting + short-TTL cache for popular queries |
| Category/area pages | Read-heavy, slow-changing | Edge-cacheable, paginated |
| Media | Potentially the largest bytes | Never from the app host — object storage + CDN |
| Event ingestion (views, clicks, impressions) | High write volume, low value per row | Append-only, batched, aggregated by cron |
| Dashboards | Low volume, expensive queries | Read from pre-aggregated rollups, never from raw events |

### 22.3 Analytics without a warehouse

**[P]** The confirmed analytics list (profile views, search impressions,
search appearances, phone clicks, website clicks, direction requests,
favourites, review stats, popular services/products, traffic trends, campaign
performance) is substantial for a shared host. Proposed model:

```text
request time   append a compact event row (or increment a daily counter row)
cron (hourly)  aggregate raw events → daily rollups per entity/metric
cron (daily)   prune or archive raw events beyond the retention window
dashboards     read only rollups
```

Granularity, retention and whether raw events are kept at all are open
(D-27) — and they are *cost* decisions on shared hosting, not just product
decisions.

### 22.4 Named triggers for re-evaluating infrastructure

**[P]** Rather than "scale later", the project should record the thresholds
that would justify new infrastructure, so the decision is evidence-driven:

| Trigger | Consider |
| --- | --- |
| Search p95 > 300 ms at the database, with indexes tuned | A dedicated search engine or a denormalised index table |
| > ~50 000 active listings, or multi-city live | Re-examining the search and location model |
| Analytics rollup job cannot finish within its cron window | Pre-aggregation at write time, or a separate analytics store |
| Sustained CPU/memory throttling by the host | Moving to a VPS (still a monolith) |
| Notification volume beyond shared-host sending limits | A transactional email/messaging provider |

---

## 23. SEO considerations

### 23.1 Why it is structural here

SEO is a confirmed major concern, and for a directory it is not a marketing
add-on: organic discovery is the first half of the confirmed revenue model.
Every structural decision in §16.3 (URLs, canonicals, pagination, indexable
filters) is an SEO decision. **[C + SI]**

### 23.2 Research-driven positions

**[P], grounded in research:**

| Finding | Consequence for Bulbula |
| --- | --- |
| Google's local ranking rests on relevance, distance and prominence (R-14) | Bulbula's *own* ranking model should be legible in the same terms — it is the model users and business owners already understand |
| "Self-serving" review markup is ineligible for rich results for `LocalBusiness`/`Organization` — but a site publishing reviews **about other businesses** (a directory) is explicitly outside that restriction (R-15) | Bulbula's business pages **may** legitimately mark up `AggregateRating`/`Review` and be eligible for star rich results — a genuine, durable SEO advantage over each business's own website. It also raises the stakes on review authenticity |
| Being listed as closed at search time measurably reduces local visibility (R-14) | Accurate, current opening hours are an SEO asset, not just a UX detail |
| Core Web Vitals thresholds (R-13) | Public pages must hit them; the map embed is the main threat |

### 23.3 Proposed SEO rules

**[P]**

1. One canonical URL per entity; slug history with 301s; never reuse a slug.
2. `LocalBusiness` (and appropriate subtype) structured data on every profile
   page, with address, geo, hours, phone, price range, and aggregate rating
   where real reviews exist; `BreadcrumbList` on hierarchical pages;
   `ItemList` on category/area pages.
3. Indexable: home, category, subcategory, area, category×area, business
   profiles, collections, offers index. `noindex`: internal search result
   permutations outside a whitelist, empty category×area pages, user
   dashboards, anything paginated beyond a sane depth.
4. **Thin content is the main risk.** A category×area page with two listings
   should not be indexable; a page template that can generate thousands of
   near-empty permutations will attract exactly the wrong kind of attention.
   Index pages only above a minimum-content threshold.
5. XML sitemaps generated by cron, segmented, with `lastmod` driven by real
   content changes.
6. Bilingual URL/`hreflang` strategy depends on the language decision (D-18)
   and should be settled *before* the first indexable page ships.
7. The platform's own content (collections, area guides) is the only
   defensible answer to "why would Google rank Bulbula above the business's
   own site" — worth planning, out of scope for V1. **[P]**

---

## 24. Security considerations

### 24.1 Inherited posture

The baseline already ships: strict CSP and security headers on every
response, HSTS over HTTPS, prepared statements everywhere, credentials never
logged or exposed in exceptions, opaque production errors with a reference
id, JSON encoded with HTML-safe flags, a single web entry point with the
application outside the document root, secret scanning and dependency
auditing in CI. **[C]**

### 24.2 What the product phase adds

**[P]** Each of these is new attack surface that does not exist today:

| Area | Requirement |
| --- | --- |
| Authentication | Password hashing (`password_hash`, Argon2id/bcrypt), secure session cookies (`HttpOnly`, `Secure`, `SameSite`), session fixation protection, login throttling, password reset with single-use expiring tokens |
| Authorisation | Explicit, server-side, per-resource ("may this user edit this business?"); never inferred from the client |
| CSRF | Token protection on every state-changing form; the CSP already helps but is not a substitute |
| Rate limiting | On login, registration, review submission, claim submission, search, and all write APIs — database-backed, since no Redis exists |
| File uploads | Type and size validation by content, image re-encoding, EXIF/GPS stripping, randomised storage keys, never executable, never served from the app host |
| Multi-tenancy | Every business-scoped query filtered by ownership; IDOR is the most likely real-world vulnerability in a dashboard product |
| Telegram auth | Constant-time HMAC validation + `auth_date` freshness (R-10); never trust `initDataUnsafe` |
| API tokens | Scoped, revocable, hashed at rest; no long-lived unrevocable tokens for the mobile app |
| Admin | Separate authentication path, mandatory audit log, ideally IP/2FA gating |
| Moderation | Treat all user content as hostile: escaping by default in the view layer, link `rel="nofollow ugc"`, no HTML from users |

### 24.3 Legal and privacy constraints — Ethiopian law

**[C — research, R-05]** Ethiopia's **Personal Data Protection Proclamation
No. 1321/2024** is in force and closely mirrors the GDPR: lawful bases
including consent, consent that must be freely given, specific, informed,
unbundled and withdrawable (with the burden of proof on the controller), data
subject rights (access, rectification, erasure, restriction, objection,
portability), controller/processor registration with the Ethiopian
Communications Authority, breach notification within 72 hours, a Data
Protection Officer requirement, special protection for minors' data
(including a prohibition on processing minors' data for marketing or
profiling), and a **data sovereignty rule requiring personal data collected
locally to be stored on servers or data centres located in Ethiopia**, with
conditions on cross-border transfer.

**[P] Consequences Bulbula cannot ignore:**

1. Accounts, reviews, favourites, business contacts and analytics tied to
   identifiable people are all personal data under this law.
2. A privacy notice, a lawful basis per processing purpose, and a real
   consent mechanism are **V1 requirements**, not polish.
3. Erasure and access requests need an actual operational path.
4. The 72-hour breach notification duty implies logging and an incident
   procedure (also listed in the documentation plan, §33).
5. **The data residency rule collides with the planned Cloudflare R2 media
   storage and with Cloudflare/GitHub as processors** — see §31 **X-01**.
   This needs legal input, not an engineering guess.

**[U]** Whether registration with the ECA applies to a platform of this size,
and what the practical enforcement posture is (D-22).

---

## 25. Provisional V1 scope

> **Provisional.** Nothing in this section is approved. It exists to give the
> PRD a starting position and to make the trade-offs visible. **[P]**

The organising question used throughout: *does this make the directory worth
searching in one neighbourhood, or does it serve a later ambition?*

### 25.1 V1 — likely required

| # | Capability | Why it is required |
| --- | --- | --- |
| 1 | Public business profiles with the core fields (name, category, description, address + coordinates, hours, phone, website, social, logo, cover, gallery) | The product's atomic unit |
| 2 | Category + subcategory taxonomy (curated, two levels) | Navigation, search, SEO, ad targeting all depend on it |
| 3 | Location hierarchy with one populated area | Avoids a painful retrofit; costs little now |
| 4 | Keyword search + core filters (category, area, open now, verified) + sorting | The core verb of the product |
| 5 | Category, subcategory and area browsing pages | Discovery for users who do not search; the SEO surface |
| 6 | Homepage discovery surfaces (featured, popular, new) | Entry experience |
| 7 | Open/closed status from structured hours | Confirmed filter; a visible quality signal |
| 8 | Embedded map + "Open in Google Maps" | Confirmed; the contact/visit step |
| 9 | Tracked contact actions (call, website, directions) | The value proof for businesses and the basis of all analytics |
| 10 | Business account + create/claim + owner profile management | The supply side cannot exist without it |
| 11 | Verification workflow (at least contact-verified + a visible badge) | Confirmed trust mechanism; differentiator vs existing directories |
| 12 | Admin: queues for submissions, claims, verification, reports; taxonomy and location management | Nothing user-generated can ship without an operator path |
| 13 | Customer accounts (register/login) + favourites + reviews with moderation | Confirmed loop closure ("Save / Review"); guest browsing stays open |
| 14 | Report/suggest-an-edit path | Data decay is the primary quality risk |
| 15 | Media pipeline to object storage | Images are most of the perceived quality of a directory |
| 16 | Basic business analytics (views, search appearances, contact actions, over time) | The promise made to businesses at onboarding |
| 17 | SEO foundation (URLs, canonicals, structured data, sitemaps, metadata) | Organic discovery is half the business model |
| 18 | Advertising **foundation**: placements, packages, campaigns, approval, scheduling, delivery with labelling, impression/click accounting — administered by staff, not self-service | Confirmed first-class subsystem; building it in from the start is cheaper than retrofitting; selling can start manually |
| 19 | Audit log + privacy notice + consent + data-subject request path | Law, not polish |
| 20 | Notifications over at least one channel (verification codes, claim decisions) | Several V1 flows are impossible without it |

### 25.2 V1 candidate — valuable, needs prioritisation

- Services and products with prices (and therefore the price filter)
- FAQs on profiles
- Offers (time-bounded promotions)
- Distance/radius filtering and "nearby" (needs geolocation UX + accurate
  coordinates for every listing)
- Autocomplete
- Review replies by owners
- Branches/multi-location management
- Collections (curated lists) — cheap editorially, strong for SEO and ads
- Amharic interface (as opposed to Amharic *content*) — depends on D-18
- Telegram Mini App v1 (read-only discovery + share)
- Self-service advertising purchase and invoicing
- Business "profile completeness" guidance UI
- Platform analytics dashboard beyond raw counts

### 25.3 Future — explicitly not V1

Events · jobs · local news/articles · deals/coupons as a separate module ·
subscriptions · loyalty · reservations · online ordering · digital menus ·
customer↔business messaging · AI search · AI recommendations · auction-based
advertising · multi-city expansion UI · Flutter application · banner ad
network · reviews with photos · video hosting (as opposed to links) ·
recommendation personalisation · public API for third parties. **[C — all
named as future in the brief; the Flutter sequencing is [P].]**

### 25.4 The honest observation about V1

**[P]** Even the "likely required" list above is a large V1 for a small team —
roughly four product domains (content, accounts/trust, admin, advertising
foundation) plus SEO and media. Two legitimate ways to cut it exist, and the
choice should be explicit rather than accidental:

- **Cut A — "Directory first":** ship 1–9, 12, 14, 15, 17, 19 (public
  directory, admin-curated, no public accounts). Fastest path to something
  worth visiting; the business loop and reviews arrive next. Risk: no supply-
  side self-service, so all data entry is operator work.
- **Cut B — "Two-sided from day one":** everything in 25.1. Slower, but the
  business loop (claim → verify → analytics) is what eventually produces
  revenue, and it is the loop that needs the most iteration.

This is decision **D-01**, and it is the first decision the PRD must make.

---

## 26. Future scope

Beyond §25.3, three structural items deserve naming now because they would
change the architecture if they arrived unplanned: **[P]**

1. **Transactions** (ordering, reservations, payments between customer and
   business) would turn Bulbula from a directory into a marketplace, bringing
   money handling, disputes and refunds. Nothing in V1 should assume it will
   never happen, but nothing should be built for it either.
2. **Multi-city** changes search (location-scoped ranking), SEO (city-level
   pages and sitemaps), advertising (location sponsorship by city) and
   operations (moderation per city). The location hierarchy in V1 is the
   single cheap preparation.
3. **A public API / data syndication** (other apps consuming Bulbula data)
   would make the API contract a published product with compatibility
   obligations.

---

## 27. Confirmed decisions

Everything in this list is explicitly decided and may be treated as a
requirement by later documents. **[C]**

### Product
1. Bulbula is a local business discovery platform, first market Bole Bulbula,
   Addis Ababa.
2. The platform must be able to expand to other areas, cities and beyond,
   without national-scale complexity in V1.
3. Core customer loop: discover → search → compare → profile → contact/visit
   → save/review.
4. Core business loop: create/claim → manage → verify → be discovered →
   promote → analyze.
5. Guest browsing remains possible.
6. The business profile is a Bulbula-owned experience; Google Maps is embedded
   for location, with an "Open in Google Maps" hand-off.
7. No OSM tile server, routing engine, map server, satellite infrastructure or
   PostGIS.
8. Revenue: organic discovery + sponsored placement + advertising + future
   premium services.
9. Advertising is a first-class subsystem, not a `featured` flag.
10. Initial monetisation is **fixed packages**; no auction/CPC/CPM/CPA.
11. Sponsored content is always clearly labelled and clearly distinguishable.
12. Paid placement must never secretly manipulate organic ranking.
13. Organic ranking considers relevance, distance, rating, review quality,
    profile completeness, popularity, freshness, open status, engagement.
14. Trust and safety capabilities are requirements, not decorations.
15. No additional business roles beyond the business account until a real
    requirement exists.
16. Future modules (deals, events, jobs, news, subscriptions, loyalty,
    reservations, ordering, menus, messaging, AI) are not V1 unless
    explicitly approved.

### Platform
17. One product, three clients (web, Flutter, Telegram Mini App), one backend,
    one shared domain model; no duplicated business logic.
18. The web application is a full platform (discovery, SEO, accounts,
    dashboards), fast and optimised for shared hosting.
19. The Flutter app targets Android and iOS and consumes the same API.
20. The Telegram Mini App is a real client, not an embedded web page, and gets
    no separate backend.

### Brand and UI
21. Primary orange, secondary blue; white dominates light mode.
22. No finalised logo; none may be invented and treated as approved.
23. The design must be modern, professional, clean, fast, usable,
    mobile-first where appropriate, responsive, accessible and coherent, and
    must not imitate a single existing product.
24. Research informs design decisions; interfaces are not copied.

### Engineering
25. **Framework-free plain PHP.** No Laravel, Symfony, Mezzio, Laminas,
    Dotkernel, Slim, Flight, or home-grown framework. Small single-purpose
    libraries are acceptable.
26. Architecture is a **modular monolith**, not microservices; avoid service
    sprawl, excessive abstraction, premature events and accidental framework
    creation.
27. Quality gates (PHPStan max, tests, coverage, mutation testing,
    architecture tests, security checks, dependency auditing) must not be
    weakened; failures are fixed, not suppressed.
28. Infrastructure assumptions: cPanel/Apache/LiteSpeed, PHP 8.4, MariaDB,
    Composer PHAR, cron, Cloudflare; **no** Redis, Elasticsearch, message
    broker, PostGIS, WebSockets, Kubernetes or multiple app servers.
29. Media belongs in object storage/CDN (e.g. Cloudflare R2) where
    appropriate.
30. Work happens on feature branches with PRs; `main` is protected by CI;
    `vendor/` and `.env` are never committed; repository configuration is the
    source of truth for the server.

### This phase
31. This phase produces understanding only: no product features, no PRD, no
    TRD, no final UI, no logo, no AI context files; the report is reviewed
    before anything else proceeds.

---

## 28. Strongly implied requirements

Logical consequences of §27 that have **not** been explicitly approved. Each
should be either confirmed or rejected during review. **[SI]**

| # | Implied requirement | Implied by |
| --- | --- | --- |
| SI-1 | User accounts, authentication, sessions and authorisation must exist | Favourites, reviews, dashboards, admin |
| SI-2 | A location hierarchy (city → sub-city → area) rather than flat address text | "Expandable but not national-scale in V1" + area browsing + location sponsorship |
| SI-3 | A curated category taxonomy with at least two levels | Category and subcategory browsing, category sponsorship, relevance |
| SI-4 | Structured opening hours, including exceptions | "Open now" filter, open-status ranking, holiday hours management |
| SI-5 | Interaction event tracking (view, phone click, website click, direction request) | Business analytics list + campaign performance list |
| SI-6 | Pre-aggregated analytics rather than live queries over raw events | Analytics breadth + shared hosting |
| SI-7 | A moderation/approval queue for every user-generated artefact | Admin approval, review moderation, claim verification, campaign approval |
| SI-8 | An immutable audit log of privileged actions | "Audit logging" + campaign approvals + verification decisions |
| SI-9 | A notification mechanism (at least one channel) | Verification codes, claim decisions, campaign status, review alerts |
| SI-10 | Finite, explicitly modelled ad inventory with availability checks | Fixed packages + "placement" + "expiration" |
| SI-11 | An ad delivery step separate from the organic ranking step | "Paid placement must not manipulate organic ranking" |
| SI-12 | A background job mechanism driven by cron | Rollups, sitemaps, campaign expiry, notifications, no daemons |
| SI-13 | Image processing and derivative generation | Logo, cover, gallery, performance budget |
| SI-14 | A server-side validation layer and a view layer | Any form-driven product on this baseline |
| SI-15 | Slug/permalink management with history and redirects | SEO + renaming businesses |
| SI-16 | Pagination everywhere lists exist | Search, categories, areas, reviews, admin queues |
| SI-17 | Soft deletion / state rather than hard deletion for listings and reviews | Moderation, audit, dispute handling |
| SI-18 | A privacy notice, consent capture and data-subject request handling | Operating in Ethiopia under Proclamation 1321/2024 |
| SI-19 | API versioning discipline and a deprecation policy | A store-distributed mobile client that cannot be force-upgraded |
| SI-20 | A design token system (colour, type, spacing) shared conceptually across clients | "Visually coherent" across three clients with one brand |

---

## 29. Proposed decisions (require approval)

Recommendations from this analysis. None is a requirement until approved.
**[P]**

| # | Proposal | Rationale | Cost of being wrong |
| --- | --- | --- | --- |
| PR-1 | Build the advertising subsystem's **data model and delivery path** in V1, but sell only through administrators until there is traffic | Satisfies "first-class subsystem" without building self-service billing for zero impressions | Low — the self-service layer is additive |
| PR-2 | Model `Business` with an explicit `Branch`, even when there is exactly one | Avoids the worst migration in the project; branches are confirmed | Low now, very high later |
| PR-3 | Two-stage search (candidate selection → weighted scoring) with **all weights in configuration** and an admin "explain this ranking" view | Ranking will be tuned constantly; explainability is also the integrity defence for the ad/organic separation | Medium |
| PR-4 | A composed, denormalised **search document** per business, refreshed on write, including bilingual aliases and transliterations | The only way to get usable relevance from MariaDB FULLTEXT in a bilingual market | Medium |
| PR-5 | Bounding box + great-circle distance in SQL for proximity; no geospatial extension | Satisfies distance filtering within the confirmed infrastructure limits | Low |
| PR-6 | Verification **tiers** (contact → owner → document) rather than a boolean | Matches the evidence actually obtainable in this market; makes the badge meaningful | Medium |
| PR-7 | Adopt FTC-grade review integrity rules as internal policy (no sentiment-based suppression, published criteria, disclosed connections) | Trust is the product; retrofitting integrity rules after a reputation problem is far costlier | Low |
| PR-8 | One sponsored label, one visual treatment, used identically on all three clients, plus a public "how ranking works" page | Confirmed labelling requirement + the clearest available regulatory standard | Low |
| PR-9 | Invoice/record-of-payment billing with out-of-band payment first; gateway integration later | Matches Ethiopian payment reality and licensing friction | Low |
| PR-10 | Append-now / aggregate-by-cron analytics with explicit retention | Only viable model on shared hosting | Medium |
| PR-11 | Server-rendered web with progressive enhancement; no SPA for public pages | SEO + performance + framework-free, simultaneously | High if reversed late |
| PR-12 | Client sequencing: **Web → Telegram Mini App → Flutter** | Telegram is the cheapest acquisition channel in this market; mobile apps need a stable API | Medium |
| PR-13 | "No client may implement a product rule the backend does not implement" as the parity rule | Prevents three divergent products without forcing feature-for-feature parity | Low |
| PR-14 | WCAG 2.2 AA as the accessibility target, designed in from the first component | Cheap now, expensive later; several criteria are purely design decisions |  Medium |
| PR-15 | Treat a customer-facing "suggest an edit / report a problem" path as V1 | Data decay is the primary quality risk for any directory | Low |
| PR-16 | Pre-seed listings operationally for the first neighbourhood, making **claim** the primary business entry path | A directory with no listings has no customers, and therefore no businesses | High either way — this is D-02 |
| PR-17 | A single internal cron-driven job mechanism, designed once, before the first feature needs it | Five features will otherwise invent five mechanisms | Low |
| PR-18 | Decide the language strategy (English-first, Amharic-first, or bilingual) **before** the first product page is designed | It changes typography, URLs, search, content model and SEO | High if deferred |

---

## 30. Unknowns

Grouped by what they block. Each appears in the decision register (§32) where
it needs a decision; items here that are purely *missing information* are
marked "research/ask".

### Market and product
| # | Unknown | Blocks |
| --- | --- | --- |
| U-1 | How many businesses exist in Bole Bulbula; how many are reachable | V1 sizing, seeding plan, launch criteria |
| U-2 | What customers actually search for, and in which language/script | Search model, taxonomy, language strategy |
| U-3 | Whether businesses will pay, and what for | Package design, pricing, revenue forecast |
| U-4 | Who the first 100 listings and first 10 paying businesses are | Go-to-market, launch definition |
| U-5 | Definition of launch success (traffic? listings? reviews? revenue?) | Everything downstream of prioritisation |
| U-6 | Whether any competitor is active in this specific niche locally | Positioning |

### Product rules
| # | Unknown | Blocks |
| --- | --- | --- |
| U-7 | Who may write a review, and under what verification | Review system, anti-fraud, trust |
| U-8 | What evidence verifies a business, and who decides | Verification, claims, admin workload |
| U-9 | Whether listings are platform-seeded or owner-created | Onboarding, claims, data provenance, legal basis |
| U-10 | The category taxonomy itself (list, depth, naming, bilingual labels) | Navigation, search, SEO, ad targeting |
| U-11 | The location hierarchy's actual levels and names for Addis Ababa | URLs, filters, SEO |
| U-12 | Exact advertising products and their inventory limits | Advertising subsystem specifics |
| U-13 | Pricing, currency, invoicing and tax obligations | Billing |
| U-14 | Notification channels available and preferred (email? SMS? Telegram?) | Verification flows, retention |
| U-15 | Moderation SLA and operator capacity | Queue design, launch scale |
| U-16 | Media limits (count, size, formats, video hosting vs linking) | Media pipeline, storage cost |

### Technical and operational
| # | Unknown | Blocks |
| --- | --- | --- |
| U-17 | Host resource limits (memory, execution time, cron frequency, DB connections, disk, GD/Imagick availability) | Image pipeline, job design, analytics |
| U-18 | Whether MariaDB server variables can be tuned at all | Search design |
| U-19 | Whether a Google Maps API key with billing exists | Profile page map |
| U-20 | Whether a Cloudflare account + R2 bucket exist, and who owns them | Media pipeline |
| U-21 | How `.env` reaches the production host, and who holds production credentials | Deployment, security |
| U-22 | Backup and restore arrangements for MariaDB and media | Operations, risk |
| U-23 | Legal status: registered entity, trade licence, ECA registration obligations, DPO requirement | Compliance, billing, verification evidence handling |
| U-24 | Whether data residency (local storage of personal data) is enforced in practice | Hosting and media architecture — see X-01 |
| U-25 | Team composition and realistic velocity | Roadmap credibility |

---

## 31. Conflicts

Genuine inconsistencies between stated constraints. Each needs a decision,
not a workaround invented silently.

### X-01 — Data residency versus Cloudflare R2 (and every foreign processor)

- **Side A [C]:** Proclamation 1321/2024 requires personal data collected
  locally to be stored on servers or data centres located in Ethiopia, with
  conditions on cross-border transfer (R-05).
- **Side B [C]:** media is expected to live in Cloudflare R2; the site sits
  behind Cloudflare; the code lives on GitHub; the host itself may not be in
  Ethiopia.
- **Why it matters:** business photos are arguably not personal data, but user
  avatars, review content, uploaded documents for verification and analytics
  tied to accounts are. This is a legal question with architectural
  consequences (where the database lives, where uploads go).
- **Resolution needed:** legal advice, then a documented data-classification
  policy (which classes may leave Ethiopia, which may not). **Not an
  engineering decision.** → D-22.

### X-02 — "Distance/radius search" versus "no geospatial infrastructure"

- **Side A [C]:** search must support distance/radius filtering and distance
  sorting; nearby businesses is a discovery surface.
- **Side B [C]:** no PostGIS, no mapping infrastructure.
- **Assessment:** *resolvable, not a true conflict.* Bounding box + haversine
  on indexed decimal columns is sufficient at neighbourhood and city scale
  (§10.5). Recorded here so nobody later "discovers" the need for PostGIS and
  treats it as a licence to add it. → PR-5.

### X-03 — Advertising as a first-class subsystem versus a small V1

- **Side A [C]:** advertising must not be modelled as a flag; it is a major
  business requirement with campaigns, placements, approval, billing and
  performance.
- **Side B [C]:** do not add unnecessary infrastructure; V1 should be the
  smallest thing that works; the audience does not exist yet.
- **Assessment:** resolvable by splitting *model* from *commerce* (PR-1), but
  it must be an explicit decision, because "first-class" could also mean
  "fully self-service from day one". → D-01/D-10.

### X-04 — 100 % coverage and 100 % MSI versus domain growth

- **Side A [C]:** quality gates must not be weakened.
- **Side B:** the current thresholds were set on ~3 000 lines of
  infrastructure with no UI, no third-party integrations and no templates.
  Holding literal 100 % line coverage and 100 % MSI across a large product
  domain (including view code and integration adapters) is an unusual
  standard that will eventually be met by writing tests that assert
  implementation detail — which lowers real quality while keeping the number.
- **Assessment:** this is a tension, not a contradiction. The decision to make
  consciously is *what the gate applies to*, not *what the number is*. → D-26.

### X-05 — "No invented logo" versus launching a consumer brand

- **Side A [C]:** no finalised logo; none may be treated as approved.
- **Side B [SI]:** a public launch needs a wordmark, a favicon, an app icon
  (required by both app stores) and a Telegram Mini App icon.
- **Assessment:** needs an explicit "temporary mark" policy: what the
  placeholder is, where it may appear, and the commitment that it is
  replaceable without layout changes. → D-28.

### X-06 — Historical, already resolved

An earlier instruction said the main product would be built in **Laravel
(latest version)**. It was later superseded by the standing architectural
rule that **Bulbula must remain plain PHP with no framework**. Recorded here
only so the earlier statement is never resurrected as a requirement. **[C —
resolved in favour of framework-free PHP.]**

---

## 32. Open decisions register

Decisions that must be made before or during the PRD. **No decision below has
been made in this document.** Priority: **P0** blocks the PRD, **P1** blocks
V1 design, **P2** blocks implementation of a specific area.

| ID | Decision | Priority | Depends on / notes |
| --- | --- | --- | --- |
| D-01 | **Exact V1 scope** — Cut A (directory first) or Cut B (two-sided) or another | **P0** | §25; the single most consequential decision |
| D-02 | Are listings pre-seeded by the platform, owner-created, or both? | **P0** | U-9; drives claims, verification, legal basis, admin load |
| D-03 | Business/branch model: implicit branch or not; where reviews, hours, analytics attach | **P0** | §11.3; migration cost if wrong |
| D-04 | Opening-hours model (regular, exceptions, temporary closure, by appointment, 24h) | P1 | "Open now" filter + ranking |
| D-05 | Which discovery surfaces exist in V1 (offers, events, collections, recommended, nearby) | P1 | §6, §25 |
| D-06 | Category taxonomy: source, depth, naming, bilingual labels, who may extend it | **P0** | U-10; affects URLs and SEO permanently |
| D-07 | Price representation (free text / numeric / range / "from") and whether a price filter ships | P1 | Confirmed filter vs data reality |
| D-08 | Verification rules: tiers, evidence, decision-maker, expiry, re-verification on edit | **P0** | §13.3; legal handling of documents |
| D-09 | Definition of "profile completeness" and its weight in ranking | P1 | Confirmed ranking factor |
| D-10 | Exact V1 advertising products, inventory caps per surface, and the sponsored label wording | **P0** | §12; X-03 |
| D-11 | Billing approach: invoices + manual payment vs gateway; currency, tax, receipts | P1 | §8.4 |
| D-12 | Review eligibility (account required? verified visit? one per business?) and moderation criteria | **P0** | §13.4; fraud exposure |
| D-13 | Identity model across clients: one account with linked Telegram/phone/email identities, or separate | P1 | §18.4; duplicate accounts are hard to merge later |
| D-14 | Admin roles: single `administrator` or `operator` + `administrator` | P2 | §14.3 |
| D-15 | Client sequencing and whether Flutter is in the first year at all | P1 | PR-12 |
| D-16 | Whether dashboards may use a frontend framework, and if so which | P1 | Must not contradict the no-framework rule (which is about PHP) |
| D-17 | View/template layer: in-house renderer or a single-purpose library | P1 | §21.3 |
| D-18 | **Language strategy**: English-only, Amharic-only, or bilingual; UI vs content; URL strategy | **P0** | Affects search, typography, SEO, taxonomy, data model |
| D-19 | Dark mode: yes/no/later | P1 | Token structure |
| D-20 | Confirmed host resource limits and whether MariaDB tuning is possible | P1 | U-17/U-18; ask the host |
| D-21 | Google Maps: API key, billing owner, embed strategy, fallback | P1 | U-19 |
| D-22 | **Legal/compliance**: entity, ECA registration, DPO, data residency, document retention | **P0** | X-01; needs external advice |
| D-23 | Production configuration management (`.env` provisioning, secret custody, rotation) | P1 | U-21 |
| D-24 | Notification channels for V1 and the sending provider | P1 | U-14; blocks verification flows |
| D-25 | Media: storage provider/account, limits per business, formats, video policy | P1 | U-16/U-20 |
| D-26 | What the coverage/MSI gates apply to as the domain grows (scope, not threshold) | P1 | X-04 |
| D-27 | Analytics granularity, retention and whether raw events are kept | P1 | §22.3; cost decision |
| D-28 | Temporary brand mark policy (placeholder wordmark, favicon, app icons) | P1 | X-05 |
| D-29 | Documentation versioning model and approval authority (who says "Approved") | P1 | §33 |
| D-30 | Definition of launch: the measurable bar for going public | **P0** | U-5 |

---

## 33. Documentation architecture proposal

**[P] — proposal only; nothing is created in this phase.**

### 33.1 Principles

1. **One fact, one home.** Every statement has exactly one authoritative
   document. Others link to it. Duplication is how documentation sets rot.
2. **Numbered directories** so order is visible in a file listing and
   references are stable (`docs/20-technical/...`).
3. **Front matter on every document** carrying status, version, owner, date
   and supersession — machine-checkable in CI later.
4. **Documentation lives in the repository**, reviewed through pull requests
   like code. The repository is already the source of truth for the server;
   it should be for decisions too.
5. **Small documents beat large ones.** A 40-page PRD is read once; eight
   focused documents are read repeatedly.

### 33.2 Proposed hierarchy

```text
docs/
├── README.md                         index + how to navigate + status legend
├── 00-discovery/
│   ├── project-understanding-v0.1.md this document
│   └── research-notes-v0.1.md        sourced findings
├── 10-product/
│   ├── product-vision.md             why Bulbula exists, 2–3 pages, rarely changes
│   ├── product-overview.md           what it is, for whom, capability map
│   ├── prd.md                        the specification of what V1 is
│   ├── personas.md
│   ├── user-journeys.md
│   ├── feature-specs/                one file per non-trivial feature
│   │   ├── search.md
│   │   ├── business-profile.md
│   │   ├── claims-and-verification.md
│   │   ├── reviews.md
│   │   └── advertising.md
│   └── roadmap.md                    phases + what is explicitly deferred
├── 20-business/
│   ├── business-model.md
│   ├── advertising-model.md          products, inventory, labelling policy
│   ├── pricing.md
│   ├── business-owner-model.md       ownership, claims, entitlements
│   └── platform-policies.md          content, review, moderation, ad policies (public-facing source)
├── 30-technical/
│   ├── trd.md
│   ├── architecture.md               ← existing document moves here
│   ├── domain-model.md               entities, relationships, invariants, glossary binding
│   ├── data-model.md                 schema, indexes, migrations strategy
│   ├── api-specification.md          versioning, contracts, errors, pagination
│   ├── search-design.md              ranking, indexing, bilingual strategy
│   ├── security.md                   authn/z, threats, privacy, compliance
│   ├── performance-and-scalability.md budgets, caching, triggers
│   ├── observability.md              logging, metrics, health, alerting
│   └── deployment.md                 ← existing document moves here
├── 40-ux/
│   ├── ux-strategy.md
│   ├── information-architecture.md   URLs, navigation, page inventory
│   ├── design-system.md              tokens, components, states
│   ├── responsive-design.md
│   ├── accessibility.md              WCAG 2.2 AA commitments and test method
│   └── design-research.md
├── 50-platforms/
│   ├── web.md
│   ├── flutter.md
│   └── telegram-mini-app.md
├── 60-quality-operations/
│   ├── testing-strategy.md
│   ├── ci-cd.md
│   ├── release-management.md
│   ├── backups-and-recovery.md
│   └── incident-response.md
└── 90-decisions/
    ├── README.md                     index of ADRs
    └── adr-0001-....md               one decision per file, immutable once accepted
```

### 33.3 Recommended changes to the list in the brief

**Additions [P]:**

- `90-decisions/` (ADRs). The brief's documentation list has no home for *why*
  a decision was made. Without ADRs, decisions get re-litigated — which is
  exactly one of the failure modes the AI context system is meant to prevent.
- `30-technical/domain-model.md` as a document distinct from the data model.
  The ubiquitous language belongs with the domain, and the glossary binds to
  it.
- `30-technical/search-design.md`. Search is the core verb of the product and
  the hardest thing to get right on the chosen infrastructure; it deserves
  more than a TRD section.
- `20-business/platform-policies.md` as the single source for review,
  moderation, advertising and content policy, from which the public-facing
  pages are generated. Policy stated in two places will eventually contradict
  itself.
- `60-quality-operations/backups-and-recovery.md` — absent from the brief and
  genuinely load-bearing for a directory whose entire value is accumulated
  data.

**Cautions [P]:**

- `prd.md` must not duplicate feature specs; it should own scope, priorities
  and acceptance, and link out.
- `scalability` and `performance` are one document at this stage, not two.
- Do not create empty placeholder files. An empty document reads as "nothing
  decided here" exactly like a missing one, but costs maintenance and implies
  completeness that does not exist. Create each document when it has content.

### 33.4 Document front matter

```yaml
---
title: Advertising Model
status: Draft            # Draft | Review | Approved | Superseded
version: 0.3
owner: product
updated: 2026-10-07
supersedes: null
superseded_by: null
related: [docs/10-product/prd.md, docs/90-decisions/adr-0007-fixed-packages.md]
---
```

---

## 34. AI context system proposal

**[P] — proposal only; no files are created in this phase.**

### 34.1 What the context system is for

The `.ai/` directory is **not documentation**. It is the briefing an assistant
reads *before* touching the repository, and its only purpose is to prevent the
failure modes already named: hallucinated requirements, architecture drift,
inconsistent UI, inconsistent terminology, repeated decisions, undocumented
assumptions, accidental framework introduction and scope creep.

The governing distinction:

> **Documentation explains the product to people. AI context constrains the
> assistant's behaviour and tells it where the truth lives.**

If a fact belongs to the product, it goes in `docs/` and the context file
*links* to it. Context files that copy documentation become a second source of
truth and drift within weeks — the exact problem they were created to solve.

### 34.2 Proposed files and contents

| File | Contains | Must **not** contain | Changes |
| --- | --- | --- | --- |
| `project-overview.md` | What Bulbula is in ≤ 1 page; the market; the three clients; the hard rules (framework-free, modular monolith, shared hosting); links to `docs/10-product/` | Feature specifications, roadmap detail | Rarely |
| `architecture.md` | The *invariants*: layering, what may depend on what, where code belongs, the no-container/no-magic rules, boot sequence, link to `docs/30-technical/architecture.md` | A duplicate of the full architecture doc | On architectural decisions |
| `code-standards.md` | PHP 8.4 style in force, strict types, naming, error handling, testing expectations, the quality gates and how to run them, commit/PR conventions | Tool configuration (that lives in `pint.json`, `phpstan.neon.dist`) | Rarely |
| `ui-context.md` | Brand colours and their *roles*, the sponsored-label treatment, typography and bilingual rules, accessibility target, component conventions, what is not designed yet | Page-by-page designs | On design-system changes |
| `ai-workflow-rules.md` | How the assistant must work: branch naming, no direct commits to `main`, run `composer test` before proposing, never weaken gates, never add a framework, never invent requirements, always state certainty, ask instead of assuming, never commit secrets | Product content | Rarely |
| `progress-tracker.md` | What is done / in progress / next, per capability, with links to PRs | Aspirational roadmap (that is `roadmap.md`) | Every work session |
| `current-state.md` **(recommended)** | A factual snapshot: current `main` SHA, what exists in `src/`, what endpoints exist, migrations applied, environment status, known broken things | Opinions, plans | Every session |
| `decision-log.md` **(recommended)** | A one-line-per-decision index: date, decision, status, link to the ADR | The reasoning (that is the ADR) | On every decision |
| `glossary.md` **(recommended addition)** | The canonical term list from §35, binding term → code name → DB name → UI label | Definitions that contradict `domain-model.md` | On terminology decisions |

### 34.3 Why `current-state.md` and `decision-log.md` are worth their cost

- `current-state.md` answers the question that causes the most assistant
  hallucination: *"what is actually in this repository right now?"* It is also
  the cheapest defence against an assistant confidently describing features
  that do not exist.
- `decision-log.md` answers *"has this already been decided?"* — the direct
  countermeasure to re-litigating settled questions and to silently reversing
  them.

### 34.4 Operating rules for the context system

**[P]**

1. Every context file starts with its purpose, its last-updated date, and the
   statement **"If this file disagrees with `docs/`, `docs/` wins."**
2. Keep each file short enough to be read in full every session (target
   ≤ 300 lines). A context file nobody loads has no effect.
3. Updating `progress-tracker.md` and `current-state.md` is part of the
   definition of done for a PR, not a separate chore.
4. Context files never contain secrets, tokens, credentials or customer data.
   (Gitleaks already runs in CI and would catch it; the rule is stated anyway.)
5. The assistant must state a certainty level for anything not found in
   `docs/` or `.ai/`, and must ask rather than invent — the same discipline
   this report uses.

---

## 35. Terminology and glossary (provisional)

Consistent naming across code, database, API, UI and documentation. Status:
**settled** (use it), **proposed** (this document's recommendation),
**needs decision** (do not use until decided).

| Term | Meaning | Code / DB name | UI label | Status |
| --- | --- | --- | --- | --- |
| **Business** | A real-world business entity listed on Bulbula | `Business` / `businesses` | "Business" | settled |
| **Branch** | A physical location of a business | `Branch` / `branches` | "Branch" / "Location" | needs decision (D-03) |
| **Listing** | Informal synonym for a published business profile | — (avoid in code) | avoid | proposed: **do not use as a model name**; it blurs business vs branch vs profile |
| **Profile** | The public page of a business | — | "Profile" | settled (UI term only) |
| **Customer** | A person who uses Bulbula to find businesses | `User` with customer capability | "You" / no label | proposed |
| **Business owner** | A person who manages one or more businesses | `User` + `Ownership` | "Business owner" | proposed |
| **User** | Any authenticated person (customer, owner, operator) | `User` / `users` | avoid in UI | settled |
| **Operator / Administrator** | Platform staff working queues / managing the platform | `User` + role | "Admin" | needs decision (D-14) |
| **Category** | Primary classification of a business | `Category` / `categories` | "Category" | settled |
| **Subcategory** | Second-level classification | same table, `parent_id` | "Subcategory" | proposed |
| **Location** | A node in the geographic hierarchy (city, sub-city, area) | `Location` / `locations` | "Area" | proposed |
| **Area** | The neighbourhood-level location shown to users | `Location` of type `area` | "Area" | proposed |
| **Service** | Something a business does, optionally priced | `Service` | "Service" | proposed |
| **Product** | Something a business sells, optionally priced | `Product` | "Product" | proposed |
| **Offer** | A time-bounded promotion by a business | `Offer` | "Offer" | proposed |
| **Collection** | A curated list of businesses | `Collection` | "Collection" | proposed |
| **Claim** | A request to take ownership of an existing listing | `Claim` | "Claim this business" | settled |
| **Verification** | Evidence-based confirmation of a business and/or its owner | `Verification` | "Verified" | settled (tiers: D-08) |
| **Review** | A customer's rating + text about a business | `Review` | "Review" | settled |
| **Reply** | A business owner's response to a review | `ReviewReply` | "Response from the owner" | proposed |
| **Report** | A user flagging content or a business | `Report` | "Report" | settled |
| **Favourite** | A business saved by a customer | `Favourite` | "Saved" | needs decision (label) |
| **Organic ranking** | Ordering produced solely by the relevance model | `Ranking` | not shown | settled |
| **Sponsored placement** | A paid position on a discovery surface | `Placement` | **one label, D-10** | needs decision |
| **Campaign** | A business's purchase of a package over a date range | `Campaign` | "Campaign" | settled |
| **Package** | The sellable bundle: placement + targeting + duration + price | `Package` | "Package" | settled |
| **Placement** | A specific slot on a specific surface | `Placement` | internal | proposed |
| **Targeting** | The scope a campaign applies to (category, area) | `Targeting` | "Targeting" | proposed |
| **Impression** | A sponsored item rendered and eligible to be seen | `impressions` | "Impressions" | settled (counting rule: D-27) |
| **Click** | A user activating a sponsored item | `clicks` | "Clicks" | settled |
| **Conversion** | A valued action after a click (call, directions, website) | `conversions` | "Actions" | needs decision (definition) |
| **Profile view** | A business profile page opened | `profile_views` | "Profile views" | settled |
| **Search appearance / impression** | A business appearing in a result set | `search_impressions` | "Search appearances" | needs decision — the brief lists "search impressions" **and** "search appearances"; if they differ, define both; if not, pick one |
| **Completeness** | How fully a profile is filled in | `completeness_score` | "Profile strength" | needs decision (D-09) |
| **Open now** | Computed state from structured hours + current time | `isOpenAt()` | "Open now" | settled |

**Terminology decisions still required:** customer vs user in the UI; branch
vs location; the sponsored label; "search impressions" vs "search
appearances"; the favourites label; Amharic equivalents for every public term
(dependent on D-18).

---

## 36. Risks

| # | Risk | Likelihood | Impact | Mitigation |
| --- | --- | --- | --- | --- |
| R-1 | **Empty directory at launch** — nothing to search, so no customers, so no businesses | High | Critical | Decide D-02 early; treat "N quality listings in Bole Bulbula" as a launch gate, not a nice-to-have |
| R-2 | **Data decay** — hours, phones and existence go stale; the directory becomes untrustworthy | High | Critical | Suggest-an-edit (PR-15), owner re-engagement, freshness as a ranking signal, periodic operator audits |
| R-3 | **Scope creep from the capability map** — the brief describes a platform; V1 must be a product | High | High | D-01 as a hard boundary; the "future" list is a commitment, not a wish list |
| R-4 | **Advertising built before an audience exists** | Medium | High | PR-1: model now, sell manually, automate later |
| R-5 | **Trust failure** (fake reviews, fake listings, wrongly verified business) damaging credibility | Medium | Critical | PR-6/PR-7, moderation queues, audit log, published policies |
| R-6 | **Shared hosting limits hit unexpectedly** (memory, cron, connections, image processing) | Medium | High | Answer D-20 before designing the media and analytics pipelines |
| R-7 | **Legal exposure** under Proclamation 1321/2024 (consent, residency, minors, breach notice) | Medium | High | D-22 with external advice before collecting personal data at scale |
| R-8 | **Three clients diverge** into three products | Medium | High | PR-13 parity rule; one domain; API contract discipline |
| R-9 | **Language strategy decided late**, forcing rework of search, URLs, taxonomy and design | Medium | High | PR-18 / D-18 before the PRD closes |
| R-10 | **Quality gates become a tax** as the domain grows, or are quietly weakened under pressure | Medium | Medium | D-26: decide scope of gates consciously, in writing, once |
| R-11 | **Single-person bus factor** across product, engineering, moderation and sales | High | High | Documentation discipline (§33), ADRs, operator runbooks |
| R-12 | **Deployment model surprises** (reset-hard wiping host state, no `.env`, PHP handler) | Medium (already materialised twice) | Medium | Everything the server needs is tracked or documented; D-23 |
| R-13 | **SEO thin-content penalty** from generated category×area permutations | Medium | Medium | Index thresholds (§23.3) |
| R-14 | **Media costs or abuse** (large uploads, hotlinking, inappropriate images) | Low | Medium | Limits, moderation, R2 + CDN, content policy |
| R-15 | **Google Maps dependency** (API key, billing, quota, terms) | Medium | Medium | D-21; static fallback; no critical function depends on the embed |

---

## 37. Recommended next phase

**[P]** A sequence, with gates. Nothing proceeds past a gate until the
preceding output is approved.

### Step 1 — Review this report *(you)*
Work through §27–§32. For each **[SI]** item: confirm or reject. For each
**[P]** item: approve, modify or reject. For the **P0** decisions in §32:
answer them, or commission the research that will.

**Gate:** this document reaches `status: Approved`, with the P0 decisions
answered or explicitly deferred with a named owner.

### Step 2 — Close the four market unknowns *(cheap, high-value)*
U-1 to U-4 are answerable in days, not weeks: walk Bole Bulbula and count and
categorise businesses; talk to ten business owners about presence and
willingness to pay; talk to ten residents about how they find a plumber, a
pharmacy, a clinic; check what the existing Ethiopian directories list for
this area and how stale it is.

**Gate:** a short findings note (one page) that either confirms or corrects
§3 and §5.

### Step 3 — Decide the V1 boundary *(D-01, D-02, D-18, D-30)*
These four together define the shape of everything that follows: what is in,
where the data comes from, what language it is in, and what "launched" means.

**Gate:** an ADR per decision in `docs/90-decisions/`.

### Step 4 — Write the PRD
Scope, priorities, user stories, acceptance criteria, non-goals — drawing on
this report as source material and referencing the ADRs, not re-deciding.

### Step 5 — Write the TRD, domain model and data model
Only after the PRD. The domain model is the point at which the glossary (§35)
becomes binding on code, database and API simultaneously.

### Step 6 — UX strategy, information architecture, design system
Typography and tokens can only be settled once D-18 (language) and D-19 (dark
mode) are answered.

### Step 7 — Create the AI context system
Last, not first: it summarises decisions that must already exist. Creating it
earlier would mean writing context about a product that has not been
specified — the exact hallucination risk it is meant to prevent.

### Step 8 — Begin implementation, capability by capability
Suggested order, each behind the existing quality gates:
identity & accounts → content (business, category, location) → admin queues →
search → media → trust (claims, verification, reviews) → analytics events →
advertising foundation → SEO surface polish.

---

## Appendix A — Source material for this report

| Source | Used for |
| --- | --- |
| The Phase 2 brief (this task's instructions) | Every **[C]** product, platform, brand and constraint statement |
| Repository `main` @ `fc6188b` — `src/`, `routes/`, `config/`, `tests/`, `docs/architecture.md`, `docs/deployment.md`, `composer.json`, `.github/workflows/` | §21, and every **[C]** engineering statement |
| Prior sessions' standing instructions (framework-free rule, branch/PR workflow, quality gates, brand colours, contact address) | §27 |
| External research, 15 findings with sources | [research-notes-v0.1.md](research-notes-v0.1.md) |

## Appendix B — Document control

| Field | Value |
| --- | --- |
| Status | **Discovery — awaiting review.** Not approved. Not a specification. |
| Version | 0.1 |
| Next version trigger | Review feedback on §27–§32 |
| Authority to approve | Product owner |
| Review checklist | (1) every **[SI]** confirmed or rejected · (2) every **[P]** approved, modified or rejected · (3) every **P0** decision in §32 answered or assigned · (4) conflicts in §31 resolved or accepted as open · (5) terminology in §35 ratified |
