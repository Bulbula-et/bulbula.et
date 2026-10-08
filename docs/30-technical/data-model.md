# Data Model

| | |
| --- | --- |
| **Document** | Conceptual and Logical Data Model — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Scope.** The conceptual and logical model: entities, their purpose,
lifecycle, relationships, identity, uniqueness and data classification.

**Not in scope.** SQL, DDL, column types, index syntax and migration files.
Those are written when the capability that needs them is built, under
[`trd-v1.0.md`](trd-v1.0.md) §30–§31.

**Conventions used below**

| Term | Meaning |
| --- | --- |
| **Required** | A row cannot exist meaningfully without it |
| **Optional** | Absent means *unknown*, never *false* or *zero* (TRD TR-56) |
| **Derived** | Maintained from other data and rebuildable (TRD DO-7) |
| **Class** | Data classification per PRD §21.2: *business*, *personal*, *operational*, *customer*, *derived* |

**Terminology** is fixed by [`../10-product/glossary.md`](../10-product/glossary.md).

---

## 1. Model principles

| ID | Principle | Source |
| --- | --- | --- |
| DM-1 | Explicit relational structures. **No EAV, no generic attribute bags, no JSON blob standing in for columns** where the field is queried or validated | TRD TR-170 |
| DM-2 | Every entity has a single surrogate primary key; natural keys become unique constraints, not primary keys | TRD TR-166 |
| DM-3 | Public URLs use a stable slug, independent of the primary key and stable across renames | SEO-4, TRD TR-100 |
| DM-4 | Uniqueness that matters is enforced by the database, not only by application code | TRD TR-160 |
| DM-5 | Absent means unknown. Booleans are not used to encode "not collected" | TRD TR-56 |
| DM-6 | Personal data is identifiable from this document so a rights request can be executed completely | TRD TR-172, PRIV-7 |
| DM-7 | History that the product promises (Listing changes, moderation, audit) is never destroyed by an ordinary delete | TRD TR-171 |
| DM-8 | Derived tables exist for performance and are rebuildable from source | TRD DO-7 |
| DM-9 | Times are stored in UTC; display uses `Africa/Addis_Ababa` | TRD TR-168 |
| DM-10 | Text is `utf8mb4`, so Amharic stores and compares correctly | TRD TR-165, D-18 |

---

## 2. Entity overview

```text
DIRECTORY                        OPERATIONS
  Business 1──n Branch             PermissionRecord ─── Business
     │  1                            VerificationRecord ── Listing
     │  n                            ListingChange (correction) ── Listing
  BusinessCategory ── Category ── Subcategory          Report
     │                   │                              WorkQueue (derived)
     │                 Alias
  Branch ── Area ── SubCity
     │        │
  Landmark   Alias
     │
  MediaAsset ── MediaAttachment

IDENTITY                         COMMUNITY
  Customer 1──n ProviderIdentity    Review ── Customer, Business/Branch
     │  1──n Session                  │
     │  1──n OtpRequest               ReviewModeration
  StaffUser 1──n Role ── Permission   ReviewReport
     │  1──n StaffAuthFactor        Save ── Customer, Business
     │  1──n Session

ADVERTISING                      PLATFORM
  Package ── Placement             AuditEntry
     │                             AnalyticsEvent → AnalyticsRollup
  Campaign ── Business             NotificationMessage → DeliveryAttempt
     │  1──n CampaignTarget        QueuedJob
     │  1──n CampaignDelivery      SearchDocument (derived)
                                   CacheEntry (optional backend)
```

---

## 3. Directory

### 3.1 Business

| | |
| --- | --- |
| **Purpose** | The brand-level entity Bulbula describes (`glossary.md` §2) |
| **Owner** | Bulbula. **Never a platform account** (D-54, D-02) |
| **Lifecycle** | `draft` → `in_review` → `published` → (`unpublished` \| `closed`). Closure is a state, never a delete (TRD TR-53) |
| **Identity** | Surrogate id; public slug (DM-3) |
| **Class** | *business*, with flagged *personal* exceptions (§3.10) |

