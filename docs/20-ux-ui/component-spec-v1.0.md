# Component Specification

| | |
| --- | --- |
| **Document** | Component Specification — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The behavioural contract for every V1 interface component.

**This document contains no HTML, no CSS and no JavaScript.** It describes
what each component *is*, *does*, *shows* and *must never do*. Visual
values come from [`design-system-v1.0.md`](design-system-v1.0.md); all are
`[P] Proposed` until the owner approves them.

**Template.** Each component states: *Purpose · Anatomy · Variants ·
States · Responsive · Accessibility · Content rules · Interaction ·
Loading and error · Dependencies.*

**Global rules** (§0) apply to every component and are not repeated.

---

## 0. Rules applying to every component

| ID | Rule | Source |
| --- | --- | --- |
| CMP-0.1 | Primary targets ≥ 44 × 44 px; absolute floor 24 × 24 px with spacing | UR-01, WCAG 2.5.8 |
| CMP-0.2 | Visible focus on every interactive element, never obscured | WCAG 2.4.7, 2.4.11 |
| CMP-0.3 | Information is never conveyed by colour alone | WCAG 1.4.1 |
| CMP-0.4 | Every control has an accessible name; icon-only controls carry a text alternative | WCAG 4.1.2 |
| CMP-0.5 | No component requires dragging or a path-based gesture | WCAG 2.5.1, 2.5.7 |
| CMP-0.6 | Reading order matches visual order at every breakpoint | WCAG 1.3.2 |
| CMP-0.7 | No component is hover-only on any surface | WCAG 1.4.13 |
| CMP-0.8 | A component consumes semantic tokens only — never a raw value | DSN-1.1 |
| CMP-0.9 | A component works without JavaScript or degrades to a working link or form | MOB-5, D-16 |
| CMP-0.10 | Loading placeholders appear only past ~1 s | UR-11 |
| CMP-0.11 | Every error state names a recovery action | UFL-0.4 |
| CMP-0.12 | Wording comes from [`content-design-v1.0.md`](content-design-v1.0.md). No component invents a label | — |
| CMP-0.13 | A component never requests authentication except as the consequence of a gated action | GS-3 |
| CMP-0.14 | No component renders a count, badge, dot or unread marker in navigation | UXP-6.7 |
| CMP-0.15 | The same component serves both surfaces; only host chrome differs | SUR-5, D-49 |

---

# A. Navigation

## 1. Header

**Purpose.** Constant orientation and access to search and discovery.

**Anatomy.** Wordmark (temporary — DSN-11.3) · search field or full-width
search target · account control. Below it, on mobile, the discovery bar
(§3).

**Variants.** Web default · Web compact (scrolled) · **Mini App** —
wordmark row and back affordance suppressed because Telegram supplies
them (UR-12).

**States.** At top · scrolled (may gain `elevation.raised`) · search
focused.

**Responsive.** Mobile: two rows (identity + search). Desktop: one row with
navigation inline.

**Accessibility.** `banner` landmark · a skip link to main content as the
first focusable element (WCAG 2.4.1) · search is a labelled `search`
landmark · sticky header never obscures focus (WCAG 2.4.11).

**Content rules.** No slogan, no promotion, no announcement bar.

**Interaction.** Wordmark → Home. Search → §4.

**Loading/error.** None — the header never waits on data.

**Dependencies.** Search field, account menu, discovery bar.

---

## 2. Footer (Web)

**Purpose.** Policy, static pages and a consistent help route.

**Anatomy.** Static-page links · policy links · contact route · copyright.

**Variants.** Web only. The Mini App exposes the same links through the
account menu.

**Accessibility.** `contentinfo` landmark. The help route is in the same
place on every page (WCAG 3.2.6).

**Content rules.** No newsletter signup, no social-follow wall, no sitemap
dump. Links only to pages that exist (C-18).

**Dependencies.** Static pages.

---

## 3. Discovery bar

**Purpose.** Keep the discovery axes visible — hidden navigation roughly
halves discoverability (UR-06).

**Anatomy.** Categories · Areas · Nearby · Saved.

**Variants.** Mobile persistent bar · desktop inline header links.

**States.** Default · current (the item matching the current page is marked
by text weight or an indicator **and** a programmatic current marker, not
colour alone).

**Responsive.** Mobile: a row under the header, horizontally scrollable
only if it genuinely overflows — and then with a visible affordance.
Desktop: inline.

**Accessibility.** `navigation` landmark with a name. Current item marked
programmatically (WCAG 1.3.1). Items are links.

**Content rules.** Exactly these four. No promotional entry, no badge
(CMP-0.14).

**Interaction.** Saved as a Guest routes to sign-in **on tap** — never
hidden, never disabled (UXP-3.3).

**Dependencies.** None.

---

## 4. Breadcrumb

**Purpose.** Show position and provide the "up" route, especially for
Users arriving from an external search engine.

**Anatomy.** Ordered trail; separators are decorative.

**Variants.** Full · truncated (mobile) — truncation **always keeps the
immediate parent** (IAR-20).

**Accessibility.** `navigation` landmark named "Breadcrumb"; ordered list;
the final item is not a link and is marked as current (IAR-18, IAR-19).

**Content rules.** Real page names. No "Home › … › Here" with invented
levels.

