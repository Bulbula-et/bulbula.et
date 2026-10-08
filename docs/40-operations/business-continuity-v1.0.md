# Business Continuity

| | |
| --- | --- |
| **Document** | Business Continuity — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

How Bulbula keeps operating, or stops operating responsibly, when
something larger than a bug goes wrong: loss of a person, loss of
access, loss of a vendor, loss of the host, prolonged unavailability,
and the obligations that survive even a shutdown.

**Out of scope.** Technical recovery (`backup-recovery-v1.0.md`),
incident response (`../50-security/incident-response-v1.0.md`), daily
operations (`operations-model-v1.0.md`).

## Authority and precedence

| ID | Rule |
| --- | --- |
| BC-0.1 | **No availability commitment is made.** No availability percentage is committed in V1 | NFR-A2 |
| BC-0.2 | **No recovery-time objective is stated.** RTO is **Open — technical decision (OT-06, BK-7)** |
| BC-0.3 | **No insurance, contract, legal entity structure or financial arrangement is assumed or invented** |
| BC-0.4 | **No staffing number appears here.** The plan is written for a small team and must work with one person |
| BC-0.5 | **Legal obligations that survive a disruption are PENDING COUNSEL.** This document identifies them; it does not determine them |

---

## 1. What continuity means here

Bulbula is a small, company-operated directory on shared hosting. The
realistic threats are not datacentre fires.

| ID | Reality | Consequence |
| --- | --- | --- |
| BC-1.1 | **The team is very small.** One person being unavailable can stop a function entirely | §3 |
| BC-1.2 | **Knowledge concentrates.** Undocumented knowledge leaves with the person | §3.2 |
| BC-1.3 | **Access concentrates.** Credentials held by one person are a single point of failure | §4 |
| BC-1.4 | **Operations depend on physical presence.** Collection and verification happen in Bole Bulbula, on foot | §6 |
| BC-1.5 | **Published data outlives attention.** A directory left alone becomes wrong, and stays public while wrong | §7 |
| BC-1.6 | **Obligations to data subjects do not pause.** Deletion requests and breach duties continue through a disruption | §8 |

| ID | Principle |
| --- | --- |
| BC-1.7 | **Being wrong in public is worse than being absent.** Where the choice is between stale published data and no published data, honesty about staleness comes first (LO-4.4) |
| BC-1.8 | **Degraded is better than down, and down is better than wrong** |
| BC-1.9 | **A plan nobody has read is not a plan.** This document is reviewed annually (OM §9) |

---

## 2. What must keep working

In priority order. Everything below the line may stop.

| Priority | Function | Why |
| --- | --- | --- |
| **1** | **Public discovery** — search and profiles | It is the product, and it needs no staff |
| **2** | **The correction and report route** | Businesses cannot edit; this is the safety valve (TS-1, TS-3) |
| **3** | **Privacy, deletion and data-subject requests** | Legal obligation; does not pause (DSR) |
| **4** | **Breach assessment and notification** | Statutory clock runs from awareness (IR §7) |
| **5** | **Removal of actively harmful content** | Harm to a person outranks everything operational (MO-2.9) |
| **6** | **Authentication** | Required for Customers to act at all |
| — | — | *below this line, degradation is acceptable* |
| 7 | Review moderation | Queue grows; backlog is recoverable |
| 8 | Listing creation and verification | Coverage stalls; nothing breaks |
| 9 | Campaign administration | Delivery is automatic; approval can wait |
| 10 | Analytics and reporting | Purely internal |

| ID | Rule |
| --- | --- |
| BC-2.1 | **Priority 1 needs no staff, no cron and no external dependency.** That is why TR-02 and AC-9 matter operationally, not only architecturally |
| BC-2.2 | **Priorities 3, 4 and 5 are obligations, not workload.** They are performed even when nothing else is |
| BC-2.3 | **Priority 2 must not be switched off to reduce inbound volume** (SUP-12.4) |
| BC-2.4 | **Degrading 7–10 is a recorded decision with a review date**, not a quiet lapse |

---

## 3. Loss of people

### 3.1 Scenarios

