# Search Design

| | |
| --- | --- |
| **Document** | Search and Discovery Design — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Scope.** How search works in V1: what is searchable, how text is prepared
and matched, how filters and distance apply, how results are ordered, how
Sponsored placements stay separate, and how zero results are handled.

**Authority.** This document owns search *mechanics*. Product behaviour
belongs to PRD §16; the API contract belongs to
[`api-spec-v1.0.md`](api-spec-v1.0.md) §2.2; the ordering *policy* belongs to
the owner.

**The hard constraint (TRD TD-04).** V1 search runs **inside MariaDB**. No
Elasticsearch, no OpenSearch, no Meilisearch, no Typesense, no Solr, no
Algolia, no vector database, no separate search process of any kind
(TRD NG-5, NG-6).

---

## 1. Requirements search must satisfy

| ID | Requirement | Source |
| --- | --- | --- |
| SR-1 | Find a Business by name, including partial and misremembered names | C-02 |
| SR-2 | Find Businesses by what they do, even when the User's word is not the catalogue word | C-02, Alias (D-06) |
| SR-3 | Work for English queries at launch and be ready for Amharic without redesign | D-18 |
| SR-4 | Suggest as the User types | C-03 |
| SR-5 | Combine query text with Category, Area, open-now, rating and verification filters | C-02, C-06 |
| SR-6 | Order by distance when the User shares location | C-07 |
| SR-7 | Be fast enough on shared hosting at launch volume | NFR, TRD TR-127 |
| SR-8 | Never let a paid placement influence organic order | D-10, TRD TR-41 |
| SR-9 | Return something useful when nothing matches | C-02, SRCH-8 |
| SR-10 | Be stable and deterministic across pages | PRD SRCH-6 |

---

## 2. Why MariaDB is sufficient for V1

| Consideration | V1 reality |
| --- | --- |
| Corpus size | One launch area; re-evaluation is triggered at **~50 000 listings `[P]`** (NFR) |
| Document size | A Listing is short: name, description, taxonomy labels, area labels, aliases |
| Query shape | Mostly short queries combined with a few structured filters — a job relational databases do well |
| Hosting | Shared hosting; a second daemon is not deployable (TRD TR-183) |
| Operational cost | A search cluster means another process to secure, monitor, back up and keep in sync. V1 cannot justify it |
| Correctness risk | A second store is a second source of truth and a new class of drift bugs |

**MariaDB provides** `FULLTEXT` indexing with natural-language and boolean
modes, prefix matching, ordinary B-tree indexes for filters, and
`utf8mb4` collation that handles Amharic text (TRD TR-165).

**This is a V1 decision, not a permanent one.** §12 describes the exit.

---

## 3. The search document (TD-04)

Search does **not** query the normalised entity tables directly. It queries a
maintained, denormalised **search document**: one row per published Business
(`data-model.md` §8.5).

### 3.1 Why

| Reason | Effect |
| --- | --- |
| Matching needs text from six tables (Business, Branch, Category, Subcategory, Alias, Area) | Composing at query time means joins that cannot be full-text indexed together |
| Full-text indexes work on columns, not on joins | The composed column is the index target |
| Filters need to sit beside the matched text | One row, one index access path |
| Ranking inputs change rarely and are read constantly | Precomputing is the right trade |

### 3.2 Contents

**Text part** — one or more indexed text columns, weighted by *which column*
rather than by an invented numeric weight:

| Column | Composed from |
| --- | --- |
| Name text | Business name, Amharic name, Branch labels |
| Classification text | Category and Subcategory names (both languages) and their Aliases |
| Location text | Area and Sub-city names (both languages), Area Aliases, Landmark |
| Description text | Short and long description |

Separating these allows a name match to be treated differently from a
description match **without asserting by how much** — the policy question
(§9) stays open while the mechanism exists.

**Filter and ordering part** — ordinary indexed columns: published flag,
primary category id, category ids, area id, sub-city id, verified flag,
verification date, rating average, rating count, completeness score,
has-confirmed-hours flag, latitude, longitude, created/updated timestamps.

