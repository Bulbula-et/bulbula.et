# Authentication and Identity

| | |
| --- | --- |
| **Document** | Authentication and Identity Design — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Scope.** How a Customer proves who they are, how one person with several
sign-in methods stays one account, how sessions work across Web and the
Telegram Mini App, and how staff access is structured.

**Not in scope.** Threat modelling, incident response, key custody and the
full control set — `docs/50-security/`, not yet written.
Detailed rate-limit values are set there; this document fixes the
*mechanisms*, not the numbers.

**Authority.** Authentication mechanisms are **D-48 (Approved)**. Nothing
here may add one.

---

## 1. What V1 is

| ID | Statement | Source |
| --- | --- | --- |
| AI-1 | Customers authenticate with **Google sign-in** or **email one-time code**. Those two, and only those two | D-48, C-30, C-31 |
| AI-2 | **There are no passwords anywhere in the product.** No password field, no password column, no reset flow, no "forgot password" | D-48 |
| AI-3 | **Apple sign-in is not in V1** | D-48 |
| AI-4 | **Telegram is not an authentication provider in V1** | D-48, D-33 |
| AI-5 | Facebook, phone/SMS and magic-link-by-SMS do not exist | D-48, PRD §13 |
| AI-6 | **Businesses do not authenticate.** There is no business login, no claim flow, no owner portal | D-54, D-02 |
| AI-7 | Browsing, searching and reporting a Listing require no account | GS-1, TS-2 |
| AI-8 | Reviewing, Saving and reporting a Review require an account | D-12, `interaction-permissions.md` §3 |

**Why no passwords.** Removing passwords removes credential stuffing,
password reuse, reset-flow abuse, storage-strength obligations and the
largest category of support work, at the cost of depending on email
deliverability (D-41) — a dependency the product has anyway.

---

## 2. Principals

| Principal | Authenticates | Lives in |
| --- | --- | --- |
| **Guest** | No | — |
| **Customer** | Google or email OTP | `Customer` + `ProviderIdentity` |
| **Staff** (Operator, Administrator) | Separately, §7 | `StaffUser` |
| **Business** | **Never** | — |

Customers and Staff are **separate principals with separate records and
separate sessions** (PRD ACC-8). A staff member who also uses the public site
as a Customer holds two unrelated accounts. There is no privilege escalation
path from a Customer session to a staff one (TRD TR-39).

---

## 3. Account creation

**There is no separate registration step.** The first successful
authentication creates the Customer (C-30, C-31).

```text
Authenticate (Google or OTP)
   └─ verified email address established
        ├─ matches an existing verified Customer → sign in to it
        └─ no match                              → create Customer + ProviderIdentity
```

| ID | Rule |
| --- | --- |
| NA-1 | A Customer record is created only after the email address is **verified** — by Google's assertion or by a consumed OTP |
| NA-2 | At creation the record holds the minimum: email, display name, timestamps, state (PRD ACC-6, D-51) |
| NA-3 | A display name is seeded from the provider where available and is editable (C-33) |
| NA-4 | Creation is audited as an account event; it is not an operational audit entry about Listings |
| NA-5 | **Nothing in account creation asks for, or stores, a date of birth.** The minimum-age position is **Open (D-46)** and **PENDING COUNSEL** |

---

## 4. The two flows

### 4.1 Google sign-in (C-30)

```text
Client → Google → credential
Client → POST /api/v1/auth/google  { credential }
Server: verify signature, issuer, audience, expiry against Google's keys
        require email_verified = true
        resolve identity (§5) → create or load Customer
        create Session → issue cookie (Web) or bearer token (API)
```

| ID | Rule |
| --- | --- |
| GA-1 | The credential is verified **server-side** against Google's published keys. A client assertion is never trusted (TRD TR-104) |
| GA-2 | Issuer, audience, expiry and signature are all checked; failure of any is a generic failure |
| GA-3 | An unverified provider email **MUST NOT** establish a verified address |
| GA-4 | The Google subject identifier, not the email address, is the stable provider key — email addresses change |
| GA-5 | Client identifiers and secrets come from environment configuration, never from source (TRD TR-189) |
| GA-6 | Google's availability is a dependency of this flow only. If Google is unreachable, email OTP still works and browsing is unaffected (TRD TR-205) |

