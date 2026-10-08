# Business Model

| | |
| --- | --- |
| **Document** | Business Model |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** How Bulbula creates value, for whom, and how it intends to earn
money. Product requirements live in
[`../10-product/prd-v1.0.md`](../10-product/prd-v1.0.md); the advertising
product definitions live in
[`advertising-products.md`](advertising-products.md).

**No prices.** No price has been approved. This document therefore contains
**no prices, no price ranges, no revenue projections and no targets.** Any
number of that kind would be invented (D-10, D-11).

---

## 1. What Bulbula is

A **company-operated local business directory**. Bulbula employs people to
find businesses, obtain their permission, collect their information, verify
it, publish it and keep it current. Businesses are the *subject* of the
product, not its operators (D-02, D-50, D-54).

This is the central structural choice, and everything else follows from it:

| Choice | Consequence |
| --- | --- |
| Bulbula builds the data | Quality is controllable; growth is limited by operational capacity, not by business sign-ups |
| Businesses have no accounts | No two-sided cold-start problem; no owner-spam; but no free labour either |
| Coverage is narrow and deep | Credible in the launch area from day one; irrelevant outside it |
| Advertising is staff-sold | No self-service funnel; revenue is limited by sales effort, not by product |

---

## 2. Value propositions

### 2.1 For Users (Customers and Guests)

| Value | How it is delivered | Capability |
| --- | --- | --- |
| **Find a specific local business quickly** | Search, categories, areas, nearby | C-02, C-04, C-05, C-07 |
| **Reach it successfully** | Numbers, hours and locations that are verified and current | C-09, C-11, C-12 |
| **Avoid a wasted trip** | Open-now status and accurate location | C-09, C-10 |
| **Judge before going** | Reviews, photographs, completeness | C-08, C-13 |
| **Zero friction** | No account required for anything in the discovery journey | `interaction-permissions.md` §3.1 |
| **Available where people already are** | Web and Telegram Mini App | D-15, D-49 |

The User pays nothing and is not the product: no behavioural advertising, no
data sale, no third-party advertising tracking (PRD NFR-PR4).

### 2.2 For Businesses

| Value | How it is delivered |
| --- | --- |
| **A free, accurate public presence** | Bulbula creates and maintains it at no cost to the business |
| **No effort required** | One conversation and a permission; no account, no forms, no maintenance |
| **Reachable by nearby customers** | Local discovery, local intent |
| **Findable on the open web** | SEO work the business would not do itself (C-37) |
| **Credibility** | Verified information, presented consistently (C-12) |
| **Optional additional visibility** | Clearly labelled sponsorship, sold and managed by staff (C-16, C-27) |

### 2.3 For Bulbula

A defensible asset: a verified, permissioned local dataset that is expensive
to copy, with a direct commercial relationship with the businesses in it.

---

## 3. The relationship between the two sides

Bulbula is **not** a conventional two-sided marketplace in V1. The sides are
asymmetric by design.

| | Users | Businesses |
| --- | --- | --- |
| Accounts | Yes (optional) | **No** (D-54) |
| Create content | Reviews, Saves, reports | **Nothing** (D-02) |
| Pay | Never | Optionally, for sponsorship |
| Relationship | Product relationship | **Human relationship**, through Staff |

**Implications accepted deliberately:**

| Implication | Position |
| --- | --- |
| Bulbula carries the full cost of data creation | Accepted; it is the source of the quality advantage |
| Growth is limited by operational throughput | Accepted; the pilot measures the real rate (D-31) |
| Businesses cannot fix their own errors | Mitigated by the correction route (PRD §20.1), which is a first-class capability |
| Selling requires human contact | Accepted in V1; staff-managed advertising is the approved model (D-10) |

**The permission conversation is also the sales conversation.** The Operator
who collects a Listing is the same person a business already knows when
sponsorship is later discussed. That is an advantage of the model, and it is
precisely why the integrity controls separating commercial relationships from
moderation and verification matter — **Open (D-39)**, and required before the
first paid Campaign (PRD ADV-12).

---

## 4. Revenue model

### 4.1 V1 — one revenue line

| Line | Status | Decision |
| --- | --- | --- |
| **Advertising: staff-managed, fixed-package sponsorship** | Approved for V1 | D-10 |
| Everything else | Not in V1 | — |

