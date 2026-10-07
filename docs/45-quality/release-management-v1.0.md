# Release Management

| | |
| --- | --- |
| **Document** | Release Management — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

How a change travels from a branch to production: branching, review,
the gate, approval, the release record, deployment, verification,
rollback, and the discipline around schema and configuration changes.

**Out of scope.** The deployment mechanics
([`../30-technical/deployment.md`](../30-technical/deployment.md) — the
authority), test design (`test-strategy-v1.0.md`), launch gating
(`production-readiness-v1.0.md`), recovery
(`../40-operations/backup-recovery-v1.0.md`).

## Authority and precedence

| ID | Rule |
| --- | --- |
| REL-0.1 | **`deployment.md` is authoritative for mechanics.** This document adds the process around them and contradicts nothing in it |
| REL-0.2 | **No CI workflow, branch protection setting or repository configuration is changed by this document.** It describes what exists |
| REL-0.3 | **No release cadence, frequency or freeze calendar is invented** |
| REL-0.4 | **No production environment exists yet.** Rules about production are written to be in force when it does | `deployment.md` §9 |

---

## 1. Principles

| ID | Principle | Source |
| --- | --- | --- |
| REL-1.1 | **The repository is the source of truth.** Nothing a deployed environment needs may exist only on a server | DP-1 |
| REL-1.2 | **Never patch a server by hand. Change the repository and deploy** | DP-7 |
| REL-1.3 | **A deployment is reproducible**: the same commit plus the same environment configuration yields the same running system | DP-3 |
| REL-1.4 | **A deployment is reversible** | DP-4 |
| REL-1.5 | **A deployment is a specific commit, not "latest"** | DS-4 |
| REL-1.6 | **Production deployment is deliberate and recorded — never automatic on push** | DS-3, DA-1, TR-180 |
| REL-1.7 | **Failures are loud.** A misconfigured system fails to boot rather than running degraded in silence | DP-6 |
| REL-1.8 | **Any step failing stops the deployment.** It does not continue hopefully | DS-2 |
| REL-1.9 | **No secret is ever committed** | DP-2, SC-1 |
| REL-1.10 | **The environment is configuration, never a code branch.** No `if (production)` scattered through the code | DP-5, EN-3 |

---

## 2. Branching and change flow

```text
feature branch → pull request → CI gate → review → merge to main
  → development host refreshes automatically
  → deliberate production deployment of a chosen commit
```

| ID | Rule | Source |
| --- | --- | --- |
| REL-2.1 | **Work happens on a branch. Never directly on `main`** | Owner instruction |
| REL-2.2 | **Every change reaches `main` through a pull request** | Owner instruction |
| REL-2.3 | **Commits are logically structured**, so history is readable and a rollback target is identifiable | Owner instruction |
| REL-2.4 | **The development host refreshes from `main` automatically every ten minutes and `git reset --hard`s**, which is the mechanical enforcement of DP-7 | `deployment.md` §7.1 |
| REL-2.5 | **Migrations are deliberately not part of that cron** | MG-3, DA-1 |
| REL-2.6 | **Production must not auto-deploy from a branch** | DA-1, DS-3 |
| REL-2.7 | **A branch that cannot be merged cleanly is rebased or remade**, not forced |

### 2.1 Change classes

| Class | Examples | Extra requirement |
| --- | --- | --- |
| **Documentation only** | `docs/**` | Gate must still pass; zero non-docs diff is verified |
| **Code, no schema** | A capability, a fix | Standard gate |
| **Schema change** | A migration | §6 |
| **Configuration change** | A new variable | §7 |
| **Dependency change** | A library version | §5.3 |
| **Security fix** | An advisory, a disclosure | `../50-security/vulnerability-management-v1.0.md` |
| **Policy content change** | A published policy page | Administrator (`review.policy`); may be a privacy-notice change (PNR) |

---

## 3. The gate

### 3.1 Before a pull request

`composer test` locally: `composer validate --strict` → Rector dry-run
→ Pint check → PHPStan → Pest → type coverage.

### 3.2 On the pull request

**`ci.yml`** — dependencies · coding-standards · static-analysis
(PHPStan max, dependency matrix highest and lowest) · refactoring ·
tests (matrix highest and lowest, `--bail`) · type-coverage (100 %) ·
quality (PHPInsights) · code-coverage (100 %).
**`security.yml`** — `composer audit --locked` and gitleaks over the
tree and the full history.
**`mutation.yml`** — runs on `main` and nightly; **does not gate the
pull request**.

