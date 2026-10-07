# API Specification v1

| | |
| --- | --- |
| **Document** | API Specification — `/api/v1` |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Scope.** The contract and its boundaries: conventions, authentication,
errors, pagination, caching, and the endpoint families with their purpose,
actor and shape.

**Not in scope.** PHP classes, controller code, serializer implementations
and exhaustive field-by-field schemas. Field lists here are the *contract
surface*, not a database projection — the model is in
[`data-model.md`](data-model.md).

**Why the API exists.** The Telegram Mini App, progressive-enhancement
fetches on the Web, and the later Flutter client (PRD FL-2) all consume it.
**The Web surface does not render pages by calling it** (TRD TD-01): both
call the same application services in-process.

---

## 1. Conventions

### 1.1 Base path and versioning

| Item | Value |
| --- | --- |
| Base path | `/api/v1` |
| Transport | HTTPS only |
| Content type | `application/json; charset=utf-8` |
| Versioning | Path-based, established in `routes/api.php` from the first endpoint |
| Compatibility | `/api/v1` changes are **additive only**. A breaking change introduces `/api/v2` (TRD TR-23) |

**Additive-only means:** new optional fields and new endpoints are allowed;
removing a field, renaming it, changing its type, narrowing an enum or
changing a default is not.

### 1.2 Resource naming

| Rule | Example |
| --- | --- |
| Plural nouns for collections | `/api/v1/businesses` |
| Stable public slug or opaque id in the path | `/api/v1/businesses/{slug}` |
| Sub-resources express containment | `/api/v1/businesses/{slug}/reviews` |
| Verbs only for actions that are not resource state | `/api/v1/auth/otp/request` |
| Operations namespace is separate | `/api/v1/ops/...` |
| No file extensions, no trailing slash, lowercase and hyphenated | — |

### 1.3 Request conventions

| Item | Rule |
| --- | --- |
| Query parameters | `snake_case`; unknown parameters are **rejected**, not ignored (TRD §34) |
| Bodies | JSON objects; unknown fields rejected |
| Booleans | `true`/`false`, never `1`/`0` |
| Dates and times | ISO 8601 with offset; stored UTC, rendered for `Africa/Addis_Ababa` |
| Language | `Accept-Language` is accepted and recorded; V1 responses are English-first with Amharic labels where stored (D-18) |
| Surface | `X-Bulbula-Surface: web | telegram` — informational; it **MUST NOT** change domain behaviour (PRD SUR-6) |

### 1.4 Response envelope

Collections:

```json
{
  "data": [ { "...": "..." } ],
  "meta": { "page": 1, "per_page": 20, "total": 134, "has_more": true },
  "links": { "self": "...", "next": "...", "prev": null }
}
```

Single resources return the object under `data`. `meta` and `links` are
omitted where they carry no information.

### 1.5 HTTP status conventions

| Status | Used for |
| --- | --- |
| `200` | Successful read, or a successful write that returns the resource |
| `201` | Resource created; `Location` header set |
| `204` | Successful write with nothing to return (for example a delete) |
| `304` | Conditional GET matched the validator |
| `400` | Malformed request: bad JSON, unknown field, wrong type |
| `401` | Authentication required or invalid |
| `403` | Authenticated but not permitted |
| `404` | Not found — **also returned instead of `403` where existence itself is privileged** (TRD TR-35) |
| `405` | Method not allowed; `Allow` header set (existing behaviour) |
| `409` | Conflict: duplicate, concurrent edit, overlapping Campaign |
| `410` | Resource permanently removed where that is meaningful to the client |
| `422` | Validation failed on a well-formed request |
| `429` | Rate limited; `Retry-After` set, thresholds **not** disclosed |
| `500` | Unexpected failure; opaque message plus correlation id |
| `503` | Dependency unavailable; used by readiness |

### 1.6 Error envelope

```json
{
  "error": {
    "code": "validation_failed",
    "message": "The request could not be processed.",
    "request_id": "01JB2K7W3Q8X5R0M4D9F6T1C2A",
    "details": [
      { "field": "rating", "code": "out_of_range", "message": "Rating is outside the permitted range." }
    ]
  }
}
```

