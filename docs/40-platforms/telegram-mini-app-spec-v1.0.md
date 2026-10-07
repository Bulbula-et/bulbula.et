# Telegram Mini App Specification

| | |
| --- | --- |
| **Document** | Telegram Mini App Specification — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The Telegram Mini App surface: how it launches, what the host
owns, how context is validated, how authentication works inside an embedded
webview, and what Bulbula must never do inside someone else's chrome.

**Scope.** Surface behaviour and the adapter. Screens, components, flows
and wording are shared and are not restated here.

**Governed by** [`platform-strategy-v1.0.md`](platform-strategy-v1.0.md).

**This document selects no technology and resolves no open decision.**
D-33 (Telegram identity) and D-38 (navigation model) remain open, and this
specification is written so that either answer to each can be adopted
without rework.

---

## 1. What the Mini App is

| ID | Statement | Source |
| --- | --- | --- |
| TMA-1.1 | The Mini App is **the same application served to a Telegram webview** — not a separate build target, not a separate codebase, not a port | TR-109, R-16, D-49 |
| TMA-1.2 | It is a **first-class V1 surface**, launching simultaneously with the Web | SUR-7, D-15 |
| TMA-1.3 | It runs on the **same backend, the same domain model and the same product rules** | SUR-2, PLT-1.2 |
| TMA-1.4 | It delivers every capability except C-37 (SEO) and C-19…C-29 (operations) | `scope-v1.md` §1.5 |
| TMA-1.5 | It **must work within Telegram's viewport, navigation and theme conventions** | TG-2 |
| TMA-1.6 | It is **not an SEO surface** and is excluded from indexing | TG-7, TR-117 |

---

## 2. The host owns these — Bulbula does not

The single most common way to build a bad Mini App is to re-implement what
the host already provides. The result is two back buttons, a double header,
and content under the system bars.

| Owned by Telegram | Bulbula's obligation |
| --- | --- |
| The header and its title | Suppress Bulbula's own wordmark row (A1) |
| The **Back button** | **Never render a second back control.** Bind to the host Back button (UR-12, A11-18.1) |
| Swipe-to-close | Do not intercept it; do not build a competing dismiss gesture |
| The bottom bar | Keep interactive content clear of it |
| Safe-area insets | Respect **both** the safe-area inset and the content safe-area inset (UR-12, A11-18.3) |
| Viewport height | Size layout from the **stable viewport height**, never the raw window height (UR-12, A11-18.2) |
| Theme | Adopt host theme parameters **through the semantic token tier** (DSN-2.25, A11-18.4) |
| Link opening | Hand off external links, maps and contact actions to the host (A4) |
| Sharing | Use the host share mechanism (A2) |

| ID | Rule | Source |
| --- | --- | --- |
| TMA-2.1 | **No second back button, under any circumstance** | UR-12 |
| TMA-2.2 | Layout is sized from the host stable viewport so the on-screen keyboard does not displace controls | UR-12, A11-18.2 |
| TMA-2.3 | Both insets are applied; **nothing interactive sits under host chrome** | A11-18.3 |
| TMA-2.4 | Bottom sheets sit above the safe-area inset and above the host bottom bar | DSN-8.11 |
| TMA-2.5 | Bulbula **does not animate anything the host already animates** | DSN-7.8 |
| TMA-2.6 | Host chrome is **not** reproduced, imitated or supplemented |

---

## 3. Context validation

| ID | Rule | Source |
| --- | --- | --- |
| TMA-3.1 | Telegram-supplied context **must be validated server-side before any trust is placed in it**, using the documented signature check | TR-112, R-10, TG-3 |
| TMA-3.2 | Validation includes a **freshness bound** on the payload | TR-112 |
| TMA-3.3 | **A client assertion is never trusted.** Context arriving from the browser is unverified input until the server says otherwise | TMA-3.1 |
| TMA-3.4 | **Validated context establishes a surface, not an identity** | TR-113 |
| TMA-3.5 | Validation failure degrades to the **unauthenticated Guest experience**, which is fully functional. It is not an error screen and not a dead end | GS-1, UFL-0.4 |
| TMA-3.6 | Context is marked on the request by the surface adapter and is not inspected elsewhere | `architecture.md` Surface, PLT-2.4 |
| TMA-3.7 | No Telegram-supplied personal data is stored beyond what the surface requires, and none is sent to analytics | TR-201, TR-202 |

---

## 4. Identity and authentication

This is the sharpest constraint on this surface, and the one most likely to
be got wrong by assumption.

