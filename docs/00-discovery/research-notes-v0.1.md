# Bulbula Research Notes

| | |
| --- | --- |
| **Document** | Research Notes supporting the Product Understanding Report |
| **Version** | 0.1 |
| **Status** | **Approved — historical record (frozen)** |
| **Note** | Findings R-01…R-15. Later findings R-16…R-23 are in product-decision-brief-v0.2.md and R-24…R-26 in v0.3. |
| **Date** | 2026-10-07 |
| **Parent** | [project-understanding-v0.1.md](project-understanding-v0.1.md) |

## Purpose and reading rules

This file holds **external findings with sources**, kept separate from the
report so that fact and opinion never blur. Every finding follows the same
four-part structure:

```text
Finding            what the source says
Why it matters     why it is relevant to a product like Bulbula
Applies to Bulbula yes / partly / no — and the reason
Interpretation     the recommendation it leads to (ours, not the source's)
```

**Finding** is sourced. **Interpretation** is a proposal and carries no
authority until approved. Findings are referenced from the report as
`R-01` … `R-15`.

A caution on sources: local-SEO and marketing sources frequently present
survey data (e.g. Whitespark/BrightLocal practitioner surveys) as if it were
Google's own weighting. Only Google's own three-factor framing is treated as
fact below; the percentages are recorded as industry opinion.

---

## R-01 — Fake-review regulation is now enforced, and it targets suppression as much as fabrication