| Rule | Statement |
| --- | --- |
| E-1 | `code` is stable and machine-readable; clients branch on it, never on `message` |
| E-2 | `message` is safe: no stack trace, no SQL, no file path, no internal identifier (TRD TR-144) |
| E-3 | `request_id` is always present and matches the correlation id in the logs (TRD TR-12) |
| E-4 | `details` appears only for validation failures |
| E-5 | Authentication failures **MUST NOT** reveal whether an account exists (C-31) |
| E-6 | Rate-limit responses **MUST NOT** disclose the limit |

### 1.7 Pagination, filtering, sorting

| Item | Rule |
| --- | --- |
| Pagination | `page` and `per_page`; `per_page` has a documented maximum and is clamped, never honoured unbounded (TRD TR-30, TR-47) |
| Deep pagination | Bounded by a maximum offset; the API is not a bulk-export surface |
| Style | Offset-based in V1; keyset is **Open — technical decision (TRD OT-08)** for high-volume lists |
| Filtering | Explicit named parameters only (`category`, `area`, `open_now`, `min_rating`, `verified`) — no generic query-object syntax |
| Sorting | `sort` with an allow-list (`relevance`, `distance`, `rating`); unknown values are rejected |
| Stability | Ordering **MUST** be stable across pages, with a deterministic tiebreaker (PRD SRCH-6) |

### 1.8 Caching

| Response class | Headers |
| --- | --- |
| Public read (discovery, profiles, taxonomy) | `Cache-Control: public, max-age=…, stale-while-revalidate=…` plus `ETag`; conditional requests supported |
| Search results | Short `max-age`, varying by every filter in the key (TRD TR-122) |
| Authenticated or staff | `Cache-Control: no-store` (TRD TR-120) |
| Health | `no-store` (existing behaviour) |

`Vary` includes `Accept-Language` and, where it affects output, the surface
header. **No cached response may contain personal data** (TRD TR-123).

### 1.9 Idempotency

| Case | Mechanism |
| --- | --- |
| `GET`, `HEAD` | Naturally safe; **never** mutate state (TRD TR-157) |
| `PUT`, `DELETE` | Idempotent by definition |
| Natural uniqueness (Save, Review per subject) | Enforced in the database; a repeat returns the existing state, not a duplicate (TRD TR-160) |
| OTP verification | Single-use; a replay fails inside the validity window (TRD TR-163) |
| Report submission, campaign creation | `Idempotency-Key` header accepted; a repeat with the same key returns the original outcome (TRD TR-159) |

### 1.10 Rate limiting and correlation

| Item | Rule |
| --- | --- |
| Limited paths | Authentication and OTP, review submission, report submission, search, all `/ops/*` writes |
| Dimensions | Per principal where authenticated; per source network for Guests |
| Values | Configuration, not constants (TRD TR-196); **not published, not disclosed in responses** |
| Response | `429` with `Retry-After` |
| Correlation | Every response carries `X-Request-Id`; a client-supplied one is accepted and echoed if well-formed (TRD TR-12) |

### 1.11 Authentication and authorization

| Item | Rule |
| --- | --- |
| Transport | `Authorization: Bearer <opaque token>` bound to a server-side session (TRD TD-02) |
| Issuance | By the auth endpoints in §3.1; detail in [`auth-identity.md`](auth-identity.md) |
| Cookies | The Web surface uses the session cookie; the API accepts bearer tokens so the Mini App does not depend on third-party cookie behaviour (R-23) |
| CSRF | Bearer-authenticated requests are not cookie-authenticated and therefore not CSRF-exposed; cookie-authenticated JSON writes require the CSRF token |
| Guest access | All public read endpoints work with no credential (GS-1) |
| Staff | `/api/v1/ops/*` requires a staff session **and** the named permission (TRD TR-36, TD-03) |
| Business | **No business principal exists** (D-54) — there is no business authentication, anywhere |

---

## 2. Public discovery endpoints

All are Guest-safe, cacheable, and require no credential
(`interaction-permissions.md` §3).

### 2.1 Discovery and taxonomy

| Method | Path | Purpose | Capability |
| --- | --- | --- | --- |
| `GET` | `/discovery/home` | Homepage composition: discovery blocks, featured Categories and Areas, Sponsored placements for the homepage Placement | C-01 |
| `GET` | `/categories` | Category tree (two levels, D-06), with counts of published Listings | C-04 |
| `GET` | `/categories/{slug}` | One Category with its Subcategories | C-04 |
| `GET` | `/areas` | Areas with Sub-city, counts of published Listings | C-05 |
| `GET` | `/areas/{slug}` | One Area | C-05 |