**Not in V1:** paid listings, paid verification, paid ranking, consumer
subscriptions, commission on transactions, lead fees, data licensing, and any
performance-priced advertising (`../10-product/scope-v1.md` §2).

### 4.2 Properties of the V1 model

| Property | Statement | Decision |
| --- | --- | --- |
| **Fixed packages** | A package has a stated placement, duration and fixed price. The business knows the cost in advance | D-10 |
| **Staff-managed** | Staff create, configure and run Campaigns. There is no self-service surface | D-10, D-54 |
| **Sold offline** | Negotiated in person; the console records the result | D-10 |
| **No auction** | No bidding, no dynamic pricing | D-10 |
| **No performance pricing** | No CPC, CPM, CPA. Measurement is for reporting, never for billing | D-10, ADV-7 |
| **Invoiced manually** | No payment gateway in V1; billing records are **Open (D-11)** | D-11 |
| **Time-bounded** | Every Campaign has start and end dates and stops automatically | ADV-4 |

### 4.3 Why fixed packages rather than an auction

| Reason | |
| --- | --- |
| Inventory is tiny | One area's worth of categories cannot clear an auction |
| Buyers are small businesses | A predictable price is sellable; a bid is not (persona B1) |
| Operationally simple | No bidding infrastructure, no budget pacing, no fraud surface |
| Protects integrity | Nothing about pricing can influence ranking (RANK-2) |
| Local payment reality | Mobile-money-first, gateway friction is real (R-08) |

### 4.4 What is deliberately not monetized

| Not monetized | Why |
| --- | --- |
| Listing inclusion | A directory with paid inclusion is not a directory |
| Verification and trust markers | Purchasable trust destroys the product's one differentiator (ADV-9) |
| Organic ranking | RANK-2, RANK-5 |
| Review outcomes | `review-policy.md` §7 — non-negotiable |
| User data | No sale, no sharing for advertising |

---

## 5. Cost structure

Qualitative only; no figures are available or invented.

| Cost | Driver |
| --- | --- |
| Field operations | The dominant cost: Operator time per Listing — measured by the pilot (D-31) |
| Verification and maintenance | Recurring; driven by the verification interval (**Open, D-08**) |
| Moderation and report handling | Scales with Users and Listings |
| Infrastructure | Hosting, media delivery, maps, email — vendors **Open (D-21, D-25, D-41, D-42)** |
| Compliance | Legal advice, registration duties (R-26); **PENDING COUNSEL** (D-46) |
| Sales | Staff time per sponsorship sold |

**The structural question** — whether sponsorship revenue in one area can
cover the cost of collecting and maintaining that area — is open and is
answered by the pilot plus early sales, not by assertion.

---

## 6. Launch and success conditions

| Condition | Status |
| --- | --- |
| Coverage threshold for the launch area | **PENDING PILOT** — the number does not exist (D-30n) |
| Data quality at launch | Every Listing permissioned and verified (D-50, C-21) |
| Both surfaces live | D-15, D-49 |
| Legal minima met | **PENDING COUNSEL** (D-46) |

**Early indicators** (defined, not targeted — no targets are approved):
searches performed, zero-result rate, profile views, contact actions per
Listing, return usage, Review volume, report volume and correction
turnaround, Listings maintained per Operator-week, and sponsorship
conversations converted. All are available from C-28 and §26 of the PRD.

---

## 7. Future directions

Direction only; nothing below is approved for V1. See
[`../10-product/roadmap.md`](../10-product/roadmap.md).

| Direction | Dependency |
| --- | --- |
| Business accounts, with mandatory approval of every submission | D-54 |
| Self-service advertising purchase | Business accounts, billing, tax (L-17, L-20) |
| Online payment | D-11; local payment reality (R-08) |
| Additional sponsorship formats | Within the D-10 boundary unless D-10 is revisited |
| Geographic expansion | Operational capacity, not software (GEO-4) |
| Additional clients (Flutter) | D-15, D-15r |

**Explicitly not pursued as a direction:** paid inclusion, paid verification,
paid ranking, review suppression services, and data sale. These are excluded
on principle, not on sequencing.

---

## Decision references

D-02, D-08, D-10, D-11, D-15, D-15r, D-21, D-25, D-30, D-31, D-39, D-41,
D-42, D-46, D-49, D-50, D-54.