| Field group | Fields | Notes |
| --- | --- | --- |
| Identity | id, slug, legal/trading name, name in Amharic (optional) | Slug unique and stable |
| Description | short description, long description | Optional |
| Classification | primary Category, secondary Categories | Cardinality **Open (D-57)** |
| Contact | website, public email, social links | Each optional; may be a personal contact point (§3.10) |
| Publication | status, published_at, unpublished_at, closed_at, closure reason | — |
| Quality | completeness score (derived), verification state (derived from §4.2) | Definition **Open (D-09)** |
| Ratings | rating average, rating count (derived; absent when no published Reviews) | TRD TR-62, TR-63 |
| Concurrency | version / updated_at token | TRD TR-57 |
| Provenance | created_by, created_at, updated_by, updated_at | *operational* |

**Uniqueness.** Slug unique. Name + primary Area is **not** unique (genuinely
similar businesses exist) but is the duplicate-detection signal (TRD TR-55).

**Deletion.** Not deleted in normal operation. Hard deletion exists only as a
staff-executed correction of a mistaken creation, is audited, and cascades to
Branches and media attachments while **preserving** audit entries.

### 3.2 Branch

| | |
| --- | --- |
| **Purpose** | A physical location of a Business (D-03) |
| **Rule** | Every Business has **at least one**; a single-location business has exactly one, never a special case (D-03, PRD BM-1, BM-2) |
| **Class** | *business*, with flagged *personal* exceptions |

| Field group | Fields | Notes |
| --- | --- | --- |
| Identity | id, business_id, branch label (optional), slug segment (optional) | Label needed only for multi-branch |
| Location | Area (required), Sub-city (required), address line, Landmark (optional), latitude, longitude | Sub-city required for administrative correctness (GEO-6) |
| Contact | phone numbers, secondary phone, branch email | Optional; may be personal contact points |
| Hours | opening-hours structure | **Open (D-04)** — see §3.9 |
| Status | active, temporarily closed, permanently closed | Distinct from Business closure |
| Primacy | is_primary | Exactly one per Business |

**Which attributes live on Branch versus Business is Open (D-55).** This
model therefore keeps contact and hours on Branch (the safe default for a
multi-branch business) and brand-level attributes on Business; D-55 may move
specific fields, and the document records that explicitly rather than
pretending the question is settled.

**Uniqueness.** One primary Branch per Business. Coordinates are not unique
(a mall may host several).

### 3.3 Listing (publication concept)

A **Listing** is the published representation of a Business and its Branches
(`glossary.md`). It is **not** a separate table in this model: publication
state lives on Business, and the Listing is the published projection of
Business + Branches + media + taxonomy.

| Why it stays a concept, not a table | |
| --- | --- |
| There is exactly one Listing per Business | A second table would add a join with no additional state |
| Publication state is already on Business | Duplicating it invites divergence |
| Provenance, verification and corrections attach to the Business/Branch rows they describe | Precision is better than a wrapper |

**This is a technical decision (TRD A-2), not a product change.** The product
vocabulary keeps the word Listing; the schema expresses it as the published
state of a Business.

### 3.4 Category and Subcategory

| | |
| --- | --- |
| **Purpose** | The Bulbula-controlled taxonomy, exactly two levels (D-06) |
| **Owner** | Administrator (C-23) |
| **Class** | *business* (reference data) |

| Field | Notes |
| --- | --- |
| id, slug, name (English), name (Amharic, optional) | Slug unique within level |
| parent_id | Null for Category; set for Subcategory. **Exactly two levels** (D-06) |
| display order, visibility | Hidden removes from navigation without unclassifying Listings (C-23) |
| icon reference | Optional |

**Deletion.** Refused while any Business is classified under it (C-23). Merge
reassigns and leaves a redirect record for the retired slug (SEO, C-23).

**Open:** the catalogue itself (D-56) and how many Categories a Listing may
carry (D-57). The join table `business_category` with a `is_primary` flag
supports one primary plus N secondary without pre-deciding N.

### 3.5 Alias

| | |
| --- | --- |
| **Purpose** | Controlled synonyms improving search matching, including Amharic (D-06, D-18) |
| **Attaches to** | Category, Subcategory or Area (polymorphic by explicit target type + id, not a generic EAV) |
| **Class** | *business* (reference data) |

| Field | Notes |
| --- | --- |
| id, target type, target id, alias text, language/script marker | Unique per (target type, target id, normalised alias) |

### 3.6 Area and Sub-city

| | |
| --- | --- |
| **Purpose** | The user-facing location unit (Area) and the administrative unit above it (Sub-city) |
| **Owner** | Administrator (C-24) |
| **Class** | *business* (reference data) |

