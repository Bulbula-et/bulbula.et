# Platform Strategy

| | |
| --- | --- |
| **Document** | Platform Strategy — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The contract between Bulbula's two V1 client surfaces: what is
shared, what may differ, where the difference is allowed to live, and how a
proposed difference is judged.

**Scope.** Web and the Telegram Mini App. This document governs the two
surface specifications that follow it:
[`web-platform-spec-v1.0.md`](web-platform-spec-v1.0.md) and
[`telegram-mini-app-spec-v1.0.md`](telegram-mini-app-spec-v1.0.md).
Capability-level parity is enumerated in
[`surface-parity-matrix-v1.0.md`](surface-parity-matrix-v1.0.md).

**This document selects no technology.** The JavaScript approach (D-16),
the view layer (D-17) and the Mini App navigation model (D-38) remain open,
and nothing here depends on how they are answered.

---

## 1. The position

**Bulbula is one product on two surfaces, not two products.** (D-49, SUR-7)

| ID | Rule | Source |
| --- | --- | --- |
| PLT-1.1 | Both surfaces deliver the **same capabilities**, except the two documented exceptions in §3 | SUR-1, `scope-v1.md` §1.5 |
| PLT-1.2 | Both surfaces run on the **same backend, the same domain rules and the same API contracts** | SUR-2, TR-109 |
| PLT-1.3 | A Customer identity is **the same on both surfaces** | SUR-3, C-32 |
| PLT-1.4 | Content is identical: the same Listings, the same Reviews, the same ranking, the same Sponsored placements with the same labels | SUR-4 |
| PLT-1.5 | **Neither surface is a port of the other.** Both are first-class | SUR-7 |
| PLT-1.6 | The Mini App is the **same application served to a Telegram webview**, not a separate build target or codebase | TR-109, R-16 |
| PLT-1.7 | **No duplicated business logic.** One implementation of every rule | D-49, G-5, P-4 |

### 1.1 Why this is a strategy and not an accident

A Mini App is a web page that Telegram opens. The expensive mistake
available here is to treat it as a second client, fork the frontend, and
then spend the rest of the product's life keeping two implementations of
the same rules in step. D-49 forecloses that. The cost of the position is
that every surface difference must be deliberate and must be written down —
which is what this document is for.

---

## 2. The three layers

```text
┌──────────────────────────────────────────────────────────────┐
│ SHARED — one implementation, no surface knowledge            │
│                                                              │
│ Domain rules · application services · API contracts ·        │
│ design tokens · components · screens · content and wording · │
│ validation · permissions · ranking · trust and sponsorship   │
│ rules · accessibility requirements                           │
└──────────────────────────────────────────────────────────────┘
                              │
┌──────────────────────────────────────────────────────────────┐
│ ADAPTER — the ONLY place a surface may be named              │
│                                                              │
│ Navigation chrome · share mechanism · authentication entry · │
│ map hand-off · viewport conventions · theme source ·         │
│ session transport                                            │
└──────────────────────────────────────────────────────────────┘
                              │
          ┌───────────────────┴───────────────────┐
          ▼                                       ▼
┌────────────────────┐                  ┌────────────────────┐
│ WEB                │                  │ TELEGRAM MINI APP  │
│ own chrome         │                  │ host chrome        │
│ SEO surface        │                  │ not an SEO surface │
│ hosts the console  │                  │ no console         │
└────────────────────┘                  └────────────────────┘
```

| ID | Rule | Source |
| --- | --- | --- |
| PLT-2.1 | **Shared is the default.** A thing is surface-specific only if it appears in the adapter list below | D-49 |
| PLT-2.2 | Surface-specific logic is **confined to the adapter boundary** and is not scattered through the application | TR-110, SUR-5 |
| PLT-2.3 | The adapter **must not change domain behaviour** | TR-111 |
| PLT-2.4 | Shared code **must not branch on surface.** A surface check outside the adapter is a defect, not a shortcut | P-4 |
| PLT-2.5 | The adapter is a **named, documented boundary** — `Bulbula\Telegram` for Mini App context and surface presentation | `architecture.md` §`Bulbula\Telegram` |
| PLT-2.6 | Validated Telegram context establishes a **surface**, not an identity | TR-113, D-48 |

