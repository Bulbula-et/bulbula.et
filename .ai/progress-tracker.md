# Progress Tracker

```text
Source baseline:   9730b5426efffdd6756075d97354319d1686b74a
Last derived from: 2026-10-07
Context status:    Current
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
| Documentation | **69 files · 33,484 lines** across 11 directories |
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
| **3.7** | **`.ai/` implementation context system** | `docs/phase-3-ai-context` | *this branch* | **In progress** |
| 3.8 | Final documentation audit | — | — | Not started |
| 3.9 | *Not yet defined in the repository* | — | — | — |

**The chain is stacked and nothing is merged:**

```text
main ← #12 ← #13 ← #14 ← #15 ← #16 ← #17 ← #19 ← #20 ← #21 ← (3.7)
```

Merging out of order, or merging any of these without the owner's
instruction, breaks the chain. **Do not merge PR #21.**

---

## 3. Documentation inventory at the baseline

| Directory | Files | Lines | Produced by |
| --- | --- | --- | --- |
| `docs/00-discovery/` | 5 | 6,492 | Phase 2 |
| `docs/10-product/` | 7 | 4,153 | Phase 3.1 |
| `docs/15-business/` | 2 | 445 | Phase 3.1 |
| `docs/20-ux-ui/` | 10 | 6,036 | Phase 3.3 |
| `docs/30-technical/` | 8 | 4,584 | Phase 3.2 |
| `docs/35-platforms/` | 9 | 2,807 | Phase 3.4 |
| `docs/40-operations/` | 9 | 2,853 | Phase 3.6 |
| `docs/45-quality/` | 5 | 1,318 | Phase 3.6 |
| `docs/50-security/` | 6 | 1,956 | Phase 3.5 |
| `docs/55-privacy/` | 7 | 2,287 | Phase 3.5 |
| `docs/60-decisions/` | 1 | 553 | Phase 2.3, maintained since |
| **Total** | **69** | **33,484** | |

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
| **L-5** | Privacy notice, launch | PENDING COUNSEL — lawful basis per purpose |
| **L-21** | Retention implementation | PENDING COUNSEL — retention periods |
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