### 3.3 Maintenance

| ID | Rule |
| --- | --- |
| SD-1 | The document is **derived**. The entity tables are the source of truth (TRD DO-7) |
| SD-2 | It is updated synchronously when a Listing is published, edited, unpublished or closed — a staff edit must be visible immediately (TRD TR-45) |
| SD-3 | Bulk taxonomy or area changes queue a rebuild of the affected subset (TRD TD-06) |
| SD-4 | `bin/console search:reindex` rebuilds everything from source and is safe to re-run (TRD TR-152) |
| SD-5 | A drift check compares document count and freshness against source and reports in operational diagnostics |
| SD-6 | **Losing the table loses nothing.** It is rebuilt from source, never restored as truth (TRD TR-212) |
| SD-7 | Only **published** Listings have a document. Unpublishing deletes the row |

---

## 4. Text normalisation

Applied identically to indexed content and to queries — asymmetry is the
classic cause of "I can see it but I can't find it".

| ID | Step | Notes |
| --- | --- | --- |
| N-1 | Unicode normalisation to a canonical form | Essential for Amharic, where visually identical text can differ in encoding |
| N-2 | Case folding | Latin script; Amharic has no case |
| N-3 | Trim and collapse whitespace | — |
| N-4 | Strip punctuation that is not meaningful (`&`, `.`, `'`, `-`) | "St. Mary's" matches "St Marys" |
| N-5 | Preserve digits and alphanumeric tokens | "Bole 24" |
| N-6 | **No stemming in V1** | English stemmers mangle Amharic and proper nouns; Amharic stemming is a research problem (§5.3) |
| N-7 | **No automatic transliteration between Latin and Ge'ez script in V1** | Guessing produces wrong matches; Aliases cover the real cases explicitly (§6) |
| N-8 | Normalisation is **one shared implementation** used by indexing, querying and suggestion | Divergence is a defect |

---

## 5. Tokenisation and the multilingual problem

### 5.1 English

Whitespace and punctuation tokenisation, which MariaDB's full-text index does
natively. Minimum token length and the stopword list are **configuration**,
not hard-coded, because defaults suited to prose damage short directory
names (TRD TR-196).

### 5.2 Amharic

| Fact | Consequence |
| --- | --- |
| Amharic uses Ge'ez script and **does** separate words with spaces in modern usage | Whitespace tokenisation is workable |
| Traditional punctuation (`፡`, `።`) may appear | Treated as separators in N-4 |
| Amharic is morphologically rich — affixes attach to roots | Exact-token matching under-matches |
| MariaDB's full-text tokeniser is not Amharic-aware | Behaviour must be verified, not assumed |

**V1 position.** Amharic content is **stored, indexed and matched** so a
User typing Amharic that matches stored Amharic finds the Listing. Bulbula
does **not** claim Amharic morphological search in V1, and **MUST NOT**
imply it in UI copy. Aliases carry the weight where exact matching falls
short (§6).

| ID | Rule |
| --- | --- |
| M-1 | All text columns are `utf8mb4` with a collation that handles Ge'ez correctly (TRD TR-165) |
| M-2 | Amharic name, Amharic Category name and Amharic Area name are indexed wherever stored (D-18) |
| M-3 | Mixed-script queries are handled without failing |
| M-4 | **No language detection, no auto-translation, no transliteration guessing** |
| M-5 | Amharic search quality is measured against real queries before any claim is made about it (SRCH-8) |

**This is "Amharic-ready", exactly as D-18 requires — not "Amharic-complete".**

### 5.3 What is deferred

Stemming, lemmatisation, phonetic matching, synonym expansion beyond curated
Aliases, and spelling correction. Each needs evidence from real query logs
that does not exist yet. Adding them speculatively would degrade precision
for guessed recall.

---

## 6. Aliases — the deliberate substitute for cleverness

Where an algorithm would have to guess, curated data states the answer
(D-06, `data-model.md` §3.5).

