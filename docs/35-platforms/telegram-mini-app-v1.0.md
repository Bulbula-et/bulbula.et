# Telegram Mini App Platform

| | |
| --- | --- |
| **Document** | Telegram Mini App Platform — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** Platform behaviour for the Telegram Mini App surface:
initialisation, the adapter, navigation, identity, authentication,
external links and theme.

**External authority.** Current official Telegram Mini Apps platform
documentation. Findings that materially shaped this document are recorded
in [`platform-architecture-v1.0.md`](platform-architecture-v1.0.md) §22.

**This document resolves no open decision.** D-33 and D-38 remain open.

---

## 1. Position

| ID | Statement | Source |
| --- | --- | --- |
| TM-1.1 | The Mini App is **the same application served to a Telegram webview** | TR-109, R-16, D-49 |
| TM-1.2 | It is a **first-class V1 surface** | SUR-7, D-15 |
| TM-1.3 | Same backend, same domain model, same rules, same terminology | SUR-2 |
| TM-1.4 | Every capability except C-37 and C-19…C-29 | `scope-v1.md` §1.5 |
| TM-1.5 | **Not an SEO surface**; excluded from indexing | TG-7, TR-117 |
| TM-1.6 | It must work within Telegram's viewport, navigation and theme conventions | TG-2 |

---

## 2. Initialisation

### 2.1 Launch sequence

```text
1. Host opens the Mini App URL in its webview
2. Host supplies launch context (signed) and theme parameters
3. Bulbula renders essential interface                 ← content first
4. Adapter signals readiness to the host               ← placeholder hidden
5. Adapter reads viewport, safe areas and theme; maps theme onto tokens
6. Adapter binds the host Back button to the navigation stack
7. Raw launch context is sent to the server for validation
8. Server validates; establishes SURFACE, not identity
9. Any existing Customer session is resolved from the server record
```

| ID | Rule | Source |
| --- | --- | --- |
| TM-2.1 | **Readiness is signalled as early as essential interface is available.** If it is never signalled, the host's loading placeholder persists until full page load, which wastes the User's time | Platform research §22.9 |
| TM-2.2 | Readiness must not wait on the map, the gallery, Reviews or any deferred block | PWX §8 |
| TM-2.3 | Steps 7–9 **must not block first render.** The Guest experience is complete without them | GS-1 |
| TM-2.4 | The Mini App opens minimised inside a draggable host sheet on mobile; **the viewport is unstable while it is being dragged**, and no layout work is performed during that period | Platform research §22.11 |
| TM-2.5 | Expanding the view is permitted; it is a presentation action and changes no product behaviour | SCC-1.1 |
| TM-2.6 | **Fullscreen mode is not used in V1.** It removes host chrome Bulbula deliberately relies on | TM-3 |

### 2.2 Lifecycle

| Event | Behaviour |
| --- | --- |
| Viewport change | Re-read the **stable** height; reposition pinned elements only once stable |
| Theme change | Re-map theme onto tokens live; **re-verify nothing becomes unreadable** |
| Safe-area change | Re-apply insets |
| Back pressed | Pop one layer from the single navigation stack (§6) |
| Minimised / restored | No state is lost; nothing is re-fetched merely because focus returned |
| Closed | Nothing is lost that the server has already accepted |

| ID | Rule |
| --- | --- |
| TM-2.7 | **No polling, no background timers, no keep-alive requests** (PP-5.4) |
| TM-2.8 | Lifecycle handling lives in the adapter; product code never subscribes to host events |

---

## 3. The Telegram adapter

The adapter owns **all** of the following. Nothing below may appear in
product code (PD-02, TR-110).

| # | Owned concern |
| --- | --- |
| 1 | Launch-context capture and hand-off for server-side validation |
| 2 | Viewport behaviour — stable height, expansion state, drag stability |
| 3 | Safe-area and content-safe-area insets |
| 4 | Theme parameter capture and mapping onto semantic tokens |
| 5 | Host Back button binding |
| 6 | Main-button integration — **only where genuinely required** (§6.3) |
| 7 | Share mechanism |
| 8 | External and Telegram link opening |
| 9 | Host lifecycle events |
| 10 | Host capability detection and fallback selection |

