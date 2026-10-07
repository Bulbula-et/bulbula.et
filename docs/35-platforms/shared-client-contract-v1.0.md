# Shared Client Contract

| | |
| --- | --- |
| **Document** | Shared Client Contract — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The contract both surfaces must honour: what is identical,
what may be adapted, and the rule that separates the two.

**Governed by** [`platform-architecture-v1.0.md`](platform-architecture-v1.0.md).

---

## 1. The rule

> **A platform adaptation may change _how_ a capability is reached, but
> not _what_ the capability means or what business rule it follows.**

| ID | Statement | Source |
| --- | --- | --- |
| SCC-1.1 | This rule is binding on both surfaces and on every future client | SUR-6, TR-111, FL-3 |
| SCC-1.2 | A difference that changes what a User can do is a **product decision**, not a platform adaptation | SUR-6 |
| SCC-1.3 | A difference that changes what a User is **told** — a label, a disclosure, a status — is also a product decision | LB-1…LB-8 |
| SCC-1.4 | "It is easier on this surface" is never a sufficient reason to diverge | PA-2.5 |
| SCC-1.5 | An adaptation must be **forced by the host platform**, confined to the adapter inventory, and recorded with the constraint that forces it | TR-110 |
| SCC-1.6 | This contract is enforceable because both surfaces are **one application**, not two products kept in step by discipline | PD-01, D-49 |

---

## 2. Identical across surfaces

Nothing in this section may vary. A surface conditional touching any of it
is a defect.

### 2.1 Product substance

| ID | Must be identical | Source |
| --- | --- | --- |
| SCC-2.1 | **Terminology.** Every term and every string (`content-design-v1.0.md`) | WCAG 3.2.4, `glossary.md` |
| SCC-2.2 | **Business data.** The same fields, the same values, the same omissions | SUR-4 |
| SCC-2.3 | **Listing data** and publication state | SUR-4, C-08 |
| SCC-2.4 | **Search semantics** — matching, aliasing, tokenisation | SUR-4, `search-design.md` |
| SCC-2.5 | **Filters** — the same set, the same meanings, the same results | C-02 |
| SCC-2.6 | **Organic ranking** — the same order for the same query | SUR-4, SRCH-6 |
| SCC-2.7 | **Sponsored/organic separation** — label, container, segregation, collapse-when-unsold | LB-1…LB-8, PL-5 |
| SCC-2.8 | **Review rules** — who may write, the **Branch** subject, the **1–5** rating, optional text, **one active Review per Customer per Branch**, the **30-day** edit window and its re-moderation, **pre-publication** moderation states, withdrawal on deletion, and policy grounds | D-12, D-34 |
| SCC-2.9 | **Category cardinality** — exactly one primary Category and zero or more secondaries, with **no artificial maximum** exposed on either surface | D-57 |
| SCC-2.9 | **Save behaviour** — private, no counts, idempotent, identity-scoped | C-14, UR-18 |
| SCC-2.10 | **Report behaviour** — Listing reports Guest-safe, Review reports authenticated | TS-2, `interaction-permissions.md` §3 |
| SCC-2.11 | **Authentication identity** — one Customer, one session concept | SUR-3, C-32, TD-02 |
| SCC-2.12 | **Account model** — the same minimal profile, the same rights | PRD ACC-6, C-33 |
| SCC-2.13 | **Accessibility principles** — WCAG 2.2 AA on both | NFR-AC1, A11-1.3 |
| SCC-2.14 | **Content hierarchy** — the same information order on every screen | `public-web-ux-v1.0.md` |
| SCC-2.15 | **Error semantics** — the same codes produce the same meanings and the same recovery | `api-spec-v1.0.md` E-1 |

### 2.2 Trust and integrity signals

Called out separately because a surface difference here damages the
product, not merely its consistency.

