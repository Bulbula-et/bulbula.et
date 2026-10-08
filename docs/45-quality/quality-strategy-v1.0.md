# Quality Strategy

| | |
| --- | --- |
| **Document** | Quality Strategy — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

What quality means for Bulbula, what is already enforced, what is
measured, where quality is a human judgement rather than a gate, and
what is deliberately not promised.

This document is the root of `docs/45-quality/`. It covers **product
quality, data quality and code quality as one subject**, because for a
directory they are the same subject.

**Out of scope.** The test design (`test-strategy-v1.0.md`), release
mechanics (`release-management-v1.0.md`), launch gating
(`production-readiness-v1.0.md`), ongoing upkeep
(`maintenance-v1.0.md`).

## Authority and precedence

| ID | Rule |
| --- | --- |
| QS-0.1 | **This document introduces no product capability and no new quality gate.** It describes the gates that exist and names the ones that are open |
| QS-0.2 | **It does not change any threshold.** The Phase 1 thresholds hold and are never lowered | D-26 |
| QS-0.3 | **No defect-rate, uptime or satisfaction target is stated.** None is approved |
| QS-0.4 | **It describes the repository as it is.** Where it describes CI, it is describing, not proposing |

---

## 1. What quality means here

| ID | Statement |
| --- | --- |
| QS-1.1 | **The product's value is whether the information is true.** A fast, beautiful, well-tested directory with the wrong phone number has failed completely |
| QS-1.2 | **Therefore data quality is product quality**, and the operational controls in `../40-operations/listing-operations-v1.0.md` are quality controls in the fullest sense |
| QS-1.3 | **Code quality exists to protect data quality.** The gates stop a regression from silently publishing something false |
| QS-1.4 | **Quality that is not enforced is an intention.** Wherever a rule can be made executable, it is | §3 |
| QS-1.5 | **Quality that cannot be enforced is named as a judgement**, with an owner, rather than written as if it were automatic | §5 |

### 1.1 The three quality domains

| Domain | Fails as | Caught by |
| --- | --- | --- |
| **Data** | Wrong, stale or unpermissioned published information | Permission, verification and review gates; reports; re-verification |
| **Code** | A defect that breaks or corrupts a capability | The quality gate, the test suite, CI |
| **Operational** | A process not followed, or evidence not recorded | Audit trail, queue state, cadence review |

| ID | Rule |
| --- | --- |
| QS-1.6 | **All three are in scope of "quality" in this project.** Treating only the middle row as quality is the common and costly mistake |

---

## 2. Quality principles

| ID | Principle | Source |
| --- | --- | --- |
| QS-2.1 | **Do not hide or suppress a failure to make the pipeline green.** Fix the cause | Owner instruction |
| QS-2.2 | **Do not lower an existing threshold.** Thresholds move up or stay | D-26, Owner instruction |
| QS-2.3 | **Do not suppress a mutant.** A surviving mutant is a weak test; strengthen the test | Owner instruction |
| QS-2.4 | **No framework, no ORM, no container, no broker.** Simplicity is a quality property | NG-1, NG-4, NG-7, NG-8; AC-2 |
| QS-2.5 | **An enforced boundary is never weakened to accommodate a foreseeable requirement.** That is the failure mode the boundary exists to prevent | TD-07 |
| QS-2.6 | **Omission beats invention.** "Hours not confirmed" is higher quality than plausible wrong hours | DQ-2 |
| QS-2.7 | **An open item stays visibly open.** Silent resolution in code is a process failure | TRD §40 |
| QS-2.8 | **A number that was not measured is not stated** | PRD §24 numbers discipline |

---

## 3. What is already enforced

**Description of the repository as it stands.** Nothing here is a
proposal.

### 3.1 The local gate

`composer test` runs, in order:

| # | Step | Command |
| --- | --- | --- |
| 1 | Manifest validity | `composer validate --strict` |
| 2 | Automated refactoring check | `rector --dry-run` |
| 3 | Code style | `pint --test` |
| 4 | Static analysis | `phpstan analyse` |
| 5 | Tests | `pest` |
| 6 | Type coverage | `pest --type-coverage --min=100` |

`composer test:all` additionally runs PHPInsights and Infection.

### 3.2 Continuous integration

