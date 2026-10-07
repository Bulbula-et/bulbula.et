# Platform SEO

| | |
| --- | --- |
| **Document** | Platform SEO — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** Discoverability behaviour for the **Web surface only**:
indexing rules, canonical URLs, metadata, structured data, sitemap and
the integrity constraints that override any ranking benefit.

> **SEO is a Web-surface capability (C-37). It does not exist on the
> Telegram Mini App.** No Mini App route is indexed, submitted,
> canonicalised or optimised for a search engine (TG-7, TR-117).

---

## 1. Position

| ID | Statement | Source |
| --- | --- | --- |
| PSE-1.1 | Search engines are a **primary discovery route** for a local directory | C-37 |
| PSE-1.2 | **Only the Web surface is indexable** | TG-7, TR-117 |
| PSE-1.3 | SEO is one of the two documented surface-capability exceptions; it is **machine-facing and reduces nothing a person can do** (AC-7) | SCC §4 |
| PSE-1.4 | **SEO never overrides integrity.** Where discoverability and honesty conflict, honesty wins, without exception | PA §16 |
| PSE-1.5 | SEO is a consequence of **publishing accurate, well-structured, genuinely useful pages** — not a layer applied on top of them | — |

---

## 2. Indexable and non-indexable

### 2.1 Indexable

| Route | Notes |
| --- | --- |
| `/` | Home |
| `/categories` | Category index |
| `/c/{category}` | Category |
| `/c/{category}/{sub}` | Sub-category |
| `/c/{category}/in/{area}` | Category within an Area — a primary local-intent destination |
| `/areas` | Area index |
| `/a/{area}` | Area |
| `/b/{business}` | **Business profile — the most important indexable page** |
| `/about`, `/privacy`, `/terms`, `/advertising`, `/corrections`, `/how-we-verify`, `/how-ranking-works`, `/review-policy`, `/contact` | Static and transparency pages |

### 2.2 Not indexable

| Route / surface | Reason |
| --- | --- |
| `/search` and every query-string result | Infinite, low-value, duplicative |
| `/nearby` | Location-dependent, not a stable document |
| `/signin` | No value; authentication entry |
| `/saved` | Personal |
| `/account`, `/account/reviews`, `/account/privacy` | Personal |
| `/ops/*` | Staff-facing; must not be discoverable at all |
| `/b/{business}/report`, `/b/{business}/review` | Forms, not documents |
| **Every Telegram Mini App route** | TG-7, TR-117 |

| ID | Rule |
| --- | --- |
| PSE-2.1 | **Non-indexable routes are excluded by response-level directive**, not merely omitted from the sitemap |
| PSE-2.2 | **Robots-file disallow is not used as the privacy mechanism** for personal or staff routes — a disallowed URL can still be listed. Exclusion is enforced at the response, and access is enforced by authorisation (PAU §9) |
| PSE-2.3 | **No personal route is ever indexable**, in any circumstance (TR-123) |
| PSE-2.4 | **No Operations route is indexable, linked publicly, or present in any sitemap** |
| PSE-2.5 | A new route is **non-indexable until deliberately made indexable** |
| PSE-2.6 | **Any route serving personalised content must be `no-store`**, which also makes it uncacheable by intermediaries (TR-120) |

---

## 3. Crawling

| ID | Rule | Source |
| --- | --- | --- |
| PSE-3.1 | Indexable pages are **fully rendered in the first HTML response** | PD-03, TR-107 |
| PSE-3.2 | **No indexable content requires JavaScript** to be present or readable | PWX-1 |
| PSE-3.3 | **Content is never injected after load for crawler benefit** | PSE-3.4 |
| PSE-3.4 | **No cloaking.** The crawler receives byte-for-byte the same content a person receives at the same URL — no special treatment, no alternative version, no keyword-enriched variant | PA-16.x |
| PSE-3.5 | **No user-agent branching that changes content.** Negotiation may vary language; it may never vary substance | — |
| PSE-3.6 | **Crawlers are not treated as authenticated** and never see personal or staff content |
| PSE-3.7 | Crawl budget is respected: stable URLs, correct status codes, no redirect chains, no soft-404s |
| PSE-3.8 | **Gone content returns gone, not a redirect to Home** (PEH §7) | PEH §7 |
| PSE-3.9 | Server errors return a server error status — **never a 200 with an apology** | PEH-2.2 |

---

## 4. Canonical URLs

