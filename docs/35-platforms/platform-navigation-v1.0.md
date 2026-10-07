# Platform Navigation

| | |
| --- | --- |
| **Document** | Platform Navigation — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** How the one information architecture is navigated on each
surface: history, Back, deep links, state and entry points.

**Scope note.** The structure itself — hierarchy, URL grammar,
breadcrumbs, labels — is settled in
`docs/20-ux-ui/information-architecture-v1.0.md` and is **not restated
or amended here**. This document covers only surface mechanics.

**D-38 — the navigation model — remains open.** Everything below is
written so that either resolution satisfies it.

---

## 1. Principles

| ID | Principle | Source |
| --- | --- | --- |
| PN-1.1 | **One information architecture.** Both surfaces navigate the same hierarchy, the same destinations and the same canonical URLs | IAR §3, SUR-2 |
| PN-1.2 | **One stack per surface.** Exactly one history stack is authoritative; nothing maintains a second | PD-08 |
| PN-1.3 | **One Back affordance.** Whatever the host provides is the Back affordance; Bulbula never adds a second | TM-6.2 |
| PN-1.4 | **Back is always destructive-safe.** Back never loses work the User would expect to be kept, and never silently discards an accepted action | UFL §9 |
| PN-1.5 | **Every meaningful destination is addressable.** Transient state never is | IAR §5.1 |
| PN-1.6 | **No dead ends.** Every screen offers at least one forward route (IAR-37) | IAR-37 |
| PN-1.7 | Navigation chrome differs between surfaces; **destinations never do** | SCC-3 |

---

## 2. What is a destination

| Class | Examples | Addressable | Pushes history |
| --- | --- | --- | --- |
| **Page** | Home, Search results, Category, Area, Business profile, Saved, Account, static pages | **Yes** | Yes |
| **Sub-page form** | Report, Review | **Yes** | Yes |
| **Layer** | Modal, bottom sheet, filter panel, image viewer | No | **Yes** (closable by Back) |
| **Transient** | Toast, inline validation, autocomplete list, loading state, tooltip | No | **No** |
| **In-page** | Accordion open, tab selected, Reviews sort | No | No (see §6.2) |

| ID | Rule |
| --- | --- |
| PN-2.1 | A **layer pushes a history entry so Back closes it.** Back never skips past an open layer to the previous page (IAR-31) |
| PN-2.2 | **Transient state never pushes history** and is never restorable by Back or by URL (IAR §5.1) |
| PN-2.3 | A layer that collects input the User would mourn losing warns before Back discards it (UFL §9) |
| PN-2.4 | The same thing is the same class on both surfaces. A filter panel that is a layer on one surface is a layer on the other |

---

## 3. Back behaviour

### 3.1 Shared contract

| ID | Rule |
| --- | --- |
| PN-3.1 | Back pops **exactly one** layer of the stack: topmost overlay → sheet → page |
| PN-3.2 | Back returns the User to **the previous destination in the state they left it** — same scroll position, same results, same filters, same expanded blocks |
| PN-3.3 | Back **must not re-run a submitted action**. Returning to a submitted form shows its outcome, not a resubmission |
| PN-3.4 | Back from a destination reached after authentication **does not return the User to the sign-in screen** (PAU-6.5) |
| PN-3.5 | Back after a redirect does not trap the User in a redirect loop |
| PN-3.6 | At the root, Back is unavailable; the surface's own exit behaviour applies |
| PN-3.7 | **Back is never relabelled, never conditional, and never performs an action other than going back** |

### 3.2 Surface mechanics

| | **Web** | **Telegram Mini App** |
| --- | --- | --- |
| Affordance | Browser back — chrome, gesture, keyboard, hardware | Host Back button; Android hardware back routed to it |
| Owner | The browser | The host, bound by the adapter |
| Bulbula renders one | **No** (WP-4.2) | **No** (TM-6.2) |
| Stack source | Browser history | The single stack the adapter binds (PD-08) |
| Visibility | Always present in chrome | Shown when a parent exists, hidden at root |
| Fallback | — | In-page breadcrumb parent where the host control is unavailable (TM-3.5) |