| ID | Rule | Source |
| --- | --- | --- |
| TM-3.1 | **Telegram-specific calls are never spread through product code** | TR-110, PD-02 |
| TM-3.2 | The adapter exposes **capabilities**, not host objects. Shared components receive "can share" or "has safe-area insets", never a host handle | PA-2.2 |
| TM-3.3 | The adapter is the only component that knows which surface it is on | PA-2.1 |
| TM-3.4 | Every host feature the adapter uses has a **documented fallback** (PD-06) | PD-06 |

### 3.1 Host capability gating

Host features are gated by the user's Telegram client version. Bulbula
**detects rather than demands** (PD-06).

| Host feature | Availability | Fallback when absent |
| --- | --- | --- |
| Back button | Bot API 6.1+ | A single in-page up affordance — breadcrumb parent (IAR-20) |
| Safe-area and content-safe-area insets | Bot API 8.0+ | Static conservative padding |
| Secondary background colour | Bot API 6.1+ | Bulbula's own surface token |
| Extended theme fields | Bot API 7.0+ to 7.10+ | Bulbula's own semantic tokens |
| External link opening without closing the app | Behaviour improved at Bot API 7.0 | Treat leaving as possible; warn before destructive loss (§9.3) |

| ID | Rule |
| --- | --- |
| TM-3.5 | **No minimum Bot API version gate.** A user on an older client gets reduced chrome integration, never a refusal (PD-06, MOB-3) |
| TM-3.6 | A missing host feature **never removes a capability** — only its chrome integration |

---

## 4. Viewport and safe areas

| ID | Rule | Source |
| --- | --- | --- |
| TM-4.1 | Layout is sized from the host's **stable viewport height**, never the raw window height and never `100vh` | Research §22.2, UR-12 |
| TM-4.2 | The continuously-updating viewport height **must not** be used to pin interface elements; the platform documentation states its refresh rate is insufficient for that purpose | Research §22.2 |
| TM-4.3 | **Both** the safe-area inset and the content-safe-area inset are applied | Research §22.3, A11-18.3 |
| TM-4.4 | **Nothing interactive sits under host chrome** at any viewport, with the keyboard open or closed | A11-18.3, WCAG 2.4.11 |
| TM-4.5 | Bottom sheets sit above the safe-area inset and above the host bottom bar | DSN-8.11 |
| TM-4.6 | **The layout does not jump when the on-screen keyboard opens** | TM-4.1 |
| TM-4.7 | Autocomplete suggestion count is sized so no suggestion sits under the keyboard | UR-03 |
| TM-4.8 | At most one sticky region, as on the Web | IAR §6 |
| TM-4.9 | Where insets are unavailable, conservative static padding is applied rather than none | TM-3.5 |

---

## 5. Theme

| ID | Rule | Source |
| --- | --- | --- |
| TM-5.1 | Host theme parameters are **mapped onto Bulbula's semantic token tier**. No second palette is shipped | PD-07, DSN-2.25 |
| TM-5.2 | Theme fields are **version-gated**; each maps where present and **falls back to Bulbula's own token** where absent | Research §22.7 |
| TM-5.3 | The host colour scheme signal is respected for surface and text tokens; it **does not** constitute a dark-mode product feature — **D-19 remains open** | D-19 |
| TM-5.4 | **Contrast is re-verified against host themes.** A ratio that passes on white can fail under a host theme | A11-18.4 |
| TM-5.5 | **The three integrity signals must remain distinguishable under every host theme** — Sponsored, Verified and open status each carry a non-colour carrier for exactly this reason | A11 §5.1, PA-15.6 |
| TM-5.6 | A host theme may not recolour, obscure or reduce the contrast of the **Sponsored label** | LB-4, PA-15.6 |
| TM-5.7 | Theme changes are applied live without reload and without layout shift | TM-2.2 |
| TM-5.8 | Where a host theme would make Bulbula's brand accent unreadable, **legibility wins** | A11-1.5 |

