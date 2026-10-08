# Security and Privacy Context

```text
Source baseline:   b68ce5acf32afd6b525226a90343e3196f258a0e
Last derived from: 2026-10-07
Context status:    Current
```

**Derived from** `docs/50-security/` (six documents) ·
`docs/55-privacy/` (seven documents) ·
`docs/30-technical/auth-identity.md` · `trd-v1.0.md` §§7–9.
**Authority:** those documents. **This file never relaxes a control.**

> **SEC-1.1 — A perfectly secure system can still violate privacy.**
> Encrypting data that should never have been collected is a security
> success and a privacy failure. Security controls never justify collecting
> more personal data than the purpose requires (SEC-1.2, D-51).
> **Both document sets are binding; neither overrides the other** (SEC-1.4).

---

## 1. Authentication — fixed ground

| ID | Rule |
| --- | --- |
| **AS-1.1** | **Google and email OTP only** (D-48) |
| **AS-1.2** | **No passwords anywhere** — no field, no column, no reset flow, no strength meter |
| **AS-1.3** | **No Apple sign-in** |
| **AS-1.4** | **Telegram is not an authentication provider** (D-33) |
| **AS-1.5** | **Businesses do not authenticate** — no business login, claim flow or owner portal (D-02, D-54) |
| **AS-1.6** | Browsing, searching and reporting need **no account** (GS-1) |
| **AS-1.7** | Reviewing, Saving and reporting a Review need an account |
| **AS-1.8** | **One identity across both surfaces** (C-32) |
| **AS-1.9** | Staff use the same providers; staff authentication **strength is Open (D-45)** and must exceed Customer authentication |

**If you are about to write a `password` column, a `claim` route or an Apple
provider, you have misread the specification. Stop.**

### Derived implementation constraints

| Rule | Source |
| --- | --- |
| An OTP is stored **hashed**, is single-use, short-lived and rate-limited | `authentication-security-v1.0.md` §3 |
| OTP verification is **constant-time** and reveals nothing about account existence | §3 |
| Session identifiers are **regenerated on privilege change** | §7 |
| Session cookies are `HttpOnly`, `Secure`, `SameSite` | §7, TR-109 |
| Sign-out invalidates **server-side**, not merely the cookie | §8 |
| Identity linking is **explicit and verified**, never inferred from a matching email alone | §6 |
| Telegram launch data is **validated server-side** before any trust; it establishes a surface, not an identity | §10, TM-7.3, TM-7.9, TM-8.8 |

---

## 2. Authorization

| Rule | Source |
| --- | --- |
| **Authorization is server-side, in the application service.** Never in a controller, never in a template, never in JavaScript | TR-38 |
| **Deny by default.** A missing rule denies | TR-37 |
| A hidden UI element is **not** an authorization control | TR-40 |
| Every operations endpoint authorizes **per request**, never per session only | TR-29 |
| Ownership is checked on the **object**, not on the identifier in the URL | TR-39 |
| Guest is a first-class role with its own explicit permissions, not "logged-out" | GS-1, TR-34 |
| Roles: **Guest · Customer · Operator · Administrator** — no business role exists | D-54 |
| **A single Administrator is a known continuity risk**, recorded in BC §3.3. Do not design around it; do not add roles to work around it | BC-3.3 |

---

## 3. Input, output, SQL, CSRF

