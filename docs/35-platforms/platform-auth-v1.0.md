# Platform Authentication

| | |
| --- | --- |
| **Document** | Platform Authentication — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** How authentication behaves **on each surface**: entry
points, flow mechanics, session transport, return-to-task and failure.

**Scope boundaries.**

- **Identity model, roles and permissions** — `docs/30-technical/auth-identity.md`.
- **Security controls** — rate limiting, token entropy, rotation, threat
  model — belong to `docs/50-security/`, **not written yet**. This
  document states boundaries only.
- **Personal-data handling and consent** — `docs/55-privacy/`, **not
  written yet**.
- **Flow content and copy** — `docs/20-ux-ui/`.

**No authentication decision is taken here.** D-33 remains open.

---

## 1. Fixed ground

| ID | Rule | Source |
| --- | --- | --- |
| PAU-1.1 | **Two methods only: Google and email OTP** | D-48 |
| PAU-1.2 | **No passwords, ever.** No password field, no reset flow, no strength meter | D-48 |
| PAU-1.3 | **No Apple sign-in in V1** | D-48 |
| PAU-1.4 | **No Telegram login.** D-33 is open and may not be decided by implementation | D-33, TG-4 |
| PAU-1.5 | **One identity across both surfaces**; the same person is the same account | C-32, S-1 |
| PAU-1.6 | Google and email OTP resolving to the **same verified email address resolve to the same account** | C-32 |
| PAU-1.7 | **One session model, two transports** | TD-02 |
| PAU-1.8 | **Browsing never requires an account** | GS-1, AC-7 |
| PAU-1.9 | Only Save, Review, Report follow-up, Account and Operations require one | C-14, C-15, C-33 |
| PAU-1.10 | **A business owner is not a platform user** and has no login | D-02, D-54 |

---

## 2. Which surfaces offer what

| | **Web** | **Telegram Mini App** |
| --- | --- | --- |
| Google | Yes | Yes — external-browser hand-off (§5) |
| Email OTP | Yes | Yes |
| Default order | **Google first**, email OTP second | **Email OTP first**, Google second |
| Passwords | None | None |
| Session transport | Cookie (TR-116) | Bearer token (TR-116) |
| Dedicated route | `/signin` (+ `?continue=`) | In-surface equivalent; same destinations |
| Operations sign-in | Web only | **Not available** |

| ID | Rule |
| --- | --- |
| PAU-2.1 | The order difference is **presentation only**. Both methods are fully available on both surfaces (SCC-1.1) |
| PAU-2.2 | Email OTP leads in the Mini App because the redirect-based flow is the one at risk inside an embedded webview (TR-115) |
| PAU-2.3 | **Neither method is ever hidden, collapsed behind "other options", or presented as inferior** |
| PAU-2.4 | **No surface adds a third method**, including a host-provided one (PAU-1.4) |

---

## 3. Entry points

| ID | Rule | Source |
| --- | --- | --- |
| PAU-3.1 | Authentication is reached **from the action that needs it**, not from a wall in front of the product | UFL-0.1 |
| PAU-3.2 | The reason is stated plainly at the point of entry — the User knows **why** before being asked | CDN-4 |
| PAU-3.3 | **No interstitial, modal or countdown gate** on browsing | GS-1 |
| PAU-3.4 | A sign-in prompt **never blocks reading** a Business profile, Reviews, hours or contact details | AC-7 |
| PAU-3.5 | The intent is **captured before** authentication and **completed after** it (PN-7.1) | UFL-0.1 |
| PAU-3.6 | Entry points: Save, Review submission, Report follow-up (optional), Account, Saved, Operations (Web) | C-14, C-15, C-33 |
| PAU-3.7 | **Reporting a problem does not require an account**; an account is only needed to be told the outcome | C-15 |

---

## 4. Email OTP

Identical in meaning on both surfaces; differs only in keyboard and
viewport handling.

```text
1. User states an email address
2. Server sends a one-time code                        (generic response)
3. User enters the code
4. Server verifies: correct, unexpired, unused, within limits
5. Session established; original intent completed
```

| ID | Rule | Source |
| --- | --- | --- |
| PAU-4.1 | **The response after step 2 is identical whether or not an account exists** | C-31, E-5 |
| PAU-4.2 | **No screen, message, timing or error reveals whether an email is registered** | E-5 |
| PAU-4.3 | The code is **single-use** and expires | TR-163 |
| PAU-4.4 | Length, expiry window and attempt limits are **Open (OT-02)** | OT-02 |
| PAU-4.5 | Resend is available after a visible wait; **the limit itself is never disclosed** | E-6 |
| PAU-4.6 | A wrong code states that it is wrong and **how many attempts remain only if that disclosure is safe**; otherwise it states only that it is wrong | E-5 |
| PAU-4.7 | An expired code offers **resend**, not a restart from the email step | UFL §9 |
| PAU-4.8 | **No CAPTCHA, puzzle, or cognitive test** | WCAG 3.3.8 |
| PAU-4.9 | The code field accepts **paste**, has an input mode producing a numeric keypad, and carries the one-time-code autofill hint | A11-8.3, A11-8.4 |
| PAU-4.10 | The field is labelled, focus is placed on it, and errors are announced | A11 §8 |
| PAU-4.11 | **In the Mini App the keyboard must not cover the field or its error** | TM-4.6 |
| PAU-4.12 | The flow is completable **with the keyboard alone** and **with a screen reader alone** | WCAG 2.1.1 |
| PAU-4.13 | A delayed email is explained with a recovery route, never left silent | PEH §4 |
| PAU-4.14 | The address is **never echoed into a URL, log line or analytics event** | TR-123, TR-202 |

