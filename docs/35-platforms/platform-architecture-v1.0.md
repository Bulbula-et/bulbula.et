# Platform Architecture

| | |
| --- | --- |
| **Document** | Platform Architecture — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The client-platform architecture for the two V1 surfaces, the
boundaries between shared and surface-specific code, and the platform
decision register (`PD-xx`) introduced by this phase.

**Scope.** Client platform only. Domain, application and persistence
architecture is fixed by
[`../30-technical/architecture.md`](../30-technical/architecture.md) and
[`../30-technical/trd-v1.0.md`](../30-technical/trd-v1.0.md) and is not
restated or revised here.

**Documentation only.** No implementation.

---

## 1. The shape

```text
┌─────────────────────────────────────────────────────────────────┐
│                  SHARED BULBULA APPLICATION                     │
│   domain · application services · repositories · search port    │
│   one set of business rules, permissions, ranking, review and   │
│   sponsorship logic                              (TR-109, P-4)  │
└───────────────────────────┬─────────────────────────────────────┘
                            │
        ┌───────────────────┴────────────────────┐
        │   PRESENTATION — shared as far as      │
        │   practical: tokens, components,       │
        │   screens, content, IA, a11y rules     │
        └───────────────────┬────────────────────┘
                            │
             ┌──────────────┴───────────────┐
             │       SURFACE ADAPTER        │
             │  the only place a surface    │
             │  may be named       (TR-110) │
             └──────┬────────────────┬──────┘
                    │                │
      ┌─────────────▼──────┐  ┌──────▼──────────────────────┐
      │ WEB SURFACE        │  │ TELEGRAM MINI APP           │
      │ server-rendered    │  │ same application in the     │
      │ HTML + progressive │  │ Telegram web runtime        │
      │ enhancement        │  │ + Telegram adapter          │
      │ browser APIs       │  │ + host integrations         │
      │ SEO surface        │  │ not an SEO surface          │
      │ hosts /ops/*       │  │ no operations console       │
      └────────────────────┘  └─────────────────────────────┘
```

| ID | Rule | Source |
| --- | --- | --- |
| PA-1.1 | The Mini App is **the same application served to a Telegram webview** — not a separate build target, codebase or product | TR-109, R-16, D-49 |
| PA-1.2 | **One backend, one domain model, one API contract, one permissions model** | SUR-2, PLT-equivalent |
| PA-1.3 | Both surfaces are **first-class** V1 surfaces | SUR-7 |
| PA-1.4 | **The platform layer stays thin.** It adapts; it does not implement product behaviour | TR-111 |
| PA-1.5 | There is **no Telegram backend, no second database, no duplicated domain rule, no independent search, no independent review logic** | D-49, G-5 |
| PA-1.6 | **No separate frontend application** is created. One presentation layer serves both surfaces | D-49 |

---

## 2. Boundaries

### 2.1 Shared code — the default

| Layer | Content | May name a surface? |
| --- | --- | --- |
| Domain | Entities, rules, invariants | **No** |
| Application services | Use cases, permissions, validation | **No** |
| Presentation — tokens | The semantic token tier (`design-system-v1.0.md` §1) | **No** |
| Presentation — components | All 44 (`component-spec-v1.0.md` §45) | **No** |
| Presentation — screens | Every public screen (`public-web-ux-v1.0.md`) | **No** |
| Content | Every string (`content-design-v1.0.md`) | **No** |
| Information architecture | Structure and URL grammar | **No** |
| Accessibility rules | `accessibility-v1.0.md` | **No** |

| ID | Rule |
| --- | --- |
| PA-2.1 | **A surface conditional outside the adapter is a defect**, not a shortcut (TR-110) |
| PA-2.2 | Shared presentation receives surface capabilities as **inputs**, never by inspecting the runtime itself |
| PA-2.3 | If a component needs to know which surface it is on, the requirement is wrong or the adapter is missing a capability |

### 2.2 The surface adapter — the only place a surface is named

| Concern | Web | Mini App | Spec |
| --- | --- | --- | --- |
| **Navigation chrome** | Bulbula header, footer, own back affordance | Host header, host Back button, host bottom bar; Bulbula suppresses its own | [`platform-navigation-v1.0.md`](platform-navigation-v1.0.md) |
| **Viewport conventions** | Browser viewport | Host stable viewport height, both safe-area insets | [`telegram-mini-app-v1.0.md`](telegram-mini-app-v1.0.md) §4 |
| **Theme source** | Bulbula semantic tokens | Host theme parameters mapped **onto** those tokens | §8 of the Mini App spec |
| **Authentication entry** | Google first, email OTP second | **Email OTP first**, Google via external-browser hand-off | [`platform-auth-v1.0.md`](platform-auth-v1.0.md) |
| **Session transport** | Cookie | `Authorization: Bearer` | TD-02, TR-116 |
| **Share mechanism** | Web Share API with copy-link fallback | Telegram share | §9 of the Mini App spec |
| **External links** | Normal navigation | Host link-opening methods | §9 of the Mini App spec |
| **Map hand-off** | Browser or device maps | Host external-link hand-off | §16 of this document |
| **Analytics transport** | Server-side event recording | Server-side event recording | §14 |

