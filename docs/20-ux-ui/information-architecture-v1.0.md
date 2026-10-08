# Information Architecture

| | |
| --- | --- |
| **Document** | Information Architecture — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The complete V1 structure: what pages exist, how they relate,
how Users move between them, and what their addresses are.

**Scope.** Public Web and Telegram Mini App. The operations console
structure is in
[`operations-console-ux-v1.0.md`](operations-console-ux-v1.0.md) §2.

**Rule.** This document creates **no page that does not serve an approved
capability**. Each node below names its `C-xx`.

---

## 1. The complete public structure

```text
Home  (C-01)
│
├── Search                                   /search              C-02, C-03
│     └── results · filters · sort · zero-result
│
├── Discover
│     ├── Categories (index)                 /categories          C-04
│     │     └── Category                     /c/{category}        C-04
│     │           ├── Subcategory            /c/{category}/{sub}  C-04
│     │           └── Category × Area        /c/{category}/in/{area}   C-06
│     ├── Areas (index)                      /areas               C-05
│     │     └── Area                         /a/{area}            C-05
│     │           └── Area × Category        /c/{category}/in/{area}   C-06
│     └── Nearby                             /nearby              C-07
│
├── Business profile                         /b/{business}        C-08…C-17
│     ├── (branch context)                   /b/{business}?branch={slug}
│     ├── Reviews (in page)                                       C-13
│     ├── Report a problem                   /b/{business}/report C-15
│     └── Write a Review                     /b/{business}/review C-13  [auth]
│
├── Saved                                    /saved               C-34  [auth]
│
├── Account                                                       C-33
│     ├── Sign in                            /signin              C-30, C-31
│     ├── Profile                            /account             C-33  [auth]
│     ├── My Reviews                         /account/reviews     C-35  [auth]
│     └── Privacy and deletion               /account/privacy     C-36  [auth]
│
└── Static / policy                          /about, /privacy, …  C-18
```

`[auth]` = authenticated-required (`interaction-permissions.md` §3).
Everything else is **Guest-safe**.

### 1.1 Capability coverage

| Capability | Where it lives |
| --- | --- |
| C-01 Homepage | Home |
| C-02 Search · C-03 Autocomplete | `/search`, search control on every page |
| C-04 Category browse | `/categories`, `/c/{category}`, `/c/{category}/{sub}` |
| C-05 Area browse | `/areas`, `/a/{area}` |
| C-06 Category × Area | `/c/{category}/in/{area}` |
| C-07 Nearby | `/nearby` |
| C-08 Profile · C-09 Hours · C-10 Map · C-11 Contact · C-12 Trust | `/b/{business}` |
| C-13 Reviews | `/b/{business}` (read), `/b/{business}/review` (write) |
| C-14 Save | Control on cards and profile |
| C-15 Report | `/b/{business}/report`; Review report in page |
| C-16 Sponsored | Defined Placements on Home, Search, Category, Category × Area |
| C-17 Share | Control on profile |
| C-18 Static pages | `/about`, `/privacy`, `/terms`, `/advertising`, `/corrections`, `/how-we-verify`, `/how-ranking-works`, `/review-policy`, `/contact` |
| C-30…C-32 Auth and identity | `/signin` and its return path |
| C-33 Profile · C-34 Saved · C-35 My Reviews · C-36 Deletion | `/account*`, `/saved` |
| C-37 SEO · C-38 Analytics · C-39 Notifications · C-40 Privacy | Cross-cutting (§9, §10) |
| C-19…C-29 Operations | `/ops/*` — separate surface, Web only |

**All 40 capabilities are placed.**

---

## 2. URL principles

