# Content Design

| | |
| --- | --- |
| **Document** | Content Design — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The words. Voice, terminology, labels, messages and the rules
that keep them consistent across both surfaces.

**Authority.** Terminology is fixed by
[`../10-product/glossary.md`](../10-product/glossary.md). Where this
document and the glossary differ, **the glossary wins**.

**Language.** English-first, bilingual-ready. **No full Amharic UI in V1**
(PRD §13, D-18).

---

## 1. Voice

Bulbula sounds like **a well-informed local who answers the question and
stops talking**.

| It is | It is not |
| --- | --- |
| Direct | Blunt |
| Plain | Simplistic |
| Honest about gaps | Apologetic |
| Useful | Chatty |
| Calm | Excited |
| Specific | Vague |

| ID | Rule | Source |
| --- | --- | --- |
| CDN-1.1 | **Say the thing.** The shortest accurate sentence wins | UXP-6 |
| CDN-1.2 | **Never exclaim.** No exclamation marks in interface copy | UXP-6 |
| CDN-1.3 | **No humour at the User's expense**, and no jokes in error states | — |
| CDN-1.4 | **No marketing language in the product.** No "amazing", "best", "trusted by thousands", "discover the magic" | UXP-5 |
| CDN-1.5 | **No invented numbers.** No counts, percentages, ratings, user totals or growth claims that are not real | Owner constraint |
| CDN-1.6 | **No urgency, scarcity or countdown framing** | GS-4 |
| CDN-1.7 | Address the User as **you**. Bulbula is **we** only where an action is genuinely Bulbula's — "we checked this listing" | — |
| CDN-1.8 | Prefer the **active voice** and the **present tense** | — |
| CDN-1.9 | **Never personify the product.** Bulbula does not think, feel, hope or apologise | — |
| CDN-1.10 | A sentence ends with a full stop; a short label does not | — |

---

## 2. Writing for bilingual readiness

| ID | Rule | Source |
| --- | --- | --- |
| CDN-2.1 | **Amharic content renders correctly today** even though the UI is English: Business names, Aliases and Area names (D-18, DSN-3.3) |
| CDN-2.2 | Amharic strings inside an English page **declare their language** (A11-17.2) |
| CDN-2.3 | Interface strings are written as whole sentences, **never assembled from fragments** — concatenation does not survive translation |
| CDN-2.4 | No string embeds grammar that assumes English word order or English pluralisation |
| CDN-2.5 | Layouts tolerate **strings 30–40 % longer** `[P]` without breaking |
| CDN-2.6 | **No text inside images.** Text in an image cannot be translated, resized or read aloud (WCAG 1.4.5) |
| CDN-2.7 | Dates and times are written unambiguously — a month name, not a numeric order that differs by locale |
| CDN-2.8 | Idiom, slang and culturally specific metaphor are avoided |

---

## 3. Terminology — binding

From [`../10-product/glossary.md`](../10-product/glossary.md). **These are
not preferences.**