| Alias kind | Example of the problem it solves |
| --- | --- |
| Colloquial category term | A User's everyday word for a service differs from the catalogue label |
| Amharic ↔ English equivalence | The Amharic term for a Category, explicitly recorded rather than transliterated |
| Local area name | The name people actually use for a place versus its administrative name |
| Common misspelling | A frequent, verified misspelling of a Category or Area |
| Abbreviation | A widely used short form |

| ID | Rule |
| --- | --- |
| AL-1 | Aliases attach to Category, Subcategory or Area — **not** to individual Businesses (which would become a keyword-stuffing channel) |
| AL-2 | Aliases are staff-managed through taxonomy and location management (C-23, C-24) |
| AL-3 | Aliases are composed into the classification and location text (§3.2) — they are matching inputs, never display labels |
| AL-4 | **Aliases do not change ranking order**; they change whether something matches at all |
| AL-5 | Adding an Alias is a data operation with no deployment (TRD TR-217) |
| AL-6 | Zero-result queries are the primary source of new Aliases (§11) |

---

## 7. Matching

### 7.1 Strategy

Attempted in order; the first that produces results wins, and the response
records which applied so behaviour is observable.

| Stage | Behaviour |
| --- | --- |
| 1. Exact | The normalised query equals a normalised Business name or Area or Category label. Deserves the top position |
| 2. Full-text | All significant terms present. The main path |
| 3. Partial / prefix | For short queries and the last token being typed. Primary mechanism for autocomplete |
| 4. Relaxed | Any significant term present, used only when stricter stages returned nothing, and **clearly presented as broader** |

| ID | Rule |
| --- | --- |
| MT-1 | **Filters apply at every stage.** A relaxed match never escapes an explicit Category or Area filter |
| MT-2 | Unpublished, draft and removed Listings are never matched (SD-7) |
| MT-3 | Leading-wildcard matching is **not** used in V1 — it cannot use the index and degrades badly |
| MT-4 | Query length is bounded; an excessive query is rejected cleanly, never run (TRD TR-128) |
| MT-5 | Terms the index ignores (stopwords, sub-minimum length) **MUST NOT** silently produce an empty result — the system falls through to §11 |
| MT-6 | A filter-only request (no `q`) is a browse, not a search, and skips stages 1–4 entirely |

### 7.2 Why a staged approach rather than one scored query

A single scoring expression mixing text relevance, rating, distance and
freshness requires numeric weights **nobody has approved** and whose effects
cannot be predicted before launch (D-09). Staging is explainable, debuggable,
and leaves the weighting question genuinely open.

---

## 8. Filters, distance and open-now

### 8.1 Filters

| Filter | Mechanism |
| --- | --- |
| Category / Subcategory | Indexed id column on the search document |
| Area | Indexed id column; Sub-city available as a broader fallback |
| Category × Area | Composite index (C-06) |
| Verified | Indexed flag (C-12) |
| Minimum rating | Indexed rating average; Listings with no Reviews are excluded from a rating filter, never treated as zero (TRD TR-63) |
| Open now | §8.3 |

All filter combinations must be satisfiable by an index. A combination that
would force a full scan is a schema defect, not a tuning problem
(TRD TR-127).

### 8.2 Distance (C-07)

| ID | Rule |
| --- | --- |
| DS-1 | Location is used **only** when the User grants it (PRD LOC-2) |
| DS-2 | Coordinates are used in-request and **never stored, never logged, never sent to analytics** (TRD TR-201) |
| DS-3 | Candidates are narrowed by an indexed **bounding box** on latitude and longitude before any distance is computed |
| DS-4 | Exact distance is computed only on the bounded candidate set |
| DS-5 | A maximum radius and a maximum result count bound the operation (TRD TR-128) |
| DS-6 | Distances are presented as approximate; they are straight-line, not travel distance |
| DS-7 | Branches without coordinates are excluded from distance ordering but remain findable by every other means |
| DS-8 | Without location, nearby degrades to Area browsing — it never blocks and never nags (C-07) |

