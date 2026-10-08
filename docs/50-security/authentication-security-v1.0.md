# Authentication Security

| | |
| --- | --- |
| **Document** | Authentication Security — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

Security requirements for the **already approved** authentication model:
Google and email OTP, no passwords, no Apple. Covers account creation,
OTP handling, OAuth integrity, identity linking, sessions on both
transports, revocation and the Telegram position.

**Out of scope.** The choice of providers (D-48, settled), the identity
model (`auth-identity.md`), flow UX (`platform-auth-v1.0.md`), and
retention of authentication records (`data-retention-v1.0.md`).

## Authority

**This document does not redesign authentication.** It states security
requirements for the approved model. Where a security requirement would
imply a product or policy change, it is marked open.

---

## 1. Fixed ground

| ID | Rule | Source |
| --- | --- | --- |
| AS-1.1 | **Google and email OTP only** | D-48, AI-1 |
| AS-1.2 | **No passwords anywhere** — no field, no column, no reset flow, no strength meter | D-48, AI-2 |
| AS-1.3 | **No Apple sign-in** | D-48, AI-3 |
| AS-1.4 | **Telegram is not an authentication provider** | AI-4, D-33 |
| AS-1.5 | **Businesses do not authenticate** — no business login, claim flow or owner portal | AI-6, D-02, D-54 |
| AS-1.6 | Browsing, searching and reporting need no account | AI-7, GS-1 |
| AS-1.7 | Reviewing, Saving and reporting a Review need an account | AI-8 |
| AS-1.8 | **One identity across both surfaces** | C-32 |
| AS-1.9 | Staff use the same providers; **strength is Open (D-45)** and must exceed Customer authentication | TS-17, D-45 |

**Security consequence of passwordlessness.** There is no password
store, no reset flow and no credential-stuffing exposure. In exchange,
**the email channel and the OTP are the account.** Their protection is
therefore the centre of this document.

---

## 2. Account creation

| ID | Requirement | Source |
| --- | --- | --- |
| AS-2.1 | An account exists only after an **email address is verified** — by completing an OTP, or by Google asserting a verified address | C-32 |
| AS-2.2 | **A Google assertion is accepted as verification only if the provider marks the address verified.** An unverified provider email must not create or link an account | THR-21 |
| AS-2.3 | Account creation collects **the minimum**: a verified email, a stable provider subject identifier where applicable, and account timestamps | PRIV-1, D-51 |
| AS-2.4 | **No date of birth, phone number, gender, address or demographic field is collected** at sign-up | D-51, PBD §4 |
| AS-2.5 | A display name is optional and is **not** required to be a real name | THR-56 |
| AS-2.6 | **Age handling is unresolved — PENDING COUNSEL (D-46, L-4).** No age field may be introduced to solve it technically; see `privacy-governance-v1.0.md` §9 | D-46 |
| AS-2.7 | Account creation is rate-limited per address and per source | THR-27 |
| AS-2.8 | Creating an account **never** discloses whether the address already existed | C-31, E-5 |

---

## 3. Email OTP — generation and storage

| ID | Requirement | Source |
| --- | --- | --- |
| AS-3.1 | Codes are generated with a **cryptographically secure random source**. A time-seeded or predictable generator is a defect | THR-15 |
| AS-3.2 | **Length, expiry window and attempt limits are Open (OT-02).** They must be chosen together: a short code demands tight limits | OT-02 |
| AS-3.3 | The code is bound to **one email address and one request**; a code issued for one address never validates another | THR-16 |
| AS-3.4 | **The stored form must not be usable to authenticate.** A code is credential material: store a hash, not the value | S-3, THR-63 |
| AS-3.5 | Verification compares in **constant time** | THR-15 |
| AS-3.6 | **Single-use.** Consumption is atomic — a concurrent second use must lose | TR-163, SAC-9 |
| AS-3.7 | A code is invalidated on: successful use, expiry, attempt-limit exhaustion, a newer code for the same address, and account deletion | THR-16 |
| AS-3.8 | **The code value never appears in a log, error, analytics event or URL** | TR-135, SEC-5.2 |
| AS-3.9 | The code is delivered **only** to the address that requested it | AS-3.3 |
| AS-3.10 | OTP records are retained only as long as the flow and its abuse controls require — period **PENDING COUNSEL** (L-21, D-46) | RET §4 |

