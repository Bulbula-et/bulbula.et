# User Flows

| | |
| --- | --- |
| **Document** | User Flows — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** End-to-end flows for Guests, Customers and Staff, each with
its entry points, preconditions, steps, system responses and every failure
path. This is the document an implementer reads to know what happens when
things go wrong.

**Template.** Every flow states: *Entry points · Preconditions · User goal ·
Steps · System response · Loading · Success · Empty · Validation failure ·
Permission failure · Recovery · Exit points.*

**Terminology** is fixed by [`../10-product/glossary.md`](../10-product/glossary.md).

---

## 0. Rules that apply to every flow

| ID | Rule | Source |
| --- | --- | --- |
| UFL-0.1 | **Authentication returns the User to the task they were attempting**, with their input preserved | PRD ACC-3, UXP-3.6, WCAG 3.3.7 |
| UFL-0.2 | A sign-in prompt appears **only** as the direct consequence of attempting an authenticated-required action | GS-3 |
| UFL-0.3 | No flow is interrupted by an interstitial, nag or countdown | GS-4 |
| UFL-0.4 | Every failure state offers a **named recovery action**, never only an apology | UXP-1.4 |
| UFL-0.5 | A loading state appears only where a wait genuinely occurs — past roughly a second | UR-11, UXP-8.8 |
| UFL-0.6 | Destructive actions require explicit confirmation stating what will happen | WCAG 3.3.4 |
| UFL-0.7 | Error messages are safe: no internal identifier, no stack trace, no SQL | TRD TR-144 |
| UFL-0.8 | A permission failure **does not disclose whether the target exists** | ENF-2, TRD TR-35 |
| UFL-0.9 | Status changes are announced to assistive technology, not only shown | WCAG 4.1.3 |
| UFL-0.10 | Every flow works on both surfaces; only chrome differs | SUR-5, SUR-6 |

---

# Part A — Guest flows

## A1. Home → Search → Results → Profile

| | |
| --- | --- |
| **Entry** | Direct, bookmark, external search engine, shared link, Mini App launch |
| **Preconditions** | At least one published Listing (C-01) |
| **Goal** | Find a specific business, or a business of a kind |
| **Capabilities** | C-01, C-02, C-03, C-08 |

```text
Home ──▶ tap search ──▶ type ──▶ autocomplete ──┬──▶ pick suggestion ──▶ Results
                                                 └──▶ submit query ─────▶ Results
                                                                            │
                                            refine: filter · sort · page ◀──┤
                                                                            ▼
                                                                    Business profile
```

**Steps and system response**

| # | User | System |
| --- | --- | --- |
| 1 | Opens Home | Search entry point visible **without scrolling** on mobile; no authentication requested (C-01 AC) |
| 2 | Focuses search | Field expands; keyboard opens; submit control visible beside the field (UR-04) |
| 3 | Types | Autocomplete after a short debounce: a small bounded list, grouped by type — Business · Category · Subcategory · Area — each group labelled (UR-03) |
| 4 | Arrows / taps a suggestion | Active suggestion is **copied into the field** so it can be edited (UR-03) |
| 5 | Submits | Navigates to `/search?q=…`; the query **persists in the field** (IAR-25) |
| 6 | — | Results render: organic list, result count, filters, sort. Sponsored items in a **separate labelled group** (UXP-5) |
| 7 | Opens a result | Business profile |

**Loading.** Autocomplete targets the sub-second band and shows no spinner
(UR-11). Full search is a normal page load; progressive-enhancement updates
show an inline indicator only past ~1 s.

**Empty — zero results.** Full spec in A7.

**Validation failure.** An over-length query is rejected cleanly with the
limit stated; the query is **not** silently truncated (TRD TR-128,
`search-design.md` MT-4).

**Degradation.** If autocomplete fails, plain search still works —
suggestion failure must never break search (C-03, `search-design.md` PF-4).
With JavaScript off, the field is a form and submits normally (MOB-5).

**Exit.** Profile · a Category or Area from the results · refine · Home.

---

## A2. Category → Subcategory → Results → Profile

| | |
| --- | --- |
| **Entry** | Primary navigation, Home discovery block, breadcrumb, profile Category link, external search engine |
| **Preconditions** | The Category has at least one published Listing |
| **Goal** | Browse by kind of business rather than by name |
| **Capabilities** | C-04, C-08 |

| # | User | System |
| --- | --- | --- |
| 1 | Opens Categories index | Categories with published Listings, with counts. **Empty Categories are not listed** (C-04, UXP-7.5) |
| 2 | Opens a Category | Category introduction, its Subcategories, Areas where it has Listings, and the organic Business list. Sponsored slot above the organic list if sold (ADV §2.2) |
| 3 | Opens a Subcategory | Narrowed list; breadcrumb gains a level |
| 4 | Opens a result | Business profile |

