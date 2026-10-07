# Public Web UX

| | |
| --- | --- |
| **Document** | Public Web UX — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** Screen-by-screen specification of every public screen.

**Template.** Every screen is specified with the same eighteen headings:
*Purpose · Primary user · Entry points · Primary task · Layout hierarchy ·
Content hierarchy · Primary CTA · Secondary actions · Loading state ·
Empty state · Error state · Authentication behaviour · Mobile behaviour ·
Desktop behaviour · Accessibility notes · SEO notes · Performance notes ·
Relevant C-xx / D-xx.*

Components are referenced by number from
[`component-spec-v1.0.md`](component-spec-v1.0.md) §45.

---

## 0. Rules for every public screen

| ID | Rule | Source |
| --- | --- | --- |
| PWX-0.1 | Every public screen is fully usable by a **Guest**, with the single exception of the account screens | GS-1, C-01 |
| PWX-0.2 | Search is reachable from every screen without scrolling to find it | C-01, IAR-9 |
| PWX-0.3 | A missing optional field is **omitted silently**, with its space | C-08, UXP-7.2 |
| PWX-0.4 | Every screen is readable with **no JavaScript** | MOB-5, D-16 |
| PWX-0.5 | Every indexable screen has one `h1`, a non-skipping heading order, a unique title and a unique description | SEO-5, WCAG 1.3.1 |
| PWX-0.6 | No screen shows an empty Sponsored frame | PL-5 |
| PWX-0.7 | No screen fabricates content to look fuller | UXP-7.6 |
| PWX-0.8 | The Mini App renders the same screens with host chrome suppressed | SUR-5, D-49 |

---

# 1. Home

**Purpose.** Prove in one screen that Bulbula knows the businesses of the
area, and get the User into search or browsing immediately.

**Primary user.** Guest, on a low-end phone, often on a slow connection.

**Entry points.** Direct, bookmark, external search engine, shared link,
Mini App launch, the wordmark on any screen.

**Primary task.** Start a search, or enter a Category or an Area.

**Layout hierarchy (mobile, top to bottom).**
1. Header with wordmark and account control (§1)
2. **Search field with a visible submit control (§6)** — *above the fold,
   no scrolling required* (C-01 AC)
3. Discovery bar: Categories · Areas · Nearby · Saved (§3)
4. A short statement of what Bulbula is — one or two lines
5. Primary Categories as tiles (§13)
6. Areas as tiles (§13)
7. **Homepage sponsored block (§19) — only if sold; otherwise absent
   entirely**
8. Recently verified or recently added Businesses `[P]` — only if the
   underlying data supports it honestly
9. Footer (§2)

**Content hierarchy.** Search → discovery axes → identity statement →
browse entries → sponsorship → footer. Sponsorship is **never** the first
content block.

**Primary CTA.** The search field.

**Secondary actions.** Enter a Category · enter an Area · Nearby · Saved ·
static pages.

**Loading state.** The header and search field render immediately and are
never blocked by data. Category and Area tiles may show skeletons.
Sponsored and "recently verified" blocks load last and never delay
anything above them.

**Empty state.** If no Listing is published, Home shows an honest
statement of coverage and a route to report a missing business (C-15). It
**never** shows fabricated Categories or placeholder Businesses (PWX-0.7).

**Error state.** If a block fails, the block is omitted; the screen still
loads. If the page itself fails, a plain error with retry and a working
search field.

**Authentication behaviour.** None requested. **No sign-in prompt, no
modal, no banner** (GS-3, GS-4). A signed-in Customer sees a Saved entry
point in the discovery bar (C-01).

**Mobile behaviour.** Search within thumb reach. Tiles in two columns.
Only one sticky region at most.

**Desktop behaviour.** One header row with navigation inline; tiles in up
to five columns; content capped at the page container width.

**Accessibility notes.** Skip link first. `h1` states what Bulbula is.
Search is a labelled landmark. Tiles are links whose names include their
counts. Sponsored is announced as sponsored (LB-7).

**SEO notes.** Indexable. The most important internal link hub: it links
to the Categories index, the Areas index and the primary Categories and
Areas (SEO-12). Title and description describe the directory and its area.
Canonical to the root (SEO-2).

**Performance notes.** The lightest screen in the product. Within the
critical budget (DSN-12.1). No font request, no carousel, no hero video,
no map. The homepage is the Largest-Contentful-Paint test case
(NFR-P `[P]`).

**Relevant C-xx.** C-01, C-02, C-03, C-04, C-05, C-07, C-16, C-18, C-34.
**Relevant D-xx.** D-52, D-53, D-49, D-10.

---

# 2. Search results

**Purpose.** Answer a query with the right businesses, or fail usefully.