| ID | Must be identical | Source |
| --- | --- | --- |
| SCC-2.16 | The **Sponsored** label, container and separation | UR-02, LB-1…LB-5 |
| SCC-2.17 | The **Verified** indicator **with its date**, and the absence of any "unverified" badge | DSN-9.12, DSN-9.14 |
| SCC-2.18 | The **three** open-status states: Open · Closed · Hours not confirmed | UR-17 |
| SCC-2.19 | **Rating average always with its count**, and nothing at all when there are no Reviews | UR-16, TR-63 |
| SCC-2.20 | **Guest-safe boundaries** — the same actions gated on both surfaces, no truncation or teasing | GS-1…GS-5 |
| SCC-2.21 | **Zero-result screens are never filled with Sponsored content** | ZR-5 |
| SCC-2.22 | The **canonical URL** in every share | IAR-36 |

---

## 3. Permitted adaptation

Only these. Each entry states the host constraint that forces it.

| # | Adaptation | Forcing constraint | Spec |
| --- | --- | --- | --- |
| 1 | **Navigation chrome** | The host owns its header, Back button and bottom bar; duplicating them produces two competing controls | [`platform-navigation-v1.0.md`](platform-navigation-v1.0.md) |
| 2 | **Viewport handling** | Host viewport height changes during drag and is not the window height | Mini App spec §4 |
| 3 | **Safe areas** | Host and device insets are only knowable at runtime, and are version-gated | Mini App spec §4 |
| 4 | **Back navigation** | The host Back button and the Android hardware back must drive one stack | `platform-navigation-v1.0.md` §4 |
| 5 | **Sharing** | No common share primitive exists across the two runtimes | Mini App spec §9 |
| 6 | **External link behaviour** | Leaving a webview requires host methods; a plain anchor is unreliable | Mini App spec §9 |
| 7 | **Authentication entry flow** | Redirect-based OAuth is unreliable inside embedded webviews | [`platform-auth-v1.0.md`](platform-auth-v1.0.md) |
| 8 | **Telegram host integration** | Launch context, theme and lifecycle exist only on that surface | Mini App spec |
| 9 | **Browser-specific capabilities** | Capability availability genuinely differs | Web spec §7 |

| ID | Rule |
| --- | --- |
| SCC-3.1 | **The list is closed.** A tenth entry requires an owner decision recorded in the register (PA-2.4) |
| SCC-3.2 | Every entry changes the *mechanism*, never the *capability* |
| SCC-3.3 | Where an adaptation could be avoided, it must be |

---

## 4. The two capability exceptions

| Exception | Surface | Reason | Source |
| --- | --- | --- | --- |
| **C-37 SEO** | Web only | Telegram content is not crawled | TG-7, TR-117 |
| **C-19…C-29 operations** | Web only | Staff tooling | `scope-v1.md` §1.5 |

| ID | Rule |
| --- | --- |
| SCC-4.1 | These are the **only** two. Every other capability ships on both surfaces (AC-7) |
| SCC-4.2 | **Neither removes anything a Guest or a Customer can do.** SEO is machine-facing; operations is staff-facing |
| SCC-4.3 | A third exception is a scope change requiring a decision |

---

## 5. Capability parity — C-01…C-40

| C | Capability | Web | Telegram | Adaptation |
| --- | --- | --- | --- | --- |
| C-01 | Homepage | Yes | Yes | 1 chrome |
| C-02 | Search | Yes | Yes | — |
| C-03 | Autocomplete | Yes | Yes | 2 viewport (suggestion count fits the stable height) |
| C-04 | Category browse | Yes | Yes | — |
| C-05 | Area browse | Yes | Yes | — |
| C-06 | Category × Area | Yes | Yes | — |
| C-07 | Nearby | Yes | Yes | 9 capability (geolocation availability) |
| C-08 | Business profile | Yes | Yes | 1 chrome |
| C-09 | Hours and open status | Yes | Yes | — |
| C-10 | Maps | Yes | Yes | 6 external links |
| C-11 | Contact actions | Yes | Yes | 6 external links |
| C-12 | Trust indicators | Yes | Yes | **None — integrity** |
| C-13 | Reviews | Yes | Yes | — |
| C-14 | Save | Yes | Yes | 7 auth entry (when gated) |
| C-15 | Report | Yes | Yes | — |
| C-16 | Sponsored | Yes | Yes | **None — integrity** |
| C-17 | Sharing | Yes | Yes | 5 sharing |
| C-18 | Static and policy pages | Yes | Yes | 1 chrome (footer vs account menu) |
| C-19…C-29 | Operations | Yes | **No** | Exception |
| C-30 | Google authentication (D-48) | Yes | Yes | 7 auth entry (presented second; external-browser hand-off) |
| C-31 | Email OTP | Yes | Yes | 7 auth entry (presented first) |
| C-32 | Unified identity | Yes | Yes | Transport only (cookie vs bearer) |
| C-33 | Customer profile | Yes | Yes | — |
| C-34 | Saved management | Yes | Yes | — |
| C-35 | Customer review management | Yes | Yes | — |
| C-36 | Deletion and privacy | Yes | Yes | — |
| C-37 | SEO | **Yes** | **No** | Exception |
| C-38 | Analytics | Yes | Yes | — |
| C-39 | Notifications | Yes | Yes | — (email only on both, D-24) |
| C-40 | Privacy | Yes | Yes | — |

