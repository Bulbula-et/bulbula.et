# Application Security

| | |
| --- | --- |
| **Document** | Application Security — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

Secure development rules for the Bulbula application: input, SQL,
output, CSRF, authorization, file and media handling, HTTP security
headers, dependencies and logging.

**Out of scope.** Authentication mechanics
(`authentication-security-v1.0.md`), server and hosting operations
(`security-operations-v1.0.md`), incident handling
(`incident-response-v1.0.md`), privacy rules (`docs/55-privacy/`).

## Authority

Below the TRD and the platform specifications, above implementation.
**Changes no product decision.** No rule here may weaken an existing
test, quality gate or CI threshold.

---

## 1. Ground rules

| ID | Rule | Source |
| --- | --- | --- |
| APP-1.1 | **Plain PHP.** No framework is introduced by any rule in this document | Architectural rule |
| APP-1.2 | **Validate on input, encode on output.** Neither substitutes for the other | APP §2, §4 |
| APP-1.3 | **Allow-list, never deny-list.** A deny-list is a list of attacks someone already thought of | SEC-3.2 |
| APP-1.4 | **Fail closed.** A validator or authorization check that errors must refuse | SEC-3.7 |
| APP-1.5 | **The Web surface never calls its own HTTP API**, so security lives in the service, not the controller | TD-01 |
| APP-1.6 | A control written once in a service protects **both surfaces**. A control written in a template protects neither reliably | SEC-4.3 |
| APP-1.7 | **No rule here may be satisfied by suppressing a static-analysis finding or a mutant.** Fix the code | Standing instruction |

---

## 2. Input

### 2.1 Every input

| ID | Requirement |
| --- | --- |
| APP-2.1 | **Type validation** before use: integers are integers, dates are dates, booleans are booleans. No PHP loose comparison on untrusted input |
| APP-2.2 | **Length limits** on every string, enforced server-side, sized to the column and the purpose |
| APP-2.3 | **Enum allow-lists** for every constrained value: `sort`, `open_now`, `min_rating`, `verified`, status, category and permission names |
| APP-2.4 | **Query-parameter allow-list.** Only named parameters are honoured; unknown ones are ignored and **never reflected** (WP §5) |
| APP-2.5 | **No mass assignment.** Fields are assigned explicitly; a request body never maps wholesale onto a record |
| APP-2.6 | **Required means required**: absence is rejected, not defaulted to something convenient |
| APP-2.7 | **Canonicalise before validating** — decode once, normalise, then check. Never validate then decode |
| APP-2.8 | Rejection is **explicit and safe**: no echo of the offending value into HTML or logs |
| APP-2.9 | Numeric bounds are enforced, including pagination page and size, to bound query cost (THR-10) |
| APP-2.10 | Array and nested-structure inputs are **depth- and size-limited** |

### 2.2 URLs

| ID | Requirement |
| --- | --- |
| APP-2.11 | A stored URL (business website, social link) is validated for **scheme allow-list (`http`, `https` only)**, host shape and length |
| APP-2.12 | **`javascript:`, `data:`, `vbscript:` and `file:` are rejected** |
| APP-2.13 | A redirect target (`continue`) is validated as an **internal destination**; absolute and protocol-relative targets are rejected (THR-06) |
| APP-2.14 | **No user-supplied URL is fetched server-side.** There is no URL-fetch feature in V1; introducing one creates SSRF exposure and is a new decision |
| APP-2.15 | Absolute URLs Bulbula generates are built **from configuration, never from the request `Host`** (THR-13) |

### 2.3 JSON

| ID | Requirement |
| --- | --- |
| APP-2.16 | JSON bodies are **size-limited** before parsing |
| APP-2.17 | Parsing uses a **depth limit**; decode failure is a 400 with the standard envelope, never an exception to the User |
| APP-2.18 | The parsed structure is validated against an expected shape; **extra keys are ignored, never absorbed** (APP-2.5) |
| APP-2.19 | Content type is verified; a mismatched type is rejected |

