# Design System

| | |
| --- | --- |
| **Document** | Design System — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

---

## 0. Approval status — read this first

**D-53 approved exactly one thing:** the brand direction is
**orange primary, blue secondary, white-dominant light mode**.

| Approved | Not approved |
| --- | --- |
| Orange as primary | Any specific orange |
| Blue as secondary | Any specific blue |
| White-dominant light mode | Any specific neutral ramp |
| — | Typeface, type scale, logo, icon style, illustration style, shadow values, radius values, motion values |

Everything in this document that proposes a **value** is marked
**`[P] Proposed`** and **requires owner approval before implementation**.
Everything that defines a **structure** — what tokens exist, what they
mean, how they are chosen — is a design requirement and is not awaiting
approval.

| ID | Rule |
| --- | --- |
| DSN-0.1 | No `[P]` value may be treated as decided. Implementation of visual values is blocked until the owner approves them |
| DSN-0.2 | **No logo is invented in this document.** See §11 |
| DSN-0.3 | Approving a value changes the value, not the token architecture |
| DSN-0.4 | Where a value is marked `[P]`, the *contrast and target-size requirements around it are not* — those come from WCAG 2.2 AA and are binding regardless (NFR-AC1) |

---

## 1. Token architecture

Bulbula uses **three tiers**. Components reference **only the third tier**.

```text
Tier 1  PRIMITIVE      raw values, no meaning
        orange-500, blue-600, neutral-100, size-4, duration-fast
                 │
                 ▼
Tier 2  SEMANTIC       meaning, independent of component
        color.brand, color.text.primary, color.border,
        space.component, radius.control, elevation.raised
                 │
                 ▼
Tier 3  COMPONENT      a component's contract with the system
        button.primary.background, card.padding, input.border.focus
```

| ID | Rule |
| --- | --- |
| DSN-1.1 | A component **never** references a primitive. If it needs one, a semantic token is missing |
| DSN-1.2 | A semantic token names its **role**, never its appearance. `color.brand`, never `color.orange` |
| DSN-1.3 | **Dark-mode readiness** means the semantic tier is complete enough that a second primitive mapping is the whole change. It does **not** mean shipping dark mode (D-19) |
| DSN-1.4 | Tokens are defined once and consumed by both surfaces through the adapter (D-49) |
| DSN-1.5 | A new token requires a justified role. The set stays small deliberately |
| DSN-1.6 | **No token is specific to a Sponsored placement's persuasive styling.** Sponsored uses the same system with the differentiation rules of §9 |

---

## 2. Colour

### 2.1 The semantic colour set

Every token below is **required**. Values are `[P]`.

| Token | Role | Notes |
| --- | --- | --- |
| `color.brand` | Primary action, active state, brand presence | Orange (D-53) |
| `color.brand.hover` / `.active` | Interaction states of brand | Derived |
| `color.brand.subtle` | Tinted background for brand emphasis | Must carry text at AA |
| `color.on-brand` | Text/icon **on** brand | ≥ 4.5:1 on `color.brand` |
| `color.secondary` | Secondary action, links, informational accent | Blue (D-53) |
| `color.secondary.subtle` / `color.on-secondary` | As above | — |
| `color.background` | Page canvas | White-dominant (D-53) |
| `color.surface` | Cards, panels | Distinguishable from background **without relying on a shadow** |
| `color.surface.elevated` | Sheets, modals, menus, popovers | — |
| `color.surface.sunken` | Wells, skeleton tracks | — |
| `color.text.primary` | Body and headings | ≥ 7:1 on background `[P]` — exceeds AA deliberately (UR-09) |
| `color.text.secondary` | Supporting text | ≥ 4.5:1 |
| `color.text.muted` | Least important text still meant to be read | **≥ 4.5:1 — "muted" never means "below AA"** |
| `color.text.on-surface` / `.inverse` | Text on surfaces / dark backgrounds | — |
| `color.border` | Default separation | ≥ 3:1 where it is the only boundary of a control |
| `color.border.strong` | Input borders, emphasised separation | ≥ 3:1 |
| `color.border.subtle` | Decorative dividers | Exempt — carries no information |
| `color.focus` | Focus indicator | ≥ 3:1 against **both** the control and its surroundings |
| `color.status.success` / `.warning` / `.error` / `.info` | Feedback | Each with `.subtle` and `.on-*` |
| `color.trust.verified` | Verified indicator | **Always with an icon and text** (§9) |
| `color.commercial.sponsored` | Sponsored container | **Must not resemble `color.brand`** (§9) |
| `color.status.open` / `.closed` / `.unconfirmed` | Open status — **three** states | Never colour alone (UR-17) |
| `color.rating` | Rating marks | Never the only carrier of the value |
| `color.overlay` | Scrim behind modals and sheets | — |
| `color.skeleton` | Loading placeholder | — |

