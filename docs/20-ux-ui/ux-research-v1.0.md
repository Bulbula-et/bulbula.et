# UX Research

| | |
| --- | --- |
| **Document** | UX Research Findings — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The evidence base for the UX and design decisions in this phase.
Findings are numbered **UR-01…UR-18** and cited by the other UX documents.

**Numbering.** Phase 2 research findings are `R-01…R-27` in
[`../00-discovery/research-notes-v0.1.md`](../00-discovery/research-notes-v0.1.md)
and the discovery briefs. **UX findings use the `UR-` prefix** so the two
sets never collide. Where a UX finding depends on an earlier one, the earlier
`R-xx` is cited.

**Discipline.** Research extracts reusable *interaction principles*. It does
**not** create product requirements. Where a finding suggests a capability
Bulbula has not approved, the finding records the restraint explicitly. No
product is copied visually.

---

## 1. Accessibility and standards

### UR-01 — WCAG 2.2 AA is the right target, and it is specific

| | |
| --- | --- |
| **Source** | W3C WCAG 2.2 (W3C Recommendation, October 2023); multiple conformance summaries |
| **Pattern** | WCAG 2.2 keeps all of 2.1 (minus 4.1.1 Parsing) and adds nine criteria. **Four new ones are Level AA**: 2.4.11 Focus Not Obscured (Minimum), 2.5.7 Dragging Movements, 2.5.8 Target Size (Minimum, 24×24 CSS px or equivalent spacing), 3.3.8 Accessible Authentication (Minimum). Two more are Level A: 3.2.6 Consistent Help, 3.3.7 Redundant Entry |
| **Why it matters to Bulbula** | NFR-AC1 names WCAG 2.2 AA but marks it `[P]`. The added criteria are overwhelmingly *mobile and motor* criteria — exactly Bulbula's user base (R-06). They are cheap to meet when designed in and expensive to retrofit |
| **Applies to** | NFR-AC1, NFR-AC2, D-52 |
| **Affects** | Accessibility, UX |

**Interpretation.** 2.5.8 gives a defensible floor (24 px) rather than an
invented one, and 3.3.8 is already satisfied by Bulbula's passwordless model
(D-48) — email OTP with paste support is specifically *not* a cognitive
function test. Bulbula adopts **44 px as its own touch-target target** for
primary controls (the stricter 2.5.5 AAA figure) because the product is
mobile-first, while treating 24 px as the absolute conformance floor.

### UR-02 — Sponsored labelling must not depend on colour

| | |
| --- | --- |
| **Source** | WCAG 1.4.1 Use of Colour (Level A); FTC search-engine guidance (2013) and the Native Advertising Enforcement Policy Statement (2015) |
| **Pattern** | The FTC advised search engines to use **a text label, a different background, *and* segregation from natural results** — after a study found 62 % of consumers could not identify paid results from shading alone. The FTC prefers "Ad", "Advertisement" or "Sponsored"; it explicitly calls **"Promoted" ambiguous**. The disclosure must be visible *before* the user engages, must survive mobile rendering, and must not be a hover or tooltip |
| **Why it matters to Bulbula** | `advertising-products.md` LB-1…LB-8 already requires exactly this. The research confirms the rules are a regulatory floor, not a design preference, and supplies the reason Bulbula must do **all three** things at once |
| **Applies to** | C-16, D-10, ADV-1, ADV-2, NFR-AC3, LB-4, LB-6 |
| **Affects** | UX, accessibility, content |

**Interpretation.** Bulbula's approved label wording is `[P]` "Sponsored"
(LB-5) — which is on the FTC's acceptable list. The design must add a
container boundary and a separate group, not shading alone. Ethiopian
advertising rules are a separate question and remain **PENDING COUNSEL**
(L-16).

---

## 2. Search and discovery

### UR-03 — Autocomplete lists must be short, spaced and keyboard-navigable