| ID | Rule | Source |
| --- | --- | --- |
| TMA-4.1 | **A Telegram user is not an approved login provider.** The provider set is Google and email OTP | D-48, TG-4, AI-4 |
| TMA-4.2 | Telegram must **not be silently added** to the provider set | D-33 |
| TMA-4.3 | How Telegram context relates to a Bulbula Customer identity is **Open (D-33)** | TG-4, TR-114 |
| TMA-4.4 | The design **must support attaching Telegram later as an additional provider identity without restructuring** | TR-114 |
| TMA-4.5 | **Sign-in must account for OAuth friction inside embedded webviews.** A plain redirect must not be assumed to work | TG-5, TR-115, R-23 |
| TMA-4.6 | **Email OTP is presented first** inside the Mini App; Google remains available and is presented second. This is an ordering difference, not an availability difference | UR-14, A3, SPM-3.1 |
| TMA-4.7 | Session transport is a **bearer token**, so the surface does not depend on third-party cookie behaviour | TD-02, TR-116 |
| TMA-4.8 | The session resolves to the **same server-side record** as a Web session | S-1 |
| TMA-4.9 | **No password exists** on this surface, as on the Web | D-48 |
| TMA-4.10 | The OTP field accepts paste, uses a numeric keypad and carries the one-time-code autocomplete token | A11-8.3, A11-8.4 |
| TMA-4.11 | **No screen implies that a Telegram account is, or will become, a Bulbula login** | D-33, CDN-1.5 |

**What "Open (D-33)" means operationally.** V1 ships Mini App
authentication using Google and email OTP, ordered per TMA-4.6. Validated
Telegram context is recorded as a surface signal. If D-33 later approves
Telegram as a provider, it attaches to the existing identity model as an
additional provider record — which is exactly what TMA-4.4 requires to be
possible now.

---

## 5. Navigation

| ID | Rule | Source |
| --- | --- | --- |
| TMA-5.1 | The **navigation model — full page loads versus fragment swaps — is Open (D-38)**. Both must remain possible | TR-119, D-38 |
| TMA-5.2 | Whichever model is chosen, the **host Back button drives it**, closing the topmost layer first: overlay, then sheet, then page | IAR-35, UR-12 |
| TMA-5.3 | Opening a modal or sheet pushes a layer that Back closes rather than leaving the view | IAR-31 |
| TMA-5.4 | Breadcrumbs matter **more** here than on the Web, because there is no browser chrome: the immediate parent is always retained | IAR-20 |
| TMA-5.5 | The discovery axes — Categories, Areas, Nearby, Saved — remain visible and are **never collapsed into a menu** | IAR-9, UR-06 |
| TMA-5.6 | Navigation items appear in the **same relative order** as on the Web | IAR-10, WCAG 3.2.3 |
| TMA-5.7 | **No infinite scroll**, as on the Web | IAR-30 |
| TMA-5.8 | A help and contact route is in a consistent place, reached from the account menu | IAR-11, WCAG 3.2.6 |

---

## 6. Sharing and deep links

| ID | Rule | Source |
| --- | --- | --- |
| TMA-6.1 | Sharing uses the **host share mechanism** (A2) | SUR-5 |
| TMA-6.2 | What is shared is the **canonical URL** — never a session, filter, tracking parameter or referral code | IAR-36, TR-201 |
| TMA-6.3 | **A link shared out of the Mini App must resolve on the Web** for recipients who are not Telegram users | TG-6, TR-118, C-17 |
| TMA-6.4 | Link previews carry title, description and image metadata | SEO-11 |
| TMA-6.5 | Sharing requires **no account** and is never recorded against a person | IAR-40, GS-1 |
| TMA-6.6 | Inbound deep links resolve to the same destinations as on the Web: Home, Search with filters, Categories, Areas, Category × Area, Business profile, static pages | IAR §5.1 |
| TMA-6.7 | A transient state — an open sheet, autocomplete, a toast — is **not** deep-linkable on either surface | IAR §5.1 |

---

## 7. Theme

| ID | Rule | Source |
| --- | --- | --- |
| TMA-7.1 | Host theme parameters are mapped **onto the semantic token tier**, not shipped as a second palette | DSN-2.25 |
| TMA-7.2 | This is what makes the Mini App respect the host theme **without making dark mode a V1 product decision** (D-19 stays open) | D-19, DSN-2.22 |
| TMA-7.3 | **Contrast is re-verified against the host theme.** A ratio that passes on white can fail under a host theme | A11-18.4 |
| TMA-7.4 | The three integrity signals — **Sponsored**, **Verified** and **open status** — must remain distinguishable under **any** host theme, because each carries a non-colour carrier as well | A11 §5.1, DSN-9 |
| TMA-7.5 | Brand presence is not abandoned under a host theme, but host legibility wins where they conflict | A11-1.5 |

---

## 8. Accessibility on this surface

WCAG 2.2 AA applies here exactly as on the Web (NFR-AC1, A11-1.3).
Surface-specific obligations:

| ID | Requirement | Source |
| --- | --- | --- |
| TMA-8.1 | No second back control | A11-18.1 |
| TMA-8.2 | Sized from the stable viewport so the keyboard does not displace controls | A11-18.2 |
| TMA-8.3 | Both safe-area insets respected, so nothing sits under host chrome and **no focused control is obscured** | A11-18.3, WCAG 2.4.11 |
| TMA-8.4 | Contrast re-verified against the host theme | A11-18.4 |
| TMA-8.5 | Operable with the platform's own assistive technology; **Bulbula adds no gesture the host does not support** | A11-18.5 |
| TMA-8.6 | **Email OTP offered first** — an authentication dead end inside a webview is an accessibility failure, not merely an inconvenience | A11-18.6, WCAG 3.3.8 |
| TMA-8.7 | Autocomplete suggestion count is sized to the host viewport so suggestions are not hidden behind the keyboard | UR-03, UR-12 |
| TMA-8.8 | Bottom sheets are never dismissible **only** by swipe | A11-11.4, WCAG 2.5.7 |