### 2.2 Colour rules

| ID | Rule | Source |
| --- | --- | --- |
| DSN-2.1 | **Colour is never the only means of conveying information** | WCAG 1.4.1, NFR-AC3 |
| DSN-2.2 | Body text ≥ **4.5:1**; large text ≥ **3:1** | WCAG 1.4.3 |
| DSN-2.3 | UI components and meaningful graphics ≥ **3:1** | WCAG 1.4.11 |
| DSN-2.4 | `color.brand` is reserved for **primary action and brand presence**. Spending it on decoration destroys its signal | UXP-6.2 |
| DSN-2.5 | A page shows **at most one** primary brand-coloured action in a viewport | UXP-6.3 |
| DSN-2.6 | **Sponsored styling must not borrow brand colour**; "endorsed by Bulbula" is the exact wrong signal | UR-02, LB-6 |
| DSN-2.7 | Verified uses `color.trust.verified` **plus** icon **plus** text. Three carriers | UR-07 |
| DSN-2.8 | `color.status.error` is never used decoratively | — |
| DSN-2.9 | Contrast is verified against the **rendered** background, including `.subtle` tints and overlays | — |
| DSN-2.10 | Each `.subtle` token is paired with a tested `.on-*` token; the pair ships together | — |

### 2.3 Proposed values `[P]`

**No hex values are proposed in this document.** Proposing a palette here
would make the colour decision by default, which D-53 explicitly did not
make. What is specified instead is the **selection brief** the owner's
chosen values must satisfy:

| ID | Requirement for the final orange `[P] brief` |
| --- | --- |
| DSN-2.11 | A tone dark enough that **white text on it reaches 4.5:1** at body size, or the brand button uses dark text and that pairing is tested |
| DSN-2.12 | A ramp of at least: subtle tint, base, hover, active, and a text-safe dark step |
| DSN-2.13 | Distinguishable from the warning/alert family so brand actions do not read as warnings |
| DSN-2.14 | Distinguishable from the Sponsored container colour |
| DSN-2.15 | Legible on low-quality and strongly colour-shifted phone displays — orange is the most variable hue across cheap panels |

| ID | Requirement for the final blue `[P] brief` |
| --- | --- |
| DSN-2.16 | Clearly distinct from the informational status colour, or deliberately unified with it and documented |
| DSN-2.17 | Reaches 4.5:1 on white at body size for link text |
| DSN-2.18 | Does not read as "primary" beside the orange |

| ID | Requirement for the neutral ramp `[P] brief` |
| --- | --- |
| DSN-2.19 | White-dominant canvas (D-53); surfaces differentiated by a **very low-contrast tint or a border**, never by shadow alone |
| DSN-2.20 | At least seven steps so text tiers, borders and skeletons each have their own |
| DSN-2.21 | Slightly warm or neutral — not cool — so it sits with orange without a colour clash `[P]` |

### 2.4 Dark-mode readiness

| ID | Rule |
| --- | --- |
| DSN-2.22 | **Dark mode is not a V1 feature** (D-19 open). This document ships *readiness*, not the theme |
| DSN-2.23 | Readiness means: no component hard-codes a colour; every colour flows from the semantic tier; `color.background` is never assumed to be white in logic; elevation never depends on a shadow alone (shadows disappear on dark) |
| DSN-2.24 | Images, logos and illustrations are chosen so that a later dark theme needs **no second asset set**, or the asset is explicitly flagged as needing one |
| DSN-2.25 | Telegram's `themeParams` are mapped **onto the semantic tier**, not into a second palette (UR-12). This is also what makes the Mini App respect the host theme without a product decision |
| DSN-2.26 | Readiness adds **no runtime cost** to V1 — no theme switcher, no second stylesheet, no preference storage |

---

## 3. Typography

### 3.1 Strategy