| ID | Rule | Source |
| --- | --- | --- |
| IAR-1 | URLs are **stable, human-readable and semantically meaningful** | SEO-3 |
| IAR-2 | Every public page declares a **single canonical URL** | SEO-2 |
| IAR-3 | A Business profile URL **survives a name change**; a changed slug redirects permanently | SEO-4, `data-model.md` §8.7 |
| IAR-4 | Lowercase, hyphenated, no file extension, no trailing slash, no internal numeric id | TRD TR-25 |
| IAR-5 | Filter, sort and pagination parameters **never create a second indexable URL**; they canonicalise to the base page | SEO-10 |
| IAR-6 | The URL model **must not preclude Amharic pages later** — no English-only assumption is baked into the path grammar | SEO-14, D-18 |
| IAR-7 | Non-public pages (`/ops/*`, `/account/*`, `/signin`) are **excluded from indexing** | SEO-8 |
| IAR-8 | A removed Listing returns **not found** and leaves the sitemap | SEO-13 |

### 2.1 The path grammar

| Pattern | Example | Why |
| --- | --- | --- |
| `/b/{business}` | `/b/tsegaye-pharmacy` | Short prefix keeps profile URLs compact for sharing and SMS |
| `/c/{category}` | `/c/pharmacies` | — |
| `/c/{category}/{subcategory}` | `/c/pharmacies/24-hour` | Mirrors the two-level taxonomy exactly (D-06) |
| `/c/{category}/in/{area}` | `/c/pharmacies/in/bole-bulbula` | The `in` segment reads as English and signals the intersection |
| `/a/{area}` | `/a/bole-bulbula` | — |
| `/search?q=…` | — | Query is a parameter, not a path: it is not an indexable destination |

**Category × Area canonical form.** The page is reachable conceptually from
both axes but has **one** URL — `/c/{category}/in/{area}`. The Area page
links to it; it does not mint `/a/{area}/{category}` as a duplicate
(IAR-5, SEO-10).

**Sub-city** is stored and displayed (GEO-6) but is **not** a browsable URL
level in V1. Adding one would create thin intermediate pages with no
capability behind them.

### 2.2 Parameters

| Parameter | Used on | Indexable? |
| --- | --- | --- |
| `q` | `/search` | No |
| `category`, `area`, `open_now`, `min_rating`, `verified` | `/search` | No |
| `sort` | `/search`, list pages | No |
| `page` | list pages | Canonicalises to page 1; crawlable via `rel` hints but not duplicated |
| `branch` | `/b/{business}` | No — canonical is the bare profile URL |

---

## 3. Navigation

### 3.1 Primary navigation — persistent

Present on **every** public page, on both surfaces.

| Element | Mobile | Desktop | Why |
| --- | --- | --- | --- |
| Wordmark → Home | Top left | Top left | Universal home affordance |
| **Search** | Persistent field or a full-width tap target in the header | Persistent field in the header | C-02 is the primary capability; it is never hidden behind an icon (UR-06) |
| **Categories** | Visible in a bottom or sub-header bar | Header link | UR-06 — hiding the discovery axes hides the inventory |
| **Areas** | Visible in the same bar | Header link | As above |
| **Saved** | Visible in the same bar | Header link | C-34; for a Guest it leads to sign-in **only on tap** (UXP-3.3) |
| Account / Sign in | Header icon → menu | Header link → menu | Secondary |

| ID | Rule |
| --- | --- |
| IAR-9 | **Search and the two discovery axes are never collapsed into a hamburger** (UR-06) |
| IAR-10 | Navigation appears in the **same relative order on every page** (WCAG 3.2.3) |
| IAR-11 | A help/contact route appears in a **consistent location** on every page (WCAG 3.2.6, C-18) |
| IAR-12 | The collapsed menu holds only: static pages, policy pages and account items |
| IAR-13 | **No notification badge, no unread count, no dot** on any navigation item (UXP-6.7) |

**Telegram adaptation (D-49, SUR-5).** The Mini App suppresses the Bulbula
wordmark row and the browser-style back affordance, because Telegram
supplies both. Search and the discovery axes remain, in the same order.
Bulbula **never renders a second back button** (UR-12, UXP-9.4).

### 3.2 Secondary navigation — contextual