**SEO.** Marked up as structured data (SEO-12).

**Dependencies.** IA hierarchy (IAR §3.3).

---

## 5. Account menu

**Purpose.** Access account functions without occupying primary navigation.

**Anatomy.** Trigger → menu. **Guest:** Sign in · static/policy links.
**Customer:** Profile · My Reviews · Saved · Privacy · Sign out.

**States.** Closed · open · keyboard-focused item.

**Responsive.** Mobile: bottom sheet or full-width menu. Desktop:
anchored dropdown.

**Accessibility.** Trigger states its expanded state; focus moves into the
menu; Escape closes and returns focus; arrow keys move between items
(WCAG 2.1.1, 2.1.2).

**Content rules.** No avatar, no email shown as decoration, no "upgrade",
no promotion.

**Interaction.** Sign out requires no confirmation; it is not destructive.

**Dependencies.** Auth state, bottom sheet, menu.

---

# B. Search

## 6. Search field

**Purpose.** The primary capability (C-02).

**Anatomy.** Label (visible or accessible) · input · clear control ·
**visible submit control** · autocomplete anchor.

**Variants.** Header (compact) · homepage (prominent) · results page
(pre-filled).

**States.** Empty · focused · typing · has value · submitted · error.

**Responsive.** Mobile: full width, roughly 27–30 characters of visible
text, submit adjacent (UR-04). Desktop: inline in the header.

**Accessibility.** A real label, not a placeholder alone (WCAG 3.3.2) ·
`search` landmark · appropriate input type and autocomplete hint ·
16 px minimum font to prevent iOS zoom (DSN-3.8) · the clear control is a
named button.

**Content rules.** Placeholder shows an **example query**, never an
instruction. The placeholder is never the only label.

**Interaction.** Enter submits · clear restores the empty state and keeps
focus · the submitted query **persists** on the results page (IAR-25) ·
over-length input is rejected with the limit stated, never silently
truncated (UFL-A1).

**Loading/error.** No spinner inside the field. A failed submit keeps the
query and offers retry.

**Dependencies.** Autocomplete.

---

## 7. Autocomplete / suggestions

**Purpose.** Reduce typing and reveal vocabulary (C-03).

**Anatomy.** Panel · grouped suggestions with group labels · each
suggestion showing its matched text and type · optional "see all results".

**Variants.** Business · Category · Subcategory · Area suggestions, each in
a labelled group and **visually distinguished from Business suggestions**
(UR-03).

**States.** Hidden · loading (only if genuinely slow) · suggestions ·
no suggestions (panel closes silently — **never** "no suggestions") ·
error (closes silently; typed search still works).

**Responsive.** Mobile: full-width panel, **4–8 suggestions** sized so none
sits under the keyboard (UR-03). Desktop: anchored dropdown, up to ~10.

**Accessibility.** Combobox semantics; the field keeps focus; the active
option is announced; arrow keys move and **wrap**; the active suggestion is
**copied into the field** so it can be edited; Escape closes and restores
the typed text (UR-03, WCAG 4.1.2).

**Content rules.** The matched portion is emphasised. A suggestion never
includes a Sponsored result (PL-1 lists the placements; autocomplete is
not one).

**Interaction.** Debounced. Tap or Enter navigates; a Business suggestion
goes straight to the profile; a Category or Area suggestion goes to that
page.

**Loading/error.** Failure is silent and non-blocking — suggestion failure
must never break search (`search-design.md` PF-4).

**Dependencies.** Search field.

---

## 8. Filter panel

**Purpose.** Narrow results (C-02).

**Anatomy.** Trigger with the active-filter count · groups (Category, Area,
Open now, Verified, Minimum rating) · per-group clear · Clear all · Apply.

**Variants.** Mobile bottom sheet · desktop persistent column.

**States.** No filters · filters applied · applying · zero results after
filtering (§A7 of user flows).

**Responsive.** Mobile: sheet with Apply pinned above the safe area;
closing without Apply discards. Desktop: changes apply immediately with no
Apply button.

**Accessibility.** Each group is a labelled fieldset; controls are real
checkboxes/radios; the sheet traps focus and returns it on close; the
result count change is announced (WCAG 4.1.3).

**Content rules.** Every filter is **named in plain words**. A filter with
no matching inventory is shown disabled with its zero count, or omitted —
never shown as available and then yielding nothing.

**Interaction.** Each change creates a history entry so Back undoes one
filter (IAR-29). Applied filters are also shown as removable chips beside
the results (§9).

**Loading/error.** Results show a loading state; filters stay interactive.
Failure keeps the previous results and says the filter could not be
applied.

**Dependencies.** Bottom sheet, chips, result list.

---

## 9. Filter chips / active-filter summary

**Purpose.** Make the current scope visible and removable (IAR-24).

**Anatomy.** A chip per active filter with a remove control; a Clear all
when more than one.

**States.** Default · focused · removing.

**Accessibility.** Each chip's accessible name includes both the filter and
the removal action. Removal announces the new result count.

**Content rules.** Chips name the filter **and its value** — "Area: Bole
Bulbula", not "Area".

**Interaction.** A scope applied automatically by context appears as a
chip and is removable (IAR-24).

**Dependencies.** Filter panel.