| ID | Rule | Source |
| --- | --- | --- |
| REL-3.1 | **A red gate blocks the merge** | QS-2.1 |
| REL-3.2 | **A failure is fixed, never hidden, skipped, excluded or marked as allowed** | Owner instruction |
| REL-3.3 | **A threshold is never lowered to make a build pass** | D-26, QS-2.2 |
| REL-3.4 | **A test is never deleted to make a change pass** | TST-3.4 |
| REL-3.5 | **A flaky test is a defect**, fixed or quarantined with a recorded reason and an owner — never retried until green |
| REL-3.6 | **The matrix exists for a reason.** A failure only at lowest dependencies is a real constraint violation, not noise |
| REL-3.7 | **A gitleaks finding means rotation, not merely removal from history** | SC-5 |

---

## 4. Review

| ID | Rule | Source |
| --- | --- | --- |
| REL-4.1 | **Every pull request is reviewed by a person** where more than one exists; with one, self-merge after a green gate is a recorded accepted risk | OM-3.8 |
| REL-4.2 | **Review checks intent, not only correctness**: does this change do what the requirement asked, and nothing else? |
| REL-4.3 | **A change touching an invariant in `test-strategy-v1.0.md` §3 gets explicit attention**, because those are the regressions that matter most |
| REL-4.4 | **A change that silently resolves an open decision is rejected.** Open items stay visibly open | TRD §40, QS-2.7 |
| REL-4.5 | **A change that introduces a product capability not in the PRD is rejected** | AC-9 |
| REL-4.6 | **A change that adds a framework, ORM, container or broker is rejected** | NG-1, NG-4, NG-7, NG-8; AC-2 |
| REL-4.7 | **A change that weakens an architecture test is rejected** unless the rule itself was changed by a decision | TD-07, QS-2.5 |
| REL-4.8 | **A change that adds a dependency is a decision**, weighed against the four-library baseline | TRD §6.1 |
| REL-4.9 | **A change touching personal data, a new field, a new purpose, a new recipient or a retention change triggers a privacy review** | PBD §11, PG §11 |

---

## 5. The release record

| Field | Content |
| --- | --- |
| **Commit** | The exact SHA deployed (DS-4) |
| **Previous commit** | Recorded before deployment, as the rollback target (RB-5) |
| **Date, time, who** | — |
| **What changed** | In product terms, not commit subjects |
| **Migrations included** | Yes/no, and which (§6) |
| **Configuration changes required** | New or changed variables (§7) |
| **Backup reference** | Taken at step 2 (MG-7, BK-8) |
| **Smoke-test result** | Pass/fail, who ran it (TR-190) |
| **Observation window outcome** | Step 10 (§8.2) |
| **Rollback, if any** | With the reason |

| ID | Rule | Source |
| --- | --- | --- |
| REL-5.1 | **A production deployment with no release record did not follow this process** | DS-3 |
| REL-5.2 | **The previous commit reference is recorded before deployment, not reconstructed afterwards** | RB-5 |
| REL-5.3 | **Whether the release record is a file in the repository, an issue, or a console entity is Open — implementation detail.** It must be durable and findable |
| REL-5.4 | **A release record is not a changelog for Users.** Whether a public changelog exists is **Open — operational decision** |

### 5.1 Approval

| ID | Rule |
| --- | --- |
| REL-5.5 | **Production deployment is an owner or Administrator decision** (DS-3) |
| REL-5.6 | **The decision is to deploy a named commit**, not to "release what's on main" (DS-4, REL-1.5) |
| REL-5.7 | **A deployment is not approved while a gate is red on that commit**, including the nightly mutation run where it has already failed on `main` |

### 5.2 Timing

| ID | Rule |
| --- | --- |
| REL-5.8 | **A deployment happens when someone is available to watch it** and to roll back (§8.2) |
| REL-5.9 | **No release cadence is fixed by this document** |
| REL-5.10 | **A migration-bearing release is not deployed when nobody can respond to a failure**, because schema rollback is not automatic (RB-2) |

### 5.3 Dependencies

