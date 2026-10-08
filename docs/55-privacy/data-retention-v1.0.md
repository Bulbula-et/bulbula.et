# Data Retention

| | |
| --- | --- |
| **Document** | Data Retention — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

Retention requirements for every class of personal data in V1: the
framework, the deletion standard, the proposed schedule, deletion
mechanics, backups, and the gaps that counsel must close.

**Out of scope.** The data classes themselves
(`data-inventory-v1.0.md`), rights handling
(`data-subject-rights-v1.0.md`), backup operations (`security-
operations-v1.0.md` §8).

## Authority

**This document proposes; counsel disposes.**

> **No period in this document is approved.** Every figure in §5 is a
> **proposal for counsel**, marked **PENDING COUNSEL (L-21, D-46)**.
> Nothing here may be implemented as a configured period until counsel
> confirms it. Publishing an unconfirmed period in a privacy notice
> would be worse than publishing none.

---

## 1. The framework

| ID | Statement | Source |
| --- | --- | --- |
| RET-1.1 | **Art. 15 is the governing rule**: personal data shall be kept for **no longer than is reasonably necessary** to achieve the purpose, or for a period **specified by law** | Art. 15 |
| RET-1.2 | **Retention is bounded by purpose, not by convenience.** "We might need it" is not a period | PBD-1.5 |
| RET-1.3 | **Art. 50 adds a duty to destroy** once the purpose lapses, in a manner **preventing reconstruction in intelligible form**, and to notify processors | Art. 50 |
| RET-1.4 | **Art. 46(2)(g) requires envisaged erasure time limits in the record of processing** — so a schedule is not optional | Art. 46(2)(g) |
| RET-1.5 | **Art. 24(1)(g) requires telling the data subject the retention period**, or the criteria used to determine it, **before** processing | Art. 24(1)(g) |
| RET-1.6 | **A period that is not enforced does not exist.** An unenforced policy is an accountability failure under Art. 52 | Art. 52 |
| RET-1.7 | **Some periods are set by other law** — tax and commercial records in particular — and may exceed what privacy minimisation would suggest | L-17, L-20 |
| RET-1.8 | **Where the Authority sets a period, it governs.** Art. 46(4)(e) gives the Authority power over log retention; current periods are **Unknown** | Art. 46(4)(e) |

---

## 2. Setting a period

For each class, four questions:

| # | Question | Fails if |
| --- | --- | --- |
| 1 | **What purpose requires keeping it?** | No purpose → delete now |
| 2 | **When does that purpose end?** | Cannot say → the class is not understood |
| 3 | **Does any other obligation extend it?** | Tax, accountability, litigation hold |
| 4 | **What is the minimum that satisfies 1–3?** | Anything longer is excessive |

| ID | Rule |
| --- | --- |
| RET-2.1 | **"Indefinite" is never an answer for personal data** |
| RET-2.2 | **Where a precise period cannot be set, publish the criteria** — Art. 24(1)(g) expressly allows this |
| RET-2.3 | **A longer period needs a reason that is written down** |
| RET-2.4 | **Aggregation is an alternative to retention.** Where only counts are needed, keep counts and destroy the rows |
| RET-2.5 | **Deleting earlier is always permitted**; keeping longer never is without a basis |

---

## 3. Categories

| Category | Characteristic | Approach |
| --- | --- | --- |
| **Account-lifetime** | Needed while the account exists | Delete on deletion, plus a short grace |
| **Content** | Published and useful to others | Lifetime of publication. **The deletion semantics are settled — withdrawal, not destruction (D-34)** — but the **retention period** for the withdrawn record is the hard part and remains **PENDING COUNSEL** (L-21, D-46) |
| **Transient operational** | Needed for minutes or hours | Expire automatically; prune aggressively |
| **Security** | Needed to investigate | Bounded period; the shortest that permits investigation |
| **Statutory record** | Required by law | Period set by the obligation, not by Bulbula |
| **Commercial** | Invoices, orders | Tax and commercial law govern |
| **Backup** | Everything, frozen | Expires with the backup cycle (§8) |

---

## 4. Proposed schedule

**Every row below is a proposal, not a decision.**

### 4.1 Account and identity

