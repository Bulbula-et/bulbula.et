# Security Operations

| | |
| --- | --- |
| **Document** | Security Operations — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

Operational security for V1: production access, staff accounts, audit,
monitoring, patching, backups, environment separation, deployment,
permissions, cron, file integrity and abuse monitoring.

**Out of scope.** Application code rules
(`application-security-v1.0.md`), incident handling
(`incident-response-v1.0.md`), retention periods
(`data-retention-v1.0.md`), vendor assessment
(`vendor-and-transfer-register-v1.0.md`).

## Authority

Below the TRD and `deployment.md`, above operational practice. **Changes
no product or deployment decision.**

**Shared hosting is mandatory.** Every requirement must be achievable
with Apache, `.htaccess`, PHP 8.4, MariaDB, cron and a filesystem — no
root, no Docker, no daemons. A VPS-only model would be a different
product.

---

## 1. Operating reality

| ID | Fact | Consequence |
| --- | --- | --- |
| SO-1.1 | **Shared hosting, no root** (`deployment.md` §2) | No host-level agent, no kernel tuning, no custom firewall |
| SO-1.2 | **A very small team** | Controls must be cheap to run or they will not be run |
| SO-1.3 | **No cache service** (TD-05), **no broker**, **cron-driven work only** (TD-06) | Fewer moving parts to secure; fewer places to hide |
| SO-1.4 | **Neighbouring tenants exist** | File permissions are a real control, not hygiene theatre |
| SO-1.5 | **No SIEM, no SOC, no on-call rota** | Detection is log review and alerting on a handful of signals |
| SO-1.6 | Data residency is bound by Art. 22 and **Open (D-42, D-42b)** | Every storage location decision is also a legal one |

**Design rule.** Prefer a control the team will actually perform weekly
over one that is ideal and performed never.

---

## 2. Production access

| ID | Requirement | Source |
| --- | --- | --- |
| SO-2.1 | **Production access is named, never shared.** No shared hosting-panel login, no shared SSH/SFTP credential, no shared database account | TS-15 |
| SO-2.2 | Access is granted to **the smallest set of people who need it**, and reviewed when anyone's role changes | SEC-3.1 |
| SO-2.3 | The list of who holds production access is **written down and current** | SO-2.2 |
| SO-2.4 | **Multi-factor authentication is enabled on the hosting account** wherever the provider supports it | SEC-3.6 |
| SO-2.5 | **Credentials are revoked on the day someone leaves**, not at the next review | SEC-5.9 |
| SO-2.6 | **Every secret that person could read is rotated** on departure | SEC-5.9 |
| SO-2.7 | **No production database access from a developer machine** where the provider allows it to be disabled | THR-63 |
| SO-2.8 | Production data is **never copied to a development environment**; development uses synthetic or anonymised data | SO §7 |
| SO-2.9 | Routine work is done through the application, not the database. **Direct `UPDATE` in production is an incident-grade action** and is recorded | THR-45 |
| SO-2.10 | `.env` is read on the server only; it is **never emailed, pasted into chat, or stored in a shared document** | TR-186, TR-221 |

---

## 3. Staff accounts

| ID | Requirement | Source |
| --- | --- | --- |
| SO-3.1 | **One account per person.** No shared operator account | AS-9.10, TS-15 |
| SO-3.2 | Staff authentication is **stronger than Customer authentication — Open (D-45)** | TS-17, D-45 |
| SO-3.3 | Permissions follow **least privilege**; the Operator/Administrator split is **Open (D-14)** | TS-18, D-14 |
| SO-3.4 | A permission is granted **because a task needs it**, recorded, and removed when the task ends | SEC-3.1 |
| SO-3.5 | **Disablement is immediate and revokes sessions** | S-7, AS-9.5 |
| SO-3.6 | Staff accounts are reviewed on a regular cadence: who exists, what they hold, whether they still need it | SO-2.2 |
| SO-3.7 | **Staff access to personal information is logged** and the log is reviewable | TS-20, PRIV-6 |
| SO-3.8 | Staff are **told** that their access is logged. Covert monitoring of staff is its own privacy problem | PG §10 |
| SO-3.9 | New staff receive data-protection briefing before access — Art. 16(1) requires the controller to take reasonable steps to ensure the reliability of employees with access | Art. 16(1) |

---

## 4. Privileged actions and audit