**Primary user.** Guest with a specific intent.

**Entry points.** Search field on any screen · autocomplete "see all" ·
a shared or bookmarked results URL · a corrected query from a zero-result
screen.

**Primary task.** Identify a business worth opening.

**Layout hierarchy (mobile).**
1. Header with the search field **holding the submitted query** (IAR-25)
2. Result count · sort (§10)
3. Filter trigger with the active count (§8) · active-filter chips (§9)
4. **Sponsored group with its label (§19)** — if sold, clearly separated
5. Organic result list (§12) of Business cards (§11)
6. Pagination (§27)
7. "Browse Categories / Areas instead"

**Content hierarchy.** Query echo → how many → how to narrow → results →
more results.

**Primary CTA.** Opening a result.

**Secondary actions.** Refine the query · filter · sort · page · Save from
a card · browse instead.

**Loading state.** Skeleton cards matching the real card layout, shown only
past ~1 s (UR-11). Filters stay interactive while results load.

**Empty state — the zero-result screen.** The fullest empty state in the
product, specified in [`user-flows-v1.0.md`](user-flows-v1.0.md) §A7:
the query is retained and editable; broader matches are **labelled as
broader**; any dropped filter is **named**; alternatives by wider Area and
related Category; browse routes; and a correction route that needs no
account. **No Sponsored placement may fill this screen** (UFL-A7.4).

**Error state.** Search unavailable → a plain message, the query
preserved, retry, and browse routes that do not depend on search.

**Authentication behaviour.** None. Save on a card triggers the auth prompt
**on tap only** and returns to this screen with the result saved and scroll
position intact (UFL-B4, UFL-0.1).

**Mobile behaviour.** Single column. Filters in a bottom sheet (§30). Sort
compact beside the count. Back returns here with results, filters and
scroll intact (IAR-28).

**Desktop behaviour.** Persistent filter column from `lg`; two or three
result columns. Separation between the Sponsored group and organic results
is maintained at every width (LB-5).

**Accessibility notes.** The result count is announced on change (WCAG
4.1.3). Results are a list. The Sponsored label is part of each sponsored
result's accessible name. Focus moves to the top of the results on page
change.

**SEO notes.** **Not indexable** (SEO-8). Internal search result pages are
thin and duplicative; they carry `noindex` and are excluded from the
sitemap. Browse pages — not search — are the indexable discovery surfaces.

**Performance notes.** Search runs inside MariaDB against a denormalised
search document (TD-04); there is no cache service (TD-05). The screen is
designed to work well at a bounded result count per page rather than a
large one (`performance-and-caching.md`).

**Relevant C-xx.** C-02, C-03, C-14, C-16.
**Relevant D-xx.** D-09 (ranking weights — open), D-52.

---

# 3. Categories index

**Purpose.** Show what kinds of business exist.

**Primary user.** Guest browsing rather than searching.

**Entry points.** Discovery bar · Home · breadcrumbs · external search
engine.

**Primary task.** Pick a Category.

**Layout hierarchy.** Header · breadcrumb (§4) · `h1` "Categories" ·
Category tiles with counts (§13) · footer.

**Content hierarchy.** Categories, grouped or alphabetical `[P]`, each with
its real count.

**Primary CTA.** Opening a Category.

**Secondary actions.** Search · Areas index.

**Loading state.** Tile skeletons.

**Empty state.** Does not occur in normal operation; if no Category has
published Listings, the screen says so honestly and offers the report
route.

**Error state.** Plain message with retry; search remains available.

**Authentication behaviour.** None.

**Mobile behaviour.** Two-column tiles; whole tile is the target.

**Desktop behaviour.** Up to five columns.

**Accessibility notes.** A real list of links; counts are part of the
accessible names.

**SEO notes.** Indexable. A primary internal link hub (SEO-12). Canonical
to itself.

**Performance notes.** Static-feeling and cheap: names and counts only.

**Relevant C-xx.** C-04. **Relevant D-xx.** D-06, D-56.

---

# 4. Category (and Subcategory)

**Purpose.** Everything of one kind, in the covered area.

**Primary user.** Guest with a category-level intent ("a pharmacy").

**Entry points.** Categories index · Home tiles · autocomplete · a
profile's Category link · breadcrumb · external search engine.

**Primary task.** Find a business of this kind.

**Layout hierarchy (mobile).**
1. Header · breadcrumb (§4)
2. `h1` — the Category name
3. One-line description of the Category, where one exists
4. **Subcategory chips** (§9-style) — if the Category has Subcategories
5. **Areas where this Category has published Listings** → Category × Area
6. Sort · filter · result count
7. **Category sponsorship block with its label (§19)** — if sold
8. Organic list (§12)
9. Pagination (§27)
10. Related Categories

