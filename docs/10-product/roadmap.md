# Product Roadmap

| | |
| --- | --- |
| **Document** | Product Roadmap |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** To record what comes after V1 without letting it leak into V1.

**No dates.** This roadmap contains **no dates, no quarters, no sequencing
commitments and no durations.** Bulbula has not yet run the operational pilot
(D-31) and therefore has no basis for any schedule. A date invented here
would be fiction that someone later treats as a commitment.

**Three horizons:**

| Horizon | Meaning |
| --- | --- |
| **V1** | Approved, specified, being built. `scope-v1.md` §1 |
| **Future (V2+)** | Direction approved or strongly implied; not specified; not started |
| **Later / exploratory** | Ideas with no approval and no commitment. Listed only so they are not mistaken for plans |

---

## 1. V1

The 40 approved capabilities in [`scope-v1.md`](scope-v1.md) §1, specified in
[`prd-v1.0.md`](prd-v1.0.md) §12, delivered on Web and the Telegram Mini App,
in Bole Bulbula, English-first, with staff-managed fixed-package
sponsorship.

### 1.1 V1 completion conditions

| Condition | Status |
| --- | --- |
| All 40 capabilities implemented and tested | Build |
| Operations console supports the full lifecycle | Build (`listing-operations.md` §4) |
| Pilot complete and measured | **PENDING** (D-31) |
| Launch coverage threshold met | **PENDING PILOT** (D-30n) |
| Legal minima confirmed; policy pages published | **PENDING COUNSEL** (D-46) |
| Launch-area boundary confirmed | **PENDING** (D-40) |

---

## 2. Future (V2+)

Direction is approved; **nothing here is specified, and no V1 artefact may
anticipate it** (PRD H-4).

### 2.1 Business participation

| Item | Note | Decision |
| --- | --- | --- |
| Business accounts | **With mandatory Administrator approval of every submission.** The approval gate is part of the approved direction, not an implementation detail | D-54 |
| Owner-submitted listings | Same approval gate | D-02 |
| Listing claims | Requires an identity-verification process that does not exist yet | D-02 |
| Owner-submitted corrections | Structured replacement for today's contact route | D-02 |
| Owner replies to Reviews | Requires business accounts | D-12, `review-policy.md` §8 |
| Owner-facing analytics | Requires business accounts | D-54 |

**Precondition for the whole group:** Bulbula must still own quality. The
approved direction is owner *participation* under approval, never owner
*control*.

### 2.2 Clients

| Item | Note | Decision |
| --- | --- | --- |
| Flutter client — Android first | Same backend, same domain rules, same API contracts (PRD FL-2) | D-15, D-15r |
| Flutter client — iOS | Triggers the Apple Sign In question | D-15r, D-47 |
| Sign in with Apple | Evaluate when the iOS client is planned, on store-policy grounds | D-47 |
| Native push notifications | Only meaningful with a native client | — |

### 2.3 Product depth

| Item | Note | Decision |
| --- | --- | --- |
| Review photographs | Deferred, not rejected | D-36 |
| "Helpful" voting on Reviews | Deferred, not rejected | D-37 |
| Richer services / products / pricing | Depends on what D-44 settles for V1 | D-44 |
| Amharic interface | Requires separate owner approval; V1 is bilingual-*ready*, not bilingual | D-18 |
| Dark mode | Deferred | D-19 |
| Messaging or quote requests between Users and Businesses | Requires business accounts and a moderation model | — |

### 2.4 Reach

| Item | Note |
| --- | --- |
| Additional areas of Addis Ababa | The location model already supports it (GEO-4); the constraint is operational capacity, not software |
| Additional cities | Only after the operating model is proven at area scale |

### 2.5 Monetization

| Item | Note | Decision |
| --- | --- | --- |
| Self-service advertising purchase | Requires business accounts, billing and tax handling (L-17, L-20) | D-10, D-54 |
| Online payment | Requires a payment decision not yet taken; local payments are mobile-money-first (R-08) | D-11 |
| Additional sponsorship formats | Only within the "no auction, no performance pricing" boundary unless D-10 is revisited | D-10 |

---

## 3. Later / exploratory

**No approval. No commitment. Not plans.** Recorded so that mentions of them
elsewhere are not mistaken for intent.

| Idea | Why it is only an idea |
| --- | --- |
| Online ordering | A different product with different operations |
| Reservations and bookings | Requires per-business integration Bulbula cannot maintain at this stage |
| Loyalty, coupons, deals | Requires business participation and settlement |
| Jobs listings | Adjacent market, separate supply problem |
| Local news or editorial | Changes Bulbula from a directory into a publisher, with different legal exposure |
| Events | Perishable content; a different operational cadence |
| AI-assisted recommendations | Requires behavioural data Bulbula deliberately does not collect in V1 |
| AI-generated listing content | Conflicts directly with permission-based collection and provenance (D-50) |
| Consumer subscriptions | No demonstrated willingness to pay |
| API or data licensing | Raises permission-scope questions (D-50) and legal questions |
| Afaan Oromo or other interfaces | Beyond the approved language direction (D-18) |

---

## 4. Rules for changing this roadmap

| ID | Rule |
| --- | --- |
| RM-1 | Moving an item from Future to V1 is a **scope change** requiring an owner decision recorded in the register |
| RM-2 | Items may not be promoted from "Later / exploratory" to V1 at all; they must first become an approved direction |
| RM-3 | No date may be added to this document until there is a measured basis for it |
| RM-4 | V1 build work **MUST NOT** include partial implementations of Future items |
| RM-5 | Architectural seams that avoid foreclosing Future items are permitted and encouraged; features are not (PRD H-4) |

---

## Decision references

D-02, D-10, D-11, D-12, D-15, D-15r, D-18, D-19, D-30, D-31, D-36, D-37,
D-40, D-44, D-46, D-47, D-54.