---

## 10. Sort control

**Purpose.** Change result order.

**Anatomy.** A labelled control beside the result count.

**Variants.** Options as decided by the ranking model. **Weights are not a
UX decision (D-09, SRCH-5).** The control exposes the option set; it never
exposes or implies weights.

**Accessibility.** A labelled native select or a listbox with full keyboard
support; the change announces the new order.

**Content rules.** Plain names — "Most relevant", "Nearest", "Highest
rated" `[P]`, subject to the ranking decision.

**Interaction.** Changing sort creates a history entry. Sort **never
changes the Sponsored/organic separation** (PL-6).

**Dependencies.** Result list.

---

# C. Content display

## 11. Business card

**Purpose.** The repeated unit of every list — the single most important
component in the product.

**Anatomy.**
1. Thumbnail or placeholder (1:1)
2. Business name — the heading
3. Category (and Subcategory where it adds meaning)
4. Area (and Sub-city as secondary)
5. Open status — three states (§25)
6. Rating — average **with** count, or nothing at all (UR-16)
7. Verified indicator with date, if verified (§24)
8. Distance, on Nearby only
9. Save control (§15)

**Variants.** Default · compact (dense lists) · **Sponsored** — identical
anatomy plus the label and container (§19). No other variant is larger,
bolder or more image-forward.

**States.** Default · hover (desktop, subtle) · focused · pressed ·
loading (skeleton) · saved · unavailable (a saved Business since
unpublished shows its current state — C-34).

**Responsive.** Mobile: thumbnail left, text right, the whole card a tap
target. Desktop: same inside a grid cell.

**Accessibility.** One primary link whose accessible name is the Business
name; the Save control is a **separate** control, not nested inside the
primary link; the card is a list item within a list; the thumbnail is
decorative when it adds nothing (DSN-11.23).

**Content rules.** A field that is missing is **omitted entirely**, with
its space — never "N/A", never a greyed placeholder (C-08, UXP-7.2). No
marketing copy. No truncated description teaser designed to force a click.

**Interaction.** The whole card navigates. Save does not navigate.

**Loading/error.** Skeleton mirrors the real layout (UR-11). A failed
thumbnail falls back to the placeholder without changing the card height
(DSN-11.22).

**Dependencies.** Save control, rating, verified indicator, open status,
image placeholder.

---

## 12. Result list

**Purpose.** Present cards with context.

**Anatomy.** Result count · sort · active-filter chips · **Sponsored group
(if any)** · organic list · pagination.

**States.** Loading · populated · empty · error · partially degraded (e.g.
distance unavailable).

**Accessibility.** A real list; the count is announced on change; the
heading structure places results under a heading (WCAG 1.3.1).

**Content rules.** The count is honest. Sponsored items are **never counted
in the organic result count** (LB-8).

**Interaction.** Order never changes without a User action.

**Loading/error.** Skeleton cards matching the real card layout. Error:
message plus retry, keeping the query and filters.

**Dependencies.** Business card, pagination, empty state, sponsored
placement.

---

## 13. Category / Area tile

**Purpose.** Browsable entry into the taxonomy and geography.

**Anatomy.** Name · optional icon · count of published Listings.

**States.** Default · focused · pressed.

**Responsive.** 2 columns mobile → up to 5 at `xl` (DSN-8.3).

**Accessibility.** A link; the count is part of the accessible name.

**Content rules.** **A Category or Area with no published Listings is not
rendered** (C-04, C-05, UXP-7.5). The count is real.

**Dependencies.** None.

---

## 14. Rating display

**Purpose.** Summarise Review sentiment (C-13).

**Anatomy.** Numeric average · a visual mark · **the count**.

**Variants.** Compact (card) · full (profile, with a distribution if
decided).

**States.** Has reviews · **no reviews — renders nothing at all**
(TR-63, UR-16).

**Accessibility.** The accessible name states the value **and** the count
in words; the visual mark is decorative; never colour alone.

**Content rules.** The average and the count are **inseparable** — one
rating must never look like twenty (UR-16). The rounding rule must be
consistent and must not round up to a higher whole.

**Interaction.** On a profile it may jump to the Reviews section. It is not
otherwise interactive.

**SEO.** Any structured-data rating must equal the visible one (SEO-6,
R-15).

**Dependencies.** None. **Scale is Open (D-34).**

---

## 15. Save control

**Purpose.** Private shortlisting (C-14).

**Anatomy.** Icon **plus** the word *Save* / *Saved*, or an icon with an
accessible name where space is tight.

**Variants.** Card (compact) · profile (full with label).

**States.** Not saved · saved · pending · error · guest (visually
identical — the gate appears only on tap).

**Accessibility.** A toggle button whose pressed state is programmatic; the
state change is announced; ≥ 44 px target (CMP-0.1).

**Content rules.** **Save / Saved only** — never Favourite, Like, Bookmark,
Wishlist, Follow (`glossary.md` §3). **No count is ever shown** (UR-18).

**Interaction.** Guest → sign-in in context → **returns and completes the
Save** (UFL-B4). Idempotent both ways.

**Loading/error.** Failure reverts to the true state and says so — no
optimistic lie (UFL-B4.5).

**Dependencies.** Auth prompt.

---