**Empty.** A Category whose Listings were all unpublished since the page
was linked shows an honest empty state plus Subcategory, Area and search
routes — never fabricated content (UXP-7.6).

**Recovery.** A retired Category slug redirects to its merge target
(C-23, `data-model.md` §8.7). A non-existent slug returns not found with
the Categories index offered.

---

## A3. Area → Category × Area → Profile

| | |
| --- | --- |
| **Entry** | Primary navigation, Home, breadcrumb, profile Area link, external search engine |
| **Goal** | "What is near here, of this kind" |
| **Capabilities** | C-05, C-06, C-08 |

| # | User | System |
| --- | --- | --- |
| 1 | Opens Areas index | Areas with published Listings, with Sub-city shown as secondary (GEO-6) |
| 2 | Opens an Area | Area page: Categories present here, organic list, neighbouring Areas |
| 3 | Picks a Category | **Category × Area** page at `/c/{category}/in/{area}` — one canonical URL (IAR-5) |
| 4 | Opens a result | Business profile |

**Thin content.** A Category × Area page below the minimum-content rule is
**served but not indexable** (SEO-9, C-06). The threshold is
**Open — product detail**.

**Empty.** The page offers: the same Category in a wider Area, other
Categories in this Area, and search.

---

## A4. Nearby → Profile → Directions

| | |
| --- | --- |
| **Entry** | Primary navigation, Home |
| **Preconditions** | None. **Location permission is not a precondition** |
| **Goal** | "What is close to me right now" |
| **Capabilities** | C-07, C-08, C-10 |

| # | User | System |
| --- | --- | --- |
| 1 | Opens Nearby | Explains what will happen and **why** before requesting location (UR-15, LOC-2) |
| 2 | Grants location | Distance-ordered results within a bounded radius; distances shown as approximate (`search-design.md` DS-6) |
| 2a | **Denies or dismisses** | Degrades to Area browsing. **No nagging, no repeat prompt, no blocked screen** (C-07, UXP-3.7) |
| 2b | Location unavailable or times out | Same as 2a, with a plain explanation and a retry control |
| 3 | Opens a result | Profile |
| 4 | Taps directions | Hands off to the maps application with the Branch coordinates (C-10) |

**Privacy.** Coordinates are used in-request and **never stored, never
logged, never sent to analytics** (TRD TR-201). The interface states this
plainly at the point of request.

**Empty.** Nothing within the radius → offer a wider radius, Area browsing
and search. The radius is **not** silently widened.

**Branches without coordinates** are excluded from distance ordering but
remain findable every other way (`search-design.md` DS-7).

---

## A5. Profile → Contact action

| | |
| --- | --- |
| **Entry** | Any list, share, external search engine |
| **Goal** | Call, visit the website, get directions, open a social link |
| **Capabilities** | C-08, C-09, C-10, C-11 |

| # | User | System |
| --- | --- | --- |
| 1 | Opens profile | Name, Category, verification state and date, open status, address, contact actions above the fold on mobile (UR-10, UXP-1.6) |
| 2 | Checks open status | **Open** · **Closed** · **Hours not confirmed** — three visually distinct states, never colour alone (UR-17, NFR-AC3) |
| 3 | Taps call | Hands off to the dialler |
| 3a | Taps website | Opens the site |
| 3b | Taps directions | Hands off to maps (C-10) |

| ID | Rule |
| --- | --- |
| UFL-A5.1 | A contact action appears **only where that contact point exists**. No disabled placeholders (C-11, UXP-7.2) |
| UFL-A5.2 | **No account is required** and none is suggested (GS-1) |
| UFL-A5.3 | Contact actions are recorded only as **aggregate, non-identifying** analytics (C-38, TRD TR-202) |
| UFL-A5.4 | A contact point flagged as a personal contact point is still a normal action to the User; the flag is internal (`data-model.md` §3.10) |
| UFL-A5.5 | The map **does not block the page**; address and landmark are sufficient alone (MOB-5, UXP-8.5) |

**Multi-branch.** A branch selector appears. The selected Branch drives
**address, Area, Sub-city, Landmark, map, hours, phone, branch email and
branch-specific services and pricing**; the Business name, description,
website and brand-level social links stay constant across Branches (D-55).
**Reviews and the rating shown in the Reviews block belong to the selected
Branch**; any Business-level rating figure is labelled as covering **all
branches** so the User is never shown an aggregate and a branch figure
without knowing which is which (D-34).

---

## A6. Profile → Read Reviews

| | |
| --- | --- |
| **Goal** | Judge the Business through other people's experience |
| **Capabilities** | C-13 |

| # | User | System |
| --- | --- | --- |
| 1 | Reaches the Reviews section | Rating summary — **average and count together** (UR-16) — then published Reviews, paginated |
| 2 | Reads | Each Review: display name, rating, date, text |
| 3 | Pages | Explicit pagination control; no infinite scroll (UXP-8.7) |

