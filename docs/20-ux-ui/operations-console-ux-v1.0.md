# Operations Console UX

| | |
| --- | --- |
| **Document** | Operations Console UX — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The interface through which Bulbula's own staff build and
maintain the directory. Covers C-19…C-29.

**Surface.** **Web only** (`scope-v1.md` §1.5). There is no Telegram
operations surface and no mobile operations app in V1.

**Actor model.** **Operator** and **Administrator** only. There is **no
business-owner actor, no business login, no claim flow and no self-service
surface of any kind** (D-54, D-02).

---

## 0. Rules for the whole console

| ID | Rule | Source |
| --- | --- | --- |
| OPX-0.1 | **Where D-14 is open, every screen names the required *permission*, never the role.** The interface must not decide who holds a permission | D-14, TD-03 |
| OPX-0.2 | An action a staff member lacks permission for is **not rendered**; a direct request fails **without disclosing whether the target exists** | ENF-2, TRD TR-35 |
| OPX-0.3 | Every privileged action is audited with actor, action, target, time and reason. **If the audit write fails, the action fails** | TR-08, C-29 |
| OPX-0.4 | Staff authentication is **separate** from Customer authentication, with its own entry point | ST-2, ACC-8, D-45 |
| OPX-0.5 | The console is **never linked from a public page**, never mentioned in public navigation and never indexed | SEO-8, ENF-2 |
| OPX-0.6 | A state-changing action that cannot proceed is refused with a **named blocker**, never a generic failure | UFL-C3 |
| OPX-0.7 | **Nothing in this console can alter, hide or reorder public ranking for commercial reasons** | D-39, ADV-9 |
| OPX-0.8 | Destructive and irreversible actions require explicit confirmation naming the consequence | WCAG 3.3.4 |
| OPX-0.9 | The console meets the same **WCAG 2.2 AA** target as the public surface. Staff tooling is not exempt | NFR-AC1 |
| OPX-0.10 | Queue and filter state lives in the URL so a view is shareable between staff | §2.3 |
| OPX-0.11 | **No capability excluded from V1 appears here**, including owner replies, business messaging and self-service advertising | PRD §13 |

---

## 1. Permissions referenced

Permissions are **named strings**; the mapping to Operator or
Administrator is **D-14, open** (TD-03). This document uses the names
below and does not assign them.

| Permission | Used by |
| --- | --- |
| `listing.create` · `listing.edit` · `listing.verify` · `listing.publish` · `listing.unpublish` | §3, §4, §5 |
| `media.manage` | §6 |
| `taxonomy.manage` · `location.manage` | §7 — **Administrator** (`interaction-permissions.md` §4) |
| `review.moderate` · `review.policy` | §8 — policy is **Administrator** |
| `report.handle` | §9 |
| `campaign.create` · `campaign.approve` · `campaign.activate` · `campaign.suspend` | §10 — approval, activation and early termination are **Administrator** |
| `analytics.read` | §11 |
| `audit.read` | §12 — **Administrator** |
| `staff.manage` | §13 — **Administrator** |

---

## 2. Console structure

### 2.1 Navigation

```text
/ops
├── Dashboard            work waiting, by queue
├── Listings             C-19, C-20, C-21
│    ├── All listings
│    ├── Drafts
│    ├── In review              ← quality-review queue
│    ├── Published
│    ├── Needs re-verification  ← derived from last verification
│    └── Unpublished / closed
├── Taxonomy             C-23
├── Locations            C-24
├── Reviews              C-25
├── Reports              C-26
├── Advertising          C-27
│    ├── Packages
│    ├── Placements
│    └── Campaigns
├── Analytics            C-28
└── Audit                C-29
```

| ID | Rule |
| --- | --- |
| OPX-2.1 | A section the staff member cannot act in **is not shown** (OPX-0.2) |
| OPX-2.2 | Navigation is persistent, consistently ordered and keyboard-reachable (WCAG 3.2.3) |
| OPX-2.3 | The current section and queue are marked programmatically, not by colour alone |

### 2.2 Dashboard

Counts of work waiting in each queue the staff member can act on, each
linking to its filtered queue; recent own actions; anything blocked and
why.

**No vanity metrics.** Counts are work, not scores. There is no
leaderboard and no per-staff performance comparison.

**Empty state.** "Nothing waiting" is a legitimate, complete state.