| ID | Rule |
| --- | --- |
| PA-2.4 | **This inventory is closed.** A tenth concern requires an owner decision in the register |
| PA-2.5 | Every entry changes *how* a capability is reached, never *what* it means — the formal rule is in [`shared-client-contract-v1.0.md`](shared-client-contract-v1.0.md) §3 |
| PA-2.6 | Telegram-specific calls live in the Telegram adapter only, never in product code | TR-110 |
| PA-2.7 | The adapter corresponds to the `Bulbula\Telegram` namespace already indicated by the architecture | `architecture.md` |

### 2.3 State boundaries

Three kinds of state, deliberately separated to prevent accidental
SPA complexity. Full rules in
[`platform-navigation-v1.0.md`](platform-navigation-v1.0.md) §2.

```text
URL state          ≠   application state   ≠   ephemeral UI state
(addressable,          (server-held,            (not addressable,
 shareable,             session-scoped)          not restored)
 restorable)
query, path            identity, session        open sheet, focus,
                                                toast, autocomplete
```

| ID | Rule |
| --- | --- |
| PA-2.8 | Anything a User could reasonably share or bookmark is **URL state** |
| PA-2.9 | Anything belonging to the identity is **application state**, held server-side |
| PA-2.10 | Ephemeral UI state is **never** persisted and never restored after navigation |

### 2.4 Error boundaries

| Boundary | Behaviour |
| --- | --- |
| Field | Inline validation error; the rest of the form preserved |
| Component | A failed block degrades; the page survives (a failed map still leaves the address) |
| Page | Full error page with a working search field |
| Surface | A host failure affects that surface only; **Telegram being unavailable does not affect the Web** |

Full specification in
[`platform-error-handling-v1.0.md`](platform-error-handling-v1.0.md).

---

## 3. API consumption

| ID | Rule | Source |
| --- | --- | --- |
| PA-3.1 | **The Web surface never calls its own HTTP API.** Web controllers invoke application services in process | **TD-01** |
| PA-3.2 | Web controllers and API controllers are **two thin adapters over the same application services** | `trd-v1.0.md` §19 |
| PA-3.3 | The API **serves the Mini App** and must be sufficient for a later client without new domain rules | TR-24 |
| PA-3.4 | How much the Mini App uses server-rendered HTML versus the JSON API depends on the navigation model, which is **Open (D-38)**. Both must remain possible | TR-119, D-38 |
| PA-3.5 | Clients branch on the stable `error.code`, **never** on `message` | `api-spec-v1.0.md` E-1 |
| PA-3.6 | Pagination is offset-based in V1; keyset is **Open — technical decision (OT-08)** | OT-08 |
| PA-3.7 | No client may rely on an unbounded result set; `per_page` is clamped server-side | TR-30, TR-47 |

---

## 4. Authentication boundary

Summarised here; specified in
[`platform-auth-v1.0.md`](platform-auth-v1.0.md).

| ID | Rule | Source |
| --- | --- | --- |
| PA-4.1 | **One Customer identity** across both surfaces | SUR-3, C-32 |
| PA-4.2 | **One session concept, two transports** — cookie and bearer token, resolving to the same server-side record | TD-02, S-1 |
| PA-4.3 | Approved methods are **Google and email OTP only**. No passwords. No Apple | D-48 |
| PA-4.4 | **Telegram is not an authentication provider in V1** | AI-4, TG-4 |
| PA-4.5 | Telegram-supplied context is **validated server-side** and establishes a **surface, not an identity** | TR-112, TR-113 |
| PA-4.6 | The platform must support attaching Telegram later as an additional provider **without restructuring**. **D-33 is not decided here** | TR-114, D-33 |
| PA-4.7 | The Mini App **must not trust client-provided identity claims** | TR-112 |

---

## 5. Navigation boundary

| ID | Rule | Source |
| --- | --- | --- |
| PA-5.1 | The Web uses browser history as the navigation model | `platform-navigation-v1.0.md` |
| PA-5.2 | The Mini App binds to the **host Back button** and keeps **one** navigation stack | TMA research §22 |
| PA-5.3 | **No competing navigation controls.** The Mini App never renders a second back affordance | UR-12 |
| PA-5.4 | The Mini App navigation model is **Open (D-38)** | D-38 |

---

## 6. Media handling

