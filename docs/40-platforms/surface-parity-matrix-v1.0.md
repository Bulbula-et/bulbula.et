# Surface Parity Matrix

| | |
| --- | --- |
| **Document** | Surface Parity Matrix — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** Capability-by-capability confirmation of what ships on each V1
surface, and — where behaviour differs — exactly which adapter concern
accounts for it.

**Governed by** [`platform-strategy-v1.0.md`](platform-strategy-v1.0.md), which
implements the shared-surface direction approved in **D-49**.
The adapter inventory referenced below is that document's §2.1:

| # | Adapter concern |
| --- | --- |
| **A1** | Navigation chrome |
| **A2** | Share mechanism |
| **A3** | Authentication entry |
| **A4** | Map hand-off |
| **A5** | Viewport conventions |
| **A6** | Session transport |

**Reading the matrix.** *Both* means identical capability. A named adapter
concern means the capability is present on both surfaces but one of the six
documented concerns applies. *Web only* appears exactly twice, for the two
exceptions in `scope-v1.md` §1.5.

---

## 1. Discovery and content — C-01…C-18

| C | Capability | Web | Mini App | Adapter | Notes |
| --- | --- | --- | --- | --- | --- |
| C-01 | Homepage | Yes | Yes | A1, A5 | Same blocks, same order. Mini App suppresses the wordmark row (host chrome) |
| C-02 | Search | Yes | Yes | — | Same query handling, same results, same order (SUR-4) |
| C-03 | Autocomplete | Yes | Yes | A5 | Suggestion count sized to the host stable viewport so none sits under the keyboard (UR-03, UR-12) |
| C-04 | Category browse | Yes | Yes | — | — |
| C-05 | Area browse | Yes | Yes | — | — |
| C-06 | Category × Area | Yes | Yes | — | Served on both; indexable on the Web only (see C-37) |
| C-07 | Nearby | Yes | Yes | — | Location permission is requested by the host runtime in both cases; denial degrades to Area browsing identically |
| C-08 | Business profile | Yes | Yes | A1 | Identical information hierarchy (PWX §8.1) |
| C-09 | Hours and open status | Yes | Yes | — | Three states on both (UR-17) |
| C-10 | Maps | Yes | Yes | **A4** | Web opens the device maps application or a web map; Mini App hands off through the host's link handling. Address and landmark are text on both (MOB-5) |
| C-11 | Contact actions | Yes | Yes | A4 | Call, website and directions hand off to the host runtime |
| C-12 | Trust indicators | Yes | Yes | — | **Integrity rule — never adapts** (DSN-9.12) |
| C-13 | Reviews | Yes | Yes | — | Reading is Guest-safe on both (GS-2) |
| C-14 | Save | Yes | Yes | A3 | Gated action; the prompt differs only in method ordering |
| C-15 | Report | Yes | Yes | — | Listing report Guest-safe on both; Review report authenticated on both |
| C-16 | Sponsored placement | Yes | Yes | — | **Integrity rule — never adapts.** Same label, same container, same segregation (LB-1…LB-8) |
| C-17 | Sharing | Yes | Yes | **A2** | Web uses the platform share sheet with a copy-link fallback; Mini App uses Telegram's. **Both share the canonical URL** (IAR-36) and both resolve for non-Telegram recipients (TG-6) |
| C-18 | Static and policy pages | Yes | Yes | A1 | Reached from the footer on the Web, from the account menu in the Mini App |

---

## 2. Operations — C-19…C-29

**Web only.** Exception recorded in `scope-v1.md` §1.5: staff tooling.

| C | Capability | Web | Mini App |
| --- | --- | --- | --- |
| C-19 | Listing creation | Yes | **No** |
| C-20 | Listing editing and Corrections | Yes | **No** |
| C-21 | Verification and quality review | Yes | **No** |
| C-22 | Media management | Yes | **No** |
| C-23 | Category management | Yes | **No** |
| C-24 | Location management | Yes | **No** |
| C-25 | Review moderation | Yes | **No** |
| C-26 | Report management | Yes | **No** |
| C-27 | Campaign management | Yes | **No** |
| C-28 | Operations analytics | Yes | **No** |
| C-29 | Audit logs | Yes | **No** |

| ID | Rule | Source |
| --- | --- | --- |
| SPM-2.1 | The console is **never linked from, mentioned in, or reachable from** the Mini App | OPX-0.5, ENF-2 |
| SPM-2.2 | This exception removes nothing from a Guest or a Customer | PLT-3.2 |
| SPM-2.3 | Staff authentication is Web-only and separate from Customer authentication | ST-2, D-45 |

---

## 3. Identity and account — C-30…C-36

| C | Capability | Web | Mini App | Adapter | Notes |
| --- | --- | --- | --- | --- | --- |
| C-30 | Google authentication | Yes | Yes | **A3** | Offered on both. **Presented second in the Mini App** because embedded-webview OAuth is unreliable (TG-5, R-23, UR-14). Availability is unchanged |
| C-31 | Email OTP | Yes | Yes | **A3** | Offered on both. **Presented first in the Mini App** |
| C-32 | Unified identity | Yes | Yes | **A6** | One identity, one session concept, two transports: cookie on the Web, bearer token in the Mini App (TD-02, TR-116) |
| C-33 | Customer profile | Yes | Yes | — | Same minimal record (PRD ACC-6) |
| C-34 | Saved management | Yes | Yes | — | **The same Saved list on both surfaces** — it belongs to the identity (PLT-6.7) |
| C-35 | Customer review management | Yes | Yes | — | Same states, same policy grounds |
| C-36 | Deletion and privacy | Yes | Yes | — | Deletion revokes **every** session on **both** surfaces (DL-2) |

