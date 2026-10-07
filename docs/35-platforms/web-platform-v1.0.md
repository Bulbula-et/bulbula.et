# Web Platform

| | |
| --- | --- |
| **Document** | Web Platform — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The V1 Web surface: rendering model, browser navigation, URL
behaviour, browser capabilities and the supported baseline.

**Scope.** Platform behaviour. Screens are in
[`../20-ux-ui/public-web-ux-v1.0.md`](../20-ux-ui/public-web-ux-v1.0.md);
SEO in [`platform-seo-v1.0.md`](platform-seo-v1.0.md); performance in
[`platform-performance-v1.0.md`](platform-performance-v1.0.md).

**No technology is selected.** D-16 and D-17 remain open.

---

## 1. Rendering model

| ID | Rule | Source |
| --- | --- | --- |
| WP-1.1 | **Server-rendered HTML is the primary path.** The browser receives content, not instructions to fetch content | PD-03, MOB-5, SEO-1 |
| WP-1.1a | The Web surface is **one application with the Mini App**, not a separate build target | PD-01, D-49 |
| WP-1.1b | The Web is **designed mobile-first**; desktop is the widened case, never the reference case | D-52, MOB-1 |
| WP-1.2 | **The Web surface never calls its own HTTP API.** Web controllers invoke application services in process | **TD-01** |
| WP-1.3 | **No SPA requirement.** No client-side router, no hydration step, no build pipeline the shared host cannot run | D-16, DSN-12.7 |
| WP-1.4 | **JavaScript is optional for core discovery**: search, browse, profiles and pagination all work without it | MOB-5, C-37 |
| WP-1.5 | JavaScript enhances; it never gates | CMP-0.9 |
| WP-1.6 | A failed, blocked or slow script leaves a **working page** | PD-03 |
| WP-1.7 | The JavaScript approach and the view layer are **Open (D-16, D-17)**; nothing here depends on either | D-16, D-17 |

### 1.1 Progressive enhancement inventory

Every row must be demonstrated working in its baseline column before the
enhancement is accepted.

| Capability | Baseline — no JavaScript | Enhancement |
| --- | --- | --- |
| **Search** (C-02) | Form submit → results page | — |
| **Autocomplete** (C-03) | *Not present*; typed search works | Suggestions, keyboard navigable (UR-03) |
| **Filtering** (C-02) | Form with explicit apply, or links | Bottom sheet, live result count |
| **Sort** | Form or links | In-place update |
| **Pagination** | Real links with real URLs | — |
| **Save** (C-14) | Form post → redirect to the same place, scroll preserved | In-place toggle with announcement |
| **Review** (C-13) | Its own page, normal form post | Inline validation |
| **Report** (C-15) | Its own page, normal form post | Bottom sheet or modal |
| **Maps** (C-10) | Address, landmark, external directions link | Deferred static preview, then an embed on request |
| **Sharing** (C-17) | Copy-link control with a selectable URL | Web Share API |
| **Account** (C-33…C-36) | Normal pages and forms | Inline feedback |
| **Hours** (C-09) | Full weekly schedule rendered | Expand and collapse |
| **Gallery** (C-22) | Images in sequence, each reachable | Swipe plus full-screen viewer |

| ID | Rule |
| --- | --- |
| WP-1.8 | An enhancement may change how an action feels, **never what it does** |
| WP-1.9 | Autocomplete failure must never break typed search (`search-design.md` PF-4) |
| WP-1.10 | No enhancement may introduce a state that Back cannot undo (IAR-28) |

---

## 2. Browser navigation