### 2.3 Queues

Every queue is a data table (§37 of the component spec) with: a filter bar
(§38), a result count, sortable columns, status badges (§39), pagination
(§27) and row actions named with their subject.

| ID | Rule |
| --- | --- |
| OPX-2.4 | Default order is **oldest first** — queues are worked, not browsed |
| OPX-2.5 | Filter and sort state is in the URL (OPX-0.10) |
| OPX-2.6 | Bulk actions exist only where they are safe; a bulk state change always confirms and always names the count |
| OPX-2.7 | An empty queue says so plainly; it is not an error |
| OPX-2.8 | Timestamps are absolute with a timezone, never "3 days ago" alone — operations work needs exact times |

---

## 3. Listing creation (C-19)

**Permission.** `listing.create`.

**Flow.** [`user-flows-v1.0.md`](user-flows-v1.0.md) §C1.

### 3.1 Screen order

1. **Duplicate detection** (§42) — **before any data entry** (TR-55)
2. **Permission record** — blocks publication if absent (TR-49, D-50)
3. Business facts
4. Branches — at least one; exactly one primary (D-03)
5. Classification — Category and Subcategory
6. Media — optional (§6)
7. Submit for quality review

### 3.2 Requirements

| ID | Requirement | Source |
| --- | --- | --- |
| OPX-3.1 | Duplicate candidates by name and Area are shown first; proceeding past them is allowed and **recorded** | TR-55 |
| OPX-3.2 | The **Permission** block records person, role claimed, method, date and who obtained it. Its exact contents are **Open (D-43)** | D-50, D-43 |
| OPX-3.3 | **Publication is blocked without a Permission record**, and the blocker is named | TR-49 |
| OPX-3.4 | Required and optional fields are visibly distinguished; **"unknown" is a valid recordable state**, distinct from empty | TR-56 |
| OPX-3.5 | Each Branch requires an Area and a Sub-city | GEO-6 |
| OPX-3.6 | Exactly one Branch is primary | D-03 |
| OPX-3.7 | **Provenance** — where each fact came from — is recorded as the Listing is built | C-19, C-21 |
| OPX-3.8 | A completeness indicator (§44) lists **which fields are missing**, never only a percentage. Its weighting is **Open (D-09)** | D-09 |
| OPX-3.9 | **No field for services, products or pricing exists.** Specifying one would decide **D-44** | D-44 |
| OPX-3.10 | A draft is saved without validation so work is never lost; validation applies at submission | — |
| OPX-3.11 | Contact points may be flagged as personal; the flag is internal and never surfaces publicly | `data-model.md` §3.10 |

**Error states.** Field-level validation with the rule stated. Submitting
without Permission names Permission as the blocker. A duplicate confirmed
as the same Business routes to the existing record rather than creating a
second.

---

## 4. Listing editing and Corrections (C-20)

**Permission.** `listing.edit`. **Flow.** §C2.

| ID | Requirement | Source |
| --- | --- | --- |
| OPX-4.1 | Changing a **published** field requires a **reason and a source** | TR-52 |
| OPX-4.2 | A Correction records field, previous value, new value, reason, source, actor and time | COR-1 |
| OPX-4.3 | **Material changes flag re-verification**; the flag is visible on the record | COR-2 |
| OPX-4.4 | The Correction history is visible on the record, newest first | COR-3 |
| OPX-4.5 | A Correction that resolves a Report **links to that Report** | COR-4 |
| OPX-4.6 | A stale concurrency token produces a **conflict message showing what changed and who changed it**; the edit is **never silently overwritten** | TR-57, O-4 |
| OPX-4.7 | The search document updates **synchronously**; the change is visible immediately and the screen says so | TR-45, TD-04 |
| OPX-4.8 | Which fields a given permission may edit is **Open (D-14)**; the screen is built around named permissions so the answer is configuration | D-14 |

**Unpublishing and closure.** Both require a reason. **Closure is a state,
not a deletion**: the URL and the history survive (TR-53). Unpublishing
removes the page from the sitemap and returns 404 with noindex on the
public side (SEO-13, C-08).

---

## 5. Verification, quality review and publication (C-21)

**Permissions.** `listing.verify`, `listing.publish`. **Flow.** §C3.

### 5.1 Quality-review queue

