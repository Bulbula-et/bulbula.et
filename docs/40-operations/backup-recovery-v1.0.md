# Backup and Recovery

| | |
| --- | --- |
| **Document** | Backup and Recovery — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

What is backed up, what is deliberately not, how backups are protected,
how a restore is rehearsed, how recovery is performed, and what is still
undecided.

**Out of scope.** The deployment procedure
([`../30-technical/deployment.md`](../30-technical/deployment.md) — the
authority for the mechanics), rollback of a release
(`release-management-v1.0.md` §8), organisational continuity
(`business-continuity-v1.0.md`), security incidents
(`../50-security/incident-response-v1.0.md`).

## Authority and precedence

| ID | Rule |
| --- | --- |
| BR-0.1 | **`deployment.md` §BK, §RB and §CR are authoritative.** This document operationalises them and contradicts nothing in them |
| BR-0.2 | **No frequency, retention depth, RPO or RTO is stated.** All are **Open — technical decision (OT-06, BK-7)**, constrained by D-20 and D-42 |
| BR-0.3 | **No backup location is named.** Location is bound by residency — **Open (D-42, D-42b)** and **PENDING COUNSEL (L-2, L-10)** |
| BR-0.4 | **No infrastructure is specified or assumed** beyond the shared host already described | TR-189 |

---

## 1. What is irreplaceable

| Asset | Replaceable? | Backed up? | Source |
| --- | --- | --- | --- |
| **The database** | **No** | **Yes** — it is the only irreplaceable thing | BK-1 |
| **Uploaded media originals** | **No** — a photograph cannot be regenerated | **Yes** | BK-2, IM-5 |
| **`.env` / production configuration** | No, but it is a **secret**, not a backup artefact | **Separately, under secret custody — Open (D-23)** | CF §, D-23 |
| **Source code** | Yes — it is in Git | No | BK-3 |
| **`vendor/`** | Yes — `composer install` from the lock file | No | BK-3 |
| **Caches** | Yes — rebuilt on demand | No | BK-3 |
| **Search documents** | Yes — rebuilt from the database | No | BK-3 |
| **Logs** | **No, but they are not recovery material** | No | BK-3, BK-8 |

| ID | Rule |
| --- | --- |
| BR-1.1 | **Logs are not a backup and must not be relied on to reconstruct state** (TR-224, BK-8) |
| BR-1.2 | **Rebuildable things are not backed up.** Backing them up costs space and creates a second, staler truth (BK-3) |
| BR-1.3 | **The media originals matter because the operational cost of a re-shoot is a second field visit**, and a second Permission conversation (LO-2.12) |
| BR-1.4 | **Losing the database loses every Permission record, every provenance record and every audit entry** — which is to say, it loses the evidence that the published data is legitimate (OM §6) |

---

## 2. Backup rules

| ID | Rule | Source |
| --- | --- | --- |
| BR-2.1 | **The database is backed up on a defined schedule** | BK-1, TR-218 |
| BR-2.2 | **A backup is taken immediately before any production migration** | BK-8, MG-7 |
| BR-2.3 | **A backup is not a backup until a restore has been tested** | BK-4, TR-218 |
| BR-2.4 | **Backups contain personal data**, and are therefore protected, access-controlled, and subject to the retention schedule — **PENDING COUNSEL (L-21)** | BK-5 |
| BR-2.5 | **Backup location is bound by the residency requirement.** A backup stored abroad is a cross-border transfer | BK-6, R-26 |
| BR-2.6 | **Frequency, retention depth and restore-time objectives are Open — technical decision**, pending D-20 and D-42 | BK-7, OT-06 |
| BR-2.7 | **A backup whose creation is not recorded did not happen.** Each run records its outcome and is observable | TR-139, OBS §4 |
| BR-2.8 | **The backup job is idempotent, bounded and safe to re-run** | CR-1, CR-2 |
| BR-2.9 | **No product behaviour depends on the backup job having run** | CR-5, TR-154 |

### 2.1 Operational consequences of BK-6

