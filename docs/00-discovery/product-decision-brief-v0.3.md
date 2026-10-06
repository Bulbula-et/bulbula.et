# Bulbula Product Decision Brief

| | |
| --- | --- |
| **Document** | Product Decision Brief |
| **Version** | 0.3 |
| **Status** | **Superseded** |
| **Superseded by** | [product-decision-brief-v0.4.md](product-decision-brief-v0.4.md) — the owner approved the decisions proposed here on 2026-10-07. Approved decisions now live in [docs/60-decisions/decision-register.md](../60-decisions/decision-register.md); this body is retained unedited as the record of the analysis behind them. |
| **Date** | 2026-10-07 |
| **Supersedes** | [product-decision-brief-v0.2.md](product-decision-brief-v0.2.md) (status → Superseded). v0.2 is retained unmodified; §3 below lists every statement in it that is now obsolete |
| **Builds on** | [project-understanding-v0.1.md](project-understanding-v0.1.md) · [research-notes-v0.1.md](research-notes-v0.1.md) |
| **Phase** | 2.2 — Finalize product decisions and prepare for PRD |
| **Scope of this document** | Product definition only. No PRD, no TRD, no `.ai/` files, no schema, no code |

**Certainty tags** (unchanged, never silently upgraded):
**[C]** Confirmed by the owner · **[SI]** Strongly implied · **[P]** Proposed,
needs approval · **[U]** Unknown · **[X]** Conflict.

---

## Status at a glance

| Area | Status |
| --- | --- |
| V1 listing ownership | **[C] Bulbula-company-managed** |
| Owner-created listings | **[C] Future** |
| Owner claims | **[C] Future** |
| Owner edits / self-service dashboard | **[C] Future, and gated behind admin approval when it comes** |
| V1 data collection | **[C] Directly from the business, with the business's permission** |
| Personal-data posture | **[C] Minimize; prefer business information** |
| Guest browsing | **[C] Confirmed** |
| Google login | **[C] V1** |
| Email login (verification + OTP, no passwords) | **[C] V1** |
| Apple login | **[C] NOT V1** |
| Telegram identity | **[U] Evaluate separately for the Mini App** |
| Transactional email provider | **[C] Required capability**; **[U] provider deferred to infrastructure** |
| Authenticated reviews / favourites | **[C] Confirmed** |
| First launch | **[C] Web + Telegram Mini App** |
| Telegram Mini App | **[C] First-class client surface, shared backend and domain model** |
| Shared frontend with runtime adaptation | **[C] Confirmed direction** |
| Mobile-first Web | **[C] Confirmed** |
| Flutter | **[C] Later client** |
| Advertising V1 | **[C] Staff-managed fixed placements; no auction, no self-service** |
| Business/Branch model | **[P] Proposed until approved** |
| Language strategy | **[P] Option A recommended — owner choice outstanding** |
| Personal data hosted in Ethiopia | **[C] Preferred architecture where required** |
| Final logo | **[C] Not finalized** |
| Primary / secondary colour | **[C] Orange / Blue**, light mode white-dominant |
| Framework-free PHP backend, modular monolith | **[C] Confirmed** |

---

## 1. Executive summary

### 1.1 What this phase settled

Phase 2.1 established *who owns listings*. Phase 2.2 establishes *how the data
gets there*, *who the customer is*, and *what the platform is allowed to know
about people*. Three decisions do most of the work:

1. **Listings are built from permission-based, first-party business data.**
   Staff visit or contact the business, explain Bulbula, obtain permission,
   collect the information, verify it, and publish. Bulbula is not scraping a
   database together from public fragments.
2. **Personal data is minimized by design.** The listing model is deliberately
   a *business* record, not a record about a proprietor. Personal data enters
   the system mainly through customer accounts, which are small and bounded.
3. **Authentication is Google + email OTP. Apple is out of V1.** This removes
   the single most awkward V1 dependency (a paid Apple Developer Program
   account, domain verification, and a client secret that silently expires
   every six months) and replaces it with one real requirement: professional
   transactional email.

### 1.2 Why these decisions improve the product, not just the schedule

The permission-based collection model is the most under-appreciated decision
in this phase. It changes Bulbula's legal posture, its data quality ceiling
and its sales motion at the same time:

```text
permission-based collection
   ├── legal     → the business knows it is listed and agreed to it
   ├── quality   → data comes from the source, not from guesswork
   ├── freshness → there is a named contact to call when data goes stale
   └── revenue   → the operator who collected the data has already
                   had the conversation that advertising later requires
```

It also costs more per listing than desk research, which is precisely why
the operations pilot (§21) is now the most valuable thing Bulbula can do
before writing the PRD.

### 1.3 What this phase deliberately did not settle

The language strategy (§22) needs an owner choice. The launch threshold (§21)
needs pilot data. Several legal items need professional confirmation (§20).
Everything else that remains open is either an approval of a recommendation
in this document or a decision that genuinely belongs in the TRD.

### 1.4 Verdict

**PRD readiness: NOT READY — with four blockers, not thirty.** See §30.
Three of the four can be closed in a single review session; the fourth
(launch definition) needs roughly a week of operational measurement.

---

## 2. Newly confirmed owner decisions (Phase 2.2)

Numbering continues from v0.2's NC-1…NC-15. All are **[C]**.

| # | Decision |
| --- | --- |
| **NC-16** | **Future owner model is approval-gated.** When business accounts arrive, owners may create, claim, manage and submit changes — but **every owner-submitted change passes through Bulbula admin review** (approve / reject / request correction) before publication. The architecture must keep this path open without building dormant owner features into V1 |
| **NC-17** | **V1 collects business information directly from the business, with the business's permission.** The operating model is: visit or contact → explain Bulbula → obtain permission → collect information → verify → create listing → publish |
| **NC-18** | **Data minimization is a product principle:** collect the minimum personal information necessary, and prefer business information wherever the product goal can be achieved without personal data |
| **NC-19** | **Information is classified by whether it relates to an identifiable natural person**, not by whether it is labelled "business data". Business data must not be declared automatically non-personal |
| **NC-20** | **V1 customer authentication = Google + email.** **Apple Sign In is not V1.** Email authentication uses email verification and one-time codes. **No password-based system** unless a later explicit decision changes this |
| **NC-21** | **Email is a first-class V1 product capability**, not only an authentication mechanism: verification, account/security notifications, review and moderation notifications, support and legitimate service communications. Delivery must use a **professional transactional email provider**, not shared-hosting local mail. Provider selection is a later infrastructure decision |
| **NC-22** | **Unified Bulbula identity**: one user with linked Google and email identities. No separate account per provider. **No unsafe auto-merge on matching email alone.** Telegram identity is evaluated separately for the Mini App |
| **NC-23** | **Telegram is a first-class client surface** running in Telegram's Mini App web runtime, sharing the same backend, domain model, product rules, business data, search, reviews and (where applicable) authentication model. **No separate backend. No duplicated business logic.** Telegram-specific capability sits behind an adapter boundary |
| **NC-24** | **One frontend implementation shared between Web and the Mini App** as far as is technically sensible (tokens, components, layouts, cards, profiles, search, filters, navigation concepts, interaction patterns), with **runtime-specific adaptation** for Telegram theme, safe areas, lifecycle, navigation, deep links, sharing and identity. Identical chrome must **not** be forced where Telegram requires different interaction |
| **NC-25** | **V1 product shape is as enumerated by the owner** (public discovery / Bulbula operations / customer accounts), with business accounts, owner-created listings, owner claims, owner profile control, owner dashboards, owner review replies, owner self-service advertising and owner self-service billing **explicitly excluded from V1** |
| **NC-26** | **Advertising V1 is staff-managed**: sponsored business placement, category sponsorship, homepage promotion and other clearly defined fixed placements. Staff create campaigns on behalf of businesses. **No auction, no CPC, CPM or CPA, and no self-service advertiser dashboard in V1.** Organic ranking and paid placement remain separate; paid placement must not silently modify organic relevance; sponsored content is clearly labelled |
| **NC-27** | **Preferred architecture keeps personal data on appropriate Ethiopian-hosted infrastructure where required.** Storing data locally does **not** by itself constitute legal compliance, and this document gives no legal advice |
| **NC-28** | **Operations are central to the product.** The lifecycle is discovery → collection → permission → verification → listing creation → quality review → publication → monitoring → correction → re-verification. A timed pilot of roughly 20 businesses may be used to measure it. **The final launch threshold is not to be set automatically** |
| **NC-29** | **Documentation must carry status (Draft / Review / Approved / Superseded) and semantic versions**, and must cover product, business, technical, UX/UI, platforms, security, privacy, quality, operations and decisions |
| **NC-30** | **The `.ai/` context system is defined now and created later**, and must prevent hallucinated requirements, architecture drift, UI inconsistency, terminology inconsistency, forgotten decisions, undocumented assumptions, scope creep and accidental framework introduction |

**Reaffirmed without change from v0.2:** company-managed V1 listings (NC-1…NC-3),
guest browsing (NC-4), authentication required for identity-bearing
interactions (NC-6), Web + Telegram first launch (NC-9), mobile-first web
(NC-10), Flutter as a later client that shares backend and rules but not
frontend code (NC-14).

---

## 3. Corrections to v0.2 — obsolete assumptions removed

This is the authoritative delta. Each row states what v0.2 said, what is now
true, and what follows.

| # | v0.2 statement | Correction | Consequence |
| --- | --- | --- | --- |
| **C-1** | Apple Sign In confirmed for V1 (NC-5); **D-32** raised to secure an Apple Developer Program account; **RK-5** flagged it as a likely V1 slip | **Apple is not V1** (NC-20) | **D-32 closed. RK-5 retired.** Removes a 99 USD/yr dependency, a Services ID and domain-verification setup, a six-monthly client-secret rotation task, and the Apple private-relay email edge case from V1 entirely |
| **C-2** | Email login described as viable *only if* deliverability could be solved, and flagged **[U]** with **RK-6** ("email login undeliverable — may have to be dropped") | **Email OTP is confirmed V1, and professional transactional email is a funded requirement** (NC-20, NC-21) | **RK-6 downgraded** from "may kill a feature" to "must be procured". Email moves from an auth detail to a product subsystem (§9) |
| **C-3** | v0.2 §5.3 left password-vs-passwordless as a preference ("prefer one-time codes or magic links") | **No passwords. Verification + OTP only** (NC-20) | Removes password storage, reset flows, credential-stuffing exposure and the associated test surface from V1 |
| **C-4** | v0.2 §4.1 described the model as Bulbula "discovering" listings, with provenance sources including "public web", and §15 **L-14** framed the legal posture as *publishing a business database without owner involvement*; **RK-3** rated this High/Medium | **V1 collects directly from the business, with permission** (NC-17) | **L-14 materially reframed and RK-3 reduced** (§20.4). A *permission record* becomes a first-class listing artifact (**D-43**). Desk research survives only as a *lead-generation* step before contact, never as a publication source |
| **C-5** | v0.2 §7 framed Telegram as "a runtime of the web application" with a `runtime: web \| telegram` flag | **Telegram is a first-class client surface** that shares one implementation (NC-23) | Terminology and architecture corrected in §12. The implementation technique (one codebase, adapter boundary) is unchanged and still validated; the *status* of the surface is upgraded. Telegram must appear in requirements as a surface in its own right, not as a conditional branch of the website |
| **C-6** | v0.2 **D-33** proposed adopting Telegram as a fourth identity provider for V1 | **Telegram identity is to be evaluated separately** (NC-22) — not confirmed | D-33 stays open, reclassified **B**, with the evaluation framed in §10.4. v0.2's recommendation is retained as **[P]**, not as a plan |
| **C-7** | v0.2 §3.2 item 14 treated "report a problem" as the *only* inbound channel from reality | There is now also a **named business contact** who gave permission and can be called | Correction turnaround (§21) gains a second, faster channel. Does not reduce the need for the public report form |
| **C-8** | v0.2 §15 **L-13** worried about retaining third-party verification *evidence* | V1 records verification method, date, operator and a **permission record**, not uploaded third-party documents | Lower retention risk, but a new minimization question: how much is recorded about the *person* who gave permission (**D-43**) |
| **C-9** | v0.2 §24 listed **five** PRD blockers and 13 Class A decisions | The owner's §13 scope statement, the auth decisions and the advertising decisions resolve several of them outright | Blocker set reduced to **four** (§29), Class A reduced from 13 to 6 (§28) |
| **C-10** | v0.2 §10 proposed an "approval" step and package catalogue that implied possible future self-service | **No self-service advertiser dashboard and no auction/CPC/CPM/CPA in V1** (NC-26) | §15 simplified: fixed placements, fixed periods, staff-created campaigns |