| ID | Rule | Source |
| --- | --- | --- |
| PSE-4.1 | **Every indexable page declares exactly one canonical URL** | IAR §5 |
| PSE-4.2 | The canonical form follows the URL grammar in `information-architecture-v1.0.md` — lowercase, hyphenated, stable, human-readable | IAR §5 |
| PSE-4.3 | **A slug change preserves the old address by redirect.** Links shared by real people must not rot | IAR §5 |
| PSE-4.4 | **Canonical URLs carry no query parameters**, no session, no tracking and no personal identifier |
| PSE-4.5 | `?branch=` on a Business profile is a **view of one canonical profile**, not a separate indexable document |
| PSE-4.6 | A category-within-area page is canonical **in its own right** — it is the real local-intent destination, not a duplicate of either parent |
| PSE-4.7 | **The same business never has two indexable pages** |
| PSE-4.8 | **Telegram direct links are never canonical and never referenced as alternates** | TG-7 |
| PSE-4.9 | Trailing-slash, case and parameter-order variants resolve to one canonical form |

---

## 5. Metadata

| ID | Rule | Source |
| --- | --- | --- |
| PSE-5.1 | **Every indexable page has a unique, descriptive title and description**, generated from real data | — |
| PSE-5.2 | **Metadata is generated from the same data the page displays.** It never contains a claim the page does not support | PSE-1.4 |
| PSE-5.3 | **No keyword stuffing, no invented superlatives, no "best" or "top" claims** Bulbula cannot substantiate | PA §16 |
| PSE-5.4 | **No rating, count or verification status appears in metadata unless it is true at render time** | PSE-6.5 |
| PSE-5.5 | A profile with no reviews **must not imply it has any** | PSE-5.2 |
| PSE-5.6 | **Verified** in metadata means exactly what `how-we-verify` says — nothing more | C-12 |
| PSE-5.7 | One `h1` per page, honest heading hierarchy — the same structure screen readers rely on serves machines too | A11 §4 |
| PSE-5.8 | Language is declared; `Vary` includes the language header (PP §6) | — |
| PSE-5.9 | Social preview metadata is provided and is **subject to every rule above** |
| PSE-5.10 | **A Sponsored placement is never represented as an organic result in any metadata or preview** | LB-4 |

---

## 6. Structured data

| ID | Rule | Source |
| --- | --- | --- |
| PSE-6.1 | Structured data is provided for **Business profile**, **category** and **area** pages | C-37 |
| PSE-6.2 | It is emitted **server-side in the HTML response** | PSE-3.1 |
| PSE-6.3 | **It must describe exactly what the page shows** — no additional fields, no enriched values, no aspirational data | PSE-1.4 |
| PSE-6.4 | **No field is emitted for data Bulbula does not hold.** Absent is absent | — |
| PSE-6.5 | **Aggregate rating is emitted only where real reviews exist**, and the value and count must match the page exactly | C-13 |
| PSE-6.6 | **Review markup reflects only published reviews** under `review-policy.md`. Removed, pending or rejected reviews never appear | `review-policy.md` |
| PSE-6.7 | **Opening hours markup matches the displayed hours and their known-as-of date.** Stale hours shown as fact is a trust failure (UFL-B4) | C-09 |
| PSE-6.8 | **Verification status is never emitted as a third-party accreditation or award** | C-12 |
| PSE-6.9 | **Sponsored placements are never emitted as endorsements, awards or ratings** | LB-4 |
| PSE-6.10 | Structured data is **never used to obtain a rich result the page does not earn** | PSE-1.4 |
| PSE-6.11 | The exact vocabulary and entity types are **Open — technical decision**; the honesty rules above bind whatever is chosen |

---

## 7. Sitemap and robots

| ID | Rule |
| --- | --- |
| PSE-7.1 | A sitemap lists **only indexable canonical URLs** |
| PSE-7.2 | **No personal, staff, search, nearby, form or Mini App URL** ever appears in it |
| PSE-7.3 | Change frequency and last-modified reflect **real** change — never synthetic freshness |
| PSE-7.4 | An unpublished, suspended or removed listing is **removed from the sitemap** (`listing-operations.md`) |
| PSE-7.5 | The robots file points at the sitemap and disallows staff and personal paths **in addition to**, never instead of, response-level exclusion (PSE-2.2) |
| PSE-7.6 | Sitemap generation is **derived from publication state**, not hand-maintained |
| PSE-7.7 | Generation timing and segmentation are **Open — implementation detail** |