**Inside Telegram.** Opening an external OAuth flow inside an in-app webview
is unreliable and is blocked by Google in some webviews (R-23). The Mini App
therefore treats **email OTP as the primary path** and routes Google sign-in
through the platform's external-browser mechanism rather than an embedded
webview. This is a surface adaptation, not a different product behaviour
(PRD SUR-6).

### 4.2 Email one-time code (C-31)

```text
POST /api/v1/auth/otp/request { email }
  → always the same response, regardless of whether an account exists
  → generate CSPRNG numeric code
  → store HASH of the code + expiry + attempt counter + session binding
  → send email (D-41)

POST /api/v1/auth/otp/verify { email, code }
  → constant-time compare against the stored hash
  → check not expired, not consumed, attempts remaining
  → mark consumed (single use) → establish verified email → session
```

| ID | Rule | Source |
| --- | --- | --- |
| OTP-1 | The code is generated with a cryptographically secure RNG | R-25 |
| OTP-2 | **Only a hash of the code is stored**, never the code itself | R-25, TRD TR-25 |
| OTP-3 | Codes are **single-use**; a consumed code fails even inside its window | TRD TR-163 |
| OTP-4 | Codes expire on a short fixed window | R-25 (NIST caps out-of-band completion at 10 minutes) |
| OTP-5 | A small number of failed attempts invalidates the code entirely, forcing a new request | R-25 |
| OTP-6 | Requests are limited per address **and** per source network, with a resend cooldown | R-25, TRD §34 |
| OTP-7 | The code is bound to the requesting session so a code cannot be driven from a different context | R-25 |
| OTP-8 | Request and verify responses are **uniform**: they never reveal whether the address is registered, nor why verification failed | C-31, TRD TR-28 |
| OTP-9 | Anomalous patterns (many addresses from one network, many requests for one address) are logged for review | R-25 |
| OTP-10 | Codes **MUST NOT** appear in logs, analytics, error reports or URLs | TRD TR-25 |

**Exact values — code length, validity window, attempt ceiling, request
quota, cooldown — are `Open — technical decision` (TRD OT-02)** and are
fixed in the security phase. They are configuration, not constants
(TRD TR-196).

**Standards note.** NIST SP 800-63B prohibits email as an out-of-band channel
for *authenticator* purposes, while exempting address validation and issued
recovery codes (R-25). Email OTP is used here as a **primary sign-in
mechanism for a low-risk consumer directory**, not as a second factor, and
not for privileged accounts — which is precisely why §7 keeps staff
authentication strength to D-45.

---

## 5. Identity resolution and linking

**Goal (C-32).** One person, however they sign in, is one Customer with one
Saved list and one set of Reviews.

### 5.1 The mechanism

A Customer has zero or more `ProviderIdentity` rows (`data-model.md` §5.2).
Resolution on each authentication:

```text
1. Does a ProviderIdentity exist for (provider, subject)?      → sign in to its Customer
2. Else, does a Customer exist with this VERIFIED email?
      → candidate link
3. Else                                                        → new Customer + identity
```

### 5.2 What is fixed and what is not

| ID | Rule | Status |
| --- | --- | --- |
| LK-1 | Linking is only ever considered on a **verified** email address on both sides | Fixed (C-32) |
| LK-2 | Case folding and standard normalisation apply before comparison | Fixed |
| LK-3 | **Provider-specific aliasing (dots, plus-addressing) is not treated as equivalence** — guessing causes wrong merges | Fixed |
| LK-4 | Ambiguity **MUST NOT** silently merge or silently fork. Where the system cannot be sure, it stops and asks | Fixed (C-32) |
| LK-5 | Linking never silently grants access to another person's Saves or Reviews | Fixed (TRD TR-110) |
| LK-6 | Whether step 2 links automatically or requires an explicit confirming action | **Open (D-13)** |
| LK-7 | Whether a Customer may unlink a provider, and what happens if it is their last | **Open (D-13)** |
| LK-8 | Whether linking is offered proactively in the profile | **Open (D-13)** |