### 2.1 The adapter inventory — exhaustive

These six, and nothing else, may differ between surfaces (SUR-5, TR-110):

| # | Concern | Web | Mini App |
| --- | --- | --- | --- |
| 1 | **Navigation chrome** | Bulbula renders its own header, back affordance and footer | Telegram supplies header, Back button and bottom bar; Bulbula suppresses its own (UR-12, A11-18.1) |
| 2 | **Share mechanism** | Platform share sheet with a copy-link fallback | Telegram's share | 
| 3 | **Authentication entry** | Google first, email OTP second | **Email OTP first** — embedded-webview OAuth friction (TG-5, R-23, UR-14) |
| 4 | **Map hand-off** | Opens the device maps application or a web map | Opens through the Telegram host's link handling |
| 5 | **Viewport conventions** | Standard browser viewport | Host stable viewport height plus both safe-area insets (UR-12) |
| 6 | **Session transport** | Cookie | `Authorization: Bearer` — no dependence on third-party cookie behaviour (TD-02, TR-116) |

Two further differences are **capability exceptions**, not adapter items,
and are listed separately in §3: SEO and the operations console.

| ID | Rule |
| --- | --- |
| PLT-2.7 | **This list is closed.** Adding a seventh adapter concern requires an owner decision recorded in the decision register |
| PLT-2.8 | Every item above changes *how* something is done, never *whether* a User can do it |
| PLT-2.9 | The theme source is part of concern 5: the Mini App adopts the host theme **through the semantic token tier**, not as a second palette (DSN-2.25) |

---

## 3. The two capability exceptions

| Exception | Surface | Reason | Source |
| --- | --- | --- | --- |
| **C-37 SEO** | Web only | Telegram content is not crawled; the Mini App is not an SEO surface | TG-7, TR-117, `scope-v1.md` §1.5 |
| **C-19…C-29 operations** | Web only | Staff tooling; there is no Mini App operations surface | `scope-v1.md` §1.5 |

| ID | Rule |
| --- | --- |
| PLT-3.1 | These are the **only** two exceptions. Every other capability ships on both surfaces (AC-7) |
| PLT-3.2 | Neither exception removes anything a **Customer or Guest** can do. SEO is machine-facing; operations is staff-facing |
| PLT-3.3 | A new exception is a **scope change** requiring a decision, not an implementation convenience (SUR-6) |

---

## 4. The surface-difference test

Any proposed difference must pass this test before it is built.

```text
Proposed difference
        │
        ▼
Q1. Does it change WHAT a User can do, or what they see as content?
        │
        ├── YES ──▶ STOP. This is a product decision, not a surface
        │           difference. It requires an owner decision and a
        │           register entry.                      (SUR-6, TR-111)
        │
        └── NO
             │
             ▼
Q2. Does it fall inside the closed adapter inventory (§2.1)?
             │
             ├── NO ──▶ STOP. Either it belongs in shared code, or the
             │          adapter inventory must be extended by decision.
             │                                            (PLT-2.7)
             │
             └── YES
                  │
                  ▼
Q3. Is it forced by the host platform, or merely preferred?
                  │
                  ├── PREFERRED ──▶ STOP. Preference is not a reason to
                  │                 diverge. Use the shared behaviour.
                  │
                  └── FORCED ──▶ Permitted. Document it in the relevant
                                 surface spec with the host constraint
                                 that forces it.
```

| ID | Rule | Source |
| --- | --- | --- |
| PLT-4.1 | A surface-specific behaviour **must not become a product difference** | SUR-6 |
| PLT-4.2 | "It is easier on this surface" is **not** a passing answer to Q3 |
| PLT-4.3 | A difference that passes the test is recorded in the surface spec **with the host constraint that forced it**, so it can be revisited if the host changes |
| PLT-4.4 | A difference that fails the test and is still wanted goes to the decision register as an open item |

---

## 5. Worked applications of the test