| Interaction | Required behaviour | Source |
| --- | --- | --- |
| **Normal link** | Full navigation to a real URL | WP-1.1 |
| **Back** | Restores the previous page with its state — same results, filters, scroll position | IAR-28 |
| **Forward** | Restores what Back undid | IAR-32 |
| **Refresh** | Reproduces the current page exactly from its URL; no re-post prompt for a navigational action | §3 |
| **Deep link** | Any URL in §3 is directly loadable, with no prior navigation required | IAR §5.1 |
| **Query parameters** | Filters, sort and page are query state and survive Back, refresh and sharing | PD-10 |
| **Forms** | Real forms; a failed submit preserves every other value | WCAG 3.3.7 |
| **Pagination** | Real links; Back returns to the prior page of results | CMP §27 |
| **Filter change** | A real history entry, so Back undoes one filter at a time | IAR-29 |
| **Modal / sheet** | Pushes a history entry; Back closes it rather than leaving the page | IAR-31 |
| **Sign-in** | **Replaces** rather than stacks; Back from a post-sign-in task does not return to the sign-in screen | IAR-33 |

| ID | Rule |
| --- | --- |
| WP-2.1 | **Browser navigation must remain functional without JavaScript**: every navigational control is a real link or form (IAR-34) |
| WP-2.2 | **No infinite scroll** (IAR-30) |
| WP-2.3 | Scroll position is restored on Back for list pages (IAR-28) |
| WP-2.4 | A state-changing POST redirects to a GET, so refresh never re-submits |
| WP-2.5 | Focus is placed deliberately after navigation — results heading, first error, or new content (A11-3.6) |

---

## 3. URL behaviour

The grammar is fixed by
[`../20-ux-ui/information-architecture-v1.0.md`](../20-ux-ui/information-architecture-v1.0.md)
§2 and is **not re-decided here**.

### 3.1 Canonical public URLs

| Destination | Pattern | Indexable |
| --- | --- | --- |
| Home | `/` | Yes |
| Categories index | `/categories` | Yes |
| Category | `/c/{category}` | Yes |
| Subcategory | `/c/{category}/{subcategory}` | Yes |
| Areas index | `/areas` | Yes |
| Area | `/a/{area}` | Yes |
| **Category × Area** | `/c/{category}/in/{area}` | Yes, unless below the minimum-content rule |
| Business profile | `/b/{business}` | Yes |
| Static and policy | `/about`, `/privacy`, `/terms`, … | Yes |
| Search | `/search?q=…` | **No** |
| Nearby | `/nearby` | **No** |
| Sign in | `/signin` | **No** |
| Saved | `/saved` | **No** |
| Account | `/account`, `/account/reviews`, `/account/privacy` | **No** |
| Report / review forms | `/b/{business}/report`, `/b/{business}/review` | **No** |
| Operations console | `/ops/*` | **No** |

### 3.2 Slugs

| ID | Rule | Source |
| --- | --- | --- |
| WP-3.1 | Lowercase, hyphenated, no file extension, no trailing slash, **no internal numeric id** | IAR-4, TR-25 |
| WP-3.2 | A Business profile URL **survives a name change**; a changed slug **redirects permanently** | IAR-3, SEO-4 |
| WP-3.3 | A merged or retired Category or Area slug redirects permanently to its target | SEO-4, C-23 |
| WP-3.4 | A removed Listing returns **not found** and leaves the sitemap | SEO-13, IAR-8 |
| WP-3.5 | The grammar must not preclude Amharic pages later | SEO-14, IAR-6 |

### 3.3 Query-string rules

| Parameter | Used on | Effect |
| --- | --- | --- |
| `q` | `/search` | The query; retained in the field on the results page |
| `category`, `area`, `open_now`, `min_rating`, `verified` | `/search` | Named filters only — no generic query-object syntax |
| `sort` | lists | Allow-listed values; unknown values rejected |
| `page` | lists | Offset pagination; canonicalises to the base page |
| `branch` | `/b/{business}` | Selects a Branch; **not** a separate canonical |
| `continue` | `/signin` | Authentication continuation target — see §3.4 |

| ID | Rule | Source |
| --- | --- | --- |
| WP-3.6 | Filter, sort and pagination parameters **never create a second indexable URL** | SEO-10, IAR-5 |
| WP-3.7 | Unknown parameters are **ignored, not echoed** | WP-6.9 |
| WP-3.8 | **No sensitive value ever appears in a URL** — no token, session, OTP or personal identifier | PA-18.9, TR-201 |
| WP-3.9 | **No tracking or referral parameter is generated by Bulbula**, and none is included in a shared link | IAR-36 |
| WP-3.10 | Parameter order does not change meaning; the canonical form is emitted consistently | SEO-2 |

