# Source Map

```text
Source baseline:   b5d606612c10e2a7a3c284b69fafab507f0b92fb
Last derived from: 2026-10-08
Context status:    Current
```

**Topic → the `.ai/` file to read first → the formal document that decides.**
The right-hand column always wins. `.ai/` is an index; `docs/` is the
authority (AI-G-07).

---

## 1. Product and scope

| Topic | First read | Formal authority |
| --- | --- | --- |
| What Bulbula is | `project-overview.md` | `docs/10-product/prd-v1.0.md` |
| What V1 includes | `product-context.md` §2 | `docs/10-product/scope-v1.md` |
| What V1 excludes | `product-context.md` §3 | `docs/10-product/scope-v1.md` §3; `prd-v1.0.md` §13 |
| Capability C-01…C-40 detail | `product-context.md` §2 | `docs/10-product/scope-v1.md` |
| Business ≠ platform account | `project-overview.md` §3 | `docs/60-decisions/decision-register.md` D-54 |
| Guest rights | `product-context.md` §4 | `docs/10-product/prd-v1.0.md` §11 (BND-1…BND-5) |
| Reviews policy | `product-context.md` §4 | `docs/10-product/review-policy.md` |
| Interaction permissions by role | `product-context.md` §4 | `docs/10-product/interaction-permissions.md` |
| Terminology | `ui-context.md` §4 | `docs/10-product/glossary.md` |
| Sequencing and horizon | `progress-tracker.md` | `docs/10-product/roadmap.md` |
| Revenue model | `product-context.md` §6 | `docs/15-business/business-model.md` |
| Advertising products | `product-context.md` §6 | `docs/15-business/advertising-products.md` |
| **Any decision** | `product-context.md` §7 | **`docs/60-decisions/decision-register.md`** |

## 2. Technical

| Topic | First read | Formal authority |
| --- | --- | --- |
| Runtime, stack, constraints | `technical-context.md` §1 | `docs/30-technical/trd-v1.0.md` |
| Layers and boundaries | `architecture-context.md` §1–§2 | `docs/30-technical/architecture.md` |
| Request path | `architecture-context.md` §2 | `docs/30-technical/architecture.md` §2 |
| Enforced boundaries | `architecture-context.md` §3 | `tests/Arch/ArchTest.php` **(executable)** |
| Entities, keys, relations | `technical-context.md` §6 | `docs/30-technical/data-model.md` |
| API shape, envelope, errors | `technical-context.md` §3 | `docs/30-technical/api-spec-v1.0.md` |
| Search behaviour | `technical-context.md` §4 | `docs/30-technical/search-design.md` |
| Authentication mechanics | `security-privacy-context.md` §1 | `docs/30-technical/auth-identity.md`; `docs/50-security/authentication-security-v1.0.md` |
| Caching and performance | `technical-context.md` §5 | `docs/30-technical/performance-and-caching.md` |
| Background work | `technical-context.md` §5 | `docs/30-technical/trd-v1.0.md` §§TR-150…TR-156 |
| Servers, environments, deploy | `operations-quality-context.md` §5 | `docs/30-technical/deployment.md` |
| Coding conventions | `code-standards.md` | **the repository**: `pint.json`, `phpstan.neon.dist`, `rector.php`, `composer.json` |

## 3. UI and surfaces

| Topic | First read | Formal authority |
| --- | --- | --- |
| Visual direction, tokens | `ui-context.md` §1 | `docs/20-ux-ui/design-system-v1.0.md` |
| UX principles | `ui-context.md` §2 | `docs/20-ux-ui/ux-principles-v1.0.md` |
| Why a principle exists | `ui-context.md` §2 | `docs/20-ux-ui/ux-research-v1.0.md` |
| Pages and URLs | `ui-context.md` §9 | `docs/20-ux-ui/information-architecture-v1.0.md` |
| Screen contents | `ui-context.md` §9 | `docs/20-ux-ui/public-web-ux-v1.0.md` |
| Component states | `ui-context.md` §9 | `docs/20-ux-ui/component-spec-v1.0.md` |
| Exact wording | `ui-context.md` §4 | `docs/20-ux-ui/content-design-v1.0.md` |
| Step-by-step flows | `ui-context.md` §9 | `docs/20-ux-ui/user-flows-v1.0.md` |
| Accessibility | `ui-context.md` §7 | `docs/20-ux-ui/accessibility-v1.0.md` |
| Staff console screens | `ui-context.md` §9 | `docs/20-ux-ui/operations-console-ux-v1.0.md` |
| What must be identical on both surfaces | `ui-context.md` §3 | `docs/35-platforms/shared-client-contract-v1.0.md` |
| Web delivery | `ui-context.md` §3 | `docs/35-platforms/web-platform-v1.0.md` |
| Telegram delivery | `ui-context.md` §3 | `docs/35-platforms/telegram-mini-app-v1.0.md` |
| Navigation · auth · errors · performance · SEO per surface | `ui-context.md` §3 | `docs/35-platforms/platform-*-v1.0.md` |
| Sponsored vs organic presentation | `ui-context.md` §5 | `docs/20-ux-ui/design-system-v1.0.md` §9; `docs/15-business/advertising-products.md` |