| ID | Class | Proposed | Trigger | Reasoning |
| --- | --- | --- | --- | --- |
| RET-4.1 | Account record (email, display name, status) | Life of the account **+ a short grace period** | Deletion request | Grace allows recovery from accidental or coerced deletion |
| RET-4.2 | Google account link | Deleted with the account, or on unlink | Deletion / unlink | No purpose survives |
| RET-4.3 | **Active OTP** | **Minutes** — a short, single-use validity window | Expiry or use | AS-3. Already transient by design |
| RET-4.4 | **OTP attempt and issuance records** | **Short — days, not months** | Age | Needed only for immediate brute-force defence. **If DI-6.5 resolves that these are Art. 2(5) communications data, the period must be shorter still and the vendor question hardens** |
| RET-4.5 | Session records | Session lifetime; **expired sessions pruned promptly** | Expiry / sign-out | PD-05: nothing survives on the client |
| RET-4.6 | Session metadata (IP, user agent) | **With the session, plus a short security tail** | Expiry | Personal data (DI-7.2) |
| RET-4.7 | Rate-limit counters | **Hours** | Window expiry | Purely operational |
| RET-4.8 | Last sign-in time | With the account **if retained at all** | Deletion | DI-3.7 open |

### 4.2 Content

| ID | Class | Proposed | Trigger | Reasoning |
| --- | --- | --- | --- | --- |
| RET-4.9 | **Published Review** | Life of publication; on author deletion or account deletion the Review is **withdrawn** — public visibility ceases and it leaves every rating summary (D-34) | Author deletion, account deletion, moderation removal, listing removal | **How long the withdrawn record is retained is PENDING COUNSEL (L-21, D-46).** No period is proposed here |
| RET-4.10 | Removed Review | **Short retention of the fact and policy basis; the text destroyed** | Removal | Accountability for the decision without preserving the content |
| RET-4.11 | Review edit history | With the Review. An edit within the **30-day window** returns the Review to moderation (D-34), so the history is moderation evidence as well as content | — | **PENDING COUNSEL** (L-21), as for RET-4.9 |
| RET-4.12 | **Saved listings** | Life of the account; **deleted with it** | Deletion | Private, revealing, no secondary purpose (PRIV-3) |
| RET-4.13 | **Report submissions** | **Until resolved, plus a short period**; reporter identity destroyed earliest consistent with handling | Resolution | TS-8. Holding reporter identity longer than needed is a direct risk to the reporter |
| RET-4.14 | Correction requests | Until actioned, plus a short period | Resolution | TS-1…TS-5 |
| RET-4.15 | Listing data including personal contact points | Life of the listing | Listing removal | Removal must purge contact points (PCP-2) |
| RET-4.16 | **Permission records (D-43)** | **Life of the listing + a defined accountability period** | Listing removal | The record proves the basis for publishing a personal contact point; destroying it early destroys the defence |
| RET-4.17 | Verification evidence (D-08) | **Life of the verified status + a short period** | Status lapse | Evidence about a named individual should not outlive the status |
| RET-4.18 | Listing media — originals and derivatives | Life of the listing | Removal | Derivatives regenerable; both purged |
| RET-4.19 | Replaced or superseded media | **Purged promptly**, not archived | Replacement | A removed photograph that stays on disk was not removed |

### 4.3 Operational and security

| ID | Class | Proposed | Trigger | Reasoning |
| --- | --- | --- | --- | --- |
| RET-4.20 | Application logs | **Short — weeks** | Age / rotation | Diagnostic value decays fast; identifier content does not |
| RET-4.21 | Security event logs | **Longer than application logs, still bounded** | Age | Investigation needs history; indefinite history is a liability |
| RET-4.22 | **Art. 46(4) access logs** | **Period set by the Authority — Unknown** | Statutory | Bulbula cannot set this unilaterally |
| RET-4.23 | **Audit trail of staff action** | **Accountability period — PENDING COUNSEL** | Statutory | Art. 46, Art. 52. Survives Customer deletion (SO-4.4) |
| RET-4.24 | Web server access logs | Host rotation default, **reviewed and shortened if excessive** | Rotation | Partly the host's processing (DI-7.10) |
| RET-4.25 | **Email delivery records** | **Short** | Age | Deliverability troubleshooting only. Vendor-side retention is **the vendor's period, not Bulbula's** — a contract term, not a configuration (VT §6) |
| RET-4.26 | Error reports | **Short** | Age | Must contain no records (APP-9.1) |
| RET-4.27 | Incident records and evidence | **Long — accountability** | Statutory | Art. 43(6); PENDING COUNSEL |
| RET-4.28 | Abuse signals and blocks | **Bounded**, reviewed | Age | Not a permanent reputation record (SO-13.5) |

### 4.4 Analytics and commercial

| ID | Class | Proposed | Trigger | Reasoning |
| --- | --- | --- | --- | --- |
| RET-4.29 | **Raw analytics events, if any exist** | **Shortest possible; aggregate then destroy** | Aggregation | **D-27 open.** The ideal answer is that raw rows never persist |
| RET-4.30 | Aggregates | Indefinite **only while genuinely non-identifying** | — | Thresholds required (PCP-4). If an aggregate can identify, it is personal data with a period |
| RET-4.31 | **Search query logs** | **Short, and ideally not tied to a session** | Age | DI-8.9. A long-lived query log is a behavioural record |
| RET-4.32 | Campaign and order records | **Commercial and tax period** | Statutory | L-17, L-20 |
| RET-4.33 | Invoices | **Tax period — governed by tax law, not by this document** | Statutory | May exceed privacy preference (RET-1.7) |
| RET-4.34 | Advertiser contact details | Life of the relationship **+ commercial period** | End of relationship | DI-9.4 |