**No spatial extension, no PostGIS-equivalent, no geohash service.** A
bounding box plus a distance calculation on a small candidate set is
sufficient and uses ordinary indexes.

### 8.3 Open now (C-09)

| ID | Rule |
| --- | --- |
| ON-1 | Evaluated against the configured timezone (TRD TR-168) |
| ON-2 | **Unknown hours are excluded from the filter and displayed as `unknown`, never as `closed`** (TRD TR-56) |
| ON-3 | A `has_confirmed_hours` flag on the search document lets the filter avoid evaluating every row |
| ON-4 | The full evaluation depends on the hours model, which is **Open (D-04)**. The flag and the exclusion rule hold regardless |

---

## 9. Organic ranking

### 9.1 The inputs — and only the inputs

Order within a match stage is determined by these, and **nothing else**:

| Input | Why it is admissible | Source |
| --- | --- | --- |
| Text match quality | Relevance to what was asked | C-02 |
| Match location (name versus description) | A name match is a stronger signal | §3.2 |
| Verification state and recency | The trust layer the product promises | C-12, D-08 |
| Completeness | A complete Listing serves the User better | D-09 |
| Rating average and count together | Never average alone — one five-star Review is not better than forty four-star ones | D-34 |
| Distance | Only when the User asked for proximity | C-07 |
| Deterministic tiebreaker | Stable pagination | SRCH-6 |

### 9.2 Rules

| ID | Rule |
| --- | --- |
| OR-1 | **No weights are specified in this document.** Inventing them would decide D-09 and parts of D-34 by accident |
| OR-2 | Weights live in **configuration**, are reviewable, and are changed deliberately (TRD TR-196) |
| OR-3 | **No commercial input may enter the ordering function.** Not Campaign state, not spend, not impressions, not clicks (D-10, TRD TR-41) |
| OR-4 | Ranking inputs are explainable in plain language on a public page (C-18, PRD SEO/trust) |
| OR-5 | Ordering is **deterministic**: the same query with the same data yields the same order, every page (SRCH-6) |
| OR-6 | No personalisation, no behavioural profiling, no per-User ordering in V1 (PRD §13, PRIV) |
| OR-7 | No manual per-Listing boost field exists. A hidden lever would make OR-3 unverifiable |
| OR-8 | Ranking changes are recorded as decisions, not slipped in as tuning |

---

## 10. Sponsored separation

```text
┌──────────────── GET /search ────────────────┐
│                                              │
│  organic pipeline            sponsored lookup│
│  ─────────────────           ────────────────│
│  match → filter → rank       placement + target
│         │                     + active period │
│         ▼                            ▼        │
│      data[]                    sponsored[]    │
│         └──────── separate arrays ────┘       │
└──────────────────────────────────────────────┘
```

| ID | Rule |
| --- | --- |
| SP-1 | The organic pipeline **does not know Campaigns exist**. No join, no flag, no parameter (TRD TR-41) |
| SP-2 | Sponsored selection is a separate lookup by Placement, target and active period (TRD TR-71) |
| SP-3 | The two are returned as **separate arrays** and rendered in fixed, documented slots (API §2.2, ADV-8) |
| SP-4 | Every Sponsored item carries a label; an unlabelled one is a defect (ADV-1) |
| SP-5 | A Sponsored Business that also matches organically appears in both — **its organic position is unchanged** (ADV-8) |
| SP-6 | Sponsored slots are capped per Placement; unsold slots are **left empty**, never backfilled with a free promotion (ADV-3) |
| SP-7 | Sponsored measurement is counted separately and **never written back** into ranking inputs (TRD TR-74, MS-2) |
| SP-8 | Removing all advertising code **MUST** leave organic search byte-identical. That is the test of this boundary |

---

## 11. Zero results

Returning an empty page is a product failure, not a correct answer (C-02).