**Rules.** Categories and Areas with no published Listings are not returned
as navigable entries (C-04, C-05). Responses include Amharic labels where
stored (D-18, GEO-3).

### 2.2 Search and autocomplete

| Method | Path | Purpose | Capability |
| --- | --- | --- | --- |
| `GET` | `/search` | Primary search | C-02 |
| `GET` | `/search/suggest` | Autocomplete suggestions | C-03 |

**`GET /search` parameters**

| Parameter | Notes |
| --- | --- |
| `q` | Query text; optional when browsing by filter alone |
| `category`, `subcategory`, `area` | Slugs |
| `open_now` | Boolean; Listings with unknown hours are **excluded** (C-09, TRD TR-56) |
| `min_rating`, `verified` | Quality filters |
| `lat`, `lng` | Only when the User granted location; **never stored** (TRD TR-201) |
| `sort` | `relevance` \| `distance` \| `rating` |
| `page`, `per_page` | Clamped (§1.7) |

**Response.** `data` is the organic result list. Sponsored placements are
returned in a **separate** `sponsored` array with their Placement key and
label — never interleaved by the server into `data` (TRD TR-43, ADV-8).
Clients render them in the documented slots with the label (ADV-1).

```json
{
  "data": [ { "slug": "...", "name": "...", "category": {...}, "area": {...},
              "rating": { "average": 4.3, "count": 17 },
              "open_status": "open" | "closed" | "unknown",
              "verified": true, "verified_on": "2026-09-14",
              "distance_m": 420 } ],
  "sponsored": [ { "placement": "search_results", "label": "Sponsored",
                   "business": { "slug": "...", "name": "..." } } ],
  "meta": { "page": 1, "per_page": 20, "total": 48, "has_more": true,
            "zero_result": false }
}
```

**Zero results** return `200` with an empty `data`, `zero_result: true` and a
`recovery` object offering concrete next actions (C-02). The query is
recorded for operational review (PRD SRCH-8).

**`GET /search/suggest`** returns a bounded list of typed suggestions
(business, category, subcategory, area), each with a target path. Matching
includes Amharic Aliases (D-18, TRD TR-44). Failure of the suggestion service
**MUST NOT** break plain search (C-03).

### 2.3 Business profiles

| Method | Path | Purpose | Capability |
| --- | --- | --- | --- |
| `GET` | `/businesses/{slug}` | Full public profile | C-08 |
| `GET` | `/businesses/{slug}/branches` | Branches with location, hours, contact | C-08, C-09 |
| `GET` | `/businesses/{slug}/reviews` | Published Reviews, paginated | C-13 |
| `GET` | `/businesses/nearby` | Distance-ordered Businesses | C-07 |

**Profile response includes:** name, slug, description, Categories, Branches
(Area, Sub-city, address, landmark, coordinates, hours, open status, contact
actions), media references, rating summary, trust indicators (verified state
and verification date, C-12), share metadata (C-17), and services/products
where captured — **shape Open (D-44)**.

**Profile response excludes, always:** provenance, Permission records,
verification method, staff identities, moderation internals, reporter
identities, and any internal numeric id (TRD TR-25, DO-4).

| Rule | Statement |
| --- | --- |
| PR-1 | An unpublished or removed Listing returns `404`, and **MUST NOT** be indexable (C-08) |
| PR-2 | A permanently closed Business returns `200` with `status: "closed"` — the URL and history survive (TRD TR-53) |
| PR-3 | Missing optional data is **omitted**, not returned as empty strings or `"unknown"` clutter (C-08) |
| PR-4 | Contact actions are present only where a contact point exists; no disabled placeholders (C-11) |
| PR-5 | `open_status` is `"unknown"` when hours are not confirmed — never `"closed"` (C-09) |
| PR-6 | Map data is the Branch coordinates plus the open-in-maps target; the client degrades to address and landmark if the map fails (C-10) |

`GET /businesses/nearby` requires `lat` and `lng`, returns approximate
distances, and is bounded by a maximum radius and page size. Coordinates are
used in-request and discarded (TRD TR-201).

### 2.4 Reports (Guest-safe)

| Method | Path | Purpose | Capability |
| --- | --- | --- | --- |
| `POST` | `/reports` | Report a problem with a Listing or suggest a correction | C-15 |

Body: target type and slug, problem type from a published enum, optional
free-text description, optional contact address. **No account required**
(TS-2). Rate-limited per source network without disclosing the limit (C-15).
Returns `202` with a reference. Structured field-level suggestions are
**Open (D-35)** and are not part of this contract.