| ID | Rule |
| --- | --- |
| UFL-A6.1 | Reading Reviews is **Guest-safe** and never truncated behind sign-in (GS-2) |
| UFL-A6.2 | No published Reviews → **no rating element at all**, plus an invitation to be the first (TR-63, UXP-7.4) |
| UFL-A6.3 | **There is no owner reply affordance** — no field, no button, no empty slot (D-12, D-54) |
| UFL-A6.4 | **No photos on Reviews** (D-36) and **no helpful voting** (D-37) |
| UFL-A6.5 | Reporting a Review requires an account; tapping it as a Guest starts B1 and returns here |
| UFL-A6.6 | The marked-up rating matches the visible rating exactly (SEO-6, R-15) |

**Review context (D-34).** The block shows **the selected Branch's**
Reviews, with the Branch named so the context is unambiguous. Ratings are
on a **1 to 5** scale. A Review with a rating and no text is a **normal,
complete Review** and is rendered as such — never as incomplete or empty.
Reviews are listed **newest first**. Moderation **precedes publication**, so
the flow states the resulting state rather than assuming immediate
publication (`api-spec-v1.0.md` RV-3).

---

## A7. Zero results — a designed destination

| | |
| --- | --- |
| **Entry** | Any search or filtered list returning nothing |
| **Goal** | Leave with a next step, not a dead end |
| **Capabilities** | C-02, C-15 |
| **Evidence** | UR-05 |

```text
Zero results
 ├─ 1. "No results for «query»"            query stated, still editable
 ├─ 2. Broader matches, labelled as broader
 ├─ 3. "We removed the filter: Open now"   the dropped filter is NAMED
 ├─ 4. Nearest alternatives                same Category wider Area
 │                                         same Area related Category
 ├─ 5. Browse Categories · Browse Areas
 └─ 6. "Tell us what's missing"            → C-15, no account needed
```

| ID | Rule | Source |
| --- | --- | --- |
| UFL-A7.1 | The query is **retained and editable** (UR-04) |
| UFL-A7.2 | A relaxed result set is **labelled as broader**; it is never passed off as an exact match | `search-design.md` ZR-3 |
| UFL-A7.3 | A dropped filter is **named**, never silently removed | ZR-2 |
| UFL-A7.4 | **No Sponsored placement may fill a zero-result page** | ZR-5, UXP-5 |
| UFL-A7.5 | The query is recorded for coverage and Alias work, **with no Guest identifier** | SRCH-8, TRD TR-202 |
| UFL-A7.6 | The correction route is offered without an account | C-15, TS-2 |

---

## A8. Profile → Report a problem

| | |
| --- | --- |
| **Entry** | Report control on every Business profile (TS-1) |
| **Preconditions** | **None — Guest-safe** (TS-2) |
| **Goal** | Tell Bulbula something is wrong |
| **Capabilities** | C-15 |

| # | User | System |
| --- | --- | --- |
| 1 | Taps "Report a problem" | Form: problem type from a published list, free-text description, optional contact address |
| 2 | Submits | Accepted with a reference; plain confirmation of what happens next |

| ID | Rule |
| --- | --- |
| UFL-A8.1 | **No account required**, and none is suggested (TS-2, GS-1) |
| UFL-A8.2 | Guest reports store **no identity** (TRD TR-202). A contact address is optional and its purpose is stated |
| UFL-A8.3 | Rate-limited per source network; the limit is **never disclosed** (C-15, `api-spec-v1.0.md` §1.10) |
| UFL-A8.4 | A rate-limited submission says plainly that it cannot be accepted right now and when to retry — it does **not** say "too many requests" with a number |
| UFL-A8.5 | The confirmation promises **review**, not a fix or a timescale |
| UFL-A8.6 | Structured field-level suggestions are **Open (D-35)**; V1 is free text |
| UFL-A8.7 | Reporting a **Review** is a different, authenticated flow (B6) |

**Validation failure.** Missing problem type → inline error on the field,
focus moved to it, the rest of the input preserved (WCAG 3.3.1, 3.3.3).

---

## A9. Profile → Share

| | |
| --- | --- |
| **Goal** | Send a business to someone |
| **Capabilities** | C-17 |

| # | User | System |
| --- | --- | --- |
| 1 | Taps share | **Web:** platform share sheet, with copy-link fallback. **Mini App:** Telegram's share (SUR-5, IAR-39) |
| 2 | Shares | The **canonical URL** — no session, no filter, no tracking parameter (IAR-36) |

The link previews with title, description and image (SEO-11). A link shared
from the Mini App **resolves on the Web** for non-Telegram recipients
(TG-6). Sharing requires no account and is not recorded against a person
(IAR-40).

---

# Part B — Customer flows

## B1. Sign in with Google

| | |
| --- | --- |
| **Entry** | **Only** from attempting an authenticated-required action, or the account menu (GS-3) |
| **Goal** | Reach an account with the least effort |
| **Capabilities** | C-30, C-32 |

