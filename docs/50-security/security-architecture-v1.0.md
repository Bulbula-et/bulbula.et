# Security Architecture

| | |
| --- | --- |
| **Document** | Security Architecture — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

The V1 security architecture: trust boundaries, security principles,
secrets handling, the research that informed it, the severity model's
entry point, and the testable security and privacy acceptance criteria
that implementation must satisfy.

**In scope.** Boundaries, principles, secrets, enforcement points,
research record, acceptance criteria.

**Out of scope.** Threats (`threat-model-v1.0.md`), authentication
controls (`authentication-security-v1.0.md`), secure coding rules
(`application-security-v1.0.md`), operations (`security-operations-v1.0.md`),
incidents (`incident-response-v1.0.md`), and everything in
`docs/55-privacy/`.

## Authority

This document sits **below** owner decisions, the decision register, the
PRD, the TRD and the UX/platform specifications, and **above**
implementation. It **must not change a product decision.** Where a
security concern exposes an unapproved product question, this document
names it, records the risk and marks it open — it does not decide it.

**No statement here is a claim of legal compliance.**

---

## 1. Security and privacy are different problems

| | **Security** | **Privacy** |
| --- | --- | --- |
| Question | Is the data protected? | Should we have the data at all? |
| Protects against | Unauthorised access, alteration, destruction, disclosure, abuse | Excessive collection, unexpected use, indefinite retention, unwanted disclosure |
| Fails by | Compromise | Over-collection, over-retention, over-sharing |
| Owner here | `docs/50-security/` | `docs/55-privacy/` |

| ID | Statement |
| --- | --- |
| SEC-1.1 | **A perfectly secure system can still violate privacy.** Encrypting data that should never have been collected is a security success and a privacy failure |
| SEC-1.2 | Security controls **never** justify collecting more personal data than the purpose requires (PRIV-1, D-51) |
| SEC-1.3 | "We log everything for security" is a privacy decision and is treated as one (SO §5) |
| SEC-1.4 | Both documents sets are binding; neither overrides the other |

---

## 2. Trust boundaries

```text
┌───────────────────────────────────────────────────────────┐
│  INTERNET — fully untrusted                               │
└───────────────────────────────────────────────────────────┘
                 │
      ┌──────────┴──────────┐
      ▼                     ▼
┌─────────────┐      ┌──────────────────┐
│ Web browser │      │ Telegram webview │   CLIENTS — untrusted
└─────────────┘      └──────────────────┘   nothing here is evidence
      │                     │
      └──────────┬──────────┘
                 ▼
╔═══════════════════════════════════════════════════════════╗
║  BOUNDARY 1 — HTTP boundary                               ║
║  TLS · security headers · input parsing · rate limiting   ║
╚═══════════════════════════════════════════════════════════╝
                 ▼
╔═══════════════════════════════════════════════════════════╗
║  BOUNDARY 2 — Authentication                              ║
║  Who is this? Resolved server-side, from a server record  ║
╚═══════════════════════════════════════════════════════════╝
                 ▼
╔═══════════════════════════════════════════════════════════╗
║  BOUNDARY 3 — Authorization                               ║
║  May they? Named permission strings (TD-03). Deny default ║
╚═══════════════════════════════════════════════════════════╝
                 ▼
┌───────────────────────────────────────────────────────────┐
│  APPLICATION SERVICES — trusted, the only place rules live│
└───────────────────────────────────────────────────────────┘
      │              │               │
      ▼              ▼               ▼
┌───────────┐ ┌──────────────┐ ┌──────────────────────────┐
│ Database  │ │ Filesystem   │ │ Outbound gateways (TD-07)│
│ MariaDB   │ │ media, logs  │ │ email · Google · Maps    │
└───────────┘ └──────────────┘ └──────────────────────────┘
   BOUNDARY 4       BOUNDARY 5          BOUNDARY 6
   storage          storage             external / cross-border
```

### 2.1 Boundary inventory