**Content hierarchy.** What this Category is → how to narrow it (by
Subcategory, then by Area) → the businesses.

**Primary CTA.** Opening a Business.

**Secondary actions.** Subcategory · Area · filter · sort · Save · search.

**Loading state.** Skeleton cards; the heading, Subcategories and Areas
render first.

**Empty state.** An honest statement that nothing is published here yet,
with Subcategory, Area, search and report routes. **An empty Category is
not listed in navigation at all** (C-04), so this state is only reachable
by a stale link.

**Error state.** Section-level failures degrade individually; the list
failing still leaves navigation and search.

**Authentication behaviour.** None. Save behaves as on search results.

**Mobile behaviour.** Subcategory chips scroll horizontally **with a
visible affordance**; filters in a sheet; single-column list.

**Desktop behaviour.** Persistent filter column; two or three card
columns; Subcategories and Areas as visible groups rather than scrollers.

**Accessibility notes.** `h1` is the Category name. Subcategory and Area
groups are labelled navigation regions. Counts are in accessible names.

**SEO notes.** **Indexable and a priority page** (SEO-12). Unique title and
description from the Category and the covered area. Canonical to the base
Category URL; filters, sort and pagination do not create second indexable
URLs (SEO-10, IAR-5). A merged or retired Category permanently redirects
(SEO-4). Links down to Subcategories and across to Category × Area pages.

**Performance notes.** Counts and lists come from the denormalised search
document (TD-04). Bounded page size; no infinite scroll.

**Relevant C-xx.** C-04, C-06, C-14, C-16.
**Relevant D-xx.** D-06, D-09, D-56, D-57.

---

# 5. Areas index

**Purpose.** Show the geography Bulbula covers.

**Primary user.** Guest who thinks in places.

**Entry points.** Discovery bar · Home · breadcrumb · external search
engine.

**Primary task.** Pick an Area.

**Layout hierarchy.** Header · breadcrumb · `h1` "Areas" · Area tiles with
Sub-city shown as secondary text and real counts (§13) · footer.

**Content hierarchy.** Areas grouped by Sub-city `[P]`, each with its
count.

**Primary CTA.** Opening an Area.

**Secondary actions.** Search · Categories index.

**Loading state.** Tile skeletons.

**Empty state.** As the Categories index.

**Error state.** As the Categories index.

**Authentication behaviour.** None.

**Mobile behaviour.** Two-column tiles.

**Desktop behaviour.** Multi-column, grouped by Sub-city.

**Accessibility notes.** Grouping by Sub-city uses real headings, not
visual separation alone (WCAG 1.3.1).

**SEO notes.** Indexable. An internal link hub for the geographic axis.

**Performance notes.** Names and counts only.

**Relevant C-xx.** C-05. **Relevant D-xx.** D-40 (launch-area boundary —
open).

---

# 6. Area

**Purpose.** Everything published in one place.

**Primary user.** Guest with a local intent ("what's in Bole Bulbula").

**Entry points.** Areas index · Home · autocomplete · a profile's Area
link · breadcrumb · external search engine.

**Primary task.** Find a business here, usually of a particular kind.

**Layout hierarchy (mobile).**
1. Header · breadcrumb
2. `h1` — the Area name, with Sub-city as secondary
3. **Categories present in this Area** → Category × Area *(the primary
   affordance on this screen — most Area intents are category intents)*
4. Sort · filter · result count
5. Organic list (§12)
6. Pagination
7. Neighbouring Areas

**Content hierarchy.** Where this is → what kinds of business are here →
the businesses → nearby places.

**Primary CTA.** Picking a Category present in this Area.

**Secondary actions.** Open a Business · filter · sort · neighbouring Area
· search.

**Loading state.** Heading and Category list first; result skeletons after.

**Empty state.** Honest statement plus neighbouring Areas, the Categories
index, search and the report route.

**Error state.** Section-level degradation as elsewhere.

**Authentication behaviour.** None.

**Mobile behaviour.** Category list as a wrapped set of chips, not a long
scroller.

**Desktop behaviour.** Categories as a visible column or grid; filter
column from `lg`.

**Accessibility notes.** `h1` is the Area name. The Category group is a
labelled navigation region.

**SEO notes.** **Indexable and a priority page.** Unique title and
description. Links across to Category × Area pages (SEO-12).

**Performance notes.** As the Category screen.

**Relevant C-xx.** C-05, C-06, C-14.
**Relevant D-xx.** D-40.

---

# 7. Category × Area

**Purpose.** The highest-intent discovery page in the product — "this kind
of business, in this place".

