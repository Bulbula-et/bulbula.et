# Product Context

```text
Source baseline:   9730b5426efffdd6756075d97354319d1686b74a
Last derived from: 2026-10-07
Context status:    Current
```

**Derived from** `docs/10-product/scope-v1.md` · `prd-v1.0.md` §11–§28 ·
`interaction-permissions.md` · `review-policy.md` · `listing-operations.md` ·
`docs/15-business/advertising-products.md`.
**Authority:** those documents. This is an index, not a specification.

---

## 1. The scope test

```text
In docs/10-product/scope-v1.md §1?  →  in V1.
Not listed?                         →  NOT in V1.
Absence from the exclusion list is not permission.
```

Approved by **D-01**: exactly **40 capabilities, C-01 … C-40**. None added,
removed or merged (BND-1, §11.1).

---

## 2. V1 capabilities

Each is specified in `docs/10-product/prd-v1.0.md` §12 under its reference,
with acceptance criteria. **Open sub-decisions do not remove the capability —
they leave detail inside it undecided.**

### 2.1 Public discovery (18) — Guest-usable, both surfaces

| Ref | Capability | Principal actor | Open |
| --- | --- | --- | --- |
| C-01 | Homepage | Guest | — |
| C-02 | Search | Guest | D-09 |
| C-03 | Search autocomplete | Guest | — |
| C-04 | Category / subcategory browsing | Guest | D-56, D-57 |
| C-05 | Location / area browsing | Guest | D-40 |
| C-06 | Category × area pages | Guest | D-56 |
| C-07 | Nearby / distance discovery | Guest | — |
| C-08 | Business profile pages | Guest | D-44, D-55 |
| C-09 | Opening hours and open status | Guest | D-04, D-55 |
| C-10 | Google Maps embed and open-in-maps | Guest | D-21 |
| C-11 | Business contact actions — **the core value event** | Guest | D-55 |
| C-12 | Trust indicators | Guest | D-08 |
| C-13 | Reviews — read as Guest, **write as Customer** | Guest / Customer | D-34, D-36, D-37 |
| C-14 | Save — **private, no counts** | Customer | — |
| C-15 | Report a problem / suggest a correction | **Guest, no account** | D-35 |
| C-16 | Sponsored placements | Guest | D-10 detail |
| C-17 | Sharing | Guest | — |
| C-18 | Static information and policy pages | Guest | legal items |

### 2.2 Internal operations (11) — staff only, **Web only**

| Ref | Capability | Principal actor | Open |
| --- | --- | --- | --- |
| C-19 | Listing creation | Operator | D-43, D-50 |
| C-20 | Listing editing | Operator | D-14 |
| C-21 | Verification and quality control | Operator | D-08 |
| C-22 | Media management | Operator | D-25 |
| C-23 | Category management | **Administrator** | D-06, D-56, D-57 |
| C-24 | Location management | **Administrator** | D-40 |
| C-25 | Review moderation | Operator | D-34 |
| C-26 | Report management | Operator | D-35 |
| C-27 | Advertising and campaign management | Operator creates, **Administrator approves** | D-11, D-39 |
| C-28 | Operational analytics | Staff | D-27 |
| C-29 | Audit logs | **Administrator** reads | D-14 |

### 2.3 Customer accounts (7) — both surfaces

| Ref | Capability | Principal actor | Open |
| --- | --- | --- | --- |
| C-30 | Google authentication | Customer | — |
| C-31 | Email OTP authentication | Customer | D-41 |
| C-32 | Unified Bulbula identity | Customer | D-13, D-33 |
| C-33 | Customer profile | Customer | D-51 |
| C-34 | Save management | Customer | — |
| C-35 | Customer review management | Customer | D-34 |
| C-36 | Account deletion and privacy controls | Customer | D-46 |

### 2.4 Cross-cutting (4)

| Ref | Capability | Note | Open |
| --- | --- | --- | --- |
| C-37 | SEO | **Web only** | D-18 |
| C-38 | Analytics | Never on a User's critical path | D-27 |
| C-39 | Notifications | **Email only** (D-24) | D-24, D-41 |
| C-40 | Privacy and data handling | Constrains every other capability | D-42, D-46 |

### 2.5 Surface obligation (D-49)

Every capability ships on **both** Web and Telegram Mini App **except**
C-37 (Web only) and C-19…C-29 (Web only). A third exception is a scope change
requiring a decision (SCC-4.3).

---

## 3. V1 exclusions — hard

Any requirement implying one of these is a **defect**. Full list:
`docs/10-product/scope-v1.md` §2 and `prd-v1.0.md` §13.

### Business side

Business accounts · business login · owner-created Listings · owner claims ·
owner Listing management · owner profile control · owner replies to Reviews ·
owner-facing analytics or dashboards · self-service advertising purchase ·
self-service billing. → **D-54, D-02, D-12, D-10, D-11**

### Advertising