| ID | Rule | Source |
| --- | --- | --- |
| REL-5.11 | **`composer.lock` is committed and deployments install from it** | `deployment.md` §4 |
| REL-5.12 | **`composer audit --locked` runs on pull requests, on `main`, and weekly** | `security.yml` |
| REL-5.13 | **Package versions and compatibility are verified against the actual PHP requirement, never guessed** | Owner instruction |
| REL-5.14 | **A dependency update is a release and follows this process** | §1 |

---

## 6. Schema changes

| ID | Rule | Source |
| --- | --- | --- |
| REL-6.1 | **Schema changes happen only through migrations. No manual DDL, ever** | MG-1, TR-174 |
| REL-6.2 | **Migrations are forward-only in production.** A bad schema change is corrected by a new migration | MG-2, RB-2 |
| REL-6.3 | **Migrations are run deliberately, never automatically by the deploy script or by cron** | MG-3, TR-180 |
| REL-6.4 | **Every migration is re-runnable-safe**: already applied is a no-op, not an error | MG-4 |
| REL-6.5 | **Expand-then-contract for breaking changes** — add, write both, backfill, read new, drop old in a later migration | MG-5 |
| REL-6.6 | **A data-moving migration is idempotent and resumable** | MG-6 |
| REL-6.7 | **A backup is taken before running migrations in production** | MG-7, BK-8 |
| REL-6.8 | **A migration never depends on application classes that may change.** It is a historical record | MG-8 |
| REL-6.9 | **Migrations stay in portable SQL**, or branch by engine, because the test suite runs on SQLite | TR-173 |
| REL-6.10 | **A migration must not contain business data beyond reference data the system needs.** **D-56 settles this:** the Category catalogue is **reference data, not application schema**, so catalogue changes **must not** require a code change or a migration where the model already supports them. A seed migration that ships the catalogue is a defect | TR-177, D-56 |
| REL-6.11 | **MG-5 is what makes a code-only rollback safe**, because the old code still works against the expanded schema | RB-3, DS-5 |

---

## 7. Configuration changes

| ID | Rule | Source |
| --- | --- | --- |
| REL-7.1 | **Configuration is read exclusively through the `Config` repository; only `Bulbula\Config` may read the environment** | TR-192 |
| REL-7.2 | **Secrets live in `.env` on the server, created from `.env.example`, never committed, never logged** | SC-2, TR-186 |
| REL-7.3 | **A new variable is added to `.env.example` in the same change that uses it**, or the deployment will fail at step 5 |
| REL-7.4 | **Deployment verifies `.env` has every variable in `.env.example`** | `deployment.md` §7 step 5 |
| REL-7.5 | **A missing or invalid configuration value fails the boot loudly** | DP-6 |
| REL-7.6 | **Rotation requires no code change — every secret is a variable** | SC-6 |
| REL-7.7 | **A secret committed by accident is rotated, not merely removed from history** | SC-5 |
| REL-7.8 | **Production secret custody is Open (D-23).** The mechanism is stated; custody is not assigned | SC-7 |

---

## 8. Deployment and verification

### 8.1 The sequence

```text
1 Pre-flight     gate green on this commit
2 Backup         database dump + record the current release reference
3 Fetch          git fetch && git checkout <commit>
4 Dependencies   composer install --no-dev --optimize-autoloader
5 Configuration  verify .env has every variable in .env.example
6 Migrations     migration:status → migrate        (deliberate, MG-3)
7 Caches         cache:clear
8 Permissions    verify storage/ is writable
9 Smoke test     home page, /health, /health/ready, a 404, a static asset
10 Observe       logs and health for a defined window
```

| ID | Rule | Source |
| --- | --- | --- |
| REL-8.1 | **Steps run in this order** | DS-1 |
| REL-8.2 | **Any step failing stops the deployment** | DS-2 |
| REL-8.3 | **The smoke test runs after every deployment and every rollback** | HK-1, TR-190, AC-10 |
| REL-8.4 | **A failing smoke test triggers rollback, not investigation on a live site** | HK-2 |
| REL-8.5 | **`storage/` is the only writable path the application requires, and application code is not writable by the web-server user** | PM-1, PM-4 |

### 8.2 The observation window

| ID | Rule |
| --- | --- |
| REL-8.6 | **Someone watches logs and health for a defined window after deployment** (step 10) |
| REL-8.7 | **The window's length is Open — operational decision.** It is defined before production exists, not improvised on the night |
| REL-8.8 | **"It returned 200 once" is not verification.** Readiness, error rate and job outcomes are all checked (OBS §4) |
| REL-8.9 | **A defect found in the window is a rollback candidate**, assessed against §9 |
| REL-8.10 | **The window's outcome goes in the release record** |