**Primary user.** Guest with a complete, local, category-level intent;
also the most likely external-search-engine landing.

**Entry points.** Category page · Area page · autocomplete · breadcrumb ·
external search engine.

**Primary task.** Choose between the businesses of this kind here.

**Layout hierarchy (mobile).**
1. Header · breadcrumb "Home › Categories › *Category* › in *Area*"
2. `h1` — "*Category* in *Area*"
3. Result count · sort · filter
4. **Sponsored slot with its label (§19)** — if sold
5. Organic list (§12)
6. Pagination
7. Onward links: the same Category in neighbouring Areas · other
   Categories in this Area · the parent Category · the parent Area

**Content hierarchy.** The intersection stated plainly → the businesses →
the nearest alternatives.

**Primary CTA.** Opening a Business.

**Secondary actions.** Widen to the Category · widen to the Area · a
neighbouring Area · filter · sort · Save · search.

**Loading state.** Skeleton cards.

**Empty state.** Important and common on a young directory. It offers, in
order: the same Category in a wider or neighbouring Area · other Categories
in this Area · search · the report route. It **never** shows unrelated
businesses as if they matched (UFL-A3).

**Error state.** As the Category screen.

**Authentication behaviour.** None.

**Mobile behaviour.** As the Category screen.

**Desktop behaviour.** As the Category screen.

**Accessibility notes.** The `h1` states both axes, so a screen-reader user
knows the scope immediately. Onward links are grouped and labelled.

**SEO notes.** **The most SEO-sensitive screen.** One canonical URL,
`/c/{category}/in/{area}` — the Area axis does not mint a duplicate
(IAR-5, SEO-10). A page below the minimum-content rule is **served but
not indexable and excluded from the sitemap** (SEO-9); the threshold is
**Open — product detail**. Unique title and description generated from
both axes — never a template that reads identically across hundreds of
pages. Thin, near-duplicate intersection pages are the main duplicate-
content risk in this product and this rule exists to contain it.

**Performance notes.** Potentially the highest page count in the sitemap;
it must be cheap to render from the search document (TD-04) and must not
require per-page hand-authored content.

**Relevant C-xx.** C-06, C-14, C-16, C-37.
**Relevant D-xx.** D-09, D-40, D-56.

---

# 8. Business profile

**Purpose.** Everything a person needs to decide whether to go, call or
trust this business — and nothing else.

**Primary user.** Guest, usually on a phone, often arriving directly from
an external search engine or a shared link.

**Entry points.** Search results · autocomplete · Category, Subcategory,
Area and Category × Area lists · Nearby · Saved · My Reviews · a shared
link · an external search engine · a Sponsored placement.

**Primary task.** Decide, then act — call, visit, or get directions.

### 8.1 Information hierarchy

Ordered by **decision value**, not by database order. Only fields that
help a person decide appear; the rest stay in the operations console
(C-08).

| # | Block | Contains | Rule |
| --- | --- | --- | --- |
| 1 | **Identity** | Business name (`h1`) · primary Category (link) · Subcategory | Always present |
| 2 | **Trust** | Verified indicator **with its date** (§16) | Only if verified; **no "unverified" badge** (DSN-9.14) |
| 3 | **Rating summary** | Average **with** count (§14) | **Absent entirely if there are no published Reviews** (UR-16, TR-63) |
| 4 | **Open status** | Open · Closed · Hours not confirmed (§17) | Three states, never colour alone (UR-17) |
| 5 | **Location** | Area · Sub-city · address · landmark | Text always; the map is additional |
| 6 | **Contact actions** | Call · Website · Directions · Social (§18) | Only those that exist; **high on the page on mobile** (UR-10) |
| 7 | **Branch selector** | If more than one Branch | Drives blocks 4, 5, 6, 9 |
| 8 | **Hours** | Weekly schedule, expandable (§17) | Only if recorded |
| 9 | **Map** | Static preview, opens on demand (§20) | Deferred, never blocking (DSN-12.6) |
| 10 | **About** | Short description | Only if it adds information |
| 11 | **Photos** | Gallery (§21) | Block absent if there are none |
| 12 | **Reviews** | Summary, then published Reviews, paginated (§14, §27) | Reading is Guest-safe (GS-2) |
| 13 | **Write a review** | Entry point to the review form | Gated — prompts on tap only |
| 14 | **Actions** | Save (§15) · Share (§22) · **Report a problem** (§23) | Report is **Guest-safe** (TS-2) |
| 15 | **Provenance / freshness** | When this record was last verified | Stated honestly, including when stale (UXP-7.3) |
| 16 | **Onward discovery** | The Category · the Area · similar businesses nearby | The internal link graph (SEO-12) |