| # | User | System |
| --- | --- | --- |
| 1 | Attempts a gated action | Sign-in presented **in context**, saying what it unlocks and what Bulbula will receive |
| 2 | Chooses Google | Google flow |
| 3 | Completes it | Credential verified **server-side**; session created; **returns to the original task with input preserved** (UFL-0.1) |

| ID | Rule |
| --- | --- |
| UFL-B1.1 | Only **Google and email OTP** are offered. No password field exists anywhere (D-48) |
| UFL-B1.2 | A client assertion is never trusted; verification is server-side (`auth-identity.md` GA-1) |
| UFL-B1.3 | An unverified provider email **cannot** establish a verified address (GA-3) |
| UFL-B1.4 | **Inside Telegram, email OTP is presented first** — embedded-webview OAuth is unreliable (UR-14, TG-5). Both remain available: a runtime adaptation, not a product difference (SUR-6) |
| UFL-B1.5 | If Google is unreachable, email OTP still works and browsing is unaffected (`auth-identity.md` GA-6) |
| UFL-B1.6 | Cancelling returns to the task **unchanged** — no penalty, no nag, no modal |

**Failure.** Generic, non-enumerating message plus the alternative method
(`auth-identity.md` OTP-8).

**Identity collision.** Where the verified address matches an existing
Customer, resolution follows **D-13 — open**. The interface **never
silently merges and never silently forks** (C-32, LK-4). While D-13 is
open, the design presents an explicit confirmation step rather than
assuming automatic linking.

---

## B2. Sign in with email OTP

| | |
| --- | --- |
| **Capabilities** | C-31, C-32 |

```text
Enter email ──▶ "If that address can sign in, we've sent a code"
                 (identical response whether or not an account exists)
      │
      ▼
Enter code ──┬── correct ──▶ session ──▶ return to task
             ├── wrong ─────▶ generic failure, attempts remain
             ├── expired ───▶ "That code has expired" + request a new one
             └── exhausted ─▶ code invalidated, must request a new one
```

| ID | Rule | Source |
| --- | --- | --- |
| UFL-B2.1 | The request response is **identical** whether or not the address has an account | C-31, OTP-8 |
| UFL-B2.2 | The code field accepts **paste**, is typed with a numeric keypad, and has a correct autocomplete hint | WCAG 3.3.8, UR-01 |
| UFL-B2.3 | Failures are **generic** — never "wrong code" versus "no such account" | OTP-8 |
| UFL-B2.4 | Resend is available after a **visible cooldown**, shown as a countdown on the control | OTP-6 |
| UFL-B2.5 | Length, validity window, attempt ceiling and request quota are **Open — technical decision (TRD OT-02)**. The interface reads them from configuration and **must not hard-code them** | TRD TR-196 |
| UFL-B2.6 | The screen states the address the code went to, and offers "use a different address" | WCAG 3.3.7 |
| UFL-B2.7 | Single-use: a replayed code fails even inside its window | TRD TR-163 |
| UFL-B2.8 | **Accessible authentication:** no puzzle, no CAPTCHA, no memory test | WCAG 3.3.8, AX-12 |

**Email not arriving.** The screen explains the realistic causes and offers
resend after cooldown. Deliverability depends on **D-41 — open**.

---

## B3. First authenticated session

| # | User | System |
| --- | --- | --- |
| 1 | Completes B1 or B2 | Account created on first successful authentication — **no separate registration step** (C-30, C-31) |
| 2 | — | **Returns to the original task** and completes it (UFL-0.1) |
| 3 | — | A brief, dismissible confirmation that they are signed in |

| ID | Rule |
| --- | --- |
| UFL-B3.1 | **No onboarding, no tour, no profile-completion prompt, no welcome screen** (UXP-3.4) |
| UFL-B3.2 | Only the minimum is stored: email, display name, timestamps, state (PRD ACC-6, D-51) |
| UFL-B3.3 | **Nothing asks for a date of birth.** The minimum-age position is **Open (D-46)** and **PENDING COUNSEL** |
| UFL-B3.4 | The account menu becomes available; nothing else in the interface changes |
| UFL-B3.5 | A Customer sees an entry point to their Saved list on Home (C-01) |

---

## B4. Save a Business

| | |
| --- | --- |
| **Entry** | Save control on a card or a profile |
| **Capabilities** | C-14, C-34 |

| # | User | System |
| --- | --- | --- |
| 1 | **Guest** taps Save | Sign-in in context → B1/B2 → **returns and completes the Save** (UFL-0.1) |
| 1a | **Customer** taps Save | Saved immediately; the control reflects the new state; a brief confirmation |
| 2 | Taps again | Removed. Both directions are idempotent (`api-spec-v1.0.md` §3.3) |