| ID | Rule | Source |
| --- | --- | --- |
| PA-6.1 | Responsive sources; a phone never downloads a desktop-sized image | DSN-8.15, MOB-4 |
| PA-6.2 | Fixed aspect ratios per role; space reserved before load so nothing shifts | DSN-8.12, DSN-11.7 |
| PA-6.3 | Below-the-fold images load lazily | DSN-8.14 |
| PA-6.4 | A missing image is a **designed placeholder**, never a broken-image icon, and never changes the card height | DSN-11.20, DSN-11.22 |
| PA-6.5 | Every meaningful image carries descriptive alt text; placeholders are decorative | DSN-11.11, DSN-11.23 |
| PA-6.6 | Uploaded media is re-encoded to bounded display sizes; the original is never served to a phone | DSN-11.8 |
| PA-6.7 | Media is not served from the document root and is not executable | TR-81 |
| PA-6.8 | Storage, formats, dimensions and caps are **Open (D-25)** | D-25 |

---

## 7. Sharing

| ID | Rule | Source |
| --- | --- | --- |
| PA-7.1 | What is shared is the **canonical URL** — never a session, filter, tracking parameter or referral code | IAR-36, TR-201 |
| PA-7.2 | A link shared from either surface **resolves on the Web** for any recipient | TG-6, TR-118 |
| PA-7.3 | Sharing requires **no account** and is never recorded against a person | IAR-40, GS-1 |
| PA-7.4 | Web: Web Share API where available, copy-link fallback otherwise | §12 |
| PA-7.5 | Mini App: Telegram share, which is itself implemented over a Telegram link | §9 of the Mini App spec |

---

## 8. Maps

| ID | Rule | Source |
| --- | --- | --- |
| PA-8.1 | The map is **supplementary**. Address, Area and landmark are always present as text | MOB-5, A11-12.1 |
| PA-8.2 | The map **must not block initial content** and is never render-blocking | DSN-12.6 |
| PA-8.3 | An "Open in maps" external action is always available where coordinates exist | C-10 |
| PA-8.4 | A failed map degrades to address plus the external action, with no error shouting | CMP §20 |
| PA-8.5 | A Branch with no coordinates shows **no empty map frame** | UXP-7.2 |
| PA-8.6 | **No map vendor is selected here. D-21 is open** and the design must tolerate either an embed or no embed at all | D-21 |

---

## 9. Browser and device capabilities

| Capability | Use | Fallback |
| --- | --- | --- |
| Geolocation | Nearby only (C-07) | Area browsing; **no nag, no repeat prompt** |
| Web Share API | Share control | Copy link |
| Clipboard | Copy link | A selectable text field |
| `tel:` | Call action | The number shown as readable text |
| `mailto:` | Contact | The address shown as text |
| External maps | Directions | Address and landmark text |
| `prefers-reduced-motion` | Motion suppression | Motion is minimal by default anyway |
| `prefers-color-scheme` | **Not used in V1** — dark mode is **Open (D-19)** | — |

| ID | Rule |
| --- | --- |
| PA-9.1 | **Every enhanced capability has a graceful fallback.** A feature that cannot degrade is not added |
| PA-9.2 | Capability is **detected**, never inferred from a user-agent string |
| PA-9.3 | Coordinates are used in-request and **never stored, never logged, never sent to analytics** (TR-201) |

---

## 10. Platform decision register — `PD-xx`

**These are platform-local, implementation-level decisions introduced by
this phase.** They are **not** part of the global `D-xx` register and must
never be cited as if they were. Where a candidate decision would change
product behaviour, it is **not** taken here — it is raised as an owner
decision instead (see §11).

---

### PD-01 — One application, one deployment, two surfaces

| | |
| --- | --- |
| **Decision** | Both surfaces are served by one application from one deployment. No separate Mini App build target, codebase, pipeline or release train. |
| **Reason** | D-49 approved exactly this direction; a fork would duplicate every rule. |
| **Impact** | Shared code is the default; a change reaches both surfaces at once. |
| **Alternatives** | A separate Mini App SPA consuming the API — rejected: duplicates presentation and drifts from product rules. |
| **Reversibility** | Low cost to keep; high cost to reverse later. |
| **Related** | D-49, D-15 · TRD §23, TR-109 |

### PD-02 — A single named surface adapter

| | |
| --- | --- |
| **Decision** | All surface-specific behaviour lives behind one adapter boundary, corresponding to the `Bulbula\Telegram` namespace for Mini App concerns. Product code never inspects the surface. |
| **Reason** | TR-110 requires confinement; scattered host calls are the main failure mode in Mini App codebases. |
| **Impact** | Testable surface behaviour; a host change has a bounded blast radius. |
| **Alternatives** | Inline host calls at point of use — rejected as unmaintainable and untestable. |
| **Reversibility** | High — the boundary can be widened if a concern is genuinely missing. |
| **Related** | D-49 · TR-110, TR-111, `architecture.md` |

### PD-03 — Web renders server-side HTML with progressive enhancement

| | |
| --- | --- |
| **Decision** | The Web surface serves complete HTML; JavaScript enhances and is never required for core discovery. |
| **Reason** | MOB-5 and C-37 require it; SEO and low-end devices both depend on it. |
| **Impact** | Sets the baseline for every component; constrains the eventual D-16 answer but does not make it. |
| **Alternatives** | Client-rendered SPA — already rejected by D-16's framing. |
| **Reversibility** | Low — this is foundational. |
| **Related** | D-16, D-49, D-52 · MOB-5, SEO-1, TD-01 |

