# Bulbula V1 Scope

| | |
| --- | --- |
| **Document** | V1 Scope Reference |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — (compact successor to `docs/00-discovery/product-decision-brief-v0.4.md` §4–§5) |

**Purpose.** The compact, authoritative answer to "is this in V1?". The PRD
references this document instead of restating scope lists. Decisions remain
authoritative in [`docs/60-decisions/decision-register.md`](../60-decisions/decision-register.md).

**Rule of interpretation.** If a capability is not listed in §1, it is **not**
in V1. Absence from §2 is not permission.

---

## 1. V1 — included (40 approved capabilities)

Approved by D-01. Each capability is specified in
[`prd-v1.0.md`](prd-v1.0.md) §12 under the reference shown.

### 1.1 Public discovery (18)

| # | Capability | PRD ref | Open sub-decisions |
| --- | --- | --- | --- |
| 1 | Homepage | C-01 | — |
| 2 | Search | C-02 | D-09 |
| 3 | Search autocomplete | C-03 | — |
| 4 | Category and subcategory browsing | C-04 | D-56, D-57 |
| 5 | Location / area browsing | C-05 | D-40 |
| 6 | Category × area pages | C-06 | D-56 |
| 7 | Nearby / distance discovery | C-07 | — |
| 8 | Business profile pages | C-08 | D-44, D-55 |
| 9 | Opening hours and open status | C-09 | D-04, D-55 |
| 10 | Google Maps embed and open-in-maps | C-10 | D-21 |
| 11 | Business contact actions | C-11 | D-55 |
| 12 | Trust indicators | C-12 | D-08 |
| 13 | Reviews (read and write) | C-13 | D-34, D-36, D-37 |
| 14 | Save | C-14 | — |
| 15 | Report a problem / suggest a correction | C-15 | D-35 |
| 16 | Sponsored placements | C-16 | D-10 detail |
| 17 | Sharing | C-17 | — |
| 18 | Static information and policy pages | C-18 | legal items |

### 1.2 Bulbula internal operations (11)

| # | Capability | PRD ref | Open sub-decisions |
| --- | --- | --- | --- |
| 19 | Listing creation | C-19 | D-43, D-50 |
| 20 | Listing editing | C-20 | D-14 |
| 21 | Verification and quality control | C-21 | D-08 |
| 22 | Media management | C-22 | D-25 |
| 23 | Category management | C-23 | D-06, D-56, D-57 |
| 24 | Location management | C-24 | D-40 |
| 25 | Review moderation | C-25 | D-34 |
| 26 | Report management | C-26 | D-35 |
| 27 | Advertising and campaign management | C-27 | D-11, D-39 |
| 28 | Operational analytics | C-28 | D-27 |
| 29 | Audit logs | C-29 | D-14 |

### 1.3 Customer accounts (7)

| # | Capability | PRD ref | Open sub-decisions |
| --- | --- | --- | --- |
| 30 | Google authentication | C-30 | — |
| 31 | Email OTP authentication | C-31 | D-41 |
| 32 | Unified Bulbula identity | C-32 | D-13, D-33 |
| 33 | Customer profile | C-33 | D-51 |
| 34 | Save management | C-34 | — |
| 35 | Customer review management | C-35 | D-34 |
| 36 | Account deletion and privacy controls | C-36 | D-46 |

### 1.4 Cross-cutting (4)

| # | Capability | PRD ref | Open sub-decisions |
| --- | --- | --- | --- |
| 37 | SEO | C-37 | D-18 |
| 38 | Analytics | C-38 | D-27 |
| 39 | Notifications | C-39 | D-24, D-41 |
| 40 | Privacy and data handling | C-40 | D-42, D-46 |

### 1.5 Surface obligation

Every capability is delivered on **both** first-launch surfaces (Web and
Telegram Mini App) except:

| Exception | Reason |
| --- | --- |
| C-37 SEO | Web only — Telegram content is not crawled |
| C-19…C-29 operations | Web only — staff tooling |

Source: D-49.

---

## 2. V1 — excluded

**Not in V1.** Any requirement implying one of these is a defect.