| Area | Rule | Source |
| --- | --- | --- |
| **Input** | Validate on the **server**, always. Client validation is a convenience | APP §2 |
| | **Allow-list** shape, type, length and range. Reject, do not sanitise into validity | APP §2 |
| | Validation failures preserve user input and say what is wrong | TR-148 |
| **SQL** | **Prepared statements only.** No concatenated SQL, ever | APP §3, TRD §9 |
| | Identifiers (table, column, sort direction) that come from input are mapped through an **allow-list**, never interpolated | APP §3 |
| **Output** | **Escape at the point of output**, contextually — HTML, attribute, URL, JS, CSS are different contexts | APP §4 |
| | Never render an internal field publicly: provenance, Permission records, verification evidence, personal-contact flags, reporter identity | TR-25, PCP-3, PCP-5 |
| | Error responses in production carry **no** stack trace, SQL, path or internal id | TR-144, EN-1 |
| **CSRF** | Every **state-changing** request carries a token, verified server-side | APP §5 |
| | `GET` never changes state | APP §5 |
| **Files** | Uploads are type-, size- and content-checked; stored **outside the document root**; never executed; served with a safe content type | APP §6 |
| **Headers** | CSP, `X-Content-Type-Options`, `Referrer-Policy`, frame protection and HSTS are set — note the Mini App's framing requirement | APP §7 |
| **Deps** | `composer audit --locked` runs in CI; a vulnerable dependency is an incident, not a backlog item | APP §8 |

---

## 4. Secrets

| Rule | Source |
| --- | --- |
| **No secret in the repository. Ever.** Not in code, config, fixtures, tests, docs or `.ai/` | SC-2, TR-186 |
| Secrets live in `.env` on the server, outside the document root, readable only by the application user | PS-2 |
| **Never log** a credential, session id, bearer token, OTP code or full personal record | TR-135 |
| `gitleaks` 8.30.1 scans the working tree **and full git history** on every PR, on `main`, and weekly | `security.yml` |
| A gitleaks finding means **rotation**, not merely removal from history | SC-5 |
| Keys are rotatable without a code change; rotation is a documented procedure | SO §4 |
| A new variable goes into `.env.example` in the same change — **with a placeholder, never a real value** | REL-7.3 |

---

## 5. Privacy — the governing law

**Proclamation No. 1321/2024** (Federal Negarit Gazette No. 35, 24 July
2024) is treated as applicable.

> **Numbering warning.** Several widely-circulated online summaries use
> **draft-era article numbers** ("consent at Art. 14", "adequacy at Art. 40",
> "minors under 16"). Those do **not** match the enacted text. Use only the
> article map in `docs/55-privacy/privacy-governance-v1.0.md`.

| Article | Obligation that shapes the build |
| --- | --- |
| 2(2) | Personal data **includes location data and online identifiers** |
| 2(5) | Sensitive data **includes communications data, content and metadata** |
| 7(2) | Six lawful bases, including legitimate interests |
| 8 | Consent must be free, informed, specific, clear and by **active action**; unbundled; **burden of proof on the controller** |
| 8(4) | A service may not be conditioned on consent to processing not necessary for it |
| 11 | Minors: best interests; controller bears the burden; **reasonable age-verification efforts**; **marketing, profiling and profile-merging prohibited** |
| 17(4) | Pseudonymisation, encryption, resilience, timely restoration, **regular testing of effectiveness** |
| 20–21 | Cross-border transfer bases and pre-transfer safeguards |
| **22** | **Data sovereignty — local storage in Ethiopia**; sensitive-data transfer needs **prior Authority approval** |
| 24 | Right to be informed — **15 specified items**; 24(2)–(3) impose timing duties where data was **not obtained from the subject** |
| 25–32 | Access · rectification · erasure · objection · restriction · automated decisions · portability |
| 33–39 | Controller registration and certificate (2-year, renewable) |
| 40–41 | DPO triggers and duties — **"large scale" is undefined in the text** |
| 42 | Technical and organisational measures |
| 43–44 | Breach notification: Authority within **72 h**; data subject within **72 h** (exceptions, including encryption) |
| 45–46 | Prior security check; record of processing and logging |
| 47–48 | DPIA (four triggers); prior authorization / consultation |
| 49 | **Data protection by design and by default** |
| 50 | Duty to destroy so there is **no intelligible reconstruction** |
| 51–52 | Joint controllers; accountability |

---

## 6. Privacy rules that bind implementation