### PD-04 — The Mini App is served the same pages; its navigation model stays open

| | |
| --- | --- |
| **Decision** | The Mini App receives the same server-rendered pages as the Web, adapted by the adapter. Whether in-app navigation is full page loads or fragment swaps is **not decided** and remains D-38. The JSON API remains available to it. |
| **Reason** | TR-119 requires both models to remain possible; TR-24 keeps the API sufficient. |
| **Impact** | No work done now forecloses either answer. |
| **Alternatives** | Choosing a model now — refused: that is D-38, an open decision. |
| **Reversibility** | Full. |
| **Related** | D-38, D-49 · TR-24, TR-119 |

### PD-05 — Mini App session tokens are never kept in `localStorage`

| | |
| --- | --- |
| **Decision** | The bearer token for the Mini App is not stored in `localStorage`. The session is re-established from validated launch context plus the server-side session record. |
| **Reason** | Current platform guidance records that WebView `localStorage` may be wiped on iOS and some desktop builds, producing blank screens and lost sessions. |
| **Impact** | Session durability depends on the server record, which is already the single source of truth (S-1). |
| **Alternatives** | `localStorage` — rejected on durability. Telegram `CloudStorage`/`SecureStorage` — rejected for V1: version-gated and not needed for a server-held session. |
| **Reversibility** | High. |
| **Related** | D-48 · TD-02, TR-116, `auth-identity.md` §6.3 |

### PD-06 — Host capability detection, not a hard Bot API floor

| | |
| --- | --- |
| **Decision** | The Mini App detects host capability per feature rather than refusing to run below a fixed Bot API version. Every version-gated host feature has a documented fallback. |
| **Reason** | Bot API features are gated by the user's Telegram client version (Back button 6.1+, safe-area insets 8.0+, theme fields across 6.1–7.10). A hard floor would exclude users for cosmetic reasons. |
| **Impact** | The Mini App remains usable on older clients with reduced chrome integration. |
| **Alternatives** | A minimum version gate — rejected: excludes users on low-end and older devices, contrary to MOB-3. |
| **Reversibility** | High. |
| **Related** | D-49 · TR-109, PRD MOB-3 |

### PD-07 — Telegram theme is mapped onto the semantic token tier

| | |
| --- | --- |
| **Decision** | Host theme parameters are mapped onto Bulbula's existing semantic tokens. No second palette is shipped. |
| **Reason** | DSN-2.25 already requires this; it is also what lets the Mini App respect the host theme without deciding dark mode. |
| **Impact** | Contrast must be re-verified against host themes (A11-18.4). |
| **Alternatives** | A dedicated Telegram palette — rejected: a second palette to maintain and a de-facto dark-mode decision. |
| **Reversibility** | High. |
| **Related** | D-19, D-53 · DSN-2.25, A11-18.4 |

### PD-08 — One navigation stack, bound to the host Back button

| | |
| --- | --- |
| **Decision** | The Mini App maintains one navigation stack and binds the host Back button to it. Bulbula renders no back control of its own. |
| **Reason** | UR-12; a custom back arrow competes with both Telegram navigation and the Android hardware back button. |
| **Impact** | Overlays and sheets become stack layers that Back closes. |
| **Alternatives** | An in-app back button — rejected outright. |
| **Reversibility** | High. |
| **Related** | D-38, D-49 · UR-12, A11-18.1 |

### PD-09 — External links leave through host methods, not `window.open`

| | |
| --- | --- |
| **Decision** | In the Mini App, external links are opened through the host's link-opening method, and Telegram links through the host's Telegram-link method. Direct `window.open` is not used. |
| **Reason** | Host methods keep the Mini App open and behave correctly across Telegram clients; current platform documentation also notes that link-opening may only be invoked in response to a user interaction. |
| **Impact** | Every outbound action is an explicit, user-initiated call through the adapter. |
| **Alternatives** | Plain anchors — unreliable inside the webview. |
| **Reversibility** | High. |
| **Related** | D-49 · TR-110 |

### PD-10 — URL state, application state and ephemeral UI state are separated

| | |
| --- | --- |
| **Decision** | Each piece of client state is classified as URL-addressable, server-held application state, or ephemeral UI state, and is treated accordingly on both surfaces. |
| **Reason** | Prevents accidental SPA complexity and keeps Back, refresh and sharing predictable. |
| **Impact** | Filters, sort and pagination are URL state; identity is application state; sheets and toasts are ephemeral. |
| **Alternatives** | Ad-hoc client state — rejected: breaks Back and sharing. |
| **Reversibility** | Moderate. |
| **Related** | D-38 · IAR-28…IAR-35 |

### PD-11 — Clients branch on stable error codes

