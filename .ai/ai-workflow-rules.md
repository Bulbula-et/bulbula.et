# AI Workflow Rules

> **Read this before touching any file in this repository.**
> These rules are binding on every coding agent working on Bulbula.

```text
Source baseline:   b5d606612c10e2a7a3c284b69fafab507f0b92fb
Last derived from: 2026-10-08
Context status:    Current
```

---

## 1. Local guardrails

These are `.ai/` rules about agent behaviour. They are **not** owner
decisions and must never be written as `D-xx`.

| ID | Guardrail |
| --- | --- |
| **AI-G-01** | Never change product behaviour without a traced source. Every behavioural change cites a `C-xx`, a `D-xx`, or a numbered requirement in a formal document |
| **AI-G-02** | Never convert an open question into a closed one. If it is `Open`, `PENDING COUNSEL` or `PENDING PILOT`, it stays that way |
| **AI-G-03** | Never invent a number. No price, SLA, threshold, retention period, staffing figure, ranking weight or capacity target |
| **AI-G-04** | Prefer omission to invention. A visible gap is better than a plausible fabrication |
| **AI-G-05** | An architecture boundary is changed by a decision, never by a test exclusion or a config edit |
| **AI-G-06** | Propose, do not decide. An agent may recommend an owner-level change; it may not make one |
| **AI-G-07** | If a formal document and `.ai/` disagree, follow the formal document and report the drift |
| **AI-G-08** | If two formal documents disagree, stop and report. Do not pick one silently |
| **AI-G-09** | Leave the repository greener than you found it, never looser. Gates go up or stay |
| **AI-G-10** | Report what you did not do, and why, as plainly as what you did |

---

## 2. Before changing code

The agent **MUST**, in order:

1. **Read the relevant `.ai/` context files** — at minimum
   `project-overview.md`, plus the context file for the area being touched
   (see `source-map.md`).
2. **Locate the formal source document** for the change. If none exists, that
   is itself a finding — stop and report.
3. **Check the decision register** (`docs/60-decisions/decision-register.md`)
   for applicable `D-xx` decisions, including ones that *forbid* the change.
4. **Check the relevant capability `C-xx`** in `docs/10-product/prd-v1.0.md`
   §12 and its acceptance criteria.
5. **Check the technical requirements** — `docs/30-technical/trd-v1.0.md`
   (`TR-xxx`), plus `architecture.md`, `data-model.md`, `api-spec-v1.0.md`,
   `search-design.md` or `auth-identity.md` as applicable.
6. **Check security and privacy requirements** for anything touching
   authentication, authorization, personal data, media, logging or secrets —
   `docs/50-security/` and `docs/55-privacy/`.
7. **Check UX and platform requirements** for anything user-facing —
   `docs/20-ux-ui/` and `docs/35-platforms/`. A change that behaves
   differently on Web and Telegram is a **product decision**, not an
   implementation choice.
8. **Determine whether the request is V1 scope** against
   `docs/10-product/scope-v1.md`. *If it is not listed in §1, it is not in
   V1. Absence from the exclusion list is not permission.*
9. **Identify unresolved decisions** that the change would touch, and confirm
   the change does not silently resolve one.
10. **Do not invent missing requirements.** A gap in the specification is
    reported, not filled.

If any of steps 2, 4, 8 or 9 produces an unclear answer, apply the escalation
rule in §7 before writing code.

---

## 3. While changing code

The agent **MUST**:

- **Follow the existing architecture.** Request → Middleware → Router →
  Controller → Application Service → Domain → Repository → Database/Gateway.
- **Preserve the enforced boundaries.** They are architecture tests, not
  conventions (`architecture-context.md` §3).
- **Extend the architecture tests to cover any new namespace** rather than
  leaving it unguarded (TR-01).
- **Preserve terminology.** Use the glossary terms exactly: Business, Branch,
  Listing, Business profile, Save, Review, Rating, Permission, Verification,
  Sponsored, Organic ranking (`docs/10-product/glossary.md`).
- **Preserve security controls.** Server-side authorization on every request,
  deny by default, parameterised SQL, encoded output, no secrets in source.
- **Preserve privacy controls.** Collect only what has a stated purpose; keep
  personal data out of shared caches, logs and analytics events.
- **Preserve accessibility.** Keyboard operability, non-colour-only meaning,
  text alternatives, legibility at increased text size.
- **Preserve test quality.** Add tests with the change; never weaken existing
  ones.
- **Write one implementation of every rule**, shared by Web, API and Telegram.

The agent **MUST NOT**:

- Add a dependency without a recorded reason weighed against the four-library
  runtime baseline.
- Introduce framework creep of any kind.
- Change architecture silently.
- Change product behaviour silently.
- Implement a future capability as if it were V1.
- Duplicate a business rule per surface.
- Relax an architecture test to accommodate a foreseeable requirement —
  that is the exact failure mode the boundary exists to prevent (TD-07).

---

## 4. Before finishing

The agent **MUST**:

1. **Run the relevant tests.** At minimum `composer test`.
2. **Run the quality checks** the change touches — see
   `code-standards.md` §7 for the exact commands.
3. **Inspect the diff**, file by file. Not the summary — the diff.
4. **Verify no secrets** were added: no token, key, password, `.env` value or
   credential in code, config, fixture, test or documentation.
5. **Verify no accidental product scope.** Did anything appear that
   `scope-v1.md` §2 excludes?
6. **Verify documentation impact.** Does a formal document now need to
   change? Does a `.ai/` context file need its status set to
   *Needs review*?
7. **Verify decision traceability.** Can every behavioural change be traced
   to a `C-xx`, `D-xx` or numbered requirement?