### 3.1 Brute force, resend and enumeration

| ID | Requirement | Source |
| --- | --- | --- |
| AS-3.11 | Attempts are limited **per code**, **per address** and **per source**. Per-code alone is defeated by requesting many codes | THR-15 |
| AS-3.12 | Exhausting the attempt limit **invalidates the code** rather than merely pausing | AS-3.7 |
| AS-3.13 | Resend is rate-limited with increasing back-off | PAU-4.5 |
| AS-3.14 | **Send limits apply per address and per source** so Bulbula cannot be used to mail-bomb a third party | THR-23 |
| AS-3.15 | **No response discloses a limit, a remaining count or a threshold** | E-6, SAC-8 |
| AS-3.16 | **Request, failure and lockout responses are identical for registered and unregistered addresses — in content, status and timing** | C-31, E-5, THR-20 |
| AS-3.17 | Whether remaining-attempt counts can be safely displayed is **Open — security decision** | PAU §12 |
| AS-3.18 | **No CAPTCHA, puzzle or cognitive test** is used as the anti-automation control | WCAG 3.3.8 |
| AS-3.19 | Repeated failures for one address are **logged as a security event** without logging the code | TR-136 |

---

## 4. Email channel

The OTP is only as strong as its delivery.

| ID | Requirement | Source |
| --- | --- | --- |
| AS-4.1 | OTP mail is sent through the professional transactional provider, not shared-host local mail | D-24 |
| AS-4.2 | The provider credential is a secret (SEC §5); it is never committed or logged | TR-198 |
| AS-4.3 | Authentication mail contains **the code and nothing else of value** — no personal data beyond the address, no session link, no account details | PRIV-1 |
| AS-4.4 | Mail must not leak whether an account existed previously | AS-3.16 |
| AS-4.5 | Delivery failures are surfaced to the User with a recovery route, never silently swallowed | PAU-4.13 |
| AS-4.6 | **The email provider is a processor receiving personal data** and is registered in `vendor-and-transfer-register-v1.0.md`; provider selection is **Open (D-41)** | D-41, VT §3 |
| AS-4.7 | Email delivery logs are personal data and are retained under the retention schedule, not indefinitely | RET §7 |

---

## 5. Google sign-in

| ID | Requirement | Source |
| --- | --- | --- |
| AS-5.1 | The OAuth **state parameter is mandatory**: unguessable, single-use, bound to the user agent's pre-authentication context, and verified on callback | THR-04, T7 |
| AS-5.2 | **A callback without a valid state is rejected**, with no partial session created | SAC-17 |
| AS-5.3 | A nonce is used where the flow supports it, and verified | T7 |
| AS-5.4 | **Identity assertions are verified server-side** — signature, issuer, audience and expiry — never trusted from the client | SEC-3.9 |
| AS-5.5 | The **stable provider subject identifier** is stored alongside the verified email; the email alone is not the identity key | THR-22 |
| AS-5.6 | The client secret is server-side only | SEC-5.1 |
| AS-5.7 | Redirect URIs are **exactly registered**; no wildcard, no dynamic host | THR-13 |
| AS-5.8 | The post-authentication destination is **validated as internal** before redirect | PAU-6.6, THR-06 |
| AS-5.9 | **Only the identity scope is requested.** No contacts, profile breadth or Drive scope | PRIV-1, D-51 |
| AS-5.10 | **No Google profile data beyond what identity requires is stored or displayed** | TR-201 |
| AS-5.11 | In the Mini App, Google uses an **external-browser hand-off**; a redirect flow inside the webview must not be assumed to work | TR-115, PAU-5.1 |
| AS-5.12 | A lost or failed return leaves the surface usable with **email OTP still available** | PAU-5.5 |
| AS-5.13 | **Google is a processor and a cross-border recipient**; see `vendor-and-transfer-register-v1.0.md` §3 | VT §3 |