Reporting a **Review** is a different, authenticated endpoint (§3.5).

### 2.5 Static content

| Method | Path | Purpose | Capability |
| --- | --- | --- | --- |
| `GET` | `/pages/{slug}` | Policy and information pages | C-18 |

Serves About, how Listings are collected and verified, how ranking works,
review policy summary, advertising information, privacy notice, terms,
contact and corrections. A page whose content is **PENDING COUNSEL** is not
exposed as complete (C-18).

---

## 3. Customer endpoints

Authenticated-required unless stated. Always `no-store`.

### 3.1 Authentication

| Method | Path | Purpose | Capability |
| --- | --- | --- | --- |
| `POST` | `/auth/google` | Exchange a Google credential for a Bulbula session | C-30 |
| `POST` | `/auth/otp/request` | Request an email sign-in code | C-31 |
| `POST` | `/auth/otp/verify` | Verify the code, receive a session token | C-31 |
| `POST` | `/auth/telegram/context` | Validate Telegram Mini App context (surface only, **not a login**) | D-33 |
| `GET` | `/auth/session` | Current actor and capabilities | C-32 |
| `POST` | `/auth/logout` | Revoke the current session | C-32 |

| Rule | Statement |
| --- | --- |
| AU-1 | **Only Google and email OTP create sessions** (D-48). No password endpoint exists; no Apple endpoint exists |
| AU-2 | `/auth/otp/request` always returns the same response whether or not the address has an account (E-5) |
| AU-3 | `/auth/otp/verify` is single-use and rate-limited; failures are generic (TRD TR-163) |
| AU-4 | `/auth/telegram/context` validates the signed payload server-side and establishes the **surface**; it **MUST NOT** by itself authenticate a Customer (D-48, TRD TR-113) |
| AU-5 | How Telegram context relates to a Customer identity is **Open (D-33)**; this endpoint's contract does not presume an answer |
| AU-6 | `/auth/session` returns the actor type and the permitted actions so clients need not infer authorization (still enforced server-side, TRD TR-33) |

### 3.2 Profile

| Method | Path | Purpose | Capability |
| --- | --- | --- | --- |
| `GET` | `/me` | Display name, email address, sign-in methods, dates | C-33 |
| `PATCH` | `/me` | Update the display name | C-33 |

Returns only the minimal profile (PRD ACC-6). There are no avatars, no public
Customer profiles, no social graph.

### 3.3 Saves

| Method | Path | Purpose | Capability |
| --- | --- | --- | --- |
| `GET` | `/me/saves` | The Saved list | C-34 |
| `PUT` | `/me/saves/{business_slug}` | Save (idempotent) | C-14 |
| `DELETE` | `/me/saves/{business_slug}` | Remove (idempotent) | C-14 |

**The canonical term is Save.** No endpoint, field or code uses "favourite",
"like" or "bookmark" (`glossary.md` §3). A Saved Business that is later
unpublished is returned with its current status rather than disappearing
(C-34).

### 3.4 Reviews

| Method | Path | Purpose | Capability |
| --- | --- | --- | --- |
| `GET` | `/me/reviews` | Own Reviews with state (`pending`, `published`, `rejected`, `removed`) | C-35 |
| `GET` | `/branches/{id}/reviews` | Published Reviews for a Branch, newest first | C-13 |
| `POST` | `/branches/{id}/reviews` | Submit a Review **for a Branch** | C-13 |
| `PATCH` | `/me/reviews/{id}` | Edit own Review | C-35 |
| `DELETE` | `/me/reviews/{id}` | Delete own Review | C-35 |