| ID | Requirement | Source |
| --- | --- | --- |
| SO-4.1 | Every privileged action records **actor, action, subject, time** and, where applicable, the **policy basis** | C-29, TS-9, TS-15 |
| SO-4.2 | Privileged actions include: listing create/edit/publish/unpublish, verification, moderation decisions, campaign create/activate/change, taxonomy changes, user management, permission changes, data exports and rights-request fulfilment | `listing-operations.md`, C-19…C-29 |
| SO-4.3 | **Audit records are append-only from the application.** No console path edits or deletes them | THR-45, APP-9.13 |
| SO-4.4 | **Deleting a Customer does not delete audit history** of staff action | TR-167 |
| SO-4.5 | The audit trail is written **in the same transaction as the act**, so an action cannot succeed unrecorded | APP-3.7 |
| SO-4.6 | Audit records are **readable by those entitled to review them** and are not a hidden store | PG §4 |
| SO-4.7 | **Audit retention is PENDING COUNSEL (L-21, D-46)**; it is an accountability record under Art. 52 and a processing record under Art. 46 | RET §6 |
| SO-4.8 | Direct database changes bypass audit, which is **precisely why SO-2.9 makes them incident-grade** | SO-2.9 |

---

## 5. Logging, monitoring and alerting

| ID | Requirement | Source |
| --- | --- | --- |
| SO-5.1 | Logs live **outside the web root** and are never served | THR-65, APP-9.20 |
| SO-5.2 | **Rotation is configured so logs cannot exhaust shared-host disk** | TR-140 |
| SO-5.3 | Logs contain **no secrets and no full personal records** | TR-135, APP §9.1 |
| SO-5.4 | **Logs are personal data** where they hold IP addresses or account identifiers, and are retained accordingly | APP-9.16, DI §5 |
| SO-5.5 | **Log review is a scheduled human activity**, not an aspiration. Without a SIEM, a person reading logs on a cadence *is* the detection capability | SO-1.5 |
| SO-5.6 | A small, deliberate set of signals is alerted on rather than everything: repeated authentication failure, authorization denial spikes, 500-rate spikes, cron failure, email-delivery failure, disk pressure, backup failure | TR-136 |
| SO-5.7 | **An alert nobody reads is not a control.** Each alert has a named recipient and an expected response | SO-1.2 |
| SO-5.8 | Alert channels must not carry personal data; an alert says *what* happened, not *to whom* | PRIV-1 |
| SO-5.9 | **Observability tooling beyond logs is Open (OT-07)**; V1 requires no external APM | TR-141, OT-07 |
| SO-5.10 | Any external monitoring service introduced is a **processor** and must be registered and assessed before use | VT §3 |
| SO-5.11 | Art. 46(4) requires logs of reading, disclosure and transmission of personal data; **the Authority sets retention periods**, currently **Unknown** | Art. 46(4) |

---

## 6. Patching and dependency updates

| ID | Requirement | Source |
| --- | --- | --- |
| SO-6.1 | **`composer audit` runs in CI**; a known vulnerability fails the build | APP-8.2 |
| SO-6.2 | Dependency updates are **proposed automatically and reviewed by a person** | APP-8.4 |
| SO-6.3 | A security update to a directly used package is applied **promptly**, with the full gate run before deployment | SO-6.5 |
| SO-6.4 | The **PHP version and platform patches are the host's responsibility**; the team tracks the host's PHP version and does not run an end-of-life PHP | SO-1.1 |
| SO-6.5 | **No update is deployed without the complete quality gate passing** — validate, Rector, Pint, PHPStan, tests, coverage, type coverage | Standing instruction |
| SO-6.6 | **No threshold is lowered and no mutant is suppressed** to make an update pass | Standing instruction |
| SO-6.7 | **Abandoned packages are a security finding** and are tracked to replacement | APP-8.5 |
| SO-6.8 | Patch urgency is judged by the severity model (IR §3) and by whether Bulbula's code reaches the vulnerable path | SEV |

---

## 7. Environment separation

| ID | Requirement | Source |
| --- | --- | --- |
| SO-7.1 | **Production, staging/development and local are separate environments with separate credentials** | SEC-5.7 |
| SO-7.2 | **A non-production credential must never grant production access** | SEC-5.7 |
| SO-7.3 | **No production personal data in any non-production environment** | SO-2.8, PRIV-1 |
| SO-7.4 | Non-production environments **must not send real email to real addresses** | AS-4.1 |
| SO-7.5 | Non-production environments are **not indexable** and are not publicly linked | PSE-2.5 |
| SO-7.6 | The development host refreshes from `main` by cron every ten minutes; **migrations are deliberately excluded** | `deployment.md` §13, MG-3 |
| SO-7.7 | A non-production environment that holds no personal data is **not** a residency concern; one that does, is | Art. 22 |

---

## 8. Backups

