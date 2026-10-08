# Accessibility

| | |
| --- | --- |
| **Document** | Accessibility — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Target.** **WCAG 2.2 Level AA** across the public Web, the Telegram Mini
App and the operations console (NFR-AC1 `[P]`, NFR-AC2…AC5 `[C]`).

**Scope.** Design and interface requirements. It does not prescribe
implementation technique.

**Why AA 2.2 specifically.** 2.2 is the current W3C Recommendation, and
its AA additions — focus not obscured, dragging alternatives, a 24 px
target floor, and authentication without a cognitive test — map directly
onto the way this product is used: one-handed, on a small screen, on a
cheap device (UR-01, UR-14).

---

## 1. Principles

| ID | Principle |
| --- | --- |
| A11-1.1 | Accessibility is a **property of the specification**, not a remediation phase. Every component in [`component-spec-v1.0.md`](component-spec-v1.0.md) states its requirements inline |
| A11-1.2 | Accessible and mobile-first are the **same work**: large targets, high contrast, plain language and simple structure serve everyone (UXP-2, D-52) |
| A11-1.3 | **The console is not exempt.** Staff tooling meets the same bar (OPX-0.9) |
| A11-1.4 | **No accessibility requirement is `[P]`.** The visual values that satisfy them are proposed; the requirements are not (DSN-0.4) |
| A11-1.5 | Where a WCAG criterion and a visual preference conflict, **the criterion wins** |

---

## 2. Keyboard

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-2.1 | Every function is operable by keyboard alone | 2.1.1 |
| A11-2.2 | **No keyboard trap.** Focus can always move out of any component, including maps and embeds | 2.1.2 |
| A11-2.3 | Any single-character shortcut can be turned off or remapped. V1 **adds none** | 2.1.4 |
| A11-2.4 | Standard keys behave as expected: Enter and Space activate, Escape closes, Tab moves, arrows move within a composite widget | 2.1.1 |
| A11-2.5 | Autocomplete is fully keyboard-driven: arrows move and **wrap**, the active suggestion is **copied into the field**, Escape restores the typed text (UR-03) | 2.1.1, 4.1.2 |
| A11-2.6 | Dialogs and sheets trap focus **while open** and return it to the trigger on close | 2.4.3 |
| A11-2.7 | **The console's keyboard path is a primary path**, not a fallback (OPX-15.3) | 2.1.1 |

---

## 3. Focus

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-3.1 | Focus order follows meaning and matches visual order at every breakpoint | 2.4.3, 1.3.2 |
| A11-3.2 | The focus indicator is always **visible**: ≥ 2 px, ≥ 3:1 against both the control and its surroundings `[P]` | 2.4.7 |
| A11-3.3 | **A focused element is never hidden** behind a sticky header, a sticky footer, a bottom sheet or a toast | **2.4.11 (new in 2.2)** |
| A11-3.4 | The focus indicator is not clipped by overflow or rounded corners | 2.4.7 |
| A11-3.5 | Removing the default outline without an equivalent replacement is a **defect**, not a style choice | 2.4.7 |
| A11-3.6 | After a page change, filter application or pagination, focus is **moved deliberately** — to the results heading, the first error, or the new content | 2.4.3 |
| A11-3.7 | Focus is never moved unexpectedly while the User is reading or typing | 3.2.1, 3.2.2 |
| A11-3.8 | A skip link to main content is the first focusable element on every screen | 2.4.1 |

---

## 4. Touch targets and pointer

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-4.1 | **Primary targets ≥ 44 × 44 px** (UR-01) | 2.5.5 (AAA) adopted as a design rule |
| A11-4.2 | **Absolute floor 24 × 24 px**, with sufficient spacing where a target is smaller | **2.5.8 (new in 2.2)** |
| A11-4.3 | A target's **hit area may exceed its visual size** — the preferred way to keep compact icons accessible | 2.5.8 |
| A11-4.4 | **No function requires dragging.** Every drag has a single-pointer alternative — notably console media ordering | **2.5.7 (new in 2.2)** |
| A11-4.5 | No function requires a multi-point or path-based gesture. Swipe is an enhancement with a tap equivalent — galleries and sheets in particular | 2.5.1 |
| A11-4.6 | Actions complete on **pointer-up**, so a mis-press can be dragged away and cancelled | 2.5.2 |
| A11-4.7 | A control's accessible name **contains its visible label** | 2.5.3 |
| A11-4.8 | Nothing requires device motion | 2.5.4 |
| A11-4.9 | Adjacent destructive and non-destructive actions are separated by enough space that a thumb cannot hit the wrong one | 2.5.8 |

