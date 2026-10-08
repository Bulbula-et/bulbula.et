# UX Principles

| | |
| --- | --- |
| **Document** | Bulbula UX Principles — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The reasoning every other UX document inherits. When two
designs both satisfy the requirements, these principles decide between them.

**Authority.** Subordinate to
[`../60-decisions/decision-register.md`](../60-decisions/decision-register.md),
[`../10-product/prd-v1.0.md`](../10-product/prd-v1.0.md) and
[`../30-technical/trd-v1.0.md`](../30-technical/trd-v1.0.md). A principle
that appears to override an approved decision is wrong.

**Terminology** is fixed by [`../10-product/glossary.md`](../10-product/glossary.md).

---

## 1. The product in one sentence

> **Bulbula answers "where is it, is it open, and how do I reach it" for
> businesses in Bole Bulbula, for someone standing outside on a phone.**

Everything below follows from taking that sentence literally.

---

## 2. The nine principles

| ID | Principle | One-line test |
| --- | --- | --- |
| **UXP-1** | **Discovery first** | Does this help someone find a business faster? |
| **UXP-2** | **Mobile-first, genuinely** | Was this designed at 360 px before it was designed at 1280 px? |
| **UXP-3** | **Zero-friction browsing** | Can a Guest do this with no account and no interruption? |
| **UXP-4** | **Trust is visible** | Can the User see how current this information is, and who stands behind it? |
| **UXP-5** | **Commercial integrity** | Could a reasonable User mistake this paid item for an organic one? |
| **UXP-6** | **Calm interface** | Is anything here competing with the answer the User came for? |
| **UXP-7** | **Honest absence** | Does this show "unknown" as a fact, rather than inventing or implying? |
| **UXP-8** | **Weight is a cost the User pays** | Is every kilobyte earning its place on a constrained connection? |
| **UXP-9** | **One product, two surfaces** | Does this work identically on Web and in the Mini App, with only chrome differing? |

---

## 3. UXP-1 — Discovery first

### 3.1 The journey the interface exists to serve

```text
Discover ──▶ Search ──▶ Evaluate ──▶ Contact / Visit ──▶ Save / Review ──▶ Return
   │           │            │               │                  │             │
 C-01        C-02         C-08            C-11               C-14          C-34
 C-04        C-03         C-09            C-10               C-13          C-01
 C-05        C-07         C-12            C-17               C-15
 C-06                     C-13
```

| ID | Rule | Source |
| --- | --- | --- |
| UXP-1.1 | **The whole journey works without an account.** Not a reduced version of it — the whole thing | GS-1, D-48 |
| UXP-1.2 | A search entry point is visible **without scrolling** on a mobile viewport, on the homepage | C-01 acceptance criteria |
| UXP-1.3 | Discovery has **two axes — Category and Area** — and both are reachable from every public page | C-04, C-05, D-06 |
| UXP-1.4 | Every screen in the journey offers a next step. A dead end is a defect, including the zero-result screen | C-02, UR-05 |
| UXP-1.5 | Evaluation happens **on one page**. A User must not navigate away to learn whether a Business suits them | C-08 |
| UXP-1.6 | The contact action is the point of the product. It is never below the fold on a profile on mobile | C-11, UR-10 |
| UXP-1.7 | Return visits are served by Saved (C-34) and by the homepage, not by notifications or re-engagement prompts | C-39, PRD §13 |

### 3.2 What "discovery first" rules out

No feed, no personalised recommendations, no editorial collections, no
"trending", no gamification, no onboarding carousel, no tour. The homepage
is a route into the directory, not a destination to dwell on
(C-01 *Out of scope*).

---

## 4. UXP-2 — Mobile-first, genuinely

**D-52 is explicit: "Desktop-first design that is merely shrunk is not
acceptable."**