**Not corrected — still standing from v0.2:** the three extensibility seams
(§7.2), the shared-frontend technique, the mobile-first guidance, the
business/branch recommendation, the category and location strategies, and
research findings R-16…R-23.

---

## 4. V1 product shape (updated)

**[C] NC-25.** The owner's enumeration is adopted as the V1 scope statement.
This largely resolves **D-01**; what remains are the item-level questions
marked **[P]** or **[U]** below.

### 4.1 Public discovery — **[C] in V1**

| Capability | Note |
| --- | --- |
| Homepage | Discovery surfaces + labelled sponsored slots |
| Search | Core verb; MariaDB full-text + alias expansion (v0.1 R-03, PR-4) |
| Autocomplete | Confirmed in V1 by the owner's list |
| Categories / subcategories | Two levels (§18) |
| Location / area browsing | §19 |
| Nearby | Device geolocation, bounding box + haversine |
| Business profiles | §17 model |
| Opening hours | Drives open/closed and the "open now" filter |
| Contact actions | Call, website, directions, social — all tracked |
| Google Maps | Deferred-load embed + "open in Maps" |
| Reviews | Authenticated write, public read (§16) |
| Favourites / likes / saves | Authenticated (§11) |
| Share | Web share + Telegram share |
| Trust indicators | Verified state, last-verified date, data-provenance statement |
| Report a problem / suggest correction | Guest-safe, rate-limited (§11) |
| Sponsored placements | Labelled, fixed inventory (§15) |
| SEO and static information pages | Policies, about, how ranking works, how listings are collected |

### 4.2 Bulbula operations — **[C] in V1**

Business research · listing creation · listing editing · verification and
quality control · media management · taxonomy management · moderation ·
reports · advertising management · analytics · audit logging.

**[P] Addition for consideration:** a **permission register** view — the set
of listings and the permission record behind each (§5.4). It is one screen,
and it is what makes NC-17 auditable rather than aspirational.

### 4.3 Customer accounts — **[C] in V1**

Google authentication · email verification and OTP · profile · favourites ·
reviews · account deletion · privacy controls appropriate to approved legal
requirements.

### 4.4 Explicitly not V1 — **[C]**

Business accounts · owner-created listings · owner claims · owner profile
control · owner self-service dashboard · owner review replies · owner
self-service advertising purchase · owner self-service billing.

### 4.5 Still open at item level

| Item | Status | Decision |
| --- | --- | --- |
| Services / products / pricing representation (structured vs free text) | **[U]** — the owner's field list includes them, the *shape* is undecided | **D-44** |
| Review photos | **[U]** | D-36 |
| "Helpful" voting on reviews | **[U]** | D-37 |
| Saved searches, collections, offers, FAQs | **[P] Later** | — |
| Amharic interface | **[P]** depends on §22 | D-18 |

### 4.6 Updated capability map

**V1** · **Later** (planned, post-V1) · **Future** (conditional) ·
**Unknown** (undecided).

| Capability area | Status | Change since v0.2 |
| --- | --- | --- |
| Guest browsing | **V1** | — |
| Search, filters, sorting, autocomplete | **V1** | Autocomplete now explicitly V1 |
| Business profiles (Business → Branch) | **V1** | Model still **[P]** |
| Categories / taxonomy | **V1** | — |
| Locations / areas | **V1** | — |
| Maps and directions | **V1** | — |
| Opening hours / open-now | **V1** | — |
| Customer accounts: **Google + email OTP** | **V1** | **Apple removed** |
| Transactional email subsystem | **V1** | **Upgraded from "auth dependency" to product capability** |
| Reviews and ratings | **V1** | — |
| Favourites / likes / saves | **V1** | — |
| Reports and corrections | **V1** | Plus named business contact |
| **Permission-based data collection** | **V1** | **New** |
| Listing management console | **V1** | — |
| Verification, provenance, permission records | **V1** | Permission record is new |
| Moderation | **V1** | — |
| Advertising (staff-managed fixed placements) | **V1** | Auction/self-service explicitly excluded |
| Billing records (manual, staff-entered) | **V1** | — |
| Media management | **V1** | — |
| Analytics and internal reporting | **V1** | — |
| Audit logging | **V1** | — |
| SEO and structured data | **V1** | — |
| Web frontend (mobile-first) | **V1** | — |
| Telegram Mini App | **V1** | **Elevated to first-class client surface** |
| Internationalisation readiness | **V1 (architecture)** | UI language pending D-18 |
| Privacy operations (DSR, consent, retention) | **V1** | Scope reduced by minimization |
| Apple Sign In | **Future / not planned** | **Removed from V1** |
| Telegram identity provider | **Unknown** | Evaluate separately |
| Flutter client | **Later** | — |
| Business accounts + owner submissions with admin approval | **Future** | Approval gate now specified |
| Owner review replies | **Future** | — |
| Self-service advertising and billing | **Future** | — |
| Messaging / leads | **Future** | — |
| Transactions (booking, ordering, payment) | **Out of scope** | — |
| Dark mode (web) | **Unknown** | Required in the Telegram surface regardless |

---

## 5. V1 listing and data-collection model

### 5.1 The confirmed operating model — **[C] NC-17**

```text
   lead generation            │ desk research may find candidates,
   (internal only)            │ but never publishes anything
            ↓
   visit or contact the business
            ↓
   explain Bulbula            │ what it is, what gets published,
                              │ what it costs (nothing), how to correct it
            ↓
   obtain permission          │ recorded: who, role, date, method
            ↓
   collect business information
            ↓
   verify                     │ method + date + operator
            ↓
   create listing             │ completeness check, duplicate check
            ↓
   quality review             │ second pair of eyes before publish
            ↓
   publish
            ↓
   monitor → correct → re-verify
```

### 5.2 Why the "permission" step is architecturally significant

**[P]** It is not merely politeness. It produces:

- a **named contact** for every listing, which is the cheapest possible
  freshness mechanism;
- a **defensible basis** for publication (§20.4), which desk-scraped data
  would not have;
- a **sales relationship** that advertising (§15) later depends on;
- and a **quality floor**, because the business itself supplied the facts.

It also produces a cost: permission can be refused. **Permission-refusal rate
is therefore a V1 operations metric** (§21.3), and it directly affects the
coverage target in the launch definition. A refusal must be recorded so the
same business is not re-approached blindly next month — and so that a refusal
is honoured rather than forgotten.

### 5.3 Listing field model — **[C] field list, [P] shapes**

Business-related information (the default, and the overwhelming majority):

business name · category and subcategories · description · address ·
business phone · business email where applicable · website · public social
links · opening hours · services · products · pricing information where
appropriate · business photos · location coordinates · other genuinely
business-related information.

**[P] Shaping notes:**

| Field | Note |
| --- | --- |
| Business phone / email | Treated as **contact-point data for the business**, published deliberately. See §6 for when such a contact point is nevertheless personal data |
| Services / products / pricing | **D-44** — recommend a simple structured list (name, optional short description, optional price or price range, optional currency) rather than free text, because it is searchable, filterable and translatable. Prices in Ethiopia move; recommend "price range" plus an "as of" date rather than exact prices for most categories |
| Photos | Premises, signage, products. **[P] Rule: avoid photographing identifiable individuals**, which serves both minimization (NC-18) and simpler consent |
| Coordinates | Captured on site where possible; this is the single hardest field to fix later remotely |
| "Other business information" | Must not become a free-text dumping ground that smuggles personal data in. Recommend an allow-list of additional attribute types per category |

### 5.4 The permission record — **[P], new, D-43**

Proposed minimum content: the business it relates to, the **role** of the
person who gave permission (owner / manager / authorised staff), their name
only where necessary to make the record meaningful, the date, the method
(in person / phone / written), the operator who obtained it, and the scope
(publication of business information; photography yes/no; contact for
advertising yes/no).

**[P] Minimization tension, stated honestly:** the permission record is
*itself* personal data about the person who consented. The product goal
(provable permission) cannot be fully achieved without identifying a person,
so NC-18's test ("prefer business information whenever the product goal can
be achieved without personal data") is not satisfiable here. The
recommendation is therefore: record the minimum that makes the permission
provable, keep it **internal and never published**, and set a retention
period with counsel (**D-46**).

### 5.5 What V1 must not collect — **[C] NC-18**

Owner's private phone · owner's private email · employee lists · national ID
information · personal residential addresses · unnecessary personal documents
· unnecessary demographic information.

**[P] Enforcement, not just policy:** the operations console should have no
field in which these can be entered. A policy that relies on staff
remembering it will fail; an absent field cannot be filled in. Where a
proprietor's personal mobile is genuinely the only business contact number,
it should be recorded as the business contact point with an explicit
acknowledgement in the permission scope — that is a real and common case in
Addis, and pretending otherwise would make the rule unworkable.

---

## 6. Information classification and data minimization

**[C] NC-19:** classify by whether information relates to an identifiable
natural person. **[C]** Do not claim business data is automatically
non-personal.

### 6.1 The four classes

| Class | Definition | Examples | Personal data? |
| --- | --- | --- | --- |
| **Business information** | Information describing the business as an entity | Trading name, category, description, address, opening hours, services, premises photos, coordinates | **Usually not** — Ethiopian law protects natural persons, not legal persons (**[C] R-26**) |
| **Personal information** | Information relating to an identified or identifiable natural person | A sole trader's own name used as the trading name, a proprietor's mobile used as the business line, a named contact in a permission record, a person visible in a photo | **Yes** |
| **Internal operational information** | Records of Bulbula staff actions and data provenance | Who created a listing, who verified it and when, audit log entries, moderation decisions | **Yes, about staff** — and often also about the business contact |
| **Customer information** | Data belonging to registered users | Identity links, display name, email, reviews, favourites, sessions, analytics tied to an account | **Yes** |

### 6.2 The overlap that must not be glossed over — **[P]**

In a market with many sole proprietorships, the line between "business
contact" and "personal data" is genuinely blurred:

```text
"Abebe Electronics, call 09xx xxx xxx"
          ↑                  ↑
   may be the owner's   may be the owner's
   personal name        personal mobile
```

**[P] Recommended handling:** treat any contact point that is *also* a
natural person's personal contact point as personal data for rights purposes
(access, correction, erasure, objection), while publishing it as a business
contact under the recorded permission. Flag it in the data model
(`is_personal_contact`) so an erasure or objection request can be executed
precisely instead of by manual search. This single boolean is the cheapest
compliance affordance available and must be decided before the data model
(**Class B**).

### 6.3 The minimization principle as a design rule — **[P]**

> **Collect the minimum personal information necessary, and prefer business
> information whenever the product goal can be achieved without personal
> data.**

Applied as three operational tests, in order:

1. **Necessity** — does a V1 product goal fail without this field? If not,
   do not add the field.
