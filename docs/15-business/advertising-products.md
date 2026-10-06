# Advertising Products

| | |
| --- | --- |
| **Document** | Advertising Products |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** What Bulbula sells, where it appears, how it is labelled, how it
is run and how it is measured. The product requirements are in
[`../10-product/prd-v1.0.md`](../10-product/prd-v1.0.md) §19 (ADV-1…ADV-12)
and capabilities C-16 and C-27; this document holds the commercial
definitions.

**No prices.** No price has been approved (D-10, D-11). Every package below
is defined by **what it delivers**, not by what it costs. Prices are set by
the owner and recorded when approved — **not here, and not by inference.**

**Proposed values.** Inventory counts, density limits and placement positions
marked **[P]** are *proposals* requiring owner approval. They are stated so
that design and implementation have something concrete to react to, and are
clearly distinguished from approved rules.

---

## 1. The model in one paragraph

Bulbula sells **fixed-price, time-bounded sponsorship packages**, negotiated
offline by Staff and configured in the operations console. There is no
auction, no bidding, no performance pricing, no self-service purchase and no
automated targeting. Sponsored content is always labelled, always separate
from organic results, and never influences them (D-10).

---

## 2. Approved sponsorship forms

Exactly three forms exist in V1. No other form may be sold or built.

### 2.1 Sponsored placement in search results

| | |
| --- | --- |
| **What the business gets** | A labelled position within a search result list for queries matching its Category or Subcategory |
| **Surface** | Search results (C-02), both client surfaces |
| **Targeting** | Category or Subcategory. **Not** keyword-level, **not** behavioural, **not** individual |
| **Position** | **[P]** One slot at the top of the first page of results; additional slots, if any, below the fold |
| **Inventory** | **[P]** One concurrent Campaign per Category per period |
| **Label** | Required (§4) |

### 2.2 Category sponsorship

| | |
| --- | --- |
| **What the business gets** | A labelled position on a specific Category or Subcategory page, and on the corresponding category × area pages |
| **Surface** | Category pages (C-04), category × area pages (C-06) |
| **Targeting** | One Category or Subcategory |
| **Position** | **[P]** One prominent slot above the organic list |
| **Inventory** | **[P]** One concurrent Campaign per Category per period |
| **Label** | Required (§4) |

### 2.3 Homepage promotion

| | |
| --- | --- |
| **What the business gets** | A labelled position in a defined homepage slot |
| **Surface** | Homepage (C-01), both client surfaces |
| **Targeting** | None — the homepage is undifferentiated |
| **Position** | **[P]** One defined block, below the primary search entry point |
| **Inventory** | **[P]** A small fixed number of positions within that block, rotated per impression |
| **Label** | Required (§4) |

### 2.4 Not sold

Keyword targeting · audience or behavioural targeting · retargeting ·
interstitials · pop-ups · auto-playing media · banner networks ·
third-party ad tags · sponsored Reviews · sponsored editorial · paid
inclusion · paid verification · paid organic ranking · any placement not
listed in §2.1–§2.3.

---

## 3. Packages

A **Package** is the sellable unit: a Placement, a target, a duration and a
fixed price.

| Element | Rule |
| --- | --- |
| Placement | Exactly one of §2.1, §2.2, §2.3 |
| Target | The Category, Subcategory or Area the Placement requires; none for homepage |
| Duration | A fixed period with explicit start and end dates (ADV-4) |
| Price | Fixed, stated in advance, in Ethiopian Birr. **No price is approved yet** |
| What is excluded | No guarantee of clicks, calls, customers or ranking position — see §7 |

### 3.1 Package rules

| ID | Rule |
| --- | --- |
| PK-1 | A Package **MUST** state exactly what the business receives, where, and for how long |
| PK-2 | A Package **MUST NOT** include anything that touches organic ranking, verification, trust indicators or Reviews (ADV-9) |
| PK-3 | Prices are fixed per Package and **MUST NOT** vary by performance (D-10) |
| PK-4 | The Package catalogue is maintained by the owner; adding a Package type beyond §2 requires a decision |
| PK-5 | Discounts, if any, are a commercial matter recorded with the Campaign, never a product mechanism |
| PK-6 | A Package **MUST NOT** be bundled with a promise about Listing content, correction priority or moderation outcomes |

---

## 4. Labelling

Non-negotiable. Distinguishability of paid results is a regulatory
expectation, not a design preference (R-04).

| ID | Rule |
| --- | --- |
| LB-1 | Every Sponsored placement **MUST** carry a visible text label |
| LB-2 | The label **MUST** be legible without interaction — no hover, no tap, no tooltip |
| LB-3 | The label **MUST** use consistent wording across every surface and both client surfaces (ADV-2) |
| LB-4 | The label **MUST NOT** rely on colour, shading or position alone (NFR-AC3) |
| LB-5 | Label wording **[P]**: "Sponsored". Final wording, including the Amharic rendering when the interface becomes bilingual, is confirmed in the UX phase |
| LB-6 | Sponsored items **MUST** be visually distinguishable from organic items beyond the label alone — a container, separator or equivalent treatment |
| LB-7 | The product **MUST NOT** blend sponsored items into an organic list in a way that makes the boundary ambiguous |
| LB-8 | A public page **MUST** explain what sponsorship is and that it does not affect organic ranking (C-18) |