## 16. Verified indicator

**Purpose.** Show that Bulbula checked this record (C-12).

**Anatomy.** Icon · the word *Verified* · the verification **date**.

**States.** Verified · **not present** (absence is the signal) · stale —
shown with its honest date (UXP-7.3).

**Accessibility.** Icon, word and colour together; the accessible name
includes the date.

**Content rules.** **No "unverified" badge exists** (DSN-9.14). The
indicator never appears without a date (C-08). It is never purchasable and
never appears in or beside a Sponsored treatment (ADV-9, D-39).

**Dependencies.** Verification data. **Methods and tiers are Open (D-08).**

---

## 17. Hours and open status

**Purpose.** Answer "can I go now?" — one of the highest-value mobile facts
(UR-10, C-09).

**Anatomy.** Current status · the next transition in words · the full
weekly schedule, expandable.

**Variants.** Compact (card: status only) · full (profile).

**States.** **Open** · **Closed** · **Hours not confirmed** — three, always
(UR-17).

**Accessibility.** Status is text, not colour; each state has its own icon
or shape; the schedule is a table with day headers.

**Content rules.** "Hours not confirmed" is **neutral**, never an error or
an apology (UXP-7.3). Status is computed in the Business's local time
(TRD TR-92).

**Interaction.** The schedule expands in place; it does not open a modal.

**Dependencies.** **Hours model is Open (D-04)** — special hours, breaks,
24-hour and holiday handling are unresolved. The component states only
what the data supports.

---

## 18. Contact actions

**Purpose.** Let the User act on the business (C-11).

**Anatomy.** A row of actions: Call · Website · Directions · Social, each
with an icon **and** a label.

**States.** Available · pressed. **There is no disabled state**: an action
with no underlying contact point is **not rendered** (UFL-A5.1).

**Responsive.** Mobile: prominent, within thumb reach, high on the page.
Desktop: in the secondary column, same order.

**Accessibility.** Each is a link with the correct scheme; ≥ 44 px targets;
the accessible name states both the action and its target type.

**Content rules.** Phone numbers displayed in a consistent, readable
format. The website label shows the domain, not a raw URL. **No action is
gated** (GS-1).

**Interaction.** Hands off to the dialler, browser or maps application.
Recorded only as aggregate analytics (TRD TR-202).

**Dependencies.** Business data; maps hand-off (**D-21 open**).

---

## 19. Sponsored placement and label

**Purpose.** Show paid placement **without** compromising integrity
(C-16).

**Anatomy.** Group container · **"Sponsored" label at the top of the
group** · one or more cards of standard anatomy · clear separation from
organic results.

**Variants.** Search-result slot · category sponsorship · homepage
promotion. **These three and no others** (`advertising-products.md` §2).

**States.** Sold (renders) · **unsold — the whole block collapses, leaving
nothing** (PL-5, C-01).

**Responsive.** Mobile: the label is visible without scroll or expansion;
separation is maintained even where space is tight — shrink the cards, not
the separation (LB-5).

**Accessibility.** The label is part of the accessible name of each
sponsored result, so screen-reader users hear it **before** the content
(LB-7). Never conveyed by background colour alone (DSN-9.1).

**Content rules.** The word is **"Sponsored"**. Never Promoted, Featured,
Partner, Recommended, Top pick, or an icon alone (LB-3, UR-02). No
persuasive copy added by Bulbula.

**Interaction.** A sponsored card behaves exactly like an organic one and
leads to the **same unmodified profile** (UXP-5.8). It never appears twice
on one page (PL-7).

**Loading/error.** If placement data fails, the block **collapses
silently**; it never blocks or delays organic results (PL-5).

**Dependencies.** Business card. **Prices, inventory counts and density
are not set here** (D-10, D-11; PL-3/PL-4 are `[P]`).

---

## 20. Map block

**Purpose.** Show where a Branch is (C-10).

**Anatomy.** Address text · landmark · a static preview · "Open in maps" ·
optional interactive embed.

**Variants.** Static-only · interactive on demand.

**States.** Loaded · not yet loaded (deferred) · unavailable · no
coordinates.

**Responsive.** Mobile: bounded height, interaction only on explicit tap so
it never hijacks page scroll. Desktop: in the secondary column.

**Accessibility.** The map is **never the only** source of location: the
address and landmark are always present as text (MOB-5). An interactive
map must be keyboard-operable or clearly supplementary with a text
equivalent. A decorative static preview carries no alt text.

**Content rules.** A Branch without coordinates shows address and landmark
with **no empty map frame** (UXP-7.2).

**Interaction.** Deferred load; never render-blocking (DSN-12.6).

**Loading/error.** On failure the block degrades to address plus a
directions link, with no error shouting.

**Dependencies.** **D-21 open** — provider, embed strategy, fallback and
**L-19 terms of use**.

---

## 21. Photo gallery

**Purpose.** Show the place (C-22).

**Anatomy.** Primary image · thumbnails or a swipeable strip · counter ·
full-screen viewer.

**States.** No images (**block absent entirely**) · one image · several ·
loading · failed.

**Responsive.** Mobile: swipe with **tap-navigable** previous/next controls
as well (WCAG 2.5.1). Desktop: grid.