| ID | Requirement | Source |
| --- | --- | --- |
| SO-8.1 | Backups cover the database and **uploaded media independently**, because derivatives are rebuildable and originals are not | TR-219 |
| SO-8.2 | **A backup is not a backup until a restore has been tested** | BK-4, TR-213 |
| SO-8.3 | **Restoration testing is a statutory expectation**, not just good practice — Art. 17(4)(c) requires the ability to restore availability and access in a timely manner | Art. 17(4)(c) |
| SO-8.4 | A restore test is performed **before launch** and on a recurring cadence, and the result is recorded | SAC-43 |
| SO-8.5 | **Backups are stored outside the web root and are not web-reachable** | THR-64 |
| SO-8.6 | **Backups are access-controlled** to the same standard as production data; they contain everything | THR-64 |
| SO-8.7 | **A backup stored abroad is a cross-border transfer** of personal data and engages Art. 20 and Art. 22 — **PENDING COUNSEL (L-2, L-10)**, **Open (D-42)** | BK-6, D-42 |
| SO-8.8 | A backup is taken **immediately before any production migration** | MG-7, BK-8 |
| SO-8.9 | **Backup encryption is strongly indicated** — it directly engages the Art. 44(3)(a) exception to breach communication. **Open — security decision** | Art. 44(3)(a) |
| SO-8.10 | **Backup retention bounds the honesty of deletion.** A deleted account persists in backups until they expire; this must be stated plainly, not hidden | RET §8 |
| SO-8.11 | Backup retention, frequency and off-host location are **Open (OT-06)**, constrained by residency (D-42) and the retention schedule (**PENDING COUNSEL**, L-21) | TR-222, OT-06 |

---

## 9. Server, filesystem and permissions

| ID | Requirement | Source |
| --- | --- | --- |
| SO-9.1 | **Only the public directory is web-accessible.** Application code, configuration, storage, logs, backups and `vendor/` are outside the document root | `deployment.md` |
| SO-9.2 | **`.env` is outside the web root** and readable only by the application user | TR-186, SO-1.4 |
| SO-9.3 | **No file is world-writable.** Permissions are the narrowest that work on a shared host | SO-1.4 |
| SO-9.4 | **Directory indexing is disabled everywhere** | THR-67 |
| SO-9.5 | The media directory **must not execute PHP** under any server configuration | APP-6.7 |
| SO-9.6 | **Dotfiles, `composer.json`, `composer.lock`, `.git/` and documentation are not served** | THR-65 |
| SO-9.7 | **HTTPS everywhere; HTTP redirects to HTTPS**; HSTS is sent on secure responses | PS-5, APP-7.6 |
| SO-9.8 | Server version banners are reduced where the host permits; this is **hardening, not a control** (SEC-3.12) | SEC-3.12 |
| SO-9.9 | **Development dependencies are not installed in production** | APP-8.8 |

---

## 10. Deployment

| ID | Requirement | Source |
| --- | --- | --- |
| SO-10.1 | Deployment is **from a known commit**, never by editing files on the server | `deployment.md` |
| SO-10.2 | **The full quality gate passes before deployment** | SO-6.5 |
| SO-10.3 | **Migrations run manually, after a backup** | MG-7 |
| SO-10.4 | A rollback path exists and is understood; **restoring a database backup is a last resort and a decision, not a reflex** | RB-4 |
| SO-10.5 | **No secret is introduced by a deployment**; configuration changes are made on the server (TR-197, D-23 open) | SEC-5.10 |
| SO-10.6 | **CI/CD workflow files are not modified by this phase** | Phase brief |
| SO-10.7 | CI holds **no production credential** it does not need; anything it does hold is scoped and rotatable | SEC-5.8 |
| SO-10.8 | A deployment that changes a security header, an authorization rule or a retention behaviour is **noted as such** in the commit | SO-10.1 |

---

## 11. Cron

| ID | Requirement | Source |
| --- | --- | --- |
| SO-11.1 | All background work is **cron-driven console commands** | TD-06 |
| SO-11.2 | **Console commands are not reachable over HTTP** | SO-9.1 |
| SO-11.3 | A cron job runs as the application user, **never as a more privileged user** | SEC-3.1 |
| SO-11.4 | **No secret is passed on the cron command line**, where it would be visible in the process list; secrets come from `.env` | SEC-5.2 |
| SO-11.5 | **Cron failure is alerted on.** A silently dead retention or pruning job is a compliance failure that looks like nothing | SO-5.6 |
| SO-11.6 | **No cron job is required for the site to serve correct pages**; absence degrades freshness, never correctness | CR-5, TR-154 |
| SO-11.7 | A job that deletes data **logs what it deleted in aggregate** — counts and classes, never the records | APP-9.8 |
| SO-11.8 | Retention and pruning jobs read their periods **from configuration**, so a counsel decision is a configuration change, not a code change | TR-208, TR-196 |