### 3.4 Authentication continuation URLs

| ID | Rule | Source |
| --- | --- | --- |
| WP-3.11 | The continuation target is carried so authentication **returns the User to the task they attempted** | UFL-0.1, PRD ACC-3 |
| WP-3.12 | The target is validated as an **internal, relative destination**. An absolute or off-site target is rejected — this is an open-redirect boundary | PA-18.4 |
| WP-3.13 | The continuation carries **no form payload in the URL**; in-progress input is preserved server-side against the session | WCAG 3.3.7, PA-18.9 |
| WP-3.14 | Sign-in **replaces** in history, so Back does not return to it | IAR-33 |
| WP-3.15 | A continuation to a non-existent or now-forbidden target falls back to Home, not to an error | UFL-0.4 |

| ID | Rule |
| --- | --- |
| WP-3.16 | **No opaque JavaScript-only navigation.** Every destination has a URL (PD-10) |

---

## 4. Browser capabilities

| Capability | Use | Fallback | Source |
| --- | --- | --- | --- |
| **Geolocation** | Nearby only (C-07) | Area browsing; **no nag, no repeat prompt, no blocked screen** | C-07, UR-15 |
| **Web Share API** | Share control (C-17) | Copy-link control | PA-7.4 |
| **Clipboard** | Copy link | A selectable, pre-focused text field | — |
| **`tel:`** | Call (C-11) | The number rendered as readable text | C-11 |
| **`mailto:`** | Contact | The address rendered as text | C-18 |
| **External maps** | Directions (C-10) | Address, Area and landmark as text | MOB-5 |
| **Social links** | Profile links (C-11) | Omitted where absent — never a disabled placeholder | UFL-A5.1 |
| **`prefers-reduced-motion`** | Suppress motion | Motion is minimal by default | A11-13.1 |
| **`prefers-color-scheme`** | **Not used in V1** | — | D-19 |

| ID | Rule |
| --- | --- |
| WP-4.1 | **Every enhanced capability has a graceful fallback.** A capability that cannot degrade is not used (PA-9.1) |
| WP-4.2 | Capability is **feature-detected**, never inferred from a user-agent string (PA-9.2) |
| WP-4.3 | Geolocation is requested only after the screen has explained what it is for (UR-15) |
| WP-4.4 | Coordinates are used in-request and **never stored, logged or sent to analytics** (TR-201) |
| WP-4.5 | An external link states that it leaves Bulbula; nothing opens a new window without saying so (CMP §36) |
| WP-4.6 | No capability request is made on page load. All are consequences of a user action |

---

## 5. Sessions and forms

| ID | Rule | Source |
| --- | --- | --- |
| WP-5.1 | The Web transports its session as a **cookie**; it resolves to the same server-side record as the Mini App's bearer token | TD-02, S-1 |
| WP-5.2 | **Guest browsing requires no session and sets no identifying cookie** | GS-1, TR-202 |
| WP-5.3 | State-changing requests carry **CSRF protection** | `trd-v1.0.md` §19 |
| WP-5.4 | A failed submit preserves every other value and moves focus to the first error | WCAG 3.3.3, 3.3.7 |
| WP-5.5 | Session expiry **preserves in-progress input** and restores it after re-authentication | WCAG 2.2.5 |
| WP-5.6 | Session lifetime, idle timeout and rotation are **Open — technical decision (OT-01)** | OT-01 |
| WP-5.7 | Whether a cookie notice is required is **PENDING COUNSEL** | L-5, L-6 |

---

## 6. Supported baseline

Capability-based, not a version matrix (PD-13).

| Tier | Targets | Expectation |
| --- | --- | --- |
| **Baseline** | Anything that renders HTML and CSS | Every capability in §1.1's baseline column works |
| **Enhanced** | Current Chromium, current Firefox, current Safari, common Android browsers, Android WebView | Enhancements apply where detected |
| **Priority** | **Mid- and low-range Android over constrained networks** | The performance envelope is measured here, not on a desktop | 

