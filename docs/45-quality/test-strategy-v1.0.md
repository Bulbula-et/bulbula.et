# Test Strategy

| | |
| --- | --- |
| **Document** | Test Strategy — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

What is tested, at which level, with what tools, against what
thresholds, and which behaviours **must** have a test because the
product's integrity depends on them.

**Out of scope.** Thresholds policy (`quality-strategy-v1.0.md` §4),
CI as a release mechanism (`release-management-v1.0.md`), launch
verification (`production-readiness-v1.0.md`), manual operational
checks (`../40-operations/`).

## Authority and precedence

| ID | Rule |
| --- | --- |
| TST-0.1 | **The existing suite and configuration are the baseline.** This document describes and extends; it does not redesign |
| TST-0.2 | **No threshold is lowered, no mutant is suppressed, no failure is hidden** | QS-2.1…QS-2.3 |
| TST-0.3 | **No test tool beyond those already in `composer.json` is adopted here.** Adding one is a decision |
| TST-0.4 | **Every acceptance criterion a test asserts must already exist in the PRD or the TRD.** Tests verify requirements; they do not create them |

---

## 1. Existing baseline

| Item | Value |
| --- | --- |
| Test framework | **Pest 4** over PHPUnit |
| Suites | `unit` (PHPUnit-style, `tests/Unit`) · `feature` (Pest-style, `tests/Feature`) · `arch` (`tests/Arch`) |
| Current size | **424 tests, 1 033 assertions** |
| Line coverage | **100 %**, enforced at `--min=100` |
| Type coverage | **100 %**, enforced at `--min=100` |
| Static analysis | **PHPStan at max** on `src` |
| Mutation testing | **Infection**, `minMsi: 100`, `minCoveredMsi: 100`, `--testsuite=unit` |
| Test database | **In-memory SQLite** |
| Production database | **MariaDB via PDO** |

| ID | Rule | Source |
| --- | --- | --- |
| TST-1.1 | **The suite split is meaningful, not cosmetic**: `unit` is what Infection mutates, so a behaviour that matters must have unit-level coverage or its mutants survive | `infection.json5` |
| TST-1.2 | **Migrations stay in portable SQL**, or provide an engine-specific branch, because the suite runs on SQLite | TR-173 |
| TST-1.3 | **SQLite is not MariaDB.** Anything depending on engine-specific behaviour — collation, full-text, strictness — is verified against MariaDB outside the suite | TR-173, §6 |

---

## 2. Test levels

| Level | Answers | Where | Notes |
| --- | --- | --- | --- |
| **Architecture** | Are the boundaries intact? | `tests/Arch` | Fails the build; extended per new namespace (TR-01) |
| **Unit** | Does this piece behave? | `tests/Unit` | Mutated by Infection; no I/O (TR-05) |
| **Feature** | Does this capability behave end to end in-process? | `tests/Feature` | HTTP in, response out |
| **Contract** | Does the API match its specification? | Feature | Per `api-spec-v1.0.md` |
| **Manual** | Does it make sense to a person? | Operations | §7 |

| ID | Rule |
| --- | --- |
| TST-2.1 | **Domain logic is free of I/O and is therefore unit-testable without infrastructure** (TR-05) |
| TST-2.2 | **Every application service is callable from Web, API and CLI**, so a service is tested once and not three times (TR-04, AC-4) |
| TST-2.3 | **No test reaches a real external service.** Outbound HTTP is behind one gateway interface and is substituted in tests (TR-01a, TD-07) |
| TST-2.4 | **No test depends on wall-clock time.** The clock is injected (TR-05) |
| TST-2.5 | **No test depends on another test's state or ordering** |
| TST-2.6 | **A test that needs `sleep` is a design problem**, and `sleep` is banned in source by the architecture suite |

---

## 3. Behaviours that must have a test

These are the invariants where a silent regression is a serious
failure, not an inconvenience. Each already exists as a stated
requirement.

### 3.1 Integrity invariants — TRD §39.3

| # | Invariant | Source |
| --- | --- | --- |
| 1 | **Identical organic ordering with and without an active Campaign** | TR-43, IN-7, ADV-8 |
| 2 | **Publication is refused where no Permission record is linked — by any path, including import** | TR-49, D-50, OC-10 |
| 3 | **Every staff action changing published data produces an audit entry with actor, target, change and time** | TR-08, C-29 |
| 4 | **An analytics event stored for a Guest contains nothing identifying that Guest** | TR-202, AN-3 |
| 5 | **Guest search and profile view succeed with every external dependency unavailable** | TR-02, NFR-A1 |

### 3.2 Further invariants with the same standing