| | |
| --- | --- |
| **Source** | Baymard Institute, autocomplete design research; UX Magazine autosuggest guidance |
| **Pattern** | Target **4–8 suggestions on mobile**, no more than 10 on desktop, fitting the viewport without scrolling (the mobile list is sandwiched between the field and the keyboard). Style *scope* suggestions differently from plain query suggestions. Emphasise the **predictive** part of the suggestion rather than re-highlighting what was typed. Arrow keys must move through the list, Enter must submit, and the active suggestion must be **copied into the field** so it can be edited |
| **Why it matters to Bulbula** | C-03 requires autocomplete across four suggestion types — business, category, subcategory, area (`search-design.md` §2.2). Four mixed types in one list is precisely the case where grouping and distinct styling stop being cosmetic |
| **Applies to** | C-03, C-02 |
| **Affects** | UX, accessibility, performance |

**Interpretation.** Bulbula caps mobile suggestions at a small bounded
number, groups by type with a visible type label, and treats keyboard
operation as a requirement rather than an enhancement. It does **not** adopt
recent-search history — that would store a behavioural record of a Guest,
which PRIV and TR-202 forbid.

### UR-04 — A mobile search field needs its own visible submit control

| | |
| --- | --- |
| **Source** | Baymard Institute, mobile search submit research; mobile UX benchmark |
| **Pattern** | Users instinctively reach for a submit button beside the field rather than the keyboard's return key. Roughly a fifth of mobile commerce sites omit it, which slows users most during *refinement*, not first search. The field should also be wide enough for a typical query — around 27–30 characters — so the text does not scroll out of view while being edited |
| **Why it matters to Bulbula** | Search is the product's central capability (C-02) and refinement is normal when a first query under-matches. A hidden submit affordance costs most on exactly the low-end devices Bulbula targets |
| **Applies to** | C-02, MOB-1, MOB-2 |
| **Affects** | UX |

### UR-05 — A zero-result page is the highest-leverage screen in search

| | |
| --- | --- |
| **Source** | Baymard Institute search benchmark; search UX syntheses |
| **Pattern** | Zero-result pages drive very high abandonment. A bare "No results found" plus spelling tips is the common failure. Patterns that recover users: relax the query and say so, drop the most restrictive filter and name it, offer related categories, and offer a concrete fallback list |
| **Why it matters to Bulbula** | A new directory in one launch area **will** return zero results often. `search-design.md` §11 already specifies a five-step recovery; this finding is the evidence for prioritising that screen rather than treating it as an edge case |
| **Applies to** | C-02, SRCH-8 |
| **Affects** | UX, content |

**Interpretation.** Bulbula's zero-result screen is a designed destination
with its own spec, not a fallback. **Sponsored placements are never used to
fill it** (`search-design.md` ZR-5) — a commercial fill on a failure page is
precisely the pattern that destroys the trust the directory is selling.

### UR-06 — Hidden navigation measurably reduces discoverability

| | |
| --- | --- |
| **Source** | Nielsen Norman Group, hamburger menus and hidden navigation |
| **Pattern** | Hiding main navigation behind a menu icon roughly halves content discoverability, increases task time and increases perceived difficulty. Visible or partially visible navigation performs better. Mobile users use navigation more than desktop users do |
| **Why it matters to Bulbula** | The temptation on a mobile-first directory is to collapse everything into one menu. Bulbula's two discovery axes — Category and Area — are the product; burying them hides the inventory |
| **Applies to** | C-01, C-04, C-05, D-52 |
| **Affects** | UX |

**Interpretation.** Bulbula keeps **search plus the two discovery axes
persistently visible** on mobile and reserves the collapsed menu for
secondary and account items. This is a layout consequence, not a new
capability.

---

## 3. Mobile and performance

### UR-07 — A system font stack is the only font strategy that cannot fail

| | |
| --- | --- |
| **Source** | Modern Font Stacks project; Readium CSS default font-stack documentation; font-optimisation guidance |
| **Pattern** | System fonts render on first paint, cost zero bytes, cause no layout shift and need no `font-display` strategy. The cost is brand voice. A webfont can always fail — blocked request, offline, strict policy, slow first paint |
| **Why it matters to Bulbula** | NFR-P4 forbids third-party fonts blocking first render, and the page-weight budget is `[P]` but tight. Bulbula has no approved typeface and the logo is open (D-53) |
| **Applies to** | NFR-P4, D-52, D-53, MOB-3, MOB-4 |
| **Affects** | Performance, UX |

### UR-08 — Amharic needs an explicit font strategy; it is not free

