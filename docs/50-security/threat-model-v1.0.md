# Threat Model

| | |
| --- | --- |
| **Document** | Threat Model — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

Threats against V1 as specified: public Web, Customer accounts, the
Telegram Mini App, the Operations console, Reviews, advertising and
data. STRIDE is used as a checklist where it helps and ignored where it
would add ceremony.

**Out of scope.** Threats against capabilities V1 does not have —
business accounts, owner claims, payments, native apps, offline sync,
message notifications. Modelling them would invent scope.

## Authority

Below the PRD, TRD, UX/UI and platform specifications. **Identifies
risk; decides no product question.** Where a threat implies an
unapproved product change, it is recorded as open.

---

## 1. What is worth attacking

| Asset | Why it is valuable | Worst outcome |
| --- | --- | --- |
| **Customer personal data** | Email addresses, Saves, Reviews, account history | Statutory breach (Art. 43, 44) and loss of trust |
| **The Operations console** | Publishes, verifies, moderates, runs campaigns | An attacker controls what Bulbula says is true |
| **Listing integrity** | The product *is* accurate business information | A wrong phone number or address sends people to the wrong place |
| **Review integrity** | The only user-generated trust signal | A directory with bought reviews is worthless |
| **Sponsored/organic separation** | The commercial promise and the editorial promise | Paid placement silently becomes ranking |
| **Verification status** | A claim Bulbula makes about reality | A forged Verified badge is a lie with Bulbula's name on it |
| **Audit trail** | The evidence that staff actions were legitimate | Undetectable insider abuse |
| **Secrets** | Database, email, Google, Maps, Telegram bot | Total compromise |
| **Media** | Photographs of premises | Defacement; a stored-XSS or RCE vector |
| **Availability** | A directory nobody can reach | Reputation, not safety |

### 1.1 Who attacks

| Actor | Motivation | Realistic capability |
| --- | --- | --- |
| **Opportunistic scanner** | Whatever is easy | Automated, high volume, no Bulbula knowledge. **The most likely attacker by far** |
| **Competing business** | Suppress a rival, inflate itself | Low technical skill, high local motivation, may pay for reviews |
| **Spammer** | Links, SEO, scams | Automated account creation and review posting |
| **Scraper** | Rebuild the directory elsewhere | Trivially achievable against public data |
| **Aggrieved individual** | A review, a report, a listing | Harassment, defamation, doxxing |
| **Insider** | Mistake far more often than malice | Full legitimate access within their permission set |
| **Targeted attacker** | Data, defacement, leverage | Low probability for V1; not designed against beyond fundamentals |

### 1.2 Priority

Priority = impact × likelihood, judged for a small directory on shared
hosting in its first release. **Not** an enterprise risk matrix.

- **P1** — must be controlled before launch.
- **P2** — must be controlled before the risk becomes real.
- **P3** — accepted for V1 with the acceptance recorded.

---

## 2. Public Web