**Accessibility.** Every image has descriptive alt text (DSN-11.11); the
viewer is a modal with focus trapping, Escape to close and focus returned;
position is announced ("3 of 7"); no auto-advance (WCAG 2.2.2).

**Content rules.** Only media Bulbula has the right to publish (**L-18**).
No stock photography presented as the business.

**Loading/error.** Reserved space (DSN-8.12); a failed image is replaced by
the placeholder, not removed, so positions stay stable.

**Dependencies.** Modal. **Limits and sizes Open (D-25).**

---

## 22. Share control

**Purpose.** Send a profile to someone (C-17).

**Anatomy.** Trigger → native share, with copy-link fallback.

**Variants.** Web (platform share sheet) · Mini App (Telegram share) —
behind the adapter (IAR-39, D-49).

**States.** Default · sharing · copied (confirmation) · unsupported
(falls back to copy link).

**Accessibility.** A named button; the copied confirmation is announced.

**Content rules.** Shares the **canonical URL** — no session, filter or
tracking parameter (IAR-36).

**Interaction.** No account required; not recorded against a person
(IAR-40).

**Dependencies.** Toast, surface adapter.

---

## 23. Report control and form

**Purpose.** Let anyone flag a problem (C-15).

**Anatomy.** Trigger · form with problem type, description and optional
contact · submit · confirmation.

**Variants.** **Listing report — Guest-safe.** **Review report —
authenticated** (`interaction-permissions.md` §3).

**States.** Idle · open · submitting · submitted · validation error ·
rate-limited.

**Responsive.** Mobile: bottom sheet or its own page. Desktop: modal.

**Accessibility.** A real form with labelled fields; errors are associated
with their fields and focus moves to the first error (WCAG 3.3.1, 3.3.3);
submission is announced.

**Content rules.** The confirmation promises **review**, not a fix or a
date (UFL-A8.5). A rate-limited submission explains plainly without
disclosing the limit (UFL-A8.4).

**Interaction.** Guest-safe for a Listing; for a Review the auth prompt
appears on submit intent and returns here.

**Dependencies.** Form field, bottom sheet, toast. **Structured
suggestions Open (D-35).**

---

# D. Feedback and state

## 24. Empty state

**Purpose.** Turn an absence into a next step (UXP-7).

**Anatomy.** A plain statement of what is absent · **why**, if known · one
or two concrete next actions.

**Variants.** Zero search results (the fullest — UFL-A7) · empty category ·
empty area · empty Saved list · no reviews · no gallery.

**Content rules.** Honest, never apologetic, never cute, **never
fabricated**. It must not suggest content that does not exist. **No
illustration** (DSN-11.19). **Never filled with Sponsored content**
(UFL-A7.4).

**Accessibility.** A real heading; the actions are links or buttons; the
state is announced when it replaces results (WCAG 4.1.3).

**Dependencies.** Result list, Saved list, reviews.

---

## 25. Loading state

**Purpose.** Explain a genuine wait (UR-11).

**Variants.** Skeleton (lists and cards, mirroring the real layout) ·
inline indicator (within a control) · progress text (long operations,
console).

**Rules.** Nothing appears under ~1 s. A skeleton must match the real
layout or it causes a shift. Content that is already available renders
immediately — a skeleton never blocks it (UR-11). Loading is announced
politely; a busy state does not steal focus (WCAG 4.1.3). Skeleton
animation respects reduced motion (DSN-7.4).

**Dependencies.** Business card, result list.

---

## 26. Error state

**Purpose.** Explain a failure and offer a way forward.

**Variants.** Inline (a field) · section (one block failed, the page
works) · page (the page failed) · not found (404) · permission.

**Rules.** Plain language, no code, no internal identifier, no stack trace
(UFL-0.7). Every error names a recovery action (CMP-0.11). A permission
error **does not disclose whether the target exists** (UFL-0.8). A
section-level error never takes down the whole page — a failed map still
leaves the address. Errors are announced (WCAG 4.1.3).

**Content rules.** Say what happened, then what to do. Never "Oops!",
never "Something went wrong" alone.

**Dependencies.** Toast, empty state.

---

## 27. Pagination

**Purpose.** Navigate long lists predictably.

**Anatomy.** Previous · page indicators · next · current position.

**States.** First page · middle · last · single page (**the control is not
rendered**).

**Responsive.** Mobile: previous/next plus "Page 2 of 9". Desktop: numbered
pages.

**Accessibility.** A named `navigation` landmark; the current page is
marked programmatically; targets ≥ 44 px; focus moves to the top of the
results on page change and the new page is announced.

**Content rules.** Honest totals. An unknown total shows "next" without a
fabricated count.

**Interaction.** **Real links with real URLs.** Back returns to the
previous page (IAR-28). **No infinite scroll** (IAR-30).

**SEO.** Paginated pages canonicalise appropriately and do not create thin
duplicates (SEO-10).

**Dependencies.** Result list.

---

## 28. Toast / inline confirmation

**Purpose.** Confirm a small action without interrupting.

**Anatomy.** Short message · optional single action (e.g. Undo).

**States.** Entering · visible · dismissed.

