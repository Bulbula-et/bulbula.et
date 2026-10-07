# UI Context

```text
Source baseline:   33f58e0e1619c7e5b952eede2382ae3c5e2ccf8c
Last derived from: 2026-10-07
Context status:    Needs review
```

**Derived from** `docs/20-ux-ui/` (all ten documents) ·
`docs/35-platforms/` (all nine) · `docs/10-product/glossary.md`.
**Authority:** those documents. This is an index — open the originals before
building any screen.

---

## 1. Visual direction

**D-53 approved exactly one thing:** the brand direction is
**orange primary, blue secondary, white-dominant light mode**.

| Approved | Not approved |
| --- | --- |
| Orange as primary | Any specific orange |
| Blue as secondary | Any specific blue |
| White-dominant light mode | Any specific neutral ramp |

| Rule | Source |
| --- | --- |
| **Exact colour values are Open (D-53)** and require owner approval. Choosing one in code makes the decision by default | DSN §14 |
| A **semantic token names its role, never its appearance**: `color.brand`, never `color.orange` | DSN-1.2 |
| `color.brand` → primary action, active state, brand presence (orange) | DSN §2 |
| `color.secondary` → secondary action, links, informational accent (blue) | DSN §2 |
| `color.background` → page canvas, white-dominant | DSN §2 |
| Surfaces are differentiated by a very low-contrast tint or a border, **never by shadow alone** | DSN-2.19 |
| **Dark mode is Open (D-19).** The Telegram surface is dark regardless | D-19 |
| **The logo is Open (D-53).** Do not create one | DSN §11 |

---

## 2. UX principles — the nine

Each has a one-line test. Apply them in order when they conflict
(`ux-principles-v1.0.md` §12).

| ID | Principle | Test |
| --- | --- | --- |
| **UXP-1** | Discovery first | Does this help someone find a business faster? |
| **UXP-2** | Mobile-first, genuinely | Was this designed at 360 px before 1280 px? |
| **UXP-3** | Zero-friction browsing | Can a Guest do this with no account and no interruption? |
| **UXP-4** | Trust is visible | Can the User see how current this is, and who stands behind it? |
| **UXP-5** | Commercial integrity | Could a reasonable User mistake this paid item for an organic one? |
| **UXP-6** | Calm interface | Is anything here competing with the answer the User came for? |
| **UXP-7** | Honest absence | Does this show "unknown" as a fact, rather than inventing or implying? |
| **UXP-8** | Weight is a cost the User pays | Is every kilobyte earning its place on a constrained connection? |
| **UXP-9** | One product, two surfaces | Does this work identically on Web and in the Mini App, with only chrome differing? |

Supporting constraints: **accessibility** (WCAG 2.2 AA identified; the
requirements are not `[P]`, only the visual values that satisfy them are),
**performance** as a design constraint, and **progressive enhancement** —
server-rendered HTML first, JavaScript enhances, core content works without
it (D-52, NFR-C3, SEO-1).

---

## 3. Surface model

```text
Shared product behaviour
       ↓
Web adapter          Telegram adapter
```

**One application, two adapters** — not two products kept in step by
discipline (D-49, SCC-1.6).

### Identical across surfaces — never diverge

Terminology and every string · business and listing data · publication state ·
search semantics (matching, aliasing, tokenisation) · filters · **organic
ranking order** · sponsored/organic separation · review rules · Save
behaviour · report behaviour · authentication identity · account model ·
accessibility principles · content hierarchy · error semantics.
(SCC-2.1 … SCC-2.15)

### Pixel-equivalent on both

The **Sponsored** label, container and separation · the **Verified**
indicator **with its date** and the absence of any "unverified" badge · the
**three** open-status states · **rating average always with its count** ·
guest-safe boundaries · zero-result screens never filled with Sponsored
content · the canonical URL in every share.
(SCC-2.16 … SCC-2.22)

### Permitted adaptation

A **closed list** of nine adapter entries. Each changes the *mechanism*,
never the *capability*. A tenth requires an owner decision (SCC-3.1).

| Rule |
| --- |
| A difference that changes **what a User can do** is a product decision (SCC-1.2) |
| A difference that changes **what a User is told** is also a product decision (SCC-1.3) |
| "It is easier on this surface" is never a sufficient reason to diverge (SCC-1.4) |
| An adaptation must be **forced by the host platform** and recorded with the constraint that forces it (SCC-1.5) |

### The only two capability exceptions

C-37 SEO (Web only — Telegram is not crawled) and C-19…C-29 operations
console (Web only — staff tooling). **Neither removes anything a Guest or
Customer can do.** A third exception is a scope change (SCC-4.3).

### Telegram specifics

