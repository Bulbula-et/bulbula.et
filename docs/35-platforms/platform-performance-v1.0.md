# Platform Performance

| | |
| --- | --- |
| **Document** | Platform Performance — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** How each surface meets the performance expectations already
set in the PRD, TRD and UX/UI specifications — loading strategy,
budgets, deferral, caching behaviour and degradation under poor networks.

**Scope note.** Server-side caching strategy, cache keys, invalidation
and backend performance are settled in
`docs/30-technical/performance-and-caching.md`. This document covers
**client and surface behaviour** and restates server rules only where a
surface must observe them.

**All numbers in this document are `[P]` — provisional, owner-ratifiable.
None is a contractual guarantee.**

---

## 1. Why this matters here

| ID | Statement | Source |
| --- | --- | --- |
| PP-1.1 | The primary audience is **mobile-first, on constrained and variable networks** | R-3, MOB-1 |
| PP-1.2 | Data cost is a real constraint; **bytes are a user cost, not just a latency figure** | R-3 |
| PP-1.3 | A directory is used in short, urgent bursts — "is this place open, what is the number". **Slowness is failure** | UR-01 |
| PP-1.4 | The Mini App adds host startup on top of Bulbula's own cost, so its budget is **tighter in practice, not looser** | TM-2.1 |
| PP-1.5 | **No offline support exists** (PD-12). Degradation must therefore be graceful by design | PD-12 |

---

## 2. Targets `[P]`

| Metric | Target (p75) | Applies to |
| --- | --- | --- |
| Largest Contentful Paint | **≤ 2.5 s** | Both surfaces |
| Interaction to Next Paint | **≤ 200 ms** | Both surfaces |
| Cumulative Layout Shift | **≤ 0.1** | Both surfaces |
| Critical HTML + CSS | **≤ 150 KB** | Both surfaces |
| JavaScript | **≤ 100 KB** | Both surfaces |

| ID | Rule | Source |
| --- | --- | --- |
| PP-2.1 | These are **the same on both surfaces**. No surface is allowed a weaker budget | SUR-3 |
| PP-2.2 | They are measured on a **mid-range Android device on a constrained network**, not on a developer machine | MOB-1 |
| PP-2.3 | Every figure here is `[P]` and is **not promised to any user, partner or advertiser** | NFR notation |
| PP-2.4 | **No numeric performance guarantee may be published** on a public page or in a commercial agreement without an owner decision | PA §11 |
| PP-2.5 | A budget is **a design constraint, not a post-hoc measurement**. Exceeding it is a design failure, not a tuning task | — |

---

## 3. Loading strategy

### 3.1 Shared

| ID | Rule | Source |
| --- | --- | --- |
| PP-3.1 | **Server-rendered HTML is the delivery mechanism.** The first response contains the content, not a loader | PD-03, TR-107 |
| PP-3.2 | **Content is never gated behind JavaScript execution** | PD-03, PWX-1 |
| PP-3.3 | **Critical CSS is inlined**; the rest loads without blocking render | WP §3 |
| PP-3.4 | **No render-blocking JavaScript** | WP §3 |
| PP-3.5 | **No client-side framework is assumed** — D-16 and D-17 are open, and the budget must hold under either answer | D-16, D-17 |
| PP-3.6 | **Fonts never block text.** Text is visible during font load, and the fallback is metrically close enough that swapping does not shift layout | DSN §4, PP-4.2 |
| PP-3.7 | **Images carry intrinsic dimensions**; space is reserved before they load | PP-4.1 |
| PP-3.8 | Below-the-fold images are lazy-loaded; **above-the-fold images are not** | — |
| PP-3.9 | **No third-party script is loaded on the critical path.** None is loaded at all in V1 (PD-14) | PD-14 |
| PP-3.10 | **No web font, icon font, analytics SDK, tag manager, A/B framework, chat widget or ad network client** is present | PD-14 |

### 3.2 Deferred by default

| Deferred | Why | Degradation if it never arrives |
| --- | --- | --- |
| Map (C-10) | Heaviest element on the profile; vendor open (D-21) | Address, Area, landmark as text (MOB-5) |
| Photo gallery beyond the first image | Bytes | First image plus a count |
| Reviews beyond the first page (C-13) | Bytes | Rating summary plus the first page |
| Autocomplete (C-03) | Enhancement only | Full search still submits (PWX-2) |
| Nearby geolocation (C-07) | Permission-dependent | Area selection remains (UFL-N3) |
| Non-critical icons | Bytes | Text labels already present |