| ID | Rule | Source |
| --- | --- | --- |
| DSN-3.1 | **No paid font.** Licence cost is an ongoing obligation the project has not accepted | Owner constraint |
| DSN-3.2 | The default stack is a **system font stack** — zero bytes, zero requests, no FOIT, no layout shift, correct rendering on low-end Android | UR-08, NFR-P |
| DSN-3.3 | The stack names an **Ethiopic fallback explicitly**, because an unqualified system stack resolves Ethiopic inconsistently and on some platforms not at all | UR-08 |
| DSN-3.4 | A `unicode-range`-scoped Ethiopic webfont (SIL OFL, e.g. Noto Sans Ethiopic) may be added as a **conditional enhancement** that downloads only when Ethiopic characters are rendered. It is never on the Latin critical path | UR-08, SEO-14 |
| DSN-3.5 | The interface is **English-first and bilingual-ready**: Amharic Aliases, names and Area labels must render correctly today even though the UI is English (D-18) |
| DSN-3.6 | **No full Amharic UI in V1** (PRD §13). The type system must not make one expensive later |
| DSN-3.7 | Line length, line height and scale are chosen for Ethiopic as well as Latin: Ethiopic syllabics are visually denser and need **more line height**, not less `[P]` |

### 3.2 Proposed stack `[P]`

```text
--font-ui:   system-ui, -apple-system, "Segoe UI", Roboto,
             "Helvetica Neue", Arial, "Noto Sans",
             "Noto Sans Ethiopic", Nyala, Kefa, sans-serif

--font-mono: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace
             (operations console only: identifiers, timestamps, diffs)
```

`[P]` — the exact ordering requires testing on the target device mix.
`Nyala` and `Kefa` are platform Ethiopic faces; neither exists on iOS, which
is why DSN-3.4 exists.

### 3.3 Type scale `[P]`

A **modest** scale. A directory is read, not admired.

| Role | Size | Weight | Line height | Use |
| --- | --- | --- | --- | --- |
| `display` | 28–32 px | 700 | 1.2 | Home statement only |
| `h1` | 24 px | 700 | 1.25 | Page title, Business name |
| `h2` | 20 px | 600 | 1.3 | Section |
| `h3` | 17 px | 600 | 1.35 | Sub-section, card title |
| `body` | 16 px | 400 | **1.5** | Default. **Never smaller for primary content** |
| `body-sm` | 14 px | 400 | 1.45 | Supporting |
| `caption` | 13 px | 400/500 | 1.4 | Metadata, timestamps, labels |
| `label` | 13–14 px | 600 | 1.3 | Form labels, the Sponsored label |
| `overline` | 12 px | 600 | 1.3 | Rare. **Never for anything that must be read to decide** |

| ID | Rule | Source |
| --- | --- | --- |
| DSN-3.8 | Body text is **never below 16 px** on mobile — smaller text also triggers input zoom on iOS | UR-09 |
| DSN-3.9 | Sizes are declared in **relative units** so OS text-size settings work | WCAG 1.4.4 |
| DSN-3.10 | **200 % zoom without loss of content or function**; **no horizontal scrolling at 320 px equivalent** | WCAG 1.4.4, 1.4.10 |
| DSN-3.11 | Text spacing overrides must not clip content | WCAG 1.4.12 |
| DSN-3.12 | Measure is capped at roughly 70–80 characters `[P]` |
| DSN-3.13 | **Weight carries hierarchy before size does.** Three sizes and two weights beat six sizes | UXP-6.4 |
| DSN-3.14 | **No all-caps for sentences.** Acceptable only for a short label | — |
| DSN-3.15 | Heading *level* is semantic and independent of visual size (WCAG 1.3.1); a visually small heading is still the correct level |

---

## 4. Spacing

A **4 px base with an 8 px rhythm**: 4 for optical adjustment inside
controls, 8 and its multiples for everything structural.

| Token | Value `[P]` | Use |
| --- | --- | --- |
| `space.3xs` | 2 px | Hairline optical nudges |
| `space.2xs` | 4 px | Icon-to-label |
| `space.xs` | 8 px | Inside a control; chip padding |
| `space.sm` | 12 px | Tight related elements |
| `space.md` | 16 px | **Default** — card padding, gutters |
| `space.lg` | 24 px | Between components |
| `space.xl` | 32 px | Between sections |
| `space.2xl` | 48 px | Major section break, desktop |
| `space.3xl` | 64 px | Page-level breathing, desktop |

### 4.1 Applied spacing `[P]`