| Rule | Statement |
| --- | --- |
| RV-1 | Only authenticated Customers may write (D-12) |
| RV-2 | **At most one active Review per Customer per Branch** (D-34); a second `POST` for the same Branch returns `409` pointing at the existing Review, which the Customer edits instead (TRD TR-160) |
| RV-2a | **The subject of a Review is always a Branch** (D-34, D-55). There is no endpoint that creates a Review against a Business |
| RV-3 | **Moderation precedes publication** (D-34). A successful `POST` returns the Review in state `pending`; clients **MUST NOT** assume immediate publication and **MUST** render the returned state (TRD TR-60) |
| RV-3a | A `PATCH` within the edit window returns the Review to `pending`; the response states the new state (D-34) |
| RV-4 | `rating` is a **required integer from 1 to 5**; a value outside that range is a `422`. `text` is **optional**, so a rating-only Review is valid (D-34) |
| RV-4a | The edit window is **30 days from creation**; a `PATCH` after it returns `422`. The window is enforced server-side and **MUST NOT** be hard-coded in clients (D-34) |
| RV-4b | `DELETE /me/reviews/{id}` is a **withdrawal**: public visibility ceases and the Review leaves every published list and every rating summary. It is **not** a destructive erase, and the API makes no promise about how long an internal record is kept — that remains **PENDING COUNSEL** (L-21, D-46) |
| RV-4c | A maximum text length, rate limits and anomaly thresholds are **server-side configuration**, not part of this contract, and no value is specified here (D-34) |
| RV-5 | **There is no reply endpoint, field or affordance** (D-12, D-54, TRD TR-67) |
| RV-6 | Review photos and helpful votes do not exist (D-36, D-37) |
| RV-7 | A Business profile **MAY** expose a rating summary, but it is **derived by aggregating the Reviews of that Business's Branches** (D-34, D-55). No Review is stored against a Business |
| RV-8 | **No Business-owner review endpoint exists**, and no Business-account endpoint exists, because no business account exists in V1 (D-12, D-54) |
| RV-9 | Published Reviews are returned **newest first** by default (D-34, SUM-9) |

### 3.5 Review reports

| Method | Path | Purpose | Capability |
| --- | --- | --- | --- |
| `POST` | `/reviews/{id}/reports` | Report a Review | C-15, C-25 |

**Authentication required** (`interaction-permissions.md` §3). Requires a
ground from the published list. A report alone never unpublishes a Review
(REP-3). Reporter identity is never exposed (TRD TR-65).

### 3.6 Privacy controls

| Method | Path | Purpose | Capability |
| --- | --- | --- | --- |
| `GET` | `/me/data` | Export the Customer's own data | C-36 |
| `POST` | `/me/deletion` | Request account deletion | C-36 |
| `DELETE` | `/me` | Confirm and execute deletion | C-36 |

Deletion is explicitly confirmed, audited, and acknowledged by email (C-36,
C-39). Response windows and the required export format are **PENDING
COUNSEL** (L-7). A published Review authored by the deleted Customer follows
the **withdrawal** model fixed by D-34 — it ceases to be publicly visible —
but **how long any internal record is retained remains PENDING COUNSEL
(L-21, D-46)** and this contract specifies no period.

---

## 4. Operations endpoints

`/api/v1/ops/*`. Staff session plus named permission (TRD TD-03). Always
`no-store`, never indexable, never referenced from public responses.

| Area | Method and path | Purpose | Capability |
| --- | --- | --- | --- |
| Listings | `GET /ops/businesses` · `POST /ops/businesses` · `GET|PATCH /ops/businesses/{id}` | Create and maintain Listings | C-19, C-20 |
| | `POST /ops/businesses/{id}/submit` | Submit for quality review | C-19 |
| | `POST /ops/businesses/{id}/publish` · `/unpublish` · `/close` | Publication lifecycle | C-21, TRD TR-53 |
| | `GET /ops/businesses/{id}/changes` | Correction history | C-20 |
| | `GET /ops/duplicates?name=&area=` | Duplicate candidates | TRD TR-55 |
| Branches | `POST /ops/businesses/{id}/branches` · `PATCH|DELETE /ops/branches/{id}` | Branch management | C-19, C-20 |
| Permission | `POST /ops/businesses/{id}/permission` · `POST /ops/permissions/{id}/withdraw` | Record and withdraw Permission | D-50, C-19 |
| Verification | `POST /ops/businesses/{id}/verifications` · `GET /ops/queues/reverification` | Record Verification; stale queue | C-21 |
| Media | `POST /ops/media` · `POST /ops/media/{id}/attach` · `DELETE /ops/media/{id}` | Upload, attach, remove | C-22 |
| Taxonomy | `POST|PATCH|DELETE /ops/categories` · `POST /ops/categories/{id}/merge` | Category management — **Administrator** | C-23 |
| Locations | `POST|PATCH|DELETE /ops/areas` | Area management — **Administrator** | C-24 |
| Moderation | `GET /ops/queues/reviews` · `POST /ops/reviews/{id}/moderate` | Review moderation with policy ground | C-25 |
| Reports | `GET /ops/queues/reports` · `POST /ops/reports/{id}/resolve` | Report triage and resolution | C-26 |
| Campaigns | `GET|POST /ops/campaigns` · `POST /ops/campaigns/{id}/approve` · `/suspend` · `/cancel` | Campaign lifecycle; **approve is Administrator-only** | C-27 |
| | `GET /ops/placements/{key}/availability` | Inventory check before creation | TRD TR-72 |
| Analytics | `GET /ops/analytics/coverage` · `/quality` · `/throughput` · `/content-signals` · `/campaigns` | Operational metrics from rollups | C-28 |
| Audit | `GET /ops/audit` | Audit log — **Administrator only** | C-29 |