---

## 6. Navigation

Full cross-surface rules in
[`platform-navigation-v1.0.md`](platform-navigation-v1.0.md).

### 6.1 One stack

| ID | Rule | Source |
| --- | --- | --- |
| TM-6.1 | **One navigation stack**, bound to the host Back button | PD-08 |
| TM-6.2 | **Bulbula renders no back control of its own, ever** | UR-12, A11-18.1 |
| TM-6.3 | Back pops exactly one layer: topmost overlay → sheet → page | IAR-35 |
| TM-6.4 | The Android hardware back must behave identically, because the host routes it to the same control | Research §22.4 |
| TM-6.5 | The Back button is shown when there is somewhere to go back to and hidden at the root | — |
| TM-6.6 | **No competing navigation controls** of any kind | PD-08 |

### 6.2 Internal history

| ID | Rule |
| --- | --- |
| TM-6.7 | The model — full page loads or fragment swaps — is **Open (D-38)**. Both must remain possible (TR-119) |
| TM-6.8 | Whichever is chosen, Back, refresh and deep links behave as specified in §10 and in the navigation document |
| TM-6.9 | Opening a modal or sheet pushes a layer; Back closes it (IAR-31) |
| TM-6.10 | **No infinite scroll** (IAR-30) |
| TM-6.11 | Breadcrumbs matter more here than on the Web because there is no browser chrome; the immediate parent is always retained (IAR-20) |

### 6.3 Main button

| ID | Rule |
| --- | --- |
| TM-6.12 | The host main button is used **only where a screen has exactly one unambiguous primary action** and placing it in host chrome genuinely helps |
| TM-6.13 | It is **not** used on browse, search or profile screens, which have several legitimate actions |
| TM-6.14 | It never becomes the **only** route to an action: an in-page control always exists, so the capability survives on a client without it |
| TM-6.15 | Its label follows `content-design-v1.0.md` exactly — no surface-specific wording (SCC-2.1) |
| TM-6.16 | Whether it is used at all in V1 is **Open — platform decision**, to be settled against real screens during build |

---

## 7. Identity

| ID | Rule | Source |
| --- | --- | --- |
| TM-7.1 | **A Telegram user is not an approved login provider.** The provider set is Google and email OTP | D-48, TG-4, AI-4 |
| TM-7.2 | **D-33 is not decided here** and must not be decided by implementation | D-33 |
| TM-7.3 | Validated launch context establishes a **surface, not an identity** | TR-113 |
| TM-7.4 | **No Telegram authentication may be presented as a confirmed V1 product requirement** | TG-4 |
| TM-7.5 | **Telegram identity must not become the primary account model** | D-33 |
| TM-7.6 | The surface must continue to work fully with the approved authentication methods alone | D-48 |
| TM-7.7 | The platform **must support attaching Telegram later as an additional provider identity without restructuring** | TR-114 |
| TM-7.8 | **No screen implies that a Telegram account is, or will become, a Bulbula login** | CDN-1.5 |

### 7.1 Context validation

| ID | Rule | Source |
| --- | --- | --- |
| TM-7.9 | The **raw signed launch payload** is validated **server-side** before any trust is placed in it | TR-112, TG-3 |
| TM-7.10 | The host-parsed convenience object is **never** used for authorisation | Research §22.1 |
| TM-7.11 | Validation verifies the signature using the documented derivation and compares in **constant time** | Research §22.1 |
| TM-7.12 | Validation **rejects payloads outside a freshness window** | TR-112 |
| TM-7.13 | The freshness window value is **Open — technical decision** | — |
| TM-7.14 | **Validation failure degrades to the full Guest experience** — not an error screen, not a dead end | GS-1 |
| TM-7.15 | A launch parameter is **trivially forgeable** and is read from validated context server-side, never trusted from the client | Research §22.10 |
| TM-7.16 | **No bot token or secret ever reaches the client** | PA-18.10 |
| TM-7.17 | Telegram-supplied personal data is not stored beyond what the surface requires and is never sent to analytics | TR-201, TR-202 |