**Finding.** The US FTC's Trade Regulation Rule on the Use of Consumer Reviews
and Testimonials took effect on 21 October 2024. It prohibits creating,
buying, selling or disseminating fake reviews (including AI-generated ones);
incentives conditioned on a particular sentiment; undisclosed insider reviews
(officers, managers, employees, agents and their immediate relatives);
company-controlled review sites presented as independent; and the suppression
or selective display of negative reviews, including via legal threats or
intimidation. Criteria for removing reviews must be applied equally regardless
of sentiment. Civil penalties run to roughly $51,744 per violation. The rule
does **not** oblige platforms that merely host reviews to verify their
truthfulness. The FTC issued its first enforcement sweep — warning letters to
ten companies — on 22 December 2025.
Sources: [FTC final rule analysis (Alston)](https://www.alston.com/en/insights/publications/2024/10/ftc-issues-final-rule-on-fake-reviews-testimonials),
[first enforcement sweep (Crowell)](https://www.crowell.com/en/insights/client-alerts/keeping-it-real-ftc-targets-fake-reviews-in-first-consumer-review-rule),
[rule summary (Freshfields)](https://www.freshfields.com/en/our-thinking/blogs/a-fresh-take/ftc-announces-final-rule-on-deceptive-reviews-102jh9p).

**Why it matters.** It is the clearest published standard for review integrity
on a platform, and it codifies a rule that is easy to violate accidentally:
hiding or down-ranking negative reviews is as serious as inventing positive
ones.

**Applies to Bulbula.** Partly. Bulbula operates in Ethiopia and is not
subject to the FTC. But it is a review-hosting directory whose entire value
proposition is trust, and the hosting exemption only protects platforms that
*host*; Bulbula intends to *moderate and rank*.

**Interpretation.** Adopt the substance as internal policy (report §13.4):
sentiment-neutral, published moderation criteria; no deletion for negativity;
owners may reply and dispute but never delete; insider reviews prohibited or
disclosed; incentives, if ever used, disclosed and never sentiment-conditioned;
display the real rating distribution. Doing this from the start costs almost
nothing; retrofitting it after a credibility incident costs the business.

---

## R-02 — Verification is the gatekeeper of listing control, and the method mix is well established

**Finding.** Mature platforms gate profile control behind verification and use
a small, consistent set of methods: phone call or SMS code (fastest), email,
video recording or live video call, and postcard to the physical address
(5–14 days, used as a fallback). Claiming an existing listing is a distinct
flow from creating one, and platforms routinely hold listings that the owner
never created.
Sources: [Moz — claiming local listings](https://moz.com/learn/seo/claiming-local-listings),
[claiming guide across platforms](https://www.flento.io/blog/claim-business-listings),
[listing management guide](https://synup.com/en/learn/claim-and-verify-local-listings).

**Why it matters.** It confirms the brief's split between *create* and *claim*
as the industry norm, and shows which verification methods are practical.

**Applies to Bulbula.** Yes, with one local adaptation: postcard verification
depends on a postal-address system that Addis Ababa does not practically have.

**Interpretation.** Phone/SMS as the baseline method; evidence upload
(trade licence, shopfront photo) as the second tier; **in-person verification
as a deliberate advantage** — a single-neighbourhood launch makes physically
walking to a business entirely feasible, which no national competitor can
match. Verification tiers rather than a boolean (report §13.3, PR-6).

---

## R-03 — MariaDB full-text search is usable but has specific, known limits

**Finding.** InnoDB and MyISAM/Aria support `FULLTEXT` indexes on `CHAR`,
`VARCHAR` and `TEXT` columns with natural-language and boolean modes, and
MariaDB computes a relevance score usable in `ORDER BY`. Limits: tokens
shorter than `innodb_ft_min_token_size` (default 3 for InnoDB, 4 for MyISAM)
are not indexed; stopword lists apply; partial words are excluded; MyISAM
additionally drops terms appearing in more than 50 % of rows (not in boolean
mode); all columns in a `MATCH()` must belong to the same index; and the
engine offers no fuzzy matching, stemming or synonym handling. Full-text
search is also ineffective on very small tables. For fuzzy, multilingual or
large-scale search, the documented answer is a dedicated engine.
Sources: [MariaDB full-text index overview](https://mariadb.com/docs/server/ha-and-performance/optimization-and-tuning/optimization-and-indexes/full-text-indexes/full-text-index-overview),
[common pitfalls](https://runebook.dev/en/docs/mariadb/full-text-indexes/index),
[MySQL FULLTEXT limitations](https://oneuptime.com/blog/post/2026-03-31-mysql-what-is-a-mysql-fulltext-index/view).

**Why it matters.** Search is the core verb of Bulbula, and the infrastructure
constraints rule out Elasticsearch, OpenSearch and Meilisearch.

**Applies to Bulbula.** Yes, directly — and the limits bite harder in a
bilingual market with short words and transliterations.

**Interpretation.** Build a composed **search document** per business
(names, aliases, transliterations, category and service terms, area names)
and index that single column; add an application-level **synonym/alias table**
to substitute for the missing analyser; serve autocomplete from a small
prefix-indexed suggestions table rather than the full-text index; do not
assume server variables can be tuned on shared hosting. Record explicit
triggers for revisiting a dedicated engine (report §10.4, §22.4, PR-4).

---

## R-04 — Paid results must be unmistakably distinguishable from organic ones

**Finding.** FTC staff guidance to search engines — reissued in 2013 to
general-purpose engines *and to seventeen specialised engines including local
business search* — states that including or ranking a result based on payment
is advertising, and that failing to "clearly and prominently" distinguish it
from natural results may be a deceptive practice under Section 5. Recommended
techniques: prominent shading and/or a clear border, plus a text label that
(1) explicitly and unambiguously says the result is advertising, (2) is large
enough to notice, and (3) sits immediately before the ad or at the top-left of
an ad block — with the same label used for all advertising types. A cited
survey found nearly half of searchers did not recognise top ads as distinct
from natural results.
Sources: [FTC press release](https://www.ftc.gov/news-events/news/press-releases/2013/06/ftc-consumer-protection-staff-updates-agencys-guidance-search-engine-industry-need-distinguish),
[analysis of the guidance](https://www.lexology.com/library/detail.aspx?g=32413b73-e385-4831-b7fe-9ffbc9571be0),
[Manatt summary](https://www.manatt.com/insights/newsletters/advertising-law/will-advertisers-feel-the-backlash-after-revel-(1)).

**Why it matters.** It converts the brief's "sponsored content should always
be clearly labelled" from a principle into concrete, testable UI rules — and
it was written for exactly this product category.

**Applies to Bulbula.** Yes as a design standard, even though the FTC has no
jurisdiction here.

**Interpretation.** One label word, chosen once (D-10) and used identically
across web, Flutter and Telegram; label placed before the item or at the
top-left of the sponsored block; visual separation in addition to the label;
fixed, capped sponsored positions per surface; plus a public "how ranking
works" page. The label must not be rendered in the brand orange, or it will
read as emphasis rather than disclosure (report §10.6, §19.2, PR-8).

---

## R-05 — Ethiopia has a GDPR-shaped data protection law, including data residency

**Finding.** Proclamation No. 1321/2024 (in force since April 2024) applies to
controllers and processors handling the personal data of people in Ethiopia.
It requires a lawful basis (consent, contract, legal obligation, vital
interests, public interest, legitimate interests); consent that is freely
given, specific, informed, unambiguous, unbundled from other terms and
withdrawable at any time, with the burden of proof on the controller; data
subject rights of information, access, rectification, erasure, restriction,
objection and portability (portability free of charge); registration of
controllers and processors with the Ethiopian Communications Authority;
appointment of a Data Protection Officer; breach notification to the ECA and
affected subjects within 72 hours; heightened protection for minors,
including a prohibition on processing minors' data for marketing or
profiling; and — Article 22 — **data sovereignty: personal data collected
locally must be stored on a server or data centre located in Ethiopia**, with
cross-border transfer permitted only under stated conditions (adequacy proof,
explicit informed consent, necessity, or public registers). Fines are capped
at a GDPR-like percentage of turnover, with criminal penalties for some
violations.
Sources: [full text of the Proclamation](https://www.metaappz.com/References/ethiopian_laws/federal/pr_1321_2024/en/txt),
[CIPIT analysis](https://cipit.strathmore.edu/ethiopias-personal-data-protection-proclamation-of-2024-and-its-budding-digital-identity-regime/),
[overview vs GDPR (DataGuidance)](https://www.dataguidance.com/opinion/ethiopia-general-overview-ethiopias-first-personal-data-protection-proclamation-of-2024),
[consent/cookies guide](https://kukie.io/blog/cookie-consent-ethiopia-data-protection).

**Why it matters.** Bulbula will hold accounts, reviews, favourites, contact
details, uploaded verification documents and behavioural analytics — all
personal data. Two provisions have direct architectural consequences: data
residency, and the 72-hour breach notification duty.

**Applies to Bulbula.** Yes, fully — it is an Ethiopian product serving
Ethiopian users.

**Interpretation.** Privacy notice, lawful-basis mapping per purpose,
unbundled consent, and operational paths for access/erasure are **V1
requirements**, not later polish (report §24.3). Contextual-only ad targeting
avoids profiling obligations (report §12.5). The residency rule is in direct
tension with Cloudflare R2 and foreign hosting — recorded as conflict **X-01**
and escalated to legal advice (D-22), not resolved by engineering. Minors'
data rules argue for a minimum account age and against any profiling.

---

## R-06 — The Ethiopian digital market: large, shallow, mobile, and unusually Telegram-centric

**Finding.** Government figures put mobile subscriptions at ~97 million with
~57 million internet users and 4G in 1,030 towns. GSMA's *State of Mobile
Internet Connectivity 2026* reports urban Ethiopian adults at 95 % phone
ownership but only 61 % owning an internet-enabled phone, 48 % using mobile
internet and 29 % using it daily (rural: 72 % / 30 % / 19 % / 7 %); Ethiopia
has the world's seventh-largest mobile-internet usage gap. Among users,
social media (89 %) and instant messaging (74 %) dominate, and GSMA's own
developer guidance is that products integrating with messaging channels such
as Telegram have a shorter path to adoption than standalone apps. Ethiopia is
the continental exception to WhatsApp dominance: Telegram and Facebook are
unusually prominent, historically attributed to Telegram's smaller app and
lighter data use, large group sizes, and its role as a business/marketing
channel. Smartphone penetration remains low (~15 % of population) with a wide
gender gap.
Sources: [GSMA 2026 data analysis](https://www.blackpixel.et/ethiopias-mobile-internet-gap-what-the-gsma-2026-data-means-for-developers-business-and-government/),
[messaging penetration by country](https://www.askyazi.com/articles/whatsapp-penetration-across-africa-statistics-by-country),
[why Ethiopia prefers Telegram](https://qz.com/africa/1214381/in-a-continent-dominated-by-whatsapp-ethiopia-says-yes-to-telegram),
[mobile gender gap](https://birrmetrics.com/ethiopia-narrows-mobile-gender-gap-to-24-but-smartphone-access-for-women-remains-just-6/),
[PM Office figures](https://x.com/PMEthiopia/status/2002438296438259872).

**Why it matters.** It sets the device, bandwidth and channel assumptions for
every client decision, and it independently validates the Telegram Mini App
as more than a novelty.

**Applies to Bulbula.** Yes — though note all figures are national; Bole
Bulbula is urban Addis Ababa and will sit at the favourable end of every
distribution.

**Interpretation.** Design for mid-range Android on metered data: strict page
budgets, server-rendered HTML, aggressive image optimisation, no heavy
frameworks on public pages (report §22.1, PR-11). Elevate the Telegram Mini
App from "third client" to a primary acquisition channel, and make "share to
Telegram" a first-class feature of the web product (report §18.3, PR-12).

---

## R-07 — The Ethiopian directory landscape is crowded with low-quality national directories

**Finding.** Dozens of Ethiopian business directories exist — EthioYP,
Ethiopian Yellow Pages, AddisBiz, 2merkato, ezega Business Guide, EthioVisit,
Qefira and many more; one roundup lists 80+ such sites, another lists 13
"free" ones with their domain authority. They are overwhelmingly national,
category-and-city organised, built on scraped or self-submitted data, and
show little evidence of verification, freshness or neighbourhood granularity.
Sources: [80+ Ethiopian directory list](https://www.sfconsultingbd.com/blog/business-directory-and-listing-in-ethiopia),
[13 free Ethiopian directories with DA/DR](https://www.nichemarket.co.za/blog/nichemarket-advice/list-ethiopia-business-directory),
[directory roundup 2025](https://aamax.co/blog/top-business-directories-and-listing-sites-in-ethiopia),
[EthioVisit Addis Ababa directory](https://www.ethiovisit.com/directory/addis-ababa/).

**Why it matters.** Two opposite lessons. The quality bar to beat is low — but
many have tried the generic national directory and none became the default,
which suggests the generic model itself is the failure mode.

**Applies to Bulbula.** Yes, as competitive context. (No structured
competitive analysis has been done; this is a landscape scan, not a
competitor study.)

**Interpretation.** Bulbula's defensible difference is **depth in one
neighbourhood** — verified, complete, current, photographed listings with
working phone numbers and real hours — not breadth. That reinforces the
hyper-local strategy and makes verification and freshness the product, not
features of it. It also argues for measuring launch readiness in *coverage of
Bole Bulbula*, not in total listings.

---

## R-08 — Ethiopian payments are mobile-money-first and gateway integration has real friction

**Finding.** Telebirr (Ethio Telecom, launched 2021) has tens of millions of
users and is the dominant mobile money rail, alongside M-Pesa Ethiopia and
bank wallets (CBE Birr, Amole). Licensed payment gateways have multiplied —
Chapa, ArifPay, SantimPay, YenePay, AddisPay — with Chapa the most commonly
cited as developer-friendly. In practice, integration is reported to be
difficult: bank APIs are complex, telebirr onboarding is slow, gateway access
typically requires a business licence and a physical office, and a large share
of commerce remains cash.
Sources: [Ethiopia's payment gateway space](https://sigma.world/news/is-there-uncertainty-behind-ethiopias-payment-gateway-space/),
[fintech landscape](https://ibsintelligence.com/ibsi-news/5-fintech-companies-in-ethiopia-diversifying-africas-digital-finance/),
[practical constraints overview](https://bossbot.uk/blog/wati-alternativa-et).

**Why it matters.** Advertising revenue requires collecting money. If the
billing design assumes an online gateway, revenue is blocked on a licensing
and integration process the project may not have completed.

**Applies to Bulbula.** Yes, directly to the advertising and billing
subsystems.

**Interpretation.** Design billing as **orders → invoices → recorded
payments → entitlements**, with payment captured out-of-band (telebirr, bank
transfer, cash) and confirmed by an administrator; treat a gateway
integration as an optional later adapter behind the same interface
(report §8.4, PR-9). This also fits the fixed-package model, which needs no
real-time payment authorisation.

---

## R-09 — Bilingual search (Amharic + Latin transliteration) is a design problem, not a translation task

**Finding.** This finding is **partly inferred**, and is flagged as such. What
is sourced: MariaDB's tokeniser is whitespace/punctuation based with no
stemming or synonym support and a configurable minimum token length (R-03);
Ethiopia's online population uses both Amharic (Ge'ez script) and English.
What is inferred from general knowledge rather than a cited study: Ethiopian
users routinely type Amharic business names in Latin transliteration with
inconsistent spellings, and Amharic morphology is not served by any built-in
MariaDB analyser.

**Why it matters.** If a customer types `bet`, `bét` or `ቤት` and gets nothing,
search is broken for a large fraction of real queries — and the failure is
invisible unless zero-result queries are logged.

**Applies to Bulbula.** Yes, assuming any Amharic support. If the product is
English-only (D-18), much of this disappears — which is precisely why the
language decision must precede the search design.

**Interpretation.** Treat aliases and transliterations as **data, not code**:
an editable alias table per business and per category, folded into the
composed search document; log every zero-result query and review it as an
operational routine; validate this assumption with real query logs before
investing further. Marked as needing validation, not treated as established
fact.

---

## R-10 — Telegram Mini App authentication is a small, well-specified server-side check

**Finding.** A Mini App receives `initData` in the `tgWebAppData` launch
parameter. Validation with the bot token: take all key/value pairs except
`hash`, format as `key=value`, sort alphabetically, join with `\n`; compute
`HMAC-SHA256` of the bot token using the literal key `WebAppData`; use that
digest as the key to `HMAC-SHA256` the joined string; compare hex output with
the supplied `hash`. An alternative flow verifies an Ed25519 `signature`
against Telegram's public key. Documented recommendations: always validate
server-side, enforce an `auth_date` expiry (≈1 day) against replay, use
HTTPS, never trust `initDataUnsafe`, never log raw init data (it contains
PII), and transmit it as sent (commonly in an `Authorization: tma <initData>`
header).
Sources: [Telegram Mini Apps — Init Data](https://docs.telegram-mini-apps.com/platform/init-data),
[initialization data reference](https://deepwiki.com/telegram-mini-apps-dev/telegram-apps/7.2-initialization-data).

**Why it matters.** It determines whether the Mini App can share Bulbula's
identity model, and it is a security-critical code path where a wrong
comparison (non-constant-time, or trusting `initDataUnsafe`) is a complete
authentication bypass.

**Applies to Bulbula.** Yes, when the Mini App is built.

**Interpretation.** Implement as a dependency-free adapter using
`hash_hmac()` and `hash_equals()` — comfortably inside the framework-free
rule — producing a Bulbula session/token from a verified Telegram identity.
Account linking (one user, multiple identities) must be designed before
launch to avoid duplicate accounts (D-13). Never log raw `initData`
(report §18.2, §24.2).

---

## R-11 — WCAG 2.2 AA adds criteria that are cheap at design time and expensive later

**Finding.** WCAG 2.2 (W3C Recommendation, October 2023; ISO/IEC 40500:2025)
adds nine success criteria and removes 4.1.1 Parsing. At Level A: 3.2.6
Consistent Help, 3.3.7 Redundant Entry. At Level AA: 2.4.11 Focus Not
Obscured (Minimum), 2.5.7 Dragging Movements (a single-pointer alternative for
anything draggable), 2.5.8 Target Size (Minimum) — 24×24 CSS px or equivalent
spacing — and 3.3.8 Accessible Authentication (Minimum) — no cognitive-
function test such as puzzles or transcription, and paste must not be blocked.
2.4.13 Focus Appearance (indicator at least a 2 px perimeter with 3:1
contrast) is AAA. Sticky headers, cookie banners and chat widgets are the
common cause of focus-obscured failures.
Sources: [WCAG 2.2 vs 2.1 comparison](https://web-accessibility-checker.com/en/blog/wcag-2-2-vs-2-1-differences),
[the nine criteria explained](https://www.complymo.com/blog/wcag-2-2-new-success-criteria),
[implementation guide](https://testparty.ai/blog/wcag-22-new-success-criteria).

**Why it matters.** The brief requires accessibility but names no standard. A
named standard is testable; "accessible" is not.

**Applies to Bulbula.** Yes. Several criteria map directly onto the planned
UI: filter chips and icon buttons (target size), a sticky search header (focus
not obscured), map interactions (dragging alternatives), and login
(accessible authentication).

**Interpretation.** Adopt **WCAG 2.2 AA** as the explicit target, encode the
mechanical parts in the design system (minimum 24 px targets with spacing
rules, a visible focus token, no drag-only interactions, paste-friendly
auth), and keep a short manual checklist for the rest
(report §19.2, PR-14).

---

## R-12 — Cloudflare R2 makes a media-heavy directory affordable, chiefly through zero egress

**Finding.** R2 Standard: $0.015/GB-month storage, Class A (write/list)
$4.50/million, Class B (read) $0.36/million, **$0.00/GB egress at any
volume**, no minimum storage duration, with a monthly free tier of 10 GB
storage + 1 M Class A + 10 M Class B operations. Infrequent Access is
$0.01/GB-month with higher operation rates, a $0.01/GB retrieval fee and a
30-day minimum. S3 comparison: ~$0.023/GB-month storage and ~$0.09/GB egress.
A custom domain can be attached to a bucket so objects are served from
Cloudflare's edge.
Sources: [R2 pricing 2026](https://egresscost.com/cloudflare/),
[R2 pricing breakdown](https://www.bucketmate.app/blogs/cloudflare-r2-pricing-2026),
[R2 for image hosting](https://mecanik.dev/en/posts/cloudflare-r2-for-image-hosting-zero-egress-image-cdn/).

**Why it matters.** Photos dominate both the perceived quality and the
bandwidth of a directory. Zero egress removes the variable that would
otherwise make a photo-rich product unaffordable on a cheap host — and it
keeps media traffic off the shared hosting account entirely.

**Applies to Bulbula.** Yes, subject to the data-residency question (X-01).
Business photographs are unlikely to be personal data; user avatars and
uploaded verification documents may be.

**Interpretation.** Store originals plus a small fixed set of derivatives in
R2; serve from a CDN subdomain; keep only keys and metadata in MariaDB; never
serve user media from the application host and never place it in the
repository tree (which the deployment resets). Classify data before deciding
what may leave Ethiopia (report §20.3, §24.3).

---

## R-13 — Core Web Vitals thresholds are stable, and mobile LCP is the hard one

**Finding.** The "good" thresholds, measured at the 75th percentile of real
user data: **LCP ≤ 2.5 s**, **INP ≤ 200 ms** (INP replaced FID on 12 March
2024), **CLS ≤ 0.1**; "poor" begins at 4.0 s, 500 ms and 0.25. A page passes
only if all three are good. Field data cited for 2025–26 shows roughly 48 % of
mobile sites passing all three, with LCP the most commonly failed (≈62 % of
mobile sites good). Common causes: unoptimised hero images and slow TTFB
(LCP), long main-thread JavaScript tasks and heavy third-party scripts (INP),
images and embeds without reserved dimensions (CLS).
Sources: [thresholds and field data](https://yassersoliman.com/blog/core-web-vitals-explained-marketers/),
[2026 metric guide](https://nitropack.io/blog/most-important-core-web-vitals-metrics/),
[thresholds table](https://www.sonarops.it/blog/core-web-vitals-what-they-are-how-to-improve-them-2026).

**Why it matters.** It converts "fast" into measurable acceptance criteria,
and it identifies the two biggest risks for Bulbula specifically: the Google
Maps embed (a heavy third-party script, bad for INP) and the business photo
gallery (bad for LCP and CLS if unsized).

**Applies to Bulbula.** Yes, for every public page.

**Interpretation.** Adopt the three thresholds as product requirements, add a
page-weight budget, defer the map embed until interaction behind a static
placeholder, give every image explicit dimensions and lazy-loading, subset and
preload fonts (note: Ethiopic font files are not small), and keep public-page
JavaScript minimal (report §22.1).

---

## R-14 — Google's local ranking is three factors, and being closed hurts

**Finding.** Google's own published framework for local results is
**relevance, distance and prominence** (sometimes called popularity):
relevance is how well a profile matches the query, driven by categories,
services, description and completeness; distance is proximity to the searcher
or the named place; prominence reflects how well known the business is,
informed by reviews, links and mentions. Everything beyond this is
practitioner survey data, not Google statement — commonly cited figures put
Business Profile signals at ~32 % of local-pack weight, reviews ~20 % and
on-page ~15 %, with primary category, proximity and business-title keywords as
the top individual factors. One cited study found rankings dropped
consistently when a business was listed as closed at the time of search, and
review recency has risen sharply in practitioner rankings.
Sources: [how local ranking works, with the Google framing](https://growwithba.com/blog/local-search-ranking-factors-2026),
[relevance/distance/prominence explained](https://www.earlyseo.com/blogs/google-business-profile-relevance-distance-prominence),
[practitioner weightings](https://searchcounselco.com/google-business-profile-ranking-factors/).

**Why it matters.** Two distinct uses: (a) it is the mental model business
owners already have, so Bulbula's own ranking explanation should speak the
same language; (b) it is a sanity check on the brief's ranking factor list,
which matches it closely.

**Applies to Bulbula.** Yes, as a model to borrow — not as an algorithm to
reverse-engineer. Treat the percentages as opinion.

**Interpretation.** Group Bulbula's confirmed ranking factors under the same
three headings (relevance: text, category, services, completeness; distance:
proximity; prominence: ratings, review quality and recency, popularity,
engagement, verification), with open-now as a modifier. Publish the
explanation. Make hours accuracy a priority, since "closed" demonstrably
costs visibility and the same logic should apply inside Bulbula
(report §10.3, §23.2).

---

## R-15 — As a directory, Bulbula may legitimately show review stars in Google results

**Finding.** Since September 2019 Google does not display review rich results
for `LocalBusiness` and `Organization` schema types (and subtypes) when the
reviewed entity controls the reviews — "self-serving" reviews, including those
injected via third-party widgets. The restriction is ineligibility, not a
penalty. Critically, the rule targets *entity A reviewing entity A*: sites
that publish reviews **about other businesses** — directories, comparison
sites, review platforms — remain eligible for review rich results with
`LocalBusiness`/`Organization` markup. Misleading or fabricated markup can
still trigger manual action.
Sources: [Google Search Central — making review rich results more helpful](https://developers.google.com/search/blog/2019/09/making-review-rich-results-more-helpful),
[what the self-serving rule bans and does not ban](https://jsonschemaapp.com/blog/google-self-serving-reviews-rule/),
[BrightLocal explainer](https://www.brightlocal.com/learn/review-schema/).

**Why it matters.** This is a structural SEO advantage that is easy to miss: a
business's own website cannot get star ratings in search results for its own
reviews, but Bulbula's page about that business can.

**Applies to Bulbula.** Yes — provided reviews are genuine, from real
customers, and not controlled by the business. That condition makes review
integrity (R-01) an SEO asset as well as a trust asset.

**Interpretation.** Implement `LocalBusiness` + `AggregateRating`/`Review`
structured data on business profile pages, only where real reviews exist,
with accurate counts. Never mark up aggregate ratings on pages without
reviews. Combined with R-01, this means review authenticity is simultaneously
a legal-ethical position, a trust differentiator and a ranking advantage —
the strongest argument in this research for investing in review integrity
early (report §23.2, §23.3).

---

## Research gaps

Named so they are not mistaken for completed work. None of these was
researched in this phase.

| Gap | Why it matters | Suggested method |
| --- | --- | --- |
| Primary user research (customers and business owners in Bole Bulbula) | Every persona and journey in the report is a hypothesis | 10 + 10 interviews; half a day each |
| Census of businesses in Bole Bulbula | Determines V1 feasibility and launch criteria | Physical survey + existing directory scraping comparison |
| Structured competitive analysis of the named Ethiopian directories | Positioning and feature baseline | Hands-on evaluation of 5 sites against a fixed rubric |
| Ethiopian advertising/consumer-protection law as it applies to sponsored content labelling | Currently extrapolated from FTC guidance | Local legal counsel |
| Whether Ethiopian law or the ECA requires registration for a platform of this size | Compliance load, DPO appointment | Local legal counsel (D-22) |
| Telegram Mini App UX conventions and real performance limits on low-end Android | Mini App design quality | Hands-on prototyping |
| Amharic search behaviour and transliteration patterns (R-09 is partly inferred) | Search design correctness | Query-log analysis after launch; small user test before |
| Design-system research against high-quality products (search UX, cards, profile layouts, filter UX) | Required by the brief for the UX phase | Deferred to the UX phase, deliberately |
| Shared-host resource limits and MariaDB tunability | Media pipeline and job design | Ask the hosting provider (D-20) |