| Rule | Source |
| --- | --- |
| The raw signed launch payload is **validated server-side** before any trust is placed in it | TM-7.9 |
| Validated launch context establishes a **surface, not an identity** | TM-7.3, TR-113 |
| **The Mini App must not trust client-provided identity claims** | TM-8.8 |
| A launch parameter is trivially forgeable; resolve it from validated context server-side | TM-7.15 |
| Telegram identity must not become the primary account model — **Open (D-33)** | TM-7.2, TM-7.5 |
| Saved items are **server-side**, never stored on the device | TM-11.4 |
| Mini App navigation model — full page loads vs fragment swaps — is **Open (D-38)** | D-38 |

---

## 4. Shared terminology

**Use these exact words in code, copy, identifiers and tests.** Full
definitions: `docs/10-product/glossary.md`.

| Term | Means |
| --- | --- |
| **Business** | The real-world organisation. **Not a platform account** (D-54) |
| **Branch** | A physical location of a Business. One Business, many Branches, exactly one primary (D-03) |
| **Listing** | The internal record Bulbula staff create, verify and publish |
| **Business profile** | The public page a User sees |
| **Save** | A Customer bookmarking a Business. **Private, no counts** |
| **Review** | A Customer's Rating plus optional text |
| **Rating** | The numeric score; shown as an average **always with its count** |
| **Permission** | The Business's agreement to be listed. A publication gate (D-50) |
| **Verification** | Staff confirmation of facts, recorded with method, date, scope and actor |
| **Verified** | The trust indicator, shown **with its date** |
| **Sponsored** | A paid placement. The label reads exactly **"Sponsored"** |
| **Organic ranking** | The unpurchasable result order |
| **Correction** | A change to published data, with reason and source |
| **Provenance** | Where a published fact came from. **Internal only** |

Also: Category · Subcategory · Alias · Area · Sub-city · Landmark · Report ·
Contact action · Share · Collection · Re-verification · Freshness · Stale
listing · Completeness · Quality review · Moderation · Audit log · Duplicate ·
Pilot · Placement · Package · Campaign · Inventory · Guest · Customer ·
Operator · Administrator · Staff.

**Never invent a synonym.** Consistent identification is a WCAG 3.2.4
obligation as well as a product one (SCC-2.1).

---

## 5. Sponsored vs organic — the highest-risk UI area

A Sponsored placement is distinguished by **three mechanisms together**:

```text
explicit label  +  distinct container  +  spatial segregation
```

| Rule | Source |
| --- | --- |
| **Shading alone is insufficient** — most people do not notice it | DSN-9.2 |
| The label reads **"Sponsored"**. Never "Promoted", "Featured", "Partner", "Top pick", or an icon alone | DSN-9.3, LB-3 |
| The label sits **before** the content in reading order, visible without hover, scroll or expansion | DSN-9.4, LB-2 |
| The label meets normal body contrast — **never muted text** | DSN-9.5 |
| The Sponsored container colour **must not** be the brand colour | DSN-9.6 |
| A Sponsored card uses the **same anatomy** as an organic card — no larger image, no extra badge, no bolder type | DSN-9.7, PL-6 |
| Separation from organic results is at least `space.xl` and greater than within-group spacing `[P]` | DSN-9.8 |
| **An unsold Placement collapses completely** — no empty frame, no "advertise here", no reserved whitespace | DSN-9.9, PL-5 |
| The label is part of the **accessible name**, so it is announced, not merely seen | DSN-9.10, WCAG 1.3.1 |
| **Sponsored is never styled to resemble Verified** | DSN-9.11 |

---

## 6. Trust and status indicators

| Indicator | Rule |
| --- | --- |
| **Verified** | Icon **+** the word **+** colour. Any two must suffice alone (DSN-9.12, NFR-AC3) |
| | Always **with its verification date**. A badge without a date is forbidden (DSN-9.13) |
| | **There is no "unverified" badge.** Absence is the signal; marking absence stigmatises honest records (DSN-9.14) |
| | Never purchasable, never in a commercial surface (DSN-9.15, ADV-9) |
| | A stale record **shows its date honestly** rather than hiding the badge (DSN-9.16) |
| **Open status** | Exactly **three** states, each with its own text: *Open* · *Closed* · *Hours not confirmed* (DSN-9.17) |
| | Each state has a distinct shape or icon as well as a colour (DSN-9.18, WCAG 1.4.1) |
| | "Hours not confirmed" is **neutral, not an error** (DSN-9.19) |
| **Rating** | Average **always with its count**; **nothing at all** when there are no Reviews (UR-16, TR-63) |

---

## 7. Accessibility — non-negotiable