---

## 8. Authentication

Full flows in [`platform-auth-v1.0.md`](platform-auth-v1.0.md).

| ID | Rule | Source |
| --- | --- | --- |
| TM-8.1 | Approved methods only: **Google and email OTP**. No passwords. No Apple | D-48 |
| TM-8.2 | **Email OTP is presented first**; Google remains available and is presented second | UR-14, TG-5 |
| TM-8.3 | This is an **ordering** difference, not an availability difference | SCC-1.1 |
| TM-8.4 | Redirect-based OAuth inside an embedded webview **must not be assumed to work**; Google uses an **external-browser hand-off** with a safe return | TR-115, R-23 |
| TM-8.5 | Session transport is a **bearer token**, avoiding third-party cookie behaviour | TD-02, TR-116 |
| TM-8.6 | **The session token is never held in `localStorage`** — WebView storage may be cleared on some platforms | PD-05, Research §22.8 |
| TM-8.7 | The session resolves to the **same server-side record** as a Web session | S-1 |
| TM-8.8 | **The Mini App must not trust client-provided identity claims** | TR-112 |
| TM-8.9 | Authentication **returns the User to the task they attempted**, inside the Mini App | UFL-0.1 |
| TM-8.10 | The OTP field accepts paste, uses a numeric keypad, and carries the one-time-code hint | A11-8.3, A11-8.4 |
| TM-8.11 | **No CAPTCHA, puzzle or memory test** | WCAG 3.3.8 |

---

## 9. External links

| ID | Rule | Source |
| --- | --- | --- |
| TM-9.1 | External links are opened through the **host's link-opening method**, not `window.open` or a bare anchor | PD-09 |
| TM-9.2 | Telegram links are opened through the **host's Telegram-link method** | PD-09 |
| TM-9.3 | Link opening is invoked **only in response to a user interaction**, as the platform requires | Research §22.5 |
| TM-9.4 | **A link leaving Telegram is clearly indicated before it is followed** | WCAG 3.2.5, PA-16.9 |
| TM-9.5 | Behaviour on older clients differs — a Telegram link could close the Mini App before Bot API 7.0 — so **no unsaved state may depend on the app staying open** | Research §22.5 |

| Target | Mechanism | Fallback |
| --- | --- | --- |
| **Maps / directions** (C-10) | Host external-link hand-off | Address, Area and landmark as text (MOB-5) |
| **Business website** (C-11) | Host external-link hand-off | The domain shown as readable text |
| **Phone** (C-11) | Host hand-off to the dialler | The number shown as readable text |
| **Email** | Host hand-off | The address shown as text |
| **Social links** (C-11) | Host hand-off; Telegram links via the Telegram method | Omitted where absent |
| **Google sign-in** | External browser, safe return (§8) | Email OTP |

| ID | Rule |
| --- | --- |
| TM-9.6 | A contact action is rendered **only where that contact point exists** — no disabled placeholders (UFL-A5.1) |
| TM-9.7 | **No capability depends on leaving Telegram**, except Google sign-in, which has email OTP as its alternative |

---

## 10. Deep links and sharing

| ID | Rule | Source |
| --- | --- | --- |
| TM-10.1 | A Telegram direct-link launch maps to **the same canonical destinations** as the Web | IAR §5.1 |
| TM-10.2 | A launch parameter is resolved from **validated** context server-side before anything is acted on | Research §22.10, TM-7.15 |
| TM-10.3 | Deep-linkable destinations are exactly those in `information-architecture-v1.0.md` §5.1 | IAR §5.1 |
| TM-10.4 | **Transient state is never deep-linkable** — no open sheet, no toast, no autocomplete | IAR §5.1 |
| TM-10.5 | Sharing shares the **canonical Web URL** — never a session, filter or tracking parameter | IAR-36 |
| TM-10.6 | **A shared link must resolve on the Web for a recipient with no Telegram account** | TG-6, TR-118 |
| TM-10.7 | There is no native direct-share primitive; sharing is implemented over a Telegram share link, which means **share must not be relied on to keep the app open** on older clients | Research §22.5, §22.6 |
| TM-10.8 | Sharing requires **no account** and is never recorded against a person | IAR-40 |