**Twenty-eight of forty capabilities ship on both surfaces.** The twelve
that do not are the two documented exceptions.

---

## 6. Cross-surface identity continuity

| ID | Rule | Source |
| --- | --- | --- |
| SCC-6.1 | The **same Saved list** appears on both surfaces — it belongs to the identity, not the surface | C-34, SUR-3 |
| SCC-6.2 | The **same Reviews** and the same Review states appear on both | C-35 |
| SCC-6.3 | The same account record, the same rights, the same deletion outcome | C-33, C-36 |
| SCC-6.4 | Account deletion on one surface **revokes sessions on both** | DL-2 |
| SCC-6.5 | A Customer may hold concurrent sessions on both surfaces | S-8 |
| SCC-6.6 | **No surface-local user data exists.** Nothing a Customer creates is stored only on one surface | SUR-3 |

---

## 7. Forbidden divergences

| Forbidden | Why |
| --- | --- |
| Different ranking or result order | SCC-2.6 |
| Different result counts or page sizes | SCC-2.2 |
| A weaker or relocated Sponsored label | SCC-2.16, LB-2 |
| Sponsored density tuned per surface | PD-16 |
| Ad sales influencing listing data on either surface | D-39 |
| Different Review rules or limits | SCC-2.8 |
| Save counts or social affordances on one surface | UR-18 |
| A Telegram-only capability of any kind | SUR-4 |
| A Web-only Customer capability | AC-7 |
| Surface-local storage of Customer data | SCC-6.6 |
| Different error meanings for the same code | SCC-2.15 |
| A different label for the same action | SCC-2.1 |
| Reduced accessibility on either surface | SCC-2.13 |
| An offline claim on either surface | PA-17.1 |

---

## 8. Verification

| ID | Check |
| --- | --- |
| SCC-8.1 | The same query returns the same results in the same order on both surfaces |
| SCC-8.2 | Sponsored label, container and separation are identical, verified at the narrowest viewport on both |
| SCC-8.3 | Verified, rating and open status render identically |
| SCC-8.4 | The same Customer sees the same Saved list and Reviews on both |
| SCC-8.5 | Deletion on one surface revokes sessions on the other |
| SCC-8.6 | Every gated action is gated on both, and ungated actions are ungated on both |
| SCC-8.7 | Every string matches between surfaces |
| SCC-8.8 | No surface conditional exists outside the adapter |
| SCC-8.9 | Every C-01…C-18 and C-30…C-40 capability is exercised on both |
| SCC-8.10 | No adaptation exists outside the nine in §3 |

---

## 9. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-33 | Telegram identity relationship | Owner decision — not taken here |
| D-38 | Mini App navigation model | **Open — implementation detail** |
| D-21 | Maps provider | Owner decision — affects adaptation 6 |
| D-24 | Notification channels | Owner decision — email only in V1 |

**D-34, D-55, D-56 and D-57 closed on 2026-10-07** and are no longer open
here. They are **server-owned rules** under SCC-2, so Web and the Telegram
Mini App behave **identically**: the same Branch subject, the same 1–5
scale, the same optional text, the same 30-day edit window, the same
pre-publication moderation and the same category cardinality. **Neither
surface may define a platform-specific product rule**, and neither may
hard-code a value the server owns.

---

## Decision references

D-12, D-21, D-24, D-33, D-34, D-38, D-39, D-48, D-49, D-55, D-56, D-57.