| ID | Rule | Source |
| --- | --- | --- |
| UFL-B4.1 | The label is **Save** / **Saved**. Never Favourite, Like, Bookmark, Wishlist or Follow | `glossary.md` §3 |
| UFL-B4.2 | **No save count is ever displayed.** Save is a private utility, not a social signal | UR-18 |
| UFL-B4.3 | The control carries a text label or an accessible name — never an unlabelled heart | WCAG 1.1.1, 2.5.3 |
| UFL-B4.4 | The state change is announced to assistive technology | WCAG 4.1.3 |
| UFL-B4.5 | Failure leaves the control in its **true** prior state and says so — no optimistic lie | UFL-0.4 |

---

## B5. Write, edit and delete a Review

| | |
| --- | --- |
| **Capabilities** | C-13, C-35 |

```text
Profile ──▶ "Write a review" ──┬─ Guest ──▶ sign in ──▶ return to the form
                                └─ Customer ──▶ form
                                                  │
                        rating + optional text ───┤
                                                  ▼
                                       submit ──▶ state shown honestly
                                                  (published OR awaiting review)
```

| ID | Rule | Source |
| --- | --- | --- |
| UFL-B5.1 | Authenticated Customers only | D-12 |
| UFL-B5.2 | **One Review per Customer per subject.** A second attempt opens the existing Review for editing rather than creating a duplicate | TRD TR-160, AB-3 |
| UFL-B5.3 | **Moderation precedes publication** (D-34). The result screen states plainly that the Review has been submitted and is awaiting review, and the interface **must not** assume immediate publication or imply the Review is already visible | `api-spec-v1.0.md` RV-3 |
| UFL-B5.4 | The rating is a **required choice from 1 to 5**; submission without it is blocked with an inline message. **Review text is optional** — the form **must not** require it, and **must not** present a rating-only Review as incomplete (D-34) | RV-4 |
| UFL-B5.5 | The subject is a **Branch**. Where a Business has several Branches, the form **names the Branch being reviewed** before submission (D-34, D-55) | RV-2a |
| UFL-B5.6 | A Customer who already has an active Review for that Branch is taken to **edit** it rather than shown an error about duplicates (D-34, RV-2) | RV-2 |
| UFL-B5.7 | The edit affordance is available for **30 days after the Review was created** and the screen says so in plain language — for example that a Review can be edited for 30 days after posting. After the window it is absent, not disabled-with-no-reason (D-34) | RV-4a |
| UFL-B5.8 | Editing a published Review **returns it to review**, and the interface says so **before** the edit is submitted, so the Customer is not surprised that their Review left public view (D-34) | RV-3a |
| UFL-B5.9 | Maximum text length, rate limits and thresholds are **server configuration** and are read from the server, never hard-coded in the client (D-34) | RV-4c |
| UFL-B5.5 | The review policy is linked from the form before submission | `review-policy.md`, C-18 |
| UFL-B5.6 | **No photo upload** (D-36). **No helpful voting** (D-37) |
| UFL-B5.7 | Deleting own Review requires confirmation stating what happens | UFL-0.6 |
| UFL-B5.8 | My Reviews shows each Review's state — pending · published · rejected · removed — with the policy ground where rejected | C-35, C-25 |
| UFL-B5.9 | Staff **must not** write Reviews from staff accounts | `review-policy.md` §7 |

**Validation failure.** Inline, beside the field, focus moved to the first
error, every other input preserved (WCAG 3.3.1, 3.3.3).

**Rejection.** The Customer is told, with the policy ground, and told
whether an appeal route exists. A rejected Review is **not** silently
deleted (C-25).

---

## B6. Report a Review

| | |
| --- | --- |
| **Preconditions** | **Authenticated** — a report against a person's content must be attributable (`interaction-permissions.md` §3 n.2) |
| **Capabilities** | C-15, C-25 |

| # | User | System |
| --- | --- | --- |
| 1 | Taps report on a Review | Guest → sign in → returns here |
| 2 | Picks a ground | Grounds from the published list (`review-policy.md` §5.1) |
| 3 | Submits | Confirmation that it will be reviewed against the policy |

| ID | Rule |
| --- | --- |
| UFL-B6.1 | **Reporter identity is never exposed** to the author or the Business (TRD TR-65) |
| UFL-B6.2 | A report alone **never** unpublishes a Review (REP-3) |
| UFL-B6.3 | The confirmation promises review, not removal |
| UFL-B6.4 | Rate-limited without disclosing the limit |

---

## B7. Saved list

| | |
| --- | --- |
| **Capabilities** | C-34 |

Shows Saved Businesses as cards with their **current** state. A Business
later unpublished is shown with its current status rather than silently
disappearing (C-34). Each card offers remove.

**Empty state.** Explains what Save is for and routes to search and
browsing (UXP-1.4). It does **not** suggest businesses to save.

**Guest.** Tapping Saved routes to sign-in **on tap** — the navigation item
itself is never hidden or disabled (UXP-3.3, UXP-7.7).