| Workflow | Jobs |
| --- | --- |
| **`ci.yml`** | dependencies (`validate --strict`, `check-platform-reqs --lock`, `audit --locked`) · coding-standards (Pint) · static-analysis (**PHPStan level max on `src`**, dependency matrix highest and lowest) · refactoring (Rector dry-run) · tests (Pest, matrix highest and lowest, `--bail`) · type-coverage (`--min=100`) · quality (PHPInsights over `src config public routes database bin`) · code-coverage (pcov, `--min=100`) |
| **`mutation.yml`** | Infection on `main`, nightly, and on dispatch. **Does not gate a pull request** |
| **`security.yml`** | `composer audit --locked` and **gitleaks** over the working tree and full history, on pull requests, `main`, and weekly |

### 3.3 Thresholds in force

| Threshold | Value | Configured in |
| --- | --- | --- |
| Line coverage | **100 %** | `pest:coverage --min=100`, CI code-coverage job |
| Type coverage | **100 %** | `pest:type-coverage --min=100` |
| Static analysis | **PHPStan max** | `phpstan.neon.dist` |
| Mutation score indicator | **100** | `infection.json5` `minMsi` |
| Covered-code MSI | **100** | `infection.json5` `minCoveredMsi` |

### 3.4 Architectural rules enforced by tests

`tests/Arch/ArchTest.php` fails the build on: framework namespaces ·
ORM or DBAL · `PDO`/`mysqli`/`curl_init` outside `Bulbula\Database` ·
SQL in `Bulbula\Http` · controllers touching the database · controller
and exception naming · `Bulbula\Database` depending on `Http`, `View`
or `Console` · service namespaces depending on `Http`, `View` or
`Console` · `Bulbula\View` touching the database or HTTP · `Env` read
outside `Bulbula\Config` · non-`final` classes · missing `strict_types`
· non-readonly `Request`, `Response`, `DatabaseConfig` · `dd`, `dump`,
`ray`, `die`, `var_dump`, `sleep`, `exit` in source.

| ID | Rule | Source |
| --- | --- | --- |
| QS-3.1 | **Every new namespace is placed on the correct side of these boundaries, and the architecture suite is extended to cover it** rather than left unguarded | TR-01, AC-1 |
| QS-3.2 | **An architectural rule is changed by a decision, never by a test exclusion** | QS-2.5 |

---

## 4. Thresholds as the domain grows — D-26

| ID | Statement |
| --- | --- |
| QS-4.1 | **100 % coverage on a 49-file foundation is a different proposition from 100 % across the full V1 domain.** That tension is real and is exactly what **D-26** exists to resolve |
| QS-4.2 | **Until D-26 is decided, the Phase 1 thresholds hold unchanged** (D-26, TRD §40.2) |
| QS-4.3 | **Lowering a threshold to accommodate new code is prohibited** (QS-2.2) |
| QS-4.4 | **If a threshold genuinely cannot hold, that is a decision request to the owner with evidence** — the measured cost, the specific code, the alternative — **not an edit to a config file** |
| QS-4.5 | **D-26 may legitimately conclude that coverage is scoped differently** — by namespace, or by criticality. That is a decision, not a relaxation |
| QS-4.6 | **Mutation testing does not gate pull requests today.** Whether it should as the domain grows is part of D-26 |

---

## 5. Quality that cannot be automated

Honesty about the limits of the gate.

| What the gate cannot catch | Caught instead by | Owner |
| --- | --- | --- |
| A published phone number that is wrong | Verification, reports, re-verification | Operations |
| A Listing published without real Permission | The Permission gate catches the *record*; only process catches a *false* record | Operations |
| A category assignment that is technically valid and practically useless | Second-person quality review | Operations |
| A moderation decision that cites a ground but misapplies it | Appeal and Administrator review | Moderation |
| Ranking that is lawful but unhelpful | Judgement, zero-result analysis | Owner |
| A privacy decision that is compliant but wrong in spirit | Privacy review (PG §11) | Administrator |
| Interface copy that is accurate but incomprehensible | UX review | Owner |
| A correct but misleading campaign report | AO §9 standing language | Administrator |

| ID | Rule |
| --- | --- |
| QS-5.1 | **These are not gaps to be closed with more tooling.** They are judgements, and naming them is what keeps them from being assumed away |
| QS-5.2 | **Each row has an owner.** A judgement with no owner is not made |
| QS-5.3 | **A failure in this table is a quality failure of equal standing to a failing build** |

---

## 6. Quality signals