Listings in review, oldest first, each showing completeness, Permission
state, Verification state and who created it.

### 5.2 The review screen

The full record plus the **Permission**, **Provenance** and
**Verification** blocks side by side with the data they justify, so the
reviewer can see the evidence next to the claim.

| ID | Requirement | Source |
| --- | --- | --- |
| OPX-5.1 | Publication is **refused with a named blocker** when Permission or Verification is missing | TR-49, TR-50, O-1 |
| OPX-5.2 | Recording a Verification captures method, date, scope and actor | C-21 |
| OPX-5.3 | Methods, tiers and the re-verification interval are **Open (D-08)**; the form reads them from configuration and hard-codes nothing | D-08 |
| OPX-5.4 | **Separation of duties:** where another reviewer exists, the publisher is not the creator. Where Bulbula operates with a single staff member this is a **recorded operational risk**, not a removed control | `interaction-permissions.md` n.3 |
| OPX-5.5 | Returning a Listing for rework requires a reason and routes it back to its creator |
| OPX-5.6 | Every publish, unpublish and verification is audited; **a failed audit write fails the action** | TR-08 |

### 5.3 Stale listings

A **Needs re-verification** queue derived from the most recent
Verification date at read time — **no scheduled job is required** (TR-54,
TR-154, TD-06). Sorted by staleness. Staleness is shown on the public
profile as an honest date, never hidden (UXP-7.3).

---

## 6. Media management (C-22)

**Permission.** `media.manage`.

| ID | Requirement | Source |
| --- | --- | --- |
| OPX-6.1 | Each media item records its **provenance and the right to publish it** | C-22, **L-18** |
| OPX-6.2 | **Alt text is required**, not optional; media without a description is a completeness gap | DSN-11.13, WCAG 1.1.1 |
| OPX-6.3 | Upload shows accepted formats and limits **before** the attempt, and reports a rejection with the actual reason | WCAG 3.3.1 |
| OPX-6.4 | Media is re-encoded to bounded display sizes; the original is never served to a phone | DSN-11.8 |
| OPX-6.5 | Ordering, replacing and removing media are explicit actions; **ordering never requires dragging** — a non-drag alternative always exists | WCAG 2.5.7 |
| OPX-6.6 | Removing media is confirmed and audited | OPX-0.3 |

**Open (D-25):** storage location, formats, dimensions, size caps and
retention.

---

## 7. Taxonomy and locations (C-23, C-24)

**Permissions.** `taxonomy.manage`, `location.manage` —
**Administrator-only** (`interaction-permissions.md` §4).

### 7.1 Taxonomy

| Action | Behaviour | Source |
| --- | --- | --- |
| Create / edit a Category | **Two levels only** — Category and Subcategory | D-06 |
| Manage Aliases | Including Amharic Aliases, which drive search matching | D-18, SRCH-4 |
| Hide a Category | Removes it from public navigation **without unclassifying** its Listings | C-23 |
| Delete a Category | **Refused** while any Business is classified under it; the refusal names the count and links to them | C-23 |
| Merge Categories | Reassigns Listings and **leaves a permanent redirect** for the retired slug | SEO-4 |
| Reorder | Affects public display order; never a ranking signal | D-39 |

A bulk taxonomy change **queues a rebuild of the affected search
documents and says so**, with the affected count (`search-design.md`
SD-3). Every change is audited.

### 7.2 Locations

| Action | Behaviour | Source |
| --- | --- | --- |
| Create / edit an Area | Area plus Sub-city; optional landmarks | GEO-6 |
| Add an Area | **Pure data — no deployment and no code change** | GEO-4, TR-217 |
| Delete an Area | **Refused** while Branches are assigned | C-24 |
| Merge Areas | Reassigns Branches and leaves a redirect | SEO-4 |

**Settled (D-56, D-57).** The Category catalogue is **centrally owned and
manually curated by Bulbula** and is maintained **here, as data** — adding,
renaming, merging or hiding a Category **must not** require a code change or
a deployment. There are **no user-created** Categories. Each Listing takes
**exactly one primary Category**, mandatory before publication, and **zero
or more secondary Categories** with **no artificial maximum**; the console
prevents duplicates and prevents the primary also being a secondary.

**Open.** The launch-area boundary (D-40). The console must keep Areas
editable as data rather than assuming any particular answer.

---

## 8. Review moderation (C-25)