| Rule | Source |
| --- | --- |
| **Collect the minimum.** Every field needs a purpose before it needs a column | D-51, PRIV-1 |
| **Purpose limitation**: data collected for one purpose is not reused for another without a new basis | PBD |
| **Privacy by design and default**: the default setting is the most protective one | Art. 49, PBD |
| **Save is private** — no counts, no public lists, no social graph | C-14 |
| **Reporter identity is never exposed**, to anyone outside staff | PCP-5 |
| **No behavioural advertising, no profiling-based targeting, no sale or sharing of customer data** | D-39, ADV exclusions |
| **No tracking a Guest across sessions for advertising purposes** | D-39 |
| A **personal contact point** published on a Business profile is personal data; the business's **Permission is not that individual's consent** | PG-7.7 |
| Deletion means **deleted, not flagged** — and destroyed so it cannot be intelligibly reconstructed | Art. 50, RET |
| Retention is **defined per data category**; nothing is kept "just in case" | RET |
| Backups are in scope for retention and for deletion requests, with documented limits | RET, BK |
| Every processing activity is in the **record of processing**; a new field updates `data-inventory-v1.0.md` | Art. 46, DI |
| Every vendor that touches personal data is in the **vendor and transfer register** | VT |
| A **DPIA is required** before processing that meets a trigger — not after | Art. 47 |
| A breach starts the **72-hour clock** at awareness, not at confirmation | Art. 43–44, IR |

### Data-subject rights — the request paths that must exist

Informed · access · rectification · erasure · objection · restriction ·
portability · not-subject-to-automated-decision.
Detail and windows: `docs/55-privacy/data-subject-rights-v1.0.md` (DSR-).
**The windows themselves are PENDING COUNSEL (L-7).**

---

## 7. PENDING COUNSEL

**These are unresolved legal questions. Do not resolve them in code,
in a comment, in a test or in this file.** Where a build decision depends on
one, stop and escalate.

| ID | Question | Status |
| --- | --- | --- |
| **L-2** | Cross-border transfer basis | PENDING COUNSEL |
| **L-3** | Controller registration obligation and timing | PENDING COUNSEL |
| **L-4** | Whether a DPO is required (Art. 40 "large scale" is undefined) | PENDING COUNSEL |
| **L-5** | **The lawful basis for each processing purpose** | PENDING COUNSEL |
| **L-6** | Legitimate-interests assessment, if that basis is used | PENDING COUNSEL |
| **L-7** | Rights procedures, windows and identity-verification standard | PENDING COUNSEL |
| **L-8** | Publishing business data, and personal contact data, without the individual's consent | PENDING COUNSEL |
| **L-10** | Hosting location and Art. 22 data sovereignty | PENDING COUNSEL |
| **L-12** | Processor and sub-processor terms | PENDING COUNSEL |
| **L-15** | Reviews as personal data; defamation exposure | PENDING COUNSEL |
| **L-16** | Takedown and removal obligations | PENDING COUNSEL |
| **L-17** | Advertising disclosure obligations under local law | PENDING COUNSEL |
| **L-18** | **Whether, and on what basis, Bulbula may photograph premises and people** | PENDING COUNSEL |
| **L-19** | Maps provider terms | PENDING COUNSEL |
| **L-20** | Business-entity, tax and invoicing obligations | PENDING COUNSEL |
| **L-21** | **Retention periods, or the criteria that determine them** | PENDING COUNSEL |
| **L-22** | Breach-notification mechanics with the Authority | PENDING COUNSEL |
| **D-46** | Minimum account age (Art. 11 minors) | PENDING COUNSEL |

Also pending, and flagged in the formal documents:

- **PNR-3.14** — whether any automated decision-making or profiling exists
  that must be disclosed.
- **PNR-3.18 / PNR-5.7** — Art. 24(2)–(3) timing where data was **not**
  obtained from the subject. **Bulbula discloses by publishing, so this is
  acute**; a published notice may not discharge the duty.
- **PBD-9.11** — until L-18 is answered, the strict photography defaults
  (PBD-9.2, PBD-9.3) apply.