| Scenario | Immediate effect | Response |
| --- | --- | --- |
| **An Operator is unavailable** | Collection and verification stall | Accept the stall; queues are oldest-first and recover |
| **The only Administrator is unavailable** | **Approvals, DSRs, escalations and legal routing all stop** | §3.3 — this is the critical gap |
| **The owner is unavailable** | No decisions can be taken; open items stay open | Operations continue within the documented rules only |
| **A staff member leaves** | Access must be removed; knowledge may leave with them | §3.2, §4.3 |
| **Everyone is unavailable** | Priority 1 continues; 2–10 stop | §7 |

### 3.2 Knowledge continuity

| ID | Rule |
| --- | --- |
| BC-3.1 | **This documentation set is the knowledge continuity plan.** It exists so that no process lives only in one head |
| BC-3.2 | **A procedure performed but not written down is a single point of failure**, and is recorded as one |
| BC-3.3 | **Evidence lives in the system, not in a notebook.** Provenance, Permission, verification and decisions are in the console by rule (OM-4.9) |
| BC-3.4 | **A person leaving triggers a handover that is written, not verbal** |
| BC-3.5 | **Vendor accounts, domain registration and the repository are registered in the vendor register**, so they are findable by someone who did not create them (VT §2) |

### 3.3 The single-Administrator problem

| ID | Rule |
| --- | --- |
| BC-3.6 | **Several obligations are Administrator-only: DSR execution, breach assessment, legal escalation, campaign approval, audit access.** With one Administrator, every one of those stops when that person does |
| BC-3.7 | **This is the single largest continuity risk in the V1 operating model**, and it is recorded as such rather than designed around |
| BC-3.8 | **Whether a second Administrator exists, and how the owner's authority is delegated in their absence, is Open — operational decision.** It interacts directly with D-14 |
| BC-3.9 | **The answer must not be shared credentials.** A shared account destroys attribution, and attribution is what makes the audit trail worth keeping (ST-1, TS-15) |
| BC-3.10 | **Until resolved, the honest position is that obligations with a legal clock have a single human dependency.** That is stated, not hidden |

---

## 4. Loss of access

| Asset | If access is lost | Status |
| --- | --- | --- |
| **Hosting control panel** | No deploys, no restores, no configuration | Recovery path depends on the provider — **Open (D-42b)** |
| **Domain registrar** | The site becomes unreachable at its name; email at the domain may fail | **Open — operational decision**; registrar and custody not recorded here |
| **Git remote** | Code history is at risk; local clones remain | Mitigated by every clone being a copy |
| **Production `.env` / secrets** | Cannot redeploy a working configuration | **Open (D-23)** |
| **Email provider account** | Sign-in OTP and all notifications stop | **Open (D-41)** |
| **Google Cloud project** (sign-in, Maps) | Google sign-in and map embeds degrade | Fallbacks exist (D-48, D-21) |
| **Telegram bot token** | The Mini App surface degrades | Web is unaffected (TR-02) |
| **Backup storage** | Recovery becomes impossible | **Open (OT-06)** |

| ID | Rule |
| --- | --- |
| BC-4.1 | **Secret custody is Open (D-23) and is a continuity problem, not only a security one.** A secret only one person can reach is a single point of failure |
| BC-4.2 | **Credentials are never shared between people to solve this.** The answer is custody, not sharing (BC-3.9) |
| BC-4.3 | **A staff departure removes access the same day**, and the removal is recorded (SO §4, ST-1) |
| BC-4.4 | **Account recovery routes for every external account are known before they are needed** — **Open — operational decision** |
| BC-4.5 | **Loss of access to a system holding personal data may itself be a breach** — availability is part of integrity and confidentiality (IR §7, Art. 16) |

---

## 5. Loss of a vendor or dependency

| Dependency | Loss means | Mitigation already in the design |
| --- | --- | --- |
| **Shared host** | Total outage | Redeploy from Git + restore (BR §5); blocked on a target host — **Open (D-42b)** |
| **Email provider** | No sign-in codes, no notifications | The provider is behind an adapter; **the product must operate with a different provider without product changes** (EM-7, D-41) |
| **Google sign-in** | One auth path gone | Email OTP remains (D-48) |
| **Google Maps** | Map embeds gone | Must not block first render; a fallback is required (NFR-P4, D-21) |
| **Telegram** | One client surface gone | Web unaffected (TR-02) |
| **Media storage** | Images unavailable | Originals are backed up (BK-2); provider is **Open (D-25)** |