---

## 8. Integrity constraints

These **override** any ranking benefit. They are not negotiable.

| ID | Constraint | Source |
| --- | --- | --- |
| PSE-8.1 | **No doorway pages.** No page exists solely to capture a query | PA §16 |
| PSE-8.2 | **No programmatic generation of thin category×area pages** with no real businesses behind them | PSE-8.1 |
| PSE-8.3 | **An empty result page is honest about being empty** and offers onward routes; it is not padded to look populated | UFL §7 |
| PSE-8.4 | **No hidden text, no hidden links, no off-screen keyword blocks** | A11 §4 |
| PSE-8.5 | **No paid placement affects organic ranking or indexing.** Advertising and ranking are separate systems | D-05, `advertising-products.md` |
| PSE-8.6 | **A Sponsored placement is labelled on the page and never presented to a crawler as organic** | LB-4 |
| PSE-8.7 | **No link scheme, reciprocal arrangement or paid link acquisition** | — |
| PSE-8.8 | **No scraped or syndicated content** presented as Bulbula's own | — |
| PSE-8.9 | **No review is created, solicited under incentive, edited for SEO, or filtered for favourability** | `review-policy.md` |
| PSE-8.10 | **Business data is never embellished to improve a page's ranking** | PSE-5.2 |
| PSE-8.11 | **`how-ranking-works` must remain true.** If an SEO measure would make that page inaccurate, the measure is rejected | C-18 |
| PSE-8.12 | **A business owner cannot buy, request or influence organic ranking**; owners are not users and have no such channel (D-02, D-54) | D-02, D-54 |

---

## 9. What SEO must not do to the product

| ID | Rule |
| --- | --- |
| PSE-9.1 | **SEO must not change ranking logic.** Ranking serves the User (D-05) |
| PSE-9.2 | **SEO must not add a capability, page type or behaviour to the Mini App** (PSE-1.2) |
| PSE-9.3 | **SEO must not introduce a third-party script** (PD-14) |
| PSE-9.4 | **SEO must not degrade performance or accessibility.** The same HTML serves both (PP §2) |
| PSE-9.5 | **SEO must not put personal data in a URL, title, description or structured-data field** (TR-123) |
| PSE-9.6 | **SEO must not create a surface difference in what a person can do** (SUR-3) |
| PSE-9.7 | **SEO is never cited as a reason to weaken a review, verification or labelling rule** |

---

## 10. Verification

| ID | Check |
| --- | --- |
| PSE-10.1 | Every indexable page renders fully with JavaScript disabled |
| PSE-10.2 | Crawler and user receive identical content at the same URL |
| PSE-10.3 | Every indexable page has exactly one canonical URL |
| PSE-10.4 | No personal, staff, search, nearby or form route is indexable |
| PSE-10.5 | No Mini App route is indexable or present in any sitemap |
| PSE-10.6 | Every indexable page has a unique title and description derived from real data |
| PSE-10.7 | Structured data matches the rendered page field for field |
| PSE-10.8 | No aggregate rating is emitted where no reviews exist |
| PSE-10.9 | Hours markup matches displayed hours |
| PSE-10.10 | No Sponsored placement is represented as organic anywhere |
| PSE-10.11 | The sitemap contains only indexable canonical URLs |
| PSE-10.12 | An unpublished listing disappears from the sitemap |
| PSE-10.13 | A renamed slug still resolves via redirect |
| PSE-10.14 | Gone content returns gone, not a redirect to Home |
| PSE-10.15 | No page exists with no real businesses behind it |
| PSE-10.16 | `how-ranking-works` remains an accurate description of actual behaviour |

---

## 11. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-25 | Area taxonomy and boundaries — determines which area pages legitimately exist | **Owner decision, open** |
| D-21 | Maps vendor — may affect location markup | Owner decision |
| D-45 | Multi-language scope — determines language and alternate handling | **Owner decision, open** |
| D-20 | Image handling — affects preview imagery | Owner decision |
| — | Structured-data vocabulary and entity types | **Open — technical decision** |
| — | Sitemap generation timing and segmentation | **Open — implementation detail** |
| — | Which category×area combinations are published | **Open — platform decision**, bounded by PSE-8.2, pending D-25 |

---

## Decision references

D-02, D-05, D-20, D-21, D-25, D-45, D-54.