| Field | Notes |
| --- | --- |
| Area: id, slug, name, name (Amharic), sub_city_id, display order, visibility | Slug unique; Aliases attach via §3.5 |
| Sub-city: id, slug, name, name (Amharic), city | — |
| City/Region, Country | Modelled so the hierarchy Country → City/Region → Sub-city → Area exists (GEO-1), populated one branch deep (GEO-5) |

**Deletion.** Refused while Branches are assigned (C-24). Adding an Area is a
pure data operation — no code change (GEO-4, TRD TR-217).

**Open (D-40):** the launch area's administrative parent and practical
boundary.

### 3.7 Landmark

A named reference point used to help Users locate a Branch. Optional,
attached to a Branch or to an Area. Class: *business*.

### 3.8 MediaAsset and MediaAttachment

| | |
| --- | --- |
| **Purpose** | Photographs that make a Listing credible (C-22) |
| **Owner** | Bulbula; uploaded only by Staff (TRD TR-79) |
| **Class** | *business*; media depicting identifiable people is constrained and **PENDING COUNSEL** (L-18) |

**MediaAsset** — the stored file: id, storage key, original filename,
content type (verified by inspection, TRD TR-80), byte size, dimensions,
checksum, uploaded_by, uploaded_at, source note, derivative references.

**MediaAttachment** — the link to what it depicts: id, asset_id, target type
(Business or Branch), target id, role (primary, gallery), display order.

**Uniqueness.** One primary attachment per target. Checksum is indexed to
detect re-uploads.

**Deletion.** Removal on request from a Business is a staff action, recorded
with a reason (C-22). Originals are retained so derivatives can be
regenerated (TRD TR-83) — subject to the retention schedule, **PENDING
COUNSEL** (L-21).

**Open (D-25):** storage provider, formats, size limits.

### 3.9 Opening hours — deliberately deferred structure

Hours are required by C-09 and `open now` filtering, but the model —
regular hours, split shifts, exceptions, public holidays, 24-hour operation —
is **Open (D-04)**.

| What is fixed now | What D-04 decides |
| --- | --- |
| Hours attach to a Branch (subject to D-55) | Whether split shifts and exception dates are supported |
| Unknown hours are representable and excluded from open-now filtering (TRD TR-56) | Holiday handling |
| Evaluation uses the configured timezone (DM-9) | Whether a "24 hours" marker is a flag or a range |

No hours table is specified here. Specifying one would silently decide D-04.

### 3.10 Personal contact points

A business phone number is frequently a person's personal mobile (PRD §14.4).

| Rule | Model consequence |
| --- | --- |
| PCP-2 | Every contact field carries an `is_personal_contact_point` flag set at collection time |
| PCP-3 | The flag is indexed so all personal contact points for a person can be located during a rights request |
| PCP-4 | A named individual associated with a Business (for example a named practitioner) is *personal* data and is stored only where necessary |
| PCP-5 | Whether a given published field is personal data in law is **PENDING COUNSEL** (L-5) |

---

## 4. Operations

### 4.1 PermissionRecord

| | |
| --- | --- |
| **Purpose** | Evidence that the Business agreed to collection and publication (D-50) |
| **Rule** | **No publication without a linked Permission record** (TRD TR-49) |
| **Class** | *operational*, containing *personal* data (the agreeing person) |

| Field | Notes |
| --- | --- |
| id, business_id | — |
| person name, role claimed | *Personal* — minimised (D-51) |
| method (in person, telephone, written), granted_at | — |
| obtained_by (staff user), scope note | *Operational* |
| state (granted, withdrawn), withdrawn_at, withdrawal reason | Withdrawal must allow prompt unpublication (PRD TS-5) |
| evidence reference (optional) | Contents **Open (D-43)** |

**Open (D-43):** exact contents and retention. The fields above are the
minimum the product needs; D-43 may add or remove.

**Refusal.** A refusal is recorded without creating a Business record for
publication. Whether anything is shown publicly is an **open product question
routed to counsel** (RK-20, `listing-operations.md` §2.3) — until then,
nothing is shown.

### 4.2 VerificationRecord

| | |
| --- | --- |
| **Purpose** | Staff confirmation that facts are accurate (C-21) |
| **Class** | *operational* |