| Signal | Reads on | Source |
| --- | --- | --- |
| Rework rate at quality review | Collection quality | Pilot measure 6 |
| Correction volume in the first period after publication | Collection and verification quality | Pilot measure 10 |
| Report volume and resolution time | Whether corrections keep up | PRD §26.3 |
| Duplicate rate | Intake discipline | Pilot measure 7 |
| Completeness distribution | Collection completeness | PRD §26.3 |
| Verification age distribution, stale count | Freshness | PRD §26.3 |
| Zero-result rate | Coverage | SRCH-8 |
| Support contact volume by class | Product defects in disguise | SUP-12.2 |
| Unhandled exception volume | Code quality in production | OBS §4 |
| Moderation reversal rate on appeal | Moderation consistency | MO §8 |

| ID | Rule |
| --- | --- |
| QS-6.1 | **No target value is set for any of these.** Baselines come from the pilot — **PENDING PILOT** |
| QS-6.2 | **A signal is read against the operational event that explains it** (ANO-5.2) |
| QS-6.3 | **These are system signals, not staff scores.** There is no per-operator leaderboard (ANO-1.8, OPX §2.2) |
| QS-6.4 | **A signal with no reader is removed or given one** (ANO-4.1) |

---

## 7. Accessibility and performance as quality

| ID | Position | Source |
| --- | --- | --- |
| QS-7.1 | **WCAG 2.2 AA is identified as the standard; adoption as a hard requirement is [P] and not yet approved** | NFR-AC1 |
| QS-7.2 | **Keyboard operability, non-colour-only meaning, text alternatives and legibility at increased text size are [C] requirements** and are therefore testable today | NFR-AC2…NFR-AC5 |
| QS-7.3 | **Core Web Vitals thresholds are [P] — an external standard, not yet a contractual target** | NFR-P1 |
| QS-7.4 | **Page-weight budgets are [P] and not approved** | NFR-P2 |
| QS-7.5 | **Third-party components must not block first render** — this is **[C]** and enforceable | NFR-P4 |
| QS-7.6 | **Core content must be reachable without JavaScript** — **[C]** | NFR-C3, SEO-1 |
| QS-7.7 | **A [P] item must not be reported as if it were a commitment**, in either direction | PRD §24 |

---

## 8. Quality in the operational documents

| Domain | Controls live in |
| --- | --- |
| Listing data | `../40-operations/listing-operations-v1.0.md` §2–§7 |
| Moderation consistency | `../40-operations/moderation-operations-v1.0.md` §3, §8 |
| Campaign integrity | `../40-operations/advertising-operations-v1.0.md` §11 |
| Support outcomes | `../40-operations/customer-support-v1.0.md` §11 |
| Metric correctness | `../40-operations/analytics-operations-v1.0.md` §6 |
| Production health | `../40-operations/observability-operations-v1.0.md` |
| Recoverability | `../40-operations/backup-recovery-v1.0.md` §4 |

| ID | Rule |
| --- | --- |
| QS-8.1 | **Those controls are not restated here.** A restatement would drift from the original and create two sources of truth |

---

## 9. Traceability

| This document | Traces to |
| --- | --- |
| §1 meaning | D-02, D-54; DQ-1…DQ-6; OPS-1 |
| §2 principles | D-26; NG-1, NG-4, NG-7, NG-8; TD-07; TRD §40; PRD §24 |
| §3 enforced | `composer.json` scripts; `.github/workflows/*`; `phpstan.neon.dist`; `infection.json5`; `tests/Arch/ArchTest.php`; AC-1, AC-2, AC-3, AC-5; TR-01 |
| §4 thresholds | D-26; TRD §40.2 |
| §5 judgement | DQ-2; MOD-6; PG §11; AO §9 |
| §6 signals | PRD §26.3; `listing-operations.md` §6.3; SRCH-8 |
| §7 accessibility and performance | NFR-AC1…AC5; NFR-P1, P2, P4; NFR-C3; SEO-1 |

---

## 10. Open items

| ID | Item | Marker |
| --- | --- | --- |
| D-26 | Coverage and mutation-score gates as the domain grows | **Open — quality decision** |
| — | Whether mutation testing ever gates a pull request (QS-4.6) | **Open — quality decision** |
| NFR-AC1 | Whether WCAG 2.2 AA becomes a hard requirement | **Open — product decision** |
| NFR-P1 / NFR-P2 | Whether Core Web Vitals and page-weight budgets become targets | **Open — product decision** |
| — | Baselines for every signal in §6 | **PENDING PILOT** |
| — | Whether a quality review of this strategy happens on a cadence, and when | **Open — quality decision** |
| — | How an accepted quality risk is recorded and reviewed | **Open — quality decision** |

---

## Decision references

D-02, D-26, D-54.