| # | Invariant | Source |
| --- | --- | --- |
| 6 | **A failed audit write fails the action it accompanies** | OPX-0.3, TR-08 |
| 7 | **Sponsored placements carry a label that is readable without interaction** | ADV-1, LB-1, LB-2 |
| 8 | **Sponsored content does not displace organic results** | PL-6, ADV-8 |
| 9 | **Moderation cannot edit Review content — publish, reject and remove only** | MOD-4 |
| 10 | **A moderation decision cannot be recorded without a policy ground** | MOD-1, OPX-8.1 |
| 11 | **Reporter identity is never exposed on any surface or in any response** | TS-10, REP-4 |
| 12 | **The complete discovery journey works with no session, no cookie interaction and no account** | GS-1, AC-8 |
| 13 | **Authorisation is enforced server-side for every staff and Customer action** | NFR-S3, ENF-1 |
| 14 | **Error responses in production contain no stack trace, SQL, file path or internal identifier** | TR-144 |
| 15 | **Logs contain no credential, session identifier, bearer token, OTP code or full personal record** | TR-135 |
| 16 | **`public/` contains exactly one executable entry point** | TR-09, AC-3 |
| 17 | **Campaign start and stop are evaluated at request time, not by a job** | TR-71, TR-154 |
| 18 | **Closure is a state, not a deletion — the URL and history survive** | COR-3 |
| 19 | **No excluded capability exists in any form**: no business account, no claim, no owner reply, no password, no auction | AC-9 |
| 20 | **Every scheduled job is idempotent and safe to run concurrently or not at all** | TR-150, AC-11 |

| ID | Rule |
| --- | --- |
| TST-3.1 | **Each invariant above is an executable test, not a review item**, wherever it is expressible as one |
| TST-3.2 | **Invariant 1 is the single most important test in the system.** It is the mechanical proof that organic results are not for sale (IN-7) |
| TST-3.3 | **Invariant 2 is tested on every path that can publish**, including any future import, precisely because an import is where a gate gets bypassed (OC-10) |
| TST-3.4 | **A test for an invariant is never deleted to make a change pass.** The change is wrong |
| TST-3.5 | **Where an invariant cannot be expressed as a test, it moves to `quality-strategy-v1.0.md` §5 with a named owner** |

---

## 4. Acceptance criteria as tests

| ID | Rule | Source |
| --- | --- | --- |
| TST-4.1 | **Every capability C-01…C-40 carries PRD acceptance criteria; each must pass** | AC-6 |
| TST-4.2 | **Acceptance criteria are tested against the stated criterion**, not against the implementation's behaviour |
| TST-4.3 | **Every capability except C-37 and C-19…C-29 is available on both Web and the Telegram Mini App**, which makes surface parity a test concern | AC-7 |
| TST-4.4 | **A capability with an open decision is tested for what is settled**, and the open part is left visibly untested rather than pinned to a guess | TRD §40 |
| TST-4.5 | **A test must not encode an unresolved decision.** Asserting a specific OTP validity window while OT-02 is open converts an open item into a silent resolution | QS-2.7 |

---

## 5. Mutation testing

| ID | Rule | Source |
| --- | --- | --- |
| TST-5.1 | **Infection runs on `main`, nightly, and on dispatch. It does not gate a pull request today** | `mutation.yml` |
| TST-5.2 | **`minMsi` and `minCoveredMsi` are both 100 and are not lowered** | `infection.json5`, D-26 |
| TST-5.3 | **A surviving mutant is a weak test. Strengthen the test** | Owner instruction |
| TST-5.4 | **Mutants are not suppressed, ignored or excluded to reach the score** | Owner instruction |
| TST-5.5 | **Infection mutates the `unit` suite only.** Behaviour tested solely at feature level is not protected by mutation testing — a known and accepted limitation of the current configuration | `infection.json5` |
| TST-5.6 | **Whether the Infection scope or its gating status changes as the domain grows is part of D-26** | QS-4.6 |
| TST-5.7 | **A genuinely equivalent mutant is documented with its reasoning**, not silently configured away |

---

## 6. What the suite cannot cover

| Gap | Why | Compensating control |
| --- | --- | --- |
| **MariaDB-specific behaviour** | The suite runs on SQLite | Verified against MariaDB on the real host (TR-173) |
| **Real shared-host behaviour** | No root, no daemon, cron only | Deploy smoke test (TR-190, AC-10) |
| **Cron window timing** | Depends on the real host | Measured in production; a growth trigger (TR-215, AC-11) |
| **External service behaviour** | Substituted in tests | Timeout, bounded retry and defined degradation per call (TR-01c) |
| **Real email deliverability** | Provider is **Open (D-41)** | Delivery failures recorded and surfaced (EM-4) |
| **Telegram runtime** | Lives inside Telegram | Manual verification on the real client (AC-7) |
| **Browser rendering and real devices** | Out of the PHP suite | Manual verification (§7) |
| **Accessibility in practice** | Automation finds a minority of issues | Manual keyboard and text-size checks (NFR-AC2, NFR-AC5) |
| **Whether published data is true** | Not a software property | Operational gates (`listing-operations-v1.0.md`) |