| Field | Notes |
| --- | --- |
| id, business_id (and optionally branch_id), method, verified_at, verified_by, note | Methods **Open (D-08)** |
| scope (what was verified) | — |

**Derived.** "Verification age" and the stale queue are computed from the
most recent record (TRD TR-54) — no batch job needed for correctness.

**Open (D-08):** methods, tiers, re-verification interval, public treatment
of stale Listings.

### 4.3 ListingChange (Correction)

| | |
| --- | --- |
| **Purpose** | The audit of what changed on published data, why and from what source (C-20, COR-1) |
| **Class** | *operational* |

| Field | Notes |
| --- | --- |
| id, target type, target id, field, previous value, new value | Previous/new stored as text representations |
| reason, source, changed_by, changed_at | Required |
| originating report id (optional) | Links a report to the Correction it produced (COR-4) |
| triggers_reverification | Material changes (COR-2) |

**Deletion.** Never deleted by application code (DM-7).

### 4.4 Report

| | |
| --- | --- |
| **Purpose** | Public correction and abuse channel (C-15), the substitute for owner editing |
| **Submitted by** | Guest (Listing problems) or Customer (Review reports) |
| **Class** | *operational*; the free-text body may contain *personal* data supplied by the reporter |

| Field | Notes |
| --- | --- |
| id, target type (Business, Branch, Review), target id | — |
| problem type, free-text description | Structured field-level suggestions are **Open (D-35)** |
| reporter customer_id (nullable for Guests) | Guest reports store **no identity** (TRD TR-202) |
| submitted_at, state (open, triaged, resolved, closed-unverified, rejected) | Every report reaches a recorded outcome (C-26) |
| resolution, resolved_by, resolved_at | — |
| grouping key | Groups duplicate reports on one issue (C-26) |

**Reporter identity is never exposed** to the author or the Business (TRD
TR-65).

### 4.5 Work queues

Queues (awaiting review, reports to triage, reviews to moderate, due for
re-verification) are **derived views over existing state**, not stored
entities. This keeps them correct without a job (TRD TR-154, OC-1).

---

## 5. Identity

### 5.1 Customer

| | |
| --- | --- |
| **Purpose** | An authenticated end user (`glossary.md`) |
| **Class** | ***customer / personal*** |
| **Minimality** | id, email address, display name, created_at, last_seen_at, state. Nothing more without a stated purpose (PRD ACC-6, TRD TR-200) |

| Field | Notes |
| --- | --- |
| id, public reference | Public reference used where an identifier must leave the system |
| email address | Unique when verified; *personal* |
| email verified flag / verified_at | — |
| display name | Shown on Reviews (C-33) |
| state (active, deleted) | Deletion per C-36 |
| created_at, updated_at | — |

**No password column exists anywhere in the model** (D-48).

**Deletion (C-36).** Removes or irreversibly detaches personal data. The
treatment of published Reviews afterwards is **Open — product/legal (D-34,
L-21)**; the model supports both by allowing a Review to reference a deleted
author placeholder.

### 5.2 ProviderIdentity

| | |
| --- | --- |
| **Purpose** | A way one Customer can authenticate (C-30, C-31, C-32) |
| **Class** | *personal* |

| Field | Notes |
| --- | --- |
| id, customer_id, provider (`google`, `email`), provider subject identifier, email at provider, linked_at, last_used_at | Unique per (provider, subject) |

A Customer may have several. **Automatic linking rules are Open (D-13)**: the
model permits multiple identities; it does not decide when two authentications
collapse into one Customer. Ambiguity must never silently merge accounts
(C-32).

**Telegram is not a provider** (D-48). If D-33 later attaches Telegram
context to an identity, it becomes an additional row type without
restructuring (TRD TR-114).

### 5.3 OtpRequest

| | |
| --- | --- |
| **Purpose** | Email OTP state (C-31) |
| **Class** | *personal*, short-lived |

| Field | Notes |
| --- | --- |
| id, email address, **code hash** (never the code, R-25), issued_at, expires_at, consumed_at | Single-use (TRD TR-163) |
| attempt count, request session binding, source network marker | Attempt and rate limits (TRD §34) |

**Values — length, validity window, attempt and request limits — are Open
(TRD OT-02).** The shape is fixed by R-25; the numbers are set in the
security phase.

**Retention.** Pruned aggressively; period is configuration, bounded by the
retention schedule (**PENDING COUNSEL**, L-21).

### 5.4 Session