| # | Boundary | Untrusted side | Trusted side | Primary control |
| --- | --- | --- | --- | --- |
| 1 | HTTP | Internet, both clients | Application | TLS, headers, validation, rate limiting |
| 2 | Authentication | Anonymous request | Identified session | Server-side session record (S-1…S-4) |
| 3 | Authorization | Any identity | Permitted operation | Named permission strings (TD-03) |
| 4 | Database | Application | Stored data | Prepared statements, least-privilege DB user |
| 5 | Filesystem | Uploaded bytes | Served media | Non-executable storage, re-encoding |
| 6 | External | Bulbula | Third party | Single gateway namespace (TD-07), minimised payloads |
| 7 | Secrets | Everything | Configuration | `.env` on the server only (TR-186) |
| 8 | Staff surface | Public internet | `/ops/*` | Authentication + authorization + `no-store` |

### 2.2 Privileged boundaries

| ID | Rule | Source |
| --- | --- | --- |
| SEC-2.1 | **`/ops/*` is a privileged boundary.** It is never linked publicly, never indexed, and never reachable from the Mini App | WP-7.1, WP-7.2, PSE-2.4 |
| SEC-2.2 | **Route secrecy is not a control.** `/ops/*` is protected by authentication and authorization, not by being unguessable | SEC-3.4 |
| SEC-2.3 | Staff authentication is separate from and stronger than Customer authentication — **Open (D-45)** | TS-17, D-45 |
| SEC-2.4 | Staff permissions follow least privilege; the Operator/Administrator split is **Open (D-14)** | TS-18, D-14 |
| SEC-2.5 | **Where existence is itself privileged, absence is returned rather than refusal** | TR-35 |
| SEC-2.6 | Every privileged action is attributable to a named actor and time | TS-15, C-29 |
| SEC-2.7 | Staff access to personal information is **logged** | TS-20, PRIV-6 |

### 2.3 Untrusted input

Everything below crosses boundary 1 and is **never** trusted:

URL path · query string · form bodies · JSON bodies · headers ·
cookies · bearer tokens · file uploads · filenames · MIME types ·
Telegram launch context · OAuth callback parameters · `continue`
targets · referrers · user agents · hostnames · third-party API
responses · email content.

| ID | Rule |
| --- | --- |
| SEC-2.8 | **A validated Telegram launch context establishes a surface, not an identity** (TR-113, TM-7.3) |
| SEC-2.9 | **A third-party response is untrusted input**, including from Google and the email provider (D-48) |
| SEC-2.10 | **The client never supplies a role, permission, price, ranking position or identity claim** the server honours |

---

## 3. Security principles

| ID | Principle | What it forbids |
| --- | --- | --- |
| SEC-3.1 | **Least privilege** | A database user that can drop tables; staff who can do more than their job; a token with more scope than the request |
| SEC-3.2 | **Deny by default** | An endpoint that is public because nobody marked it private; an allow-list implemented as a deny-list |
| SEC-3.3 | **Server-side enforcement** | Any rule enforced only in the browser, the template or the Mini App |
| SEC-3.4 | **Explicit authorization** | Relying on hidden UI, unguessable routes, client-provided roles, surface headers, query parameters or JavaScript conditions |
| SEC-3.5 | **Secure defaults** | A new route that is unauthenticated unless secured; a new field that is public unless hidden |
| SEC-3.6 | **Defence in depth** | A single control standing alone where its failure is total |
| SEC-3.7 | **Fail closed** | An authorization check that passes when the permission service errors; a validator that accepts on exception |
| SEC-3.8 | **Minimise sensitive data** | Collecting, logging, caching or transmitting personal data the purpose does not need (D-51) |
| SEC-3.9 | **Never trust client claims** | Treating `initDataUnsafe`, a cookie value, or a hidden form field as evidence |
| SEC-3.10 | **Separate public from privileged data** | One query that returns both a public profile and a Customer's private Saves |
| SEC-3.11 | **Audit privileged actions** | A staff edit, moderation decision, verification or campaign change with no record of who and when |
| SEC-3.12 | **No security through obscurity** | Any control whose strength depends on an attacker not knowing a path, parameter or format |

---

## 4. Enforcement points

| Concern | Enforced at | Never enforced at |
| --- | --- | --- |
| Authentication | Application service boundary, from the server session record | Template, client, Telegram host |
| Authorization | Application service, per named permission | Route table alone, UI visibility, client |
| Input validation | HTTP boundary, before the domain sees it | Database constraints alone |
| Output encoding | Render time, per context | Input time |
| Rate limiting | HTTP boundary | Client |
| Audit | Application service, in the same transaction as the act | Best-effort afterwards |