Auction · bidding · CPC · CPM · CPA · performance pricing · programmatic ·
third-party ad networks · automated targeting · paid influence on organic
ranking · purchasable verification or trust markers · retargeting ·
behavioural targeting of individuals. → **D-10**

### Authentication

Passwords · Sign in with Apple · Facebook/X/other social providers ·
phone/SMS authentication · Telegram as a login provider · 2FA for Customers.
→ **D-48** (Apple revisit: D-47; Telegram identity **Open — D-33**)

### Platform

Flutter client · any native client · native push notifications · Telegram bot
features beyond the Mini App · desktop-first design · offline mode.
→ **D-15, D-52**

### Product features

Online ordering · reservations/bookings · loyalty programmes · messaging or
leads between Users and Businesses · jobs · local news · subscriptions ·
advanced AI recommendations · review photos (D-36) · "helpful" voting (D-37) ·
payment gateway (D-10, D-11) · Amharic interface (D-18) · Afaan Oromo (D-18) ·
national-scale expansion (D-01).

### The seam rule

Architecture **seams** that keep the future owner model possible are
permitted. **Features** for it are not (BND-4).

---

## 4. Product rules an implementer must hold in memory

### Identity and accounts

| Rule | Source |
| --- | --- |
| **Google + email OTP only. No passwords. No Apple. No social. No SMS.** | D-48 |
| One unified Bulbula identity across both surfaces | C-32, SCC-2.11 |
| Automatic linking of a Google identity and an email identity is **Open (D-13)** — multiple identities are supported, auto-linking is not implemented | D-13 |
| **Telegram context establishes a surface, not an identity.** It must be validated server-side and must never become the primary account model | TR-112, TR-113, D-33 |
| The platform must support attaching Telegram later as an additional provider without restructuring | TR-114 |

### Business and listing model

| Rule | Source |
| --- | --- |
| **A Business is not a platform account.** No business principal exists in the authorization model | D-54, TR-40 |
| One Business, many **Branches**; exactly one primary | D-03 |
| **Staff-managed Listings.** Every fact is collected, verified and published by staff | D-02, D-50 |
| **Permission-based collection.** No Listing publishes without a linked Permission record, by any path including import | D-50, TR-49, OC-10 |
| **Provenance per fact.** A fact with no recorded source must not be published | DQ-1 |
| **Verification is recorded** with method, date, scope and actor; methods and interval are **Open (D-08)** | C-21, D-08 |
| **Closure is a state, never a deletion.** The URL and history survive | COR-3 |
| Correction is the safety valve, available to **Guests without an account** | TS-1, TS-2, TS-3 |

### Reviews

| Rule | Source |
| --- | --- |
| **Guests read. Only authenticated Customers write.** | D-12, D-05 |
| **No owner replies. No business account. No manipulation.** | D-12, D-54 |
| Moderation may **publish, reject or remove — never edit** | MOD-4 |
| Every moderation outcome cites one of the **published grounds** | MOD-1 |
| A negative review is not a ground for removal | MOD-2 |
| **Moderation is independent of advertising** | MOD-3, TS-14, IN-3 |
| Reporter identity is never disclosed | TS-10, REP-4 |
| Rating average is always shown **with its count**; nothing at all when there are no Reviews | UR-16, TR-63 |
| Review subject, edit window, deletion semantics and moderation order are **Open (D-34)** | D-34 |

### Save

| Rule | Source |
| --- | --- |
| **Private.** No public counts, no social signal, no "N people saved this" | C-14, SCC-2.9 |
| Server-side, identity-scoped, idempotent; never stored on the device | TM-11.4, SCC-6.6 |

### Advertising

| Rule | Source |
| --- | --- |
| Exactly **three** sponsorship forms: search slot, category sponsorship, homepage promotion | `advertising-products.md` §2 |
| **Fixed packages, staff-managed, sold offline, invoiced manually** | D-10, D-11 |
| Every Sponsored placement carries a **visible text label readable without interaction** | ADV-1, LB-1, LB-2 |
| Label must not rely on colour, shading or position alone | LB-4, NFR-AC3 |
| **Sponsored ≠ organic.** Separate container; never blended; never displaces organic results | LB-6, LB-7, PL-6, ADV-8 |
| **Identical organic ordering with and without an active Campaign** — a testable property | IN-7, TR-43 |
| Unsold inventory **collapses**: no placeholder, no house ad, no empty frame | PL-5 |
| Every Campaign has explicit start/end dates and **stops automatically**; evaluated at request time, not by a job | ADV-4, TR-71, TR-154 |
| Only published, verified, in-good-standing Businesses are eligible | ADV-10 |
| Measurement is impressions and clicks, **for reporting only** | MS-1, MS-2 |
| **No price is approved.** Inventory counts and density values are `[P]`, unapproved | §10 |
| Integrity controls separating commercial from editorial are **Open (D-39)** and **block the first paid Campaign** | IN-6, ADV-12 |