**Fields that are deliberately not displayed:** internal identifiers,
completeness score, Permission record, Provenance detail beyond the
verification date, contact points flagged as personal, operational state,
moderation history, audit data, any field whose only consumer is the
console.

**Layout hierarchy (mobile).** Blocks 1–6 occupy the first screen wherever
the data allows — identity, trust, open status, location and contact are
what a mobile User came for (UR-10). Everything else follows in the order
above.

**Content hierarchy.** Who they are → can you trust this → are they open →
where → how to reach them → what others say → what you can do.

**Primary CTA.** The most actionable contact point present — Call where a
phone number exists, otherwise Directions, otherwise Website.

**Secondary actions.** Save · Share · Write a review · Report a problem ·
browse the Category or Area.

**Loading state.** Blocks 1–6 render first and are never blocked by the
map, the gallery or the Reviews. The map and gallery are deferred. Reviews
may load after the primary content.

**Empty states.** No Reviews → no rating element at all, plus an invitation
to be the first (TR-63). No photos → the block is absent. No hours →
"Hours not confirmed", neutrally (UXP-7.3). No coordinates → address and
landmark with no empty map frame.

**Error state.** A failed map degrades to address plus a directions link.
A failed gallery degrades to placeholders. A failed Reviews section shows a
retry without affecting the rest. An **unpublished or non-existent**
Business returns **404 with noindex** — never a "this business was
removed" page that remains indexed (C-08).

**Authentication behaviour.** Reading everything is Guest-safe — **no
truncation, no blur, no teaser** (GS-2). Save and Write a review prompt on
tap and **return here and complete the action** (UFL-0.1). Reporting the
*Business* needs no account; reporting a *Review* does.

**Mobile behaviour.** One column. Contact actions within thumb reach. The
map does not capture scroll. The gallery swipes but also has tap controls
(WCAG 2.5.1).

**Desktop behaviour.** Two columns — primary content left, a secondary
column with map, hours and contact right — **without changing the
information hierarchy** (IAR §7): the DOM order stays as specified so the
reading order and the screen-reader order match (WCAG 1.3.2).

**Accessibility notes.** `h1` is the Business name. Each block is a
section with a heading. Verified, Sponsored-origin and Open status are all
distinguishable without colour (NFR-AC3). Contact actions have names
stating action and target. The rating's accessible name includes the
count. Arriving from a Sponsored placement changes nothing about this
screen (UXP-5.8).

**SEO notes.** **The most important indexable page type.** Unique title and
description from the name, Category and Area. Canonical to
`/b/{business}`; a branch parameter is not a separate canonical (IAR-5). A
changed slug redirects permanently and the URL survives a name change
(IAR-3, SEO-4). Structured data for the Business, location, hours and
rating **where one exists**, and the marked-up rating **must equal the
visible rating** (SEO-6, R-15). Breadcrumb structured data (SEO-12).
Unpublished → 404 and removal from the sitemap (SEO-13). Links out to the
Category and Area.

**Performance notes.** The highest-traffic page type; it must render from
a small number of queries with no cache service (TD-05). Map and gallery
are deferred. Images use fixed ratios with reserved space (DSN-8.12).
Reviews are paginated, never loaded in bulk.

**Relevant C-xx.** C-08, C-09, C-10, C-11, C-12, C-13, C-14, C-15, C-16,
C-17, C-22, C-37.
**Relevant D-xx.** D-03 (one primary Branch), D-04, D-08, D-12, D-21,
D-25, D-34, D-54, D-55.

---

# 9. Nearby

**Purpose.** Distance-ordered discovery for someone standing somewhere
(C-07).

**Primary user.** Guest, on the move.

**Entry points.** Discovery bar · Home.

**Primary task.** See what is close.

**Layout hierarchy.** Header · `h1` "Nearby" · an explanation of what
location is used for, **before** the request (UR-15) · the request control
· results with distances · a fallback route to Area browsing.

**Content hierarchy.** Why location is needed → results → alternatives.

**Primary CTA.** Share location — or, where it is denied, browse by Area.

**Secondary actions.** Filter by Category · widen the radius explicitly ·
open a Business · search.

**Loading state.** A brief indicator while the position is resolved, with a
cancel route.

**Empty state.** Nothing in the radius → offer a wider radius, Area
browsing and search. **The radius is never silently widened** (UFL-A4).

**Error state.** Denied, unavailable or timed out → degrade to Area
browsing with a plain explanation and a retry. **No nag, no repeat prompt,
no blocked screen** (C-07).

**Authentication behaviour.** None; location is not an account feature.

**Mobile behaviour.** The primary context for this screen. Distances shown
as approximate (`search-design.md` DS-6).