2. **Substitution** — can a business-level fact achieve the same goal? (Use
   a shop's landline rather than the owner's mobile; use a role rather than
   a name.)
3. **Separation** — if personal data is necessary, can it be kept *internal*
   rather than published? (Permission records: yes. Review authorship:
   no — a display name is inherent to the feature.)

Every field added to the PRD should carry the answer to test 1 in one line.
That is a small discipline that makes the later DPIA (§20) largely a matter
of transcription.

---

## 7. Future business-owner model

### 7.1 The confirmed future workflow — **[C] NC-16**

```text
Business owner
     ↓  create / claim / submit change
Pending submission
     ↓
Bulbula admin review  ──► reject (with reason)
     ↓                ──► request correction (back to owner)
   approve
     ↓
Publish  (listing history records who proposed and who approved)
```

**[C]** Not implemented now. **[C]** V1 must not contain dormant owner
features.

### 7.2 What V1 must do to keep this cheap — **[P]**

The three seams from v0.2 §4.3 remain correct and are now **strengthened by
NC-16**, because an approval workflow is easier to add to a system that
already routes every change through one audited path:

1. **Ownership seam** — `ownership_state` (`company_managed` → later
   `claimed`, `owner_managed`) and a nullable `owner_user_id`, always `NULL`
   in V1.
2. **Change seam** — every mutation goes through one application service that
   records *actor*, *reason* and *source*. In V1 the actor is always a staff
   member. The future owner path adds an actor type and an approval state;
   it does not add a second write path.
3. **Provenance seam** — field-level source and verified-at metadata, so an
   owner-proposed value can be distinguished from a Bulbula-verified one.

**[P] One further seam this phase adds:** because NC-16 requires
*proposed-change review*, the change seam should be designed so a change can
exist in a **proposed** state without being applied. In V1 this is used by
exactly one feature that already exists in scope — the public "suggest a
correction" queue. Building that queue properly in V1 *is* the prototype of
the future owner-submission workflow, at no extra cost. This is the single
most valuable architectural observation in this section. **[P]**

### 7.3 Anti-recommendations — **[P]**

Do not build: a disabled business dashboard, owner roles or permission
scaffolding for actors that cannot exist, a claim state machine, or
owner-facing notification templates. Seams are cheap; unused features are
liabilities that must still be tested, documented and secured.

---

## 8. Customer accounts and authentication (updated)

### 8.1 Confirmed — **[C] NC-20**

| | |
| --- | --- |
| **Guest** | Browse, search, view businesses, categories, locations and public information. No account required for normal discovery |
| **Registered** | **Google** and **email**. Email uses verification + one-time code. **No passwords.** **Apple Sign In is not V1** |

### 8.2 What dropping Apple actually removes

**[C]** Per R-17 (v0.2), Sign in with Apple for the web required a paid
developer programme membership, a Services ID tied to a primary App ID,
registered domains and return URLs, a private key, and a client secret JWT
that must be regenerated at least every six months. All of that leaves V1.

**[P] One consequence to record for later, not now:** if and when the Flutter
iOS app ships, App Store review guidelines effectively require Sign in with
Apple wherever other third-party social logins are offered. Apple login is
therefore **not cancelled — it is deferred to the iOS client decision**, and
should be revisited as part of the Flutter phase, not forgotten. Recorded as
**D-47 (Class D)**.

### 8.3 Email OTP: the controls that make it responsible — **[P]**

Email OTP is the right choice for this product: it needs no password
infrastructure, works on any device, and matches how Ethiopian users already
receive codes. But it must be built with its known weaknesses in mind.

**[C] R-25 (research):** NIST SP 800-63B is explicit that email **shall not**
be used as an out-of-band channel for *multi-factor* authentication — it is
not bound to a device and can be intercepted or rerouted. The same guidance
treats **email address validation and account-recovery codes as a different,
permitted case.** Mainstream practice for consumer sign-in is 6–8 digit
codes, 5–10 minute expiry, 3–5 failed attempts, 3–5 code requests per address
per hour, codes stored hashed, and codes bound to the session that requested
them.

**[P] Recommended V1 controls:**

| Control | Recommendation |
| --- | --- |
| Code format | 6 digits, generated with a CSPRNG |
| Expiry | 10 minutes (email delivery in Ethiopia can be slow; shorter windows cause failures) |
| Attempts | 5 failed attempts per code, then invalidate and require a new one |
| Request rate | 3–5 per address per hour, plus a per-IP cap and a 60-second resend cooldown |
| Storage | Store a hash, never the code; delete on use |
| Replay | Single use, invalidated on success |
| Session binding | A code is only valid in the browser/session that requested it |
| Enumeration | Identical response whether or not the address exists |
| Notification | Email the user on new-device sign-in and on identity linking |

**[P] The non-obvious consequence — staff accounts must be stronger.** Email
OTP is adequate for a customer who writes reviews. It is **not** adequate for
an operations console that can publish listings, approve campaigns and read
the permission register. A compromised staff mailbox would mean a compromised
directory. Recommendation: staff/admin accounts require a second factor
(TOTP authenticator app — free, offline, no SMS costs, no provider
dependency). Recorded as **D-45 (Class B)**. This is a real gap in v0.2 that
only became visible once passwords were removed.

### 8.4 Account lifecycle — **[P]**

Sign-up is implicit on first successful authentication (no separate
registration form). Minimum profile: display name (defaulted, editable),
locale, created-at. Account deletion is self-service, soft-deletes reviews'
author linkage while preserving moderation history, and is confirmed by
email. Minimum account age must be set with counsel (**D-46**) because the
Proclamation gives minors heightened protection.

---

## 9. Email as a V1 product capability

**[C] NC-21.** Email is not merely an auth mechanism. It is a subsystem with
product requirements.

### 9.1 Required message classes in V1 — **[P] for the list, [C] for the requirement**

| Class | Examples | Consent basis |
| --- | --- | --- |
| **Authentication** | Login codes, email verification | Necessary for the service requested |
| **Security** | New-device sign-in, identity linked, account deletion confirmed | Legitimate service notification |
| **Review lifecycle** | Review published, rejected with reason, reported content outcome | Service notification |
| **Moderation / support** | Correction report acknowledged and resolved, support replies | Service notification |
| **Account / product** | Material policy changes, scheduled downtime | Service notification |
| **Marketing** | Newsletters, promotions | **Separate, opt-in, unbundled, withdrawable** — must never be bundled with the above |

**[P]** The marketing/service split must exist in the data model from day
one (a per-purpose preference, not a single "emails on/off" flag). Retrofitting
unbundled consent is far more expensive than designing it.

### 9.2 Delivery requirements — **[P]**

1. A **professional transactional provider** (NC-21), not shared-hosting
   `mail()`. Shared cPanel IPs have no sending reputation and OTP mail that
   lands in spam is indistinguishable from a broken login.
2. **Authenticated sending domain**: SPF, DKIM and DMARC on `bulbula.et`.
3. **Separate streams** for transactional and any future marketing mail, so
   a marketing complaint can never damage OTP deliverability.
4. **Observability**: delivery, bounce, complaint and latency metrics must be
   visible to operations — OTP delivery latency is effectively a login
   success metric.
5. **Templates**: plain, text-first, no tracking pixels on authentication
   mail, Amharic-ready (§22).
6. **Failure behaviour**: if the provider is down, the login screen must say
   so rather than silently failing.

### 9.3 Provider selection — deferred, with the criteria fixed now

**[C]** Provider choice is a later infrastructure decision (**D-41, Class C**).
**[P]** The selection criteria should be recorded now so the decision is not
made casually later: deliverability to Ethiopian recipients (Gmail dominance
matters), latency, price at low volume, API simplicity from plain PHP,
webhook support for bounces, **and — materially — where the provider
processes data** (§20.3), because every transactional provider is a foreign
processor receiving email addresses.

**[P]** One option deserves explicit evaluation alongside the global
providers: a **local or regionally-hosted sending service**, if one exists
with adequate deliverability. This may be the only way to reconcile NC-21
with NC-27 without relying on a transfer condition. Unknown whether a
suitable option exists — **[U]**, to be checked during the infrastructure
phase.

---

## 10. Unified Bulbula identity

### 10.1 Confirmed model — **[C] NC-22**

```text
Bulbula User  (one person, one account)
   ├── Identity(provider = google, subject = <sub>, email, verified)
   └── Identity(provider = email,  subject = <address>, verified)
```

**[C]** No separate account per provider. **[C]** No unsafe auto-merge based
only on a matching email address. **[U]** Exact linking rules remain a
technical/security decision (**D-13, Class B**).

### 10.2 Recommended linking rules — **[P]**

1. The provider **subject** (`sub`) is the key, never the email address.
2. A Google sign-in whose verified email matches an existing **verified**
   email identity may be offered as a link — **after** the user proves
   control of the existing account (complete an email OTP), never silently.
3. Linking is always an authenticated, deliberate action.
4. Unlinking is allowed only while at least one identity remains.
5. Changing the email on the email identity re-verifies both addresses.
6. Display identity is Bulbula's own, so a provider change never alters how
   an existing review is attributed.

### 10.3 Why rule 2 matters more than it looks — **[P]**

The combination "Google sign-in" + "email OTP on the same address" is the
classic pre-hijack setup: an attacker registers with the victim's email
address before the victim does, then waits. Requiring proof of control before
merging, and notifying both addresses on every link, closes it. The cost is
one extra screen.

### 10.4 Telegram identity — the separate evaluation — **[U], D-33**

**[C]** To be evaluated separately for the Mini App. The evaluation should
weigh:

| For adopting Telegram identity | Against |
| --- | --- |
| Telegram already supplies a signed, server-verifiable user identity (`initData`, v0.1 R-10) — zero friction, no email round-trip | A third identity provider to maintain, link and reason about |
| Redirect-based OAuth inside an embedded WebView is a known friction point (**[C] R-23**); Google sign-in inside the Mini App may be awkward or blocked | Telegram becomes a processor receiving Bulbula account linkage (§20.3, **L-12**) |
| Without it, Mini App users may be unable to review or save in practice, which undermines NC-23's "shared review system" | A Telegram-only account has no email, so it cannot receive the service notifications of §9 |
| Matches how Ethiopian users already authenticate to Telegram-native services (v0.1 R-06) | Linking a Telegram identity to an existing web account needs its own, careful flow |

**[P] Preliminary recommendation (not a decision):** adopt it for the Mini
App surface, mapped into the same `User` through the same `Identity` table,
with email capture offered but not required. The decision should be taken
with the Mini App's authentication prototype in hand, not before.

---

## 11. Interaction classification

**[C]** The four-bucket classification is required. **[C]** Not every future
interaction requires authentication.

### 11.1 Guest-safe

Browse · search · autocomplete · view categories, areas and profiles ·
view reviews and ratings · use nearby with device permission · open map and
directions · call / website / social click · share · **report a problem with
listing data** · **report a business as closed or fraudulent** · read static
and policy pages · request personal-data removal via the published contact
path.

**[P] Rationale for keeping reports guest-safe:** a correction channel behind
a login collects almost nothing. Rate limiting and a moderation queue are the
right controls, not authentication.

### 11.2 Authenticated-required

Write, edit or delete a review · favourite / like / save · report a review ·
manage profile and notification preferences · delete account · (if adopted)
any action attributed to a named user.

### 11.3 Admin-only

Create / edit / unpublish listings · set verification state · record and view
permission records · manage categories and locations · moderate reviews and
reports · process correction submissions · create / pause campaigns · view
analytics and audit logs · export a performance report for a business.

### 11.4 Future

Owner submissions and claims (admin-approved, NC-16) · owner review replies ·
owner self-service advertising and billing · follow a business · messaging ·
"helpful" voting (D-37) · review photos (D-36).