**Rules.** **Never** used for errors that need a decision, and never the
only place important information appears. Long enough to read `[P]`;
dismissible; pauses on focus or hover. Announced politely (WCAG 4.1.3).
Never covers the control that triggered it, and never covers a focused
element (WCAG 2.4.11). Respects reduced motion.

**Content rules.** Fewer than ~8 words `[P]`. States what happened, in the
past tense.

**Dependencies.** None.

---

# E. Overlays

## 29. Modal dialog

**Purpose.** A decision that must be made before continuing.

**Anatomy.** Title · body · primary action · secondary action · close.

**States.** Closed · open · submitting.

**Responsive.** Mobile: near-full-screen. Desktop: centred, max 480 px.

**Accessibility.** `dialog` role, modal; focus moves in and is trapped;
Escape closes; focus returns to the trigger; the title names the dialog;
content behind is inert (WCAG 2.1.2, 2.4.3).

**Content rules.** Used **sparingly** — confirmations and destructive
actions only. **Never** for marketing, never for sign-in nagging (GS-4).

**Interaction.** Opening pushes a history entry; **Back closes it**
(IAR-31).

**Dependencies.** Scrim, buttons.

---

## 30. Bottom sheet

**Purpose.** The mobile pattern for choices — reachable by thumb (UXP-2.1).

**Anatomy.** Handle · optional title · content · actions pinned above the
safe area.

**States.** Closed · open · scrolled internally.

**Responsive.** Mobile only; desktop uses a dropdown or popover instead.

**Accessibility.** Same dialog semantics as §29. **Swipe-to-dismiss is an
enhancement**: a visible close control and Escape must both work (WCAG
2.5.1, 2.5.7). Sits above the safe-area inset and above the Telegram bottom
bar (UR-12). Must not be dismissed by a drag that is the only way out.

**Content rules.** One purpose per sheet. No stacked sheets.

**Interaction.** Back closes it (IAR-31). Inside Telegram, the **host**
Back button drives this (UR-12).

**Dependencies.** Scrim.

---

## 31. Confirmation dialog

**Purpose.** Prevent irreversible mistakes.

**Anatomy.** What will happen · what cannot be undone · a **specific**
confirm label · cancel.

**Rules.** The confirm button names the action — "Delete review", not
"OK" / "Yes". Cancel is the safe default and gets initial focus for a
destructive action. **Required for:** deleting a Review, deleting an
account, unpublishing a Listing, suspending a Campaign, closing a Business
(WCAG 3.3.4).

**Content rules.** State consequences honestly. Where an outcome is open —
e.g. the fate of Reviews after account deletion (D-34, L-21) — the dialog
must state the **actual decided** behaviour and must not guess (UFL-B9.5).

**Dependencies.** Modal.

---

# F. Forms and authentication

## 32. Form field

**Purpose.** Collect input correctly the first time.

**Anatomy.** Visible label · optional help text · control · error message ·
optional character counter.

**Variants.** Text · textarea · select · checkbox · radio · date.

**States.** Default · focused · filled · disabled (rare) · error ·
read-only.

**Accessibility.** A **persistent visible label** — placeholder-as-label is
forbidden (WCAG 3.3.2); the error is programmatically associated and
announced; required fields are marked in text as well as symbol; correct
input type, keyboard and autocomplete token (WCAG 1.3.5); ≥ 44 px target;
16 px text to prevent iOS zoom.

**Content rules.** Labels say what is wanted. Errors say what is wrong
**and how to fix it** — never "Invalid input".

**Interaction.** Validation on blur and on submit, **not** on every
keystroke. Focus moves to the first error. **Every other value is
preserved** (WCAG 3.3.7).

**Dependencies.** None.

---

## 33. OTP input

**Purpose.** Enter an emailed code (C-31).

**Anatomy.** Label · code field · resend with a visible cooldown · "use a
different address" · the address the code was sent to.

**States.** Empty · entering · verifying · incorrect · expired ·
attempts exhausted · cooldown.

**Accessibility.** **Paste must work** — including pasting into a split
digit field (WCAG 3.3.8); numeric keypad; the one-time-code autocomplete
token; each state announced; no CAPTCHA, no puzzle, no memory test
(AX-12).

**Content rules.** Failures are **generic** — never distinguishing "wrong
code" from "no such account" (UFL-B2.3). The cooldown is shown as a
countdown.

**Interaction.** Length, validity, attempt ceiling and request quota come
from configuration and are **never hard-coded** (UFL-B2.5, TRD OT-02).

**Dependencies.** Form field, auth prompt.

---

## 34. Authentication prompt

**Purpose.** Ask for an account **only** when an action requires one.

**Anatomy.** What is being unlocked · Google · email OTP · cancel.

**Variants.** Inline (preferred) · sheet · dedicated page for a direct
visit.

**Rules.** Appears **only** on attempting a gated action (GS-3). **Never**
an interstitial, never a nag, never on page load, never with a dismiss
penalty (GS-4). States what Bulbula receives. **Inside Telegram, email OTP
is listed first** (UR-14, TG-5). Cancelling returns the User to exactly
where they were, unchanged. On success, **returns to the task and
completes it** with input preserved (UFL-0.1).

**Content rules.** No scarcity, no "join thousands", no benefit list beyond
the action at hand.

**Dependencies.** OTP input, bottom sheet.

---

## 35. Button