**Desktop behaviour.** Works, but Area browsing is given equal prominence
because desktop geolocation is coarse.

**Accessibility notes.** The permission explanation is real text before the
control, not a tooltip. Distance is part of each card's accessible name.

**SEO notes.** **Not indexable** (SEO-8) — the content is per-User and
non-reproducible.

**Performance notes.** Coordinates are used in-request and **never stored,
never logged, never sent to analytics** (TRD TR-201). Branches without
coordinates are excluded from ordering but remain findable elsewhere
(DS-7).

**Relevant C-xx.** C-07, C-10. **Relevant D-xx.** D-21, D-40.

---

# 10. Customer account screens

All require authentication. All are **noindex** (SEO-8).

## 10.1 Sign in

**Purpose.** Establish identity with the least friction (C-30, C-31,
C-32).

**Primary user.** A Guest who has just attempted a gated action.

**Entry points.** A gated action (the normal case) · the account menu · a
direct visit.

**Primary task.** Sign in and **return to the task** (UFL-0.1).

**Layout hierarchy.** What is being unlocked · **Continue with Google** ·
**Continue with email** · a link to the privacy notice · cancel.

**Primary CTA.** Google on the Web; **email OTP first inside Telegram**
(UR-14, TG-5).

**Secondary actions.** Switch method · cancel and return unchanged.

**Loading state.** The chosen method shows a busy state; the other remains
available.

**Empty state.** Not applicable.

**Error state.** Generic, non-enumerating; the alternative method is
offered; browsing is unaffected (`auth-identity.md` GA-6, OTP-8).

**Authentication behaviour.** This *is* the authentication screen. **No
password field exists anywhere** (D-48). No CAPTCHA, no puzzle, no memory
test (WCAG 3.3.8).

**Mobile behaviour.** Full width; the OTP field uses a numeric keypad and
accepts paste (§33).

**Desktop behaviour.** Centred, narrow.

**Accessibility notes.** Real labels; errors associated with fields; the
cooldown announced; focus managed between the two OTP steps.

**SEO notes.** Noindex.

**Performance notes.** Minimal; must work on a slow connection where the
Google flow may be the slow part.

**Relevant C-xx.** C-30, C-31, C-32.
**Relevant D-xx.** D-48, D-13, D-41.

## 10.2 Profile

**Purpose.** Show and edit the minimal account record (C-33).

**Layout hierarchy.** Display name (editable) · email address · sign-in
methods · dates · links to Saved, My Reviews and Privacy.

**Primary CTA.** Save changes.

**Content rules.** **No avatar, no bio, no public Customer page** (PRD
ACC-6). **Changing the email address is not a V1 capability** and its
absence is stated plainly rather than discovered (`auth-identity.md` §9,
D-13). **Nothing asks for a date of birth** (D-46, **PENDING COUNSEL**).

**Error state.** Validation inline; other values preserved (WCAG 3.3.7).

**Relevant C-xx.** C-33. **Relevant D-xx.** D-13, D-46, D-51.

## 10.3 Saved

**Purpose.** The private shortlist (C-34).

**Layout hierarchy.** `h1` "Saved" · count · Business cards (§11) with
remove · pagination.

**Primary CTA.** Opening a saved Business.

**Empty state.** Explains what Save is for and routes to search and
browsing. It **does not suggest businesses to save**.

**Content rules.** A Business later unpublished is shown **with its current
state**, not silently removed (C-34). **No save counts anywhere** (UR-18).

**Relevant C-xx.** C-14, C-34.

## 10.4 My Reviews

**Purpose.** See and manage own Reviews (C-35).

**Layout hierarchy.** `h1` · each Review with its subject Business, rating,
text, date and **state** — pending · published · rejected · removed — with
the policy ground where rejected (C-25) · edit and delete.

**Primary CTA.** Edit a Review.

**Empty state.** Explains how to write one; links to search.

**Content rules.** Rejection reasons are stated plainly with the policy
ground, and whether an appeal route exists. A rejected Review is **not
silently deleted**. Deletion requires confirmation (§31).

**Open.** Scale, limits, edit window and deletion semantics — **D-34**.

**Relevant C-xx.** C-13, C-25, C-35. **Relevant D-xx.** D-34.

## 10.5 Privacy and deletion

**Purpose.** Exercise data rights (C-36, C-40).

**Layout hierarchy.** What Bulbula holds · export own data · delete
account · links to the privacy notice.

**Primary CTA.** Depends on intent; neither is visually emphasised.

**Content rules.** Deletion states **what will and will not be removed
before it happens**, requires explicit confirmation, revokes every session
and sends an email confirmation (UFL-B9). The fate of published Reviews is
**Open (D-34, L-21)** and the screen must state the **decided** outcome,
not a guess. Response windows and export format are **PENDING COUNSEL
(L-7)**.