---

## 9. Delivery and security

| ID | Rule | Source |
| --- | --- | --- |
| TMA-9.1 | Served over **HTTPS** from the same deployment as the Web | PS-5, PLT-9.3 |
| TMA-9.2 | The Mini App requires a CSP **`frame-ancestors`** policy that admits the Telegram host. **It must not weaken the Web policy** — **Open — technical decision (OT-05)** | OT-05, TRD §34 |
| TMA-9.3 | `X-Frame-Options` and `frame-ancestors` must be reconciled, since the Web surface is framed by nobody and this surface is framed by the host | WEB-9.1 |
| TMA-9.4 | Mini App routes carry `noindex` and are excluded from the sitemap | TR-117 |
| TMA-9.5 | Outbound HTTP to Telegram is confined to the **gateway namespace**, as all outbound traffic is | **TD-07**, TR-07 |
| TMA-9.6 | Surface logic lives in `Bulbula\Telegram` and nowhere else | `architecture.md`, PLT-2.5 |
| TMA-9.7 | Telegram being unavailable affects this surface only. **The Web is unaffected** | `trd-v1.0.md` §12 |
| TMA-9.8 | Error output is safe here as everywhere: no internal identifier, stack trace or SQL | TR-144 |

### 9.1 Framing the OT-05 decision

The TRD schedules OT-05 for the platform phase, so this document states the
decision cleanly without taking it:

| Requirement the chosen policy must satisfy |
| --- |
| Admit the Telegram host so the Mini App renders at all |
| Not admit arbitrary third-party framing of the Web surface |
| Not require disabling or blanket-relaxing the existing CSP |
| Remain verifiable — it must be possible to test that the Web surface is still unframeable by an unapproved origin |
| Survive a change in Telegram's host origins without a code change, if that is achievable by configuration |

**Status: Open — technical decision (OT-05).** Selecting the exact policy
value is a security decision and is not made here.

---

## 10. What this surface deliberately does not have

| Absent | Why |
| --- | --- |
| The operations console | Web only (`scope-v1.md` §1.5) |
| Any SEO surface or indexable route | TG-7, TR-117 |
| Telegram as a login provider | D-48; **Open (D-33)** |
| Telegram message notifications | Email only in V1 (D-24). Adding them fails Q1 of the surface-difference test |
| A Telegram bot conversational interface | Not a V1 capability |
| A second back button or any host-chrome duplicate | TMA-2.1 |
| A separate Mini App codebase, build target or release train | TR-109, PLT-9.1 |
| A separate Mini App domain or URL space | IAR §11 |
| Payments or in-app purchase | No V1 payment capability |
| Any capability the Web does not have | SUR-4, PLT-1.1 |

---

## 11. Verification

| ID | Check |
| --- | --- |
| TMA-11.1 | Context validation is performed server-side and rejects a forged or stale payload |
| TMA-11.2 | Validation failure yields a fully functional Guest experience, not an error |
| TMA-11.3 | Both authentication methods complete successfully inside the Telegram webview on iOS and Android |
| TMA-11.4 | Email OTP is presented first; Google remains reachable |
| TMA-11.5 | Only one back affordance exists, and it is the host's |
| TMA-11.6 | No content or control sits under host chrome at any viewport, with the keyboard open and closed |
| TMA-11.7 | The layout does not jump when the on-screen keyboard opens |
| TMA-11.8 | Contrast passes under the host themes tested, including a dark host theme |
| TMA-11.9 | Sponsored, Verified and open status remain distinguishable under every tested host theme |
| TMA-11.10 | A shared link opens correctly for a recipient with no Telegram account |
| TMA-11.11 | No Mini App route appears in the sitemap or is indexable |
| TMA-11.12 | No console route is reachable from this surface |
| TMA-11.13 | The same query returns the same results in the same order as on the Web |
| TMA-11.14 | The same Customer sees the same Saved list and Reviews as on the Web |

---

## 12. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-33 | Telegram identity relationship | **Open — product detail**; attach-later support required now (TMA-4.4) |
| D-38 | Navigation model: full page loads vs fragment swaps | **Open — implementation detail**; both must remain possible |
| D-16 / D-17 | Frontend approach and view layer | **Open — implementation detail** |
| D-19 | Dark mode as a V1 feature | **Open — product detail**; host theme adoption does not decide it |
| D-21 | Maps provider and hand-off | **Open — product detail**; affects adapter concern A4 |
| OT-05 | CSP `frame-ancestors` admitting the Telegram host | **Open — technical decision**; framed in §9.1 |
| OT-01 | Session lifetime and rotation | **Open — technical decision** |
| — | Telegram host origins to admit | **Open — technical decision**; part of OT-05 |

---

## Decision references

D-15, D-16, D-17, D-19, D-21, D-24, D-33, D-38, D-48, D-49.