| | |
| --- | --- |
| **Decision** | Client error handling keys off the API's stable `error.code`, never the human-readable `message`, and never the HTTP status alone where a code exists. |
| **Reason** | `api-spec-v1.0.md` E-1 already fixes this; the platform layer must honour it. |
| **Impact** | Messages can be rewritten without breaking behaviour. |
| **Alternatives** | Parsing messages — rejected. |
| **Reversibility** | High. |
| **Related** | — · `api-spec-v1.0.md` §1.6 |

### PD-12 — No service worker, no offline storage, no background sync in V1

| | |
| --- | --- |
| **Decision** | V1 ships no service worker, no local application database, no offline queue and no background sync. Network failures are handled in the moment. |
| **Reason** | Bulbula V1 is explicitly not offline-first; an offline queue would create write-ordering and privacy problems for no approved capability. |
| **Impact** | Failures must be reported honestly and recoverably (§14 of the error-handling spec). |
| **Alternatives** | A service worker cache — rejected for V1: adds an invalidation surface and a privacy risk on shared devices. |
| **Reversibility** | High. |
| **Related** | — · `performance-and-caching.md`, TD-05 |

### PD-13 — Browser support is capability-based with a named baseline

| | |
| --- | --- |
| **Decision** | Support is defined by capability with a small named baseline — current Chromium, Firefox and Safari, common Android browsers and the Android WebView — rather than a numeric matrix. |
| **Reason** | MOB-3 targets mid- and low-range Android; a version matrix would be both unverifiable and misleading. |
| **Impact** | The baseline experience is HTML and CSS; enhancements are feature-detected. |
| **Alternatives** | An explicit numeric support matrix — rejected as an unsupportable claim. |
| **Reversibility** | High. Exact version policy remains **Open — implementation detail**. |
| **Related** | D-52 · PRD MOB-3 |

### PD-14 — Analytics is recorded server-side; no client tracking SDK

| | |
| --- | --- |
| **Decision** | Product analytics is derived from server-side request and event recording. No third-party client analytics or tracking SDK is embedded on either surface. |
| **Reason** | PRD §21 and TR-202 forbid identifying Guests; a client tracking SDK is a third-party processor relationship nobody has approved. |
| **Impact** | Analytics granularity is bounded by what the server already sees. |
| **Alternatives** | A client analytics library — rejected on privacy and on payload budget. |
| **Reversibility** | High. Granularity and retention remain **Open (D-27)**. |
| **Related** | D-27, D-42 · TR-201, TR-202 |

### PD-15 — Maps are static-first and deferred

| | |
| --- | --- |
| **Decision** | Location is rendered as text plus, at most, a deferred static preview; any interactive embed loads only on explicit user action. |
| **Reason** | MOB-5 requires usability without a map; DSN-12.6 forbids render-blocking embeds. |
| **Impact** | The profile page is not hostage to a third-party script. |
| **Alternatives** | An eager interactive embed — rejected on performance, privacy and D-21 being open. |
| **Reversibility** | High. |
| **Related** | D-21 · MOB-5, L-19 |

### PD-16 — Sponsored rendering is shared, never surface-specific

| | |
| --- | --- |
| **Decision** | Sponsored selection, labelling, containment and separation are implemented once in shared code. The platform layer may not alter them. |
| **Reason** | Commercial integrity must not vary by surface; a weaker disclosure on a smaller screen is the exact failure mode regulators describe. |
| **Impact** | Narrow viewports shrink the cards, never the separation or the label. |
| **Alternatives** | Surface-tuned ad density — refused; it would also be a product change, not a platform one. |
| **Reversibility** | Low — intentionally rigid. |
| **Related** | D-10, D-39 · PRD §19, LB-1…LB-8, PL-1…PL-7 |

---

## 11. Candidate decisions deliberately **not** taken

Each of these would change product behaviour, so under the brief's rule
they are raised rather than adopted.

| Candidate | Why it was not taken | Status |
| --- | --- | --- |
| Use Telegram identity to sign a Customer in | Adds a third identity provider; D-48 lists Google and email only | **Open (D-33)** — owner decision |
| Send notifications through Telegram | A new channel beyond email | **Open** — owner decision; D-24 is email-only |
| Use Telegram `CloudStorage` for Saved items | Would fork the Saved model away from the identity | Refused — contradicts C-34 and SUR-3 |
| Choose full page loads or fragment swaps | That is D-38 | **Open (D-38)** |
| Select a maps vendor | That is D-21 | **Open (D-21)** |
| Set a dark-mode product feature from host theme support | That is D-19 | **Open (D-19)** |
| Fix numeric performance budgets as guarantees | Only `[P]` values exist | `[P]`, see [`platform-performance-v1.0.md`](platform-performance-v1.0.md) |
| Choose the CSP `frame-ancestors` value | Security decision | **Open — technical decision (OT-05)** |

---

## 12. Web surface summary

Specified in [`web-platform-v1.0.md`](web-platform-v1.0.md).