8. **Report unresolved issues** — open questions hit, assumptions made,
   contradictions found, and anything deliberately left undone.

---

## 5. Forbidden agent behaviour

### 5.1 Never make an owner-level decision

- Never **invent a product decision**.
- Never **change, reinterpret or silently resolve a `D-xx`**.
- Never **create a `D-xx`**. Local AI rules use `AI-G-xx`.
- Never **rewrite formal documentation to justify an implementation choice**.

### 5.2 Never introduce an excluded capability

All of the following are excluded from V1. Each has a decision behind it.

| Never add | Decision |
| --- | --- |
| A Business account of any kind | D-54 |
| Business login or a business principal in the authorization model | D-54, TR-40 |
| Business-created Listings, claims, or owner profile control | D-02 |
| An owner dashboard or owner-facing analytics | D-54 |
| Owner replies to Reviews | D-12 |
| Self-service advertising purchase or billing | D-10, D-11 |
| Auctions or bidding | D-10 |
| CPC, CPM, CPA or any performance pricing | D-10 |
| Programmatic or third-party ad networks | D-10 |
| Paid influence on organic ranking | D-10, ADV-8 |
| Purchasable verification or trust markers | D-10, ADV-9 |
| Behavioural advertising or targeting of individuals | D-10, NFR-PR4 |
| Sale of customer data | `business-model.md` |
| Password authentication | D-48 |
| Sign in with Apple | D-48 (revisit: D-47) |
| Facebook, X or other social providers | D-48 |
| Phone / SMS authentication | D-48 |
| Telegram as a login provider | D-48; identity handling **Open (D-33)** |
| A Flutter or native client in V1 | D-15 |
| Native push notifications | Requires a native client |
| Online ordering, reservations, bookings | Out of product scope |
| Loyalty programmes, jobs, local news, subscriptions | Out of product scope |
| Messaging or leads between Users and Businesses | Future |
| Review photos | D-36 (Deferred) |
| "Helpful" voting on Reviews | D-37 (Deferred) |
| An Amharic or Afaan Oromo interface | D-18 — requires separate approval |
| Offline mode / offline-first infrastructure | Not in V1 |
| A payment gateway | D-10, D-11 |

### 5.3 Never introduce unapproved infrastructure

| Never add | Why |
| --- | --- |
| Laravel, Symfony full-stack, Laminas, Mezzio, Dotkernel, Slim, Flight | Architecture test; owner instruction |
| Any ORM or DBAL (Doctrine, Eloquent, Propel, Cycle) | Architecture test |
| A dependency-injection container or service locator | Composition over magic |
| Redis, because it is familiar | App cache is filesystem-backed; add only where measurement justifies it (TRD §24, OT-03) |
| Elasticsearch, because search exists | MariaDB is sufficient for V1 behind a port (TR-48) |
| A message broker, without an approved reason | NG-8 |
| A SPA, because it is conventional | Server-rendered first; SPA explicitly rejected (D-16) |

### 5.4 Never weaken quality or security

- Never **weaken, delete or skip a test** to make a change pass.
- Never **suppress a mutant**. A surviving mutant is a weak test — fix the
  test.
- Never **lower coverage, type coverage, PHPStan level or mutation
  thresholds** (D-26).
- Never **disable or bypass a CI check**, including gitleaks and
  `composer audit`.
- Never **bypass authorization**, or rely on hidden UI as protection.
- Never **trust a client-provided role, permission or identity claim**.
- Never **trust Telegram launch context without server-side validation** —
  and even validated, it establishes a *surface*, not an identity (TR-113).
- Never **store unnecessary personal data**.
- Never **expose private data in a public response** — provenance, Permission
  records, moderation internals, staff identities and reporter identity are
  all internal (TR-25, TS-10).
- Never **put a secret in source code**, a fixture or a client bundle.
- Never **log an OTP, token, session id, credential or full personal record**
  (TR-135).

> An agent may **propose** any of the above as a change for the owner to
> decide. It may not **do** it.

---

## 6. Scope test

Before implementing anything, answer:

```text
Is it in docs/10-product/scope-v1.md §1 (the 40 capabilities)?
        ├── Yes → in V1. Find its C-xx and its acceptance criteria.
        └── No  → NOT in V1. Stop.
                  Absence from the exclusion list is not permission.
```

Architecture **seams** that keep a future model possible are permitted.
**Features** for it are not (BND-4).

---

## 7. Handling ambiguity

```text
An approved decision exists
    → follow it

No approved decision, and it is an implementation detail only
    → choose the simplest architecture consistent with the TRD
    → record the choice in the change description

Product behaviour is unclear
    → STOP. Do not decide.
    → mark Open and report

Legal or privacy meaning is unclear
    → mark PENDING COUNSEL and report

A threshold depends on the pilot
    → mark PENDING PILOT and report

Technical design conflict
    → consult the TRD and the decision register before proceeding
    → if they do not resolve it, stop and report
```

**An agent must never convert uncertainty into certainty merely to keep
coding.**

The difference that matters:

| Implementation choice | Product decision |
| --- | --- |
| Which class owns a method | Whether a User can do something |
| Table or index shape, given the data model | What data is collected |
| Error message wording within the content rules | What a User is told about state |
| Caching a computed value | Whether a value is shown at all |
| Test structure | What behaviour is guaranteed |
| Agent decides it | **Owner decides it** |

---

## 8. Reporting

Every completed change reports:

- what changed, and the `C-xx` / `D-xx` / `TR-xxx` it traces to;
- test and quality-gate results;
- scope confirmation (nothing from §5.2 appeared);
- open items encountered and left open;
- any formal contradiction discovered;
- any `.ai/` file that now needs review;
- what was deliberately **not** done.