| ID | Consequence |
| --- | --- |
| BR-2.10 | **"Where is the backup stored?" is a legal question, not only a technical one.** Art. 22 requires local storage in Ethiopia, and cross-border transfer requires a lawful basis and safeguards (PG, DI, `cross-border-transfers`) |
| BR-2.11 | **A managed-host automatic backup is a vendor processing personal data** and belongs in the vendor register (VT §2) |
| BR-2.12 | **A host's automatic backups are not evidence of a tested restore.** BK-4 still applies |
| BR-2.13 | **Until D-42 and the residency position are settled, no off-host backup arrangement is made.** This is an accepted, recorded risk, not a loophole |
| BR-2.14 | **A backup taken to a staff laptop is an uncontrolled copy of personal data** and is prohibited (PG §9, DI §9) |

---

## 3. Protecting backups

| ID | Rule | Source |
| --- | --- | --- |
| BR-3.1 | **A backup is the entire database in one portable file.** It deserves stronger protection than the running system, not weaker | BK-5 |
| BR-3.2 | **Backups are access-controlled; access is limited to those who need it and is recorded** | BK-5, PG-10.3 |
| BR-3.3 | **Backups are never web-accessible.** Not in `public/`, not reachable by URL | PS-2, DR-1 |
| BR-3.4 | **Backups are never committed to the repository** | TR-198, gitleaks |
| BR-3.5 | **Whether backups are encrypted at rest, and with what key custody, is Open (D-23).** Encryption is the most direct answer to Art. 44's exception, which is a reason to decide it | D-23, Art. 44 |
| BR-3.6 | **Loss or exposure of a backup is a personal-data breach** and is handled under `incident-response-v1.0.md` §7 | IR §7 |
| BR-3.7 | **Backups age out under the retention schedule.** An indefinitely retained backup defeats erasure — **PENDING COUNSEL (L-21)** | RET §4, Art. 15 |
| BR-3.8 | **Erasure and backups interact and the interaction is not resolved here.** How a deletion propagates to existing backups is **Open — technical decision**, and the position is stated honestly in the privacy notice rather than overclaimed | DSR §6 |

---

## 4. Restore rehearsal

| ID | Rule | Source |
| --- | --- | --- |
| BR-4.1 | **A restore must have been performed successfully in a rehearsal before launch** | AC-12, TR-218 |
| BR-4.2 | **The rehearsal restores into a non-production environment**, never over production | EN §, DS-3 |
| BR-4.3 | **The rehearsal is timed.** The time it actually takes is the only honest input to any future recovery objective | BK-7 |
| BR-4.4 | **The rehearsal is repeated on the quarterly cadence** | OM §9 |
| BR-4.5 | **A failed rehearsal is a launch blocker**, not a note for later | PRR §4 |
| BR-4.6 | **The rehearsal is recorded**: date, who, which backup, how long, what failed, what was learned |
| BR-4.7 | **A rehearsal that used a specially prepared backup proves nothing.** It uses a real scheduled backup, chosen without warning |

### 4.1 What the rehearsal must prove

| # | Proof |
| --- | --- |
| 1 | The backup file is complete and readable |
| 2 | It restores into a database the application can use |
| 3 | `migration:status` reports a coherent state afterwards |
| 4 | The application starts against the restored data |
| 5 | The smoke test passes (TR-190) |
| 6 | Media references in the data resolve against the restored media |
| 7 | The elapsed time is known |

---

## 5. Recovery

### 5.1 The procedure

Authoritative in TR-223; restated here in operational order.

```text
1  Decide to recover            an Administrator decision, not a reflex (RB-4)
2  Stop writes if possible      avoid recovering onto a moving target
3  Redeploy the known-good commit
4  Restore the database
5  Restore media
6  Run migration:status         confirm a coherent schema state
7  Run the smoke test           TR-190
8  Verify a sample of real data by hand
9  Record what was lost         the window between the dump and the failure
10 Communicate                  §6
```

| ID | Rule | Source |
| --- | --- | --- |
| BR-5.1 | **Restoring a database backup is a last resort** — it loses everything written since the dump — **and is a decision, not a reflex** | RB-4 |
| BR-5.2 | **Code rollback is the lighter instrument and is tried first where it is sufficient** | RB-1 |
| BR-5.3 | **Schema rollback is not automatic.** Forward-only migrations mean a bad schema change is corrected by a new migration | RB-2, MG-2 |
| BR-5.4 | **The expand-then-contract rule is what makes code rollback safe on its own** | RB-3, MG-5 |
| BR-5.5 | **A recovery is smoke-tested exactly like a deployment** | RB-6, TR-190 |
| BR-5.6 | **A recovery is audited and recorded** like any other privileged action | C-29 |