| Aspect | Position |
| --- | --- |
| Rendering | Server-rendered HTML, progressive enhancement (PD-03) |
| API use | **Never calls its own HTTP API** (TD-01) |
| Navigation | Browser history and real links |
| Session | Cookie, with CSRF protection on state-changing requests |
| SEO | **Yes** — the only SEO surface |
| Console | Hosts `/ops/*` |
| Share | Web Share API with copy-link fallback |

---

## 13. Mini App surface summary

Specified in [`telegram-mini-app-v1.0.md`](telegram-mini-app-v1.0.md).

| Aspect | Position |
| --- | --- |
| Runtime | Telegram webview, same application (PD-01) |
| Launch context | Validated server-side; establishes a **surface, not an identity** |
| Navigation | Host Back button, one stack (PD-08) |
| Viewport | Host stable height plus both safe-area insets |
| Theme | Host parameters mapped onto semantic tokens (PD-07) |
| Session | Bearer token, not in `localStorage` (PD-05) |
| SEO | **No** |
| Console | **None** |

---

## 14. Analytics and instrumentation boundary

Four distinct concerns, deliberately separated (full rules in
[`platform-performance-v1.0.md`](platform-performance-v1.0.md) §9 and the
privacy constraints in §19 below).

| Concern | What it is | Where it lives | Constraint |
| --- | --- | --- | --- |
| **Product events** | Capability usage in aggregate — searches run, zero-result queries, profile views, contact-action taps | Server-side, from requests already handled | **Never identifies a Guest** (TR-202) |
| **Technical telemetry** | Errors, latency, failure rates | Application logs with a correlation id | No personal data; `request_id` only (TR-12) |
| **Campaign measurement** | Sponsored impressions and clicks | Server-side, per Campaign and Placement | **Reporting only**; never feeds ranking or price (TR-74, MS-2) |
| **Error reporting** | Client-visible failures | Surfaced to the User; recorded server-side | No stack traces to the client (TR-144) |

| ID | Rule | Source |
| --- | --- | --- |
| PA-14.1 | **No third-party client tracking SDK on either surface** | PD-14, PRD §21 |
| PA-14.2 | No behavioural profiling, no cross-session Guest identifier, no fingerprinting | TR-202 |
| PA-14.3 | **Location is never recorded.** Coordinates are used in-request for Nearby and discarded | TR-201 |
| PA-14.4 | Analytics is read from rollups, never raw events | AN-2 |
| PA-14.5 | Granularity and retention are **Open (D-27)** | D-27 |
| PA-14.6 | A metric is not collected merely because another platform collects it | PRD §21 |

---

## 15. Sponsored content at the platform layer

| ID | Rule | Source |
| --- | --- | --- |
| PA-15.1 | Sponsored selection and rendering are **shared**; no platform-specific sponsored ranking logic exists | PD-16 |
| PA-15.2 | The label, the container and the **spatial separation** from organic results are identical on both surfaces | LB-1…LB-5, UR-02 |
| PA-15.3 | **Sponsorship never modifies organic ranking** | D-39, PL-6 |
| PA-15.4 | The same Business data is used for sponsored and organic rendering | SUR-4 |
| PA-15.5 | Inventory and density rules are shared and read from configuration | PL-3, PL-4 |
| PA-15.6 | **Platform chrome must never make sponsorship ambiguous** — a host header, theme or inset may not obscure, crop or recolour the label | LB-2, LB-4 |
| PA-15.7 | An unsold Placement **collapses entirely** on both surfaces | PL-5, C-01 |
| PA-15.8 | The label is part of the accessible name on both surfaces | LB-7 |

---

## 16. Accessibility at the platform layer

The complete requirements are in
[`../20-ux-ui/accessibility-v1.0.md`](../20-ux-ui/accessibility-v1.0.md)
and are **not restated**. Platform-level obligations only:

| ID | Requirement | Source |
| --- | --- | --- |
| PA-16.1 | Full keyboard operation on the Web, including the console | A11 §2 |
| PA-16.2 | Touch targets and spacing hold on both surfaces at the narrowest viewport | A11 §4 |
| PA-16.3 | **Focus is restored** after navigation, after an overlay closes, and after authentication returns | A11-3.6, A11-11.2 |
| PA-16.4 | Screen-reader state is preserved across history navigation; dynamic content is reachable in reading order | A11-10.10 |
| PA-16.5 | Browser history behaviour must not strand assistive technology — a Back that changes content announces it | A11-9.2 |
| PA-16.6 | Host controls must not reduce accessibility; Bulbula adds no gesture the host does not support | A11-18.5 |
| PA-16.7 | Reduced-motion preference is honoured on both surfaces | A11-13.1 |
| PA-16.8 | Viewport and safe-area handling must never obscure a focused control | A11-3.3, WCAG 2.4.11 |
| PA-16.9 | **An external-link transition is announced**, so a User knows they are leaving — this matters more in the Mini App, where leaving the host is a larger context change | WCAG 3.2.5, A11-12 |
| PA-16.10 | Telegram-specific capability may not reduce accessibility below the Web baseline | A11-1.3 |

---

## 17. Offline behaviour