> **The valid legal IDs are `L-2`…`L-8`, `L-10`, `L-12`, `L-15`…`L-22`.**
> `L-1`, `L-9`, `L-11`, `L-13` and `L-14` appear only in the superseded
> discovery briefs (v0.2 / v0.3) and **must not be cited as live items**.

---

## 8. Telegram — security specifics

| Rule | Source |
| --- | --- |
| Validate the signed launch payload **server-side** before trusting anything in it | TM-7.9 |
| Validated launch context establishes a **surface, not an identity** | TM-7.3, TR-113 |
| **Never trust a client-provided identity claim** | TM-8.8 |
| A launch parameter is trivially forgeable — resolve it server-side | TM-7.15 |
| Saved items are **server-side**; nothing identifying is stored on the device | TM-11.4 |
| Whether Telegram identity may become an account model is **Open (D-33)** | D-33 |

---

## 9. Threat model and incident response — orientation

`threat-model-v1.0.md` (`THR-`) works through eight surfaces — public Web,
Customer accounts, Mini App, operations console, Reviews, advertising, data —
plus **STRIDE coverage** (§9) and an explicit **accepted risks** register
(§10). An accepted risk is accepted **by an owner decision**, not by an
implementer.

`incident-response-v1.0.md` (`IR-`) defines detection, severity, roles,
containment, the **72-hour** regulatory clock, communication and
post-incident review. `security-operations-v1.0.md` (`SO-`) covers patching,
access review, key rotation, log review and monitoring.

---

## 10. Absolute prohibitions

| Never | Source |
| --- | --- |
| Add a password field, column, hash or reset flow | AS-1.2, D-48 |
| Add Apple sign-in | AS-1.3, D-48 |
| Treat Telegram as an authentication provider | AS-1.4, D-33 |
| Build a business login, claim flow or owner portal | AS-1.5, D-02, D-54 |
| Authorize in a controller, template or in JavaScript | TR-38 |
| Hide a UI element and call it an authorization control | TR-40 |
| Concatenate a value into SQL | APP §3 |
| Trust client-side validation | APP §2 |
| Commit, log or print a secret, token or OTP | SC-2, TR-135 |
| Collect a personal field without a stated purpose | D-51 |
| Infer, assume, or write down a lawful basis | L-5, PG-7.1 |
| Invent a retention period | L-21 |
| Invent a rights-response window | L-7 |
| State a legal conclusion anywhere in code or `.ai/` | AI-G-03 |
| Build behavioural targeting, profiling-based ads, or sell customer data | D-39 |
| Expose reporter identity, provenance, or verification evidence publicly | PCP-3, PCP-5, TR-25 |
| Resolve a `PENDING COUNSEL` item in order to unblock a build | AI-G-02 |
| Suppress a gitleaks or `composer audit` finding | SC-5, AI-G-09 |

---

## 11. Where to open the formal document

| Question | Open |
| --- | --- |
| How does sign-in actually work? | `docs/50-security/authentication-security-v1.0.md` |
| Input, SQL, output, CSRF, files, headers, deps | `docs/50-security/application-security-v1.0.md` |
| Trust boundaries, defence in depth | `docs/50-security/security-architecture-v1.0.md` |
| What are we defending against? | `docs/50-security/threat-model-v1.0.md` |
| Patching, access review, rotation, monitoring | `docs/50-security/security-operations-v1.0.md` |
| What to do when something happens | `docs/50-security/incident-response-v1.0.md` |
| What data exists, where, and why | `docs/55-privacy/data-inventory-v1.0.md` |
| How long we keep it | `docs/55-privacy/data-retention-v1.0.md` |
| Rights requests | `docs/55-privacy/data-subject-rights-v1.0.md` |
| Design-time privacy obligations | `docs/55-privacy/privacy-by-design-v1.0.md` |
| The Proclamation article map and governance | `docs/55-privacy/privacy-governance-v1.0.md` |
| What the notice must say | `docs/55-privacy/privacy-notice-requirements-v1.0.md` |
| Vendors and transfers | `docs/55-privacy/vendor-and-transfer-register-v1.0.md` |