---

## 5. Contrast and colour

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-5.1 | Body text ≥ **4.5:1**; large text ≥ **3:1** | 1.4.3 |
| A11-5.2 | UI component boundaries, icons and meaningful graphics ≥ **3:1** | 1.4.11 |
| A11-5.3 | `color.text.muted` still meets 4.5:1 — **"muted" never means "below AA"** (DSN-2.1) | 1.4.3 |
| A11-5.4 | **Colour is never the sole carrier of information** | 1.4.1 |
| A11-5.5 | Contrast is tested against the **rendered** background, including subtle tints, overlays and images | 1.4.3 |
| A11-5.6 | Text over an image has a guaranteed contrast treatment — a scrim or a solid panel — never a hope | 1.4.3 |
| A11-5.7 | Content remains usable in forced-colours and high-contrast modes: elevation never depends on a shadow alone (DSN-6.3) | 1.4.3 |

### 5.1 The three colour-independence cases

These are the places where colour independence carries **product
meaning**, so they are called out individually (NFR-AC3).

| Case | Carriers required |
| --- | --- |
| **Sponsored** | The word **"Sponsored"** · a distinct container · **spatial segregation** from organic results. Background shading alone is explicitly insufficient (UR-02, DSN-9.1) |
| **Verified** | An icon · the word **"Verified"** · the verification **date**. Any two must suffice alone (DSN-9.12) |
| **Open status** | Distinct **text** for each of the three states — *Open* · *Closed* · *Hours not confirmed* — plus a distinct icon or shape per state (UR-17, DSN-9.17) |

---

## 6. Structure, headings and landmarks

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-6.1 | One `h1` per screen, naming what the screen is about | 1.3.1, 2.4.6 |
| A11-6.2 | Heading levels do not skip; heading **level** is semantic and independent of visual size (DSN-3.15) | 1.3.1 |
| A11-6.3 | Every screen has landmarks: banner, navigation, search, main, contentinfo | 1.3.1, 2.4.1 |
| A11-6.4 | Multiple navigation regions on one screen are **distinctly named** | 1.3.1 |
| A11-6.5 | Lists of results, Categories, Areas and Reviews are real lists | 1.3.1 |
| A11-6.6 | Relationships conveyed visually — grouping, hierarchy, a card's parts — are conveyed programmatically too | 1.3.1 |
| A11-6.7 | Reading and navigation order is meaningful at every breakpoint, including the desktop two-column profile (PWX §8) | 1.3.2 |
| A11-6.8 | Instructions never rely on shape, size, position or sound — never "the button on the right" | 1.3.3 |
| A11-6.9 | Each screen has a unique, descriptive title | 2.4.2 |
| A11-6.10 | Link text makes sense out of context — never "here" or "read more" alone | 2.4.4 |
| A11-6.11 | More than one way exists to reach each Business profile: search and browsing both do | 2.4.5 |

---

## 7. Forms, labels and errors

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-7.1 | Every input has a **persistent visible label**. **A placeholder is never a label** | 3.3.2, 1.3.1 |
| A11-7.2 | Required fields are indicated in **text**, not by a symbol alone | 3.3.2 |
| A11-7.3 | Errors are identified in text, associated with their field, and announced | 3.3.1, 4.1.3 |
| A11-7.4 | Error messages say **what is wrong and how to fix it** — never "Invalid input" | 3.3.3 |
| A11-7.5 | Focus moves to the first error on a failed submit | 3.3.1, 2.4.3 |
| A11-7.6 | **All other input is preserved** on a failed submit, across authentication, and across session expiry | **3.3.7 (new in 2.2)** |
| A11-7.7 | Destructive and irreversible actions are confirmed, and the confirm label names the action | 3.3.4 |
| A11-7.8 | Inputs expecting known personal data carry the correct autocomplete token | 1.3.5 |
| A11-7.9 | Validation runs on blur and on submit, **not** on every keystroke | 3.3.1 |
| A11-7.10 | Character limits are stated **before** the limit is reached, and content is never silently truncated | 3.3.1 |