**Purpose.** Trigger an action.

**Variants.** Primary (brand) · secondary · tertiary/text · destructive ·
icon-only (named).

**States.** Default · hover · focus · active · disabled (rare) · loading.

**Rules.** **At most one primary action visible at a time** (DSN-2.5).
≥ 44 px. A loading button keeps its width so nothing shifts, and announces
its busy state. A disabled button must be avoidable: prefer an enabled
control that explains the blocker (DSN-10.8). Actions fire on pointer-up
(WCAG 2.5.2).

**Content rules.** A **verb describing the outcome** — "Save", "Send
report", "Delete review". Never "Submit", "OK", "Click here".

**Dependencies.** None.

---

## 36. Link

**Rules.** Links navigate; buttons act — never swapped. Underlined or
otherwise distinguished from body text by more than colour (WCAG 1.4.1).
Link text makes sense out of context (WCAG 2.4.4) — never "here" or "read
more" alone. External links are indicated. Nothing opens a new window
without saying so.

---

# G. Operations console

## 37. Data table

**Purpose.** Work through queues and records efficiently (C-19…C-29).

**Anatomy.** Column headers · sortable columns · rows · row actions ·
selection where bulk actions exist · pagination · result count.

**States.** Loading · populated · empty · error · filtered · a row
mid-action.

**Responsive.** Desktop-first — this is a Web-only surface
(`scope-v1.md` §1.5). Below `lg`: horizontal scroll with a sticky first
column, or stacked rows. **No data is hidden** (DSN-8.4).