| ID | Rule |
| --- | --- |
| PN-3.8 | Where a surface's Back control is absent, **the breadcrumb parent is the fallback** (IAR-20) — never nothing |
| PN-3.9 | Up (breadcrumb parent) and Back (previous destination) are **distinct and both honest**. Breadcrumbs are never rewritten to match history (IAR-21) |

---

## 4. Forward navigation and entry points

| ID | Rule | Source |
| --- | --- | --- |
| PN-4.1 | Entry can occur at **any** destination — deep link, share, search engine (Web), Telegram direct link | IAR §5 |
| PN-4.2 | **A deep-entered page is fully usable with no prior history.** It shows breadcrumbs, its parent, and onward routes | IAR-20, IAR-37 |
| PN-4.3 | Deep entry **never** triggers a redirect to Home | PN-1.6 |
| PN-4.4 | The global header's search entry is reachable from every page on both surfaces | IAR §6 |
| PN-4.5 | **No infinite scroll.** Pagination is explicit so positions remain addressable and returnable | IAR-30 |
| PN-4.6 | Pagination state is part of the address on the Web and part of the stack on both surfaces | WP-5 |

---

## 5. Deep links

| ID | Rule | Source |
| --- | --- | --- |
| PN-5.1 | The deep-linkable set is exactly `information-architecture-v1.0.md` §5.1 — identical on both surfaces | IAR §5.1 |
| PN-5.2 | The **canonical form is the Web URL**. A Telegram direct link resolves to the same canonical destination | TM-10.1 |
| PN-5.3 | A shared link **must open for a recipient with no Telegram account** | TG-6, TR-118 |
| PN-5.4 | Shared links carry **no session, no personal identifier and no tracking parameter** | IAR-36 |
| PN-5.5 | A Telegram launch parameter is resolved from **validated server-side context**, never trusted from the client | TM-7.15 |
| PN-5.6 | An unrecognised or stale deep link resolves to the **closest valid ancestor with an explanation**, not to a blank error | PEH-7 |
| PN-5.7 | A link to content that no longer exists follows the gone/removed handling in `platform-error-handling-v1.0.md` §7 | PEH-7 |

---

## 6. State

### 6.1 Three kinds

Established in PD-10 and binding on both surfaces.

| Kind | Definition | Lives in | Survives Back | Survives reload | Survives surface switch |
| --- | --- | --- | --- | --- | --- |
| **Address state** | What the destination *is* — route, query, filters, sort, page | The URL (Web) / the stack entry (both) | Yes | Yes | **Yes** — it is shareable |
| **Application state** | What the identity *owns* — session, Saved, Reviews, permissions | The server | Yes | Yes | **Yes** |
| **Ephemeral state** | What the moment *is* — scroll, open accordion, focus, draft text | Memory | Restored on Back (§6.3) | **No** | No |

| ID | Rule |
| --- | --- |
| PN-6.1 | **These three are never confused.** Ephemeral state is never put in a URL; address state is never kept only in memory; application state is never kept only on the client |
| PN-6.2 | **Saved is application state on both surfaces** — server-side, never device-local (C-34, TM-11.4) |
| PN-6.3 | **Nothing personal is ever placed in address state** (TR-123) |

### 6.2 What belongs in the address

| In | Out |
| --- | --- |
| Route, search query, named filters (`category`, `area`, `open_now`, `min_rating`, `verified`), allow-listed `sort`, page, business `branch`, sign-in `continue` | Scroll position, open accordion, selected tab within a page, map pan or zoom, session, personal identifiers, tracking parameters, any unrecognised parameter |

| ID | Rule |
| --- | --- |
| PN-6.4 | Only **named, allow-listed** parameters are honoured; unknown parameters are ignored, never reflected (WP-5) |
| PN-6.5 | Changing a filter produces a **new, shareable address** reproducing the same results |
| PN-6.6 | A `continue` target is validated as an **internal destination** before use (PAU-6.6) |

### 6.3 Restoration on Back