| Proposal | Q1 | Q2 | Q3 | Outcome |
| --- | --- | --- | --- | --- |
| Suppress Bulbula's back button inside Telegram | No | Yes (chrome) | Forced — host owns Back | **Permitted** (UR-12) |
| Offer email OTP first inside Telegram | No — both methods remain available | Yes (auth entry) | Forced — embedded-webview OAuth friction | **Permitted** (TG-5) |
| Use bearer tokens in the Mini App | No | Yes (session transport) | Forced — third-party cookie behaviour | **Permitted** (TD-02) |
| Skip the Sponsored label in the Mini App because space is tight | **Yes** — changes what the User is told | — | — | **Refused.** Integrity rule, shared (LB-1…LB-8) |
| Show fewer search results in the Mini App | **Yes** — changes content | — | — | **Refused** (SUR-4) |
| A Telegram-only "share to chat" that bypasses the canonical URL | **Yes** — changes what is shared | — | — | **Refused** (IAR-36, TG-6) |
| Different ranking in the Mini App | **Yes** | — | — | **Refused** (SUR-4) |
| Native-feeling page transitions in the Mini App | No | No — not in the inventory | — | **Refused** unless D-38 makes it a shared navigation model |
| Telegram sign-in as a login provider | **Yes** — adds a provider | — | — | **Refused in V1.** Telegram is not an approved provider (D-48); the question is **Open (D-33)** |
| Omit the operations console from the Mini App | No — staff-facing | Exception, §3 | Forced | **Permitted** (`scope-v1.md` §1.5) |

---

## 6. Identity across surfaces

| ID | Rule | Source |
| --- | --- | --- |
| PLT-6.1 | **One Customer identity**, reachable from either surface with the same credentials | SUR-3, C-32 |
| PLT-6.2 | **One session concept**, two transports: cookie on the Web, bearer token on the Mini App and API | TD-02, S-1 |
| PLT-6.3 | A Customer may hold concurrent sessions on both surfaces | S-8 |
| PLT-6.4 | The approved provider set is **Google and email OTP only** | D-48 |
| PLT-6.5 | **Telegram is not an authentication provider in V1.** Telegram context must never be silently promoted to an identity | AI-4, TR-113, D-48 |
| PLT-6.6 | Whether Telegram context may later attach as an additional provider identity is **Open (D-33)**. The design must support attaching it later **without restructuring** | TR-114, D-33 |
| PLT-6.7 | Saved lists, Reviews and account state are identical on both surfaces because they belong to the identity, not the surface | C-32, C-34, C-35 |

**What D-33 being open means in practice.** The Mini App signs a Customer
in with Google or email OTP exactly as the Web does, with the ordering
difference of §2.1 item 3. Validated Telegram context is recorded as a
surface signal only. No screen implies that a Telegram account is or will
become a Bulbula login.

---

## 7. What the surfaces share, in detail

| Shared | Consequence |
| --- | --- |
| Design tokens | One token set; the Mini App maps the host theme onto the semantic tier (DSN-2.25) |
| Components | All 44 (`component-spec-v1.0.md` §45); none is surface-specific |
| Screens | Every public screen in `public-web-ux-v1.0.md` renders on both surfaces |
| User flows | Every Guest and Customer flow in `user-flows-v1.0.md` works on both |
| Content and wording | One string set (`content-design-v1.0.md`); the same label everywhere (WCAG 3.2.4) |
| Information architecture | One structure, one URL grammar (`information-architecture-v1.0.md`) |
| Accessibility requirements | WCAG 2.2 AA on both (NFR-AC1) |
| Trust and commercial rules | Verified, Sponsored and Open-status rules are integrity rules and never adapt |
| Validation and error semantics | Identical; only the presentation chrome differs |
| Ranking | Identical (SUR-4) |

| ID | Rule |
| --- | --- |
| PLT-7.1 | A component, screen or flow **may not be omitted** on a surface. If it cannot work there, that is a product problem, not a surface problem |
| PLT-7.2 | A string may not vary by surface. One exception is permitted and is already specified: the ordering of authentication methods, which changes order, not wording |

---

## 8. The future client