| Context | Secondary navigation |
| --- | --- |
| Category page | Its Subcategories; Areas where this Category has published Listings (→ Category × Area) |
| Subcategory page | Sibling Subcategories; parent Category; Areas |
| Area page | Categories present in this Area (→ Category × Area); neighbouring Areas |
| Category × Area page | Parent Category; parent Area; sibling Areas for the same Category |
| Search results | Filter and sort controls; "browse Categories / Areas instead" |
| Business profile | Branch selector where more than one Branch exists; its Category and Area as links |
| Account | Profile · Saved · My Reviews · Privacy |

| ID | Rule |
| --- | --- |
| IAR-14 | Secondary navigation **only lists destinations that have published Listings** (C-04, C-05) |
| IAR-15 | Every Business profile links **out** to its Category and its Area — this is the internal link graph SEO-12 depends on |
| IAR-16 | Secondary navigation is not duplicated as a third mechanism; it appears once per page |

### 3.3 Breadcrumbs

| Page | Breadcrumb |
| --- | --- |
| Category | Home › Categories › *Category* |
| Subcategory | Home › Categories › *Category* › *Subcategory* |
| Area | Home › Areas › *Area* |
| Category × Area | Home › Categories › *Category* › in *Area* |
| Business profile | Home › *Category* › *Business* |
| Static page | Home › *Page* |

| ID | Rule |
| --- | --- |
| IAR-17 | Breadcrumbs appear on every page **except** Home, Search results and account pages |
| IAR-18 | Breadcrumbs are real links, marked up as a navigation landmark with an ordered list |
| IAR-19 | The current page is the **last item and is not a link** |
| IAR-20 | On mobile the breadcrumb may truncate intermediate items but **always keeps the immediate parent** — it is the primary "up" affordance where there is no browser chrome (Mini App) |
| IAR-21 | The profile breadcrumb uses the **primary Category**. Every published Listing has **exactly one** (D-57), so the path is always well defined even where a Business carries secondary Categories |

### 3.4 Search entry points

| Entry point | Behaviour |
| --- | --- |
| Header search (every page) | Focus opens autocomplete; submit goes to `/search` |
| Homepage primary search | Visible without scrolling on mobile (C-01) |
| Zero-result screen | The query is retained and editable (UR-04) |
| Category / Area pages | Search is scoped-suggestible but submits to the same `/search`; the current Category or Area is pre-applied **as a visible, removable filter** |
| Empty Saved list | Offers search as the route out |

| ID | Rule |
| --- | --- |
| IAR-22 | The search field is wide enough for a typical query and the text does not scroll out of view while editing (UR-04) |
| IAR-23 | A **visible submit control sits adjacent to the field** on mobile (UR-04) |
| IAR-24 | A pre-applied scope is always **visible and removable**; a hidden scope is never applied |
| IAR-25 | The submitted query **persists in the field** on the results page |

### 3.5 Profile entry points

From search results · autocomplete · Category, Subcategory, Area and
Category × Area lists · nearby · Saved list · My Reviews · a shared link ·
an external search engine · a Sponsored placement.

| ID | Rule |
| --- | --- |
| IAR-26 | The profile renders **identically regardless of entry point**. Arriving from a Sponsored placement changes nothing about the page (UXP-5.8) |
| IAR-27 | Deep arrival from an external search engine is the **primary** case, not a fallback: the page must be self-sufficient, with breadcrumbs and onward discovery (SEO-12) |

---

## 4. Browser navigation behaviour

| ID | Rule | Source |
| --- | --- | --- |
| IAR-28 | **Back returns to the previous page with its state intact** — the same results, the same scroll position, the same filters | D-52 |
| IAR-29 | Filter, sort and pagination changes produce a **real history entry** so Back undoes them one at a time | — |
| IAR-30 | **No infinite scroll.** It destroys Back and defeats pagination | SEO-10, UXP-8.7 |
| IAR-31 | Opening a modal or bottom sheet pushes a history entry; **Back closes it** rather than leaving the page | UR-12 |
| IAR-32 | Forward navigation restores what Back undid | — |
| IAR-33 | Sign-in **replaces** rather than stacks: Back from a post-sign-in task does not return to the sign-in screen | UXP-3.6 |
| IAR-34 | Core navigation works **without JavaScript**; every navigational control is a real link or a form | SEO-1, MOB-5, D-16 |
| IAR-35 | Inside Telegram, the **host Back button** drives this same model, closing the topmost layer first (UR-12). The underlying model — full loads or fragment swaps — is **Open (D-38)** | D-38, D-49 |