| | |
| --- | --- |
| **Source** | Readium CSS Amharic stack (`Kefa, Nyala, Roboto, Noto, "Noto Sans Ethiopic", serif` — **warned as having no iOS entry**); Noto Sans Ethiopic documentation (SIL OFL, 566 glyphs, Amharic / Tigrinya / Ge'ez); a 2026 Telegram Desktop regression in which Ethiopic shaping broke outright |
| **Pattern** | Ethiopic coverage in default system stacks is uneven across platforms. `Noto Sans Ethiopic` is the free, openly licensed, comprehensive face. Ethiopic rendering can break in a client even when the text is correct |
| **Why it matters to Bulbula** | D-18 requires Amharic-ready, English-first. Amharic Business names, Category names, Area names and Aliases are **stored and displayed in V1** even while the interface chrome is English. If Ethiopic falls back to tofu, stored Amharic data is unreadable |
| **Applies to** | D-18, SEO-14, MOB-3 |
| **Affects** | UX, accessibility, performance, content |

**Interpretation.** Bulbula's font strategy is **system stack first, with an
Ethiopic-capable fallback named in the stack**, and a `unicode-range`-scoped
Ethiopic webfont as a **conditional** enhancement that downloads only when
Ethiopic characters are present. This is the one justified exception to
UR-07, and it costs nothing on an all-Latin page. **No paid font.**

### UR-09 — Skeleton screens help, modestly, and only when honest

| | |
| --- | --- |
| **Source** | Nielsen Norman Group, *Skeleton Screens 101*; controlled perceived-duration study across blank / spinner / skeleton on mobile |
| **Pattern** | On mobile, skeletons are perceived as shorter and rate better emotionally than a spinner, and much better than a blank screen — but the margin over a spinner is **small**. The critical rule: a skeleton must mirror the real layout and must not block progressive content; real content replaces placeholders as it arrives. Most shipped "skeletons" are actually splash screens |
| **Why it matters to Bulbula** | Bulbula is server-rendered (D-16, RN-1). A full-page skeleton is **pointless** — the server already sends the content. Skeletons are only justified for genuinely asynchronous fragments |
| **Applies to** | NFR-P1, D-16 |
| **Affects** | Performance, UX |

**Interpretation.** Skeletons are permitted **only** where a region loads
after the document: the map block, lazily loaded gallery images, and
progressively enhanced result updates. Everywhere else, a skeleton would be
theatre over content that already exists.

### UR-10 — Mobile is a different context, not a smaller screen

| | |
| --- | --- |
| **Source** | Nielsen Norman Group, *We Can Do Better on Mobile*; mobile UX report |
| **Pattern** | Certain content is disproportionately valuable on mobile — **opening hours, location, directions, phone** — because the user is often in transit and deciding. Shrinking a desktop layout misses this entirely |
| **Why it matters to Bulbula** | This is a direct endorsement of the product's thesis: "is it open, where is it, what is the number" is the job. It confirms the Business profile hierarchy should lead with status and contact, not with description or photography |
| **Applies to** | C-08, C-09, C-10, C-11, D-52 |
| **Affects** | UX |

### UR-11 — Interaction response budgets are perceptual, not arbitrary

| | |
| --- | --- |
| **Source** | Search UX syntheses citing the classic response-time thresholds |
| **Pattern** | ~100 ms feels instantaneous; delays past ~1 s break the user's flow of thought and require a progress indication |
| **Why it matters to Bulbula** | Gives a defensible rule for *when* a loading state is required at all, without inventing a Bulbula-specific number. Autocomplete sits in the 100 ms band; a full search sits in the second band |
| **Applies to** | C-02, C-03, NFR-P3 |
| **Affects** | Performance, UX |

**Interpretation.** Used as a **design rule** ("show feedback past ~1 s"),
not as a performance commitment. All Bulbula performance targets remain
`[P]` (NFR-P1…P3).

---

## 4. Telegram Mini App

### UR-12 — A Mini App is hosted chrome, not a framed website

| | |
| --- | --- |
| **Source** | Telegram Mini Apps platform documentation (viewport, safe area); Mini App UX guidance; published developer post-mortems |
| **Pattern** | Telegram owns the header, the bottom bar, the swipe-to-close gesture and the safe area. The host injects a bridge exposing theme, viewport and **native Back and Main buttons**. The recurring failures are: shipping your own back button beside Telegram's (two competing navigation stacks), using `100vh` instead of the host viewport height, and ignoring safe-area insets |
| **Why it matters to Bulbula** | D-49 makes the Mini App a first-class surface sharing one implementation behind an adapter. SUR-5 already confines chrome, share, auth entry, map hand-off and **viewport conventions** to that adapter — this finding says exactly what the adapter must absorb |
| **Applies to** | D-49, D-38, SUR-5, TG-1, TG-2 |
| **Affects** | UX |

**Interpretation.** The Bulbula adapter owns: navigation chrome (use the
host Back button, never a second one), viewport sizing from the host's
stable height, safe-area insets, and the share mechanism. **Nothing
product-visible changes** (SUR-6). The navigation model itself — full page
loads versus fragment swaps — stays **Open (D-38)**.

### UR-13 — Theme adoption, not a second palette

| | |
| --- | --- |
| **Source** | Telegram Mini App theming guidance |
| **Pattern** | The host supplies the user's theme (including custom themes) as parameters. Shipping a separate light and dark palette and toggling guarantees a mismatch. The recommended approach is one **semantic token set** whose values are supplied by the host inside Telegram |
| **Why it matters to Bulbula** | This is a strong argument for Bulbula's token layer being **semantic from the start** (`--surface`, `--text-muted`) rather than literal (`--orange-500`). The same indirection that makes Telegram theming work also makes a future dark mode cheap — **without making dark mode a V1 feature** (D-19 is open) |
| **Applies to** | D-49, D-19, D-53 |
| **Affects** | UX |

### UR-14 — OAuth inside an embedded webview is unreliable

| | |
| --- | --- |
| **Source** | Carried forward from **R-23** (Phase 2.1); confirmed by Mini App integration guidance |
| **Pattern** | External OAuth flows opened inside in-app webviews are blocked or broken in some clients; Telegram `initData` must be validated server-side and never trusted from the client |
| **Why it matters to Bulbula** | `auth-identity.md` §4.1 already routes Google sign-in through an external-browser mechanism on the Mini App and makes **email OTP the primary path there**. This is a UX consequence: the Mini App sign-in screen orders the two options differently from the Web |
| **Applies to** | C-30, C-31, D-48, TG-3, TG-5 |
| **Affects** | UX, accessibility |

**Interpretation.** Ordering two approved options by reliability on a given
surface is a **runtime adaptation** (D-49), not a product difference — both
options remain available on both surfaces (SUR-6).

---

## 5. Trust, reviews and local discovery

### UR-15 — Trust is earned before it is requested

| | |
| --- | --- |
| **Source** | Nielsen Norman Group "pyramid of trust" as summarised in conversion-design literature |
| **Pattern** | Interfaces must satisfy lower levels of commitment before asking for more. Asking early — for an account, for location, for personal data — causes abandonment because the basic levels have not been met |
| **Why it matters to Bulbula** | Direct support for GS-1…GS-5: the entire discovery journey is Guest-safe, sign-in appears **only** as a consequence of attempting an authenticated action, and location is requested only when the User asks for nearby (LOC-2) |
| **Applies to** | GS-1, GS-3, GS-4, C-07, C-30, C-31 |
| **Affects** | UX, content |

### UR-16 — Rating count must travel with rating average

| | |
| --- | --- |
| **Source** | Review-presentation convention across major review products; reinforced by SEO-6's requirement that marked-up ratings match the visible page (R-15) |
| **Pattern** | An average shown without its count is uninterpretable — one five-star review and forty four-star reviews are not comparable. Structured data that disagrees with the visible page is a search-engine policy violation |
| **Why it matters to Bulbula** | `search-design.md` §9.1 already names "rating average and count **together**" as one ranking input. The display rule is the same rule |
| **Applies to** | C-13, SEO-6, D-34 |
| **Affects** | UX, SEO, content |

**Interpretation.** Bulbula never renders an average alone, and renders
**nothing** where there are no published Reviews — not "0.0", not an empty
star row (TR-63).

### UR-17 — "Open now" is the highest-value field and the easiest to get wrong

| | |
| --- | --- |
| **Source** | NN/g mobile content-priority findings (UR-10); Bulbula's own problem statement (PRD §5.1) |
| **Pattern** | Status-at-a-glance is the single most requested local attribute. The common failure is presenting *unknown* hours as *closed*, which is an active falsehood about a real business |
| **Why it matters to Bulbula** | TR-56 and `search-design.md` ON-2 already forbid it. This finding makes it a **visual** requirement too: three states must be visually distinct — Open, Closed, Hours not confirmed — and the third must not borrow the styling of the second |
| **Applies to** | C-09, TR-56, NFR-AC3 |
| **Affects** | UX, accessibility, content |

### UR-18 — Save is a private utility, not a social signal

| | |
| --- | --- |
| **Source** | Comparative reading of bookmark and favourite patterns; Bulbula glossary §3 |
| **Pattern** | Products conflate three different things — a private bookmark, a public endorsement and a follow — behind one icon. Each implies different privacy, different notification behaviour and different social expectations |
| **Why it matters to Bulbula** | The glossary deprecates *favourite*, *like*, *bookmark*, *wishlist* and *follow* and fixes **Save** as the one capability. There is no public Saved list, no count, no notification and no social graph |
| **Applies to** | C-14, C-34, D-01 |
| **Affects** | UX, content |

**Interpretation.** The Save control must not look like a social affordance:
**no count beside it, no "N people saved this", no heart borrowed from a
like pattern.** Showing a save count would turn a private action into a
public signal and would create an incentive to game it.

---

## 6. What the research deliberately did **not** produce

Patterns observed in comparable products that Bulbula **does not adopt**,
recorded so that nobody re-proposes them as "standard practice":

| Observed elsewhere | Why Bulbula declines |
| --- | --- |
| Recent-search history in the autocomplete panel | Stores a Guest behavioural record (TR-202, PRIV) |
| Personalised or "for you" result ordering | No personalisation in V1 (`search-design.md` OR-6) |
| Owner-response threads beneath Reviews | No business accounts (D-54), no replies (D-12) |
| "Claim this business" call to action | No claims in V1 (D-02) |
| Photo upload attached to a Review | Deferred (D-36) |
| "Was this helpful?" voting on Reviews | Deferred (D-37) |
| Map-first results view with pin clustering | Maps are an enhancement, not the layout (MOB-5, C-10); strategy is **Open (D-21)** |
| Infinite scroll on result lists | Breaks back-navigation, pagination SEO and low-bandwidth control (SEO-10) |
| Interstitial app-install or sign-in prompts | GS-4 forbids interruption outright |
| Sponsored content filling empty inventory | PL-5 requires the slot to collapse |
| Review photo galleries, check-ins, follower counts | Not in V1 (PRD §13) |

---

## 7. Open questions this research could not close

| Question | Status |
| --- | --- |
| Final brand colour values | **Open — UX decision** (D-53); research informs method, not values |
| Final typeface | **Open — UX decision**; UR-07 and UR-08 constrain the strategy |
| Dark mode as a V1 feature | **Open (D-19)**; UR-13 makes it cheap to add later |
| Mini App navigation model | **Open (D-38)** |
| Maps embed strategy and fallback | **Open (D-21)** |
| Whether Guests may submit structured suggestions | **Open (D-35)** |
| Review scale, edit window, moderation timing | **Closed 2026-10-07 (D-34)** — 1 to 5, 30 days, pre-publication |
| Hours model (split shifts, exceptions, 24 h) | **Open (D-04)** |
| Whether NFR-AC1 (WCAG 2.2 AA) becomes contractual | **`[P]` — owner approval** |
| Ethiopian advertising-disclosure rules | **PENDING COUNSEL (L-16)** |

**No usability testing with real users has been conducted.** Every finding
above is secondary research. The pilot (D-31) is the first opportunity to
observe real Ethiopian users against a real Listing set, and several
findings — particularly UR-05, UR-08 and UR-17 — should be re-examined
against it. Nothing in this document should be read as validated with
Bulbula's actual users.

---

## Decision references

D-01, D-02, D-04, D-10, D-12, D-16, D-18, D-19, D-21, D-31, D-34, D-35,
D-36, D-37, D-38, D-48, D-49, D-52, D-53, D-54.