| ID | Rule |
| --- | --- |
| PP-3.11 | **Deferral never removes a capability**, only its enhanced form (PWX-1) |
| PP-3.12 | **Deferred regions reserve their space.** Arrival must not shift what the User is reading (CLS) |
| PP-3.13 | **Nothing essential is deferred** — name, status, hours, contact, trust signals and Sponsored labelling are in the first response (PA-15.6) |
| PP-3.14 | **A Sponsored label is never deferred, lazy-loaded or dependent on JavaScript** | LB-4, PWX-7 |

---

## 4. Layout stability

| ID | Rule | Source |
| --- | --- | --- |
| PP-4.1 | **Every image, embed and media element reserves its space** before loading | CLS |
| PP-4.2 | **Font swap must not shift layout** | PP-3.6 |
| PP-4.3 | **Nothing is injected above content the User is already reading** — no banner, no cookie bar, no promo strip | DSN §8 |
| PP-4.4 | Deferred blocks occupy their final height from first paint | PP-3.12 |
| PP-4.5 | A Sponsored result **occupies the same vertical rhythm as an organic one** so arrival order never reshuffles the page | LB §5 |
| PP-4.6 | In the Mini App, **viewport changes during host sheet drag must not trigger layout recalculation** | TM-2.4 |
| PP-4.7 | **Keyboard opening must not shift the layout** (Mini App especially) | TM-4.6 |
| PP-4.8 | An error, toast or inline validation message **reserves or overlays — it never pushes** content the User is reading | PEH §3 |

---

## 5. Network behaviour

| ID | Rule | Source |
| --- | --- | --- |
| PP-5.1 | **The product assumes an unreliable network as the normal case**, not the exception | R-3 |
| PP-5.2 | **No request is made that the current screen does not need** | — |
| PP-5.3 | **No request is made without a visible consequence** — no speculative fetching of content the User has not asked for | — |
| PP-5.4 | **No polling, no background sync, no keep-alive, no heartbeat** on either surface | PD-12, TM-2.7 |
| PP-5.5 | **No prefetch of authenticated or personal content** | TR-123 |
| PP-5.6 | Requests that can be cancelled when superseded are cancelled — typing in search must not leave a queue of stale requests in flight | UR-03 |
| PP-5.7 | **Retry is user-initiated, never automatic and never unbounded.** An automatic retry storm on a constrained network is worse than an honest failure | PEH §4 |
| PP-5.8 | Where a retry is offered, **it retries the action, not the whole page** | PEH §4 |
| PP-5.9 | **A slow response is reported as slow, not as broken** (PEH-3) | PEH §3 |

---

## 6. Caching, as surfaces see it

Authoritative rules are in `performance-and-caching.md`; the surfaces
must not contradict them.

| Content | Behaviour | Source |
| --- | --- | --- |
| Public pages — Home, categories, areas, profiles, static | `public` with a max age and stale-while-revalidate, plus a validator | TR-121 |
| Search results | **Short** max age, varying on **every** filter that changed the result | TR-122 |
| Authenticated and staff responses | **`no-store`** | TR-120 |
| Personal data | **Never cached by any shared cache** | TR-123 |
| Static assets | Long-lived, content-addressed filenames | — |
| Language negotiation | `Vary` includes the language header | — |

| ID | Rule |
| --- | --- |
| PP-6.1 | **Both surfaces obey the identical cache contract.** The Mini App is not an excuse to cache personal data (SCC-6) |
| PP-6.2 | **Nothing personal is ever cached client-side** (TR-202) |
| PP-6.3 | **No service worker and no offline cache** (PD-12) |
| PP-6.4 | A cached public page must never render as though the Guest were signed in |
| PP-6.5 | Sign-out must not leave authenticated content retrievable from any cache or history entry (PAU-6.12) |
| PP-6.6 | **OT-03 (cache backend) is open** and does not change any rule above | OT-03 |

---

## 7. Per-surface notes

### 7.1 Web

| ID | Rule |
| --- | --- |
| PP-7.1 | The baseline is a **mid-range Android device on a constrained connection** (WP §8) |
| PP-7.2 | Browser caching, conditional requests and content-addressed assets carry most repeat-visit performance |
| PP-7.3 | **No preload or prefetch of personal routes** (PP-5.5) |
| PP-7.4 | Search-engine crawlers must receive the **same fast, complete HTML** as users (PSE §3) |
| PP-7.5 | Performance is **not** improved by shipping a lighter page to crawlers — that is cloaking and is forbidden (PSE-3.4) |