## 4. Security and privacy

| Topic | First read | Formal authority |
| --- | --- | --- |
| Who may sign in, and how | `security-privacy-context.md` §1 | `docs/50-security/authentication-security-v1.0.md` |
| Authorization | `security-privacy-context.md` §2 | `docs/30-technical/trd-v1.0.md` TR-33…TR-40 |
| Input, SQL, output, CSRF, files, headers | `security-privacy-context.md` §3 | `docs/50-security/application-security-v1.0.md` |
| Trust boundaries | `security-privacy-context.md` | `docs/50-security/security-architecture-v1.0.md` |
| Threats and accepted risks | `security-privacy-context.md` §9 | `docs/50-security/threat-model-v1.0.md` |
| Patching, rotation, monitoring | `security-privacy-context.md` §4 | `docs/50-security/security-operations-v1.0.md` |
| Breach handling | `security-privacy-context.md` §9 | `docs/50-security/incident-response-v1.0.md` |
| What personal data exists | `security-privacy-context.md` §6 | `docs/55-privacy/data-inventory-v1.0.md` |
| How long it is kept | `security-privacy-context.md` §6 | `docs/55-privacy/data-retention-v1.0.md` |
| Rights requests | `security-privacy-context.md` §6 | `docs/55-privacy/data-subject-rights-v1.0.md` |
| Design-time privacy duties | `security-privacy-context.md` §6 | `docs/55-privacy/privacy-by-design-v1.0.md` |
| Proclamation 1321/2024 article map | `security-privacy-context.md` §5 | `docs/55-privacy/privacy-governance-v1.0.md` |
| Privacy notice contents | `security-privacy-context.md` §6 | `docs/55-privacy/privacy-notice-requirements-v1.0.md` |
| Vendors and transfers | `security-privacy-context.md` §6 | `docs/55-privacy/vendor-and-transfer-register-v1.0.md` |
| **Any `L-xx` legal question** | `security-privacy-context.md` §7 | **PENDING COUNSEL — no document answers it** |

## 5. Operations and quality

| Topic | First read | Formal authority |
| --- | --- | --- |
| Roles, hours, escalation | `operations-quality-context.md` §1 | `docs/40-operations/operations-model-v1.0.md` |
| Listing lifecycle | `operations-quality-context.md` §2 | `docs/40-operations/listing-operations-v1.0.md` |
| Review and report handling | `operations-quality-context.md` §3 | `docs/40-operations/moderation-operations-v1.0.md` |
| Campaign fulfilment | `operations-quality-context.md` §3 | `docs/40-operations/advertising-operations-v1.0.md` |
| Support | `operations-quality-context.md` §3 | `docs/40-operations/customer-support-v1.0.md` |
| Measurement | `operations-quality-context.md` §3 | `docs/40-operations/analytics-operations-v1.0.md` |
| Logs, metrics, alerts | `operations-quality-context.md` §3 | `docs/40-operations/observability-operations-v1.0.md` |
| Backups and restore | `operations-quality-context.md` §3 | `docs/40-operations/backup-recovery-v1.0.md` |
| Continuity | `operations-quality-context.md` §3 | `docs/40-operations/business-continuity-v1.0.md` |
| What quality means | `operations-quality-context.md` §4 | `docs/45-quality/quality-strategy-v1.0.md` |
| Test approach and invariants | `code-standards.md` §5 | `docs/45-quality/test-strategy-v1.0.md` |
| How a change ships | `operations-quality-context.md` §5 | `docs/45-quality/release-management-v1.0.md` |
| Launch criteria | `operations-quality-context.md` §6 | `docs/45-quality/production-readiness-v1.0.md` |
| Post-launch | `operations-quality-context.md` | `docs/45-quality/maintenance-v1.0.md` |

## 6. Process and history