---

## 6. Identity linking

**D-13 is open.** The architecture below must be secure under any
resolution and must not pre-empt it.

| ID | Requirement | Source |
| --- | --- | --- |
| AS-6.1 | An identity record is **(provider, subject identifier, verified email)**, not an email alone | AS-5.5 |
| AS-6.2 | **Linking occurs only on a verified email on both sides.** An unverified claim never links | THR-21, AS-2.2 |
| AS-6.3 | **No silent merge.** Where linking would join two existing accounts with data on both, the system must not merge automatically — the case is escalated | THR-21 |
| AS-6.4 | **Whether same-verified-email identities auto-link, prompt, or stay separate is Open (D-13)** and must not be decided by implementation | D-13 |
| AS-6.5 | **Unlinking must never leave an account unreachable**: a Customer must always retain at least one usable method | AS-1.1 |
| AS-6.6 | A provider identity **cannot be reassigned** to another account without re-verification | THR-22 |
| AS-6.7 | **Email collision is a security event**, not a convenience case: two identities asserting one address are logged and surfaced | THR-21 |
| AS-6.8 | Linking and unlinking are **recorded with actor, time and method** | C-29 |
| AS-6.9 | A linking change **invalidates existing sessions** where it alters who can reach the account | S-5 |

### 6.1 What must be prevented

| Must not happen | Prevented by |
| --- | --- |
| Accidental merge of two real people sharing an address | AS-6.2, AS-6.3 |
| Takeover by registering an unverified provider identity | AS-2.2, AS-6.2 |
| Takeover by email collision | AS-6.7, AS-6.3 |
| Provider-identity reassignment inheriting an account | AS-5.5, AS-6.6 |
| Orphaning an account with no usable method | AS-6.5 |

---

## 7. Sessions

### 7.1 Shared model

| ID | Requirement | Source |
| --- | --- | --- |
| AS-7.1 | **One session concept, two transports** — cookie on Web, bearer in the Mini App, both resolving to one server record | TD-02, S-1 |
| AS-7.2 | Tokens are **opaque, high-entropy and server-generated**. No JWT, no self-describing token, no client-readable claims | S-2 |
| AS-7.3 | **Only a hash of the token is stored** | S-3 |
| AS-7.4 | **The client holds no authorization data the server trusts** | S-4, TR-33 |
| AS-7.5 | The session **rotates on authentication and on privilege change** | S-5, TR-107 |
| AS-7.6 | Sessions carry an **absolute and an inactivity expiry**; values are **Open (OT-01)** | S-6, OT-01 |
| AS-7.7 | **Revocation is immediate and server-side** on sign-out, deletion, staff disablement and suspected compromise | S-7 |
| AS-7.8 | Several concurrent sessions are permitted | S-8 |
| AS-7.9 | **Authentication state is resolved from the server**, never inferred from client storage | PD-05 |
| AS-7.10 | Authenticated responses are **`no-store`** | TR-120 |

### 7.2 Web cookie

| ID | Requirement |
| --- | --- |
| AS-7.11 | **`HttpOnly`** — script must not read the session cookie |
| AS-7.12 | **`Secure`** — never sent over plaintext; HTTPS everywhere (PS-5) |
| AS-7.13 | **`SameSite`** set restrictively by default; any relaxation must be justified against THR-04 and recorded |
| AS-7.14 | Scoped to the application path and host; **no wildcard domain** |
| AS-7.15 | **Session fixation is prevented by rotation on authentication** (AS-7.5) |
| AS-7.16 | The cookie carries the session identifier only — **no personal data, no role, no preference the server trusts** |
| AS-7.17 | Sign-out clears the cookie **and** revokes server-side (AS-7.7) |
| AS-7.18 | The exact `SameSite` value and cookie prefix choices are **Open — implementation detail**, bounded by AS-7.11…AS-7.14 |