---

## 5. Deep links and sharing

### 5.1 Deep-linkable destinations

Every one of these is a real, shareable, bookmarkable URL:

Home · Search results including filters and sort · Categories index ·
Category · Subcategory · Areas index · Area · Category × Area · Nearby ·
Business profile · Business profile with a branch selected · Static pages.

**Not deep-linkable:** a transient state — an open filter sheet, an open
share sheet, autocomplete, a toast.

### 5.2 Sharing

| ID | Rule | Source |
| --- | --- | --- |
| IAR-36 | Sharing shares the **canonical URL** — never a URL carrying a session, a filter, a tracking parameter or a referral code | SEO-2, TRD TR-201 |
| IAR-37 | Every public page provides **title, description and image metadata** so links preview correctly in messaging applications | SEO-11, C-17 |
| IAR-38 | Links shared from the Mini App **resolve on the Web** for recipients who do not use Telegram | TG-6 |
| IAR-39 | The share mechanism itself is surface-specific and lives behind the adapter: the Web uses the platform share sheet with a copy-link fallback; the Mini App uses Telegram's | SUR-5, D-49 |
| IAR-40 | **No share action requires an account**, and no share is recorded against a person | GS-1, TRD TR-202 |

---

## 6. Mobile navigation behaviour

| Aspect | Behaviour |
| --- | --- |
| Header | Compact. Wordmark, search, account. Search is a field or a full-width tap target, never only an icon |
| Discovery axes | A persistent bar — Categories · Areas · Nearby · Saved — visible without opening a menu (UR-06) |
| Sticky elements | **At most one** sticky region. A sticky element must never obscure a focused control (WCAG 2.4.11) |
| Filters | Open in a bottom sheet; the sheet is dismissible by Back, by a close control and by tapping outside |
| Sort | A compact control beside the result count, not buried in the filter sheet |
| Long lists | Paginated with an explicit control; no infinite scroll |
| Modal versus sheet | Sheets for choices and filters; modals reserved for confirmations |
| Keyboard | When the on-screen keyboard is open, layout is sized from the stable viewport height so nothing jumps (UR-12) |

---

## 7. Desktop navigation behaviour

| Aspect | Behaviour |
| --- | --- |
| Header | One row: wordmark, search, Categories, Areas, Nearby, Saved, account |
| Content width | A bounded reading measure; the page does not stretch to the full window |
| Filters | A persistent column beside the results, not a sheet |
| Lists | Multi-column where the card tolerates it; the card itself is unchanged |
| Profile | Two columns — primary content and a secondary column for map, hours and contact — **without reordering the information hierarchy** |
| Hover | Hover may refine, never reveal. Everything reachable by hover is reachable by tap and by keyboard (UXP-2) |

---

## 8. The operations console in the IA

| ID | Rule | Source |
| --- | --- | --- |
| IAR-41 | The operations console lives under `/ops/*`, on the **Web only** | `scope-v1.md` §1.5 |
| IAR-42 | It is **never linked from a public page**, never mentioned in public navigation and never indexed | SEO-8, `interaction-permissions.md` §2 |
| IAR-43 | Staff authentication is **separate** from Customer authentication, with its own entry | ST-2, PRD ACC-8 |
| IAR-44 | No public URL exposes an operations capability, and no public page hints that one exists | ENF-2 |

---

## 9. SEO structure