| | |
| --- | --- |
| **Purpose** | One session concept, two transports (TRD TD-02) |
| **Class** | *personal* |

| Field | Notes |
| --- | --- |
| id, principal type (customer, staff), principal id | — |
| token hash | The cookie value or bearer token is never stored in plaintext |
| surface (web, telegram, api) | Informational |
| created_at, last_active_at, expires_at, revoked_at | Lifetimes **Open (TRD OT-01)** |
| user agent / network marker | Minimal, for abuse review only |

**Revoked on** sign-out, account deletion, and privilege change (rotation).

### 5.5 StaffUser, Role, Permission, StaffAuthFactor

| | |
| --- | --- |
| **Purpose** | Staff accounts and the authorization model (C-19…C-29) |
| **Class** | *personal* (staff are natural persons) + *operational* |

| Entity | Fields |
| --- | --- |
| StaffUser | id, name, work email, state (active, disabled), created_by, created_at. **No self-registration** (ST-1) |
| Role | id, key (`operator`, `administrator`), name |
| Permission | id, key (`listing.publish`, `taxonomy.manage`, `campaign.activate`, `audit.read`, …) |
| RolePermission | role_id, permission_id |
| StaffUserRole | staff_user_id, role_id |
| StaffAuthFactor | id, staff_user_id, factor type, state, enrolled_at — **rows, not columns**, so the eventual D-45 outcome needs no rewrite (TRD TR-31) |

**The Operator/Administrator capability split is Open (D-14).** Permissions
exist as data precisely so the split is configuration, not code (TRD TD-03).

**There is no business role and no business principal** (D-54, TRD TR-40).

---

## 6. Community

### 6.1 Review

| | |
| --- | --- |
| **Purpose** | A Customer's published evaluation (C-13) |
| **Written by** | Authenticated Customers only (D-12) |
| **Class** | *customer / personal* (content authored by an identifiable person) |

| Field | Notes |
| --- | --- |
| id, customer_id, subject type + subject id | **Business or Branch is Open (D-34)** — the model records the subject explicitly so the decision is a data change, not a rewrite |
| rating | Scale **[P] five points**, confirmation under D-34 |
| text | Optional; length limits **Open (D-34)** |
| state (`pending`, `published`, `rejected`, `removed`, `deleted`) | Supports pre- **or** post-publication moderation (TRD TR-60) |
| submitted_at, published_at, edited_at, deleted_at | Edit window **Open (D-34)** |
| moderation reason reference | — |

**Uniqueness.** One Review per Customer per subject, enforced in the database
(TRD TR-160, AB-3).

**No reply structure exists** (TRD TR-67, D-12, D-54).

**Not in V1:** review photos (D-36), helpful votes (D-37). No column, no
table, no placeholder.

### 6.2 ReviewModeration

id, review_id, decision (publish, reject, remove), policy ground (from
`review-policy.md` §5.1), actor, decided_at, reason, appeal reference
(optional). Append-only. Class: *operational*.

### 6.3 ReviewReport

id, review_id, reporter customer_id (**authentication required**), ground,
detail, submitted_at, outcome, resolved_by, resolved_at. Reporter identity is
never exposed (TRD TR-65).

### 6.4 Save

| | |
| --- | --- |
| **Purpose** | The single canonical capability — not "favourite", not "like", not "bookmark" (`glossary.md` §3) |
| **Class** | *customer / personal* |

| Field | Notes |
| --- | --- |
| customer_id, business_id, saved_at | Unique per (customer, business) — enforced in the database (TRD TR-160) |

Deleting a Customer deletes their Saves. There is no public list, no sharing,
no collections.

### 6.5 Rating summary

Derived values on Business: average and count over **published** Reviews
only. Absent when there are none (TRD TR-63). Rebuildable (DM-8). The
computation method is **Open (D-34, SUM-6)**, so it is stored as a derived
value rather than hard-coded in a query that assumes a plain mean.

---

## 7. Advertising

### 7.1 Placement

| | |
| --- | --- |
| **Purpose** | A named, documented slot where Sponsored placements may appear (ADV-3) |
| **Class** | *business* (commercial reference data) |

Fields: id, key (`search_results`, `category_page`, `homepage`), surface,
position description, maximum slots, density rule reference. Values are
**[P] proposed** in `advertising-products.md` until approved.

### 7.2 Package