| Context | Mobile | Tablet | Desktop |
| --- | --- | --- | --- |
| Page gutter | 16 px | 24 px | 32 px |
| Between sections | 32 px | 40 px | 48 px |
| Between components | 16 px | 16 px | 24 px |
| Card padding | 16 px | 16 px | 20 px |
| Between cards in a list | 8–12 px | 12 px | 16 px |
| Inside a list row | 12 px vertical | 12 px | 12 px |
| Input padding | 12 px vertical / 14 px horizontal | same | same |
| Between form fields | 20 px | 20 px | 20 px |
| Between a label and its field | 6 px | 6 px | 6 px |
| Between a field and its error | 4 px | 4 px | 4 px |

| ID | Rule |
| --- | --- |
| DSN-4.1 | **Spacing, not lines, is the primary separator.** Dividers only where proximity genuinely misleads (UXP-6.5) |
| DSN-4.2 | Space between groups always exceeds space within a group (WCAG 1.3.1 proximity) |
| DSN-4.3 | Card spacing never falls so low that two adjacent targets fail the 24 px separation allowance (WCAG 2.5.8) |
| DSN-4.4 | Vertical rhythm is preserved when a block is absent — removing a missing field removes its space too, leaving no gap (UXP-7.2) |

---

## 5. Radius

| Token | Value `[P]` | Applied to |
| --- | --- | --- |
| `radius.none` | 0 | Full-bleed media, table cells |
| `radius.sm` | 4 px | Chips, badges, the Sponsored label, inline tags |
| `radius.control` | 8 px | Buttons, inputs, selects |
| `radius.surface` | 12 px | Cards, panels |
| `radius.sheet` | 16 px top only | Bottom sheets, modals |
| `radius.full` | 9999 px | Avatars, count bubbles, toggles |

| ID | Rule |
| --- | --- |
| DSN-5.1 | Radius is **semantic**: a card is `radius.surface` because it is a card, not because 12 looked right |
| DSN-5.2 | At most **four** distinct radii in the visible interface |
| DSN-5.3 | Nested radii reduce inward — a child inside a `radius.surface` card uses `radius.control` or `radius.sm` |
| DSN-5.4 | `radius.full` is **never** applied to a rectangular text button: a pill button reads as a chip and invites the wrong gesture `[P]` |

---

## 6. Elevation

| Token | Appearance `[P]` | Use |
| --- | --- | --- |
| `elevation.flat` | No shadow; border or surface tint only | **Default for cards** |
| `elevation.raised` | Very subtle, short, low-opacity | Sticky header once scrolled; hovered desktop card |
| `elevation.overlay` | Clearly separated | Dropdowns, autocomplete, popovers |
| `elevation.modal` | Strongest, plus scrim | Modals, bottom sheets |

| ID | Rule |
| --- | --- |
| DSN-6.1 | **Elevation is restrained.** A page of floating cards is noise, and shadow rendering is a real cost on low-end GPUs (UXP-8.6) |
| DSN-6.2 | Elevation always means **"this is above the page"** — never decoration |
| DSN-6.3 | A shadow is **never the only** boundary: every elevated surface also has a border or a distinct surface colour. This is what makes dark mode and high-contrast mode work (DSN-2.23) |
| DSN-6.4 | **Four levels, no more.** No z-index ladder beyond: base → sticky → overlay → modal → toast |
| DSN-6.5 | Cards in lists are **flat**. Elevating every result makes the list heavier without making it clearer |

---

## 7. Motion

| Token | Value `[P]` | Use |
| --- | --- | --- |
| `duration.instant` | 0 ms | State the User must perceive as immediate |
| `duration.fast` | 120 ms | Hover, focus, small state change |
| `duration.base` | 200 ms | Expand/collapse, toast, fade |
| `duration.slow` | 280 ms | Bottom sheet, modal entry |
| `easing.standard` | ease-out | Entering, appearing |
| `easing.exit` | ease-in | Leaving |
| `easing.emphasis` | gentle spring-like curve | Sheet entry only |

