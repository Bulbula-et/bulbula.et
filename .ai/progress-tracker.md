# Progress Tracker

```text
Source baseline:   33f58e0e1619c7e5b952eede2382ae3c5e2ccf8c
Last derived from: 2026-10-07
Context status:    Needs review
```

> **This is the one file in `.ai/` designed to change often.**
> It is a *record*, not a plan. Every PR number, branch name and state below
> was read from Git and the GitHub API at the baseline commit. **Nothing here
> is projected.** If you cannot verify a fact from Git, leave the cell empty
> rather than estimating it.

---

## 1. Where the project actually is

**Documentation phase.** The specification is largely written; the product is
not built.

| Reality | Detail |
| --- | --- |
| Documentation | **71 files · 36,016 lines** across 11 directories |
| Application code | **49 files in `src/`** — HTTP kernel, routing, config, logging, view, database, diagnostics |
| Business features | **None.** No Business, Listing, Category, Review, Save, Search, Campaign or account exists |
| Routes | `GET /` · `GET /health` · `GET /health/ready` · `GET /api/v1/health` · `GET /api/v1/health/ready` |
| Migrations | **One** — `2026_10_04_120000_create_health_checks_table.php` |
| Test suite | **424 tests · 1,033 assertions · 100 % line and type coverage** |
| Merged to `main` | Phase 1 infrastructure and seven fixes. **No Phase 2 or Phase 3 documentation has been merged** |

---

## 2. Documentation phases

| Phase | Scope | Branch | PR | State |
| --- | --- | --- | --- | --- |
| 2.0 | Discovery — product understanding | `docs/phase-2-discovery` | **#12** → `main` | Open |
| 2.1–2.2 | Decision briefs v0.2, v0.3 | `docs/product-decision-workshop-v2` | **#13** → `docs/phase-2-discovery` | Open |
| 2.3 | Decision freeze · decision register v0.4 | `docs/finalize-product-decisions` | **#14** → `docs/product-decision-workshop-v2` | Open |
| **3.1** | PRD v1.0 and core product docs | `docs/phase-3-product-specification` | **#15** → `docs/finalize-product-decisions` | Open |
| **3.2** | TRD, architecture, data model, API | `docs/phase-3-technical-design` | **#16** → `docs/phase-3-product-specification` | Open |
| **3.3** | UX/UI specification and design system | `docs/phase-3-ux-ui` | **#17** → `docs/phase-3-technical-design` | Open |
| **3.4** | Web and Telegram platform specifications | `docs/phase-3-platform-specifications` | **#19** → `docs/phase-3-ux-ui` | Open |
| — | *Superseded first attempt at 3.4* | `docs/phase-3-platforms` | **#18** | **Closed, not merged** |
| **3.5** | Security, privacy and compliance | `docs/phase-3-security-privacy` | **#20** → `docs/phase-3-platform-specifications` | Open |
| **3.6** | Operations, quality, release, production readiness | `docs/phase-3-operations-quality` | **#21** → `docs/phase-3-security-privacy` | Open |
| **3.7** | **`.ai/` implementation context system** | `docs/phase-3-ai-context` | **#22** → `docs/phase-3-operations-quality` | Open |
| **3.8** | **Final documentation audit and reconciliation** | `docs/phase-3-final-audit` | **#23** → `docs/phase-3-ai-context` | Open |
| **3.9** | **Implementation plan and build readiness** | `docs/phase-3-implementation-plan` | **#24** → `docs/phase-3-final-audit` | Open |
| **M0** | **Schema-shaping decisions approved and reconciled** | `docs/m0-schema-decisions` | *this branch* | **In progress** |

**The chain is stacked and nothing is merged:**

```text
main ← #12 ← #13 ← #14 ← #15 ← #16 ← #17 ← #19 ← #20 ← #21 ← #22 ← #23 ← #24 ← (M0)
```

Merging out of order, or merging any of these without the owner's
instruction, breaks the chain. **Do not merge PR #21, PR #22, PR #23 or
PR #24.**

**M0 — the schema decision gate — is complete.** `D-34`, `D-55`, `D-56` and
`D-57` were approved on **2026-10-07** and are recorded in
`docs/60-decisions/decision-register.md` §1 (register **v1.1**). Schema
implementation may proceed within the boundaries those decisions set and the
boundaries the remaining open decisions still impose.

---

## 3. Documentation inventory at the baseline