| ID | Rule | Source |
| --- | --- | --- |
| IAR-45 | Indexable: Home, Categories index, Category, Subcategory, Areas index, Area, Category × Area, Business profiles, static pages | SEO-12 |
| IAR-46 | Not indexable: `/search`, `/nearby`, `/signin`, `/account/*`, `/saved`, `/ops/*`, report and review forms | SEO-8 |
| IAR-47 | A Category × Area page **below the minimum-content rule is not indexable** | SEO-9, C-06 |
| IAR-48 | Every indexable page has a **unique** title and description derived from its content | SEO-5 |
| IAR-49 | **One `h1` per page**, and the heading hierarchy does not skip levels | WCAG 1.3.1 |
| IAR-50 | Business profiles publish structured data for the Business, its location, hours and rating summary **where one exists** — and the marked-up rating always matches what is visible | SEO-6, R-15, UR-16 |
| IAR-51 | Breadcrumbs are marked up as structured data | SEO-12 |
| IAR-52 | A sitemap lists indexable pages and is kept current as Listings are published and unpublished | SEO-7, SEO-13 |
| IAR-53 | Mini App content is **not** an SEO surface | TG-7 |

### 9.1 Heading structure per page type

| Page | `h1` | `h2` |
| --- | --- | --- |
| Home | Bulbula's purpose statement | Discovery blocks, Categories, Areas |
| Category | The Category name | Subcategories · Areas · Results |
| Area | The Area name | Categories in this Area · Results |
| Category × Area | "*Category* in *Area*" | Results · Related |
| Business profile | The Business name | Hours · Location · Reviews · About |
| Search results | The query, or "Search" | Results · Sponsored |
| Static page | The page title | Sections |

---

## 10. Cross-cutting placements

| Concern | Where it appears |
| --- | --- |
| **Sponsored (C-16)** | Homepage block (below the primary search entry point) · search results (top slot) · Category page (above the organic list) · Category × Area page. **Nowhere else** (PL-1, PL-2) |
| **Report (C-15)** | Every Business profile (TS-1); every published Review (authenticated) |
| **Share (C-17)** | Every Business profile |
| **Save (C-14)** | Business cards in lists; Business profile |
| **Sign-in prompt** | Only on attempting an authenticated-required action (GS-3) |
| **Policy links** | Footer on Web; the collapsed menu in the Mini App (C-18) |
| **Analytics (C-38)** | No visible surface; never identifies a Guest (TRD TR-202) |
| **Notifications (C-39)** | Email only (D-24). **No in-product notification surface in V1** |

---

## 11. Pages deliberately **not** created

| Not created | Why |
| --- | --- |
| Business sign-in, claim, dashboard | D-54, D-02 |
| Sponsorship purchase or self-service advertising page | D-10 — `/advertising` *explains* sponsorship and gives a contact route; it sells nothing |
| Public Customer profile | PRD §13 |
| Sub-city browse level | §2.1 — a thin page with no capability |
| "All businesses" directory index | Unbounded list; the API and the site are not bulk-export surfaces (TRD TR-30) |
| Blog, news, editorial | Not in V1 |
| Onboarding, tour, welcome screens | UXP-3.4 |
| Separate mobile domain or `/m/` path | Mobile-first means one responsive site (D-52) |
| Separate Telegram URL space | One domain model (D-49) |
| Dedicated map-first browse page | Maps are an enhancement (MOB-5); strategy **Open (D-21)** |

---

## 12. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-38 | Mini App navigation model | **Open — implementation detail**; §4 holds for either answer |
| D-21 | Maps embed strategy and fallback | **Open — product detail**; affects the profile map block only |
| D-40 | Launch-area boundary | **Open — product detail**; affects which Areas exist, not the structure |
| D-56 / D-57 | Category catalogue and cardinality | **Closed 2026-10-07.** The catalogue is centrally curated reference data over exactly two levels (D-56); each Listing has one primary Category and zero or more secondaries (D-57). §3.3 breadcrumbs use the primary Category, which is now guaranteed to exist |
| D-35 | Structured guest suggestions | **Open — product detail**; `/b/{business}/report` is free-text today |
| D-04 | Hours model | **Open — product detail**; affects the hours block, not the IA |
| — | Minimum-content threshold for SEO-9 | **Open — product detail**; the rule exists, the number does not |
| — | Amharic URL and metadata model | Deferred; IAR-6 keeps it possible |

---

## Decision references

D-02, D-04, D-06, D-10, D-16, D-18, D-21, D-24, D-35, D-38, D-40, D-49,
D-52, D-54, D-56, D-57.