| ID | Threat | Attack path | Impact | Pri | Existing control | Required control | Residual | Owner | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| THR-01 | **Stored XSS** via Review text, report text or a listing field | Staff or Customer input rendered unescaped | Session theft, defacement, staff account compromise | **P1** | — | Context-sensitive output escaping (APP §4); CSP without `unsafe-inline` | Low | Engineering | Open |
| THR-02 | **Reflected XSS** via search query or error echo | `q` or an error message rendered raw | Phishing, token theft | **P1** | Opaque error rendering (TR-142) | Escape on output; never reflect unknown parameters (WP §5) | Low | Engineering | Open |
| THR-03 | **SQL injection** | Any untrusted value reaching a query | Full data disclosure | **P1** | PDO foundation | Prepared statements everywhere; no interpolation (APP §3) | Low | Engineering | Open |
| THR-04 | **CSRF** on cookie-authenticated writes — Save, Review, Report, account deletion | Cross-site form post | Actions taken as the Customer | **P1** | — | Token on every cookie-authenticated state change (APP §5) | Low | Engineering | Open |
| THR-05 | **Malicious query parameters** — mass assignment, filter injection, sort injection | Unexpected parameter honoured | Data exposure, expensive queries | **P1** | Named filters only; allow-listed `sort` | Allow-list enforced server-side; unknown ignored | Low | Engineering | Open |
| THR-06 | **Malicious URLs** — open redirect via `continue` | `/signin?continue=//evil` | Credential phishing with a Bulbula origin | **P1** | Continuation-URL safety (WP §5) | Validate as internal destination before redirect (PAU-6.6) | Low | Engineering | Open |
| THR-07 | **Malicious outbound links** in Reviews or listing social links | Link to malware or phishing | Harm to Users; reputation | **P2** | Review moderation (TS-7) | URL validation on save; no automatic link rendering in Reviews | Medium | Operations | Open |
| THR-08 | **Scraping** the directory | Sequential crawl of public pages | Competitor copies the dataset | **P3** | Public by design | Rate limiting; **accepted** — the data is published to be read | **Accepted** | Owner | Accepted |
| THR-09 | **Automation abuse** of public endpoints — report spam, search flooding | Scripted submission | Operational load; cost | **P2** | — | Rate limiting per endpoint, without disclosing limits (E-6) | Medium | Engineering | Open |
| THR-10 | **Denial-of-service pressure** on shared hosting | Expensive search queries, image requests | Site unavailable; neighbour impact | **P2** | Short-cached search (TR-122) | Query cost bounds; pagination limits; host-level protection | Medium | Engineering | Open |
| THR-11 | **Cache poisoning** — a personal response cached publicly | Wrong `Cache-Control`/`Vary` | One Customer sees another's data | **P1** | `no-store` on authenticated (TR-120); no personal data cached (TR-123) | Test that authenticated responses are never public-cacheable | Low | Engineering | Open |
| THR-12 | **Sensitive data leakage** in errors | Stack trace or SQL in a 500 | Reconnaissance | **P1** | Opaque 500 with reference id (TR-142, TR-144) | Keep; test it | Low | Engineering | Controlled |
| THR-13 | **Host header / absolute URL injection** | Forged `Host` used to build links or emails | Phishing via Bulbula-generated links | **P2** | — | Build absolute URLs from configuration, never from the request | Low | Engineering | Open |
| THR-14 | **Clickjacking** | Bulbula framed by a hostile site | UI redress | **P2** | `frame-ancestors 'none'`, `X-Frame-Options: DENY` | **Conflicts with Telegram embedding — OT-05** | Low | Owner | **Open (OT-05)** |

---

## 3. Customer accounts

| ID | Threat | Attack path | Impact | Pri | Existing control | Required control | Residual | Owner | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| THR-15 | **Account takeover via OTP brute force** | Guess a short code | Full account access | **P1** | — | Attempt limits per code, per address, per source; code invalidated on exhaustion (OT-02) | Low | Engineering | Open |
| THR-16 | **OTP replay** | Reuse an observed code | Account access | **P1** | Single-use (TR-163) | Mark consumed atomically; reject second use (SAC-9) | Low | Engineering | Controlled |
| THR-17 | **OTP interception** via email compromise | Attacker controls the mailbox | Account access | **P2** | — | Out of Bulbula's control; limit code lifetime; notify on new sign-in | **Accepted** | Owner | Accepted |
| THR-18 | **Session theft** | XSS, network interception, shared device | Impersonation | **P1** | HTTPS everywhere (PS-5); hashed tokens (S-3) | `HttpOnly`, `Secure`, `SameSite`; rotation (S-5); revocation (S-7) | Low | Engineering | Open |
| THR-19 | **Session fixation** | Attacker fixes a session before sign-in | Impersonation | **P1** | Rotation on authentication (S-5, TR-107) | Keep; test it | Low | Engineering | Controlled |
| THR-20 | **Account enumeration** | Different response for a known address | Target list; privacy harm | **P1** | Identical responses required (C-31, E-5) | Equal responses **and** equal timing (SAC-8) | Low | Engineering | Open |
| THR-21 | **Identity-linking abuse** — claim an account by registering its email at a provider | Google identity collides with an email identity | **Account takeover by design flaw** | **P1** | — | Link only on a **verified** email; never auto-merge on an unverified claim. **Policy is Open (D-13)** | Medium | Owner | **Open (D-13)** |
| THR-22 | **Provider-identity reassignment** | A provider reassigns an address to a new person | Wrong person inherits an account | **P2** | — | Store the provider's stable subject identifier, not only the email | Medium | Engineering | Open |
| THR-23 | **Email abuse — Bulbula as a spam cannon** | Request OTPs for arbitrary addresses | Deliverability destroyed; harassment | **P1** | — | Per-address and per-source send limits; resend back-off (PAU-4.5) | Low | Engineering | Open |
| THR-24 | **Unauthorised Review manipulation** | Edit or delete another Customer's Review | Integrity | **P1** | — | Ownership check in the service, not the UI (SAC-2) | Low | Engineering | Open |
| THR-25 | **Unauthorised Save access** | Read another Customer's Saved list | Privacy breach — Saves reveal interests | **P1** | Save is private (UR-18) | Ownership-scoped queries; never addressable by another identity | Low | Engineering | Open |
| THR-26 | **Account deletion abuse** | Delete someone else's account; or deletion as a vandalism route | Data loss; harassment | **P2** | — | Authenticated, confirmed, auditable; deletion never removes others' records (TR-167) | Low | Engineering | Open |
| THR-27 | **Mass account creation** | Scripted sign-ups | Review spam substrate | **P2** | Email verification required | Rate limits; **no CAPTCHA** (WCAG 3.3.8) — rely on verification and anomaly review | Medium | Operations | Open |