---

## 5. Google

| | **Web** | **Telegram Mini App** |
| --- | --- | --- |
| Mechanism | Standard redirect flow | **External-browser hand-off** (TR-115) |
| Return | Back to the originating destination | Back **into the Mini App**, at the originating destination |
| Risk | Standard | Embedded-webview restrictions; return must be explicit |

| ID | Rule | Source |
| --- | --- | --- |
| PAU-5.1 | **A redirect-based OAuth flow must not be assumed to work inside an embedded webview** | TR-115, R-23 |
| PAU-5.2 | The Mini App uses an external-browser hand-off with a **safe, explicit return** | TR-115 |
| PAU-5.3 | The hand-off is invoked **only in response to a user interaction** | TM-9.3 |
| PAU-5.4 | The User is told they are leaving Telegram **before** it happens | TM-9.4, WCAG 3.2.5 |
| PAU-5.5 | **If the return does not occur, email OTP remains fully available** and the User is not stranded | PAU-2.1 |
| PAU-5.6 | Google is **never** the only route to an account | D-48 |
| PAU-5.7 | The return target is validated as an internal destination (PAU-6.6) | — |
| PAU-5.8 | A cancelled or failed Google flow returns the User to where they started, with the other method offered — **not an error page** | PEH §5 |
| PAU-5.9 | **No Google profile data beyond what identity requires** is requested, stored or displayed | TR-201 |
| PAU-5.10 | Client secrets and tokens are **server-side only** | PA-18.10 |

---

## 6. Session

| ID | Rule | Source |
| --- | --- | --- |
| PAU-6.1 | **One session model, two transports**: cookie on the Web, bearer token in the Mini App | TD-02, TR-116 |
| PAU-6.2 | Both resolve to **the same server-side session record** | TD-02 |
| PAU-6.3 | Bearer transport is used in the Mini App to avoid dependence on third-party cookie behaviour in an embedded webview | TR-116 |
| PAU-6.4 | **The session token is never written to `localStorage`** on any surface | PD-05 |
| PAU-6.5 | Authentication state is **resolved from the server**, never inferred from client storage | PD-05 |
| PAU-6.6 | A `continue` target is **validated as an internal destination** before redirect; external targets are rejected | WP-5 |
| PAU-6.7 | **No personal data is stored client-side** on either surface | TR-202 |
| PAU-6.8 | Session lifetime, rotation and revocation are **Open (OT-01)**; the detailed controls belong to `docs/50-security/` | OT-01 |
| PAU-6.9 | Authenticated responses are **`no-store`**; no authenticated page is cached by a shared cache | TR-120 |
| PAU-6.10 | Signing in on one surface does not sign the User out of the other | C-32 |
| PAU-6.11 | Signing out **clears the session on the server**, not merely on the client | — |
| PAU-6.12 | After sign-out, Back **must not** reveal authenticated content from cache | TR-120 |

---

## 7. Identity and the Telegram surface

| ID | Rule | Source |
| --- | --- | --- |
| PAU-7.1 | **Validated Telegram launch context establishes a surface, not an identity** | TR-113, TM-7.3 |
| PAU-7.2 | It **never** signs anyone in and never pre-fills an account | D-33 |
| PAU-7.3 | A Telegram user without a Bulbula account is a **Guest**, with the full Guest experience | GS-1 |
| PAU-7.4 | **D-33 remains open.** No Telegram authentication may be presented as a confirmed V1 requirement | D-33, TG-4 |
| PAU-7.5 | **Telegram identity must never become the primary account model** | D-33 |
| PAU-7.6 | The identity model **must support attaching a Telegram identity later as an additional provider identity, without restructuring accounts** | TR-114 |
| PAU-7.7 | Supporting that attachment **is not** the same as implementing it; V1 implements it **not at all** | TR-114 |
| PAU-7.8 | No copy, button or hint anywhere implies a Telegram account is or will be a Bulbula login | TM-7.8 |

---

## 8. Failure

Full taxonomy in `platform-error-handling-v1.0.md`.

