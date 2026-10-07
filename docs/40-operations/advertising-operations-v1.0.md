# Advertising Operations

| | |
| --- | --- |
| **Document** | Advertising Operations — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

How Bulbula runs the advertising business operationally: Package
administration, Placement administration, Campaign creation, inventory
validation, approval, activation, delivery, suspension, expiry,
measurement, reporting, audit, and eligibility maintenance.

The commercial definitions live in
[`../15-business/advertising-products.md`](../15-business/advertising-products.md)
and the product requirements in the PRD §19. **This document
operationalises them and changes neither.**

**Out of scope.** Pricing (owner decision, none approved), billing
mechanics (**Open — D-11**), advertising copy standards (**Open —
commercial policy**), tax and invoicing (**PENDING COUNSEL — L-17,
L-20**).

## Authority and precedence

| ID | Rule |
| --- | --- |
| AO-0.1 | **No price appears in this document.** No price is approved |
| AO-0.2 | **No final inventory count, slot count or density value appears here.** The values in `advertising-products.md` §2 and §5 are marked **[P]** and are not approved |
| AO-0.3 | **No revenue target, forecast or commercial projection appears here** |
| AO-0.4 | **This document introduces no advertising product.** Exactly three sponsorship forms exist; a fourth requires an owner decision (PK-4) |
| AO-0.5 | **D-39 blocks the first paid Campaign.** Nothing here may be read as permission to run one before it is resolved |

---

## 1. The model, operationally

```text
offline agreement → Operator creates Campaign → validation
  → Administrator approves and activates → runs between dates
  → measured → ends automatically → reported to the business
```

| ID | Fixed property | Source |
| --- | --- | --- |
| AO-1.1 | **Fixed-package sponsorship.** A Package is a Placement, a target, a duration and a fixed price | D-10, §3 |
| AO-1.2 | **Staff-managed throughout.** There is no self-service purchase, no business login and no business dashboard | D-54, MS-7 |
| AO-1.3 | **No auction, no bidding, no CPC, CPM, CPA or performance pricing** | PRD §19.3, PK-3 |
| AO-1.4 | **No programmatic, no third-party network, no automated targeting, no retargeting, no behavioural targeting** | PRD §19.3, D-10 |
| AO-1.5 | **Verification, ranking and inclusion are not purchasable** | ADV-9, PK-2 |
| AO-1.6 | **Listing is free and unconditional.** Sponsorship is never a condition of being listed or of being listed well | PK-6 |
| AO-1.7 | **Organic results are fully separated from sponsored ones** | ADV-8, IN-1, IN-7 |
| AO-1.8 | **Contracting is offline; invoicing is manual; there is no payment gateway** | D-11, §9 |

### 1.1 The three approved forms

| Form | Target | Capability |
| --- | --- | --- |
| Sponsored placement in search results | Category, Subcategory or Area | C-16 |
| Category sponsorship | A Category or Subcategory surface | C-16 |
| Homepage promotion | A defined homepage slot; no target | C-16, C-01 |

| ID | Rule |
| --- | --- |
| AO-1.9 | **No other form may be sold, promised or discussed as available** |
| AO-1.10 | **A request for a form that does not exist is a decision request to the owner**, not an improvisation |

---

## 2. Package administration

| ID | Practice | Source |
| --- | --- | --- |
| AO-2.1 | **The Package catalogue is maintained by the owner.** Operators sell from it; they do not extend it | PK-4 |
| AO-2.2 | **A Package states exactly what the business receives, where, and for how long** | PK-1 |
| AO-2.3 | **A Package must not include anything touching ranking, verification, trust indicators or Reviews** — a bundled promise of that kind is void and reportable | PK-2, PK-6 |
| AO-2.4 | **Price is fixed per Package and must not vary by performance** | PK-3, D-10 |
| AO-2.5 | **A discount is recorded with the Campaign as a commercial note, never implemented as a product mechanism** | PK-5 |
| AO-2.6 | **No price is approved.** Until the owner approves a price list, nothing may be quoted | §10 |
| AO-2.7 | **Whether the Package catalogue is a console-managed entity or an owner-maintained list is Open — implementation detail**, constrained by C-27 |

### 2.1 What an Operator may say