**The architecture accommodates every D-13 outcome** because the link is a
row, not a column: automatic linking inserts it at step 2, confirmed linking
inserts it after an explicit action, and no linking never inserts it. No
schema change, no rewrite (TRD TR-111).

### 5.3 Collision cases

| Case | Behaviour |
| --- | --- |
| Google email equals an existing OTP Customer's email | Candidate link — resolution per D-13; **never an automatic merge of data in V1** |
| Two existing Customers end up sharing a verified address | Cannot occur: verified email is unique (`data-model.md` §5.1) |
| A Google account's email changes to one already held by another Customer | Subject identifier wins for sign-in; the address is **not** reassigned; flagged for staff review |
| A Customer signs in with a provider whose email is unverified | Rejected (GA-3) |
| A person has two genuinely separate addresses | Two accounts. Merging accounts is **not a V1 capability** |

**Merging two existing Customers is out of scope for V1.** It touches
Reviews, Saves and deletion semantics and would need a product decision that
does not exist.

---

## 6. Sessions

### 6.1 One session, two transports (TRD TD-02)

```text
                 ┌──────────────── Session record ────────────────┐
Web browser  ───▶│ principal · token hash · surface · expiry      │
Mini App/API ───▶│ created_at · last_active_at · revoked_at       │
                 └───────────────────────────────────────────────┘
      cookie                                   bearer token
```

| ID | Rule |
| --- | --- |
| S-1 | **One session concept.** The Web sends a cookie, the API and Mini App send `Authorization: Bearer`; both resolve to the same server-side record (TD-02) |
| S-2 | The token is opaque, high-entropy and server-generated. **No JWT, no self-describing token, no client-readable claims** in V1 (TRD TR-105) |
| S-3 | **Only a hash of the token is stored.** A database disclosure does not yield usable sessions |
| S-4 | Session state is server-side; the client holds no authorization data that the server trusts (TRD TR-33) |
| S-5 | The session identifier **rotates on privilege change** and on authentication (TRD TR-107) |
| S-6 | Sessions carry an absolute expiry and an inactivity expiry. **Values are `Open — technical decision` (TRD OT-01)** |
| S-7 | Revocation is immediate and server-side: sign-out, deletion, staff disablement, suspected compromise |
| S-8 | A Customer can hold several concurrent sessions (phone, desktop, Mini App) |

**Why not JWT.** Stateless tokens cannot be revoked without the server-side
state they were meant to avoid, and the product needs immediate revocation on
deletion (C-36). A stateless token may be reconsidered later; it is not
needed, so it is not added (TRD NG-9).

### 6.2 Cookie requirements (Web)

| ID | Requirement |
| --- | --- |
| K-1 | `HttpOnly` — never readable by JavaScript |
| K-2 | `Secure` — HTTPS only |
| K-3 | `SameSite` set restrictively; a relaxation needed for an embedded surface must be justified and documented, not assumed |
| K-4 | `Path=/`, host-only, no cross-subdomain scope |
| K-5 | No personal data in the cookie value — it is an opaque reference (TRD TR-123) |
| K-6 | A fresh identifier is issued at authentication; a pre-authentication value is never promoted (S-5) |

### 6.3 Token storage (Mini App and API clients)

| ID | Requirement |
| --- | --- |
| T-1 | Stored in the platform's own storage, not in a URL, not in a query parameter, not in a page fragment |
| T-2 | Never logged, never sent to analytics, never placed in an error report (TRD TR-25) |
| T-3 | Transmitted only over HTTPS in the `Authorization` header |
| T-4 | Discarded on sign-out and on revocation responses |
| T-5 | A token leaked into a referrer or a link is a defect; links out of authenticated views carry no credential |