**Accessibility.** A real table with header cells and scope; sort state is
announced; row actions have names that include the row subject ("Publish —
Tsegaye Pharmacy"); keyboard-navigable.

**Content rules.** Timestamps in a consistent format with a timezone.
Actors are named. Identifiers are shown where staff need them.

**Interaction.** Filters and sorting are reflected in the URL so a queue
view is shareable between staff.

**Dependencies.** Pagination, status badge, filter panel.

---

## 38. Console filter bar

**Purpose.** Narrow a queue (state, date range, actor, Category, Area).

**Rules.** Filters persist within a session; the active set is always
visible; Clear all is always present; the filtered count is shown and
announced; filter state lives in the URL.

**Content rules.** Named states matching the domain vocabulary exactly —
draft, in review, published, unpublished, closed (`listing-operations.md`).

**Dependencies.** Data table, chips.

---

## 39. Status badge

**Purpose.** Show the state of a record at a glance.

**Variants.** Listing state · Review moderation state · Report state ·
Campaign state · Verification freshness.

**Rules.** **Text always**; colour and shape are reinforcement only (WCAG
1.4.1). States come from the domain model — no invented state. A badge is
never interactive; the action is a separate control.

**Content rules.** The exact domain word, not a friendlier synonym.

**Dependencies.** Data table.

---

## 40. Record detail panel

**Purpose.** The full working view of one record.

**Anatomy.** Identity header with state · the record's fields grouped ·
**Permission** block · **Provenance** block · **Verification** block ·
completeness indicator · Correction history · audit trail · actions.

**States.** Viewing · editing · saving · conflict · blocked from
publishing.

**Accessibility.** Headings per section; the blocked-publication reason is
announced and is adjacent to the blocked action.

**Content rules.** A publication blocker is **named** — "Permission not
recorded", "Verification not recorded" (UFL-C3). Completeness is shown as a
state; its **weighting is Open (D-09)** and is never presented as a score
the Business can be judged by.

**Interaction.** A stale concurrency token produces a **conflict message
showing what changed and who changed it**; the edit is never silently
overwritten (TR-57). Changing a published field requires a reason and
source (TR-52).

**Dependencies.** Form field, status badge, confirmation dialog, audit
list.

---

## 41. Audit trail list

**Purpose.** Show who did what, when, and why (C-29).

**Anatomy.** Actor · action · target · timestamp · reason.

**Rules.** **Append-only and read-only** — no edit control and no delete
control exists anywhere in the interface (DO-8). Filterable and paginated.
Entries survive Customer deletion and contain no personal data (TR-167).
Visible to the **`audit.read`** permission — **Administrator-only**
(`interaction-permissions.md` §4).

**Dependencies.** Data table.

---

## 42. Duplicate-detection panel

**Purpose.** Stop duplicate Businesses at creation (C-19, TR-55).

**Anatomy.** Candidate matches by name and Area · similarity context ·
"this is the same business" / "this is different" · a link to each
candidate.

**Rules.** Shown **before** any data entry (UFL-C1). Choosing "different"
is recorded. Proceeding past candidates is permitted but auditable.

**Content rules.** Never asserts a match; it presents candidates for a
human to judge.

**Dependencies.** Data table, record detail.

---

## 43. Campaign form

**Purpose.** Create and manage sponsorship (C-27).

**Anatomy.** Business · Placement · target (Category/Area/search) · period
· package · availability check · state · actions.

**Rules.** **No bid, budget, CPC, CPM or CPA field exists** (D-10, CR-3).
No price value is pre-filled — pricing is **Open (D-11)**. An unavailable
Placement and period is refused with a clear conflict (TR-72). Approval,
activation and early termination are **Administrator** actions and require
a reason (UFL-C7.2). Delivery figures are shown as **reporting only**
(TR-74). The form **cannot** reach Listing content, verification, trust
indicators or Reviews (ADV-9).

**Dependencies.** Form field, status badge, confirmation dialog.

---

## 44. Completeness indicator

**Purpose.** Show how complete a Listing is (C-19, C-21).

**Rules.** Lists **which fields are missing**, not just a percentage. It is
an internal operations signal: it is **never shown on a public page** and
never affects public ranking unless the ranking decision says so. The
weighting is **Open (D-09)**.

**Content rules.** Neutral. Not a score, not a grade, not a league table.

**Dependencies.** Record detail panel.

---

## 45. Component inventory

| # | Component | Primary capabilities |
| --- | --- | --- |
| 1 | Header | C-01…C-18 |
| 2 | Footer | C-18 |
| 3 | Discovery bar | C-04, C-05, C-07, C-34 |
| 4 | Breadcrumb | C-04, C-05, C-06, C-08, C-37 |
| 5 | Account menu | C-33 |
| 6 | Search field | C-02 |
| 7 | Autocomplete | C-03 |
| 8 | Filter panel | C-02 |
| 9 | Filter chips | C-02 |
| 10 | Sort control | C-02 |
| 11 | Business card | C-02, C-04…C-08, C-14 |
| 12 | Result list | C-02, C-04, C-05, C-06 |
| 13 | Category / Area tile | C-04, C-05 |
| 14 | Rating display | C-13 |
| 15 | Save control | C-14, C-34 |
| 16 | Verified indicator | C-12 |
| 17 | Hours and open status | C-09 |
| 18 | Contact actions | C-11 |
| 19 | Sponsored placement and label | C-16 |
| 20 | Map block | C-10 |
| 21 | Photo gallery | C-22 |
| 22 | Share control | C-17 |
| 23 | Report control and form | C-15 |
| 24 | Empty state | all lists |
| 25 | Loading state | all async |
| 26 | Error state | all |
| 27 | Pagination | C-02, C-04…C-06, C-13 |
| 28 | Toast | C-14, C-15, C-17 |
| 29 | Modal | C-22, C-36 |
| 30 | Bottom sheet | C-02, C-15, C-17 |
| 31 | Confirmation dialog | C-35, C-36, C-20, C-27 |
| 32 | Form field | C-13, C-15, C-19, C-20, C-33 |
| 33 | OTP input | C-31 |
| 34 | Auth prompt | C-30, C-31, C-32 |
| 35 | Button | all |
| 36 | Link | all |
| 37 | Data table | C-19…C-29 |
| 38 | Console filter bar | C-19…C-29 |
| 39 | Status badge | C-19…C-27 |
| 40 | Record detail panel | C-19, C-20, C-21 |
| 41 | Audit trail list | C-29 |
| 42 | Duplicate-detection panel | C-19 |
| 43 | Campaign form | C-27 |
| 44 | Completeness indicator | C-19, C-21 |

**44 components.** Every V1 screen is built from this set; a screen needing
something outside it is a signal to revisit the specification, not to
improvise (DSN-13.1).

---

## 46. Components deliberately **not** specified

| Not specified | Why |
| --- | --- |
| Owner reply field on a Review | D-12, D-54 |
| Business claim or dashboard control | D-54 |
| Review photo upload | D-36 |
| Helpful / useful vote | D-37 |
| Follow, like, friend, message | Not in V1 |
| In-product notification centre or bell | Email only (D-24) |
| Carousel or auto-advancing banner | DSN-7.3 |
| Onboarding tour or coach marks | UXP-3.4 |
| Cookie banner | **PENDING COUNSEL** — presence and form depend on L-5/L-6 |
| Chat or live-support widget | Not in V1 |
| Dark-mode toggle | D-19 — readiness only |
| Language switcher | No full Amharic UI in V1 (PRD §13, D-18) |
| Sponsorship self-service purchase control | D-10 |

---

## 47. Open items

| ID | Item | Components affected |
| --- | --- | --- |
| D-04 | Hours model | 17 |
| D-08 | Verification methods and tiers | 16, 40 |
| D-09 | Completeness weighting and ranking use | 10, 44 |
| D-10 / D-11 | Ad pricing and billing | 19, 43 |
| D-13 | Identity linking | 5, 34 |
| D-14 | Operator / Administrator split | 37–44 |
| D-16 / D-17 | Frontend JS and view layer | all (enhancement layer) |
| D-19 | Dark mode | all (readiness only) |
| D-21 | Maps | 20 |
| D-25 | Media limits | 21 |
| D-28 | Logo | 1 |
| D-34 | Review mechanics | 14, 31 |
| D-35 | Structured guest suggestions | 23 |
| D-38 | Mini App navigation | 1, 29, 30 |
| D-55 | Branch versus Business attributes | 11, 17, 18, 20 |
| TRD OT-02 | OTP parameters | 33 |
| L-18 / L-19 | Photo rights, maps terms | 20, 21 |

---

## Decision references

D-04, D-08, D-09, D-10, D-11, D-12, D-13, D-14, D-16, D-17, D-18, D-19,
D-21, D-24, D-25, D-28, D-34, D-35, D-36, D-37, D-38, D-39, D-49, D-54,
D-55.