| ID | Rule | Source |
| --- | --- | --- |
| DSN-7.1 | Motion **explains a relationship** — where something came from, what it belongs to. Motion with no explanation is deleted | UXP-6.6 |
| DSN-7.2 | **Nothing exceeds ~300 ms.** Longer feels broken on a slow device and wastes the User's time | UXP-8.8 |
| DSN-7.3 | **No decorative animation, no parallax, no scroll-triggered reveal, no looping animation, no auto-advancing carousel** | UXP-6.6, D-52 |
| DSN-7.4 | `prefers-reduced-motion: reduce` removes movement entirely, retains opacity changes under ~100 ms, and **never** removes the state change itself | WCAG 2.3.3, NFR-AC5 |
| DSN-7.5 | Nothing moves, blinks or auto-updates for more than 5 s without a control to stop it | WCAG 2.2.2 |
| DSN-7.6 | Animation **never gates content**: content is readable the moment it exists | UXP-8.8 |
| DSN-7.7 | Transitions use compositor-friendly properties only — transform and opacity | NFR-P |
| DSN-7.8 | In the Mini App, Bulbula does **not** animate anything the Telegram host already animates | UR-12 |

---

## 8. Responsive system

### 8.1 Breakpoints — derived from layout, not from a framework

Each breakpoint exists because a **specific layout fact changes there**.

| Name | Range `[P]` | The layout fact that justifies it |
| --- | --- | --- |
| `base` | ≥ 320 px | Single column. The floor: WCAG 1.4.10 requires no horizontal scroll at 320 px |
| `sm` | ≥ 400 px | Cards gain internal horizontal room; a thumbnail can sit beside text without squeezing the title `[P]` |
| `md` | ≥ 680 px | A two-column result grid becomes readable while a card keeps a sane measure; filters can sit inline `[P]` |
| `lg` | ≥ 960 px | A persistent filter column plus results fits without either falling below its minimum `[P]` |
| `xl` | ≥ 1280 px | Content reaches its maximum measure; further width becomes margin, not content `[P]` |

| ID | Rule |
| --- | --- |
| DSN-8.1 | **Mobile-first**: base styles are the mobile styles; breakpoints only add (D-52) |
| DSN-8.2 | A breakpoint is added only when a named layout fact demands it. Copying a framework's six breakpoints is forbidden |
| DSN-8.3 | Component-level adaptation prefers container-driven rules over global breakpoints where the component is reused at different widths |
| DSN-8.4 | Content **reflows**; it is never hidden at a smaller size. "Mobile users don't need it" is not a reason (UR-13) |
| DSN-8.5 | Orientation is never locked; landscape phones must work (WCAG 1.3.4) |

### 8.2 Container widths `[P]`

| Context | Max width |
| --- | --- |
| Page container | 1200 px |
| Reading column (static pages, policy) | 680 px |
| Profile primary column | 720 px |
| Form | 480 px |
| Modal | 480 px |
| Operations console | Full width with a 1440 px cap |

### 8.3 Grid

| Breakpoint | Result cards | Category tiles |
| --- | --- | --- |
| `base` | 1 | 2 |
| `sm` | 1 | 2 |
| `md` | 2 | 3 |
| `lg` | 2 (with a filter column) | 4 |
| `xl` | 3 (with a filter column) | 5 |

| ID | Rule |
| --- | --- |
| DSN-8.6 | Reading order in the markup matches visual order at **every** breakpoint (WCAG 1.3.2, 2.4.3) |
| DSN-8.7 | A grid never orphans a single card on its own row where that reads as an error `[P]` |
| DSN-8.8 | Lists stay single-column at `base` and `sm`: two columns of cramped cards are worse than one readable one |

### 8.4 Component responsive behaviour

| Component | base / sm | md | lg / xl |
| --- | --- | --- | --- |
| **Navigation** | Compact header + persistent discovery bar | Header row | Single header row, all items inline |
| **Search field** | Full width + adjacent visible submit (UR-04) | Inline in header | Inline, wider |
| **Autocomplete** | Full-width panel, 4–8 suggestions (UR-03) | Anchored dropdown | Anchored dropdown |
| **Filters** | Bottom sheet | Collapsible inline row | Persistent column |
| **Sort** | Compact control by the result count | Inline | Inline |
| **Business card** | Thumbnail left, text right; full-width tap target | Same inside a grid cell | Same |
| **Profile** | One column: identity → trust → hours → contact → location → about → reviews | One column, wider | Two columns; **hierarchy unchanged** (IAR §7) |
| **Gallery** | Horizontal swipe, one image prominent | 2–3 visible | Grid |
| **Map** | Static preview, opens on demand | Inline, fixed height | Inline in the secondary column |
| **Modal** | Full-screen or near-full-screen | Centred, max 480 px | Centred |
| **Bottom sheet** | Yes — the default for choices | Sheet or popover | Popover/dropdown |
| **Sticky** | **At most one** sticky region | Header | Header; filter column scrolls with the page |
| **Tables (ops)** | Stacked rows | Horizontal scroll with a sticky first column | Full table |