| ID | Rule |
| --- | --- |
| PN-6.7 | Returning to a results list restores **scroll position and the same results** — the User does not re-scroll or re-filter (IAR §5) |
| PN-6.8 | Restoration **must not** reorder results beneath the User, including Sponsored placement |
| PN-6.9 | Where results genuinely cannot be restored identically, the list is shown from the top **with the filters intact**, never with filters silently dropped |
| PN-6.10 | Restoration never resurrects a dismissed toast, an expired OTP or a stale error |

---

## 7. Authenticated transitions

| ID | Rule | Source |
| --- | --- | --- |
| PN-7.1 | An action needing an account **preserves the intent**, authenticates, and **completes the original action** (UFL-0.1) | UFL-0.1 |
| PN-7.2 | The User is returned to the destination they were on, **not to Home and not to Account** | UFL-0.1 |
| PN-7.3 | On the Web this uses the `continue` parameter on `/signin`; in the Mini App the equivalent intent is held across the flow | WP-5, PAU-6 |
| PN-7.4 | Back from the completed action does not re-enter sign-in (PN-3.4) | — |
| PN-7.5 | Sign-out returns the User to a public destination, never to a blank or forbidden screen | PAU §8 |
| PN-7.6 | Session expiry **mid-task** preserves the attempted action and re-authenticates, rather than discarding it (PEH-5) | PEH-5 |

---

## 8. Surface-specific chrome

| | **Web** | **Telegram Mini App** |
| --- | --- | --- |
| Global header | Yes — persistent, with search entry | Yes, within host chrome; sized from the stable viewport |
| Browser chrome | Present (address bar, tabs, back, refresh) | Absent |
| Host chrome | Absent | Present (title bar, Back, close, draggable sheet) |
| Breadcrumbs | Present | Present and **more important** — the only parent cue |
| Footer | Full — static pages, legal, contact | Reduced; the same links remain reachable |
| New tab / window | Possible | Not possible; external links hand off to the host |
| Sticky regions | At most one | At most one, above safe-area insets |
| Primary action in chrome | No | Optionally, under the strict limits of TM §6.3 |

| ID | Rule |
| --- | --- |
| PN-8.1 | **Chrome differences never change which destinations exist or what they are called** (SCC-2.1) |
| PN-8.2 | A reduced footer **never removes access** to legal, privacy, contact or policy pages (C-18) |
| PN-8.3 | No surface invents a destination the other lacks, except the two documented exceptions (SCC §4) |

---

## 9. Verification

| ID | Check |
| --- | --- |
| PN-9.1 | Exactly one back affordance exists on each surface |
| PN-9.2 | Back closes an open layer rather than skipping the page beneath it |
| PN-9.3 | Back after submission never resubmits |
| PN-9.4 | Back after authentication never re-enters sign-in |
| PN-9.5 | Returning to results restores scroll and the same results in the same order |
| PN-9.6 | Every §5.1 destination deep-enters correctly with no prior history |
| PN-9.7 | A deep-entered page shows breadcrumbs and at least one onward route |
| PN-9.8 | A shared link opens on the Web for a recipient with no Telegram account |
| PN-9.9 | Unknown query parameters are ignored and never reflected |
| PN-9.10 | No personal data appears in any address |
| PN-9.11 | An interrupted action resumes after authentication, on both surfaces |
| PN-9.12 | Android hardware back behaves identically to the host Back button |
| PN-9.13 | A stale deep link resolves to the nearest valid ancestor with an explanation |

---

## 10. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-38 | Navigation model — full page loads or fragment swaps | **Owner decision, open.** Both must remain possible (TR-119) |
| D-16 / D-17 | Frontend approach and view layer | **Open — implementation detail** |
| OT-08 | Pagination style | **Open — technical decision**; does not change PN-4.5 |
| D-21 | Maps vendor — affects whether map state is ever addressable | Owner decision |
| — | Scroll-restoration mechanism | **Open — implementation detail**; PN-6.7 is binding regardless |

---

## Decision references

D-16, D-17, D-21, D-38.