| Permitted | Prohibited |
| --- | --- |
| What the Placement is and where it appears | Any guarantee of clicks, calls, customers, revenue or position (MS-5) |
| The duration and the fixed price, once approved | Any suggestion that sponsorship improves organic ranking (IN-1) |
| That impressions and clicks are reported | Any suggestion that it affects verification or trust indicators (IN-2) |
| That organic results are unaffected | Any suggestion that it affects Reviews or moderation (IN-3) |
| That the label is mandatory and visible | Any offer of correction or report priority (IN-4) |
| That listing is free regardless | Any offer to remove, reorder or suppress a Review (PART-3) |

| ID | Rule |
| --- | --- |
| AO-2.8 | **A promise outside the left column is not a sale, it is a misrepresentation.** It is escalated to the owner and the Campaign does not proceed |

---

## 3. Placement administration

| ID | Rule | Source |
| --- | --- | --- |
| AO-3.1 | **Every Placement is documented in `advertising-products.md` before it may be sold or built** | PL-1, ADV-3 |
| AO-3.2 | **Each Placement has a maximum slot count**; sponsored content must not appear outside a documented Placement | PL-2 |
| AO-3.3 | **Slot counts and density values are [P] and unapproved.** Operations must not treat the proposed figures as inventory | PL-3, PL-4 |
| AO-3.4 | **Unsold inventory collapses.** No placeholder, no house ad, no empty frame — an empty slot is not an operational problem to fill | PL-5 |
| AO-3.5 | **Sponsored content never displaces organic results** | PL-6, ADV-8 |
| AO-3.6 | **A Business must not appear twice on a surface as a result of sponsorship** | PL-7 |
| AO-3.7 | **Placements are identical across Web and Telegram** | PL-8, SUR-4 |
| AO-3.8 | **Labelling is non-negotiable and is not an operational variable.** An Operator cannot agree to a softer label | LB-1…LB-8, ADV-1 |
| AO-3.9 | **A public page explains what sponsorship is and that it does not affect organic ranking** | LB-8, C-18 |

---

## 4. Campaign creation

### 4.1 Prerequisites before a Campaign is created

| # | Prerequisite |
| --- | --- |
| 1 | An offline agreement exists with the Business |
| 2 | The Business's Listing is **published** (ADV-10) |
| 3 | The Business's Listing is **verified** (ADV-10) |
| 4 | The Business is in **good standing** — §7 |
| 5 | An approved Package exists with an approved price |
| 6 | Inventory is available for the Placement and period (AO-5) |
| 7 | **D-39 integrity controls are resolved** — for the first paid Campaign (IN-6, ADV-12) |

### 4.2 Creation

| ID | Rule | Source |
| --- | --- | --- |
| AO-4.1 | **An Operator records Business, Package, Placement, target and period** | §6, C-27 |
| AO-4.2 | **Start and end dates are explicit.** There is no open-ended Campaign | ADV-4, OPX-10.3 |
| AO-4.3 | **Exactly one published, eligible Business per Campaign** | ADV-5 |
| AO-4.4 | **Creating a Campaign does not activate it.** Creation and approval are separate acts by separate people | §6, OPX-10.4 |
| AO-4.5 | **Campaign state is explicit and visible**: draft, approved, scheduled, active, suspended, ended | OPX-10.2 |
| AO-4.6 | **The creation is audited** with actor, time and reason | ADV-6, OPX-10.8 |
| AO-4.7 | **Renewal is a new Campaign, never a silent extension** | §6 |

---

## 5. Inventory validation

| ID | Rule | Source |
| --- | --- | --- |
| AO-5.1 | **Eligibility and inventory availability are checked at creation** | §6 |
| AO-5.2 | **Overbooking is refused.** The check is against the Placement's slot count for the requested period | §6, OPX-10.5 |
| AO-5.3 | **Refusal names the conflict** — which Placement, which period, which existing Campaigns hold the slots | OPX-0.6, OPX-10.5 |
| AO-5.4 | **Inventory is period-aware.** A slot free next month is not a slot free now | AO-4.2 |
| AO-5.5 | **Slot counts come from configuration, not from the Operator's judgement.** The approved values do not yet exist | PL-2, AO-3.3 |
| AO-5.6 | **An Operator must not promise inventory before the check passes.** The sequence is agreement in principle → create → validate → approve |
| AO-5.7 | **No overbooking waiver exists.** There is no operational path to sell an eleventh slot in a ten-slot Placement |