### 2.4 Free text

| ID | Requirement |
| --- | --- |
| APP-2.20 | Review and report text is **length-limited** and stored **as text, never as markup** |
| APP-2.21 | **No HTML is accepted from any User or Guest**, ever. There is no rich-text capability in V1 |
| APP-2.22 | Text is **not auto-linked** on render (THR-53) |
| APP-2.23 | Control characters and bidirectional-override characters are stripped or rejected |
| APP-2.24 | **Nothing solicits sensitive personal data** (Art. 9); where it appears unsolicited it is a moderation matter (THR-54) |

---

## 3. SQL

| ID | Requirement | Source |
| --- | --- | --- |
| APP-3.1 | **PDO prepared statements with bound parameters for every query that touches untrusted input** | SAC-19 |
| APP-3.2 | **No SQL is built by string interpolation or concatenation of untrusted values.** No exceptions for "it's only an integer" | THR-03 |
| APP-3.3 | Identifiers that cannot be bound — column and table names for sorting — come from a **hard-coded allow-list mapped from an enum**, never from the request | APP-2.3 |
| APP-3.4 | `LIMIT` and `OFFSET` are bound or cast to validated integers with enforced bounds | APP-2.9 |
| APP-3.5 | **PDO emulated prepares are disabled** so the driver sends real prepared statements | APP-3.1 |
| APP-3.6 | Errors surface as exceptions and are handled; **no silent failure** | TR-142 |
| APP-3.7 | **Transactions wrap any multi-statement change that must be atomic** — publication, moderation decisions, campaign changes, account deletion, OTP consumption | AS-3.6 |
| APP-3.8 | A failed transaction **rolls back and rethrows**; it never leaves a partial state |
| APP-3.9 | The application database user holds **only the privileges it needs**. It does not need `DROP`, `CREATE USER`, `FILE` or `GRANT` in normal operation | SEC-3.1 |
| APP-3.10 | **No raw SQL is accepted from any interface**, including the console |
| APP-3.11 | Search queries are built from **structured, validated criteria**, never by pasting user text into the statement | `search-design.md` |
| APP-3.12 | Database errors are **never rendered to the User** (TR-144) | E-2 |

---

## 4. Output

| ID | Requirement | Source |
| --- | --- | --- |
| APP-4.1 | **Encoding is context-sensitive.** HTML body, HTML attribute, URL component, JavaScript and JSON are five different contexts with five different rules | THR-01 |
| APP-4.2 | **Escape at render time, in the template layer**, so the stored value stays canonical | APP-1.2 |
| APP-4.3 | **Escaping is the default.** Any unescaped output is explicit, rare, reviewed and justified in a comment | SEC-3.5 |
| APP-4.4 | **No user or staff input is ever rendered as HTML** (APP-2.21) |
| APP-4.5 | URLs rendered into `href` or `src` are **validated (APP-2.11) and attribute-escaped** |
| APP-4.6 | **No data is interpolated into inline JavaScript.** Pass values through data attributes or a JSON script block with correct encoding | APP-4.1 |
| APP-4.7 | JSON responses are encoded by the serialiser, **never assembled by concatenation** |
| APP-4.8 | JSON responses carry `Content-Type: application/json` and **`X-Content-Type-Options: nosniff`** (already shipped) |
| APP-4.9 | Error responses follow the API envelope and expose **no internal detail** (E-2, TR-144) |
| APP-4.10 | Responses echoing a user-supplied value do so **escaped**, or not at all (APP-2.8) |
| APP-4.11 | **The Sponsored label is rendered server-side** and cannot be removed by suppressing script (LB-4, PWX-7) |

---

## 5. CSRF

### 5.1 Where it applies