---

## B8. Account and privacy controls

| | |
| --- | --- |
| **Capabilities** | C-33, C-36, C-40 |

| Screen | Content |
| --- | --- |
| Profile | Display name (editable), email address, sign-in methods, dates (C-33) |
| My Reviews | Own Reviews with state (C-35) |
| Privacy | Export own data · delete account · links to the privacy notice (C-36) |

| ID | Rule |
| --- | --- |
| UFL-B8.1 | Only the minimal profile exists. No avatar, no bio, no public Customer page (PRD ACC-6) |
| UFL-B8.2 | Sign-in methods are listed. Linking and unlinking behaviour is **Open (D-13)**; the screen presents only what is certain |
| UFL-B8.3 | **Changing the email address is not a V1 capability** — it depends on D-13 (`auth-identity.md` §9). Its absence is stated plainly rather than discovered |
| UFL-B8.4 | Export is generated as a bounded job, not an unbounded synchronous query (`performance-and-caching.md` §12) |

---

## B9. Account deletion

| | |
| --- | --- |
| **Capabilities** | C-36 |

```text
Privacy ──▶ Delete account
              │
              ▼
   What will be removed · what will not · that it cannot be undone
              │
         explicit confirmation
              │
              ▼
   Sessions revoked · personal data removed or detached
   Audit entry written WITHOUT personal data · email confirmation
              │
              ▼
          Signed out → Home
```

| ID | Rule | Source |
| --- | --- | --- |
| UFL-B9.1 | The screen states **what will and will not be removed before it happens** | `auth-identity.md` DL-1 |
| UFL-B9.2 | Explicit confirmation; no single-tap deletion | UFL-0.6 |
| UFL-B9.3 | **Every session is revoked** | DL-2 |
| UFL-B9.4 | An email confirmation is sent | C-36, C-39 |
| UFL-B9.5 | Published Reviews are **withdrawn** on account deletion — they stop being publicly visible and leave every rating summary (D-34), and the screen says so plainly. **How long any internal record is kept is PENDING COUNSEL (L-21, D-46)**; the screen **must not** promise a period or imply immediate permanent erasure | DL-4 |
| UFL-B9.6 | Response windows and export format are **PENDING COUNSEL (L-7)** |
| UFL-B9.7 | Signing in again later creates a **new** Customer with no prior data, and the screen says so | DL-6 |

---

# Part C — Operations flows

All Web-only, staff-authenticated, audited
(`scope-v1.md` §1.5, C-29). **Where D-14 leaves the Operator /
Administrator split open, these flows name the required *permission*, not
the role.**

## C1. Create a Listing

| | |
| --- | --- |
| **Capabilities** | C-19 · **Permission** `listing.create` |

```text
Duplicate check ──▶ Permission record ──▶ Collect ──▶ Draft
   (name + area)        (D-50)            (business facts)
                                                 │
                                                 ▼
                                      Submit for quality review
```

| # | Staff | System |
| --- | --- | --- |
| 1 | Starts a new Listing | **Duplicate candidates shown first**, by name and Area (TRD TR-55) |
| 2 | Confirms it is new | Draft opens |
| 3 | Records **Permission** | Person, role claimed, method, date, obtained-by. **Publication is blocked without it** (TR-49, D-50) |
| 4 | Enters business facts | Required versus optional clearly marked; **unknown is a valid, recordable state** (TR-56) |
| 5 | Adds at least one Branch | Area and Sub-city required (GEO-6); exactly one primary Branch (D-03) |
| 6 | Submits for review | State → in review; enters the quality-review queue |

**Validation failure.** Field-level, with the rule stated. Submitting
without Permission names Permission as the blocker (TR-49).

**Permission failure.** Without `listing.create`, the action is not
offered and a direct request fails without disclosing existence
(UFL-0.8).

**Open (D-43):** exact Permission-record contents. **Open (D-44):**
services, products and pricing — **no field is specified**, because
specifying one would decide D-44.

---

## C2. Edit a Listing and record a Correction

| | |
| --- | --- |
| **Capabilities** | C-20 · **Permission** `listing.edit` |

| # | Staff | System |
| --- | --- | --- |
| 1 | Opens a published Listing | Current values plus the Correction history |
| 2 | Changes a field | A **reason and source are required** for a change to published data (TR-52) |
| 3 | Saves | Correction recorded: field, previous value, new value, reason, source, actor, time (COR-1). Material changes flag re-verification (COR-2) |
| 4 | — | Search document updated **synchronously**; the change is visible immediately (TR-45) |

**Concurrent edit.** A stale concurrency token is refused with a clear
conflict message showing what changed and who changed it — the edit is
**never silently overwritten** (TR-57, O-4).

**Which fields a given permission may edit is Open (D-14).** The screen is
built around named permissions so the answer is configuration.

---