---

## 8. Authentication accessibility

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-8.1 | **No cognitive function test** — no CAPTCHA, no puzzle, no memory test, no transcription challenge | **3.3.8 (new in 2.2)** |
| A11-8.2 | **No password exists** in the product, so no password is ever to be remembered (D-48) | 3.3.8 |
| A11-8.3 | The OTP field **accepts paste**, including into a split-digit field | 3.3.8 |
| A11-8.4 | The OTP field uses a numeric keypad and the one-time-code autocomplete token | 1.3.5, 3.3.8 |
| A11-8.5 | The screen states **which address** the code went to, and offers a different address | 3.3.7 |
| A11-8.6 | The resend cooldown is shown as a visible countdown, announced on completion | 4.1.3 |
| A11-8.7 | Authentication **returns the User to their task with input intact** | 3.3.7, UFL-0.1 |
| A11-8.8 | A help route is available in a consistent place during authentication | **3.2.6 (new in 2.2)** |

---

## 9. Status messages and announcements

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-9.1 | Status changes are announced **without moving focus** | 4.1.3 |
| A11-9.2 | Announced events include: result count change, filter applied or removed, Save state change, submission success, error, page change, loading beginning and ending | 4.1.3 |
| A11-9.3 | Announcements are **polite**, not assertive, unless the User must stop | 4.1.3 |
| A11-9.4 | A loading state is announced when it begins **and** when it ends | 4.1.3 |
| A11-9.5 | Toasts are announced and **never** the only place important information appears (§28 of the component spec) | 4.1.3 |
| A11-9.6 | Announcements are short and state the outcome, not the mechanism | — |

---

## 10. Screen reader support

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-10.1 | Every control exposes a name, a role and its state | 4.1.2 |
| A11-10.2 | **The Sponsored label is part of the accessible name** of a sponsored result, so it is heard **before** the content (LB-7) | 1.3.1 |
| A11-10.3 | The Verified indicator's accessible name includes the verification date | 1.3.1 |
| A11-10.4 | A rating's accessible name states the value **and the count** in words (UR-16) | 1.3.1 |
| A11-10.5 | Open status is announced as text, including "Hours not confirmed" | 1.4.1 |
| A11-10.6 | A business card is announced as one coherent item, with the Save control as a separate control — not nested inside the primary link | 1.3.1 |
| A11-10.7 | Decorative images and placeholders are **not announced** (DSN-11.23) | 1.1.1 |
| A11-10.8 | Native semantics are preferred over added roles; a role is added only when no native element fits | 4.1.2 |
| A11-10.9 | Pagination announces the new page and the total | 4.1.3 |
| A11-10.10 | Dynamic content inserted into the page is reachable in the reading order, not only visually present | 1.3.2 |

---

## 11. Dialogs, sheets and overlays

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-11.1 | A dialog has a role, a name from its title, and modal semantics | 4.1.2 |
| A11-11.2 | Focus moves in on open, is trapped while open, and returns to the trigger on close | 2.4.3, 2.1.2 |
| A11-11.3 | **Escape always closes**, and a visible close control always exists | 2.1.1 |
| A11-11.4 | A bottom sheet is never dismissible **only** by swipe | **2.5.7** |
| A11-11.5 | Content behind a modal is inert to keyboard and screen reader | 2.4.3 |
| A11-11.6 | A sheet or toast must never cover a focused control | **2.4.11** |
| A11-11.7 | Opening an overlay pushes a history entry so **Back closes it** (IAR-31) | — |
| A11-11.8 | No stacked overlays | — |