| ID | Rule | Source |
| --- | --- | --- |
| SEC-4.1 | **The Web surface never calls its own HTTP API**; it invokes application services in process, so authorization lives in the service, not in the HTTP layer | TD-01 |
| SEC-4.2 | **One session concept, two transports** — cookie on Web, bearer in the Mini App, both resolving to the same server record | TD-02, S-1 |
| SEC-4.3 | A control implemented once, in the service, is correct on both surfaces. A control implemented at the HTTP layer is not | SEC-4.1 |
| SEC-4.4 | **Outbound HTTP lives in one gateway namespace**, which is therefore the single place to enforce timeouts, payload minimisation and credential handling | TD-07 |

---

## 5. Secrets

### 5.1 What is a secret

| Secret | Examples |
| --- | --- |
| **Credentials** | Database password, email provider credential (D-41), Google OAuth client secret, Maps key (D-21), Telegram bot token |
| **Keys** | Application key, signing or encryption keys, CSRF seed |
| **Tokens** | Session tokens, bearer tokens, OTP values, password-equivalent material |
| **Derived** | Session token hashes are not secrets but are **sensitive**; a database dump must not yield usable sessions (S-3) |

**Not a secret:** route paths, table names, class names, permission
string names, the CSP value, this document. Treating these as secrets
is security through obscurity (SEC-3.12).

### 5.2 Where secrets may and may not exist

| May exist | Must never exist |
| --- | --- |
| `.env` on the production server, outside the web root (TR-186) | The Git repository, in any branch or history (TR-198) |
| Process environment of the application | Log files of any level (TR-135) |
| The host's secure configuration store | Error output, stack traces or HTTP responses |
| A secure out-of-band record of `.env` (TR-221) | Client-side storage of any kind (PD-05) |
| | URLs, query strings or referrers |
| | Test fixtures, seeds or documentation (TR-198) |
| | Analytics, monitoring or crash payloads |
| | Backups that are not themselves access-controlled |

| ID | Rule | Source |
| --- | --- | --- |
| SEC-5.1 | **No secret is committed.** `.gitleaks.toml` scanning is already in CI and must not be weakened | TR-198 |
| SEC-5.2 | **No secret is logged**, including in request dumps and exception context | TR-135, TR-01e |
| SEC-5.3 | Secrets reach the application **from configuration only** | TR-01e, TR-189 |
| SEC-5.4 | **The Telegram bot token never reaches the client**; launch-context validation is server-side only | TM-7.16, TR-112 |
| SEC-5.5 | **The session token is never written to `localStorage`** on either surface | PD-05 |
| SEC-5.6 | **Only a hash of a session token is stored** | S-3 |
| SEC-5.7 | **Production and staging use different secrets.** A staging credential must never grant production access | SO §7 |
| SEC-5.8 | Rotation is possible without a code change — every secret is configuration, never a constant | TR-186 |
| SEC-5.9 | A secret is rotated on staff departure, suspected exposure, vendor change, and on any incident touching it | IR §6 |
| SEC-5.10 | **Secret custody and production configuration management are Open (D-23).** Until resolved, `.env` on the server is the mechanism and TR-186 applies | D-23, TR-197 |
| SEC-5.11 | **No secret-management vendor is selected.** Introducing one is a decision with its own privacy and cross-border analysis (`vendor-and-transfer-register-v1.0.md`) | SEC-5.10 |

---

## 6. Shared-hosting reality

| ID | Constraint | Source |
| --- | --- | --- |
| SEC-6.1 | The target is **shared hosting**: Apache with `.htaccess`, PHP 8.4, MariaDB, cron, a filesystem. No Docker, no root | `deployment.md` §2 |
| SEC-6.2 | **No control may assume** Kubernetes, a service mesh, a SIEM, an HSM, a SOC, Redis, a message broker, Elasticsearch or microservices | Phase brief §26 |
| SEC-6.3 | The security posture rests on **application controls, filesystem layout, correct permissions, secure configuration, good logging, backups, monitored dependencies and careful staff access** | SEC-6.2 |
| SEC-6.4 | **No cache service exists** (TD-05); there is no cache layer to poison or to leak personal data from | TD-05 |
| SEC-6.5 | **All background work is cron-driven console commands** (TD-06); there is no worker daemon to compromise | TD-06 |
| SEC-6.6 | A shared host means **neighbours**: the filesystem layout and permissions must assume other tenants exist | SO §9 |
| SEC-6.7 | Introducing an external service is permitted only where the architecture already allows it **and** its security and privacy impact is documented in the vendor register | VT §1 |