## C3. Verification and quality review

| | |
| --- | --- |
| **Capabilities** | C-21 · **Permissions** `listing.verify`, `listing.publish` |

| # | Staff | System |
| --- | --- | --- |
| 1 | Opens the quality-review queue | Listings in review, oldest first, with completeness and Permission state |
| 2 | Reviews a Listing | Full record plus Provenance and Permission |
| 3 | Records **Verification** | Method, date, scope, actor (C-21) |
| 4 | Publishes | **Refused with a named blocker** if Permission or Verification is missing (TR-49, TR-50, O-1) |
| 4a | Returns for rework | Reason required; returns to the creator |

| ID | Rule |
| --- | --- |
| UFL-C3.1 | **Separation of duties:** the publisher is not the creator where another reviewer exists. Where Bulbula runs with one Staff member the gap is a recorded operational risk, not removed from the product (`interaction-permissions.md` n.3) |
| UFL-C3.2 | Verification methods, tiers and re-verification interval are **Open (D-08)**; the form reads them from configuration |
| UFL-C3.3 | A stale-Listing queue is derived from the most recent Verification — no job required (TR-54, TR-154) |
| UFL-C3.4 | Every publish, unpublish and verification is audited; **if the audit write fails, the action fails** (TR-08) |

**Unpublish and close.** Both require a reason. Closure is a **state**, not
a delete: the URL and history survive (TR-53, UXP-7.8).

---

## C4. Moderate a Review

| | |
| --- | --- |
| **Capabilities** | C-25 · **Permission** `review.moderate` |

| # | Staff | System |
| --- | --- | --- |
| 1 | Opens the moderation queue | Pending and reported Reviews with their report counts |
| 2 | Opens one | Full text, subject Business, author display name, reports and their grounds |
| 3 | Decides | Publish · reject · remove — **a policy ground is required** (`review-policy.md` §5.1) |
| 4 | — | Decision recorded append-only; the author is informed with the ground (C-25) |

| ID | Rule |
| --- | --- |
| UFL-C4.1 | **Reporter identity is never shown** to the author or the Business (TR-65) |
| UFL-C4.2 | A decision without a policy ground cannot be submitted |
| UFL-C4.3 | Report volume is context, **never** an automatic trigger (REP-3) |
| UFL-C4.4 | Policy changes and appeals are **Administrator** (`interaction-permissions.md` §4) |
| UFL-C4.5 | **Moderation precedes publication** (D-34): the queue holds `pending` Reviews, approval publishes, and a rejected Review is never public. Post-publication removal of an already-published Review remains available |

---

## C5. Handle a Report

| | |
| --- | --- |
| **Capabilities** | C-26 · **Permission** `report.handle` |

| # | Staff | System |
| --- | --- | --- |
| 1 | Opens the report queue | Open reports, **grouped where several describe one issue** (C-26) |
| 2 | Triages | Full report, target, and history of reports on that target |
| 3 | Acts | Opens a Correction (C2), moderates (C4), or closes with an outcome |
| 4 | Resolves | **Every report reaches a recorded outcome** — resolved · closed-unverified · rejected (C-26) |

A report resolved by a Correction **links to it** (COR-4). Legal
escalations are Administrator (`interaction-permissions.md` §4).

---

## C6. Taxonomy and locations

| | |
| --- | --- |
| **Capabilities** | C-23, C-24 · **Permissions** `taxonomy.manage`, `location.manage` — **Administrator-only** |

| Action | Behaviour |
| --- | --- |
| Create / edit a Category | Two levels only (D-06). Aliases managed here, including Amharic (D-18) |
| Hide a Category | Removes it from navigation **without unclassifying** its Listings (C-23) |
| Delete a Category | **Refused** while any Business is classified under it |
| Merge Categories | Reassigns Listings and **leaves a redirect** for the retired slug (SEO-4) |
| Create / edit an Area | Area plus Sub-city. Adding an Area is **pure data — no deployment** (GEO-4, TR-217) |
| Delete an Area | **Refused** while Branches are assigned |

Bulk taxonomy changes queue a rebuild of affected search documents and say
so (`search-design.md` SD-3).

**Classification (D-56, D-57).** The catalogue is **centrally curated by
Bulbula** and edited here as data — no Operator or User creates a Category
outside this screen. A Listing is given **exactly one primary Category**,
required before publication, plus **any number of secondary Categories**.
The form **must not** present an artificial maximum, and it **must** prevent
the same Category being chosen twice or appearing as both primary and
secondary. The **catalogue content itself** is an operations task, not a
specification item.

---

## C7. Campaign management

| | |
| --- | --- |
| **Capabilities** | C-27 · **Permissions** `campaign.create`, `campaign.activate` |

```text
Check availability ──▶ Create Campaign ──▶ Pending approval
  (placement+period)      (business, package,          │
                           target, dates)              ▼
                                            Administrator approves
                                                       │
                                            Active between its dates
                                                       │
                                      suspend · cancel · ends naturally
```