### 7.3 Mini App bearer

| ID | Requirement |
| --- | --- |
| AS-7.19 | Transport is an **opaque bearer token**, chosen to avoid dependence on third-party cookie behaviour in an embedded webview (TR-116) |
| AS-7.20 | **The token is never written to `localStorage`** (PD-05) — WebView storage may be cleared, and it is script-readable |
| AS-7.21 | **No Telegram cloud, device or secure storage is used for session material** (TM-11.3) |
| AS-7.22 | The token is sent only to Bulbula's own origin, over HTTPS, in the `Authorization` header — **never in a URL or query string** |
| AS-7.23 | The token is revocable and expires identically to the cookie session (AS-7.6, AS-7.7) |
| AS-7.24 | Bearer-authenticated requests do not rely on CSRF tokens, because they are not sent automatically by the browser; see `application-security-v1.0.md` §5 |
| AS-7.25 | Where the token is held in memory only, loss of memory is an acceptable logout — **it is never persisted to make sessions survive** (PD-05) |

### 7.4 Suspicious authentication

| ID | Requirement | Source |
| --- | --- | --- |
| AS-7.26 | The following are **logged as security events**: repeated OTP failure, resend flooding, OAuth state mismatch, email collision, authorization denial, staff authentication failure | TR-136 |
| AS-7.27 | Security events carry the correlation id and **never the credential** | TR-134, TR-135 |
| AS-7.28 | Whether a Customer is **notified of a new sign-in** is **Open — product decision.** It would be a new notification type and is not an approved V1 capability | D-24 |
| AS-7.29 | Anomaly thresholds and alerting are **Open — implementation detail** (OT-07) | OT-07 |

---

## 8. Sign-out and deletion

| ID | Requirement | Source |
| --- | --- | --- |
| AS-8.1 | Sign-out revokes **the server record**, not only the client artefact | S-7, PAU-6.11 |
| AS-8.2 | After sign-out, Back must reveal no authenticated content from cache or history | PAU-6.12, TR-120 |
| AS-8.3 | Sign-out on one surface does not sign the Customer out of the other, unless they ask for all sessions | S-8, C-32 |
| AS-8.4 | **Account deletion revokes every session immediately** | S-7 |
| AS-8.5 | Deletion requires an authenticated, confirmed action and is **audited** | THR-26, C-29 |
| AS-8.6 | Deletion removes or irreversibly detaches personal data per the approved retention rules; **the treatment of published Reviews is Open (D-34) and PENDING COUNSEL (L-21)** | TR-205, D-34 |
| AS-8.7 | Deletion **must not** delete audit or moderation history belonging to the record of staff action | TR-167 |
| AS-8.8 | After deletion, the address must be able to create a **new, unrelated account** without inheriting anything | TR-205 |
| AS-8.9 | Deletion is a right under Art. 28 and is specified in `data-subject-rights-v1.0.md` §5 | DSR §5 |

---

## 9. Staff authentication