### 4.5 Staff and deceased

| ID | Class | Proposed | Trigger | Reasoning |
| --- | --- | --- | --- | --- |
| RET-4.35 | Staff account record | Life of engagement **+ a short period** | Departure | Access revoked same day (SO-2.5) |
| RET-4.36 | Staff action audit | **As RET-4.23** | Statutory | Outlives the staff account by design |
| RET-4.37 | Staff access logs | **As RET-4.22** | Statutory | Art. 46(4) |
| RET-4.38 | **Data of a deceased person** | **Art. 23 preserves rights for ten years after death** | Statutory | Does not mandate retention, but shapes any deletion or disclosure decision. **PENDING COUNSEL** |

---

## 5. Status of every period above

| ID | Rule |
| --- | --- |
| RET-5.1 | **Nothing in §4 is approved.** Each row is a reasoned proposal for counsel under **L-21** and **D-46** |
| RET-5.2 | **Relative language is deliberate.** Writing "90 days" would create a false impression of settlement |
| RET-5.3 | **Counsel's output is a table of concrete periods**, which then supersedes §4 in a later version of this document |
| RET-5.4 | **Until then, no period is configured and no period is published** |
| RET-5.5 | **Where counsel cannot set a period, Art. 24(1)(g) criteria are published instead** (RET-2.2) |
| RET-5.6 | **The schedule is a launch dependency.** Art. 46(2)(g) and Art. 24(1)(g) both need it |

---

## 6. Enforcement

| ID | Requirement | Source |
| --- | --- | --- |
| RET-6.1 | **Retention is enforced by scheduled jobs**, not by intention | TD-06 |
| RET-6.2 | **Periods come from configuration**, so a counsel decision is a configuration change, not a code change | SO-11.8 |
| RET-6.3 | **A pruning job that fails silently is a compliance failure.** Cron failure is alerted on | SO-11.5 |
| RET-6.4 | **Jobs log aggregates only** — counts and classes, never the deleted records | SO-11.7 |
| RET-6.5 | **Deletion is verifiable.** It must be possible to show that a class was pruned, and when | Art. 52 |
| RET-6.6 | **Every class in §4 has an owning mechanism** — a job, a cascade, an expiry, or an explicit manual process. A class with no mechanism is retained forever by accident |
| RET-6.7 | **Absence of a cron run degrades freshness, never correctness** — but for retention, a long absence *is* a correctness problem and must be alerted | CR-5 |
| RET-6.8 | **Art. 50(2): processors are notified of the destruction obligation**; this is a contract term (VT §6) | Art. 50 |

---

## 7. Logs

Logs deserve their own treatment because they are the easiest class to
retain forever by inattention.

| ID | Rule | Source |
| --- | --- | --- |
| RET-7.1 | **Logs are personal data** where they contain IP addresses or account identifiers | DI-7.13 |
| RET-7.2 | **Rotation is a disk-space control, not a retention control.** The two must be set deliberately and may differ | SO-5.2 |
| RET-7.3 | **Three distinct log families have three distinct periods**: application diagnostics (shortest), security events (longer), Art. 46(4) access logs (**Authority-set, Unknown**) | RET-4.20…4.22 |
| RET-7.4 | **Email-delivery logs are held by the vendor as well as by Bulbula.** Bulbula's period does not bind the vendor; only the contract does | RET-4.25 |
| RET-7.5 | **Logs are excluded from exports** unless a rights request specifically reaches them, and are redacted when disclosed | DSR §6 |
| RET-7.6 | **A log retained for security is not thereby available for analytics.** Purpose limitation applies to logs | Art. 13 |
| RET-7.7 | **Archiving logs off-host is a transfer question** if the destination is abroad | SO-8.7 |

---

## 8. Backups: the honest limit