| ID | Rule | Source |
| --- | --- | --- |
| BC-5.1 | **No external dependency may be on the critical path of Guest discovery** | TR-02, AC-9 |
| BC-5.2 | **Every outbound call has a timeout, a bounded retry and a defined degraded behaviour** | TR-01c |
| BC-5.3 | **Every vendor processing personal data is in the vendor register**, which is also the list to work through when one fails | VT §2 |
| BC-5.4 | **A vendor change that moves personal data across a border is a transfer decision**, not a procurement decision — **PENDING COUNSEL (L-2, L-10)** | BK-6 |
| BC-5.5 | **Replacing a vendor must not require a product change where the design already placed an adapter in between** | EM-7 |

---

## 6. Loss of operating conditions

| Condition | Effect | Response |
| --- | --- | --- |
| **Field work impossible** (safety, access, weather, disruption) | Collection, verification and re-verification stop | Priorities 1–6 continue; coverage stalls; staleness is shown honestly (LO-4.4) |
| **Prolonged connectivity loss** | Staff cannot reach the console | Field collection cannot be recorded; nothing is published from memory later (OM-6.6) |
| **Power or infrastructure disruption** | Intermittent everything | Public surfaces are unaffected if the host is unaffected |
| **A regulatory requirement Bulbula cannot yet meet** | Potentially a stop | **PENDING COUNSEL**; the registration position is already recorded as open (REG) |
| **Funding or commercial failure** | Operations cannot continue | §7 |

| ID | Rule |
| --- | --- |
| BC-6.1 | **A stale directory is marked stale, not quietly left to look current** (LO-4.4, OM-7.24) |
| BC-6.2 | **A prolonged stall in re-verification is a recorded accepted risk with a review date**, not an unremarked drift |
| BC-6.3 | **Work collected on paper during an outage is entered with its real collection date and method**, never backdated or smoothed (LO-3.1) |

---

## 7. Prolonged unavailability and responsible shutdown

The scenario nobody writes down, and the one where published personal
data makes doing nothing the worst option.

| ID | Rule |
| --- | --- |
| BC-7.1 | **Bulbula publishes information about real businesses and real people. Abandoning it in place is not a neutral act** |
| BC-7.2 | **If operations stop for a prolonged period, the correction and privacy routes must still function or the service must be taken down.** Publishing with no route to object is the one state that must not persist (TS-1, DSR) |
| BC-7.3 | **A decision to suspend or wind down is the owner's and is recorded** |
| BC-7.4 | **On wind-down, personal data is destroyed so it cannot be intelligibly reconstructed**, under the duty to destroy — **PENDING COUNSEL (L-21)** (Art. 50, RET §7) |
| BC-7.5 | **Businesses listed without an account cannot be notified individually unless contact details were collected for that purpose.** Whether a public notice suffices is **PENDING COUNSEL** |
| BC-7.6 | **Customer accounts, Reviews and personal data are deleted or exported under the data-subject-rights procedure**, not abandoned (DSR §7) |
| BC-7.7 | **A wind-down is not a reason to skip breach obligations that arose before it** |
| BC-7.8 | **Transfer of the service or the data to another party is a decision with legal consequences and is PENDING COUNSEL.** It is not an operational step |

---

## 8. Obligations that do not pause

| Obligation | Clock | Owner |
| --- | --- | --- |
| **Breach assessment and notification** | Starts at awareness; Proclamation Art. 43–44 — **PENDING COUNSEL** | Administrator / owner (IR §7) |
| **Data-subject requests** | Statutory period — **PENDING COUNSEL** | Administrator (DSR) |
| **Removal of unlawful content on a valid demand** | On receipt | Administrator → counsel (**PENDING COUNSEL — L-15**) |
| **Honouring a Permission withdrawal** | Promptly | Administrator (TS-5) |
| **Removing actively harmful content** | Immediately | Any staff (MO-2.9) |