| ID | Rule |
| --- | --- |
| DSN-8.9 | A sticky element must **never** obscure a focused control (WCAG 2.4.11) |
| DSN-8.10 | A sticky element must never consume more than ~15 % of the viewport height on mobile `[P]` |
| DSN-8.11 | Bottom sheets sit above the safe-area inset and above the Telegram bottom bar (UR-12) |

### 8.5 Images

| ID | Rule | Source |
| --- | --- | --- |
| DSN-8.12 | Every image reserves its space before it loads — **no layout shift** | NFR-P (CLS ≤ 0.1 `[P]`) |
| DSN-8.13 | Fixed aspect ratios per role (§11.2); the ratio never varies with the source file |
| DSN-8.14 | Images below the fold load lazily; the single above-the-fold image does not | NFR-P |
| DSN-8.15 | Responsive sources: a phone never downloads a desktop-sized image | UXP-8.2 |
| DSN-8.16 | Modern formats with fallbacks; quality tuned for small screens, not for print | D-25 open |
| DSN-8.17 | `object-fit: cover` with a sensible focal position; a logo uses `contain` on a neutral field so it is never cropped |
| DSN-8.18 | **A missing image is a designed state, never a broken icon** (§11.4) |

---

## 9. Trust, commercial and status styling

These three are the places where visual design carries **product
integrity**, so their rules are binding, not `[P]`.

### 9.1 Sponsored

| ID | Rule | Source |
| --- | --- | --- |
| DSN-9.1 | A Sponsored placement is distinguished by **three** mechanisms together: an explicit **label**, a distinct **container** (background or border), and **spatial segregation** from organic results | UR-02, LB-1…LB-5 |
| DSN-9.2 | Shading alone is insufficient — most people do not notice it | UR-02 |
| DSN-9.3 | The label reads **"Sponsored"**. Never "Promoted", "Featured", "Partner", "Top pick" or an icon alone | LB-3, UR-02 |
| DSN-9.4 | The label sits **before** the content in reading order and is visible without hover, without scroll and without expansion | LB-2, UR-02 |
| DSN-9.5 | The label meets normal body contrast; it is **never** `color.text.muted` | LB-4 |
| DSN-9.6 | The Sponsored container colour **must not** be the brand colour (DSN-2.6) |
| DSN-9.7 | A Sponsored card uses the **same anatomy** as an organic card — no larger image, no extra badge, no bolder type. Only the label and container differ | PL-6 |
| DSN-9.8 | Spacing between the Sponsored group and organic results is **at least `space.xl`**, and greater than the spacing within either group `[P]` | LB-5 |
| DSN-9.9 | **An unsold Placement collapses completely** — no empty frame, no "advertise here", no reserved whitespace | C-01, PL-5 |
| DSN-9.10 | The label is part of the accessible name of the result, so it is announced, not merely seen | LB-7, WCAG 1.3.1 |
| DSN-9.11 | Sponsored is **never** styled to resemble Verified | D-39, PK-2 |

### 9.2 Verified

| ID | Rule |
| --- | --- |
| DSN-9.12 | Verified = icon **+** the word **+** colour. Any two must suffice alone (NFR-AC3) |
| DSN-9.13 | It appears **with its verification date**; a badge without a date is forbidden (C-08, C-12) |
| DSN-9.14 | **There is no "unverified" badge.** Absence is the signal; marking absence stigmatises honest records (UXP-4.3) |
| DSN-9.15 | Verified is never purchasable and never appears in commercial surfaces (ADV-9) |
| DSN-9.16 | A **stale** record shows its date honestly rather than hiding the badge (UXP-7.3) |

### 9.3 Open / Closed / Hours not confirmed

| ID | Rule |
| --- | --- |
| DSN-9.17 | **Three** states, each with its own text: *Open* · *Closed* · *Hours not confirmed* (UR-17) |
| DSN-9.18 | Each state has a distinct shape or icon as well as a colour (WCAG 1.4.1) |
| DSN-9.19 | "Hours not confirmed" is **neutral**, not an error (UXP-7.3) |
| DSN-9.20 | Where hours exist, the next transition is shown in words — "Closes at …", "Opens at …" `[P]`, pending D-04 |
| DSN-9.21 | Status is computed in the Business's local time, never the device's (TRD TR-92) |

---