| ID | Rule |
| --- | --- |
| PA-17.1 | **Bulbula V1 is not an offline-first application and makes no offline claim** |
| PA-17.2 | No local application database, no offline sync, no write queue (PD-12) |
| PA-17.3 | Network loss during navigation, failed Save, failed Review, failed Report and partial asset loading are handled gracefully and honestly |
| PA-17.4 | **The User must always be able to tell what completed and what did not** |
| PA-17.5 | Full behaviour in [`platform-error-handling-v1.0.md`](platform-error-handling-v1.0.md) §8 |

---

## 18. Security boundary

The complete security specification belongs to `docs/50-security/` and is
**not written here**. Platform-specific constraints only:

| ID | Constraint | Source |
| --- | --- | --- |
| PA-18.1 | **HTTPS everywhere**; HTTP redirects to HTTPS | PS-5 |
| PA-18.2 | Session handling is server-side; the client holds a reference, never a claim | S-1 |
| PA-18.3 | **No client-trusted Telegram identity** | TR-112, TR-113 |
| PA-18.4 | External navigation is explicit and user-initiated | PD-09 |
| PA-18.5 | The design must remain **CSP-compatible**: no inline event handlers, no `eval`, no injected third-party script | `architecture.md` |
| PA-18.6 | The `frame-ancestors` policy admitting the Telegram host **must not weaken the Web policy** — **Open — technical decision (OT-05)** | OT-05 |
| PA-18.7 | CSRF protection on state-changing Web requests | `trd-v1.0.md` §19 |
| PA-18.8 | **XSS-safe rendering**: output is escaped by default; Business-supplied text is never rendered as markup | TR-144 |
| PA-18.9 | **No sensitive data in URLs** — no token, no session, no OTP, no personal identifier | TR-201 |
| PA-18.10 | **No secrets in frontend code**, including no bot token and no API key | TR-07 |
| PA-18.11 | Safe storage: no personal data in `localStorage`; no session token in client storage (PD-05) | PD-05 |
| PA-18.12 | Safe error display: no stack trace, SQL, file path, internal identifier or infrastructure detail | TR-144, E-2 |

---

## 19. Privacy boundary

The complete privacy specification belongs to `docs/55-privacy/` and is
**not written here**. Platform-specific constraints only:

| ID | Constraint | Source |
| --- | --- | --- |
| PA-19.1 | **Data minimisation**: the platform collects nothing it does not need | PRD §21 |
| PA-19.2 | **No behavioural profiling and no hidden tracking** | TR-202, PD-14 |
| PA-19.3 | **Location only for the immediate Nearby interaction**, then discarded — never stored, logged or sent to analytics | TR-201, C-07 |
| PA-19.4 | **No personal data in any public cache**; authenticated responses are `no-store` | TR-120, TR-123 |
| PA-19.5 | No unnecessary persistence of browser or device information | PRD §21 |
| PA-19.6 | Guest browsing sets no identifying cookie | GS-1 |
| PA-19.7 | Whether any cookie notice is required is **PENDING COUNSEL** | L-5, L-6 |

---

## 20. Surface-specific differences — authoritative table

| Behaviour | Web | Telegram |
| --- | --- | --- |
| **Rendering** | Server-rendered HTML | Web runtime using the same shared frontend behaviour |
| **Main navigation** | Browser and Web navigation | Shared product navigation plus host integration |
| **Back** | Browser history | Telegram Back button bound to one internal stack |
| **Theme** | Bulbula semantic tokens | Telegram theme adapted into the same tokens |
| **Safe area** | Browser environment | Telegram host insets (safe area and content safe area) |
| **Authentication** | Google first, email OTP second; cookie session | Email OTP first, Google via external-browser hand-off; bearer session |
| **Maps** | Browser or device maps | External maps hand-off through the host |
| **Share** | Web Share API with copy-link fallback | Telegram-aware sharing of the canonical URL |
| **SEO** | **Yes** | **No** |
| **Deep links** | Canonical Web URLs | Telegram deep-link entry mapped to the same canonical destinations |
| **Offline** | **No** | **No** |
| **Backend** | Same | Same |
| **Domain model** | Same | Same |
| **Business rules** | Same | Same |
| **Sponsored rules** | Same | Same |
| **Operations console** | Yes | **No** |

| ID | Rule |
| --- | --- |
| PA-20.1 | **This table is not a licence to duplicate product logic.** Every row is either chrome, transport or a documented capability exception |
| PA-20.2 | No row changes what a Guest or a Customer can do |

---

## 21. Traceability

| Source | Where it lands |
| --- | --- |
| D-49 shared frontend direction | PA-1, PD-01, PD-02 |
| D-15 two V1 surfaces | PA-1.3 |
| D-48 provider set | PA-4.3 |
| D-33 Telegram identity | PA-4.6 — **not decided** |
| D-38 navigation model | PA-5.4, PD-04 — **not decided** |
| D-16 / D-17 frontend approach | PD-03 constrains the baseline, does not choose |
| D-39 ad integrity | PA-15 |
| TD-01 Web never calls its API | PA-3.1 |
| TD-02 one session, two transports | PA-4.2, PD-05 |
| TR-109…TR-119 Mini App architecture | PA-1, PA-2.2, §13 |
| C-01…C-18, C-30…C-40 | Both surfaces, per the parity rules in the shared client contract |
| C-19…C-29 | Web only |
| C-37 | Web only |