---

## 6. Approval, activation, suspension and expiry

### 6.1 Approval

| ID | Rule | Source |
| --- | --- | --- |
| AO-6.1 | **Approval and activation are Administrator-only** | §6, ADM-1, `interaction-permissions.md` §4 |
| AO-6.2 | **The approver is not the creator** where more than one staff member exists; with one, it is a recorded accepted risk | OM-3.8 |
| AO-6.3 | **The approver re-checks eligibility and inventory at the moment of approval**, because both can change between creation and approval | §6.1 |
| AO-6.4 | **Approval records actor, time and reason** | ADV-6 |
| AO-6.5 | **An approval must not be given under commercial pressure to bypass an eligibility failure.** That is an escalation to the owner | IN-5, IN-6 |

### 6.2 Activation and delivery

| ID | Rule | Source |
| --- | --- | --- |
| AO-6.6 | **Delivery is automatic between start and end dates.** No daily staff action is required or permitted | §6, ADV-4 |
| AO-6.7 | **Campaign start and stop are evaluated at request time, not by a scheduled job.** No product behaviour depends on a job having run | TR-71, TR-154 |
| AO-6.8 | **A delivery failure is an availability matter, not a commercial one.** It is handled under `observability-operations-v1.0.md` |
| AO-6.9 | **Delivery never alters organic ordering, inclusion or exclusion** | ADV-8, IN-7 |

### 6.3 Suspension

| Trigger | Mechanism |
| --- | --- |
| Loss of eligibility (§7) | **Automatic**; delivery stops and Staff are informed (§6.1, ADV-10) |
| Administrator decision | **Manual, with a recorded reason** (§6) |
| Unresolved material Correction on the Business | Eligibility failure → automatic (§6.1) |
| Serious open report on the Business | Eligibility failure → automatic (§6.1) |
| Listing unpublished for any reason | Eligibility failure → automatic (ADV-10) |

| ID | Rule |
| --- | --- |
| AO-6.10 | **Suspension never requires editing the Listing.** A commercial problem is never solved by changing editorial data (OPX-10.9) |
| AO-6.11 | **A suspension is audited with its reason** (ADV-6) |
| AO-6.12 | **Resumption after suspension is an Administrator action** requiring eligibility to have been restored |
| AO-6.13 | **A business objecting to a suspension is not entitled to an editorial change.** Correcting the underlying data, if it is wrong, happens through the correction route on its own merits (IN-4) |

### 6.4 Expiry

| ID | Rule | Source |
| --- | --- | --- |
| AO-6.14 | **A Campaign ends automatically at the end of its period.** No manual action is required | ADV-4, §6 |
| AO-6.15 | **There is no automatic renewal.** Silence ends the Campaign | §6 |
| AO-6.16 | **An expired Campaign's measurement data is retained for reporting**, under the retention schedule — **PENDING COUNSEL (L-21)** | RET §4 |
| AO-6.17 | **Expiry must not be prevented by an operational omission.** If a Campaign is still delivering after its end date, that is a defect, raised under `observability-operations-v1.0.md` |

---

## 7. Eligibility maintenance

### 7.1 The standard

A Business may be sponsored only if its Listing is **published**,
**verified** and **in good standing**, and it is **not subject to an
unresolved material Correction or a serious open report** (§6.1,
ADV-10).

### 7.2 Operational handling

| ID | Rule |
| --- | --- |
| AO-7.1 | **Eligibility is a continuous condition, not a one-time check.** It is validated at creation, re-validated at approval, and monitored while active (§6.1) |
| AO-7.2 | **Loss of eligibility stops delivery and is raised to Staff** (§6.1) |
| AO-7.3 | **"Good standing" and "serious open report" are not defined numerically anywhere, and are not defined here.** The judgement is an Administrator's and is recorded with its reasoning — **Open — operational decision** |
| AO-7.4 | **A monthly eligibility sweep over active Campaigns is part of the operating cadence** (`operations-model-v1.0.md` §9) |
| AO-7.5 | **Restoring eligibility is editorial work done on its own merits.** It is never expedited because a Campaign is paused (IN-4) |
| AO-7.6 | **An Operator must not create a Campaign for an ineligible Business "pending" eligibility** |