---

## 12. File integrity

| ID | Requirement | Source |
| --- | --- | --- |
| SO-12.1 | The deployed tree should match the deployed commit; **unexpected changes on the server are a signal** | SO-10.1 |
| SO-12.2 | Checking is **lightweight and periodic** — a comparison against the known commit, not a commercial integrity product | SEC-6.2 |
| SO-12.3 | **New or modified files in the media directory that are not media** are a strong compromise indicator | THR-46 |
| SO-12.4 | **PHP files anywhere in writable storage** are treated as compromise until proven otherwise | APP-6.7 |
| SO-12.5 | Timestamps and sizes of application files are reviewed after any suspected incident | IR §5 |

---

## 13. Abuse monitoring

| ID | Requirement | Source |
| --- | --- | --- |
| SO-13.1 | Monitored signals: OTP failure rates, resend floods, account-creation spikes, review-posting rates, report-submission spikes, search-query floods, 404 sweeps against `/ops/*` | THR-09, THR-15, THR-27, THR-49 |
| SO-13.2 | **Review anomaly review is a staffed activity**, not only an automated rule — coordinated manipulation is detected by people (THR-50) | TS-12 |
| SO-13.3 | Rate limits never disclose the limit (E-6) and are applied **without a CAPTCHA** (WCAG 3.3.8) | AS-3.15 |
| SO-13.4 | **Scraping is accepted** (THR-08); it is monitored only to protect availability, not to prevent reading | THR-08 |
| SO-13.5 | Abuse signals are retained under the retention schedule, **not indefinitely** | RET §7 |
| SO-13.6 | Blocking a source is an operational action; it is **recorded** and is reversible | SO-4.1 |

---

## 14. Recurring calendar

A realistic cadence for a small team. Each item has an owner.

| Cadence | Activity |
| --- | --- |
| **Continuous (CI)** | `composer audit`, secret scanning, full quality gate |
| **Weekly** | Review alerts and security events; triage dependency proposals |
| **Monthly** | Review staff accounts and permissions; review audit sample; confirm backups ran |
| **Quarterly** | **Restore test** (SO-8.4); review the vendor register; review accepted risks; review the compliance register |
| **On change** | Rotate secrets on departure or suspected exposure; reassess on new vendor, new data class or new surface |
| **Annually** | Review the threat model and this document; confirm registration currency (Art. 35(2) two-year certificate) |

---

## 15. Unresolved items

| ID | Item | Status |
| --- | --- | --- |
| D-45 | Staff authentication strength | **Owner decision, open** |
| D-14 | Operator / Administrator split | **Owner decision, open** |
| D-23 | Production configuration management and secret custody | **Open — technical decision** |
| D-42 / D-42b | Data location and hosting vendor — binds backups | **Owner decision, open** |
| D-20 | Confirmed host resource limits — bounds backup and cron budgets | **Open — technical decision** |
| OT-06 | Backup retention, frequency, off-host location | **Open — technical decision** |
| OT-07 | Observability tooling | **Open — technical decision** |
| L-2 / L-10 | Whether an off-host backup location is a lawful transfer | **PENDING COUNSEL** |
| L-21 | Audit, log and backup retention periods | **PENDING COUNSEL** |
| — | Backup encryption | **Open — security decision.** Engages Art. 44(3)(a) |
| — | Who holds production access at launch | **Open — implementation detail** |
| — | Alert delivery channel | **Open — implementation detail**; must not become an unassessed processor |

---

## Legal and regulatory references

| Reference | Relevance | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, Art. 16(1) | Reasonable steps to ensure the reliability of employees with access (SO-3.9) | Confirmed — statute/regulation |
| Art. 16(2), 16(3) | Processor selection, written contract, instruction-only processing | Confirmed — statute/regulation |
| Art. 17(4)(b), 17(4)(c), 17(4)(d) | Confidentiality/integrity/availability/resilience; **timely restoration**; **regular testing of effectiveness** | Confirmed — statute/regulation |
| Art. 22(1) | Local storage of locally collected personal data — binds backup location | Confirmed — statute/regulation |
| Art. 44(3)(a) | Breach communication exception where data is unintelligible, e.g. encrypted — motivates SO-8.9 | Confirmed — statute/regulation |
| Art. 46(4) | Logging of reading, disclosure and transmission; **Authority sets log retention** | Confirmed — statute/regulation; periods **Unknown** |
| Art. 35(2) | Registration certificate valid two years, renewable | Confirmed — statute/regulation |
| Whether these operational measures are "appropriate" for Bulbula | — | **Counsel interpretation required** |

---

## Decision references

D-14, D-20, D-23, D-42, D-42b, D-45, D-46.