### 6.4 CSRF

| ID | Requirement |
| --- | --- |
| CS-1 | Every cookie-authenticated state-changing request carries a per-session CSRF token, validated server-side (TRD TR-108) |
| CS-2 | `GET` **never** changes state, so it is never a CSRF target (TRD TR-157) |
| CS-3 | Bearer-authenticated API requests are not cookie-authenticated and so are not CSRF-exposed; they **MUST NOT** fall back to cookie authentication |
| CS-4 | The CSRF token is bound to the session and rotates with it |
| CS-5 | Cross-origin requests to state-changing endpoints are rejected by default; **CORS is not opened to arbitrary origins** |

### 6.5 Logout (C-32)

Logout revokes the server-side session, clears the cookie or instructs the
client to discard the token, and returns the User to a sensible public page.
It is a state change and therefore a `POST`. Logging out of one device does
not revoke the others; account deletion revokes everything.

---

## 7. Staff authentication

**Staff authentication strength is D-45 — an open decision.** This document
**MUST NOT** decide it. It defines the architecture that accommodates every
outcome (TRD TR-31).

| ID | Rule | Status |
| --- | --- | --- |
| SF-1 | Staff accounts are created by an Administrator. **No self-registration, no public staff sign-in discovery** | Fixed (PRD §28, SF-1) |
| SF-2 | Staff authentication is **separate** from Customer authentication, with separate sessions and separate endpoints | Fixed (TRD TR-39) |
| SF-3 | Authentication factors are stored as **rows** (`StaffAuthFactor`), so adding a second factor is data plus a verifier, not a redesign | Fixed (`data-model.md` §5.5) |
| SF-4 | Authorization is by **named permission**, never by "is staff" (TRD TD-03, TR-36) |
| SF-5 | Staff sessions are shorter-lived than Customer sessions and revocable by an Administrator | Fixed; values **Open (OT-01)** |
| SF-6 | Every privileged action is audited with the acting staff identity (C-29, TRD TR-08) |
| SF-7 | Disabling a staff account revokes their sessions immediately |
| SF-8 | **Which factors are required** — email OTP, TOTP, hardware key, IP restriction, or a combination | **Open (D-45)** |
| SF-9 | Whether staff reuse the Customer mechanisms at all | **Open (D-45)** |

**R-25 bears on D-45:** email OTP alone is appropriate for a low-risk
consumer account, not for an account that can publish, unpublish, moderate
and activate paid placements. This document records the evidence; the
decision remains the owner's.

**Until D-45 is decided**, no staff console is built — consistent with Phase
1's scope and with the rule that technical documents do not create product
decisions.

---

## 8. Abuse controls

| ID | Control | Applies to |
| --- | --- | --- |
| AX-1 | Rate limiting per address and per source network | OTP request, OTP verify, Google exchange |
| AX-2 | Resend cooldown | OTP request |
| AX-3 | Attempt ceiling invalidating the code | OTP verify |
| AX-4 | Uniform responses and uniform timing where feasible | All authentication failures (TRD TR-28) |
| AX-5 | One Review per Customer per subject, enforced in the database | Review submission (PRD §20, AX-3) |
| AX-6 | Rate limiting on review and report submission | Community endpoints |
| AX-7 | Logging of anomalous authentication patterns for staff review | All |
| AX-8 | Account state `disabled` available for abuse response | Customer |
| AX-9 | No credential, code or token in logs, analytics or error output | All (TRD TR-25) |

| ID | Rule |
| --- | --- |
| AX-10 | Limits are **configuration**, not constants (TRD TR-196) |
| AX-11 | Limits are **never disclosed** in responses or documentation (API §1.10) |
| AX-12 | CAPTCHA is **not** introduced in V1. It adds an external dependency and an accessibility cost; rate limiting plus authentication requirements are the V1 controls (TRD NG-11) |

---

## 9. Recovery

**There is no password, so there is nothing to reset** (AI-2). Recovery is
regaining access to the *address*, which is outside Bulbula.