---

## 11. Storage

| ID | Rule | Source |
| --- | --- | --- |
| TM-11.1 | **No session token in `localStorage`** | PD-05 |
| TM-11.2 | **No personal data in any client storage** | PA-18.11, TR-202 |
| TM-11.3 | Telegram's cloud, device and secure storage facilities are **not used in V1** — they are version-gated and nothing in V1 needs them | PD-05 |
| TM-11.4 | **Saved items are server-side**, belonging to the identity. They are never stored on the device | C-34, SCC-6.6 |
| TM-11.5 | **No offline storage, no write queue, no service worker** | PD-12 |
| TM-11.6 | Any client storage used for throwaway UI state must be safe to lose at any moment | Research §22.8 |

---

## 12. Not present on this surface

| Absent | Why |
| --- | --- |
| Operations console | Web only (`scope-v1.md` §1.5) |
| Any indexable route or SEO behaviour | TG-7, TR-117 |
| Telegram as a login provider | D-48; **Open (D-33)** |
| Telegram message notifications | Email only (D-24) |
| A Telegram bot conversational interface | Not a V1 capability |
| Payments or invoices | No V1 payment capability |
| Fullscreen mode | TM-2.6 |
| A second back button or any host-chrome duplicate | TM-6.2 |
| A separate codebase, build target or release train | PD-01 |
| Any capability the Web does not have | SUR-4 |

---

## 13. Verification

| ID | Check |
| --- | --- |
| TM-13.1 | Launch context is validated server-side; a forged or stale payload is rejected |
| TM-13.2 | Validation failure yields a fully functional Guest experience |
| TM-13.3 | The host-parsed convenience object is never used for authorisation |
| TM-13.4 | Only one back affordance exists, and it is the host's |
| TM-13.5 | Android hardware back behaves identically to the host Back button |
| TM-13.6 | Nothing sits under host chrome at any viewport, keyboard open and closed |
| TM-13.7 | The layout does not jump when the keyboard opens |
| TM-13.8 | Contrast passes under light and dark host themes |
| TM-13.9 | Sponsored, Verified and open status remain distinguishable under every tested host theme |
| TM-13.10 | Both authentication methods complete inside the webview on iOS and Android |
| TM-13.11 | Google sign-in via external browser returns safely into the Mini App |
| TM-13.12 | No session token is present in client storage |
| TM-13.13 | A shared link opens for a recipient with no Telegram account |
| TM-13.14 | No Mini App route is indexable or present in the sitemap |
| TM-13.15 | No console route is reachable |
| TM-13.16 | The same query returns the same results in the same order as the Web |
| TM-13.17 | The same Customer sees the same Saved list and Reviews as on the Web |
| TM-13.18 | Behaviour verified on a client **below** the newest Bot API version, confirming fallbacks |

---

## 14. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-33 | Telegram identity relationship | Owner decision — **not taken here**; attach-later support required now |
| D-38 | Navigation model | **Open — implementation detail**; both models remain possible |
| D-19 | Dark mode as a V1 feature | Owner decision — host theme support does not decide it |
| D-21 | Maps provider and hand-off | Owner decision |
| D-16 / D-17 | Frontend approach and view layer | **Open — implementation detail** |
| OT-05 | CSP `frame-ancestors` admitting the Telegram host | **Open — technical decision** |
| OT-01 | Session lifetime and rotation | **Open — technical decision** |
| — | Launch-context freshness window | **Open — technical decision** |
| — | Whether the host main button is used in V1 | **Open — platform decision** |
| — | Telegram host origins to admit | **Open — technical decision**, part of OT-05 |

---

## Decision references

D-15, D-16, D-17, D-19, D-21, D-24, D-33, D-38, D-48, D-49.