| ID | Rule | Source |
| --- | --- | --- |
| UXP-2.1 | Every layout is **designed at the small viewport first**; larger viewports adapt the layout, they do not define it | D-52, MOB-6 |
| UXP-2.2 | **Thumb reach.** Primary actions sit in the lower and middle thirds of a phone screen; destructive actions never sit under the resting thumb | MOB-2 |
| UXP-2.3 | **Touch targets** meet a 44 px target for primary controls, with 24 × 24 px as the absolute floor and adequate spacing where a control is visually smaller | UR-01, WCAG 2.5.8 |
| UXP-2.4 | **Readable type** without zooming, and still functional when the User enlarges text | NFR-AC5, UR-01 |
| UXP-2.5 | **Compact hierarchy.** A phone screen carries one primary idea; secondary information is below it, not beside it | D-52 |
| UXP-2.6 | **Low-end device budget.** No layout that requires continuous recalculation, large images or heavy script to be usable | MOB-3, R-06 |
| UXP-2.7 | **Fast first interaction.** The first useful action is available on first paint, because the server sent it | D-16, RN-1 |
| UXP-2.8 | **Minimal animation.** Motion clarifies a state change; it is never required to understand information | UR-01, WCAG 2.3.3 |
| UXP-2.9 | **Progressive enhancement.** Core content and navigation work with no JavaScript | MOB-5, SEO-1, D-16 |
| UXP-2.10 | **Desktop is supported properly, not grudgingly.** Larger viewports get real multi-column layouts and comfortable measure — they simply are not the baseline | MOB-6, D-52 |

### 4.1 The uncomfortable consequence

Designing mobile-first means some desktop affordances are **not available**:
no hover-dependent interaction (UR-02, WCAG 1.4.13), no dense data table on
a public page, no multi-level dropdown menu. If a pattern cannot be made to
work by touch, it is not used on either surface.

---

## 5. UXP-3 — Zero-friction browsing

| ID | Rule | Source |
| --- | --- | --- |
| UXP-3.1 | Homepage, search, autocomplete, Category browse, Area browse, Category × Area, nearby, profiles, hours, maps, contact actions, trust indicators, **reading Reviews**, reporting a Listing, sharing and static pages are **all Guest-safe** | `interaction-permissions.md` §3 |
| UXP-3.2 | **No content is truncated, blurred, counted-down or teased** behind authentication | GS-2 |
| UXP-3.3 | A sign-in prompt appears **only** as the direct consequence of a Guest attempting an authenticated-required action | GS-3 |
| UXP-3.4 | **No interstitial, modal wall, nag or countdown** may interrupt a Guest journey | GS-4 |
| UXP-3.5 | A Guest may use Bulbula **indefinitely** without ever creating an account, and the interface never implies otherwise | GS-5 |
| UXP-3.6 | After authenticating, the User **returns to the task they were attempting**, with their input preserved | PRD ACC-3, WCAG 3.3.7 |
| UXP-3.7 | Device location is requested **only** when the User asks for nearby, with the reason stated, and refusal degrades gracefully to Area browsing | C-07, LOC-2, UR-15 |
| UXP-3.8 | Permission requests of any kind are **in context and consequential** — never on arrival | UR-15 |

### 5.1 The only four gates in the public product

| Action | Why it needs an account |
| --- | --- |
| Write, edit or delete a Review | Attributable to a person; anti-abuse; moderation accountability (D-12) |
| Save / Saved list | Stored against a person, synchronised across surfaces (C-32) |
| Profile, deletion, privacy controls | Operate on that person's own data |
| Report a **Review** | Attribution and rate limiting against targeted abuse |

**Reporting a *Listing* is not gated** — the subject is a Business, not a
person (TS-2). This asymmetry is deliberate and must survive design review.

---

## 6. UXP-4 — Trust is visible

Bulbula's differentiator is that a human verified the information
(D-50, C-21). If the interface does not show that, the work is invisible and
the product is just another list.

| ID | Rule | Source |
| --- | --- | --- |
| UXP-4.1 | A verified Listing shows **a verified indicator and the date of last Verification** | C-08 acceptance criteria, C-12 |
| UXP-4.2 | Verification state is conveyed by **icon + text**, never by colour alone | NFR-AC3, UR-02 |
| UXP-4.3 | **Open status has three distinct states** — Open · Closed · Hours not confirmed — and the third never borrows the styling of the second | C-09, TR-56, UR-17 |
| UXP-4.4 | Rating average is **never shown without its count**, and nothing is shown where no published Reviews exist | UR-16, TR-63 |
| UXP-4.5 | A public page explains in plain language how Listings are collected and verified, and how ranking works | C-18 |
| UXP-4.6 | **Provenance, Permission records, verification method and staff identity are never public** | TRD TR-25, DO-4 |
| UXP-4.7 | Every Business profile carries a visible route to report a problem | TS-1, C-15 |
| UXP-4.8 | Completeness is **not** displayed as a score to Users. It is an internal quality signal; a public "40 % complete" badge shames a business for Bulbula's own collection gap | D-09, UXP-7 |

**UXP-4.6 and UXP-4.1 together:** Users see *that* and *when* something was
verified. They do not see *who* verified it or *how*. The first builds
trust; the second is an operational and safety disclosure.

---