---

## 7. Severity model

Defined in full, with escalation, in
[`incident-response-v1.0.md`](incident-response-v1.0.md) §3. Summary:

| Level | Meaning | Example |
| --- | --- | --- |
| **Critical** | Personal data exposed, or the application controlled by an attacker | Database disclosure; `/ops/*` reachable without authentication |
| **High** | A privileged boundary or an account is compromised, bounded | A single account taken over; a privilege-escalation path |
| **Medium** | A control is weak but not known to be exploited | Missing authorization on a non-sensitive privileged route |
| **Low** | A defect with limited realistic impact | A missing hardening header |
| **Informational** | No impact; worth recording | A dependency advisory not reachable in Bulbula's code |

---

## 8. Research record

Material findings only. Generic industry advice is **not** turned into a
Bulbula requirement without a stated reason.

### 8.1 Legal

| # | Source | Finding | Bulbula impact | Affects |
| --- | --- | --- | --- | --- |
| L1 | Proclamation 1321/2024 **Art. 17(4)** | Requires measures appropriate to risk, **expressly naming pseudonymisation and encryption, confidentiality/integrity/availability/resilience, timely restoration, and a process for regularly testing and evaluating effectiveness** | Restoration testing and periodic control testing are statutory, not optional | SO §8, SO §10 |
| L2 | **Art. 22(1)** | Personal data **collected or obtained locally must be stored on a server or data centre located in Ethiopia** | LOC-1 has a direct statutory basis. **This is Art. 22, not Art. 20** | VT §5 |
| L3 | **Art. 22(3)** | **Cross-border transfer of sensitive personal data requires prior approval of the Authority** | Bulbula must avoid processing sensitive data; if any arises, transfer is gated | VT §5 |
| L4 | **Art. 20(1)** | Cross-border transfer is permitted on Authority determination, explicit informed consent, necessity, or transfer from a public register | The lawful basis for Google/email/Maps transfers must be chosen deliberately | VT §4 |
| L5 | **Art. 43(1)** | Breach notification to the Authority **within 72 hours** of becoming aware | A concrete, statutory clock | IR §7 |
| L6 | **Art. 44(1)** | Communication to the **data subject within 72 hours**, with exceptions at 44(3) including where data was rendered unintelligible, e.g. encryption | Encryption at rest has a direct, documented benefit | IR §7 |
| L7 | **Art. 33** | Controllers **and** processors must register with the Authority; separate entries per purpose | Registration is a launch dependency (L-3) | PG §6 |
| L8 | **Art. 40(1)** | A DPO is mandatory for government bodies, large-scale regular systematic monitoring, or large-scale sensitive-data processing. **"Large scale" is undefined** | Bulbula is likely outside the triggers, but this is a legal judgement (L-4) | PG §5 |
| L9 | **Art. 46** | Record of processing operations is mandatory, **including logging of reading, disclosure and transmission**, with the Authority setting log retention | Access logging of personal data is statutory, reinforcing PRIV-6/TS-20 | PG §4, RET §6 |
| L10 | **Art. 47** | DPIA required where processing may risk rights and freedoms, with four enumerated triggers | L-22 has a defined test to apply | PG §7 |
| L11 | **Art. 48(1)** | **Prior authorization** is required where the controller **cannot provide appropriate safeguards for transfer to a third-party jurisdiction** | Directly relevant to Google, email and Maps | VT §5 |
| L12 | **Art. 49** | Data protection by design **and by default** — by default only necessary data, and **not accessible to an indefinite number of people without intervention** | Statutory backing for `privacy-by-design-v1.0.md` | PBD §1 |
| L13 | **Art. 50** | On lapse of purpose, destroy **in a manner preventing reconstruction in intelligible form** | Constrains "deletion" semantics and backups | RET §8 |
| L14 | **Art. 11** | Minors: processing must advance the minor's best interests, **burden of proof on the controller**; parental consent; **reasonable efforts to verify age**; **marketing, profiling and profile-merging of minors prohibited** | The age question cannot be dodged. Bulbula does no profiling or marketing, which helps | PG §9 |
| L15 | **Art. 9(1)** | Processing sensitive personal data is **prohibited** save for enumerated exceptions | Strong reason to keep sensitive data out of Reviews and reports | PBD §5 |
| L16 | **Art. 2(5)(i)** | Sensitive personal data includes **communications data, content and metadata** | Caution with anything resembling message content | DI §6 |
| L17 | **Art. 31** | Right not to be subject to a decision **based solely on automated processing** producing legal or similarly significant effects | Requires explicit analysis of ranking and moderation | PG §8 |
| L18 | **Art. 8(5)** | **Burden of proof of consent is on the controller**; consent must be unbundled and separately presented | If consent is ever a basis, it must be evidenced | PG §3 |
| L19 | **Art. 2(2)** | Personal data expressly includes **location data and online identifiers** | IP addresses and coordinates are in scope | DI §5 |
| L20 | **Art. 15(1)** | Retain only for a reasonable period necessary for the purpose, or as defined by law | Confirms no arbitrary period may be invented | RET §1 |
| L21 | **Art. 24** | Fifteen enumerated items the data subject must be told | Becomes the checklist for the privacy notice | PNR §3 |
| L22 | **Art. 23** | Privacy rights **survive death for ten years**; heirs may invoke them | Unusual; affects deletion and rights handling | DSR §9 |
| L23 | **Art. 3(4)** | Exclusions are narrow — household activity, inter-agency need-to-know, restricted application, mere transit | **No exclusion plausibly covers Bulbula** | PG §2 |
| L24 | **Art. 35(2)** | Registration certificate is **valid two years and renewable** | A recurring obligation, not one-off | PG §6 |
| L25 | ECA PDP platform | **Could not be reached during this phase.** Secondary legal commentary indicates the registration directive was still awaited as of mid-2025 | Operational status of the regulator's machinery is **Unknown** and must be confirmed by counsel at launch | PG §6 |