| Rule | Source |
| --- | --- |
| Accessibility is a **property of the specification**, not a remediation phase | A11-1.1 |
| Accessible and mobile-first are the **same work** | A11-1.2 |
| **The console is not exempt.** Staff tooling meets the same bar | A11-1.3 |
| **No accessibility requirement is `[P]`** — only the visual values that satisfy them are | A11-1.4 |
| Where a WCAG criterion and a visual preference conflict, **the criterion wins** | A11-1.5 |
| Every function operable by **keyboard alone** | A11-2.1, NFR-AC2 |
| **No keyboard trap** — focus can always leave any component, including maps and embeds | A11-2.2 |
| V1 adds **no** single-character shortcuts | A11-2.3 |
| Enter/Space activate · Escape closes · Tab moves · arrows move within a component | A11-2.4 |
| Autocomplete is fully keyboard-driven; arrows wrap; the active suggestion is copied into the field | A11-2.5 |
| Dialogs trap focus **while open** and return it to the trigger on close | A11-2.6 |
| Focus order follows meaning and matches visual order at every breakpoint | A11-3.1 |
| Focus indicator always visible: ≥ 2 px, ≥ 3:1 against control and surroundings `[P]` | A11-3.2 |
| Colour is never the sole carrier of meaning — **including the Sponsored label** | NFR-AC3 |
| Text alternatives exist for meaningful images | NFR-AC4 |
| Legible and functional at increased text size | NFR-AC5 |

---

## 8. UI prohibitions

| Never | Why |
| --- | --- |
| **Create a logo** | **Open (D-53)**. Do not invent one |
| **Pick a specific orange, blue or neutral ramp** | **Open (D-53)**. Use semantic tokens with the decision visibly open |
| **Show a Save count, "N people saved this", or any social signal** | Save is private (C-14, SCC-2.9) |
| **Build an owner dashboard** | No business account exists (D-54) |
| **Add a "Claim this business" button** | D-02 |
| **Add owner replies to Reviews** | D-12 |
| **Blend Sponsored content into organic results** | LB-7, DSN-9.1 |
| **Use "Promoted", "Featured", "Partner", "Top pick" or an icon alone** | DSN-9.3 |
| **Use colour as the only status indicator** | NFR-AC3, WCAG 1.4.1 |
| **Make animation mandatory or load-bearing** | UXP-6; honour reduced-motion (DSN §7) |
| **Gate discovery behind authentication** | GS-1, UXP-3, TR-34 |
| **Interrupt a Guest with a sign-in prompt, modal or teaser** | UXP-3, GS-1…GS-5 |
| **Truncate or tease content to push sign-in** | SCC-2.20 |
| **Show an "unverified" badge** | DSN-9.14 |
| **Show a rating with no count, or a rating with no Reviews** | UR-16 |
| **Fill a zero-result screen with Sponsored content** | ZR-5, SCC-2.21 |
| **Render an internal field publicly** — provenance, Permission, verification evidence, personal-contact flags, reporter identity | PCP-3, PCP-5, TR-25 |
| **Block first render on a third-party component (maps, fonts)** | NFR-P4 |
| **Require JavaScript for core content** | NFR-C3, SEO-1 |
| **Design at desktop width first** | D-52, UXP-2 |

---

## 9. Where to open the formal document

| Question | Open |
| --- | --- |
| What does this component do, in every state? | `docs/20-ux-ui/component-spec-v1.0.md` (1,273 lines) |
| What is the page and URL structure? | `docs/20-ux-ui/information-architecture-v1.0.md` |
| What does this public screen look like and contain? | `docs/20-ux-ui/public-web-ux-v1.0.md` |
| What is the exact wording? | `docs/20-ux-ui/content-design-v1.0.md` |
| What is the step-by-step flow? | `docs/20-ux-ui/user-flows-v1.0.md` |
| Tokens, type, spacing, radius, elevation, motion, breakpoints | `docs/20-ux-ui/design-system-v1.0.md` |
| Accessibility detail | `docs/20-ux-ui/accessibility-v1.0.md` |
| Staff console screens | `docs/20-ux-ui/operations-console-ux-v1.0.md` |
| Why a principle exists | `docs/20-ux-ui/ux-principles-v1.0.md`, `ux-research-v1.0.md` |
| What must be identical across surfaces | `docs/35-platforms/shared-client-contract-v1.0.md` |
| Web-specific delivery | `docs/35-platforms/web-platform-v1.0.md` |
| Telegram-specific delivery | `docs/35-platforms/telegram-mini-app-v1.0.md` |
| Navigation · auth · errors · performance · SEO per surface | `docs/35-platforms/platform-*-v1.0.md` |

---

## 10. Open UI decisions

| ID | Question |
| --- | --- |
| **D-53** | Exact orange, blue and neutral ramp; the logo |
| **D-19** | Dark mode on the website |
| **D-16** | Frontend JS approach: htmx + Alpine vs vanilla modules — **SPA rejected** |
| **D-17** | View / template layer: in-house vs library |
| **D-38** | Mini App navigation: full page loads vs fragment swaps |
| **D-04** | Opening-hours model — affects the "Closes at …" copy |
| **D-21** | Maps embed strategy and fallback |
| **D-18** | Amharic interface — **not V1**; data is bilingual-ready, the interface is English |
| **NFR-AC1** | Whether WCAG 2.2 AA becomes a hard contractual requirement (the individual requirements already apply) |
| **LB-5** | Final Sponsored label wording, including any future Amharic rendering |