| ID | Rule |
| --- | --- |
| TST-6.1 | **Each gap has a compensating control, or it is a recorded open item.** A gap with neither is a defect in this strategy |
| TST-6.2 | **A green suite is not a statement that the product is correct.** It is a statement that the tested properties hold |

---

## 7. Manual verification

| # | Check | When |
| --- | --- | --- |
| 1 | Deploy smoke test: home page, `/health`, `/health/ready`, a 404, a static asset | Every production deploy (TR-190) |
| 2 | Guest discovery journey on a real Android device over a constrained network | Before launch; after material changes (NFR-C1, R-06) |
| 3 | Mini App journey inside the real Telegram client | Before launch; after material changes (NFR-C2) |
| 4 | Keyboard-only traversal of the console and the public surfaces | Before launch (NFR-AC2) |
| 5 | Interface legibility at increased text size | Before launch (NFR-AC5) |
| 6 | Sponsored label visible and legible on every placement, both surfaces | Before the first paid Campaign (ADV-1, ADV-2) |
| 7 | A restore rehearsal from a real scheduled backup | Before launch; quarterly (AC-12, BR §4) |
| 8 | A full lifecycle walkthrough in the console by an Operator | During the pilot (PIL-1) |
| 9 | Core content reachable with JavaScript disabled | Before launch (NFR-C3) |

| ID | Rule |
| --- | --- |
| TST-7.1 | **A manual check has a recorded result**, with date and who performed it |
| TST-7.2 | **A manual check that keeps finding the same defect becomes an automated test** |
| TST-7.3 | **Check 1 is not optional and not delegated to "it looked fine"** (TR-190) |

---

## 8. Test data and privacy

| ID | Rule | Source |
| --- | --- | --- |
| TST-8.1 | **Production personal data is never used in testing or development** | DI §9, PG §9 |
| TST-8.2 | **A fixture must not contain a real person's name, phone number, email or photograph** | PBD §8 |
| TST-8.3 | **No credential, token or key appears in a test fixture** | TR-198, AC-14 |
| TST-8.4 | **Fixtures are obviously synthetic**, so a fixture leaking is embarrassing rather than reportable |
| TST-8.5 | **A restore rehearsal uses real backup data and is therefore a production-data environment**, access-controlled and non-public (BR-4.2) |
| TST-8.6 | **Gitleaks scans the working tree and the full history** on every pull request | `security.yml` |

---

## 9. Traceability

| This document | Traces to |
| --- | --- |
| §1 baseline | `composer.json`; `phpunit.xml.dist`; `infection.json5`; `phpstan.neon.dist`; TR-173 |
| §2 levels | TR-01, TR-04, TR-05, TR-01a; TD-07; AC-1, AC-4 |
| §3 invariants | TRD §39.3; TR-02, TR-08, TR-09, TR-43, TR-49, TR-71, TR-135, TR-144, TR-150, TR-154, TR-202; ADV-1, ADV-8; MOD-1, MOD-4; TS-10; GS-1; AC-3, AC-8, AC-9, AC-11; OPX-0.3 |
| §4 acceptance | AC-6, AC-7; PRD §12; TRD §40 |
| §5 mutation | `infection.json5`; `mutation.yml`; D-26 |
| §6 gaps | TR-173, TR-190, TR-215, TR-01c; EM-4; AC-7, AC-10 |
| §7 manual | TR-190; AC-12; NFR-AC2, NFR-AC5, NFR-C1, NFR-C2, NFR-C3; ADV-1; PIL-1 |
| §8 test data | TR-198; AC-14; DI §9; PBD §8; `security.yml` |

---

## 10. Open items

| ID | Item | Marker |
| --- | --- | --- |
| D-26 | Coverage, mutation scope and gating as the domain grows | **Open — quality decision** |
| — | Whether Infection's scope widens beyond the `unit` suite (TST-5.5) | **Open — quality decision** |
| — | Whether any browser or end-to-end automation is adopted | **Open — quality decision**; none committed |
| — | Whether MariaDB-backed integration tests are added alongside SQLite | **Open — technical decision** |
| — | How accessibility is verified beyond the manual checks in §7 | **Open — quality decision**; depends on NFR-AC1 |
| OT-02 | OTP values — tests must not pin them while open (TST-4.5) | **Open — technical decision** |
| D-34 | Review mechanics — tests must not pin edit, deletion or moderation order | **Open — product decision** |
| — | Who performs and records each manual check in §7 | **Open — operational decision** |

---

## Decision references

D-26, D-34, D-41, D-50.