**Permissions.** `review.moderate`; policy changes and appeals are
`review.policy` — **Administrator**. **Flow.** §C4.

### 8.1 Queue

Pending and reported Reviews, oldest first, with report counts and
grounds.

### 8.2 Decision screen

Full text · subject Business · author display name · reports with their
grounds · prior decisions on this author's Reviews `[P]`.

| ID | Requirement | Source |
| --- | --- | --- |
| OPX-8.1 | A decision **cannot be submitted without a policy ground** from the published list | `review-policy.md` §5.1 |
| OPX-8.2 | **Reporter identity is never shown** to the author or the Business | TR-65 |
| OPX-8.3 | Report volume is **context only**, never an automatic trigger | REP-3 |
| OPX-8.4 | Decisions are recorded append-only and are visible to the author with the ground | C-25, C-35 |
| OPX-8.5 | A rejected Review is **not silently deleted**; the author is told | UFL-B5.8 |
| OPX-8.6 | **Staff must not write Reviews from staff accounts**, and the console provides no affordance to do so | `review-policy.md` §7 |
| OPX-8.7 | **Moderation precedes publication** (D-34). The queue holds Reviews in `pending`; approval publishes them and rejection means they were never public. Removing an already-published Review stays available as a separate action |
| OPX-8.8 | A queued Review shows **which Branch it concerns**, not only the Business, because the Review belongs to the Branch (D-34, D-55) |
| OPX-8.9 | A Review with a rating and **no text** is a valid Review and is presented as such in the queue, not as an empty or broken item (D-34) |
| OPX-8.10 | An **edited** Review re-enters the queue and is marked as an edit of an already-published Review, so the moderator sees the change in context (D-34) |
| OPX-8.8 | **There is no owner-reply moderation surface** — owner replies do not exist | D-12, D-54 |

---

## 9. Report management (C-26)

**Permission.** `report.handle`. **Flow.** §C5.

| ID | Requirement | Source |
| --- | --- | --- |
| OPX-9.1 | Open reports are **grouped where several describe one issue** | C-26 |
| OPX-9.2 | The screen shows the target's report history, so a pattern is visible |
| OPX-9.3 | Resolution routes to the right tool: a Correction (§4), a moderation decision (§8), or closure with an outcome |
| OPX-9.4 | **Every report reaches a recorded outcome** — resolved · closed-unverified · rejected | C-26 |
| OPX-9.5 | A report resolved by a Correction **links to that Correction** | COR-4 |
| OPX-9.6 | Guest reports carry **no identity**; the queue must not imply one exists | TR-202 |
| OPX-9.7 | Legal escalation is an **Administrator** action | `interaction-permissions.md` §4 |

**Open (D-35):** whether guest suggestions become structured
field-level corrections. The queue is built for free text today.

---

## 10. Advertising (C-27)

**Permissions.** `campaign.create`; approval, activation, suspension and
early termination are **Administrator**. **Flow.** §C7.

### 10.1 Packages

Package definitions and their Placements. **No price value is approved**
(D-10, D-11); the field exists, the numbers do not. A package can be
retired without affecting Campaigns already sold under it (PK-6).

### 10.2 Placements

The **three** sponsorship forms and no others
(`advertising-products.md` §2): the search-result slot, Category
sponsorship, and homepage promotion. Each shows its availability by period.

| ID | Requirement | Source |
| --- | --- | --- |
| OPX-10.1 | Density per Placement is `[P]` and unapproved — the console reads it from configuration | PL-3, PL-4 |
| OPX-10.2 | **An unsold Placement collapses entirely on the public side**; the console states this as the behaviour, not an option | PL-5, C-01 |
| OPX-10.3 | A Business **cannot appear twice on one page** as sponsored and organic; the console enforces this at activation | PL-7 |

### 10.3 Campaigns