---

## 8. Measurement

| ID | Rule | Source |
| --- | --- | --- |
| AO-8.1 | **Impressions and clicks are recorded per Campaign** | MS-1, ADV-7 |
| AO-8.2 | **Measurement is for reporting only.** It must not affect price and must not feed organic ranking | MS-2, ADV-7, RANK-2 |
| AO-8.3 | **Sponsored impression and sponsored click are two of the defined V1 events** | PRD §26.2 |
| AO-8.4 | **Campaign metrics are read from aggregated rollups, never computed from raw events on demand** | AN-2, OPX-11.1 |
| AO-8.5 | **Analytics must not identify an individual Guest.** A sponsored click records the Campaign, not the clicker | AN-3, TR-202 |
| AO-8.6 | **Analytics must never be on the critical path of a User action.** A measurement failure must not break a page | AN-4, C-38 |
| AO-8.7 | **Invalid or anomalous traffic should be excluded where it can be identified; the method is a technical matter and is not settled here** | MS-6 |
| AO-8.8 | **Granularity and retention of campaign measurement are Open (D-27)** | D-27, AN-5 |

---

## 9. Reporting

| ID | Rule | Source |
| --- | --- | --- |
| AO-9.1 | **Staff must be able to produce a delivery report per Campaign** | MS-3, C-28 |
| AO-9.2 | **Reports go to the business through Staff.** There is no business-facing dashboard in V1 | MS-7, D-54 |
| AO-9.3 | **Every report states what was measured and what was not** | MS-4 |
| AO-9.4 | **Bulbula measures impressions and clicks. It does not measure calls attributable to a Campaign, visits, footfall or sales, and must not imply otherwise** | MS-4 |
| AO-9.5 | **No report may promise or imply an outcome** | MS-5 |
| AO-9.6 | **Unflattering numbers are reported as they are.** A low click count is reported as a low click count | MS-4 |
| AO-9.7 | **Reports must not be reconciled to a figure the business expected**, and must not be revised after issue except to correct an error, which is itself recorded |
| AO-9.8 | **A report contains no personal data about the people who generated the impressions**, because none is held | AN-3 |

### 9.1 Standing language for every report

> Bulbula reports impressions and clicks for this Campaign. Bulbula
> does not measure calls, visits, purchases or revenue, and makes no
> claim about them. Sponsorship does not affect this business's organic
> search ranking, its verification status, its trust indicators or its
> reviews.

| ID | Rule |
| --- | --- |
| AO-9.9 | **Wording may be adapted; the four claims it makes may not be dropped** (MS-4, MS-5, IN-1, IN-2) |

---

## 10. Audit

| ID | Requirement | Source |
| --- | --- | --- |
| AO-10.1 | **Every creation, approval, modification, suspension and termination is recorded with actor, timestamp and reason** | ADV-6, OPX-10.8 |
| AO-10.2 | **Campaign state history is visible on the Campaign record** | OPX-10.2 |
| AO-10.3 | **A failed audit write fails the action** | OPX-0.3, TR-08 |
| AO-10.4 | **The audit trail is append-only** | OPX-12.2 |
| AO-10.5 | **Audit entries survive the Campaign's end** | OPX-12.4 |
| AO-10.6 | **The audit trail is the evidence that organic results were not sold.** It is the primary record if that is ever questioned | TS-15, IN-7 |

---

## 11. Integrity in daily practice

| ID | Practice | Source |
| --- | --- | --- |
| AO-11.1 | **The console provides no path from a Campaign to editing that Business's editorial data** | OPX-10.9 |
| AO-11.2 | **The Campaign queue shows no editorial controls**, and the moderation queue shows no commercial status | OPX-10.9, MO-9.4 |
| AO-11.3 | **A moderator must not be told that a Business is an advertiser**, and must not need to know | IN-3 |
| AO-11.4 | **Advertising performance must not be a staff incentive that conflicts with moderation duties** | IN-5 |
| AO-11.5 | **Commercial pressure on an editorial decision is escalated to the Administrator and then to the owner** | OM-5.5 |
| AO-11.6 | **An identical query produces identical organic ordering with and without an active Campaign.** This is tested, not asserted | IN-7, TR-43, AC-9 |
| AO-11.7 | **The controls that enforce AO-11.1…AO-11.6 organisationally and technically are Open (D-39) and MUST be resolved before the first paid Campaign runs** | IN-6, ADV-12 |