| Situation | Behaviour | Surface difference |
| --- | --- | --- |
| Code wrong | Stated plainly; retry in place; existence never disclosed | None |
| Code expired | Resend offered; email step not repeated | None |
| Too many attempts | Wait stated; **the limit is not disclosed** (E-6) | None |
| Email not delivered | Explained with a recovery route | None |
| Google cancelled | Return to origin; other method offered | Mini App also confirms the return landed |
| Google unreachable | Explained; email OTP offered | None |
| External return lost (Mini App) | Mini App remains usable; email OTP offered | **Mini App only** |
| Session expired mid-task | Intent preserved; re-authenticate; **action completes** | None |
| Launch context invalid | **Full Guest experience**, no error screen | **Mini App only** |
| Network lost during flow | State preserved; retry offered; **no silent failure** | None |

| ID | Rule |
| --- | --- |
| PAU-8.1 | **No authentication failure ever reveals whether an account exists** (C-31, E-5) |
| PAU-8.2 | **No authentication failure discards the User's original intent** (PN-7.6) |
| PAU-8.3 | **No authentication failure leaves the surface unusable**; the Guest experience is always available |
| PAU-8.4 | Error copy follows `content-design-v1.0.md`: what happened, why, what to do next — **no blame, no jargon, no internal identifiers** (E-2) |
| PAU-8.5 | **Failures are never silent** (PEH-2.1) |

---

## 9. Operations

| ID | Rule | Source |
| --- | --- | --- |
| PAU-9.1 | Operator and Administrator access is **Web only** | `scope-v1.md` §1.5 |
| PAU-9.2 | **No Operations route is reachable from the Mini App** | SCC §4 |
| PAU-9.3 | Staff use the same two methods — **no separate password system** | D-48 |
| PAU-9.4 | Authorisation uses **named permission strings**, not role checks (TD-03) | TD-03 |
| PAU-9.5 | **D-14 — the permission set — is open**; nothing here fixes it | D-14 |
| PAU-9.6 | Staff responses are **`no-store`** (TR-120) | TR-120 |
| PAU-9.7 | **Where existence is itself privileged, absence is returned rather than refusal** (TR-35) | TR-35 |
| PAU-9.8 | **No Operations route is indexable** (PSE §4) | — |

---

## 10. Boundaries

| Concern | Owner | Status |
| --- | --- | --- |
| Token entropy, hashing, rotation, revocation detail | `docs/50-security/` | Not written |
| Rate-limit values and lockout policy | `docs/50-security/` | Not written; OT-02 open |
| CSRF, CSP, `frame-ancestors`, cookie attributes | `docs/50-security/` | Not written; OT-05 open |
| Threat model and abuse handling | `docs/50-security/` | Not written |
| Retention, deletion, export, consent | `docs/55-privacy/` | Not written |
| Identity model, roles, permission strings | `docs/30-technical/auth-identity.md` | Written; D-14 open |
| Flow copy | `docs/20-ux-ui/content-design-v1.0.md` | Written |

**This document must not pre-empt those.** Where it states a rule in
their territory, it states only the **platform-visible boundary**.

---

## 11. Verification

| ID | Check |
| --- | --- |
| PAU-11.1 | Both methods complete on both surfaces, on iOS and Android |
| PAU-11.2 | No password field exists anywhere |
| PAU-11.3 | No Telegram login appears anywhere |
| PAU-11.4 | Google and email OTP for the same address resolve to one account |
| PAU-11.5 | Responses are identical for registered and unregistered addresses |
| PAU-11.6 | No message or timing discloses account existence |
| PAU-11.7 | Rate-limit responses never disclose the limit |
| PAU-11.8 | The interrupted action completes after authentication, on both surfaces |
| PAU-11.9 | Back after authentication does not re-enter sign-in |
| PAU-11.10 | A `continue` value pointing off-site is rejected |
| PAU-11.11 | No session token appears in client storage on either surface |
| PAU-11.12 | Authenticated responses carry `no-store`; Back after sign-out reveals nothing |
| PAU-11.13 | The Mini App is fully usable as a Guest with invalid launch context |
| PAU-11.14 | Google hand-off from the Mini App returns to the originating destination |
| PAU-11.15 | A lost return leaves the Mini App usable with email OTP offered |
| PAU-11.16 | The whole flow completes with keyboard alone and with a screen reader alone |
| PAU-11.17 | The OTP field accepts paste and raises a numeric keypad |
| PAU-11.18 | No Operations route is reachable from the Mini App |

---

## 12. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-33 | Telegram identity relationship | **Owner decision, open.** Attach-later support required; no implementation |
| D-14 | Permission set | **Owner decision, open** |
| OT-01 | Session lifetime and rotation | **Open — technical decision** |
| OT-02 | OTP length, window, attempt limits | **Open — technical decision** |
| OT-05 | CSP `frame-ancestors` | **Open — technical decision** |
| — | Launch-context freshness window | **Open — technical decision** |
| — | Google hand-off return mechanism in the Mini App | **Open — implementation detail**; PAU-5.2 and PAU-5.5 bind it |
| — | Whether attempt counts are safe to display | **Open — platform decision**, pending `docs/50-security/` |

---

## Decision references

D-02, D-14, D-33, D-48, D-54.