### 7.2 Telegram Mini App

| ID | Rule |
| --- | --- |
| PP-7.6 | **Host startup cost is additive.** Bulbula's own budget does not expand to absorb it (PP-1.4) |
| PP-7.7 | **Readiness is signalled as early as essential interface exists** — a late signal keeps the host placeholder on screen and makes the app feel slower than it is (TM-2.1) |
| PP-7.8 | Readiness **must not** wait on the map, gallery or Reviews (TM-2.2) |
| PP-7.9 | **Nothing is re-fetched merely because the app was minimised and restored** (TM §2.2) |
| PP-7.10 | **No layout work during host sheet drag** (PP-4.6) |
| PP-7.11 | The adapter itself must be **small**; it is part of the JavaScript budget, not an exemption from it |
| PP-7.12 | **No Telegram storage facility is used as a performance cache** (TM-11.3) |

---

## 8. Degradation

**No offline support may be claimed (PD-12).** What follows is graceful
handling of loss, not offline capability.

| Situation | Behaviour |
| --- | --- |
| Network lost while reading | What has rendered stays rendered; nothing is blanked |
| Network lost before a deferred block | The block states it could not load and offers retry; the page remains usable |
| Map fails | Address, Area and landmark remain as text (MOB-5) |
| Images fail | Alternative text and layout hold; no broken frames |
| Enhancement script fails | The page still works — progressive enhancement is the contract (PWX-1) |
| Save / Review / Report submission fails | **The attempt is reported honestly; the User's input is preserved; retry is offered** |
| Submission outcome unknown | **Never claimed as success.** The uncertainty is stated (PEH-4) |
| Session expires mid-task | Intent preserved; re-authenticate; action completes (PN-7.6) |
| Partial assets | Content and meaning survive; only polish is lost |

| ID | Rule |
| --- | --- |
| PP-8.1 | **No success is ever shown for an action the server has not accepted** (PEH-4.1) |
| PP-8.2 | **No write is queued for later replay.** Without offline storage there is nowhere honest to queue it (PD-12) |
| PP-8.3 | **A failed write never silently loses the User's input** (PEH-4) |
| PP-8.4 | **No "you are offline" screen replaces usable rendered content** |
| PP-8.5 | **No capability is advertised as working offline, anywhere in the product or its copy** (PD-12) |

---

## 9. Verification

| ID | Check |
| --- | --- |
| PP-9.1 | Targets measured on a mid-range Android device on a constrained network, on both surfaces |
| PP-9.2 | Critical HTML/CSS and JavaScript budgets met on Home, search results and a Business profile |
| PP-9.3 | Every page renders its content with JavaScript disabled |
| PP-9.4 | No render-blocking script on any route |
| PP-9.5 | No third-party request on any route |
| PP-9.6 | No layout shift from fonts, images, deferred blocks or error messages |
| PP-9.7 | No layout shift when the keyboard opens in the Mini App |
| PP-9.8 | Sponsored labelling is present in the first response and with JavaScript disabled |
| PP-9.9 | Authenticated responses carry `no-store`; no personal data appears in any cache |
| PP-9.10 | No background polling or keep-alive traffic on an idle screen |
| PP-9.11 | Readiness is signalled before deferred blocks resolve |
| PP-9.12 | A failed submission preserves input, reports honestly and offers retry |
| PP-9.13 | No success state appears for an unaccepted action |
| PP-9.14 | A failed map leaves the address readable |
| PP-9.15 | No service worker is registered |

---

## 10. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-16 / D-17 | Frontend approach and view layer | **Open — implementation detail**; budgets bind either answer |
| D-21 | Maps vendor | Owner decision; the heaviest single performance variable |
| D-20 | Image handling and derivatives | Owner decision |
| OT-03 | Cache backend | **Open — technical decision** |
| OT-04 | Search index refresh strategy | **Open — technical decision** |
| OT-08 | Pagination style | **Open — technical decision** |
| OT-09 | Derivative generation timing | **Open — technical decision** |
| — | Ratifying any `[P]` number as a commitment | **Owner decision** — none may be published until taken |

---

## Decision references

D-16, D-17, D-20, D-21.