| ID | Requirement | Source |
| --- | --- | --- |
| OPX-10.4 | Creation checks availability and is **refused with a clear conflict** when the Placement and period are sold out | TR-72, O-5 |
| OPX-10.5 | **Approval and activation are Administrator-only**; so is early termination, with a reason | §1 |
| OPX-10.6 | Campaign state is evaluated **at request time**; no job starts or stops a Campaign | TR-71, TD-06 |
| OPX-10.7 | **No bid, budget, CPC, CPM or CPA field exists anywhere** | D-10, CR-3 |
| OPX-10.8 | Delivery figures are **reporting only** and never feed ranking or price | TR-74, MS-2 |
| OPX-10.9 | The advertising section **cannot reach** Listing content, verification, trust indicators or Reviews. There is no navigation path from a Campaign to editing the Business's data | ADV-9, PK-2, D-39 |
| OPX-10.10 | Every lifecycle action is audited with its reason | TR-75 |
| OPX-10.11 | Invoicing, VAT and licensing are **out of scope for this console** — **PENDING COUNSEL** (L-16, L-17, L-20) and **D-11 open** | D-11 |

### 10.4 Delivery reports

Impressions and clicks per Campaign and Placement, by period, **labelled
explicitly as reporting**. They are never presented as a quality signal,
never shown publicly, and never influence organic order (MS-2, D-39).

---

## 11. Analytics (C-28)

**Permission.** `analytics.read`.

| View | Content | Source |
| --- | --- | --- |
| Coverage | Listings by Category and Area; gaps | C-28 |
| Quality | Completeness distribution; verification freshness; stale counts | C-21, C-28 |
| Throughput | Created, reviewed, published, corrected per period | C-28 |
| Content signals | Top queries, **zero-result queries**, reports by type | SRCH-8, C-26 |
| Campaign delivery | §10.4 | TR-74 |

| ID | Requirement | Source |
| --- | --- | --- |
| OPX-11.1 | Views read **rollups, never raw events** | AN-2 |
| OPX-11.2 | **No view identifies an individual Guest.** There is no session replay, no user timeline and no per-person view | TR-202, PRD §21 |
| OPX-11.3 | Zero-result queries are the most actionable view — they drive coverage and Alias work | SRCH-8, UR-05 |
| OPX-11.4 | Granularity and retention are **Open (D-27)** | D-27 |
| OPX-11.5 | Whether an Operator sees all work or only their own is **Open (D-14)**; the screens are permission-scoped so either answer is configuration | D-14 |
| OPX-11.6 | Exports are bounded jobs, not unbounded synchronous queries | `performance-and-caching.md` §12 |

---

## 12. Audit (C-29)

**Permission.** `audit.read` — **Administrator-only**
(`interaction-permissions.md` §4).

| ID | Requirement | Source |
| --- | --- | --- |
| OPX-12.1 | Append-only history of privileged actions: actor, action, target, timestamp, reason | C-29 |
| OPX-12.2 | **No edit control and no delete control exists anywhere in the interface** | DO-8 |
| OPX-12.3 | Filterable by actor, action, target type and date; paginated | §37 |
| OPX-12.4 | Entries **survive Customer deletion** and contain no personal data | TR-167 |
| OPX-12.5 | Audit entries are reachable from the record they concern, and from the audit section | — |
| OPX-12.6 | Reading the audit log is itself subject to permission and is not hidden from other Administrators | — |

---

## 13. Staff accounts (C-29 adjacent)

**Permission.** `staff.manage` — **Administrator**.

Creating, suspending and removing staff accounts, and assigning
permissions. **The permission catalogue is fixed; the assignment is
configuration** (TD-03). Every change is audited.

**Open (D-45):** the staff authentication mechanism. **Open (D-14):** the
default permission set per role. The screen is built around named
permissions, so neither answer changes its structure.

---

## 14. Console-wide states

| State | Behaviour |
| --- | --- |
| Loading | Skeleton rows matching the table; filters stay interactive |
| Empty queue | "Nothing waiting" — a complete, non-error state |
| Validation failure | Field-level, focus to the first error, every other value preserved (WCAG 3.3.3, 3.3.7) |
| Permission failure | The action is not rendered; a direct request fails without disclosing existence (OPX-0.2) |
| Blocked action | A **named blocker** adjacent to the disabled path, with the route to resolve it (OPX-0.6) |
| Conflict | What changed, who changed it, and when; the edit is never silently overwritten (OPX-4.6) |
| Audit failure | **The action fails.** The staff member is told the action did not take effect (TR-08) |
| Session expiry | The in-progress form is preserved and restored after re-authentication (WCAG 3.3.7) |

---

## 15. Accessibility

The console meets **WCAG 2.2 AA** (OPX-0.9). Beyond the shared
requirements in [`accessibility-v1.0.md`](accessibility-v1.0.md):