| ID | Rule | Source |
| --- | --- | --- |
| PLT-8.1 | A Flutter client is a **later** client, **not part of V1** | FL-1, D-15 |
| PLT-8.2 | When it ships it consumes the **same backend, domain rules and API contracts** | FL-2 |
| PLT-8.3 | It **must not introduce product rules of its own**; anything it needs is a requirement for all surfaces | FL-3 |
| PLT-8.4 | **No V1 requirement may be shaped by a hypothetical Flutter need.** Store obligations such as Apple Sign In (D-47) are addressed when that client is planned | FL-4, D-47 |
| PLT-8.5 | API design **should** avoid decisions that make a native client unreasonably difficult. This is a preference, not a V1 feature | FL-5, TR-24 |

**Consequence for this phase.** The adapter inventory in §2.1 is written so
that a third surface would add a third adapter, not a third set of product
rules. That is the whole of the V1 obligation to a future client.

---

## 9. Delivery and release

| ID | Rule | Source |
| --- | --- | --- |
| PLT-9.1 | Both surfaces are **released together from one deployment**. There is no separate Mini App release train | TR-109 |
| PLT-9.2 | A change to shared code reaches both surfaces simultaneously; this is a feature of the strategy, not a risk to be engineered away |
| PLT-9.3 | There is **one domain model and one deployment target**; the Mini App is not a subdomain with its own application | `deployment.md` |
| PLT-9.4 | HTTPS everywhere; HTTP redirects to HTTPS. Telegram will not load a Mini App over plain HTTP | PS-5 |
| PLT-9.5 | A surface cannot be feature-flagged into divergence. Flags may stage a rollout; they may not create a permanent difference | SUR-6 |

---

## 10. Verification

| ID | Check |
| --- | --- |
| PLT-10.1 | Every capability except C-37 and C-19…C-29 is available on both surfaces (AC-7, `surface-parity-matrix-v1.0.md`) |
| PLT-10.2 | No surface check exists outside the adapter boundary |
| PLT-10.3 | Every documented difference appears in the §2.1 inventory and names the host constraint that forces it |
| PLT-10.4 | The same Customer sees the same Saved list, Reviews and account state on both surfaces |
| PLT-10.5 | The same query returns the same results in the same order on both surfaces |
| PLT-10.6 | Sponsored labelling and separation are identical on both surfaces |
| PLT-10.7 | A link shared from the Mini App opens correctly for a recipient with no Telegram account (TG-6) |
| PLT-10.8 | No Mini App URL is indexable (TR-117) |

---

## 11. Risks

| Risk | Mitigation |
| --- | --- |
| Adapter creep — surface checks leaking into shared code | PLT-2.4 and the closed inventory (PLT-2.7); verification check PLT-10.2 |
| The Mini App quietly becoming the second-class surface | SUR-7; the parity matrix is enumerated rather than assumed |
| A host platform change breaking an adapter assumption | PLT-4.3 records the forcing constraint, so a host change has a known blast radius |
| D-33 being answered later and forcing rework | PLT-6.6 and TR-114 require attach-later support now |
| D-38 being answered later | Both navigation models remain possible; nothing in this phase depends on the answer (TR-119) |
| A CSP that admits Telegram weakening the Web policy | **Open — technical decision (OT-05)**; framed in `telegram-mini-app-spec-v1.0.md` §9 |

---

## 12. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-16 | Frontend JavaScript approach | **Open — implementation detail**; nothing here depends on it |
| D-17 | View layer | **Open — implementation detail** |
| D-33 | Telegram identity relationship | **Open — product detail**; attach-later support required now |
| D-38 | Mini App navigation model | **Open — implementation detail**; both models must remain possible |
| D-45 | Staff authentication strength | **Open — implementation detail**; Web-only surface |
| D-47 | Sign in with Apple for a future client | Deferred; must not shape V1 |
| OT-05 | CSP `frame-ancestors` admitting the Telegram host | **Open — technical decision** |
| D-21 | Maps provider | **Open — product detail**; affects adapter concern 4 |
| D-20 | Confirmed host resource limits | **Open — technical decision** |

---

## Decision references

D-15, D-16, D-17, D-20, D-21, D-33, D-38, D-45, D-47, D-48, D-49.