| Situation | Outcome |
| --- | --- |
| Lost access to the Google account but retains the address | Email OTP to the same address reaches the same Customer (subject to §5) |
| Lost access to the email address entirely | **Not recoverable by Bulbula.** The address is the identity; a staff override would be an account-takeover mechanism and is deliberately absent |
| Wants to change the email address | **Not a V1 capability.** It is an identity change with linking and uniqueness consequences and needs D-13 settled first |
| Code not arriving | Resend after cooldown; deliverability is the D-41 dependency |

| ID | Rule |
| --- | --- |
| RC-1 | **Staff cannot authenticate as a Customer, impersonate one, or transfer an account** (TRD TR-38) |
| RC-2 | No recovery path weakens the primary mechanism — no backup questions, no "contact support to get in" |
| RC-3 | The absence of recovery is stated plainly in user-facing text, not discovered at the moment of loss (C-18) |

---

## 10. Deletion (C-36)

```text
POST /me/deletion   → explicit, informed confirmation
DELETE /me          → execute
   ├─ revoke every session
   ├─ delete or irreversibly detach personal data (identities, OTP state, saves)
   ├─ withdraw the Customer's Reviews — public visibility ceases (D-34)
   │    retention of the internal record: PENDING COUNSEL (L-21, D-46)
   ├─ write an audit entry WITHOUT personal data (TRD TR-167)
   └─ send confirmation (C-39)
```

| ID | Rule |
| --- | --- |
| DL-1 | Deletion requires explicit confirmation and states what will and will not be removed before it happens |
| DL-2 | Every session is revoked |
| DL-3 | Audit entries survive; they record that a deletion occurred, not who the person was (TRD TR-167) |
| DL-4 | Published Reviews are **withdrawn**: public visibility ceases and they leave every rating summary (D-34). **How long the internal record is retained, and whether it is ultimately anonymised or erased, remains PENDING COUNSEL (L-21, D-46)** — neither is asserted here |
| DL-5 | Response windows and completeness obligations are **PENDING COUNSEL** (LK-7) |
| DL-6 | A deleted address may sign in again — producing a **new** Customer with no prior data (NA-1) |

---

## 11. What is deliberately absent

| Absent | Reason |
| --- | --- |
| Passwords, reset flows, strength meters | D-48 |
| Apple, Facebook, phone/SMS sign-in | D-48 |
| Telegram as an identity provider | D-48, D-33 open |
| Business login, claim flow, owner portal | D-54, D-02 |
| Account merging | No product decision exists (§5.3) |
| Email-address change | Depends on D-13 |
| JWT or stateless sessions | Revocation requirement (§6.1) |
| CAPTCHA, third-party bot defence | AX-12 |
| Social graph, following, public Customer profiles | PRD §13 |

---

## 12. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-13 | Identity-linking rules | Open — product decision |
| D-33 | Telegram identity relationship | Open — product decision |
| D-41 | Email provider and deliverability | Open — OTP depends on it |
| D-45 | **Staff authentication strength** | Open — §7 accommodates all outcomes |
| D-46 | Minimum age | Open — **PENDING COUNSEL** |
| TRD OT-01 | Session lifetimes | Open — technical decision |
| TRD OT-02 | OTP length, window, attempt and request limits | Open — technical decision |

**D-34 closed on 2026-10-07** and has left this table. It fixes the
*mechanism* on deletion — a Review is **withdrawn**, not destructively
erased — which is reflected in §10 DL-4. It does **not** fix the retention
period or the minimum age; both remain **PENDING COUNSEL** under L-21 and
D-46.
| LK-5 | Lawful basis for processing | **PENDING COUNSEL** |
| LK-7 | Rights-request windows and export format | **PENDING COUNSEL** |
| L-21 | Retention schedule | **PENDING COUNSEL** |

---

## Decision references

D-02, D-12, D-13, D-33, D-34, D-41, D-45, D-46, D-48, D-51, D-54.