| Excluded | Why / decision |
| --- | --- |
| Business accounts | D-54 |
| Business login | D-54 |
| Owner-created listings | D-02 |
| Owner claims | D-02 |
| Owner listing management | D-02 |
| Owner profile control | D-02 |
| Owner review replies | D-12 — no account to reply from |
| Owner self-service advertising | D-10 |
| Owner self-service billing | D-10 |
| Auction, bidding, CPC, CPM, CPA, programmatic | D-10 |
| Password authentication | D-48 |
| Apple Sign In | D-48 (revisit for iOS: D-47) |
| Flutter client | D-15 |
| Online ordering | Out of product scope |
| Reservations / bookings | Out of product scope |
| Loyalty programmes | Out of product scope |
| Messaging / leads between Users and Businesses | Future |
| Jobs | Out of product scope |
| Local news | Out of product scope |
| Subscriptions | Out of product scope |
| Advanced AI recommendations | Future |
| Afaan Oromo interface | D-18 |
| Full bilingual (Amharic) interface | D-18 — requires separate approval |
| National-scale geographic expansion | D-01; the model stays expandable, the data does not |
| Payment gateway | D-10, D-11 — advertising is invoiced manually |
| Native push notifications | Requires the Flutter client |
| Review photos | D-36 (Deferred) |
| "Helpful" voting on reviews | D-37 (Deferred) |

---

## 3. Future (post-V1, direction approved)

| Capability | Condition |
| --- | --- |
| Business accounts with **mandatory administrator approval** of every submission | D-54; architecture seams exist in V1, features do not |
| Owner-created listings and claims | D-02, same approval gate |
| Owner review replies | Requires business accounts |
| Self-service advertising and billing | Requires business accounts plus L-17/L-20 |
| Flutter client (Android, then iOS) | D-15, D-15r; same backend, same API contracts, separate client |
| Sign in with Apple | D-47 — likely required by App Store rules when the iOS client ships |
| Amharic interface | D-18 — separate owner approval |
| Expansion beyond the launch area | Location model already supports it |

---

## 4. Unresolved sub-decisions affecting V1

These do **not** change scope; they change detail inside an approved
capability. Full list in the decision register §2.

| ID | Affects | Must resolve before |
| --- | --- | --- |
| D-04 | Opening-hours model | UX / data model |
| D-08 | Verification rules and interval | UX / data model |
| D-09 | Completeness definition and ranking weight | UX / data model |
| D-13 | Identity-linking rules | Data model |
| D-14 | Operator / Administrator split | UX / data model |
| D-21 | Maps key, billing, fallback | UX |
| D-25 | Media storage and limits | Data model |
| D-27 | Analytics granularity and retention | Data model |
| D-33 | Telegram identity (**open evaluation**) | Mini App auth design |
| D-34 | Review mechanics detail | UX / data model |
| D-35 | Guest structured suggestions | UX |
| D-39 | Ad/editorial integrity controls | Implementation |
| D-41 | Transactional email provider | Implementation |
| D-42 | Data-location policy and hosting | Implementation |
| D-43 | Permission record contents and retention | Data model |
| D-44 | Services / products / pricing representation | Data model |
| D-45 | Staff authentication strength (**open**) | TRD / security |
| D-55 | Branch vs Business attribute boundary | Data model |
| D-56 | Category catalogue production | Content |
| D-57 | Category cardinality per Listing | Data model |

---

## 5. Pending external items

Not scope questions, but they gate PRD approval and launch.

| Item | Owner | Status |
| --- | --- | --- |
| Legal/compliance confirmation (D-46 and the L-items) | Counsel | **Pending** |
| 20-business operational pilot (D-31) | Bulbula operations | **Pending** |
| Numeric launch threshold (D-30n) | Project owner, after the pilot | **PENDING PILOT** |
| Launch-area boundary confirmation (D-40) | Local confirmation | **Pending** |

---

## Decision references

D-01, D-02, D-04, D-06, D-08, D-09, D-10, D-11, D-12, D-13, D-14, D-15,
D-18, D-21, D-24, D-25, D-27, D-30, D-31, D-33, D-34, D-35, D-36, D-37,
D-39, D-40, D-41, D-42, D-43, D-44, D-45, D-46, D-47, D-48, D-49, D-50,
D-51, D-54, D-55, D-56, D-57.