## 10. Focus, targets and interaction states

| ID | Rule | Source |
| --- | --- | --- |
| DSN-10.1 | Every interactive element has **visible focus**: a ≥ 2 px indicator with ≥ 3:1 contrast against both the control and its surroundings `[P]` | WCAG 2.4.7, 2.4.11 |
| DSN-10.2 | Focus is **never** removed without an equally visible replacement. `outline: none` without a replacement is a defect |
| DSN-10.3 | The focus indicator is not clipped by overflow and not covered by a sticky element | WCAG 2.4.11 |
| DSN-10.4 | Primary touch targets are **≥ 44 × 44 px**; the absolute floor for any target is **24 × 24 px** with adequate spacing | UR-01, WCAG 2.5.8 |
| DSN-10.5 | A target's **hit area may exceed its visual size** — this is the preferred way to keep a compact icon accessible |
| DSN-10.6 | Every component defines: default · hover · focus · active · disabled · loading · error (where applicable) |
| DSN-10.7 | **Hover is an enhancement only.** No content is hover-only (WCAG 1.4.13) |
| DSN-10.8 | A disabled control is **rare**: prefer an enabled control that explains why it cannot proceed. Disabled text is exempt from contrast and is therefore a trap (UXP-7.7) |
| DSN-10.9 | No interaction requires **dragging** — every drag has a single-pointer alternative (WCAG 2.5.7) |
| DSN-10.10 | No interaction requires a **path-based gesture**; swipe is an enhancement with a tap equivalent (WCAG 2.5.1) |
| DSN-10.11 | Actions fire on **pointer-up**, so a mis-press can be dragged away and cancelled (WCAG 2.5.2) |

---

## 11. Logo and imagery

### 11.1 Logo

| ID | Rule | Source |
| --- | --- | --- |
| DSN-11.1 | **No logo is created, proposed or implied by this document.** A logo is an owner decision and is not a UX deliverable | D-28 open |
| DSN-11.2 | Layouts reserve a **brand-mark region** with a defined footprint, so a future logo drops in with no redesign `[P]` |
| DSN-11.3 | Until a mark exists, the header uses a **plain typographic wordmark** — the product name set in the UI typeface at a heading weight. It is explicitly **temporary**, exists only so wireframes are not blank, and is **not a brand asset** (D-28) |
| DSN-11.4 | The temporary wordmark uses **no custom lettering, no symbol, no icon, no monogram and no colour treatment** that could be mistaken for a logo |
| DSN-11.5 | The wordmark region must tolerate a horizontal lockup, a stacked lockup and an icon-only mark in constrained space `[P]` |
| DSN-11.6 | **No favicon, app icon, social share image or Mini App icon artwork is designed here.** Their *slots* are specified; their content awaits D-28 |

### 11.2 Business imagery — aspect ratios `[P]`

| Role | Ratio | Rendering |
| --- | --- | --- |
| Card thumbnail | 1:1 | `cover`, small |
| Profile hero / primary photo | 16:9 | `cover`, full width on mobile |
| Gallery item | 4:3 | `cover`, uniform |
| Business logo (where supplied) | Flexible within a 1:1 box | **`contain`** on a neutral field — never cropped |
| Category tile illustration/icon | 1:1 | `contain` |

| ID | Rule | Source |
| --- | --- | --- |
| DSN-11.7 | Ratios are **fixed per role** so lists stay even and nothing shifts (DSN-8.12) |
| DSN-11.8 | Uploaded media is re-encoded to bounded display sizes; the original is never served to a phone (C-22, D-25 open) |
| DSN-11.9 | Quality guidance: a card thumbnail is optimised for a small screen, not for a desktop monitor. Exact dimensions and limits are **Open (D-25)** |
| DSN-11.10 | **Photography rights are a legal requirement (L-18)** — only media Bulbula has the right to publish is displayed. The console records provenance (C-22) |
| DSN-11.11 | Every meaningful image has **descriptive alt text**; purely decorative images are marked decorative (WCAG 1.1.1) |
| DSN-11.12 | Alt text describes **what is shown**, never "image of" and never the Business name alone |
| DSN-11.13 | A gallery image's alt text is **not** optional; media without a description is a completeness gap (C-22) |

### 11.3 Icons