### 11.5 Matrix

| Interaction | Guest | Registered | Business (no account in V1) | Admin | Status |
| --- | --- | --- | --- | --- | --- |
| Browse / search / view | Yes | Yes | n/a | Yes | **[C]** |
| Nearby, map, directions | Yes | Yes | n/a | Yes | **[C]** |
| Contact actions (call, site, social) | Yes | Yes | n/a | Yes | **[C]** |
| Share | Yes | Yes | n/a | Yes | **[C]** |
| Report listing problem | **Yes** | Yes | via staff contact | Process | **[P]** |
| Suggest a structured correction | **No** | Yes | via staff contact | Process | **[P]** — D-35 |
| Write / edit / delete review | No | Yes | n/a | Moderate | **[C]** |
| Favourite / save | No | Yes | n/a | — | **[C]** |
| Report a review | No | Yes | via staff contact | Process | **[P]** |
| Reply to a review | No | No | **Future** (NC-16) | Post on request, labelled | **[C] future** |
| Manage listing data | No | No | **Future, admin-approved** | Yes | **[C]** |
| Campaign management | No | No | **Future** | Yes | **[C]** |
| Request erasure of own personal data | Yes (contact path) | Yes (in account) | n/a | Process | **[C] legal** |

---

## 12. Telegram Mini App (terminology and architecture corrected)

### 12.1 Status — **[C] NC-23**

Telegram is a **first-class client surface**, not a conditional rendering
mode of the website. It runs in Telegram's Mini App web runtime and shares:
backend, domain model, product rules, business data, search, review system,
authentication model where applicable, and core UI components where
technically appropriate.

**[C]** No separate backend. **[C]** No duplicated business logic.

### 12.2 What the correction changes in practice — **[P]**

| v0.2 framing | v0.3 framing |
| --- | --- |
| "The Mini App is the website with a flag set" | "The Mini App is a product surface that happens to be implemented by the same code" |
| Telegram requirements appear as caveats in web sections | **Telegram requirements appear as first-class requirements** in the PRD, with their own acceptance criteria |
| Success measured on web metrics | **Surface-level metrics**: Mini App sessions, Mini App search→contact rate, share-to-open rate from Telegram |
| Telegram-specific UX treated as deviation | Telegram-specific UX treated as **correct adaptation** (NC-24) |

The implementation technique from v0.2 §7 — one server-rendered view layer,
one Telegram adapter, CSS-variable design tokens — remains the recommendation
(**[P]**, still pending the prototype). Only its *status in the product
hierarchy* changes.

### 12.3 The adapter boundary — **[P]**

Isolated behind the adapter, and nowhere else in the codebase:
`telegram-web-app.js` loading and `WebApp.ready()` · theme parameters and
`themeChanged` · viewport and safe-area variables · `MainButton` /
`BackButton` · haptics · Telegram share and deep links (`startapp`
parameters) · `initData` acquisition · Mini App lifecycle events.

Validated server-side, outside the adapter: **`initData` signature
verification against the bot token** (v0.1 R-10) before any authenticated
action. **[C] requirement, implementation later.**

### 12.4 Explicit non-goals — **[C] NC-24**

Do not force identical chrome. Do not push Telegram navigation patterns into
the website. Do not build a second backend, a second domain model or a
Telegram-only data path.

---

## 13. Web + Telegram frontend strategy

### 13.1 Shared by default — **[C] NC-24**

Design tokens · components · layouts · business cards · business profiles ·
search · filters · navigation concepts · interaction patterns · terminology ·
API integration.

### 13.2 Adapted per surface — **[C]**

| Concern | Web | Telegram |
| --- | --- | --- |
| Chrome | Site header, footer, breadcrumbs | Telegram header; minimal in-app chrome |
| Back | Browser history | `BackButton`, kept consistent with history |
| Primary action | In-page button | May bind to `MainButton` where it is the single action |
| Theme | Bulbula tokens, white-dominant light mode | Bulbula tokens **mapped onto** Telegram theme variables |
| Share | Web Share API / copy link | Telegram share, deep link with `startapp` |
| Auth | Google + email OTP | Per **D-33** evaluation |
| Viewport | Standard responsive | `--tg-viewport-stable-height`, safe-area insets |
| SEO | Full indexing, structured data | Not applicable — Telegram content is not crawled |

### 13.3 The token rule that makes this work — **[P]**

All colour, spacing and radius values are CSS custom properties. The Telegram
adapter maps *surface* tokens (background, secondary background, text, hint,
link) to Telegram's values, while **identity tokens (brand orange, brand
blue) stay Bulbula's**. Two consequences that must be designed for:

1. Contrast must be re-validated against Telegram's light **and** dark
   themes, because the user controls them. A brand colour that passes on
   white may fail on a dark Telegram theme.
2. The web light mode is white-dominant **[C]**, but the Telegram surface
   will be dark for many users regardless of **D-19**. The design system
   therefore needs dark-capable semantic tokens even if the website ships
   light-only — this is a stronger statement than v0.2 made.

### 13.4 Discipline — **[P]**

Branching on surface is permitted only in layout and chrome components.
Content components (business card, review item, filter chip, hours table)
must contain no surface conditionals. If a content component needs to know
the surface, that is a signal the adaptation belongs one level up.

---

## 14. Mobile-first design

**[C]** Confirmed principle. Design from small phones, touch interaction,
thumb reach, low bandwidth, slower devices and small screens — then scale up
to tablets, laptops, desktops and large screens. **[C]** Without sacrificing
SEO, accessibility, deep linking, browser navigation, shareability or desktop
usability.

**[P] Operational rules** (carried from v0.2 §8, unchanged and still
recommended):

- Mobile-first CSS authoring; `min-width` queries only.
- Single column by default; multi-column is an enhancement.
- Touch targets: WCAG 2.2 AA minimum 24×24 px; **44×44 px for primary
  actions** (call, directions, save, submit).
- Primary actions within thumb reach on phone layouts.
- Performance budget: LCP ≤ 2.5 s, INP ≤ 200 ms, CLS ≤ 0.1; ≤ 150 KB
  critical HTML+CSS; ≤ 100 KB JS on public pages.
- Progressive enhancement: search, filter, open profile and call must work
  with no JavaScript.
- Adopt app patterns that make the task faster (sticky profile action bar);
  reject patterns that merely imitate an app (bottom tab bar on web,
  pull-to-refresh, modal "screens" that break deep links).
- Breakpoints: base, 480, 768, 1024, 1280 — content-driven.

---

## 15. Advertising V1

### 15.1 Confirmed — **[C] NC-26**

Staff-managed. Products: sponsored business placement, category sponsorship,
homepage promotion, and other clearly defined fixed placements. Staff create
campaigns on behalf of businesses. **No auction. No CPC / CPM / CPA. No
self-service advertiser dashboard.** Organic ranking and paid placement stay
separate; paid placement must not silently modify organic relevance;
sponsored content is clearly labelled.

### 15.2 Recommended V1 shape — **[P]**

| Element | Recommendation |
| --- | --- |
| **Placement** | A named, finite slot: `search.top`, `category.sponsor`, `home.promoted`. Each has a position, a maximum count and eligibility rules |
| **Package** | A sellable bundle: placement + target (category and/or area) + duration + fixed price. 3–5 packages at launch |
| **Campaign** | Business + package + start/end date + state (`draft`, `scheduled`, `active`, `paused`, `ended`) + the operator who created it |
| **Pricing** | **Fixed price per period** (flat fee), not per impression or click. This is what "no CPC/CPM/CPA" means commercially: Bulbula sells *exposure for a period*, which is also easier to explain to a first-time advertiser |
| **Availability** | Hard inventory guard — a package cannot be sold twice into a full slot for an overlapping period. Essential with fixed placements |
| **Eligibility** | Only verified, complete, currently-published listings may be sponsored |
| **Labelling** | One consistent word (recommend "Sponsored"), visually distinct container, never interleaved ambiguously with organic results (v0.1 R-04) |
| **Caps** | A documented maximum per surface (recommend: top of search ≤ 2, category page ≤ 1, homepage ≤ 3) |
| **Measurement** | Impressions and clicks recorded, deduplicated and bot-filtered — **for reporting, not for pricing** |
| **Reporting** | Staff-generated report sent to the business; no login |
| **Billing** | Order + invoice + payment recorded by staff (v0.1 PR-9); no gateway |

### 15.3 Integrity — **[C] principle, [P] controls**

Organic ranking is computed independently of campaign state; a sponsored
listing appears in organic results only on its organic merits; the public
"how ranking works" page says so plainly.

**[P]** Because Bulbula both authors the listing data and sells the
placement (**D-39**), add: audit-logged listing edits with actor and reason;
objective, recorded ranking inputs; and a periodic internal comparison of
organic positions for sponsored versus non-sponsored businesses. Pricing on a
flat fee rather than clicks also removes the incentive to inflate traffic.

---

## 16. Reviews V1

**[C]** Review creation requires authentication. **[C]** There is no business
account in V1, so owner replies are a future capability.

**[P] Recommendations:**

| Question | Recommendation | Reasoning |
| --- | --- | --- |
| **One review per user per business or per branch?** | **Per business** in V1, with the originating `branch_id` recorded | Almost all V1 listings are single-branch; per-business matches user intent ("I used this business"), and keeping `branch_id` means a later split to per-branch needs no backfill |
| **Editing** | Allowed; edited reviews show an edited timestamp and re-enter moderation if the text changed | Honest and simple; prevents rating manipulation by silent edit |
| **Deletion** | Author may delete; soft delete; aggregate recalculates; moderation history retained | Satisfies data-subject rights without destroying audit trail |
| **Rating without text** | **D-34** — recommend allowing it, excluded from the displayed "review count" and weighted lower in ranking | Volume for a young directory, without letting empty ratings dominate |
| **Moderation** | Pre-publication for a new account's first reviews, post-publication thereafter; sentiment-neutral criteria; reasons recorded and communicated | Balance of speed and safety; sentiment-neutral criteria is the defence against the suppression concern in R-01 |
| **Reporting** | Authenticated users report reviews; guests report listings | §11 |
| **Business responses** | **Future** (NC-16). Interim: a business may ask staff to publish a response clearly labelled *"Response from the business, published by Bulbula"* | Honest labelling beats silence; avoids inventing a shadow account |
| **Anti-spam** | Per-account and per-IP rate limits, duplicate-text detection, velocity alerts, no links in review text, `rel="nofollow ugc"`, account age threshold | Cheap and effective; authentication already removes the bulk |
| **Rating calculation** | Bayesian-smoothed average with a global prior; display the distribution and the count | Prevents a single 5★ outranking 40 reviews at 4.6 |
| **Auditability** | Every state change (submitted, approved, rejected, edited, deleted, reported) recorded with actor, timestamp and reason | Required for disputes, and for R-01's "no suppression" standard |
| **Conflict of interest** | Staff and operators must not review listings they created or sold advertising to | New rule, follows from the company-managed model |

---

## 17. Business / Branch model