| Topic | First read | Formal authority |
| --- | --- | --- |
| How an AI agent must behave here | `ai-workflow-rules.md` | *this file set is the authority on its own rules* |
| Where the project stands | `progress-tracker.md` | **Git and the GitHub API** |
| Why an early option was rejected | — | `docs/00-discovery/product-decision-brief-v0.2.md`, `v0.3.md` |
| Phase 2 understanding | — | `docs/00-discovery/` (5 files) |
| Is the documentation consistent, and what is still blocking? | `progress-tracker.md` §5 | `docs/45-quality/documentation-audit-v1.0.md` |
| **What do we build first, and what does it depend on?** | `progress-tracker.md` §5 | **`docs/45-quality/implementation-plan-v1.0.md`** |
| **Is this table safe to create yet?** | — | **`docs/45-quality/implementation-plan-v1.0.md` §9 — the schema gate. `M0 — COMPLETE`: D-34, D-55, D-56 and D-57 were approved on 2026-10-07 and §9.2 states the resulting schema boundary. Still open and still gating a table shape: D-04 (hours) and D-44 (services, products, pricing)** |
| **Can this area go to production, or only to development?** | — | **`docs/45-quality/implementation-plan-v1.0.md` §6.1** |
| Is `.ai/` still true to `docs/`? | — | `tools/verify-ai-context.py` **(executable)** |
| Is `docs/` internally consistent? | — | `tools/verify-docs.py` **(executable)** |

> **Discovery documents are historical.** They contain superseded numbering
> (for example `L-1`, `L-9`, `L-11`, `L-13`, `L-14`, and Apple sign-in).
> Read them for *reasoning*, never for *current rules*.

---

## 7. Prefix → owning document

| Prefix | Meaning | Document |
| --- | --- | --- |
| `D-` | Decision | `docs/60-decisions/decision-register.md` |
| `L-` | Legal question | `docs/55-privacy/` (PENDING COUNSEL) |
| `C-` | Capability | `docs/10-product/scope-v1.md` |
| `TR-` | Technical requirement | `docs/30-technical/trd-v1.0.md` |
| `TD-` | Technical decision | `docs/30-technical/architecture.md` |
| `AC-` | Acceptance criterion | `docs/30-technical/trd-v1.0.md` |
| `BND-` | Product boundary | `docs/10-product/prd-v1.0.md` §11 |
| `UXP-` | UX principle | `docs/20-ux-ui/ux-principles-v1.0.md` |
| `DSN-` | Design-system rule | `docs/20-ux-ui/design-system-v1.0.md` |
| `A11-` | Accessibility rule | `docs/20-ux-ui/accessibility-v1.0.md` |
| `SCC-` | Shared client contract | `docs/35-platforms/shared-client-contract-v1.0.md` |
| `TM-` | Telegram rule | `docs/35-platforms/telegram-mini-app-v1.0.md` |
| `SEC- SAC-` | Security architecture | `docs/50-security/security-architecture-v1.0.md` |
| `AS-` | Authentication security | `docs/50-security/authentication-security-v1.0.md` |
| `APP-` | Application security | `docs/50-security/application-security-v1.0.md` |
| `THR-` | Threat | `docs/50-security/threat-model-v1.0.md` |
| `SO-` | Security operations | `docs/50-security/security-operations-v1.0.md` |
| `IR-` | Incident response | `docs/50-security/incident-response-v1.0.md` |
| `DI- RET- DSR- PBD- PG- PNR- VT- REG-` | Privacy | `docs/55-privacy/` |
| `OM- LO- MO- AO- SUP- ANO- OBS- BR- BC-` | Operations | `docs/40-operations/` |
| `QS- TST- REL- PRR- MNT- IMP- IMR-` | Quality | `docs/45-quality/` |
| **`AI-G-`** | **Local AI guardrail** | **`.ai/ai-workflow-rules.md` — derived, never a decision** |

**`AI-G-` is the only prefix this directory may create.** Everything else is
cited, never coined. Never write a `D-xx` that does not exist in the
register.

---

## 8. When nothing here answers the question

| Situation | Action |
| --- | --- |
| Product behaviour is unclear | **Stop.** Record it as an Open question. Do not choose |
| It is purely an implementation detail | Choose the simplest option consistent with the TRD, and say what you chose |
| Legal or privacy | **PENDING COUNSEL.** Do not reason toward an answer |
| Depends on pilot data | **PENDING PILOT.** Do not invent a number |
| Two formal documents disagree | **Stop, report both, identify the authoritative one, change nothing** |
| `.ai/` disagrees with a formal document | The **formal document wins**; correct `.ai/` (AI-G-07) |