| ID | Rule |
| --- | --- |
| DSN-11.14 | One icon set, consistent weight, consistent grid. The specific set is **`[P]` and must be openly licensed** |
| DSN-11.15 | An icon is **never the only label** for an action whose meaning is not universal. Search, close and back may stand alone; Save, share, report, verify, filter and sort may **not** (UR-13) |
| DSN-11.16 | Every standalone icon control has an accessible name (WCAG 4.1.2) |
| DSN-11.17 | Meaningful icons meet 3:1 contrast (WCAG 1.4.11) |
| DSN-11.18 | Icons are inline vector, not an icon font and not a sprite request (NFR-P) |
| DSN-11.19 | **No illustration system in V1.** Empty states use words and a route out, not artwork (UXP-6.8, UXP-8.4). Any future illustration style is `[P]` and unapproved |

### 11.4 Missing image treatment

| ID | Rule |
| --- | --- |
| DSN-11.20 | A Business with no photo shows a **neutral placeholder of the correct ratio** — the first letters of the name, or a category-neutral field. Never a broken-image icon, never "no image available", never a stock photo of something unrelated |
| DSN-11.21 | The placeholder is quiet. It must not look like a loading state and must not attract the eye (UXP-7.2) |
| DSN-11.22 | A missing image **never** collapses the layout or changes the card's height |
| DSN-11.23 | A placeholder is **decorative** for assistive technology: it carries no information, so it is not announced |

---

## 12. Performance budget as a design constraint

Shared hosting, one server, no cache service (TD-05), no CDN assumed
(`deployment.md`).

| ID | Rule | Source |
| --- | --- | --- |
| DSN-12.1 | **≤ 150 KB critical HTML and CSS; ≤ 100 KB JavaScript** `[P]`. A design that cannot fit is redesigned, not re-budgeted | NFR-P |
| DSN-12.2 | Zero font requests for Latin; the Ethiopic face is conditional and `unicode-range`-scoped | DSN-3.2, DSN-3.4 |
| DSN-12.3 | No CSS framework and no component library. A framework's bytes buy nothing a token system does not | Owner constraint |
| DSN-12.4 | **No JavaScript is required to read a page.** JavaScript enhances: autocomplete, filter sheets, Save (D-16 open — htmx + Alpine or vanilla; SPA rejected) | MOB-5, D-16 |
| DSN-12.5 | Every visual effect is checked against a low-end device: large blurs, many shadows, large gradients and heavy compositing are **design** problems | UXP-8.6 |
| DSN-12.6 | Third-party embeds — maps in particular — are **deferred and never render-blocking** (D-21 open) | MOB-5 |
| DSN-12.7 | The design does not depend on a client-side router, a hydration step, or a build-time CSS pipeline the host cannot run | TD-01, `deployment.md` |

---

## 13. Governance

| ID | Rule |
| --- | --- |
| DSN-13.1 | A new component is added only when an existing one genuinely cannot serve the need |
| DSN-13.2 | A visual value is changed by changing the **token**, never by a one-off override |
| DSN-13.3 | Both surfaces consume the same tokens through the adapter; the Mini App overrides only what the host owns (D-49) |
| DSN-13.4 | When the owner approves the values in §2 and §3, this document moves from Draft to Approved for those sections; the rest stays as it is |
| DSN-13.5 | **No implementation may begin from this document's `[P]` values.** It is a specification, not a stylesheet |

---

## 14. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-53 values | Exact orange, blue and neutral ramp | **Open — UX decision**, owner approval required |
| — | Typeface and final stack ordering | **Open — UX decision** (`[P]` §3.2) |
| D-28 | Logo and temporary brand-mark policy | **Open — product detail** |
| D-19 | Dark mode as a V1 feature | **Open — product detail**; V1 ships readiness only |
| D-16 | Frontend JavaScript approach | **Open — implementation detail** |
| D-17 | View layer | **Open — implementation detail** |
| D-21 | Maps provider and fallback | **Open — product detail**; affects §12.6 |
| D-25 | Media storage, sizes and limits | **Open — implementation detail**; affects §11.2 |
| D-04 | Hours model | **Open — product detail**; affects §9.3 |
| D-38 | Mini App navigation model | **Open — implementation detail**; affects §8.4 |
| — | Icon set selection | **Open — UX decision**; must be openly licensed |
| — | Breakpoint values | `[P]`; must be validated against real device data before approval |
| — | Illustration style | Not proposed. No V1 illustration system |

---

## Decision references

D-04, D-16, D-17, D-18, D-19, D-21, D-25, D-28, D-38, D-39, D-49, D-52,
D-53.