---

## 12. Maps, images and icons

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-12.1 | **A map is never the only source of location.** Address and landmark are always present as text | 1.1.1 |
| A11-12.2 | An interactive map is keyboard-operable, or is clearly supplementary with a complete text equivalent | 2.1.1 |
| A11-12.3 | A map embed must not trap focus or hijack page scroll | 2.1.2 |
| A11-12.4 | Every meaningful image has descriptive alt text; decorative images are marked decorative | 1.1.1 |
| A11-12.5 | Alt text describes **what is shown** — never "image of", never the Business name alone | 1.1.1 |
| A11-12.6 | Gallery alt text is **required**, not optional (OPX-6.2) | 1.1.1 |
| A11-12.7 | Every standalone icon control has an accessible name | 4.1.2 |
| A11-12.8 | An icon is **never the only label** for an action whose meaning is not universal — Save, share, report, verify, filter and sort all need words (UR-13) | 1.1.1 |
| A11-12.9 | Meaningful icons meet 3:1 contrast | 1.4.11 |

---

## 13. Motion and animation

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-13.1 | `prefers-reduced-motion: reduce` removes movement entirely, keeps brief opacity changes, and **never removes the state change itself** | 2.3.3 |
| A11-13.2 | Nothing flashes more than three times per second | 2.3.1 |
| A11-13.3 | Nothing moves, blinks, scrolls or auto-updates for more than 5 s without a control to stop it | 2.2.2 |
| A11-13.4 | **No auto-advancing carousel exists** (DSN-7.3) | 2.2.2 |
| A11-13.5 | Animation never gates content: content is readable the moment it exists | 2.2.1 |
| A11-13.6 | Skeleton animation respects reduced motion | 2.3.3 |

---

## 14. Timing

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-14.1 | **No content is time-limited** except the OTP code, which is a security necessity | 2.2.1 |
| A11-14.2 | OTP expiry is stated, and requesting a new code is always available after the cooldown | 2.2.1 |
| A11-14.3 | Session expiry **preserves in-progress input** and restores it after re-authentication | 2.2.5, 3.3.7 |
| A11-14.4 | No countdown pressures a decision; no scarcity timer exists anywhere | 2.2.1 |

---

## 15. Zoom, text resizing and responsive behaviour

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-15.1 | **200 % zoom with no loss of content or function** | 1.4.4 |
| A11-15.2 | **No horizontal scrolling at a 320 px equivalent width** | 1.4.10 |
| A11-15.3 | Zoom is never disabled; a maximum-scale restriction is forbidden | 1.4.4 |
| A11-15.4 | Text sizes are relative so OS text-size settings work | 1.4.4 |
| A11-15.5 | Text-spacing overrides do not clip or overlap content | 1.4.12 |
| A11-15.6 | Content works in **both orientations**; orientation is never locked | 1.3.4 |
| A11-15.7 | **Content is never hidden at a smaller size.** It reflows (UR-13, DSN-8.4) | 1.4.10 |
| A11-15.8 | Console tables below `lg` scroll horizontally **within the table**, never forcing the whole page to scroll | 1.4.10 |
| A11-15.9 | Hover and focus content is dismissible, hoverable and persistent | 1.4.13 |

---

## 16. Consistency

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-16.1 | Navigation appears in the **same relative order on every screen** | 3.2.3 |
| A11-16.2 | A component with the same function is **labelled identically everywhere** — Save is always "Save" (`glossary.md`) | 3.2.4 |
| A11-16.3 | A **help and contact route is in a consistent place on every screen** | **3.2.6 (new in 2.2)** |
| A11-16.4 | Focus alone never changes context | 3.2.1 |
| A11-16.5 | Changing an input value alone never changes context; a submit action is always explicit | 3.2.2 |
| A11-16.6 | **Information already supplied is not requested again** within a process | **3.3.7 (new in 2.2)** |

---

## 17. Language