| ID | Requirement | Source |
| --- | --- | --- |
| AS-9.1 | Staff **must use stronger authentication than Customers** and **must not rely on email OTP alone** | TS-17 |
| AS-9.2 | **The mechanism is Open (D-45).** It is the single highest-impact unresolved security decision in V1 | D-45, THR-38 |
| AS-9.3 | Until D-45 is resolved, the console **must not be treated as safe to operate** at scale; this is recorded as a risk, not worked around | THR-38 |
| AS-9.4 | Staff sessions expire; values are a TRD matter | TS-19, OT-01 |
| AS-9.5 | Staff sessions are revoked immediately on disablement | S-7 |
| AS-9.6 | Staff responses are `no-store` | TR-120 |
| AS-9.7 | Authorization uses **named permission strings**, never role checks in code | TD-03 |
| AS-9.8 | **D-14 (the permission split) is open**; nothing here fixes it | D-14 |
| AS-9.9 | Staff authentication and authorization failures are logged and attributable | TR-136, TS-15 |
| AS-9.10 | **No shared staff account exists.** Attribution (TS-15) is impossible without individual accounts | TS-15 |

---

## 10. Telegram

| ID | Requirement | Source |
| --- | --- | --- |
| AS-10.1 | **Telegram client data is never authentication evidence** | TR-112, TM-7.10 |
| AS-10.2 | Validated launch context establishes **a surface, not an identity** | TR-113, TM-7.3 |
| AS-10.3 | Validation is **server-side**, on the raw signed payload, using the documented derivation, with a **constant-time compare** and a **freshness window** | TM-7.9…TM-7.12 |
| AS-10.4 | **Validation failure degrades to the full Guest experience** — not an error, not a dead end | TM-7.14 |
| AS-10.5 | **The bot token never reaches the client** | TM-7.16, SEC-5.4 |
| AS-10.6 | A launch parameter is **forgeable input** and is resolved from validated context server-side | TM-7.15, THR-30 |
| AS-10.7 | **D-33 must not be turned into an approved login mechanism** by this document or by implementation | D-33 |
| AS-10.8 | The identity model must support **attaching a Telegram identity later** as an additional provider identity, without restructuring — supporting it is not implementing it | TR-114 |
| AS-10.9 | If Telegram identity is ever approved, it enters through §6 with the **same linking protections**, including verified-email binding | AS-6.2 |
| AS-10.10 | The freshness window value is **Open — technical decision** | — |

---

## 11. Unresolved items

| ID | Item | Status |
| --- | --- | --- |
| D-45 | Staff authentication strength | **Owner decision, open — highest-impact security gap** |
| D-13 | Identity-linking rules | **Owner decision, open** |
| D-14 | Permission split | **Owner decision, open** |
| D-33 | Telegram identity | **Owner decision, open.** Not a login mechanism |
| D-34 | Review treatment after account deletion | **Owner decision, open** |
| D-41 | Email provider | **Open — technical decision** |
| D-46 | Minimum account age | **PENDING COUNSEL** |
| OT-01 | Session lifetime and rotation values | **Open — technical decision** |
| OT-02 | OTP length, window, attempt limits | **Open — technical decision** |
| L-21 | Retention of authentication records | **PENDING COUNSEL** |
| — | Launch-context freshness window | **Open — technical decision** |
| — | `SameSite` value and cookie prefixes | **Open — implementation detail** |
| — | Whether remaining-attempt counts may be displayed | **Open — security decision** |
| — | New-sign-in notification | **Open — product decision**; would be a new notification type |

---

## Legal and regulatory references

| Reference | Relevance | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, Art. 17(4)(a) | Pseudonymisation and encryption of personal data — supports hashing session tokens and OTP records | Confirmed — statute/regulation |
| Art. 11 | Minors; age verification obligations bearing on AS-2.6 | Confirmed — statute/regulation |
| Art. 28 | Right to erasure, underlying AS-8.6 | Confirmed — statute/regulation |
| Art. 20, 22 | Google and the email provider as cross-border recipients | Confirmed — statute/regulation |
| Minimum account age for Bulbula | — | **PENDING COUNSEL** (D-46) |
| Retention of OTP, session and email-delivery records | — | **PENDING COUNSEL** (L-21) |

---

## Decision references

D-02, D-13, D-14, D-24, D-33, D-34, D-41, D-45, D-46, D-48, D-51, D-54.