### 5.2 The data-loss window

| ID | Rule |
| --- | --- |
| BR-5.7 | **Recovery loses data. Say so, precisely.** The window between the last good backup and the failure is identified and recorded |
| BR-5.8 | **Work lost in that window is re-done, not assumed to have survived.** Listings created, Reviews submitted, moderation decisions taken and Corrections applied in the window are gone |
| BR-5.9 | **Lost Reviews and lost Corrections are a User-visible loss**, and whether Bulbula communicates it is a decision under §6, not a question of embarrassment |
| BR-5.10 | **An audit trail with a hole is recorded as having a hole.** It is never reconstructed from memory (OM-6.6) |

### 5.3 Scenarios

| Scenario | Response |
| --- | --- |
| **Bad deploy** | Code rollback (RB-1); no restore |
| **Bad migration** | Forward-fix migration (RB-2); restore only if data is corrupted |
| **Accidental data deletion by Staff** | Assess scope; restore is last resort; the audit trail identifies what and who |
| **Database corruption** | Restore (§5.1) |
| **Host loss** | §5.1 plus a new host — **blocked on D-42 until a target exists** |
| **Media loss only** | Restore media; the database is untouched |
| **Ransomware or malicious destruction** | `incident-response-v1.0.md` first; recovery second; **the backup may also be compromised** |
| **Backup itself exposed** | Personal-data breach (IR §7); recovery is not the issue |

---

## 6. Communication during recovery

| ID | Rule | Source |
| --- | --- | --- |
| BR-6.1 | **Planned unavailability is a Service email category and a public notice** | PRD §18 |
| BR-6.2 | **Unplanned unavailability is communicated honestly**: that it happened, roughly what was affected, and what Users should do | TR-146 |
| BR-6.3 | **A loss of User-contributed content is disclosed**, because the alternative is a person discovering their Review silently gone |
| BR-6.4 | **If the incident involves personal data, the incident process owns communication**, not this document | IR §7, §8 |
| BR-6.5 | **No cause is stated publicly before it is known** | IR §8 |
| BR-6.6 | **Staff must not speculate publicly** | SUP-8.10 |
| BR-6.7 | **Whether a status page exists is Open — operational decision**; nothing currently commits one | OBS-7.7 |

---

## 7. Traceability

| This document | Traces to |
| --- | --- |
| §1 assets | BK-1, BK-2, BK-3, BK-8; IM-5; TR-224 |
| §2 backup rules | BK-1…BK-8; MG-7; CR-1, CR-2, CR-5; TR-139, TR-154, TR-218; OT-06 |
| §3 protection | BK-5, BK-6; PS-2; DR-1; TR-198; D-23; RET §4; IR §7; Proclamation Art. 15, 22, 44 |
| §4 rehearsal | BK-4; AC-12; TR-190, TR-218 |
| §5 recovery | TR-223; RB-1…RB-6; MG-2, MG-5; C-29 |
| §6 communication | PRD §18; TR-146; IR §8 |

---

## 8. Open items

| ID | Item | Marker |
| --- | --- | --- |
| OT-06 / BK-7 | Backup frequency, retention depth, off-host location, RPO and RTO | **Open — technical decision** |
| D-42 / D-42b | Hosting and data-location policy — constrains BK-6 and the host-loss scenario | **Open — product decision** |
| D-20 | Host limits — constrains feasible backup frequency | **Open — product decision** |
| D-23 | Secret custody, and therefore backup-encryption key custody (BR-3.5) | **Open — technical decision** |
| D-25 | Media storage provider — changes what BK-2 means in practice | **Open — technical decision** |
| L-2 / L-10 | Residency and cross-border transfer basis for backups | **PENDING COUNSEL** |
| L-21 / D-46 | Retention of backups; interaction with erasure | **PENDING COUNSEL** |
| — | How erasure propagates to existing backups (BR-3.8) | **Open — technical decision** |
| — | Whether a public status page exists (BR-6.7) | **Open — operational decision** |
| — | Who holds backup access, and how that access is reviewed | **Open — operational decision** |

---

## Decision references

D-20, D-23, D-25, D-42, D-42b, D-46.