| ID | Requirement | Criterion |
| --- | --- | --- |
| A11-17.1 | The page language is declared | 3.1.1 |
| A11-17.2 | **Amharic content inside an English page declares its language** — Business names, Aliases and Area names in particular. This matters for both pronunciation and font selection (DSN-3.3, D-18) | 3.1.2 |
| A11-17.3 | Plain language throughout; unavoidable terms are explained at first use (`content-design-v1.0.md`) | 3.1.5 (AAA) adopted as an editorial rule |

---

## 18. Telegram Mini App

The Mini App is one of the two V1 client surfaces and shares the same
components through the surface adapter (D-49); only host chrome differs.

| ID | Requirement | Source |
| --- | --- | --- |
| A11-18.1 | Bulbula **never renders a second back control**; it binds to the host Back button (UR-12) | 3.2.3 |
| A11-18.2 | Layout is sized from the host's **stable viewport height**, not the raw window, so the keyboard does not displace controls (UR-12) | 1.4.10 |
| A11-18.3 | Both the safe-area inset and the content safe-area inset are respected, so nothing sits under host chrome (UR-12) | 2.4.11 |
| A11-18.4 | The host theme is adopted through the semantic token tier, and **contrast is re-verified** against it — a host theme can break a contrast ratio that passed on white (DSN-2.25) | 1.4.3 |
| A11-18.5 | The Mini App is operable with the platform's own assistive technology; Bulbula adds no gesture the host does not support | 2.1.1, 2.5.1 |
| A11-18.6 | **Email OTP is offered first inside Telegram** — embedded-webview OAuth is unreliable, and an authentication dead end is an accessibility failure (UR-14) | 3.3.8 |

---

## 19. Verification approach

| ID | Requirement |
| --- | --- |
| A11-19.1 | Automated checking catches contrast, names, labels, landmarks and heading order — and is **never treated as sufficient** |
| A11-19.2 | Manual keyboard-only testing of every flow in [`user-flows-v1.0.md`](user-flows-v1.0.md) |
| A11-19.3 | Screen-reader testing on at least one mobile and one desktop combination |
| A11-19.4 | Zoom testing at 200 % and at a 320 px equivalent width |
| A11-19.5 | Reduced-motion and forced-colours testing |
| A11-19.6 | Target-size measurement on the real mobile layout, not in the design file |
| A11-19.7 | **Specific checks for the three colour-independence cases** (§5.1) |
| A11-19.8 | An accessibility defect is a defect, triaged with functional bugs, not deferred to a separate backlog |

---

## 20. Known risks

| Risk | Mitigation |
| --- | --- |
| An approved orange that cannot carry white text at 4.5:1 | §5 and DSN-2.11 make contrast a precondition of approval, not a later fix |
| Dense console tables breaching the target floor | OPX-15.7; measured at the real layout |
| A map embed that traps focus or is keyboard-inoperable | A11-12.1…12.3; address and landmark always present (**D-21 open**) |
| A Telegram host theme breaking contrast | A11-18.4 |
| Amharic text rendering in a fallback that lacks Ethiopic glyphs | DSN-3.3, DSN-3.4, A11-17.2 |
| "Sponsored" being noticed visually but not announced | A11-10.2 makes it part of the accessible name |
| A sticky header obscuring focus on a small screen | A11-3.3; at most one sticky region (IAR §6) |

---

## 21. Open items

| ID | Item | Status |
| --- | --- | --- |
| — | Final colour values and their measured contrast | **Open — UX decision** (D-53 values) |
| — | Focus-indicator values | `[P]`; the ratio requirement is not |
| D-21 | Map provider and its accessibility | **Open — product detail**; affects §12 |
| D-16 / D-17 | Frontend approach | **Open — implementation detail**; affects how announcements are implemented, not whether |
| D-19 | Dark mode | **Open — product detail**; contrast must be re-verified if ever approved |
| — | Whether WCAG 2.4.13 Focus Appearance is AA or AAA | Sources disagree; **Bulbula treats it as a design requirement regardless** (UR-01) |
| — | Formal accessibility statement | Not written; a public statement is **Open — product detail** |

---

## Decision references

D-16, D-17, D-18, D-19, D-21, D-48, D-49, D-52, D-53.