| Directory | Files | Lines | Produced by |
| --- | --- | --- | --- |
| `docs/00-discovery/` | 5 | 6,500 | Phase 2 |
| `docs/10-product/` | 7 | 4,153 | Phase 3.1 |
| `docs/15-business/` | 2 | 445 | Phase 3.1 |
| `docs/20-ux-ui/` | 10 | 6,036 | Phase 3.3 |
| `docs/30-technical/` | 8 | 4,601 | Phase 3.2 |
| `docs/35-platforms/` | 9 | 2,807 | Phase 3.4 |
| `docs/40-operations/` | 9 | 2,885 | Phase 3.6 |
| `docs/45-quality/` | 7 | 3,791 | Phase 3.6, plus the audit (3.8) and the implementation plan (3.9) |
| `docs/50-security/` | 6 | 1,956 | Phase 3.5 |
| `docs/55-privacy/` | 7 | 2,289 | Phase 3.5 |
| `docs/60-decisions/` | 1 | 553 | Phase 2.3, maintained since |
| **Total** | **71** | **36,016** | |

---

## 4. Implementation phases

| Phase | Scope | State |
| --- | --- | --- |
| **1** | Minimal application infrastructure | **Merged — PR #5, plus fixes #6–#11** |
| 2+ | Every product capability (C-01…C-40) | **Not started, and not authorised** |

**Phase 1 deliberately excluded** — and these remain excluded until the owner
says otherwise: users, authentication, businesses, listings, categories,
reviews, advertising, payments, search, Maps, frontend redesign, mobile API,
admin dashboard.

### What exists in `src/`

HTTP kernel and middleware pipeline · FastRoute routing with named routes and
a URL generator · `Request` / `Response` value objects · `Method` and
`Status` enums · `HttpException` hierarchy · configuration repository with
`Env` confined to `Bulbula\Config` · Monolog logger factory · a minimal view
renderer · a PDO connection and migration runner · a diagnostics/health
service · a console entry point.

### What does not exist

Any domain model · any repository beyond health checks · authentication of
any kind · search · caching · a queue or scheduler · an outbound HTTP
gateway · a media pipeline · the operations console · the Telegram surface.

---

## 5. Blocking and pending items

| Item | Blocks | Status |
| --- | --- | --- |
| **D-31** | Launch | **Scheduled / PENDING PILOT** — the launch threshold is derived from pilot measurement (D-30n) |
| **D-39** | **First paid Campaign** | **Open** — advertising integrity controls (PRR 65) |
| **D-11** | Billing | Open; also PENDING COUNSEL (L-17, L-20) |
| **D-53** | Visual build | Open — exact colours and the logo |
| **D-16 / D-17** | Frontend work | Open — JS approach and view layer. **SPA is rejected** |
| **D-33** | Telegram identity | Open |
| **D-45** | Staff auth | Open |
| **Category catalogue content** | Launch | **Not produced.** D-56 settled the model on 2026-10-07; the curated content is an operations task and must never be invented |
| **L-5** | Privacy notice, launch | PENDING COUNSEL — lawful basis per purpose |
| **L-21 / D-46** | Retention implementation, including how long a **withdrawn Review** is kept | PENDING COUNSEL — retention periods. D-34 settled the *mechanism* (withdrawal), not the *duration* |
| **L-7** | Rights handling | PENDING COUNSEL — procedures and windows |
| **L-10** | Hosting choice | PENDING COUNSEL — Art. 22 data sovereignty |
| Prices, inventory counts, SLAs, throughput, staffing | Operations and advertising | **Not decided. None exist. Do not invent any** |

Full lists: `docs/60-decisions/decision-register.md` (60 tracked items),
`docs/45-quality/production-readiness-v1.0.md` §4 (70 criteria).

---

## 6. Quality baseline at `9730b54`

```text
composer test  →  exit 0
  composer validate --strict        pass
  rector --dry-run                  pass
  pint --test                       pass
  phpstan analyse (level: max)      pass
  pest                              424 tests, 1033 assertions
  pest --type-coverage --min=100    100 %
```

Line coverage **100 %** · type coverage **100 %** ·
Infection `minMsi: 100` / `minCoveredMsi: 100` (nightly and on `main`; it
does not gate a PR) · 18 architecture assertions in `tests/Arch/ArchTest.php`.

**This baseline must not regress.** If a change lowers any number here, the
change is wrong — not the threshold.

---

## 7. How to update this file

1. Read the facts from Git: `git log`, `git branch -r`, and the GitHub pulls
   API. **Do not reproduce a number from memory.**
2. Update the table rows that changed, and nothing else.
3. Update the `Source baseline` / `Last derived from` block at the top to the
   new commit.
4. Re-run `python3 tools/verify-ai-context.py`, which cross-checks the branch
   and PR references in this file against the repository.
5. If a phase row has no verifiable PR yet, write `—`. **Never write a PR
   number that does not exist.**