| | |
| --- | --- |
| **Purpose** | The sellable unit: Placement + target type + duration + fixed price (D-10) |
| **Class** | *business* |

Fields: id, name, placement_id, target type (category, area, none), duration,
**price — no price is approved; the column exists, the values do not**
(`advertising-products.md`), state (available, retired).

### 7.3 Campaign

| | |
| --- | --- |
| **Purpose** | One Business's purchase of one Package for one period (C-27) |
| **Created by** | Operator; **activated by Administrator only** |
| **Class** | *business* + *operational* |

| Field | Notes |
| --- | --- |
| id, business_id, package_id, placement_id | — |
| starts_on, ends_on | Evaluated at request time; no job starts or stops a Campaign (TRD TR-71) |
| state (`draft`, `pending_approval`, `active`, `suspended`, `ended`, `cancelled`) | — |
| approved_by, approved_at, suspended reason, cancelled reason | Audited (TRD TR-75) |
| agreed price, invoice reference | Billing mechanics **Open (D-11)** |

**Uniqueness.** No overlapping active Campaign for the same Placement and
target beyond the Placement's slot count — enforced at creation (TRD TR-72,
TR-160).

### 7.4 CampaignTarget

id, campaign_id, target type (category, subcategory, area), target id. No
keyword targeting, no audience targeting, no behavioural targeting
(`advertising-products.md` §2.4).

### 7.5 CampaignDelivery

| | |
| --- | --- |
| **Purpose** | Impression and click measurement **for reporting only** (ADV-7, MS-2) |
| **Class** | *derived*; **MUST NOT** identify a Guest (TRD TR-202) |

Fields: campaign_id, date, placement_id, impressions, clicks. Aggregated by
day; raw per-event rows, if kept at all, follow the analytics policy
(**Open — D-27**).

**These numbers never affect price or ranking** (TRD TR-74).

---

## 8. Platform

### 8.1 AuditEntry

| | |
| --- | --- |
| **Purpose** | The append-only record of privileged actions (C-29) |
| **Class** | *operational*; contains staff identity (*personal*) |

Fields: id, actor type, actor id, action key, target type, target id, before
summary, after summary, reason, correlation id, occurred_at.

**Append-only.** No update path, no delete path (DO-8). If the entry cannot
be written, the action fails (TRD TR-08). Deleting a Customer does **not**
delete audit entries (TRD TR-167); the retention floor is **PENDING COUNSEL**
(L-21, D-46).

### 8.2 AnalyticsEvent and AnalyticsRollup

| | |
| --- | --- |
| **Purpose** | Measure what the product does (C-38, PRD §26) |
| **Class** | *derived* — **never identifying an individual Guest** (TRD TR-202) |

**AnalyticsEvent** — append-only: id, event type (from PRD §26.2), occurred_at,
subject type, subject id, coarse dimensions (Category, Area, surface, result
position). **No IP address retained as an identifier, no device fingerprint,
no cross-session identifier.**

**AnalyticsRollup** — metric key, dimension keys, period, value. Staff views
read rollups, never raw events (PRD AN-2).

**Granularity, retention and whether raw events are kept at all are Open
(D-27).**

### 8.3 NotificationMessage and DeliveryAttempt

**NotificationMessage** — id, category (from PRD §18), recipient (customer_id
or staff_user_id or address), subject reference, payload reference,
created_at, state (`queued`, `sent`, `failed`, `suppressed`).

**DeliveryAttempt** — id, message_id, attempted_at, outcome, provider
reference, error summary. Bounded retries (TRD TR-161).

Class: *personal* (recipient address). Provider is **Open (D-41)**.

### 8.4 QueuedJob

id, job type, payload, available_at, reserved_at, attempts, last_error,
completed_at. Drained by cron-invoked console commands (TRD TD-06).
Idempotent by construction (TRD TR-150).

### 8.5 SearchDocument

| | |
| --- | --- |
| **Purpose** | The denormalised, maintained text and filter surface for search (TRD TD-04) |
| **Class** | *derived* — rebuildable (TRD TR-45) |

Fields: business_id, composed searchable text (name, Amharic name,
description, Category and Subcategory names and Aliases, Area names and
Aliases, Landmark), plus filter columns: primary category id, area ids,
verified flag, rating average, rating count, completeness, has-hours flag,
published flag, coordinates.

Detail: [`search-design.md`](search-design.md).