---

## 9. Rollback

| ID | Rule | Source |
| --- | --- | --- |
| REL-9.1 | **Code rollback is checking out the previous commit and re-running steps 4, 7 and 9** | RB-1, TR-182 |
| REL-9.2 | **Schema rollback is not automatic** | RB-2 |
| REL-9.3 | **MG-5 is what makes code rollback safe on its own** | RB-3 |
| REL-9.4 | **Restoring a database backup is a last resort and a decision, not a reflex** | RB-4 |
| REL-9.5 | **The previous release's commit reference is recorded before every production deployment** | RB-5 |
| REL-9.6 | **A rollback is smoke-tested exactly like a deployment** | RB-6 |
| REL-9.7 | **Rolling back is a legitimate first response, not a failure of nerve** | OBS-6.3 |
| REL-9.8 | **The forward fix comes after service is restored**, not instead of restoring it |
| REL-9.9 | **A rollback is recorded with its reason** | REL-5 |

### 9.1 When to roll back

| Situation | Action |
| --- | --- |
| Smoke test fails | **Roll back** (HK-2) |
| Readiness red | **Roll back** |
| Unhandled exceptions on a core path | **Roll back** |
| Data being written incorrectly | **Roll back immediately**; assess the damage (§9.2) |
| Personal data exposed | **Roll back**, then `incident-response-v1.0.md` §7 |
| A cosmetic defect | Forward fix; no rollback |
| A defect in a staff-only surface | Judgement; Users are unaffected |

### 9.2 After a bad release

| ID | Rule |
| --- | --- |
| REL-9.10 | **Data written incorrectly during a bad release is identified and corrected**, with each correction recorded (COR-1) |
| REL-9.11 | **If published data was wrong, that is a data-quality incident**, not only a software one |
| REL-9.12 | **The release record states what was wrong, for how long, and what was done** |
| REL-9.13 | **The missing test is added before the forward fix ships** (TST-7.2) |

---

## 10. Traceability

| This document | Traces to |
| --- | --- |
| §1 principles | DP-1…DP-8; DS-2, DS-3, DS-4; TR-180, TR-181, TR-182 |
| §2 branching | `deployment.md` §7.1; DA-1; MG-3; owner instructions |
| §3 gate | `composer.json` scripts; `ci.yml`, `security.yml`, `mutation.yml`; D-26; SC-5 |
| §4 review | AC-2, AC-9; NG-1, NG-4, NG-7, NG-8; TD-07; TRD §40; PG §11 |
| §5 release record | DS-3, DS-4; RB-5; MG-7; BK-8; TR-190 |
| §6 schema | MG-1…MG-8; TR-173, TR-174, TR-177, TR-180; RB-2, RB-3; D-56 |
| §7 configuration | SC-1…SC-7; CF-1; TR-186, TR-192; DP-6; D-23 |
| §8 deployment | `deployment.md` §7, §11; DS-1, DS-2; HK-1, HK-2; PM-1, PM-4; TR-190; AC-10 |
| §9 rollback | RB-1…RB-6; HK-2; IR §7; COR-1 |

---

## 11. Open items

| ID | Item | Marker |
| --- | --- | --- |
| D-42 / D-42b | Hosting vendor and data location — **no production environment exists yet** | **Open — product decision** |
| D-23 | Production secret custody | **Open — technical decision** |
| D-26 | Coverage and mutation gates as the domain grows | **Open — quality decision** |
| — | Category catalogue as reference data rather than schema | **Closed 2026-10-07 (D-56).** REL-6.10 now states the rule rather than deferring it |
| — | Length and content of the observation window (REL-8.7) | **Open — operational decision** |
| — | Form of the release record (REL-5.3) | **Open — implementation detail** |
| — | Whether a public changelog exists (REL-5.4) | **Open — operational decision** |
| — | Whether branch protection enforces review on `main` | **Open — operational decision** |
| — | Who holds production deployment authority when the owner is unavailable | **Open — operational decision**; see BC §3.3 |
| L-2 / L-10 | Residency constraints on the production host | **PENDING COUNSEL** |

---

## Decision references

D-23, D-26, D-42, D-42b, D-56.