### 4.1 Operations rules

| ID | Rule |
| --- | --- |
| O-1 | `POST /ops/businesses/{id}/publish` **MUST** fail with `422` when Permission or Verification is missing, naming which (TRD TR-49, TR-50) |
| O-2 | Every mutating `/ops/*` request **MUST** carry a reason where the action changes published data, and the reason is stored (TRD TR-52) |
| O-3 | Every mutating `/ops/*` request produces an audit entry; if the audit write fails, the action fails (TRD TR-08) |
| O-4 | `PATCH` on a Listing **MUST** carry the concurrency token; a stale token returns `409` (TRD TR-57) |
| O-5 | Campaign creation returns `409` when the Placement and period are sold out (TRD TR-72) |
| O-6 | Administrator-only endpoints check the named permission, never console membership (TRD TR-36) |
| O-7 | Operations endpoints **MUST NOT** be reachable with a Customer token; responses to unauthenticated callers do not distinguish existence (TRD TR-35) |
| O-8 | There is **no endpoint for a business to act on its own behalf**, in any form (D-54) |

---

## 5. Health

| Method | Path | Purpose |
| --- | --- | --- |
| `GET` | `/api/v1/health` | Liveness — no dependencies |
| `GET` | `/api/v1/health/ready` | Readiness — database reachable, storage writable |

Existing behaviour, preserved: `no-store`, `503` with per-check detail on
failure.

---

## 6. Contract rules that protect product decisions

| ID | Rule | Source |
| --- | --- | --- |
| CR-1 | No endpoint ranks, boosts or filters organic results by Campaign (TRD TR-41) | D-10 |
| CR-2 | Sponsored items are always a separate, labelled array (§2.2) | ADV-1, ADV-8 |
| CR-3 | No endpoint accepts a bid, budget, CPC, CPM or CPA value | D-10 |
| CR-4 | No endpoint authenticates or authorizes a Business | D-54 |
| CR-5 | No endpoint exposes provenance, Permission records, verification method or staff identity publicly | DO-4 |
| CR-6 | No endpoint returns an unbounded collection; the API is not a bulk-export surface | TRD TR-30 |
| CR-7 | No endpoint stores a Guest's precise location or any Guest identifier | TRD TR-201, TR-202 |
| CR-8 | No password, Apple, Facebook or SMS authentication endpoint exists | D-48 |
| CR-9 | Adding an endpoint that implies an excluded capability is a defect, not a feature | PRD §13 |

---

## 7. Open items

| ID | Question | Status |
| --- | --- | --- |
| TRD OT-08 | Offset versus keyset pagination for large lists | Open — technical decision |
| D-35 | Structured guest suggestions | Open — free-text reports only today |
| D-33 | Telegram identity relationship | Open — §3.1 AU-4/AU-5 |
| D-44 | Services/products/pricing representation | Open — profile field shape deferred |
| D-13 | Identity linking | Open — no auto-merge behaviour specified |
| D-04 | Opening-hours model | Open — `open_status` contract is stable regardless |

**D-34 closed at the M0 schema gate on 2026-10-07** and no longer appears
above. §3.4 now states the Branch subject, the 1–5 rating, optional text,
one active Review per Customer per Branch, the 30-day edit window,
pre-publication moderation and withdrawal semantics. Text length, rate
limits and anomaly thresholds remain **server configuration** rather than
contract, and review retention remains **PENDING COUNSEL** (L-21, D-46).
| L-7 | Rights-request response windows and export format | **PENDING COUNSEL** |
| — | Rate-limit values | Configuration; never published |

---

## Decision references

D-04, D-06, D-10, D-12, D-13, D-18, D-33, D-34, D-35, D-36, D-37, D-44,
D-46, D-48, D-50, D-54, D-55.