---

## 4. Telegram Mini App

| ID | Threat | Attack path | Impact | Pri | Existing control | Required control | Residual | Owner | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| THR-28 | **Forged launch context** | Craft an init payload claiming a user | Impersonation if trusted | **P1** | Server-side validation required (TR-112) | Signature verification, constant-time compare, freshness window; **never trust the parsed convenience object** | Low | Engineering | Open |
| THR-29 | **Replay of a captured launch context** | Reuse a valid old payload | Impersonation | **P1** | Freshness rejection (TM-7.12) | Reject stale `auth_date`; window value is open | Low | Engineering | Open |
| THR-30 | **Spoofed launch parameter** | Forge a start parameter to claim attribution or access | Fraud; unauthorised context | **P2** | Must resolve from validated context (TM-7.15) | Treat as untrusted input; resolve server-side | Low | Engineering | Open |
| THR-31 | **Malicious deep link** | Craft a link to an unexpected destination or injected state | Phishing within a trusted surface | **P2** | Deep-linkable set is closed (IAR §5.1) | Resolve only to known destinations; never eval parameters | Low | Engineering | Open |
| THR-32 | **Client-side trust error** — treating host data as authentication | Developer uses `initDataUnsafe` | **Trivial impersonation** | **P1** | TM-7.10 forbids it | Code review rule; the adapter exposes capabilities, not host objects (TM-3.2) | Low | Engineering | Open |
| THR-33 | **Token leakage via client storage** | Session token in `localStorage`, wiped or read | Session theft; silent logout | **P1** | **PD-05** forbids it | Verify no client storage holds the token (SAC-15) | Low | Engineering | Controlled |
| THR-34 | **Unsafe external navigation** | Link out without host mediation, or without warning | Phishing; lost unsaved state | **P2** | Host link methods only (PD-09); warn before leaving (TM-9.4) | Keep | Low | Engineering | Open |
| THR-35 | **Host runtime assumption failure** | Assume a Bot API version or host feature | Blank screen; broken capability | **P2** | **PD-06** capability detection | Documented fallback for every host feature (TM-3.4) | Low | Engineering | Controlled |
| THR-36 | **Bot token exposure** | Token shipped to the client for local validation | **Total Telegram compromise** | **P1** | Server-side only (TM-7.16) | Never reference the token in client code | Low | Engineering | Controlled |
| THR-37 | **Embedding refused by own headers** | `frame-ancestors 'none'` blocks the Mini App | Surface does not function | **P1** | — | Resolve **OT-05** deliberately; admit only Telegram origins | Medium | Owner | **Open (OT-05)** |

---

## 5. Operations console

The highest-impact surface in V1. It is small, staffed by few people,
and it decides what the public sees.