| Always | Never | Why |
| --- | --- | --- |
| **Save** / **Saved** | Favourite, Like, Bookmark, Wishlist, Follow, Add to list | Save is a private utility, not a social act (UR-18) |
| **Business profile** (the public page) | Listing page, Business page, Store page | A Listing is the publication unit, not the page |
| **Listing** (the publication unit) | Entry, record, post | Operations vocabulary |
| **Permission** (the business's agreement to be listed) | Consent, approval, sign-off | "Consent" is reserved for personal-data contexts |
| **Consent** (personal data only) | — | Never used for a business agreement |
| **Verified** / **Verification** | Certified, approved, trusted, official, endorsed | Verification is a factual check, not an endorsement |
| **Sponsored** | Promoted, Featured, Partner, Recommended, Top pick, Ad | FTC guidance names "Promoted" as ambiguous (UR-02, LB-3) |
| **Business** | Shop, store, vendor, merchant, place | — |
| **Branch** | Location, outlet, store | "Location" means geography here |
| **Category** / **Subcategory** | Type, tag, section | Exactly two levels (D-06) |
| **Alias** | Synonym, keyword, also-known-as | — |
| **Area** / **Sub-city** / **Landmark** | Neighbourhood, district, zone | — |
| **Guest** | Anonymous user, visitor, non-user | — |
| **Customer** | User, member, account holder | A Customer has an account; a User may not |
| **Operator** / **Administrator** / **Staff** | Admin, moderator, editor, agent | — |
| **Review** | Rating, comment, feedback, testimonial | A Review may include a rating; it is not the same thing |
| **Report** | Flag, complaint, abuse | — |
| **Correction** | Edit, update, fix | A Correction is a recorded change with a reason |
| **Campaign** / **Package** / **Placement** | Ad, banner, slot, spot | — |

| ID | Rule |
| --- | --- |
| CDN-3.1 | **A Business owner is not a platform user.** No copy addresses them as one, offers them an account, or implies a dashboard exists (D-54, D-02) |
| CDN-3.2 | A term means **one thing** across the product, the console and the policy pages |
| CDN-3.3 | A term that must be explained is explained **at first use**, in place, not in a tooltip |

---

## 4. Capitalisation, numbers and formatting

| ID | Rule |
| --- | --- |
| CDN-4.1 | **Sentence case** for every heading, label, button and message. No Title Case |
| CDN-4.2 | Glossary terms are capitalised **only** where the glossary capitalises them |
| CDN-4.3 | **No all-caps sentences**; acceptable only for a very short label |
| CDN-4.4 | Numerals for all numbers in the interface, including one to nine — they scan faster |
| CDN-4.5 | Phone numbers in a single consistent, readable format |
| CDN-4.6 | Times in a single consistent format, in the Business's local time (TRD TR-92) |
| CDN-4.7 | Dates as day, month name, year — never a purely numeric order |
| CDN-4.8 | Distances as approximate, with a unit — never false precision (`search-design.md` DS-6) |
| CDN-4.9 | A rating is written to one consistent decimal and **always with its count** (UR-16) |
| CDN-4.10 | Counts are real. "Showing 12 of 47" is only written when both numbers are true |

---

## 5. Buttons and actions

| ID | Rule |
| --- | --- |
| CDN-5.1 | A button label is a **verb naming the outcome** |
| CDN-5.2 | **Never** "Submit", "OK", "Yes", "No", "Click here", "Continue" where something more specific is true |
| CDN-5.3 | A confirmation button names the action: "Delete review", not "Confirm" |
| CDN-5.4 | Cancel is "Cancel". It is never dressed up as a loss |
| CDN-5.5 | Labels are 1–3 words where possible |
| CDN-5.6 | The same action carries the same label everywhere (WCAG 3.2.4) |

| Action | Label |
| --- | --- |
| Run a search | **Search** |
| Save a Business | **Save** → **Saved** |
| Remove from Saved | **Remove** |
| Open directions | **Directions** |
| Call | **Call** |
| Open the website | **Website** |
| Share | **Share** |
| Copy the link | **Copy link** |
| Report a Business | **Report a problem** |
| Report a Review | **Report review** |
| Write a Review | **Write a review** |
| Edit own Review | **Edit review** |
| Delete own Review | **Delete review** |
| Apply filters | **Show results** |
| Clear filters | **Clear all** |
| Next page | **Next** |
| Sign in with Google | **Continue with Google** |
| Sign in with email | **Continue with email** |
| Request a new code | **Send a new code** |
| Sign out | **Sign out** |
| Delete the account | **Delete account** |
| Export own data | **Download my data** |

---

## 6. Empty states

**Shape:** what is absent → why, if known → what to do next.

| ID | Rule | Source |
| --- | --- | --- |
| CDN-6.1 | **Never apologise for an absence.** "We don't have any listings for this yet" — not "Sorry!" | UXP-7 |
| CDN-6.2 | **Never fabricate.** Do not suggest content that does not exist | UXP-7.6 |
| CDN-6.3 | Always offer at least one concrete route out | UFL-0.4 |
| CDN-6.4 | Never cute, never an illustration-plus-pun (DSN-11.19) |
| CDN-6.5 | **Never fill an empty state with Sponsored content** | ZR-5 |

| State | Copy |
| --- | --- |
| Zero search results | **No results for "{query}"** — "Try a different spelling, or browse by category or area." |
| Zero results after filtering | **No results with these filters** — "We removed the filter: {filter name}. Here are broader matches." |
| Empty Category | **No businesses listed in {Category} yet** — "Browse other categories, or tell us about a business we're missing." |
| Empty Area | **No businesses listed in {Area} yet** — "Browse nearby areas, or tell us about a business we're missing." |
| Empty Category × Area | **No {Category} listed in {Area} yet** — "Try {Category} in nearby areas, or other categories in {Area}." |
| Empty Saved | **Nothing saved yet** — "Save a business to find it again quickly. Only you can see your saved list." |
| No Reviews | **No reviews yet** — "Be the first to review {Business}." |
| No photos | *(the block is absent — no message)* |
| Hours not recorded | **Hours not confirmed** *(neutral — not an error)* |
| Empty console queue | **Nothing waiting** |
| Nearby, nothing in range | **Nothing found nearby** — "Try a wider area, or browse by area." |

---

## 7. Errors

**Shape:** what happened → what to do. No blame, no code, no jargon.

| ID | Rule | Source |
| --- | --- | --- |
| CDN-7.1 | **Never** "Oops!", "Uh oh", "Something went wrong" alone | CDN-1.3 |
| CDN-7.2 | No error codes, internal identifiers, stack traces or SQL in User-facing copy | TRD TR-144 |
| CDN-7.3 | Say what the User can do next | UFL-0.4 |
| CDN-7.4 | Do not blame the User. "That code has expired", not "You entered an expired code" |
| CDN-7.5 | A permission error **does not reveal whether the target exists** | UFL-0.8 |
| CDN-7.6 | A rate-limit message does not disclose the limit | UFL-A8.4 |
| CDN-7.7 | A security-sensitive failure is **generic** — never distinguishing causes | OTP-8 |

| Error | Copy |
| --- | --- |
| Page not found | **We couldn't find that page** — "It may have been removed. Try searching, or browse by category." |
| Business not found or unpublished | **We couldn't find that business** — "It may no longer be listed. Try searching for it." |
| Search unavailable | **Search isn't working right now** — "Try again in a moment, or browse by category or area." |
| Network failure | **We couldn't load that** — "Check your connection and try again." |
| Save failed | **We couldn't save that** — "Try again." |
| Form validation | **{Field} is required** / **{Field} must be {rule}** |
| Too long | **That's too long. Maximum {n} characters.** |
| Sign-in failed | **We couldn't sign you in** — "Try again, or continue with email instead." |
| OTP incorrect | **That code isn't right** — "Check the code and try again." |
| OTP expired | **That code has expired** — "Send a new code." |
| OTP attempts exhausted | **That code is no longer valid** — "Send a new code." |
| Rate limited | **We can't accept that right now** — "Please try again later." |
| Permission denied | **You don't have access to that** |
| Console conflict | **This record changed while you were editing** — "{Actor} changed {field} at {time}. Review the changes before saving." |
| Publication blocked | **This listing can't be published yet** — "{Blocker} is missing." |
| Audit write failed | **That action didn't take effect** — "It couldn't be recorded. Try again." |

---

## 8. Confirmations

| ID | Rule |
| --- | --- |
| CDN-8.1 | Confirmations are short, in the past tense, and state what happened |
| CDN-8.2 | Fewer than ~8 words `[P]` |
| CDN-8.3 | **No celebration.** No "Great!", no "Awesome!", no confetti |
| CDN-8.4 | A destructive confirmation states the consequence **before** it happens, and says what cannot be undone |

| Event | Copy |
| --- | --- |
| Saved | **Saved** |
| Removed from Saved | **Removed** |
| Link copied | **Link copied** |
| Report sent | **Thanks — we'll review this.** |
| Review submitted (published) | **Your review is published.** |
| Review submitted (pending) | **Your review has been submitted for review.** |
| Review deleted | **Review deleted** |
| Account deletion, before | **Delete your account?** — "This removes your profile and saved list. {Review outcome}. This can't be undone." |
| Account deletion, after | **Your account has been deleted.** |
| Data export requested | **We're preparing your data** — "We'll email you when it's ready." |

**The `{Review outcome}` placeholder now has a decided core.** On account
deletion a published Review is **withdrawn** — it stops being publicly
visible and no longer counts towards any rating (D-34) — and the copy says
exactly that. What the copy **must not** do is state or imply **how long**
any internal record is kept, because that remains **PENDING COUNSEL** (L-21,
D-46).

---

## 9. Authentication copy

| ID | Rule | Source |
| --- | --- | --- |
| CDN-9.1 | The prompt says **what the User is trying to do**, not what Bulbula wants | GS-3 |
| CDN-9.2 | **No benefit list, no "join thousands", no social proof, no scarcity** | GS-4, CDN-1.6 |
| CDN-9.3 | Say what Bulbula receives from the provider | PRD §21 |
| CDN-9.4 | Failures are generic (CDN-7.7) |
| CDN-9.5 | **No copy anywhere refers to a password** — none exists (D-48) |

| Context | Copy |
| --- | --- |
| Prompt — Save | **Sign in to save this business** — "Your saved list is private to you." |
| Prompt — Review | **Sign in to write a review** |
| Prompt — Report a Review | **Sign in to report this review** |
| Prompt — Saved list | **Sign in to see your saved list** |
| Method choice | **Continue with Google** · **Continue with email** |
| What is received | "We'll receive your email address and name from Google." |
| OTP sent | **Check your email** — "If that address can sign in, we've sent a code to {address}." |
| OTP entry | **Enter the code we emailed you** |
| Resend cooldown | **Send a new code in {n}s** |
| Different address | **Use a different email** |
| Cancel | **Cancel** |

The OTP-sent wording is **deliberately conditional** so it is identical
whether or not an account exists (UFL-B2.1, OTP-8).

---

## 10. Trust wording

| ID | Rule | Source |
| --- | --- | --- |
| CDN-10.1 | **Verification is a check, not an endorsement.** Never "approved", "certified", "official", "trusted" or "recommended by Bulbula" | C-12 |
| CDN-10.2 | Verified is always shown **with its date** | C-08 |
| CDN-10.3 | **There is no "unverified" label.** Absence is the signal | DSN-9.14 |
| CDN-10.4 | Staleness is stated honestly, never hidden and never apologised for | UXP-7.3 |
| CDN-10.5 | Bulbula never claims completeness it does not have — no "every business in the area" claim inside the product |

| Element | Copy |
| --- | --- |
| Verified indicator | **Verified {date}** |
| Verified, explained in place | "We confirmed these details with the business." |
| Stale record | **Last verified {date}** |
| No verification | *(nothing shown)* |
| Link to the explanation | **How we verify** |
| Open status | **Open** · **Closed** · **Hours not confirmed** |
| Next transition | **Closes at {time}** · **Opens at {time}** |
| Rating with count | **{average} ({count} reviews)** |
| One review | **{average} (1 review)** |

---

## 11. Sponsored wording

| ID | Rule | Source |
| --- | --- | --- |
| CDN-11.1 | The word is **"Sponsored"** — nothing else | LB-3, UR-02 |
| CDN-11.2 | The label appears **before** the content in reading order, visible without hover, scroll or expansion | LB-2 |
| CDN-11.3 | It is **part of the accessible name**, so it is announced (LB-7) |
| CDN-11.4 | Bulbula **adds no persuasive copy** to a sponsored result — no "recommended", no "top choice", no editorial endorsement | D-39 |
| CDN-11.5 | A sponsored result is never described as a search result or counted as one | LB-8 |
| CDN-11.6 | **No price, package name, inventory count or availability figure appears in any public copy** | D-10, D-11 |
| CDN-11.7 | An unsold placement produces **no copy at all** — no "advertise here" | PL-5 |

| Element | Copy |
| --- | --- |
| Group label | **Sponsored** |
| Explained in place | "This business paid for this placement." |
| Link to the explanation | **How ranking works** |
| On the ranking page | "Sponsored placements are always labelled and are shown separately from search results. Paying for a placement does not change a business's position in search results." |
| Advertising page CTA | **Contact us about sponsorship** |

---

## 12. Moderation, report and correction copy

| ID | Rule | Source |
| --- | --- | --- |
| CDN-12.1 | A confirmation promises **review**, never a fix, a removal or a date | UFL-A8.5 |
| CDN-12.2 | A moderation decision states the **policy ground** | `review-policy.md` §5.1 |
| CDN-12.3 | **Reporter identity is never referenced** in any copy shown to the author or the Business | TR-65 |
| CDN-12.4 | Decisions are stated neutrally; the Customer is not scolded |
| CDN-12.5 | Where an appeal route exists it is named; where it does not, the copy does not imply one |

| Element | Copy |
| --- | --- |
| Report form intro | **Tell us what's wrong** — "We review every report. You don't need an account." |
| Problem type label | **What's the problem?** |
| Description label | **Describe the problem** |
| Optional contact | **Your email (optional)** — "Only used if we need to ask you about this report." |
| Report sent | **Thanks — we'll review this.** |
| Review pending | **Your review is awaiting moderation.** |
| Review published | **Your review is published.** |
| Review rejected | **This review wasn't published** — "It doesn't meet our review policy: {ground}." |
| Review removed | **This review was removed** — "It doesn't meet our review policy: {ground}." |
| Link to the policy | **Review policy** |
| Review form guidance | "Write about your own experience with this business. Reviews that break our policy aren't published." |

---

## 13. Microcopy inventory

| Element | Copy |
| --- | --- |
| Search placeholder | **Search businesses, categories or areas** |
| Autocomplete group labels | **Businesses** · **Categories** · **Areas** |
| Result count | **{n} results** · **1 result** |
| Filtered count | **{n} results with your filters** |
| Sort label | **Sort by** |
| Filter trigger | **Filters** · **Filters ({n})** |
| Pagination | **Page {n} of {total}** |
| Breadcrumb root | **Home** |
| Discovery bar | **Categories** · **Areas** · **Nearby** · **Saved** |
| Category count | **{n} businesses** |
| Distance | **About {n} km away** |
| Branch selector | **Choose a branch** |
| Hours expander | **See all hours** |
| Gallery position | **{n} of {total}** |
| Skip link | **Skip to main content** |
| Loading | **Loading…** |
| Account menu, Guest | **Sign in** |
| Account menu, Customer | **Account** |
| Location request | **Use my location** — "We use your location to show what's nearby. We don't store it." |
| Location denied | **Location isn't available** — "Browse by area instead." |

---

## 14. SEO copy rules

| ID | Rule | Source |
| --- | --- | --- |
| CDN-14.1 | Every indexable page has a **unique** title and description | SEO-5 |
| CDN-14.2 | Titles are generated from real content — the Business name, Category, Area — never from a template that reads identically across hundreds of pages | SEO-5, SEO-9 |
| CDN-14.3 | A description summarises the page honestly; it is not keyword-stuffed | SEO-5 |
| CDN-14.4 | Heading text matches what is on the page | WCAG 2.4.6 |
| CDN-14.5 | Link text is meaningful out of context | WCAG 2.4.4 |
| CDN-14.6 | **No doorway copy, no keyword padding, no fake "best of" content** | UXP-5 |
| CDN-14.7 | A Category × Area page below the minimum-content rule gets no synthesised filler to pad it — it is simply not indexed | SEO-9 |

| Page | Title pattern `[P]` |
| --- | --- |
| Home | **Bulbula — business directory for {covered area}** |
| Category | **{Category} in {covered area}** |
| Subcategory | **{Subcategory} — {Category} in {covered area}** |
| Area | **Businesses in {Area}** |
| Category × Area | **{Category} in {Area}** |
| Business profile | **{Business} — {Category} in {Area}** |
| Static | **{Page title} — Bulbula** |

---

## 15. Operations console copy

| ID | Rule | Source |
| --- | --- | --- |
| CDN-15.1 | The console uses the **domain vocabulary exactly** — no friendlier synonyms (§3) |
| CDN-15.2 | A blocker is **named**: "Permission not recorded", "Verification not recorded" | OPX-0.6 |
| CDN-15.3 | A reason field says what the reason is for and that it is recorded |
| CDN-15.4 | Timestamps are absolute with a timezone | OPX-2.8 |
| CDN-15.5 | Destructive confirmations name the consequence and the count |
| CDN-15.6 | **No per-staff praise, score or leaderboard copy** | OPX-2.2 |

| Element | Copy |
| --- | --- |
| Empty queue | **Nothing waiting** |
| Duplicate check | **Possible duplicates** — "Check these before creating a new business." |
| Permission block | **Permission record** — "Required before this listing can be published." |
| Unknown value | **Unknown** *(distinct from empty)* |
| Completeness | **Missing: {fields}** |
| Publish blocked | **Can't publish — {blocker} is missing** |
| Return for rework | **Send back for changes** |
| Reason field | **Reason** — "Recorded in the audit log." |
| Correction prompt | **Why is this changing?** · **Where did this come from?** |
| Stale queue | **Needs re-verification** |
| Taxonomy delete blocked | **Can't delete — {n} businesses use this category** |
| Area delete blocked | **Can't delete — {n} branches are in this area** |
| Rebuild notice | **Updating {n} listings. This may take a moment.** |
| Campaign unavailable | **Not available — this placement is sold for {period}** |
| Delivery figures | **Delivery report** — "For reporting only. Delivery doesn't affect ranking." |

---

## 16. Copy that must never appear

| Never | Why |
| --- | --- |
| "Claim this business" | D-54 — no business accounts |
| "Are you the owner?" | D-54 |
| "Business owner? Sign in" | D-54 |
| "Reply to this review" | D-12 |
| "Upgrade", "Go Pro", "Premium" | No subscription product |
| "Advertise here" on an empty slot | PL-5 |
| "Promoted", "Featured", "Partner" for paid placement | LB-3, UR-02 |
| "Recommended by Bulbula" | D-39 |
| "Trusted business", "Certified", "Approved" | CDN-10.1 |
| "{n} people saved this" | UR-18 |
| "Was this helpful?" on a Review | D-37 |
| "Add a photo to your review" | D-36 |
| "Create an account to continue" as an interstitial | GS-3, GS-4 |
| "Join thousands of users" | CDN-1.5 |
| "Sign up free" | No paid tier exists for Customers |
| "Download our app" | No app exists |
| "Switch to Amharic" | No Amharic UI in V1 |
| "Dark mode" | D-19 |
| Any price, fee or rate | D-10, D-11 |
| Any legal assertion about rights, retention or obligations | **PENDING COUNSEL** |
| Any launch date, deadline or milestone | No dates are approved |

---

## 17. Governance

| ID | Rule |
| --- | --- |
| CDN-17.1 | Every string lives in one place and is reused; the same action never has two labels (WCAG 3.2.4) |
| CDN-17.2 | A new term is added to the glossary **before** it appears in the interface |
| CDN-17.3 | Legal and policy copy is owner-approved and **PENDING COUNSEL** where it touches L-5…L-22 |
| CDN-17.4 | Patterns written as `{placeholder}` are structural; the final wording of a variable outcome is approved before it ships |
| CDN-17.5 | **`[P]` copy is proposed, not approved.** Titles, lengths and exact phrasing require owner sign-off |

---

## 18. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-34 | Review state wording, and the deletion outcome in §8 | **Closed 2026-10-07.** Copy says 1 to 5, text optional, awaiting review before publication, editable for 30 days, and withdrawal on deletion. The retention period stays **PENDING COUNSEL** (L-21, D-46) |
| D-35 | Report form structure and its labels | Open — product detail |
| D-04 | Hours wording for special and 24-hour cases | Open — product detail |
| D-08 | What "Verified" means in detail, for the explanation page | Open — product detail |
| D-13 | Sign-in method wording on the Profile screen | Open — product detail |
| D-10 / D-11 | Advertising page wording beyond "contact us" | Open — product detail |
| D-18 | Amharic UI strings | Deferred — not V1 |
| D-28 | Wordmark wording and treatment | Open — product detail |
| D-46 | Any age-related copy | **PENDING COUNSEL** |
| L-5…L-22 | Privacy notice and terms wording | **PENDING COUNSEL** |
| — | Title patterns in §14 | `[P]` — require owner approval |

---

## Decision references

D-02, D-04, D-06, D-08, D-10, D-11, D-12, D-13, D-18, D-19, D-28, D-34,
D-35, D-36, D-37, D-39, D-46, D-48, D-54.