| ID | Requirement |
| --- | --- |
| OPX-15.1 | Tables use real header cells with scope; sort state is announced (WCAG 1.3.1) |
| OPX-15.2 | Row actions have names including the row subject — "Publish — Tsegaye Pharmacy" (WCAG 2.4.6) |
| OPX-15.3 | Keyboard operation is sufficient for every task. **Operators work fast; the keyboard path is a primary path, not a fallback** |
| OPX-15.4 | Status badges carry text, never colour alone (WCAG 1.4.1) |
| OPX-15.5 | Long forms are sectioned with headings and support interruption and resumption (WCAG 3.3.7) |
| OPX-15.6 | No task requires dragging (WCAG 2.5.7) — notably media ordering (OPX-6.5) |
| OPX-15.7 | Dense tables still meet the 24 px target floor with adequate spacing (WCAG 2.5.8) |
| OPX-15.8 | Queue count changes are announced (WCAG 4.1.3) |

---

## 16. Performance

Shared hosting, one server, no cache service (TD-05), no worker (TD-06).

| ID | Requirement | Source |
| --- | --- | --- |
| OPX-16.1 | Every queue is paginated with a bounded page size; **no screen loads an unbounded set** | `performance-and-caching.md` |
| OPX-16.2 | Exports and bulk operations are bounded jobs with progress feedback | §11 |
| OPX-16.3 | Derived queues such as stale listings are computed at read time, not by a job | TR-154, TD-06 |
| OPX-16.4 | Search-document rebuilds triggered by taxonomy changes report their scope and do not block the interface | SD-3 |
| OPX-16.5 | Console load must not degrade public performance; heavy reads are bounded and paginated | NFR-P |

---

## 17. Explicitly absent from this console

| Absent | Why |
| --- | --- |
| Business owner login, claim, dashboard, messaging | D-54, D-02 |
| Owner reply moderation | D-12 |
| Self-service advertising purchase | D-10 |
| Bid, budget or auction controls | CR-3 |
| Any control that reorders organic results commercially | D-39, ADV-9 |
| Review photo moderation | D-36 |
| Helpful-vote management | D-37 |
| Per-staff performance scoring or leaderboards | §2.2 |
| Individual Guest profiles or session replay | TR-202 |
| Audit edit or delete | DO-8 |
| Bulk public messaging or email campaigns | Email is transactional only (D-24) |
| A Telegram operations surface | `scope-v1.md` §1.5 |

---

## 18. Capability coverage

| Capability | Section |
| --- | --- |
| C-19 Listing creation | §3 |
| C-20 Listing editing and Corrections | §4 |
| C-21 Verification and quality review | §5 |
| C-22 Media | §6 |
| C-23 Category management | §7.1 |
| C-24 Location management | §7.2 |
| C-25 Review moderation | §8 |
| C-26 Report management | §9 |
| C-27 Campaign management | §10 |
| C-28 Operations analytics | §11 |
| C-29 Audit logs | §12 |

**All eleven operations capabilities are covered.**

---

## 19. Open items

| ID | Item | Sections |
| --- | --- | --- |
| D-08 | Verification methods, tiers, interval | §5 |
| D-09 | Completeness weighting | §3, §5 |
| D-10 / D-11 | Ad pricing, packages, billing | §10 |
| D-14 | Operator / Administrator permission split | all |
| D-25 | Media storage and limits | §6 |
| D-27 | Analytics granularity and retention | §11 |
| D-35 | Structured guest suggestions | §9 |
| D-40 | Launch-area boundary | §7.2 |
| D-43 | Permission-record contents | §3 |
| D-44 | Services, products and pricing fields | §3 |
| D-45 | Staff authentication mechanism | §13 |
| L-16 / L-17 / L-20 | Ad disclosure, invoicing, VAT, licence | §10 — **PENDING COUNSEL** |
| L-18 | Photography rights | §6 — **PENDING COUNSEL** |
| L-21 | Retention | §11, §12 — **PENDING COUNSEL** |

---

## Decision references

D-02, D-03, D-06, D-08, D-09, D-10, D-11, D-12, D-14, D-18, D-24, D-25,
D-27, D-34, D-35, D-36, D-37, D-39, D-40, D-43, D-44, D-45, D-50, D-54,
D-55, D-56, D-57.