| ID | Rule |
| --- | --- |
| BC-8.1 | **These five continue through any disruption, including a wind-down** (BC-2.2) |
| BC-8.2 | **A disruption is not a defence for missing a statutory deadline**, and this document does not suggest otherwise |
| BC-8.3 | **If the people who can perform them are unavailable, that is the continuity failure** — see §3.3 |

---

## 9. Review and testing

| ID | Rule |
| --- | --- |
| BC-9.1 | **This document is reviewed annually and after any event that tested it** (OM §9) |
| BC-9.2 | **The restore rehearsal is the one part of continuity that is actually tested** (BR §4), and is quarterly |
| BC-9.3 | **The single-Administrator gap (§3.3) is reviewed at every review until it is closed or formally accepted** |
| BC-9.4 | **An event that this plan did not anticipate produces an amendment**, not a note |
| BC-9.5 | **Testing the people-dependent parts means an absence rehearsal** — can anyone else do it? Whether that is practised is **Open — operational decision** |

---

## 10. Traceability

| This document | Traces to |
| --- | --- |
| §1 reality | D-02, D-54; `deployment.md` §2; OPS-8 |
| §2 priorities | TR-02, AC-9; TS-1, TS-3, TS-5; DSR; IR §7; MO-2.9 |
| §3 people | ST-1, ST-5; TS-15; D-14; OM-3.7…OM-3.10 |
| §4 access | D-23, D-41, D-42b, D-48, D-21; OT-06; SO §4; IR §7 |
| §5 vendors | TR-01c, TR-02; EM-7; BK-2, BK-6; VT §2; D-25, D-41 |
| §6 conditions | LO-4.4; OM-6.6, OM-7.24; REG |
| §7 shutdown | TS-1; DSR §6, §7; RET §7; Proclamation Art. 50; L-21 |
| §8 obligations | IR §7; DSR; TS-5; MO-2.9; L-15 |

---

## 11. Open items

| ID | Item | Marker |
| --- | --- | --- |
| D-14 | Operator/Administrator split — determines how much is single-person dependent | **Open (D-14)** — product decision |
| D-23 | Secret custody — a continuity dependency, not only a security one | **Open (D-23)** — technical decision |
| D-42 / D-42b | Hosting and data location — the host-loss path depends on it | **Open (D-42)** — product decision |
| D-41 | Email provider — loss of sign-in is a priority-6 failure | **Open (D-41)** — product decision |
| OT-06 / BK-7 | Recovery-time objective | **Open — technical decision** |
| L-21 / D-46 | Retention and the duty to destroy on wind-down | **PENDING COUNSEL** |
| L-2 / L-10 | Cross-border consequences of a vendor change | **PENDING COUNSEL** |
| L-15 | Takedown obligations during disruption | **PENDING COUNSEL** |
| — | Whether a second Administrator exists, and delegation of owner authority (BC-3.8) | **Open — operational decision** |
| — | Domain registrar, custody and recovery route (BC-4.4) | **Open — operational decision** |
| — | Whether an absence rehearsal is practised (BC-9.5) | **Open — operational decision** |
| — | Whether and how a wind-down is publicly notified (BC-7.5) | **PENDING COUNSEL** |

---

## Legal and regulatory references

| Reference | Relevance | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, Art. 16 | Integrity and confidentiality; availability loss may itself be a breach (BC-4.5) | Confirmed — statute/regulation |
| Art. 43, Art. 44 | Breach assessment and notification, 72 hours from awareness (BC §8) | Confirmed — statute/regulation |
| Art. 50 | Duty to destroy so data cannot be intelligibly reconstructed; binds wind-down (BC-7.4) | Confirmed — statute/regulation |
| Retention and destruction schedule on wind-down | BC-7.4 | **PENDING COUNSEL (L-21)** |
| Whether and how a wind-down must be notified | BC-7.5 | **PENDING COUNSEL** |

The article map lives in
[`../55-privacy/privacy-governance-v1.0.md`](../55-privacy/privacy-governance-v1.0.md)
§11. This document cites it; it does not interpret it.

---

## Decision references

D-02, D-14, D-21, D-23, D-25, D-41, D-42, D-42b, D-46, D-48, D-54.