| ID | Rule | Source |
| --- | --- | --- |
| UFL-C7.1 | Creation is refused with a clear conflict when the Placement and period are **sold out** | TR-72, O-5 |
| UFL-C7.2 | **Approval and activation are Administrator-only**, as is early termination, with a reason | `interaction-permissions.md` §4 |
| UFL-C7.3 | State is evaluated **at request time**; no job starts or stops a Campaign | TR-71 |
| UFL-C7.4 | **No bid, budget, CPC, CPM or CPA field exists anywhere** | D-10, CR-3 |
| UFL-C7.5 | Delivery figures are **reporting only** and never feed ranking or price | TR-74, MS-2 |
| UFL-C7.6 | Campaign work **cannot touch** Listing content, verification, trust indicators or Reviews | ADV-9, PK-2, D-39 |
| UFL-C7.7 | **No price is approved.** The field exists; the values do not (D-10, D-11) |
| UFL-C7.8 | Every lifecycle action is audited | TR-75 |

---

## C8. Analytics and audit

| | |
| --- | --- |
| **Capabilities** | C-28, C-29 |

**Analytics** (`analytics.read`): coverage, quality, throughput, content
signals and campaign delivery, read from **rollups, never raw events**
(AN-2). No view identifies an individual Guest (TR-202). Granularity and
retention are **Open (D-27)**. An Operator's scope — own work versus all
work — is **Open (D-14)**.

**Audit** (`audit.read`, **Administrator-only**): append-only history of
privileged actions with actor, action, target, time and reason. Filterable,
paginated, **never editable and never deletable** (DO-8). Deleting a
Customer does not delete audit entries (TR-167).

---

## 11. Flow coverage matrix

| Flow | Capabilities | Actor |
| --- | --- | --- |
| A1 Home → search → profile | C-01, C-02, C-03, C-08 | Guest |
| A2 Category browse | C-04, C-08 | Guest |
| A3 Area and Category × Area | C-05, C-06, C-08 | Guest |
| A4 Nearby → directions | C-07, C-08, C-10 | Guest |
| A5 Contact action | C-08, C-09, C-10, C-11, C-12 | Guest |
| A6 Read Reviews | C-13 | Guest |
| A7 Zero results | C-02, C-15 | Guest |
| A8 Report a problem | C-15 | Guest |
| A9 Share | C-17 | Guest |
| B1 Google sign-in | C-30, C-32 | Customer |
| B2 Email OTP | C-31, C-32 | Customer |
| B3 First session | C-30, C-31, C-33 | Customer |
| B4 Save | C-14, C-34 | Customer |
| B5 Reviews write/edit/delete | C-13, C-35 | Customer |
| B6 Report a Review | C-15, C-25 | Customer |
| B7 Saved list | C-34 | Customer |
| B8 Account and privacy | C-33, C-36, C-40 | Customer |
| B9 Deletion | C-36 | Customer |
| C1 Create Listing | C-19 | Staff |
| C2 Edit and correct | C-20 | Staff |
| C3 Verify, quality review, publish | C-21 | Staff |
| C4 Moderate Reviews | C-25 | Staff |
| C5 Handle Reports | C-26 | Staff |
| C6 Taxonomy and locations | C-23, C-24 | Administrator |
| C7 Campaigns | C-27 | Staff / Administrator |
| C8 Analytics and audit | C-28, C-29 | Staff / Administrator |
| Cross-cutting | C-16 (§A1, A2, A3), C-18 (§A7, A8), C-22 (C1, C2), C-37, C-38, C-39 | — |

**All 40 capabilities appear in at least one flow.**

---

## 12. Open items

| ID | Item | Affected flows |
| --- | --- | --- |
| D-04 | Hours model | A5 |
| D-08 | Verification rules and interval | C3 |
| D-13 | Identity linking | B1, B8 |
| D-14 | Operator / Administrator split | C1–C8 |
| D-21 | Maps strategy and fallback | A4, A5 |
| D-25 | Media limits | C1, C2 |
| D-27 | Analytics granularity | C8 |
| D-35 | Structured guest suggestions | A8 |
| D-38 | Mini App navigation model | all |
| D-41 | Email provider | B2 |
| D-43 | Permission-record contents | C1 |
| D-44 | Services / products / pricing | A5, C1 |
| D-46 | Minimum age | B3 |
| TRD OT-02 | OTP parameters | B2 |
| L-7 | Rights-request windows | B9 |
| L-21 | Retention | B9 |

---

## Decision references

D-03, D-04, D-06, D-08, D-10, D-11, D-12, D-13, D-14, D-18, D-21, D-25,
D-27, D-34, D-35, D-36, D-37, D-38, D-39, D-41, D-43, D-44, D-46, D-48,
D-50, D-51, D-54, D-55, D-56, D-57.