| Transport | Credential sent automatically by the browser? | CSRF token required |
| --- | --- | --- |
| **Web — session cookie** | **Yes** | **Yes, on every state-changing request** |
| **Mini App — bearer token** | **No** — attached explicitly by Bulbula's own code | **No** |
| Unauthenticated public reads | No credential | No |
| Unauthenticated writes (Guest report) | No credential | **Yes** — see APP-5.5 |

| ID | Requirement | Source |
| --- | --- | --- |
| APP-5.1 | **Every cookie-authenticated state-changing request carries a CSRF token** — Save, Review, Report, account changes, deletion, and every `/ops/*` write | SAC-20, THR-04 |
| APP-5.2 | The token is **per-session, unpredictable, and verified server-side in constant time** |
| APP-5.3 | **Verification is centralised**, not repeated per controller, so a new route cannot forget it (SEC-3.5) |
| APP-5.4 | `SameSite` on the session cookie is **defence in depth, not the control** — browser behaviour varies and the token is mandatory regardless (AS-7.13) |
| APP-5.5 | Guest writes carry a token too: it is not an authorization control, but it blocks trivial cross-site submission and abuse (THR-09) |
| APP-5.6 | **Bearer-authenticated requests are exempt** because the browser never attaches the token automatically; the exemption is **by transport, never by surface header** | AS-7.24 |
| APP-5.7 | A surface header, user agent or client claim **must never** be accepted as evidence that CSRF protection can be skipped | SEC-2.10 |
| APP-5.8 | **`GET` never changes state.** A state change reachable by `GET` is a defect, not a CSRF-token problem | SEC-3.2 |
| APP-5.9 | Token failure returns a clear, non-technical error with a safe retry — the User's input is preserved (PEH-4.2) |

---

## 6. File and media security

Applies to staff-uploaded business photography. **There is no
Customer or Guest upload capability in V1**; introducing one is a new
product decision.

| ID | Requirement | Source |
| --- | --- | --- |
| APP-6.1 | **Size limits** enforced before processing, at both the web server and the application | THR-10 |
| APP-6.2 | **Extension allow-list** of permitted raster image types |
| APP-6.3 | **Declared MIME is not trusted**; the type is determined by inspecting content |
| APP-6.4 | **Content validation**: the bytes must actually parse as the claimed image type |
| APP-6.5 | **Re-encode every accepted image.** This is the control that defeats polyglots and embedded payloads; extension and MIME checks alone do not | T5, THR-46 |
| APP-6.6 | **SVG is rejected.** It is a script-capable document, not a raster image | THR-46 |
| APP-6.7 | Stored **outside any executable path**; the storage directory must not execute PHP under any configuration | SAC-21 |
| APP-6.8 | **Randomised, server-generated filenames.** The uploaded name is never used on disk | THR-67 |
| APP-6.9 | The original filename, if retained for display, is **treated as untrusted text** and escaped (APP-4.1) |
| APP-6.10 | **No path component from the request reaches the filesystem path.** Traversal sequences cannot escape the media directory | SAC-22 |
| APP-6.11 | **Directory indexing is disabled** for media paths | THR-67 |
| APP-6.12 | Media is served with a **correct, non-sniffable content type** and never as `text/html` |
| APP-6.13 | **EXIF and metadata are stripped on re-encode.** Camera GPS coordinates and device identifiers are personal data and are not published | DI §4, PBD §9 |
| APP-6.14 | **Unpublished media is not web-reachable** | THR-67 |
| APP-6.15 | Media storage provider, limits and formats are **Open (D-25)**; every rule above binds whatever is chosen | D-25 |
| APP-6.16 | Photographs may contain identifiable people; that is a **privacy matter** governed by `privacy-by-design-v1.0.md` §9, not solved by the controls above | PBD §9 |

---

## 7. HTTP security headers

### 7.1 Current, shipped state

`src/Http/Middleware/SecureHeaders.php` already applies:

| Header | Value |
| --- | --- |
| `Strict-Transport-Security` | `max-age=31536000; includeSubDomains` (HTTPS requests only) |
| `X-Content-Type-Options` | `nosniff` |
| `X-Frame-Options` | `DENY` |
| `Referrer-Policy` | `strict-origin-when-cross-origin` |
| `Permissions-Policy` | `geolocation=(), microphone=(), camera=()` |
| `Content-Security-Policy` | `default-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline'; object-src 'none'; base-uri 'self'; frame-ancestors 'none'` |

| ID | Requirement | Source |
| --- | --- | --- |
| APP-7.1 | **This posture is preserved.** No header is removed or weakened by this phase | Standing instruction |
| APP-7.2 | **No new value is invented here.** Two changes are known to be needed and are recorded as open, not made | Phase brief §8 |

### 7.2 Known required changes

| # | Change | Why | Status |
| --- | --- | --- | --- |
| 1 | **`frame-ancestors` must admit the Telegram host** | `'none'` plus `X-Frame-Options: DENY` **prevents the Mini App from loading at all** | **Open — security decision (OT-05).** Blocks the Mini App |
| 2 | **`X-Frame-Options` must be reconciled with `frame-ancestors`** | A legacy `DENY` can override a permissive `frame-ancestors` in some clients | **Open — security decision**, part of OT-05 |
| 3 | **`style-src 'unsafe-inline'` should be removed** | Present for the pre-launch page; it materially weakens XSS defence | **Open — implementation detail**, when real templates land |
| 4 | **`geolocation=()` must admit `self`** when Nearby ships | Nearby needs geolocation on Bulbula's own origin | **Open — implementation detail** |

| ID | Requirement |
| --- | --- |
| APP-7.3 | **`frame-ancestors` must list only Telegram's origins** — never `*`, never a scheme-only source (OT-05) |
| APP-7.4 | Permissions-Policy stays **deny-by-default**; a capability is added only when a shipped feature needs it (APP-7.2, item 4) |
| APP-7.5 | CSP must not be relaxed to `unsafe-eval` or a wildcard host for any reason |
| APP-7.6 | **HSTS is only sent over HTTPS** (already correct); preload is a separate, deliberate decision |
| APP-7.7 | Any CSP change ships with a test asserting the resulting value |
| APP-7.8 | **No third-party origin appears in any directive**, consistent with there being no third-party script (PD-14) |

---

## 8. Dependencies

| ID | Requirement | Source |
| --- | --- | --- |
| APP-8.1 | **`composer.lock` is committed** and is the source of truth for installed versions |
| APP-8.2 | **`composer audit` runs in CI** and a known vulnerability fails the build | SAC-26 |
| APP-8.3 | **Versions are verified against the actual PHP requirement**, never guessed | Standing instruction |
| APP-8.4 | Automated dependency update proposals (Dependabot or equivalent) are enabled; **updates are reviewed, not auto-merged** |
| APP-8.5 | **Abandoned packages are treated as a security finding**, not a style issue; `composer` reports them and they are tracked |
| APP-8.6 | A new dependency requires a justification: what it does, why it is not written in-house, its maintenance state and its licence | Standing instruction |
| APP-8.7 | **No dependency is added that introduces a framework** | APP-1.1 |
| APP-8.8 | Development dependencies are **not installed in production** |
| APP-8.9 | **Secret scanning (`.gitleaks.toml`) stays in CI** and is not weakened | TR-198 |
| APP-8.10 | **No CI quality threshold is lowered and no mutant is suppressed** to accommodate a security change | Standing instruction |
| APP-8.11 | Supply-chain posture for V1 is **lockfile + audit + review**. A signing or SBOM regime is **Future**, not a V1 requirement | SEC-6.2 |

---

## 9. Logging

### 9.1 Never logged