| ID | Threat | Attack path | Impact | Pri | Existing control | Required control | Residual | Owner | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| THR-38 | **Staff account compromise** | OTP-only staff login phished or intercepted | **Attacker controls published truth** | **P1** | TS-17 requires stronger-than-Customer auth | Resolve **D-45**; staff auth must not rely on email OTP alone | **High until D-45** | Owner | **Open (D-45)** |
| THR-39 | **Privilege escalation** Operator → Administrator | Missing check on a high-risk action | Taxonomy, campaigns, user management abused | **P1** | — | Named permission per operation, checked in the service; **D-14 open** | Medium | Owner | **Open (D-14)** |
| THR-40 | **Operator/Administrator boundary failure** | Shared permission set in practice | Least privilege defeated | **P2** | TS-18 | Define the split (D-14); test each boundary | Medium | Owner | **Open (D-14)** |
| THR-41 | **Unauthorised listing publication** | Publish without review | False information with Bulbula's authority | **P1** | Lifecycle in `listing-operations.md` | Publication is a permissioned, audited transition | Low | Engineering | Open |
| THR-42 | **Verification manipulation** | Mark Verified without evidence | **A lie carrying Bulbula's name** | **P1** | Verification rules **Open (D-08)** | Verification requires a recorded basis and an audit entry | Medium | Owner | **Open (D-08)** |
| THR-43 | **Moderation abuse** | Remove honest Reviews; keep paid ones | Review integrity destroyed | **P1** | TS-9 policy basis recorded; TS-14 forbids commercial suppression | Every decision records policy ground, actor, time; periodic review | Medium | Operations | Open |
| THR-44 | **Campaign activation abuse** | Activate a campaign without a sold order | Revenue leakage; integrity | **P2** | Staff-managed fixed packages (D-10) | Permissioned, audited; **D-39 separation controls open** | Medium | Owner | **Open (D-39)** |
| THR-45 | **Audit-log tampering** | Staff edit or delete their own audit trail | Insider abuse becomes undetectable | **P1** | Audit required (C-29, TS-15) | **Audit records are append-only from the application**; no console delete path; deleting a Customer must not delete audit history (TR-167) | Medium | Engineering | Open |
| THR-46 | **Media upload attack** | Upload a crafted image, SVG or polyglot | Stored XSS or code execution | **P1** | — | Re-encode; non-executable storage; randomised filenames; reject SVG (APP §6) | Low | Engineering | Open |
| THR-47 | **Console exposed publicly** | Indexed, linked, or reachable from the Mini App | Attack surface handed over | **P1** | Never linked or indexed (WP-7.2, PSE-2.4) | Response-level exclusion plus authorization (PSE-2.2) | Low | Engineering | Controlled |
| THR-48 | **Insider error** — wrong listing edited, wrong review removed | Ordinary mistake | Incorrect public information | **P2** | Audit trail | Reversibility and a correction route (COR-1…COR-4) | Medium | Operations | Open |

---

## 6. Reviews

| ID | Threat | Attack path | Impact | Pri | Existing control | Required control | Residual | Owner | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| THR-49 | **Review spam** | Automated posting | Noise; trust collapse | **P1** | Authentication required (D-12); anti-abuse (TS-12) | Rate limits, duplicate detection, anomaly review | Medium | Operations | Open |
| THR-50 | **Coordinated manipulation** | Many accounts, one target | Ratings become fiction | **P1** | TS-12 anomaly review | Pattern detection on timing, source and text similarity; staff escalation | **Medium — hard problem** | Operations | Open |
| THR-51 | **Review bombing** | Sudden mass negative reviews | A business destroyed by a mob | **P2** | Moderation (TS-7) | Rate-of-change alerting; temporary moderation hold is a **product question — Open** | Medium | Owner | **Open** |
| THR-52 | **Harassment or doxxing** in review text | Publish a person's details | Serious harm to an individual; legal exposure | **P1** | Policy forbids content about named private individuals (TS-11) | Reporting route (TS-8); prompt takedown; **L-15 open** | Medium | Operations | **PENDING COUNSEL (L-15)** |
| THR-53 | **Malicious links** in review text | Phishing or malware link | Harm to Users | **P1** | Moderation | Do not auto-link review text; validate and strip | Low | Engineering | Open |
| THR-54 | **Sensitive personal data** in review text | Reviewer writes health, religion or ethnicity about a person | **Art. 9 prohibited category processed unintentionally** | **P2** | — | Policy; moderation; prompt removal. **No sensitive field is ever solicited** | Medium | Operations | Open |
| THR-55 | **Fraudulent identity use** | Review impersonating a real named person | Defamation attributed to an innocent party | **P2** | Only necessary identity is exposed (PBD §6) | Display-name policy; no claim of verified authorship | Medium | Owner | Open |
| THR-56 | **Reviewer deanonymisation** | Cross-referencing display name with other data | Retaliation against a reviewer | **P2** | Minimal public identity | Publish the minimum; never expose email or account identifier | Medium | Engineering | Open |

