# Bulbula Product Glossary

| | |
| --- | --- |
| **Document** | Product Glossary |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | The provisional glossary in `docs/00-discovery/project-understanding-v0.1.md` §35 |

**Purpose.** One word per concept. Every product, design, technical and
operations document — and every user-visible string — uses the canonical term
below. Where a term has common synonyms, the synonyms are listed as
**deprecated** and must not appear in documentation, code identifiers, or UI
copy.

---

## 1. People and actors

| Term | Definition | Deprecated synonyms |
| --- | --- | --- |
| **User** | Any human interacting with a Bulbula client surface. Umbrella term covering Guest and Customer. Not a role | end user, visitor (as a role) |
| **Guest** | An unauthenticated User. May browse, search and read public content | anonymous user, public user |
| **Customer** | An authenticated end user with a Bulbula account. The term for the demand side of the product | member, consumer, registered user (as a role name) |
| **Operator** | A Bulbula staff member who performs day-to-day listing and moderation work in the operations console | agent, editor, content staff |
| **Administrator** | A Bulbula staff member with higher-risk permissions: taxonomy, campaigns, user management, policy and audit access | admin user, superuser, owner (never use "owner" for staff) |
| **Staff** | Collective term for Operator and Administrator | team, back office |
| **Business owner** | The person who owns or manages a Business in the real world. **Not a platform user in V1** — has no account, no login and no self-service surface (D-54) | merchant, vendor, partner, client (as a platform role) |

**Rule.** V1 has exactly **three platform actors**: Guest, Customer, Staff
(split into Operator and Administrator). Any requirement that implies a
fourth actor is out of scope (D-54).

---

## 2. Core product entities

| Term | Definition | Deprecated synonyms |
| --- | --- | --- |
| **Business** | A commercial or service entity that Bulbula describes. The brand-level record: name, description, categories, website, social links | company, merchant, shop (as the entity name), place |
| **Branch** | A physical location belonging to a Business. Every Business has at least one Branch; single-location businesses have exactly one (D-03) | location, outlet, store, site, venue |
| **Listing** | The published representation of a Business and its Branches on Bulbula. The unit of publication, provenance, verification and freshness. A Business that has never been published has no Listing | entry, record, page, profile (as the data unit) |
| **Business profile** | The public page that presents a Listing to Users. The *presentation* of a Listing, not the data unit | business page, detail page |
| **Category** | A top-level classification in the Bulbula-controlled taxonomy (D-06) | type, sector, industry |
| **Subcategory** | A second-level classification beneath a Category. The taxonomy is exactly two levels in V1 (D-06) | sub-type, niche, child category |
| **Alias** | A controlled synonym attached to a Category, Subcategory or Area, used to improve search matching. May contain Amharic (D-06, D-18) | synonym (acceptable in prose), keyword, tag |
| **Area** | The user-facing location unit — a named neighbourhood or locality such as Bole Bulbula. Areas are curated, not free text | neighbourhood (acceptable in prose), locality, zone, district |
| **Sub-city** | The administrative unit above Area in Addis Ababa (*kifle ketema*). Stored for correctness; secondary in the interface | district, borough |
| **Landmark** | An optional named reference point used to help Users locate a Branch | POI, reference point |

---

## 3. Customer interactions

| Term | Definition | Deprecated synonyms |
| --- | --- | --- |
| **Review** | A Customer's published evaluation of a Business, consisting of a Rating and optional review text | comment, feedback, testimonial, post |
| **Rating** | The numeric score a Customer assigns within a Review | score, stars (acceptable in UI copy only), grade |
| **Rating summary** | The aggregate rating displayed for a Business, computed from its Reviews | average rating, overall score |
| **Save** | **The canonical name for the single capability** by which a Customer marks a Business for later. One capability, one verb (D-01 capability 14) | **favorite, favourite, like, bookmark, wishlist, follow** — all deprecated |
| **Saved list** | The collection of Businesses a Customer has Saved | favorites list, my list |
| **Report** | A User-submitted notification that something is wrong — either a data problem on a Listing or abusive content in a Review | flag, complaint, abuse report |
| **Correction** | A requested or applied change to Listing data arising from a Report, a staff check or a business contact | edit request, update request, change request |
| **Contact action** | A User action that initiates real-world contact with a Business: call, website visit, directions, social link | conversion, lead (deprecated — implies attribution Bulbula does not measure) |
| **Share** | A User action that distributes a link to a Business profile | send, forward |

**Note on "Save".** The product has one capability, not three. "Like" implies
a public social signal and "Favorite" implies a separate list; neither is in
scope. Use **Save** / **Saved** everywhere, including UI labels.

---

## 4. Operations and data quality