| ID | Never logged | Source |
| --- | --- | --- |
| APP-9.1 | Passwords — **none exist** (AI-2), so any appearance is a defect | AI-2 |
| APP-9.2 | **OTP values**, in any form | AS-3.8 |
| APP-9.3 | **Session identifiers and bearer tokens** | TR-135 |
| APP-9.4 | Any secret: database, email, Google, Maps, **Telegram bot token** | TR-135, TR-01e |
| APP-9.5 | **Full personal records** | TR-135 |
| APP-9.6 | Request bodies or headers wholesale — they contain the above | APP-9.3 |
| APP-9.7 | **Telegram launch-context payloads** — they contain a signature and user data | AS-10.3 |
| APP-9.8 | Personal data beyond what the event genuinely needs | PRIV-1 |

### 9.2 Always logged

| ID | Requirement | Source |
| --- | --- | --- |
| APP-9.9 | Every log line carries the **request correlation id**, environment and severity | TR-134 |
| APP-9.10 | Operational events needing review are logged at a surfacing level: **failed logins, authorization denials, moderation actions, campaign changes, email failures, job failures** | TR-136 |
| APP-9.11 | **Privileged actions are auditable**: actor, action, subject, time, in the same transaction as the act | TS-15, C-29, SEC-3.11 |
| APP-9.12 | **Staff access to personal information is logged** | TS-20, PRIV-6 |
| APP-9.13 | **Audit records are append-only from the application.** No console path edits or deletes them | THR-45 |
| APP-9.14 | Security events (AS-7.26) are logged **without the credential** | AS-7.27 |
| APP-9.15 | A correlation id shown to a User is **meaningless on its own** and reveals nothing | PEH-8.12 |

### 9.3 Logs are personal data

| ID | Requirement | Source |
| --- | --- | --- |
| APP-9.16 | **Logs containing IP addresses or account identifiers are personal data** — Art. 2(2) names online identifiers and location data explicitly | DI §5 |
| APP-9.17 | Art. 46(4) requires logs of reading, disclosure and transmission of personal data, and **the Authority sets their retention period** — currently **Unknown** | RET §6 |
| APP-9.18 | Log retention is configured so logs cannot exhaust shared-host disk, and interacts with the retention schedule — **PENDING COUNSEL (L-21)** | TR-140, L-21 |
| APP-9.19 | **Logs are not a backup** and must not be relied on to reconstruct state | TR-224 |
| APP-9.20 | Logs live **outside the web root** | THR-65 |

---

## 10. Unresolved items

| ID | Item | Status |
| --- | --- | --- |
| OT-05 | `frame-ancestors` and `X-Frame-Options` vs Telegram | **Open — security decision.** Blocks the Mini App |
| D-25 | Media storage provider, limits, formats | **Owner decision, open** |
| D-27 | Analytics granularity and raw-event policy | **Owner decision, open** |
| L-21 | Log and audit retention | **PENDING COUNSEL** |
| — | Removal of `style-src 'unsafe-inline'` | **Open — implementation detail** |
| — | `geolocation=(self)` when Nearby ships | **Open — implementation detail** |
| — | Whether to adopt CSP reporting | **Open — implementation detail**; must not become a third-party endpoint (PD-14) |
| — | Rate-limit implementation on shared hosting without a cache service | **Open — implementation detail** (TD-05) |
| — | Encryption at rest for the database | **Open — security decision** |

---

## Legal and regulatory references

| Reference | Relevance | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, Art. 17(1), 17(4) | Appropriate technical measures; pseudonymisation and encryption; regular testing of effectiveness | Confirmed — statute/regulation |
| Art. 46(1), 46(4) | Record of processing including logging of reading, disclosure and transmission | Confirmed — statute/regulation |
| Art. 46(4)(e) | **The Authority establishes log retention periods** | Confirmed — statute/regulation; **the periods themselves are Unknown** |
| Art. 9, Art. 2(5) | Sensitive data must not be solicited (APP-2.24) | Confirmed — statute/regulation |
| Art. 49(3) | By default only necessary personal data is processed — bears on logging breadth | Confirmed — statute/regulation |
| Whether Bulbula's logging satisfies Art. 46 | — | **Counsel interpretation required** |

---

## Decision references

D-25, D-27.