| ID | Rule | Source |
| --- | --- | --- |
| SPM-3.1 | **Telegram is not a login provider on either surface** | D-48, AI-4, TR-113 |
| SPM-3.2 | Validated Telegram context is a **surface signal only** | TR-113 |
| SPM-3.3 | Whether Telegram context may later attach as an additional provider identity is **Open (D-33)**; attaching it later must not require restructuring | TR-114 |
| SPM-3.4 | **No password exists on either surface** | D-48 |

---

## 4. Cross-cutting — C-37…C-40

| C | Capability | Web | Mini App | Notes |
| --- | --- | --- | --- | --- |
| C-37 | SEO | **Yes** | **No** | Exception, `scope-v1.md` §1.5. Mini App pages are excluded from indexing (TR-117, TG-7). Machine-facing: removes nothing from a User |
| C-38 | Analytics | Yes | Yes | Identical, non-identifying on both (TR-202) |
| C-39 | Notifications | Yes | Yes | Email only on both (D-24). **No Telegram message notifications in V1** — that would be a new capability, not an adapter difference |
| C-40 | Privacy | Yes | Yes | Same notice, same rights, same controls |

| ID | Rule | Source |
| --- | --- | --- |
| SPM-4.1 | C-37 is the **only** capability a Web user has and a Mini App user does not, and it is machine-facing | PLT-3.2 |
| SPM-4.2 | Sending notifications through Telegram is **out of V1 scope**. Proposing it invokes the §4 test in the platform strategy and fails Q1 | D-24, SUR-6 |

---

## 5. Summary

| Outcome | Count | Capabilities |
| --- | --- | --- |
| **Identical on both surfaces** | 20 | C-02, C-04, C-05, C-06, C-07, C-09, C-12, C-13, C-15, C-16, C-18*, C-33, C-34, C-35, C-36, C-38, C-39, C-40, plus C-11 and C-18 chrome-only |
| **Both surfaces, one adapter concern applies** | 9 | C-01, C-03, C-08, C-10, C-11, C-14, C-17, C-30/C-31 (A3), C-32 |
| **Web only — documented exception** | 12 | C-19…C-29 (operations), C-37 (SEO) |

\* C-18 differs only in where the links live (footer versus account menu),
which is adapter concern A1.

**Twenty-eight of forty capabilities ship on both surfaces.** The twelve
that do not are the two documented exceptions, neither of which reduces
what a Guest or a Customer can do (AC-7, PLT-3.2).

---

## 6. Behaviours that must be byte-identical

These are not merely "shared" — they are the rules where a surface
difference would damage the product's integrity, so they are called out for
explicit verification.

| ID | Behaviour | Why | Source |
| --- | --- | --- | --- |
| SPM-6.1 | **Sponsored label, container and segregation** | A weaker disclosure on a smaller surface is exactly the failure FTC guidance describes | LB-1…LB-8, UR-02 |
| SPM-6.2 | **Verified indicator with its date** | Trust must not vary by where it is read | DSN-9.12, C-12 |
| SPM-6.3 | **The three open-status states** | A collapsed two-state version misleads | UR-17 |
| SPM-6.4 | **Ranking and result order** | Same query, same order | SUR-4 |
| SPM-6.5 | **Rating average always with its count** | | UR-16 |
| SPM-6.6 | **Guest-safe boundaries** | The same actions are gated on both surfaces | GS-1…GS-5 |
| SPM-6.7 | **The canonical URL in every share** | | IAR-36, TG-6 |
| SPM-6.8 | **Zero-result screens never filled with Sponsored content** | | ZR-5 |
| SPM-6.9 | **Every string** | One label per action everywhere | WCAG 3.2.4, `content-design-v1.0.md` |
| SPM-6.10 | **WCAG 2.2 AA conformance** | Not a Web-only obligation | NFR-AC1 |

---

## 7. Verification checklist

| ID | Check |
| --- | --- |
| SPM-7.1 | Each of C-01…C-18 and C-30…C-40 is exercised on both surfaces |
| SPM-7.2 | The same query returns the same results in the same order on both |
| SPM-7.3 | A Customer's Saved list and Reviews are identical across surfaces |
| SPM-7.4 | Account deletion on one surface revokes sessions on the other |
| SPM-7.5 | Sponsored labelling is verified on both, including at the narrowest viewport |
| SPM-7.6 | A link shared from the Mini App opens for a recipient with no Telegram account |
| SPM-7.7 | No Mini App URL appears in the sitemap or is indexable |
| SPM-7.8 | No console route is reachable from the Mini App |
| SPM-7.9 | Both authentication methods complete successfully inside the Mini App |
| SPM-7.10 | No surface-conditional branch exists outside the adapter boundary |

---

## 8. Open items

| ID | Item | Affected rows |
| --- | --- | --- |
| D-33 | Telegram identity relationship | C-30, C-31, C-32 |
| D-38 | Mini App navigation model | C-01…C-18 presentation only |
| D-21 | Maps provider and fallback | C-10, C-11 |
| D-16 / D-17 | Frontend approach and view layer | none — parity is independent of both |
| D-45 | Staff authentication strength | C-19…C-29 |
| D-24 | Notification channels beyond email | C-39 |

---

## Decision references

D-16, D-17, D-21, D-24, D-33, D-38, D-45, D-48, D-49.