### Data and privacy

| Rule | Source |
| --- | --- |
| **Data minimisation**: collect only fields with a stated product purpose | D-51, PRIV-2, COL-1 |
| Business contact information is **not automatically non-personal** — a personal contact point is flagged at collection | D-51, PCP-1, COL-2 |
| Internal fields never render publicly: provenance, Permission records, verification evidence, personal-contact flags, reporter identity | PCP-3, PCP-5, TR-25 |
| Analytics must not identify an individual Guest | AN-3, TR-202 |
| **No third-party tracking for advertising purposes** | NFR-PR4, D-10 |
| Email is the only Bulbula-operated notification channel | D-24, NOT-1 |
| Transactional email only; **no marketing in V1** | EM-2, EM-3, NOT-4 |

### Presentation

| Rule | Source |
| --- | --- |
| **English-first**, bilingual-ready: Amharic names and Aliases are stored and searchable; the interface is English | D-18 |
| **Mobile-first**, genuinely — Android-first, constrained networks | D-52, NFR-C1, R-06 |
| Core content reachable **without JavaScript** | NFR-C3, SEO-1 |
| Third-party components (maps, fonts) must not block first render | NFR-P4 |
| Brand direction: **orange primary, blue secondary, white-dominant light mode**. Exact values **Open (D-53)** | D-53 |
| **Honest absence**: show "hours not confirmed", never a plausible guess | UXP-7, DQ-2 |
| Dark mode is **Open (D-19)** | D-19 |

---

## 5. Open product decisions relevant to implementation

**These are not requirements.** They are holes. Keep them visible; do not fill
them. Authority: `docs/60-decisions/decision-register.md` §2.

| ID | Question | Hits you when |
| --- | --- | --- |
| **D-04** | Opening-hours model: regular, split shifts, exceptions, 24 h | Hours schema, open-now evaluation |
| **D-08** | Verification rules, tiers, evidence, re-verification interval | Trust indicators, freshness queue |
| **D-09** | Completeness definition and ranking weight | Ranking, profile completeness |
| **D-11** | Billing mechanics and billing records | Campaign records |
| **D-13** | Identity-linking across Google and email | Account creation and resolution |
| **D-14** | Operator / Administrator split | **Any staff permission check — name permissions, not roles (TR-37)** |
| **D-16** | Frontend JS approach: htmx + Alpine vs vanilla modules (**SPA rejected**) | Client-side work |
| **D-17** | View / template layer: in-house vs library | Rendering |
| **D-19** | Dark mode | Theming |
| **D-20** | Host limits and MariaDB tuning | Capacity assumptions |
| **D-21** | Maps key ownership, billing, embed strategy, fallback | C-10 |
| **D-23** | Production configuration and secret custody | Deployment |
| **D-25** | Media storage provider, limits, formats | C-22 |
| **D-26** | Coverage and mutation gates as the domain grows | Any new code |
| **D-27** | Analytics granularity, retention, raw-event policy | C-28, C-38 |
| **D-33** | Telegram identity integration — **open evaluation** | Mini App auth |
| **D-34** | Review mechanics: subject, edit window, deletion, moderation order | C-13, C-25, C-35 |
| **D-35** | Structured guest suggestions vs free-text reports | C-15 |
| **D-38** | Mini App navigation: full page loads vs fragment swaps | Mini App |
| **D-39** | Ad / editorial integrity controls — **blocks the first paid Campaign** | C-16, C-27 |
| **D-40** | Launch-area boundary | C-05, C-24 |
| **D-41** | Transactional email provider | C-31, C-39 |
| **D-42 / D-42b** | Data-location policy and hosting vendor | Deployment, backups |
| **D-43** | Permission record contents and retention | C-19 |
| **D-44** | Services / products / pricing representation | C-08 |
| **D-45** | Staff authentication strength | Staff auth |
| **D-46** | Minimum account age; retention per data class | C-36, retention |
| **D-55** | Branch vs Business attribute boundary | Data model |
| **D-56 / D-57** | Category catalogue production and cardinality | C-04, C-23 |

Technical opens `OT-01…OT-09` are in `technical-context.md` §9.

---

## 6. Deferred — direction approved, not V1

| Capability | Condition |
| --- | --- |
| Business accounts **with mandatory administrator approval of every submission** | D-54; **seams in V1, features not** |
| Owner-created listings and claims | D-02, same approval gate |
| Owner review replies | Requires business accounts |
| Self-service advertising and billing | Requires business accounts + L-17/L-20 |
| Flutter client (Android, then iOS) | D-15, D-15r; same backend, same API contracts |
| Sign in with Apple | D-47 — likely required by App Store rules when iOS ships |
| Amharic interface | D-18 — separate owner approval |
| Expansion beyond the launch area | Location model already supports it |
| Review photos · "helpful" voting | D-36 · D-37 |

Building any of these now is a scope violation, **including** building "just
the model for it".