**[P] Proposed until approved** (per the owner's instruction). Recommendation
unchanged from v0.2: adopt `Business → Branch`, with exactly one Branch for a
single-location business.

### 17.1 Consequences, as requested

| Area | With explicit Branch | If Branch were omitted |
| --- | --- | --- |
| **Address** | Lives on Branch; naturally multi-valued later | Would need a migration and a backfill for the first multi-location business |
| **Coordinates** | On Branch — distance and nearby are branch facts | Ambiguous for multi-location |
| **Opening hours** | On Branch; per-branch divergence is free | Cannot express "this shop closes earlier" |
| **Reviews** | On Business, with `branch_id` recorded (§16) | Same in V1, but no path to per-branch later |
| **Analytics** | Location-bound events (directions, calls) on Branch; brand events on Business | Loses the ability to compare locations — exactly what a multi-branch advertiser would pay for |
| **Search** | Index per branch, deduplicate by business in results | Simpler, but "nearest Ethio-burger" becomes impossible |
| **SEO** | Canonical `/b/{business}`; branch URLs only when branches > 1; `LocalBusiness` per branch, `Organization` for the parent | Matches schema.org; avoids duplicate thin pages |
| **Future multi-location** | A data entry task | A schema migration touching every table that references a location |

### 17.2 Cost, stated fairly — **[P]**

The cost is one extra table, one extra join on the hot profile path, and the
discipline to hide the distinction in the operations console ("Add listing"
creates both rows). Under the company-managed model there is no owner-facing
form to confuse, so the usual objection does not apply. Residual open points
(**D-03**): whether single-branch businesses expose a branch URL (recommend
no), and whether per-branch ratings are displayed (recommend only above a
review threshold).

---

## 18. Categories

**[C]** A controlled Bulbula taxonomy. **[C]** It must not become an
uncontrolled user-generated taxonomy.

### 18.1 Must the PRD specify these? — direct answers

| Question | Must the PRD specify it? | Answer |
| --- | --- | --- |
| **Taxonomy depth** | **Yes** | Two levels: category → subcategory. Deeper is unmaintainable for a small team and dilutes per-page content for SEO |
| **Category governance** | **Yes** | Staff-only; a named owner approves additions; every category is a potential indexable page and is therefore an editorial decision |
| **Category creation process** | **Yes** | Request → check against existing categories and aliases → check evidence of ≥ 3 real listings → create with slug, labels and aliases → never silently rename a live slug |
| **Bilingual labels** | **Yes, as a requirement** | Every category carries an English canonical name and an Amharic label from day one, regardless of which UI language ships (§22) |
| **Aliases / synonyms** | **Yes, as a requirement** | A first-class alias table per category (spelling variants, Amharic terms, Latin transliterations, common misspellings), folded into the search document |
| **The actual category list** | **No** | The seeded list is a *content deliverable* produced by operations, not a PRD blocker. The PRD specifies the rules; the list follows |

That last row is what keeps D-06 off the blocker list (§29).

### 18.2 Cardinality and targeting — **[P]**

One **primary** category per listing (drives URL, breadcrumb and ranking)
plus up to three secondary categories; category stuffing dilutes relevance
(**[C] R-19**). Advertising targets the primary category only, so that
"category sponsorship" means something unambiguous commercially.

### 18.3 Shape — **[P]**

Curate roughly 100–200 categories for the launch area, using the established
public vocabulary (Google Business Profile's ~4,000-category taxonomy) as the
naming and shape reference rather than inventing names. Slugs are English,
ASCII, stable and never reused.

---

## 19. Locations

**[C]** The model must serve the current Bole Bulbula focus, future Addis
Ababa expansion and future Ethiopian expansion — without hard-coding a single
neighbourhood and without building a national geography database in V1.

### 19.1 Recommended hierarchy — **[P]**

```text
Country          Ethiopia
  └── City/Region      Addis Ababa
        └── Sub-city   Bole, Yeka, …  (11 since Lemi Kura, 2020 — R-20)
              └── Area  Bole Bulbula, Ayat, Summit …   ← user-facing unit
                    └── Landmark (optional, text + coords)
```

| Rule | Reason |
| --- | --- |
| **Area is the user-facing unit** for filters, URLs, `category × area` pages and ad targeting | It is how people in Addis actually navigate and search |
| Sub-city stored on every listing, secondary in the UI | Administrative correctness and later city-wide reporting |
| Woreda optional, operational only | Useful to staff; meaningless to customers |
| Areas curated with aliases ("Bulbula", "ቡልቡላ", "Bole Bulbula") | Informal names are the real search key |
| Area has a centre point and approximate radius, not polygons | Enough for distance sort; avoids geospatial infrastructure |
| Populate **one branch deep** in V1 | Shape is national; data is one neighbourhood |
| No code may reference a specific area | Expansion must be a data task — this is the concrete meaning of "do not hard-code to one neighbourhood" |

### 19.2 Outstanding fact to confirm — **[U] D-40**

Public sources place Bole Bulbula in **Bole** sub-city, but boundaries were
redrawn in 2020 and at least one source disagrees (**[C] R-22**). The
administrative parent and the *practical* launch boundary must be confirmed
locally before the location tree is seeded, because the coverage target in
the launch definition is expressed against that boundary.

---

## 20. Data residency and compliance (reframed)

**[C] NC-27.** Preferred architecture keeps personal data on appropriate
Ethiopian-hosted infrastructure where required. **[C]** Local storage does
not by itself establish compliance. **This document gives no legal advice**;
items below marked **⚖** require professional confirmation.

### 20.1 What minimization changed

The V1 listing process is now designed to produce mostly **business
information** (§6). That materially shrinks the personal-data footprint, but
it does not remove it:

```text
personal data still present in V1
 ├── customer accounts         email, provider subject, display name, sessions
 ├── reviews                   authored content tied to a person
 ├── permission records        the named person who agreed (internal only)
 ├── staff/operational records audit logs, who created and verified what
 ├── sole-trader contact data  business contact that is also a personal one
 └── analytics                 anything tied to an account or a stable identifier
```

### 20.2 Data-location map — **[P], the core recommendation**

Decide location **per data class**, not per system. This is what makes NC-27
implementable without abandoning useful foreign services:

| Data class | Contains personal data? | Recommended location |
| --- | --- | --- |
| Customer accounts, identities, sessions, OTP records | **Yes** | **Ethiopia-hosted primary store** |
| Reviews (content + authorship) | **Yes** | **Ethiopia-hosted primary store** |
| Permission records, audit logs, staff records | **Yes** | **Ethiopia-hosted primary store** |
| Listing data for registered companies | Generally no | Flexible; co-located with the above for simplicity |
| Listing contact points flagged `is_personal_contact` | **Yes** | With the primary store |
| Business media (premises, products, signage) | Usually no, if people are not identifiable | **May use foreign object storage / CDN** — this is what resolves most of conflict **X-01** |
| Aggregated, non-identifying analytics | No | Flexible |
| Email in transit (OTP, notifications) | **Yes — recipient address** | **⚖** Transfer to a foreign provider needs a transfer condition (§20.3) |

**[P]** The practical consequence: Bulbula can keep a foreign CDN and object
storage for photographs — provided the photo rule of §5.3 (avoid identifiable
individuals) is enforced — while the authoritative personal-data store sits
in Ethiopia. v0.2 treated X-01 as a single unresolved conflict; splitting by
data class largely dissolves it.

**[C] R-24:** local hosting is practically available — Addis Ababa has
carrier-neutral Tier III colocation (Wingu.Africa and Raxio ET1, both in the
ICT Park), Safaricom facilities, and Ethio Telecom's `telecloud` IaaS/PaaS
offering billed in Birr. **[U]** Their suitability, pricing, reliability and
operational maturity for a small PHP application are unverified and must be
assessed in the infrastructure phase (**D-42**).

### 20.3 Foreign processors V1 will have — **[P] inventory, ⚖ assessment**

Every one of these receives personal data and must appear in the privacy
notice and in any processor register:

| Processor | Receives | Why |
| --- | --- | --- |
| Google (Sign-In) | Account identifier, email | Confirmed auth provider |
| Transactional email provider | Email address, message content | NC-21 |
| Telegram | Mini App user identifiers | NC-23, and **L-12** if identity is adopted |
| Google Maps Platform | Approximate user location in map requests | Maps embed |
| CDN / object storage | IP addresses in request logs | Delivery |
| Analytics (if any third party) | Behavioural data | Prefer first-party to avoid this entirely |

**[C] R-26:** the Proclamation permits cross-border transfer only where the
ECA has determined the recipient jurisdiction adequate, **or** the data
subject gives explicit informed consent after being told the risks, **or**
the transfer is necessary, **or** the data comes from a public register.
Transfers of *sensitive* personal data need prior ECA approval.

**[P] Interpretation (not advice):** Google Sign-In and email delivery are
plausibly "necessary" for a service the user requested, and are additionally
disclosed and consented to at sign-up — but this is exactly the reasoning
that must be confirmed with counsel rather than assumed, and it should be
documented in a DPIA.

### 20.4 L-14 reframed — the biggest legal change this phase

v0.2 rated "publishing a business database without owner involvement" as a
High risk requiring counsel. **NC-17 changes the facts**: the business is
contacted, informed, and gives permission before publication, and the
permission is recorded.

**[P]** This moves Bulbula from "publisher of scraped data" toward "publisher
of first-party data supplied with permission" — a materially better posture
for accuracy disputes, removal requests and reputation. **⚖ It does not
dispose of the question**: permission from a business is not the same legal
construct as consent from a data subject, and where published contact data is
*also* personal data (§6.2), the lawful basis still has to be established.
The question for counsel is now narrower and more answerable.

### 20.5 Compliance register

| # | Item | Needs | Status |
| --- | --- | --- | --- |
| L-1 | Applicability of Proclamation 1321/2024 | ⚖ | Assume it applies |
| L-2 | Residency of personal data (Art. 21) | ⚖ + architecture | **Addressed in principle by §20.2; provider choice is D-42** |
| L-3 | **ECA registration** of controller/processor — certificate valid two years, renewable; requirements to be set by directive | ⚖ | **[C] R-26** that the duty exists; **[U]** whether the registration portal is operational |
| L-4 | DPO appointment | ⚖ | Open |
| L-5 | Lawful basis per purpose; unbundled, withdrawable consent | ⚖ + product | Design implication already in §9.1 |
| L-6 | Privacy notice + cookie/consent mechanism | Product | **V1 requirement** |
| L-7 | Data-subject rights: access, rectification, erasure, restriction, objection, portability | Product | **V1 requirement**; §6.2 flag makes erasure executable |
| L-8 | 72-hour breach notification to ECA and subjects | Operations | **V1 requirement** (runbook) |
| L-9 | Minors: minimum age, no marketing or profiling of minors | ⚖ + product | **D-46** |
| L-10 | Cross-border transfer conditions for Google and the email provider | ⚖ | §20.3 |
| L-11 | *(Retired — Apple private relay no longer applies)* | — | **Closed by NC-20** |
| L-12 | Telegram as processor | ⚖ | Open; tied to D-33 |
| L-13 | Verification evidence retention | ⚖ | **Reduced** — internal notes, not third-party documents |
| L-14 | Publishing business data | ⚖ | **Reframed and reduced** (§20.4) |
| L-15 | Review content liability and takedown | ⚖ | Open |
| L-16 | Advertising disclosure requirements | ⚖ | Labelling standard already adopted (R-04) |
| L-17 | Advertising invoices, VAT, tax records | ⚖ + finance | Open |
| L-18 | Photography of premises and people | ⚖ | **Mitigated** by the no-identifiable-people rule (§5.3) |
| L-19 | Google Maps Platform terms (caching, attribution) | Review | Open |
| L-20 | Trade licence / entity status to sell advertising | ⚖ + finance | Blocks revenue, not launch |
| L-21 | **NEW — Retention schedule per data class** (accounts, OTP records, reviews, permission records, audit logs, analytics) | ⚖ + product | **D-46**; required for the privacy notice |
| L-22 | **NEW — DPIA** covering the account system, reviews and cross-border transfers | ⚖ | Recommended before launch; §6.3 makes it largely transcription |

---

## 21. Operations model

**[C] NC-28.** Operations are central to the V1 product.

### 21.1 The lifecycle

```text
business discovery → data collection → permission → verification
   → listing creation → quality review → publication
      → monitoring → correction → re-verification
```

**[P]** Two states deserve explicit modelling because they are where work
actually stalls: **awaiting permission** (contacted, not yet agreed) and
**awaiting quality review** (created, not yet published). A listing pipeline
that cannot show how many records sit in each state cannot be managed.

### 21.2 Roles — **[P]**

The company-managed model makes **operator** the primary product user, which
strengthens the case for splitting **D-14** into `operator` (create, edit,
collect, verify) and `administrator` (publish policy changes, taxonomy,
campaigns, user management, audit access). Quality review should be
performed by someone other than the creating operator wherever team size
allows.

### 21.3 Metrics — **[P]**

| Metric | Why it matters | Target |
| --- | --- | --- |
| **Listings created per staff-hour** | Sets the realistic scope ceiling | From the pilot |
| **Permission rate** (approached → agreed) | New in V1; directly limits coverage | From the pilot |
| **Listing completeness score** | Quality floor and a ranking input | Define in PRD |
| **Verification completion rate** | Share of published listings verified within policy | Define in PRD |
| **Correction turnaround** | Trust-critical: how fast a wrong fact gets fixed | Target ≤ 72 h |
| **Stale listings** | Published and not re-verified within the interval | Trend to zero |
| **Coverage** | Share of known businesses in the launch area that are listed | **Launch criterion** |
| **Duplicate rate** | Data-quality canary | < 1 % |
| **Zero-result searches** | Where coverage or aliases are missing | Weekly review |

### 21.4 The pilot — **[P] design, [C] permitted**

Roughly 20 businesses, measured end to end: approach → permission →
collection → verification → creation → quality review → publish. Record
per business: time spent at each step, permission outcome, fields obtainable
on the first visit, photos obtained, coordinate accuracy, and whether a
second contact was required.

**[P]** The pilot produces four things the PRD needs and nothing else can
supply: a defensible listings-per-staff-hour figure, the permission rate, the
*actual* required field list (as opposed to the wished-for one), and
~20 real listings of seed data. **[C]** The launch threshold is not to be
set automatically — the pilot informs the owner's decision (**D-30**), it
does not make it.

---

## 22. Language strategy

**[C]** Do not silently choose. A recommendation follows the evaluation.

### 22.1 Evaluation

| Dimension | **A — English-first, bilingual-ready** | **B — Amharic-first, bilingual-ready** | **C — Bilingual from V1** |
| --- | --- | --- | --- |
| **Customer behaviour** | Addis users commonly use English for business search; signage, brands and categories are often English | Matches everyday spoken language; stronger for less English-literate users | Serves both, forces a choice at first visit |
| **Search** | Amharic *content* and aliases still searchable from day one (the key point) | Requires Ethiopic input handling to be excellent on day one | Both; needs transliteration matching and script-agnostic ranking (v0.1 R-09) |
| **Taxonomy** | English canonical + Amharic labels stored | Amharic canonical + English labels | Two complete label sets maintained in parallel |
| **Typography** | Latin fonts only on the critical path | Ethiopic webfont on the critical path — the subsets are heavy and directly cost LCP | Both fonts, worst-case payload |
| **URL strategy** | Stable ASCII slugs; no locale prefix needed yet | ASCII slugs still required for SEO, so URLs diverge from the UI language | Locale-prefixed URLs (`/am/…`) and `hreflang` from day one |
| **SEO** | One clean indexable surface; strongest short-term | Narrower reach; diaspora and English queries underserved | Strongest long-term, but duplicate-content and `hreflang` risk if content is thin |
| **Telegram** | Mini App inherits Telegram's language setting; English is safe | Same mechanism | Natural fit — Telegram exposes the user's language, so switching is free |
| **Mobile UX** | Lightest payload, fastest on slow devices | Heavier fonts | Heaviest; also a language switcher competing for screen space |
| **Operational complexity** | Lowest: one UI to write, one content workflow | Medium: translation of all UI; Amharic copywriting capability needed | **Highest: every listing, category, page and notification maintained twice, forever** — and the content treadmill is already the binding constraint (§21) |

### 22.2 Recommendation — **[P] Option A**

English-first, bilingual-ready, with three non-negotiables that keep B or C
cheap later:

1. **No hard-coded user-visible strings.** All copy externalised from the
   first line of view code.
2. **Amharic stored from the first migration** for every name-like field:
   business names, category labels, area names.
3. **Alias tables accept Ethiopic script**, so Amharic *search* works at
   launch even though the *interface* is English.

Add one commitment that makes the recommendation honest: **ship the Amharic
interface when evidence justifies it** — specifically when Amharic queries
or Ethiopic-script interactions exceed a threshold the owner sets, or when a
content workflow exists to maintain it. Afaan Oromo remains **Future**,
relevant only on expansion beyond Addis (**[C] R-21**).

**[P] Why not C:** bilingual-from-V1 is the right *end state* but it doubles
the per-listing content cost at exactly the moment the operations loop is the
scarce resource. Option A reaches the same end state without betting V1 on
double the throughput.

---

## 23. Design research preparation

**[C]** The visual system is not approved. Brand direction: primary orange,
secondary blue, light mode white-dominant, logo not finalized. **[C]** Do not
invent a permanent logo. The following is the scope for a later UX/UI phase —
**nothing here is a design decision**.

| Area | What the research phase must produce | Known constraint to carry in |
| --- | --- | --- |
| **Design language** | A short written design intent (plain, trustworthy, local, fast) and three reference directions to choose between | Must read as a *directory*, not a social app |
| **Typography** | A Latin family with a matching **Ethiopic** companion, with subset and loading strategy | Ethiopic subsets are heavy; font loading must not break the LCP budget (§14) |
| **Colour tokens** | Semantic tokens (surface, text, muted, border, brand, accent, success, warning, danger, sponsored) mapped from the orange/blue brand | **Orange on white rarely meets 4.5:1** — a darker "orange-ink" token for text and a lighter orange for fills must be separate tokens. This is the most common failure mode for orange brands |
| **Spacing / radii / elevation** | A 4 px-based scale, two or three radii, minimal elevation | Mobile-first density; shadows cost paint time on low-end devices |
| **Buttons** | Primary, secondary, tertiary, destructive; 44 px primary touch target; loading and disabled states | WCAG 2.2 target size |
| **Cards** | The business card is the single most reused component — must work in search results, category pages, favourites, sponsored slots and the Mini App | One component, one data shape |
| **Navigation** | Mobile nav, desktop nav, breadcrumbs, and how the Telegram surface differs | No bottom tab bar on web (§14) |
| **Search UI** | Input, autocomplete, recent searches, empty and zero-result states, keyboard behaviour | Zero-result state is a product surface, not an error |
| **Filters** | Mobile filter sheet vs desktop rail; chip patterns; applied-filter clarity; reset | Must work without JavaScript at a basic level |
| **Business profile** | Section order, hours display, contact action bar, gallery, reviews, map placement | The action bar is the page's purpose |
| **Sponsored presentation** | Label wording, container treatment, placement separation | Must satisfy "clearly distinguishable" (R-04) and never mimic organic rows exactly |
| **Mobile interaction** | Thumb zones, sheets, gestures with non-gesture alternatives, haptics in Telegram | WCAG 2.5.7 dragging alternatives |
| **Desktop adaptation** | Wider arrangement of the same components; two-column results with a filter rail | Not a different design |
| **Accessibility** | Contrast matrix for every token pair, focus visibility, keyboard paths, labels, Amharic text rendering | WCAG 2.2 AA as the stated bar (v0.1 R-11) |
| **Dark mode** | A decision (**D-19**) plus the token work to support it | The **Telegram surface is dark for many users regardless** — dark-capable semantic tokens are required even if the website ships light-only |

**[P] Recommended sequencing:** tokens and the business card first, then
search and filters, then the profile page, then desktop. The business card
and the profile page carry ~80 % of the product's visual surface.

---

## 24. Documentation architecture

**[C] NC-29.** Status and versioning required; the system must cover
product, business, technical, UX/UI, platforms, security, privacy, quality,
operations and decisions.

### 24.1 Recommended hierarchy — **[P]**

```text
docs/
├── 00-discovery/              DISCOVERY (frozen history)
│   ├── project-understanding-v0.1.md
│   ├── research-notes-v0.1.md
│   ├── product-decision-brief-v0.2.md     (Superseded)
│   └── product-decision-brief-v0.3.md     (this document)
├── 10-product/                PRODUCT
│   ├── prd-v1.0.md
│   ├── scope-v1.md
│   ├── interaction-permissions.md
│   ├── review-policy.md
│   ├── listing-operations.md
│   ├── glossary.md
│   └── taxonomy/{categories.md,locations.md}
├── 15-business/               BUSINESS
│   ├── business-model.md
│   ├── advertising-products.md
│   ├── pricing.md
│   └── market-notes.md
├── 20-ux-ui/                  UX / UI
│   ├── design-principles.md
│   ├── design-tokens.md
│   ├── components.md
│   ├── mobile-first-guidelines.md
│   └── accessibility.md
├── 30-technical/              TECHNICAL
│   ├── architecture.md            (relocated from docs/)
│   ├── data-model.md
│   ├── api-contract.md
│   ├── search-architecture.md
│   ├── deployment.md              (relocated from docs/)
│   └── trd-v1.0.md
├── 35-platforms/              PLATFORMS (one per client surface)
│   ├── web.md
│   ├── telegram-mini-app.md
│   └── flutter.md                 (later)
├── 40-operations/             OPERATIONS
│   ├── runbook.md
│   ├── data-quality-sop.md
│   ├── permission-sop.md
│   ├── moderation-sop.md
│   └── incident-response.md
├── 45-quality/                QUALITY
│   ├── quality-gates.md
│   ├── test-strategy.md
│   └── definition-of-done.md
├── 50-security/               SECURITY
│   ├── threat-model.md
│   ├── auth-architecture.md
│   └── secrets-management.md
├── 55-privacy/                PRIVACY
│   ├── data-inventory.md          (the §6 classification, living)
│   ├── privacy-notice.md
│   ├── retention-schedule.md
│   ├── dpia.md
│   └── compliance-register.md     (the §20.5 register, living)
└── 60-decisions/              DECISIONS
    ├── decision-register.md       (D-xx, the single source of truth)
    └── adr/0001-....md            (one file per architectural decision)
```

### 24.2 Document control — **[P]**

Every document opens with the same control block:

```text
| Document | <title> |
| Version  | v1.0 |
| Status   | Draft | Review | Approved | Superseded |
| Date     | YYYY-MM-DD |
| Owner    | <role> |
| Supersedes / Superseded by | <link> |
```

| Rule | Detail |
| --- | --- |
| **Status lifecycle** | `Draft → Review → Approved`; later `Superseded` by a named successor. A superseded document is **never deleted or edited** |
| **Versioning** | `vMAJOR.MINOR`. MINOR = clarification or additive change. MAJOR = a change that alters an approved decision |
| **Decisions live in one place** | `60-decisions/decision-register.md`. Every other document *references* D-xx and must not restate the decision — this is what stops two documents disagreeing |
| **Approval** | Only the owner moves a document to `Approved` (**D-29**) |
| **Traceability** | PRD requirements cite the D-xx that justifies them; ADRs cite the requirement they serve |
| **Discovery is frozen** | `00-discovery/` is historical record; corrections happen in successor documents, exactly as v0.2 → v0.3 did |

---

## 25. AI context system

**[C] NC-30.** Defined now, **not created in this phase**.

### 25.1 Purpose split

```text
docs/        = the truth, written for humans, versioned and approved
.ai/         = a compressed, always-current briefing that points at the truth
```

`.ai/` files must be short enough to paste into a context window, must never
contain a decision that is not in the register, and must be regenerable from
`docs/`. If the two ever disagree, `docs/` wins.

### 25.2 File-by-file

| File | What belongs in it | What must stay in formal docs |
| --- | --- | --- |
| **`ai-workflow-rules.md`** | How an AI assistant must behave on this repo: read these files first; never invent a requirement; ask when a decision is missing; one task at a time; always run the quality gates; never weaken a gate; branch and PR conventions; never commit secrets | The actual CI configuration and quality thresholds (`45-quality/`) |
| **`progress-tracker.md`** | What is done, in progress, and next, at phase granularity, with dates | Detailed planning, estimates, roadmap (`10-product/`) |
| **`ui-context.md`** | Token names, component inventory, mobile-first rules, the surface-adaptation rule, the "no app imitation" rule | Full design system, specs, accessibility matrix (`20-ux-ui/`) |
| **`code-standards.md`** | PHP style, strict types, naming, directory layout, test conventions, "no framework" rule, approved library list and the bar for adding one | Rationale and full tooling configuration (`45-quality/`) |
| **`architecture.md`** | Modular monolith, module boundaries, layering, the one-write-path rule, the Telegram adapter boundary, the shared-frontend rule | Full architecture and ADRs (`30-technical/`, `60-decisions/`) |
| **`project-overview.md`** | What Bulbula is, who it serves, the V1 boundary, the explicit not-V1 list | Vision, market, personas (`10-product/`, `15-business/`) |
| **`decision-log.md`** | A compact mirror of resolved decisions: ID, one-line resolution, date. **Resolved only** | The full register with rationale and alternatives (`60-decisions/`) |
| **`current-state.md`** | What exists in the codebase *right now*, what is deliberately absent, the current branch and phase, and what must not be touched | Deployment state and runbooks (`40-operations/`) |

### 25.3 How each failure mode is prevented — **[P]**

| Failure mode | Primary defence |
| --- | --- |
| **Hallucinated requirements** | `project-overview.md` states the V1 boundary and the not-V1 list; `ai-workflow-rules.md` requires asking instead of inventing; PRD requirements carry D-xx citations |
| **Architecture drift** | `architecture.md` states boundaries as rules, and `code-standards.md` lists the approved libraries; architecture tests enforce the entry-point and layering rules |
| **UI inconsistency** | `ui-context.md` gives token and component names, so generated code reuses rather than reinvents |
| **Terminology inconsistency** | A single binding glossary, mirrored in `.ai/` and enforced in review: *business, branch, listing, category, subcategory, area, sub-city, review, rating, save, campaign, placement, package, operator, administrator, verification, correction, permission record* |
| **Forgotten decisions** | `decision-log.md` is read first in every session; the register is the source of truth |
| **Undocumented assumptions** | The certainty tags **[C] [SI] [P] [U] [X]** are mandatory in all product documents; an assistant must tag anything it asserts |
| **Scope creep** | The explicit not-V1 list plus `current-state.md`'s "what must not be touched" |
| **Accidental framework introduction** | A hard rule in both `code-standards.md` and `architecture.md`, plus a composer-dependency review step in the PR checklist — the single most frequently violated instruction class |

**[P]** Two practices matter more than the file contents: `current-state.md`
must be updated at the **end of every working session** (a stale state file
is worse than none), and nothing may enter `.ai/` that has not first been
decided in `docs/`.

---

## 26. Decision reclassification (complete register)

**Classes:** **A** must decide before PRD · **B** before UX/data model ·
**C** during implementation · **D** future/defer · **E** resolved.

Every decision from v0.1 and v0.2 appears exactly once.

### 26.1 Class E — resolved (no longer open)

**Owner-mandated resolutions (NC-1…NC-30):**

| Item | Resolution |
| --- | --- |
| V1 listing ownership | **Bulbula-company-managed** |
| Owner-created listings | **Future**, admin-approved when introduced |
| Owner claims | **Future**, admin-approved when introduced |
| First launch surfaces | **Web + Telegram Mini App** |
| Flutter | **Later client**, no shared frontend code |
| Guest browsing | **Confirmed** |
| Google authentication | **Confirmed V1** |
| Email authentication (verification + OTP, no passwords) | **Confirmed V1** |
| Apple Sign In | **Not V1** |
| Business-data-first collection with permission | **Confirmed V1** |
| Minimize unnecessary personal data | **Confirmed principle** |
| Telegram as a first-class client surface, one backend | **Confirmed** |
| Shared frontend with runtime adaptation | **Confirmed direction** |
| Mobile-first web | **Confirmed** |
| Advertising: staff-managed fixed placements, no auction/CPC/CPM/CPA, no self-service | **Confirmed** |
| Personal data preferably hosted in Ethiopia | **Confirmed preference** |
| Documentation status + versioning | **Confirmed requirement** |

**Numbered decisions now closed:**

| ID | Resolution |
| --- | --- |
| **D-01** V1 scope | **Resolved** by NC-25; item-level residue tracked in §4.5 |
| **D-02** pre-seeded vs owner-created | **Resolved** — company-managed (v0.2) |
| **D-05** discovery surfaces in V1 | **Resolved** by the owner's enumerated list (§4.1) |
| **D-15** client sequencing | **Resolved** for launch; Flutter timing → Class D |
| **D-24** notification channel | **Resolved** — email is the V1 channel and a product capability; *provider* → D-41 |
| **D-29** documentation versioning and approval authority | **Resolved** by NC-29; hierarchy in §24 approved together with this brief |
| **D-32** Apple Developer Program dependency | **Closed** — Apple is not in V1 |

### 26.2 Class A — must decide before the PRD (6)

| # | ID(s) | Decision | What the owner must do |
| --- | --- | --- | --- |
| 1 | **D-03** | Business → Branch canonical model | Approve or reject §17 |
| 2 | **D-10** | V1 advertising products, inventory caps, label wording | Approve or amend §15.2 |
| 3 | **D-12 + D-34** | Review policy: eligibility, editing, deletion, rating-only, moderation, responses | Approve or amend §16 |
| 4 | **D-18** | Language strategy | Choose A, B or C (§22 recommends **A**) |
| 5 | **D-30** *(inputs: **D-31** capacity, **D-40** area boundary)* | Definition of launch: coverage, quality and readiness bar | Run the pilot (§21.4), confirm the Bole Bulbula boundary, then set the numbers |
| 6 | **D-46** *(subset of D-22)* | Minimum account age, retention schedule per data class, and confirmation of the §20.2 data-location policy and transfer basis | Engage counsel |

### 26.3 Class B — before UX design or the data model (20)

| ID | Decision | Recommendation available |
| --- | --- | --- |
| **D-04** | Opening-hours model (regular, split shifts, exceptions, 24 h) | v0.1 §11.3 |
| **D-06** | Category taxonomy policy: depth, governance, creation process, bilingual labels, aliases | §18 — the *list* is an operations deliverable, not a PRD blocker |
| **D-08** | Verification rules, tiers, re-verification interval (internal only) | §5.1 |
| **D-09** | Profile-completeness definition and ranking weight | v0.1 PR-3 |
| **D-13** | Identity-linking rules | §10.2 |
| **D-14** | Admin role split (`operator` + `administrator`) | §21.2 — upgraded from Class C |
| **D-16** | Frontend JS approach (htmx + Alpine vs vanilla modules; SPA rejected) | v0.2 §7.6 |
| **D-17** | View/template layer | Must support one view layer, two surfaces |
| **D-19** | Dark mode on the website | §13.3 — dark-capable tokens required regardless |
| **D-21** | Google Maps key, billing owner, embed strategy, fallback | v0.1 §11.4 |
| **D-25** | Media storage provider, limits, formats | §20.2 permits foreign object storage for non-personal media |
| **D-27** | Analytics granularity, retention, raw-event policy | Ties to D-46 |
| **D-33** | Telegram identity for the Mini App | §10.4 — evaluate with the prototype |
| **D-35** | Guest structured edit suggestions | §11.1 — recommend guests report, accounts suggest |
| **D-38** | Mini App navigation model (full page loads vs fragment swaps) | §12.3 |
| **D-39** | Ad/editorial integrity controls | §15.3 |
| **D-42** | Which data classes must be Ethiopia-hosted, and the hosting approach | §20.2 |
| **D-43** | Permission record contents and retention | §5.4 |
| **D-44** | Services / products / pricing representation | §5.3 — absorbs the old **D-07** |
| **D-45** | Staff/admin authentication strength (TOTP second factor) | §8.3 |

### 26.4 Class C — during implementation (7)

**D-11** billing mechanics (staff-entered invoices) · **D-20** confirmed host
resource limits and MariaDB tuning · **D-23** production config and secret
custody · **D-26** coverage/MSI gate scope as the domain grows · **D-28**
temporary brand-mark policy · **D-41** transactional email provider selection
(criteria fixed in §9.3) · **D-42b** the specific Ethiopian hosting provider
(policy is Class B; vendor is Class C).

### 26.5 Class D — deferred (10)

**D-15r** whether Flutter happens within year one · **D-36** review photos ·
**D-37** "helpful" voting · **D-47** Sign in with Apple for the iOS client ·
business accounts and owner submissions · owner review replies ·
self-service advertising and billing · messaging/leads · multi-city
expansion · Afaan Oromo and further languages.

---

## 27. Decisions that can safely wait for the TRD or implementation

Stated explicitly so they are not mistaken for blockers:

| Area | Why it can wait |
| --- | --- |
| Email provider, hosting vendor, CDN vendor | Requirements and selection criteria are fixed; vendor choice changes no product requirement |
| Template/view library, htmx vs vanilla | An implementation technique behind a stable requirement ("server-rendered, progressively enhanced, shared across surfaces") |
| Search internals (indexing, scoring weights) | The PRD specifies behaviour and quality bars; MariaDB tuning is technical |
| Session and token mechanics | The PRD specifies who may do what; mechanics are security design |
| Media pipeline formats and derivative sizes | Bounded by the performance budget |
| Caching strategy, rate-limit implementation | Non-functional requirements already stated |
| Audit log storage format | Requirement is "every privileged action is attributable" |
| Deployment topology, CI thresholds | Already governed by existing quality gates |

---

## 28. Class A at a glance

Six decisions, down from thirteen in v0.2. Four of the six are an
**approval** of recommendations contained in this document (rows 1–3 and the
taxonomy policy in §18); one is a **choice** between three documented options
(language); one requires **external input** (counsel); and one requires
**measurement** (the pilot).

---

## 29. The true PRD blockers

The smallest set that genuinely prevents PRD v1.0 from being written and
finalized:

| # | Blocker | Why it blocks | How it closes | Effort |
| --- | --- | --- | --- | --- |
| **1** | **Approval of this brief** (D-03, D-06 policy, D-10, D-12/D-34, interaction matrix) | The PRD's functional requirements would otherwise be built on unapproved proposals, and a later reversal would invalidate written requirements | One review session; approve, amend or reject each recommendation | Hours |
| **2** | **Language strategy (D-18)** | Determines every string, URL, font, taxonomy label and content workflow in the PRD | Owner chooses Option A, B or C | Minutes, once considered |
| **3** | **Legal minima (D-46)** — minimum account age, retention per data class, confirmation of the data-location policy and transfer basis | These appear directly as requirements; writing them wrongly means rewriting the privacy, accounts and analytics sections | Counsel engagement; can run in parallel with drafting | 1–3 weeks external |
| **4** | **Launch definition (D-30)**, informed by the capacity pilot (D-31) and the area boundary (D-40) | The PRD's launch criteria section cannot be written without a coverage and quality bar, and the bar must be grounded in measured throughput | Run the ~20-business pilot, confirm the boundary, owner sets the numbers | ~1 week |

**Nothing else blocks.** Everything in Class B is answerable during UX and
data-model work, and Class C during implementation.

**[P] Sequencing note:** blockers 1, 2 and 4 are entirely within Bulbula's
control and can all be closed within about a week. Blocker 3 is external; the
PRD can be drafted with its privacy requirements marked *pending counsel*,
but should not be moved to `Approved` until counsel responds.

---

## 30. Risk register update

| ID | Risk | Change this phase | Current rating |
| --- | --- | --- | --- |
| **RK-1** | Operations capacity ceiling | **Worse**: permission-based collection costs more per listing than desk research | **High / High** — pilot is the mitigation |
| **RK-2** | Data decay | **Better**: every listing now has a named contact | High / Medium |
| **RK-3** | Legal exposure from publishing business data (L-14) | **Much better**: permission is obtained and recorded | Medium / Low–Medium |
| **RK-4** | Cold start | Unchanged | High / Medium |
| **RK-5** | Apple login undeliverable | **Retired** — Apple is not V1 | — |
| **RK-6** | Email undeliverable from shared hosting | **Downgraded to a procurement item** — but now load-bearing: if OTP mail fails, **login itself fails** | Medium / Medium |
| **RK-7** | Telegram WebView quirks break the shared frontend | Unchanged — prototype still outstanding | Medium / Medium |
| **RK-8** | Shared components accumulate surface conditionals | Unchanged — rule in §13.4 | Medium / Medium |
| **RK-9** | Ad/editorial conflict of interest | **Better**: flat-fee pricing removes the traffic-inflation incentive | Medium / Low–Medium |
| **RK-10** | Poor ranking quality with sparse data | Unchanged | Medium / High |
| **RK-11** | Review spam | Unchanged | Medium / Medium |
| **RK-12** | Shared-hosting resource limits | Unchanged | Medium / Medium |
| **RK-13** | Data residency invalidates infrastructure choices late | **Better**: §20.2 splits by data class; local capacity confirmed to exist (R-24) | Medium / Medium |
| **RK-14** | Scope creep back toward a two-sided platform | **Better**: the not-V1 list is now owner-stated | Medium / Low |
| **RK-15** | Thin `category × area` SEO pages | Unchanged — growth-gate categories | Medium / Medium |
| **RK-16** | Key-person dependency | Unchanged — `.ai/` and docs partially mitigate | High / Medium |
| **RK-17** | Brand/logo not finalized delays assets | Unchanged | Low / Medium |
| **RK-18** | Google Maps billing surprise | Unchanged | Low / Medium |
| **RK-19** | **NEW — Staff account compromise.** Email OTP alone protects a console that can publish listings and approve campaigns | New, from removing passwords | **High / Low–Medium** — mitigation is D-45 (TOTP for staff) |
| **RK-20** | **NEW — Permission refusal suppresses coverage.** Businesses may decline, leaving visible gaps in the launch area | New, from NC-17 | Medium / Medium — measure in the pilot; define how a refused business is represented, if at all |

**[P]** RK-20 raises a product question worth an explicit answer in the PRD:
**if a business refuses permission, does Bulbula show nothing at all?** A
directory with holes in a small area is noticeably incomplete. Options are to
show nothing, to show a minimal name-and-category stub, or to record the
refusal internally only. This is a legal *and* product question and should go
to counsel alongside D-46.

---

## 31. Recommended next phase

**Phase 3 — Decision closure and PRD v1.0.** Not started; nothing beyond this
document should begin until the owner reviews it.

| Step | Activity | Output |
| --- | --- | --- |
| 1 | **Owner review session** on §26.2 (six Class A decisions) | Approved decisions; seeds `60-decisions/decision-register.md` |
| 2 | **Operations pilot**, ~20 businesses, timed end to end | Listings-per-staff-hour, permission rate, the real field list, 20 seed listings |
| 3 | **Confirm the Bole Bulbula boundary** (D-40) | Seed data for the location tree |
| 4 | **Engage counsel** on D-46 and the §20.5 register | Retention schedule, minimum age, transfer basis, DPIA outline |
| 5 | **Telegram prototype** (one throwaway page: theme mapping, back button, `initData` round-trip, low-end Android) | De-risks RK-7 and informs D-33 and D-38 |
| 6 | **Write PRD v1.0** | `docs/10-product/prd-v1.0.md` |
| 7 | *Then* the TRD, data model, `.ai/` files, implementation | Later phases |

Steps 2, 3, 4 and 5 can all run in parallel with step 1.

---

## 32. PRD Readiness

```text
NOT READY
```

**Four decisions block PRD v1.0:**

1. **Approval of this brief** — the V1 business/branch model, advertising
   products, review policy, taxonomy policy and interaction matrix are all
   **[P]** and must become **[C]**.
2. **Language strategy (D-18)** — Option A, B or C. §22 recommends **A**.
3. **Legal minima (D-46)** — minimum account age, retention schedule, and
   confirmation of the data-location and cross-border transfer basis.
   Requires counsel.
4. **Launch definition (D-30)** — informed by the ~20-business capacity pilot
   (D-31) and confirmation of the launch-area boundary (D-40).

**Once those four are closed:**

```text
PRD v1.0 can be generated from:
- approved decisions        (60-decisions/decision-register.md, seeded from §26)
- project understanding     (project-understanding-v0.1.md)
- research findings         (research-notes-v0.1.md + R-16…R-26)
- product decision brief    (this document, v0.3, Approved)
```

**The PRD is not generated in this task.**

---

## Appendix A — New research findings (R-24 … R-26)

Format: **Finding** (fact, **[C]**) · **Why it matters** · **Applies to
Bulbula** · **Interpretation** (**[P]**, clearly separated) · **Source**.

### R-24 — Ethiopia has real local data-centre and cloud capacity

- **Finding [C]:** Addis Ababa hosts carrier-neutral Tier III colocation
  facilities — Wingu.Africa's facility in the ICT Park (built to Tier III,
  up to about 10 MW, carrier-neutral) and Raxio's ET1, also Tier III
  certified. Safaricom Ethiopia operates data-centre capacity (ICT Park and
  Kality), and Ethio Telecom offers a cloud service ("telecloud") providing
  IaaS, PaaS and SaaS built on Huawei Cloud Stack, billed in Birr, positioned
  as a national sovereign cloud with data held in local data centres.
  Industry trackers list four to seven facilities in the country.
- **Why it matters:** The Proclamation's residency rule (R-05, Art. 21) is
  only actionable if local hosting exists at a usable quality and price.
- **Applies to Bulbula:** Directly — D-42 and the data-location map in §20.2.
- **Interpretation [P]:** Residency is achievable without abandoning the
  product's architecture: keep the authoritative personal-data store in
  Ethiopia (colocation or local cloud) and continue using foreign object
  storage and CDN for non-personal business media. Suitability for a small
  PHP application — pricing, support, uptime history, connectivity, backup
  options — is unverified and must be assessed before committing.
- **Source:** Ethiopian data-centre market reporting; Wingu.Africa and Raxio
  facility descriptions; Ethio Telecom telecloud announcements; Huawei Cloud
  Stack case study; colocation market trackers.

### R-25 — Email OTP is acceptable for consumer sign-in, but not as a second factor

- **Finding [C]:** NIST SP 800-63B states that email **shall not** be used
  for out-of-band *authentication*, because the channel is not bound to a
  device and can be intercepted or rerouted; it explicitly treats **email
  address validation and account-recovery codes as a separate, permitted
  case** (recovery codes by email up to 24 hours). The same guidance requires
  verifiers to throttle failed attempts and enforce single-use codes.
  OWASP notes a 6-digit code has only ~1,000,000 possibilities and
  recommends longer codes where usability allows. Production practice (e.g.
  Auth0's passwordless email) is on the order of 3-minute validity and three
  failed attempts; common guidance is 6–8 digit codes, 5–10 minute expiry,
  3–5 failed attempts, 3–5 code requests per address per hour, hashed
  storage, and binding the code to the requesting session.
- **Why it matters:** Email OTP is now Bulbula's confirmed V1 authentication
  method, and the honest security characterisation must be on record.
- **Applies to Bulbula:** §8.3 controls, and the staff-account gap (D-45).
- **Interpretation [P]:** For a consumer directory, email OTP as the
  *primary* sign-in factor is a reasonable, widely-used choice and is not the
  case NIST prohibits. But it must not later be presented as "two-factor
  authentication", and it is insufficient on its own for operations-console
  accounts that can publish content and approve campaigns — those need a
  TOTP second factor.
- **Source:** NIST SP 800-63B guidance summaries, OWASP Multifactor
  Authentication Cheat Sheet, Auth0 passwordless documentation, and
  practitioner analyses of email OTP expiry and rate limiting.

### R-26 — The Proclamation protects natural persons, and its machinery is still being built

- **Finding [C]:** Proclamation 1321/2024 protects **natural persons only —
  not legal persons**. Controllers *and* processors must register with the
  Ethiopian Communications Authority before processing; registration
  certificates are valid for two years and renewable, and the ECA may set
  registration requirements by directive. Cross-border transfer is permitted
  only where the ECA has determined the destination adequate, the data
  subject has given explicit informed consent after being told the risks, the
  transfer is necessary, or the data comes from a public register; transfer
  to jurisdictions without adequate protection is otherwise prohibited, and
  **sensitive** personal data requires prior ECA approval. Personal data
  collected locally must be stored on a server or data centre located in
  Ethiopia, and the ECA may designate categories of "critical personal data"
  that may only be processed locally. Breach notification to the ECA is
  within 72 hours. As of early 2025, commentary reported **no public
  enforcement actions or guidelines yet**, a registration portal previewed
  but not live, and four implementing directives still forthcoming.
- **Why it matters:** It determines how Bulbula classifies listing data,
  where personal data lives, and what obligations attach to Google, Telegram
  and the email provider.
- **Applies to Bulbula:** §6 classification, §20 compliance register.
- **Interpretation [P]:** Three practical conclusions. First, data about a
  *registered company* is generally outside the Proclamation, which supports
  the business-data-first model — but a sole trader's name or personal mobile
  is squarely inside it, which is why §6.2's `is_personal_contact` flag
  matters. Second, the registration duty is real even though the mechanism is
  immature; the current operational status of the ECA register must be
  checked at the time of launch rather than assumed from this note. Third,
  every foreign processor in §20.3 needs a transfer basis identified and
  documented, ideally in a DPIA. **None of this is legal advice; all of it
  needs confirmation by counsel.**
- **Source:** Full text of Proclamation No. 1321/2024 (Arts. 19–21, 33–35);
  Ethiopian legal-practice commentaries and business compliance guides;
  Ethiopian data-protection Q&A analyses.

---

## Appendix B — Document control and version history

| Version | Date | Status | Summary |
| --- | --- | --- | --- |
| v0.1 | 2026-10 | Approved as historical record | `project-understanding-v0.1.md` — 37-section discovery report + research notes R-01…R-15 |
| v0.2 | 2026-10-07 | **Superseded by v0.3** | First decision brief: company-managed listings, V1 scope, shared frontend validation, D-01…D-40 classified, R-16…R-23 |
| **v0.3** | **2026-10-07** | **Draft — awaiting owner approval** | Permission-based collection, data minimization and classification, Google + email OTP (Apple removed), email as a product capability, Telegram as a first-class surface, staff-managed advertising, full reclassification, four PRD blockers, R-24…R-26 |

**Retention rule:** v0.1 and v0.2 are historical records and are never
edited. Corrections appear only in successor documents — §3 of this document
is the authoritative list of what in v0.2 is obsolete.

**On approval of this document:** its status becomes `Approved`, the Class A
decisions in §26.2 are recorded in `docs/60-decisions/decision-register.md`,
and PRD v1.0 drafting may begin subject to §32.