| Step | Behaviour |
| --- | --- |
| 1 | Re-run with the **relaxed** stage, clearly labelled as broader |
| 2 | Drop the most restrictive filter and say which was dropped — never silently |
| 3 | Offer the nearest alternatives: same Category wider Area, same Area related Category |
| 4 | Offer navigation: Category browse, Area browse, homepage discovery |
| 5 | Offer the correction channel — "tell us what's missing" (C-15) |

| ID | Rule |
| --- | --- |
| ZR-1 | Zero-result queries are recorded with their filters, **without any Guest identifier** (SRCH-8, TRD TR-202) |
| ZR-2 | They drive coverage work (which Businesses to add) and Alias work (which words to map) |
| ZR-3 | A zero result **MUST NOT** silently return an unrelated result set in place of nothing |
| ZR-4 | The response states plainly what was searched and what was relaxed |
| ZR-5 | **Sponsored placements MUST NOT be used to fill a zero-result page.** Nothing relevant means nothing relevant (SP-6) |

---

## 12. Future migration path — described, not designed

| ID | Statement |
| --- | --- |
| FM-1 | Search is reached through a **single application-level interface**. Controllers, views and the API never write search SQL (TRD TR-46, architecture §2) |
| FM-2 | The search document is already a **document**: swapping the store means changing what writes and reads it, not re-deriving what is searchable (§3) |
| FM-3 | `search:reindex` already rebuilds from source — the same command any external engine would need (SD-4) |
| FM-4 | Ranking inputs are named and configurable, so they transfer rather than being rediscovered (§9) |
| FM-5 | Sponsored separation means the hard commercial-integrity boundary is unaffected by any engine change (§10) |

**Triggers for re-evaluation** (NFR, all `[P]`): search p95 above 300 ms on
production hardware; the corpus approaching ~50 000 listings; a product
requirement MariaDB genuinely cannot serve (true linguistic analysis,
typo-tolerant fuzzy matching at scale, faceting across very large result
sets).

| ID | Rule |
| --- | --- |
| FM-6 | **No external engine is introduced in V1**, and none is designed here. Designing an unneeded migration is the waste this rule exists to prevent (TRD NG-5) |
| FM-7 | A future engine would be adopted by implementing the same interface behind a configuration switch, with both paths comparable |
| FM-8 | Any adoption is a **decision**, recorded in the register with its trigger evidence |

---

## 13. Performance expectations

| ID | Requirement |
| --- | --- |
| PF-1 | Every search query is served by an index. A full scan on the search document is a defect (TRD TR-127) |
| PF-2 | Result pages are bounded; `per_page` is clamped and deep offsets are capped (API §1.7) |
| PF-3 | Autocomplete returns a small bounded list and is latency-budgeted more tightly than full search |
| PF-4 | Autocomplete failure **MUST NOT** break plain search (C-03) |
| PF-5 | Common queries may be cached briefly with the full filter set in the key (TRD TR-122); **no cache service is required** (TD-05) |
| PF-6 | Search p95 is measured and reported, because it is a documented re-evaluation trigger (FM-5) |
| PF-7 | Search performs **no external network call** — it cannot be slowed or broken by a third party (TRD TR-205) |

Detail: [`performance-and-caching.md`](performance-and-caching.md).

---

## 14. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-04 | Opening-hours model | Open — §8.3 holds regardless |
| D-06 | Alias catalogue content | Managed data, not a code question |
| D-08 | Verification tiers and interval | Open — affects a ranking input |
| D-09 | Completeness definition and ranking weight | **Open — the central ranking question** |
| D-34 | Rating scale and summary computation | Open — affects a ranking input |
| D-40 | Launch-area boundary | Open — affects Area data |
| D-56 / D-57 | Category catalogue and cardinality | Open — affects classification text |
| — | Ranking weights | **Deliberately unspecified** (OR-1) |
| — | Amharic morphological matching | Deferred (§5.3) |
| — | External search engine | Deferred (§12) |

---

## Decision references

D-04, D-06, D-08, D-09, D-10, D-18, D-34, D-40, D-56, D-57.