**Error state.** A failed export says so and offers retry; it never
silently produces nothing.

**Relevant C-xx.** C-36, C-39, C-40. **Relevant D-xx.** D-34, D-42.

---

# 11. Static and policy pages

**Purpose.** Explain how Bulbula works and meet its disclosure obligations
(C-18).

**Primary user.** Anyone — including a Business owner who has no account
and no dashboard (D-54), for whom these pages are the **only** route in.

**Entry points.** Footer · the account menu in the Mini App · in-context
links (the review policy from the review form, "how we verify" from a
Verified indicator, "how ranking works" from a results screen).

**The set.**

| Page | Content | Source |
| --- | --- | --- |
| About | What Bulbula is, what area it covers, how listings get there | C-18 |
| How we verify | Verification in plain words, and what Verified does and does not mean | C-12, C-18 |
| How ranking works | That organic ranking is editorial-quality-driven and **that Sponsored placement never affects it** | PL-6, D-39, SRCH-5 |
| Review policy | Grounds for rejection and removal, and the appeal route | `review-policy.md` |
| Corrections | How to report a problem and what happens next | C-15 |
| Advertising | What sponsorship is, what it does **not** buy, and a contact route. **It sells nothing and shows no price** | D-10, D-11, L-16 |
| Privacy notice | What is collected, why, how long, and rights | C-40, **PENDING COUNSEL** |
| Terms | Terms of use | **PENDING COUNSEL** |
| Contact | How to reach Bulbula, including for businesses | C-18 |

**Layout hierarchy.** Header · breadcrumb · `h1` · a narrow reading column
· last-updated date · footer.

**Content hierarchy.** Plain language first; detail after.

**Primary CTA.** Usually none. Corrections and Advertising carry a contact
route.

**Loading state.** None — these are static.

**Empty state.** Not applicable. **A page that is not written is not
linked** (PWX-0.7).

**Error state.** Standard 404 with a route to the page index and search.

**Authentication behaviour.** None.

**Mobile behaviour.** Single narrow column; body text at the default size
(DSN-3.8).

**Desktop behaviour.** A capped reading measure (DSN-8.2), not full width.

**Accessibility notes.** Proper heading structure; links meaningful out of
context (WCAG 2.4.4); the help route is in a consistent place on every
screen (WCAG 3.2.6).

**SEO notes.** Indexable. Unique titles and descriptions. "How we verify"
and "How ranking works" also serve trust and are linked from in-product
indicators.

**Performance notes.** The cheapest pages in the product.

**Legal note.** The privacy notice and terms are **PENDING COUNSEL**:
their content depends on L-5, L-6, L-7, L-10, L-12, L-21 and L-22. This
document specifies **where they live and how they are reached**, not what
they say. Advertising-disclosure obligations (L-16) and invoicing/VAT
(L-17, L-20) are likewise out of scope here.

**Relevant C-xx.** C-18, C-12, C-15, C-40.
**Relevant D-xx.** D-10, D-11, D-42, D-54.

---

# 12. Cross-screen behaviours

## 12.1 Sponsored placement, by screen

| Screen | Placement | Behaviour |
| --- | --- | --- |
| Home | Homepage promotion | Below the primary search entry point and below the discovery axes; collapses entirely when unsold (C-01, PL-5) |
| Search results | Search-result slot | A separate labelled group above the organic list, with separation greater than the spacing within either group (LB-5) |
| Category | Category sponsorship | Above the organic list, labelled, separated |
| Category × Area | Search-result slot | As search results |
| **Zero results** | **None permitted** | ZR-5 |
| Area | **None** | Not one of the three forms |
| Business profile | **None** | PL-1 |
| Account, static, Nearby | **None** | PL-1 |

A Business **never appears twice on one screen** as both sponsored and
organic (PL-7). Sponsored items are **not counted in the organic result
count** (LB-8). Density values (PL-3, PL-4) are `[P]` and unapproved;
prices and inventory counts are **not set** (D-10, D-11).

## 12.2 Authentication, by screen

| Screen | Gated element | Behaviour on tap |
| --- | --- | --- |
| Any list | Save on a card | Prompt → return → Save completed, scroll preserved |
| Business profile | Save, Write a review, Report a Review | Prompt → return → action completed |
| Discovery bar | Saved | Prompt → return → Saved list |
| Everything else | — | No prompt ever appears (GS-3, GS-4) |

## 12.3 SEO summary