---

## 12. Commercial administration — V1 position

| Item | Position | Status |
| --- | --- | --- |
| Contracting | Offline, between Bulbula and the business | Settled (§9) |
| Invoicing | Manual; no payment gateway | D-11 |
| Billing records in the console | **Open (D-11)** | — |
| Prices | **No price approved** | Owner decision |
| Refunds and make-goods | **Open — commercial policy**; not invented here | — |
| Prohibited advertiser categories | **Open — commercial policy**; a decision, not an assumption | — |
| Tax and regulatory obligations on advertising revenue | **PENDING COUNSEL (L-17, L-20)** | — |
| Advertising content standards | **Open — commercial policy** | — |

| ID | Rule |
| --- | --- |
| AO-12.1 | **Operations must not improvise any row marked Open or PENDING.** A live question in one of these rows stops the Campaign and goes to the owner |
| AO-12.2 | **No revenue number, forecast, pipeline or target appears in this documentation set** |

---

## 13. Traceability

| This document | Traces to |
| --- | --- |
| §1 model | D-10, D-11, D-54; PRD §19.1, §19.3; `advertising-products.md` §1, §2 |
| §2 Packages | PK-1…PK-6; MS-5; PART-3 |
| §3 Placements | PL-1…PL-8; LB-1…LB-8; ADV-1, ADV-2, ADV-3; SUR-4 |
| §4 creation | `advertising-products.md` §6; C-27; ADV-4, ADV-5, ADV-6; OPX-10.2…10.4 |
| §5 inventory | §6; PL-2; OPX-10.5; OPX-0.6 |
| §6 approval…expiry | §6; ADM-1; ADV-4, ADV-10; TR-71, TR-154 |
| §7 eligibility | §6.1; ADV-10; IN-4 |
| §8 measurement | MS-1, MS-2, MS-6; ADV-7; AN-2…AN-5; OPX-11.1; D-27 |
| §9 reporting | MS-3, MS-4, MS-5, MS-7; C-28; D-54 |
| §10 audit | ADV-6; C-29; OPX-10.8, OPX-12.2, OPX-12.4; TS-15 |
| §11 integrity | IN-1…IN-7; ADV-8, ADV-9, ADV-12; OPX-10.9; TR-43 |
| §12 commercial | `advertising-products.md` §9, §10; D-11; L-17, L-20 |

---

## 14. Open items

| ID | Item | Marker |
| --- | --- | --- |
| D-39 | Integrity controls separating commercial from editorial — **blocks the first paid Campaign** | **Open (D-39)** — product decision |
| D-11 | Billing mechanics and billing records in the console | **Open (D-11)** — product decision |
| D-27 | Campaign measurement granularity and retention | **Open (D-27)** — product decision |
| D-14 | Which permission covers campaign create, approve, activate, suspend | **Open (D-14)** — product decision |
| — | Prices per Package | **Not approved** — owner decision |
| — | Inventory counts, slot positions, density values (**[P]**) | **Not approved** — owner decision |
| — | Refund and make-good policy | **Open — operational decision** (commercial policy) |
| — | Prohibited advertiser categories | **Open — operational decision** (commercial policy) |
| — | What "good standing" and "serious open report" mean in practice (AO-7.3) | **Open — operational decision** |
| — | Whether the Package catalogue is a console entity (AO-2.7) | **Open — implementation detail** |
| MS-6 | Method for excluding invalid traffic | **Open — technical decision** |
| L-17 / L-20 | Tax and invoicing obligations on advertising revenue | **PENDING COUNSEL** |
| L-21 | Retention of campaign measurement data | **PENDING COUNSEL** |

---

## Decision references

D-10, D-11, D-14, D-27, D-39, D-54.