> **Numbering caution.** Several widely circulated secondary summaries
> cite article numbers from an **earlier draft** (for example "consent
> at Art. 14", "adequacy at Art. 40", "minors under 16"). Those numbers
> do **not** match the enacted text in Federal Negarit Gazette No. 35 of
> 24 July 2024. **Only the enacted numbering is used in these
> documents.** Any secondary source conflicting with it is disregarded.

### 8.2 Technical

| # | Source | Finding | Bulbula impact |
| --- | --- | --- | --- |
| T1 | Telegram Mini Apps platform documentation | WebView `localStorage` may be cleared on iOS and some desktop builds | Confirms **PD-05**: no session token client-side |
| T2 | Telegram Mini Apps platform documentation | Raw launch context must be validated server-side; the host-parsed convenience object is never trustworthy; stale payloads must be rejected | TR-112, TM-7.9…TM-7.12 |
| T3 | Telegram Mini Apps platform documentation | A launch start parameter is trivially forgeable — a known referral-fraud vector | Must be resolved from validated context server-side (TM-7.15) |
| T4 | OWASP application security guidance | Authorization failures and injection remain the dominant classes of web application risk | Shapes the threat model's priority ordering, not its content |
| T5 | OWASP file upload guidance | Extension and MIME checks are insufficient alone; re-encoding and non-executable storage are the reliable controls | APP §6 |
| T6 | MDN / W3C security headers | `frame-ancestors` supersedes `X-Frame-Options` where both are understood | The current `frame-ancestors 'none'` **conflicts with embedding in Telegram** — OT-05 |
| T7 | Google Identity documentation | OAuth state is required for CSRF protection on the callback; embedded webviews are explicitly discouraged for OAuth | AS §5, TR-115 |
| T8 | Existing repository `SecureHeaders` | Already ships HSTS, `nosniff`, `DENY`, referrer policy, permissions policy and a CSP with `frame-ancestors 'none'` and `style-src 'unsafe-inline'` | Two known future changes, both recorded, neither made here |

---

## 9. Known conflicts carried forward

| # | Conflict | Status |
| --- | --- | --- |
| 1 | `frame-ancestors 'none'` and `X-Frame-Options: DENY` **prevent the Telegram Mini App from being embedded** | **Open — security decision (OT-05).** Must be resolved before the Mini App ships; see APP §7 |
| 2 | `style-src 'unsafe-inline'` is present for the pre-launch page | **Open — implementation detail.** Must be removed or justified when real templates land |
| 3 | `deployment.md` EN-4 and BK-6 cite the residency requirement as **Art. 20**; the enacted text places it at **Art. 22** | Documentation defect; corrected here. The requirement itself stands |
| 4 | The PRD and `review-policy.md` cite two lettered sub-identifiers of **D-46** that do not exist in the decision register | Documentation defect; this phase cites **D-46** only |

---

## 10. Security and privacy acceptance criteria

Testable at implementation. Every criterion traces to a source.

### 10.1 Authorization and access

| ID | Criterion | Traces to |
| --- | --- | --- |
| SAC-1 | Every `/ops/*` route rejects an unauthenticated caller | SEC-2.1, TS-18 |
| SAC-2 | Every privileged operation enforces a named permission **server-side**, verified by a test that calls the service directly | TD-03, SEC-3.4 |
| SAC-3 | Removing a UI control does not grant access; the service still refuses | SEC-3.4 |
| SAC-4 | A client-supplied role, permission or surface header changes no authorization outcome | SEC-2.10 |
| SAC-5 | Where existence is privileged, the response is absence, not refusal | TR-35 |
| SAC-6 | No public endpoint returns another Customer's email, Saves, Reviews-in-moderation or account data | SEC-3.10, PRIV-6 |
| SAC-7 | No Operations route is reachable from the Mini App | SCC §4, WP-7.1 |

### 10.2 Authentication and session

| ID | Criterion | Traces to |
| --- | --- | --- |
| SAC-8 | Authentication failure responses are identical for registered and unregistered addresses, including timing | C-31, E-5, PAU-4.1 |
| SAC-9 | An OTP cannot be replayed: second use of a consumed code fails | TR-163, AS §4 |
| SAC-10 | An expired OTP fails and offers resend | PAU-4.7 |
| SAC-11 | An expired or revoked session cannot reach a protected resource | S-6, S-7 |
| SAC-12 | The session identifier rotates on authentication and on privilege change | S-5, TR-107 |
| SAC-13 | Sign-out revokes server-side; Back reveals no authenticated content | S-7, PAU-6.12 |
| SAC-14 | Only a hash of a session token is stored; a database dump yields no usable session | S-3 |
| SAC-15 | No session token appears in client storage on either surface | PD-05 |
| SAC-16 | Telegram client data alone never establishes identity; forged or stale launch context degrades to Guest | TR-112, TM-7.14 |
| SAC-17 | An OAuth callback without a valid state parameter is rejected | AS §5 |
| SAC-18 | No password field, reset flow or password column exists anywhere | AI-2 |

### 10.3 Application

| ID | Criterion | Traces to |
| --- | --- | --- |
| SAC-19 | Every database access uses prepared statements with bound parameters; no SQL is built by interpolation | APP §3 |
| SAC-20 | CSRF protection applies to every cookie-authenticated state-changing request | APP §5 |
| SAC-21 | Uploaded media cannot execute as application code under any filename, extension or content | APP §6 |
| SAC-22 | A path-traversal filename cannot escape the media directory | APP §6 |
| SAC-23 | User-supplied text renders escaped in HTML, attribute, URL and JSON contexts | APP §4 |
| SAC-24 | An unknown query parameter is ignored and never reflected | WP §5, APP §2 |
| SAC-25 | An error response contains no stack trace, SQL, path or internal identifier | E-2, TR-144 |
| SAC-26 | `composer audit` reports no known vulnerability at release | APP §8 |

### 10.4 Data protection

| ID | Criterion | Traces to |
| --- | --- | --- |
| SAC-27 | No secret appears in the repository, logs or error output | TR-198, AC-14 |
| SAC-28 | No log line contains a credential, session identifier, bearer token or OTP value | TR-135 |
| SAC-29 | No shared cache contains personal data; authenticated responses are `no-store` | TR-120, TR-123 |
| SAC-30 | All personal data relating to one person is locatable through documented relationships | PRIV-7, TR-204 |
| SAC-31 | Personal-data columns are identifiable from schema documentation | TR-172 |
| SAC-32 | Account deletion removes or irreversibly detaches personal data per the approved retention rules | C-36, TR-205 |
| SAC-33 | A personal contact point flagged at collection is retrievable for a rights request | PCP-2, PCP-3 |
| SAC-34 | An analytics event contains nothing identifying an individual Guest | AN-3, PRIV-3 |
| SAC-35 | A data-subject request produces an auditable outcome with actor, date and result | DSR §7 |

### 10.5 Integrity

| ID | Criterion | Traces to |
| --- | --- | --- |
| SAC-36 | Sponsored delivery cannot influence organic ranking; ranking inputs contain no campaign field | D-05, D-10, TS-14 |
| SAC-37 | A Sponsored placement is labelled server-side and survives JavaScript being disabled | LB-4, PWX-7 |
| SAC-38 | Staff actions on Listings and Reviews are attributable and immutable from the application | TS-15, C-29 |
| SAC-39 | Staff access to personal information is logged | TS-20, PRIV-6 |
| SAC-40 | A reporter's identity is not disclosed to the reported party | TS-10 |

### 10.6 Process

| ID | Criterion | Traces to |
| --- | --- | --- |
| SAC-41 | A security incident is recorded with class, severity, timeline and outcome | IR §9 |
| SAC-42 | A privacy incident can be distinguished from a security incident and escalated on its own path | IR §7 |
| SAC-43 | A restore from backup has been performed and documented before launch | SO §8, Art. 17(4)(c) |
| SAC-44 | The record of processing operations exists and is current | PG §4, Art. 46 |

---

## 11. Unresolved items

| ID | Item | Status |
| --- | --- | --- |
| OT-05 | CSP `frame-ancestors` / `X-Frame-Options` vs Telegram embedding | **Open — security decision.** Blocks the Mini App |
| D-45 | Staff authentication strength | **Owner decision, open** |
| D-14 | Operator / Administrator permission split | **Owner decision, open** |
| D-13 | Identity-linking rules across Google and email | **Owner decision, open** |
| D-23 | Production configuration management and secret custody | **Open — technical decision** |
| D-42 / D-42b | Which data classes must be Ethiopia-hosted, and the vendor | **Owner decision, open.** Interacts with Art. 22 |
| D-33 | Telegram identity | **Owner decision, open.** Not an approved login mechanism |
| D-39 | Controls separating ad sales from listing data | **Owner decision, open** |
| D-27 | Analytics granularity, retention, raw-event policy | **Owner decision, open** |
| D-46 | Minimum account age, retention schedule, data-location and transfer basis | **PENDING COUNSEL** |
| OT-01 | Session lifetime and rotation values | **Open — technical decision** |
| OT-02 | OTP length, window and attempt limits | **Open — technical decision** |
| OT-07 | Observability tooling | **Open — technical decision** |
| — | Removal of `style-src 'unsafe-inline'` | **Open — implementation detail** |
| — | Whether Bulbula must appoint a DPO | **PENDING COUNSEL** (L-4) |
| — | Encryption at rest on shared hosting | **Open — security decision.** Materially affects Art. 44(3)(a) |

---

## Legal and regulatory references

| Reference | Provision | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, Art. 3 | Scope of application and exclusions | Confirmed — statute/regulation |
| Art. 6 | Processing principles | Confirmed — statute/regulation |
| Art. 9, Art. 2(5) | Sensitive personal data | Confirmed — statute/regulation |
| Art. 11 | Minors | Confirmed — statute/regulation |
| Art. 15 | Storage limitation | Confirmed — statute/regulation |
| Art. 17 | Security measures | Confirmed — statute/regulation |
| Art. 20, 21, 22 | Cross-border transfer, safeguards, data sovereignty | Confirmed — statute/regulation |
| Art. 23, 24, 31 | Duration, right to be informed, automated decisions | Confirmed — statute/regulation |
| Art. 33, 35, 40, 46, 47, 48, 49, 50, 52 | Registration, certificate, DPO, records, DPIA, prior authorization, by design, destruction, accountability | Confirmed — statute/regulation |
| Art. 43, 44 | Breach notification and communication | Confirmed — statute/regulation |
| Applicability of each to Bulbula | — | **Counsel interpretation required** |
| ECA registration process, portal, directives, adequacy determinations | — | **Unknown** — not verifiable in this phase; **PENDING COUNSEL** |

**Source of enacted text.** Federal Negarit Gazette No. 35, 24 July 2024.

---

## Decision references

D-05, D-10, D-13, D-14, D-21, D-23, D-27, D-33, D-39, D-41, D-42, D-42b,
D-45, D-46, D-48, D-51.
