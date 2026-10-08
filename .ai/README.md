# `.ai/` — Bulbula Implementation Context System

**This directory is a derived context layer for coding agents. It is not a
source of truth.**

---

## 1. What `.ai/` is

A compact, structured, traceable representation of the formal Bulbula
documentation — about **33,500 lines across 69 documents** — reduced to the
minimum an implementation agent needs in order to change code **without
getting it wrong**.

It exists so that routine implementation work does not require rereading the
whole corpus, and so that an agent can find the correct formal document in
seconds when it does need detail.

## 2. What `.ai/` is not

| It is not | Because |
| --- | --- |
| A source of truth | The formal documents are. `.ai/` only summarises them |
| A decision record | `docs/60-decisions/decision-register.md` is the only decision record |
| A replacement for the PRD, TRD or UX specs | It omits most of their content by design |
| A place to resolve open questions | An open item stays open here exactly as it is open there |
| Automatically current | It is derived by hand from a named commit (§7) |
| A policy or legal document | Legal meaning is **PENDING COUNSEL**, never settled here |

```text
.ai/context  ≠  source of truth
```

## 3. Source-of-truth hierarchy

```text
OWNER DECISIONS
      ↓
docs/60-decisions/decision-register.md
      ↓
FORMAL PRODUCT / BUSINESS / UX / TECHNICAL / PLATFORM /
SECURITY / PRIVACY / OPERATIONS / QUALITY DOCUMENTS
      ↓
.ai/ CONTEXT FILES          ← you are here
      ↓
IMPLEMENTATION
```

**`.ai/` may summarise, index and operationalise. It may never override.**

Each layer answers a different question, and they are not interchangeable:

```text
decision register ≠ PRD ≠ TRD ≠ UX specification ≠ AI context
```

| Layer | Answers |
| --- | --- |
| **Decision register** | What was decided? |
| **PRD** | What must the product do? |
| **TRD** | How is the system technically designed? |
| **UX/UI** | How should the product behave and appear? |
| **Platform specs** | How does each client surface implement the shared behaviour? |
| **Security / privacy** | What protections and data-governance constraints apply? |
| **Operations / quality** | How is the system run, tested, released and maintained? |
| **`.ai/`** | What does an implementation agent need to know *immediately* before changing code? |

## 4. Reading order

```text
.ai/README.md                        ← start here
        ↓
.ai/ai-workflow-rules.md             ← mandatory agent behaviour
        ↓
.ai/project-overview.md
        ↓
.ai/product-context.md
        ↓
.ai/technical-context.md
        ↓
.ai/architecture-context.md
        ↓
.ai/ui-context.md
        ↓
.ai/code-standards.md
        ↓
.ai/security-privacy-context.md
        ↓
.ai/operations-quality-context.md
        ↓
.ai/progress-tracker.md
```

`.ai/source-map.md` is a lookup table, not a reading step. Consult it whenever
a question belongs to a formal subject area.

**Then open formal documents only where the task requires detail.**

## 5. When to consult formal documentation

Always, for any of these:

| Situation | Open |
| --- | --- |
| Implementing a specific capability `C-xx` | `docs/10-product/prd-v1.0.md` §12 |
| Checking whether something is in V1 | `docs/10-product/scope-v1.md` |
| Any question about a `D-xx` decision | `docs/60-decisions/decision-register.md` |
| Designing a table, column or relationship | `docs/30-technical/data-model.md` |
| Designing an endpoint | `docs/30-technical/api-spec-v1.0.md` |
| Writing search behaviour | `docs/30-technical/search-design.md` |
| Writing auth or session code | `docs/30-technical/auth-identity.md` + `docs/50-security/authentication-security-v1.0.md` |
| Building any user-facing screen | `docs/20-ux-ui/` + `docs/35-platforms/` |
| Touching personal data | `docs/55-privacy/` |
| Changing deployment, migrations or release behaviour | `docs/30-technical/deployment.md` + `docs/45-quality/release-management-v1.0.md` |

The rule in one line:

> **When a question belongs to a formal subject area, open the formal
> authority rather than relying on a context summary alone.**

## 6. Handling conflicts

If `.ai/` disagrees with a formal document:

1. **The formal document wins.** Follow it.
2. **`.ai/` is wrong and must be corrected** in the same change, or in a
   follow-up that is reported.
3. **Never edit the formal document to match `.ai/`.**

If two *formal* documents disagree with each other:

1. **Stop.**
2. Apply the hierarchy in §3 — the higher document wins.
3. **Report the contradiction.** Do not resolve it silently in `.ai/`.
4. The context layer must never hide a formal-document contradiction.

## 7. Freshness

Every context file except this README and `ai-workflow-rules.md` carries:

```text
Source baseline:   <commit SHA>
Last derived from: <date>
Context status:    Current | Needs review | Stale
```

| Status | Meaning |
| --- | --- |
| **Current** | Derived from the named commit; no material source change known since |
| **Needs review** | A source document has changed materially; the file may still be correct but has not been checked |
| **Stale** | Known to be out of date; do not rely on it — read the formal source |

**There is no automation behind this.** No hook, no CI job and no script keeps
these files current. The freshness rule is a human and agent obligation:

> **If a formal source changes materially, the dependent `.ai/` context file
> MUST be reviewed** and its status updated. `.ai/source-map.md` records which
> formal sources each context file depends on.

A "material" change is one that alters a rule, a decision, a boundary, a
prohibition, a name or a number. Typo fixes and prose polish are not material.

## 8. When context files must be updated

| Trigger | Review |
| --- | --- |
| A `D-xx` decision closes, opens or changes | Every file citing it; always `progress-tracker.md` |
| Counsel answers an `L-xx` item | `security-privacy-context.md`, `progress-tracker.md` |
| The pilot (D-31) reports | `operations-quality-context.md`, `progress-tracker.md` |
| A formal document reaches a new version | The dependent files in `source-map.md` |
| A new namespace or architecture test is added | `architecture-context.md`, `code-standards.md` |
| A CI workflow, threshold or tool changes | `code-standards.md`, `operations-quality-context.md` |
| A PR merges or a branch changes | `progress-tracker.md` |
| A new formal document directory appears | `source-map.md`, this README |

## 9. Local AI guardrails

`.ai/` may define its own implementation guardrails. They use the prefix
`AI-G-xx` and are **never** written as `D-xx`:

```text
AI-G-01: Never change product behaviour without a traced source.
```

A guardrail is a rule about *how an agent works*. It is not a product
decision and carries no owner authority. The full list is in
`ai-workflow-rules.md` §1.

## 10. Verifier

`.ai/` is checked by `tools/verify-ai-context.py`, run from the repository
root:

```bash
python3 tools/verify-ai-context.py
```

It verifies the file set, source-baseline metadata, that every `D-xx`
mentioned is real, that no fictional decision IDs exist, that no forbidden V1
feature has drifted in, that no context file claims authority over formal
documents, and that the progress tracker matches verified Git state.

It does not replace, weaken or modify any existing quality gate.