---

## 5. Placement and density rules

| ID | Rule |
| --- | --- |
| PL-1 | Every Placement **MUST** be documented here before it may be sold or built (ADV-3) |
| PL-2 | Each Placement has a maximum number of slots; sponsored content **MUST NOT** appear outside documented Placements |
| PL-3 | **[P]** Sponsored items **MUST NOT** exceed one in the first five organic results on any list surface |
| PL-4 | **[P]** No surface may be more than approximately one-fifth sponsored content by item count |
| PL-5 | Unsold inventory **MUST** collapse. No placeholder, no house advertisement, no empty frame (C-01) |
| PL-6 | Sponsored content **MUST NOT** displace organic results; it occupies its own slots (ADV-8) |
| PL-7 | A Business **MUST NOT** appear more than once on a surface as a result of sponsorship — if it would also rank organically, it appears in both only where that is clearly distinguishable; otherwise it appears once, in the sponsored slot |
| PL-8 | Placements **MUST** be identical in position and treatment across Web and Telegram (SUR-4) |

---

## 6. Campaign lifecycle

```text
offline agreement → Operator creates Campaign → validation
   → Administrator approves and activates → runs between dates
   → measured → ends automatically → reported to the business
```

| Stage | Rule | Capability |
| --- | --- | --- |
| Creation | Operator records business, Package, Placement, target, period | C-27 |
| Validation | Eligibility and inventory availability checked at creation; overbooking refused | C-27 |
| Approval | **Administrator only** | C-27, ADM-1 |
| Delivery | Automatic between start and end dates | C-16 |
| Suspension | Administrator, with a recorded reason; ineligibility suspends automatically | C-16, C-27 |
| End | Automatic at period end; no manual action required | ADV-4 |
| Renewal | A new Campaign, never a silent extension | C-27 |
| Audit | Every state change recorded with actor, time and reason | ADV-6, C-29 |

### 6.1 Eligibility

A Business may be sponsored only if its Listing is published, verified and in
good standing, and if it is not subject to an unresolved material Correction
or a serious open report (ADV-10). Loss of eligibility stops delivery and is
raised to Staff.

---

## 7. Measurement and reporting

| ID | Rule |
| --- | --- |
| MS-1 | Impressions and clicks **MUST** be recorded per Campaign (ADV-7) |
| MS-2 | Measurement is for **reporting only**. It **MUST NOT** affect price (fixed packages) and **MUST NOT** feed organic ranking (RANK-2) |
| MS-3 | Staff **MUST** be able to produce a delivery report per Campaign (C-28) |
| MS-4 | Reports **MUST** state what was measured and what was not. Bulbula measures impressions and clicks; it does **not** measure calls attributable to a Campaign, visits, or sales |
| MS-5 | Bulbula **MUST NOT** promise or imply outcomes: no guaranteed clicks, calls, customers, revenue or ranking position |
| MS-6 | Invalid or anomalous traffic **SHOULD** be excluded from reports where it can be identified; the method is a TRD matter |
| MS-7 | Businesses receive reports through Staff. There is no business-facing dashboard in V1 (D-54) |

---

## 8. Integrity

The commercial relationship and the editorial function must not touch.

| ID | Rule |
| --- | --- |
| IN-1 | Sponsorship **MUST NOT** influence organic ranking, inclusion or exclusion (ADV-8) |
| IN-2 | Sponsorship **MUST NOT** influence verification, trust indicators or the rating summary (ADV-9) |
| IN-3 | Sponsorship **MUST NOT** influence Review moderation outcomes (`../10-product/review-policy.md` §7) |
| IN-4 | Sponsorship **MUST NOT** buy priority in correction or report handling |
| IN-5 | Advertising performance **MUST NOT** be a staff incentive that conflicts with moderation duties |
| IN-6 | The specific organisational and technical controls enforcing IN-1…IN-5 are **Open (D-39)** and **MUST** be resolved before the first paid Campaign runs (ADV-12) |
| IN-7 | An identical query **MUST** produce identical organic ordering with and without an active Campaign; this is a testable property (RANK-3) |

---

## 9. Commercial administration

| Item | V1 position |
| --- | --- |
| Contracting | Offline, between Bulbula and the business |
| Invoicing | Manual; no payment gateway (D-11) |
| Billing records in the console | **Open (D-11)** |
| Tax and regulatory obligations for advertising revenue | **PENDING COUNSEL** (L-17, L-20) |
| Refunds and make-goods | **Open — commercial policy**; not invented here |
| Advertising content standards (what a sponsored business may not be) | **Open — commercial policy**; prohibited-category rules are a decision, not an assumption |

---

## 10. Open items

| Item | Status |
| --- | --- |
| Prices per Package | **Not approved** — owner decision |
| Inventory counts, positions, density values (**[P]** in §2 and §5) | Require owner approval |
| Label wording (LB-5) | UX phase |
| Billing record structure | **Open (D-11)** |
| Integrity controls | **Open (D-39)** — blocks the first paid Campaign |
| Prohibited advertiser categories | **Open — commercial policy** |
| Refund and make-good policy | **Open — commercial policy** |
| Tax and invoicing obligations | **PENDING COUNSEL** (L-17, L-20) |

---

## Decision references

D-10, D-11, D-39, D-46, D-54.