| ID | Rule | Source |
| --- | --- | --- |
| WP-6.1 | **Mid- and low-range Android devices on constrained networks are the priority target**, not an afterthought | MOB-3, R-06 |
| WP-6.2 | Support is expressed as capability, so no User is excluded by a version check | PD-13 |
| WP-6.3 | **No unsupported-browser interstitial exists.** A browser lacking an enhancement gets the baseline, silently | WP-1.6 |
| WP-6.4 | The exact numeric version policy is **Open — implementation detail** | PD-13 |
| WP-6.5 | No horizontal scrolling at a 320 px equivalent width; usable at 200 % zoom; both orientations | WCAG 1.4.10, 1.4.4, 1.3.4 |
| WP-6.6 | The Android WebView is a first-class target because it is also the Mini App runtime | PA-1.1 |

---

## 7. Operations console on this surface

| ID | Rule | Source |
| --- | --- | --- |
| WP-7.1 | The console is **Web-only**, at `/ops/*` | `scope-v1.md` §1.5 |
| WP-7.2 | It is **never linked from a public page**, never mentioned in public navigation, never indexed | OPX-0.5, SEO-8 |
| WP-7.3 | Staff authentication is **separate** from Customer authentication; its strength is **Open (D-45)** | ST-2, D-45 |
| WP-7.3a | Staff nevertheless use the **same two approved methods** — Google and email OTP. No separate password system exists | D-48 |
| WP-7.4 | It meets the same WCAG 2.2 AA bar and hides no data at smaller widths | OPX-0.9, DSN-8.4 |
| WP-7.5 | Console responses are `no-store` | TR-120 |
| WP-7.6 | Console load must not degrade public performance | OPX-16.5 |
| WP-7.7 | Its behaviour is specified in [`../20-ux-ui/operations-console-ux-v1.0.md`](../20-ux-ui/operations-console-ux-v1.0.md) | — |

---

## 8. Verification

| ID | Check |
| --- | --- |
| WP-8.1 | Every baseline cell in §1.1 works with JavaScript disabled |
| WP-8.2 | Search, browse and profile pages render complete content in the initial HTML response |
| WP-8.3 | Back restores results, filters and scroll position after navigation, filtering and pagination |
| WP-8.4 | Refresh reproduces any page from its URL alone; no re-post prompt after a navigational action |
| WP-8.5 | A continuation target that is absolute or off-site is rejected |
| WP-8.6 | No URL contains a token, session, OTP or personal identifier |
| WP-8.7 | Every capability in §4 is exercised with the capability unavailable |
| WP-8.8 | Measured on a mid-range Android device over a constrained network |
| WP-8.9 | 320 px width and 200 % zoom produce no horizontal scrolling and no loss of function |
| WP-8.10 | No Web page calls the public HTTP API |
| WP-8.11 | `/ops/*` is absent from the sitemap and discloses nothing to an unauthenticated request |

---

## 9. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-16 | Frontend JavaScript approach | **Open — implementation detail** |
| D-17 | View layer | **Open — implementation detail** |
| D-19 | Dark mode | Owner decision — `prefers-color-scheme` unused in V1 |
| D-20 | Host resource limits | **Open — technical decision** |
| D-21 | Maps provider and embed | Owner decision |
| D-25 | Media limits | **Open — implementation detail** |
| D-45 | Staff authentication strength | **Open — technical decision** |
| OT-01 | Session lifetime and rotation | **Open — technical decision** |
| OT-08 | Pagination style | **Open — technical decision** |
| — | Exact browser version policy | **Open — implementation detail** |
| — | Minimum-content threshold for SEO-9 | **Open — platform decision** pending the product threshold |
| L-5 / L-6 | Cookie notice | **PENDING COUNSEL** |

---

## Decision references

D-16, D-17, D-19, D-20, D-21, D-25, D-45, D-48, D-49, D-52.