| Term | Definition | Deprecated synonyms |
| --- | --- | --- |
| **Permission** | A Business's agreement that Bulbula may collect and publish its information (D-50) | consent (reserved for personal-data consent — do not use for businesses), approval, sign-off |
| **Permission record** | The internal record evidencing that Permission was obtained: role of the person who agreed, date, method, operator, scope | consent form, agreement |
| **Collection** | The act of gathering Business information directly from the Business (D-50) | data entry, intake, onboarding (deprecated — implies the business acts) |
| **Verification** | Internal confirmation by Staff that Listing facts are accurate, recorded with method, date and Operator (D-08) | validation, approval, certification |
| **Verified** | A Listing state indicating Verification is current according to policy | trusted, certified, confirmed |
| **Re-verification** | Repeating Verification after the defined interval, or after a Correction | refresh, re-check |
| **Provenance** | The recorded origin of Listing data: who collected it, from what source, when, and who verified it | audit (deprecated for this meaning), lineage, source tracking |
| **Freshness** | How recently a Listing was verified or confirmed unchanged | recency, up-to-dateness |
| **Stale listing** | A published Listing whose Verification has aged beyond policy | outdated listing, expired listing |
| **Completeness** | The proportion of the defined field set that a Listing has populated (D-09) | profile score, quality score (deprecated — quality is broader) |
| **Quality review** | The check performed by a second person before a Listing is published | QA, approval step |
| **Moderation** | The review of Customer-generated content (Reviews, Reports) against published policy | curation, censorship (never), policing |
| **Audit log** | The immutable record of privileged actions, with actor, action, target, timestamp and reason | activity log, history (acceptable in UI) |
| **Duplicate** | Two or more Listings describing the same Business or Branch | dupe, clone |
| **Pilot** | The 20-business operational pilot that produces Bulbula's operational benchmark before launch thresholds are set (D-30, D-31) | trial, beta (deprecated — "beta" implies a public release stage) |

---

## 5. Monetization

| Term | Definition | Deprecated synonyms |
| --- | --- | --- |
| **Sponsored placement** | A paid, clearly labelled position in which a Business appears outside organic ranking (D-10) | ad, advert, promoted listing (acceptable in prose), banner |
| **Placement** | A named, finite slot in the product where Sponsored placements may appear, with a defined position and maximum count | ad slot, inventory unit |
| **Package** | A sellable bundle of Placement + targeting + duration + fixed price (D-10) | plan, tier, product (ambiguous — avoid) |
| **Campaign** | A time-bounded instance of a Package purchased for a specific Business, created by Staff on the Business's behalf | ad buy, booking, order (reserved for the billing record) |
| **Sponsored label** | The visible marker that identifies a Sponsored placement to Users | ad badge, promoted tag |
| **Organic ranking** | The ordering of results computed from product signals only, never influenced by Campaigns (D-10) | natural results, unpaid results |
| **Inventory** | The total number of Placement slots available in a period | ad space, capacity |

---

## 6. Platform and surfaces

| Term | Definition | Deprecated synonyms |
| --- | --- | --- |
| **Client surface** | A first-class product surface through which Users reach Bulbula. V1 surfaces: **Web** and **Telegram Mini App** (D-15, D-49) | platform (ambiguous), channel, app (ambiguous) |
| **Web** | The mobile-first website, served at Bulbula's public domain | website (acceptable in prose), desktop site (never — it is mobile-first) |
| **Telegram Mini App** | The Bulbula client surface running inside Telegram's Mini App web runtime, on the same backend and domain model (D-49) | Telegram bot (incorrect), Telegram app, TMA (avoid in prose) |
| **Operations console** | The internal, staff-only Web surface used to run the Listing lifecycle (D-01 capabilities 19–29) | admin panel, dashboard, back office, CMS |
| **Runtime adaptation** | A surface-specific behaviour difference permitted by D-49, implemented behind the Telegram adapter | fork, variant, port |

---

## 7. Information classification

Defined by D-51. Classification is by whether information relates to an
identifiable natural person, **never** by where it appears.

| Term | Definition |
| --- | --- |
| **Business information** | Information describing a Business as an entity. Usually not personal data |
| **Personal information** | Information relating to an identified or identifiable natural person, wherever it appears — including on a Listing |
| **Operational information** | Records of Staff actions and data Provenance |
| **Customer information** | Data belonging to a registered Customer |
| **Personal contact point** | A Business contact detail that is also a natural person's personal contact detail, flagged so that rights requests can be executed precisely |

---

## 8. Status vocabulary

| Term | Applies to | Values |
| --- | --- | --- |
| **Document status** | Documentation (D-29) | Draft · Review · Approved · Superseded |
| **Decision status** | Decision register entries | Approved · Open · Pending external · Deferred · Closed · Superseded |
| **Decision class** | Open decisions | A (before PRD) · B (before UX/data model) · C (implementation/TRD) · D (future) |
| **Certainty tag** | Statements in product documents | [C] Confirmed · [SI] Strongly implied · [P] Proposed · [U] Unknown · [X] Conflict |

---

## Decision references

D-01, D-03, D-06, D-08, D-09, D-10, D-15, D-18, D-29, D-49, D-50, D-51, D-54.