---

## 22. Platform research used

Verified against current official Telegram Mini Apps platform
documentation and the platform's own developer reference during this
phase. Only findings that materially changed the specification are
recorded.

| # | Finding | Effect |
| --- | --- | --- |
| 1 | `initData` must be validated server-side; `initDataUnsafe` is never trustworthy. Validation derives a secret with HMAC-SHA256 over the bot token with the literal key `WebAppData`, compares the hash in constant time, and rejects payloads outside a freshness window on `auth_date`. Third-party validation using a bot id is also offered | Fixes the validation requirement in the Mini App spec §3 and confirms TR-112's freshness bound |
| 2 | `viewportHeight` updates continuously during drag and is explicitly unsuitable for pinning elements; `viewportStableHeight` is the correct basis, exposed as a CSS variable | Confirms UR-12; makes "size from the stable height" a concrete, checkable rule |
| 3 | `safeAreaInset` **and** `contentSafeAreaInset` are distinct, are **Bot API 8.0+**, and are exposed as CSS variables | Drove PD-06: insets need a static-padding fallback on older clients |
| 4 | `BackButton` is **Bot API 6.1+** | Drove PD-06 and the fallback note in the Mini App spec §6 |
| 5 | **Since Bot API 7.0 `openTelegramLink` no longer closes the Mini App**; before 7.0 it did. `openLink` opens an external browser without closing the Mini App, and may only be called in response to a user interaction | Drove PD-09 and the sharing rules — a share must not silently close the app on older clients |
| 6 | There is no native direct-share method; sharing a URL is implemented over a Telegram share link | Share is specified in terms of the canonical URL, not a platform primitive |
| 7 | Theme parameters are version-gated across Bot API 6.1 to 7.10 (`secondary_bg_color` 6.1+, a block of fields 7.0+, `section_separator_color` 7.6+, `bottom_bar_bg_color` 7.10+) and are exposed as `--tg-theme-*` CSS variables, with `colorScheme` light or dark | Drove PD-07's mapping approach: map what exists, fall back to Bulbula tokens for what does not |
| 8 | WebView `localStorage` may be wiped on iOS and some desktop builds; `CloudStorage` is 6.9+, `DeviceStorage` and `SecureStorage` are 9.0+ | **Drove PD-05** — no session token in `localStorage` |
| 9 | `ready()` hides the host loading placeholder; if never called, the placeholder persists until full page load | Drove the initialisation sequence in the Mini App spec §2 |
| 10 | Direct-link Mini Apps launch as `https://t.me/<bot>/<app>?startapp=<param>`; a launch parameter is trivially forgeable and must be read from validated context server-side | Drove the deep-link rules in the Mini App spec §10 |
| 11 | On mobile the Mini App opens inside a draggable bottom sheet, minimised by default, expandable programmatically; the viewport is unstable mid-drag | Confirms the stable-height rule and the "no resize work during drag" guidance |

**Sources.** Telegram's official Mini Apps platform documentation and Bot
API reference, and the Telegram Mini Apps platform developer
documentation. Browser-platform behaviour follows WHATWG and MDN
conventions; no normative browser claim in this set rests on a blog post.

---

## 23. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-16 | Frontend JavaScript approach | **Open — implementation detail** |
| D-17 | View layer | **Open — implementation detail** |
| D-19 | Dark mode as a V1 feature | Owner decision — not taken here |
| D-20 | Host resource limits and MariaDB tuning | **Open — technical decision** |
| D-21 | Maps provider and fallback | Owner decision — not taken here |
| D-25 | Media storage, sizes and limits | **Open — implementation detail** |
| D-27 | Analytics granularity and retention | Owner decision — not taken here |
| D-33 | Telegram identity relationship | Owner decision — **not taken here** |
| D-38 | Mini App navigation model | **Open — implementation detail** |
| D-45 | Staff authentication strength | **Open — technical decision** |
| OT-01 | Session lifetime, idle timeout, rotation | **Open — technical decision** |
| OT-05 | CSP `frame-ancestors` admitting the Telegram host | **Open — technical decision** |
| OT-08 | Pagination style for public lists | **Open — technical decision** |
| — | Exact browser version policy | **Open — implementation detail** (PD-13) |
| L-5 / L-6 | Cookie notice requirement | **PENDING COUNSEL** |
| L-19 | Maps terms of use | **PENDING COUNSEL** |

---

## Decision references

D-10, D-15, D-16, D-17, D-19, D-20, D-21, D-24, D-25, D-27, D-33, D-38,
D-39, D-42, D-45, D-48, D-49, D-52, D-53.