| Screen | Indexable | Canonical |
| --- | --- | --- |
| Home | Yes | `/` |
| Categories index | Yes | self |
| Category | Yes | base Category URL |
| Subcategory | Yes | self |
| Areas index | Yes | self |
| Area | Yes | self |
| Category × Area | **Yes, unless below the minimum-content rule** (SEO-9) | `/c/{category}/in/{area}` |
| Business profile | Yes | `/b/{business}` |
| Static and policy | Yes | self |
| Search results | **No** | — |
| Nearby | **No** | — |
| Sign in, account, Saved, My Reviews, Privacy | **No** | — |
| Report and review forms | **No** | — |
| `/ops/*` | **No** | — |

## 12.4 Performance, by screen

| Screen | Dominant cost | Mitigation |
| --- | --- | --- |
| Home | Tiles and counts | Precomputed counts; no media above the fold |
| Search | Query execution | MariaDB search document (TD-04); bounded page size |
| Category / Area / Category × Area | Count plus list | Same document; pagination |
| Business profile | Several blocks | Primary blocks first; map and gallery deferred; Reviews paginated |
| Nearby | Distance computation | Bounded radius; no storage of coordinates |
| Static | None | — |

No cache service exists (TD-05); no background worker exists (TD-06).
Nothing in this document assumes either.

---

# 13. Screen-to-capability coverage

| Capability | Screen(s) |
| --- | --- |
| C-01 | Home |
| C-02 | Search results; search field on every screen |
| C-03 | Autocomplete on every screen with a search field |
| C-04 | Categories index, Category, Subcategory |
| C-05 | Areas index, Area |
| C-06 | Category × Area |
| C-07 | Nearby |
| C-08 | Business profile |
| C-09 | Business profile §8.1 block 4 and 8 |
| C-10 | Business profile block 9; Nearby |
| C-11 | Business profile block 6 |
| C-12 | Business profile block 2; "How we verify" |
| C-13 | Business profile block 12; My Reviews |
| C-14 | Cards on every list; profile block 14; Saved |
| C-15 | Business profile block 14; zero-result screen; Corrections page |
| C-16 | Home, Search, Category, Category × Area (§12.1) |
| C-17 | Business profile block 14 |
| C-18 | Static and policy pages |
| C-19…C-29 | **Operations console** — see [`operations-console-ux-v1.0.md`](operations-console-ux-v1.0.md) (Web only, `scope-v1.md` §1.5) |
| C-30 | Sign in |
| C-31 | Sign in (OTP) |
| C-32 | Sign in; Profile |
| C-33 | Profile |
| C-34 | Saved |
| C-35 | My Reviews |
| C-36 | Privacy and deletion |
| C-37 | §12.3, and the SEO notes on every screen |
| C-38 | Cross-cutting, no visible surface (TR-202) |
| C-39 | Email only (D-24); confirmations referenced in §10.5 and UFL-B9 |
| C-40 | Privacy and deletion; privacy notice |

**All forty capabilities are covered**: C-01…C-18 and C-30…C-40 on the
screens above, C-19…C-29 in the operations console document.

---

# 14. Open items

| ID | Item | Screens affected |
| --- | --- | --- |
| D-04 | Hours model | Business profile |
| D-08 | Verification methods and tiers | Business profile, How we verify |
| D-09 | Ranking weights and completeness use | Search, Category, Category × Area |
| D-10 / D-11 | Ad pricing and billing | Advertising page, all sponsored placements |
| D-13 | Identity linking | Sign in, Profile |
| D-19 | Dark mode | all (readiness only) |
| D-21 | Maps | Business profile, Nearby |
| D-25 | Media limits | Business profile gallery |
| D-28 | Logo | header on all screens |
| D-33 | Telegram identity | Sign in inside the Mini App |
| D-34 | Review mechanics and deletion semantics | Business profile, My Reviews, Privacy |
| D-35 | Structured guest suggestions | Report form |
| D-38 | Mini App navigation model | all |
| D-40 | Launch-area boundary | Areas index, Area, Category × Area |
| D-46 | Minimum age | Sign in, Profile — **PENDING COUNSEL** |
| D-55 | Branch versus Business attributes | Business profile |
| D-56 / D-57 | Category catalogue and cardinality | Categories index, Category, breadcrumbs |
| — | Minimum-content threshold for SEO-9 | Category × Area — **Open — product detail** |
| L-5…L-7, L-21, L-22 | Privacy notice and terms content | Static pages — **PENDING COUNSEL** |

---

## Decision references

D-03, D-04, D-06, D-08, D-09, D-10, D-11, D-12, D-13, D-16, D-19, D-21,
D-24, D-25, D-28, D-33, D-34, D-35, D-38, D-39, D-40, D-41, D-42, D-46,
D-48, D-49, D-51, D-52, D-53, D-54, D-55, D-56, D-57.