## 7. UXP-5 — Commercial integrity

**The hardest line in the product.** Bulbula sells placement but not
ranking (D-10). The interface is what makes that claim true or false.

| ID | Rule | Source |
| --- | --- | --- |
| UXP-5.1 | Every Sponsored placement carries a **visible text label**, legible without interaction — no hover, no tap, no tooltip | LB-1, LB-2, UR-02 |
| UXP-5.2 | Label wording is **identical on every surface and both client surfaces**. `[P]` "Sponsored" | LB-3, LB-5 |
| UXP-5.3 | The label **must not rely on colour, shading or position alone** | LB-4, NFR-AC3 |
| UXP-5.4 | Sponsored items are **visually distinguishable beyond the label** — a container, border or separator | LB-6, UR-02 |
| UXP-5.5 | Sponsored items are **never interleaved** into an organic list so that the boundary is ambiguous | LB-7, ADV-8 |
| UXP-5.6 | **Unsold inventory collapses.** No placeholder, no house advertisement, no empty frame | PL-5, C-01 |
| UXP-5.7 | A Sponsored placement **must never resemble** a verification badge, a trust indicator or editorial endorsement | ADV-9 |
| UXP-5.8 | Sponsorship **never changes** a Business's organic position, rating, verification state or Review presentation | D-10, TRD TR-41 |
| UXP-5.9 | A public page explains what sponsorship is and that it does not affect organic ranking | LB-8, C-18 |

### 7.1 The design test

> **Remove every Sponsored placement from a page. If the organic content
> shifts, reorders, or changes in any way, the separation has been broken.**

This mirrors `search-design.md` SP-8 at the visual layer.

---

## 8. UXP-6 — Calm interface

| ID | Rule |
| --- | --- |
| UXP-6.1 | **One primary action per screen.** Everything else is visibly secondary |
| UXP-6.2 | **Hierarchy before decoration.** Size, weight and spacing carry structure; borders and shadows are a last resort |
| UXP-6.3 | **Whitespace is functional** on a small screen — it is what makes a list scannable with one eye while walking |
| UXP-6.4 | **Scanability.** Lists are scanned, not read. The first line of a card answers the question; the rest supports it |
| UXP-6.5 | **Predictable navigation.** The same control does the same thing in the same place on every page |
| UXP-6.6 | **No badge inflation.** A badge that appears on most items carries no information |
| UXP-6.7 | **No notification dots, no unread counts, no re-engagement surfaces** in V1 |
| UXP-6.8 | **No carousel** as a primary discovery mechanism: it hides content, fights touch scroll and is poor for accessibility and SEO |
| UXP-6.9 | **Density is earned.** The operations console may be dense because Staff use it daily; a public page may not |

**Calm is a commercial position, not an aesthetic preference.** A directory
that feels like an advertising network cannot credibly claim its rankings
are unpaid (UXP-5).

---

## 9. UXP-7 — Honest absence

| ID | Rule | Source |
| --- | --- | --- |
| UXP-7.1 | Missing optional data is **omitted silently** — not rendered as "Unknown", "N/A" or an empty row | C-08 |
| UXP-7.2 | A contact action appears **only** where a contact point exists. No disabled placeholders | C-11, TRD P-4 |
| UXP-7.3 | Unknown hours render as **"Hours not confirmed"**, never as "Closed" | TR-56, UR-17 |
| UXP-7.4 | A Business with no published Reviews shows **no rating element at all** — not "0.0", not an empty star row | TR-63 |
| UXP-7.5 | Empty Categories and Areas are **not offered as navigation** | C-04, C-05 |
| UXP-7.6 | An honest empty state is required where nothing exists — **fabricated content is prohibited** | C-01 |
| UXP-7.7 | **No "coming soon", no disabled control, no placeholder UI** for a future capability | `interaction-permissions.md` §2 |
| UXP-7.8 | A permanently closed Business shows its status plainly; the URL and history survive | TRD TR-53 |

---

## 10. UXP-8 — Weight is a cost the User pays