### 8.6 CacheEntry (optional backend)

key, value, expires_at — used only if the application cache is backed by the
database rather than the filesystem (**Open — TRD OT-03**). Never contains
personal data (TRD TR-123).

### 8.7 Redirect

old path, new path, reason, created_at — supports stable URLs after a slug
change or a Category merge (SEO-4, C-23).

---

## 9. Data classification summary

| Entity group | Class | Deletion on Customer request | Retention |
| --- | --- | --- | --- |
| Business, Branch, Category, Area, Alias, Landmark, Media | *business* | Not applicable | While published or archived |
| Personal contact points within business data | *personal* (flagged) | On justified request | **PENDING COUNSEL** (L-5) |
| PermissionRecord, VerificationRecord, ListingChange, Report | *operational* (+ personal fragments) | Not deleted; personal fragments minimised | **PENDING COUNSEL** (L-21) |
| Customer, ProviderIdentity, Session, OtpRequest, Save | *customer / personal* | **Yes** (C-36) | Short for OTP and sessions |
| Review, ReviewReport | *customer / personal* | **Open — product/legal (D-34, L-21)** | — |
| StaffUser, Role, StaffAuthFactor | *personal* + *operational* | Staff lifecycle, not Customer deletion | — |
| Package, Placement, Campaign, CampaignTarget | *business* | Not applicable | Commercial records (L-17) |
| AuditEntry | *operational* | **No** (DO-8) | Floor **PENDING COUNSEL** (L-21) |
| AnalyticsEvent, AnalyticsRollup, CampaignDelivery | *derived* | Not applicable (no identity stored) | **Open (D-27)** |
| NotificationMessage, DeliveryAttempt | *personal* (address) | With the Customer | Short |

---

## 10. Entities deliberately **not** created

| Not created | Why |
| --- | --- |
| `business_user`, `business_account`, `claim` | No business accounts, no claims (D-54, D-02) |
| `password`, `password_reset` | No passwords (D-48) |
| `review_reply` | No owner replies (D-12) |
| `review_photo`, `review_vote` | Deferred (D-36, D-37) |
| `bid`, `auction`, `ad_impression_price`, `budget` | No auction, no performance pricing (D-10) |
| `payment`, `transaction`, `gateway_event` | No payment processing (D-11) |
| Separate `listing` table | §3.3 — publication state lives on Business |
| Generic `attribute` / `entity_attribute` tables | DM-1 |
| `user` table shared by Customers and Staff | Separate principals, separate lifecycles (PRD ACC-8) |

---

## 11. Open data questions

| ID | Question | Effect |
| --- | --- | --- |
| D-04 | Opening-hours model | §3.9 intentionally unspecified |
| D-08 | Verification rules and interval | Thresholds are configuration |
| D-09 | Completeness definition | Stored as derived, unweighted |
| D-11 | Billing records | Invoice reference only |
| D-13 | Identity linking | Multiple identities supported; no auto-merge |
| D-14 | Operator/Administrator split | Permissions as data |
| D-25 | Media storage and limits | Storage key is adapter-agnostic |
| D-27 | Analytics granularity and retention | Raw-event policy open |
| D-33 | Telegram identity | No Telegram provider row in V1 |
| D-34 | Review subject, scale, limits, deletion semantics | Subject stored explicitly |
| D-35 | Guest structured suggestions | Free-text reports only |
| D-40 | Launch-area boundary | Area data provisional |
| D-43 | Permission record contents and retention | Minimum fields only |
| D-44 | Services / products / pricing | **No table specified** — see below |
| D-55 | Branch versus Business attributes | Defaults stated, decision pending |
| D-56 / D-57 | Category catalogue and cardinality | Join table supports either |
| L-21 / D-46 | Retention schedule | Expiry supported, periods unset |

**Services, products and pricing (D-44).** C-08 displays them "where
captured", but their representation is unresolved. Specifying a table now
would decide D-44 by accident. The model therefore records the requirement
and the dependency, and leaves the structure to the decision.

---

## Decision references

D-02, D-03, D-04, D-06, D-08, D-09, D-10, D-11, D-12, D-13, D-14, D-18,
D-25, D-27, D-33, D-34, D-35, D-36, D-37, D-40, D-41, D-43, D-44, D-45,
D-46, D-48, D-50, D-51, D-54, D-55, D-56, D-57.