| ID | Statement |
| --- | --- |
| RET-8.1 | **Deleting a record from the live database does not delete it from backups.** It persists until every backup containing it expires |
| RET-8.2 | **This is a genuine limit, not an excuse.** It is universal to systems that have backups at all, and the alternative — no backups — is worse for availability and for Art. 17(4)(c) |
| RET-8.3 | **Backup retention therefore sets the true maximum lifetime of every deleted record** |
| RET-8.4 | **A shorter backup cycle is a privacy improvement** and must be weighed against recovery needs |
| RET-8.5 | **Backups are never used to restore deleted personal data.** If a restore reintroduces a deleted record, **the deletion is re-applied as part of the restore procedure** |
| RET-8.6 | **RET-8.5 is a procedural requirement with a named step in the restore runbook**, not an aspiration |
| RET-8.7 | **This limit is stated plainly to data subjects** — in the notice and in any deletion confirmation. Claiming immediate total erasure would be false |
| RET-8.8 | **Art. 50's "no reconstruction in intelligible form" is satisfied when the last backup containing the record expires**, provided RET-8.5 holds. **Whether that reading is correct is PENDING COUNSEL** |
| RET-8.9 | **Backup encryption shortens the exposure window in practice** and has an independent legal benefit (Art. 44(3)(a)). **Open — security decision** |
| RET-8.10 | **Backup retention is OT-06, open**, and must be decided together with the retention schedule rather than separately |

---

## 9. Conflicts

| Conflict | Resolution |
| --- | --- |
| Deletion request vs **tax retention** of an invoice | Statutory obligation prevails for the invoice; the account is still deleted. **PENDING COUNSEL (L-17, L-20)** |
| Deletion request vs **audit of staff action** | The staff audit survives (SO-4.4); the Customer's own data goes. Minimise what identifies the Customer in the audit |
| Deletion request vs **ongoing investigation or dispute** | Art. 28 provides erasure exceptions; a hold may apply. **PENDING COUNSEL** |
| Short log retention vs **incident investigation** | Security logs get the longer of the two periods; application logs do not |
| Analytics usefulness vs **minimisation** | Aggregate and destroy the rows (RET-2.4) |
| **Backups vs erasure** | §8 |
| Authority-set log period vs **Bulbula's preference** | The Authority's period governs (RET-1.8) |

| ID | Rule |
| --- | --- |
| RET-9.1 | **A conflict is resolved by counsel, recorded, and reflected in the notice** — not settled ad hoc by whoever is handling the request |
| RET-9.2 | **A retention conflict never results in keeping everything.** It results in keeping the specific thing the obligation requires |

---

## 10. Unresolved items

| ID | Item | Status |
| --- | --- | --- |
| **L-21** | **The entire schedule in §4** | **PENDING COUNSEL** — the central gap |
| D-46 | Retention periods as a Class A legal item | **PENDING COUNSEL** |
| L-21 / D-46 | **Retention period** for a withdrawn Review and its edit history | **PENDING COUNSEL.** D-34 closed the mechanism on 2026-10-07 and RET-4.9 now states it; only the duration is missing |
| D-27 | Whether raw analytics events exist at all | **Open — privacy decision** |
| D-43 | Permission record contents and period | **Open — privacy decision** |
| D-08 | Verification evidence contents and period | **Open — product decision** |
| OT-06 | Backup retention, frequency and location | **Open — technical decision** |
| L-17 / L-20 | Tax and commercial record periods | **PENDING COUNSEL** |
| — | Art. 46(4) Authority-set log retention periods | **Unknown** |
| — | Whether Art. 23's ten-year post-mortem rule affects deletion | **PENDING COUNSEL** |
| — | Length of the deletion grace period (RET-4.1) | **Open — product decision** |
| — | Whether RET-8.8's reading of Art. 50 is correct | **PENDING COUNSEL** |
| — | Backup encryption | **Open — security decision** |

---

## Legal and regulatory references

| Reference | Provision | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, **Art. 15** | Storage limitation — no longer than reasonably necessary, or a period specified by law | Confirmed — statute/regulation |
| **Art. 24(1)(g)** | Data subject must be told the retention period **or the criteria** | Confirmed — statute/regulation |
| **Art. 46(2)(g)** | Envisaged erasure time limits in the record of processing | Confirmed — statute/regulation |
| **Art. 46(4)(e)** | **The Authority determines log retention periods** | Confirmed — statute/regulation; periods **Unknown** |
| **Art. 50** | Duty to destroy; no reconstruction in intelligible form; notify processors | Confirmed — statute/regulation |
| Art. 28 | Erasure and its exceptions | Confirmed — statute/regulation |
| Art. 23 | Rights persist ten years after death | Confirmed — statute/regulation |
| Art. 17(4)(c) | Timely restoration — the reason backups exist | Confirmed — statute/regulation |
| Art. 44(3)(a) | Encryption exception, relevant to backup encryption | Confirmed — statute/regulation |
| Art. 52 | Accountability — an unenforced period is a failure | Confirmed — statute/regulation |
| **Every period in §4** | — | **PENDING COUNSEL (L-21)** |
| Tax and commercial record-keeping periods | Outside Proclamation 1321/2024 | **PENDING COUNSEL (L-17, L-20)** |

---

## Decision references

D-08, D-27, D-34, D-43, D-46.