---

## 7. Advertising

| ID | Threat | Attack path | Impact | Pri | Existing control | Required control | Residual | Owner | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| THR-57 | **Unauthorised sponsorship** | Campaign created without a real order | Revenue and integrity loss | **P2** | Staff-managed only (D-10) | Permissioned creation; audit; reconciliation against orders (D-11) | Medium | Operations | Open |
| THR-58 | **Inventory manipulation** | Exceed documented placement counts | Users see more ads than the product promises | **P2** | Fixed inventory in the PRD | Enforce counts server-side, not in the template | Low | Engineering | Open |
| THR-59 | **Campaign privilege abuse** | Staff self-serve a favour | Integrity; unbilled inventory | **P2** | Audit (TS-15) | Separate the sales relationship from activation — **D-39 open** | Medium | Owner | **Open (D-39)** |
| THR-60 | **Paid/organic integrity failure** | A campaign field reaches the ranking inputs | **The core editorial promise broken** | **P1** | Separation is approved (D-05, D-10); labelled always (LB-4) | Ranking inputs must contain no campaign field — testable (SAC-36) | Low | Engineering | Open |
| THR-61 | **Reporting manipulation** | Inflate delivery figures to an advertiser | Fraud against a customer | **P2** | Metrics from aggregates (AN-2) | Metrics derived from recorded events only; no manual adjustment path | Medium | Engineering | Open |
| THR-62 | **Advertiser pressure on listing data or Reviews** | Commercial leverage on editorial | Trust destroyed | **P1** | TS-14, TS-16 forbid it | **D-39** control design; escalation route independent of sales | Medium | Owner | **Open (D-39)** |

---

## 8. Data

| ID | Threat | Attack path | Impact | Pri | Existing control | Required control | Residual | Owner | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| THR-63 | **Database compromise** | SQL injection, credential theft, host compromise | **Statutory breach; all Customer data** | **P1** | Prepared statements; hashed session tokens (S-3) | Least-privilege DB user; no remote DB access; encryption at rest is **Open** | Medium | Engineering | Open |
| THR-64 | **Backup leakage** | Backup readable in the web root or stored abroad | Full disclosure; **and a cross-border transfer** | **P1** | BK-6 binds backups to residency | Backups outside the web root, access-controlled, location-bound (Art. 22) | Medium | Engineering | **Open (D-42)** |
| THR-65 | **Log leakage** | Logs in the web root; secrets or personal data in lines | Disclosure | **P1** | TR-135 forbids secrets in logs | Logs outside the web root; rotation (TR-140); no personal records | Low | Engineering | Open |
| THR-66 | **Exported data leakage** | A rights-request export emailed unprotected or left on disk | Disclosure of everything about one person | **P2** | — | Exports are transient, access-controlled, deleted after collection (DSR §6) | Medium | Operations | Open |
| THR-67 | **Media leakage** | Directory listing; guessable filenames | Unpublished media exposed | **P2** | — | Randomised filenames; no directory indexing; unpublished media not web-reachable | Low | Engineering | Open |
| THR-68 | **Cross-border processor exposure** | Personal data reaches Google, the email provider, Maps or a CDN | **Art. 22 residency and Art. 20 transfer obligations engaged** | **P1** | LOC-1…LOC-5; VT register | Per-vendor lawful basis — **PENDING COUNSEL (L-10, L-12)** | **High until resolved** | Owner | **PENDING COUNSEL** |
| THR-69 | **Analytics re-identification** | Events detailed enough to single out a Guest | Covert profiling; AN-3 violated | **P2** | AN-3 forbids it | No identifier, no precise location, no fingerprinting; **D-27 open** | Medium | Owner | **Open (D-27)** |
| THR-70 | **Over-retention** | Data kept because nobody deleted it | Art. 15 and Art. 50 exposure; larger breach impact | **P1** | Retention schedule **PENDING COUNSEL** (L-21, D-46) | Expiry supported in schema (TR-208); period from configuration | **High until L-21** | Owner | **PENDING COUNSEL** |
| THR-71 | **Shared-host neighbour exposure** | Another tenant reads Bulbula's files | Disclosure of `.env`, backups, media | **P1** | — | Restrictive permissions; nothing sensitive world-readable; `.env` outside the web root | Medium | Engineering | Open |
| THR-72 | **Deletion that does not delete** | Soft delete marketed as erasure; data survives in backups | Art. 50 non-compliance; a false promise to a User | **P1** | TR-171 distinguishes soft from real deletion | Define logical vs hard deletion vs anonymisation; **backup expiry is the honest limit** (RET §8) | Medium | Owner | **Open — privacy decision** |