| ID | Rule | Source |
| --- | --- | --- |
| UXP-8.1 | Server-rendered HTML is the delivery mechanism; JavaScript is enhancement | D-16, RN-1, RN-2 |
| UXP-8.2 | **No design may require a large JavaScript bundle.** If a pattern cannot be built with a small script, a different pattern is chosen | PRD NFR-P2 `[P]` |
| UXP-8.3 | **No webfont in the critical path.** System stack first; a scoped Ethiopic fallback is the single justified exception | UR-07, UR-08, NFR-P4 |
| UXP-8.4 | Images are lazy-loaded below the fold, sized for the device and always have declared dimensions | MOB-4, NFR-P5 |
| UXP-8.5 | **The map does not load until it is needed**, and the page is complete and useful without it | C-10, MOB-5, NFR-P4 |
| UXP-8.6 | **No autoplay media, no background video, no full-bleed hero photography** | UXP-6, MOB-4 |
| UXP-8.7 | **No infinite scroll and no background polling** | SEO-10, UR-06 |
| UXP-8.8 | Loading states appear only where a wait genuinely occurs — past roughly a second — and skeletons only for genuinely asynchronous regions | UR-09, UR-11 |
| UXP-8.9 | **No third-party script is required** for any core capability | NFR-A1, TRD TR-205 |

---

## 11. UXP-9 — One product, two surfaces

| ID | Rule | Source |
| --- | --- | --- |
| UXP-9.1 | Web and Telegram Mini App share design tokens, components, cards, search UI, profile presentation, layouts, interaction patterns and terminology | D-49 |
| UXP-9.2 | Surface differences are confined to **navigation chrome, share mechanism, authentication entry, map hand-off and viewport conventions** | SUR-5, UR-12 |
| UXP-9.3 | A surface-specific behaviour **MUST NOT** become a product difference | SUR-6 |
| UXP-9.4 | Inside Telegram the **host's Back button is the back button**. Bulbula never renders a second one | UR-12 |
| UXP-9.5 | Inside Telegram, layout is sized from the **host's stable viewport height** and respects safe-area insets | UR-12 |
| UXP-9.6 | Tokens are **semantic**, so the Telegram theme can supply values without a second palette | UR-13 |
| UXP-9.7 | Shared links from the Mini App **must resolve on the Web** for recipients who are not Telegram users | TG-6, C-17 |
| UXP-9.8 | Neither surface is a port of the other | SUR-7 |

**Open (D-38):** the Mini App navigation model — full page loads versus
fragment swaps. Every component in this phase is specified so that either
answer works.

---

## 12. Design conflicts and how they resolve

When two principles collide, this order decides:

```text
1. An approved decision (D-xx)          ← never overridden by a principle
2. UXP-3  Zero-friction browsing        ← the Guest journey is never sacrificed
3. UXP-5  Commercial integrity          ← never traded for revenue or density
4. UXP-4  Trust is visible
5. UXP-7  Honest absence
6. UXP-1  Discovery first
7. UXP-2  Mobile-first
8. UXP-8  Weight
9. UXP-6  Calm
```

| Conflict | Resolution |
| --- | --- |
| A Sponsored slot would improve revenue on the zero-result page | **Refused.** UXP-5 and ZR-5 outrank it |
| Showing more fields would make the profile more complete-looking | **Refused.** UXP-7.1 — omit what is unknown |
| An account prompt would raise sign-ups | **Refused.** UXP-3.3 and GS-4 |
| A richer map would improve the profile | **Deferred to load.** UXP-8.5 — the page works without it |
| A webfont would strengthen the brand | **Refused for Latin.** UXP-8.3; brand expression comes from colour, spacing and layout |
| Density would fit more results above the fold | **Refused on public pages**, permitted in the operations console (UXP-6.9) |
| A badge would highlight good Listings | **Refused.** UXP-6.6 and UXP-4.8 |

---

## 13. What these principles deliberately leave open

| Item | Status |
| --- | --- |
| Final brand colour values | **Open — UX decision**; owner approval required (D-53) |
| Final typeface | **Open — UX decision** |
| Final logo | **Open (D-53, D-28)** |
| Dark mode as a V1 feature | **Open (D-19)** |
| Frontend JS approach (htmx + Alpine vs vanilla) | **Open (D-16)** — both satisfy UXP-8 |
| View layer | **Open (D-17)** |
| Mini App navigation model | **Open (D-38)** |
| Maps embed strategy and fallback | **Open (D-21)** |
| Review scale, edit window, moderation timing | **Closed 2026-10-07 (D-34)** — 1 to 5, 30 days, pre-publication |
| Hours model | **Open (D-04)** |
| Structured guest suggestions | **Open (D-35)** |
| Operator / Administrator split | **Open (D-14)** |
| WCAG 2.2 AA as a contractual target | **`[P]` — owner approval (NFR-AC1)** |

---

## Decision references

D-04, D-06, D-09, D-10, D-12, D-14, D-16, D-17, D-19, D-21, D-28, D-34,
D-35, D-38, D-48, D-49, D-50, D-52, D-53.