---

## 9. STRIDE coverage

| Category | Where it is covered |
| --- | --- |
| **Spoofing** | THR-15…THR-22, THR-28…THR-32, THR-36, THR-55 |
| **Tampering** | THR-01…THR-05, THR-24, THR-41, THR-42, THR-45, THR-58, THR-60 |
| **Repudiation** | THR-45, THR-48, THR-57 |
| **Information disclosure** | THR-11, THR-12, THR-20, THR-25, THR-56, THR-63…THR-69, THR-71 |
| **Denial of service** | THR-09, THR-10, THR-23 |
| **Elevation of privilege** | THR-06, THR-32, THR-38…THR-40, THR-46 |

---

## 10. Accepted risks

Recorded as accepted for V1, with the reason. Acceptance is the owner's,
not engineering's.

| ID | Risk | Why accepted |
| --- | --- | --- |
| THR-08 | Public data is scrapeable | The data is published to be read. Defending it would damage the product and SEO |
| THR-17 | OTP interception via a compromised mailbox | Outside Bulbula's control; mitigated by short code lifetime |
| THR-50 | Coordinated review manipulation cannot be fully prevented | A genuinely hard problem; mitigated by detection and staff review, not solved |
| — | No formal penetration test before launch | Cost. Revisit after launch; not a licence to skip the controls above |

---

## 11. Unresolved items

| ID | Item | Status |
| --- | --- | --- |
| D-45 | Staff authentication strength — **gates THR-38** | **Owner decision, open** |
| D-14 | Permission split — **gates THR-39, THR-40** | **Owner decision, open** |
| D-13 | Identity-linking rules — **gates THR-21** | **Owner decision, open** |
| D-39 | Ad/editorial separation — **gates THR-44, THR-59, THR-62** | **Owner decision, open** |
| D-08 | Verification rules — **gates THR-42** | **Owner decision, open** |
| D-27 | Analytics granularity — **gates THR-69** | **Owner decision, open** |
| D-42 / D-42b | Data location and vendor — **gates THR-64, THR-68** | **Owner decision, open** |
| D-46 | Retention and minimum age — **gates THR-70** | **PENDING COUNSEL** |
| OT-05 | `frame-ancestors` vs Telegram — **gates THR-14, THR-37** | **Open — security decision** |
| OT-02 | OTP limits — **gates THR-15** | **Open — technical decision** |
| L-15 | Review liability and takedown — **gates THR-52** | **PENDING COUNSEL** |
| L-10 / L-12 | Cross-border basis — **gates THR-68** | **PENDING COUNSEL** |
| L-21 | Retention schedule — **gates THR-70, THR-72** | **PENDING COUNSEL** |
| — | Temporary moderation hold during review bombing (THR-51) | **Open — product decision.** Not decided here |
| — | Encryption at rest (THR-63) | **Open — security decision** |

---

## Legal and regulatory references

| Reference | Relevance | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, Art. 9 and Art. 2(5) | Sensitive data appearing unsolicited in Reviews (THR-54) | Confirmed — statute/regulation |
| Art. 15, Art. 50 | Over-retention and deletion that does not delete (THR-70, THR-72) | Confirmed — statute/regulation |
| Art. 20, 21, 22 | Processor and backup location (THR-64, THR-68) | Confirmed — statute/regulation |
| Art. 43, 44 | Consequences of THR-63 materialising | Confirmed — statute/regulation |
| Whether any specific threat creates liability for Bulbula | — | **Counsel interpretation required** |

---

## Decision references

D-05, D-08, D-10, D-11, D-12, D-13, D-14, D-27, D-39, D-42, D-42b, D-45,
D-46.
